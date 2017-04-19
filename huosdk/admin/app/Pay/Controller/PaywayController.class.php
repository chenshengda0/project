<?php

/**
 * 充值统计页面
 * 
 * @author
 *
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class PaywayController extends AdminbaseController {
    private $topdir;
    
	function _initialize() {
		parent::_initialize();
		if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
            $this->topdir = "D:/workspace/league/";
        } else {
            $this->topdir = "/alidata/html/";
        }
	}

    public function index() {
		
		$liststatus = I('liststatus',4);

		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
		$name = trim(I('realname'));
		
		$waymodel = M("db_sdk_mn.payway","c_");
		$result = array();
		$where = " status = 0 ";
		
		$where_arr = array();
		if(!empty($name) && $name!=''){
			$where .= " AND realname like '%s'";
			$sqlname = "%{$name}%";
			array_push($where_arr,$sqlname);
		}
		
		
		$result["total"] = $total = $waymodel->where($where,$where_arr)->count();
		
		$page = $this->page($result["total"], $rows);
		
		$ways = $waymodel->where($where,$where_arr)->limit($page->firstRow . ',' . $page->listRows)->select();
		$cid = C('CLIENTID');
		$model = M();
		
		foreach($ways as $key=>$val){

			$sql = "select * from db_sdk_mn.c_".$val['payname']."history a WHERE 
				NOT EXISTS (SELECT 1 FROM db_sdk_mn.c_".$val['payname']."history b WHERE a.cid=b.cid AND a.create_time<b.create_time AND b.cid=%d) AND a.cid=%d";

			$info = $model->query($sql,$cid,$cid);
			if(count($info) > 0){
				$ways[$key]['paystatus'] = $info[0]['status'];
			}else{
				$ways[$key]['paystatus'] = 3;
			}
			if($liststatus != 4 && $ways[$key]['paystatus'] != $liststatus){
				unset($ways[$key]);
			}
			
		}
		
		$this->assign("liststatus",$liststatus);
		$this->assign("ways",$ways);
		$this->assign("Page", $page->show('Admin'));
		$this->assign("realname",$name);
        $this->display();
    }

	/**
	 * 渠道添加支付
	 */
	public function payAdd(){
		$id = I("id");
		
		$this->assign("typeid",$id);
		$this->display();
	}

	/**
	 * 渠道修改支付
	 */
	public function payEdit(){
		$typeid = I("id");
		$cid = C('CLIENTID');
		
		$where = " cid = %d";
		$array = array($cid);
		switch ($typeid) {
            case 2 : // 支付宝
				$table = 'alipay';
                $info = $this->_seachpay($table,$where,$array);
                break;
            case 6 : // 易宝支付
				$table = 'yeepay';
                $info = $this->_seachpay($table,$where,$array);
                break;
            case 7 : // 易联支付
				$table = 'payeco';
                $info = $this->_seachpay($table,$where,$array);
                break;
            default :
               $this->error('请求失败.');
               exit();
        }
		
		if ($info) {
				$this->assign($info);
				$this->assign("typeid",$typeid);
				$this->display();
		}else{
			 $this->error('请求失败.');
              exit();
		}
				
	}
	
	public function _seachpay($table,$where,$array) {
		$model = M("db_sdk_mn.".$table,"c_");
		$info = $model ->where($where,$array)-> find();
		return $info;
	}

	/**
	 * 渠道添加修改支付
	 */
	public function payadd_post() {
		$typeid = I("typeid");
		$cid = C('CLIENTID');
		
		switch ($typeid) {
            case 2 : // 支付宝
                $info = $this->_addAlipay($cid);
                break;
            case 6 : // 易宝支付
                $info = $this->_addYeepay($cid);
                break;
            case 7 : // 易联支付
                $info = $this->_addPayeco($cid);
                break;
            default :
               $this->error('请求失败.');
               exit();
        }
		
		if ($info) {
			$this->success("操作成功！",U("Payway/index"));
			exit();
		}
		$this->error('请求失败.');
        exit();
	}

	public function _addAlipay($cid) {
		$alipay['cid'] = $cid;
        $alipay['filepath'] = I('filepath');
        $alipay['email'] = I('email');
        $alipay['partner'] = I('partner');
        $alipay['alikey'] = I('alikey');
        $alipay['rsa_private_key'] = I('rsa_private_key');
        $alipay['rsa_public_key'] = I('rsa_public_key');
        $alipay['alipay_public_key'] = I('alipay_public_key');
        $alipay['disc'] = I('disc');
        
        if (empty($alipay['email']) ||
             empty($alipay['partner']) || empty($alipay['alikey']) || empty($alipay['alipay_public_key']) ||
             empty($alipay['rsa_private_key']) || empty($alipay['rsa_public_key'])) {
			
            return false;
        }
        if (empty($filepath)) {
            $alipay['filepath'] = "/sdk/alipay";
        }
        
        $table = 'alipay';
        
        $where = "cid = " . $cid;
        $history = $this->_checkHistory($table, $where);
        
        if (count($history) > 0) {
			if($history[0]['status'] == 0){
				$this->error('请求失败,已有申请在等待审核.');
                exit();
			}
            $alipay['create_time'] = time();
            $rs = $this->_addPayhistory($table, $alipay);
        } else {
			$alirs = $this->_genAlipay($cid,$alipay);
			if($alirs){
				$alipay['create_time'] = time();
				$history = $alipay;
				$history['status'] = '1';
				$rs = $this->_addPayhistory($table, $history);
				if($rs){
					$alipay['update_time'] = $alipay['create_time'];
					$rs = $this->_addPaydata($table, $alipay);
				}
			}else{
				$this->error('配置文件生成失败.');
                exit();
			}      
			
        }
        
        if ($rs > 0) {
            return true;
        } else {
            return false;
        }
        
    }
	public function _addYeepay($cid) {
        $yeepay['cid'] = $cid;
        $yeepay['filepath'] = I('filepath');
        $yeepay['merchantaccount'] = I('merchantaccount');
        $yeepay['merchantprivatekey'] = I('merchantprivatekey');
        $yeepay['merchantpublickey'] = I('merchantpublickey');
        $yeepay['yeepaypublickey'] = I('yeepaypublickey');
        $yeepay['disc'] = I('disc');
        
        if (empty($yeepay['merchantaccount']) || empty($yeepay['yeepaypublickey']) ||
             empty($yeepay['merchantprivatekey']) || empty($yeepay['merchantpublickey'])) {
            return false;
        }
        if (empty($filepath)) {
            $yeepay['filepath'] = "/sdk/yeepay";
        }
        
        $table = 'yeepay';
        
        $where = "cid = " . $cid;
        $history = $this->_checkHistory($table, $where);
      
        if (count($history) > 0) {
			if($history[0]['status'] == 0){
				$this->error('请求失败,已有申请在等待审核.');
                exit();
			}
            $yeepay['create_time'] = time();
            $rs = $this->_addPayhistory($table, $yeepay);
        } else {
			
			$alirs = $this->_genYeepay($cid,$yeepay);
			if($alirs){
				$yeepay['create_time'] = time();
				$history = $yeepay;
				$history['status'] = '1';
				$rs = $this->_addPayhistory($table, $history);
				if($rs){
					 $yeepay['update_time'] = $yeepay['create_time'];
					 $rs = $this->_addPaydata($table, $yeepay);
				}
			}else{
				$this->error('配置文件生成失败.');
                exit();
			}
           
        }
        
        if ($rs > 0) {
            return true;
        } else {
            return false;
        }
        
    }
	public function _addPayeco($cid) {
        $payeco['cid'] = $cid;
        $payeco['filepath'] = I('filepath');
        $payeco['merchant_id'] = I('merchant_id');
        $payeco['rsa_private_key'] = I('ecorsa_private_key');
        $payeco['rsa_public_key'] = I('ecorsa_public_key');
        $payeco['disc'] = I('disc');
		
        if (empty($payeco['merchant_id']) || empty($payeco['rsa_private_key']) || empty($payeco['rsa_public_key'])) {
            return false;
        }
        if (empty($filepath)) {
            $payeco['filepath'] = "/sdk/payeco";
        }
        
        $table = 'payeco';
        
        $where = "cid = " . $cid;
        $history = $this->_checkHistory($table, $where);
        
        if (count($history) > 0) {
			if($history[0]['status'] == 0){
				$this->error('请求失败,已有申请在等待审核.');
                exit();
			}
            $payeco['create_time'] = time();
            $rs = $this->_addPayhistory($table, $payeco);
        } else {
			$alirs = $this->_genPayeco($cid,$payeco);
			
			if($alirs){
				 $payeco['create_time'] = time();
				$history = $payeco;
				$history['status'] = '1';
				$rs = $this->_addPayhistory($table, $history);
				if($rs){
					$payeco['update_time'] = $payeco['create_time'];
					$rs = $this->_addPaydata($table, $payeco);
				}
			}else{
				$this->error('配置文件生成失败.');
                exit();
			}
           
        }
        
        if ($rs > 0) {
            return true;
        } else {
            return false;
        }
    }


	public function _checkHistory($table,$where = NULL){
		$model = M();
		
		$cid = C('CLIENTID');
		$sql = "select * from db_sdk_mn.c_".$table."history a WHERE 
				NOT EXISTS (SELECT 1 FROM db_sdk_mn.c_".$table."history b WHERE a.cid=b.cid AND a.create_time<b.create_time AND b.cid=%d) AND a.cid=%d";

		$info = $model->query($sql,$cid,$cid);
		//$cnt = $paymodel -> where($where) ->count('cid');
		return $info;
	}
	
	public function _addPaydata($table, $data){
		if(empty($data)){
			return -1;
		}
	
		$paymodel = M("db_sdk_mn.".$table,"c_");
		$rs = $paymodel -> add($data);
		return $rs;
	}

	public function _addPayhistory($table, $data){
		if(empty($data)){
			return -1;
		}
	
		$paymodel = M("db_sdk_mn.".$table."history","c_");
		$rs = $paymodel -> add($data);
		return $rs;
	}


	 public function _genAlipay($cid,$alipayinfo) {
        if (empty($cid)) {
            return FALSE;
        }
       
        if (empty($alipayinfo)) {
            return FALSE;
        }
		$identifier = C('CLIENTIDENTY');
        $confdir = $this->topdir . "client/" . $identifier . "/install/conf/alipay/";
        $alipaydir = $this->topdir . "client/" . $identifier . "/sdk/alipay/";
        
        // 支付宝partner
        $srcincfile = "alipay.config.php";
        $strConfig = file_get_contents($confdir . $srcincfile);
        $strConfig = str_replace('#partner#', $alipayinfo['partner'], $strConfig);
        @chmod($alipaydir . $srcincfile, 0777);
        $rs = file_put_contents($alipaydir . $srcincfile, $strConfig);
        
        // 支付宝key
        $srcincfile = "rsa_private_key.pem";
        $strConfig = file_get_contents($confdir . $srcincfile);
        $strConfig = str_replace('#rsa_private_key#', $alipayinfo['rsa_private_key'], $strConfig);
        @chmod($alipaydir . "key/" . $srcincfile, 0777);
        $rs = file_put_contents($alipaydir . "key/" . $srcincfile, $strConfig);
        
        if ($rs > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function _genPayeco($cid,$payinfo) {
        if (empty($cid)) {
            return FALSE;
        }
		
        if (empty($payinfo)) {
            return FALSE;
        }
        $identifier = C('CLIENTIDENTY');
		$weburl = C('WEBSITE');
        
        $projectdir = $this->topdir . "client/" . $identifier . "/";
        if (empty($payinfo['filepath'])) {
            $relative_path = "/sdk/payeco";
        } else {
            $relative_path = $payinfo['filepath'];
        }
        $src_conf_dir = $projectdir . "./install/conf/payeco/";
        $paydir = $projectdir . $relative_path . "/";
        $payweb = $weburl . $relative_path . "/";
        
        // 配置文件修改
        $configfile = "Constants.php";
        $strConfig = file_get_contents($src_conf_dir . $configfile);
        
        $strConfig = str_replace('#merchant_id#', $payinfo['merchant_id'], $strConfig);
        $strConfig = str_replace('#merchant_notify_url#', $payweb, $strConfig);
        @chmod($paydir . $configfile, 0777);
        $rs1 = file_put_contents($paydir . $configfile, $strConfig);
        
        // 易联 private_key
        $key_file = "rsa_private_key.pem";
        $strConfig = file_get_contents($src_conf_dir . $key_file);
        $strConfig = str_replace('#rsa_private_key#', $payinfo['rsa_private_key'], $strConfig);
        @chmod($paydir . "key/" . $key_file, 0777);
        $rs2 = file_put_contents($paydir . "key/" . $key_file, $strConfig);
        
        // 易联 rsa_public_key
        $key_file = "rsa_public_key.pem";
        $strConfig = file_get_contents($src_conf_dir . $key_file);
        $strConfig = str_replace('#rsa_public_key#', $payinfo['rsa_public_key'], $strConfig);
        @chmod($paydir . "key/" . $key_file, 0777);
        $rs3 = file_put_contents($paydir . "key/" . $key_file, $strConfig);
        
        if ($rs1 > 0 && $rs1 > 1 && $rs1 > 2) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function _genYeepay($cid,$payinfo) {
        if (empty($cid)) {
            return FALSE;
        }
        $payname = "yeepay";
        $identifier = C('CLIENTIDENTY');
		$weburl = C('WEBSITE');
        
        if (empty($payinfo)) {
            return FALSE;
        }
        $projectdir = $this->topdir . "client/" . $identifier . "/";
        if (empty($payinfo['filepath'])) {
            $relative_path = "/sdk/" . $payname;
        } else {
            $relative_path = $payinfo['filepath'];
        }
        
        $src_conf_dir = $projectdir . "./install/conf/" . $payname . "/";
        $paydir = $projectdir . $relative_path . "/";
        $payweb = $weburl . $relative_path . "/";
        
        // 配置文件修改
        $configfile = "config.php";
        $strConfig = file_get_contents($src_conf_dir . $configfile);
        
        $strConfig = str_replace('#merchantaccount#', $payinfo['merchantaccount'], $strConfig);
        $strConfig = str_replace('#merchantprivatekey#', $payinfo['merchantprivatekey'], $strConfig);
        $strConfig = str_replace('#merchantpublickey#', $payinfo['merchantpublickey'], $strConfig);
        $strConfig = str_replace('#yeepaypublickey#', $payinfo['yeepaypublickey'], $strConfig);
        $strConfig = str_replace('#yeepaywebdir#', $payweb, $strConfig);
        @chmod($paydir . $configfile, 0777);
        $rs = file_put_contents($paydir . $configfile, $strConfig);
        
        if ($rs > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
   
    
}
<?php
/**
* 注册统计页面
*
* @author
*/
namespace Data\Controller;
use Common\Controller\AdminbaseController;

class RegisterController extends AdminbaseController {
	
    /**
	 * 显示页面
	 * 
	 * return void
	 */
    public function index(){
    	
    	
    	$this->display();
    }
	/**
	 * 新注册人数统计
	 * 
	 * return void
	 */
	public function regList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$start = I('start');
		$end = I('end');
		$username = I('username');
		$imeil = I('imeil');
		$agent = I('agent');
		$gameid = I('gameid');
		$owner = I('owner');
		$flag = I('flag');
			
		$offset = ($page-1)*$rows;
		
		$result = array();
		$model = M('members');
		
		$where = " 1 and fromflag!=3 ";
		$where_arr = array();

		if (isset($flag) && $flag>0){
    		$where .= " AND flag=%d";
			array_push($where_arr,$flag);
    	}

		if (isset($start) && $start>0){
    		//$tmp = $start." 00:00:00";
    		$start = strtotime($start);
    		$where .= " AND reg_time >='%s'";
			array_push($where_arr,$start);
    	}
		
		//结束时间
    	if (isset($end) && $end>0) {
    		//$tmp = $end . " 23:59:59";
    		$end = strtotime($end);
    		$where .= " AND reg_time <='%s'";
			array_push($where_arr,$end);
    	}
    	
    	//用户名
    	if (isset($username) && $username !='') {
    		$where .= " AND username ='%s'";
			array_push($where_arr,$username);
    	}
    	
    	//imeil码
    	if (isset($imeil) && $imeil !='') {
    		$where .= " AND imei ='%s'";
			array_push($where_arr,$imeil);
    	}
    	
    	//游戏ID
    	if (isset($gameid) && $gameid >0) {
    		$where .= " AND gameid =%d";
			array_push($where_arr,$gameid);
    	}
    	
    	//渠道号
    	if (isset($agent) && $agent !='') {
    		$where .= " AND agentgame ='%s'";
			array_push($where_arr,$agent);
    	}
		
    	//渠道专员
		if (isset($owner) && $owner !='') {
    		$agentstr= $this->getAgentByusername($owner);
			
			$place_holders = implode(',', array_fill(0, count($agentstr), "'%s'"));
    		$where .= " AND agentgame in (".$place_holders.")";
			$where_arr = array_merge($where_arr,$agentstr);
    	}
    	
		$result["total"] = $model->where($where,$where_arr)->count();
			
		$field = "id,username,imei,agentgame,gameid,reg_time,deviceinfo";
		$items = $model->field($field)->where($where,$where_arr)->order('id DESC')->limit($offset . ',' .  $rows)->select();
		
		//获取游戏列表
    	$gamemodel = M('game');
		$gamelist = $gamemodel->select();
		foreach ($gamelist as $key=>$val) {
    		$game[$val['appid']] = $val['name'];
    	}
		
		$login = M('login_log');
		$pay = M('pay');
		$ttb = M('ttb');
		$agentlist = M('agentlist');
		$two = time() - 3600*24*2; 		//最近二天时间
		foreach ($items as $key=>$val) {
			$items[$key]['reg_time'] = date('Y-m-d H:i:s',$val['reg_time']);
			$items[$key]['game'] = $game[$val['gameid']];
			$items[$key]['imei'] = !empty($val['imei']) ? $val['imei'] : "--";
			
			$logintime = $login->where("mem_id =%d",$val['id'])->order("id DESC")->limit(1)->field('login_time')->select();
			$logincount = $login->where("mem_id=%d and login_time>'%s'",array($val['id'],$two))->count("id");
			$items[$key]['login_time'] =  isset($logintime[0]['login_time']) ? date('Y-m-d H:i:s',$logintime[0]['login_time']) : "--";	//最近登录时间
			$items[$key]['logincount'] =  isset($logincount) ? $logincount : "--";	//最近登录次数
			
			$sql = "SELECT SUM(amount) AS money,COUNT(id) AS paycount,agent FROM c_pay WHERE username='%s' and status=1" ;
			$paylist = $pay->query($sql,array($val['username']));
			
			$items[$key]['money'] = isset($paylist[0]['money']) ? $paylist[0]['money'] : "--";				//充钱金额
			$items[$key]['paycount'] = isset($paylist[0]['paycount']) ? $paylist[0]['paycount'] : "--";		//充钱次数
			
			$ttblist = $ttb->field("ttb")->where("username='".$val['username']."'")->select();
			$items[$key]['ttb'] = isset($ttblist[0]['ttb']) ? $ttblist[0]['ttb'] : "--";		//平台币
			
			$join = "c_users on c_agentlist.agentid=c_users.id";
			$agentdata = $agentlist->field('c_users.user_login,c_users.user_nicename')->join($join)->where("c_agentlist.agentgame='%s'",$val['agentgame'])->select();
			$items[$key]['owner'] = isset($agentdata[0]['user_login']) ? $agentdata[0]['user_login'] : "--";			//渠道专员
			$items[$key]['agentname'] = isset($agentdata[0]['user_nicename']) ? $agentdata[0]['user_nicename'] : "--";	//渠道名称 
			
		}

		$result["rows"] = $items;

		if($result["total"] == 0){
			$result["rows"] = array();
		}

		//下载
    	if ($_REQUEST['downloads'] == 1) {
            $list = $model->where($where,$where_arr)->order("id DESC")->field('id,username,imei,agentgame,reg_time')->select();
			$title = "新注册用户列表";
            $this->downloadexls($list,$title,'register');
        }
		echo json_encode($result);
	}

	//获取游戏列表
	public function gameList(){
		$gamelist1[0]['appid'] = 0;
		$gamelist1[0]['name'] = '请选择游戏名称'; 

		$gamemodel = M('game');
		$gamelist2 = $gamemodel->field("appid,name")->select();
		
		$gamelist = array_merge($gamelist1,$gamelist2);
		echo json_encode($gamelist);
	}
    
    /**
     * 
     * 登录统计
     */
    public function login(){
    	
    	$this->display();
    }
	
	 /**
     * 
     * 登录列表
     */
	public function loginList(){
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$start = I('start');
		$end = I('end');
		$username = I('username');
		$imeil = I('imeil');
		$agent = I('agent');
		$gameid = I('gameid');
		$owner = I('owner');
			
		$offset = ($page-1) * $rows;
		
		$result = array();
		$model = M('login_log');

		$where = " 1 ";
		$where_arr = array();
		if (isset($start) && $start>0) {
    		//$tmp = $start . " 00:00:00";
    		$start = strtotime($start);
    		$where .= " AND login_time >='%s'";
			array_push($where_arr,$start);
    	}
    	
    	if (isset($end) && $end>0) {
    		//$tmp = $_POST['end'] . " 23:59:59";
    		$end = strtotime($end);
    		$where .= " AND login_time <='%s'";
			array_push($where_arr,$end);
    	}
    	
    	$members = M('members');
    	//用户名
    	if (isset($username) && $username !='') {
    		$rs = $members->where("username='%s'",$username)->field("id")->select();
    		$where .= " AND mem_id = %d";
			array_push($where_arr,$rs[0]['id']);
		}
    	
    	//imeil码
    	if (isset($imeil) && $imeil !='') {
    		$where .= " AND imei ='%s'";
			array_push($where_arr,$imeil);
    	}
    	
		//游戏ID
    	if (isset($gameid) && $gameid >0) {
    		$where .= " AND gameid = %d";
			array_push($where_arr,$gameid);
    	}

    	//渠道号
    	if (isset($agent) && $agent !='') {
    		$where .= " AND agentgame ='%s'";
			array_push($where_arr,$agent);
    	}
    	
		//渠道专员
		if (isset($owner) && $owner !='') {
    		$agentstr= $this->getAgentByusername($owner);
    		$useridarr = $this->getUsernameByagents($agentstr);
    		
    		$place_holders = implode(',', array_fill(0, count($useridarr), "'%s'"));
    		$where .= " AND mem_id in (".$place_holders.")";
			$where_arr = array_merge($where_arr,$useridarr);
    	}
		
		//获取游戏列表
    	$gamemodel = M('game');
		$gamelist = $gamemodel->select();
		foreach ($gamelist as $key=>$val) {
    		$game[$val['appid']] = $val['name'];
    	}
    	
		$result["total"] = $model->where($where,$where_arr)->count();
			
		$items = $model->where($where,$where_arr)->order('id DESC')->limit($offset . ',' .  $rows)->select();
		$agentmodel = M('agent_game');
		foreach ($items as $key=>$val) {
			$items[$key]['login_time'] = date('Y-m-d H:i:s',$val['login_time']);
			$items[$key]['imei'] = !empty($val['imei']) ? $val['imei'] : "--";
			$rs = $members->where("id=".$val['mem_id'])->field("username")->select();
			$items[$key]['username'] = $rs[0]['username'];
			$items[$key]['game'] = $game[$val['gameid']];
			
			$join = "c_users on c_agentlist.agentid=c_users.id";
			$agentdata = $agentmodel->field('c_users.user_login')->join($join)->where("c_agentlist.agentgame='%s'",$val['agentgame'])->select();
			$items[$key]['owner'] = $agentdata[0]['user_login'];
		}

		$result["rows"] = $items;

		if($result["total"] == 0){
			$result["rows"] = array();
		}

		//下载
    	if ($_REQUEST['downloads'] == 1) {
            $list = $model->where($where,$where_arr)->order("id DESC")->field('id,mem_id,agentgame,login_time')->select();
			$title = "登录用户统计";
            $this->downloadexls($list,$title,'login');
        }
		
		echo json_encode($result);
	}
    
    /**
     * 
     * 修改密码
     */
    public function updatePsw() {
    	$id = I('id');
    	$action = I('action');
    	if (isset($action) && $action == 'updatepsw') {
	    	
	    	$password = I('password');
	    	$chkpassword = I('chkpassword');
	    	
	    	if (empty($password) || ($password != $chkpassword)) {
	    		echo json_encode(array('msg'=>'密码不能为空或者二次填写的密码不相等.'));
				exit();
	    	}

	    	$model = M('members');
	    	$data['password'] =  $this->auth_code($_POST['password'],'ENCODE');
	    	
	    	if ($model->create($data)) {
				$rs = $model->where("id = %d",$id)->save();
				if($rs){
					echo json_encode(array('success'=>true,'msg'=>'更改密码成功.'));
					exit();
				}
	    	}
			echo json_encode(array('msg'=>'修改失败.'));
			exit();
    	}
    }
    
    /**
     * 
     * 冻结账号
     */
    public function frozen() {
    	$id = I('id');
    	$flag = I('get.flag',0);
		
		$action = I('action','');
    	
    	if (isset($action) && $action == 'frozenpsw') {
    		if ($id) {
    			$model = M('members');
    			$data['flag'] = $flag;
	    		
		    	if ($model->create($data)) {
					
					$rs = $model->where("id=%d",$id)->save();
					if($rs){
						$msg = ($flag == 0) ? "解冻成功." : "冻结成功.";
						echo json_encode(array('success'=>true,'msg'=>$msg));
						exit();
					}
		    	}
		    	$msg = ($flag == 0) ? "解冻失败，请重试." : "冻结失败，请重试.";
		    	echo json_encode(array('msg'=>$msg));
				exit();
    		} 
    	}
    }
    
    /**
     * 
     * 冻结列表
     */
    public function frozenindex() {
    	$this->display();
    }
    
	/**
     * 
     * 密码加密解密
     * @param $string     密码
     * @param $operation  DECODE 为解密，其他为加密
     * @param $key		     密钥
     * @param $expiry
     */
	function auth_code($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		$ckey_length = 0;
	
		$key = md5($key ? $key : '9e13yK8RN2M0lKP8CLRLhGs468d1WMaSlbDeCcI_etsdk@you@baixunbaosdk@2015');
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
	
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
	
		$result = '';
		//$box = range(0, 255);
		$box = 100;
	
		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
	
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
	
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
	
		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}



	/**
     * 
     * 通过渠道专员姓名查找所绑定的渠道号
     * @param $username
     */
    public function getAgentByusername ($username) {
    	$admin = M('users');
		$admindata = $admin->field("user_login")->where("user_login like '%".$username."%'")->select();
		$ownerstr = '';
		foreach ($admindata as $key=>$val) {
			$ownerstr .= "'".$val['user_login']."',";
		}
		$ownerstr = substr($ownerstr, 0,-1);
		$agentlist = M('agent_game');
		$agentstr = array();
		if($ownerstr != ''){
			$agentrs = $agentlist->field("agentgame")->where("owner in (".$ownerstr.")")->select();
			//$agentstr = '';
			foreach ($agentrs as $key=>$val) {
				//$agentstr .= "'".$val['agent']."',";
				$agentstr[$key] = $val['agentgame'];
			}
		}
		
    	return $agentstr;
    }

	/**
     * 
     * 通过渠道包获取这些渠道包的注册用户
     * @param $agents
     */
    public function getUsernameByagents($agents) {
		$agentstr ="'".implode("','",$agents)."'";
    	$members = M('members');
	 	$usernamestr = '';
    	$memlist = $members->field('id')->where("agentgame in (".$agentstr.")")->select();
		
		$usernlist = array();
		if(count($memlist) > 0){
			foreach ($memlist as $key=>$val) {
				$usernlist[$key] = $val['id'];
			} 
		}
    	
    	return $usernlist;
    }

}
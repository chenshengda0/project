<?php

/**
 * 支付中心控制器
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class PayController extends HomebaseController {
    //支付公共部分
    public function paycommon(){
        $username = session("user.sdkuser");
        $this->assign('username',$username);
    
        //订单号
        $orderid = time().rand(1000,9999);
        $this->assign('orderid',$orderid);
    
        $payarr = array(
            '3' => "支付宝支付",
            '5' => "易联支付",
            '16' => "神州付",
            '19' => "星启天微信支付"
        );
        $moneyrate = array(
            //支付宝
            '3'		=> array(
                'rate' => 1,
                'money' => 100,
                'ttb' => 100 + 100 * $ttbrate
            ),
            //支付宝
            '5'		=> array(
                'rate' => 1,
                'money' => 100,
                'ttb' => 100 + 100 * $ttbrate
            ),
            //支付宝
            '16'		=> array(
                'rate' => 1,
                'money' => 100,
                'ttb' => 100 + 100 * $ttbrate
            ),
            //支付宝
            '19'		=> array(
                'rate' => 1,
                'money' => 100,
                'ttb' => 100 + 100 * $ttbrate
            ),
        );
    
        $this->assign ( "keywords", "手机游戏,手机游戏推广,手游公会联盟,手机游戏下载,手游礼包" );
        $this->assign ( "description", C ( 'BRAND_NAME' ) . "提供最新最好玩的手机游戏下载，首家全民手游充值返利平台及手机游戏公会推广联盟，最新最热的手机游戏下载排行榜评测，为公会提供专属返利APP及手游代理平台服务。" . C ( 'BRAND_NAME' ) . "关注游戏玩家利益，助您畅玩手游。" );
        $this->assign ( "title", C ( 'BRAND_NAME' ) . "|最新最好玩的手机游戏下载排行榜_手游公会联盟_手游CPS公会推广渠道及联运发行合作平台" );
    
        // 获取充值页面下面的显示内容
        $this->payInfo ();
        	
        // 热门游戏列表清单
        $hotgamelist = hotgamelist ();
        $this->assign ( "footgamelist", $hotgamelist );
    
        $this->assign('moneyrate',$moneyrate);
        $this->assign('payarr',$payarr);
        $logo = getGuanggao(4);
        $this->assign("WEB_ICP", C('WEB_ICP'));
        $this->assign("logo",$logo);
        $this->assign("website",WEBSITE);
    }
    
    //支付宝
    public function index(){
        $url = WEBSITE.U('Web/Pay/alipay_post');
        $this->assign('url',$url);
        $paytypeid = 3;
        $this->assign("paytypeid",$paytypeid);
        $this->paycommon();
		$this->display();
    }

    public function payInfo(){
    	$pc_model = M('ptbContent');
    	$rs = $pc_model->find(1);
    	$content = $rs['content'];
    	$this->assign('_content',$content);
    }

    public function payBi(){
        $this->display('payBi');
    }

    public function userTtb(){

    	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

		if ($action == 'name') {
			//检测用户名
			$username = I('username','');
			if (!empty($username)) {
				$map['username'] = $username;
				$data = M('members')->where($map)->getField('id');

				if ($data) {
					echo "1";
				} else {
					echo "2";
				}
			} else {
				echo "2";
			}
		} else if ($action == 'ttb') {
			//通过ajax获取返利比例
			//赠送平台币的数量
			$give = 0;
			$money = I('money','');
			$return = getTTBtime ();
			if ($return == 0) {
				echo $give = 0;
				exit;
			}
			
			//查询充值金额在活动中设定的金额范围。
			$data1 = M('ptbRate')->field('money,rate,given_money')->where('money<='.$money)->order('money desc')->limit(1)->find();
			$data2 = M('ptbRate')->field('money,rate,given_money')->where('money>='.$money)->order('money asc')->limit(1)->find();
			
			if (empty($data1)) {
				echo $give = 0;
			} else if (empty($data2)) {
				echo $give = 1*$money;
			} else if ($money == $data1['money']) {
				echo $give = $data1['rate']*$money+$data1['given_money'];
			} else if ($money == $data2['money']) {
				echo $give = $data2['rate']*$money+$data2['given_money'];
			} else if ($money>$data1['money'] && $money<$data2['money']) {
				echo $give = $data1['rate']*$money+$data1['given_money'];
			}
			exit;
		} else if ($action == 'activetime') {
			$return = getTTBtime();
			echo $return;
			exit;
		}
    }
	
	//查询平台币返利活动是否是在有效时间内
	public function getTTBtime() {
		$time = time();
		$checktime = M('ptbRate')->getfield('start_time,end_time')->where(array('id'=>1))->find();
		$starttime = $checktime['start_time'];
		$endtime = $checktime['end_time'];
		
		//若不在活动时间之内，则没有返利
		if ($time<$starttime || $time>$endtime) {
			$return = 0;
		} else {
			$return = 1;
		}
		return $return;
	}
	
	//生成订单号
	function  setorderid($mem_id) {
	    list($usec, $sec) = explode(" ", microtime());
	
	    // 取微秒前3位+再两位随机数+渠道ID后四位
	    $orderid = $sec . substr($usec, 2, 3) . rand(10, 99) . sprintf("%04d", $mem_id % 10000);
	    return $orderid;
	}
	
	//返回信息
	function returninfo($msg){
		echo "<script type='text/javascript' >";
		echo "alert('".$msg."');";
		echo "window.close();";
		echo "</script>";
		exit;
	}
	
	//支付返回的接口
	public function alipay_post(){
		import("Vendor.lib.alipay_submit");
		$amount = I('amount/d');
		$username = I('username');
		$ptb_cnt = I('ttb/d');
		$paytypeid = I('paytypeid/d');

		//验证参数有效性
		if (empty($amount) || empty($username) || empty($ptb_cnt) || empty($paytypeid)) {
			$str = "缺少参数，请重新提交";
			return $this->returninfo($str);
		}

		//检查用户名是否存在
		$mem_id = M('members')->where(array('username'=>$username))->getfield('id');
		if(empty($mem_id)){
			$str = "用户不存在";
			return $this->returninfo($str);
		}
		
		if(!empty($_SESSION['paytime']) && $_SESSION['paytime'] + 5 > time()){
			$str = "订单己存在，请确认是您的付款单号再付款!";
			return $this->returninfo($str);
		}
		
		//订单流水号
		$order_id = $this->setorderid($mem_id);
		$_SESSION['weborderid'] = $order_id;
		$_SESSION['paytime'] = time();
		
		//比对比例是否正确
		if ($amount * 10 < $ptb_cnt){
			$str = "参数错误，请重新提交";
			return $this->returninfo($str);
		}
		
		//查询是否为同一订单，插入到平台币充值订单中
		$orderdata = M('ptbCharge')->where(array('order_id'=>$order_id))->getField('id');
		
		//判断订单是否存在
		if ($orderdata) {
			$str = "订单己存在，请确认是您的付款单号再付款!";
			echo "<script type='text/javascript' >";
			echo "alert('".$str."');";
			echo "window.close();";
			echo "</script>";
			exit;
		}
	
		//要插入数据库的参数
		$data['order_id'] = $order_id;
		$data['mem_id'] = $mem_id;
		$data['money'] = $amount;
		$data['ptb_cnt'] = $ptb_cnt;
		$data['status'] = 1; //状态 1 待支付 2 支付完成 3 支付失败
		$data['create_time'] = time();
		$data['update_time'] = time();
		$data['pay_type_id'] = $paytypeid;
		$data['flag'] = 3; //3代表官网充值
		$data['ip'] = $this->GetIP(0); //
		$data['payway'] = $paytypeid; //3代表支付类型
		$data['remark'] = '官网充值'; //备注
		$data['discount'] = 10; //折扣
		
		//插入订单记录
		$rs = M('ptbCharge')->data($data)->add();
		
		if (!$rs) {
			$str = "数据处理出错，请重新提交!";
			echo "<script type='text/javascript' charset='UTF-8'>";
			echo "alert('".$str."');";
			echo "window.close();";
			echo "</script>";
			exit;
		}
		
		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service" => 'create_direct_pay_by_user',
			"partner" => trim(C('alipay_config_partner')),
			"payment_type" => C('payment_type'),
 			//"notify_url" => WEBSITE.U('Web/Pay/notify_url'),
			//"return_url" => WEBSITE.U('Web/Pay/return_url'),
			"notify_url" => C('notify_url'),
			"return_url" => C('return_url'), 
			"seller_email"  => C('seller_email'),
			"out_trade_no" => $order_id,
			"subject" => C('subject'),
			"total_fee" => $amount,
			"body" => C('body'),
			"show_url" => C('show_url'),
			"anti_phishing_key" => C('anti_phishing_key'),
			"exter_invoke_ip" => C('exter_invoke_ip'),
			"_input_charset" => trim(strtolower(C('alipay_config_input_charset')))
		); 

		//构造要请求的参数$alipay_config
		$alipay_config = $this->get_config_data();
		
		//建立请求
		$alipaySubmit = new \AlipaySubmit($alipay_config);

		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");

		echo $html_text;
	}

	// 取得当前IP
    private function GetIP($type=0){
        //获取客户端IP
        if(!empty($_SERVER["HTTP_CLIENT_IP"])) {
             $cip = $_SERVER["HTTP_CLIENT_IP"];
        } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if(!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = "";
        }
        preg_match("/[0-9\.]{7,15}/", $cip, $cips);
        $cip = $cips[0] ? $cips[0] : 'unknown';
        unset($cips);
        if ($type==1) $cip = myip2long($cip);
        return $cip;
    }
	
	//回调方法
	public function notify_url(){
		import("Vendor.lib.alipay_notify");
		
		//构造要请求的参数$alipay_config
		$alipay_config = $this->get_config_data();
		
		//计算得出通知验证结果
		$alipayNotify = new \AlipayNotify($alipay_config);		
		$verify_result = $alipayNotify->verifyReturn();
		
		$html = "<!DOCTYPE HTML>";
		$html .= "<html>";
		$html .= "<head>";
		$html .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		$html .= "<link href='public/pay/css/toper.css' rel='stylesheet' type='text/css'>";
		$html .= "<style type='text/css'>";
		$html .= "	.cz_ba p{ line-height:22px;}";
		$html .= "	.cz_b{width:600px; margin:0 auto; padding-top:50px;}";
		$html .= "	.cz_ba{ background:url(../images/cg.jpg) no-repeat; padding-left:80px;}";
		$html .= "	.mna{padding-top:10px;}";
		$html .= "	.mna a{ color:#006699; padding:0 6px;}";
		$html .= "	.cz_ann{ height:30px; padding:0 10px;}";
		$html .= "</style>";
		
		if($verify_result){
			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];

			//支付宝交易号
			$trade_no = $_GET['trade_no'];

			//交易状态
			$trade_status = $_GET['trade_status'];

			//充值金额
			$amount = $_GET['total_fee'];
		
		
			if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {		
			
				$html .= "<div class='cz_b'>";
				$html .= "<div class='cz_ba'>";
				$html .= "<p style='font-size:16px; font-weight:bold;'>恭喜您，充值成功！</p>";
				$html .= "<p style='border-bottom:1px solid #e0e0e0; padding-bottom:10px; line-height:20px;'>如果查询未到账可能是运营商网络问题而导致暂时充值不成功，请联系客服。</p>";
				$html .= "<p class='mna'>订单号：".$trade_no."</p>";
				$html .= "<p>充值金额：".$amount."</p>";
				$html .= "<p style='margin-top:20px;'><a href='http://pc.haiwangame.com/index.php/Web/Pay/index.html'><input type='button' value='返回充值中心' class='cz_ann'/></a></p>";
				$html .= "</div>";
				$html .= "</div>";
			}
		}else{
			$html .= "<div class='cz_b'>";
			$html .= "<div class='cz_ba'>";
			$html .= "<p style='font-size:16px; font-weight:bold;'>充值失败，请重试！</p>";
			$html .= "<p style='margin-top:20px;'><a href='http://pc.haiwangame.com/index.php/Web/Pay/index.html'><input type='button' value='返回充值中心' class='cz_ann'/></a></p>";
			$html .= "</div>";
			$html .= "</div>";
		}
		$html .= "<title>支付宝即时到账交易接口</title>";
		$html .= "</head>";
		$html .= "<body>";
		$html .= "</body>";
		$html .= "</html>";
		echo $html;
	}	
	
	public function return_url(){
		import("Vendor.lib.alipay_notify");
		
		//构造要请求的参数$alipay_config
		$alipay_config = $this->get_config_data();
		
		//计算得出通知验证结果
		$alipayNotify = new \AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		if($verify_result){
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];

			//支付宝交易号
			$trade_no = $_POST['trade_no'];

			//交易状态
			$trade_status = $_POST['trade_status'];

			//充值金额
			$amount = $_POST['total_fee'];
		
		
		if($_POST['trade_status'] == 'TRADE_FINISHED') {
			
		}else if($_POST['trade_status'] == 'TRADE_SUCCESS'){
			$time = time();
			$data = M('ptbCharge')->field('order_id,mem_id,amount,ptb_cnt,status,create_time')->where(array('orderid'=>$out_trade_no))->select();
			$id = M('members')->where(array('order_id'=>$out_trade_no))->getField('id');
			if(!empty($data) && $data['status'] == 1){
				//验证金额
				if($data['amount'] == $amount){

					$rs = M('ptbCharge')->where(array('id'=>$id))->save(array('status'=>2));
					if($rs){
						$check = checkPtb($data['mem_id'],$data['ptb_cnt'],$amount);
						if ($check) {
							echo '0000'; //如果交易完成 则返回'0000'通知系统
							exit;
						}
					}
				}
			}else{
				$rs = M('ptbCharge')->where(array('id'=>$id))->save(array('status'=>3));
				if($rs){
					exit('支付失败，请重试');
				}
			}

		}
		
		//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			echo "success";		//请不要修改或删除	
		}else{
			//验证失败
			echo "fail";
		}
	}
	
	//检查是否已经存在过平台币并更新
	public function checkPtb($mem_id,$ptb_cnt,$amount) { 
		//获取玩家平台币余额表中的ID
		$data = M('ptbMem')->where(array('mem_id'=>$mem_id))->find();
		$where['remain'] = $data['remain']+$ptb_cnt;
		$where['update_time'] = time();
		$where['total'] = $data['total']+$ptb_cnt;
		$where['sum_money'] = $data['sum_money']+$amount;
		$map['mem_id'] = $mem_id;
		
		//判断玩家平台币余额表中是否存在数据，没有则添加，有则修改！
		if(!empty($data)){
			$result = M('ptbMem')->where($map)->save($where);
		}else{
			$where['create_time']=time();
			$where['mem_id'] = $mem_id;
			$where['total'] = $ptb_cnt;
			$where['remain'] = $ptb_cnt;
			$where['sum_money'] = $amount;
			$result = M('ptbMem')->data($where)->add();
		}
		
		//判断充值结果
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	//导入文件并构造要请求的参数$alipay_config
	private function get_config_data(){
		//构造要请求的参数$alipay_config
		$alipay_config = array(
			'partner' => C('alipay_config_partner'),
			'key' => C('alipay_config_key'),
			'sign_type' => C('alipay_config_sign_type'),
			'input_charset' => C('alipay_config_input_charset') ,
			'cacert' => C('alipay_config_cacert'),
			'transport'  => C('alipay_config_transport')
		);
		return $alipay_config;
	}
    
    /**
     *支付记录保存
     */
    function _insertpay(){
        $amount = I('post.amount',0);		//交易金额
        $ttb = I('post.ttb',0);
        $orderid = I("post.orderid");
        $username = I("post.username");
        $paytypeid = I("post.paytypeid");
        $productname = urldecode($productname);
        if (empty($orderid) || empty($amount) || empty($username) || empty($ttb) || empty($paytypeid)) {
            $str = "缺少参数，请重新提交";
            echo "<script type='text/javascript' >";
            echo "alert('".$str."');";
            echo "window.close();";
            echo "</script>";
            exit;
        }
    
        if ($amount * 10 < $ttb){
            $str = "参数错误，请重新提交";
            echo "<script type='text/javascript' >";
            echo "alert('".$str."');";
            echo "window.close();";
            echo "</script>";
            exit;
        }
    
        $model = M("ptbCharge");
        $checkorder = $model->where(array("order_id"=>$orderid))->find();
        if (!empty($checkorder)) {
            $str = "订单己存在，请确认是您的付款单号再付款!";
            echo "<script type='text/javascript' >";
            echo "alert('".$str."');";
            echo "window.close();";
            echo "</script>";
            exit;
        }
    
        $BuyerIp = get_client_ip();										//用户支付时使用的网络终端IP
        $transtime	= time();													//交易时间
        $mem_id = M("members")->where(array("username"=>$username))->getfield("id");
    
        $data['order_id'] = $orderid;
        $data['mem_id'] = $mem_id;
        $data['money'] = $amount;
        $data['ptb_cnt'] = $ttb;
        $data['status'] = 1;
        $data['create_time'] = $transtime;
        $data['payway'] = $paytypeid;
        $data['flag'] = 3;
        $data['remark'] = "官网充值";
        $data['ip'] = $BuyerIp;
        $data["payway"] = $paytypeid;
    
        	
        if($model->create($data)){
            $rs = $model->add();
        }
        if (!$rs) {
            $this->error("数据处理出错，请重新提交!");
            exit;
        }
        return $data;
    }
    
    function paypost($out_trade_no,$total_fee){
        $time = time();
        $data = M("ptbCharge")->where(array("order_id"=>$out_trade_no))->find();
        $myamount=number_format($data['money']);
        $transAmount=number_format($total_fee);
        if($myamount==$transAmount){
            if($data['status'] == 1){
                $status['status']=2;
                $rs = M("ptbCharge")->where(array("order_id"=>$out_trade_no))->save($status);
                if ($rs) {
                    $check = $this->checkPtb($data['mem_id'],$data['ptb_cnt'],$myamount);
                    if ($check) {
                        echo "OK";
                        exit;
                    }
                }
            }
        }
    }
    
    //神州付
    public function shenzhoufu(){
        $url = WEBSITE."/web/shenzhoufu/shenzhoufu.php";
        $this->assign('url',$url);
        $paytypeid = 16;
        $this->assign("paytypeid",$paytypeid);
        $this->paycommon();
        $this->display("shenzhoufu");
    }
    
    //星启天微信
    public function xqtpay(){
        $url = WEBSITE.U('Web/Xqtpay/xqtpay');
        $this->assign('url',$url);
        $paytypeid = 19;
        $this->assign("paytypeid",$paytypeid);
        $this->paycommon();
        $this->display("index");
    }
}
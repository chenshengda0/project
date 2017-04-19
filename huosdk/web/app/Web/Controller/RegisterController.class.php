<?php

/**
 * 用户注册控制器
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class RegisterController extends HomebaseController {
    
    public function _initialize() {
		session_start();
        parent::_initialize();
    }

    //显示注册界面
    public function index(){
    	$this->display();
    }

    //注册
    public function register(){
        $action = I('action');
        
        if ($action && $action == 'register') {
            //接收参数
            $username = I('username','');    //用户名
            $password = I('password','');    //密码
            $email    = I('email','');       //邮箱
            $nickname = I('nickname','');    //昵称
            
            //判断参数的有效性
            if(empty($username) || empty($password) || empty($email) || empty($nickname)){
                echo "<script>alert('请填写完整资料后再提交');history.go(-1);</script>";
                exit();
            }
        
            //对字符参数做去反斜线处理
            if (!get_magic_quotes_gpc()) {
                $username = addslashes($username);
                $password = addslashes($password);
                $email = addslashes($email);
                $nickname = addslashes($nickname);
            }
            
            //判断终端是手机(iphone,ipad,android)还是电脑访问网站
            $tmp = strtolower($_SERVER['HTTP_USER_AGENT']);
            $is_pc = (strpos($tmp, 'windows nt')) ? true : false;
            $is_iphone = (strpos($tmp, 'iphone')) ? true : false;
            $is_ipad = (strpos($tmp, 'ipad')) ? true : false;
            $is_android = (strpos($tmp, 'android')) ? true : false;
        
            $device = 1;
            if($is_pc){
                $device = 1; //PC(电脑)
            }
        
            if($is_android){
                $device = 2; //Android
            }
        
            if($is_iphone){
                $device = 3; //iPhone
            }
        
            if($is_ipad){
                $device = 3; //iPad
            }
            
            $userid = checkusername($username);
            
            if(0 != $userid){
                echo "<script>alert('注册用户已存在，请重新注册');history.go(-1);</script>";
                exit();
            }
            
            $agentgame = "default";
            $skip = I('skip');
            $reg_time = time();
            $ip = $this -> GetIP(0);
			$pay_pwd = $password;
            
            $id = register($username,$password,$email,$reg_time,$ip,$device,$agentgame,$nickname);
            
            if (C('UCENTER_ENABLED') && $id <= 0 ){
                if($uid == -1) {
                    echo "<script>alert('用户名不合法');history.go(-1);</script>";
                } elseif($id == -2) {
                    echo "<script>alert('包含要允许注册的词语');history.go(-1);</script>";
                } elseif($id == -3) {
                    echo "<script>alert('用户名已经存在');history.go(-1);</script>";
                } elseif($id == -4) {
                    echo "<script>alert('Email 格式有误');history.go(-1);</script>";
                } elseif($id == -5) {
                    echo "<script>alert('Email 不允许注册';);history.go(-1);</script>";
                } elseif($id == -6) {
                    echo "<script>alert('该 Email 已经被注册');history.go(-1);</script>";
                } else {
                    echo "<script>alert('未定义错误'".$uid.");history.go(-1);</script>";
                }
                exit();
            }
        
            if ($id > 0) {
                $userid = checkusername($username);
                session('user.sdkuser',$username);
                session('user.xsst_id',$userid);
        
                if (C('UCENTER_ENABLED')){
                    list($uid, $username, $password, $email) = uc_user_login($username, $password);
                    if($uid > 0){
                        echo uc_user_synlogin($uid);
                    }
                }
                $rs = insertLogininfo($userid,$agent,time(),$device,"");
                
                $url = WEBSITE;
                if($skip == 'luntan'){
                    $url = BBSSITE;
                    echo "<meta http-equiv=refresh content=0;URL=".$url.">";
                    exit();
                }else if($skip == 'school'){
                    $url = "http://xy.".C('SECOND_DOMAIN');
                    echo "<meta http-equiv=refresh content=0;URL=".$url.">";
                    exit();
                }
                
                echo "<script>alert('注册成功')</script>";
                echo "<meta http-equiv=refresh content=0;URL=".$url.">";
                exit();
        
            } else {
                echo "<script>alert('注册失败');history.go(-1);</script>";
                exit();
            }
        }
        
        $tgcode = I('tgcode','');//推广码
        $this->assign('tgcode',$tgcode);
    	$this->display('index');
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

    //获取验证码
    public function Verify(){
    	$Verify =  new \Think\Verify();
    	$Verify->fontSize = 30;
    	$Verify->length   = 4;
    	$Verify->useNoise = false;
    	$Verify->entry();
    }

    //比对验证码
    private function check_verify($code, $id = ''){
       $verify = new \Think\Verify();    
       return $verify->check($code, $id);
    }

    //检查注册信息是否符合要求
    public function checkRegisterInfo(){
        $do = I('post.do','');
        if ($do == "code"){
            //验证码的检测
            $code = I('code','');
            if(empty($code)) {
                $code="";
            }else{
                $code = strtolower($code);
            }
        
            $scode =$this->check_verify($code);
            if($code == "" || !$scode) {
                echo '2';
            } else {
                echo '1';
            }
        } else if ($do == "username") {
            //用户名的检测
            $username = I('post.username','');

            if ($username) {
                $rs = checkusername($username);

                if ($rs>0) {
                    echo "2";
                } else {
                    echo "1";
                }
            }
        } else if ($do == "email") {
            //邮箱的检测
            $email = I('email','');
            if ($email) {
                $rs = checkemail($email);
                if ($rs) {
                    echo "2";
                } else {
                    echo "1";
                } 
            }
        } else if ($do == "nickname") {
            //昵称检测
            $nickname = I('nickname','');
            if ($nickname) {
                $rs = checknick($nickname);
                if ($rs) {
                    echo "2";
                } else {
                    echo "1";
                } 
            }
        }
    }
    
    public function agreement(){
    	$this->display();
    }
}
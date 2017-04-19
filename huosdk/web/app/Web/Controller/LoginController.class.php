<?php

/**
 * 登录控制器
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class LoginController extends HomebaseController {
    
    public function _initialize() {
        parent::_initialize();
    }
    
    public function index(){
        $this->display();
    }
    
    public function login(){
        
        $action = I('action');
        
        if ($action && $action == 'login') {
            $skip = I('skip');
            $username = I('username','');
            $password = I('password','');
            $lusername = I('lusername','');
            $lpassword = I('lpassword','');
            
            if($lusername != '' && $lpassword != ''){
                $username = $lusername;
                $password = $lpassword;
            }
                       
            $userid = checkusername($username);
            
            if (0 == $userid){
                echo "<script>alert('此帐号不存在');history.go(-1);</script>";
                exit();
            }else if($userid == -1) {
                echo "<script>alert('用户名不合法');history.go(-1);</script>";
                exit();
            } elseif($userid == -2) {
                echo "<script>alert('包含要允许注册的词语');history.go(-1);</script>";
                exit();
            }elseif($userid == -4) {
                echo "<script>alert('改账号已被冻结，请联系客服');history.go(-1);</script>";
                exit();
            }
            
            if ($username && $password) {
                
                $rs = login($username,$password);
                
                if ($rs>0) {
                    $userid = checkusername($username);
                    
                    $userdate = searchuser($username);

                    session('user.sdkuser',$username);
                    session('user.xsst_id',$userid);
                    $rs = insertLogininfo($userid,$userdate['agentgame'],time(),$userdate['from'],$userdate['reg_time']);
                    echo "<meta http-equiv=refresh content=0;URL=".WEBSITE.">";
                    exit();
                } else {
                    echo "<script>alert('密码错误');history.go(-1);</script>";
                    exit();
                }
            }
        }
    }
    
    //退出
    public function logout(){
        
        //退出ucenter
        if (C('UCENTER_ENABLED')) {
            echo uc_user_synlogout();
        }
        
        session('user.sdkuser',NULL);
        session('user.xsst_id',NULL);
        //跳转到首页
        echo "<meta http-equiv=refresh content=0;URL=".U('Index/index').">";
        exit();
    }
}


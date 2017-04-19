<?php

/*
 * 密码邮箱绑定类
 */
namespace Mobile\Controller;

use Common\Controller\MobilebaseController;

class SecurityController extends MobilebaseController
{
    function _initialize() {
        parent::_initialize();
		$contact = sp_get_help();
		$this->assign('qq',$contact['qq']);
		$this->assign('qqgroup',$contact['qqgroup']);
    }
    
    // 账户安全首页
    public function index() {
        $this->assign("title", '账号安全');
        $this->display();
    }
    public function mobile() {
        $mem_id = sp_get_current_userid();
        $userdata = sp_get_user_info($mem_id);
        
        // 查询是否绑定手机
        if (!empty($userdata['mobile'])) {
            // 验证手机
            $_SESSION['mobile_old'] = $userdata['mobile'];
            // $userdata['mobile'] = preg_replace('/(^.*)\d{4}(\d{4})$/','\\1****\\2',$userdata['mobile']);
            $_SESSION['setmobile'] = 1;
            $url = "Security/mobile_verify";
            $this->redirect($url);
        } else {
            // 设置手机
            $_SESSION['setmobile'] = 2;
            $url = "Security/mobile_set";
            $this->redirect($url);
        }
        
        $this->assign("title", '绑定手机');
        $this->display();
    }
    /*
     * 用户更换绑定手机流程
     * 1、 验证原手机
     * 2、 绑定现手机
     * 3、 绑定成功
     * 1、 验证原手机
     */
    public function mobile_verify() {
		if ($_SESSION['setmobile'] != 1) {
            $this->mobile_error();
            exit();
        }
        $mem_id = sp_get_current_userid();
        $userdata = sp_get_user_info($mem_id);
        $_SESSION['mobile_old'] = $userdata['mobile'];
        if (!empty($userdata['mobile'])) {
            $userdata['mobile'] = preg_replace('/(^.*)\d{4}(\d{4})$/', '\\1****\\2', $userdata['mobile']);
        }
        $this->assign("title", "手机绑定");
        $this->assign('userdata', $userdata);
        $this->display();
    }
    
    /*
     * 用户更换绑定手机流程
     * 1、 验证原手机
     * 2、 绑定现手机
     * 3、 绑定成功
     * 2、 绑定现手机
     */
    public function mobile_set() {
        if ($_SESSION['setmobile'] != 2) {
            $this->mobile_error();
            exit();
        }
        
        $_SESSION['setmobile'] = 2;
        $this->assign("title", "手机绑定");
        $this->display();
    }
    
    /*
     * 短信发送
     */
    public function mobile_send() {
        if (1 != $_SESSION['setmobile'] && 2 != $_SESSION['setmobile']) {
            $this->error("参数错误", U('Security/mobile_error'));
        }
        
        if (1 == $_SESSION['setmobile']) {
            $phone = $_SESSION['mobile_old'];
        } else {
            $phone = I('phone/s', '');
        }
        
        $type = I('type/d', 1);
        $sms_controller = new \Common\Controller\SmsController();
        $rdata = $sms_controller->send_auth_code($phone, $type);
        if (!empty($rdata) && $rdata['status'] > 0) {
            $this->success($rdata['info']);
        } else {
            $this->error("短信发送失败");
        }
    }
    
    /*
     * 短信验证码验证
     */
    public function mobile_checkcode() {
        if (1 != $_SESSION['setmobile'] &&  2 != $_SESSION['setmobile']) {
            $this->error("参数错误", U('Security/mobile_error'));
        }
        
        if (1 == $_SESSION['setmobile']) {
            $phone = $_SESSION['mobile_old'];
        } else {
            $phone = I('phone/s', '');
            $pwd = I('pwd/s', '');
            $rs = $this->checkUserpwd($pwd);
            if (!$rs){
                $this->error("密码错误");
            }
        }
        $code = I('code/s', '');
        $type = I('type/d', 1);
        
        $sms_controller = new \Common\Controller\SmsController();
        $rdata = $sms_controller->sms_verify_code($phone, $code);
        if (!empty($rdata) && $rdata['status'] > 0) {
            if (1 == $_SESSION['setmobile']) {
                $_SESSION['setmobile'] = 2;
                unset($_SESSION['mobile_old']);
                $this->success($rdata['info'], U('Security/mobile_set'));
            } else {
                // 更新玩家手机号
                $rs = $sms_controller->setMemphone($phone);
                if (false === $rs) {
                    $this->error("内部服务器错误");
                }
                $this->success($rdata['info'], U('Security/mobile_success'));
            }
        } else {
            $this->error($rdata['info']);
        }
    }
    
    // 操作成功跳转页面
    public function mobile_success() {
        $msg = "手机绑定成功";
        $this->assign('phone', $_SESSION['mobile']);
        
        session('setmobile', null);
        session('mobile', null);
        
        $this->assign('title', "手机绑定");
        $this->assign('info', $msg);
        $this->display('Security/success');
    }
    
    // 绑定失败跳转页面
    public function mobile_error() {
        session('setmobile', null);
        
        $title = "绑定手机";
        $msg = "手机绑定失败";
        $this->assign('title', '手机绑定');
        $this->assign('info', $msg);
        $this->display('Security/error');
    }
    
    public function email() {
        $mem_id = sp_get_current_userid();
        $userdata = sp_get_user_info($mem_id);
        // 查询是否绑定邮箱
        if (!empty($userdata['email'])) {
            // 验证邮箱
            $_SESSION['email_old'] = $userdata['email'];
            // $userdata['email'] = preg_replace('/(^.*)\d{4}(\d{4})$/','\\1****\\2',$userdata['email']);
            $_SESSION['setemail'] = 1;
            $url = "Security/email_verify";
            $this->redirect($url);
        } else {
            // 设置邮箱
            $_SESSION['setemail'] = 2;
            $url = "Security/email_set";
            $this->redirect($url);
        }
        
        $this->assign("title", '邮箱绑定');
        $this->display();
    }

    /*
     * 用户更换绑定邮箱流程
     * 1、 验证原邮箱
     * 2、 绑定现邮箱
     * 3、 绑定成功
     * 1、 验证原邮箱
     */
    public function email_verify() {
        /*if ($_SESSION['setemail'] != 1) {
            $this->email_error();
            exit();
        }
        */
        $mem_id = sp_get_current_userid();
        $userdata = sp_get_user_info($mem_id);
        $_SESSION['email_old'] = $userdata['email'];
        if (!empty($userdata['email'])) {
            $str = $userdata['email'];
            $email_array = explode("@",$str);
            $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
            $count = 0;
            $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
            $userdata['email'] = $prevfix . $str;
        }
        $this->assign("title", "邮箱绑定");
        $this->assign('userdata', $userdata);
        $this->display();
    }
    
    /*
     * 用户更换绑定邮箱流程
     * 1、 验证原邮箱
     * 2、 绑定现邮箱
     * 3、 绑定成功
     * 2、 绑定现邮箱
     */
    public function email_set() {
        /* if ($_SESSION['setemail'] != 2) {
            $this->email_error();
            exit();
        } */
        
        $_SESSION['setemail'] = 2;
        $this->assign("title", "邮箱绑定");
        $this->display();
    }
    
    /*
     * 邮件发送
     */
    public function email_send() {
        if (1 != $_SESSION['setemail'] && 2 != $_SESSION['setemail']) {
            $this->error("参数错误", U('Security/email_error'));
        }
        
        if (1 == $_SESSION['setemail']) {
            $email = $_SESSION['email_old'];
        } else {
            $email = I('email/s', '');
        }
        
        $type = I('type/d', 1);
        $email_controller = new \Common\Controller\EmailController();
        $rdata = $email_controller->send_auth_code($email, $type,60,$_SESSION['user']['username']);
        if (!empty($rdata) && $rdata['status'] > 0) {
            $this->success($rdata['info']);
        } else {
            $this->error("邮件发送失败");
        }
    }
    
    /*
     * 邮件验证码验证
     */
    public function email_checkcode() {
        if (1 != $_SESSION['setemail'] &&  2 != $_SESSION['setemail']) {
            $this->error("参数错误", U('Security/email_error'));
        }
        
        if (1 == $_SESSION['setemail']) {
            $email = $_SESSION['email_old'];
            unset($_SESSION['email_old']);
        } else {
            $email = I('email/s', '');
            $pwd = I('pwd/s', '');
            
            $rs = $this->checkUserpwd($pwd);
            if (!$rs){
                $this->error("密码错误");
            }
        }
        
        $code = I('code/s', '');
        $type = I('type/d', 1);

        
        $email_controller = new \Common\Controller\EmailController();
        $rdata = $email_controller->verify_code($email, $code, 300);
        if (!empty($rdata) && $rdata['status'] > 0) {
            if (1 == $_SESSION['setemail']) {
                $_SESSION['setemail'] = 2;
                $this->success($rdata['info'], U('Security/email_set'));
            } else {
                // 更新玩家邮箱号
                $rs = $email_controller->setMememail($email);
                if (false == $rs) {
                    $this->error("内部服务器错误");
                }
                $this->success($rdata['info'], U('Security/email_success'));
            }
        } else {
            $this->error($rdata['info']);
        }
    }
    
    // 操作成功跳转页面
    public function email_success() {
        $msg = "邮箱绑定成功";
        $this->assign('email', $_SESSION['email']);
        session('setemail', null);
        session('email', null);
        
        $this->assign('title', "邮箱绑定");
        $this->assign('info', $msg);
        $this->display('Security/email_success');
    }
    
    // 绑定失败跳转页面
    public function email_error() {
        session('setemail', null);
        
        $title = "绑定邮箱";
        $msg = "邮箱绑定失败";
        $this->assign('title', '邮箱绑定');
        $this->assign('info', $msg);
        $this->display('Security/email_error');
    }
    
    // 绑定失败跳转页面
    private function checkUserpwd($pwd) {
        if (empty($pwd)){
            return false;
        }
        
        //判断密码是否正确
        $pwd = pw_auth_code($pwd);
        $mem_id = sp_get_current_userid();
        $user_info = sp_get_user_info($mem_id);
        if ($pwd != $user_info['password']){
            return false;
        }
        return true;
    }
    

    
}
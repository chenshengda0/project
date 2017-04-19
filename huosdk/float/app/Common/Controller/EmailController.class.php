<?php

/**
 * EmailController.class.php UTF-8
 * 邮箱处理函数
 * @date: 2016年9月9日下午5:59:10
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : EMAIL 2.0
 */
namespace Common\Controller;

use Common\Controller\AppframeController;

class EmailController extends AppframeController
{
    
    public function checkEmail($email){
        $checkExpressions = "/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/i";
        if (false == preg_match($checkExpressions, $email)) {
            return false;
        }
        return true;
    }
    /*
     * 发送邮箱校验码，登陆注册时
     */
    public function send_auth_code($email, $type, $expaire_time = 0,$username) {
        // 检查邮箱合法性
        $rs = $this->checkEmail($email);
        if (false == $rs) {
            $result['status'] = -1;
            $result['info'] = "邮箱格式不正确";
            return $result;
        }
        
        if (empty($expaire_time)) {
            $limit_time = 120;
        } else {
            $limit_time = $expaire_time; // 设定超时时间 2min
        }
        // 先判断是否发送过验证码
        if (isset($_SESSION['email']) && $_SESSION['email'] == $email && $_SESSION['email_time'] + $limit_time > time()) {
            $result['status'] = 1;
            $result['info'] = "已发送邮件到".$_SESSION['email'];
            return $result;
        }
        
        $email_code = rand(1000, 9999); // 获取随机码
        $_SESSION['email_code'] = $email_code;
        $rs = $this->send_email($email, $email_code, $username);
        if ($rs) {
            $_SESSION['email_time'] = time();
            $_SESSION['email'] = $email;
            // 发送成功
            $result['status'] = 1;
            $result['info'] = "邮件发送成功,请到邮箱查看验证码";
            return $result;
        } else {
            // 邮件发送失败
            $result['status'] = -1;
            $result['info'] = "邮件发送失败";
            return $result;
        }
    }
    public function verify_code($email, $code, $expaire_time = 0) {
        if (empty($expaire_time)) {
            $limit_time = 120;
        } else {
            $limit_time = $expaire_time; // 设定超时时间 2min
        }
        
        if (!$this->checkEmail($email)) {
            $data = array(
                'status' => '-1', 
                'info' => '邮箱格式不正确' 
            );
            return $data;
        }
        
        if (empty($_SESSION['email_time']) || $_SESSION['email_time'] + $limit_time < time()) {
            $data = array(
                'status' => '-1', 
                'info' => '验证码已过期,请重新获取' 
            );
            session('email_time', null);
            session('email_code', null);
            session('email', null);
            return $data;
        }
        // 判断邮箱码是否有效
        if (empty($_SESSION['email']) || $_SESSION['email'] != $email) {
            $data = array(
                'status' => '-1', 
                'info' => '邮箱错误或未填验证码' 
            );
            return $data;
        }
        
        // 验证验证码是否正确
        if (empty($_SESSION['email_code']) || $_SESSION['email_code'] != $code) {
            $data = array(
                'status' => '-1', 
                'info' => '验证码错误' 
            );
            return $data;
        }
        
        //清空验证码与验证码时间
        session('email_time', null);
        session('email_code', null);
        $data = array(
            'status' => '1',
            'info' => '验证码正确'
        );
        
        return $data;
    }
    
    public function setMememail($email){
        $userdata['id'] = sp_get_current_userid();
        $userdata['email'] = $email;
        $rs = M('members')->save($userdata);
        return $rs;
    }
    
    /**
     * 发送 邮件验证码 验证码
     * 
     * @param string $email 邮箱
     * @param string $code 验证码
     * @param string $type 类型
     * @return boole 是否发送成功
     */
    public function send_email($postemail, $code, $username) {
        if (!$this->checkEmail($postemail)) {
            return false;
        }
        $subject = "官方邮箱验证";
        $message = "<html lang='zh-cn'>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
        <style>
        h1 {
        font-size: 16px;
    }
    
    p {
    font-size: 14px;
    line-height: 23px;
    }
    
    .red {
    color: #f30808;
    font-size: 14px;
    }
    
    .redNum {
    display: block;
    color: #fff;
    background: #aac5eb;
    font-size: 33px;
    margin: auto;
    width: 132px;
    text-align: center;
    border: 1px solid;
    height: 36px;
    line-height: 36px;
    position: relative;
    }
    
    .redNum:before {
    content: '';
    display: block;
    height: 100%;
    width: 3px;
    position: absolute;
    left: -4px;
    background: #aac5eb;
    }
    
    .redNum:after {
    content: '';
    display: block;
    height: 100%;
    width: 3px;
    position: absolute;
    top: 0;
    right: -4px;
    background: #aac5eb;
    }
    
    .wrap {
    width: 682px;
    height: 594px;
    no-repeat;
    padding: 60px 30px 50px 30px;
    }
    
    .content {
    width: 620px;
    height: 590px;
    word-break: break-all;
    padding-top: 20px;
    padding-left: 30px;
    padding-right: 30px;
    }
    
    .confirm-mail {
    display: block;
    width: 285px;
    height: 82px;
    
    margin-left: 134px;
    margin-bottom: 30px;
    }
    </style>
    </head>
    <body>
    <div class='wrap'>
    <div class='content'>
    <h1>尊敬的用户（ {$username} ）：</h1>
    <p>
    您正在进行身份认证，要完成该操作，请在<span class='red'>30分钟</span>内输入如下验证码:
    </p>
    <p>
    <span class='redNum'>{$code}</span>
    </p>
    
    <p>如果您输入验证码，或者点击上述链接，提示已过期，请重新发起设置申请，感谢您的配合与支持！</p>
    <p>（如非本人操作，请忽略此邮件）</p>
    <p>&nbsp;</p>
    </div>
    </div>
    </body>
    </html>";
        $emailinfo = sp_send_email($postemail, $subject, $message);
        if (0 == $emailinfo['error']){
            return true;
        }
        return false;
    }
}
<?php
/**
* ForgetpwdController.class.php UTF-8
* 找回密码
* @date: 2016年9月7日下午3:26:39
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@huosdk.com>
* @version: float 2.0
*/
namespace Mobile\Controller;
use Common\Controller\HomebaseController;

class ForgetpwdController extends HomebaseController
{
    function _initialize() {
        parent::_initialize();
		$contact = sp_get_help();
		$this->assign('qq',$contact['qq']);
		$this->assign('qqgroup',$contact['qqgroup']);
    }
    
    // 浮点找回密码首页,输入找回密码的账号
    public function index() {
        session(null);
        $this->assign("title", '找回密码');
        $this->display();
    }
    
    // 检查用户名
    public function check(){
        $username = I('post.username/s','');
        if (empty($username) && $username != $_SESSION['username']) {
            $this->error("请输入正确的用户名");
        }
        
        $map['username'] = $username;
        $userdata = M('members')->where($map)->find();
        if (empty($userdata)){
            $this->error("请输入正确的用户名");
        }
        
        if (3 == $userdata['status']){
            $this->error("改用户账号已被冻结");
        }
        
        if (empty($userdata['email']) && empty($userdata['mobile'])) {
            $helpurl = U('Help/index');
            $msg = "<a href=$helpurl  class='blue'>该账号未绑定手机,点此联系客服</a>";
            $this->error($msg);
        }
        $url = U('Forgetpwd/bindinfo');
        $_SESSION['check_mem_id'] = $userdata['id'];
        $this->success("查询成功",$url);
    }
    
    // 检查账户是否绑定手机
    public function bindinfo() {
        //没有经过验证的用户，继续验证
        if (empty($_SESSION['check_mem_id'])){
            $this->redirect("Forgetpwd/index");
        }
        $mem_id =$_SESSION['check_mem_id'];
        $userdata = sp_get_user_info($mem_id);
        
        if (!empty($userdata['mobile'])) {
            $userdata['mobile'] = preg_replace('/(^.*)\d{4}(\d{4})$/', '\\1****\\2', $userdata['mobile']);
            $_SESSION['setmobile'] = 1;
        }
        
        if (!empty($userdata['email'])) {
            $str = $userdata['email'];
            $email_array = explode("@", $str);
            $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); // 邮箱前缀
            $count = 0;
            $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
            $userdata['email'] = $prevfix . $str;
            $_SESSION['setemail'] = 1;
        }
        
        $this->assign("title", "找回密码");
        $this->assign('userdata', $userdata);
        $this->display();
    }
    
    /*
     * 修改密码三部曲 
     * 1 验证手机
     * 2 修改密码
     * 3 修改成功
     * 
     * 1 验证手机
     */
    public function mobile(){
        if ($_SESSION['setmobile'] != 1) {
            $this->error();
            exit();
        }

        $mem_id = $_SESSION['check_mem_id'];
        $userdata = sp_get_user_info($mem_id);
        $_SESSION['mobile'] = $userdata['mobile'];
        
        if (!empty($userdata['mobile'])) {
            $userdata['mobile'] = preg_replace('/(^.*)\d{4}(\d{4})$/', '\\1****\\2', $userdata['mobile']);
        }
        $this->assign("title", "找回密码");
        $this->assign('userdata', $userdata);
        $this->display();
    }
    
    
    /*
     * 修改密码三部曲
     * 1 验证
     * 2 修改密码
     * 3 修改成功
     *
     * 1 验证邮箱
     */
    public function email(){
        if ($_SESSION['setemail'] != 1) {
            $this->error();
            exit();
        }

        $mem_id = $_SESSION['check_mem_id'];
        $userdata = sp_get_user_info($mem_id);
        $_SESSION['email'] = $userdata['email'];
        
        if (!empty($userdata['email'])) {
            $str = $userdata['email'];
            $email_array = explode("@",$str);
            $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
            $count = 0;
            $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
            $userdata['email'] = $prevfix . $str;
            $_SESSION['find_username'] = $userdata['username'];
        }
        $this->assign("title", "找回密码");
        $this->assign('userdata', $userdata);
        $this->display();
    }
    
    
    // 更新密码
    public function uppwd() {
        //是否已经验证通过
        if (1 != $_SESSION['checkok']) {
            session(null);
            $this->redirect("Forgetpwd/find_error");
            exit;
        }
        
        $_SESSION['uppwd_token'] = md5(uniqid());
        $this->assign("uppwdtoken", $_SESSION['uppwd_token']);
        $this->assign("title", '找回密码');
        $this->display();
    }
    
    // 修改成功
    public function find_success() {
        $this->assign("title", '找回密码');
        $this->assign("info", "密码找回成功");
        $this->display();
    }
    
    // 错误界面
    public function find_error() {
        $this->assign("title", '找回密码');
        $this->assign("info", "密码找回失败");
        $this->display();
    }
    
    // 更新密码处理函数
    public function uppwd_post() {
        if (IS_POST) {
            $action = I('post.action/s','');
            if (!empty($_SESSION['uppwd_token']) && $_SESSION['uppwd_token'] == $action) {                
                $newpwd = I('post.newpwd');
                $verifypwd = I('post.verifypwd');
                
                // 密码不能为空
                if (empty($newpwd)) {
                    $this->error("新密码不能为空.", '', true);
                    exit();
                }
                
                // 确认密码不能为空
                if (empty($verifypwd)) {
                    $this->error("新密码不能为空.", '', true);
                    exit();
                }
                
                // 密码必须为数字字母组合, 长度在6-16位之间
                $checkExpressions = "/^[0-9A-Za-z-`=\\\[\];',.\/~!@#$%^&*()_+|{}:\"<>?]{6,16}$/";
                if (false == preg_match($checkExpressions, $newpwd)) {
                    $this->error("密码必须由6-16位的数字、字母、符号组成", '', true);
                    exit();
                }
                
                // 新密码与确认密码不一致
                if ($newpwd != $verifypwd) {
                    $this->error("确认密码与新密码不一致", '', true);
                    exit();
                }
                $mem_id = $_SESSION['check_mem_id'];
                $userdata['id'] = $mem_id;
                $userdata['password'] = pw_auth_code($newpwd);
                $userdata['update_time'] = time();
                $rs = M('members')->save($userdata);
                if (false != $rs) {
                    $this->success("密码修改成功", U('Forgetpwd/find_success'));
                    exit();
                } else {
                    $this->error("服务器内部错误");
                    exit();
                }
            }
            $this->error("非法请求");
        }
    }
    
    

    
    
    /*
     * 短信发送
     */
    public function mobile_send() {
        if (1 != $_SESSION['setmobile']) {
            session(null);
            $this->success("参数错误", U('Forgetpwd/find_error'));
            exit;
        }
    
        if (1 == $_SESSION['setmobile']) {
            $phone = $_SESSION['mobile'];
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
        if (1 != $_SESSION['setmobile'] ) {
            session(null);
            $this->success("参数错误", U('Forgetpwd/find_error'));
            exit;
        }
    
        if (1 == $_SESSION['setmobile']) {
            $phone = $_SESSION['mobile'];
        } 
    
        $code = I('code/s', '');
        $type = I('type/d', 1);
    
        $sms_controller = new \Common\Controller\SmsController();
        $rdata = $sms_controller->sms_verify_code($phone, $code);
        if (!empty($rdata) && $rdata['status'] > 0) {
            if (1 == $_SESSION['setmobile']) {
                session('setmobile',null);
                $_SESSION['checkok'] = 1;  //表明验证通过
                $this->success($rdata['info'], U('Forgetpwd/uppwd'));
            } 
        } else {
            $this->error($rdata['info']);
        }
    }
    
    /*
     * 邮件发送
     */
    public function email_send() {
        if (1 != $_SESSION['setemail']) {
            session(null);
            $this->success("参数错误", U('Forgetpwd/find_error'));
            exit;
        }
    
        if (1 == $_SESSION['setemail']) {
            $email = $_SESSION['email'];
        }
        
        $username = $_SESSION['find_username'];
        $type = I('type/d', 1);
        $email_controller = new \Common\Controller\EmailController();
        $rdata = $email_controller->send_auth_code($email, $type,60,$username);
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
        if (1 != $_SESSION['setemail'] ) {
            session(null);
            $this->success("参数错误", U('Forgetpwd/find_error'));
            exit;
        }
    
        if (1 == $_SESSION['setemail']) {
            $email = $_SESSION['email'];
        }
    
        $code = I('code/s', '');
        $type = I('type/d', 1);
    
        $email_controller = new \Common\Controller\EmailController();
        $rdata = $email_controller->verify_code($email, $code,300);
        if (!empty($rdata) && $rdata['status'] > 0) {
            if (1 == $_SESSION['setemail']) {
                session('setemail',null);
                $_SESSION['checkok'] = 1;  //表明验证通过
                $this->success($rdata['info'], U('Forgetpwd/uppwd'));
            }
        } else {
            $this->error($rdata['info']);
        }
    }
    
}
<?php

/**
 * PasswordController.class.php UTF-8
 * 密码控制类
 * @date: 2016年9月7日下午2:53:01
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : float 2.0
 */
namespace Mobile\Controller;

use Common\Controller\MobilebaseController;

class PasswordController extends MobilebaseController
{
    private $member_model;
    function _initialize() {
        parent::_initialize();
        $this->member_model = M('members');
		$contact = sp_get_help();
		$this->assign('qq',$contact['qq']);
		$this->assign('qqgroup',$contact['qqgroup']);
    }
    
    // 修改密码
    public function uppwd() {
        $this->assign("title", '修改密码');
        $_SESSION['uppwd_token'] = md5(uniqid());
        $this->assign('uppwdtoken',$_SESSION['uppwd_token']);
        $this->display();
    }
    
    /*
     * 修改密码处理函数
     */
    public function uppwd_post() {
        if (IS_POST) {
            $action = I('post.action/s','');
            if (!empty($_SESSION['uppwd_token']) && $_SESSION['uppwd_token'] == $action) {                
                $oldpwd = I('post.oldpwd');
                $newpwd = I('post.newpwd');
                $verifypwd = I('post.verifypwd');
                
                // 不能为空
                if (empty($oldpwd)) {
                    $this->error("原密码不能为空.", '', true);
                    exit();
                }
                
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
                
                $data['password'] = pw_auth_code($oldpwd);
                $mem_id = sp_get_current_userid();
                $userdata = $this->member_model->where(array(
                    'id' => $mem_id 
                ))->find();
                if ($data['password'] == $userdata['password']) {
                    $userdata['password'] = pw_auth_code($newpwd);
                    $userdata['update_time'] = time();
                    
                    $rs = $this->member_model->where(array(
                        'id' => $mem_id 
                    ))->save($userdata);
                    if (false != $rs) {
                        $this->success("密码修改成功", U('Password/pwd_success'));
                        exit();
                    }
                } else {
                    $this->error("原密码错误".$data['password']);
                    exit();
                }
            }
            $this->error("非法请求");
        }
    }
    
    // 操作成功跳转页面
    public function pwd_success() {
        $this->assign("title", '修改密码');
        $this->assign('info',"密码修改成功");
        $this->display("Password/success");
    }
    
    // 操作成功跳转页面
    public function pwd_error() {
        $this->assign("title", '修改密码');
        $this->assign('info',"密码修改失败");
        $this->display("Password/error");
    }
}
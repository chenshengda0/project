<?php
/**
* UserController.class.php UTF-8
* 用户中心控制
* @date: 2016年7月8日下午2:54:46
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@1tsdk.com>
* @version: 1.0
*/
namespace Float\Controller;
use Common\Controller\AdminbaseController;
class UserController extends AdminbaseController{
	protected $member_model;
	
	function _initialize() {
		parent::_initialize();
		$this->member_model = M('members');
	}
	
	//浮点用户信息首页
	public function index(){
	    $mem_id = sp_get_current_user();
        $userdata = $this->member_model->where(array('id'=>$mem_id))->find();

        $yxb_cnt = M('gm_mem')
        ->where(array('mem_id'=>$mem_id))
        ->count();
		
		$ptb_sum = M('ptb_mem')
        ->where(array('mem_id'=>$mem_id))
        ->getField("remain");
        
		$this->assign("ptb_sum",$ptb_sum);
        $this->assign("yxb_cnt", $yxb_cnt);
        $this->assign("title", '用户中心');
        $this->assign("userdata", $userdata);
		$this->display();
	}
	
	//修改密码
	public function uppwd(){
	    $this->assign("title", '修改密码');
	    $this->display();
	}
	
	/*
	 * 修改密码处理函数
	 */
	public function uppwd_post(){
	    if (IS_POST){
	        $action = I('post.action');
	        if ('updatepwd' == $action){
	            $oldpwd = I('post.oldpwd');
	            $newpwd = I('post.newpwd');
	            $verifypwd = I('post.verifypwd');
	            
	            //不能为空
	            if (empty($oldpwd)) {
	                $this->error("原密码不能为空.",'',true);
	                exit;
	            }
	            
	            //密码不能为空
	            if (empty($newpwd)) {
	                $this->error("新密码不能为空.",'',true);
	                exit;
	            }
	            
	            //确认密码不能为空
	            if (empty($newpwd)) {
	                $this->error("新密码不能为空.",'',true);
	                exit;
	            }
	            
	            //用户名必须为数字字母组合, 长度在6-16位之间
	            $checkExpressions = "/^[0-9A-Za-z-`=\\\[\];',.\/~!@#$%^&*()_+|{}:\"<>?]{6,16}$/";
	            if (false == preg_match($checkExpressions, $newpwd)){
	                $this->error("密码必须由6-16位的数字、字母、符号组成",'',true);
	                exit;
	            }
	            
	            //新密码与确认密码不一致
	            if ($newpwd != $verifypwd) {
	                $this->error("确认密码与新密码不一致",'',true);
	                exit;
	            }
	            
	            $data['password'] = pw_auth_code($oldpwd);
	            $mem_id = sp_get_current_user();
	            $userpwd = $this->member_model->where(array('id'=>$mem_id))->getField('password');
	            if ($data['password'] == $userpwd) {
	                $data['password'] = pw_auth_code($newpwd);
	                $data['update_time'] = time();
	            
	                $rs =  $this->member_model->where(array('id'=>$mem_id))->save($data);
	                if (false != $rs) {
	                    $this->success("修改密码成功",U('User/pwd_success'),true);
	                    exit;
	                }
	            }else{
	                $this->error("原密码错误",'',true);
	                exit;
	            }
	        }
	    }
	}
	//操作成功跳转页面
	public function pwd_success(){
	    $title = "密码修改";
	    $msg = "密码修改成功";
        $this->ac_success($title, $msg);
	}
	
	//操作成功跳转页面
	public function pwd_error(){
	    $title = "密码修改";
	    $msg = "密码修改失败";
        $this->ac_error($title, $msg);
	}
}
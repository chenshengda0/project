<?php

namespace Api\Controller;
use Think\Controller;
class CheckemailController extends Controller {
	public function emailsafe(){
	    header("Content-Type:text/html; charset=utf-8");
	    $user = I('get.u');
	    $userarr = explode(',',base64_decode($user));
	    if($user){
	        $time = sp_auth_code($userarr[2], "DECODE");
	        if(time() - $time > 60*60*30){
	            echo '验证邮箱已过有效期,请重新申请';
	            exit;
	        }

	        $mem_id = sp_auth_code($userarr[0], "DECODE");
	        $password = sp_auth_code($userarr[1], "DECODE");
	        $email = $userarr[3];

	        $member_model = M('members');
	        $field = "password, email";
	        $userdata = $member_model->field($field)->where(array('id'=>$mem_id, 'status'=>2))->find();
	        if($userdata['password'] == $password){
				if (!empty($userdata['email'])){
					$msg = "该账号已绑定过邮箱.";
				}else{
					$rs =  $member_model->where(array('id'=>$mem_id, 'status'=>2))->setField('email',$email);
					if (isset($rs) && $rs>0) {
						$msg = "邮箱绑定成功.";
					} elseif (isset($rs) && $rs==0){
						$msg = "邮箱已绑定.";
					}else{
						$msg = "邮箱绑定失败.";
					}
				}
	        } else {
	            $msg = "验证失败,请重新申请或联系客服.";
	        }
	    }
        echo $msg;
        exit;
	}
}
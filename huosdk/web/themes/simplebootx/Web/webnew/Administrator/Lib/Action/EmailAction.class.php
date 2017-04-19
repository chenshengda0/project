<?php
class EmailAction extends AuthAction {
    function index(){
        //查询邮箱配置表
        $email_arr = $GLOBALS['db']->getROw("SELECT * FROM ".DB_PREFIX."email_conf");
        if($email_arr){
        	$email_type = $email_arr['mail_type'];
        	$email_auth = $email_arr['mail_auth'];
        }else{
        	$email_type = 1;
        	$email_auth = 1;
        }
        $this->assign("email_type",$email_type);
        $this->assign("email_auth",$email_auth);
        $this->assign("email_arr",$email_arr);
        $this->display();
    }
    
    /*
     * 
     * 保存邮箱配置
     * 
     */
    function save_conf(){
        //查询邮箱配置表
        $email_arr = $GLOBALS['db']->getROw("SELECT * FROM ".DB_PREFIX."email_conf");
        
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    	$mail_server = isset($_POST['mail_server']) ? new_html_special_chars(trim($_POST['mail_server'])) : "";
    	$mail_port = isset($_POST['mail_port']) ? intval($_POST['mail_port']) : 25;
    	$mail_from = isset($_POST['mail_from']) ? new_html_special_chars(trim($_POST['mail_from'])) : "";
    	$mail_user = isset($_POST['mail_user']) ? new_html_special_chars(trim($_POST['mail_user'])) : "";
    	$mail_password = isset($_POST['mail_password']) ? new_html_special_chars(trim($_POST['mail_password'])) : "";
    	
    	
    	if($_POST['email_conf']){
    	    if(!$email_arr){
    	    	$sql = "INSERT INTO ".DB_PREFIX."email_conf SET 
    	    	        mail_server = '".$mail_server."',
    	    	        mail_port = '".$mail_port."',
    	    	        mail_from = '".$mail_from."',
    	    	        mail_user = '".$mail_user."',
    	    	        mail_password = '".$mail_password."'";
    	    }else{
    	        $sql = "UPDATE ".DB_PREFIX."email_conf SET
    	    	        mail_server = '".$mail_server."',
    	    	        mail_port = '".$mail_port."',
    	    	        mail_from = '".$mail_from."',
    	    	        mail_user = '".$mail_user."',
    	    	        mail_password = '".$mail_password."' 
    	    	        WHERE id = ".$id;
    	    }
    	    if($GLOBALS['db']->query($sql)){
    	    	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME]);
    	    	echo json_encode(array("success"=>1,"info"=>"邮箱配置成功"));
    	    	exit;
    	    }else{
    	    	echo json_encode(array("success"=>-1,"info"=>"未知错误，请重新配置！"));
    	    	exit;
    	    }
    	}
    }
    
    /*
     * 
     * 测试邮件发送
     * 
     */
    function public_test_email(){
        $subject = '钱景测试';
        $message = '<h1>钱景测试邮件</h1>';
    	$mail_server = isset($_POST['mail_server']) ? new_html_special_chars(trim($_POST['mail_server'])) : "";
    	$mail_port = isset($_POST['mail_port']) ? intval($_POST['mail_port']) : 25;
    	$mail_from = isset($_POST['mail_from']) ? new_html_special_chars(trim($_POST['mail_from'])) : "";
    	$mail_user = isset($_POST['mail_user']) ? new_html_special_chars(trim($_POST['mail_user'])) : "";
    	$mail_password = isset($_POST['mail_password']) ? new_html_special_chars(trim($_POST['mail_password'])) : "";
    	$mail_to = isset($_POST['mail_to']) ? new_html_special_chars(trim($_POST['mail_to'])) : "";
    	
        if(sendmail_test($mail_server, $mail_user, $mail_password, $mail_to, $mail_user, $subject, $message)) {
        	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
        	echo json_encode(array("success"=>1,"info"=>"邮件发送成功，请查收！"));
        	exit;
        } else {
        	echo json_encode(array("success"=>-1,"info"=>"邮件发送失败！"));
	    	exit;
        }
    }
    
}
?>
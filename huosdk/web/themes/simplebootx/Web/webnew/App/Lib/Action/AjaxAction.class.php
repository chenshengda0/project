<?php
    /*
     * xiucms
     * 
     */
    class AjaxAction extends CommonAction {
        function check_field(){
        	$field_name = new_addslashes(trim($_POST['field_name']));
        	if($field_name == "username"){
        	    $arr2 = array(
        	    		'~', '!', '@', '#', '$', '%', '^', '&', '*', '_', '+', '|', '-', '=', '\\',
        	    		'{', '}', '[', ']', ':', ';', '"', '\'', '<', '>', ',', '.', '?', '/', '“', '”',
        	    		'’', '‘', '【', '】', '~', '！', '￥', '……', '——', '、', '《', '》', '。',
        	    		PHP_EOL, chr(10), chr(13), "\t", chr(32)
        	    );
        		$username = new_addslashes(trim($_POST['field_data']));
        		if(preg_match('/^\d+$/i', $username)){
        			showMsgajax("用户名不能为纯数字",-1);
        		}
        		if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."user WHERE username = '$username' AND emailpassed = 1")){
        			showMsgajax("用户名已存在",-1);
        		}
        		
        		if($arr2){
        			foreach($arr2 as $k=>$v){
        				if(strpos($username,$v) !== false){
        				    showMsgajax("用户名不能含有空格、点、逗号等特殊字符",-1);
        				}
        			}
        		}
        		
        	}elseif($field_name == "mobile"){
        		$mobile = new_addslashes(trim($_POST['field_data']));
        
        		if(!check_mobile($_POST['mobile'])){
        			showMsgajax("手机号格式错误",-1);
        		}
        
        		if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."user WHERE mobile = '$mobile'")){
        			showMsgajax("手机号已存在",-1);
        		}
        	}elseif($field_name == "verify"){
        	    $verify = new_addslashes(trim($_POST['field_data']));
        	     
        	    if($verify == "" || strtolower($verify) != strtolower($this->session->get("verify"))){
        	    	showMsgajax(lang("VERIFY_ERROR"),-1);
        	    }
        	}elseif($field_name == "email"){
        		$email = new_addslashes(trim($_POST['field_data']));
        		if(!check_email($email)){
        			showMsgajax("邮箱地址错误",-1);
        		}
        		if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."user WHERE email = '$email' AND emailpassed = 1")){
        			showMsgajax("邮箱已使用",-1);
        		}
        	}
        	showMsgajax("",1);
        
        }
        
    }
    
?>
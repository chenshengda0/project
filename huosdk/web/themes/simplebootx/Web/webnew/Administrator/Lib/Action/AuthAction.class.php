<?php
    /*
     *  qjcms
     * 
     */
    class AuthAction extends CommonAction {
        
        function __construct(){
        	parent::__construct();
            $this->assign("GLOBALS",$GLOBALS);
            $this->check_auth();
        }
        
        function check_auth(){
        	$admin_uid = $this->session->get("admin_uid");
    	    $admin_time = $this->session->get("admin_time");
    	    $time = time();
    	    
    	    ///免登陆页面
    	    $nologin_arr = array("login","dologin");
    	    $noaccess_arr = array("login","dologin","loginout","lang","index","clear_cache");
        	
        	if(in_array(strtolower(ACTION_NAME), $nologin_arr) && strtolower(MODULE_NAME) == "index"){
        		if($admin_uid && (sysconf("EXPIRED_TIME")==0 || $time - $admin_time<= sysconf("EXPIRED_TIME"))){
        		    url_redirect(ADMIN_URL."/Admin/index");
        		}
        	}else{
        	    if(!$admin_uid || (sysconf("EXPIRED_TIME")>0 && $time - $admin_time > sysconf("EXPIRED_TIME"))){
                    unset($GLOBALS['admin']);
        	    	url_redirect(ADMIN_URL."/Index/login");
        	    }else{
            	    global $admin;
            	    $admin = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."admin WHERE id = ".$admin_uid);
            	    if($admin['is_del'] == 1){
            	    	$this->do_loginout();
            	    	url_redirect(ADMIN_URL."/Index/login");
            	    }
        	        $this->session->set("admin_time",time());
        	    }
        	}
        	if(!(in_array(strtolower(ACTION_NAME), $noaccess_arr) && strtolower(MODULE_NAME) == "index")){
        	    //$this->check_access();
        	}
        	
        }
        
        function do_login($username,$password){
        	$username = new_addslashes($username);
        	$password = new_addslashes($password);
        	$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."admin WHERE username = '$username' AND is_del = 0");
        	if(!$user){
        		return -1;
        	}
        	elseif($user['password']!= md5($password.$user['salt'])){
        		return 0;
        	}else{
        	    $this->session->set("admin_uid",$user['id']);
        	    $this->session->set("admin_time",time());
        	    $GLOBALS['db']->query("UPDATE ".DB_PREFIX."admin SET login_ip = '".get_client_ip()."',login_time = '".time()."' WHERE id = ".$user['id']);
        	    return 1;
        	}
        	
        	
        }
        
        function do_loginout(){
            unset($GLOBALS['admin']);
        	$this->session->clear();
        }

        /************权限***********/
        function check_access(){
            
            $moduleid = $GLOBALS['db']->getOne("SELECT id FROM ".DB_PREFIX."modules WHERE `class` = '".MODULE_NAME."' AND is_del = 0");
            $actionid = $GLOBALS['db']->getOne("SELECT id FROM ".DB_PREFIX."actions WHERE moduleid = ".intval($moduleid)." AND `func` = '".ACTION_NAME."' AND is_del = 0");
            $admin_uid = $this->session->get("admin_uid");
            
            $access = $GLOBALS['db']->getOne("SELECT * FROM ".DB_PREFIX."group_access 
                                                WHERE groupid = ".$GLOBALS['admin']['groupid']."
                                                AND actionid = ".intval($actionid));
            if(!$access){
                if(isset($_REQUEST['isajax'])){
                    showMsgajax("对不起，您暂时无操作权限，请联系管理员开放权限！",-1);exit;
                }else{
            	    showMsg("无权限","goback",-1,0,1);exit;
                }
            }
        }
        /************权限***********/
	
        
    }
    
    
?>
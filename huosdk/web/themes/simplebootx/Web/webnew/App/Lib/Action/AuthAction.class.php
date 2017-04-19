<?php
    /*
     *  qjcms
     * 
     */
    class AuthAction extends CommonAction {
        
        function __construct(){
        	parent::__construct();
            $this->check_auth();
        }
        
        function check_auth(){
            $wap = isset($_GET['wap']) ? intval($_GET['wap']) : 0;
            if(!$GLOBALS['user']){
                showMsg("用户已经过期，请重新登录",url()."login/",-1,$wap);
            	url_redirect(url()."login/");
            }
        	return ;
        }
        
        
    
    }
    
    
?>
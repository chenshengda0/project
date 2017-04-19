<?php
    
/*
     *  qjcms
     * 
     */
    class IndexAction extends AuthAction {
        public function index(){
            
    	    $this->display();
        }
    	
    	public function login(){
    	    
    		$this->display("Public:login");
    	}
    	
    	public function dologin(){
    	    $username = $_POST['username'];
    	    $password = $_POST['password'];
    	    $verify = $_POST['verify'];
    	   
    	    if($verify == "" || strtolower($verify) != strtolower($this->session->get("verify"))){
    	        showMsg(lang("VERIFY_ERROR"),ADMIN_URL."/Index/login",-1);
    	    }
    	    
    	    $result = parent::do_login($username, $password);
    	    if($result==1){
    	    	add_admin_log("管理员".$GLOBALS['action'][ACTION_NAME].":".$username);
    	        url_redirect(ADMIN_URL."/Admin/index");
    	    }elseif($result==0){
    	        showMsg(lang("PWD_ERROR"),ADMIN_URL."/Index/login",-1);
    	    }elseif($result==-1){
    	        showMsg(lang("USERNAME_NOT_EXISTS"),ADMIN_URL."/Index/login",-1);
    	    }
    	    
    	}
    	
    	public function loginout(){
    		parent::do_loginout();
    		url_redirect(ADMIN_URL."/Index/login");
    	}
    	
    	//清除缓存
    	function clear_cache(){
    		import('ORG.Util.Dir');// 导入文件操作类
    		$runtime_path = str_replace(APP_NAME, "App", RUNTIME_PATH);
    		$html_path = str_replace(APP_NAME, "App", HTML_PATH);
    		//echo $html_path;exit;
    		$dir = new Dir();
    		if(is_dir($runtime_path)){
    			$dir->delDir($runtime_path,false);
    			showMsg("清除成功");
    		}else{
    			showMsg("没有缓存文件");
    		}
    	
    	}
    	
    	public function lang(){
    	    
    	    $str = "var LANG = {";
    	    foreach($GLOBALS["lang"] as $k=>$lang)
    	    {
    	    	$str .= "\"".$k."\":\"".$lang."\",";
    	    }
    	    $str = substr($str,0,-1);
    	    $str .="};";
    	    echo $str;
    	}
    }
?>
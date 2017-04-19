<?php
    /*
     *  qjcms
    *
    */
    class SmsAction extends AuthAction {
        function index(){
    		$modules = $this->read_modules();
    		$db_modules = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."sms_conf");
    		foreach($modules as $k=>$v)
    		{
    			$sms_info = array();
    			foreach($db_modules as $kk=>$vv)
    			{
    				if($v['class_name']==$vv['class_name'])
    				{
    					//已安装
    					$modules[$k]['id'] = $vv['id'];
    					//$modules[$k]['is_effect'] = $vv['is_effect'];
    					$modules[$k]['description'] = $vv['description'];
    					$modules[$k]['installed'] = 1;
    					$sms_info = $vv;
    					break;
    				}
    			}
    			if($modules[$k]['installed'] != 1)
    			$modules[$k]['installed'] = 0;	
    			else
    			{
    				if($modules[$k]['is_effect']==1)
    				{
    					include ROOT_PATH."system/sms/".$modules[$k]['class_name']."_sms.php";
    					$sms_class = $modules[$k]['class_name']."_sms";
    					$sms_module = new $sms_class($sms_info);
    					$modules[$k]['name'] = $sms_module->getSmsInfo();
    				}
    			}			
    		}
    		$this->assign("sms_list",$modules);
    		$this->display();
    	}
    	
    	function install(){
    	    
    	    if($_POST['commit']){
    	        if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."sms_conf WHERE class_name = '".new_addslashes($_POST['name'])."'")){
    	        	showMsg(lang("SUCCESS"),ADMIN_URL."/Sms/index");
    	        }
    	        

    	        $sms_conf = array();
    	        $sms_conf['userid'] = intval($_POST['userid']);
    	        $sms_conf['name'] = new_addslashes($_POST['name']);
    	        $sms_conf['class_name'] = new_addslashes($_POST['class_name']);
    	        $sms_conf['server_url'] = new_addslashes($_POST['server_url']);
    	        $sms_conf['username'] = new_addslashes($_POST['username']);
    	        $sms_conf['password'] = new_addslashes($_POST['password']);
    	        $sms_conf['description'] = new_addslashes($_POST['description']);
    	        
    	    	if($GLOBALS['db']->getOne("SELECT count(1) FROM ".DB_PREFIX."sms_conf WHERE is_effect = 1")){
    	    		$sms_conf['is_effect'] = 0;
    	    	}else{
    	    		$sms_conf['is_effect'] = 1;
    	    	}
    	    	$GLOBALS['db']->autoExecute(DB_PREFIX."sms_conf",$sms_conf,"INSERT");
    	    	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$sms_conf['name']);
    	    	showMsg(lang("SUCCESS"),ADMIN_URL."/Sms/index");
    	    }
    		
    	    $class_name = $_REQUEST['class_name'];
    		$directory = ROOT_PATH."system/sms/";
    		$read_modules = true;
    		
    		$file = $directory.$class_name."_sms.php";
    		if(file_exists($file))
    		{
    			$module = require_once($file);
    			$rs = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."sms_conf WHERE class_name = '$class_name'");
    			if($rs > 0)
    			{
    				showMsg(lang("SMS_INSTALLED"),ADMIN_URL."/Sms/index",-1);
    			}
    		}
    		else
    		{
    			showMsg(lang("SMS_NOT_EXISTS"),ADMIN_URL."/Sms/index",-1);
    		}
    		
    		//开始插入数据
    		$data['name'] = $module['name'];
    		$data['class_name'] = $module['class_name'];
    		$data['server_url'] = $module['server_url'];
    		$data['lang'] = $module['lang'];
    		
    
    		$this->assign("data",$data);
    	    
    	    $this->display();
    	}
    	
    	function edit(){
    	    if($_POST['commit']){
    	    	$id = intval($_POST['id']);
    	    	$sms_conf = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."sms_conf WHERE id = '$id'");
    	    	if(!$sms_conf){
    	    		showMsg(lang("ERROR"),ADMIN_URL."/Sms/index",-1);
    	    	}
    	    	$sms_conf['userid'] = intval($_POST['userid']);
    	    	$sms_conf['username'] = new_addslashes($_POST['username']);
    	    	$sms_conf['password'] = new_addslashes($_POST['password']);
    	    	$sms_conf['description'] = new_addslashes($_POST['description']);
    	        $sms_conf['server_url'] = new_addslashes($_POST['server_url']);
    	    	
    	    	$GLOBALS['db']->autoExecute(DB_PREFIX."sms_conf",$sms_conf,"UPDATE","id = '$id'");
    	    	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$sms_conf['name']);
    	    	showMsg(lang("SUCCESS"),ADMIN_URL."/Sms/index");
    	    	
    	    }
    	    
    		$id = intval($_GET['id']);
    		
    		$data = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."sms_conf WHERE id = '$id'");
    		
    		$this->assign("data",$data);
    		
    		$this->display();
    		
    	}
    	
    	function uninstall(){
    	    $id = intval($_GET['id']);
    	    $sms_conf = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."sms_conf WHERE id = '$id'");
    	    $GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."sms_conf WHERE id = '$id'");
    	    add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$sms_conf['name']);
    	    showMsg(lang("SUCCESS"),ADMIN_URL."/Sms/index");
    	}
    	
    	function set_effect(){
    		$id = intval($_GET['id']);
    		
    		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."sms_conf SET is_effect = 0");
    		
    		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."sms_conf SET is_effect = 1 WHERE id = '$id'");
    		
    		showMsg(lang("SUCCESS"),ADMIN_URL."/Sms/index");
    		
    	}
        
        

        private function read_modules()
        {
        	$directory = ROOT_PATH."system/sms/";
        	$read_modules = true;
        	$dir = @opendir($directory);
        	$modules     = array();
        
        	while (false !== ($file = @readdir($dir)))
        	{
        		if (preg_match("/^.*?\.php$/", $file))
        		{
        			$modules[] = require_once($directory.$file);
        		}
        	}
        	@closedir($dir);
        	unset($read_modules);
        
        	foreach ($modules AS $key => $value)
        	{
        		ksort($modules[$key]);
        	}
        	ksort($modules);
        
        	return $modules;
        }
        
    }
?>
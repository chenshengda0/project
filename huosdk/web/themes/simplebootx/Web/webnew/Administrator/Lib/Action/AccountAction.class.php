<?php
    /*
     *  qjcms
    *
    */
    class AccountAction extends AuthAction {
        function index(){
    		$modules = $this->read_modules();
    		$db_modules = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."account_conf");
    		foreach($modules as $k=>$v)
    		{
    			$account = array();
    			foreach($db_modules as $kk=>$vv)
    			{
    				if($v['code']==$vv['code'])
    				{
    					//已安装
    					$modules[$k]['id'] = $vv['id'];
    					$modules[$k]['is_open'] = $vv['is_open'];
    					$modules[$k]['description'] = $vv['description'];
    					$modules[$k]['installed'] = 1;
    					$account = $vv;
    					break;
    				}
    			}
    		}
    		$this->assign("list",$modules);
    		$this->display();
    	}
    	
    	function install(){
    	    
    	    if($_POST['commit']){
    	        if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."account_conf WHERE code = '".new_addslashes($_POST['code'])."'")){
    	        	showMsg(lang("SUCCESS"),ADMIN_URL."/Account/index");
    	        }
    	        

    	        $account_conf = array();
    	        $account_conf['code'] = new_addslashes($_POST['code']);
    	        $account_conf['name'] = new_addslashes($_POST['name']);
    	        $account_conf['description'] = new_addslashes($_POST['description']);
    	        $account_conf['config'] = serialize($_POST['config']);
    	        $account_conf['is_open'] = intval($_POST['is_open']);
    	        $account_conf['create_time'] = time();
    	        
    	    	$GLOBALS['db']->autoExecute(DB_PREFIX."account_conf",$account_conf,"INSERT");
    	    	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$account_conf['name']);
    	    	showMsg(lang("SUCCESS"),ADMIN_URL."/Account/index");
    	    }
    		
    	    $code = $_REQUEST['code'];
    		$directory = ROOT_PATH."system/account/";
    		$read_modules = true;
    		
    		$file = $directory.$code."_account.php";
    		if(file_exists($file))
    		{
    			$module = require_once($file);
    			$rs = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."account_conf WHERE code = '$code'");
    			if($rs > 0)
    			{
    				showMsg(lang("SMS_INSTALLED"),ADMIN_URL."/Account/index",-1);
    			}
    		}
    		else
    		{
    			showMsg(lang("SMS_NOT_EXISTS"),ADMIN_URL."/Account/index",-1);
    		}
    		
    		//开始插入数据
    		$data['name'] = $module['name'];
    		$data['code'] = $module['code'];
    		$data['description'] = $module['description'];
    		$data['config'] = $module['config'];
    		
    
    		$this->assign("data",$data);
    	    
    	    $this->display();
    	}
    	
    	function edit(){
    	    if($_POST['commit']){
    	    	$id = intval($_POST['id']);
    	    	$account_conf = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."account_conf WHERE id = '$id'");
    	    	if(!$account_conf){
    	    		showMsg(lang("ERROR"),ADMIN_URL."/Account/index",-1);
    	    	}
    	    	$account_conf['code'] = new_addslashes($_POST['code']);
    	        $account_conf['name'] = new_addslashes($_POST['name']);
    	        $account_conf['description'] = new_addslashes($_POST['description']);
    	        $account_conf['config'] = serialize($_POST['config']);
    	        $account_conf['is_open'] = intval($_POST['is_open']);
    	    	
    	    	$GLOBALS['db']->autoExecute(DB_PREFIX."account_conf",$account_conf,"UPDATE","id = '$id'");
    	    	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$account_conf['name']);
    	    	showMsg(lang("SUCCESS"),ADMIN_URL."/Account/index");
    	    	
    	    }
    	    
    		$id = intval($_GET['id']);
    		
    		$data = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."account_conf WHERE id = '$id'");
    		$data['config'] = unserialize($data['config']);
    		$this->assign("data",$data);
    		
    		$this->display();
    		
    	}
    	
    	function uninstall(){
    	    $id = intval($_GET['id']);
    	    $account_conf = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."account_conf WHERE id = '$id'");
    	    $GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."account_conf WHERE id = '$id'");
    	    add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$account_conf['name']);
    	    showMsg(lang("SUCCESS"),ADMIN_URL."/Account/index");
    	}
    	
    	function set_effect(){
    		$id = intval($_GET['id']);
    		
    		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."account_conf SET is_effect = 0");
    		
    		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."account_conf SET is_effect = 1 WHERE id = '$id'");
    		
    		showMsg(lang("SUCCESS"),ADMIN_URL."/Account/index");
    		
    	}
        
        

        private function read_modules()
        {
        	$directory = ROOT_PATH."system/account/";
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
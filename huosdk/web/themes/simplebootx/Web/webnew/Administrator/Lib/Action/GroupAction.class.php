<?php
    /*
     * showcms
     * 
     */
    class GroupAction extends AuthAction {
    	function index(){
    	    
    	    $page = $_GET['page']?intval($_GET['page']):1;
    	    $perpage = 20;
    	    $where = " WHERE is_del = 0 ";
    	    $limit = " LIMIT ".($perpage*($page-1)).",$perpage";
    	    
    	    $list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."group WHERE is_del = 0 ORDER BY orderid ASC $limit");
    	    $count = $GLOBALS['db']->getOne("SELECT count(1) FROM ".DB_PREFIX."group WHERE is_del = 0");
    	    
    	    $pages = new Page($count,$perpage);
    	    $pages = $pages->show();
    	    $this->assign("pages",$pages);
    	    
    	    
    	    $this->assign("list",$list);
    	    $this->display();
    	}
    	
    	function add(){
    		
    	    if($_POST){
    	    	if(new_addslashes($_POST['name']) == "" || new_addslashes($_POST['view_name']) == ""){
    	    	    showMsg(lang("FILL_REQUIRE"),"goback",-1);
    	    	}
    	        
    	    	if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."group WHERE name = '".new_addslashes($_POST['name'])."'")>0){
    	    		showMsg(lang("CODE_NAME_EXISTS"),"goback",-1);
    	    	}
    	    	 
    	    	$actions = $GLOBALS['db']->getAll("SELECT id,moduleid FROM ".DB_PREFIX."actions WHERE is_del=0 ORDER BY moduleid ASC");
    	    	$action_arr = array();
    	    	foreach($actions as $v){
    	    		$action_arr[$v['id']] = $v['moduleid'];
    	    	}
    	    
    	    	$group = array();
    	    	$group['name'] = new_addslashes($_POST['name']);
    	    	$group['orderid'] = intval($_POST['orderid']);
    	    	if(lang($_POST['name']) != new_addslashes($_POST['view_name'])){
    	    		$lang = require ROOT_PATH.'/public/lang/sys_lang.php';
    	    		$lang[$_POST['name']] = new_addslashes($_POST['view_name']);
    	    		 
    	    		edit_lang_file(ROOT_PATH.'/public/lang/sys_lang.php',$lang);
    	    		 
    	    	}
    	    	$GLOBALS['db']->autoExecute(DB_PREFIX."group",$group,"INSERT");
    	    	
    	    	$id = $GLOBALS['db']->insert_id();
    	    	
    	    
    	    	/*全选*/
    	    	$module_access = array();
    	    	foreach($_POST['module_access'] as $v){
    	    		$module_access[] = $v;
    	    	}
    	    
    	    	$access = array();
    	    	foreach($_POST['group_access'] as $k=>$v){
    	    		if(!in_array($action_arr[$v],$module_access)){
    	    			$access[] = $v;
    	    		}
    	    	}
    	    
    	    	$module_str = implode(",",$module_access);
    	    	if($module_str){
    	    		$where = "AND moduleid in ($module_str) ";
    	    		$actions = $GLOBALS['db']->getAll("SELECT id FROM ".DB_PREFIX."actions WHERE is_del=0 $where ");
    	    		foreach($actions as $v){
    	    			$access[] = $v['id'];
    	    		}
    	    	}
    	    
    	    	$GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."group_access WHERE groupid = ".$id);
    	    	foreach($access as $v){
    	    		$GLOBALS['db']->autoExecute(DB_PREFIX."group_access",array("groupid"=>$id,"actionid"=>$v),"INSERT");
    	    	}
    	    	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$group['name']);
    	    	showMsg(lang("SUCCESS"),ADMIN_URL."/Group/index");
    	    
    	    
    	    }
    	    
    	    
    	    $modules = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."modules WHERE is_del = 0 AND pid>0 ORDER BY orderid ASC");
    	    foreach($modules as $k=>$v){
    	    	$modules[$k]['actions'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."actions WHERE moduleid = ".$v['id']." AND is_del = 0");
    	    }
    	    $this->assign("modules",$modules);
    	    
    	    $this->display();
    	}
    	
    	function edit(){
    	    
    	    if($_POST){
    	        $id = intval($_POST['id']);
    	    	if(new_addslashes($_POST['name']) == "" || new_addslashes($_POST['view_name']) == ""){
    	    	    showMsg(lang("FILL_REQUIRE"),"goback",-1);
    	    	}
    	        
    	        if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."group WHERE name = '".new_addslashes($_POST['name'])."' AND id != $id")>0){
    	        	showMsg(lang("GROUP_EXISTS"),"goback",-1);
    	        }
    	        
    	    	$actions = $GLOBALS['db']->getAll("SELECT id,moduleid FROM ".DB_PREFIX."actions WHERE is_del=0 ORDER BY moduleid ASC");
    	    	$action_arr = array();
    	    	foreach($actions as $v){
    	    		$action_arr[$v['id']] = $v['moduleid'];
    	    	}
    	    	
    	    	$group = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."group WHERE id = $id");
    	    	$group['name'] = new_addslashes($_POST['name']);
    	    	$group['orderid'] = intval($_POST['orderid']);
    	    	if(lang($_POST['name']) != new_addslashes($_POST['view_name'])){
    	    		$lang = require ROOT_PATH.'/public/lang/sys_lang.php';
    	    		$lang[$_POST['name']] = new_addslashes($_POST['view_name']);
    	    		
    	    		edit_lang_file(ROOT_PATH.'/public/lang/sys_lang.php',$lang);
    	    		
    	    	}
    	    	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$group['name']);
    	    	$GLOBALS['db']->autoExecute(DB_PREFIX."group",$group,"UPDATE","id=$id");
    	    	
    	    	/*全选*/
    	    	$module_access = array();
    	    	foreach($_POST['module_access'] as $v){
    	    		$module_access[] = $v;
    	    	}
    	    	
    	    	$access = array();
    	    	foreach($_POST['group_access'] as $k=>$v){
    	    		if(!in_array($action_arr[$v],$module_access)){
    	    			$access[] = $v;
    	    		}
    	    	}
    	    	
    	    	$module_str = implode(",",$module_access);
    	    	if($module_str){
    	    		$where = "AND moduleid in ($module_str) ";
    	    		$actions = $GLOBALS['db']->getAll("SELECT id FROM ".DB_PREFIX."actions WHERE is_del=0 $where ");
    	    		foreach($actions as $v){
    	    		    $access[] = $v['id'];
    	    		}
    	    	}
    	    	
    	    	$GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."group_access WHERE groupid = ".$id);
    	    	foreach($access as $v){
    	    		$GLOBALS['db']->autoExecute(DB_PREFIX."group_access",array("groupid"=>$id,"actionid"=>$v),"INSERT");
    	    	}
    	    	
    	    	showMsg(lang("SUCCESS"),ADMIN_URL."/Group/index");
    	    	
    	    	
    	    }
    	    

    	    $id = intval($_GET['id']);
    	    $this->assign("id",$id);
    	    $group = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."group WHERE id = $id");
    	    if(!$group){
    	        url_redirect(ADMIN_URL."/Group/index");
    	    }
    	    $this->assign("group",$group);
    	    	
    	    	
    	    $modules = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."modules WHERE is_del = 0 AND pid>0 ORDER BY orderid ASC");
    	    foreach($modules as $k=>$v){
    	    	$modules[$k]['actions'] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."actions WHERE moduleid = ".$v['id']." AND is_del = 0");
    	    }
    	    $this->assign("modules",$modules);
    	    	
    	    $group_access = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."group_access WHERE groupid = ".$id);
    	    $access_arr = array();
    	    foreach($group_access as $k=>$v){
    	        $access_arr[] = $v['actionid'];
    	    }
    	    
    	    $this->assign("access_arr",$access_arr);
    	    	
    	    
    		$this->display();
    	}
    	
    	function delete(){
    		$id = intval($_GET['id']);
    		$ids = $_POST['ids'];
    		if(isset($ids)){
    		    $id_arr = explode(",",$ids);
    		    foreach($id_arr as $k=>$v){
    		    	if($v==""){
    		    	    unset($id_arr[$k]);	
    		    	}
    		    }
    		    if(count($id_arr)==0){
    		    	showMsgajax(lang("SUCCESS"),1);
    		    }
    		    
    		    $admin_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."admin WHERE `groupid` in ($ids)");
    		    if($admin_num>0){
    		    	showMsgajax(lang("GROUP_HAS_ADMIN"),1);
    		    }
    		    
    		    $sql_del = "UPDATE ".DB_PREFIX."group SET is_del = 1 WHERE id in ($ids)";
        		$GLOBALS['db']->query($sql_del);
        		add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
        		showMsgajax(lang("SUCCESS"),1);
    		}else{
    		    $admin_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."admin WHERE `groupid` = $id");
    		    if($admin_num>0){
    		    	showMsg(lang("GROUP_HAS_ADMIN"),ADMIN_URL."/Group/index",-1);
    		    }
    		    $GLOBALS['db']->query("UPDATE ".DB_PREFIX."group SET is_del = 1 WHERE id = $id");
    		    add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
    		    showMsg(lang("SUCCESS"),ADMIN_URL."/Group/index");
    		}
    		
    	}
    	
    }
?>
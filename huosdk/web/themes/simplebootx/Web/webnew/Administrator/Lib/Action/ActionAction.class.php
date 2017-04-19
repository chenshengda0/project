<?php
    /*
     * showcms
     * 
     */
    class ActionAction extends AuthAction {
        function index(){
            
            
            $actions = $GLOBALS['db']->getAll("SELECT a.*,m.name as muname,m.class as muclass 
                                            FROM ".DB_PREFIX."actions a
                                            LEFT JOIN ".DB_PREFIX."modules m
                                            ON a.moduleid = m.id 
                                            WHERE a.is_del = 0 ORDER BY a.moduleid ASC,a.orderid ASC");
            
            
            
            
            $this->assign("actions",$actions);
            
            $this->display();
        }
        
        function add(){
        	
            if($_POST){
                if(new_addslashes($_POST['name']) == "" || new_addslashes($_POST['view_name']) == "" || new_addslashes($_POST['orderid']) == "" || new_addslashes($_POST['func']) == ""){
                	showMsg(lang("FILL_REQUIRE"),"goback",-1);
                }
                
            	$name = new_addslashes($_POST['name']);
            	if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."modules WHERE name = '$name'")>0){
            		showMsg(lang("MODULE_EXISTS"),"goback",-1);
            	}
            	
            	$action = array();
            	$action['name'] = new_addslashes($_POST['name']);
            	$action['func'] = new_addslashes($_POST['func']);
            	$action['orderid'] = intval($_POST['orderid']);
            	$action['moduleid'] = intval($_POST['moduleid']);
            	

            	if(lang($action['name']) != new_addslashes($_POST['view_name'])){
            		$lang = require ROOT_PATH.'/public/lang/sys_lang.php';
            		$lang[$action['name']] = new_addslashes($_POST['view_name']);
            	
            		edit_lang_file(ROOT_PATH.'/public/lang/sys_lang.php',$lang);
            	
            	}
            	$GLOBALS['db']->autoExecute(DB_PREFIX."actions",$action,"INSERT");
            	
            	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$action['name']);
            	
            	showMsg(lang("SUCCESS"),ADMIN_URL."/Action/index");
            	
            }
            
            
            $modules = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."modules WHERE is_del = 0 AND pid != 0 ORDER BY orderid ASC");
            $this->assign("modules",$modules);
            
            $this->display();
        }
        
        function edit(){

            if($_POST){
                $id = intval($_POST['id']);
                $module = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."modules WHERE id = $id AND is_del = 0");
                
            if(new_addslashes($_POST['name']) == "" || new_addslashes($_POST['view_name']) == "" || new_addslashes($_POST['orderid']) == "" || new_addslashes($_POST['func']) == ""){
                	showMsg(lang("FILL_REQUIRE"),"goback",-1);
                }
            
            	$name = new_addslashes($_POST['name']);
            	if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."actions WHERE name = '$name' AND id !=$id")>0){
            		showMsg(lang("ACTION_EXISTS"),"goback",-1);
            	}
            	 
            	
            	$action['name'] = new_addslashes($_POST['name']);
            	$action['func'] = new_addslashes($_POST['func']);
            	$action['orderid'] = intval($_POST['orderid']);
            	$action['moduleid'] = intval($_POST['moduleid']);
            	 
            
            	if(lang($action['name']) != new_addslashes($_POST['view_name'])){
            		$lang = require ROOT_PATH.'/public/lang/sys_lang.php';
            		$lang[$action['name']] = new_addslashes($_POST['view_name']);
            		 
            		edit_lang_file(ROOT_PATH.'/public/lang/sys_lang.php',$lang);
            		 
            	}
            	$GLOBALS['db']->autoExecute(DB_PREFIX."actions",$action,"UPDATE","id=$id");
            	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$action['name']);
            	showMsg(lang("SUCCESS"),ADMIN_URL."/Action/index");
            	 
            }
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $action = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."actions WHERE id = ".$id);
            if(!$action){
            	url_redirect(ADMIN_URL."/Action/index");
            }
            
            $this->assign("id",$id);
            $this->assign("action",$action);
           
            
            $modules = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."modules WHERE is_del = 0 AND pid != 0 ORDER BY orderid ASC");
            $this->assign("modules",$modules);
            
            $this->display();
            
        }


        function delete(){
        	$id = intval($_GET['id']);
        	$action = $GLOBALS->getRow("SELECT * FROM ".DB_PREFIX."actions WHERE id = $id");
        	$GLOBALS['db']->query("UPDATE ".DB_PREFIX."actions SET is_del = 1 WHERE id = $id");
        	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$action['name']);
        	showMsg(lang("SUCCESS"),ADMIN_URL."/Action/index");
        }
        
    }
?>
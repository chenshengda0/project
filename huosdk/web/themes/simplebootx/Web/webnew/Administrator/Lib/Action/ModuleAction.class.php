<?php
    /*
     * showcms
     * 
     */
    class ModuleAction extends AuthAction {
        function index(){
            
            $modules = $GLOBALS['db']->getAll("SELECT mo.id,mo.name,mo.is_show,mu.name as muname,mu.is_del as muis_del 
                                                FROM ".DB_PREFIX."modules mo
                                                LEFT JOIN ".DB_PREFIX."menus mu
                                                ON mo.menuid = mu.id 
                                                WHERE mo.is_del = 0 AND mo.pid = 0 ORDER BY mu.orderid ASC ,mo.orderid ASC");
            foreach($modules as $k=>$v){
                $modules_child = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."modules WHERE pid = ".$v['id']." AND is_del = 0 ORDER BY orderid ASC");
                $modules[$k]['child_modules'] = $modules_child;
            }
            
            
            $this->assign("modules",$modules);
            
            $this->display();
        }
        
        function change_show(){
        	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        	$module = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."modules WHERE id = $id AND is_del = 0");
        	if(!$module){
        		showMsgajax(lang("SUCCESS"),-1);
        	}else{
        	    if($module['is_show'] == 1){
        	    	$module['is_show'] = 0;
        	    }else{
        	        $module['is_show'] = 1;
        	    }
        	    $GLOBALS['db']->autoExecute(DB_PREFIX."modules",$module,"UPDATE","id=$id");
        	    add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".lang($module['name']));
        	    showMsgajax(lang("SUCCESS"),$module['is_show']);
        	}
        }
        
        function add(){
        	
            if($_POST){
                if(new_addslashes($_POST['name']) == "" || new_addslashes($_POST['view_name']) == "" || new_addslashes($_POST['orderid']) == ""){
                	showMsg(lang("FILL_REQUIRE"),"goback",-1);
                }
                
            	$name = new_addslashes($_POST['name']);
            	
            	$module = array();
            	$module['name'] = new_addslashes($_POST['name']);
            	$module['menuid'] = intval($_POST['menuid']);
            	$module['pid'] = intval($_POST['pid']);
            	$module['orderid'] = intval($_POST['orderid']);
            	$module['is_show'] = intval($_POST['is_show']);
            	$module['class'] = $module['pid']!=0 ? new_addslashes($_POST['class']) : "";
            	

            	if(lang($module['name']) != new_addslashes($_POST['view_name'])){
            		$lang = require ROOT_PATH.'/public/lang/sys_lang.php';
            		$lang[$module['name']] = new_addslashes($_POST['view_name']);
            	
            		edit_lang_file(ROOT_PATH.'/public/lang/sys_lang.php',$lang);
            	
            	}
            	$GLOBALS['db']->autoExecute(DB_PREFIX."modules",$module,"INSERT");
            	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".lang($module['name']));
            	showMsg(lang("SUCCESS"),ADMIN_URL."/Module/index");
            	
            }
            
            $menus = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."menus WHERE is_del = 0 ORDER BY orderid ASC");
            $this->assign("menus",$menus);
            
            $modules = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."modules WHERE is_del = 0 AND pid = 0 ORDER BY orderid ASC");
            $this->assign("modules",$modules);
            
            $this->display();
        }
        
        function edit(){

            if($_POST){
                $id = intval($_POST['id']);
                $module = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."modules WHERE id = $id AND is_del = 0");
                
            	if(new_addslashes($_POST['name']) == "" || new_addslashes($_POST['view_name']) == "" || new_addslashes($_POST['orderid']) == ""){
            		showMsg(lang("FILL_REQUIRE"),"goback",-1);
            	}
            
            	$name = new_addslashes($_POST['name']);
            	 
            	
            	$module['name'] = new_addslashes($_POST['name']);
            	$module['menuid'] = intval($_POST['menuid']);
            	$module['pid'] = intval($_POST['pid']);
            	$module['orderid'] = intval($_POST['orderid']);
            	$module['is_show'] = intval($_POST['is_show']);
            	$module['class'] = $module['pid']!=0 ? new_addslashes($_POST['class']) : "";
            	 
            
            	if(lang($module['name']) != new_addslashes($_POST['view_name'])){
            		$lang = require ROOT_PATH.'/public/lang/sys_lang.php';
            		$lang[$module['name']] = new_addslashes($_POST['view_name']);
            		 
            		edit_lang_file(ROOT_PATH.'/public/lang/sys_lang.php',$lang);
            		 
            	}
            	$GLOBALS['db']->autoExecute(DB_PREFIX."modules",$module,"UPDATE","id=$id");
            	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".lang($module['name']));
            	showMsg(lang("SUCCESS"),ADMIN_URL."/Module/index");
            	 
            }
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $module = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."modules WHERE id = ".$id);
            if(!$module){
            	url_redirect(ADMIN_URL."/Module/index");
            }
            
            $this->assign("id",$id);
            $this->assign("module",$module);
            
            $menus = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."menus WHERE is_del = 0 ORDER BY orderid ASC");
            $this->assign("menus",$menus);
            
            $modules = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."modules WHERE is_del = 0 AND pid = 0 ORDER BY orderid ASC");
            $this->assign("modules",$modules);
            
            $this->display();
            
        }


        function delete(){
        	$id = intval($_GET['id']);
        	$GLOBALS['db']->query("UPDATE ".DB_PREFIX."modules SET is_del = 1 WHERE id = $id");
        	add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
        	showMsg(lang("SUCCESS"),ADMIN_URL."/Module/index");
        }
        
    }
?>
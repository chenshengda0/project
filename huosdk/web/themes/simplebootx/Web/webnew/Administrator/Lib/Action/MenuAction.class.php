<?php
    /*
     *  qjcms
     * 
     */
    class MenuAction extends AuthAction {
        public function index(){
            $menus = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."menus WHERE is_del = 0 ORDER BY orderid ASC");
            
            $this->assign("menus",$menus);
            
            $this->display();
        }
        
        function change_show(){
        	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        	$menu = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."menus WHERE id = $id AND is_del = 0");
        	if(!$menu){
        		showMsgajax(lang("SUCCESS"),-1);
        	}else{
        	    if($menu['is_show'] == 1){
        	    	$menu['is_show'] = 0;
        	    }else{
        	        $menu['is_show'] = 1;
        	    }
        	    $GLOBALS['db']->autoExecute(DB_PREFIX."menus",$menu,"UPDATE","id=$id");
        	    add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".lang($menu['name']));
        	    showMsgajax(lang("SUCCESS"),$menu['is_show']);
        	}
        }
        
        function add(){
            
            if($_POST){
                if(new_addslashes($_POST['name']) == "" || new_addslashes($_POST['view_name']) == ""){
                	showMsg(lang("FILL_REQUIRE"),"goback",-1);
                }

                if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."menus WHERE name = '".new_addslashes($_POST['name'])."'")>0){
                	showMsg(lang("CODE_NAME_EXISTS"),"goback",-1);
                }
                
                $menu = array();
                $menu['name'] = trim(new_addslashes($_POST['name']));
                $menu['orderid'] = intval($_POST['orderid']);
                $menu['is_show'] = intval($_POST['is_show']);
                
                if(lang($_POST['name']) != new_addslashes($_POST['view_name'])){
                	$lang = require ROOT_PATH.'/public/lang/sys_lang.php';
                	$lang[$_POST['name']] = new_addslashes($_POST['view_name']);
                
                	edit_lang_file(ROOT_PATH.'/public/lang/sys_lang.php',$lang);
                
                }
                
                $GLOBALS['db']->autoExecute(DB_PREFIX."menus",$menu,"INSERT");
                add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".lang($menu['name']));
                showMsg(lang("SUCCESS"),ADMIN_URL."/Menu/index");
            }
            
        	$this->display();
        }
        
        function edit(){
        	
        	if($_POST){
    	        $id = intval($_POST['id']);
    	        $menu = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."menus WHERE id = $id AND is_del = 0");
    	        
    	    	if(new_addslashes($_POST['name']) == "" || new_addslashes($_POST['view_name']) == ""){
    	    	    showMsg(lang("FILL_REQUIRE"),"goback",-1);
    	    	}

        	    if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."menus WHERE name = '".new_addslashes($_POST['name'])."' AND id != $id")>0){
        	    	showMsg(lang("MENU_EXISTS"),"goback",-1);
        	    }
        	    
        	    $menu['name'] = trim(new_addslashes($_POST['name']));
                $menu['orderid'] = intval($_POST['orderid']);
                $menu['is_show'] = intval($_POST['is_show']);
                
                if(lang($_POST['name']) != new_addslashes($_POST['view_name'])){
                	$lang = require ROOT_PATH.'/public/lang/sys_lang.php';
                	$lang[$_POST['name']] = new_addslashes($_POST['view_name']);
                	 
                	edit_lang_file(ROOT_PATH.'/public/lang/sys_lang.php',$lang);
                	 
                }
                
                $GLOBALS['db']->autoExecute(DB_PREFIX."menus",$menu,"UPDATE","id=$id");
                add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".lang($menu['name']));
                showMsg(lang("SUCCESS"),ADMIN_URL."/Menu/index");
        	    
        	    
        	}

        	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        	$menu = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."menus WHERE id = $id AND is_del = 0");
        	if(!$menu){
        		url_redirect(ADMIN_URL."/Menu/index");
        	}

        	$this->assign("id",$id);
        	$this->assign("menu",$menu);
        	
        	$this->display();
        }
        
        function delete(){
            $id = intval($_GET['id']);
            $GLOBALS['db']->query("UPDATE ".DB_PREFIX."menus SET is_del = 1 WHERE id = $id");
            add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
            showMsg(lang("SUCCESS"),ADMIN_URL."/Menu/index");
        }
    }
?>
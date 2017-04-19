<?php
    /*
     *  qjcms
    *
    */
    class GroupTrashAction extends AuthAction {
    	public function index(){
    	    $page = $_GET['page']?intval($_GET['page']):1;
    	    $perpage = 20;
    	    $where = " WHERE is_del = 1 ";
    	    $orderby = "";
    	    $limit = " LIMIT ".($perpage*($page-1)).",$perpage";
    	    
    	    
    	    $sql = "SELECT * FROM ".DB_PREFIX."group $where $orderby $limit";
    	    $count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."group a $where ");
    	    
    	    $pages = new Page($count,$perpage);
    	    $pages = $pages->show();
    	    $this->assign("pages",$pages);
    	    
    	    $list = $GLOBALS['db']->getAll($sql);
    	    $this->assign("list",$list);
    	    $this->display();
    	}
    	
    	public function del_complate(){
    	    $ids = trim($_POST['ids']);
    	    $id_arr = explode(",",$ids);
    	    foreach($id_arr as $k=>$v){
    	    	if($v==""){
    	    		unset($id_arr[$k]);
    	    	}
    	    }
    	    if(count($id_arr)==0){
    	    	showMsgajax(lang("SUCCESS"),1);
    	    }
    	    $sql_del = "DELETE FROM ".DB_PREFIX."group WHERE is_del = 1 AND id in ($ids)";
    	    $GLOBALS['db']->query($sql_del);
    	    add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
    	    showMsgajax(lang("SUCCESS"),1);
    	}
    	
    	public function del_restore(){
    	    $ids = trim($_POST['ids']);
    	    $id_arr = explode(",",$ids);
    	    foreach($id_arr as $k=>$v){
    	    	if($v==""){
    	    		unset($id_arr[$k]);
    	    	}
    	    }
    	    if(count($id_arr)==0){
    	    	showMsgajax(lang("SUCCESS"),1);
    	    }
    	    $sql_del = "UPDATE ".DB_PREFIX."group SET is_del = 0 WHERE id in ($ids)";
    	    $GLOBALS['db']->query($sql_del);
    	    add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
    	    showMsgajax(lang("SUCCESS"),1);
    	}
    	
    }
?>
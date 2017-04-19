<?php
    /*
     *  qjcms
    *
    */
    class AdminTrashAction extends AuthAction {
    	public function index(){
    	    $page = $_GET['page']?intval($_GET['page']):1;
    	    $perpage = 20;
    	    $where = " WHERE a.is_del = 1 ";
    	    $orderby = "";
    	    $limit = " LIMIT ".($perpage*($page-1)).",$perpage";
    	    
    	    
    	    $sql = "SELECT a.id,a.username,a.create_time,a.login_time,a.login_ip,a.`groupid`,g.name,g.is_del
                        FROM ".DB_PREFIX."admin a
                        LEFT JOIN ".DB_PREFIX."group g
    	                ON a.`groupid` = g.id $where $orderby $limit";
    	    $count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."admin a $where ");
    	    
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
    	    if(in_array($GLOBALS['admin']['id'],$id_arr)){
    	    	showMsgajax(lang("NOTDELSELF"),-1);
    	    }
    	    if(count($id_arr)==0){
    	    	showMsgajax(lang("SUCCESS"),1);
    	    }
    	    $sql_del = "DELETE FROM ".DB_PREFIX."admin WHERE is_del = 1 AND id in ($ids)";
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
    	    $sql_del = "UPDATE ".DB_PREFIX."admin SET is_del = 0 WHERE id in ($ids)";
    	    $GLOBALS['db']->query($sql_del);
    	    add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
    	    showMsgajax(lang("SUCCESS"),1);
    	}
    	
    }
?>
<?php
class OrderAction extends AuthAction{
	
	function index(){
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$where = " WHERE is_del = 0 ";
		$orderby = " ORDER BY add_time DESC  ";
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		
		$name = isset($_GET['name'])?new_addslashes($_GET['name']):"";
		$status = isset($_GET['status'])?intval($_GET['status']):"-1";
		$this->assign("name",$name);
		$this->assign("status",$status);
		
		if($name != ""){
			$where .= " AND name like '%".$name."%' ";
		}
		if($status != "-1"){
			$where .= " AND status = '".$status."' ";
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."order ".$where. $orderby . $limit . " ";
		$count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."order ". $where ."");
		
		$list = $GLOBALS['db']->getAll($sql);
		
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		$this->assign("list",$list);
		 
		$this->display();
	}
	
	function edit() {
		if ($_POST) {
			$id = intval ( $_POST ['id'] );
			$order = $GLOBALS ['db']->getRow ( "SELECT * FROM " . DB_PREFIX . "order WHERE id = $id AND is_del = 0" );
		
			$order ['address'] = trim ( new_addslashes ( $_POST ['address'] ) ); //
			$order ['status'] = isset ( $_POST ['status'] ) ? intval ( $_POST ['status'] ) : 0;
			
			$GLOBALS ['db']->autoExecute ( DB_PREFIX . "order", $order, "UPDATE", "id=$id" );
			
			showMsg ( lang ( "SUCCESS" ), ADMIN_URL . "/Order/index" );
		}
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : 0;
		$order = $GLOBALS ['db']->getRow ( "SELECT * FROM " . DB_PREFIX . "order WHERE id = $id AND is_del = 0" );
		if (! $order) {
			url_redirect ( ADMIN_URL . "/Order/index" );
		}
		$this->assign ( "id", $id );
		$this->assign ( "order", $order );
		
		$this->display ();
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
			$sql_del = "UPDATE ".DB_PREFIX."order SET is_del = 1 WHERE id in ($ids)";
			$GLOBALS['db']->query($sql_del);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
			showMsgajax(lang("SUCCESS"),1);
		}else{
			$sql_del = "UPDATE ".DB_PREFIX."order SET is_del = 1 WHERE id = $id";
			$GLOBALS['db']->query($sql_del);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Order/index");
		}
	}
	
	
}
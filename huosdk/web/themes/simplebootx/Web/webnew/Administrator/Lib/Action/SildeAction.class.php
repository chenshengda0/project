<?php
class SildeAction extends AuthAction{
	
	function index(){
		$silde = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."silde ORDER BY type ASC,orderid ASC");
		
		$this->assign("silde",$silde);
		$this->display();
	}
	
	function add(){
		if($_POST){
			if($_POST['name'] == "" || $_POST['img'] == ""){
				showMsg(lang("FILL_REQUIRE"),"goback");
			}
			/* $reg = '/^(http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?/';
			if($_POST['link'] != ""){
				if(!preg_match($reg,trim(new_addslashes($_POST['link'])))){
					showMsg("链接格式不正确","goback");
				}
			} */
			
			$silde = array();
			$silde['name'] = trim(new_addslashes($_POST['name']));
			$silde['link'] = trim(new_addslashes($_POST['link']));
			$silde['img'] = trim(new_addslashes($_POST['img']));
			$silde['type'] = intval($_POST['type']);
			$silde['orderid'] = intval($_POST['orderid']);
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."silde",$silde,"INSERT");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$silde['name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Silde/index");
			
		}
		$this->display();
	}
	
	function edit(){
		if($_POST){
			if($_POST['name'] == "" || $_POST['img'] == ""){
				showMsg(lang("FILL_REQUIRE"),"goback");
			}
			/*$reg = '/^(http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?/';
			if(preg_match($reg,trim(new_addslashes($_POST['link'])))){
				showMsg("链接格式不正确","goback");
			}*/
			$id = intval($_POST['id']);
			$silde = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."silde WHERE id = ".$id);
			$silde['name'] = trim(new_addslashes($_POST['name']));
			$silde['link'] = trim(new_addslashes($_POST['link']));
			$silde['img'] = trim(new_addslashes($_POST['img']));
			$silde['type'] = intval($_POST['type']);
			$silde['orderid'] = intval($_POST['orderid']);
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."silde",$silde,"UPDATE","id = ".$id);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$silde['name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Silde/index");
				
		}
		$id = intval($_GET['id']);
		$silde = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."silde WHERE id = ".$id);
		if(!$silde){
			url_redirect(ADMIN_URL."/Silde/index");
		}
		$this->assign("silde",$silde);
		$this->assign("id",$id);
		$this->display();
	}
	
	function delete(){
		$id = intval($_GET['id']);
		$GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."silde WHERE id = $id");
		add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
		showMsg(lang("SUCCESS"),ADMIN_URL."/Silde/index");
	}
}
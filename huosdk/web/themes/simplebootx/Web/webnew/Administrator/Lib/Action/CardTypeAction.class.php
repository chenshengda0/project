<?php
class CardTypeAction extends AuthAction{
	
	function index(){
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$orderby = " ORDER BY orderid ASC ";
		$where = " WHERE 1 = 1 ";
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."card_type $where $orderby $limit");
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."card_type $where");
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		$this->assign("list",$list);
		
		$this->display();
	}
	
	function add(){
		if($_POST){
			if(new_addslashes($_POST['name']) == ""){
				showMsg(lang("FILL_REQUIRE"),"goback");
			}
			
			//查询重名
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."card_type WHERE name = '".$_POST['name']."'") > 0){
				showMsg("名称重名，请重新输入！");
			}
			
			$card_type = array();
			$card_type['name'] = trim(new_addslashes($_POST['name']));
			$card_type['orderid'] = intval(new_addslashes($_POST['orderid']));
			
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."card_type",$card_type,"INSERT");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$page['name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/CardType/index");
		}
		$this->display();
	}
	
	function edit(){
		if($_POST){
			if(new_addslashes($_POST['name']) == ""){
				showMsg(lang("FILL_REQUIRE"),"goback");
			}
			
			$id = intval($_POST['id']);
			//查询重名
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."card_type WHERE name = '".$_POST['name']."' AND id <> $id") > 0){
				showMsg("名称重名，请重新输入！");
			}
			$card_type = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."card_type WHERE id = ".$id);
			$card_type['name'] = trim(new_addslashes($_POST['name']));
			$card_type['orderid'] = intval(new_addslashes($_POST['orderid']));
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."card_type",$card_type,"UPDATE","id = ".$id);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$page['name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/CardType/index");
		}
		$id = intval($_GET['id']);
		$card_type = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."card_type WHERE id = ".$id);
		if(!$card_type){
			url_redirect(ADMIN_URL."/CardType/index");
		}
		$this->assign("card_type",$card_type);
		$this->assign("id",$id);
		$this->display();
	}
	
	
	function delete(){
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		//查询子集
		$arr = $GLOBALS['db']->getAll("SELECT id FROM ".DB_PREFIX."game_card WHERE  type = '".$id."'");
		if($arr){
			showMsg("该分类下存在游戏卡，无法删除","goback");
		}
		if($id){
			$sql_del = "DELETE FROM ".DB_PREFIX."card_type WHERE id = ".$id;
			$GLOBALS['db']->query($sql_del);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
			showMsg(lang("SUCCESS"),ADMIN_URL."/CardType/index");
		}
	}
	
}
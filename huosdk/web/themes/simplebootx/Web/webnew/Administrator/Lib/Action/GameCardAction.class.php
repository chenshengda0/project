<?php
class GameCardAction extends AuthAction{
	
	function index(){
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$orderby = " ORDER BY add_time DESC ";
		$where = " WHERE 1 = 1 ";
		$list = $GLOBALS['db']->getAll("SELECT c.*,t.name as tname FROM ".DB_PREFIX."game_card c LEFT JOIN ".DB_PREFIX."card_type t ON c.type = t.id $where $orderby $limit");
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."game_card $where");
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		$this->assign("list",$list);
		
		$this->display();
	}
	
	function add(){
		if($_POST){
			if(new_addslashes($_POST['name']) == "" || intval($_POST['type']) == 0){
				showMsg(lang("FILL_REQUIRE"),"goback");
			}
			
			$game_card = array();
			$game_card['name'] = trim(new_addslashes($_POST['name']));
			$game_card['type'] = intval(new_addslashes($_POST['type']));
			$game_card['game_id'] = intval(new_addslashes($_POST['game_id']));
			$game_card['end_time'] = $_POST['end_time'] ? strtotime(trim(new_addslashes($_POST['name']))) : 0;
			$game_card['content'] = trim(new_addslashes($_POST['content']));
			$game_card['thumb'] = trim(new_addslashes($_POST['thumb']));
			$game_card['seo_title'] = trim(new_addslashes($_POST['seo_title']));      //seo标题
			$game_card['seo_keywords'] = trim(new_addslashes($_POST['seo_keywords']));      //seo关键词
			$game_card['seo_description'] = trim(new_addslashes($_POST['seo_description']));      //seo描述
			$game_card['add_time'] = time();
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."game_card",$game_card,"INSERT");
			
			$card_id = $GLOBALS['db']->insert_id();
			
			$cards = trim(new_addslashes($_POST['cards']));
			$cards = explode(PHP_EOL,$cards);
			foreach($cards as $v){
				if($v != ""){
					$GLOBALS['db']->autoExecute(DB_PREFIX."card",array('card_id'=>$card_id,'card_idno'=>$v),"INSERT");
				}
			}
			$card_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."card WHERE card_id = $card_id");
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."game_card SET num = $card_num WHERE id = $card_id");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$game_card['name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/GameCard/index");
		}
		$card_type = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."card_type ORDER BY orderid ASC");
		
		$this->assign("card_type",$card_type);
		
		$games = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."game ORDER BY orderid ASC");
		
		$this->assign("games",$games);
		$this->display();
	}
	
	function edit(){
		if($_POST){
			if(new_addslashes($_POST['name']) == "" || intval($_POST['type']) == 0){
				showMsg(lang("FILL_REQUIRE"),"goback");
			}
			
			$id = intval($_POST['id']);
			$game_card = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."game_card WHERE id = ".$id);
			$game_card['name'] = trim(new_addslashes($_POST['name']));
			$game_card['type'] = intval(new_addslashes($_POST['type']));
			$game_card['game_id'] = intval(new_addslashes($_POST['game_id']));
			$game_card['end_time'] = $_POST['end_time'] ? strtotime(trim(new_addslashes($_POST['name']))) : 0;
			$game_card['content'] = trim(new_addslashes($_POST['content']));
			$game_card['thumb'] = trim(new_addslashes($_POST['thumb']));
			$game_card['seo_title'] = trim(new_addslashes($_POST['seo_title']));      //seo标题
			$game_card['seo_keywords'] = trim(new_addslashes($_POST['seo_keywords']));      //seo关键词
			$game_card['seo_description'] = trim(new_addslashes($_POST['seo_description']));    
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."game_card",$game_card,"UPDATE","id = ".$id);
			
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$page['page_name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/GameCard/index");
		}
		$id = intval($_GET['id']);
		$game_card = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."game_card WHERE id = ".$id);
		if(!$game_card){
			url_redirect(ADMIN_URL."/GameCard/index");
		}
		$card_type = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."card_type ORDER BY orderid ASC");
		
		$this->assign("card_type",$card_type);
		
		$games = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."game ORDER BY orderid ASC");
		
		$this->assign("games",$games);
		$this->assign("game_card",$game_card);
		$this->assign("id",$id);
		$this->display();
	}
	
	
	function delete(){
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		//查询子集
		$arr = $GLOBALS['db']->getAll("SELECT id FROM ".DB_PREFIX."card WHERE card_id = '".$id."'");
		if($arr){
			showMsg("该游戏卡下存在卡号，无法删除","goback");
		}
		if($id){
			$sql_del = "DELETE FROM ".DB_PREFIX."game_card WHERE id = ".$id;
			$GLOBALS['db']->query($sql_del);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
			showMsg(lang("SUCCESS"),ADMIN_URL."/GameCard/index");
		}
	}
	
	function card_list(){
		$id = intval($_GET['id']);
		$game_card = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."game_card WHERE id = ".$id);
		if(!$game_card){
			url_redirect(ADMIN_URL."/GameCard/index");
		}
		
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$orderby = " ORDER BY id DESC ";
		$where = " WHERE card_id = $id ";
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."card $where $orderby $limit");
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."card $where");
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		$this->assign("list",$list);
		$this->assign("card_id",$id);
		$this->display();
	}
	
	function add_card(){
		if($_POST){
			$card_id = intval($_POST['card_id']);
			$content = trim(new_addslashes($_POST['content']));
			$cards = explode(PHP_EOL,$content);
			foreach($cards as $v){
				if($v != ""){
					$GLOBALS['db']->autoExecute(DB_PREFIX."card",array('card_id'=>$card_id,'card_idno'=>$v),"INSERT");
				}
			}
			$card_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."card WHERE card_id = $card_id");
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."game_card SET num = $card_num WHERE id = $card_id");
			showMsg(lang("SUCCESS"),ADMIN_URL."/GameCard/index");
		}
		$id = intval($_GET['id']);
		$game_card = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."game_card WHERE id = ".$id);
		if(!$game_card){
			url_redirect(ADMIN_URL."/GameCard/index");
		}
		$this->assign("id",$id);
		$this->display();
	}
	
	function delete_card(){
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
     		$card_id = $GLOBALS['db']->getOne("SELECT card_id FROM ".DB_PREFIX."card WHERE id in ($ids)");
     		$sql_del = "DELETE FROM ".DB_PREFIX."card WHERE id in ($ids) AND userid = 0 AND username = ''";
     		echo $sql_del;
     		$GLOBALS['db']->query($sql_del);
     		$card_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."card WHERE card_id = $card_id");
     		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."game_card SET num = $card_num WHERE id = $card_id");
     		showMsgajax(lang("SUCCESS"),1);
     	}
	}
	
}
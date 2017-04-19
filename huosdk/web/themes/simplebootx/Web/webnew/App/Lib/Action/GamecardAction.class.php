<?php
class GamecardAction extends CommonAction{
	
	function index(){
		$game_id = intval($_GET['game_id']);
		$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		
		$orderby = " ORDER BY add_time DESC ";
		$where = " WHERE 1 = 1 ";
		if($game_id){
			$where .= " AND game_id = $game_id ";
		}
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."game_card $where");
		
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."game_card $where $orderby $limit");
		$time = strtotime(date("Y-m-d"));
		foreach ($list as $k => $v){
			$game = $GLOBALS['db']->getRow("SELECT name,logo FROM ".DB_PREFIX."game WHERE id = ".$v['game_id']);
			$list[$k]['gname'] = $game['name'];
			$list[$k]['logo'] = $game['logo'];
			$list[$k]['time'] = $v['end_time'] && $time < $v['end_time'] ? ceil(($v['end_time']-$time)/86400).'天后结束' : '';
		}
		$pages = new Page_1($count,$perpage);
		$pages = $pages->show();
		
		$this->assign("list",$list);
		$this->assign("count",$count);
		$this->assign("pages",$pages);
		$catid = 19;
		$cate = $GLOBALS['db']->getRow("SELECT title,keywords,description FROM ".DB_PREFIX."category WHERE catid = $catid");
		$seo = array();
		$seo['title'] = $cate['title'] ? $cate['title'] : "游戏礼包-".$GLOBALS['site']['site_name'];
		$seo['keywords'] = $cate['keywords'];
		$seo['description'] = $cate['description'];
		$this->assign("seo",$seo);
		
		$this->display();
	}
	
	function show(){
		$id = intval($_GET['id']);
		$card = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."game_card WHERE id = $id");
		if(!$card){
			url_redirect(url('Gamecard'));
		}
		$card['sy'] = round(($card['num']-$card['make_num'])/$card['num'],2)*100;
		$game = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."game WHERE id = ".$card['game_id']);
		
		$this->assign("card",$card);
		$this->assign("game",$game);
		$seo = array();
		$seo['title'] = $card['name']."-".$GLOBALS['site']['site_name'];
		$seo['keywords'] = $card['keywords'];
		$seo['description'] = $card['description'];
		$this->assign("seo",$seo);
		
		$this->display();
	}
	
	function make_card(){
		if(!$GLOBALS['user']){
			showMsgajax("",-2);
		}
		$user = $GLOBALS['user'];
		$id = intval($_POST['id']);
		$card = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."card WHERE card_id = $id AND userid = 0 ORDER BY id ASC");
		if(!$card){
			showMsgajax("该游戏卡号已放完",-1);
		}
		if($GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."card WHERE card_id = $id AND userid = ".$user['id'])){
			showMsgajax("已经领取该游戏卡号",-1);
		}
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."card SET userid = ".$user['id'].",username = '".$user['username']."',collection_time = ".time()." WHERE id = ".$card['id']);
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."game_card SET make_num = make_num + 1 WHERE id = $id");
		showMsgajax($card);
	}
	
	function del_card(){
		if(!$GLOBALS['user']){
			showMsgajax("",-2);
		}
		$user = $GLOBALS['user'];
		$id = intval($_POST['id']);
		$sql = "UPDATE ".DB_PREFIX."card SET is_del = 1 WHERE id = $id AND userid = ".$user['id'];
		$GLOBALS['db']->query($sql);
		showMsgajax("",1);
	}
}
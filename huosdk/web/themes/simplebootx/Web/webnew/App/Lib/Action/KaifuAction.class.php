<?php
class KaifuAction extends CommonAction{
	function index(){
		$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		
		$orderby = " ORDER BY open_time DESC ";
		$where = " WHERE 1 = 1 ";
		
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."kaifu $where");
		
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."kaifu $where $orderby $limit");
		foreach ($list as $k => $v){
			$list[$k]['gname'] = $GLOBALS['db']->getOne("SELECT name FROM ".DB_PREFIX."game WHERE id = ".$v['game_id']);
		}
		$pages = new Page_1($count,$perpage);
		$pages = $pages->show();
		
		$this->assign("list",$list);
		$this->assign("count",$count);
		$this->assign("pages",$pages);
		
		$catid = 16;
		$cate = $GLOBALS['db']->getRow("SELECT title,keywords,description FROM ".DB_PREFIX."category WHERE catid = $catid");
		$seo = array();
		$seo['title'] = $cate['title'] ? $cate['title'] : "游戏开服-".$GLOBALS['site']['site_name'];
		$seo['keywords'] = $cate['keywords'];
		$seo['description'] = $cate['description'];
		$this->assign("seo",$seo);
		$this->display();
	}
}
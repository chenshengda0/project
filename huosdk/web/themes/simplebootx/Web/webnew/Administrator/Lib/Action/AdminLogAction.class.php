<?php
class AdminLogAction extends AuthAction{
	
	function index(){
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$orderby = " ORDER BY create_time DESC ";
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."admin_log $where $orderby $limit");
		foreach ($list as $k => $v){
			$list[$k]['username'] = $GLOBALS['db']->getOne("SELECT username FROM ".DB_PREFIX."admin WHERE id = ".$v['admin_id']);
		}
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."admin_log $where");
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		
		$this->assign("list",$list);
		
		$this->display();
	}
}
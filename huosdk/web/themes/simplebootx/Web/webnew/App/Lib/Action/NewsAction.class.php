<?php
class NewsAction extends CommonAction{
	
	function index(){
		if(sysconf("URL_REWRITE") == 1){
			$catdir = trim(new_addslashes($_GET['catdir']));
			$cate = $GLOBALS['db']->getRow("SELECT catid,catname,en_name,title,keywords,description,parentid,list_template FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' AND catdir = '".$catdir."'");
		}else{
			$id = intval($_GET['id']);
			$cate = $GLOBALS['db']->getRow("SELECT catid,catname,en_name,title,keywords,description,parentid,list_template FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' AND catid = ".$id);
		}
		if(!$cate){
			url_redirect($_SERVER['HTTP_REFERER']);
		}
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = $GLOBALS['site']['news_page_num'] ? intval($GLOBALS['site']['news_page_num']) : 20;
		
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$child = $this->is_parent($cate['catid']);
		if($child){
			url_redirect(url('News#index','id='.$child[0]));
		}else{
			$where = " WHERE is_del = 0 AND catid = ".$cate['catid']." AND add_time < ".time();
			$orderby = " ORDER BY add_time DESC ";
			$count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."news ".$where);
			$sql = "SELECT id,title,thumb,clicks,keywords,description,add_time FROM ".DB_PREFIX."news ".$where.$orderby.$limit;
			$list = $GLOBALS['db']->getAll($sql);
			if($cate['parentid']){
				$parent = $GLOBALS['db']->getRow("SELECT catid,catname,en_name FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' AND catid = ".$cate['parentid']);
				$childs = $GLOBALS['db']->getAll("SELECT catid,catname FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' AND parentid = ".$cate['parentid']." ORDER BY orderid ASC");
				$this->assign("parent",$parent);
				$this->assign("childs",$childs);
			}
		}
		if(sysconf("URL_REWRITE") == 1){
			$pages = new Page_1($count,$perpage);
			$pages->url = url()."news/".$catdir."/page-";
		}else{
			$pages = new Page($count,$perpage);
		}
		$pages = $pages->show();
		$this->assign("pages",$pages);
		
		$this->assign("cate",$cate);
		$this->assign("catid",$cate['catid']);
		$this->assign("list",$list);
		
		$seo = array();
		$seo['title'] = $cate['title'] != "" ? $cate['title'] : $cate['catname'];
		$seo['keywords'] = $cate['keywords'];
		$seo['description'] = $cate['description'];
		$this->assign("seo",$seo);
		
		//点击量
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."category SET hits = hits + 1 WHERE catid = ".$cate['catid']);
		
		$this->display($cate['list_template']);
	}
	

	
	function show(){
		$id = intval($_GET['id']);
		$sql = "SELECT n.*,c.catname,c.catid,c.parentid,c.en_name FROM ".DB_PREFIX."news n
				LEFT JOIN ".DB_PREFIX."category c ON n.catid = c.catid
				WHERE n.is_del = 0 AND n.is_show = 1 AND c.is_del = 0 AND c.tablename = 'news' AND n.id = ".$id." AND n.add_time < ".time();
		$news = $GLOBALS['db']->getRow($sql);
		if(!$news){
			url_redirect($_SERVER['HTTP_REFERER']);
		}
	
		$this->assign("news",$news);
		$this->assign("catid",$news['catid']);
	
		if($news['parentid']){
			$parent = $GLOBALS['db']->getRow("SELECT catid,catname,en_name FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' AND catid = ".$news['parentid']);
			$childs = $GLOBALS['db']->getAll("SELECT catid,catname FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' AND parentid = ".$news['parentid']." ORDER BY orderid ASC");
			$this->assign("parent",$parent);
			$this->assign("childs",$childs);
		}
		
		//上一篇
		$prev = $GLOBALS['db']->getRow("SELECT id,title FROM ".DB_PREFIX."news WHERE is_del= 0 AND catid = ".$news['catid']." AND id > ".$news['id']." ORDER BY id ASC");
		//下一篇
		$next = $GLOBALS['db']->getRow("SELECT id,title FROM ".DB_PREFIX."news WHERE is_del= 0 AND catid = ".$news['catid']." AND id < ".$news['id']." ORDER BY id DESC");
		
		$this->assign("prev",$prev);
		$this->assign("next",$next);
		
		if($news['game_id']){
			$typeid = $GLOBALS['db']->getOne("SELECT typeid FROM ".DB_PREFIX."game WHERE id = ".$news['game_id']);
			$xgyx = $GLOBALS['db']->getAll("SELECT g.id,g.name,g.logo,g.down_num,t.name as typename FROM ".DB_PREFIX."game g LEFT JOIN ".DB_PREFIX."gametype t ON g.typeid = t.id WHERE g.is_del = 0 AND g.typeid = $typeid");
			$this->assign("xgyx",$xgyx);
		}
		
		$seo = array();
		$seo['title'] = $news['title']."-".$GLOBALS['site']['site_name'];
		$seo['keywords'] = $news['keywords'];
		$seo['description'] = $news['description'];
		$this->assign("seo",$seo);
		
		//点击量
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."news SET clicks = clicks + 1 WHERE id = ".$id);
		
		$this->display();
	}
	

	private function get_parent($par_url){
		return $GLOBALS['db']->getAll("SELECT catid,catname FROM ".DB_PREFIX."category WHERE is_del = 0 AND catid IN ($par_url) AND tablename = 'news'");
	}
	
	private function is_parent($catid){
		return $GLOBALS['db']->getCol("SELECT catid FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' AND parentid = ".$catid." ORDER BY orderid ASC");
	}
}
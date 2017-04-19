<?php
class PageAction extends CommonAction{
	function index(){
		if(sysconf("URL_REWRITE") == 1){
			$catdir = trim(new_addslashes($_GET['catdir']));
			$page = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."page WHERE catdir = '".$catdir."'");
		}else{
			$id = intval($_GET['id']);
			$page = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."page WHERE id = ".$id);
		}
		if(!$page){
			url_redirect(url());
		}
		if($page['parentid']){
			$parent = $GLOBALS['db']->getRow("SELECT id,page_name FROM ".DB_PREFIX."page WHERE id = ".$page['parentid']);
		}else{
			$parent = $page;
			//$page = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."page WHERE parentid = ".$parent['id']." ORDER BY orderid ASC");
		}
		//dump($parent);exit;
		$child = $GLOBALS['db']->getAll("SELECT id,page_name FROM ".DB_PREFIX."page WHERE parentid = ".$parent['id']." ORDER BY orderid ASC");
		//dump($child);
		$this->assign("page",$page);
		$this->assign("parent",$parent);
		$this->assign("child",$child);
		$this->assign("catid",$page['id']);
		$seo = array();
		$seo['title'] = $page['title'] != "" ? $page['title'] : $page['page_name']."-".$GLOBALS['site']['site_name'];
		$seo['keywords'] = $page['keywords'];
		$seo['description'] = $page['description'];
		$this->assign("seo",$seo);
		if($page['template']){
			$this->display($page['template']);
		}else{
			$this->display();
		}
		
	}
}
<?php 
class EmptyAction extends Action{
	function _empty(){
		echo MODULE_NAME.'<br>';
		echo ACTION_NAME.'<br>';
		//网站设置
		$config = $GLOBALS['db']->getAll("SELECT name,value,type FROM ".DB_PREFIX."site_conf");
		global $site;
		$site = array();
		foreach ($config as $k1 => $v1){
			if($v1['type'] == 2){
				$site[$v1['name']] = str_replace(PHP_EOL,"<br>", $v1['value']);
			}else{
				$site[$v1['name']] = $v1['value'];
			}
		
		}
		$this->assign("site",$site);
		 
		//头部导航
		$header_nav = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."navs WHERE type = 0 ORDER BY orderid ASC");
		foreach ($header_nav as $k => $v){
			$header_nav[$k]['url'] = get_nav($v);
		}
		$this->assign("header_nav",$header_nav);
		//底部
		$footer_nav = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."navs WHERE type = 1 ORDER BY orderid ASC");
		foreach ($footer_nav as $k => $v){
			$footer_nav[$k]['url'] = get_nav($v);
		}
		$this->assign("footer_nav",$footer_nav);
		//顶部导航
		$top_nav = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."navs WHERE type = 2 ORDER BY orderid ASC");
		foreach ($top_nav as $k => $v){
			$top_nav[$k]['url'] = get_nav($v);
		}
		$this->assign("top_nav",$top_nav);
	    $seo['title'] = $GLOBALS['site']['site_name']."404页面-".$GLOBALS['site']['site_name'];
	    $seo['keywords'] = "访问页面不存在";
	    $seo['description'] = $GLOBALS['site']['site_name']."404页面";
	    $this->assign("seo",$seo);
		header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
	    $this->display("Public:404");
	}
}
?>
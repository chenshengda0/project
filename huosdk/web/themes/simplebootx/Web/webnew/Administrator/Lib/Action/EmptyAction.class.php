<?php 
class EmptyAction extends AuthAction{
	function _empty(){
	    $seo['title'] = sysconf("SITE_NAME")."404页面-".sysconf("SITE_NAME");
	    $seo['keywords'] = "访问页面不存在";
	    $seo['description'] = sysconf("SITE_NAME")."404页面";
	    $this->assign("SEO",$seo);
		header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
		
		$this->display("Public:404");
	}
}

?>
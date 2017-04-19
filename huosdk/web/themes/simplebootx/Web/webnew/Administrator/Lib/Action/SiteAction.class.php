<?php
class SiteAction extends AuthAction{
	
	function index(){
		if($_POST){
			$sites = $_POST['site'];
			foreach ($sites as $k => $v){
				$v = str_replace("/Administrator/Tpl/Common/", "__TMPL__Common/",$v);
				$GLOBALS['db']->query("UPDATE ".DB_PREFIX."site_conf SET value = '".new_addslashes($v)."' WHERE id = ".$k);
			}
			
			add_admin_log($GLOBALS['action'][MODULE_NAME].":设置成功:".$site['name']);
			showMsg("设置成功",ADMIN_URL."/Site/index");
		}
		$site = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."site_conf ORDER BY id ASC");
		$this->assign("site",$site);
		$this->display();
	}
}
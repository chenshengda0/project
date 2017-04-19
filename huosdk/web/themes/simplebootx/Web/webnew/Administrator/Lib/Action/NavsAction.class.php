<?php
class NavsAction extends AuthAction{
	
	function index(){
		$type = isset($_GET['type']) ? intval($_GET['type']) : -1;
		if($type != -1){
			$where = " WHERE type = $type ";
		}
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."navs $where ORDER BY orderid ASC");
		foreach ($list as $k => $v){
			$list[$k]['url'] = get_nav($v);
		}
		$this->assign("list",$list);
		$this->assign("type",$type);
		$this->display();
	}
	
	
	function change_show(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$navs = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."navs WHERE id = $id");
		if(!$navs){
			showMsgajax(lang("SUCCESS"),-1);
		}else{
			if($navs['is_show'] == 1){
				$navs['is_show'] = 0;
			}else{
				$navs['is_show'] = 1;
			}
			$GLOBALS['db']->autoExecute(DB_PREFIX."navs",$navs,"UPDATE","id=$id");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$navs['name']);
			showMsgajax(lang("SUCCESS"),$navs['is_show']);
		}
	}
	
	function add(){
		if($_POST){
			if($_POST['name'] == ""){
				showMsg("菜单名不能为空","",-1);
			}
			if($_POST['nav_menu'] == ""){
				showMsg("导航不正确","",-1);
			}
			$navs = array();
			$nav_menu = trim(new_addslashes($_POST['nav_menu']));
			$menus = explode(",", $nav_menu);
			if($menus[0] == "nav"){
				$navs['guide'] = trim(new_addslashes($_POST['guide']));
			}else{
				$navs['guide'] = $menus[1];
			}
			$navs['module'] = $menus[0];
			$navs['name'] = trim(new_addslashes($_POST['name']));
			$navs['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
			$navs['is_blank'] = isset($_POST['is_blank']) ? intval($_POST['is_blank']) : 0;
			$navs['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 1; 
			$navs['orderid'] = isset($_POST['orderid']) ? intval($_POST['orderid']) : 0;
			 
			$GLOBALS['db']->autoExecute(DB_PREFIX."navs",$navs,"INSERT");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$navs['name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Navs/index");
		}
		$tree = $this->category_list();
		$this->assign("tree",$tree);
		
		$this->display();
	}
	
	
	function edit(){
		if($_POST){
			$id = intval($_POST['id']);
			if($_POST['name'] == ""){
				showMsg("菜单名不能为空","",-1);
			}
			if($_POST['nav_menu'] == ""){
				showMsg("导航不正确","",-1);
			}
				
			$navs = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."navs WHERE id = ".$id);
			$navs = array();
			$nav_menu = trim(new_addslashes($_POST['nav_menu']));
			$menus = explode(",", $nav_menu);
			if($menus[0] == "nav"){
				$navs['guide'] = trim(new_addslashes($_POST['guide']));
			}else{
				$navs['guide'] = $menus[1];
			}
			$navs['module'] = $menus[0];
			$navs['name'] = trim(new_addslashes($_POST['name']));
			$navs['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
			$navs['is_blank'] = isset($_POST['is_blank']) ? intval($_POST['is_blank']) : 0;
			$navs['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 1; 
			$navs['orderid'] = isset($_POST['orderid']) ? intval($_POST['orderid']) : 0;
		
			$GLOBALS['db']->autoExecute(DB_PREFIX."navs",$navs,"UPDATE","id = ".$id);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$navs['name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Navs/index");
		}
		$id = intval($_GET['id']);
		$navs = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."navs WHERE id = ".$id);
		if(!$navs){
			url_redirect(ADMIN_URL."/Navs/index");
		}
		if($navs['module'] != 'nav'){
			$tree = $this->category_list();
			$this->assign("tree",$tree);
		}
		$this->assign("nav",$navs);
		$this->assign("id",$id);
		$this->display();
	}
	
	function delete(){
		$id = intval($_GET['id']);
        $GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."navs WHERE id = $id");
        add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
        showMsg(lang("SUCCESS"),ADMIN_URL."/Navs/index");
	}
	
	
	/**
	 * 网站栏目
	 */
	private function category_list(){
		//单页面
		$page_list = $GLOBALS['db']->getAll("SELECT id as catid,page_name as catname,parentid FROM ".DB_PREFIX."page ORDER BY id ASC");
		$str = '<option value="page,0" disabled >单页面</option>';
		$str .= $this->tree_select('page', $page_list);
		
		//文章中心
		$str .= '<option value="news,0" disabled >文章中心</option>';
		$news_list = $GLOBALS['db']->getAll("SELECT catid,catname,parentid FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' ORDER BY orderid ASC");
		$str .= $this->tree_select('news', $news_list);
		
		$str .= '<option value="game,0" >游戏大全</option>';
		$str .= '<option value="gamecard,0" >礼包中心</option>';
		$str .= '<option value="kaifu,0" >开服开测</option>';
		return $str;
	}
	
	
	/**
	 * 树形下拉
	 * @param unknown $data
	 * @param number $pid
	 * @param number $num
	 * @return string
	 */
	private function tree_select($cat,$data,$pid = 0,$num = 0,$catid = 0){
		$str = "";
		$icon = '&nbsp;&nbsp;&nbsp;&nbsp;';
		foreach($data as $k=>$v){
			if($v['parentid'] == $pid){
				$str .= '<option value="'.$cat.','.$v['catid'].'"';
				if($catid == $v['catid'] ){
					$str .= ' selected ';
				}
				$str .= '>'.str_repeat($icon,$num).'├─ '.$v['catname'].'</option>';
				$str .= $this->tree_select($cat,$data,$v['catid'],$num+1,$catid);
			}
		}
		return $str;
	}
}
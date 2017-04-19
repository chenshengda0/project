<?php
class CustomAction extends AuthAction{
	
	function index(){
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$name = isset($_GET['name'])?new_addslashes($_GET['name']):"";
		$this->assign("name",$name);
		
		$where = " WHERE is_del = 1 ";
		if($name!=""){
			$where .= " AND name like '%$name%'";
			$orderby = " ORDER BY length(replace(`name`,'$name','')) ASC ";
		}
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."site_conf $where $orderby $limit");
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."site_conf $where");
		foreach ($list as $k => $v){
			$list[$k] = $this->getTypename($v);
		}
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		
		$this->assign("list",$list);
		
		$this->display();
	}
	
	
	function add(){
		if($_POST){
			if($_POST['title'] == ""){
				showMsg("显示名不能为空","",-1);
			}
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."site_conf WHERE title = '".$_POST['title']."'") > 0){
				showMsg("显示名已存在","",-1);
			}
			if($_POST['name'] == ""){
				showMsg("标签不能为空","",-1);
			}
			if(intval($_POST['type']) == 0){
				showMsg("选择类型","",-1);
			}
			if(!preg_match('/^(?!_)(?!.*?_$)[a-z0-9_]+$/',$_POST['name'])){
				showMsg("标签只能为小写字母,数字和下划线，不能以下划线开头和结尾","",-1);
			}
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."site_conf WHERE name = '".$_POST['name']."'") > 0){
				showMsg("标签已存在","",-1);
			}
				
				
			$site = array();
			$site['title'] = trim(new_addslashes($_POST['title']));
			$site['name'] = trim(new_addslashes($_POST['name']));
			$site['type'] = intval($_POST['type']);
			$site['value'] = trim(new_addslashes($_POST['value']));
			$site['tip'] = trim(new_addslashes($_POST['tip']));
			$site['is_del'] = 1;
			$GLOBALS['db']->autoExecute(DB_PREFIX."site_conf",$site,"INSERT");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$site['name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Custom/index");
		}
	
		$this->display();
	}
	
	
	function edit(){
		if($_POST){
			
			$id = intval($_POST['id']);
			$custom = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."site_conf WHERE id = $id AND is_del = 1");
			$custom['value'] = trim(new_addslashes($_POST['value']));
			$custom['tip'] = trim(new_addslashes($_POST['tip']));
			$GLOBALS['db']->autoExecute(DB_PREFIX."site_conf",$custom,"UPDATE","id=$id");
			add_admin_log("修改".$GLOBALS['action'][MODULE_NAME]."成功:".$custom['name']);
			showMsg("设置成功",ADMIN_URL."/Custom/index");
		}
		$id = intval($_GET['id']);
		$custom = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."site_conf WHERE id = $id AND is_del = 1");
		if(!$custom){
			url_redirect(ADMIN_URL."/Custom/index");
		}
		$custom = $this->getTypename($custom);
		$this->assign("custom",$custom);
		$this->assign("id",$id);
		$this->display();
	}
	
	function delete(){
		$id = intval($_GET['id']);
		$site = $GLOBALS['db']->getOne("SELECT is_del FROM ".DB_PREFIX."site_conf WHERE id = ".$id);
		if($site){
			$sql = "DELETE FROM ".DB_PREFIX."site_conf WHERE id = ".$id;
			if($GLOBALS['db']->query($sql)){
				add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
				showMsg("删除成功");
			}else{
				showMsg("删除失败",-1);
			}
		}else{
			showMsgajax("删除项不存在或是系统自带无法删除",-1);
		}
	}
	
	private function getTypename($custom){
		switch ($custom['type']){
			case 1:
				$custom['typename'] = '单行输入框';
				break;
			case 2:
				$custom['typename'] = '多行输入框';
				break;
			case 3:
				$custom['typename'] = '图片';
				break;
			case 4:
				$custom['typename'] = 'HTML';
				break;
			default:
				$custom['typename'] = '未知';
		}
		return $custom;
	}
}
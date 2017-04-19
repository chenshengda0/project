<?php
class FormAction extends AuthAction{
	
	function index(){
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$where = " WHERE 1 = 1 ";
		$orderby = " ORDER BY create_time DESC  ";
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		
		$name = isset($_GET['name'])?new_addslashes($_GET['name']):"";
		if($name != ""){
			$where .= " AND name like '%".$name."%' ";
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."form ".$where. $orderby . $limit . " ";
		$count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."form ". $where ."");
		
		$list = $GLOBALS['db']->getAll($sql);
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		$this->assign("list",$list);
		
		$this->display();
	}
	
	
	function lists(){
		$id = intval($_GET['id']);
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$form = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."form WHERE id = $id");
		if(!$form){
			showMsg("表单不存在");
		}
		$where = " WHERE fid = $id ";
		$orderby = " ORDER BY create_time DESC  ";
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
	
		$sql = "SELECT * FROM ".DB_PREFIX."form_data ".$where. $orderby . $limit . " ";
		$count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."form_data ". $where ."");
	
		$list = $GLOBALS['db']->getAll($sql);
		foreach ($list as $k => $v){
			$list[$k]['data'] = unserialize($v['content']);
		}
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		
		$this->assign("pages",$pages);
		$this->assign("form",$form);
		$this->assign("list",$list);
			
		$this->display();
	}
	
	//查看表单
	function form_edit(){
		$id = intval($_GET['id']);
		$fid = intval($_GET['fid']);
		$form = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."form WHERE id = $fid");
		$form_data = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."form_data WHERE id = $id AND fid = $fid");
		if(!$form_data){
			url_redirect($_SERVER['HTTP_REFERER']);
		}
		$form_data['data'] = unserialize($form_data['content']);
		$this->assign("form_data",$form_data);
		$this->assign("form",$form);
		$this->display();
	}
	
	//查看表单
	function form_delete(){
		$id = intval($_GET['id']);
		$fid = intval($_GET['fid']);
		$sql_del = "DELETE FROM ".DB_PREFIX."form_data WHERE id = $id AND fid = $fid";
		$GLOBALS['db']->query($sql_del);
		showMsg(lang("SUCCESS"),ADMIN_URL."/Form/lists?id=$fid");
	}
	
	function add(){
		if($_POST){
			if($_POST['name'] == ""){
				showMsg("输入表单名称");
			}
			$form = array();
			$form['name'] = trim(new_addslashes($_POST['name']));
			$form['msg'] = trim(new_addslashes($_POST['msg']));
			$form['seo_title'] = trim(new_addslashes($_POST['seo_title']));      //seo标题
			$form['seo_keywords'] = trim(new_addslashes($_POST['seo_keywords']));      //seo关键词
			$form['seo_description'] = trim(new_addslashes($_POST['seo_description']));      //seo描述
			$form['create_time'] = time();
			$form['display'] = isset($_POST['display']) ? intval($_POST['display']) : 1;
			$form['is_verify'] = isset($_POST['is_verify']) ? intval($_POST['is_verify']) : 0;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."form",$form,"INSERT");
				
			showMsg(lang("SUCCESS"),ADMIN_URL."/Form/index");
		}
		$this->display();
	}
	
	function edit(){
		if($_POST){
			if($_POST['name'] == ""){
				showMsg("输入表单名称");
			}
			$id = intval($_POST['id']);
			$form = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."form WHERE id = '$id'");
			$form['name'] = trim(new_addslashes($_POST['name']));
			$form['msg'] = trim(new_addslashes($_POST['msg']));
			$form['seo_title'] = trim(new_addslashes($_POST['seo_title']));      //seo标题
			$form['seo_keywords'] = trim(new_addslashes($_POST['seo_keywords']));      //seo关键词
			$form['seo_description'] = trim(new_addslashes($_POST['seo_description']));      //seo描述
			$form['display'] = isset($_POST['display']) ? intval($_POST['display']) : 1;
			$form['is_verify'] = isset($_POST['is_verify']) ? intval($_POST['is_verify']) : 0;
				
			$GLOBALS['db']->autoExecute(DB_PREFIX."form",$form,"UPDATE","id = $id");
		
			showMsg(lang("SUCCESS"),ADMIN_URL."/Form/index");
		}
		$id = intval($_GET['id']);
		$form = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."form WHERE id = '$id'");
		if(!$form){
			url_redirect(ADMIN_URL."/Form/index");
		}
		$this->assign("form",$form);
		$this->assign("id",$id);
		$this->display();
	}
	
	function field(){
		$id = intval($_GET['id']);
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."form_type WHERE fid = '$id'");
		$this->assign("list",$list);
		$this->assign("fid",$id);
		$this->display();
	}
	
	function add_field(){
		if($_POST){
			if($_POST['title'] == ""){
				showMsg("字段名称不能为空");
			}
			if($_POST['type'] == ""){
				showMsg("字段类型不能为空");
			}
			$form_type = array();
			$form_type['fid'] = intval($_POST['fid']);
			$form_type['type'] = trim(new_addslashes($_POST['type']));
			$form_type['title'] = trim(new_addslashes($_POST['title']));
			$form_type['msg'] = trim(new_addslashes($_POST['msg']));
			$form_type['options'] = trim(new_addslashes($_POST['options']));
			$form_type['defaultvalue'] = trim(new_addslashes($_POST['defaultvalue']));
			$form_type['orderid'] = intval($_POST['orderid']);
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."form_type",$form_type,"INSERT");
			showMsg(lang("SUCCESS"),ADMIN_URL."/Form/field?id=".$form_type['fid']);
		}
		$fid = intval($_GET['fid']);
		$this->assign("fid",$fid);
		$this->display();
	}
	
	function edit_field(){
		if($_POST){
			if($_POST['title'] == ""){
				showMsg("字段名称不能为空");
			}
			if($_POST['type'] == ""){
				showMsg("字段类型不能为空");
			}
			$id = intval($_POST['id']);
			$fid = intval($_POST['fid']);
			$form_type = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."form_type WHERE fid = $fid AND id = $id");
			$form_type['fid'] = intval($_POST['fid']);
			$form_type['type'] = trim(new_addslashes($_POST['type']));
			$form_type['title'] = trim(new_addslashes($_POST['title']));
			$form_type['msg'] = trim(new_addslashes($_POST['msg']));
			$form_type['options'] = trim(new_addslashes($_POST['options']));
			$form_type['defaultvalue'] = trim(new_addslashes($_POST['defaultvalue']));
			$form_type['orderid'] = intval($_POST['orderid']);
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."form_type",$form_type,"UPDATE","id=$id AND fid = $fid");
			showMsg(lang("SUCCESS"),ADMIN_URL."/Form/field?id=$fid");
		}
		$id = intval($_GET['id']);
		$fid = intval($_GET['fid']);
		$form_type = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."form_type WHERE fid = $fid AND id = $id");
		if(!$form_type){
			url_redirect(ADMIN_URL."/Form/field?fid=$fid");
		}
		$this->assign("field",$form_type);
		$this->assign("id",$id);
		$this->assign("fid",$fid);
		$this->display();
	}
	
	function delete_field(){
		$id = intval($_GET['id']);
		$fid = intval($_GET['fid']);
		$sql_del = "DELETE FROM ".DB_PREFIX."form_type WHERE id = $id AND fid = $fid";
		$GLOBALS['db']->query($sql_del);
		showMsg(lang("SUCCESS"),ADMIN_URL."/Form/field?id=$fid");
	}
	
	function delete(){
		$id = intval($_GET['id']);
		$ids = $_POST['ids'];
		if(isset($ids)){
			$id_arr = explode(",",$ids);
			foreach($id_arr as $k=>$v){
				if($v==""){
					unset($id_arr[$k]);
				}
			}
	
			if(count($id_arr)==0){
				showMsgajax(lang("SUCCESS"),1);
			}
			$sql_del = "DELETE FROM ".DB_PREFIX."form WHERE id in ($ids)";
			$GLOBALS['db']->query($sql_del);
			showMsgajax(lang("SUCCESS"),1);
		}else{
			$sql_del = "DELETE FROM ".DB_PREFIX."form WHERE id = $id";
			$GLOBALS['db']->query($sql_del);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Form/index");
		}
	}
}
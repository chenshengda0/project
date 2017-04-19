<?php
class PageAction extends AuthAction{
	
	function index(){
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$orderby = " ORDER BY add_time DESC ";
		$where = " WHERE 1 = 1 ";
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."page $where $orderby $limit");
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."page $where");
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		$this->assign("list",$list);
		
		$this->display();
	}
	
	function add(){
		if($_POST){
			if(new_addslashes($_POST['page_name']) == "" || new_addslashes($_POST['catdir']) == ""){
				showMsg(lang("FILL_REQUIRE"),"goback");
			}
			//英文检测
			$isTure = preg_match('/^[a-z]+$/',$_POST['catdir']);
			if(!$isTure){
				showMsg("别名格式不正确，只能为小写英文");
			}
			
			//查询重名
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."page WHERE page_name = '".$_POST['page_name']."'") > 0){
				showMsg("名称重名，请重新输入！");
			}
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."page WHERE catdir = '".$_POST['catdir']."'") > 0){
				showMsg("别名重名，请重新输入！");
			}
			$page = array();
			$page['page_name'] = trim(new_addslashes($_POST['page_name']));
			$page['catdir'] = trim(new_addslashes($_POST['catdir']));
			$page['en_name'] = trim(new_addslashes($_POST['en_name']));
			$page['banner'] = trim(new_addslashes($_POST['banner']));
			$page['parentid'] = isset($_POST['parentid']) ? intval($_POST['parentid']) : 0;
			$page['seo_title'] = trim(new_addslashes($_POST['seo_title']));
			$page['keywords'] = trim(new_addslashes($_POST['keywords']));
			$page['description'] = trim(new_addslashes($_POST['description']));
			$page['content'] = trim(new_addslashes($_POST['content']));
			$page['add_time'] = time();
			$page['template'] = trim(new_addslashes($_POST['template']));
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."page",$page,"INSERT");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$page['page_name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Page/index");
		}
		$data = $GLOBALS['db']->getAll("SELECT id,parentid,page_name FROM ".DB_PREFIX."page");
		$tree = $this->tree_select($data);
		$this->assign("tree",$tree);
		$this->display();
	}
	
	function edit(){
		if($_POST){
			if(new_addslashes($_POST['page_name']) == "" || new_addslashes($_POST['catdir']) == ""){
				showMsg(lang("FILL_REQUIRE"),"goback");
			}
			//英文检测
			$isTure = preg_match('/^[a-z]+$/',$_POST['catdir']);
			if(!$isTure){
				showMsg("别名格式不正确，只能为小写英文");
			}
			$id = intval($_POST['id']);	
			//查询重名
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."page WHERE page_name = '".$_POST['page_name']."' AND id <> ".$id) > 0){
				showMsg("名称重名，请重新输入！");
			}
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."page WHERE catdir = '".$_POST['catdir']."' AND id <> ".$id) > 0){
				showMsg("别名重名，请重新输入！");
			}
			$page = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."page WHERE id = ".$id);
			$page['page_name'] = trim(new_addslashes($_POST['page_name']));
			$page['en_name'] = trim(new_addslashes($_POST['en_name']));
			$page['banner'] = trim(new_addslashes($_POST['banner']));
			$page['catdir'] = trim(new_addslashes($_POST['catdir']));
			$page['parentid'] = isset($_POST['parentid']) ? intval($_POST['parentid']) : 0;
			$page['seo_title'] = trim(new_addslashes($_POST['seo_title']));
			$page['keywords'] = trim(new_addslashes($_POST['keywords']));
			$page['description'] = trim(new_addslashes($_POST['description']));
			$page['content'] = trim(new_addslashes($_POST['content']));
			$page['template'] = trim(new_addslashes($_POST['template']));
				
			$GLOBALS['db']->autoExecute(DB_PREFIX."page",$page,"UPDATE","id = ".$id);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$page['page_name']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Page/index");
		}
		$id = intval($_GET['id']);
		$page = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."page WHERE id = ".$id);
		if(!$page){
			url_redirect(ADMIN_URL."/Page/index");
		}
		$data = $GLOBALS['db']->getAll("SELECT id,parentid,page_name FROM ".DB_PREFIX."page");
		$tree = $this->tree_select($data,0,0,$page['parentid']);
		$this->assign("page",$page);
		$this->assign("tree",$tree);
		$this->assign("id",$id);
		$this->display();
	}
	
	
	function delete(){
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		//查询子集
		$arr = $GLOBALS['db']->getAll("SELECT id FROM ".DB_PREFIX."page WHERE parentid = '".$id."'");
		if($arr){
			showMsg("请先删除子页面！","goback");
		}
		if($id){
			$sql_del = "DELETE FROM ".DB_PREFIX."page WHERE id = ".$id;
			$GLOBALS['db']->query($sql_del);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Page/index");
		}
	}
	
	/**
	 * 树形下拉
	 * @param unknown $data
	 * @param number $pid
	 * @param number $num
	 * @return string
	 */
	private function tree_select($data,$pid = 0,$num = 0,$catid = 0){
		$str = "";
		$icon = '&nbsp;&nbsp;&nbsp;&nbsp;';
		foreach($data as $k=>$v){
			if($v['parentid'] == $pid){
				$str .= '<option value="'.$v['id'].'"';
				if($catid == $v['id']){
					$str .= ' selected ';
				}
				$str .= '>'.str_repeat($icon,$num).'├─ '.$v['page_name'].'</option>';
				$str .= $this->tree_select($data,$v['id'],$num+1);
			}
		}
		return $str;
	}
	
}
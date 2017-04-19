<?php
class NewsAction extends AuthAction{
	
	function index(){
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$title = isset($_GET['title'])?new_addslashes($_GET['title']):"";
		$this->assign("title",$title);
		$catid = isset($_GET['catid'])?intval($_GET['catid']):0;
		$this->assign("catid",$catid);
		$orderby = " ORDER BY add_time DESC ";
		$where = " WHERE is_del = 0 ";
		if($name!=""){
			$where .= " AND title like '%$title%'";
			$orderby = " ORDER BY length(replace(`title`,'$title','')) ASC ";
		}
		if($catid > 0){
			$where .= " AND catid = ".$catid;
		}
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."news $where $orderby $limit");
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."news $where");
		foreach($list as $k => $v){
			$list[$k]['catname'] = $GLOBALS['db']->getOne("SELECT catname FROM ".DB_PREFIX."category WHERE catid = ".$v['catid']);
		}
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		
		$data = $GLOBALS['db']->getAll("SELECT catid,catname,parentid,hits,orderid,add_time FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' ORDER BY orderid ASC");
		
		$tree = $this->tree_select($data,0,0,$catid);
		
		$push = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."pushtype WHERE module = 'news' ORDER BY sort ASC");
		$this->assign("push",$push);
		
		$this->assign("list",$list);
		$this->assign("tree",$tree);
		
		$this->display();
	}
	
	function add(){
		if($_POST){
			//类别
			if(intval($_POST['catid']) == 0){
				showMsg('类别不能为空','goback');
			}
			//标题
			if(new_addslashes($_POST['title']) == ''){
				showMsg('标题不能为空','goback');
			}
		
		
			$news['catid'] = isset($_POST['catid']) ? intval($_POST['catid']) : 0;    //类别
			if($news['catid'] > 0){
				if($this->is_child($news['catid'])){
					showMsg('不能为父级分类','goback');
				}
			}
			$news['title'] = trim(new_addslashes($_POST['title']));      //标题
			$news['thumb'] = trim(new_addslashes($_POST['thumb']));      //缩略图
			$news['keywords'] = trim(new_addslashes($_POST['keywords']));      //关键词
			$news['tags'] = trim(new_addslashes($_POST['tags']));      //关键词
			$news['description'] = trim(new_addslashes($_POST['description']));      //描述
			$news['content'] = trim(new_addslashes($_POST['content']));      //内容
			$news['seo_title'] = trim(new_addslashes($_POST['seo_title']));      //seo标题
			$news['seo_keywords'] = trim(new_addslashes($_POST['seo_keywords']));      //seo关键词
			$news['seo_description'] = trim(new_addslashes($_POST['seo_description']));      //seo描述
			$news['game_id'] = intval(new_addslashes($_POST['game_id']));      //跳转url
			$news['size'] = floatval(new_addslashes($_POST['size']));      //跳转url
			$news['add_time'] = time();    //添加时间
			$news['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 1;    //是否显示
			
			if($news['description'] == ""){
				$news['description'] = mb_substr(strip_tags($news['content'],0,120));
			}
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."news",$news,"INSERT");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$news['title']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/News/index");
		}
		$data = $GLOBALS['db']->getAll("SELECT catid,catname,parentid FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' ORDER BY `orderid` ASC");
		
		$tree = $this->tree_select($data);
		
		$games = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."game WHERE is_del = 0 ORDER BY orderid ASC");
		
		$this->assign("games",$games);
		$this->assign("tree",$tree);
		$this->display();
	}
	
	
	function edit(){
		if($_POST){
			$id = intval($_POST['id']);
			$news = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."news WHERE id = $id AND is_del = 0");
			//类别
			if(intval($_POST['catid']) == 0){
				showMsg('类别不能为空','goback');
			}
			//标题
			if(new_addslashes($_POST['title']) == ''){
				showMsg('标题不能为空','goback');
			}
	
				
			$news['catid'] = isset($_POST['catid']) ? intval($_POST['catid']) : 0;    //类别
			if($news['catid'] > 0){
				if($this->is_child($news['catid'])){
					showMsg('不能为父级分类','goback');
				}
			}
			$news['title'] = trim(new_addslashes($_POST['title']));      //标题
			$news['thumb'] = trim(new_addslashes($_POST['thumb']));      //缩略图
			$news['keywords'] = trim(new_addslashes($_POST['keywords']));      //关键词
			$news['tags'] = trim(new_addslashes($_POST['tags']));      //关键词
			$news['description'] = trim(new_addslashes($_POST['description']));      //描述
			$news['content'] = trim(new_addslashes($_POST['content']));      //内容
			$news['seo_title'] = trim(new_addslashes($_POST['seo_title']));      //seo标题
			$news['seo_keywords'] = trim(new_addslashes($_POST['seo_keywords']));      //seo关键词
			$news['seo_description'] = trim(new_addslashes($_POST['seo_description']));      //seo描述
			$news['skip_url'] = trim(new_addslashes($_POST['skip_url']));      //跳转url
			$news['game_id'] = intval(new_addslashes($_POST['game_id']));      //跳转url
			$news['size'] = floatval(new_addslashes($_POST['size']));      //跳转url
			$news['is_show'] = isset($_POST['is_show']) ? intval($_POST['is_show']) : 1;    //是否显示
			if($news['description'] == ""){
				$content = cutstr_html($news['content']);
				$news['description'] = mb_substr($content,0,120,'utf-8');
			}
			$GLOBALS['db']->autoExecute(DB_PREFIX."news",$news,"UPDATE","id=$id");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$news['title']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/News/index");
		}
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$news = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."news WHERE id = $id AND is_del = 0");
		if(!$news){
			url_redirect(ADMIN_URL."/News/index");
		}
		$this->assign("id",$id);
		$this->assign("news",$news);
		$data = $GLOBALS['db']->getAll("SELECT catid,catname,parentid FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' ORDER BY `orderid` ASC");
		
		$tree = $this->tree_select($data,0,0,$news['catid']);
		$games = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."game WHERE is_del = 0 ORDER BY orderid ASC");
		
		$this->assign("games",$games);
		$this->assign("tree",$tree);
		$this->display();
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
			$sql_del = "UPDATE ".DB_PREFIX."news SET is_del = 1 WHERE id in ($ids)";
			$GLOBALS['db']->query($sql_del);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
			showMsgajax(lang("SUCCESS"),1);
		}else{
			$sql_del = "UPDATE ".DB_PREFIX."news SET is_del = 1 WHERE id = $id";
			$GLOBALS['db']->query($sql_del);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
			showMsg(lang("SUCCESS"),ADMIN_URL."/News/index");
		}
	}
	
	
	public function trashstore_index(){
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 20;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$title = isset($_GET['title'])?new_addslashes($_GET['title']):"";
		$this->assign("title",$title);
		$catid = isset($_GET['catid'])?intval($_GET['catid']):0;
		$this->assign("catid",$catid);
		$orderby = " ORDER BY add_time DESC ";
		$where = " WHERE is_del = 1 ";
		if($name!=""){
			$where .= " AND title like '%$title%'";
			$orderby = " ORDER BY length(replace(`title`,'$title','')) ASC ";
		}
		if($catid > 0){
			$where .= " AND catid = ".$catid;
		}
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."news $where $orderby $limit");
		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."news $where");
		foreach($list as $k => $v){
			$list[$k]['catname'] = $GLOBALS['db']->getOne("SELECT catname FROM ".DB_PREFIX."category WHERE catid = ".$v['catid']);
		}
		$pages = new Page($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
		
		$data = $GLOBALS['db']->getAll("SELECT catid,catname,parentid,hits,orderid,add_time FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'news' ORDER BY orderid ASC");
		
		$tree = $this->tree_select($data,0,0,$catid);
		
		$this->assign("list",$list);
		$this->assign("tree",$tree);
		
		$this->display();
	}
	 
	function trashstore_del_restore(){
		$ids = trim($_POST['ids']);
		$id_arr = explode(",",$ids);
		foreach($id_arr as $k=>$v){
			if($v==""){
				unset($id_arr[$k]);
			}
		}
		if(count($id_arr)==0){
			showMsgajax(lang("SUCCESS"),1);
		}
		$sql_del = "UPDATE ".DB_PREFIX."news SET is_del = 0 WHERE id in ($ids)";
		$GLOBALS['db']->query($sql_del);
		add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
		showMsgajax(lang("SUCCESS"),1);
	}
	 
	function trashstore_del_complate(){
		$ids = trim($_POST['ids']);
		$id_arr = explode(",",$ids);
		foreach($id_arr as $k=>$v){
			if($v==""){
				unset($id_arr[$k]);
			}
		}
		if(count($id_arr)==0){
			showMsgajax(lang("SUCCESS"),1);
		}
		$sql_del = "DELETE FROM ".DB_PREFIX."news WHERE is_del = 1 AND id in ($ids)";
		$GLOBALS['db']->query($sql_del);
		add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
		showMsgajax(lang("SUCCESS"),1);
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
				$str .= '<option value="'.$v['catid'].'"';
				if($catid == $v['catid']){
					$str .= ' selected ';
				}
				if($this->is_parent($data,$v['catid'])){
					$str .= ' disabled="disabled" ';
				}
				$str .= '>'.str_repeat($icon,$num).'├─ '.$v['catname'].'</option>';
				$str .= $this->tree_select($data,$v['catid'],$num+1,$catid);
			}
		}
		return $str;
	}
	/**
	 * 判断id是否是父类
	 * @param unknown $data
	 * @param unknown $id
	 * @return boolean
	 */
	private function is_parent($data,$id){
		foreach ($data as $k => $v){
			if($id == $v['parentid']){
				return true;
			}
		}
		return false;
	}
	
	private function is_child($catid){
		return $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."category WHERE parentid = ".$catid);
	}
}
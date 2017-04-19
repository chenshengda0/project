<?php
class CategoryAction extends AuthAction{
	
	function index(){
		$list = $GLOBALS['db']->getAll("SELECT catid,catname,parentid,hits,orderid,tablename,add_time FROM ".DB_PREFIX."category WHERE is_del = 0  ORDER BY orderid ASC");
		
		$tree = $this->table_tree($list);
		$this->assign("tree",$tree);
		$this->display();
	}
	
	/*
	 *
	* 栏目排序
	*
	*/
	function listorder(){
		if($_POST['dosubmit']){
			$listorders = isset($_POST['listorders']) ? $_POST['listorders'] : "";
			foreach($listorders as $k=>$v){
				$GLOBALS['db']->query("UPDATE ".DB_PREFIX."category SET orderid = $v WHERE catid = $k  ");
			}
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME]);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Category/index");
		}
	}
	
	function add(){
		if($_POST){
			 if(new_addslashes($_POST['catname']) == "" || new_addslashes($_POST['catdir']) == ""){
		    	showMsg(lang("FILL_REQUIRE"),"goback");
		    }
		    //英文检测
		    $isTure = preg_match('/^[a-z]+$/',$_POST['catdir']);
		    if(!$isTure){
		    	showMsg("英文目录格式不正确，只能为小写英文");
		    }
		    
		    //查询重名
		    if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."category WHERE catname = '".$_POST['catname']."'") > 0){
		    	showMsg("栏目名称重名，请重新输入！");
		    }
		    if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."category WHERE catdir = '".$_POST['catdir']."'") > 0){
		    	showMsg("英文目录重名，请重新输入！");
		    }
		    $category = array();
		    $category['catname'] = trim(new_addslashes($_POST['catname']));
		    $category['en_name'] = trim(new_addslashes($_POST['en_name']));
		    $category['parentid'] = intval($_POST['parentid']);
		    $category['catdir'] = trim(new_addslashes($_POST['catdir']));
		    $category['title'] = trim(new_addslashes($_POST['title']));
		    $category['keywords'] = trim(new_addslashes($_POST['keywords']));
		    $category['description'] = trim(new_addslashes($_POST['description']));
		    $category['list_template'] = $_POST['list_template'] ? trim(new_addslashes($_POST['list_template'])) : 'index';
		    $category['show_template'] = $_POST['show_template'] ? trim(new_addslashes($_POST['show_template'])) : 'show';
		    $category['wap_list_template'] = $_POST['wap_list_template'] ? trim(new_addslashes($_POST['wap_list_template'])) : 'index';
		    $category['wap_show_template'] = $_POST['wap_show_template'] ? trim(new_addslashes($_POST['wap_show_template'])) : 'show';
		    $category['orderid'] = intval($_POST['orderid']);
		    $category['add_time'] = time();
		    $category['tablename'] = trim(new_addslashes($_POST['tablename']));
		    if($category['parentid'] > 0){
		    	$par_urls = $GLOBALS['db']->getOne("SELECT par_urls FROM ".DB_PREFIX."category WHERE catid = ".$category['parentid']);
		    	if($par_urls){
		    		$category['par_urls'] = $par_urls.",".$category['parentid'];
		    	}else{
		    		$category['par_urls'] = $category['parentid'];
		    	}
		    }
		    $GLOBALS['db']->autoExecute(DB_PREFIX."category",$category,"INSERT");
		    add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$category['catname']);
		    showMsg(lang("SUCCESS"),ADMIN_URL."/Category/index");
		    
		}
		$list = $GLOBALS['db']->getAll("SELECT catid,catname,parentid,hits,orderid FROM ".DB_PREFIX."category WHERE is_del = 0  ORDER BY orderid ASC");
		$tree = $this->tree_select_add($list);
		$this->assign("tree",$tree);
		$this->display();
	}
	
	function edit(){
		if($_POST){
			if(new_addslashes($_POST['catname']) == "" || new_addslashes($_POST['catdir']) == ""){
				showMsg(lang("FILL_REQUIRE"),"goback");
			}
			//英文检测
			$isTure = preg_match('/^[a-z]+$/',$_POST['catdir']);
			if(!$isTure){
				showMsg("英文目录格式不正确，只能为小写英文");
			}
		
			$catid = intval($_POST['catid']);
			//查询重名
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."category WHERE catname = '".$_POST['catname']."' AND catid <> ".$catid) > 0){
				showMsg("栏目名称重名，请重新输入！");
			}
			if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."category WHERE catdir = '".$_POST['catdir']."' AND catid <> ".$catid) > 0){
				showMsg("英文目录重名，请重新输入！");
			}
			$category = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."category WHERE is_del = 0 AND catid = ".$catid." ");
			$category['catname'] = trim(new_addslashes($_POST['catname']));
			$category['en_name'] = trim(new_addslashes($_POST['en_name']));
			$category['parentid'] = intval($_POST['parentid']);
			$category['catdir'] = trim(new_addslashes($_POST['catdir']));
		    $category['title'] = trim(new_addslashes($_POST['title']));
		    $category['keywords'] = trim(new_addslashes($_POST['keywords']));
		    $category['description'] = trim(new_addslashes($_POST['description']));
		    $category['list_template'] = $_POST['list_template'] ? trim(new_addslashes($_POST['list_template'])) : 'index';
		    $category['show_template'] = $_POST['show_template'] ? trim(new_addslashes($_POST['show_template'])) : 'show';
		    $category['wap_list_template'] = $_POST['wap_list_template'] ? trim(new_addslashes($_POST['wap_list_template'])) : 'index';
		    $category['wap_show_template'] = $_POST['wap_show_template'] ? trim(new_addslashes($_POST['wap_show_template'])) : 'show';
		    $category['orderid'] = intval($_POST['orderid']);
		    $category['tablename'] = trim(new_addslashes($_POST['tablename']));
			if($category['parentid'] > 0){
				$par_urls = $GLOBALS['db']->getOne("SELECT par_urls FROM ".DB_PREFIX."category WHERE catid = ".$category['parentid']);
				if($par_urls){
					$category['par_urls'] = $par_urls.",".$category['parentid'];
				}else{
					$category['par_urls'] = $category['parentid'];
				}
			}
			$GLOBALS['db']->autoExecute(DB_PREFIX."category",$category,"UPDATE","catid = ".$catid." ");
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$category['catname']);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Category/index");
		
		}
		$catid = intval($_GET['catid']);
		$category = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."category WHERE is_del = 0  AND catid = ".$catid);
		if(!$category){
			url_redirect(ADMIN_URL."/Category/index");
		}
		$list = $GLOBALS['db']->getAll("SELECT catid,catname,parentid,hits,orderid FROM ".DB_PREFIX."category WHERE is_del = 0  ORDER BY orderid ASC");
		$tree = $this->tree_select_add($list,0,0,$category['parentid']);
		$this->assign("tree",$tree);
		$this->assign("category",$category);
		$this->assign("catid",$catid);
		$this->display();
	}
	
	function delete(){
		$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
		
		//查询子集
		$arr = $GLOBALS['db']->getAll("SELECT catid FROM ".DB_PREFIX."category WHERE parentid = '".$catid."' ");
		if($arr){
			showMsg("请先删除子栏目！","goback");
		}
		if($catid){
			$sql_del = "UPDATE ".DB_PREFIX."category SET is_del = 1 WHERE catid = ".$catid." ";
			$GLOBALS['db']->query($sql_del);
			add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$catid);
			showMsg(lang("SUCCESS"),ADMIN_URL."/Category/index");
		}
	}
	
	
	/*
	 *
	* 栏目回收站首页
	*
	*/
	function trashindex(){
		//查询栏目
		$category_arr = $GLOBALS['db']->getAll("SELECT orderid,catid,catname,hits,parentid FROM ".DB_PREFIX."category WHERE is_del = 1  ORDER BY orderid ASC");
		$this->assign("list",$category_arr);
		$this->display();
	}
	
	/*
	 *
	* 栏目回收站恢复
	*
	*/
	function trashrestore(){
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
		$sql_del = "UPDATE ".DB_PREFIX."category SET is_del = 0 WHERE catid in ($ids) ";
		$GLOBALS['db']->query($sql_del);
		add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
		showMsgajax(lang("SUCCESS"),1);
	}
	
	/*
	 *
	* 栏目回收站彻底删除
	*
	*/
	function trashdelete(){
		$ids = trim($_POST['ids']);
		$id_arr = explode(",",$ids);
		foreach($id_arr as $k=>$v){
			if($v==""){
				unset($id_arr[$k]);
			}
		}
		if(count($id_arr)==0){
			showMsgajax(lang("DELETE_SUCCESS"),1);
		}
		
		$sql_del = "DELETE FROM ".DB_PREFIX."category WHERE is_del = 1 AND catid in ($ids)";
		$GLOBALS['db']->query($sql_del);
		add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
		showMsgajax(lang("DELETE_SUCCESS"),1);
	}
	
	/**
	 * 树形table
	 * @param unknown $data
	 * @param number $pid
	 * @param number $num
	 * @return string
	 */
	private function table_tree($data,$pid = 0,$num = 0){
		$str = "";
		$icon = '&nbsp;&nbsp;&nbsp;&nbsp;';
		foreach ($data as $k => $v){
			if($v['parentid'] == $pid){
				$str .= '<tr class="tr" data-parentid="'.$pid.' data-catid="'.$v['catid'].'">';
				$str .= '<td class="td_center">';
				$str .= '<input type="text" style="width:30px; height:30px;text-align:center; border:1px solid #919191;" value="'.$v['orderid'].'" name="listorders['.$v['catid'].']" />';
				$str .= '</td>';
				$str .= '<td class="td_center">'.$v['catid'].'</td>';
				if($v['parentid'] == 0){
					$str .= '<td class="td_left">'.$v['catname'].'</td>';
				}elseif($v['parentid'] == $pid){
					$str .= '<td class="td_left">'.str_repeat($icon,$num).'├─ '.$v['catname'].'</td>';
				}
				$str .= '<td class="td_center">'.$v['hits'].'</td>';
				$str .= '<td class="td_center">'.$GLOBALS['action']['module'][$v['tablename']].'</td>';
				$str .= '<td class="td_center">'.date('Y-m-d H:i:s',$v['add_time']).'</td>';
				$str .= '<td class="td_center">';
				$str .= '<a href="__ADMIN_URL__/Category/edit/?catid='.$v['catid'].'">'.lang("EDIT").'</a>&nbsp;';
				$str .= '<a href="__ADMIN_URL__/'.$v['tablename'].'/index">'.lang("栏目管理").'</a>&nbsp;';
				$str .= '<a href="__ADMIN_URL__/Category/delete/?catid='.$v['catid'].'" onclick="return confirm(\''.lang("SURE_TO_DEL").'\')">'.lang("DELETE").'</a>&nbsp;';
				$str .= '</td></tr>';
				$str .= $this->table_tree($data,$v['catid'],$num+1);
			}
		}
		return $str;
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
				$str .= '>'.str_repeat($icon,$num).'├─ '.$v['catname'].'</option>';
				$str .= $this->tree_select($data,$v['catid'],$num+1,$catid);
			}
		}
		return $str;
	}
	
	/**
	 * 树形下拉
	 * @param unknown $data
	 * @param number $pid
	 * @param number $num
	 * @return string
	 */
	private function tree_select_add($data,$pid = 0,$num = 0,$catid = 0){
		$str = "";
		$icon = '&nbsp;&nbsp;&nbsp;&nbsp;';
		foreach($data as $k=>$v){
			if($v['parentid'] == $pid){
				$str .= '<option value="'.$v['catid'].'"';
				if($catid == $v['catid']){
					$str .= ' selected ';
				}
				if($this->is_news($v['catid'])){
					$str .= ' disabled="disabled" ';
				}
				$str .= '>'.str_repeat($icon,$num).'├─ '.$v['catname'].'</option>';
				$str .= $this->tree_select($data,$v['catid'],$num+1,$catid);
			}
		}
		return $str;
	}
	
//递归得到菜单结构
private function tree($table,$p_id=0) {
	$tree = array();
	foreach($table as $row){
		if($row['parentid']==$p_id){
			$tmp = $this->tree($table,$row['id']);
			if($tmp){
				$row['children']=$tmp;
			}
			$tree[]=$row;
		}
	}
	return $tree;
}
	
	
	private function is_news($catid){
		return $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."news WHERE catid = ".$catid." AND is_del = 0");
	}
	
}
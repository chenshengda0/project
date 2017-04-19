<?php

/**
 * 后台首页
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class IndexController extends AdminbaseController {
	function _initialize() {
		parent::_initialize();
		$this->initMenu();
	}
	//后台框架首页
	public function index() {
		$this->assign("SUBMENU_CONFIG", D("Common/Menu")->menu_json());
		$this->display();
	}
	 
	/**
	 * 首页
	 *
	 * return void
	 */
	public function content() {
		$list = D("Common/Menu")->menu_navbar();
		$mainmenustr = "";
		foreach ($list as $key=>$val) {
			//if ($key) {
			$mainmenustr .=" <a href='javascript:void(0)' id='sb1' class=\"easyui-splitbutton\"";
			$mainmenustr .=" data-options=\"iconCls:'icon-redo'\" onclick='prefresh(".$val['id'].")'>";
			$mainmenustr .=$val['name']."</a>";
			//}
			$this->assign('mainmenu_str',$mainmenustr);
		}
		$this->display();
	}

	/**
	 * 菜单选项
	 *
	 * return void
	 */
	public function mainmenu(){		
		$type = I('type');
		if(empty($type)){
			exit;
		}
		$list = D("Common/Menu")->get_tree($type);
		
		foreach ($list as $key => $val) {
			if ($key) {
				$tablestr .= "<div iconCls=\"icon-picture-edit\" title=".$val['name']."  style='overflow:auto;padding:10px;'>";
				foreach ($val['items'] as $k => $v) {
					$trstr = "<a class=\"tree-title\" href='#' iconCls=\"icon-rainbow\"";
					$trstr .= " onclick='addTab(\"".$v['name']."\",\"".$v['url']."\")'>".$v['name']."</a><br>";
				
					$tablestr .= $trstr;
				}
				$tablestr .= "</div>";
			}
		}
		$this->assign('menu_str',$tablestr);
		$this->display();
	}

	/**
	 *
	 * 退出登录
	 */
	//退出登录
	public function loginout(){
		session('aid',null);	//注销 uid ，account
		session('account',null);
		 
		$str = "退出登录成功";
		$str = iconv("UTF-8", "GB2312//IGNORE", $str);
		echo '<script language="javascript">';
		echo '	alert("'.$str.'");';
		echo "	window.parent.location.href='" . $this->urlpre."Index/index" . "'";
		echo '</script>';
	}
}


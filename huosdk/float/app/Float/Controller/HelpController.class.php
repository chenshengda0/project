<?php
/**
* 礼包管理中心
*
* @author
*/
namespace Float\Controller;
use Common\Controller\AdminbaseController;

class HelpController extends AdminbaseController {

	/**
	 * 帮助中心
	 */
	public function index(){
	    $data = sp_get_help();
	    $this->assign('data',$data);
	    $this->assign('title', '客服中心');
		$this -> display();
	}
}
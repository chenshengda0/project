<?php
/**
* 礼包管理中心
*
* @author
*/
namespace Admin\Controller;
use Common\Controller\AdminbaseController;

class HelpController extends AdminbaseController {

	/**
	 * 帮助中心
	 */
	public function index(){
		$this->redirect('Float/Help/index');
	    $data = sp_get_help();
	    $this->assign('data',$data);
		$this -> display();
	}
}
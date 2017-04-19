<?php 
/*
**游戏管理
**/

namespace Web\Controller;
use Common\Controller\AdminbaseController;

class AboutusController extends AdminbaseController {
    /**
	 * 关于我们
	 * 
	 * return void
	 */
    public function index(){
    	$aboutus = M('webAboutus');
		
    	$items = $aboutus->order("id DESC")->select();
    	$this->assign('items',$items);
        $this->display();
    }
    /**
     * 编辑关于我们
     *
     * return void
     */
    public function editAbout() {
    	$id = I("id");
    	$aboutus = M('webAboutus');
    	$list = $aboutus->where("id=".$id)->select();
    	$this->assign('list',$list[0]);
    	$this->display();
    }

    public function editAbout_post() {
    	$id = I("id");
    	$aboutus = M('webAboutus');
    	$data['content'] = htmlspecialchars_decode(I("content"));
    
    	$rs = $aboutus->where("id=".$id)->save($data);
    	if ($rs) {
    		//$this->insertLog($_SESSION['user']['username'],3, 'AboutusAction.class.php', 'editAbout', time(),"编辑了:".$data['title']);
    		$this->success("编辑成功。");
    		exit();
    	} else {
    		$this->error("编辑失败。");
    		exit();
    	}
    }
}
	
?>
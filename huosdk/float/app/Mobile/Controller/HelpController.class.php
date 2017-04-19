<?php

/**
 * 礼包管理中心
 * 
 * @author
 *
 */
namespace Mobile\Controller;
use Common\Controller\MobilebaseController;

class HelpController extends MobilebaseController
{
    
    /**
     * 帮助中心
     */
    public function index() {
        $data = sp_get_help();
        $this->assign('data', $data);
        $this->assign('title', '客服中心');
// 		$contact = sp_get_help();
// 		$this->assign('qq',$contact['qq']);
// 		$this->assign('mobile',$contact['tel']);
        $this->display();
    }
}
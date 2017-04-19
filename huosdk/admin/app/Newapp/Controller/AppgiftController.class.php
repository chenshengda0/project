<?php

/**
 * 礼包管理中心
 * 
 * @author
 *
 */
namespace Newapp\Controller;

use Common\Controller\AdminbaseController;

class AppgiftController extends AdminbaseController
{
    protected $game_model;
    function _initialize() {
        parent::_initialize();
    }
    
    /**
     * 礼包列表
     */
    public function index() {
        $this->redirect('Sdk/Gift/giftList');
        exit();
    }
}
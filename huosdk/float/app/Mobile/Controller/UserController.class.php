<?php

/**
 * UserController.class.php UTF-8
 * 用户中心控制
 * @date: 2016年7月8日下午2:54:46
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@1tsdk.com>
 * @version : 1.0
 */
namespace Mobile\Controller;
use Common\Controller\MobilebaseController;

class UserController extends MobilebaseController
{
    function _initialize() {
        parent::_initialize();
    }
    
    // 浮点用户信息首页
    public function index() {
        $this->assign("title", '个人中心');
        $this->display();
    }
    
    // 浮点详细信息
    public function setting() {
        $this->assign("title", '个人信息');
        $this->display();
    }
    
    // 详细信息修改
    public function setting_post() {
    }
}
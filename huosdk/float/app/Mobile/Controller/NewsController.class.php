<?php

/**
 * NewsController.class.php UTF-8
 * 浮点控制中心
 * @date: 2016年7月8日下午2:54:46
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : 1.0
 */
namespace Mobile\Controller;

use Common\Controller\HomebaseController;

class NewsController extends HomebaseController
{
    function _initialize() {
        parent::_initialize();
    }
    
    // 新闻详情页
    public function index() {
        $post_id = I('get.id/d', 0);
        if ($post_id <= 0) {
            $this->error("参数错误");
        }
        
        $postdata = M('posts')->where(array(
            'id' => $post_id 
        ))->find();
        if (!empty($postdata)) {
            $gamename = M('game')->where(array(
                'id' => $postdata['app_id'] 
            ))->getField('name');
        } else {
            $this->error("无此文章");
        }
        
        $this->assign("gamename", $gamename);
        $this->assign("postdata", $postdata);
        $this->display();
    }
}
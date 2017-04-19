<?php

/**
 * Ads.php UTF-8
 * 广告处理controller
 * @date: 2016年8月18日下午9:45:36
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : api 2.0
 */
namespace app\api\controller\v1;

use app\common\controller\Base;
use think\Db;
class Ads extends Base
{
    function _initialize() {
        parent::_initialize();
    }
    function index() {
        $rdata = array(
            'count' => 0, 
            'list' => array() 
        );
        
        $data = $this->getSlide('app_slide');
        $cnt = count($data);
        if ($cnt > 0) {
            $rdata['count'] = $cnt;
            $rdata['list'] = $data;
        }
        return hs_api_responce(200, '获取轮播图成功', $rdata);
    }
    
    // 获取轮播图
    function getSlide($slide, $limit = 4, $order = "listorder ASC") {
        $sc_model = DB::name("SlideCat");
        $join = [
            [
                'slide s', 
                's.slide_cid=sc.cid', 
                'LEFT' 
            ] 
        ];
        
        if ($order == '') {
            $order = "listorder ASC";
        }
        if ($limit == 0) {
            $limit = 5;
        }
        $map['cat_idname'] = $slide;
        $map['slide_status'] = 2;
        
        $field = array(
            'slide_name' => 'name', 
            'app_id' => 'gameid', 
            "CONCAT('" . $this->staticurl . "',slide_pic)" => 'image',
            'slide_url' => 'url', 
            'slide_des' => 'des',
            'slide_content' => 'content' 
        );
        $data = $sc_model->alias('sc')->field($field)->join($join)->where($map)->order($order)->limit('0,' . $limit)->select();
        return $data;
    }
}

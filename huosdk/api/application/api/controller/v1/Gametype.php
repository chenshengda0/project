<?php
/**
* Gametype.php UTF-8
* 游戏分类处理接口
* @date: 2016年8月18日下午9:46:57
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@huosdk.com>
* @version: api 2.0
*/
namespace app\api\controller\v1;
use app\common\controller\Base;
use app\api\model\Gametype as GametypeModel;

class Gametype extends Base
{
    function _initialize(){
        parent::_initialize();
    }
    /*
     * 标识	   请求类型	   生成路由规则	   对应操作方法（默认）
     * index	GET	   game	        index
     * 
     * 请求游戏列表
     */
    public function index(){
        $gt_model = new GametypeModel();
        $map['status'] = 2;
        $field = "gt.id typeid, gt.name typename, CONCAT('".$this->staticurl."',IFNULL(image,'')) icon";
        $rdata = $gt_model->alias('gt')->field($field)->where($map)->select();
        
        return hs_api_responce(200, '请求成功', $rdata);
    }
}

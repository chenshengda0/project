<?php

/**
 * Startup.php UTF-8
 * 后台管理员控制
 * @date: 2016年8月17日下午2:09:45
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : huosdk 7.0
 */
namespace app\api\model;

use think\Model;
use think\Request;

class AppVersion extends Model
{
    protected $name = 'game_version';
    
    /*
     * 获取最新版本信息
     */
    public function getNewver($app_id) {
        $map = array(
            'status' => 2, 
            'app_id' => $app_id 
        );
        return $this->where(array(
            'status' => 2, 
            'app_id' => $app_id 
        ))->order('id', 'DESC')->find();
    }
    
    // birthday读取器
    protected function getUpdatetimeAttr($uptime) {
        return date('Y-m-d', $uptime);
    }
    
    // birthday读取器
    protected function getPackageurlAttr($packageurl) {
        $checkExpressions = '|^http://|';
        
        if (false == preg_match($checkExpressions, $packageurl)) {
            return "http://down.huosdk.com".$packageurl;
        }
        return $packageurl;
    }
}
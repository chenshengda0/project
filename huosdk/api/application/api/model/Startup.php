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

class Startup extends Model
{
    protected $name = 'startupLog';
    
    // 设置数据表主键
    protected $pk = 'id';
    protected $auto = [
        'ip', 
        'create_time' 
    ];
    // protected $auto = [
    // 'create_time'
    // ];
    
    // 设置当前数据表的字段信息
    protected $field = [
        'id' => 'int', 
        'ver_id' => 'int', 
        'mem_id' => 'int', 
        'app_id' => 'int', 
        'agent_id' => 'int', 
        'create_time' => 'int',
        'ip' => 'varchar',
    ];
    protected function setIpAttr() {
        return Request::instance()->ip();
    }
    
    protected function setCreateTimeAttr() {
        return time();
    }
}
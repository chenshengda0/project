<?php
/**
* User.php UTF-8
* 后台管理员控制
* @date: 2016年8月17日下午2:09:45
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@huosdk.com>
* @version: huosdk 7.0
*/
namespace app\api\model;

use think\Model;

class User extends Model
{
    // birthday读取器
    protected function getBirthdayAttr($birthday)
    {
        return date('Y-m-d', $birthday);
    }
}
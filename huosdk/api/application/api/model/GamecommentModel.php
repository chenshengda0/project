<?php
/**
* Game.php UTF-8
* 游戏类
* @date: 2016年8月17日下午2:09:45
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@huosdk.com>
* @version: huosdk 7.0
*/
namespace app\api\model;

use think\Model;

class GamecommentModel extends Model
{
    protected $name = 'game_comments';
    
    protected function getCreatetimeAttr($create_time) {
        return date('Y-m-d H:i:s', $create_time);
    }
    
}
<?php

/**
 * Game.php UTF-8
 * 游戏处理接口
 * @date: 2016年8月18日下午9:46:57
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : api 2.0
 */
namespace app\api\controller\v1;

use app\common\controller\Base;
use think\Db;
use app\api\model\Game as GameModel;

class Cdkey extends Base
{
    function _initialize() {
        parent::_initialize();
    }
    
    /*
     * 请求礼包列表
     */
    public function index() {
        $map['gameid'] = $this->request->get('gameid/d', 0); // 游戏ID
        $map['page'] = $this->request->get('page/d', 1); // 页
        $map['offset'] = $this->request->get('offset/d', 0); // 每页请求数量，默认为10
        $map['usergift'] = 0; // 每页请求数量，默认为10
        
        $rdata = $this->getGiftlist($map);
        if ($rdata['count'] <= 0) {
            return hs_api_responce('404', '无激活码');
        }
        return hs_api_responce(200, '请求成功', $rdata);
    }
    public function gameindex() {
        $gameid = $this->request->get('gameid/d', 0); // 游戏ID
        if ($gameid <= 0) {
            return hs_api_responce('400', '参数错误');
        }
        $this->index();
    }
    
    /*
     * 玩家已领取礼包列表
     */
    public function userindex() {
        $this->isUserLogin();
        
        $map['gameid'] = $this->request->get('gameid/d', 0); // 游戏ID
        $map['page'] = $this->request->get('page/d', 1); // 页
        $map['offset'] = $this->request->get('offset/d', 0); // 每页请求数量，默认为10
        $map['usergift'] = 1;
        $rdata = $this->getGiftlist($map);
        if ($rdata['count'] <= 0) {
            return hs_api_responce('404', '无礼包');
        }
        return hs_api_responce(200, '请求成功', $rdata);
    }
    
    /*
     * 获取游戏详情
     */
    public function read() {
        $giftid = $this->request->get('giftid/d', 0); // 游戏ID
        if ($giftid <= 0) {
            return hs_api_responce('404', '非法请求');
        }
        
        $rdata = $this->getGiftdetail($giftid);
        if (empty($rdata)) {
            return hs_api_responce('404', '无礼包');
        }
        return hs_api_responce(200, '请求成功', $rdata);
    }
    
    /*
     * 临朐礼包 使用post参数
     */
    public function save() {
        $this->isUserLogin();
        $rdata = array();
        $giftid = $this->request->post('giftid/d', 0); // 礼包ID
        if ($giftid <= 0) {
            return hs_api_responce('404', '非法请求');
        }
        
        // 查询礼包是否存在
        $gflog_info = DB::name('cdkey')->where(array(
            'id' => $giftid 
        ))->find();
        if (empty($gflog_info)) {
            return hs_api_responce(400, '无此礼包');
        }
        
        // 查询礼包详情
        $gflog_info = $this->getUserGift($giftid, $this->mem_id);
        if (!empty($gflog_info)) {
            $rdata['giftcode'] = $gflog_info['code'];
            return hs_api_responce(302, '已领取过礼包', $rdata);
        }
        
        // 领取礼包
        $giftcode = $this->setUserGift($giftid);
        if (empty($giftcode)) {
            return hs_api_responce('302', '礼包已领完');
        }
        
        $rdata['giftcode'] = $giftcode;
        return hs_api_responce('201', '领取成功', $rdata);
    }
    
    /*
     * 获取礼包详情
     */
    private function getGiftdetail($giftid) {
        $data = array();
        if ($giftid <= 0) {
            return $data;
        }
        $field = array(
            'gf.id' => 'giftid', 
            'gf.app_id' => 'gameid', 
            'gf.title' => 'giftname', 
            'gf.total' => 'total', 
            'gf.remain' => 'remain', 
            'gf.content' => 'content', 
            "CONCAT('" . $this->staticurl . "',g.mobile_icon)" => 'icon', 
            "FROM_UNIXTIME(gf.`start_time`, '%Y-%m-%d %H:%i:%S')" => 'starttime', 
            "FROM_UNIXTIME(gf.`end_time`, '%Y-%m-%d %H:%i:%S')" => 'enttime', 
            'gf.scope' => 'scope' 
        );
        
        $join = [
            [
                'game g', 
                'gf.app_id =g.id', 
                'LEFT' 
            ] 
        ];
        
        $data = DB::name('cdkey')->alias('gf')->field($field)->join($join)->where(
                array(
                    'gf.id' => $giftid 
                ))->find();
        if (empty($data)) {
            return $data;
        }
        
        // 查询玩家是否已经领取过
        if ($this->mem_id > 0) {
            $gflog_info = $this->getUserGift($giftid, $this->mem_id);
            if (!empty($gflog_info)) {
                $data['giftcode'] = $gflog_info['code'];
                $data['isget'] = 1;
            }
        }
        
        return $data;
    }
    
    /*
     * 领取礼包
     * 返回礼包码
     */
    private function setUserGift($giftid) {
        if (empty($giftid)) {
            return '';
        }
        
        // 判断是否领取过礼包
        $rdata = $this->getUserGift($giftid, $this->mem_id);
        if (!empty($rdata)) {
            return $rdata['code'];
        }
        
        $giftcode = '';
        
        // 获取礼包信息
        $gift_info = DB::name('cdkey')->where(array(
            'id' => $giftid 
        ))->find();
        
        if ($gift_info['remain'] > 0) {
            // 礼包码数量-1
            $gift_info['remain'] = $gift_info['remain'] - 1;
            DB::name('cdkey')->update($gift_info);
            
            $gfc_model = DB::name('cdkey_code');
            // 查找未被领取的礼包
            $user_gift = $gfc_model->where(
                    array(
                        'gf_id' => $giftid, 
                        'mem_id' => 0 
                    ))->find();
            
            // 更新礼包
            $user_gift['mem_id'] = $this->mem_id;
            $user_gift['update_time'] = time();
            $rs = $gfc_model->update($user_gift);
            if ($rs > 0) {
                $gf_log['mem_id'] = $this->mem_id;
                $gf_log['gf_id'] = $giftid;
                $gf_log['code'] = $user_gift['code'];
                $gf_log['create_time'] = $user_gift['update_time'];
                DB::name('cdkey_log')->insert($gf_log);
                
                $giftcode = $user_gift['code'];
            }
        }
        
        return $giftcode;
    }
    
    /*
     * 根据礼包ID获取玩家礼包
     */
    private function getUserGift($giftid, $mem_id) {
        if (empty($giftid) || empty($mem_id)) {
            return array();
        }
        $giftmap['mem_id'] = $mem_id;
        $giftmap['gf_id'] = $giftid;
        $gflog_info = DB::name('cdkey_log')->where($giftmap)->find();
        
        return $gflog_info;
    }
    
    // 获取游礼包戏列表
    private function getGiftlist(array $where = array()) {
        $map['gf.is_delete'] = 2; // app中的游戏
        $map['gf.end_time'] = array(
            '>', 
            time() 
        ); // 选取不过期的游戏礼包 wuyonghong
        

        $rdata = array(
            'count' => 0, 
            'gift_list' => array() 
        );
        $page = 1;
        $offset = 10;
        
        if (!empty($where['offset'])) {
            $offset = $where['offset'];
        }
        
        if (!empty($where['page'])) {
            $page = $where['page'];
        }
        
        if (!empty($where['gameid'])) {
            $map['gf.app_id'] = $where['gameid'];
        }
        
        $field = array(
            'gf.id' => 'giftid', 
            'gf.app_id' => 'gameid', 
            'gf.title' => 'giftname', 
            'gf.total' => 'total', 
            'gf.remain' => 'remain', 
            'gf.content' => 'content', 
            "CONCAT('" . $this->staticurl . "',g.mobile_icon)" => 'icon', 
            "FROM_UNIXTIME(gf.`start_time`, '%Y-%m-%d %H:%i:%S')" => 'starttime', 
            "FROM_UNIXTIME(gf.`end_time`, '%Y-%m-%d %H:%i:%S')" => 'enttime', 
            'gf.scope' => 'scope' 
        );
        
        if (1 == $where['usergift']) {
            $join = [
                [
                    'cdkey  gf',
                    'gf.id =gfl.gf_id',
                    'LEFT'
                ],
                [
                    'game g',
                    'gf.app_id =g.id',
                    'LEFT'
                ]
            ];
            
            $field['gfl.code'] = 'code';
            $gfl_model = DB::name('cdkey_log');
            $gfl_map['mem_id'] = $this->mem_id;
            $count = $gfl_model->where($gfl_map)->count();
            $map['gfl.mem_id'] = $this->mem_id;
            $limit = $page . ',' . $offset;
            $rdata['gift_list'] = $gfl_model->alias('gfl')->field($field)->join($join)->where($map)->order('gfl.id desc')->page($limit)->select();
        } else {
             $map['gf.end_time'] = array(
                '>', 
                time() 
            ); // 选取不过期的游戏礼包 wuyonghong
            
            $map['gf.remain'] = array(
            '>', 
            0 
            ); // 选取不过期的游戏礼包 wuyonghong
        
        
            $join = [
                [
                    'game g',
                    'gf.app_id =g.id',
                    'LEFT'
                ]
            ];
            $count = DB::name('cdkey')->alias('gf')->where($map)->count();
            if ($count > 0) {
                $limit = $page . ',' . $offset;
                $data = DB::name('cdkey')->alias('gf')->field($field)->join($join)->where($map)->page($limit)->order(
                        'gf.id desc')->select();
                // 查询玩家是否已经领取过
                if ($this->mem_id > 0) {
                    foreach ($data as $k => $v) {
                        $gflog_info = $this->getUserGift($v['giftid'], $this->mem_id);
                        if (!empty($gflog_info['code'])) {
                            $data[$k]['giftcode'] = $gflog_info['code'];
                            $data[$k]['isget'] = 1;
                        } else {
                            $data[$k]['giftcode'] = '';
                            $data[$k]['isget'] = 0;
                        }
                    }
                }
                $rdata['gift_list'] = $data;
            }
        }
        $rdata['count'] = $count;
        return $rdata;
    }
}

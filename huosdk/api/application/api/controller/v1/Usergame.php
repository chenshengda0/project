<?php

/**
 * Usergame.php UTF-8
 * 用户游戏处理接口
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

class Usergame extends Base
{
    private $game_model;
    function _initialize() {
        parent::_initialize();
        $this->game_model = DB::name('game');
    }
    
    /*
     * 请求游戏列表
     */
    public function like() {
        $gameid = $this->request->get('gameid/d', 0);
        
        if ($gameid<=0){
            return hs_api_responce(400,'参数错误');
        }
        $gext_model = DB::name('game_ext');
        $gext_model->where(array('app_id'=>$gameid))->setInc('like_cnt');
        $rdata['count'] = $gext_model->where(array('app_id'=>$gameid))->value('like_cnt');
        
//         $game_info = $this->game_model->where(array('id'=>$gameid))->find();

//         
//         $gext_info = $gext_model->where(array('app_id'=>$gameid))->find();

        return hs_api_responce(200, '请求成功', $rdata);
    }
    
    /*
     * 获取游戏详情
     */
    public function read() {
        $map['id'] = $this->request->get('gameid/d', 0); // 获取游戏ID
        
        if (empty($map['id'])) {
            return hs_api_responce(400, '参数错误');
        }
        
        $field = "g.id gameid,CONCAT('" . $this->staticurl . "',g.mobile_icon) icon,g.name gamename,g.type ";
        $field .= ",g.run_time runtime,g.category category,g.is_hot hot ";
        $field .= ",IFNULL(ge.down_cnt,0) downcnt,IFNULL(ge.star_cnt,0) score ";
        $field .= ",IFNULL(ge.distype,0) distype,ge.discount discount,ge.rebate rebate ";
        $field .= ",IFNULL(ge.like_cnt,0) likecnt,IFNULL(ge.share_cnt,0) sharecnt ";
        $field .= ",gi.androidurl downlink,gi.publicity oneword,gi.size size, gi.image image";
        $field .= ",gi.lang lang, gi.adxt sys, gi.description disc ";
        $join = [
            [
                'game_ext ge', 
                'g.id=ge.app_id', 
                'LEFT' 
            ], 
            [
                'game_info  gi', 
                'g.id =gi.app_id', 
                'LEFT' 
            ] 
        ];
        
        $game_info = $this->game_model->alias('g')->field($field)->join($join)->where($map)->find();
        $gc_model = DB::name('game_client');
        $game_client_info = $gc_model->alias('gc')->field("gc.gv_id verid, gc.version vername")->where(
                array(
                    'app_id' => $map['id'] 
                ))->find();
        if (!empty($game_client_info)) {
            $game_info = array_merge($game_info, $game_client_info);
        }
        
        // 获取礼包数量
        $game_info['giftcnt'] = $this->getGiftcntbyGameid($map['id']);
        $rdata = $game_info;
        return hs_api_responce(200, '请求成功', $rdata);
    }
    
    /*
     * 游戏下载接口
     */
    public function down() {
        $app_id = $th;
    }
    // 获取礼包数量
    public function getGiftcntbyGameid($app_id = 0) {
        $app_id = (int) $app_id;
        if ($app_id <= 0) {
            return 0;
        }
        $map['app_id'] = $app_id;
        $map['is_delete'] = 2;
        $map['end_time'] = array(
            '>', 
            time() 
        );
        return DB::name('gift')->where($map)->count();
    }
    
    /*
     * 标识 请求类型 生成路由规则 对应操作方法（默认）
     * save get game save
     */
    public function save() {
        $rdata = '';
        return hs_api_responce(404, '接口不存在', $rdata);
    }
    /*
     * 标识 请求类型 生成路由规则 对应操作方法（默认）
     * create GET game/create create
     */
    public function create() {
        $rdata = '';
        return hs_api_responce(404, '接口不存在', $rdata);
    }
    
    /*
     * 标识 请求类型 生成路由规则 对应操作方法（默认）
     * edit GET game/:id/edit edit
     */
    public function edit($id) {
        $rdata = '';
        return hs_api_responce(404, '接口不存在', $rdata);
    }
    
    /*
     * 标识 请求类型 生成路由规则 对应操作方法（默认）
     * update PUT game/:id update
     * 游戏更新
     */
    public function update($id) {
        $app_id = $id;
        $agent = $this->request->put('agent/d', 0);
        $like = $this->request->put('like/d', 0);
        $star = $this->request->put('star/d', 0);
        $down = $this->request->put('download/d', 0);
        $userid = $this->request->put('userid/d', 0);
        
        $app_ext_model = Db::name('game_ext');
        $ext_data = $app_ext_model->where('app_id', $id)->find();
        if (empty($ext_data)) {
            $ext_data['app_id'] = $id;
            $ext_data['down_cnt'] = 0;
            $ext_data['install_cnt'] = 0;
            $ext_data['reg_cnt'] = 0;
            $ext_data['star_cnt'] = 0;
            $ext_data['like_cnt'] = 0;
            $ext_data['share_cnt'] = 0;
            $app_ext_model->insert($ext_data);
        }
        
        if (1 == empty($down)) {
            $ext_data['down_cnt'] += 1;
        }
        
        if (1 == empty($data['like'])) {
            $ext_data['like_cnt'] += 1;
        }
        
        if (1 == empty($star)) {
            $ext_data['star_cnt'] += 1;
        }
        
        $rdata['likecnt'] = $ext_data['like_cnt'] + rand(10, 10000);
        $rdata['starcnt'] = $ext_data['star_cnt'] + rand(10, 10000);
        $rdata['downcnt'] = $ext_data['down_cnt'] + rand(10, 10000);
        
        return hs_api_responce(201, '请求成功', $rdata);
    }
    
    /*
     * 标识 请求类型 生成路由规则 对应操作方法（默认）
     * delete DELETE game/:id delete
     */
    public function delete($id) {
        $rdata = '';
        return hs_api_responce(404, '接口不存在', $rdata);
    }
    
    // 获取游戏列表
    private function getGameList(array $where = array()) {
        $map['g.is_app'] = 2; // app中的游戏
        $data = array();
        $page = 0;
        $offset = 10;
        
        if (!empty($where['hot'])) {
            $map['g.is_hot'] = $where['hot'];
        }
        
        if (!empty($where['category'])) {
            $map['g.category'] = $where['category'];
        }
        
        if (!empty($where['classify'])) {
            $map['g.classify'] = $where['classify'];
        }
        
        if (!empty($where['offset'])) {
            $offset = $where['offset'];
        }
        
        $game_model = new GameModel();
        $field = "g.id gameid,CONCAT('" . $this->staticurl . "',g.mobile_icon) icon,g.name gamename,g.type ";
        $field .= ",g.run_time runtime,g.category category,g.is_hot hot ";
        $field .= ",IFNULL(ge.down_cnt,0) downcnt,IFNULL(ge.star_cnt,0) score ";
        $field .= ",IFNULL(ge.distype,0) distype,ge.discount discount,ge.rebate rebate ";
        $field .= ",IFNULL(ge.like_cnt,0) likecnt,IFNULL(ge.share_cnt,0) sharecnt ";
        $field .= ",gi.androidurl downlink,gi.publicity oneword,gi.size ";
        
        if (empty($where['type'])) {
            $join = [
                [
                    'game_ext ge', 
                    'g.id=ge.app_id', 
                    'LEFT' 
                ], 
                [
                    'game_info  gi', 
                    'g.id =gi.app_id', 
                    'LEFT' 
                ] 
            ];
        } else {
            $join = [
                [
                    'game_gt ggt', 
                    'g.id=ggt.app_id', 
                    'RIGHT' 
                ], 
                [
                    'game_ext ge', 
                    'g.id=ge.app_id', 
                    'LEFT' 
                ], 
                [
                    'game_info  gi', 
                    'g.id =gi.app_id', 
                    'LEFT' 
                ] 
            ];
        }
        
        $count = $game_model->alias('g')->join($join)->where($map)->count();
        if ($count > 0) {
            if (!empty($where['cnt'])) {
                $page = rand(0, intval($count / $where['cnt']));
                $offset = $where['cnt'];
            }
            
            $limit = $page . ',' . $offset;
            $data = $game_model->alias('g')->field($field)->join($join)->limit($limit)->select();
        }
        
        $rdata['count'] = $count;
        $rdata['game_list'] = $data;
        return $rdata;
    }
}

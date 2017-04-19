<?php

/**
 * Search.php UTF-8
 * 游戏搜索接口
 * @date: 2016年8月18日下午9:46:57
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : api 2.0
 */
namespace app\api\controller\v1;

use app\common\controller\Base;
use app\api\model\Game as GameModel;

class Search extends Base
{
    function _initialize() {
        parent::_initialize();
    }
    public function index() {
        $data['name'] = $this->request->get('q/s', ''); // 游戏ID.
        $data['page'] = $this->request->get('page/d', 1); // 游戏ID.
        $data['offset'] = $this->request->get('offset/d', 10); // 游戏ID.
        if (empty($data['name'] )) {
            hs_api_responce(400, '请输入搜索词');
        }
        $rdata = array(
            'count' => 0, 
            'searchtype' => 'game', 
            'game_list' => array() 
        );
        $rdata = $this->getGamelist($data);
        if (empty($rdata['count'])) {
            return hs_api_responce('404', '无记录');
        }
        return hs_api_responce('200', '请求成功', $rdata);
    }
    
    // 搜索热词
    public function recommend() {
        $game_model = new GameModel();
        $data = $game_model->where(array('is_hot'=>2))->limit(10)->column('name');
        if (empty($data)) {
            return hs_api_responce('404', '无记录');
        }
        return hs_api_responce('200', '请求成功', $data);
    }
    
    // 获取推荐词
    public function hotword() {
        $game_model = new GameModel();
        $data = $game_model->where(array('is_hot'=>2))->limit(10)->column('name');
        if (empty($data)) {
            return hs_api_responce('404', '无记录');
        }
        return hs_api_responce('200', '请求成功', $data);
    }
    
    
    public function getGamelist($searchdata) {
        if (empty($searchdata['name'])) {
            return array();
        }
        
        $map['g.name'] = array(
            'like',
            '%' . $searchdata['name'] . '%'
        );
        
        $map['g.is_app'] = 2; // app中的游戏
        $map['g.is_delete'] = 2; // 伪删除游戏不显示
        $map['g.status'] = 2; // 游戏上线才显示
        $data = array();
        
        $page = $searchdata['page'] ;
        $offset = 10 ;
        
        if (!empty($searchdata['offset'])) {
            $offset = $searchdata['offset'];
        }
        
        $game_model = new GameModel();
        $field = "g.id gameid,CONCAT('" . $this->staticurl . "',g.mobile_icon) icon,g.name gamename,g.type ";
        $field .= ",g.run_time runtime,g.category category,g.is_hot hot ";
        $field .= ",IFNULL(ge.down_cnt,0) downcnt,IFNULL(ge.star_cnt,0) score ";
        $field .= ",IFNULL(ge.distype,0) distype,ge.discount discount,ge.rebate rebate ";
        $field .= ",IFNULL(ge.like_cnt,0) likecnt,IFNULL(ge.share_cnt,0) sharecnt ";
        $field .= ",gi.androidurl downlink,gi.publicity oneword,gi.size ";

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
        
        $count = $game_model->alias('g')->join($join)->where($map)->count();
        if ($count > 0) {
            $count = $offset;
            $limit = $page . ',' . $offset;
            $data = $game_model->alias('g')->field($field)->join($join)->where($map)->limit($limit)->select();
        }
        
        $rdata['count'] = $count;
        $rdata['searchtype'] = 'game';
        $rdata['game_list'] = $data;
        return $rdata;
    }
    
    
}

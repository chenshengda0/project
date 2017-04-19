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
// use app\api\model\GamecommentModel;
use think\Model;

class Gamecomment extends Base
{
    private $game_model;
    function _initialize() {
        parent::_initialize();
        $this->gamec_model = DB::name('game_comments');
    }
    
    /*
     * 请求游戏列表
     */
    public function index() {
        $map['app_id'] = $this->request->get('gameid/d', 0); // 1 游戏ID
        $map['page'] = $this->request->get('page/d', 1); // 1 页码从1开始
        $map['offset'] = $this->request->get('offset/d', 10); // 每页请求数量，默认为10
        
        if ($map['app_id'] <= 0) {
            return hs_api_responce('400', '参数错误');
        }
        
        $rdata = $this->getGclist($map);
        if (empty($rdata) || $rdata['count']<=0){
            return hs_api_responce('404', '还没有评论');
        }
        
        return hs_api_responce('200', '', $rdata);
    }
    
    /*
     * 添加评论
     */
    public function save() {
//         $this->isUserLogin();
        
        $data['app_id'] = $this->request->post('gameid/d', 0); // 1 游戏ID
        $data['content'] = $this->request->post('content/s', 0); // 评论内容
        $data['parentid'] = $this->request->post('toid/d', 0); // 被评论ID
        $data['from'] = $this->request->post('from/d', 3); // 1-WEB、2-WAP、3-Android、4-IOS、5-WP
        
        if ($data['app_id'] <= 0) {
            return hs_api_responce('400', '参数错误');
        }
        
        if (empty($data['content'])) {
            return hs_api_responce('400', '评论为空');
        }
        
        if ($data['parentid']>0){
            $toc_info = $this->game_model->where(array('id'=>$data['parentid']))->find();

            if(empty($toc_info) || $toc_info['app_id'] != $data['app_id']){
                $data['to_mem_id'] = 0;
            }else{
                $data['to_mem_id'] = $toc_info['mem_id'];
            }
        }
        $data['mem_id'] = $this->mem_id;
        $data['create_time'] = time();
        
        $rs = $this->gamec_model->insert($data);
        if ($rs>0){
            return hs_api_responce('201','评论成功',$data);
        }else{
            return hs_api_responce('500','内部错误');
        }
    }
    
    // 获取评论列表
    public function getGclist(array $data) {
        $commentlist = array();
        $rdata = array(
            'count' => 0, 
            'commentlist' => array() 
        );
        if ($data['app_id'] <= 0) {
            return $rdata;
        }
        $map['gc.app_id'] = $data['app_id'];
        $field = array(
            'gc.id' => 'id', 
            'gc.content' => 'content', 
            "FROM_UNIXTIME(gc.`create_time`, '%Y-%m-%d %H:%i:%S')" => 'pudate', 
            'gc.from' => 'from', 
            'm.username' => 'author', 
            'gc.mem_id' => 'mem_idauthorid', 
            'm.portrait' => 'portrait' 
        );
        
        $join = [
            [
                'members m', 
                'm.id=gc.mem_id', 
                'LEFT' 
            ] 
        ];
        
        $count = $this->gamec_model->alias('gc')->field($field)->join($join)->where($map)->count();
        
        if ($count > 0) {
            $limit = $data['page'] . ',' . $data['offset'];
            $commentlist = $this->gamec_model->alias('gc')->field($field)->join($join)->where($map)->page($limit)->order(
                    'id')->select();
        }
        
        $rdata['count'] = $count;
        $rdata['commentlist'] = $commentlist;
        
        return $rdata;
    }
}

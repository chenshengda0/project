<?php

/**
 * News.php UTF-8
 * 新闻资讯
 * @date: 2016年8月18日下午9:46:57
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : api 2.0
 */
namespace app\api\controller\v1;

use think\Db;
use app\common\controller\Base;

class News extends Base
{
    function _initialize() {
        parent::_initialize();
    }
    /*
     * 资讯列表
     */
    public function index() {
        $map['page'] = $this->request->get('page/d', 0); // 页
        $map['offset'] = $this->request->get('offset/d', 0); // 每页请求数量，默认为10
        $map['gameid'] = $this->request->get('gameid/d', 0); // 每页请求数量，默认为10
        $map['catalog'] = $this->request->get('catalog/d', 0); // 每页请求数量，默认为10
        
        $rdata = $this->getNewslist($map);
        if (empty($rdata['count'])) {
            return hs_api_responce(400, '无资讯');
        }
        return hs_api_responce(200, '请求成功', $rdata);
    }
    
    /*
     * 资讯详情
     */
    public function read() {
        $map['id'] = $this->request->get('newsid/d', 0); // 资讯ID
        if (empty($map['id'])) {
            return hs_api_responce(400, '参数错误');
        }
        $field = array(
            'p.id' => 'id', 
            'p.post_title' => 'title', 
            'p.post_content' => 'content', 
            "FROM_UNIXTIME(p.`post_modified`, '%Y-%m-%d %H:%i:%S')" => 'pudate', 
            'p.app_id' => 'gameid', 
            "p.smeta" => 'img', 
            'p.post_author' => 'author', 
            'p.comment_count' => 'commentcnt', 
            'p.post_like' => 'likecnt', 
            'p.post_type' => 'type' 
        );
        
        $post_model = DB::name('posts');
        $data = $post_model->alias('p')->field($field)->where($map)->find();
        $smeta = json_decode($data['img'], true);
        $data['img'] = $smeta['thumb'];
        return hs_api_responce(200, '请求成功', $data);
    }
    
    /*
     * 获取资讯列表
     */
    private function getNewslist($where = array()) {
        $map['p.post_status'] = 2; // app中的游戏
        $data = array();
        $page = 1;
        $offset = 10;
        
        if (is_numeric($where['page']) && is_numeric($where['page'])) {
            $page = $where['page'];
        }
        
        if (!empty($where['offset']) && is_numeric($where['offset'])) {
            $offset = $where['offset'];
        }
        
        if (!empty($where['gameid'])) {
            $map['p.app_id'] = $where['gameid'];
        }
        
        // 资讯类型
        if (!empty($where['catalog'])) {
            $map['p.post_type'] = $where['catalog'];
        }
        
        $field = array(
            'p.id' => 'id', 
            'p.post_title' => 'title', 
            'p.app_id' => 'gameid', 
            "p.smeta" => 'img', 
            "FROM_UNIXTIME(p.`post_modified`, '%Y-%m-%d')" => 'pudate', 
            'p.post_author' => 'author', 
            'p.comment_count' => 'commentcnt', 
            'p.post_like' => 'likecnt', 
            'p.post_type' => 'type' 
        );
        
        $post_model = DB::name('posts');
        $count = $post_model->alias('p')->where($map)->count();
        if ($count > 0) {
            $limit = $page . ',' . $offset;
            $data = $post_model->alias('p')->field($field)->page($limit)->where($map)->order('post_modified desc')->select();
            foreach ($data as $k => $v) {
                $smeta = json_decode($v['img'], true);
                if (!empty($smeta['thumb'])) {
                    if (strpos($smeta['thumb'], "/") === 0) {
                       $data[$k]['img'] = $this->staticurl . $smeta['thumb'];
                    }else{                        
                        $data[$k]['img'] = $smeta['thumb'];
                    }
                }
            }
        }
        
        $rdata['count'] = $count;
        $rdata['news_list'] = $data;
        return $rdata;
    }
}

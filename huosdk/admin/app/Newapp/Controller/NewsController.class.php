<?php

/**
 * NewsController.class.php UTF-8
 * 新闻资讯管理
 * @date: 2016年4月11日下午2:29:32
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@1tsdk.com>
 * @version : 1.0
 */
namespace Newapp\Controller;

use think\Db;
use Common\Controller\AdminbaseController;

class NewsController extends AdminbaseController
{
    protected $posts_model;
    function _initialize() {
        $this->_game();
        parent::_initialize();
        $this->posts_model = D("Common/Posts");
    }
    function index() {
        $this->_game();
        $this->post_types();
        $where_ands = array(
            " post_status>0" 
        );
//         $where_ands = array();
        $fields = array(
            'ptype' => array(
                "field" => "post_type", 
                "operator" => "=" 
            ), 
            'start_time' => array(
                "field" => "post_date", 
                "operator" => ">" 
            ), 
            'end_time' => array(
                "field" => "post_date", 
                "operator" => "<" 
            ), 
            'keyword' => array(
                "field" => "post_title", 
                "operator" => "like" 
            ),
            'appid' => array(
                "field" => "app_id", 
                "operator" => "=" 
            ) 
        );
        // $_POST[start_time]=strtotime("$_POST[start_time]");
        // $_POST[end_time]= strtotime("$_POST[end_time]");
        
        if (IS_POST) {
            foreach ($fields as $param => $val) {
                if (isset($_POST[$param]) && !empty($_POST[$param])) {                                       
                    $operator = $val['operator'];
                    $field = $val['field'];
                    $get = trim($_POST[$param]);
                    $_GET[$param] = $get;
                    
                    if ('start_time' == $param) {
                        $get = strtotime($get);
                    } else if ('end_time' == $param) {
                        $get .= " 23:59:59";
                        $get = strtotime($get);
                    }
                    
                    if ($operator == "like") {
                        $get = "%$get%";
                    }
                    array_push($where_ands, "$field $operator '$get'");
                }
            }
        } else {
            foreach ($fields as $param => $val) {
                if (isset($_GET[$param]) && !empty($_GET[$param])) {
                    $operator = $val['operator'];
                    $field = $val['field'];
                    $get = trim($_GET[$param]);
                    
                    if ('start_time' == $param) {
                        $get = strtotime($get);
                    } else if ('end_time' == $param) {
                        $get .= " 23:59:59";
                        $get = strtotime($get);
                    }
                    
                    if ($operator == "like") {
                        $get = "%$get%";
                    }
                    array_push($where_ands, "$field $operator '$get'");
                }
            }
        }
        
        $where = join(" AND ", $where_ands);
        $count = $this->posts_model->where($where)->count();
        $page = $this->page($count, 20);
        
        $posts = $this->posts_model->alias('p')->field("p.*, g.name gamename")->join(
                "left join " . C('DB_PREFIX') . "game g ON p.app_id=g.id")->where($where)->limit(
                $page->firstRow . ',' . $page->listRows)->order("post_date desc")->select();
        
        $this->assign("Page", $page->show('Admin'));
        $this->assign("formget", $_GET);
        $this->assign("posts", $posts);
        $this->display();
    }
    
    function recyclebin() {
        $where_ands = array(
            "post_type=2 and post_status=0" 
        );
        $fields = array(
            'start_time' => array(
                "field" => "post_date", 
                "operator" => ">" 
            ), 
            'end_time' => array(
                "field" => "post_date", 
                "operator" => "<" 
            ), 
            'keyword' => array(
                "field" => "post_title", 
                "operator" => "like" 
            ) 
        );
        if (IS_POST) {
            
            foreach ($fields as $param => $val) {
                if (isset($_POST[$param]) && !empty($_POST[$param])) {
                    $operator = $val['operator'];
                    $field = $val['field'];
                    $get = $_POST[$param];
                    $_GET[$param] = $get;
                    if ($operator == "like") {
                        $get = "%$get%";
                    }
                    array_push($where_ands, "$field $operator '$get'");
                }
            }
        } else {
            foreach ($fields as $param => $val) {
                if (isset($_GET[$param]) && !empty($_GET[$param])) {
                    $operator = $val['operator'];
                    $field = $val['field'];
                    $get = $_GET[$param];
                    if ($operator == "like") {
                        $get = "%$get%";
                    }
                    array_push($where_ands, "$field $operator '$get'");
                }
            }
        }
        
        $where = join(" and ", $where_ands);
        
        $count = $this->posts_model->where($where)->count();
        $page = $this->page($count, 20);
        
        $posts = $this->posts_model->where($where)->limit($page->firstRow . ',' . $page->listRows)->select();
        L();
        $users_obj = M("Users");
        $users_data = $users_obj->field("id,user_login")->where("user_status=1")->select();
        $users = array();
        foreach ($users_data as $u) {
            $users[$u['id']] = $u;
        }
        $this->assign("users", $users);
        
        $this->assign("Page", $page->show('Admin'));
        $this->assign("formget", $_GET);
        $this->assign("posts", $posts);
        $this->display();
    }
    function add() {
        $this->post_types(1);
        $app_id = I('get.app_id');
        if ($app_id > 0) {
            $gamename = M('Game')->where(array(
                'id' => $app_id 
            ))->getField('name');
            $game_info['app_id'] = $app_id;
            $game_info['gamename'] = $gamename;
            $this->assign('gameinfo', $game_info);
        }
        $this->display('News/add');
    }
    function add_post() {
        if (IS_POST) {
//             $_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
            $_POST['post']['post_date'] = time();
            $_POST['post']['post_author'] = get_current_admin_id();
            $page = I("post.post");
            $page['smeta'] = json_encode($_POST['smeta']);
            $page['post_modified'] = time();
            $page['post_content'] = htmlspecialchars_decode($page['post_content']);
            $result = $this->posts_model->add($page);
            if ($result) {
                $this->success("添加成功！");
            } else {
                $this->error("添加失败！");
            }
        }
    }
    public function edit() {
        $this->post_types(1);
        $id = intval(I("get.id"));
        $post = $this->posts_model->where("id=$id")->find();
        $post[post_modified] = date('Y-m-d H:i:s', $post[post_modified]);
        $this->assign("post", $post);
        $this->assign("smeta", json_decode($post['smeta'], true));
        
        $this->assign("author", "1");
        $this->display();
    }
    public function edit_post() {
        if (IS_POST) {
//             $_POST['smeta']['thumb'] = sp_asset_relative_url($_POST['smeta']['thumb']);
            $_POST['post']['post_modified'] = time();
            
            unset($_POST['post']['post_author']);
            $page = I("post.post");
            $page['smeta'] = json_encode($_POST['smeta']);
            $page['post_content'] = htmlspecialchars_decode($page['post_content']);
            
            // $dir = 'D:/test.txt';
            // $re = fopen($dir, 'a');
            // fwrite($re, json_encode(array(
            // $page
            // ))); // 将内容写入文件
            // fwrite($re, "\r\n"); // 将内容写入文件
            // fclose($re);
            
            $result = $this->posts_model->save($page);
            if ($result !== false) {
                //
                $this->success("保存成功！");
            } else {
                $this->error("保存失败！");
            }
        }
    }
    function delete() {
        if (isset($_POST['ids'])) {
            $ids = implode(",", $_POST['ids']);
            $data = array(
                "post_status" => "0" 
            );
            if ($this->posts_model->where("id in ($ids)")->save($data)) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        } else {
            if (isset($_GET['id'])) {
                $id = I("get.id/d");
                $data = array(
                    "id" => $id, 
                    "post_status" => "0" 
                );
                if ($this->posts_model->save($data)) {
                    $this->success("删除成功！");
                } else {
                    $this->error("删除失败！");
                }
            }
        }
    }
    function restore() {
        if (isset($_GET['id'])) {
            $id = intval(I("get.id"));
            $data = array(
                "id" => $id, 
                "post_status" => "1" 
            );
            if ($this->posts_model->save($data)) {
                $this->success("还原成功！");
            } else {
                $this->error("还原失败！");
            }
        }
    }
    function clean() {
        if (isset($_POST['ids'])) {
            $ids = implode(",", $_POST['ids']);
            if ($this->posts_model->where("id in ($ids)")->delete() !== false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
        if (isset($_GET['id'])) {
            $id = intval(I("get.id"));
            if ($this->posts_model->delete($id) !== false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
    }
    
    /**
     * 游戏所有状态函数
     * @date: 2016年4月8日下午6:07:08
     * 
     * @param option 参数选项
     * @return NULL
     * @since 1.0
     */
    public function post_types($option = NULL) {
        $posttypes = array(
            '0' => "全部", 
            '1' => "新闻", 
            '2' => "活动", 
            '3' => "攻略" 
        )
        ;
        
        if (1 == $option) {
            $posttypes = array(
                '1' => "新闻", 
                '2' => "活动", 
                '3' => "攻略" 
            );
        }
        $this->assign("ptypes", $posttypes);
        return;
    }
}
<?php

/**
 * VersionController.class.php UTF-8
 * APP版本管理
 * @date: 2016年9月1日下午10:31:56
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : H5 2.0
 */
namespace Newapp\Controller;

use Common\Controller\AdminbaseController;

class VersionController extends AdminbaseController
{
    protected $game_model, $gv_model, $where;
    function _initialize() {
        parent::_initialize();
        $this->gc_model = M('game_client');
        $this->gv_model = M('game_version');
    }
    
    /**
     * 游戏列表
     */
    public function index() {
        $this->_vList();
        $this->display();
    }
    
    /**
     * 游戏列表
     */
    function _vList() {
        $where_ands = array();
        array_push($where_ands, "app_id = " . C('APP_APPID'));
        array_push($where_ands, "status < 3");
        $where = join(" AND ", $where_ands);
        
        /* 类型为空时，直接查询game表即可 */
        $count = $this->gv_model->where($where)->count();
        
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
        $page = $this->page($count, $rows);
        
        $items = $this->gv_model->where($where)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
        
        $this->assign("versions", $items);
        $this->assign("formget", $_GET);
        $this->assign("Page", $page->show('Admin'));
        $this->assign("current_page", $page->GetCurrentPage());
    }
    
    /**
     * 添加版本
     */
    public function add() {
        $this->_getLastver();
        $this->display();
    }
    /**
     * 编辑版本
     */
    public function edit() {
        $id = I('get.id/d', 0);
        if (empty($id)) {
            $this->error("参数错误");
        }
        $map['id'] = $id;
        $gv_data = $this->gv_model->where($map)->find();
        $this->assign($gv_data);
        $this->display();
    }
    
    /*
     * 查询最近可用最新版本
     */
    function _getLastver() {
        $map['status'] = 2;
        $map['app_id'] = C('APP_APPID');
        $order = "id desc";
        $data = $this->gv_model->where($map)->order($order)->find();
        if (empty($data)) {
            return array();
        }
        $this->assign('lastver', $data);
        return $data;
    }
    
    /**
     * 删除版本
     */
    public function del() {
        $id = I('id', 0);
        $data['status'] = 3;
        $rs = $this->gv_model->where("id = %d", $id)->save($data);
        if ($rs) {
            $this->success("删除成功", U("Version/index"));
            exit();
        }
        $this->error('删除失败.');
    }
    
    /*
     * 获取对接参数
     */
    public function get_param() {
        $verid = I('id/d', 0);
        if (empty($verid)) {
            $this->error("参数错误");
        }
        
        $param = $this->gv_model->field('id ver_id,app_id app_id,gc_id client_id')->where(
                array(
                    'id' => $verid 
                ))->find();
        $client = M('game_client')->field('client_key')->where(
                array(
                    'id' => $param['client_id'] 
                ))->find();
        $data = array_merge($param, $client);
        
        $this->assign('params', $data);
        $this->display();
    }
    
    /**
     * 渠道添加游戏
     */
    public function add_post() {
        if (IS_POST) {
            /* 获取POST数据 */
            $ver_data['version'] = I('post.vername/s', ''); // 游戏名称
            $ver_data['content'] = I('post.content/s', ''); // 游戏名称
            
            /* 检测输入参数合法性, 游戏版本 */
            if (empty($ver_data['version'])) {
                $this->error("版本名称为空，请填写版本名称");
            } else {
                $checkExpressions = "/^\d+(\.\d+){0,2}$/";
                $len = strlen($ver_data['version']);
                if ($len > 10 || false == preg_match($checkExpressions, $ver_data['version'])) {
                    $this->error("版本名称填写错误，数字与小数点组合");
                }
            }
            
            $ver_data['app_id'] = C('APP_APPID');
            $ver_data['create_time'] = time();
            $ver_data['status'] = 1;
            
            $gv_id = $this->gv_model->add($ver_data);
            if (false == $gv_id) {
                $this->error("服务器内部错误1");
            }
            // 获取游戏的clientid
            $gc_data = M('game_client')->where(array(
                'app_id' => $ver_data['app_id'] 
            ))->find();
            if (empty($gc_data)) {
                $gc_data['app_id'] = $ver_data['app_id'];
                $gc_data['version'] = $ver_data['version'];
                $gc_data['client_key'] = md5($ver_data['version'] . md5(uniqid() . rand(10, 1000)));
                $gc_data['gv_id'] = $gv_id;
                $gc_data['gv_new_id'] = $gv_id;
                $gc_id = $this->gc_model->add($gc_data);
                $ver_data['gc_id'] = $gc_id;
            } else {
                $ver_data['gc_id'] = $gc_data['id'];
            }
            $rs = $this->gv_model->where(array(
                'id' => $gv_id 
            ))->save($ver_data);
            if (false == $rs) {
                $this->error("服务器内部错误");
            } else {
                $this->success("版本添加成功",U("Version/index"));
                exit();
            }
        }
        $this->error('页面不存在');
    }
    
    /**
     * 编辑游戏提交函数
     */
    public function edit_post() {
        if (IS_POST) {
            /* 获取POST数据 */
            $ver_data['id'] = I('post.verid/d', 0); // 游戏名称
            if (empty($ver_data['id'])) {
                $this->error("参数错误");
            }
            $ver_data['version'] = I('post.vername/s', ''); // 游戏名称
            $ver_data['content'] = I('post.content/s', ''); // 游戏名称
            
            /* 检测输入参数合法性, 游戏版本 */
            if (empty($ver_data['version'])) {
                $this->error("版本名称为空，请填写版本名称");
            } else {
                $checkExpressions = "/^\d+(\.\d+){0,2}$/";
                $len = strlen($ver_data['version']);
                if ($len > 10 || false == preg_match($checkExpressions, $ver_data['version'])) {
                    $this->error("版本名称填写错误，数字与小数点组合");
                }
            }
            
            $ver_data['update_time'] = time();
            
            $rs = $this->gv_model->save($ver_data);
            if (false == $rs) {
                $this->error("服务器内部错误");
            } else {
                $this->success("修改成功", U('Version/index'));
            }
        }
        $this->error('页面不存在');
    }
    
    /**
     * 游戏状态处理
     */
    public function set_status() {
        $id = I('id/d', 0);
        $status = I('status/d', 0);
        if (empty($status)) {
            $this->error("状态错误");
        }
        $map['id'] = $id;
        if (2 == $status) {
            $packageurl = $this->gv_model->where($map)->getField('packageurl');
            if (empty($packageurl)) {
                $this->error("请上传app包");
            }
            $data['update_time'] = time();
        }
        $data['status'] = $status;
        $rs = $this->gv_model->where($map)->save($data);
        if ($rs) {
            $this->success("状态切换成功", U("Version/index"));
            exit();
        } else {
            $this->error('状态切换失败.');
        }
    }
    
    /**
     * 添加游戏母包
     */
    public function addpackageurl() {
        $verid = I("id/d", 0);
        if (empty($verid)) {
            $this->error("参数错误");
        }
        $map['id'] = $verid;
        $url = $this->get_app_down_url($verid);
        $url = DOWNSITE . $url;
        // 检查$url文件大小
        vendor('FilesizeHelper');
        $fileclass = new \FilesizeHelper();
        $size = $fileclass->getFileSize($url, false);
        if (!empty($size)) {
            $ver_data['packageurl'] = $url;
            $ver_data['size'] = $size;
            $ver_data['update_time'] = time();
            $this->gv_model->where($map)->save($ver_data);
            $this->success("APP上传成功", U('Version/index'));
            exit();
        } else {
            $this->set_app_down_url($verid);
        }
        
        $this->assign('verid', $verid);
        $this->assign('url', $url);
        $this->display();
    }
    protected function get_app_down_url($verid) {
        $map['id'] = C('APP_APPID');
        $initial = M('game')->where($map)->getField('initial');
        $filepath = "/sdkgame/" . $initial . '/' . $verid . '/' . $initial . '.apk';
        $this->assign('filepath', $filepath);
        return $filepath;
    }
    protected function set_app_down_url($verid) {
        $gv_data = $this->gv_model->where(array(
            'id' => $verid 
        ))->find();
        $initial = M('game')->where(array(
            "id" => $gv_data['app_id'] 
        ))->getField('initial');
        $initial = $initial . '/' . $gv_data['id'];
        $opt = md5(md5($initial . $initial) . 'resub');
        $pinyin = base64_encode($initial);
        $agentgame = base64_encode($initial);
        $opt = base64_encode($opt);
        $data_string = array(
            'p' => $pinyin, 
            'a' => $agentgame, 
            'o' => $opt 
        );
        $data_string = json_encode($data_string);
        $url = DOWNSITE . "/subPackage.php";
        $cnt = 0;
        $return_content = base64_decode(http_post_data($url, $data_string));
    }
    
    /**
     * 渠道添加游戏母包
     */
    public function addpackageurl_post() {
        $appid = I("appid/d");
        $gv_id = I("id/d");
        if (empty($appid)) {
            $this->error("参数错误");
        }
        
        if (empty($gv_id)) {
            $this->error("参数错误");
        }
        
        $packageurl = I("post.packageurl", "", "trim");
        if (empty($packageurl)) {
            $this->error("请填写回调地址");
        }
        
        $checkExpressions = '|^http://|';
        
        if (false == preg_match($checkExpressions, $packageurl)) {
            $this->error("请输入正确的游戏母包地址http://开头");
        }
        
        $g_data['id'] = $gv_id;
        $g_data['app_id'] = $appid;
        $g_data['packageurl'] = $packageurl;
        $rs = $this->gv_model->where(array(
            'id' => $gv_id 
        ))->save($g_data);
        if (FALSE != $rs) {
            $this->success("添加成功！", U("Game/index"));
            exit();
        } else {
            $this->error("地址已存在，添加失败！");
        }
    }
}

?>
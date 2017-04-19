<?php

/**
 * User.php UTF-8
 * 玩家处理接口
 * @date: 2016年8月18日下午9:46:57
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : api 2.0
 */
namespace app\api\controller\v1;

use app\common\controller\Base;
use app\api\controller\v1\Smscode;
use think\Db;

class User extends Base
{
    function _initialize() {
        parent::_initialize();
    }
    /*
     * 注册
     */
    public function insert() {
//         echo $mobile = hs_auth_code('15669032809', 'ENCODE', $this->client_key);
//         exit;
        $type = $this->request->post('type/d', 0); // 注册类型 1手机注册 2 普通注册 3 试玩
        $username = $this->request->post('username/s', ''); // 注册用户名
        $mobile = $this->request->post('mobile/s', ''); // 注册手机号
        $password = $this->request->post('password/s', ''); // 注册密码
        $from = $this->request->post('from/d', 0); // 注册来源
        $deviceid = $this->request->post('deviceid/s', ''); // 注册来源
        
        if (empty($type)) {
            return hs_api_responce(400, '参数错误');
        }
        if (empty($from)) {
            return hs_api_responce(400, '参数错误');
        }
        
        if (1 == $type) {
            if (empty($mobile)) {
                return hs_api_responce(400, '请填写手机号');
            }
            
            $mobile = hs_auth_code($mobile, 'DECODE', $this->client_key);
            
            if (empty($mobile)) {
                return hs_api_responce(400, '请填写手机号');
            }
            $username = $mobile;
            $userdata['mobile'] = $mobile;

            $sms_controller = new Smscode();
            $sms_controller->read();
        } elseif (2 == $type) {
            if (empty($username)) {
                return hs_api_responce(400, '请填写账号');
            }
            $username = hs_auth_code($username, 'DECODE', $this->client_key);
            
            // 用户名必须为数字字母组合, 长度在6-16位之间
            $checkExpressions = "/^[a-zA-Z0-9]+$/i";
            $len = strlen($username);
            if ($len < 6 || $len > 16 || false == preg_match($checkExpressions, $username)) {
                return hs_api_responce(400, '用户名必须为数字字母组合, 长度在6-16位之间');
            }

        }
        
        if (empty($password)) {
            return hs_api_responce(400, '请填写密码');
        }
        $password = hs_auth_code($password, 'DECODE', $this->client_key);
        if (empty($password)) {
            return hs_api_responce(400, '请填写密码');
        }
        
        // 检测账号是否存在
        $user_info = $this->getUserinfobyName($username);
        
        if (!empty($user_info)) {
            return hs_api_responce(302, '账号已存在');
        }
        if (empty($this->agent)){
            $this->agent = 'default';
        }
        $userdata['username'] = $username;
        $userdata['password'] = hs_pw_encode($password, AUTHCODE);
        $userdata['nickname'] = $username;
        $userdata['from'] = $from;
        $userdata['imei'] = $deviceid;
        $userdata['agentgame'] = $this->agent;
        $userdata['app_id'] = $this->app_id;
        $userdata['agent_id'] = $this->agent_id;
        $userdata['status'] = 2; // 1 为试玩状态 2为正常状态，3为冻结状态
        $userdata['reg_time'] = time();
        $userdata['update_time'] = $userdata['reg_time'];
        
        $mem_id = DB::name('members')->insertGetId($userdata);
        if (!$mem_id) {
            return hs_api_responce(500, '服务器内部错误');
        }
        
        $rdata = $this->generateIdenty($mem_id);
        if ($rdata){
            return hs_api_responce(201, '注册成功',$rdata);
        }
        return hs_api_responce(500, '注册失败');
    }
    
    /*
     * 登陆
     */
    public function login() {
        $username = $this->request->post('username/s', ''); // 注册类型 1手机注册 2 普通注册 3 试玩
        $password = $this->request->post('password/s', ''); // 注册用户名
        
        if (empty($username)) {
            return hs_api_responce(400, '请填写账号');
        }
        
        $username = hs_auth_code($username, 'DECODE', $this->client_key);
        if (empty($username)){
            return hs_api_responce(400, '用户名为空');
        }
        
        $user_info = $this->getUserinfobyName($username);
        if (empty($user_info)){
            return hs_api_responce(400, '用户名不存在');
        }
        
        if (1 == $user_info['status']){
            return hs_api_responce(400, '用户已禁用');
        }
        
        if (empty($password)){
            return hs_api_responce(400, '密码错误');
        }
        $password = hs_auth_code($password, 'DECODE', $this->client_key);
        $rs = hs_compare_password($password,$user_info['password']);
        if (!$rs){
            return hs_api_responce(404, '密码错误');
        }
        
        $rdata = $this->generateIdenty($user_info['id']);
        if ($rdata){
            return hs_api_responce(201, '登陆成功',$rdata);
        }
        return hs_api_responce(500, '登陆失败');
    }
    
    /*
     * 检查用户是否存在
     */
    public function check() {
        $username = $this->request->post('username/s', ''); // 账号
        $username = hs_auth_code($username, 'DECODE', $this->client_key);
        if (empty($username)){
            return hs_api_responce(400, '用户名为空');
        }
        $user_info = $this->getUserinfobyName($username);
        
        $rdata['isexist'] = $user_info['status'];
        return hs_api_responce(200, '查询成功', $rdata);
    }

    /*
     * 读取用户信息
     */
    public function read(){
        $this->isUserLogin();
        $user_info = $this->getUserinfobyId($this->mem_id);
        if (empty($user_info)){
            return hs_api_responce(400,'获取用户信息失败');
        }
        
        
    }
    
    private function getUserinfobyId($mem_id) {
        if (empty($mem_id)) {
            return array();
        }
    
        $user_info = DB::name('members')->where(array(
            'id' => $mem_id
        ))->find();
        if (empty($user_info)) {
            return array();
        }
    
        return $user_info;
    }
    
    private function getUserinfobyName($username) {
        if (empty($username)) {
            return array();
        }
        
        $user_info = DB::name('members')->where(array(
            'username' => $username 
        ))->find();
        if (empty($user_info)) {
            return array();
        }
        
        return $user_info;
    }
    public function generateIdenty($mem_id) {
        if (empty($mem_id)) {
            return array();
        }
        
        $ext_model = DB::name('mem_ext');
        $ext_info = $ext_model->where(array(
            'mem_id' => $mem_id 
        ))->find();
        
        $ext_info['last_login_time'] = time();
        
        if (empty($ext_info['mem_id'])) {
            // 插入数据
            $ext_info['mem_id'] = $mem_id;
            $ext_info['identifier'] = uniqid($mem_id);
            $ext_info['accesstoken'] = hs_generate_key();
            $ext_info['expaire_time'] = time() + 60 * 60 * 24 * 7; // 一周时间
            $ext_info['login_cnt'] = 1;
            $rs = $ext_model->insert($ext_info);
            if (!$rs){
                return array();
            }
//         } elseif ($ext_info['expaire_time'] < time()) {
        } else {
            if (empty($ext_info['identifier'])) {
                $ext_info['identifier'] = uniqid($mem_id);
            }
            $ext_info['accesstoken'] = hs_generate_key();
            $ext_info['expaire_time'] = time() + 60 * 60 * 24 * 7; // 一周时间
            $rs = $ext_model->update($ext_info);
            if (!$rs){
                return array();
            }
        }
        $rdata['identifier'] = $ext_info['identifier'];
        $rdata['accesstoken'] = $ext_info['accesstoken'];
        $rdata['expaire_time'] = $ext_info['expaire_time'];
        
        return $rdata;
    }
    
    /*
     * 标识 请求类型 生成路由规则 对应操作方法（默认）
     * save POST game save
     */
    public function save() {
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
}

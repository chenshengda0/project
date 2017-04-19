<?php

namespace app\common\controller;

use think\Db;
use think\Loader;
use think\Controller;
use think\Request;
use app\api\model\Gameclient;
use think\Log;

class Base extends Controller
{
    protected $request, $agent, $client_id, $app_id, $mem_id, $client_key, $agent_id;
    protected $staticurl;
    protected $downurl;
    protected $apiurl;
    protected function _initialize() {
        $this->request = Request::instance();
        
        // 判断请求是否超时
        // return $this->verifytime();
        Log::write($_POST,'error');//记录请求数据
        $this->agent = $this->request->param('agent/s', 'default');
        $this->agent_id = $this->getAgentid($this->agent);
        $this->client_id = $this->request->param('clientid/d', 0);
        $this->app_id = $this->request->param('appid/d', 0);
        $this->client_key = $this->getClientkey($this->client_id);
        if ($this->app_id <= 0) {
            return hs_api_responce('400', '非法请求APP');
        }
        
        if (empty($this->client_key)) {
            return hs_api_responce('400', '客户端非法请求');
        }
        // $mem_id = 100;
        // $data['identify'] = uniqid($mem_id);
        // $data['acesstoken'] = hs_generate_key();
        // $data['client_key'] = $this->client_key;
        
        // $tokenarr['identify'] = hs_auth_code($data['identify'],'ENCODE',$this->client_key);
        // $tokenarr['acesstoken'] = hs_auth_code($data['acesstoken'],'ENCODE',$this->client_key);
        
        // $data['hs_token'] = hs_auth_code(json_encode($tokenarr),'ENCODE',$this->client_key);
        // print_r($data);
        // exit;
        $this->mem_id = $this->verifyUser();
        $this->staticurl = "http://www.shouyoucun.cn";
        $this->downurl = "http:://down.shouyoucun.cn";
    }
    protected function verifytime() {
        $controller = $this->request->controller();
        $needle = strpos($controller, '.');
        if ($needle > 0) {
            $controller = strtolower(substr($controller, $needle + 1));
        }
        if ('startup' != $controller) {
            $reqtime = $this->request->header('timestamp/d', 0);
            $time = time();
            $time_diff = $time - $reqtime;
            if ($time_diff < 0 || $time_diff > 60) {
                return hs_api_responce('422', '请求超时');
            }
        }
    }
    protected function getAgentid($agentname) {
        if (empty($agentname) || 'default' == $agentname) {
            return 0;
        }
        
        $agent_id = DB::name('users')->where(array(
            'user_login' => $agentname 
        ))->value('id');
        if (empty($agent_id)) {
            return 0;
        }
        
        return $agent_id;
    }
    /*
     * 获取访问来源
     */
    protected function getClientkey($client_id = 0) {
        if ($client_id <= 0) {
            return '';
        }
        
        $gc_info = Gameclient::get($client_id);
        if (empty($gc_info->client_key)) {
            return '';
        }
        
        return $gc_info->client_key;
    }
    private function verifyUser() {
        $hs_token = hs_auth_code($this->request->header('hs-token'), 'DECODE', $this->client_key);
        $tokenobj = json_decode($hs_token);
        if (empty($tokenobj)) {
            return 0;
        }
        $tokenarr['identifier'] = hs_auth_code($tokenobj->identify, 'DECODE', $this->client_key);
        $tokenarr['accesstoken'] = hs_auth_code($tokenobj->accesstoken, 'DECODE', $this->client_key);
        if (empty($tokenarr['identifier'])) {
            return 0;
        }
        $map['identifier'] = $tokenarr['identifier'];
        $mxt_info = Db::name('mem_ext')->where($map)->find();
        if (0 != strcmp($mxt_info['accesstoken'], $tokenarr['accesstoken'])) {
            // 用户非法
            return -1;
        }
        
        return $mxt_info['mem_id'];
    }
    
    // 判断玩家是否登录
    protected function isUserLogin() {
        if ($this->mem_id <= 0) {
            return hs_api_responce('403', '玩家未登录');
        }
    }
}
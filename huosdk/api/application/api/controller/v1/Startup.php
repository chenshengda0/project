<?php

/**
 * Startup.php UTF-8
 * 启动控制
 * @date: 2016年8月18日下午9:47:10
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : api 2.0
 */
namespace app\api\controller\v1;

use app\common\controller\Base;
use app\api\model\AppVersion as AppVersionModel;
use app\api\model\Startup as StartupModel;
use think\Db;
use think\Controller;

class Startup extends Base
{
    function _initialize() {
        parent::_initialize();
    }
    /*
     * 标识 请求类型 生成路由规则 对应操作方法（默认）
     * save POST game save
     */
    public function save() {
        $data['ver_id'] = $this->request->post('verid/d', 0);
        $data['mem_id'] = $this->mem_id;
        $data['app_id'] = $this->app_id;
        $data['agentname'] = $this->agent;
        $data['openudid'] = $this->request->post('openudid', '');
        $data['deviceid'] = $this->request->post('deviceid', '');
        $data['devicetype'] = $this->request->post('devicetype', '');
        $data['deviceinfo'] = $this->request->post('deviceinfo', '');
        $data['idfa'] = $this->request->post('idfa/s', '');
        $data['idfv'] = $this->request->post('idfv/s', '');
        $data['mac'] = $this->request->post('mac/s', '');
        $data['resolution'] = $this->request->post('resolution/s', '');
        $data['network'] = $this->request->post('network/s', '');
        $data['userua'] = $this->request->post('userua/s', '');
        if ($data['ver_id'] <= 0) {
            return hs_api_responce(400, '请求参数错误');
        }
        
        $s_model = new StartupModel();
        $s_model->data($data);
        $rs = $s_model->save();
        
        $rdata['time'] = $this->getSertime();
        $rdata['version'] = $this->getNewestVer($data['ver_id'], $data['app_id']);
        $rdata['splash'] = $this->getSplash();
        $rdata['helpinfo'] = $this->getHelpinfo($data['app_id']);
        
        return hs_api_responce(201, '打开成功', $rdata);
    }
    
    // 获取是否有新版本
    public function readNewVer() {
        $verid = $this->request->get('verid/d', 0);
        if ($verid <= 0) {
            return hs_api_responce(400, '请求参数错误');
        }
        $rdata = $this->getNewestVer($verid, $this->app_id);
        if (empty($rdata) || empty($rdata['hasnew'])) {
            return hs_api_responce(404, '无新版本');
        }
        return hs_api_responce(200, '请求成功', $rdata);
    }
    
    // 获取版本信息
    public function readVerinfo() {
        $vdata = array();
        $verid = $this->request->get('verid/d', 0);
        if ($verid <= 0) {
            return hs_api_responce(400, '请求参数错误');
        }
        
        $v_model = new AppVersionModel();
        $ver_info = $v_model::get($verid);
        if (!empty($ver_info)) {
            $vdata['verid'] = $ver_info->id;
            $vdata['vername'] = $ver_info->version;
            $vdata['content'] = $ver_info->content;
            $vdata['updatetime'] = $ver_info->update_time;
            $vdata['url'] = $ver_info->packageurl;
            return hs_api_responce(200, '请求成功', $vdata);
        } else {
            return hs_api_responce(404, '无此版本', $vdata);
        }
    }
    
    // 获取服务器时间
    public function readTime() {
        $time = $this->getSertime();
        $rdata['time'] = $time;
        return hs_api_responce('200', '获取成功', $rdata);
    }
    
    // 获取闪屏图
    public function readSplash() {
        $rdata = array();
        $rdata = $this->getSplash();
        if (empty($rdata) || empty($rdata['count'])) {
            return hs_api_responce('400', '无闪屏图');
        }
        return hs_api_responce('200', '获取闪屏图成功', $rdata);
    }
    
    // 获取客服信息
    public function readHelp() {
        $app_id = $this->app_id;
        $rdata = $this->getHelpinfo($app_id);
        if (empty($rdata)) {
            return hs_api_responce('400', '无客服信息');
        }
        return hs_api_responce('200', '获取成功', $rdata);
    }
    public function getHelpinfo($app_id = 0) {
        if ($app_id < 0) {
            return array();
        }
        $helpModel = DB::name('game_contact');
        $field = "qq,qqgroup,tel,wx,email,service_time servicetime";
        $helpinfo = $helpModel->field($field)->where(array(
            'app_id' => 0 
        ))->whereor(array(
            'app_id' => $app_id 
        ))->order('app_id', 'desc')->find();
        return $helpinfo;
    }
    
    // 获取闪屏图
    public function getSplash() {
        $rdata = array(
            'count' => 0, 
            'list' => array() 
        );
        
        $slide_obj = new Ads();
        $data = $slide_obj->getSlide('app_splash');
        $cnt = count($data);
        if ($cnt > 0) {
            $rdata['count'] = $cnt;
            $rdata['list'] = $data;
        }
        return $rdata;
    }
    
    // 获取服务器时间
    public function getSertime() {
        return time();
    }
    
    // 获取最新版本信息
    public function getNewestVer($verid, $app_id = 0) {
        $vdata = array(
            'hasnew' => 0, 
            'newverid' => $verid, 
            'newurl' => '' 
        );
        if ($app_id <= 0) {
            return $vdata;
        }
        if (empty($verid) || $verid <= 0) {
            return $vdata;
        }
        $v_model = new AppVersionModel();
        $newst_ver = $v_model->getNewver($app_id);
        if(empty($newst_ver)){
            return $vdata;
        }
        
        if ($verid < $newst_ver->id) {
            $vdata['hasnew'] = 1;
            $vdata['newverid'] = $newst_ver->id;
            $vdata['newurl'] = $newst_ver->packageurl;
        }
        return $vdata;
    }
}
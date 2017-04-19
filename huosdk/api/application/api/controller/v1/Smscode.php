<?php

/**
 * Smscode.php UTF-8
 * 短信处理接口
 * @date: 2016年8月25日下午10:10:59
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : H5 2.0
 */
namespace app\api\controller\v1;

use app\common\controller\Base;
use think\Db;
use think\Loader;

class Smscode extends Base
{
    function _initialize() {
        parent::_initialize();
    }
    /*
     * 发送手机验证码
     */
    public function create() {
        $mobile = $this->request->post('mobile/s', '');
        $type = $this->request->post('type/d', 0);
        
        if ($type < 0) {
            return hs_api_responce('400', '参数错误');
        }
        
        if (empty($mobile)) {
            return hs_api_responce(400, '请填写手机号');
        }
        
        $mobile = hs_auth_code($mobile, 'DECODE', $this->client_key);
        
        if (empty($mobile)) {
            return hs_api_responce(400, '请填写手机号');
        }
        
        $checkExpressions = "/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/";
        if (false == preg_match($checkExpressions, $mobile)) {
            return hs_api_responce('400', '手机号格式错误');
        }
        
        $limit_time = 120; // 设定超时时间 2min
        
        $data['mobile'] = $mobile;
        $data['type'] = $type;
        
        // 数据库中查询是否已发送过验证码
        $sms_model = DB::name('sms_log');
        $map['expaire_time'] = array(
            'gt', 
            time() 
        );
        $map['mobile'] = $mobile;
        $sms_info = $sms_model->where($map)->find();
        
        if (!empty($sms_info) && 1 == $sms_info['status']) {
            return hs_api_responce('201', '已发送验证码,请稍后再试');
        }
        
        $data['smscode'] = hs_random_num(4); // 获取随机码
        
        $fs_data = $this->getSms($mobile, $type, $data['smscode']);
        if (0 != $fs_data['code']) {
            // 短信发送失败
            return hs_api_responce('500', $fs_data['msg']);
        } else {
            $data['create_time'] = time();
            $data['expaire_time'] = $data['create_time'] + $limit_time;
            $data['status'] = 1;
            $rdata['sessionid'] = $sms_model->insertGetId($data);
            if (false == $rdata['sessionid']) {
                return hs_api_responce('500', "短信发送成功，服务器内部错误");
            }
            // 发送成功
            return hs_api_responce('200', $fs_data['msg'], $rdata);
        }
    }
    
    /*
     * 验证手机验证码
     */
    public function read() {
        $mobile = $this->request->post('mobile/s', '');
        $smscode = $this->request->post('smscode/s', '');
        $sessionid = $this->request->post('sessionid/d', 0);
        
        $mobile = hs_auth_code($mobile, 'DECODE', $this->client_key);
        if (empty($mobile)) {
            return hs_api_responce(400, '请填写手机号');
        }
        
        if (empty($smscode)) {
            return hs_api_responce('400', '未填验证码');
        }
        
        if (empty($sessionid)) {
            return hs_api_responce('400', '还未发送验证码');
        }
        $checkExpressions = "/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/";
        if (false == preg_match($checkExpressions, $mobile)) {
            return hs_api_responce('400', '手机号格式错误');
        }
        
        $sms_model = Db::name('sms_log');
        
        $sms_info = $sms_model->where(array(
            'id' => $sessionid 
        ))->find();
        
        if (empty($sms_info)) {
            return hs_api_responce('422', '还未发送验证码');
        }
        
        if ($sms_info['expaire_time'] < time()) {
            return hs_api_responce('422', '验证码已过期');
        }
        
        if ($sms_info['smscode'] != $smscode) {
            return hs_api_responce('422', '验证码错误');
        }
        
        if ($sms_info['status'] == 2) {
            return hs_api_responce('422', '验证码已失效');
        }
        
        $sms_info['status'] = 2;
        $rs = $sms_model->update($sms_info);
        if ($rs == false){
            return hs_api_responce('422', '验证码已失效');
        }
        return true;
//         return hs_api_responce('201', '验证成功');
    }
    
    // 获取短信验证码
    public function getSms($mobile, $type, $sms_code) {
        return $this->alidayuSend($mobile, $type, $sms_code);
    }
    
    /*
     * 获取短信验证码,阿里大鱼
     * $mobile 电话号码
     */
    public function alidayuSend($mobile, $type, $sms_code) {
        include APP_PATH . "../extend/taobao/TopSdk.php";
        include APP_PATH . "../extend/taobao/top/TopClient.php";
        include APP_PATH . "../extend/taobao/top/request/AlibabaAliqinFcSmsNumSendRequest.php";
        // 获取阿里大鱼配置信息
        if (file_exists(APP_PATH . "alidayuconfig.php")) {
            $dayuconfig = include APP_PATH . "alidayuconfig.php";
        } else {
            $dayuconfig = array();
        }
        
        if (empty($dayuconfig)) {
            return FALSE;
        }
        
        $product = $dayuconfig['PRODUCT'];
        $content = array(
            "code" => "" . $sms_code, 
            "product" => $product 
        );
        
        $smstemp = 'SMSTEMPAUTH';
        if ($type == 1) {
            $smstemp = 'SMSTEMPREG';
        }
        
        $c = new \TopClient();
        $c->appkey = $dayuconfig['APPKEY'];
        $c->secretKey = $dayuconfig['APPSECRET'];
        $req = new \AlibabaAliqinFcSmsNumSendRequest();
        $req->setExtend($dayuconfig['SETEXTEND']);
        $req->setSmsType($dayuconfig['SMSTYPE']);
        $req->setSmsFreeSignName($dayuconfig['SMSFREESIGNNAME']);
        $req->setSmsParam(json_encode($content));
        $req->setRecNum($mobile);
        $req->setSmsTemplateCode($dayuconfig[$smstemp]);
        
        $resp = $c->execute($req);
        $resp = (array) $resp;
        
        if (!empty($resp['result'])) {
            $result = (array) $resp['result'];
            $data['code'] = (int) $result['err_code'];
            $data['msg'] = '发送成功';
        } else {
            $data['code'] = (int) $resp['code'];
            $data['msg'] = $resp['msg'] . $resp['sub_msg'];
        }
        
        return $data;
    }
}

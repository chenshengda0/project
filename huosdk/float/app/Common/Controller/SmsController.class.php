<?php

/**
 * PhoneController.class.php UTF-8
 * 短信处理函数
 * @date: 2016年9月9日下午5:59:10
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : SMS 2.0
 */
namespace Common\Controller;

use Common\Controller\AppframeController;

class SmsController extends AppframeController
{
    /*
     * 发送手机校验码，登陆注册时
     */
    public function send_auth_code($phone, $type, $expaire_time = 0) {
        // 检查手机号码合法性
        $checkExpressions = "/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/";
        if (false == preg_match($checkExpressions, $phone)) {
            $result['status'] = -1;
            $result['info'] = "手机号不正确";
            return $result;
        }
        
        if (empty($expaire_time)) {
            $limit_time = 120;
        } else {
            $limit_time = $expaire_time; // 设定超时时间 2min
        }
        
        // 先判断是否发送过验证码
        if (isset($_SESSION['mobile']) && $_SESSION['mobile'] == $phone && $_SESSION['sms_time'] + $limit_time > time()) {
            $result['status'] = 1;
            $result['info'] = "已发送过验证码";
            return $result;
        }
        
        $sms_code = rand(1000, 9999); // 获取随机码
        $_SESSION['sms_code'] = $sms_code;
        
        $rs = $this->send_sms_code($phone, $sms_code, $type);
        
        if ($rs) {
            $_SESSION['sms_time'] = time();
            $_SESSION['mobile'] = $phone;
            // 发送成功
            $result['status'] = 1;
            $result['info'] = "短信发送成功";
            return $result;
        } else {
            // 短信发送失败
            $result['status'] = -1;
            $result['info'] = "短信发送失败";
            return $result;
        }
    }
    public function sms_verify_code($phone, $code, $expaire_time = 0) {
        if (empty($expaire_time)) {
            $limit_time = 120;
        } else {
            $limit_time = $expaire_time; // 设定超时时间 2min
        }
        
        if (!$this->checkPhone($phone)) {
            $data = array(
                'status' => '-1', 
                'info' => '手机号错误' 
            );
            return $data;
        }
        
        if (empty($_SESSION['sms_time']) || $_SESSION['sms_time'] + $limit_time < time()) {
            $data = array(
                'status' => '-1', 
                'info' => '验证码已过期,请重新获取' 
            );
            session('sms_time', null);
            session('sms_code', null);
            session('mobile', null);
            return $data;
        }
        // 判断手机号码是否有效
        if (empty($_SESSION['mobile']) || $_SESSION['mobile'] != $phone) {
            $data = array(
                'status' => '-1', 
                'info' => '手机号错误或未填验证码' 
            );
            return $data;
        }
        
        // 验证验证码是否正确
        if (empty($_SESSION['sms_code']) || $_SESSION['sms_code'] != $code) {
            $data = array(
                'status' => '-1', 
                'info' => '验证码错误' 
            );
            return $data;
        }
        
        //清空验证码与验证码时间
        session('sms_time', null);
        session('sms_code', null);
        $data = array(
            'status' => '1',
            'info' => '验证码正确'
        );
        
        return $data;
    }
    
    public function setMemphone($phone){
        $userdata['id'] = sp_get_current_userid();
        $userdata['mobile'] = $phone;
        $rs = M('members')->save($userdata);
        return $rs;
    }
    

    
    /*
     * 检查手机号合法性
     * @param string $phone 手机号
     * @return boole 是否发送成功
     */
    public function checkPhone($phone) {
        // 检查手机号码合法性
        $checkExpressions = "/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/";
        if (false == preg_match($checkExpressions, $phone)) {
            return false;
        }
        return true;
    }
    /**
     * 发送 短信验证码 验证码
     * 
     * @param string $phone 手机号
     * @param string $code 验证码
     * @return boole 是否发送成功
     */
    public function send_sms_code($phone, $code, $type) {
        $check_phone = $this->checkPhone($phone);
        if (!$check_phone) {
            return false;
        }
        $al_rs = $this->send_alidayu_sms_code($phone, $code, $type);
        return $al_rs;
    }
    
    /**
     * 发送 容联云通讯 验证码
     * 
     * @param string $phone 手机号
     * @param string $code 验证码
     * @return boole 是否发送成功
     */
    private function send_ytx_sms_code($phone, $code) {
        // 获取容联云配置信息
        if (file_exists(SITE_PATH . "conf/sms/yuntongxun.php")) {
            $ytx_config = include SITE_PATH . "conf/sms/yuntongxun.php";
        } else {
            return false;
        }
        
        if (empty($ytx_config)) {
            return FALSE;
        }
        
        // 请求地址，格式如下，不需要写https://
        $serverIP = 'app.cloopen.com';
        // 请求端口
        $serverPort = '8883';
        // REST版本号
        $softVersion = '2013-12-26';
        // 主帐号
        $accountSid = $ytx_config['RONGLIAN_ACCOUNT_SID'];
        // 主帐号Token
        $accountToken = $ytx_config['RONGLIAN_ACCOUNT_TOKEN'];
        // 应用Id
        $appId = $ytx_config['RONGLIAN_APPID'];
        
        $rest = new \Org\Xb\Rest($serverIP, $serverPort, $softVersion);
        $rest->setAccount($accountSid, $accountToken);
        $rest->setAppId($appId);
        // 发送模板短信
        $result = $rest->sendTemplateSMS($phone, array(
            $code, 
            5 
        ), 59939);
        if ($result == NULL) {
            return false;
        }
        if ($result->statusCode != 0) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * 发送 容联云通讯 验证码
     * 
     * @param string $phone 手机号
     * @param string $code 验证码
     * @param int $type 发送类型
     * @return boole 是否发送成功
     */
    private function send_alidayu_sms_code($phone, $code, $type) {
        include VENDOR_PATH . "taobao/TopSdk.php";
        include VENDOR_PATH . "taobao/top/TopClient.php";
        include VENDOR_PATH . "taobao/top/request/AlibabaAliqinFcSmsNumSendRequest.php";
        
        // 获取阿里大鱼配置信息
        if (file_exists(SITE_PATH . "conf/sms/alidayu.php")) {
            $dayuconfig = include SITE_PATH . "conf/sms/alidayu.php";
        } else {
            return FALSE;
        }
        
        if (empty($dayuconfig)) {
            return FALSE;
        }
        
        $product = $dayuconfig['PRODUCT'];
        $content = array(
            "code" => "" . $code, 
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
        $req->setRecNum($phone);
        $req->setSmsTemplateCode($dayuconfig[$smstemp]);
        $resp = $c->execute($req);
        
        $resp = (array) $resp;
        if (!empty($resp['result'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
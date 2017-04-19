<?php
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

$sms_lang = array(
	'bizCode'	=>	'业务代码',

);
$config = array(
	'bizCode'	=>	array(
	'INPUT_TYPE'	=>	'0',
	'VALUES'	=> 	''
	),
	
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'FM';
    /* 名称 */
    $module['name']    = "2号短信平台";
    $module['lang']  = $sms_lang;
    $module['config'] = $config;	
    $module['server_url'] = 'http://222.77.181.24/sms/sendSMS.do';

    return $module;
}

// 企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
require_once APP_ROOT_PATH."system/sms/FM/transport.php"; 

class FM_sms implements sms
{
	public $sms;
	public $message = "";
	
    public function __construct($smsInfo = '')
    { 	    	
		if(!empty($smsInfo))
		{			
			$this->sms = $smsInfo;
		}
    }
	
	public function sendSMS($mobile_number,$content)
	{
		if(is_array($mobile_number))
		{
			$mobile_number = implode(",",$mobile_number);
		}
		$sms = new transport();
				
		$params = array(
			"corpId"=>$this->sms['user_name'],
			"checkKey"=>MD5($this->sms['user_name'].",".MD5($this->sms['password']).",".$this->sms['config']['bizCode']),
			"mobile"=>$mobile_number,
			"msg"=>urlencode($content),
			"bizCode"=>$this->sms['config']['bizCode']
		);
		
		$result = $sms->request($this->sms['server_url'],$params);
		$smsStatus = json_decode($result['body']);
		
		
		if($smsStatus->result=="true")
		{
			$result['status'] = 1;
		}
		else
		{
			$result['status'] = 0;
			$result['msg'] = $smsStatus->errorNo.":".$smsStatus->errorInfo;
		}
		return $result;
	}
	
	public function getSmsInfo()
	{	

		return "2号短信平台";	
		
	}
	
	public function check_fee()
	{
		$sms = new transport();
				
		$params = array(
						"corpId"=>$this->sms['user_name'],
						"password"=>md5($this->sms['password'])
					);
					
		$url = "http://222.77.181.24/sms/queryCorpBalance.do";
		$result = $sms->request($url,$params);
		$result = json_decode($result['body']);
		
		$str = "短信平台，剩余：".$result->balance."条";	

		return $str;

	}
}
?>
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
    $module['class_name']    = 'ZS';
    /* 名称 */
    $module['name']    = "中舜短信接口";
    $module['lang']  = $sms_lang;
    $module['config'] = $config;	
    $module['server_url'] = 'http://124.172.250.160/WebService.asmx?op=mt';

    return $module;
}

// 企信通短信平台
require_once ROOT_PATH."system/libs/sms.php";  //引入接口
require_once ROOT_PATH."system/sms/ZS/transport.php"; 

class ZS_sms implements sms
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
			"Sn"=>$this->sms['username'],
			"Pwd"=>$this->sms['password'],
			"mobile"=>$mobile_number,
			"content"=>$content
		);
		$result = $sms->request($this->sms['server_url'],$params);
		$smsStatus = $result;
		
		$result=array();
		if($smsStatus->mtResult=="0")
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

		return "中舜短信平台";	
		
	}
	
	public function check_fee()
	{
		$sms = new transport();
				
		$params = array(
						"Sn"=>$this->sms['user_name'],
						"Pwd"=>$this->sms['password']
					);
					
		$url = "http://124.172.250.160/WebService.asmx?op=balance";
		$result = $sms->request($url,$params);
		
		$str = "短信平台，剩余：".$result->balanceResult."条";	

		return $str;

	}
}
?>
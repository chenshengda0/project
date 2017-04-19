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
    $module['class_name']    = 'MD';
    /* 名称 */
    $module['name']    = "漫道短信接口[推荐]";
    $module['lang']  = $sms_lang;
    $module['config'] = $config;	
    $module['server_url'] = 'http://sdk9.mdjc.net.cn:8888/sms.aspx';

    return $module;
}

// 企信通短信平台
require_once ROOT_PATH."system/libs/sms.php";  //引入接口
require_once ROOT_PATH."system/sms/MD/transport.php"; 

class MD_sms implements sms
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
		$sms = new transport();
				
		$params = array(
	        "userid"=>$this->sms['userid'],
			"account"=>$this->sms['username'],
			"password"=>$this->sms['password'],
			"mobile"=>$mobile_number,
			"content"=>$content,
			"action"=>"send",
			"sendTime"=>"",
			"extno"=>""
		);
		$result = $sms->request($this->sms['server_url'],$params);
		
		$p = xml_parser_create();
		xml_parse_into_struct($p, $result, $vals, $index);
		xml_parser_free($p);
		$result = array();
		foreach($vals as $k=>$v){
			if($v['tag'] == "RETURNSTATUS"){
				if($v['value'] == "Success"){
				    $result['status'] = 1;
				}else{
				    $result['status'] = 0;
				}
			}
		}
		
		return $result;
	}
	
	public function getSmsInfo()
	{	

		return "漫道短信平台";	
		
	}
	
	public function check_fee()
	{return false;
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
<?php
/**
* 公用类
*
* @author
*/

class CommonAction extends Action {
	public $website;
	public $appkeysign;
	public $sign;
	public $_departid;
	public $AUTHCODE;

	/**
	 * 构造函数
	 * 
	 * @return void
	 */
    public function __construct(){
    	parent::__construct();
		
    	$this->sign = "admin";  //用于密码加密
		$this->AUTHCODE = '1hb0ffeSZ6MxXxA5Fl';
    	$this->appkeysign = "syc_sdk_2016";
		$this->website = "http://shouyoucun.cn";
		$this->assign("WEBSITE",$this->website);
		
    }
    
    
    /**
     * 操作成功或者失败之后的提示及跳转
     * 
     * @return void
     */
    public function prompt($msg, $url)
    {
    	echo '<script language="javascript">';
        echo '	alert("' . $msg . '");';
        echo "	window.location.href='" . $url . "'";
        echo '</script>';
    }
    
    /**
     * 
     * 获取游戏列表
     */
    public function getGameList() {
    	$model = D('Game');
    	$rs = $model->field("id,name")->findAll();
    	return $rs;
    }
    
   
	
    
	/**
     * 
     * 根据用户名查找对应的渠道包名（公会的权限设置）
     * @param $username 用户名
     */
    public function getAgentByuser($username) {
    	//$department_id = $this->getDepartmantid($username);
    	$agentstr= '';
    	$packagedata = array();
    	
    	$agenter = M('sdkagenter');
    	$agenterdata = $agenter->field("id")->where("agenter='".$username."'")->findAll();
    	$package = M('sdkpackage');
    	$packagedata = $package->field("package,rate")->where("agenterid=".$agenterdata[0]['id'])->findAll();
    	if ($packagedata) {
    		foreach ($packagedata as $key=>$val) {
    			$agentstr .= "'".$val['package']."',";
    		}
    		$agentstr= substr($agentstr, 0,-1);
    	}
    	
    	$data['agentstr'] = $agentstr;
    	$data['packagedata'] = $packagedata;
    	return $data;
    }
    
    /**
     * 
     * 获取部门id
     * @param $username 用户名
     */
    public function getDepartmantid($username) {
    	$admin = M('admin');
    	$admindata = $admin->field("department_id")->where("username='".$username."'")->findAll();
    	return $admindata[0]['department_id'];
    }
    
    /**
	 * 获取当前页面完整URL地址
	 */
	public function get_url() {
	    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
	    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
	}
	
	/**
     * 
     * 获取游戏收成比率
     */
    public function getGamearr() {
    	$model = M('spgame');
    	$gamedata = $model->findAll();
    	foreach ($gamedata as $key=>$val) {
    		$gamearr[$val['gameid']] = $val['rate'];
    	}
    	
    	return $gamearr;
    }
    
    /**
     * 提示信息及跳转
     * 
     * @param $msg			string 提示信息
     * @param $gourl		string 跳转地址
     * @param $onlymsg		
     * @param $limittime	int 停留时间
     */
	public function ShowMsg($msg,$gourl,$onlymsg=0,$limittime=0)
	{
		$htmlhead  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">'."\r\n<head>\r\n<title>系统提示</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n";
		$htmlhead .= "<base target='_self'/>\r\n</head>\r\n<body leftmargin='0' topmargin='0'>\r\n<center>\r\n";
		$htmlhead .= "<script>\r\n";
		$htmlfoot  = "</script>\r\n</center>\r\n</body>\r\n</html>\r\n";

		if($limittime==0){
            $litime = 1000;
        }elseif($limittime==1) {
            $litime = 0;
        }else{
            $litime = $limittime;
        }
        
		if($gourl == "-1"){
			$gourl = "javascript:history.go(-1);";
		}

		if($gourl==""||$onlymsg==1){
			$msg = "<script>alert(\"".str_replace("\"","“",$msg)."\");</script>";
		} else {
			$func = "      var pgo=0;
      function JumpUrl(){
        if(pgo==0){ location='$gourl'; pgo=1; }
      }\r\n";
			$rmsg = $func;
			$rmsg .= "document.write(\"<br/><br/><br/><div style='width:450px;padding:0px;border:1px solid #D1DDAA;'><div style='padding:6px;font-size:12px;border-bottom:1px solid #cccccc;background:#fff393 url(/wbg.gif)';'><b>系统提示信息！</b></div>\");\r\n";
			$rmsg .= "document.write(\"<div style='width:450px;height:100;font-size:10pt;background-color:#efefef'><br/><br/>\");\r\n";
			$rmsg .= "document.write(\"".str_replace("\"","“",$msg)."\");\r\n";
			$rmsg .= "document.write(\"";
			if($onlymsg==0){
				if($gourl!="javascript:;" && $gourl!=""){ $rmsg .= "<br/><br/><a href='".$gourl."'>如果你的浏览器没反应，请点击这里...</a>"; }
				$rmsg .= "<br/><br/></div>\");\r\n";
				if($gourl!="javascript:;" && $gourl!=""){ $rmsg .= "setTimeout('JumpUrl()',$litime);"; }
			}else{ $rmsg .= "<br/><br/></div>\");\r\n"; }
			$msg  = $htmlhead.$rmsg.$htmlfoot;
		}

        if($limittime==1) $msg="<script>location='".$gourl."';</script>";
		
		echo $msg;	
	}

	//加密
	function pw_auth_code($pw,$authcode=''){
		if(empty($authcode)){
			$authcode=$this->AUTHCODE;
		}
		$result=md5(md5($authcode.$pw).$pw);
		return $result;
	}
}
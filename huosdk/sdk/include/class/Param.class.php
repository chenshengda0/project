<?php
class Param{
    // 1	a	appid	游戏APPID
    public static function verifyappid($appid){
        $code=0;
        $appid = intval($appid);
        if (empty($appid) || $appid <0) {
            $code = -1;
        }
        return $code;
    }
    
    //2	b	username	充值玩家账号    
    public static function verifyusername($username){
        if (empty($username)) {
            return -2;
        }else{
            //用户名必须为数字字母组合, 长度在6-16位之间
            $checkExpressions = "/^[a-zA-Z0-9]+$/i";
            $len = strlen($username);
            if ($len<6 || $len>16 || false == preg_match($checkExpressions, $username)){
                return -2;
            }
        }
        
        return 0;
    }
    
    //3	c	imei	标识客户信息IMEI    长度50字符内
    public static function verifyimei($imei){
        $code=0;
        if (!empty($imei)){
            $strlen = strlen($imei);
            if (50 < $strlen){
                $code = -3;
            }
        }
        return $code;
    }
    
    //4	d	from	来源信息	1 ANDROID、2 H5、3 IOS
    public static function verifyfrom($from){
        $code=0;
        $from = intval($from);
        //来源数据  1 ANDROID、2 H5、3 IOS
        if (1 != $from && 2 != $from && 3 != $from){
            $code = -4;
        }
        return $code;
    }
    
    //5	e	agentgame	玩家所属渠道    
    public static function verifyagentgame($agentgame){
        if (!empty($agentgame)){
            $strlen = strlen($agentgame);
            if (30 < $strlen){
                return -5;
            }
        }
        return 0;
    }
    
    //6	f  	deviceinfo	设备数据,包括手机号码、用户系统版本，以||隔开    
    public static function verifydeviceinfo($deviceinfo){
        $code=0;
        if (!empty($deviceinfo)){
            $len = strlen($deviceinfo);
            if ($len > 100) {
                $code = -6;
            }
        }
        return $code;
    }
    
    //7	g	userua	用户使用的移动终端的UA信息    200内
    public static function verifyuserua($userua){
        if (!empty($userua)){
            $len = strlen($userua);
            if ($len > 200) {
                return -7;
            }
        }
        return 0;
    }
    
    //8	h	password	用户密码    长度在6-16位之间
    public static function verifypassword($password){
        if (empty($password)){
            return -8;
        }

        $len = strlen($password);        
        if ($len<6 || $len>16){
            return -8;
        }
        
        return 0;
    }
    
    //9	i	server	游戏区服信息  
    public static function verifyserver($server){
        if (empty($server)){
            return -9;
        }

        $len = strlen($server);        
        if ($len>30){
            return -9;
        }
        
        return 0;
    }
    
    //10	j	payway	支付方式   
    public static function verifypayway($payway){
        if (empty($payway)) {
            return -10;
        }
        return 0;
    }
    
    //11	k	productname	充值商品名称    
    public static function verifyproductname($productname){
        if (!empty($productname)){
            $len = strlen($productname);
            if ($len > 200) {
                return -11;
            }
        }
        return 0;
    }
    
    //12	l	productdesc	充值商品描述    
    public static function verifyproductdesc($productdes){
        if (!empty($productdes)){
            $len = strlen($productdes);
            if ($len > 400) {
                return -12;
            }
        }
        return 0;
    }
    
    //13	m	attach	CP扩张参数    
    public static function verifyattach($attach){
        if (!empty($attach)){
            $len = strlen($attach);
            if ($len > 200) {
                return -13;
            }
        }
        return 0;
    }
    
    //14	n	ptbnum yxbnum	平台币 游戏币数量    
    public static function verifynum($num){
        $num = intval($num);
        if (empty($num) || $num <0) {
            return -14;
        }
        return 0;
    }
    
    //15	o	amount	充值金额    
    public static function verifyamount($amount){
	    if (empty($amount)) {
	        return -15;
	    }
	    
	    $checkExpressions = "/^[0-9]+(.[0-9]{1,2})?$/";
	    if (false == preg_match($checkExpressions, $amount)){
	        return -15;
	    }else{
	        $amount = round((float)$amount,2);
	        /* 金额小于0.01 返回错误 */
	        if ($amount<0.01){
	            return -15;
	        }
	    }
	    
	    return 0;
    }
    
    //16	p	role	玩家角色    
    public static function verifyrole($role){
        if (empty($role)){
            return -16;
        }

        $len = strlen($role);        
        if ($len>64){
            return -16;
        }
        
        return 0;
    }
    
    //17	q	issend	 是否发送验证码
    public static function verifyissend($issend){
        $issend = intval($issend);
        if (0 != $issend && 1 != $issend) {
            return -17;
        }
        return 0;
    }
    
    //18	r	sendcode	验证码 
    public static function verifysendcode($sendcode){
        if (empty($sendcode)){
            return -18;
        }

        $len = strlen($sendcode);        
        if ($len>10){
            return -18;
        }
        
        return 0;
    }
    
    //19	s	userphone	用户手机号
    public static function verifyuserphone($userphone){
        if (empty($userphone)) {
            return -19;
        }
         
        $checkExpressions = "/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/";
        if (false == preg_match($checkExpressions, $userphone)){
            return -19;
        }
        
        return 0;
    }
    
    //20	t	paystatus 	充值状态    0 待充值  1充值成功 2 充值失败
    public static function verifypaystatus($paystatus){
        $paystatus = intval($paystatus);
        if (0 != $paystatus && 1 != $paystatus && 2 != $paystatus ) {
            return -20;
        }
        return 0;
    }
    
    //21	u	page	查询页数
    public static function verifypage($page){
        $page = intval($page);
        if (empty($page) || $page < 0 ) {
            return -21;
        }
        return 0;
    }
    
    //22	v	userid	玩家ID    
    public static function verifyuserid($mem_id){
        $mem_id = intval($mem_id);
        if (empty($mem_id) || $mem_id < 0 ) {
            return -22;
        }
        return 0;
    }
    
    //23	w	code	随机码    
    public static function verifycode($randcode){
        $randcode = intval($randcode);
        if ($randcode < 0 ) {
            return -23;
        }
        return 0;
    }
    
    //24	x	client_id	SDKID
    public static function verifyclientid($function, $client_id,$db=NULl){
        $client_id = intval($client_id);

        if ($client_id < 0){
            return -24;
        }
        if ('init' == $function) {
            if (empty($db)){
                return -1000;
            }
            $data = $db->getClient($client_id);
            if (empty($data)){
                return -24;
            }else{
                $_SESSION['clientkey'] = $data['client_key'];
                $_SESSION['client_id'] = $data['id'];
                $_SESSION['is_switch'] = $data['is_switch'];
                $_SESSION['client_status'] = $data['status'];
                $_SESSION['gh_id'] = $data['gv_id'];
                $_SESSION['gh_new_id'] = $data['gv_new_id'];
                $_SESSION['new_url'] = $data['new_url'];
            }
        }else{
            if (empty($_SESSION['client_id']) || $client_id != $_SESSION['client_id']){
                return -24;
            }
        }
        return 0;
    }
    
    //25	y	api_token	校验接口合法性    
    public static function verifyapitoken($function,$apitoken,$checkcode,$clientkey){
        if (empty($apitoken)){
            return -25;
        }
        
        //若clientkey为空则错误
        if (empty($clientkey)){
            return -24;
        }
        //方法名+日期+checkcode+clientkey
	    $date = date("Y-m-d");
	    $verifytoken = md5($function.$date.$checkcode.$clientkey);
	    
	    if ($apitoken != $verifytoken){
	        return -25;
	    }
        return 0;
    }
    
    //26	z	user_token	玩家登陆时获取	登陆时获取的user_token    
    public static function verifyusertoken($usertoken){
        if (empty($usertoken) || empty($_SESSION['user_token'])){
            return -26;
        }
        
	    $verifytoken = $_SESSION['user_token'];
	    if ($usertoken != $verifytoken){
	        return -26;
	    }
        return 0;
    }
    
    //27	az	identity_key	校验码	用于校验所有参数    
    public static function verifyparam($urldata, $clientkey,$function=NULL){
		return 0;
    	//校验参数有效性
	    $paramstr = "a=".$urldata['a']."&b=".$urldata['b']."&c=".$urldata['c']."&d=".$urldata['d'];
	    $paramstr .= "&e=".$urldata['e']."&f=".$urldata['f']."&g=".$urldata['g']."&h=".$urldata['h'];
	    $paramstr .= "&i=".$urldata['i']."&j=".$urldata['j']."&k=".$urldata['k']."&l=".$urldata['l'];
	    $paramstr .= "&m=".$urldata['m']."&n=".$urldata['n']."&o=".$urldata['o']."&p=".$urldata['p'];
	    $paramstr .= "&q=".$urldata['q']."&r=".$urldata['r']."&s=".$urldata['s']."&t=".$urldata['t'];
	    $paramstr .= "&u=".$urldata['u']."&v=".$urldata['v']."&w=".$urldata['w']."&x=".$urldata['x'];
	    $paramstr .= "&y=".$urldata['y']."&z=".$urldata['z']."&ab=".$urldata['ab']."&ac=".$urldata['ac'];
	    
	    //参数校验使用方法md5(md5($paramstr).$clientkey);
        $params_key = md5(md5($paramstr).$clientkey);
	    if ($urldata['az'] != $params_key){
			$log = SITE_PATH ."sdk/include/class/logs/verifyparam.txt";
			file_put_contents($log, json_encode(array($urldata,session_id(),$function,$params_key,$paramstr,1)));	
	        return -27;
	    }

	    return 0;
    }
    
    //28	ab	version	STRING(10)	版本号	版本号      长度限制10位,数字小数点组合
    public static function verifyversion($version){
        if (empty($version)) {
            $code = -28;
        }else{
            $checkExpressions = "/^\d+(\.\d+){0,2}$/";
            $len = strlen($version);
            if ($len>10 || false == preg_match($checkExpressions, $version)){
                $code = -2;
            }
        }
        
        return 0;
    }
    
    //29	ac	ipaddrid	INT	ip归属地市代号	中国广东广州市
    public static function verifyipcityid($ipaddrid){
        $ipaddrid = intval($ipaddrid);
        
        if ($ipaddrid<0) {
            $code = -29;
        }
        
        return 0;
    }
    
    //30	ad	pay_token	string	Y	android原接口使用	用于校验是否是此次支付
    public static function verifypaytoken($pay_token, $amount, $db){
        if (empty($_SESSION['pay_token']) || empty($_SESSION['order_id'])){
            return -35;
        }
        
        if ($pay_token != $_SESSION['pay_token']){
            return -35;
        }
        
        $order_id = $_SESSION['order_id'];
        $paydata = $db->getPayinfo($order_id);
        if (2 == $paydata['status']){
            return -35;
        }
        
        if (round($amount,2) != round($paydata['amount'],2)){
            return -15;
        }
        
        return 0;
    }
}
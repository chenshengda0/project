<?php
class Response{
	/**
	* 按综合通信方式输出数据
	* $code 状态码   
	* $message 提示信息
	* $data 数据
	* $secret_code 加密码
	* $clientkey SDK key
	* $zip 是否压缩
	*/	
	const JSON = 'json';  //定义一个常亮
	const IS_ZIP = TRUE;  //定义压缩常亮
	public static function show($code, $data=array(), $message='',$secret_code=0,$clientkey=NULL,$is_zip=self::IS_ZIP,$type=self::JSON){
		if(!is_numeric($code)){
			return '';
		}
		
		//优先取传入参数,若为空， 则从$_SESSION中取
		if (empty($clientkey)){
		    if (!empty($_SESSION['clientkey'])){
		        $clientkey = $_SESSION['clientkey'];
		    }
		}
		
		$rdata = array();
		if (!empty($data)){
		    $rdata['y'] = json_encode($data);
		    $rdata['z'] = md5(md5($rdata['y'].$clientkey).$secret_code);
		}
		
		//传回参数，校验参数有效性
		$type = isset($_GET['format']) ? $_GET['format'] : self::JSON;
		$result = array(
			'code'=>$code,
			'data'=>$rdata,
			'msg'=>$message,
		);

		if($type == 'json'){
			self::json($code,$data,$message,$secret_code,$is_zip);
			exit;
		}elseif($type == 'array'){
			echo "这里仅是调试模式，不能进行数据传输使用<br/>++++++++++++++++++++++++++++++++++++++<pre>";
			print_r($result);
			echo "</pre>++++++++++++++++++++++++++++++++++++++";
		}else{
			echo "抱歉，暂时未提供此种数据格式";
			//扩展对象或其他方式等
		}
	}
	
	/**
	* 按json格式封装数据
	* $code 状态码
	* $message 提示信息
	* $data 数据
	*/
	public static function json($code,$data=array(), $message='', $secret_code=0,$is_zip=self::IS_ZIP){
		if(!is_numeric($code)){
			return '';
		}
		$result = array(
			'code'=>$code,			
			'data'=>$data,
			'msg'=>$message,
		);
		
     	echo self::compression(json_encode($result), $secret_code,$is_zip);
 		exit;
	}
	
	/**
	 * 压缩并加密
	 * $str 要压缩加密的字符串
	 * $secret_code 加密码
	 * 
	 */
	public static function compression($str,$secret_code, $is_zip=self::IS_ZIP) {
	    //return $str;
		$arr = self::fixedArr();
		$str = base64_encode($str);
		$str = self::encrypt($str,$arr,$secret_code);
		if ($is_zip){
			return gzencode($str,9);
		}
		return $str;
	}
	
	/**
	 * 解压并解密
	 * $str 要解压解密的字符串
	 * $initcode 初始加密码
	 */
	public static function decompression($str, $initcode = 0,$is_zip=self::IS_ZIP) {
	    if (empty($str) || false == is_string($str)){
	        return '';
	    }
		$arr = self::fixedArr();
		if ($is_zip){
		    $str = gzinflate(substr($str,10,-8));
		}
		
		if (empty($_SESSION['code1'])){
		    $code = $initcode;
		}else{
		    $code = $_SESSION['code1'];
		}
		
		$tmp = self::decrypt($str,$arr,$code);
		return base64_decode($tmp);
	}
	
	//加密的固定数组
	public static function fixedArr() {
		$arr = array('0', '1', '2', '3', '4', '5', '6', '7', '8',
				'9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
				'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y',
				'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
				'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y',
				'Z', '*', '!' ,'/', '+', '=','#'
		);
		return $arr;
	}
	
	/**
	 * 加密函数
	 * $str 加密的字符串
	 * $arr 固定数组
	 * $code 
	 */
	public static function encrypt($str,$arr, $code=0) {
		if ($str == null) {
			return "";
		}
	
		$rsstr = "z";
		$toarr = str_split($str);
		$arrlenght = count($arr);
		for ($i=0;$i<count($toarr);$i++) {
		    //$code 随机加密符
		    $k = ($i % $arrlenght + $code % $arrlenght) % $arrlenght;
			$string = ord($toarr[$i]) + ord($arr[$k]);
			$rsstr .= $string."_";
		}
	
		$rsstr = substr($rsstr,0,-1);
		$rsstr .= "m";
		return $rsstr;
	}
	
	/**
	 * 解密函数
	 * $str 解密的字符串
	 * $arr 固定数组
	 * $code 数组起始符
	 */
	public static function decrypt($str, $arr, $code=0) {
	    if ($str == '') {
	        return '';
	    }
	    
	    $first = substr($str,0,1);
	    $end = substr($str,-1);
	
	    if ($first == 'z' && $end == 'm') {
	        $str = substr($str,1,-1);
	        $toarr = explode("_",$str);
	        $arrlenght = count($arr);
	        $rsstr = '';
	        for ($i=0;$i<count($toarr);$i++) {
	            //$code 随机加密符
	            $k = ($i % $arrlenght+ $code % $arrlenght) % $arrlenght;
	            $string = $toarr[$i] - ord($arr[$k]);
	            $rsstr .= chr($string);
	        }
	        return $rsstr;
	    } else {
	        return "";
	    }
	}
	
	public static function payback($url, $params){
	    $params = json_encode($params);	

	    $curl = curl_init();//初始化curl
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);//设置传送的参数

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		        'Content-Type: application/json',
		        'Content-Length: ' . strlen($params))
		);
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);//设置等待时间

		
		$rs = curl_exec($curl);//运行curl
		$rs = strtoupper($rs);
		$result = 0;
		if ( $rs == 'SUCCESS') {
			$result = 1;
		} else {
			$result = 0;
		}
		curl_close($curl);//关闭curl
		
		return $result;
	}
	
	public static function data_log($filename,$data){
		if(!file_exists($filename))
			system("cd . > ".$filename);
		$file=fopen($filename,"a+");
		if(!fwrite($file,$data)){
			$time=date("Y-m-d H:i:s");
			fwrite($file,$time." -> 记录错误。\n");
		}
		fclose($file);
	}
	
	public static function verify($function,$db=NULL){
		
	    $error_code = include 'error_code.php';
	    $functionparams = include 'functionarray.php';
	    
	    $urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"]:'';
	    $object = self::decompression($urldata);
	    if (empty($object)){
	        unset($urldata);
	        $urldata['code'] = -999;
	        $urldata['msg'] = "参数错误";
	        return $urldata;
	    }
	    $urldata = get_object_vars(json_decode($object));
		Response::data_log('userdata.log',date('Y-m-d H:i:s')." l= ".$function." e= ".$urldata['e']." ---w= ".$urldata['w']." ---x= ".$urldata['x'].' ---b= '.$urldata['b']."\n");

	    $checkarr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','ab','ac','ad','ae','af','ag','ah');
	    foreach ($checkarr as $v){
	        if (!isset($urldata["$v"])){
	            $urldata["$v"] = '';
	        }
	    }
		
	    //4	d	from	来源信息	1 ANDROID、2 H5、3 IOS
	    $code = Param::verifyfrom($urldata['d']);
	    if ($code < 0){
	        $urldata['code'] = $code;
	        $urldata['msg'] = $error_code[$code];
	        return $urldata;
	    }
	    
	    //23	w	code	随机码   
	    $code = Param::verifycode($urldata['w']);
	    if ($code < 0){
	        $urldata['code'] = $code;
	        $urldata['msg'] = $error_code[$code];
	        return $urldata;
	    }
	    
	    //24	x	client_id	SDKID
	    $code = Param::verifyclientid($function,$urldata['x'],$db);
	    if ($code < 0){
	        $urldata['code'] = $code;
	        $urldata['msg'] = $error_code[$code];
	        return $urldata;
	    }
	    $clientkey = $_SESSION['clientkey'];
	    
	    //25	y	api_token	校验接口合法性
	    $code = Param::verifyapitoken($function,$urldata['y'],$urldata['w'],$clientkey);
	    if ($code < 0){
	        $urldata['code'] = $code;
	        $urldata['msg'] = $error_code[$code];
	        return $urldata;
	    }
	    
	    //27	az	identity_key	校验码	用于校验所有参数
	    $code = Param::verifyparam($urldata,$clientkey, $function);
	    if ($code < 0){
	        $urldata['code'] = $code;
	        $urldata['msg'] = $error_code[$code];			
	        return $urldata;
	    }
	    
	    //校验参数
	    foreach ($functionparams[$function] as $v){
            switch ($v){
                case 1:{
                    $code = Param::verifyappid($urldata['a']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 2:{
                    if (isset($urldata['ae']) && intval($urldata['ae'])>0){
                        break;
                    }
                    $code = Param::verifyusername($urldata['b']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 3:{
                    $code = Param::verifyimei($urldata['c']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 4:{
                    break;
                }
                case 5:{
                    $code = Param::verifyagentgame($urldata['e']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 6:{
                    $code = Param::verifydeviceinfo($urldata['f']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 7:{
                    $code = Param::verifyuserua($urldata['g']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 8:{
                    if (isset($urldata['q']) && 0 == $urldata['q']){
                        break;
                    }
                    if (isset($urldata['ae']) && intval($urldata['ae'])>0){
                        break;
                    }
                    $code = Param::verifypassword($urldata['h']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 9:{
                    $code = Param::verifyserver($urldata['i']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 10:{
                    $code = Param::verifypayway($urldata['j']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 11:{
                    $code = Param::verifyproductname($urldata['k']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 12:{
                    $code = Param::verifyproductdesc($urldata['l']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 13:{
                    $code = Param::verifyattach($urldata['m']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 14:{
                    $code = Param::verifynum($urldata['n']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 15:{
                    $code = Param::verifyamount($urldata['o']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 16:{
                    $code = Param::verifyrole($urldata['p']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 17:{
                    $code = Param::verifyissend($urldata['q']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 18:{
                    if (isset($urldata['q']) && 0 == $urldata['q']){
                        break;
                    }
                    $code = Param::verifysendcode($urldata['r']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 19:{
                    $code = Param::verifyuserphone($urldata['s']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 20:{
                    $code = Param::verifypaystatus($urldata['t']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 21:{
                    $code = Param::verifypage($urldata['u']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 22:{
                    $code = Param::verifyuserid($urldata['v']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 23:{
                    break;
                }
                case 24:{
                    break;
                }
                case 25:{
                    break;
                }
                case 26:{
                    $code = Param::verifyusertoken($urldata['z']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 27:{
                    break;
                }
                case 28:{
                    $code = Param::verifyversion($urldata['ab']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 29:{
                    $code = Param::verifyipcityid($urldata['ac']);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                case 35:{
                    $code = Param::verifypaytoken($urldata['ai'],$urldata['o'], $db);
                    if ($code < 0){
                        $urldata['code'] = $code;
                        $urldata['msg'] = $error_code[$code];
                        return $urldata;
                    }
                    break;
                }
                default:{
                    $urldata['code'] = -100;
                    $urldata['msg'] = "参数错误";
                    return $urldata;
                }
            }
        }
        return $urldata;
	}
	
	
}
?>
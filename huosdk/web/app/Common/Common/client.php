<?php
if(!defined('IN_SYS')) {
	exit('Access Denied');
}

function outPut() {
	global $tal;
	print "<pre>";
	print_r($tpl);
	print "</pre>";
}

function daddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

function dheader($string, $replace = true, $http_response_code = 0) {
	$string = str_replace(array("\r", "\n"), array('', ''), $string);
	if(empty($http_response_code) || PHP_VERSION < '4.3' ) {
		@header($string, $replace);
	} else {
		@header($string, $replace, $http_response_code);
	}
	if(preg_match('/^\s*location:/is', $string)) {
		exit();
	}
}

function isemail($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}


function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}

function removedir($dirname, $keepdir = FALSE) {

	$dirname = wipespecial($dirname);

	if(!is_dir($dirname)) {
		return FALSE;
	}
	$handle = opendir($dirname);
	while(($file = readdir($handle)) !== FALSE) {
		if($file != '.' && $file != '..') {
			$dir = $dirname . DIRECTORY_SEPARATOR . $file;
			is_dir($dir) ? removedir($dir) : unlink($dir);
		}
	}
	closedir($handle);
	return !$keepdir ? (@rmdir($dirname) ? TRUE : FALSE) : TRUE;
}


function writelog($file, $log) {
	global $timestamp, $_DCACHE;
	$yearmonth = gmdate('Ym', $timestamp + $_DCACHE['settings']['timeoffset'] * 3600);
	$logdir = SYS_ROOT.'./forumdata/logs/';
	$logfile = $logdir.$yearmonth.'_'.$file.'.php';
	if(@filesize($logfile) > 2048000) {
		$dir = opendir($logdir);
		$length = strlen($file);
		$maxid = $id = 0;
		while($entry = readdir($dir)) {
			if(strexists($entry, $yearmonth.'_'.$file)) {
				$id = intval(substr($entry, $length + 8, -4));
				$id > $maxid && $maxid = $id;
			}
		}
		closedir($dir);

		$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
		@rename($logfile, $logfilebak);
	}
	if($fp = @fopen($logfile, 'a')) {
		@flock($fp, 2);
		$log = is_array($log) ? $log : array($log);
		foreach($log as $tmp) {
			fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>'), '', $tmp)."\n");
		}
		fclose($fp);
	}
}

function dfopen($url, $limit = 500000, $post = '', $cookie = '', $bysocket = FALSE) {
	global $version, $boardurl;
	if(ini_get('allow_url_fopen') && !$bysocket && !$post) {
		$fp = @fopen($url, 'r');
		$s = @fread($fp, $limit);
		@fclose($fp);
		return $s;
	}
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$script = $matches['path'].'?'.$matches['query'].'#'.$matches['fragment'];
	$port = !empty($matches['port']) ? $matches['port'] : 80;
	if($post) {
		$out = "POST $script HTTP/1.1\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Accept-Encoding: none\r\n";
		$out .= "User-Agent: Comsenz/1.0 ($version)\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $script HTTP/1.1\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Accept-Encoding:\r\n";
		$out .= "User-Agent: Comsenz/1.0 ($version)\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	if(!$fp) {
		return "";
	} else {
		@fwrite($fp, $out);
		while(!feof($fp) && $limit > -1) {
			$limit -= 524;
			$return .= @fread($fp, 524);
		}
		@fclose($fp);
		$return = preg_replace("/\r\n\r\n/", "\n\n", $return, 1);
		$strpos = strpos($return, "\n\n");
		$strpos = $strpos !== FALSE ? $strpos + 2 : 0;
		$return = substr($return, $strpos);
		return $return;
	}
}

function getVisitFrom($type = 'url') {
	$c_time = 3600;
	
	switch ($type) {
		case 'url':
			$from_url = $_SERVER["HTTP_REFERER"];
			$domain	  = 'http://'.$_SERVER["HTTP_HOST"];
			!$from_url and $from_url = $domain.$_SERVER['PHP_SELF'];
			$from_url == $domain.'/' and $from_url = 'ֱ��������ַ';
			$click = intval($_COOKIE['__click__']);
			setcookie('__click__',$click+1,time()+$c_time,"/");
			if ($click == 0) {
				setcookie('__from_url__',$from_url,time()+$c_time,"/");
			}
			break;
	}
}

/*
���·�ҳ
*/
function cpage($str)
{
	$str=keys($str);

	$pageurl=$_GET['page'];
	
	$allurl= "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?{$_SERVER['QUERY_STRING']}";

	$allurl=str_replace("&page=".$pageurl,'',$allurl);
	
	$expstr=explode('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>',$str);

	$m=1;

	for($k=0;$k<count($expstr);$k++)
	{
		if(($pageurl-1)==$k)
		{
			$data=$expstr[$k];
		}
		
		$p.="<a href='$allurl&page=".$m."'>[ $m ]</a>&nbsp;&nbsp;";

		$m++;
	}

	if(!$data)
	{
		$data=$expstr[0];
		$pageurl=1;
	}

	$st="<a href='$allurl&page=".$pageurl."'>[ ".$pageurl." ]</a>";


	$p=str_replace($st,'[ '.$pageurl.' ]',$p);
	
	if(count($expstr)>1)
	{
		$br=$data."<br><br><div align='right' id=page >".$p.'</div>';
	}else{
		$br=$data;
	}
	
	return $br;
}

/*
 * ���Ľ�ȡ��֧��gb2312,gbk,utf-8,big5 
 *
 * @param string $str Ҫ��ȡ���ִ�
 * @param int $start ��ȡ��ʼλ��
 * @param int $length ��ȡ����
 * @param string $charset utf-8|gb2312|gbk|big5 ����
 * @param $suffix �Ƿ��β׺
 */
function csubstr($str, $start=0, $length, $cut_charset="GBK", $suffix=true) {
	if(function_exists("mb_substr"))
		return mb_substr($str, $start, $length, $cut_charset);
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."��";
	return $slice;
}

function usertab($uname,$s=true)
{
	$uname=strtolower($uname);
	$c1=substr($uname,0,1);
	$c2=substr($uname,-1);
	$n=ord($c1)+ord($c2);
	$l=strlen($uname);
	$n+=$l*$l;
	if($s){
		return '7y_user_'.$n%20;
	}else{
		return $n%20;
	}
}

//ת��IP
function myip2long($ip){
   $ip_arr = split('\.',$ip);
   $iplong = (16777216 * intval($ip_arr[0])) + (65536 * intval($ip_arr[1])) + (256 * intval($ip_arr[2])) + intval($ip_arr[3]);
   $iplong = sprintf("%u",$iplong);
   return $iplong;
}

// ȡ�õ�ǰIP
function GetIP($type=0){
	if(!empty($_SERVER["HTTP_CLIENT_IP"])) $cip = $_SERVER["HTTP_CLIENT_IP"];
	else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	else if(!empty($_SERVER["REMOTE_ADDR"])) $cip = $_SERVER["REMOTE_ADDR"];
	else $cip = "";
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = $cips[0] ? $cips[0] : 'unknown';
	unset($cips);
	if ($type==1) $cip = myip2long($cip);
	return $cip;
}

function auth_code($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 0;

	$key = md5($key ? $key : '9e13yK8RN2M0lKP8CLRLhGs468d1WMaSlbDeCcI');
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	//$box = range(0, 255);
	$box = 100;

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
/**
 * 获取联系与图标
 */
function getcontact() {
	mysql_select_db($dbname);
	$sql = "SELECT * FROM c_contact where id=1 ";
	$rs = mysql_query($sql);

	$data = array();
	while ($row = mysql_fetch_assoc($rs)){
		$data[] = $row;
	}

	return $data;
}

?>
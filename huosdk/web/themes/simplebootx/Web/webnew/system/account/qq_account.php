<?php

/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
	
	$module['code'] = "qq";
	$module['name'] = "QQ互联";
	$module['description'] = "QQ互联,QQ登录成功后跳转的地址为：".url().'index.php/User/callback/name/qq';
	
	/* 配置信息 */
    $module['config']  = array(
        array('name' => 'appid',           'type' => 'text',   'value' => ''),
        array('name' => 'appkey',          'type' => 'text',   'value' => '')
    );

	return $module;
}

$accounts = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."account_conf WHERE code = 'qq'");
$account = unserialize($accounts['config']);

//申请到的appid
$_SESSION["appid"]    = $account['appid'];

//申请到的appkey
$_SESSION["appkey"]   = $account['appkey'];

//QQ登录成功后跳转的地址,请确保地址真实可用，否则会导致登录失败。
$_SESSION["callback"] = url().'index.php/User/callback/name/qq';

//QQ授权api接口.按需调用
//$_SESSION["scope"] = "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";
$_SESSION["scope"] = "get_user_info";


//登录
function qq_login()
{
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
    $login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" 
        . $_SESSION["appid"] . "&redirect_uri=" . urlencode($_SESSION["callback"])
        . "&state=" . $_SESSION['state']
        . "&scope=".$_SESSION["scope"];
    header("Location:$login_url");
}


//回调地址
function qq_callback()
{
	//debug
	//print_r($_REQUEST);
	//print_r($_SESSION);

	if($_REQUEST['state'] == $_SESSION['state']) //csrf
	{
		$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
				. "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
				. "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];
		//echo $token_url;
		$response = file_get_contents($token_url);
		//echo $response;exit;
		if (strpos($response, "callback") !== false)
		{
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg = json_decode($response);
			if (isset($msg->error))
			{
				echo "<h3>error:</h3>" . $msg->error;
				echo "<h3>msg  :</h3>" . $msg->error_description;
				exit;
			}
		}

		$params = array();
		parse_str($response, $params);

		//debug
		//print_r($params);

		//set access token to session
		$_SESSION["access_token"] = $params["access_token"];

	}
	else
	{
		echo("The state does not match. You may be a victim of CSRF.");
	}
}

function get_openid()
{
	$graph_url = "https://graph.qq.com/oauth2.0/me?access_token="
			. $_SESSION['access_token'];

	$str  = file_get_contents($graph_url);
	if (strpos($str, "callback") !== false)
	{
		$lpos = strpos($str, "(");
		$rpos = strrpos($str, ")");
		$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
	}

	$user = json_decode($str);
	if (isset($user->error))
	{
		echo "<h3>error:</h3>" . $user->error;
		echo "<h3>msg  :</h3>" . $user->error_description;
		exit;
	}

	//debug
	//echo("Hello " . $user->openid);
	
	//set openid to session
	$_SESSION["openid"] = $user->openid;
}

//第三方用户信息
function get_user_info()
{
	$get_user_info = "https://graph.qq.com/user/get_user_info?"
			. "access_token=" . $_SESSION['access_token']
			. "&oauth_consumer_key=" . $_SESSION["appid"]
			. "&openid=" . $_SESSION["openid"]
			. "&format=json";
	$info = file_get_contents($get_user_info);
	$arr = json_decode($info, true);

	return $arr;
}





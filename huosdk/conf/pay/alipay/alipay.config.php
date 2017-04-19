<?php
/**
 * 配置文件
 */
return array(
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//合作身份者id，以2088开头的16位纯数字
'alipay_config_partner'	=> '2088512857502955',

//安全检验码，以数字和字母组成的32位字符
'alipay_config_key'	=> 'hf4wr9r67172yo09k7frnjhkks05i8mn',

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

//签名方式 不需修改
'alipay_config_sign_type' => strtoupper('MD5'),

//字符编码格式 目前支持 gbk 或 utf-8
'alipay_config_input_charset' => strtolower('utf-8'),

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
'alipay_config_cacert'   => getcwd().'\\conf\\cacert.pem',

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
'alipay_config_transport'  => 'http',

//支付类型
"payment_type" => "1",
//必填，不能修改
//服务器异步通知页面路径
"notify_url" => "http://www.shouyoucun.cn/index.php/Web/pay/notify_url",
//需http://格式的完整路径，不能加?id=>123这类自定义参数

//页面跳转同步通知页面路径
"return_url" => "http://www.shouyoucun.cn/index.php/Web/pay/return_url",
//需http://格式的完整路径，不能加?id=>123这类自定义参数，不能写成http://localhost/

//卖家支付宝帐户
"seller_email" => "yulangen@163.com",
//必填

//商户订单号
//"out_trade_no" => $_POST['orderid'],
//商户网站订单系统中唯一订单号，必填

//订单名称
"subject" => "购买平台币",

//付款金额
//$total_fee => $_POST['amount'],
//必填

//订单描述

"body" => "购买平台币",
//商品展示地址
"show_url" => "http://www.shouyoucun.cn",
//需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

//防钓鱼时间戳
"anti_phishing_key" => "",
//若要使用请调用类文件submit中的query_timestamp函数

//客户端的IP地址
"exter_invoke_ip" => "",
//非局域网的外网IP地址，如：221.0.0.1

);

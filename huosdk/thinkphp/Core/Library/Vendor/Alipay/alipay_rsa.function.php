<?php
/* *
 * RSA
 * 详细：RSA加密
 * 版本：3.3
 * 日期：2014-02-20
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
*/

/**
 * 签名字符串
 * @param $prestr 需要签名的字符串
 * return 签名结果
*/
function rsaSign($prestr) {
    $config=C('ALIPAY_CONFIG');
    $private_key=file_get_contents($config['private_key_path']);
    $pkeyid=openssl_get_privatekey($private_key);
    openssl_sign($prestr, $sign, $pkeyid);
    openssl_free_key($pkeyid);
    $sign=base64_encode($sign);
    return $sign;
}

/**
 * 验证签名
 * @param $prestr 需要签名的字符串
 * @param $sign 签名结果
 * return 签名结果
*/
function rsaVerify($prestr, $sign) {
    $config=C('ALIPAY_CONFIG');
    $pubKey=file_get_contents($config['public_key_path']);  
    $res=openssl_get_publickey($pubKey);
    if ($res) {
        $verify=openssl_verify($prestr, base64_decode($sign), $res);
        openssl_free_key($res);
    }
    if($verify == 1){
        return true;
    }else{
        return false;
    }
 }
 /**
 * RSA解密
 * @param $content 需要解密的内容，密文
 * @param $private_key_path 商户私钥文件路径
 * return 解密后内容，明文
 */
function rsaDecrypt($content, $private_key_path) {
    $priKey = file_get_contents($private_key_path);
    $res = openssl_get_privatekey($priKey);
	//用base64将内容还原成二进制
    $content = base64_decode($content);
	//把需要解密的内容，按128位拆开解密
    $result  = '';
    for($i = 0; $i < strlen($content)/128; $i++  ) {
        $data = substr($content, $i * 128, 128);
        openssl_private_decrypt($data, $decrypt, $res);
        $result .= $decrypt;
    }
    openssl_free_key($res);
    return $result;
}
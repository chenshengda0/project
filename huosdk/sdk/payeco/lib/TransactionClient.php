<?php

/**
 * 易联服务器交易接口调用API封装，分别对以下接口调用进行了封装；
 * 接口封装了参数的转码（中文base64转码）、签名和验证签名、通讯和通讯报文处理
 * 1、商户订单下单接口
 * 2、商户订单查询接口
 * 3、商户订单冲正接口
 * 4、订单结果通知签名验证
 */
class TransactionClient {

	/**
	 * 商户订单下单接口
	 * @param merchantId:		商户代码
	 * @param merchOrderId	:	商户订单号
	 * @param amount		:	商户订单金额
	 * @param orderDesc		:	商户订单描述	  字符最大128，中文最多40个；参与签名：采用UTF-8编码提交参数：采用UTF-8的base64格式编码
	 * @param tradeTime		:	商户订单提交时间
	 * @param expTime		:	交易超时时间； 超过订单超时时间未支付，订单作废；不提交该参数，采用系统的默认时间（从接收订单后超时时间为30分钟）
	 * @param notifyUrl		:	异步通知URL ； 提交参数时，做URLEncode处理
	 * @param extData		:	商户保留信息； 通知结果时，原样返回给商户；字符最大128，中文最多40个；参与签名：采用UTF-8编码 ； 提交参数：采用UTF-8的base64格式编码
	 * @param miscData		:	订单扩展信息   根据不同的行业，传送的信息不一样；参与签名：采用UTF-8编码，提交参数：采用UTF-8的base64格式编码
	 * @param notifyFlag	:	订单通知标志    0：成功才通知，1：全部通知（成功或失败）  不填默认为“1：全部通知”
	 * @param priKey		:	商户签名的私钥
	 * @param pubKey        :   易联签名验证公钥
	 * @param payecoUrl		：	易联服务器URL地址，只需要填写域名部分
	 * @param retXml        :   通讯返回数据；当不是通讯错误时，该对象返回数据
	 * @return 				:  处理状态码： 0000 : 处理成功， 其他： 处理失败
	 * @throws Exception    :  E101:通讯失败； E102：签名验证失败；  E103：签名失败；
	 */
	static function  MerchantOrder($merchantId, $merchOrderId, 
			$amount, $orderDesc ,$tradeTime, $expTime, $notifyUrl,
			$extData, $miscData, $notifyFlag, $priKey, $pubKey, 
			$payecoUrl, Xml $retXml) {
		//交易参数
		$tradeCode = "PlaceOrder";
		$version = ConstantsClient::getCommIntfVersion();
		
		//进行数据签名
		$signData = "Version=".$version."&MerchantId=".$merchantId."&MerchOrderId=".$merchOrderId 
				."&Amount=".$amount."&OrderDesc=".$orderDesc."&TradeTime=".$tradeTime."&ExpTime="
				.$expTime."&NotifyUrl=".$notifyUrl."&ExtData=".$extData
				."&MiscData=".$miscData."&NotifyFlag=".$notifyFlag;
		
		// 私钥签名
		Log::logFile("PrivateKey=".$priKey);
		Log::logFile("data=".$signData);
		$sign = Signatory::rsaSign($signData, $priKey);
		if(Tools::isStrEmpty($sign)){
			throw new Exception("E103");
		}
		Log::logFile("sign=".$sign);

		//提交参数包含中文的需要做base64转码，通知地址做URLEncoder处理
		$orderDesc64 = base64_encode($orderDesc);
		$extData64 = base64_encode($extData);
		$miscData64 = base64_encode($miscData);
		$notifyUrlEn = urlencode($notifyUrl);
		$data64 = "Version=".$version."&MerchantId=".$merchantId."&MerchOrderId=".$merchOrderId 
				."&Amount=".$amount."&OrderDesc=".$orderDesc64."&TradeTime=".$tradeTime."&ExpTime="
				.$expTime."&NotifyUrl=".$notifyUrlEn."&ExtData=".$extData64
				."&MiscData=".$miscData64."&NotifyFlag=".$notifyFlag;

		//通讯报文
		$url = $payecoUrl."/ppi/merchant/itf.do"; //下订单URL
		$url = $url."?TradeCode=".$tradeCode."&".$data64."&Sign=".$sign;
		Log::logFile("url=".$url);
		$retStr = HttpClient::getHttpResponseGET($url, ConstantsClient::getCaCertFileName());
		Log::logFile("retStr=".$retStr);
		if(Tools::isStrEmpty($retStr)){
			throw new Exception("E101");
		}

		//返回数据的返回码判断
		$retXml->setXmlData($retStr);
		$retCode = Tools::getXMLValue($retStr, "retCode");
		Log::logFile("retCode=".$retCode);
		
		$retXml->setRetCode($retCode);
		$retXml->setRetMsg(Tools::getXMLValue($retStr, "retMsg"));
		if(strcmp("0000", $retCode)){
			return $retCode;
		}
		//获取返回数据
		$retVer = Tools::getXMLValue($retStr, "Version");
		$retMerchantId = Tools::getXMLValue($retStr, "MerchantId");
		$retMerchOrderId = Tools::getXMLValue($retStr, "MerchOrderId");
		$retAmount = Tools::getXMLValue($retStr, "Amount");
		$retTradeTime = Tools::getXMLValue($retStr, "TradeTime");
		$retOrderId = Tools::getXMLValue($retStr, "OrderId");
		$retSign = Tools::getXMLValue($retStr, "Sign");
		
		//设置返回数据
		$retXml->setTradeCode($tradeCode);
		$retXml->setVersion($retVer);
		$retXml->setMerchantId($retMerchantId);
		$retXml->setMerchOrderId($retMerchOrderId);
		$retXml->setAmount($retAmount);
		$retXml->setTradeTime($tradeTime);
		$retXml->setOrderId($retOrderId);
		$retXml->setSign($retSign);
		
		//验证签名的字符串
		$backSign = "Version=".$retVer."&MerchantId=".$retMerchantId."&MerchOrderId=".$retMerchOrderId 
				 ."&Amount=".$retAmount."&TradeTime=".$retTradeTime."&OrderId=".$retOrderId;
		//验证签名
		$retSign = str_replace(" ", "+", $retSign);
		$b = Signatory::rsaVerify($backSign, $pubKey, $retSign);
		Log::logFile("PublicKey=".$pubKey);
		Log::logFile("data=".$backSign);
		Log::logFile("Sign=".$retSign);
		Log::logFile("验证结果=".$b);
		if($b==false){
			throw new Exception("E102");
		}
		return $retCode;
	}
	
	/**
	 * 商户订单查询接口
	 * @param merchantId:		商户代码
	 * @param merchOrderId	:	商户订单号
	 * @param tradeTime		:	商户订单提交时间
	 * @param priKey		:	商户签名的私钥
	 * @param pubKey        :   易联签名验证公钥
	 * @param payecoUrl		：	易联服务器URL地址，只需要填写域名部分
	 * @param retXml        :   通讯返回数据；当不是通讯错误时，该对象返回数据
	 * @return 				: 处理状态码： 0000 : 处理成功， 其他： 处理失败
	 * @throws Exception    :  E101:通讯失败； E102：签名验证失败；  E103：签名失败；
	 */
	static function  OrderQuery($merchantId, $merchOrderId, $tradeTime, 
			$priKey, $pubKey, $payecoUrl, Xml $retXml) {
		//交易参数
		$tradeCode = "QueryOrder";
		$version = ConstantsClient::getCommIntfVersion();
		
	    //进行数据签名
	    $signData = "Version=".$version."&MerchantId=".$merchantId."&MerchOrderId=".$merchOrderId 
	            ."&TradeTime=".$tradeTime;
	    
	    // 私钥签名
		Log::logFile("PrivateKey=".$priKey);
		Log::logFile("data=".$signData);
		$sign = Signatory::rsaSign($signData, $priKey);
		if(Tools::isStrEmpty($sign)){
			throw new Exception("E103");
		}
		Log::logFile("sign=".$sign);

		//通讯报文
	    $url= $payecoUrl."/ppi/merchant/itf.do?TradeCode=".$tradeCode; //查询订单URL
	    $url = $url."&".$signData."&Sign=".$sign;
	    Log::logFile("url=".$url);
	    $retStr = HttpClient::getHttpResponseGET($url, ConstantsClient::getCaCertFileName());
		Log::logFile("retStr=".$retStr);
		if(Tools::isStrEmpty($retStr)){
			throw new Exception("E101");
		}

		//返回数据的返回码判断
		$retXml->setXmlData($retStr);
		$retCode = Tools::getXMLValue($retStr, "retCode");
		$retXml->setRetCode($retCode);
		$retXml->setRetMsg(Tools::getXMLValue($retStr, "retMsg"));
		if(strcmp("0000", $retCode)){
			return $retCode;
		}
		//获取返回数据
		$retVer = Tools::getXMLValue($retStr, "Version");
		$retMerchantId = Tools::getXMLValue($retStr, "MerchantId");
		$retMerchOrderId = Tools::getXMLValue($retStr, "MerchOrderId");
		$retAmount = Tools::getXMLValue($retStr, "Amount");
		$retExtData = Tools::getXMLValue($retStr, "ExtData");
		if($retExtData != null){
			$retExtData = str_replace(" ", "+", $retExtData);
			$retExtData = base64_decode($retExtData); 
		}
		$retOrderId = Tools::getXMLValue($retStr, "OrderId");
		$retStatus = Tools::getXMLValue($retStr, "Status");
		$retPayTime = Tools::getXMLValue($retStr, "PayTime");
		$retSettleDate = Tools::getXMLValue($retStr, "SettleDate");
		$retSign = Tools::getXMLValue($retStr, "Sign");
		//设置返回数据
		$retXml->setTradeCode($tradeCode);
		$retXml->setVersion($retVer);
		$retXml->setMerchantId($retMerchantId);
		$retXml->setMerchOrderId($retMerchOrderId);
		$retXml->setAmount($retAmount);
		$retXml->setExtData($retExtData);
		$retXml->setOrderId($retOrderId);
		$retXml->setStatus($retStatus);
		$retXml->setPayTime($retPayTime);
		$retXml->setSettleDate($retSettleDate);
		$retXml->setSign($retSign);
		  
		//验证签名的字符串
		$backSign = "Version=".$retVer."&MerchantId=".$retMerchantId."&MerchOrderId=".$retMerchOrderId 
		 ."&Amount=".$retAmount."&ExtData=".$retExtData."&OrderId=".$retOrderId
		 ."&Status=".$retStatus."&PayTime=".$retPayTime."&SettleDate=".$retSettleDate;

		//验证签名
		$retSign = str_replace(" ", "+", $retSign);
		$b = Signatory::rsaVerify($backSign, $pubKey, $retSign);
		Log::logFile("PublicKey=".$priKey);
		Log::logFile("data=".$backSign);
		Log::logFile("Sign=".$retSign);
		Log::logFile("验证结果=".$b);
		if($b==false){
			throw new Exception("E102");
		}
		return $retCode;
	}
	
	
	/**
	 * 商户订单冲正接口
	 * @param merchantId:		商户代码
	 * @param merchOrderId	:	商户订单号
	 * @param amount        :   订单金额
	 * @param tradeTime		:	商户订单提交时间
	 * @param priKey		:	商户签名的私钥
	 * @param pubKey        :   易联签名验证公钥
	 * @param payecoUrl		：	易联服务器URL地址，只需要填写域名部分
	 * @param retXml        :   通讯返回数据；当不是通讯错误时，该对象返回数据
	 * @return 				: 处理状态码： 0000 : 处理成功， 其他： 处理失败
	 * @throws Exception    :  E101:通讯失败； E102：签名验证失败；  E103：签名失败；
	 */
	static function  OrderReverse($merchantId, $merchOrderId, $amount, $tradeTime, 
			$priKey, $pubKey, $payecoUrl, Xml $retXml) {
		//交易参数
		$tradeCode = "QuashOrder";
		$version = ConstantsClient::getCommIntfVersion();
		
	    //进行数据签名
	    $signData = "Version=".$version."&MerchantId=".$merchantId."&MerchOrderId=".$merchOrderId 
	            ."&Amount=".$amount."&TradeTime=".$tradeTime;
	    
	    // 私钥签名
		Log::logFile("PrivateKey=".$priKey);
		Log::logFile("data=".$signData);
		$sign = Signatory::rsaSign($signData, $priKey);
		if(Tools::isStrEmpty($sign)){
			throw new Exception("E103");
		}
		Log::logFile("sign=".$sign);

		//通讯报文
	    $url= $payecoUrl."/ppi/merchant/itf.do?TradeCode=".$tradeCode; //查询订单URL
	    $url = $url."&".$signData."&Sign=".$sign;
	    Log::logFile("url=".$url);
	    $retStr = HttpClient::getHttpResponseGET($url, ConstantsClient::getCaCertFileName());
		Log::logFile("retStr=".$retStr);
		if(Tools::isStrEmpty($retStr)){
			throw new Exception("E101");
		}

		//返回数据的返回码判断
		$retXml->setXmlData($retStr);
		$retCode = Tools::getXMLValue($retStr, "retCode");
		$retXml->setRetCode($retCode);
		$retXml->setRetMsg(Tools::getXMLValue($retStr, "retMsg"));
		if(strcmp("0000", $retCode)){
			return $retCode;
		}
		
		//获取返回数据
		$retVer = Tools::getXMLValue($retStr, "Version");
		$retMerchantId = Tools::getXMLValue($retStr, "MerchantId");
		$retMerchOrderId = Tools::getXMLValue($retStr, "MerchOrderId");
		$retAmount = Tools::getXMLValue($retStr, "Amount");
		$retStatus = Tools::getXMLValue($retStr, "Status");
		$retTradeTime = Tools::getXMLValue($retStr, "TradeTime");
		$retSign = Tools::getXMLValue($retStr, "Sign");
		
		//设置返回数据
		$retXml->setTradeCode($tradeCode);
		$retXml->setVersion($retVer);
		$retXml->setMerchantId($retMerchantId);
		$retXml->setMerchOrderId($retMerchOrderId);
		$retXml->setAmount($retAmount);
		$retXml->setStatus($retStatus);
		$retXml->setSign($retSign);
  
		//验证签名的字符串
		$backSign = "Version=".$retVer."&MerchantId=".$retMerchantId."&MerchOrderId=".$retMerchOrderId 
				 ."&Amount=".$retAmount."&Status=".$retStatus."&TradeTime=".$retTradeTime;
		
		//验证签名
		$retSign = str_replace(" ", "+", $retSign);
		$b = Signatory::rsaVerify($backSign, $pubKey, $retSign);
		Log::logFile("PublicKey=".$priKey);
		Log::logFile("data=".$backSign);
		Log::logFile("Sign=".$retSign);
		Log::logFile("验证结果=".$b);
		if($b==false){
			throw new Exception("E102");
		}
		return $retCode;
	}

	/**
	 * 验证订单结果通知签名
	 * @param version       ： 通讯版本号
	 * @param merchantId    ： 商户代码
	 * @param merchOrderId  ：商户订单号
	 * @param amount		： 商户订单金额
	 * @param extData		：商户保留信息； 通知结果时，原样返回给商户；字符最大128，中文最多40个；参与签名：采用UTF-8编码 ； 提交参数：采用UTF-8的base64格式编码
	 * @param orderId		：易联订单号
	 * @param status		：订单状态
	 * @param payTime		：订单支付时间
	 * @param settleDate	：订单结算日期
	 * @param sign			：签名数据
	 * @param pubKey		：易联签名验证公钥
	 * @return				： true：验证通过； false：验证不通过
	 * @throws Exception
	 */
	static function  bCheckNotifySign($version, $merchantId, 
			$merchOrderId, $amount, $extData, $orderId, 
			$status, $payTime, $settleDate, $sign, $pubKey) {
		// 对extData进行转码处理: base64转码
		if ($extData != null) {
			$extData = str_replace(" ", "+", $extData);
			$extData = base64_decode($extData);
			Log::logFile("extData=".$extData); // 日志输出，检查转码是否正确
		}
		 
		// 进行数据签名
		$data = "Version=".$version."&MerchantId=".$merchantId
				."&MerchOrderId=".$merchOrderId."&Amount=".$amount
				."&ExtData=".$extData."&OrderId=".$orderId."&Status="
				.$status."&PayTime=".$payTime."&SettleDate=".$settleDate;

		// 验证签名
		$sign = str_replace(" ", "+", $sign);
		$b = Signatory::rsaVerify($data, $pubKey, $sign);
		Log::logFile("PublicKey=".$pubKey);
		Log::logFile("data=".$data);
		Log::logFile("Sign=".$sign);
		Log::logFile("验证结果=".$b);
		return $b;
	}
	
	/**
	 * 商户订单下单接口(H5版本)，本接口比SDK的下单接口增加了【returnUrl】和【clientIp】参数
	 * @param merchantId        商户代码
	 * @param merchOrderId      商户订单号
	 * @param amount            商户订单金额，单位为元，格式： nnnnnn.nn
	 * @param orderDesc         商户订单描述    字符最大128，中文最多40个；参与签名：采用UTF-8编码
	 * @param tradeTime         商户订单提交时间，格式：yyyyMMddHHmmss，超过订单超时时间未支付，订单作废；不提交该参数，采用系统的默认时间（从接收订单后超时时间为30分钟）
	 * @param expTime           交易超时时间，格式：yyyyMMddHHmmss， 超过订单超时时间未支付，订单作废；不提交该参数，采用系统的默认时间（从接收订单后超时时间为30分钟）
	 * @param notifyUrl         异步通知URL
	 * @param returnUrl         同步通知URL
	 * @param extData           商户保留信息； 通知结果时，原样返回给商户；字符最大128，中文最多40个；参与签名：采用UTF-8编码
	 * @param miscData          订单扩展信息   根据不同的行业，传送的信息不一样；参与签名：采用UTF-8编码
	 * @param notifyFlag        订单通知标志    0：成功才通知，1：全部通知（成功或失败）  不填默认为“1：全部通知”
	 * @param clientIp          针对配置了防钓鱼的商户需要提交，商户服务器通过获取访问ip得到该参数
	 * @param priKey            商户签名的私钥
	 * @param pubKey            易联签名验证公钥
	 * @param payecoUrl         易联服务器URL地址，只需要填写域名部分
	 * @param retXml            通讯返回数据；当不是通讯错误时，该对象返回数据
	 * @return  处理状态码： 0000 : 处理成功， 其他： 处理失败
	 * @throws Exception        E101:通讯失败； E102：签名验证失败；  E103：签名失败；
	 */
	static function MerchantOrderH5($merchantId, $merchOrderId,
	$amount, $orderDesc, $tradeTime, $expTime,
	$notifyUrl, $returnUrl, $extData,
	$miscData, $notifyFlag, $clientIp, $priKey,	$pubKey, 
	$payecoUrl, Xml $retXml) {
		//交易参数
		$tradeCode = "PayOrder";
		$version = ConstantsClient::getCommIntfVersion();
	
		//进行数据签名
		$signData = "Version=".$version."&MerchantId=".$merchantId."&MerchOrderId=".$merchOrderId
				."&Amount=".$amount."&OrderDesc=".$orderDesc."&TradeTime=".$tradeTime."&ExpTime="
				.$expTime."&NotifyUrl=".$notifyUrl."&ReturnUrl=".$returnUrl."&ExtData=".$extData
				."&MiscData=".$miscData."&NotifyFlag=".$notifyFlag."&ClientIp=".$clientIp;
	
		// 私钥签名
		Log::logFile("PrivateKey=".$priKey);
		Log::logFile("data=".$signData);
		$sign = Signatory::rsaSign($signData, $priKey);
		if(Tools::isStrEmpty($sign)){
			throw new Exception("E103");
		}
		Log::logFile("sign=".$sign);

		//提交参数包含中文的需要做base64转码
		$orderDesc64 = base64_encode($orderDesc);
		$extData64 = base64_encode($extData);
		$miscData64 = base64_encode($miscData);
		//通知地址做URLEncoder处理
		$notifyUrlEn = urlencode($notifyUrl);
		$returnUrlEn = urlencode($returnUrl);
	
		$data64 = "Version=".$version."&MerchantId=".$merchantId."&MerchOrderId=".$merchOrderId
				 ."&Amount=".$amount."&OrderDesc=".$orderDesc64."&TradeTime=".$tradeTime
				 ."&ExpTime=".$expTime."&NotifyUrl=".$notifyUrlEn."&ReturnUrl=".$returnUrlEn
				 ."&ExtData=".$extData64."&MiscData=".$miscData64."&NotifyFlag=".$notifyFlag
				 ."&ClientIp=".$clientIp;

		//通讯报文
		$url= $payecoUrl."/ppi/merchant/itf.do"; //下订单URL
		$url = $url."?TradeCode=".$tradeCode."&".$data64."&Sign=".$sign;
		Log::logFile("url=".$url);
		$retStr = HttpClient::getHttpResponseGET($url, ConstantsClient::getCaCertFileName());
		Log::logFile("retStr=".$retStr);
		if(Tools::isStrEmpty($retStr)){
			throw new Exception("E101");
		}
		
		//返回数据的返回码判断
		$retXml->setXmlData($retStr);
		$retCode = Tools::getXMLValue($retStr, "retCode");
		Log::logFile("retCode=".$retCode);
		
		$retXml->setRetCode($retCode);
		$retXml->setRetMsg(Tools::getXMLValue($retStr, "retMsg"));
		if(strcmp("0000", $retCode)){
			return $retCode;
		}

		//获取返回数据
		$retVer = Tools::getXMLValue($retStr, "Version");
		$retMerchantId = Tools::getXMLValue($retStr, "MerchantId");
		$retMerchOrderId = Tools::getXMLValue($retStr, "MerchOrderId");
		$retAmount = Tools::getXMLValue($retStr, "Amount");
		$retTradeTime = Tools::getXMLValue($retStr, "TradeTime");
		$retOrderId = Tools::getXMLValue($retStr, "OrderId");
		$retVerifyTime = Tools::getXMLValue($retStr, "VerifyTime");
		$retSign = Tools::getXMLValue($retStr, "Sign");

		//设置返回数据
		$retXml->setTradeCode($tradeCode);
		$retXml->setVersion($retVer);
		$retXml->setMerchantId($retMerchantId);
		$retXml->setMerchOrderId($retMerchOrderId);
		$retXml->setAmount($retAmount);
		$retXml->setTradeTime($tradeTime);
		$retXml->setOrderId($retOrderId);
		$retXml->setVerifyTime($retVerifyTime);
		$retXml->setSign($retSign);

		//验证签名的字符串
		$backSign = "Version=".$retVer."&MerchantId=".$retMerchantId."&MerchOrderId=".$retMerchOrderId
					."&Amount=".$retAmount."&TradeTime=".$retTradeTime
					."&OrderId=".$retOrderId."&VerifyTime=".$retVerifyTime;
		//验证签名
		$retSign = str_replace(" ", "+", $retSign);
		$b = Signatory::rsaVerify($backSign, $pubKey, $retSign);
		Log::logFile("PublicKey=".$pubKey);
		Log::logFile("data=".$backSign);
		Log::logFile("Sign=".$retSign);
		Log::logFile("验证结果=".$b);
		if($b==false){
			throw new Exception("E102");
		}
		return $retCode;
	}
	
	
	/**
	 * 生成订单支付重定向地址
	 * @param payecoUrl		：	易联服务器URL地址，只需要填写域名部分
	 * @param retXml 下单成功后的通讯返回数据
	 * @return
	 */
	static function getPayInitRedirectUrl($payecoUrl, Xml $retXml) {
		$tradeId = "h5Init";
		$version = ConstantsClient::getCommIntfVersion();
		$merchantId = $retXml->getMerchantId();         //商户代码
		$merchOrderId = $retXml->getMerchOrderId();     //商户订单号
		$amount = $retXml->getAmount();                 //商户订单金额，单位为元，格式： nnnnnn.nn
		$tradeTime = $retXml->getTradeTime();           //商户订单提交时间
		$orderId = $retXml->getOrderId();               //易联订单号
		$verifyTime = $retXml->getVerifyTime();         //验证时间戳
		$sign = $retXml->getSign();                     //签名,下单时返回的签名
	
		$datas = "Version=".$version."&MerchantId=".$merchantId."&MerchOrderId=".$merchOrderId
				."&Amount=".$amount."&TradeTime=".$tradeTime."&OrderId=".$orderId
				."&VerifyTime=".$verifyTime."&Sign=".$sign;
		$redirectUrl = $payecoUrl."/ppi/h5/plugin/itf.do?tradeId=".$tradeId."&".$datas;
		Log::logFile("redirectUrl=".$redirectUrl);
		return $redirectUrl;
	}
}
?>
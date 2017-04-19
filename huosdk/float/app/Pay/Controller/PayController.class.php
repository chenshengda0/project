<?php

/**
 * PayController.class.php UTF-8
 * 游戏充值
 * @date: 2016年7月21日下午9:40:30
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : H5 2.0
 */
namespace Pay\Controller;

use Common\Controller\SdkbaseController;

class PayController extends SdkbaseController
{
    protected $_fromdevice, $_wiki_back, $_egretappid, $_egretappkey;
    function _initialize() {
        parent::_initialize();
        $this->_fromdevice = get_fromdevice(); // PC端还是手机端
    }
    function index() {
        $username = I('username');
        $productname = I('productname');
        $amount = I('amount', 0);
        $roleid = I('roleid');
        $serverid = I('serverid');
        $appid = I('appid', 0);
        $agent = I('agent');
        $paytime = I('paytime');
        $attach = I('attach');
        $token = I('token');
        $ext = I('ext');
        
        $roleid = urldecode($roleid);
        $productname = urldecode($productname);
        
        if (empty(
                $username) || empty($productname) || $amount <= 0 || empty($roleid) || empty($serverid) || $appid <= 0 ||
             empty($token)) {
            $this->error("参数错误！");
            exit();
        }
        
        $model = M("Game");
        $game = $model->where(array(
            'id' => $appid 
        ))->find();
        
        if (empty($game)) {
            $this->error("游戏不存在");
            exit();
        }
        
        $paramstr = "username=" . urlencode($username) .
                     "&productname=" . urlencode($productname) . "&amount=" . urlencode($amount) . "&roleid=" .
                     urlencode($roleid) . "&serverid=" . urlencode($serverid) . "&appid=" . urlencode($appid) .
                     "&paytime=" . urlencode($paytime) . "&attach=" . urlencode($attach);
        $str = $paramstr . "&appkey=" . $game['appkey'];
        
        $paytoken = md5($str);
        if ($token != $paytoken) {
            $this->error("签名错误");
            exit();
        }
        
        $userid = get_current_userid();
        $model = M('ptb_mem');
        $mem_ptb = $model->where(array(
            'mem_id' => $userid 
        ))->find();
        
        $this->assign("mem_ptb", $mem_ptb);
        $this->assign("username", $username);
        $this->assign("gamename", $game['name']);
        $this->assign("productname", $productname);
        $this->assign("amount", $amount);
        $this->assign("roleid", $roleid);
        $this->assign("serverid", $serverid);
        $this->assign("appid", $appid);
        $this->assign("agent", $agent);
        $this->assign("attach", $attach);
        $this->assign("paytime", $paytime);
        $this->assign("paytoken", $paytoken);
        $this->assign("ext", $ext);
        $this->display();
    }
    function do_pay() {
        $type = I('post.type');
        switch ($type) {
            case 1 : // 平台币
                $this->_ptbpay();
                break;
            case 3 : // 支付宝
                $this->_alipayweb();
                break;
            case 6 : // '6', 'yeepay', '银行卡', '易宝支付', '1'
                $this->_alipayweb();
                break;
            case 7 : // 7, shengpay, 卡类, 盛付通支付, 1
                $this->_alipayweb();
                break;
            case 9 : // 9, heepay, 微信, 汇付宝, 1
                $this->heepay();
                break;
            case 11 : // 11, heepay, 微信, 汇付宝, 1
                $this->_alipayweb();
                break;
            default :
                $this->error("参数错误");
        }
    }
    
    /**
     * 平台币支付
     */
    function _ptbpay() {
        if (IS_POST) {
            $data = $this->_insertpay();
            
            if ($data['orderid']) {
                $ptb_amount = $data['amount'] * 10;
                
                $mem_model = M("ptb_mem");
                $ptb = $mem_model->where(array(
                    'mem_id' => $data['mem_id'] 
                ))->find();
                
                if (empty($ptb) || $ptb['amount'] < $ptb_amount) {
                    $this->error("平台币余额不足");
                    exit();
                } else {
                    
                    $record_data['orderid'] = $data['orderid'];
                    $record_data['mem_id'] = $data['mem_id'];
                    $record_data['app_id'] = $data['app_id'];
                    $record_data['gold'] = $ptb_amount;
                    $record_data['amount'] = $data['amount'];
                    $record_data['status'] = 1;
                    $record_data['roleid'] = $data['roleid'];
                    $record_data['productname'] = $data['productname'];
                    $record_data['ip'] = $data['ip'];
                    $record_data['serverid'] = $data['serverid'];
                    $record_data['create_time'] = time();
                    
                    $conrecord_model = M("ptb_conrecord");
                    $record_id = $conrecord_model->add($record_data);
                    
                    if ($record_id) {
                        $rs = $mem_model->where(
                                array(
                                    'mem_id' => $data['mem_id'] 
                                ))->setDec('amount', $ptb_amount);
                        if ($rs) {
                            $record_data = array(
                                'status' => 2 
                            );
                            $crs = $conrecord_model->where(
                                    array(
                                        'id' => $record_id 
                                    ))->save($record_data);
                            if ($crs) {
                                $this->paypost($data['orderid'], $data['orderid'], $data['amount']);
                            }
                        }
                    }
                }
                $this->success(
                        "支付完成！", 
                        U(
                                "Game/game", 
                                array(
                                    'appid' => $data['app_id'], 
                                    'agent_id' => $data['agent_id'] 
                                )));
                exit();
            }
        }
        $this->error("支付异常");
    }
    
    /**
     * 支付宝支付
     */
    function _alipayweb() {
        header("Content-type:text/html;charset=utf-8");
        if (IS_POST) {
            
            $data = $this->_insertpay();
            
            if (empty($data['orderid'])) {
                $this->error("内部服务器发生错误");
                exit();
            }
            vendor("alipayweb.Config");
            vendor("alipayweb.lib.alipay_submit");
            $config = new \Config();
            $alipay_config = $config->payConfig();
            
            // 支付类型
            $payment_type = "1";
            // 必填，不能修改
            
            // 服务器异步通知页面路径
            $notify_url = H5ISITE . U("User/Pay/alipay_notify");
            // 需http://格式的完整路径，不能加?id=123这类自定义参数
            
            // 页面跳转同步通知页面路径
            // $return_url = H5ISITE;
            $return_url = H5ISITE . U(
                    "User/Game/game", 
                    array(
                        'appid' => $data['app_id'] 
                    ));
            // 需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
            
            // 商户订单号
            $out_trade_no = $data['orderid'];
            // 商户网站订单系统中唯一订单号，必填
            
            // 订单名称
            $subject = $data['productname'];
            // 必填
            
            // 付款金额
            $total_fee = $data['amount'];
            // 必填
            
            // 商品展示地址
            $show_url = H5ISITE . U("Game/game", array(
                'appid' => $data['app_id'] 
            ));
            // 必填，需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
            
            // 订单描述
            $body = "游戏充值";
            // 选填
            
            // 超时时间
            $it_b_pay = '15d';
            // 选填
            
            // 钱包token
            $extern_token = "";
            // 选填
            
            $parameter = array(
                "service" => "alipay.wap.create.direct.pay.by.user", 
                "partner" => trim($alipay_config['partner']), 
                "seller_id" => trim($alipay_config['seller_id']), 
                "payment_type" => $payment_type, 
                "notify_url" => $notify_url, 
                "return_url" => $return_url, 
                "out_trade_no" => $out_trade_no, 
                "subject" => $subject, 
                "total_fee" => $total_fee, 
                "show_url" => $show_url, 
                "body" => $body, 
                "it_b_pay" => $it_b_pay, 
                "extern_token" => $extern_token, 
                "_input_charset" => trim(strtolower($alipay_config['input_charset'])) 
            );
            
            // 建立请求，请求成功之后，会通知服务器的alipay_notify方法，客户端会通知$return_url配置的方法
            $alipaySubmit = new \AlipaySubmit($alipay_config);
            $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "跳转中");
            echo $html_text;
        }
    }
    
    /**
     * 支付宝回调
     */
    function alipay_notify() {
        vendor("alipayweb.Config");
        vendor("alipayweb.lib.alipay_notify");
        
        // 计算得出通知验证结果
        $config = new \Config();
        $alipay_config = $config->payConfig();
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        
        if ($verify_result) { // 验证成功
                              // 商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            // $this->log_result($log_name,$out_trade_no."----\n");
            // 支付宝交易号
            $trade_no = $_POST['trade_no'];
            
            $amount = $_POST['total_fee'];
            
            // 交易状态
            $trade_status = $_POST['trade_status'];
            
            if ($trade_status == 'TRADE_FINISHED') {
            } else if ($trade_status == 'TRADE_SUCCESS') {
                // 支付成功后，修改支付表中支付状态，并将交易信息写入用户平台充值记录表ptb_charge。
                $this->paypost($out_trade_no, $trade_no, $amount);
            }
        }
    }
    function heepay() {
        header("Content-type:text/html;charset=utf-8");
        $data = $this->_insertpay();
        
        if (empty($data['orderid'])) {
            $this->error("内部服务器发生错误");
            exit();
        }
        
        $is_phone = '';
        $is_frame = '';
        if ($this->_fromdevice == 2) // WAP支付
{
            $is_phone = 1;
            $is_frame = 0;
        } else if ($this->_fromdevice == 3) // 公众号支付
{
            $is_phone = 1;
            $is_frame = 1;
        }
        
        $user_ip = $data['ip'];
        $version = 1;
        $agent_id = "2069316";
        $agent_bill_id = $data['orderid'];
        $agent_bill_time = date('YmdHis', time());
        $pay_type = 30;
        $pay_amt = number_format($data['amount'], 2, '.', '');
        $notify_url = $this->h5site . U('User/Pay/heepay_notify');
        $return_url = $this->h5site . U('User/Game/game', array(
            'appid' => $data['app_id'] 
        ));
        $goods_name = urlencode($data['productname']);
        $goods_num = urlencode(1);
        $goods_note = urlencode($data['productname']);
        $remark = $data['orderid'];
        $sign_key = "CF8DA9E0B1CE4010B3967695"; // 签名密钥，需要商户使用为自己的真实KEY
        /**
         * ***********创建签名**************
         */
        $sign_str = '';
        $sign_str = $sign_str . 'version=' . $version;
        $sign_str = $sign_str . '&agent_id=' . $agent_id;
        $sign_str = $sign_str . '&agent_bill_id=' . $agent_bill_id;
        $sign_str = $sign_str . '&agent_bill_time=' . $agent_bill_time;
        $sign_str = $sign_str . '&pay_type=' . $pay_type;
        $sign_str = $sign_str . '&pay_amt=' . $pay_amt;
        $sign_str = $sign_str . '&notify_url=' . $notify_url;
        $sign_str = $sign_str . '&return_url=' . $return_url;
        $sign_str = $sign_str . '&user_ip=' . $user_ip;
        $sign_str = $sign_str . '&key=' . $sign_key;
        
        $sign = md5($sign_str); // 签名值
        $sHtml = "<form id='frmSubmit' method='post' name='frmSubmit' action='https://pay.heepay.com/Payment/Index.aspx'>";
        $sHtml = $sHtml . "<input type='hidden' name='version' value='{$version}' />";
        $sHtml = $sHtml . "<input type='hidden' name='agent_id' value='{$agent_id}' />";
        $sHtml = $sHtml . "<input type='hidden' name='agent_bill_id' value='{$agent_bill_id}' />";
        $sHtml = $sHtml . "<input type='hidden' name='agent_bill_time' value='{$agent_bill_time}' />";
        $sHtml = $sHtml . "<input type='hidden' name='pay_type' value='{$pay_type}' />";
        $sHtml = $sHtml . "<input type='hidden' name='pay_amt' value='{$pay_amt}' />";
        $sHtml = $sHtml . "<input type='hidden' name='notify_url' value='{$notify_url}' />";
        $sHtml = $sHtml . "<input type='hidden' name='return_url' value='{$return_url}' />";
        $sHtml = $sHtml . "<input type='hidden' name='user_ip' value='{$user_ip}' />";
        $sHtml = $sHtml . "<input type='hidden' name='goods_name' value='{$goods_name}' />";
        $sHtml = $sHtml . "<input type='hidden' name='goods_num' value='{$goods_num}' />";
        $sHtml = $sHtml . "<input type='hidden' name='goods_note' value='{$goods_note}' />";
        $sHtml = $sHtml . "<input type='hidden' name='remark' value='{$remark}' />";
        $sHtml = $sHtml . "<input type='hidden' name='is_phone' value='{$is_phone}' />";
        $sHtml = $sHtml . "<input type='hidden' name='is_frame' value='{$is_frame}' />";
        $sHtml = $sHtml . "<input type='hidden' name='sign' value='{$sign}' />";
        $sHtml = $sHtml . "</form>";
        $sHtml = $sHtml . "<script>document.frmSubmit.submit();</script>";
        // echo json_encode($sHtml);
        echo $sHtml;
    }
    /**
     * 汇付宝微信
     */
    function heepay_notify() {
        $signkey = "CF8DA9E0B1CE4010B3967695";
        
        $result = $_GET['result'];
        $pay_message = $_GET['pay_message'];
        $agent_id = $_GET['agent_id'];
        $jnet_bill_no = $_GET['jnet_bill_no'];
        $agent_bill_id = $_GET['agent_bill_id'];
        $pay_type = $_GET['pay_type'];
        $pay_amt = $_GET['pay_amt'];
        $remark = $_GET['remark'];
        $return_sign = $_GET['sign'];
        
        $remark = iconv("GB2312", "UTF-8//IGNORE", urldecode($remark)); // 签名验证中的中文采用UTF-8编码;
        
        $signStr = '';
        $signStr = $signStr . 'result=' . $result;
        $signStr = $signStr . '&agent_id=' . $agent_id;
        $signStr = $signStr . '&jnet_bill_no=' . $jnet_bill_no;
        $signStr = $signStr . '&agent_bill_id=' . $agent_bill_id;
        $signStr = $signStr . '&pay_type=' . $pay_type;
        
        $signStr = $signStr . '&pay_amt=' . $pay_amt;
        $signStr = $signStr . '&remark=' . $remark;
        
        $signStr = $signStr . '&key=' . $signkey; // 商户签名密钥
        
        $sign = '';
        $sign = strtolower(md5($signStr));
        
        if ($sign == $return_sign) { // 验证成功
            $this->paypost($agent_bill_id, $jnet_bill_no, $pay_amt);
            echo 'ok';
        } else {
            echo 'error';
        }
    }
    
    /**
     * 微信扫码支付
     */
    function nativepay() {
        if (IS_POST) {
            $data = $this->_insertpay();
            
            if (empty($data['orderid'])) {
                $this->error("内部服务器发生错误");
                exit();
            }
            
            vendor("wxpay.WxPayPubHelper");
            
            $unifiedOrder = new \UnifiedOrder_pub();
            
            // 设置统一支付接口参数
            // 设置必填参数
            // appid已填,商户无需重复填写
            // mch_id已填,商户无需重复填写
            // noncestr已填,商户无需重复填写
            // spbill_create_ip已填,商户无需重复填写
            // sign已填,商户无需重复填写
            $unifiedOrder->setParameter("body", "游戏充值"); // 商品描述
            $unifiedOrder->setParameter("out_trade_no", $data['orderid']); // 商户订单号
            $unifiedOrder->setParameter("total_fee", $data["amount"] * 100); // 总金额
            $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL); // 通知地址
            $unifiedOrder->setParameter("trade_type", "NATIVE"); // 交易类型
                                                                 
            // 获取统一支付接口结果
            $unifiedOrderResult = $unifiedOrder->getResult();
            
            // 商户根据实际情况设置相应的处理流程
            if ($unifiedOrderResult["return_code"] == "FAIL") {
                $this->error("错误代码：" . $unifiedOrderResult['err_code'] . " 通信出错：" . $unifiedOrderResult['return_msg']);
            } else if ($unifiedOrderResult["code_url"] != NULL) {
                // 从统一支付接口获取到code_url
                $code_url = $unifiedOrderResult["code_url"];
            }
            $this->assign('out_trade_no', $data['orderid']);
            $this->assign('code_url', $code_url);
            $this->assign('unifiedOrderResult', $unifiedOrderResult);
            
            $this->display("nativepay");
        }
    }
    
    /**
     * 微信支付调起
     */
    function wxpay() {
        // if(IS_POST){
        vendor("wxpay.WxPayPubHelper");
        
        $jsApi = new \JsApi_pub();
        // 网页授权获取用户openid
        // 通过code获得openid
        if (!isset($_GET["code"])) {
            
            $data = $this->_insertpay();
            
            if (empty($data['orderid'])) {
                $this->error("内部服务器发生错误");
                exit();
            }
            
            $state = json_encode($data);
            $state = base64_encode($state);
            
            // 触发微信返回code码
            $url = $jsApi->createOauthUrlForCode(\WxPayConf_pub::JS_API_CALL_URL, $state);
            
            Header("Location: $url");
        } else {
            // 获取code码，以获取openid
            $state = $_GET['state'];
            $state = base64_decode($state);
            $statedata = get_object_vars(json_decode($state));
            
            $code = $_GET["code"];
            $jsApi->setCode($code);
            $openid = $jsApi->getOpenId();
        }
        
        $model = M("Game");
        $game = $model->where(array(
            'id' => $statedata['app_id'] 
        ))->find();
        
        // 使用统一支付接口
        $unifiedOrder = new \UnifiedOrder_pub();
        $unifiedOrder->setParameter("openid", $openid); //
        $unifiedOrder->setParameter("body", "游戏充值"); //
        $unifiedOrder->setParameter("body", $statedata["productname"]); // 商品描述
                                                                        // 参数
        $unifiedOrder->setParameter("out_trade_no", $statedata["orderid"]); // 商户订单号
        $unifiedOrder->setParameter("total_fee", $statedata["amount"] * 100); // 总金额
        
        $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::NOTIFY_URL); // 通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI"); // 交易类型
        $prepay_id = $unifiedOrder->getPrepayId();
        // =========调起支付============
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
        
        $this->assign(
                "back_url", 
                H5ISITE . U("Game/game", array(
                    'appid' => $statedata['app_id'] 
                )));
        $this->assign("jsApiParameters", $jsApiParameters);
        $this->display("wxpay");
        // }
    }
    
    /**
     * 微信回调
     */
    
    /**
     * 支付记录保存
     */
    function _insertpay() {
        $type = I('post.type'); // 支付类型
        $amount = I('post.amount', 0); // 交易金额
        $username = I('post.username'); // 用户名
        $roleid = I('post.roleid'); // 用户角色id
        $serverid = I('post.serverid'); // 服务器ID
        $appid = I('post.appid'); // appid
        $agent = I('post.agent'); // 渠道ID
        $productname = I('post.productname'); // 商品名称
        $paytime = I('post.paytime');
        $attach = I('post.attach'); // CP方的扩展参数
        $paytoken = I('post.paytoken'); //
        $ext = I('post.ext'); //
        
        $roleid = urldecode($roleid);
        $productname = urldecode($productname);
        
        if (empty($username) || empty($productname) || $amount <= 0 ||
             empty($roleid) || empty($serverid) || $appid <= 0 || empty($paytime) || empty($paytoken)) {
            $this->error("参数错误！");
            exit();
        }
        
        $orderid = setorderid(); // 客户订单号
        $userip = get_client_ip(); // 用户支付时使用的网络终端IP
        $transtime = time(); // 交易时间
        
        $model = M("Game");
        $game = $model->where(array(
            'id' => $appid 
        ))->find();
        
        if (empty($game)) {
            $this->error("游戏不存在");
            exit();
        }
        
        $paramstr = "username=" . urlencode($username) .
                     "&productname=" . urlencode($productname) . "&amount=" . urlencode($amount) . "&roleid=" .
                     urlencode($roleid) . "&serverid=" . urlencode($serverid) . "&appid=" . urlencode($appid) .
                     "&paytime=" . urlencode($paytime) . "&attach=" . urlencode($attach);
        
        $pay_str = $paramstr . "&appkey=" . $game['appkey'];
        
        $token = md5($pay_str);
        if ($paytoken != $token) {
            $this->error("口令错误！");
            exit();
        }
        
        if (empty($game['payurl'])) {
            $this->error("没有回调地址，请通知我方配置");
            exit();
        }
        
        $model = M("Members");
        $user = $model->where(array(
            'username' => $username 
        ))->find();
        
        $regagent = $user['agent_id'];
        
        $data['orderid'] = $orderid;
        // $data['mem_id'] = get_current_userid();
        $data['mem_id'] = $user['id'];
        $data['amount'] = $amount;
        $data['roleid'] = $roleid;
        $data['payway'] = $type;
        $data['productname'] = $productname;
        $data['agent_id'] = $regagent;
        $data['serverid'] = $serverid;
        $data['app_id'] = $appid;
        $data['ip'] = $userip;
        $data['create_time'] = $transtime;
        $data['remark'] = $attach;
        $data['ext'] = $ext;
        
        $model = M("pay");
        if ($model->create()) {
            $rs = $model->add($data);
        }
        
        if (!$rs) {
            $this->error("内部服务器发生错误");
            exit();
        }
        
        $param = "orderid=" .
                 $orderid . "&username=" . $username . "&productname=" . urlencode($productname) . "&amount=" . $amount .
                 "&roleid=" . $roleid . "&serverid=" . $serverid . "&appid=" . $appid . "&paytime=" . $paytime .
                 "&attach=" . urlencode($attach);
        
        $md5params = md5($param . "&appkey=" . $game['appkey']);
        $params = $param . "&token=" . $md5params;
        
        $info_data['orderid'] = $orderid;
        $info_data['fcallbackurl'] = $game['payurl'];
        $info_data['params'] = $params;
        $info_data['create_time'] = $transtime;
        
        $model = M("pay_cpinfo");
        
        $rs_info = $model->add($info_data);
        
        if (!$rs_info) {
            $this->error("内部服务器发生错误");
            exit();
        }
        
        return $data;
    }
    
    // 支付成功后，修改支付表中支付状态，并将交易信息写入用户平台充值记录表ptb_charge。
    function paypost($out_trade_no, $trade_no, $total_fee) {
        $model = M("pay");
        // 获取支付表中的支付信息
        $pay_data = $model->where(array(
            'orderid' => $out_trade_no 
        ))->find();
        // 判断充值金额与回调中是否一致，且状态是否为1，即待支付状态
        if ((number_format($total_fee) == number_format($pay_data['amount'])) && 1 == $pay_data['status']) {
            $data['status'] = 2;
            $data['paymark'] = $trade_no;
            // 将订单信息写入pay表中，并修改订单状态为2，即支付成功状态
            $rs = $model->where(array(
                'orderid' => $out_trade_no 
            ))->save($data);
            
            // $this->log_result($log_name,"vvvv-".$rs."\n");
            // 判断订单信息是否修改成功
            if ($rs) {
                $cpinfo_data['status'] = 3;
                $model = M("pay_cpinfo");
                // 获取CP支付信息表中订单信息
                $info_data = $model->where(array(
                    'orderid' => $out_trade_no 
                ))->find();
                
                if ($info_data['status'] == 1 || $info_data['status'] == 3) {
                    $game_model = M("Game");
                    $game = $game_model->field("isegret")->where(
                            array(
                                'id' => $pay_data['app_id'] 
                            ))->find();
                    $i = 0;
                    while (1) {
                        $cp_rs = $this->payback($info_data['fcallbackurl'], $info_data['params'], 'post');
                        
                        if ($cp_rs > 0) {
                            $cpinfo_data['status'] = 2;
                            break;
                        } else {
                            $cpinfo_data['status'] = 3;
                            $i++;
                            sleep(2);
                        }
                        
                        if ($i == 3) {
                            $cpinfo_data['status'] = 3;
                            break;
                        }
                    }
                }
                
                $cpinfo_data['update_time'] = time();
                $rs = $model->where(array(
                    'orderid' => $out_trade_no 
                ))->save($cpinfo_data);
                
                if ($cpinfo_data['status'] == 2) {
                    $cdata['orderid'] = $out_trade_no;
                    $cdata['type_id'] = 2;
                    $cdata['mem_id'] = $pay_data['mem_id'];
                    $cdata['app_id'] = $pay_data['app_id'];
                    $cdata['money'] = $pay_data['amount'];
                    $cdata['roleid'] = $pay_data['roleid'];
                    $cdata['serverid'] = $pay_data['serverid'];
                    $cdata['payway'] = $pay_data['payway'];
                    $cdata['ip'] = $pay_data['ip'];
                    $cdata['status'] = 2;
                    $cdata['create_time'] = time();
                    $cdata['productname'] = $pay_data['productname'];
                    
                    $model = M("ptb_charge");
                    $rs = $model->add($cdata);
                }
            }
        }
    }
    function createSign($params, $appkey) {
        ksort($params);
        $str = "";
        foreach ($params as $key => $value) {
            $str .= $key . "=" . $value;
        }
        return md5($str . $appkey);
    }
    function log_result($file, $word) {
        $fp = fopen($file, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d-%H：%M：%S", time()) . "\n" . $word . "\n\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}

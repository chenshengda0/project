<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta charset="UTF-8">
    <title>充值</title>

</head>
<body>
<!--<header class="header">
    <div class="return_btn" onclick="window.history.back()">
        <img src="/public/mobile/images/btn_return_l.png" alt=""/>
    </div>
    <h3>充值</h3>
    <div class="close_btn">
        <img src="/public/mobile/images/btn_cancle.png" alt=""/>
    </div>
</header>-->

 <link rel="stylesheet" href="/public/mobile/css/charge.css"/>
<!--帐号信息-->
<div class="account_information">
    <p class="account">帐号：<i><?php echo ($_SESSION['user']['nickname']); ?></i></p>
    <p class="balance"><?php echo C('CURRENCY_NAME');?>：<span><?php echo ((isset($wallet["remain"]) && ($wallet["remain"] !== ""))?($wallet["remain"]):0); ?></span></p>
</div>
<!--充值金额-->
<div class="recharge_amount">
    <h3 class="gray_title">充值金额（单位:元）</h3>
    <ul class="btn_group">
        <li>50</li>
        <li class="active">100</li>
        <li>500</li>
        <li>1000</li>
        <li>2000</li>
        <li>5000</li>
        <input type="text" placeholder="自定义金额:1-50000" style="font-family:'Microsoft YaHei'"/>
    </ul>
    <p class="canGet">可获得<span>1000</span><?php echo C('CURRENCY_NAME');?></p>
</div>
<!--选择支付方式-->
<div class="change_way">
    <h3 class="gray_title">选择支付方式</h3>
    <ul class="way">
        <li data-way="alipay"><div class="way_icon"><img src="/public/mobile/images/alipay.png" alt=""/></div><span>支付宝(推荐)</span><div class="right_icon"><img src="/public/mobile/images/btn_return.png" alt=""/></div></li>
        <!-- <li data-way="wxpay"><div class="way_icon"><img src="/public/mobile/images/wxpay.png" alt=""/></div><span>微信支付</span><div class="right_icon"><img src="/public/mobile/images/btn_return.png" alt=""/></div></li>
        <li data-way="unionpay"><div class="way_icon"><img src="/public/mobile/images/unionpay.png" alt=""/></div><span>银联在线支付</span><div class="right_icon"><img src="/public/mobile/images/btn_return.png" alt=""/></div></li> -->
    </ul>
    <form action="<?php echo U('Wallet/preorder');?>" method="post" id="payform" style="display:none">
	    <input type="hidden" value="<?php echo ($appid); ?>" id="appid" name="appid"/>
	    <input type="hidden" value="<?php echo ($agent); ?>" id="agent" name="agent"/>
	    <input type="hidden" id="paytype" name="paytype"/>
	    <input type="hidden" id="paytoken" name="paytoken" value="<?php echo ($paytoken); ?>"/>
	    <input type="hidden" id="money" name="money"/>
    </form>
</div>
<!--立即充值-->
<!--<div class="instant_recharge">-->
    <!--<button>立 即 充 值</button>-->
<!--</div>-->

<footer class="footer">
    <p><a class="QQ" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo ($qq); ?>&amp;site=qq&amp;menu=yes"><span>客服QQ：</span><?php echo ($qq); ?></a>
	<?php if($idkey == ''): ?><a class="QQgroup" target="_blank" href="javascript:void();" style="color:#adadad"><span>QQ 群：</span><?php echo ($qqgroup); ?></a></p>
	<?php else: ?>
		<a class="QQgroup" target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=<?php echo ($idkey); ?>"><span>QQ 群：</span><?php echo ($qqgroup); ?></a></p><?php endif; ?>
</footer>
</body>
<script src="/public/mobile/js/jquery.js"></script>
<script src="/public/mobile/js/huosdk_base.js"></script>
<script src="/public/mobile/js/recharge.js"></script>
</html>
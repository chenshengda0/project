<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="keywords" content="<?php echo ($keywords); ?>"/>
<meta name="description" content="<?php echo ($description); ?>"/>
<title><?php echo ($title); ?></title>
<link href="/public/web/css/index.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class="top_nav">
	<div class="top_nav_main" id="top_nav_main"> 
    	
    	<div class="top_nav_main_l">欢迎来到<?php echo C('BRAND_NAME');?> 
        <?php if($_SESSION['user']['sdkuser']== ''): ?><a href="<?php echo U('Login/index');?>">登录</a>  <a href=":U('Register/index')">注册</a> 
        <?php else: ?>
        <span class="orange"><?php echo ($_SESSION['user']['sdkuser']); ?></span>&nbsp;&nbsp;<a href="<?php echo U('Web/User/index',array('userinfo'=>'info'));?>" class="blue_line">进入用户中心</a>&nbsp;&nbsp;<a href="<?php echo U('User/logout');?>" class="blue_line">退出</a><?php endif; ?>
        </div>
    </div>
  	<div class="clear"></div>
</div>
<div class="top">
	<div class="top_l"><a href="<?php echo WEBSITE;?>"><img src="/upload//image/<?php echo ($logo["img"]); ?>" width="320px;"></a></div>
    	<div class="top_r">
            <div class="nav">
               <ul>
					<li><a href="<?php echo U('Index/index');?>" class="menu0_on" style="width:65px;"><i class="navIco navIco_1"></i>首页</a></li>
					<li><a href="<?php echo U('Game/index');?>" class="menu1"><i class="navIco navIco_2"></i>游戏中心</a></li>
					<li><a href="<?php echo U('Web/Pay/index');?>" class="menu3" target="_blank"><i class="navIco navIco_4"></i>充值中心</a></li>
					<li><a href="<?php echo U('Web/User/index',array('userinfo'=>'info'));?>" class="menu2"><i class="navIco navIco_3"></i>个人中心</a></li>
					<li><a href="<?php echo U('Web/Server/index',array('item'=>'zhongxin'));?>" class="menu4"><i class="navIco navIco_5"></i>客服中心</a></li>
					<li><a href="<?php echo BBSSITE;?>"  target="_blank" class="menu5"><i class="navIco navIco_6"></i>玩家社区</a></li>
            	</ul>
            </div>
        </div>
</div>
<div class="clear"></div>
<div class="main">
  <div class="xieyi">
    <div class="xieyi_main">
	  <div class="xieyi_main_t"><?php echo C('CURRENCY_NAME');?>用户服务协议</div>
	  <div class="xieyi_main_word">
	    <b>一、<?php echo C('CURRENCY_NAME');?>使用规则</b><br />
	    <p style="text-indent:2em;">1. <?php echo C('CURRENCY_NAME');?>服务简介：<?php echo C('CURRENCY_NAME');?>是<?php echo C('BRAND_NAME');?>游戏平台为用户提供的一种虚拟货币，可以用于购买<?php echo C('BRAND_NAME');?>提供的各项付费业务和增值服务，在<?php echo C('BRAND_NAME');?>游戏平台的各项游戏中可以购买各种游戏币或道具等。 <br /></p>
	    <p style="text-indent:2em;">2.<?php echo C('CURRENCY_NAME');?>注册规则 ：<br /></p>
        <p style="text-indent:2em;">2.1    用户不需要另外开通<?php echo C('BRAND_NAME');?>账户，只需在<?php echo C('BRAND_NAME');?>用户中心注册<?php echo C('BRAND_NAME');?>账号，即可使用<?php echo C('CURRENCY_NAME');?>进行付费。<br /></p>
        <p style="text-indent:2em;">2.2    用户承诺以其真实身份注册成为<?php echo C('BRAND_NAME');?>的用户，并保证所提供的个人身份资料信息真实、完整、有效，依据法律规定和必备条款约定对所提供的信息承担相应的法律责任。<br /></p>
		<p style="text-indent:2em;">3.<?php echo C('CURRENCY_NAME');?>使用规则 ：<br /></p>
		<p style="text-indent:2em;">3.1    用户可以通过支付宝、易宝/充值卡（移动/联通/电信）/网银/ /银联安全支付，以上几种方式进行充值。<br /></p>
		<p style="text-indent:2em;">3.2    若使用非<?php echo C('BRAND_NAME');?>官方网络所指定的充值方式或系统进行充值，或以非法的方式进行充值而造成用户权益受损时，用户不得因此要求<?php echo C('BRAND_NAME');?>作任何补偿或赔偿，并且<?php echo C('BRAND_NAME');?>保留随时不经通知而终止用户账户资格及使用各项充值服务的权利。<br /></p>
		<p style="text-indent:2em;">3.3    用户充值一旦成功，充值即确定完成，<?php echo C('BRAND_NAME');?>将不提供任何更改、修正服务。用户不得要求退还充值产品。<br /></p>
		<p style="text-indent:2em;">3.4    在使用充值系统时，用户必须仔细确认自己的账号，若因为用户自身输入账号错误、操作不当或不了解充值计费方式等因素造成充错账号、错选充值种类等情形而损害自身权益，用户不得因此要求<?php echo C('BRAND_NAME');?>作任何补偿或赔偿。<br /></p>
		<p style="text-indent:2em;">3.5    <?php echo C('BRAND_NAME');?>账户不支持退款和提现。用户一旦充值<?php echo C('CURRENCY_NAME');?>成功，购买<?php echo C('CURRENCY_NAME');?>的相关费用和利用<?php echo C('CURRENCY_NAME');?>购买的相关产品将不得以任何理由予以退回。<br /></p>
		<p style="text-indent:2em;">4. <?php echo C('CURRENCY_NAME');?>安全规则：<br /></p>
		<p style="text-indent:2em;">4.1    用户必须妥善保管好自己的帐号和密码，加强密码安全性，谨防泄露或被盗。<?php echo C('BRAND_NAME');?>会采取严格措施保护用户的账户安全，但对用户的账户安全不做保证。因第三方或者不可抗力的原因（参考免责声明第3条规定）账户信息泄露或被盗而造成的<?php echo C('CURRENCY_NAME');?>损失，<?php echo C('BRAND_NAME');?>不承担责任。<br /></p>
		<p style="text-indent:2em;">4.2    如<?php echo C('BRAND_NAME');?>视怀疑某用户<?php echo C('CURRENCY_NAME');?>金额或使用情况有异常状况，<?php echo C('BRAND_NAME');?>有权立即封闭该用户帐号直至情况查清楚为止。<br /></p>
		<p style="text-indent:2em;">4.3    如<?php echo C('BRAND_NAME');?>怀疑某用户在<?php echo C('CURRENCY_NAME');?>的使用中有违法违规情况<?php echo C('BRAND_NAME');?>有权拒绝该用户使用<?php echo C('CURRENCY_NAME');?>进行支付，直至封闭该用户帐号并追究其法律责任。<br /></p>
		<p style="text-indent:2em;">4.4    <?php echo C('BRAND_NAME');?>对用户帐户里的<?php echo C('CURRENCY_NAME');?>金额拥有最终及绝对的解释权和决定权。<br /></p>
		<p style="text-indent:2em;">5.保护用户隐私规则：<?php echo C('BRAND_NAME');?>对用户信息资源的保护，会使用各种安全技术和程序来保护用户信息资源不被未经授权的访问、使用和泄漏。<?php echo C('BRAND_NAME');?>不向第三方公开透露用户信息，但因下列原因而披露给第三方的除外：<br /></p>
		<p style="text-indent:2em;">5.1    基于国家法律法规的规定而对外披露；<br /></p>
		<p style="text-indent:2em;">5.2    应国家司法机关及其他有法律赋予权限的政府机关基于法定程序的要求而披露；<br /></p>
		<p style="text-indent:2em;">5.3    为保护<?php echo C('BRAND_NAME');?>或用户的合法权益而披露；<br /></p>
		<p style="text-indent:2em;">5.4    在紧急情况下，为保护其他用户及第三方人身安全而披露；<br /></p>
		<p style="text-indent:2em;">5.5    用户本人或用户监护人授权披露的。<br /></p>
       <b>二、用户权利义务</b><br />
	   <p style="text-indent:2em;">1.用户权利：<br /></p>
	   <p style="text-indent:2em;">1.1    用户有权根据本协议的规定，利用<?php echo C('BRAND_NAME');?>网上交易平台登录帐号、购买游戏道具或增值服务、查询物品信息、在本网站社区发帖、用获得的红包兑换成等额的<?php echo C('CURRENCY_NAME');?>参加本网站的有关活动及有权享受本网站提供的其他的有关资讯及信息服务。 <br /></p>
	   <p style="text-indent:2em;">1.2    用户有权根据需要充值<?php echo C('CURRENCY_NAME');?>、更改帐号和密码。用户应对以该用户名进行的所有活动和事件负全部责任。<br /></p>
	   <p style="text-indent:2em;">1.3    用户有权要求<?php echo C('BRAND_NAME');?>及时公示<?php echo C('CURRENCY_NAME');?>可以支付使用各类活动的活动规则及消费细则，并对相关活动进行投诉、发表感受或者提出建议。<br /></p>
	   <p style="text-indent:2em;">1.4    用户有权根据<?php echo C('BRAND_NAME');?>网站规定，享受<?php echo C('BRAND_NAME');?>提供的其它服务。<br /></p>
	   <p style="text-indent:2em;">1.5    用户发现其账号或密码被他人非法使用或有使用异常的情况的，应及时根据<?php echo C('BRAND_NAME');?>公布的处理方式通知<?php echo C('BRAND_NAME');?>，并有权通知<?php echo C('BRAND_NAME');?>采取措施暂停该账号的登录和使用。<br /></p>
	   <p style="text-indent:2em;">1.6    用户在<?php echo C('BRAND_NAME');?>网上交易过程中如与其他用户因交易产生纠纷，可以请求<?php echo C('BRAND_NAME');?>从中予以协调。用户如发现其他用户有违法或违反本协议的行为，可以向<?php echo C('BRAND_NAME');?>举报。如用户因网上交易与其他用户产生诉讼的，用户有权通过司法部门要求<?php echo C('BRAND_NAME');?>提供相关资料。<br /></p>
	   <p style="text-indent:2em;">2.用户义务：<br /></p>
	   <p style="text-indent:2em;">2.1    用户有义务确保向本网站提供的任何资料、注册信息真实准确，包括但不限于真实姓名、身份证号、联系电话、地址、邮政编码等。保证本网站及其他用户可以通过上述联系方式与自己进行联系。同时，用户也有义务在相关资料实际变更时及时更新有关注册资料。<br /></p>
	   <p style="text-indent:2em;">2.2    严禁用户通过直接或间接手段贩卖<?php echo C('CURRENCY_NAME');?>，严禁任何有偿转让<?php echo C('CURRENCY_NAME');?>的行为，一经发现，则<?php echo C('BRAND_NAME');?>有权直接采取一切必要的措施，包括但不限于取消用户在网站获得的星级、荣誉以及虚拟财富，暂停或查封<?php echo C('BRAND_NAME');?>帐号，取消因违规所获利益，保留通过法律手段追回因非法<?php echo C('CURRENCY_NAME');?>交易而给<?php echo C('BRAND_NAME');?>造成的直接或间接经济损失的权利。<br /></p>
	   <p style="text-indent:2em;">2.3    用户不得通过任何手段恶意注册<?php echo C('CURRENCY_NAME');?>帐号，包括但不限于以牟利、炒作、套现、获奖等为目的多个账号注册。用户亦不得盗用其他用户帐号。一经发现，则<?php echo C('BRAND_NAME');?>有权直接采取一切必要的措施，包括但不限于取消用户在网站获得的各种荣誉以及虚拟财富，暂停或查封<?php echo C('BRAND_NAME');?>帐号，取消因违规所获利益，保留通过法律手段追回因非法<?php echo C('CURRENCY_NAME');?>交易而给<?php echo C('BRAND_NAME');?>造成的直接或间接经济损失的权利。 <br /></p>
	   <p style="text-indent:2em;">2.4    <?php echo C('CURRENCY_NAME');?>仅为购买<?php echo C('BRAND_NAME');?>提供的各种付费业务使用的虚拟货币，严禁用户利用<?php echo C('CURRENCY_NAME');?>进行赌博、洗钱等非法行为，一经发现，<?php echo C('BRAND_NAME');?>有权直接采取一切必要的措施，包括但不限于取消用户在网站获得的星级、荣誉以及虚拟财富，暂停或查封<?php echo C('BRAND_NAME');?>帐号，取消因违规所获利益，保留通过法律手段追回因非法<?php echo C('CURRENCY_NAME');?>交易而给<?php echo C('BRAND_NAME');?>造成的直接或间接经济损失的权利。<br /></p>
	   <p style="text-indent:2em;">2.5    用户承诺自己在使用本网站网上交易平台实施的所有行为遵守国家法律、法规和本网站的相关规定以及各种社会公共利益或公共道德。对于任何法律后果的发生，用户将以自己的名义独立承担所有相应的法律责任。<br /></p>
	   <p style="text-indent:2em;">2.6    用户违反有关法律、法规或本协议项下的任何条款而给<?php echo C('BRAND_NAME');?>络或任何其他第三人造成损失，用户同意承担由此造成的损害赔偿责任。<br /></p>
       <b>三、服务的中止与终止</b><br />
         <p style="text-indent:2em;">1. 用户在使用<?php echo C('CURRENCY_NAME');?>服务时违法信息、严重违背社会公德、以及其他违反法律禁止性规定的行为，<?php echo C('BRAND_NAME');?>应当立即终止对用户提供服务。 <br /></p>
		 <p style="text-indent:2em;">2. 用户在接受<?php echo C('BRAND_NAME');?>服务时实施不正当行为的，<?php echo C('BRAND_NAME');?>有权终止对用户提供服务。该不正当行为的具体情形应当在本协议中关于用户的义务里面有明确约定或属于<?php echo C('BRAND_NAME');?>事先明确告知的应被终止服务的禁止性行为，否则，<?php echo C('BRAND_NAME');?>不得终止对用户提供服务。 <br /></p>
		 <p style="text-indent:2em;">3. 用户提供虚假注册身份信息，或实施违反本协议的行为，<?php echo C('BRAND_NAME');?>有权中止对用户提供全部或部分服务；<?php echo C('BRAND_NAME');?>采取中止措施应当通知乙方并告知中止期间，中止期间应该是合理的，中止期间届满<?php echo C('BRAND_NAME');?>应当及时恢复对用户的服务。 <br /></p>

       <b>四、免责声明</b><br />
       <p style="text-indent:2em;">1. 用户明确同意其使用<?php echo C('BRAND_NAME');?>充值服务所存在的一切风险将完全由其自己承担；因其使用<?php echo C('BRAND_NAME');?>充值服务而产生的一切后果也由其自己承担，<?php echo C('BRAND_NAME');?>对用户不承担任何责任。  <br /></p>
	   <p style="text-indent:2em;">2. 若因用户违反本协议条款，<?php echo C('BRAND_NAME');?>因此冻结用户账号或终止账号使用资格的，用户不得因此要求<?php echo C('BRAND_NAME');?>作任何补偿或赔偿。<br /></p>
	   <p style="text-indent:2em;">3. 因不可抗力或者其他意外事件，使得本协议的履行不可能、不必要或者无意义的，双方均不承担责任。本协议所称之不可抗力意指不能预见、不能避免并不能克服的客观情况，包括但不限于战争、台风、水灾、火灾、雷击或地震、罢工、暴动、法定疾病、黑客攻击、网站受攻击、网络病毒、互联网正常的设备维护、互联网络连接故障、电脑、通讯或其他系统的故障、电力故障、计算机设施或操作系统软件本身固有的技术缺陷、电信部门技术管制、政府行为或任何其它自然或人为造成的灾难等客观情况。<br /></p>
	   
	   <b>五、法律适用和管辖</b><br />
       <p style="text-indent:2em;">1. 本协议的生效、履行、解释及争议的解决均适用中华人民共和国法律。  <br /></p>
	   <p style="text-indent:2em;">2. 如就本协议内容或其执行发生任何争议，应尽量友好协商解决；协商不成时，则争议各方均可向<?php echo C('BRAND_NAME');?>注册所在地，具有管辖权的人民法院提起诉讼。<br /></p>
	   <p style="text-indent:2em;">3. 本协议的一切解释权与修改权归<?php echo C('BRAND_NAME');?>。 <br /></p>

	   <b>六、其他规定</b><br />
       <p style="text-indent:2em;">1. 本协议各条款是可分的，所约定的任何条款如果部分或者全部无效，不影响该条款其他部分及本协议其他条款的法律效力。<br /></p>
	   <p style="text-indent:2em;">2. 本协议自公布之日起有效。<br /></p>
	   
	   <b>七、特别提示</b><br />
		<p style="text-indent:2em;">1. <?php echo C('BRAND_NAME');?>在此特别提醒，若用户访问和使用<?php echo C('CURRENCY_NAME');?>，请事先认真阅读本协议中各条款，包括免除或者限制<?php echo C('BRAND_NAME');?>的免责条款及对用户的权利限制。<br /></p>
	   <p style="text-indent:2em;">2. <?php echo C('BRAND_NAME');?>拥有<?php echo C('CURRENCY_NAME');?>服务的所有权和运营权，以及<?php echo C('CURRENCY_NAME');?>业务规则和营销活动的最终解释权。<?php echo C('BRAND_NAME');?>保留随时地、不事先通知地、不需要任何理由地、单方面地修订本协议的权利。本协议一经修订，<?php echo C('BRAND_NAME');?>将会用修订后的协议版本完全替代修订前的协议版本，并通过原有方式向所有用户公布。用户应当及时关注和了解本协议的修订情况，如果用户不同意修订后协议版本，请用户立即停止使用和享受<?php echo C('CURRENCY_NAME');?>的相关产品及服务，否则即视同用户同意并完全接受修订后的协议版本。<br /></p>
	   <p style="text-indent:2em;">3. 用户进行<?php echo C('CURRENCY_NAME');?>充值、消费、兑换红包、账户操作等功能时即视为用户已经阅读并同意本用户服务协议，并与<?php echo C('BRAND_NAME');?>达成协议，自愿接受本服务协议的以下所以条款的约束，否则无权使用任何涉及<?php echo C('CURRENCY_NAME');?>的功能。通过点击"我同意"或"我接受"表示用户完全同意并接受本协议之约束，或者点击"我不同意"或"我不接受"表示用户不同意本协议。 <br /></p>
		<b>本服务协议双方为<?php echo C('BRAND_NAME');?>与<?php echo C('BRAND_NAME');?>用户中心注册的用户，用于规范双方之间有关<?php echo C('CURRENCY_NAME');?>的全部法律关系及权利义务。 本协议中的"用户"系指所有在<?php echo C('BRAND_NAME');?>的用户中心注册并进行登记的用户。</b><br />
	  </div>
	</div>
  </div>
  
</div>
<div class="clear"></div>
<div class="footer">
	<p>
		<a href="<?php echo U('AboutUs/index',array('show'=>'us'));?>">关于我们</a>  |  
		<a href="<?php echo U('AboutUs/index',array('show'=>'hezuo'));?>">商务合作</a>  |  
		<a href="<?php echo U('AboutUs/index',array('show'=>'zhaopin'));?>">人才招聘</a>  |  
		<a href="<?php echo U('AboutUs/index',array('show'=>'lianxi'));?>">联系我们</a>
	</p>
	<p>健康游戏忠告：抵制不良游戏 拒绝盗版游戏 注意自我保护 谨防受骗上当 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活</p>
</div>
</body>
</html>
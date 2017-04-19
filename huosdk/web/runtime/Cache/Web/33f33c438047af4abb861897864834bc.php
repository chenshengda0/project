<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="keywords" content="<?php echo ($keywords); ?>"/>
<meta name="description" content="<?php echo ($description); ?>"/>
<title>个人中心_<?php echo C('BRAND_NAME');?>手游联运平台</title>
<link href="/public/web/css/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/public/web/js/jquery-1.js"></script>
<script type="text/javascript" src="/public/web/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/public/web/js/slider.js"></script>
<script type="text/javascript" src="/public/web/js/slides.js"></script>
</head>
<body>
<div class="top_nav">
	<div class="top_nav_main" id="top_nav_main"> 
    	<div class="top_nav_main_l">欢迎来到<?php echo C('BRAND_NAME');?>

        <?php if($_SESSION['user']['sdkuser']== ''): ?><a href="<?php echo U('Login/index');?>">登录</a>  <a href="<?php echo U('Register/index');?>">注册</a> 
        <?php else: ?>
            <span class="orange"><?php echo ($_SESSION['user']['sdkuser']); ?></span>&nbsp;&nbsp;<a href="<?php echo U('Web/User/index',array('userinfo'=>'info'));?>" class="blue_line">进入用户中心</a>&nbsp;&nbsp;<a href="<?php echo U('Login/index'),array('action'=>'loginout');?>" class="blue_line">退出</a><?php endif; ?>

        </div>
    </div>
  	<div class="clear"></div>
</div>
<div class="top">
	<div class="top_l"><a href="<?php echo WEBSITE;?>"><img src="/upload//image/<?php echo ($logo["img"]); ?>"></a></div>
    	<div class="top_r">
            <div class="nav">
                <ul>
                    <li><a href="<?php echo U('Web/Index/index');?>" >首   页</a></li>
                    <li><a href="<?php echo U('Web/Game/index');?>"  >游戏大全</a></li>
                    <li><a href="<?php echo U('Web/Pay/index');?>" target="_blank">充值中心</a></li>
                    <li><a href="<?php echo U('Web/User/index?userinfo=info');?>">个人中心</a></li>
                    <li><a href="<?php echo U('Web/Server/index?item=zhongxin');?>">客服中心</a></li>
                    <li><a href="<?php echo BBSSITE;?>" target="_blank">手游社区</a></li>
                </ul>
            </div>
        </div>
</div>
<div class="clear"></div>

<div class="main">
  <div class="xieyi">
    <div class="xieyi_main">
	  <div class="xieyi_main_t"><?php echo C('BRAND_NAME');?>使用协议</div>
	  <div class="xieyi_main_word">
	    <b>一、总则</b><br />
	    <p style="text-indent:2em;">您注册成为<?php echo C('BRAND_NAME');?>手机游戏平台注册用户之前，应仔细阅读本服务协议及免责声明和隐私条款。<?php echo C('COMPANY_NAME');?>作为<?php echo C('BRAND_NAME');?>手机游戏平台的经营者，根据本服务协议及随时对其的修改向您提供基于互联网以及移动网的相关服务（下称"网络服务"）。如您不同意本服务协议、免责声明、隐私条款、或随时对该等内容的修改等，您可以主动取消<?php echo C('BRAND_NAME');?>手机游戏平台提供的网络服务。您一旦使用<?php echo C('BRAND_NAME');?>手机游戏平台网络服务，即视为您已了解并完全同意本服务协议各项内容、免责声明和隐私条款，包括<?php echo C('BRAND_NAME');?>手机游戏平台对本服务协议随时所做的任何修改，并成为<?php echo C('BRAND_NAME');?>手机游戏平台注册用户（以下简称"用户"）。<br /></p>
	    <p style="text-indent:2em;">1.1    用户应当同意本协议的条款并按照页面上的提示完成全部的注册程序。用户在进行注册程序过程中点击"同意"按钮即表示用户与<?php echo C('BRAND_NAME');?>手机游戏平台达成协议，完全接受本协议项下的全部条款。<br /></p>
	    <p style="text-indent:2em;">1.2    用户注册成功后，<?php echo C('BRAND_NAME');?>手机游戏平台将给予每个用户一个用户账号及相应的密码，该用户账号和密码由用户负责保管。用户应当对以其用户账号进行的所有活动和事件负法律责任。<br /></p>
        <p style="text-indent:2em;">1.3    用户使用<?php echo C('BRAND_NAME');?>手机游戏平台网络服务中包含的各个单项服务的，应当遵守本服务协议的规定。如该单项服务具有单独的服务条款、公告等单项服务协议的，此单项服务协议与本协议一同构成双方协议的整体。<br /></p>
        <p style="text-indent:2em;">1.4    本服务协议、免责声明、隐私条款、单项服务协议等可由<?php echo C('BRAND_NAME');?>手机游戏平台随时更新、发布，且无需另行通知。您在使用相关服务时，应关注并遵守您所适用的相关条款。<br /></p>
       <b>二、注册信息和隐私保护</b><br />
       <p style="text-indent:2em;">2.1    <?php echo C('BRAND_NAME');?>手机游戏平台账号（即<?php echo C('BRAND_NAME');?>手机游戏平台用户ID）的所有权归<?php echo C('BRAND_NAME');?>手机游戏平台，用户完成注册申请手续后，获得<?php echo C('BRAND_NAME');?>手机游戏平台账号的使用权。用户应提供及时、详尽及准确的个人资料，并不断更新注册资料，符合及时、详尽准确的要求。所有原始键入的资料将作为注册资料。如果因用户注册信息不真实而引起的问题及其产生的后果，<?php echo C('BRAND_NAME');?>手机游戏平台不负任何责任。<br /></p>
       <p style="text-indent:2em;">2.2    用户不得将其账号、密码转让或出借予他人使用。如用户发现其账号遭他人非法使用，应立即通知<?php echo C('BRAND_NAME');?>手机游戏平台。因黑客行为或用户的保管疏忽导致账号、密码遭他人非法使用，<?php echo C('BRAND_NAME');?>手机游戏平台不承担任何责任。<br /></p>
       <p style="text-indent:2em;">2.3    <?php echo C('BRAND_NAME');?>手机游戏平台不对外公开或向第三方提供单个用户的注册资料，除非：<br /></p>
       <p style="text-indent:2em;">1) 事先获得用户的明确授权；<br /></p>
       <p style="text-indent:2em;">2) 只有透露您的个人资料，才能提供您所要求的产品和服务；<br /></p>
       <p style="text-indent:2em;">3) 根据有关的法律法规要求；<br /></p>
       <p style="text-indent:2em;">4) 按照相关政府主管部门的要求；<br /></p>
       <p style="text-indent:2em;">5) 为维护<?php echo C('BRAND_NAME');?>的合法权益。<br /></p>
       <p style="text-indent:2em;">2.4    在您注册<?php echo C('BRAND_NAME');?>手机游戏平台账户，使用其他<?php echo C('BRAND_NAME');?>手机游戏平台产品或服务，访问<?php echo C('BRAND_NAME');?>手机游戏平台网页或参加促销和有奖游戏时，<?php echo C('BRAND_NAME');?>手机游戏平台会收集您的个人身份识别资料（请参见隐私条款），并会将这些资料用于为您提供的服务及网页内容。<br /></p>
       <p style="text-indent:2em;">2.5    用户的<?php echo C('BRAND_NAME');?>手机游戏平台帐号在任何连续180日内未实际使用，则<?php echo C('BRAND_NAME');?>手机游戏平台有权删除该帐号并停止为您提供相关的网络服务，但单项服务协议有特殊规定的除外。<br /></p>
       <b>三、使用规则</b><br />
       <p style="text-indent:2em;">3.1    用户在使用<?php echo C('BRAND_NAME');?>手机游戏平台网络服务时，必须遵守中华人民共和国相关法律法规的规定，用户应同意将不会利用本服务进行任何违法或不正当的活动，包括但不限于下列行为：<br /></p>
       <p style="text-indent:2em;">3.1.1    上载、展示、张贴、传播或以其它方式传送含有下列内容之一的信息：<br /></p>
       <p style="text-indent:2em;">1) 反对宪法所确定的基本原则的；<br /></p>
       <p style="text-indent:2em;">2) 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；<br /></p>
       <p style="text-indent:2em;">3) 损害国家荣誉和利益的；<br /></p>
       <p style="text-indent:2em;">4) 煽动民族仇恨、民族歧视、破坏民族团结的；<br /></p>
       <p style="text-indent:2em;">5) 破坏国家宗教政策，宣扬邪教和封建迷信的；<br /></p>
       <p style="text-indent:2em;">6) 散布谣言，扰乱社会秩序，破坏社会稳定的；<br /></p>
       <p style="text-indent:2em;">7) 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；<br /></p>
       <p style="text-indent:2em;">8) 侮辱或者诽谤他人，侵害他人合法权利的；	<br /></p>
       <p style="text-indent:2em;">9) 含有虚假、有害、胁迫、侵害他人隐私、骚扰、侵害、中伤、粗俗、猥亵、或其它道德上令人反感的内容；<br /></p>
       <p style="text-indent:2em;">10) 含有中国法律、法规、规章、条例以及任何具有法律效力之规范所限制或禁止的其它内容的。<br /></p>
       <p style="text-indent:2em;">3.1.2    不得为任何非法目的而使用网络服务系统。<br /></p>
       <p style="text-indent:2em;">3.1.3    不利用本公司网络服务从事以下活动：<br /></p>
       <p style="text-indent:2em;">1) 未经允许，进入计算机信息网络或者使用计算机信息网络资源的；<br /></p>
       <p style="text-indent:2em;">2) 未经允许，对计算机信息网络功能进行删除、修改或者增加的；<br /></p>
       <p style="text-indent:2em;">3) 未经允许，对进入计算机信息网络中存储、处理或者传输的数据和应用程序进行删除、修改或者增加的；<br /></p>
       <p style="text-indent:2em;">4) 故意制作、传播计算机病毒等破坏性程序的；<br /></p>
       <p style="text-indent:2em;">5) 其他危害计算机信息网络安全的行为。<br /></p>
       <p style="text-indent:2em;">3.2    用户违反本服务协议，导致或产生的任何第三方主张的任何索赔、要求或损失，包括合理的律师费，您同意赔偿<?php echo C('BRAND_NAME');?>与合作公司、关联公司，并使之免受损害。对此，<?php echo C('BRAND_NAME');?>有权视用户的行为性质，采取包括但不限于删除用户发布信息内容、暂停使用许可、终止服务、限制使用、回收<?php echo C('BRAND_NAME');?>账号、追究法律责任等措施。对恶意注册<?php echo C('BRAND_NAME');?>账号或利用<?php echo C('BRAND_NAME');?>账号进行违法活动、捣乱、骚扰、欺骗、其他用户以及其他违反本协议的行为，<?php echo C('BRAND_NAME');?>有权回收其账号。同时，<?php echo C('BRAND_NAME');?>会视司法部门的要求，协助调查。<br /></p>
	   <p style="text-indent:2em;">3.3    用户须对自己在使用<?php echo C('BRAND_NAME');?>手机游戏平台网络服务过程中的行为承担法律责任。用户承担法律责任的形式包括但不限于：对受到侵害者进行赔偿，以及在<?php echo C('BRAND_NAME');?>手机游戏平台在先承担了因用户行为导致的行政处罚或侵权损害赔偿责任后，用户应给予<?php echo C('BRAND_NAME');?>手机游戏平台等额的赔偿。<br /></p>
       <b>四、服务内容</b><br />
       <p style="text-indent:2em;">4.1    <?php echo C('BRAND_NAME');?>手机游戏平台网络服务的具体内容由<?php echo C('BRAND_NAME');?>手机游戏平台根据实际情况提供，<?php echo C('BRAND_NAME');?>手机游戏平台对其提供之服务拥有最终解释权。<br /></p>
	   <p style="text-indent:2em;">4.2    除非本服务协议另有其它明确规定，<?php echo C('BRAND_NAME');?>手机游戏平台在用户注册后所推出的新产品、新功能、新服务，均受到本服务协议之规范。<br /></p>
	   <p style="text-indent:2em;">4.3    为使用本网络服务，您必须能够自行经有法律资格对您提供互联网接入服务的第三方，进入国际互联网，并应自行支付相关服务费用。此外，您必须自行配备及负责与国际联网连线所需之一切必要装备，包括计算机、数据机或其它存取装置。<br /></p>
	   <p style="text-indent:2em;">4.4    <?php echo C('BRAND_NAME');?>手机游戏平台会根据您的搜索需求提供与其它国际互联网上之网站或资源之链接。由于<?php echo C('BRAND_NAME');?>手机游戏平台无法控制这些网站及资源，您了解并同意，此类网站或资源是否可供利用，<?php echo C('BRAND_NAME');?>手机游戏平台不予负责，存在或源于此类网站或资源之任何内容、广告、产品或其它资料，<?php echo C('BRAND_NAME');?>手机游戏平台亦不予保证或负责。因使用或依赖任何此类网站或资源发布的或经由此类网站或资源获得的任何内容、商品或服务所产生的任何损害或损失，<?php echo C('BRAND_NAME');?>手机游戏平台对其合法性不负责，亦不承担任何责任。<br /></p>
	   <p style="text-indent:2em;">4.5    用户完全理解并同意，任何原因下，用户使用网路服务储存的信息或数据等全部或部分发生毁损、灭失或无法恢复的风险，均由用户须自行承担，<?php echo C('BRAND_NAME');?>手机游戏平台不承担任何责任。<br /></p>
	   <p style="text-indent:2em;">4.6    用户了解并理解，任何经由本服务发布的图形、图片或个人言论等，均表示了内容提供者、服务使用者个人的观点、观念和思想，并不代表<?php echo C('BRAND_NAME');?>手机游戏平台的观点或主张，对于在享受网络服务的过程中可能会接触到令人不快、不适当等内容的，由用户个人自行加以判断并承担所有风险，<?php echo C('BRAND_NAME');?>手机游戏平台不承担任何责任。<br /></p>
	   <p style="text-indent:2em;">4.7    用户完全理解并同意，用户通过<?php echo C('BRAND_NAME');?>手机游戏平台服务购买酒店或此后新的产品和服务，将按照网络服务中展示的说明、规定或政策等合理、及时维护自身合法权益，履行相关义务，该等说明、规定或政策等与本服务协议共同构成用户与<?php echo C('BRAND_NAME');?>手机游戏平台的整体协议，用户必须严格遵守。<br /></p>
	   <p style="text-indent:2em;">4.8    用户完全理解并同意，<?php echo C('BRAND_NAME');?>手机游戏平台将会通过邮件、短信、电话等形式，向您发送订单信息、促销活动等告知信息服务。如您不同意该等信息服务，可以按照<?php echo C('BRAND_NAME');?>手机游戏平台提供的方式取消该等服务。<br /></p>
	   <b>五、服务的变更、中断或终止</b><br />
       <p style="text-indent:2em;">5.1    用户完全理解并同意，本服务涉及到互联网及移动通讯等服务，可能会受到各个环节不稳定因素的影响。因此任何因不可抗力、计算机病毒或黑客攻击、系统不稳定、用户所在位置、用户关机、GSM网络、互联网络、通信线路原因等造成的服务中断或可能造成服务取消或终止的风险，使用本服务的用户须自行承担以上风险，<?php echo C('BRAND_NAME');?>手机游戏平台对服务之及时性、安全性、准确性不做任何保证。<br /></p>
	   <p style="text-indent:2em;">5.2    <?php echo C('BRAND_NAME');?>手机游戏平台需要定期或不定期地对提供网络服务的平台或相关的设备进行检修或者维护，如因此类情况而造成网络服务（包括收费网络服务）在合理时间内的中断，<?php echo C('BRAND_NAME');?>手机游戏平台无需为此承担任何责任。<?php echo C('BRAND_NAME');?>手机游戏平台保留不经事先通知为维修保养、升级或其它目的暂停全部或部分的网络服务的权利。<br /></p>
	   <p style="text-indent:2em;">5.3    用户完全理解并同意，除本服务协议另有规定外，鉴于网络服务的特殊性，<?php echo C('BRAND_NAME');?>手机游戏平台有权随时变更、中断或终止部分或全部的网络服务，且无需通知用户，也无需对任何用户或任何第三方承担任何责任。<br /></p>
	   <b>六、知识产权和其他合法权益</b><br />
       <p style="text-indent:2em;">6.1    用户专属权利<br /></p>
	   <p style="text-indent:2em;">6.1.1    <?php echo C('BRAND_NAME');?>手机游戏平台尊重他人知识产权和合法权益，呼吁用户也要同样尊重知识产权和他人合法权益。若您认为您的知识产权或其他合法权益被侵犯，可以向<?php echo C('BRAND_NAME');?>手机游戏平台发出权利通知。为有效处理您的投诉，请按照以下说明向<?php echo C('BRAND_NAME');?>手机游戏平台提供资料：<br /></p>
	   <p style="text-indent:2em;">1) 权利人对涉嫌侵权内容拥有知识产权或其他合法权益和/或依法可以行使知识产权或其他合法权益的权属证明；<br /></p>
	   <p style="text-indent:2em;">2) 请充分、明确地描述被侵犯知识产权或其他合法权益的情况；<br /></p>
	   <p style="text-indent:2em;">3) 请指明涉嫌侵权网页的哪些内容侵犯了2)中列明的权利；<br /></p>
	   <p style="text-indent:2em;">4) 请提供权利人具体的联络信息，包括姓名、身份证或护照复印件（对自然人）、单位登记证明复印件（对单位）、通信地址、电话号码、传真和电子邮件；<br /></p>
	   <p style="text-indent:2em;">5) 请提供涉嫌侵权内容在信息网络上的位置（如指明您举报的含有侵权内容的出处，即：指网页地址或网页内的位置）以便我们与您举报的含有侵权内容的网页的所有权人或管理人联系；<br /></p>
	   <p style="text-indent:2em;">6) 请在权利通知中加入如下关于通知内容真实性的声明："我保证，本通知中所述信息是充分、真实、准确的，如果本权利通知内容不完全属实，本人将承担由此产生的一切法律责任。"<?php echo C('BRAND_NAME');?>手机游戏平台一旦收到符合上述要求之通知，<?php echo C('BRAND_NAME');?>手机游戏平台将采取包括删除等相应措施。如不符合上述条件，<?php echo C('BRAND_NAME');?>手机游戏平台会请您提供相应信息，且暂不采取包括删除等相应措施。<?php echo C('BRAND_NAME');?>手机游戏平台提请您注意：如果您的权利通知的陈述失实，权利通知提交者将承担对由此造成的全部法律责任（包括但不限于赔偿各种费用及律师费）。如果上述个人或单位不确定网络上可获取的资料是否侵犯了其知识产权和其他合法权益，<?php echo C('BRAND_NAME');?>手机游戏平台建议该个人或单位首先咨询专业人士。<br /></p>
	   <p style="text-indent:2em;">6.1.2    对于用户通过网络服务上传的任何在公开区域可获取的并受著作权保护的内容，用户应对该等内容的真实性、合法性负责，保证对该等内容拥有完整的、无瑕疵的所有权和知识产权或拥有完整的授权，并不存在任何侵犯或足以侵犯任何第三方的合法权益，包括但不限于著作权、肖像权、商标权、专利权、企业名称权、商号权、商业秘密、个人隐私、合同权利等权利。所有因用户非法上传内容而给任何第三方或<?php echo C('BRAND_NAME');?>手机游戏平台造成的损害均由用户个人承当全部的责任，<?php echo C('BRAND_NAME');?>手机游戏平台不承担任何责任，且<?php echo C('BRAND_NAME');?>手机游戏平台有义务配合第三方、司法机关或行政机关完成相关的取证，并根据法律、主管部门或司法部门的要求将用户注册信息给予披露。用户完全理解并同意，就前款内容上附有的所有著作权财产权权利，授予<?php echo C('BRAND_NAME');?>手机游戏平台在全世界范围内具有免费的、永久性的、不可撤销的、非独家的的许可以及再许可的权利。用户同时授予<?php echo C('BRAND_NAME');?>手机游戏平台就任何主体侵犯该等内容的知识产权的行为而单独采用法律救济手段包括诉讼，并获得全部赔偿的权利。用户浏览、复制、打印和传播<?php echo C('BRAND_NAME');?>手机游戏平台其他用户上传到<?php echo C('BRAND_NAME');?>手机游戏平台网站的内容，应保证仅用于个人欣赏之目的，不得用于商业目的，不得侵犯权利人的合法权益以及<?php echo C('BRAND_NAME');?>手机游戏平台的合法权益和商业利益。任何用户违反此条规定的，<?php echo C('BRAND_NAME');?>手机游戏平台均有权以自身名义利用法律手段寻求权利救济或据本协议追究您的违约责任。<br /></p>
	   <p style="text-indent:2em;">6.2    <?php echo C('BRAND_NAME');?>手机游戏平台专属权利<br /></p>
	   <p style="text-indent:2em;">6.2.1	除用户上传内容以及明显归属于合作伙伴、第三方所有的信息资料外，<?php echo C('BRAND_NAME');?>手机游戏平台拥有网路服务内所有内容，包括但不限于文字、图片、图形、表格、动画、程序、软件等单独或组合的版权。任何被授权的浏览、复制、打印和传播属于本网站内的内容必须符合以下条件：<br /></p>
	   <p style="text-indent:2em;">1) 所有的资料和图象均以获得信息为目的；<br /></p>
	   <p style="text-indent:2em;">2) 所有的资料和图象均不得用于商业目的；<br /></p>
	   <p style="text-indent:2em;">3) 所有的资料、图象及其任何部分都必须包括此版权声明。未经<?php echo C('BRAND_NAME');?>手机游戏平台许可，任何人不得擅自，包括但不限于以非法的方式复制、传播、展示、镜像、上载、下载使用。否则，<?php echo C('BRAND_NAME');?>手机游戏平台将依法追究法律责任。<br /></p>
	   <p style="text-indent:2em;">6.2.2	<?php echo C('BRAND_NAME');?>手机游戏平台为提供网络服务而自行开发的软件，包括无线客户端应用等，拥有完整的知识产权。<?php echo C('BRAND_NAME');?>手机游戏平台在此授予您个人非独家、不可转让、可撤销的，并通过一个<?php echo C('BRAND_NAME');?>手机游戏平台注册账户且在一部拥有所有权或使用权的手机或计算机上使用<?php echo C('BRAND_NAME');?>手机游戏平台软件的权利，且该使用仅限于个人非商业性使用之合法目的。<?php echo C('BRAND_NAME');?>手机游戏平台有权对该等软件进行时不时的修订、扩展、升级等更新措施，而无需提前通知用户，但您有权选择是否使用更新后的软件。您应当保证合法使用该等软件，任何用户不得对该等软件进行如下违法行为：<br /></p>
	   <p style="text-indent:2em;">1) 开展反向工程、反向编译或反汇编，或以其他方式发现其原始编码，以及实施任何涉嫌侵害著作权的行为；<br /></p>
	   <p style="text-indent:2em;">2) 以出租、租赁、销售、转授权、分配或其他任何方式向第三方转让该等软件或利用该等软件为任何第三方提供相似服务；<br /></p>
	   <p style="text-indent:2em;">3) 任何复制该等软件的行为；<br /></p>
	   <p style="text-indent:2em;">4) 以移除、规避、破坏、损害或其他任何方式干扰该等软件的安全功能；<br /></p>
	   <p style="text-indent:2em;">5) 以不正当手段取消该等软件上权利声明或权利通知的；<br /></p>
	   <p style="text-indent:2em;">6) 其他违反法律规定的行为。<br /></p>
	   <p style="text-indent:2em;">6.2.3    "<?php echo C('BRAND_NAME');?>手机游戏平台"、<?php echo C('BRAND_NAME');?>手机游戏平台网络服务LOGO等为<?php echo C('BRAND_NAME');?>手机游戏平台的注册商标，受法律的保护。任何用户不得侵犯<?php echo C('BRAND_NAME');?>手机游戏平台注册商标权。<br /></p>
	   <b>七、其他</b><br />
       <p style="text-indent:2em;">7.1    本协议的订立、执行和解释及争议的解决均应适用中华人民共和国法律。<br /></p>
	   <p style="text-indent:2em;">7.2    如双方就本协议内容或其执行发生任何争议，双方应尽量友好协商解决；协商不成时，任何一方均可向<?php echo C('BRAND_NAME');?>所在地的人民法院提起诉讼。<br /></p>
	   <p style="text-indent:2em;">7.3    <?php echo C('BRAND_NAME');?>手机游戏平台未行使或执行本服务协议任何权利或规定，不构成对前述权利或权利之放弃。<br /></p>
	   <p style="text-indent:2em;">7.4    如本协议中的任何条款无论因何种原因完全或部分无效或不具有执行力，本协议的其余条款仍应有效并且有约束力。请您在发现任何违反本服务协议情形时，通知<?php echo C('BRAND_NAME');?>手机游戏平台。您可以通过在线反馈方式提交您的意见。<br /></p>
	  </div>
	</div>
  </div>
  
</div>
<div class="clear"></div>
<div class="footer">
	<div style="padding-top:25px;">
		<p style=" padding-bottom:10px;"><a href="<?php echo U('AboutUs/index',array('show'=>'us'));?>">关于我们</a>  |  <a href="<?php echo U('AboutUs/index',array('show'=>'hezuo'));?>">商务合作</a>  |  <a href="<?php echo U('AboutUs/index',array('show'=>'zhaopin'));?>">人才招聘</a>  |  <a href="<?php echo U('AboutUs/index',array('show'=>'lianxi'));?>">联系我们</a></p>
		<p>健康游戏忠告：抵制不良游戏 拒绝盗版游戏 注意自我保护 谨防受骗上当 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活<br />
	<a href="http://www.miitbeian.gov.cn"></a>&nbsp;&nbsp;<?php echo C('COMPANY_NAME');?>版权所有</p>
	</div>
	
	

<script type="text/javascript">
function closes(id){
	document.getElementById(id).style.display='none';
}
</script>

</p>
</div>

<!--[if IE 6]>
<style type="text/css">
* html #weixin{position:absolute;
right:expression(eval(document.documentElement.scrollLeft+document.documentElement.clientWidth
-this.offsetWidth)-(parseInt(this.currentStyle.marginLeft,10)||0)-(parseInt(this.currentStyle.marginRight,10)||0));
top:expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-this.offsetHeight
-(parseInt(this.currentStyle.marginTop,10)||0)-(parseInt(this.currentStyle.marginBottom,10)||0)))}
</style>
<![endif]-->


</body>
</html>
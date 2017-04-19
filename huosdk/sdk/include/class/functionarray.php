<?php
//数字对应参数编号 
/*
1	a	app_id
2	b	username
3	c	IMEI
4	d	from
5	e	agentgame
6	f	deviceinfo
7	g	userua
8	h	password
9	i	server
10	j	payway
11	k	productname
12	l	productdesc
13	m	attach
14	n	num
15	o	amount
16	p	role
17	q	issend
18	r	sendcode
19	s	userphone
20	t	paystatus
21	u	page
22	v	mem_id
23	w	code
24	x	client_id
25	y	api_token
26	z	user_token
27	az	param_token
28	ab	version
29	ac	ipaddrid //在支付pay.php有可能这个值为“”，所有不需要去验证 @write by ling

*/
return array (
    'init' => array(23, 4, 24, 25, 27 , 1,28,3,6,7),
    'initpay' => array(23, 4, 24, 25, 27 , 1),
    'notice' => array(23, 4, 24, 25, 27 , 1),
    'registerOne' => array(23, 4, 24, 25, 27 , 1,  5, 6, 7),
    'registerNew' => array(23, 4, 24, 25, 27 , 1, 2,  5, 6, 7, 8),
    'registerMobile' => array(23, 4, 24, 25, 27 , 1, 2,  5, 6, 7, 8,17,18,19 ),
    'login' => array(23, 4, 25, 27, 24, 1, 2, 5, 6, 7, 8),
    'loginMobile' => array(23, 4, 25, 27, 24, 1, 2, 5, 6, 7, 17,18, 19),
    'loginOauth' => array(23, 4, 24, 25, 27 , 1, 2, 5, 6, 7, 8),
    'logout' => array(23, 4, 25, 27, 24, 1, 2,  5, 6, 7, 22,26),
    'float' => array(5, 6, 7,22,26),
    'pay' => array(23, 4, 25, 27, 24, 1, 5, 6, 7,9,11,12,13,15,16,22,26),
    'queryOrder' => array(26,37),
    'payRecord' => array(6, 7,20,21,26),
    'getptb' => array(1,22,23,24,25,26,27),
    'getmoney' => array(1,22,23,24,25,26,27),
    'ptbpay' => array(1,14,15,22,23,24,25,26,27,35),
    'applepay' => array(1,15,22,23,24,25,26,27,35),
    'gamepay' => array(1,15,20,22,23,24,25,26,27,35),
    'alipay' => array(1,15,22,23,24,25,26,27,35),
    'xqtpay' => array(1,15,22,23,24,25,26,27,35),
    'payeco' => array(1,15,22,23,24,25,26,27,35),
    'jubaopay' => array(1,15,22,23,24,25,26,27,35),
    'unionpay' => array(1,15,22,23,24,25,26,27,35),
    'spay' => array(1,15,22,23,24,25,26,27,35),   
    'shenzhoufu' => array(1,15,22,23,24,25,26,27,35),
    'shengpay' => array(1,15,22,23,24,25,26,27,35)
);
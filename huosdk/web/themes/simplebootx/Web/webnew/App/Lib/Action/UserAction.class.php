<?php
    /*
     * xiucms
     * 
     */
    class UserAction extends CommonAction {
    	function index(){
    		if(!$GLOBALS['user']){
    			url_redirect(url('User#login'));
    		}
    		if($GLOBALS['user']['emailpassed'] == 0){
    			url_redirect(url('User#do_email'));
    		}
    		$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
    		$perpage = 20;
    		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
    		
    		$where = " WHERE c.userid = ".$GLOBALS['user']['id'];
    		
    		$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."card c $where");
    		
    		$list = $GLOBALS['db']->getAll("SELECT gc.id,gc.name,c.card_idno,c.collection_time,gc.end_time FROM ".DB_PREFIX."card c
    										LEFT JOIN ".DB_PREFIX."game_card gc ON c.card_id = gc.id $where $orderby $limit");
    		
    		$pages = new Page_1($count,$perpage);
    		$pages = $pages->show();
    		$this->assign("list",$list);
    		$this->assign("pages",$pages);
    		
    		$this->display();
    	}
    	
   
    	function login(){
    		if($GLOBALS['user']){
    			url_redirect(url());
    		}
    		$this->cookie->set("from_url",$_SERVER['HTTP_REFERER'],3600);
    		$this->assign("username",$this->cookie->get("username"));
    		$seo['title'] = "会员登录-".sysconf("SITE_NAME");
    		$seo['keywords'] = sysconf("SITE_NAME")."登录";
    		$seo['description'] = "";
    		$this->assign("seo",$seo);
    		$this->display();
    	}
    	
    	function dologin(){
    	    if($GLOBALS['user']){
    	    	url_redirect(url());
    	    }
    	    if($_POST){
    	        $result = $this->do_login($_POST['username'], $_POST['password']);
    	        if($result == -1){
    	        	showMsg("用户不存在",url()."index.php/User/login");
    	        }
    	        elseif($result == 0){
    	            showMsg("密码错误",url()."index.php/User/login");
    	        }
    	        elseif($result == 1){
    	            if($_POST['is_username'] == "1"){
    	            	$this->cookie->set("username",$_POST['username'],'3600');
    	            }else{
    	                $this->cookie->delete("username");
    	            }
    	            if($this->cookie->get("from_url")){
    	                $from_url = $this->cookie->get("from_url");
    	                $this->cookie->delete("from_url");
    	                
    	                $userid = intval($this->session->get('userid'));
    	                showMsg("登录成功",$from_url);
    	            }else{
    	                $userid = intval($this->session->get('userid'));
    	                showMsg("登录成功",url());
    	            }
    	            
    	        }elseif($result == 2){
    	            showMsg("用户已被冻结，请联系客服",url()."index.php/User/login");
    	        }
    	        else{
    	            showMsg("登录失败",url()."index.php/User/login");
    	        }
    	    }
    	}
    	
    	
    	function register(){
    	    if($GLOBALS['user']){
    	    	url_redirect(url());
    	    }
    	    $seo['title'] = "会员注册-".sysconf("SITE_NAME");
    	    $seo['keywords'] = sysconf("SITE_NAME")."注册";
    	    $seo['description'] = "";
    	    $this->assign("seo",$seo);
    		$this->display();
    	}
    	
    	
    	function do_register(){
    	    if($GLOBALS['user']){
    	    	url_redirect(url());
    	    }

    	    if($_POST){
    	        foreach($_REQUEST as $k=>$v){
    	        	$_REQUEST[$k] = new_addslashes(trim($v));
    	        }
    	        if(strtolower($_POST['verify']) != strtolower($this->session->get("verify"))){
					showMsg("验证码不正确","goback");
				}
    	        
				$arr2 = array(
						'~', '!', '@', '#', '$', '%', '^', '&', '*', '_', '+', '|', '-', '=', '\\',
						'{', '}', '[', ']', ':', ';', '"', '\'', '<', '>', ',', '.', '?', '/', '“', '”',
						'’', '‘', '【', '】', '~', '！', '￥', '……', '——', '、', '《', '》', '。',
						PHP_EOL, chr(10), chr(13), "\t", chr(32)
				);
				
				
				if($arr2){
					foreach($arr2 as $k=>$v){
						if(strpos($_POST['username'],$v) !== false){
							showMsg("用户名不能含有空格、点、逗号等特殊字符","goback",-1);
						}
					}
				}
				$email = trim(new_addslashes($_POST['email']));
				if(!check_email($email)){
					showMsg("邮箱不正确","goback");
				}
				
    	    	if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."user WHERE username = '".str_replace(' ', '',$_POST['username'])."' AND emailpassed = 1")){
    	    		showMsg("用户名已存在","goback");
    	    	}
    	    	if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."user WHERE email = '".$_POST['email']."' AND emailpassed = 1")){
    	    		showMsg("邮箱已存在","goback");
    	    	}
    	    	
    	    	if(strlen(str_replace(' ','',$_POST['username'])) < 3){
    	    		showMsg("用户名长度不能少于3位","goback");
    	    	}
    	    	if(preg_match('/^\d+$/i', str_replace(' ','',$_POST['username']))){
    	    		showMsg("用户名不能为纯数字","goback");
    	    	}
    	    	if(strlen(str_replace(' ','',$_POST['username'])) > 16){
    	    		showMsg("用户名长度不能长于16位","goback");
    	    	}
    	    
    	    	if(strlen($_POST['password']) < 6){
    	    		showMsg("密码长度不能少于6位","goback");
    	    	}
    	    
    	    	if(strlen($_POST['password']) > 20){
    	    		showMsg("密码长度不能长于20位","goback");
    	    	}
    	    	
    	    	$rand_str = "abcdefghijklmnopqrstuvwxyz";
    	    	$salt = "";
    	    	for($i=0;$i<6;$i++){
    	    		$salt .= $rand_str{rand(0,25)};
    	    	}
    	    	$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user WHERE email = '$email' AND emailpassed = 0");
    	    	if($user){
    	    		$user['username'] = str_replace(' ','',$_POST['username']);
    	    		$user['password'] = md5(md5($_POST['password']).$salt);
    	    		$user['create_time'] = time();
					$user['email'] = $email;
    	    		$user['type'] = 0;
    	    		$user['salt'] = $salt;
    	    		
    	    		$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,"UPDATE","email = '$email' AND emailpassed = 0");
    	    	}else{
    	    		$user = array();
    	    		$user['username'] = str_replace(' ','',$_POST['username']);
    	    		$user['password'] = md5(md5($_POST['password']).$salt);
    	    		$user['create_time'] = time();
					$user['email'] = $email;
    	    		$user['type'] = 0;
    	    		$user['salt'] = $salt;
    	    		 
    	    		$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,"INSERT");
    	    	}
    	    	
        	    //$this->do_login($_POST['username'],$_POST['password']);
    			$this->session->set("username",$user['username']);
    			$this->session->set("password",$_POST['password']);
				$this->assign("email",$email);
    	    	$this->display();
    	    }else{
    			showMsg("参数不正确");
    		}
    	}
    	
		function emailpassed(){
			$email = trim(new_addslashes($_POST['email']));
			$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user WHERE email = '$email'");
			if(!$user){
        		showMsgajax("邮箱不正确",-1);
        	}
			if($user['emailpassed'] == 1){
				showMsgajax("邮箱已绑定",-1);
			}
			$str1 = "0123456789";
    		$code = "";
    		$str1 = str_shuffle($str1);
    		for($i=0;$i<6;$i++){
    			$num = rand(100000,999999);
    			$code .= $str1{$num%10};
    		}
    		
    		$user['verify'] = $code;
    		$user['verify_time'] = time();
    		$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,"UPDATE","id=".$user['id']);
			$title = "邮箱认证";
			$content = "您的验证码为 ".$code."，打死也不要告诉别人。";
			$result = sendmail($email, $title, $content);
			if($result){
    			showMsgajax("验证码发送成功",1);
    		}else{
    		    showMsgajax("验证码发送失败",-1);
    		}
		}
		
    	function do_email(){
    		if($_GET['email']){
    			$email = trim(new_addslashes($_GET['email']));
    			$verify = trim(new_addslashes($_GET['verify']));
    			$verify_time = trim(new_addslashes($_GET['verify_time']));
    			$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user WHERE email = '$email'");
    			if(!$user || $user['emailpassed'] == 1){
    				showMsg("该邮箱已绑定账号,重新注册",url('User#register'));
    			}
    			if($verify != md5($email.$user['salt'].$user['id'])){
    				showMsg("参数错误,重新注册",url('User#register'));
    			}
    			$user['emailpassed'] = 1;
    			$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,"UPDATE","email = $email");
    			$this->display("reg_success");
    		}else{
    			if(!$GLOBALS['user']){
    				url_redirect(url('User#login'));
    			}
    			$this->display();
    		}
    	}
    	
    	 function reg_success(){
    	    if($_POST['verify']){
    	    	$email = new_addslashes(trim($_POST['email']));
    	    	$verify = new_addslashes(trim($_POST['verify']));
    	    	$username = $this->session->get("username");
    	    	$password = $this->session->get("password");
    	    	
    	    	$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user WHERE email = '$email'");
    	    	
    	    	if(!$user){
    	    		url_redirect(url());
    	    	}
    	    	
    	    	if($verify == ""){
    	    		showMsg("请输入验证码","goback");
    	    	}
    	    	$time = time();
    	    	if($time - $user['verify_time'] > 3600){
    	    		showMsg("验证码已过期","goback");
    	    	}
    	    	if($verify != $user['verify']){
    	    		showMsg("验证码错误","goback");
    	    	}
    	    	
    	    	$user['emailpassed'] = 1;
    	    	$user['verify'] = "";
    	    	$user['verify_time'] = 0;
    	    	$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,"UPDATE","id='".$user['id']."'");

    	    	//add_point(1, $user['id']);
    	    	if($username){
    	    	    $this->do_login($username,$password);
    	    	    //showMsg("注册成功",url());
    	    	    $this->display("User:reg_success");
    	    	}
    	    }else{
    	        url_redirect(url());
    	    }
    	}
    	
    	
    	
    	///忘记密码第一步
    	function forget_pwd(){
    		if($GLOBALS['user']){
    			url_redirect(url());
    		}

    		$seo['title'] = "找回密码-".sysconf("SITE_NAME");
    		$seo['keywords'] = "找回密码-".sysconf("SITE_NAME");
    		$seo['description'] = "";
    		$this->assign("seo",$seo);
    		
    		$this->display();
    	}
    	
    	///忘记密码第二步
    	function forget_pwd2(){
    	    if($GLOBALS['user']){
    	    	url_redirect(url());
    	    }
    	    $email = trim(new_addslashes($_POST['email']));
			$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user WHERE email = '$email'");
    	    if(!$user || $user['emailpassed'] == 0){
    	    	showMsg("该邮箱未绑定账号","goback");
    	    }else{
    	        if(strtolower($_POST['verify']) != strtolower($this->session->get("verify"))){
    	        	showMsg("验证码不正确","goback");
    	        }
    	        $title = "密码找回-".$GLOBALS['site']['site_name'];
    	        $url = url("User#forget_pwd3")."?email=".$email."&verify=".md5($user['email'].$user['salt'].$user['id'])."&verify_time=".time();
    	        $content = '请点击以下链接进行邮箱验证，<br><a href="'.$url.'" target="_blank" style="color: rgb(255,255,255);text-decoration: none;display: block;min-height: 39px;width: 158px;line-height: 39px;background-color:rgb(80,165,230);font-size:20px;text-align:center;">邮箱验证</a>';
    	        $content .= "<br>如果您无法点击以上链接，请复制以下网址到浏览器里直接打开：<br>".$url;
    	        $result = sendmail($_POST['email'], $title, $content);
    	        $seo['title'] = "密码找回-".$GLOBALS['site']['site_name'];
    	        $seo['keywords'] = "密码找回-".$GLOBALS['site']['site_name'];
    	        $seo['description'] = "";
    	        $this->assign("seo",$seo);
    	        $this->assign("email",$email);
    	        $this->assign("result",1);
    	        $this->display();
    	    }
    	}
    	
    	function findemailpwd(){
    		$email = trim(new_addslashes($_POST['email']));
    		$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user WHERE email = '$email'");
    		if(!$user || $user['emailpassed'] == 0){
    			showMsg("该邮箱未绑定账号","goback");
    		}
    		$title = "密码找回-".$GLOBALS['site']['site_name'];
    		$url = url("User#forget_pwd3")."?email=".$email."&verify=".md5($user['email'].$user['salt'].$user['id'])."&verify_time=".time();
    		$content = '请点击以下链接进行邮箱验证，<br><a href="'.$url.'" target="_blank" style="color: rgb(255,255,255);text-decoration: none;display: block;min-height: 39px;width: 158px;line-height: 39px;background-color:rgb(80,165,230);font-size:20px;text-align:center;">邮箱验证</a>';
    		$content .= "<br>如果您无法点击以上链接，请复制以下网址到浏览器里直接打开：<br>".$url;
    		$result = sendmail($_POST['email'], $title, $content);
    		if($result){
    			showMsgajax("发送成功");
    		}else{
    			showMsgajax("发送失败",-1);
    		}
    	}
    	
    	///忘记密码第三步
    	function forget_pwd3(){
    		if($GLOBALS['user']){
    			url_redirect(url());
    		}
    		if($_GET){
    			$email = trim(new_addslashes($_GET['email']));
    			$verify = trim(new_addslashes($_GET['verify']));
    			$verify_time = trim(new_addslashes($_GET['verify_time']));
    			$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user WHERE email = '$email'");
    			if(!$user){
    				showMsg("该邮箱未绑定账号,重新输入邮箱地址",url('User#forget_pwd'));
    			}
    			if($verify != md5($email.$user['salt'].$user['id'])){
    				showMsg("参数错误,重新输入邮箱地址",url('User#forget_pwd'));
    			}
    			$this->assign("email",$email);
    			$this->assign("verify",$verify);
    			$this->display();
    		}
    	}
    	
    	//忘记密码 修改密码
    	function change_pwd(){
    		if($GLOBALS['user']){
    			url_redirect(url());
    		}
    			
    		if($_POST){
    			$email = trim(new_addslashes($_POST['email']));
    			$verify = trim(new_addslashes($_POST['verify']));
    				
    			if($verify == ""){
    				showMsg("验证错误","goback");
    			}
    			
    			$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user WHERE email = '$email'");
    			
    			if(!$user || $user['emailpassed']==0){
    				url_redirect(url());
    			}
    				
    			$time = time();
    		   
    			if($verify != md5($user['email'].$user['salt'].$user['id'])){
    				showMsg("参数错误","goback");
    			}
    				
    			$password = new_html_special_chars(new_addslashes($_POST['password']));
    			$password2 = new_html_special_chars(new_addslashes($_POST['password2']));
    	
    			if(strlen($password) < 6){
    				showMsg("密码长度不能少于6位","goback");
    			}
    			 
    			if(strlen($password) > 20){
    				showMsg("密码长度不能长于20位","goback");
    			}
    	
    			if($password != $password2){
    				showMsg("两次输入密码不一致","goback");
    			}
    	        
    			$rand_str = "abcdefghijklmnopqrstuvwxyz";
    			$salt = "";
    			for($i=0;$i<6;$i++){
    				$salt .= $rand_str{rand(0,25)};
    			}
    			 
    			$user['password'] = md5(md5($password).$salt);
    			$user['salt'] = $salt;
    			$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,"UPDATE","id='".$user['id']."'");
    			$this->display('forget_pwd4');    				
    		}else{
    			url_redirect(url("User#forget_pwd"));
    		}
    	}
    	
    	
    	
    	
    		
    	function login_out(){
    	    
    		$this->do_loginout();
    		url_redirect($_SERVER['HTTP_REFERER']);
    	}
    	
    	function do_login($username,$password){
    		$username = new_addslashes($username);
    		$password = new_addslashes($password);
    		$user = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user WHERE username = '$username' AND emailpassed = 1");

    		if(!$user){
    			return -1;
    		}
    		elseif($user['password']!= md5(md5($password).$user['salt'])){
    			return 0;
    		}elseif($user['is_del']){
    			return 2;
    		}else{
    			$this->session->set("userid",$user['id' ]);
    	        //add_point(2, $user['id' ]);
    			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."user SET login_ip = '".get_client_ip()."',login_time = '".time()."' WHERE id = ".$user['id']);
    			return 1;
    		}
    		 
    	}
        
        function do_loginout(){
            unset($GLOBALS['user']);
        	$this->session->delete("userid");
        }
    }
?>
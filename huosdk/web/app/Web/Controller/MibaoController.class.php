<?php

/**
 * 密保找回密码
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class MibaoController extends HomebaseController {
    
    public function _initialize() {
        parent::_initialize();
    }
	
	//显示首页
    public function index(){
    	$this->display();
    }
    
    public function my_mbuser(){

		$show = I('show');
		$username = I('username','');
		
		if($username != ''){
			$userdate = searchuser($username);
			if(empty($userdate)){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
				echo "<script>alert('账号不存在，请重新输入');history.go(-1);</script>";
				exit();
			}
		}
		
		$action = I('action');
		if ($action && $action == 'answer'){	
			if($username != ''){			
				$usersecq = findSecretQ($username);
			}
			
			if(empty($usersecq['wentione']) || empty($usersecq['wentitwo']) || empty($usersecq['wentithree'])){	
				echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
				echo "<script>alert('未设置密保，请通过其他方式找回密码')</script>";
				echo "<meta http-equiv=refresh content=0;URL=".U('Findpwd/index?show=findpwd').">";
				exit();
			}
			$usersecq['qNameOne'] = qNamebyqNO($usersecq['wentione']);
			$usersecq['qNameTwo'] = qNamebyqNO($usersecq['wentitwo']);
			$usersecq['qNameThree'] = qNamebyqNO($usersecq['wentithree']);
			
			//把用户名加入usersecq中
			$usersecq['username'] = $username;
			$this ->assign('usersecq',$usersecq);
			$this ->display('my_mbuser');
		}
		
		if(isset($_POST['action']) && $_POST['action'] == 'secret'){
			//$username = isset($_REQUEST['username']) ? $_REQUEST['username'] :'';
			$wentione = $_POST['wentione'];
			$wentitwo = $_POST['wentitwo'];
			$wentithree = $_POST['wentithree'];
			$answerone = $_POST['answerone'];
			$answertwo = $_POST['answertwo'];
			$answerthree = $_POST['answerthree'];

			if(empty($wentione) || empty($wentitwo) || empty($wentithree) || empty($answerone)
				 || empty($answertwo)  || empty($answerthree)){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
				echo "<script>alert('请填写完整答案后再提交');history.go(-1);</script>";
				exit();
			}

/* 			magic_quotes_gpc函数在php中的作用是判断解析用户提示的数据，如包括有:post、get、cookie过来的数据增加转义字符“\”，
			以确保这些数据不会引起程序，特别是数据库语句因为特殊字符引起的污染而出现致命的错误。在magic_quotes_gpc=On的情况下，
			如果输入的数据有单引号（’）、双引号（”）、反斜线（）与NUL（NULL字符）等字符都会被加上反斜线。这些转义是必须的，
			如果这个选项为off，那么我们就必须调用addslashes这个函数来为字符串增加转义。 */ 
			if (!get_magic_quotes_gpc()) {
				$wentione = addslashes($wentione);
				$wentitwo = addslashes($wentitwo);
				$wentithree = addslashes($wentithree);
				$answerone = addslashes($answerone);
				$answertwo = addslashes($answertwo);
				$answerthree = addslashes($answerthree);
			}

			//检查问题的答案是否正确
			$rs = checkMibao($username,$wentione,$wentitwo,$wentithree,$answerone,$answertwo,$answerthree);

			if($rs){
				$this -> assign('username',$username);
				$this -> display('my_mbxgpwd');
				exit();
			}else{
				echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
				echo "<script>alert('密保回答错误，请重新输入');history.go(-1);</script>";
				exit();
			}
		}

		if(isset($_POST['action']) && $_POST['action'] == 'updatepwd'){
			
			//获取参数
			$oldpwd = $userdate['password'];
			$newpwd = isset($_POST['usrpwd']) ? $_POST['usrpwd'] : '';
			$rs = updatepwd($username,$newpwd,$oldpwd);  //更新密码
			if($rs > 0){
				/* if (C('UCENTER_ENABLED')){
					echo uc_user_synlogout();
					//$db -> sql_connect($dbhost,$dbuser,$dbpwd,$dbname,$dbport, false, false);
				} */
				
				//setcookie('sdkuser',NULL,-86400,'/',SECOND_DOMAIN);
				//setcookie('xsst_id',NULL, -86400,'/',SECOND_DOMAIN);
				session('sdkuser',null);
				session('xsst_id',null);
				
				echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
				echo "<script>alert('修改成功,请重新登录')</script>";
				echo "<meta http-equiv=refresh content=0;URL=".U('Login/index').">";
				exit();
			}else{
				echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
				echo "<script>alert('修改失败');</script>";
				echo "<meta http-equiv=refresh content=0;URL=".U('Mibao/index').">";
				exit();
			}
		}
		$this ->display();
    }
}
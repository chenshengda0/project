<?php
/**
 * 用户中心
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;


class UserController extends HomebaseController {

    public function index(){
		header("Content-Type: text/html; charset=utf-8");

    	$useindx = I('get.userinfo');
		$ask_id = I('ask_id','');
        $action = I('action');
        //$userid = I('xsst_id');
		
    	//热门游戏列表清单
		$hotgamelist = hotgamelist();
		$this -> assign("footgamelist", $hotgamelist);
		
		$username = session('user.sdkuser');
		$userid = session('user.xsst_id');
		//判断是否已经登录
		if('' == $username){
			echo "<meta http-equiv=refresh content=0;URL=".U('Login/index').">";
			exit();
		 }
			//获取用户信息
			$field = "m.mobile,m.username,m.email,i.qq,i.address,i.zipcode";
			$userdata = M("members")
			->alias("m")
			->field($field)
			->join("LEFT JOIN ".C('DB_PREFIX')."mem_info i ON m.id=i.mem_id" )
			->where("username = '%s'",$username)
			->find();
			 
			//判断跳转页面
			if($useindx == 'info'){
				$ttbdata = findttb($userid);//用户天天币
				if(empty($ttbdata)){
					$userdata['ttb'] = 0;
				}else{
					$userdata['ttb'] = $ttbdata;
				}
				$this -> assign('userdata',$userdata);
		 		$this -> display('update_info');
			}else if($useindx == 'mygame'){
				$loginfo = $this->getLoginInfo();
				$this -> assign('loginfo',$loginfo);
		 		$this -> display('my_game');
			}else if($useindx == 'update'){
		 		$this -> display('update_pwd');
			}else if($useindx == 'secret'){
				//密保界面
				$mibao = findMibao($username);
				if(!empty($mibao)){
					$mibao['qNameOne'] = qNamebyqNO($mibao['wentione']);
					$mibao['qNameTwo'] = qNamebyqNO($mibao['wentitwo']);
					$mibao['qNameThree'] = qNamebyqNO($mibao['wentithree']);
					
					$this -> assign('usersecq',$mibao);
					$this -> display('secret_pwdtwo');
					exit();
				}
				$this -> assign('userdata',$userdata);
		 		$this -> display('secret_pwd');
				exit();
			}else if($useindx == 'myask'){	
				if($ask_id != ''){
					$ask_data = checkask($userid,$ask_id); //获取提问信息
					$gamelist = gamelist();

					foreach ($gamelist as $key => $val) {
						$gamedata[$val['id']] = $val['name'];
					}
					
					foreach ($ask_data as $key=>$val) {
						$ask_data['gamename'] = $gamedata[$ask_data['gameid']];
					}
					
					$this -> assign('ask_data',$ask_data);
					$this -> assign('gamename',$ask_data['gamename']);
					$this -> display('my_ask_ck');
					exit();
				}else{
					$ask_data = asklist($userid); //获取提问列表
					$this -> assign('ask_data',$ask_data);
					$this -> display('my_ask');
				}
			}else if($useindx == 'xiaofei'){
				
				
				 $userdata = searchuser($username); //获取用户信息

				 if($action && $action == 'search'){
					$start = I('starttime','');
				 	$end = I('endtime','');
					$orderid = I('orderid','');
					
					$and = "1 ";
					$query['start'] = $start;
					$query['end'] = $end;
					$query['orderid'] = $orderid;

				 	if($orderid != ''){
				 		$and .= " AND order_id='".$orderid."' ";
				 	}
				 	if($start != ''){
				 		$start = strtotime($start." 00:00:00");
				 		$and .= " AND create_time >= '".$start."' ";
				 	}
					if($end != ''){
					 	$end = strtotime($end." 23:59:59");
				 		$and .= " AND create_time <= '".$end."' ";
				 	}
					$this -> assign('query',$query);
				 }
					$list = xiaofeiList($username,$and);
					$xflist = $list['xflist'];
					
					$gamelist = xfgame();//游戏列表

					 //添加游戏详细信息
					 foreach ($gamelist as $key => $val) {
						$gamedata[$val['id']]['gamename'] = $val['name'];
					 }
					 //添加游戏数据	
					 foreach ($xflist as $key=>$val) {
							$xflist[$key]['gamename'] = $gamedata[$val['app_id']]['gamename'];
					  }
					$showpage = $list['showpage'];
					$this -> assign('showpage',$showpage);
					$this -> assign('xflist',$xflist);
					$this -> display('my_xf');
			}else if($useindx == 'paylog'){
		 		
		 		//获取用户信息
			 	$userdata = searchuser(session('sdkuser')); 

				if(I('action') && I('action') == 'search'){
					$start = I('starttime','');
					$end = I('endtime','');
					$orderid = I('orderid','');
					
					$and = "";
					$query['start'] = $start;
					$query['end'] = $end;
					$query['orderid'] = $orderid;

				 	if($orderid != ''){
				 		$and .= " AND order_id='".$orderid."' ";
				 	}
				 	if($start != ''){
				 		$start = strtotime($start." 00:00:00");
				 		$and .= " AND create_time >= '".$start."' ";
				 	}
					if($end != ''){
					 	$end = strtotime($end." 23:59:59");
				 		$and .= " AND create_time <= '".$end."' ";
				 	}

					$this -> assign('query',$query);

				}
				
				$list = paylist($username,$and);
				$paylist = empty($list['paylist'])?'':$list['paylist'];
				$showpage = $list['showpage'];

				$this -> assign('showpage',$showpage);
				$this -> assign('paylist',$paylist);
				
				$this -> display('my_pay');
		 	}
		//}		
    }

    //获取登陆信息
    public function getLoginInfo(){

    	$loginfo = findLogininfo($userdata['id']);//登录信息
		$gamelist = gamelist();//游戏列表
		$gameinfolist = gameinfolist();//游戏描述列表
		
		foreach ($gameinfolist as $key => $val) {
			$infodata[$val['id']]['url'] = $val['url'];
			$infodata[$val['id']]['mobileicon'] = $val['mobileicon'];
		}
		//添加游戏详细信息
		foreach ($gamelist as $key => $val) {
			$gamedata[$val['id']]['gamename'] = $val['name'];
			$gamedata[$val['id']]['url'] =  $infodata[$val['id']]['url'];
			$gamedata[$val['id']]['mobileicon'] = $infodata[$val['id']]['mobileicon']; //
		}
		
		//添加游戏数据	
		foreach ($loginfo as $key=>$val) {
			if(empty($gamedata[$val['gameid']]['gamename'])){
				$loginfo[$key]['gameid'] = '';
			}else{
				$loginfo[$key]['gamename'] = $gamedata[$val['gameid']]['gamename'];
				$loginfo[$key]['url'] = $gamedata[$val['gameid']]['url'];
				$loginfo[$key]['mobileicon'] = $gamedata[$val['gameid']]['mobileicon'];
			}
		}

		return $loginfo;
    }

    //添加个人信息
    public function registerinfo(){
        $action = I('action');
        $username = session('user.sdkuser');
        $id = session('user.xsst_id');

		 if( $action && $action == 'userinfo'){		
			$sex = I('sex');
			$qq = I('qq');
		    $tel = I('tel');
			$email = I('email');
			$mobile = I('mobile');
		    //$nickname = I('nickname');
			$fax = I('fax');
			$address = I('address');
			$zipcode = I('zipcode');
			$birthday = I('birthday');
            $birthday = $this->checkBirthday($birthday);
			$birthday = mktime($birthday);
 		    $rs = userinfo($username,$sex,$qq,$tel,$email,$mobile,$fax,$birthday,$address,$zipcode);
 		    
			if($rs){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";				
				echo "<script>alert('修改成功')</script>";
				echo "<meta http-equiv=refresh content=0;URL=".U('Web/User/index',array('userinfo'=>'info')).">";
				exit();
			}else{
				echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
				echo "<script>alert('修改失败');history.go(-1);</script>";
				exit();
			}
		 } else if($action && $action == 'updatepwd'){
			
			$oldpwd = I('old_pwd','');
			$oldpassword = pw_auth_code($oldpwd);
			$newpwd = I('usrpwd','');
			
			$usrpwd = I('usrpwd');
			$usrpwdc = I('usrpwdc');
			if (0 != strcmp($usrpwd, $usrpwdc)){
				echo "<script>alert('新密码与确认密码不一致,修改密码失败');history.go(-1);</script>";
				exit();		
			}

			$rs = updatepwd($username,$newpwd,$oldpassword,$id);
			if($rs >= 0){
				if (C('UCENTER_ENABLED')){
					echo uc_user_synlogout();
					//$db -> sql_connect($dbhost,$dbuser,$dbpwd,$dbname,$dbport, false, false);
				}
				
                session('user.sdkuser',$username);
                session('user.xsst_id',$userid);
				echo "<script>alert('修改成功,请重新登录')</script>";
				echo "<meta http-equiv=refresh content=0;URL=".U('Web/Login/index').">";
				exit();
			}else{
				echo "<script>alert('原密码错误');history.go(-1);</script>";
				exit();
			} 
		 }else if($action && $action == 'secret'){

		    $email = I('email');
		    $mobile = I('mobile');
		    $wentione = I('wentione');
		    $wentitwo = I('wentitwo');
		    $wentithree = I('wentithree');
		    $answerone = I('answerone');
		    $answertwo = I('answertwo');
		    $answerthree = I('answerthree');
			$create_time = time();
			if(empty($email) || empty($mobile) || empty($wentione) || empty($wentitwo) || empty($wentithree) 
				|| empty($answerone) || empty($answertwo) || empty($answerthree)){
				echo "<script>alert('带*为必填信息，请填写完整在提交');history.go(-1);</script>";
				exit();
			}

		    $rs = updatembinfo($username,$email,$mobile);

				$mibao = findMibao($username);
				
				if(!empty($mibao)){
					$mb = updateMibao($username,$wentione,$wentitwo,$wentithree,$answerone,$answertwo,$answerthree);
				}else{
					$mb = addMibao($username,$wentione,$wentitwo,$wentithree,$answerone,$answertwo,$answerthree,$create_time);
				}
				if($mb){
					echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
					echo "<script>alert('密保设置成功')</script>";
					echo "<meta http-equiv=refresh content=0;URL=".U('Web/User/index',array('userinfo'=>'secret')).">";
					exit();
				}else{
					echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
					//echo "<script>alert('密保设置失败');history.go(-1);</script>";
				    echo "<script>alert('密保设置失败');</script>";
				    echo "<meta http-equiv=refresh content=0;URL=".U('Web/User/index',array('userinfo'=>'secret')).">";
					exit();
				}
		 }else if($action && $action == 'tzsecret'){
		    $wentione = I('wentione');
		    $wentitwo = I('wentitwo');
		    $wentithree = I('wentithree');
		    $answerone = I('answerone');
		    $answertwo = I('answertwo');
		    $answerthree = I('answerthree');
		    
			$rs = checkMibao($username,$wentione,$wentitwo,$wentithree,$answerone,$answertwo,$answerthree);		
			
			if($rs){
				$userdata = searchuser($username); //获取用户信息
				echo "<script>alert('密保正确，点击跳入修改界面');</script>";
				$this -> assign('userdata',$userdata);
		 		$this -> display('secret_pwd');
				exit();
			}else{
				echo "<script>alert('密保问题回答错误，请重新确认');history.go(-1);</script>";
				exit();
			}
		 }
    }
    
    //检测生日是否符合格式
    public function checkBirthday($birthday){
    	if(empty($birthday)){
			$birthday = date('Y-m-d',time());
		}else{
		    $patten = "/^\d{4}(\-|\/|\.)\d{1,2}(\-|\/|\.)\d{1,2}$/";
            if (!preg_match ( $patten, $birthday )) {
                echo "<script>alert('生日格式不正确');history.go(-1);</script>";
                exit();
            }
		}
		return $birthday;
    }

    
}
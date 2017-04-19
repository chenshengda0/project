<?php
/**
 * 
 * app请求接口
 *
 */
class AppAction extends CommonAction{
	
	/**
	 * 获取首页轮播图片
	 */
	public function index(){
		$imageurl = $this->website."/upload/image/";//图片路径
		$indexDao = M('appindex');
		$indexlist = $indexDao -> field("lunbotua,lunbotub,lunbotuc,lunbotud,onegameid,twgameid,thrgameid,fourgameid")->where(" isdelete='0' ") -> order('id DESC') -> select(); //查询游戏类型	

		$image1['u'] = $imageurl.$indexlist[0]['lunbotua'];
		$image1['g'] = $indexlist[0]['onegameid'];
		$image2['u'] = $imageurl.$indexlist[0]['lunbotub'];
		$image2['g'] = $indexlist[0]['twgameid'];
		$image3['u'] = $imageurl.$indexlist[0]['lunbotuc'];
		$image3['g'] = $indexlist[0]['thrgameid'];
		$image4['u'] = $imageurl.$indexlist[0]['lunbotud'];
		$image4['g'] = $indexlist[0]['fourgameid'];

		$image[0] = $image1;
		$image[1] = $image2;
		$image[2] = $image3;
		$image[3] = $image4;

		echo json_encode($image);
		exit;
	}
	/**
	* 获取积分总数
	**/
	
	public function jfcount(){
			
			$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
			$urldata = get_object_vars(json_decode($urldata));
			$username = isset($urldata['username']) ? $urldata['username'] : '';
			
			$jifens=M('jifen');
			
			$list=$jifens->field("jifen")->where("username='".$username."'")->limit(1)->findAll();
			
			$rs=$list[0]['jifen'];
			
			$arr=array();
			if($rs){
					$arr=array(
					'a'=> $rs
					
					);	
				}
			
			echo json_encode($arr);
			echo exit;
		}
		
	/**
	*
	*	积分记录
	**/
	public function jflog(){
			$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
			$urldata = get_object_vars(json_decode($urldata));
			$username = isset($urldata['username']) ? $urldata['username'] : '';
			
			$page=isset($urldata['page']) ? $urldata['page'] : '';
			
			$pagesize=10;
			
			$jifenall=M('jifenlog');
			$list=$jifenall->field("jifen as a,create_time as b,beizhu as c")->where("username='".$username."'")->findAll();
			
			$myrow=mysql_fetch_array($list);
			$numrows=$myrow[0];
			$pages=intval($numrows/$pagesize);
			$offset=$pagesize*($page-1);
			$jifenalls=M('jifenlog');
			$lists=$jifenalls->field("jifen as a,create_time as b,beizhu as c")->where("username='".$username."'")->limit($offset . ',' .  $pagesize)->findAll();
			
			//foreach($list as $key=>$val){
			//		$arr[$key]['a'] =$val['jifen'];
			//		$arr[$key]['b'] =$val['create_time'];
			//		$arr[$key]['c'] =$val['beizhu'];
			//	}
			
			echo json_encode($lists);
			echo exit;
		}
	/**
	*
	*	爱乐币充值记录
	**/
	public function lblog(){
			$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
			$urldata = get_object_vars(json_decode($urldata));
			$username = isset($urldata['username']) ? $urldata['username'] : 'qqqwww';
			
			$page=isset($urldata['page']) ? $urldata['page'] : '1';
				
			$pagesize=10;
			
			$jifenall=M('ttbpay');
			$list=$jifenall->field("ttb as a,paytypeid as b,create_time as c,status as d")->where("username='".$username."'")->findAll();
			
			$myrow=mysql_fetch_array($list);
			$numrows=$myrow[0];
			$pages=intval($numrows/$pagesize);
			$offset=$pagesize*($page-1);
			$jifenalls=M('ttbpay');
			$lists=$jifenalls->field("ttb as a,paytypeid as b,create_time as c,status as d")->where("username='".$username."'")->limit($offset . ',' .  $pagesize)->findAll();
			
			$type=M('paytype');
			
			$listtype=$type->field("id as a , payname as b") -> findAll();
			
			foreach ($lists as $key => $val){
					$arr[$key]['a'] = $val["a"];
					$arr[$key]['b'] = $val["b"];
					$arr[$key]['c'] = $val["c"];
					
					if( $val['d'] == 0){
						$arr[$key]['d'] = "等待支付";
					}
					if( $val['d'] == 1){
						$arr[$key]['d'] = "支付成功";
					}
					else{
						$arr[$key]['d'] = "支付失败";
					}
			}
			echo json_encode($listtype);
			echo exit;
		}
	/*
	**注册
	*/
	public function reg(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$checkreg = 0;
		$username = isset($urldata['username']) ? $urldata['username'] : '';
		$password = isset($urldata['password']) ? $urldata['password'] : '';
		$upassword = isset($urldata['upassword']) ? $urldata['upassword'] : '';
		if(!empty($username) && !empty($password)){
			//$password = md5($password);
			$password = $this->pw_auth_code($password);
			$userdata['username'] = $username;
			$userdata['password'] = $password;
			$userdata['reg_time'] = time();

			$model = M('members');
			$rs = $model->add($userdata);
			if($rs){
				$checkreg = 1;
				$jfdata['username'] = $username;
				$jfdata['jifen'] = 0;
				$jfdata['create_time'] = time();

				$jfmodel = M('jifen');
				$jflist = $jfmodel->add($jfdata);

			}
		}
		$arr = array(
			'a' => $checkreg
		);

		echo json_encode($arr);
		exit;
	}

	/*
	**登录
	*/
	public function login(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$username = isset($urldata['username']) ? $urldata['username'] : '';
		$password = isset($urldata['password']) ? $urldata['password'] : '';
		
		$password = $this->pw_auth_code($password);

		$model = M('members');
    	$userlist = $model->field("username as b")->where("username='".$username."' AND password='".$password."'")->findAll();
		$userlist = $userlist[0];
		
		$checkloging = 0;
		if(!empty($userlist['b'])){
			$checkloging = 1;
			$jfmodel = M('jifen');
			$jflist = $jfmodel->where("username='".$username."'")->select();

			$userlist['c'] = $jflist[0]['jifen'];
		}
		$arr = array(
			'a' => $checkloging,
			'b' => $userlist['b'],
			'c' => $userlist['c'],
		);
		
		echo json_encode($arr);
		exit;
	}

	/*
	**修改密码
	*/
	public function updatePwd(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));

		$username = isset($urldata['username']) ? $urldata['username'] : '';
		$oldpwd = isset($urldata['oldpwd']) ? $urldata['oldpwd'] : '';
		$newpwd = isset($urldata['newpwd']) ? $urldata['newpwd'] : '';
		
		//$oldpwd = md5($oldpwd);
		//$newpwd = md5($newpwd);
		$oldpwd = $this->pw_auth_code($oldpwd);
		$newpwd = $this->pw_auth_code($newpwd);
		$ckupdate = 0;

		$model = M('members');
    	$userlist = $model->field("username as b")->where("username='".$username."' AND password='".$oldpwd."'")->findAll();
		$userlist = $userlist[0];

		if(!empty($userlist['b'])){
			$userdata['password'] = $newpwd;
			$userdata['update_time'] = time();

			$rs = $model->data($userdata)->where("username='".$username."'")->save();
			if($rs){
				$ckupdate = 1;
			}
		}
		$arr = array(
			'a' => $ckupdate,
		);
		
		echo json_encode($arr);
		exit;
	}

	/**
	**
	**保存用户意见问题
	**
	*/
	public function setAsk(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$username = isset($urldata['username']) ? $urldata['username'] : '';//账号
		$title = isset($urldata['title']) ? $urldata['title'] : '';//标题
		$descrip = isset($urldata['descrip']) ? $urldata['descrip'] : '';//问题描述
		$type = isset($urldata['type']) ? $urldata['type'] : '';//手机型号
		$lianxi = isset($urldata['lianxi']) ? $urldata['lianxi'] : '';//联系方式
		
		$bool = 0;

		if(!empty($username)){
			$askdata['username'] = $username;
			$askdata['title'] = $title;
			$askdata['descrip'] = $descrip;
			$askdata['lianxi'] = $lianxi;
			$askdata['type'] = $type;
			$askdata['create_time'] = time();
			
			//保存问题意见
			$askmodel = M('appask');
			$add = $askmodel -> add($askdata);
			if($add){
				$bool=1;
			}
		}
		$arr = array(
			'a' => $bool
		);
		echo json_encode($arr);
		exit;
	}


	/**
	**
	**兑换记录
	**
	*/
	public function dhlog(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$username = isset($urldata['username']) ? $urldata['username'] : '';//账号
		
		$dhlog = M('dhlog');
		$field = "title as a,create_time as b,code as c";
		
		$loglist = $dhlog->field($field)->where("username = '".$username."' AND status='1' AND code IS NOT NULL ")->order("id DESC")->select();
		
		echo json_encode($loglist);
		exit;
	}

	/*
	*游戏类型
	*/
	public function typeList(){

		$typemodel = M('appgametype');
		$typelist = $typemodel -> order('id desc')->select();
		//查询列表
		$where = " isdelete='0'";
		$game = M('appgame');
		$field = "type,count(type) as count";
		$gamelist = $game->field($field)->group('type')->where($where)->select();
		
		foreach ($gamelist as $key => $val){
			$gamedata[$val['type']] = $val['count'];
		}
		
		$imageurl = $this->website."/upload/image/";//图片路径
		foreach ($typelist as $key => $val){
			
			if ($val['count']<=10000) {
				$total = $val['count'];
			}
			
			$arr[$key]['a'] = $val['id'];
			$arr[$key]['b'] = $val['name'];
			$count = $gamedata[$val['id']];
			if($count > 0){
				$arr[$key]['c'] = $gamedata[$val['id']];
			}else{
				$arr[$key]['c'] = 0;
			}
			$arr[$key]['d'] = $imageurl.$val['ticon'];
		}
		echo json_encode($arr);
		exit;
	}
	
	/**
	 * 游戏类型显示游戏
	 */
	public function tgameList(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$typeid = isset($urldata['typeid']) ? $urldata['typeid'] : '';
		$ltype = isset($urldata['ltype']) ? $urldata['ltype'] : 0;
		$page = isset($urldata['page']) ? $urldata['page'] : 1;
		$pagesize = 8;
		 
		$game = M('appgame');
		//查询游戏数据列表
		$where = " c_appgame.isdelete='0' AND c_appgame.type = ".$typeid;

		$order = "count DESC,id DESC";
		if ($ltype == 1) {
			$order = "count DESC,id DESC";
		}else if($ltype == 2){
			$order = "create_time DESC,id DESC";
		}

		$count = mysql_query("select count(*) as t from c_appgame where ".$where);
		$myrow = mysql_fetch_array($count);
		$numrows=$myrow[0];
		//计算总页数
		$pages=intval($numrows/$pagesize);

		//计算记录偏移量
		$offset=$pagesize*($page - 1);
		
		$field = "c_appgame.id as id,name,image,count,size,link,c_appgame.create_time,description";
		$join = "c_appgameinfo ON c_appgame.id=c_appgameinfo.id";
		
		$gamelist = $game->field($field)->join($join)->where($where)->order($order)->limit($offset . ',' .  $pagesize)->select();
		
		$baoming = M('baoming');
		$baolist = $baoming->field("id,gameid,name")->findAll();
		foreach ($baolist as $val) {
			$baodata[$val['gameid']] = $val['name'];
		}
		
		$imageurl = $this->website."/upload/image/";//图片路径
		
		foreach ($gamelist as $key => $val){
			
			if ($val['count']<=10000) {
				$total = $val['count'];
			} elseif ($val['count']>10000 && $val['count']<=99999) {
				$tmp = substr($val['count'],0,1);
				$total = $tmp."万"."+";
			} elseif ($val['count']>=100000 && $val['count']<=999999) {
				$tmp = substr($val['count'],0,2);
				$total = $tmp."万"."+";
			}elseif ($val['count']>=1000000 && $val['count']<=9999999) {
				$tmp = substr($val['count'],0,3);
				$total = $tmp."万"."+";
			}elseif ($val['count']>=10000000 && $val['count']<=99999999) {
				$tmp = substr($val['count'],0,4);
				$total = $tmp."万"."+";
			}
			
			$arr[$key]['a'] = $val['name'];
			$arr[$key]['b'] = $imageurl.$val['image'];
			$arr[$key]['c'] = $total;
			$arr[$key]['d'] = $val['size'];
			$arr[$key]['e'] = $val['link'];
			$arr[$key]['f'] = $val['id'];
			$arr[$key]['g'] = $baodata[$val['id']];
			$arr[$key]['h']	= strip_tags($val['description']);
		}
		
		echo json_encode($arr);
		exit;
	}

	
	/**
	 * 游戏列表
	 */
	public function gameList(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$lanmu = isset($urldata['lanmu']) ? $urldata['lanmu'] : 0;
		$page = isset($urldata['page']) ? $urldata['page'] : 1;
		$pagesize = 8;
		 
		$game = M('appgame');
		//查询游戏数据列表
		$where = " c_appgame.isdelete='0'";
		if ($lanmu != 0) {
			$where .= " and lanmu=".$lanmu;
		}

		$count = mysql_query("select count(*) as t from c_appgame where ".$where);
		$myrow = mysql_fetch_array($count);
		$numrows=$myrow[0];
		//计算总页数
		$pages=intval($numrows/$pagesize);

		//计算记录偏移量
		$offset=$pagesize*($page - 1);
		 
		$field = "c_appgame.id as id,name,image,count,size,link,description";
		$join = "c_appgameinfo ON c_appgame.id=c_appgameinfo.id";
		
		$gamelist = $game->field($field)->join($join)->where($where)->order('count DESC,id DESC')->limit($offset . ',' .  $pagesize)->select();
		
		$baoming = M('baoming');
		$baolist = $baoming->field("id,gameid,name")->findAll();
		foreach ($baolist as $val) {
			$baodata[$val['gameid']] = $val['name'];
		}
		
		$imageurl = $this->website."/upload/image/";//图片路径
		
		foreach ($gamelist as $key => $val){
			
			if ($val['count']<=10000) {
				$total = $val['count'];
			} elseif ($val['count']>10000 && $val['count']<=99999) {
				$tmp = substr($val['count'],0,1);
				$total = $tmp."万"."+";
			} elseif ($val['count']>=100000 && $val['count']<=999999) {
				$tmp = substr($val['count'],0,2);
				$total = $tmp."万"."+";
			}elseif ($val['count']>=1000000 && $val['count']<=9999999) {
				$tmp = substr($val['count'],0,3);
				$total = $tmp."万"."+";
			}elseif ($val['count']>=10000000 && $val['count']<=99999999) {
				$tmp = substr($val['count'],0,4);
				$total = $tmp."万"."+";
			}
			
			$arr[$key]['a'] = $val['name'];
			$arr[$key]['b'] = $imageurl.$val['image'];
			$arr[$key]['c'] = $total;
			$arr[$key]['d'] = $val['size'];
			$arr[$key]['e'] = $val['link'];
			$arr[$key]['f'] = $val['id'];
			$arr[$key]['g'] = $baodata[$val['id']];
			$arr[$key]['h'] = strip_tags($val['description']);
		}
		
		echo json_encode($arr);
		exit;
	}
	
	/**
	 * 游戏详细信息
	 */
	public function gameDetail(){
		//获取请求参数
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$id = isset($urldata['id']) ? $urldata['id'] : '';//获取游戏ID
		
		$game = M('appgame');
		
		$field = "c_appgame.id as id,name,image,count,type,smallimage,size,versions,link,description,function";
		$join = "c_appgameinfo ON c_appgame.id=c_appgameinfo.id";
		$where = " c_appgame.isdelete=0 AND c_appgame.id=".$id;
		
		$gamelist = $game->field($field)->join($join)->where($where)->findAll();
		
		$imageurl = $this->website."/upload/image/";//图片路径
		$imagelist = explode(",", $gamelist[0]['smallimage']);
		
		$baoming = M('baoming');
		$baolist = $baoming->field("id,gameid,name")->findAll();
		foreach ($baolist as $val) {
			$baodata[$val['gameid']] = $val['name'];
		}

		$typemodel = M('appgametype');
		$typelist = $typemodel->field("id,name")->findAll();
		foreach ($typelist as $val) {
			$typedata[$val['id']] = $val['name'];
		}
		
		$arr = array();
		foreach ($gamelist as $key => $val){
			if ($val['count']<=10000) {
				$total = $val['count'];
			} elseif ($val['count']>10000 && $val['count']<=99999) {
				$tmp = substr($val['count'],0,1);
				$total = $tmp."万"."+";
			} elseif ($val['count']>100000 && $val['count']<=999999) {
				$tmp = substr($val['count'],0,2);
				$total = $tmp."万"."+";
			}elseif ($val['count']>1000000 && $val['count']<=9999999) {
				$tmp = substr($val['count'],0,3);
				$total = $tmp."万"."+";
			}elseif ($val['count']>10000000 && $val['count']<=99999999) {
				$tmp = substr($val['count'],0,4);
				$total = $tmp."万"."+";
			}
			
			$arr['n'] = $val['name'];
			$arr['m'] = $imageurl.$val['image'];
			$arr['c'] = $total;
			$arr['s'] = $val['size'];
			$arr['v'] = $val['versions'];
			$arr['t'] = $typedata[$val['type']];
			$arr['jt1'] = $imageurl.$imagelist[0];
			$arr['jt2'] = $imageurl.$imagelist[1];
			$arr['jt3'] = $imageurl.$imagelist[2];
			$arr['jt4'] = $imageurl.$imagelist[3];
			$arr['d'] = strip_tags($val['description']);
			$f = $this->format_html($val['function']);
			//$f = str_replace('&ldquo;', '"',$f);
			//$f = str_replace('&rdquo;', '"',$f);
			//$f = str_replace('&quot;', '"',$f);
			//$f = str_replace('&quot;', '"',$f);
			$arr['f'] = strip_tags($f);
			$arr['a'] = $val['link'];
			$arr['bm'] = $baodata[$val['id']];
		}
		
		$data = json_encode($arr,JSON_HEX_QUOT);
		echo $data;
	}

	//转换某些特殊字符
	public function format_html($str){
		$str = str_replace('&quot;', '"',$str);
		$str = str_replace('&quot;', '"',$str);
		$str = str_replace('&ldquo;', '"',$str);
		$str = str_replace('&rdquo;', '"',$str);
		$str = str_replace('&middot;', '·',$str);
		$str = str_replace('&lsquo;', "'",$str);
		$str = str_replace('&rsquo;', "'",$str);
		$str = str_replace('&hellip;', '…', $str);
		$str = str_replace('&mdash;', '—', $str);
		$str = str_replace('&nbsp;', ' ', $str);
		return $str;
	}

	
	/**
	 * 开服列表
	 */
	public function serverList(){
		$imageurl = $this->website."/upload/image/";//图片路径
		$serDao = M('appserverinfo');
		//查询开服数据列表
		$serverlist = $serDao->field("gameid,sername,sertime")-> where(" isdelete='0' ") -> order('id DESC')->select();
		
		$game = M('appgame');
		$field = "c_appgame.id as id,name,image,link";
		$join = "c_appgameinfo ON c_appgame.id=c_appgameinfo.id";
		$where = " c_appgame.isdelete=0";
		$gamelist = $game->field($field)->join($join)->where($where)->findAll();
		foreach ($gamelist as $key => $val) {
			$gamedata[$val['id']] = $val;
		}
		
		$baoming = M('baoming');
		$baolist = $baoming->field("id,gameid,name")->findAll();
		foreach ($baolist as $val) {
			$baodata[$val['gameid']] = $val['name'];
		}
		
		$data = array();
		$time = time();
		$todaystart = date('Y-m-d',$time);
		$todaystart = strtotime($todaystart);
		$todayend   = date('Y-m-d',$time).'24:00:00';
		$todayend   = strtotime($todayend);

		foreach ($serverlist as $key=>$val) {			
			$hours = floor(($val['sertime'] - $time)/3600);
			if($val['sertime'] >= $todaystart && $val['sertime'] < $todayend){
				//今日开服
				$minute = floor(($val['sertime'] - $time - $hours * 3600)/60);
				
				$arr2['a'] = $gamedata[$val['gameid']]['name'];
				$arr2['b'] = $imageurl.$gamedata[$val['gameid']]['image'];
				$arr2['c'] = $val['sername'];
				$arr2['d'] = date('Y-m-d H:i:s',$val['sertime']);
				$arr2['e'] = $hours."小时".$minute."分";
				$arr2['f'] = $gamedata[$val['gameid']]['link'];
				$arr2['g'] = $baodata[$val['gameid']];
				if($hours<0){
					$arr2['e'] = 0;
				}
				$data2[] = $arr2;
			}				
			
			if ($hours>=0) {
				//即将开服
				$days = floor($hours/24);
				
				$arr1['a'] = $gamedata[$val['gameid']]['name'];
				$arr1['b'] = $imageurl.$gamedata[$val['gameid']]['image'];
				$arr1['c'] = $val['sername'];
				$arr1['d'] = date('Y-m-d H:i:s',$val['sertime']);
				$arr1['e'] = $days."天";
				$arr1['f'] = $gamedata[$val['gameid']]['link'];
				$arr1['g'] = $baodata[$val['gameid']];
				
				$data1[] = $arr1;
			}else if ($hours<0) {
				//已开新服
				$arr3['a'] = $gamedata[$val['gameid']]['name'];
				$arr3['b'] = $imageurl.$gamedata[$val['gameid']]['image'];
				$arr3['c'] = $val['sername'];
				$arr3['d'] = date('Y-m-d H:i:s',$val['sertime']);
				$arr3['f'] = $gamedata[$val['gameid']]['link'];
				$arr3['g'] = $baodata[$val['gameid']];
				$data3[] = $arr3;
			}
		}
		$data = array (
			'd1' => $data1,
			'd2' => $data2,
			'd3' => $data3
		);
		
		echo json_encode($data);
		exit;
	}
	
	/**
	 * 礼包列表
	 */
	public function giftList(){	
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$username=isset($urldata['username']) ? $urldata['username'] : '';
		$libaoinfo = M('applibaoinfo');
		$time= time();
		
		$field = "c_applibaoinfo.id as a,c_applibaoinfo.title as b,c_applibaoinfo.content as c,CONCAT('".$this->website."/upload/image/',c_appgame.image) AS d,c_applibaoinfo.starttime as e";
		$join = " c_appgame ON c_applibaoinfo.gameid=c_appgame.id";
		$giftlist = $libaoinfo->field($field)->join($join)->where("c_applibaoinfo.isdelete=0 and c_applibaoinfo.endtime>'".$time."'")->order('starttime ASC')->findAll();
		$arr=array();
		foreach ($giftlist as $key => $val){
			$libaos = M('applibao');
			$total = $libaos->where("status=0 and infoid=".$val['a'])->count();
			$qcode=$libaos->field("code")->where("username='".$username."' and infoid=".$val['a'])->limit(1)->findAll();
			
			if($qcode!=null){
				$arr[$key]['j'] =$qcode[0]['code'];
			}else{
				$arr[$key]['j'] ="0";
			}
			$arr[$key]['a'] = $val['a'];
			$arr[$key]['b'] = $val['b'];
			$arr[$key]['c'] = $val['c'];
			$arr[$key]['d'] = $val['d'];
			$arr[$key]['e'] = $val['e'];
			$arr[$key]['h'] = $total;
			
		}
		
		
		echo json_encode($arr);
		exit;
	}
	
	/**
	 * 
	 * 礼包详细页面
	 */
	public function giftDetail() {
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		$username=isset($urldata['username']) ? $urldata['username'] : '';
		$id = isset($urldata['infoid']) ? $urldata['infoid'] : '';//礼包 ID
		//$id = 1;
		$libaoinfo = M('applibaoinfo');
		$field = "c_applibaoinfo.id as a,c_applibaoinfo.gameid as b,c_applibaoinfo.title as c,c_applibaoinfo.content as d,CONCAT('".$this->website."/upload/image/',c_appgame.image) AS e,c_applibaoinfo.starttime as f,c_applibaoinfo.endtime as g";
		$join = " c_appgame ON c_applibaoinfo.gameid=c_appgame.id";
		$giftlist = $libaoinfo->field($field)->join($join)->where("c_applibaoinfo.id=".$id)->findAll();
		$giftlist = $giftlist[0];
		
		//剩余数量
		$libao = M('applibao');
		$total = $libao->where("status=0 and infoid=".$giftlist['a'])->count();
		$giftlist['h'] = $total;
		
		$qcode=$libao->field("code")->where("username='".$username."' and infoid=".$id)->limit(1)->findAll();
		
		if($qcode!=null){
			$giftlist['k'] =$qcode[0]['code'];
		}else{
			$giftlist['k'] ="0";
		}
		
		//获取包名
		$baoming = M('baoming');
		$bao = $baoming->where("gameid=".$giftlist['b'])->findAll();
		$giftlist['i'] = $bao[0]['name'];
		
		//获取游戏下载地址
		$model = M('appgame');
		$gameinfo = $model->field("link")->where("id=".$giftlist['b'])->findAll();
		$giftlist['j'] = $gameinfo[0]['link'];

		echo json_encode($giftlist);
		exit;
	}
	
	/**
	 * 
	 * 领取礼包
	 */
	public function setLibao() {
		//获取请求参数
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$infoid = isset($urldata['infoid']) ? $urldata['infoid'] : '';//礼包 ID
		//$username = isset($urldata['username']) ? $urldata['username'] : '';//用户账号

		//$usermodel = M('members');
    	//$userlist = $usermodel->field("id,username")->where("username='".$username."'")->findAll();
		
		$data['status'] = 1;
		$data['update_time'] = time();
		$data['update_time'] = time();
		//$data['uid'] = $userlist[0]['id'];
		
		$model = M('applibao');
		$list = $model->field("id,code")->where("infoid=".$infoid." and status=0")->limit(1)->findAll();
		$code = $list[0]['code'];
		$id = $list[0]['id'];
		
		$rs = $model->data($data)->where("id='".$id."'")->save();
		$arr = array();
		if ($rs) {
			$arr = array(
				'a' => $code
			);
		}
		echo json_encode($arr);
		exit;
	}


	/**
	 * 积分物品列表
	 */
	public function jfList(){	
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$page = isset($urldata['page']) ? $urldata['page'] : 1;
		$pagesize = 8;

		$count = mysql_query("select count(*) as t from c_jfwupininfo where isdelete=0");
		$myrow = mysql_fetch_array($count);
		$numrows=$myrow[0];
		//计算总页数
		$pages=intval($numrows/$pagesize);

		//计算记录偏移量
		$offset=$pagesize*($page - 1);

		$wupininfo = M('jfwupininfo');
		$field = "id as a,title as b,number as c,content as d,CONCAT('".$this->website."/upload/image/',ico) AS e,type as f,endtime as j,create_time as k";

		$wupinlist = $wupininfo->field($field)->where("isdelete=0")->order('id DESC')->
			limit($offset . ',' .  $pagesize)->select();
		
		$arr = array();
		foreach ($wupinlist as $key => $val){
			//剩余数量
			$wupin = M('jfwupin');
			if($val['f'] == '1'){
				$clist = $wupin->field("count,quantity")->where("jfinfoid=".$val['a'])->findAll();
				$total =$clist[0]['count'];
				$quantity =$clist[0]['quantity'];
			}else{
				$total = $wupin->where("infoid=".$val['a'])->count();
				$quantity = $wupin->where("status=0 and infoid=".$val['a'])->count();
			}
			
			$arr[$key]['a'] = $val['a'];
			$arr[$key]['b'] = $val['b'];
			$arr[$key]['c'] = $val['c'];
			$arr[$key]['d'] = $val['d'];
			$arr[$key]['e'] = $val['e'];
			$arr[$key]['f'] = $val['f'];
			$arr[$key]['h'] = $total;
			$arr[$key]['i'] = $quantity;
			$arr[$key]['j'] = $val['j'];
			$arr[$key]['k'] = $val['k'];
		}
		
		echo json_encode($arr);
		exit;
	}
	
	/**
	 * 
	 * 积分礼包详细页面
	 */
	public function jfDetail() {
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		
		$urldata = get_object_vars(json_decode($urldata));
		
		$id = isset($urldata['infoid']) ? $urldata['infoid'] : '';//礼包 ID
		$wupininfo = M('jfwupininfo');
		$field = "id as a,title as b,number as c,content as d,CONCAT('".$this->website."/upload/image/',ico) AS e,
				type as f,endtime as j,create_time as k";
		
		$wupinlist = $wupininfo->field($field)->where("id=".$id)->findAll();
		$wupinlist = $wupinlist[0];
		
		//剩余数量
		$wupin = M('jfwupin');
		if($wupinlist['f'] == '1'){
			$clist = $wupin->field("count")->where("count>0 and jfinfoid=".$wupinlist['a'])->findAll();
			$total =$clist[0]['count'];
		}else{
			$total = $wupin->where("status=0 and jfinfoid=".$wupinlist['a'])->count();
		}
		
		$wupinlist['h'] = $total;
		
		echo json_encode($wupinlist);
		exit;
	}

	/**
	 * 领取积分物品
	 */
	public function setjfLibao() {
		//获取请求参数
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		//import('@.Action.Encrypt');
		//$encrypt = new Encrypt();
		//$urldata =$encrypt -> decode(json_decode($urldata));
		$urldata = get_object_vars(json_decode($urldata));
		
		$infoid = isset($urldata['infoid']) ? $urldata['infoid'] : '';//礼包 ID
		$username = isset($urldata['username']) ? $urldata['username'] : '';//账号
		$time = isset($urldata['time']) ? $urldata['time'] : '';//时间戳
		$appkey = isset($urldata['appkey']) ? $urldata['appkey'] : '';//key
		$sign = isset($urldata['sign']) ? $urldata['sign'] : '';//签名

		$str .= $username.$time.'c_app';
		$str = md5($str);
	
		$bool = 4;
		if($str == $sign && !empty($str)){
			$usermodel = M('members');
			$jfmodel = M('jifen');
			$jifenlist = $jfmodel->field("jifen")->where("username='".$username."'")->findAll();//积分数


			$field = "c_jifen.jifen as jifen,c_members.username as username";
			$join = " c_jifen ON c_members.username=c_jifen.username";
			$userlist = $usermodel->field($field)->join($join)->where("c_members.username='".$username."'")->findAll();
			
			$wupininfo = M('jfwupininfo');
			$infolist = $wupininfo->field("id,title,number,endtime")->where("id=".$infoid." and isdelete='0'")->findAll();
			$jifen = $infolist[0]['number'];//积分
			
			if($jifen > $userlist[0]['jifen']){
				$bool = 1;
			}else{
				if(time() > $infolist[0]['endtime']){
					$bool = 3;
				}else{
					//返回兑换码
					$model = M('jfwupin');
					$list = $model->field("id,code")->where("jfinfoid=".$infoid." and status=0")->limit(1)->findAll();
					if(count($list) > 0){
						$code = $list[0]['code'];
						$id = $list[0]['id'];
								
						$data['status'] = 1;
						$rs = $model->data($data)->where("id='".$id."'")->save();
					}else{
						$bool = 2;
					}
				}
				
			}
			$arr = array();
			if ($rs) {
				$bool = 0;
				//扣除消耗的积分
				$jbdata['jifen'] = $userlist[0]['jifen'] - $jifen;
				$jbdata['update_time'] = time();
				$jbmodel = M('jifen');
				$rs = $jbmodel -> where("username='".$userlist[0]['username']."' ")->data($jbdata)->save();

				$jbdata['title'] = $infolist[0]['title'];
				$jbdata['jfinfoid'] = $infolist[0]['id'];
				$jbdata['username'] =  $userlist[0]['username'];
				$jbdata['jifen'] = $jifen;
				$jbdata['code'] = $code;
				$jbdata['create_time'] = time();
				$jbdata['status'] = '1';

				$dhmodel = M('dhlog');
				$rs = $dhmodel -> add($jbdata);
			}
		}

		
		$arr = array(
				'a' => $bool,
				'b' => $code
		);
		//$str = $encrypt -> encode(json_encode($arr));
		echo json_encode($arr);
		exit;
	}

	/**
	 * 提交地址
	 */
	public function setAddress() {
		//获取请求参数
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$infoid = isset($urldata['infoid']) ? $urldata['infoid'] : '';//礼包 ID
		$username = isset($urldata['username']) ? $urldata['username'] : '';//账号
		$time = isset($urldata['time']) ? $urldata['time'] : '';//时间戳
		$appkey = isset($urldata['appkey']) ? $urldata['appkey'] : '';//key
		$sign = isset($urldata['sign']) ? $urldata['sign'] : '';//签名

		$str .= $username.$time.'c_app';
		$str = md5($str);
		
		$bool = 4; 
		if($str == $sign && !empty($str)){
			$usermodel = M('members');
			$field = "c_jifen.jifen as jifen,c_members.username as username";
			$join = " c_jifen ON c_members.username=c_jifen.username";
			$userlist = $usermodel->field($field)->join($join)->where("c_members.username='".$username."'")->findAll();
			
			$wupininfo = M('jfwupininfo');
			$infolist = $wupininfo->field("id,title,number,endtime")->where("id=".$infoid." and isdelete='0'")->findAll();
			$jifen = $infolist[0]['number'];//积分
			
			//$bool = 1; 
			if($jifen > $userlist[0]['jifen']){
				$bool = 1;
			}else{
				if(time() > $infolist[0]['endtime']){
					$bool = 3;
				}else{
					//提交地址
					$model = M('jfwupin');
					$list = $model->field("id,quantity")->where("jfinfoid=".$infoid)->limit(1)->findAll();
					$quantity = $list[0]['quantity'];
					$id = $list[0]['id'];
							
					$data['quantity'] = $quantity - 1;
					if($data['quantity'] > 0){
						$rs = $model -> where("id='".$id."'")->data($data)->save();
					
						if($rs){
							$address = M('address');
							
							$adata['username'] = $userlist[0]['username'];
							$adata['address'] = isset($urldata['address']) ? $urldata['address'] : '';//地址
							$adata['contact'] = isset($urldata['contact']) ? $urldata['contact'] : '';//联系方式
							$adata['jfinfoid'] = $infoid;//联系方式
							$adata['create_time'] = time();//联系方式
						
							$rs = $address -> add($adata);
						}
					}else{
						$bool = 2;
					}
				}
				
			}
			$arr = array();

			if ($rs) {
				$bool = 0;
				//扣除消耗的比特币
				$jbdata['jifen'] = $userlist[0]['jifen'] - $jifen;
				$jbdata['update_time'] = time();
				$jbmodel = M('jifen');
				$rs = $jbmodel -> where("username='".$userlist[0]['username']."' ")->data($jbdata)->save();

				//兑换记录
				$jbdata['infoid'] = $infolist[0]['id'];
				$jbdata['title'] = $infolist[0]['title'];
				$jbdata['username'] =  $userlist[0]['username'];
				$jbdata['jifen'] = $infolist[0]['number'];
				$jbdata['create_time'] = time();
				$jbdata['status'] = '1';

				$dhmodel = M('dhlog');
				$rs = $dhmodel -> add($jbdata);
			}
		}
		$arr = array(
				'a' => $bool,
		);
		//$str = $encrypt -> encode(json_encode($arr));
		echo json_encode($arr);
		exit;
	}
	
	/**
	 * 
	 * 我的游戏列表
	 */
	public function getMyGameList() {
		$join = "c_appgameinfo ON c_appgameinfo.id=c_baoming.gameid";
		$field = "gameid as a,name as b,size as c";
		$baoming = M('baoming');
		$baolist = $baoming->field($field)->join($join)->findAll();
		
		$time = time();
		$serverinfo = M('appserverinfo');
		$libaoinfo = M('applibaoinfo');
		
		$iflibao = 0;
		$ifserver = 0;
		
		foreach ($baolist as $key=>$val) {

			$libaodata = $libaoinfo->field("id,endtime")->where("gameid=".$val['a']." and isdelete=0")->order("create_time DESC")->limit(1)->findAll();
			$baolist[$key]['d'] = $libaodata[0]['id'];
			
			if ($libaodata[0]['endtime'] > $time) {
				$iflibao = 1;	
			}
			
			$baolist[$key]['e'] = $iflibao;
			
			$serverdata = $serverinfo->field("id,sertime")->where("gameid=".$val['a']." and isdelete=0")->order("create_time DESC")->limit(1)->findAll();
			
			if ($serverdata[0]['sertime'] > $time) {
				$ifserver = 1;	
			}
			$baolist[$key]['f'] = $ifserver;
		}
		
		echo json_encode($baolist);
		exit;
	}
	
	/**
	 * 
	 * 获取游戏更新信息
	 */
	public function getUpdateInfo() {	
		//获取请求参数
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = json_decode($urldata);
		$imageurl = $this->website."/upload/image/"; //图片路径

		$bao = M('baoming');
		$game = M('appgame');
		$gameinfo = M('appgameinfo');
		$join = "c_appgameinfo on c_appgame.id=c_appgameinfo.id";
		$field = "name,image,versions,size,link";
		$arr = array();
		$data = array();
		foreach ($urldata as $key=>$val) {
			$val = get_object_vars($val);
			$baodata = $bao->field("gameid")->where("name='".$val['b']."'")->findAll();
			
			if ($baodata[0]['gameid']) {
				$gamedata = $game->field($field)->join($join)->where('c_appgame.id='.$baodata[0]['gameid'])->findAll();
	
				if ($gamedata[0]['versions'] != $val['c']) {
					$arr['a'] = $val['b'];
					$arr['b'] = $gamedata[0]['versions'];
					$arr['c'] = $gamedata[0]['link'];
					$arr['d'] = $gamedata[0]['size'];
					$arr['e'] = $gamedata[0]['name'];
					$arr['f'] = $imageurl.$gamedata[0]['image'];
					
					$data[] = $arr;
				}	
			}
		}

		echo json_encode($data);
		exit;
	}
	
	/**
	 * 
	 * 设置下载次数
	 */
	public function setCount() {
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$name = isset($urldata['baoming']) ? $urldata['baoming'] : '';
		
		$model = M('baoming');
		$baoming = $model->field("gameid")->where("name='".$name."'")->findAll();
		
		$model = M('appgame');
		$sql = "update c_appgame set `count`=`count`+1 where id=".$baoming[0]['gameid'];
		$rs = $model->query($sql);
	}
	
	/**
	 * 
	 * 获取版本号已经返回相应的下载地址
	 */
	public function getVersions() {
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$versions = isset($urldata['versions']) ? $urldata['versions'] : '';
		
		$model = M('appversion');
		$appdata = $model->field('versions,url,size,content')->where("versions>".$versions)->limit(1)->findAll();
		$arr = array();
		if ($appdata) {
			$old = $model->field('size')->where("versions=".$versions)->findAll();
			
			$arr['a'] = 1;
			$arr['b'] = $appdata[0]['url'];
			$arr['c'] = $appdata[0]['versions'];
			$arr['d'] = $appdata[0]['size'];
			$arr['e'] = $old[0]['size'];
			$arr['f'] = $appdata[0]['content'];
		} else {
			$arr['a'] = 2;
			$arr['b'] = "";
			$arr['c'] = "";
			$arr['d'] = "";
			$arr['e'] = "";
			$arr['f'] = "";
		}
		echo json_encode($arr);
		exit;
	}
	
	public function get_client_ip() {
	    if(getenv('HTTP_CLIENT_IP')){
	        $client_ip = getenv('HTTP_CLIENT_IP');
	    } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
	        $client_ip = getenv('HTTP_X_FORWARDED_FOR');
	    } elseif(getenv('REMOTE_ADDR')) {
	        $client_ip = getenv('REMOTE_ADDR');
	    } else {
	        $client_ip = $_SERVER['REMOTE_ADDR'];
	    }
	    echo  $client_ip;
	}

	/**
	 * 游戏关键字搜索
	 */
	public function getKeyGame(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$name = isset($urldata['name']) ? $urldata['name'] : '';
		 
		$game = M('appgame');
		//查询游戏数据列表
		$where = " c_appgame.isdelete='0'";
		if (!empty($name)) {
			$where .= " AND (name like '%".$name."%'";
			$where .= " OR pinyin like '".$name."%'";
			$where .= " OR initial like '%".$name."%')";
		}
		
		$field = "name";
		
		$gamelist = $game->field($field)->where($where)->order('CONVERT(NAME USING gbk)')->findAll();
		
		foreach ($gamelist as $key => $val){
			$arr[$key]['a'] = $val['name'];
		}
		
		echo json_encode($arr);
		exit;
	}
	/**
	  * 查询用户是否领取过该礼包 querygiftBYname
	**/
	public function  querygiftBYname(){
			$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
			$urldata = get_object_vars(json_decode($urldata));
			$infoid = isset($urldata['infoid']) ? $urldata['infoid'] : '';//礼包 ID
			$username=isset($urldata['username']) ? $urldata['username'] : '';
			$libao=M('applibao');
			
			$list = $libao->field("username as a ,code as b")->where("infoid = '".$infoid."'")->findAll();
			foreach($list as $key=>$val){
				if($val['a']==$username){
					$msg="1";
					$arr = array(
						'a' => $msg,
						'b' => $val['b']
					);
					echo json_encode($arr);
					exit;
				}
			}
			$data['status'] = 1;
			
			$data['update_time'] = time();
			$data['username']=$username;
			
			//$data['uid'] = $userlist[0]['id'];
			
			//$model = M('applibao');
			
			
			$list = $libao->field("id,code")->where("infoid=".$infoid." and status=0")->limit(1)->findAll();
			
			$code = $list[0]['code'];
			$id = $list[0]['id'];
			
			
			
			$rs = $libao->data($data)->where("id='".$id."'")->save();
			
			
			
			$arr = array();
			if ($rs) {
				$msg="0";
				$arr = array(
					'a' => $msg,
					'b' => $code
				);
			}			
			echo json_encode($arr);
			exit;
		}
		/**
	*  淘取礼包
	**/
	public function amoyGift(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$infoid = isset($urldata['infoid']) ? $urldata['infoid'] : '';//礼包 ID
		
		$model = M('applibao');
		$datatime = time();
		$list = $model->field("code as a,update_time as b")->where("infoid='".$infoid."'")->findAll();
		$arr = array();
		foreach($list as $key=>$val){
				$timediff = $datatime-$val['b'];
				$days = intval($timediff/86400);
				if($days>='1'){
					$arr[$key]= $val['a'];
				}
				
			}
			$label_num = count($arr) >3 ? 3:count($arr);
            $tempArr = array();
            $labelArr = array();

            if ( $label_num ){
                $tempArr = array_rand($arr,$label_num);//随机取出二维数组的键
                
                foreach ( $tempArr as $value ){
                    $labelArr['a'] = $arr[$value];
                }
            }
		echo json_encode($labelArr);
		exit;
	}
	/**
	 * 游戏名搜索
	 */
	public function getFindGame(){
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$name = isset($urldata['name']) ? $urldata['name'] : '';
		 
		$game = M('appgame');
		//查询游戏数据列表
		$where = " c_appgame.isdelete='0'";
		if (!empty($name)) {
			$where .= " AND name = '".$name."'";
		}
		
		$field = "c_appgame.id as id,name,image,count,size,link";
		$join = "c_appgameinfo ON c_appgame.id=c_appgameinfo.id";
		
		$gamelist = $game->field($field)->join($join)->where($where)->order('CONVERT(name USING gbk)')->findAll();
		
		$baoming = M('baoming');
		$baolist = $baoming->field("id,gameid,name")->findAll();
		foreach ($baolist as $val) {
			$baodata[$val['gameid']] = $val['name'];
		}
		
		$imageurl = $this->website."/upload/image/";//图片路径
		
		foreach ($gamelist as $key => $val){
			
			if ($val['count']<=10000) {
				$total = $val['count'];
			} elseif ($val['count']>10000 && $val['count']<=99999) {
				$tmp = substr($val['count'],0,1);
				$total = $tmp."万"."+";
			} elseif ($val['count']>=100000 && $val['count']<=999999) {
				$tmp = substr($val['count'],0,2);
				$total = $tmp."万"."+";
			}elseif ($val['count']>=1000000 && $val['count']<=9999999) {
				$tmp = substr($val['count'],0,3);
				$total = $tmp."万"."+";
			}elseif ($val['count']>=10000000 && $val['count']<=99999999) {
				$tmp = substr($val['count'],0,4);
				$total = $tmp."万"."+";
			}
			
			$arr[$key]['a'] = $val['name'];
			$arr[$key]['b'] = $imageurl.$val['image'];
			$arr[$key]['c'] = $total;
			$arr[$key]['d'] = $val['size'];
			$arr[$key]['e'] = $val['link'];
			$arr[$key]['f'] = $val['id'];
			$arr[$key]['g'] = $baodata[$val['id']];
		}
		
		echo json_encode($arr);
		exit;
	}


	/**
	 * 任务列表
	 */
	public function rwList(){	
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		$urldata = get_object_vars(json_decode($urldata));
		
		$page = isset($urldata['page']) ? $urldata['page'] : 1;
		$pagesize = 8;

		$count = mysql_query("select count(*) as t from c_apptask where isdelete=0");
		$myrow = mysql_fetch_array($count);
		$numrows=$myrow[0];
		//计算总页数
		$pages=intval($numrows/$pagesize);

		//计算记录偏移量
		$offset=$pagesize*($page - 1);

		$taskmode = M('apptask');
		
		$field = "c_apptask.id as a,c_apptask.title as b,c_apptask.content as c,CONCAT($this->website,'/upload/image/',c_appgame.image) AS d,c_apptask.jifen as e,c_apptask.gameid as f,c_apptask.endtime as h,c_apptask.starttime as i";
		$join = " c_appgame ON c_apptask.gameid=c_appgame.id";

		$tasklist = $taskmode->field($field)->join($join)->where("c_apptask.isdelete=0")->order('c_apptask.id DESC')->limit($offset . ',' .  $pagesize)->select();
		
		$arr = array();
		foreach ($tasklist as $key => $val){
			//剩余数量
			//$wupin = M('jfwupin');
			//if($val['f'] == '1'){
			//	$clist = $wupin->field("count,quantity")->where("jfinfoid=".$val['a'])->findAll();
			//	$total =$clist[0]['count'];
			//	$quantity =$clist[0]['quantity'];
			//}else{
			//	$total = $wupin->where("infoid=".$val['a'])->count();
			//	$quantity = $wupin->where("status=0 and infoid=".$val['a'])->count();
			//}
			
			$arr[$key]['a'] = $val['a'];
			$arr[$key]['b'] = $val['b'];
			$arr[$key]['c'] = $val['c'];
			$arr[$key]['d'] = $val['d'];
			$arr[$key]['e'] = $val['e'];
			$arr[$key]['f'] = $val['f'];
			//$arr[$key]['h'] = $total;
			//$arr[$key]['i'] = $quantity;
			$arr[$key]['h'] = $val['h'];
			$arr[$key]['i'] = $val['i'];
		}
		
		echo json_encode($arr);
		exit;
	}


	/**
	 * 参加任务活动
	 */
	public function setTask() {
		//获取请求参数
		$urldata = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
		
		$urldata = get_object_vars(json_decode($urldata));
		
		$taskid = isset($urldata['taskid']) ? $urldata['taskid'] : '';//礼包 ID
		$username = isset($urldata['username']) ? $urldata['username'] : '';//账号
		$time = isset($urldata['time']) ? $urldata['time'] : '';//时间戳
		$appkey = isset($urldata['appkey']) ? $urldata['appkey'] : '';//key
		$sign = isset($urldata['sign']) ? $urldata['sign'] : '';//签名

		$str .= $username.$time.'c_app';
		$str = md5($str);
	
		$bool = 4;
		if($str == $sign && !empty($str)){
			$usermodel = M('members');
			$jfmodel = M('jifen');
			$jifenlist = $jfmodel->field("jifen")->where("username='".$username."'")->findAll();//积分数


			$field = "c_jifen.jifen as jifen,c_members.username as username";
			$join = " c_jifen ON c_members.username=c_jifen.username";
			$userlist = $usermodel->field($field)->join($join)->where("c_members.username='".$username."'")->findAll();
			
			$task = M('apptask');
			$infolist = $task->field("id,title,jifen,endtime")->where("id=".$taskid." and isdelete='0'")->findAll();
			$jifen = $infolist[0]['jifen'];//积分
			
			$arr = array();
			if(time() > $infolist[0]['endtime']){
				$bool = 3;
			}else{
				$bool = 0;
				//增加积分
				$jbdata['jifen'] = $userlist[0]['jifen'] + $jifen;
				$jbdata['update_time'] = time();
				$jbmodel = M('jifen');
				$rs = $jbmodel -> where("username='".$userlist[0]['username']."' ")->data($jbdata)->save();

				//$jbdata['title'] = $infolist[0]['title'];
				//$jbdata['jfinfoid'] = $infolist[0]['id'];
				//$jbdata['username'] =  $userlist[0]['username'];
				//$jbdata['jifen'] = $jifen;
				//$jbdata['code'] = $code;
				//$jbdata['create_time'] = time();
				//$jbdata['status'] = '1';

				//$dhmodel = M('dhlog');
				//$rs = $dhmodel -> add($jbdata);

				$jflogdata['username']= $username;
				$jflogdata['jifen']=$jifen;
				$jflogdata['create_time']=time();
				$jflogdata['beizhu']="任务";
				$jflogdata['status']='1';
				$jflogs=M('jifenlog');
				$jfs=$jflogs->add($jflogdata);
			}
		}

		
		$arr = array(
				'a' => $bool
		);
		//$str = $encrypt -> encode(json_encode($arr));
		echo json_encode($arr);
		exit;
	}
}
	
?>
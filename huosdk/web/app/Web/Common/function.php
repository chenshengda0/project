<?php

//require_once("./action.php");
/* if(!defined(C('IN_SYS'))) {
	exit('Access Denied');
} */

/**
 * 注册
 *
 * @param string $username 用户名
 * @param string $password 密码
 * @param string $email 邮箱
 * @param string $reg_time 时间
 * @param string $ip IP
 * @param int	 $source 来源
 * @return boolean
 */


function register($username,$password,$email,$reg_time,$ip,$device,$agentgame,$nickname) {
	
 	//将注册信息写到members表中
	$members = M('members');
	$data['username'] = $username;
	$data['password'] = pw_auth_code($password);
	$data['pay_pwd'] = 	$data['password'];
	$data['email'] = $email;
	$data['reg_time'] = time();
	$data['ip'] = $ip;
	$data['from'] = $device;
	$data['agentgame'] = $agentgame;
	$data['nickname'] = $nickname;
	$data['fromflag'] = 4;
	$rs = $members->add($data);
	
	if($rs){
		$id = 1;
	}else{
		$id = 0;
	}

	if (C("UCENTER_ENABLED") && $rs){
		$id = uc_user_register($username, $password, $email);
	}

	return $id;
}

/* 玩家密码加密函数 */
function pw_auth_code($pw,$authcode=''){
    if(empty($authcode)){
        $authcode=C("AUTHCODE");
    }
    $result=md5(md5($authcode.$pw).$pw);
    return $result;
}

/**
 * 注册
 *
 * @param string $username 用户名
 * @param string $password 密码
 * @param string $email 邮箱
 * @param string $reg_time 时间
 * @param int	 $source 来源
 * @return boolean
 */

function ucmember_to_sdk($username, $password, $email, $source) {
    
    $rs = checksdkusername($username);
    if ($rs > 0){
        return true;
    }
    //将bbs中的账户注册到sdk中
    $members = M('members');
    $data['username'] = $username;
    $data['password'] = pw_auth_code($password);
    $data['email'] = $email;
    $data['reg_time'] = time();
    //$data['ip'] = GetIP(0);
    //$data['agent'] = $source;
    $data['nickname'] = $username;
    $data['fromflag'] = 4; 
    $rs = $members->add($data);  
	return $rs;
}

/**
 * 检测用户名是否已经存在
 *
 * @param string $username 用户名
 * @return id
 */

function checksdkusername($username) {
    $members = M('members');
    $data['username'] = $username;

    $id = $members->where($data)->getField('id');
    
    return $id;
}

/**
 * 检测用户名是否已经存在
 *
 * @param string $username 用户名
 * @return id
 */

function checkusername($username) {

    $user_model = M('members');
    $data['username'] = $username;
    $id = $user_model->field('id')->where($data)->getfield('id');
   
	//判断是否在ucenter存在账号
	if (C("UCENTER_ENABLED") && empty($id)){
		$ucresult = uc_user_checkname($username);
		
		if ($ucresult == 1){
			$id = 0;
		}elseif($ucresult == -3){
			$id = 1;
		}else{
			$id = $ucresult;
		}	
	}

	return $id;
}


/**
 * 检测邮箱是否已经存在
 *
 * @param string $email 邮箱
 * @return id
 */
function checkemail($email) {
	if (C("UCENTER_ENABLED")){
		$id = uc_user_checkemail($email);
		if (0 >= $id){
			return $id;
		}		
	}
	
	$dbh = new PDO('mysql:host='.C('DB_HOST').';dbname='.C('DB_NAME'), C('DB_USER'), C('DB_PWD'));
	$sql = "SELECT id FROM c_members WHERE email='{$email}'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$id = 0;
	if ($rs) {
		foreach($rs as $row) {
			$id = $row['id'];
		}
	}
	return $id;
}

/**
 * 检测昵称是否已经存在
 *
 * @param string $nickname 邮箱
 * @return id
 */
function checknick($nickname) {
/*	mysql_select_db("db_sdk_mn");
	$sql = "SELECT id FROM c_members WHERE nickname='{$nickname}'";
	$rs = mysql_query($sql);
	$id = 0;
	if ($rs) {
		while ($row = mysql_fetch_assoc($rs)) {
			$id = $row['id'];
		}
	}
	
	return $id;*/

	$dbh = new PDO('mysql:host='.C('DB_HOST').';dbname='.C('DB_NAME'), C('DB_USER'), C('DB_PWD'));
	$sql = "SELECT id FROM c_members WHERE nickname='{$nickname}'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$id = 0;

	if ($rs) {
		foreach($rs as $row) {
			$id = $row['id'];
		}
	}
	return $id;
}

//检查用户的密码是否正确
function checknamepwd($username,$password){
	$map['username'] = $username;
	$map['password'] = $password;
	$id = M('members')->where($map)->getField('id');
	return $id;
}
/**
 * 登录
 *
 * @param string $username 用户名
 * @param string $password 密码
 * @return id
 */
function login($username,$password) {
    $usernamel = $username;
    if(C("UCENTER_ENABLED")){
        list($uid, $username, $password, $email) = uc_user_login($username, $password);
        
        if($uid > 0){
            echo uc_user_synlogin($uid);
        }

        $rsid = checksdkusername($username);

        if ($uid > 0){
            $source = 'ucenter';
            $rs = ucmember_to_sdk($username, $password, $email, $source);
            
            if ($rs){
                $members = M('members');
                $data['username'] = $username;
                $id = $members->where($data)->getField('id');
            }
        }
    }  

	$pwd = pw_auth_code($password);

    if(!isset($members)){
        $members = M('members');
    }
	$user['status'] = 2;
    $user['username'] = $usernamel;
    $user['password'] = $pwd;
	$id = $members->where($user)->getField('id');
	//$id = $members -> field('id')->where($user)->find();
	
	return $id;
}


/**
 * 插入登录信息
 * @param $userid userid
 * @param $agent  渠道
 * @param $time	    时间
 */
function insertLogininfo($userid,$agentgame,$time,$from,$regtime="") {
	if (empty($regtime)) {
		$regtime = $time;
	}
    $loginlog = M('loginLog');
    $data['userid'] = $userid;
    $data['agentgame'] = $agentgame;
    $data['login_time'] = $time;
    $data['from'] = $from;
    $data['reg_time'] = $regtime;
    $rs = $loginlog->add($data);
    return $rs;
}

/**
 * 查询登录信息
 * @param $userid userid
 */
function findLogininfo($userid) {
	$loginlog = M('loginLog');
	$data['mem_id'] = $userid;
	//$res = $loginLog->field('DISTINCT,app_id')->where($data)->where('gameid != 6')->order('id DESC')->limit(10)->select();
	$res = $loginlog->field('DISTINCT app_id')->where($data)->order('id DESC')->limit(10)->select();
	return $res;
}

/**
*查询用户信息
*@param string $username
*@return date
*/
function searchuser($username){
    $members = M('members');
    $sql = "SELECT m.id,m.username,m.password,m.email,m.mobile,m.nickname,m.agentgame,m.from,m.reg_time
    FROM c_members m WHERE m.username='{$username}'";
    $data = $members->query($sql);
    $data = $data[0];    
    foreach($data as $key =>$val){
        $res[$key] = $val;
    }
    return $res;
}

/**
*查询用户信息
*@param string $username
*@return date
*/
function findttb($userid){
	$data = M("PtbMem")->where("mem_id = '%d'",$userid)->getField("remain");
	return $data;
}

/**
*修改用户信息
*@param string $username
*@param int $sex
*@param string qq
*@param string tel
*@return rs
*/
function userinfo($username,$sex,$qq,$tel,$email,$mobile,$fax,$birthday,$address,$zipcode){
	$mem_id = M('members')->where(array('username'=>$username))->getField('id');
		$data['mem_id'] = $mem_id;
		$data['sex'] = $sex;
		$data['qq'] = $qq;
		$data['tel'] = $tel;
		$data['fax'] = $fax;
		$data['address'] = $address;
		$data['zipcode'] = $zipcode;
		$data['create_time'] = time();	
		$mdata['email'] = $email;
		$mdata['mobile'] = $mobile;
		M('members')->where(array('username'=>$username))->save($mdata);
	    $result = M('memInfo')->where(array('mem_id'=>$mem_id))->find();
	//判断是否存在用户的信息，如果存在则进行修改，不存在则添加
	if($result){
	    $rs = M('memInfo')->data($data)->save();		
	}else{
		$rs = M('memInfo')->data($data)->add();		
	}

    return $rs; 
}


/**
*修改用户邮箱
*@param string $email
*@param string $username
*@return rs
*/
function useremail($username,$email){
	mysql_select_db("db_sdk_mn");
	$sql = "UPDATE c_members SET email='{$email}' WHERE username='{$username}'";
	$rs = mysql_query($sql); 
	
	return $rs;
}

/**
*修改用户密码
*@param string $username
*@param string $newpwd
*@param string $oldpassword
*@param int    $id
*@return rs
*/
function updatepwd($username,$newpwd,$oldpassword){
	$id = M('members')->where(array('username'=>$username))->getField('id');
	$newpassword = pw_auth_code($newpwd);
	$members = M('members');
	$user['id'] = $id;
 	$user['password'] = $oldpassword;
	$user['username'] = $username; 
	
    $data['password'] = $newpassword;
    $rs = $members->where($user)->setField($data);

    if($rs){
        $rid = 1;
    }else{
        $rid = -1;
    }
    
    if (C("UCENTER_ENABLED") && $rs){
        if(uc_get_user($username)) {
            $rid = uc_user_edit($username , $oldpassword , $newpwd , '' , 1);
        }
    }

    return $rid;

}

/**
*插入用户密保设置信息
*@param string $username
*@return date
*/
function addMibao($username,$wentione,$wentitwo,$wentithree,$answerone,$answertwo,$answerthree,$create_time){
	
	$mem_id = M('members')->where(array('username'=>$username))->getField('id');
	
    $mibao = M('memSecret');
    $data['mem_id'] = $mem_id;
    $data['wentione'] = $wentione;
    $data['wentitwo'] = $wentitwo;
    $data['wentithree'] = $wentithree;
    $data['answerone'] = $answerone;
    $data['answertwo'] = $answertwo;
    $data['answerthree'] = $answerthree;
    $data['create_time'] = $create_time;
    $rs = $mibao->data($data)->add();
    return $rs;
}

/**
*修改密保邮箱手机
*@param string $email
*@param string $mobile
*@param string $username
*@return rs
*/
function updatembinfo($username,$email,$mobile){
/* 	mysql_select_db("db_sdk_mn");
	$sql = "UPDATE c_members SET email='{$email}',mobile='{$mobile}' WHERE username='{$username}'";
	$rs = mysql_query($sql);  */
	$members = M('members');
	$data['email'] = $email;
	$data['mobile'] = $mobile;
	$user['username'] = $username;
	$rs = $members->where($user)->setField($data);
	return $rs;
}

/**
*修改密保答案
*@param string $username
*@return rs
*/
function updateMibao($username,$wentione,$wentitwo,$wentithree,$answerone,$answertwo,$answerthree){
/* 	mysql_select_db("db_sdk_mn");
	$sql = "UPDATE c_mibao SET wentione='{$wentione}',wentitwo='{$wentitwo}',wentithree='{$wentithree}'
		,answerone='{$answerone}',answertwo='{$answertwo}',answerthree='{$answerthree}' WHERE username='{$username}'";
	$rs = mysql_query($sql); 
	
	return $rs; */
	$mibao = M('memSecret');	
	$user['username'] = $username;
	$id = M('members')->where($user)->getField('id');
	$data['wentione'] = $wentione;
	$data['wentitwo'] = $wentitwo;
	$data['wentithree'] = $wentithree;
	$data['answerone'] = $answerone;
	$data['answertwo'] = $answertwo;
	$data['answerthree'] = $answerthree;
	$user['mem_id'] = $id;
	$rs = $mibao->where($user)->save($data);
	return $rs;
}

/**
*查询用户密保设置信息
*@param string $username
*@return date
*/
function findMibao($username){
	$user['username'] = $username;
	$id = M('members')->where($user)->getField('id');	
    $mibao = M('memSecret');
    $field = "mem_id,wentione,wentitwo,wentithree,answerone,answertwo,answerthree,create_time";
    $data = $mibao->field($field)->where(array('id'=>$id))->find();
	return $data;
}


/**
 *查询用户密保问题
 *@param string $username
 *@return date
 */
function findSecretQ($username){
/* 	mysql_select_db("db_sdk_mn");
	$sql = "SELECT username,wentione,wentitwo,wentithree FROM
	c_mibao WHERE username='{$username}'";
	$rs = mysql_query($sql);
	$data = array();
	while ($row = mysql_fetch_assoc($rs)){
		$data = $row;
	}

	return $data; */
	$mem_id = M('members')->where(array('username'=>$username))->getField('id');
    $data = M('memSecret')->field('mem_id,wentione,wentitwo,wentithree')->where(array('mem_id'=>$mem_id))->find();
	return $data;
    
}

/**
 *查询密保对应问题
 *@param string $qNumber
 *@return date
 */
function qNamebyqNO($qNumber){
	switch ($qNumber){
		case 1:
			$data = "您母亲的姓名是？";
			break;
		case 2:
			$data = "您父亲的姓名是？";
			break;	
		case 3:
			$data = "您配偶的姓名是？";
			break;
		case 4:
			$data = "您的学号（或工号）是？";
			break;
		case 5:
			$data = "您父亲的生日是？";
			break;
		case 6:
			$data = "您母亲的生日是？";
			break;	
		case 7:
			$data = "您配偶的生日是？";
			break;
		case 8:
			$data = "您的家乡在哪？";
			break;		
		case 9:
			$data = "您最喜欢什么？";
			break;
		case 10:
			$data = "您最难忘的时间？";
			break;	
		default:
			$data = "";
			break;									
	}
	return $data;
}

/**
*验证密保
*@param string $username
*@param string $wentione
*@param string $wentitwo
*@param string $wentithree
*@param string $answerone
*@param string $answertwo
*@param string $answerthree
*@return date
*/
function checkMibao($username,$wentione,$wentitwo,$wentithree,$answerone,$answertwo,$answerthree){
   
    $mibao = M('memSecret');
    $data['m.username'] = $username;
    $data['ms.wentione'] = $wentione;
    $data['ms.wentitwo'] = $wentitwo;
    $data['ms.wentithree'] = $wentithree;
    $data['ms.answerone'] = $answerone;
    $data['ms.answertwo'] = $answertwo;
    $data['ms.answerthree'] = $answerthree;
    //$id = $mibao->where($data)->getField('mem_id');
	$id = $mibao->alias('ms')->join('c_members m on m.id=ms.mem_id')->where($data)->getField('mem_id');
    return $id;
}

/**
*充值记录
*@param string $username
*@return date
*/
function paylist($username,$and){
	import('Vendor.Classes.ShowPage');
	
	$start = I('starttime','');
	$end = I('endtime','');
	$orderid = I('orderid','');
	$actionstr = I('action');
	$queryurl="action=".$actionstr."&starttime=".$start."&endtime=".$end."&orderid=".$orderid;
	$_SERVER['QUERY_STRING'] = $queryurl;

	$page = new \ShowPage();
	
	$page->PageSize = 15;
	
	$mem_id = M('members')->where(array('username'=>$username))->getField('id');
	$sql = "SELECT id,order_id,amount,ptb_cnt,create_time,status FROM c_ptb_pay WHERE mem_id='{$mem_id}' ".$and." ORDER BY id DESC";
	
	//$sql_total = "select count(*) as total FROM c_ptb_pay WHERE mem_id='{$mem_id}' ".$and." ORDER BY id DESC";
/* 	$rs = mysql_query($sql_total);
	while ($row = mysql_fetch_assoc($rs)) {
		$total = $row['total'];
	} */
	$where = "mem_id=".$mem_id.' '.$and;
	$total = M('ptbPay')->where($where)->count();
	
	
	$page->Total = $total;	
	$data  = M('ptbPay')->query($sql." limit  ".$page->OffSet());
	$showpage = $page->ShowLink();

	$arr = array();
	$arr['showpage'] = $showpage;
	$arr['paylist'] = $data;
	
	return $arr;
}

/**
*天天币消费记录
*@param string $username
*@return date
*/
function xiaofeiList($username,$and){
	import('Vendor.Classes.ShowPage');
	
	$start = strtotime(I('starttime',''));
	$end = strtotime(I('endtime',''));
	$orderid = I('orderid','');

	$actionstr = I('action');
	$queryurl="action=".$actionstr."&starttime=".$start."&endtime=".$end."&orderid=".$orderid;
	$_SERVER['QUERY_STRING'] = $queryurl;
	
	$page = new \ShowPage();	
	$page->PageSize = 15;
	$mem_id = M('members')->where(array('username'=>$username))->getField('id');
	$sql = "SELECT id,order_id,amount,ptb_cnt,app_id,agent_id,create_time FROM c_ptb_pay WHERE mem_id='{$mem_id}' and status='2' AND ".$and." ORDER BY id DESC";	
	$sql_total = "select count(*) as total FROM c_ptb_pay WHERE mem_id='{$mem_id}' and status='2' AND ".$and." ORDER BY id DESC";

	$data = M('ptbPay')->query($sql." limit  ".$page->OffSet());
	$where = "mem_id=".$mem_id.' and status=2 AND '.$and;
	$total = M('ptbPay')->where($where)->count();
	$page->Total = $total;

	$showpage = $page->ShowLink();
	$arr = array();
	$arr['showpage'] = $showpage;
	$arr['xflist'] = $data;
	return $arr;
}

/**
*提交问题
*@param string $username
*@param int $sex
*@param string qq
*@param string tel
*@return rs
*/
function insertask($gameid,$title,$details,$uid,$contact,$image,$create_time,$status){
	$model = M("memProblem");
	$data["app_id"] = $gameid;
	$data["title"] = $title;
	$data["details"] = $details;
	$data["mem_id"] = $uid;
	$data["contact"] = $contact;
	$data["image"] = $image;
	$data["create_time"] = $create_time;
	$data["status"] = $status;
	$rs = $model->data($data)->add();
	return $rs; 
}

/**
*查询用户所有提问
*@param string $userid
*@return date
*/
function asklist($userid){
	mysql_select_db("db_sdk_mn");
	$sql = "SELECT id,gameid,title,details,uid,contact,icon,FROM_UNIXTIME(reg_time) reg_time,status FROM c_myask WHERE uid='{$userid}' ORDER BY id DESC";
	$rs = mysql_query($sql);
	
	$data = array();
	while ($row = mysql_fetch_assoc($rs)){
		$data[] = $row;
	}
	
	return $data;
}

/**
*查询提问详细信息
*@param string $id
*@return date
*/
function checkask($userid,$id){
/*	mysql_select_db("db_sdk_mn");
	$sql = "SELECT id,gameid,title,details,uid,contact,image,FROM_UNIXTIME(reg_time) reg_time,status FROM c_myask 
	WHERE uid='{$userid}' AND id='{$id}'";
	$rs = mysql_query($sql);
	
	$data = array();
	while ($row = mysql_fetch_assoc($rs)){
		$data = $row;
	}
	
	return $data;*/
	$dbh = new PDO('mysql:host='.C('DB_HOST').';dbname='.C('DB_NAME'), C('DB_USER'), C('DB_PWD'));
	$sql = "SELECT id,gameid,title,details,uid,contact,icon,FROM_UNIXTIME(reg_time) reg_time,status FROM c_myask 
	WHERE uid='{$userid}' AND id='{$id}'";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $data;
}

/**
*游戏列表
*@return date
*/
function gamelist(){
	$Game = M('game');
	$where['is_delete'] = 2;
	$data = $Game->where($where)->order('listorder ASC ,id ASC')->getfield('id did, id,name,type,icon,create_time,status', true);
	return $data;
}

//热门游戏列表
function hotgamelist(){
	$Game = M('game');
	$data = $Game->alias('g')->field('g.name,i.bigimage,i.url,i.publicity,i.url')->join('c_game_info i on i.app_id = g.id')->where("g.is_delete=2 and i.lanmu=2")->order('g.listorder DESC')->select();
	return $data;
}

/**
*消费相关游戏列表
*@return date
*/
function xfgame(){	
	$dbh = new PDO('mysql:host='.C('DB_HOST').';dbname='.C('DB_NAME'), C('DB_USER'), C('DB_PWD'));
	$sql = "SELECT id,name,type,icon,create_time,status FROM c_game ORDER BY listorder ASC ,id DESC";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $data;
}

/**
*条件查询游戏列表
*@return date
*/
function tggamelist($where){
	mysql_select_db("db_sdk_mn");
	$sql = "SELECT id,name,type,icon,create_time,status FROM c_game WHERE is_delete='0' ".$where.
			" ORDER BY listorder ASC ,id DESC";
	$rs = mysql_query($sql);

	$data = array();
	while ($row = mysql_fetch_assoc($rs)){
		$data[] = $row;
	}
	
	return $data;
}

/**
*条件查询游戏列表
*@return date
*/
function findgamelist($where){
	import('Vendor.Classes.ShowPage');
	$page = new \ShowPage();
	$page->PageSize = 8;
	$game_model = M('Game');
	
	$map['is_delete'] = 2;
	$total = $game_model->where($map)->where($where)->count();

	$page->Total = $total;
	$data = $game_model->field('id,name,type,icon,create_time')->where($map)->where($where)->order('listorder ASC ,id DESC')->limit($page->Offset())->select();

	$showpage = $page->ShowLink();
	$arr = array();
	$arr['showpage'] = $showpage;
	$arr['list'] = $data;
	return $arr;
}

/**
*游戏相关信息
*@param string $id
*@return date
*/
function gameinfo($id){
	$gameInfo = M('gameInfo');
	$where['app_id'] = $id;
	$where['id_delete'] = 2;
	$data = $gameInfo->field('app_id,bigimage,smallimage,mobile_icon,url,iosurl,androidurl,description,function')->where($where)->select();
	return $data;
}

/**
*游戏内容列表
*@return date
*/
function gameinfolist(){
	$field = "app_id id ,app_id , bigimage,smallimage,mobile_icon,url,iosurl,androidurl,description,function,publicity";
	$gameInfo = M('gameInfo');
	$data = $gameInfo->field($field)->getfield($field, true);
	return $data;
}


/**
*查询游戏类型
*@return date
*/
function gametype(){
	$field = "id,name";
	$model = M('gameType');
	$data = $model->field($field)->select();
	return $data;
}

/**
*查询新闻列表
*@return date
*/
function newslist($where){
/* 	include WEBSITE."/include/classes/cls.show_page.php";	
	$page = new ShowPage;	
	$page->PageSize = 4;
	mysql_select_db("db_sdk_mn");
	$sql = "SELECT id,title,image,create_time,total,zhiding FROM c_article where is_delete='0' ".$where." ORDER BY zhiding DESC ,create_time DESC";
	
	$sql_total = "select count(*) as total FROM c_article where is_delete='0' ".$where." ORDER BY zhiding DESC ,create_time DESC";
	$rs = mysql_query($sql_total);
	while ($row = mysql_fetch_assoc($rs)) {
		$total = $row['total'];
	}	
	$page->Total = $total;	
	$newslist  = mysql_query($sql." limit  ".$page->OffSet());
	$data = array();
	while ($row = mysql_fetch_assoc($newslist)) {
		$data[] = $row;
	}
	$showpage = $page->ShowLink();
	$arr = array();
	$arr['showpage'] = $showpage;
	$arr['newslist'] = $data;
	return $arr; */ 
	
	import('Vendor.Classes.ShowPage');
    $page = new \ShowPage();
	$page->PageSize = 4;
    $webArticle = M('webArticle'); // 实例化User对象
	$map['is_delete'] = 2;
    $count = $webArticle->where($map)->where($where)->count();// 查询满足要求的总记录数
	$page->Total = $count;
	$newlist = $webArticle->field('id,title,image,create_time,total,is_top')->where($map)->where($where)->order('is_top desc,create_time desc')->limit($page->OffSet())->select();
	$showpage = $page->ShowLink();
	$arr = array();
	$arr['showpage'] = $showpage;
	$arr['newslist'] = $newlist;
	return $arr;
}

/**
*条件查询新闻列表
*@param string $where
*@return date
*/
function findnews($where){
	$webArticle = M('webArticle');
	$where = "is_delete = 2".$where;
	$data = $webArticle->where($where)->order( "is_top DESC ,create_time DESC")->select();
	return $data;
}

/**
*查询新闻内容列表
*@return date
*/
function contentlist(){
    $webContent = M('webContent');
    $data = $webContent->field('id,content')->select();
    return $data;
}

/**
*查询新闻关联内容
*@param string $newsid
*@return date
*/
function contentinfo($newsid){
    $webContent = M('webContent'); 
    $where['id'] = $newsid;
    $data = $webContent->field('id,content')->where($where)->find();
    return $data;
}

/**
*查询新闻详细内容
*@param string $newsid
*@return date
*/
function newsinfo($newsid){
    $webArticle = M('webArticle');
    $where['id'] = $newsid;
    $data = $webArticle->field('id,title,image,create_time,total')->where($where)->find();
    return $data;
}

/**
*查询开服列表
*@return date
*/
function serverlist(){
	$webServer = M('webServer');
	$where['is_delete'] = 2;
	$where['start_time'] = array('lt',time());
    $data = $webServer->field('id,app_id,sername,start_time,status,image,gift_url')->where($where)->order('start_time desc')->select();

    $whereb['is_delete'] = 2;
    $whereb['start_time'] = array('gt',time());
    $datab = $webServer->field('id,app_id,sername,start_time,status,image,gift_url')->where($whereb)->order('start_time')->select();
    $data = array_merge($datab,$data);
    return $data;
}

/**
*平台首页素材
*@return date
*/
function indexinfo(){
    $webInfo = M('webInfo');
    $indexdata = $webInfo -> getField('id, title, url, img', true);

	return $indexdata;

}

/**
*查询渠道信息
*@param string $source
*@return data
*/
function findqudao($source){
	mysql_select_db("db_sdk_mn");
	$sql = "SELECT id,name,create_time,qudaohao FROM c_qudao WHERE is_delete='0' AND qudaohao='{$source}'";
	$rs = mysql_query($sql);
	
	$data = array();
	while ($row = mysql_fetch_assoc($rs)){
		$data = $row;
	}
	
	return $data;
}


/**
* 获取友情链接
*/
function getFriendLink() {
	$webLinks = M('webLinks');
	$data = $webLinks->where('link_status = 1')->order('listorder asc')->select();
	return $data;
}

/**
* 获取媒体列表
*/
function getMedia() {
	$webMedia = M('webMedia');
	$data = $webMedia->select();
	return $data;
}

/**
* 获取联系与图标
*/
function getcontact() {
	$model = M('gameContact');
	$data = $model->find();
	return $data;
}

/**
* 获取广告图片
*/
function getGuanggao($type) {
    $advertise = M('web_ads');
    $data['type'] = $type;
    $res = $advertise->where($data)->find();
	return $res; 
   
}

/**
* 获取关于我们
*/
function getAboutus($id) {
	$webAboutus = M('webAboutus');
	$data = $webAboutus->where('id='.$id)->find();
	return $data;
}

/**
* 获取邮箱
*/
function getEmail() {
	$model = M('email');
	$data = $model->select();
	return $data;
}

/**
* 获取邮箱smtp
*/
function getEmailsmtp($type) {
	$model = M('email');
	$data = $model->where('type='.$type)->select();
	return $data;
}

/**
*查询礼包信息
*@param string $gameid
*@return date
*/
function findgift($gameid) {
	$field = "id,title,content,starttime,endtime,description";
	$where = "is_delete='0' AND gameid=".$gameid;
	$order = "create_time asc";
	$limit = "0,1";
	$model = M('mlibaoinfo');
	$data = $model->field($field)->where($where)->order($order)->fine();	
	return $data;
}

/**
*查询激活码信息
*@param string $gameid
*@return date
*/
function gcodelist($infoid) {
 	mysql_select_db("db_sdk_mn");
	$sql = "SELECT id,code FROM c_mlibao WHERE status='0' AND infoid='{$infoid}'";
	$rs = mysql_query($sql);
	
	$data = array();
	while ($row = mysql_fetch_assoc($rs)){
		$data[] = $row;
	}

	return $data; 
/* 	$giftCode = M('giftCode')
	$map['status'] = 0;
	$map['infoid'] = $infoid;
	$data = $giftCode->field('id,code')->where($map)->select(); */
	
	
	
	
}

/**
*查询礼包信息
*@param string $gameid
*@return date
*/
function findgiftlog($username,$infoid) {
	mysql_select_db("db_sdk_mn");
	$sql = "SELECT id,title,code FROM c_mlibaolog WHERE libaoid={$infoid} AND username='{$username}'";
	$rs = mysql_query($sql);
	
	$data = array();
	while ($row = mysql_fetch_assoc($rs)){
		$data = $row;
	}

	return $data;
}

/**
*查询根据条件获取礼包的信息和分页的信息
*@param string $gameid
*@return date
*/
function giftlist($where){
	import('Vendor.Classes.ShowPage');
	$page = new \ShowPage();
	$page->PageSize = 12;	
	$gift = M('gift');
	$field = "id,title,app_id,content,start_time,end_time,content";
	$map['is_delete'] = 2;
	$total = $gift->where($map)->where($where)->count();
	$page->Total = $total;
	
	$data = $gift->field($field)->where($map)->where($where)->order('create_time')->limit($page->OffSet())->select();

	$showpage = $page->ShowLink();
	$arr = array();
	$arr['showpage'] = $showpage;
	$arr['list'] = $data;
	return $arr;
	
	
	
	
}


/**
*查询礼包的礼包码信息
*@param string $id   //礼包的ID
*@return date
*/
function codelist($id) {
	$where['is_delete'] = 2;    //2为正常，1为已删除
	$where['id'] = $id;
	$data = M('gift')->where($where)->getField('remain');
	return $data;
}

/**
*查询激活码
*@param string $mem_id    玩家ID
*@param string $gf_id    礼包ID 
*@return date
*/
function strcode($mem_id,$gf_id) {
/* 	mysql_select_db("db_sdk_mn");
	$sql = "SELECT c_gift.code FROM c_giftinfo LEFT JOIN c_gift on c_giftinfo.id = c_gift.infoid 
			WHERE c_giftinfo.id = '{$infoid}' AND status='0' 
			AND c_giftinfo.is_delete=0 AND c_giftinfo.endtime > '{$time}' ORDER BY c_giftinfo.starttime ASC LIMIT 0, 1";
	$rs = mysql_query($sql);
	$data = array();
	while ($row = mysql_fetch_assoc($rs)){
		$data = $row;
	}
	
	return $data; */
	$gift = M('gift');
	$where['g.is_delete'] = 2;  //2为正常，1为已删除
	$where['g.id'] = $gf_id;
	
	//添加礼包开始时间和截止时间的条件
	//$where['start_time'] = array('elt',time());
	$where['g.end_time'] = array('egt',time());
	$where['g.remain'] = array('gt',0);       //留存数要大于0;
	$where['gc.mem_id'] = array('eq',0);
	
	$data = $gift->alias('g')->field('gc.code')->join('LEFT JOIN c_gift_code gc on g.id=gc.gf_id')->where($where)->order('g.start_time ASC')->find();
	
	return $data;
	
	
}

/**
*领取礼包
*@param string $mem_id    玩家ID
*@param string $gf_id    礼包ID 
*@param string $code      礼包码
*@return date
*/
function setlibao($mem_id,$gf_id,$code){
	
	$giftCode = M("giftCode");
	$id = $giftCode->where('code='.$code)->getField('id');
	$map['mem_id'] = $mem_id;
	$map['update_time'] = time();
	$rs = $giftCode->where(array('id'=>$id))->save($map);  //领取礼包
	$grs = M('gift')->where('id='.$gf_id)->setDec('remain');   //领取之后礼包留存数减一
	if($rs && $grs){
		$map['gf_id'] = $gf_id;
		$map['create_time'] = time();
		$map['code'] = $code;
		$res = M('giftLog')->data($map)->add(); //保存礼包领取记录
		if($res){
			return true;
		}else{
			return false;
		}
	}
	return false;
	
}

/**
*是否领取过礼包
*@param string $mem_id   玩家ID
*@return gf_id   礼包ID  
*/
function checklibao($mem_id,$gf_id) {
	$giftCode = M(giftCode);
	$map['gf_id'] = $gf_id;
	$map['mem_id'] = $mem_id;
	$data = $giftCode->field('code')->where($map)->find();
	return $data;
}


//获取汉字首字母
function getfirstchar($s0){ 
		$fchar = ord($s0{0});
		if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
		$s1 = iconv("UTF-8","gb2312", $s0);
		$s2 = iconv("gb2312","UTF-8", $s1);
		if($s2 == $s0){$s = $s1;}else{$s = $s0;}
		$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
		if($asc >= -20319 and $asc <= -20284) return "A";
		if($asc >= -20283 and $asc <= -19776) return "B";
		if($asc >= -19775 and $asc <= -19219) return "C";
		if($asc >= -19218 and $asc <= -18711) return "D";
		if($asc >= -18710 and $asc <= -18527) return "E";
		if($asc >= -18526 and $asc <= -18240) return "F";
		if($asc >= -18239 and $asc <= -17923) return "G";
		if($asc >= -17922 and $asc <= -17418) return "I";
		if($asc >= -17417 and $asc <= -16475) return "J";
		if($asc >= -16474 and $asc <= -16213) return "K";
		if($asc >= -16212 and $asc <= -15641) return "L";
		if($asc >= -15640 and $asc <= -15166) return "M";
		if($asc >= -15165 and $asc <= -14923) return "N";
		if($asc >= -14922 and $asc <= -14915) return "O";
		if($asc >= -14914 and $asc <= -14631) return "P";
		if($asc >= -14630 and $asc <= -14150) return "Q";
		if($asc >= -14149 and $asc <= -14091) return "R";
		if($asc >= -14090 and $asc <= -13319) return "S";
		if($asc >= -13318 and $asc <= -12839) return "T";
		if($asc >= -12838 and $asc <= -12557) return "W";
		if($asc >= -12556 and $asc <= -11848) return "X";
		if($asc >= -11847 and $asc <= -11056) return "Y";
		if($asc >= -11055 and $asc <= -10247) return "Z";
		return null;
	}
	
	
//获取首字母函数
function getgamefirstchar($name){
	//获取name首字母
	$ret = "";
	$s1 = iconv("UTF-8","gb2312", $name);
	$s2 = iconv("gb2312","UTF-8", $s1);
	if($s2 == $name){$name = $s1;}
	for($i = 0; $i < 1; $i++){
		$s1 = substr($name,$i,1);
		$p = ord($s1);
		if($p > 160){
			$s2 = substr($name,$i++,2);
			$ret .= getfirstchar($s2);
		}else{
			$ret .= $s1;
		}
	}
	return $ret;
}
	
//查询平台币返利活动是否是在有效时间内
function getTTBtime() {
	$time = time();
	$pr_model = M('ptbRate');
	
	/* //id为平台币费率的id,通过id来控制不同类型的费率和赠送金额，默认值为0
	$checktime = $pr_model->field('start_time,end_time')->select();
	foreach($checktime as $val){
		if($time<$val['start_time'] || $time>$val['endtime']){
			$return = 0;
		}else{
			
		}
	} */
	$checktime = $pr_model->field('start_time,end_time')->where('status=2')->find();
	$starttime = $checktime['start_time'];
	$endtime = $checktime['end_time'];
	
	//若不在活动时间之内，则没有返利
	if ($time<$starttime || $time>$endtime) {
		$return = 0;
	} else {
		$return = 1;
	}
	return $return;
}

/*生产二维码
 **@param string $data
 **@param string $fileurl
 **@return date
 */
function getcodeimage($data,$fileurl){
	//导入生成二维码类
	Vendor("phpqrcode.phpqrcode");
	//$filename = $errorCorrectionLevel.'|'.$matrixPointSize.'.png';
	// 纠错级别：L、M、Q、H
	$errorCorrectionLevel = 'L';
	// 点的大小：1到10
	$matrixPointSize = 3;
	QRcode::png($data, $fileurl, $errorCorrectionLevel, $matrixPointSize, 2);

	return $fileurl;
}

/**
 * UCenter函数库文件
 */
function _uc_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;

    $key  = md5($key ? $key : UC_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey   = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string        = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box    = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j       = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp     = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a       = ($a + 1) % 256;
        $j       = ($j + $box[$a]) % 256;
        $tmp     = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }

}

function _uc_stripslashes($string) {
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = _stripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }

    return $string;
}

function xml_unserialize(&$xml, $isnormal = false) {
    $xml_parser = new XML($isnormal);
    $data       = $xml_parser->parse($xml);
    $xml_parser->destruct();

    return $data;
}

function xml_serialize($arr, $htmlon = false, $isnormal = false, $level = 1) {
    $s     = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n" : '';
    $space = str_repeat("\t", $level);
    foreach ($arr as $k => $v) {
        if (!is_array($v)) {
            $s .= $space . "<item id=\"$k\">" . ($htmlon ? '<![CDATA[' : '') . $v . ($htmlon ? ']]>' : '') . "</item>\r\n";
        } else {
            $s .= $space . "<item id=\"$k\">\r\n" . xml_serialize($v, $htmlon, $isnormal, $level + 1) . $space . "</item>\r\n";
        }
    }
    $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);

    return $level == 1 ? $s . "</root>" : $s;
}

class XML {

    var $parser;
    var $document;
    var $stack;
    var $data;
    var $last_opened_tag;
    var $isnormal;
    var $attrs = array();
    var $failed = false;

    function __construct($isnormal) {
        $this->XML($isnormal);
    }

    function XML($isnormal) {
        $this->isnormal = $isnormal;
        $this->parser   = xml_parser_create('ISO-8859-1');
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'open', 'close');
        xml_set_character_data_handler($this->parser, 'data');
    }

    function destruct() {
        xml_parser_free($this->parser);
    }

    function parse(&$data) {
        $this->document = array();
        $this->stack    = array();

        return xml_parse($this->parser, $data, true) && !$this->failed ? $this->document : '';
    }

    function open(&$parser, $tag, $attributes) {
        $this->data   = '';
        $this->failed = false;
        if (!$this->isnormal) {
            if (isset($attributes['id']) && !is_string($this->document[$attributes['id']])) {
                $this->document = &$this->document[$attributes['id']];
            } else {
                $this->failed = true;
            }
        } else {
            if (!isset($this->document[$tag]) || !is_string($this->document[$tag])) {
                $this->document = &$this->document[$tag];
            } else {
                $this->failed = true;
            }
        }
        $this->stack[]         = &$this->document;
        $this->last_opened_tag = $tag;
        $this->attrs           = $attributes;
    }

    function data(&$parser, $data) {
        if ($this->last_opened_tag != null) {
            $this->data .= $data;
        }
    }

    function close(&$parser, $tag) {
        if ($this->last_opened_tag == $tag) {
            $this->document        = $this->data;
            $this->last_opened_tag = null;
        }
        array_pop($this->stack);
        if ($this->stack) {
            $this->document = &$this->stack[count($this->stack) - 1];
        }
    }

}
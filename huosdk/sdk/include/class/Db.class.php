<?php
/**
 * DB - A simple database class
 * 
 * @author Author: Vivek Wicky Aswal. (https://twitter.com/#!/VivekWickyAswal) @git https://github.com/indieteq/PHP-MySQL-PDO-Database-Class
 * @version 0.2ab
 */
require ("Response.class.php");

class DB {
    // @object, The PDO object
    private $pdo;
    
    // @object, PDO statement object
    private $sQuery;
    
    // @array, The database settings
    private $settings;
    
    // @bool , Connected to the database
    private $bConnected = false;
    
    // @object, Object for logging exceptions
    private $log;
    
    // @array, The parameters of the SQL query
    private $parameters;
    private $_dbConfig = array (
        'db_type' => DB_TYPE, // dbms
		'db_host' => DB_HOST, // 主机地址
		'db_prefix' => DB_PREFIX, // 数据库前缀
		'db_user' => DB_USER, // 数据库用户
		'db_pwd' => DB_PWD, // 密码
		'db_name' => DB_DATABASE 
    ); // 数据库名
    
    /**
     * Default Constructor 1.
     * Instantiate Log class. 2. Connect to database. 3. Creates the parameter array.
     */
    public function __construct() {
		
        $this->log = new Switchlog(true);
        $this->Connect();
        $this->parameters = array ();
    }
    
    /**
     * This method makes connection to the database.
     * 1. Reads the database settings from a ini file. 2. Puts the ini content into the settings array. 3. Tries to connect to the database. 4. If connection failed, exception is displayed and a log file gets created.
     */
    private function Connect() {
        $dsn = $this->_dbConfig['db_type'] .
             ':dbname=' . $this->_dbConfig['db_name'] . ';host=' . $this->_dbConfig['db_host'] . '';
        try {
            // Read settings from INI file, set UTF8
            $this->pdo = new PDO(
                    $dsn, $this->_dbConfig['db_user'], $this->_dbConfig['db_pwd'], 
                    array (
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" 
                    ));
            
            // We can now log any exceptions on Fatal error.
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Disable emulation of prepared statements, use REAL prepared statements instead.
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            // Connection succeeded, set the boolean to true.
            $this->bConnected = true;
        } catch (PDOException $e) {
            // Write into log
            $this->ExceptionLog($e->getMessage());
            die();
        }
    }
    
    /*
     * You can use this little method if you want to close the PDO connection
     */
    public function CloseConnection() {
        // Set the PDO object to null to close the connection
        // http://www.php.net/manual/en/pdo.connections.php
        $this->pdo = null;
    }
    
    /**
     * Every method which needs to execute a SQL query uses this method.
     * 1. If not connected, connect to the database. 2. Prepare Query. 3. Parameterize Query. 4. Execute Query. 5. On exception : Write Exception into the log + SQL query. 6. Reset the Parameters.
     */
    private function Init($query, $parameters = "") {
        // Connect to database
        if (!$this->bConnected) {
            $this->Connect();
        }
        
        try {
            // Prepare query
            $this->sQuery = $this->pdo->prepare($query);

            // Add parameters to the parameter array
            $this->bindMore($parameters);
            
            // Bind parameters
            if (!empty($this->parameters)) {
                foreach ($this->parameters as $param) {
                    $parameters = explode("\x7F", $param);
                    $this->sQuery->bindParam($parameters[0], $parameters[1]);
                }
            }
            // Execute SQL
            $this->succes = $this->sQuery->execute();
            
        } catch (PDOException $e) {
            // Write into log and display Exception
            $this->ExceptionLog($e->getMessage(), $query);
            die();
        }
        
        // Reset the parameters
        $this->parameters = array ();
    }
    
    /**
     * 检查字符串是否是UTF8编码
     * 
     * @param string $string 字符串
     * @return Boolean
     */
    public function is_utf8($string) {
        return preg_match(
                '%^(?:
					         [\x09\x0A\x0D\x20-\x7E]            # ASCII
					       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
					       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
					       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
					       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
					       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
					       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
					       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
					    )*$%xs', 
                $string);
    }
    
    /**
     * @void Add the parameter to the parameter array
     * 
     * @param string $para
     * @param string $value
     */
    public function bind($para, $value) {
        if ($this->is_utf8($value)) {
            $str = $value;
        } else {
            $str = utf8_encode($value);
        }
        $this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . $str;
    }
    /**
     * @void Add more parameters to the parameter array
     * 
     * @param array $parray
     */
    public function bindMore($parray) {
        if (empty($this->parameters) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
                $this->bind($column, $parray[$column]);
            }
        }
    }
    
    /**
     * If the SQL query contains a SELECT or SHOW statement it returns an array containing all of the result set row If the SQL statement is a DELETE, INSERT, or UPDATE statement it returns the number of affected rows
     * 
     * @param string $query
     * @param array $params
     * @param int $fetchmode
     * @return mixed
     */
    public function query($query, $params = null, $fetchmode = PDO::FETCH_ASSOC) {
        $query = trim($query);

        $this->Init($query, $params);

        $rawStatement = explode(" ", $query);
        
        // Which SQL statement is used
        $statement = strtolower($rawStatement[0]);
        
        if ($statement === 'select' || $statement === 'show') {
            return $this->sQuery->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            return $this->sQuery->rowCount();
        } else {
            return NULL;
        }
    }
    
    /**
     * Returns the last inserted id.
     * 
     * @return string
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Returns an array which represents a column from the result set
     * 
     * @param string $query
     * @param array $params
     * @return array
     */
    public function column($query, $params = null) {
        $this->Init($query, $params);
        $Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);
        
        $column = null;
        
        foreach ($Columns as $cells) {
            $column[] = $cells[0];
        }
        
        return $column;
    }
    /**
     * Returns an array which represents a row from the result set
     * 
     * @param string $query
     * @param array $params
     * @param int $fetchmode
     * @return array
     */
    public function row($query, $params = null, $fetchmode = PDO::FETCH_ASSOC) {
        $this->Init($query, $params);
        return $this->sQuery->fetch($fetchmode);
    }
    /**
     * Returns the value of one single field/column
     * 
     * @param string $query
     * @param array $params
     * @return string
     */
    public function single($query, $params = null) {
        $this->Init($query, $params);
        return $this->sQuery->fetchColumn();
    }
    /**
     * Writes the log and returns the exception
     * 
     * @param string $message
     * @param string $sql
     * @return string
     */
    private function ExceptionLog($message, $sql = "") {
        // $exception = 'Unhandled Exception. <br />';
        // $exception .= $message;
        // $exception .= "<br /> You can find the error back in the log.";
        if (!empty($sql)) {
            // Add the Raw SQL to the Log
            $message .= "\r\nRaw SQL : " . $sql;
        }
        // Write into log
        $this->log->write($message);
        
        // return $exception;
    }
    
    public function checkClient($clientid, $appid){
        $sql = "select is_switch from ".$this->_dbConfig['db_prefix']."game_client where id=:clintid AND app_id=:appid AND status=1 ";
        $this->bind("clintid", $clientid);
        $this->bind("appid", $appid);
        $is_switch = $this->single($sql);
        return $is_switch;
    }
    
    /* 获取数据库名与支付方式  */
    public function getAppkey($appid){
        if (empty($appid) || 0 > $appid) {
            return NULL;
        }
		
        $sql = "select app_key from ".$this->_dbConfig['db_prefix']."game where id=:appid ";
        $this->bind("appid", $appid);
        $appkey = $this->single($sql);
        return $appkey;
    }
    
    /* 获取client信息 */
    public function getClient($client_id){
        if (empty($client_id) || 0 > $client_id) {
            return NULL;
        }
        
        $sql = "select * from ".$this->_dbConfig['db_prefix']."game_client where id=:clientid ";
        $this->bind("clientid", $client_id);
        $clientinfo = $this->row($sql);
        return $clientinfo;
    }
    
    
    public function getAgentid($agentgame){
        if (empty($agentgame) || 'default' ==$agentgame) {
            return 0;
        }
    
        $sql = "SELECT agent_id FROM ".$this->_dbConfig['db_prefix']."agent_game where agentgame=:agentgame";
        $this->bind("agentgame", $agentgame);
        $agent_id = $this->row($sql);
		
        if (empty($agent_id['agent_id'])){
            $agent_id = 0;
        }
        return $agent_id['agent_id'];
    }
    
    public function setUsername(){
        $basenum = 10000;
		
        // 生成用户名
        $minsql = "select min(base) from ".$this->_dbConfig['db_prefix']."mem_base";
        $min = $this->single($minsql);
		
        $cntsql = "select count(id) from ".$this->_dbConfig['db_prefix']."mem_base where base=$min";
        $cnt = $this->single($cntsql) - 1;
        
        $limit = rand(0, $cnt);
		
        $upsql = "select id from ".$this->_dbConfig['db_prefix']."mem_base where base=$min limit $limit,1";
        $uid = $this->single($upsql);

        $upsql = "UPDATE ".$this->_dbConfig['db_prefix']."mem_base SET `base` = `base` + 1 WHERE `id` = $uid";
        $rs = $this->query($upsql);
		
        if(!empty($rs) && 0 < $rs){
            $username =  $basenum * $min + $uid;
        }
		$userinfo = $this->getUserinfo($username);
		$i = 0;
		//存在用户一直向下执行
		while($userinfo && $i < 20){
			
			$i ++;
			$upsql = "UPDATE ".$this->_dbConfig['db_prefix']."mem_base SET `base` = `base` + 1 WHERE `id` = $uid";
			$rs = $this->query($upsql);
			if(!empty($rs) && 0 < $rs){
				$username =  $basenum * ($min+$i) + $limit;
			}
			$userinfo = $this->getUserinfo($username);
        }
        
        return $username ;
    }
    
    public function getUserinfo($username){
        //0 为试玩状态 1为正常状态，2为冻结状态
        $sql = "select * from ".$this->_dbConfig['db_prefix']."members where username=:username";
        $this->bind("username", $username);
        $data = $this->row($sql);
        return $data;
    }
    
    public function getOauthinfo($userfrom, $openid){
        //1试玩 2 qq 3 微信 4微博
        $sql = "select * from ".$this->_dbConfig['db_prefix']."mem_oauth where `from`=:userfrom and openid=:openid";
        $this->bind("userfrom", $userfrom);
        $this->bind("openid", $openid);
        $data = $this->row($sql);
        return $data;
    }
    
    //通过用户ID获取用户信息
    public function getUserbyid($mem_id){
        //1 为试玩状态 2为正常状态，3为冻结状态
        $sql = "select * from ".$this->_dbConfig['db_prefix']."members where id=:mem_id";
        $this->bind("mem_id", $mem_id);
        $data = $this->row($sql);
        return $data;
    }
    
    //获取平台币余额
    public function getPtb($mem_id){
        $sql = "select * from ".$this->_dbConfig['db_prefix']."ptb_mem where mem_id=:mem_id";
        $this->bind("mem_id", $mem_id);
        $data = $this->row($sql);
        return $data;
    }
    
    //获取游戏币余额
    public function getGm($mem_id, $app_id){
        $sql = "select * from ".$this->_dbConfig['db_prefix']."gm_mem where mem_id=:mem_id AND app_id=:app_id";
        $this->bind("mem_id", $mem_id);
        $this->bind("app_id", $app_id);
        $data = $this->row($sql);
        return $data;
    }
    
    //更新支付方式
    public function updatePayway($order_id, $payway){
        $sql = "UPDATE ".$this->_dbConfig['db_prefix']."pay SET `payway` = :payway WHERE `order_id` =:order_id";
        $this->bind("order_id", $order_id);
        $this->bind("payway", $payway);
        $data = $this->query($sql);
		return true;
    }
    
    //获取订单信息
    public function getPayinfo($order_id){
		if (empty($order_id)){
			return false;
		}
        $sql = "select * from ".$this->_dbConfig['db_prefix']."pay where order_id=:order_id";
        $this->bind("order_id",$order_id);
        $paydata = $this->row($sql);
        return $paydata;
    }
    
    //获取订单扩展信息
    public function getPayextinfo($pay_id){
		if (empty($pay_id)){
			return false;
		}
        $sql = "select * from ".$this->_dbConfig['db_prefix']."pay_ext where pay_id=:pay_id";
        $this->bind("pay_id",$pay_id);
        $paydata = $this->row($sql);
        return $paydata;
    }
    
    public function getCpurl($appid, $cid = 0){
        if (!isset($appid) || 1 > $appid) {
            return NULL;
        }
        $sql = "select cpurl from ".$this->_dbConfig['db_prefix']."game where appid=:appid ";
        $this->bind("appid", $appid);
        $cpurl = $this->single($sql);      
       
        return $cpurl;
    }
    
    //获取支付方式
    public function getPayway($app_id){
        //获取游戏支付方式
        $sql = "SELECT pw_id FROM ".$this->_dbConfig['db_prefix']."payway_game where app_id=:app_id ";
        $this->bind("app_id", $app_id);
        $gpw_data = $this->column($sql);

        if (empty($gpw_data)){
            $pwsql = "SELECT payname AS a, disc AS b FROM ".$this->_dbConfig['db_prefix']."payway where status=2";
			$data = $this->query($pwsql);
        }else{
            $gpwstr = implode(',', $gpw_data);
            $pwsql = "SELECT payname AS a, disc AS b FROM ".$this->_dbConfig['db_prefix']."payway where status=2 AND id IN (".$gpwstr.")";

            $data = $this->query($pwsql);
        }
		
		return $data;
    }
    
    public function insertOauth($oauthdata){
        $insql = " INSERT INTO ".$this->_dbConfig['db_prefix']."mem_oauth";
        $insql .= " (`from`,`name`,`head_img`,`mem_id`,`create_time`,`last_login_time`,`last_login_ip`,`access_token`,`expires_date`,`openid`) ";
        $insql .= " VALUES ";
        $insql .= " (:from,:name,:head_img,:mem_id,:create_time,:last_login_time,:last_login_ip,:access_token,:expires_date,:openid)";
        $rs = $this->query($insql, $oauthdata);    
        if ($rs){
            return intval($this->lastInsertId());
        }else{
            return FALSE;
        }
    }
    public function insertRegist($regdata){
        $regdata['pay_pwd'] = $regdata['password'];
        $insql = " INSERT INTO ".$this->_dbConfig['db_prefix']."members";
        $insql .= " (`username`,`password`,`pay_pwd`,`mobile`,`nickname`,`from`,`imei`,`agentgame`,`app_id`,`agent_id`,`status`,`reg_time`,`update_time`) ";
        $insql .= " VALUES ";
        $insql .= " (:username,:password,:pay_pwd, :mobile,:nickname,:from,:imei,:agentgame,:app_id,:agent_id,:status,:reg_time, :update_time)";
        $rs = $this->query($insql, $regdata);
		$this->data_log("register.log",date("Y-m-d H:i:s")." username= ".$regdata['username']." app_id= ".$regdata['app_id']." agent_game= ".$regdata['agentgame']." agent_id= ".$regdata['agent_id']."\n");
        if ($rs){
            return intval($this->lastInsertId());
        }else{
            return FALSE;
        }
    }
    /*
     * 插入登陆信息
     */
    public function insertLogin($logindata){
        $memdata['mem_id'] = $logindata['mem_id'];
        $memdata['app_id'] = $logindata['app_id'];
        $checksql = "SELECT id from ".$this->_dbConfig['db_prefix']."mem_game WHERE mem_id=:mem_id AND app_id=:app_id";
        $memgame = $this->single($checksql, $memdata);  

        if (empty($memgame)){
            $memext['game_cnt'] = 1;
            $logindata['flag'] = 1;
            $memdata['create_time'] = $logindata['login_time'];
            $memdata['update_time'] = $logindata['login_time'];
            
            //插入玩家游戏数据
            $memgamesql = "INSERT INTO ".$this->_dbConfig['db_prefix']."mem_game ";
            $memgamesql .= " (mem_id, app_id, create_time, update_time) ";
            $memgamesql .= " VALUES ";
            $memgamesql .= "(:mem_id, :app_id, :create_time, :update_time)";
            $this->query($memgamesql, $memdata);
			$this->data_log('register_data.log',date("Y-m-d H:i:s").'instersql => mem-id='.$memdata['mem_id']." app_id= ".$memdata['app_id']."\n");
        }else{
            $memext['game_cnt'] = 0;
            $logindata['flag'] = 0;
            
            //更新玩家游戏数据
            $memgamesql = "UPDATE ".$this->_dbConfig['db_prefix']."mem_game ";
            $memgamesql .= " SET update_time=:update_time WHERE id=:id";
            
            $this->bind('update_time', $logindata['login_time']);
            $this->bind('id',$memgame);
			$this->data_log('register_data.log',date("Y-m-d H:i:s").'updatesql='.$memgame."\n");
            $this->query($memgamesql);
        }

        //更新玩家其他数据
        $checkextsql = "SELECT mem_id from ".$this->_dbConfig['db_prefix']."mem_ext WHERE mem_id=:mem_id";
        $this->bind('mem_id', $logindata['mem_id']);
        $memextid = $this->single($checkextsql);
        
        $memext['mem_id'] = $logindata['mem_id'];
        $memext['last_login_time'] = $logindata['login_time'];
        $memext['user_token'] =  $logindata['user_token'];
        if (empty($memextid)){
            $memextsql = "INSERT INTO ".$this->_dbConfig['db_prefix']."mem_ext ";
            $memextsql .= " (`mem_id`,`last_login_time`,`game_cnt`,`sum_money`,`last_pay_time`,`last_money`,`order_cnt`,`login_cnt`,`user_token`) ";
            $memextsql .= " VALUES ";
            $memextsql .= " (:mem_id,:last_login_time,:game_cnt,0,0,0,0,1,:user_token)";
            $this->query($memextsql, $memext);
        }else{
            $memextsql = "UPDATE ".$this->_dbConfig['db_prefix']."mem_ext ";
            $memextsql .= " SET last_login_time=:last_login_time, game_cnt=game_cnt+:game_cnt, login_cnt=login_cnt+1, user_token=:user_token WHERE mem_id=:mem_id";
            $this->query($memextsql, $memext);
        }
        
        //更新玩家SESSION数据
        $_SESSION['user_token'] = $memext['user_token'];
        $_SESSION['mem_id'] = $memext['mem_id'];
		$sessiondata='';
		$sessiondata.=date('Y-m-d H:i:s').' -> ';
		$sessiondata.='user_token : '.$memext['user_token']." , ";
		$sessiondata.='mem_id : '.$memext['mem_id'].";\n";
		$this->data_log("session_data_log.log",$sessiondata);
        unset($logindata['user_token']);
        
        $loginsql = " INSERT INTO `".$this->_dbConfig['db_prefix']."login_log`";
        $loginsql .= " (`mem_id`,`app_id`,`agentgame`,`imei`,`deviceinfo`,`userua`,`from`,`flag`,`reg_time`,`login_time`,`agent_id`,`login_ip`,`ipaddrid`)";
        $loginsql .= " VALUES ";
        $loginsql .= " (:mem_id,:app_id,:agentgame,:imei,:deviceinfo,:userua,:from,:flag,:reg_time,:login_time,:agent_id,:login_ip,:ipaddrid)";
        return $this->query($loginsql, $logindata);
    }
    
    public function updateMemextpay($paydata){
        $userdata['last_pay_time'] = $paydata['create_time'];
        $userdata['last_money'] = $paydata['amount'];
        $userdata['last_money1'] = $userdata['last_money'];
        $userdata['mem_id'] = $paydata['mem_id'];
        $memextsql = "UPDATE ".$this->_dbConfig['db_prefix']."mem_ext ";
        $memextsql .= " SET sum_money=sum_money+:last_money, last_pay_time=:last_pay_time, last_money=:last_money1, order_cnt=order_cnt+1 WHERE mem_id=:mem_id";

        $this->query($memextsql, $userdata);
    }
    /* 
     * 插入退出信息
     */
    
    public function insertLogout($logoutdata){
        //轻松session
        session_destroy();
        $logoutsql = " INSERT INTO `".$this->_dbConfig['db_prefix']."logout_log`";
        $logoutsql .= " (`mem_id`,`app_id`,`agent_id`,`agentgame`,`imei`,`deviceinfo`,`userua`,`from`,`logout_time`,`logout_ip`,`ipaddrid`)";
        $logoutsql .= " VALUES ";
        $logoutsql .= "(:mem_id,:app_id,:agent_id,:agentgame,:imei,:deviceinfo,:userua,:from,:logout_time,:logout_ip,:ipaddrid)";
        return $this->query($logoutsql, $logoutdata);
    }
    
    /*
     * 插入支付数据
     */
    private function insertPay($paydata){
        $paysql = "INSERT INTO `".$this->_dbConfig['db_prefix']."pay`";
        $paysql .= " (`order_id`,`mem_id`,`agent_id`,`app_id`,`amount`,`from`,`status`,`cpstatus`,`create_time`,`update_time`,`attach`)";
        $paysql .= " VALUES ";
        $paysql .= " (:order_id,:mem_id,:agent_id,:app_id,:amount,:from,:status,:cpstatus,:create_time,:update_time,:attach)";
        $rs =  $this->query($paysql, $paydata);//执行sql语句
        if ($rs){
	//记录用户充值订单
		$filename="paydata_data_info.log";
		$data='';
		$data.=date("Y-m-d H:i:s")." -> ";
		$data.="订单id：".$rs.',';
		$data.="订单号：".$paydata['order_id'].',';
		$data.="用户id：".$paydata['mem_id'].',';
		//$data.="客户端v：".$paydata['client_v'].',';
		$data.="渠道id：".$paydata['agent_id'].',';
		$data.="游戏id：".$paydata['app_id'].',';
		$data.="充值金额：".$paydata['amount'].';';
		$data.="\n";
		$this->data_log($filename,$data);
		
            return (int)$this->lastInsertId();
        }else{
            return FALSE;
        }
    }
    ##日志记录
	private function data_log($filename,$data){
		if(!file_exists($filename))
			system("cd . > ".$filename);
		$file=fopen($filename,"a+");
		if(!fwrite($file,$data)){
			$time=date("Y-m-d H:i:s");
			fwrite($file,$time." -> 记录错误。\n");
		}
		fclose($file);
	}
    /*
     * 插入支付数据扩展信息
     */
    private function insertPayext($payextdata){
        $payextsql = "INSERT INTO `".$this->_dbConfig['db_prefix']."pay_ext`";
        $payextsql .= " (`pay_id`,`role`,`productname`,`productdesc`,`deviceinfo`,`userua`,`agentgame`,`server`,`pay_ip`,`imei`,`cityid`) ";
        $payextsql .= " VALUES ";
        $payextsql .= " (:pay_id,:role,:productname,:productdesc,:deviceinfo,:userua,:agentgame,:server,:pay_ip,:imei,:cityid)";
        $rs =  $this->query($payextsql, $payextdata);
		
		//记录用户充值请求信息
		$filename="pay_user_info.log";
		$data='';
		$data.=date("Y-m-d H:i:s")." -> ";
		$data.="支付id：".$rs.',';
		$data.="充值的游戏渠道：".$payextdata['agentgame'].',';
		$data.="支付ip地址：".$payextdata['pay_ip'].',';
		$data.="手机imei码：".$payextdata['imei'].';';
		$data.="\n";
		
		$this->data_log($filename,$data);
        return (int)$this->lastInsertId();
    }
    
    /*
     * 支付创建函数
     */
    public function doPay($paydata, $pay_extdata){
        $pay_id = $this->insertPay($paydata);//创建订单
        if ($pay_id){
            $pay_extdata['pay_id'] = $pay_id;
            $this->insertPayext($pay_extdata);//记录设备信息
            return $pay_id;//返回订单id
        }else{
            return FALSE;
        }
    }
    
    /*
     * 游戏币支付订单插入
     */
    public function doGmpay($order_id, $gm_cnt){
        $paysql = "INSERT INTO `".$this->_dbConfig['db_prefix']."gm_pay` ";
        $paysql .= "(`order_id`,`mem_id`,`agent_id`,`app_id`,`amount`,`gm_cnt`,`from`,`status`,`create_time`,`update_time`)";
        $paysql .= " SELECT ";
        $paysql .= "`order_id`,`mem_id`,`agent_id`,`app_id`,`amount`,:gm_cnt,`from`,`status`,`create_time`,`update_time`";
        $paysql .= " from ".$this->_dbConfig['db_prefix']."pay where order_id=:order_id";
        $this->bind("order_id",$order_id);
        $this->bind("gm_cnt",$gm_cnt);
        $rs =  $this->query($paysql);
        if ($rs){
            return (int)$this->lastInsertId();
        }else{
            return FALSE;
        }
    }
    
    public function doPtbpay($order_id, $ptb_cnt){
        $paysql = "REPLACE INTO `".$this->_dbConfig['db_prefix']."ptb_pay` ";
        $paysql .= "(`order_id`,`mem_id`,`agent_id`,`app_id`,`amount`,`ptb_cnt`,`from`,`status`,`create_time`,`update_time`)";
        $paysql .= " SELECT ";
        $paysql .= "`order_id`,`mem_id`,`agent_id`,`app_id`,`amount`,:ptb_cnt,`from`,`status`,`create_time`,`update_time`";
        $paysql .= " FROM ".$this->_dbConfig['db_prefix']."pay where order_id=:order_id";
        $this->bind("order_id",$order_id);
        $this->bind("ptb_cnt",$ptb_cnt);
        $rs =  $this->query($paysql);
        if ($rs){
            return (int)$this->lastInsertId();
        }else{
            return FALSE;
        }
    }
    
    //获取游戏信息
    public function getGameinfo($app_id){
        $sql = "SELECT * FROM ".$this->_dbConfig['db_prefix']."game WHERE id=:app_id";
        $this->bind("app_id", $app_id);
        $data = $this->row($sql);
        return $data;
    }
    
    public function doPaynotify($orderid, $amount, $paymark='') {
        if(empty($orderid) || empty($amount)){
            return FALSE;
        }
        $trade['orderid'] = $orderid;
        $trade['remark'] = $paymark;
        
        $time = time();

        // 1 通过订单号查询订单信息
        $paydata = $this->getPayinfo($orderid);
		if (empty($paydata)){
			return FALSE;
		}
        $myamount = number_format($paydata['amount'], 2, '.', '');
        $amount   = number_format($amount, 2, '.', '');
		
        //2 验证订单数额的一致性与状态改变
        if (($myamount == $amount) && 2 != $paydata['status']) {
            //2.1 订单状态改变
            $sql = "UPDATE ".$this->_dbConfig['db_prefix']."pay SET status=2, remark=:remark WHERE order_id=:orderid";
            $rs = $this->query($sql, $trade);

            //2.2 回调CP
            if ($rs) {
                $this->updateMemextpay($paydata);
                //2.2.1 查询CP回调地址与APPKEY
                $game_data = $this->getGameinfo($paydata['app_id']); 
                $cpurl = $game_data['cpurl'];
                $app_key = $game_data['app_key'];
                
                $param['order_id'] = (string)$paydata['order_id'];
                $param['mem_id'] = (string)$paydata['mem_id'];
                $param['app_id'] = (string)$paydata['app_id'];
                $param['money'] = (string)$myamount;
                $param['order_status'] ='2';
                $param['paytime'] = (string)$paydata['create_time'];
                $param['attach'] = (string)$paydata['attach'];
                
                //2.2.2 拼接回调
                $signstr = "order_id=".$paydata['order_id']."&mem_id=".$paydata['mem_id']."&app_id=".$paydata['app_id'];
                $signstr .= "&money=".$myamount."&order_status=2&paytime=".$paydata['create_time']."&attach=".$paydata['attach'];
                $md5str = $signstr."&app_key=".$app_key;

                $sign = md5($md5str);
                $param['sign'] = (string)$sign;
                //2.2.3 通知CP
                if ($paydata['cpstatus'] == 1 || $paydata['cpstatus'] == 3) {
                    $i = 0;
                    while (1) {
                        $cp_rs = Response::payback($cpurl, $param);
                        if ($cp_rs > 0) {
                            $cpstatus = 2;
                            break;
                        }else{
                            $cpstatus = 3;
                            $i ++;
                            sleep(2);
                        }
        
                        if ($i == 3) {
                            break;
                        }
                    }
                }
                
                //更新CP状态
                $sql = "UPDATE ".$this->_dbConfig['db_prefix']."pay SET cpstatus=:cpstatus WHERE order_id=:orderid";
                $this->bind('cpstatus', $cpstatus);
                $this->bind('orderid', $orderid);
                $this->query($sql);
            }
        }
        return TRUE;
    }
}

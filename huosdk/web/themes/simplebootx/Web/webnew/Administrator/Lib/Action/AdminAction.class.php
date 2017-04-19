<?php
    /*
     *  qjcms
     * 
     */
    class AdminAction extends AuthAction {
        public $session;
        function __construct(){
        	parent::__construct();
        	$this->session = new es_session();
        }
        
        public function index(){
            
            $groups = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."group WHERE is_del = 0 ORDER BY orderid ASC");
            $this->assign("groups",$groups);
            
            $page = $_GET['page']?intval($_GET['page']):1;
            $perpage = 20;
            $where = " WHERE a.is_del = 0 ";
            $orderby = "";
            $limit = " LIMIT ".($perpage*($page-1)).",$perpage";
            
            $username = isset($_GET['username'])?new_addslashes($_GET['username']):"";
            $groupid = isset($_GET['groupid'])?intval($_GET['groupid']):"-1";
            $this->assign("username",$username);
            $this->assign("groupid",$groupid);
            
            if($username!=""){
            	$where .= " AND a.username like '%$username%'";
            	$orderby = " ORDER BY length(replace(a.`username`,'$username','')) ASC ";
            }
            if($groupid != "-1"){
            	$where .= " AND a.`groupid` = $groupid";
            }
            
            $sql = "SELECT a.id,a.username,a.create_time,a.login_time,a.login_ip,a.`groupid`,g.name,g.is_del 
                        FROM ".DB_PREFIX."admin a
                        LEFT JOIN ".DB_PREFIX."group g
                        ON a.`groupid` = g.id $where $orderby $limit";
            $count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."admin a $where ");

            $pages = new Page($count,$perpage);
            $pages = $pages->show();
            $this->assign("pages",$pages);
            
            $list = $GLOBALS['db']->getAll($sql);
            $this->assign("list",$list);
    	    $this->display();
        }
        
        function add(){
            if($_POST){
                $username = trim(new_addslashes($_POST['username']));
                $password = trim(new_addslashes($_POST['password']));
                $repeat_password = trim(new_addslashes($_POST['repeat_password']));
                $groupid = intval($_POST['groupid']);
                if($username == ""){
                	showMsg(lang("FILL_REQUIRE"),"goback",-1);
                }
                if($password == ""){
                	showMsg(lang("FILL_REQUIRE"),"goback",-1);
                }
                if($username == ""){
                	showMsg(lang("FILL_REQUIRE"),"goback",-1);
                }
                if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."admin WHERE username = '$username'")>0){
                	showMsg(lang("ADMIN_EXISTS"),"goback",-1);
                }
                if(strlen($password)>0 && strlen($password)<6){
                    showMsg(lang("PWD_TOO_SHORT"),"goback",-1);
                }
                elseif(strlen($password)>20){
                    showMsg(lang("PWD_TOO_LONG"),"goback",-1);
                }
                elseif($password != $repeat_password){
                	showMsg(lang("TWO_PWD_ERROR"),"goback",-1);
                }
                
                $admin = array();
                $admin['username'] = $username;
                $admin['create_time'] = time();
                $admin['login_time '] = 0;
                $admin['groupid'] = $groupid;
                
                $rand_str = "abcdefghijklmnopqrstuvwxyz";
                $salt = "";
                for($i=0;$i<6;$i++){
                	$salt .= $rand_str{rand(0,25)};
                }
                
                $admin['salt'] = $salt;
                $admin['password'] = md5($password.$salt);
                
                $GLOBALS['db']->autoExecute(DB_PREFIX."admin",$admin,"INSERT");
                add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$admin['username']);
                showMsg(lang("SUCCESS"),ADMIN_URL."/Admin/index");
                
                
            }
            
            $groups = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."group WHERE is_del = 0 ORDER BY orderid ASC");
            $this->assign("groups",$groups);
            
            $this->display();
            
        }
        
        function edit(){
            
            $id = intval($_GET['id']);
            $admin = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."admin WHERE id = ".$id);
            if(!$admin){
                url_redirect(ADMIN_URL."/Admin/index");
            }
            $this->assign("admin",$admin);
        	
            if($_POST){
                $username = isset($_POST['username']) ? trim(new_addslashes($_POST['username'])) : "";
                $password = isset($_POST['password']) ? trim(new_addslashes($_POST['password'])) : "";
                $groupid = intval($_POST['groupid']);
                $repeat_password = isset($_POST['repeat_password']) ? trim(new_addslashes($_POST['repeat_password'])) : "";
                if(($password != "" || $repeat_password != "") && $password != $repeat_password){
                	showMsg(lang("TWO_PWD_ERROR"),"goback",-1);
                }
                if($GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."admin WHERE username = '$username' AND id != $id")>0){
                	showMsg(lang("ADMIN_EXISTS"),"goback",-1);
                }
                if(strlen($password)>0 && strlen($password)<6){
                	showMsg(lang("PWD_TOO_SHORT"),"goback",-1);
                }
                elseif(strlen($password)>20){
                	showMsg(lang("PWD_TOO_LONG"),"goback",-1);
                }
                
                $admin['username'] = $username;
                if($password != ""){
                	$admin['password'] = md5($password.$admin['salt']);
                }
                $admin['groupid'] = $groupid;
                
                $GLOBALS['db']->autoExecute(DB_PREFIX."admin",$admin,"UPDATE","id=$id");
                add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$admin['username']);
                showMsg(lang("SUCCESS"),ADMIN_URL."/Admin/index");
                
                
            }
            
            
            
            
            
            $groups = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."group WHERE is_del = 0 ORDER BY orderid ASC");
            $this->assign("groups",$groups);
            
            $this->display();
            
        }
    	
    	function delete(){
    		$id = intval($_GET['id']);
    		$ids = $_POST['ids'];
    		if(isset($ids)){
    		    $id_arr = explode(",",$ids);
    		    foreach($id_arr as $k=>$v){
    		    	if($v==""){
    		    	    unset($id_arr[$k]);	
    		    	}
    		    }
    		    if(in_array($GLOBALS['admin']['id'],$id_arr)){
    		    	showMsgajax(lang("NOTDELSELF"),-1);
    		    }
    		    if(count($id_arr)==0){
    		    	showMsgajax(lang("SUCCESS"),1);
    		    }
    		    $sql_del = "UPDATE ".DB_PREFIX."admin SET is_del = 1 WHERE id in ($ids)";
        		$GLOBALS['db']->query($sql_del);
        		add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$ids);
        		showMsgajax(lang("SUCCESS"),1);
    		}else{
        		if($id == $GLOBALS['admin']['id']){
        		    showMsg(lang("CANNOT_DEL_SELF"),"goback",-1);
        		}
        		$sql_del = "UPDATE ".DB_PREFIX."admin SET is_del = 1 WHERE id = $id";
        		$GLOBALS['db']->query($sql_del);
        		add_admin_log($GLOBALS['action'][MODULE_NAME].":".$GLOBALS['action'][ACTION_NAME].":".$id);
        		showMsg(lang("SUCCESS"),"goback");
    		}
    	}
        
    }
    
?>
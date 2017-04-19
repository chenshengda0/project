<?php
    /*
     *  qjcms
     * 
     */
    require_once ROOT_PATH."system/utils/es_session.php";
    class CommonAction extends Action{
        public $session;
        public $menuid;
        function __construct(){
            parent::__construct();
        	$this->session = new es_session();
        	$this->header_menus();
        	$this->menuid = $GLOBALS['db']->getOne("SELECT menuid FROM ".DB_PREFIX."modules WHERE `class` = '".MODULE_NAME."' AND is_del = 0");
        	$this->assign("menuid",$this->menuid);
        	$this->left_modules();
        	$this->assign("module_name",MODULE_NAME);
        	$this->assign("action_name",ACTION_NAME);
        	///当前位置
        	$location1 = $GLOBALS['db']->getRow("SELECT id,name FROM ".DB_PREFIX."modules WHERE class = '".MODULE_NAME."'");
        	$location2 = lang($GLOBALS['db']->getOne("SELECT name FROM ".DB_PREFIX."actions WHERE moduleid = '".$location1['id']."' and func = '".ACTION_NAME."'"));
        	$this->assign("location",lang($location1["name"])." - ".$location2);

        	import('ORG.Util.Page');// 导入分页类
        	$this->assign("_LANG",$GLOBALS['lang']);
        	
        	//网站设置
        	$config = $GLOBALS['db']->getAll("SELECT name,value,type FROM ".DB_PREFIX."site_conf");
        	global $site;
        	$site = array();
        	foreach ($config as $k1 => $v1){
        		if($v1['type'] == 2){
        			$site[$v1['name']] = str_replace(PHP_EOL,"<br>", $v1['value']);
        		}else{
        			$site[$v1['name']] = $v1['value'];
        		}
        	
        	}
        }
        
        function header_menus(){
            global $menus;
            $menus = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."menus WHERE is_show = 1 AND is_del = 0 ORDER BY orderid ASC");
            foreach($menus as $k=>$v){
            	$menus[$k]['first_module_name'] = $GLOBALS['db']->getOne("SELECT `class` FROM ".DB_PREFIX."modules WHERE menuid = ".$v['id']." AND is_show = 1 AND is_del = 0 AND pid > 0 ORDER BY orderid ASC LIMIT 1");
            }
        }
        
        function left_modules(){
        	global $modules;
        	if($this->menuid){
        	    $modules = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."modules WHERE menuid = ".$this->menuid." AND pid=0 AND is_show = 1 AND is_del = 0 ORDER BY orderid ASC");
        	    foreach($modules as $k=>$v){
        	    	$modules[$k]["sons"] = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."modules WHERE is_del = 0 AND pid = ".$v['id']." AND is_show = 1 ORDER BY orderid ASC");
        	    }
        	    $pid = $GLOBALS['db']->getOne("SELECT pid FROM ".DB_PREFIX."modules WHERE `class` = '".MODULE_NAME."'");
        	    $this->assign("pid",$pid);
        	}
        	
        }
        /**
         * 发送消息
         * @param unknown $title
         * @param unknown $content
         * @param unknown $uid
         * @return boolean
         */
        function send_message($title,$content,$uid){
        	$message = array();
        	$message['title'] = $title;
        	$message['content'] = $content;
        	$message['uid'] = $uid;
        	$message['create_time'] = time();
            if($GLOBALS['db']->autoExecute(DB_PREFIX."message",$message,"INSERT")){
            	return true;
            }else{
            	return false;
            }
        }
        
        /*
         *
        * 生成拼音
        *
        */
        function getpinyin(){
        	$name = isset($_POST['name']) ? new_addslashes(trim($_POST['name'])) : "";
        	$pinyin = new GetPingYing();
        	$show_pinyin = $pinyin->getAllPY(iconv('utf-8','gbk',$name));
        	if($show_pinyin){
        		echo json_encode(array("pinyin"=>$show_pinyin,"success"=>1));
        	}else{
        		echo json_encode(array("pinyin"=>$show_pinyin,"success"=>-1,"info"=>"生成失败"));
        	}
        }
        
        /*
         * 
         * 导出csv表格
         * 
         */
        function export_csv($filename,$data) {
        	header("Content-type:text/csv");
        	header("Content-Disposition:attachment;filename=".$filename);
        	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        	header('Expires:0');
        	header('Pragma:public');
        	echo $data;
        }
        
        
        
        function sreach($fields){
        	$where = "";
        	$sreach = array();
        	foreach ($_REQUEST as $key => $val){
        		if(in_array($key,$fields)){
        			$where .= " AND ".$key." = '".$val."' ";
        			$sreach[$key] = $val;
        		}
        	}
        	$this->assign("sreach",$sreach);
        	return $where;
        }
        function order($fields,$Key){
        	$orderby = " ORDER BY ";
        	if(in_array($_REQUEST['order'],$fields)){
        		$orderby .= " ".$_REQUEST['order'];
        		if($_REQUEST['desc']){
        			$orderby .= " DESC, ";
        		}else{
        			$orderby .= " ASC, ";
        		}
        	}
        	$orderby .= " ".$Key." DESC ";
        	return $orderby;
        }
        
        public function index($table = ""){
        	$table = $table ? $table : strtolower(MODULE_NAME);
        	$page = $_GET['p']?intval($_GET['p']):1;
        	$perpage = $_REQUEST['perpage'] ? intval($_REQUEST['perpage']) : 20;
        	$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
        	$filed_arr = $GLOBALS['db']->getAll("SHOW FULL FIELDS FROM ".DB_PREFIX.$table);
        	$fields = array();
        	$Key = "";
        	
        	foreach ($filed_arr as $v){
        		$fields[] = $v['Field'];
        		if($v['Key'] == 'PRI'){
        			$Key = $v['Field'];
        			//$orderby = " ORDER BY ".$v['Field']." DESC ";
        		}
        	}
        	$orderby = $this->order($fields,$Key);
        	if(in_array('is_del',$fields)){
        		$where = " WHERE is_del = 0 ";
        	}else{
        		$where = " WHERE 1 = 1 ";
        	}
        	
        	$where .= $this->sreach($fields);
        	
        	$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX.$table." $where $orderby $limit");
        	$count = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX.$table.$where);
        
        	if(method_exists($this, "_after_index")){
        		$list = $this->_after_index($list);
        	}
        	
        	$pages = new Page($count,$perpage);
        	$pages = $pages->show();
        	$this->assign("pages",$pages);
        	$this->assign("perpage",$perpage);
        
        	$this->assign("list",$list);
        
        	$this->display();
        }
        
        
        function add($table = ""){
        	$table = $table ? $table : strtolower(MODULE_NAME);
        	if($_POST){
        		$data = $_POST;
        		if (method_exists($this, '_befor_insert')) {
        			$data = $this->_befor_insert($_POST);
        		}
        		$GLOBALS['db']->autoExecute(DB_PREFIX.$table,$data,"INSERT");
        		if (method_exists($this, '_after_add')) {
        			$id = $GLOBALS['db']->insert_id();
        			$this->_after_add($id);
        		}
        		showMsg(lang("SUCCESS"),ADMIN_URL."/".MODULE_NAME."/index");
        	}
        	if (method_exists($this, '_befor_add')) {
        		$this->_befor_add();
        	}
        	$this->display();
        }
        
        function edit($table = ""){
        	$table = $table ? $table : strtolower(MODULE_NAME);
        	if($_POST){
        		$id = intval($_POST['id']);
        		$detail = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX.$table." WHERE id = ".$id);
        		if(!$detail){
        			url_redirect(ADMIN_URL."/".MODULE_NAME."/index");
        		}
        		$data = $_POST;
        		if (method_exists($this, '_befor_update')) {
        			$data = $this->_befor_update($data);
        		}
        		$GLOBALS['db']->autoExecute(DB_PREFIX.$table,$data,"UPDATE",'id = '.$id);
        		if (method_exists($this, '_after_edit')) {
        			$this->_after_edit($id);
        		}
        		showMsg(lang("SUCCESS"),ADMIN_URL."/".MODULE_NAME."/index");
        	}
        	$id = intval($_GET['id']);
        	$detail = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX.$table." WHERE id = $id");
        	if(!$detail){
        		url_redirect(ADMIN_URL."/".MODULE_NAME."/index");
        	}
        	if (method_exists($this, '_befor_edit')) {
        		$detail = $this->_befor_edit($detail);
        	}
        	$this->assign("id",$id);
        	$this->assign("detail",$detail);
        	$this->display();
        }
        
        function delete($table = ""){
        	$table = $table ? $table : strtolower(MODULE_NAME);
        	$id = intval($_GET['id']);
        	$ids = $_POST['ids'];
        	if(isset($ids)){
        		$id_arr = explode(",",$ids);
        		foreach($id_arr as $k=>$v){
        			if($v==""){
        				unset($id_arr[$k]);
        			}
        		}
        		if(count($id_arr)==0){
        			showMsgajax(lang("SUCCESS"),1);
        		}
        		$sql_del = "UPDATE ".DB_PREFIX.$table." SET is_del = 1 WHERE id in ($ids)";
        		$GLOBALS['db']->query($sql_del);
        		showMsgajax(lang("SUCCESS"),1);
        	}else{
        		$sql_del = "UPDATE ".DB_PREFIX.$table." SET is_del = 1 WHERE id = $id";
        		$GLOBALS['db']->query($sql_del);
        		showMsg(lang("SUCCESS"),ADMIN_URL."/".MODULE_NAME."/index");
        	}
        }
    }
    
    
    
?>
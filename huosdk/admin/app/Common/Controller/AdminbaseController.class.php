<?php

/**
 * 后台Controller
 */
namespace Common\Controller;
use Common\Controller\AppframeController;

class AdminbaseController extends AppframeController {
	protected $role_type, $agentwhere, $row;
	public function __construct() {
		$admintpl_path=C("SP_ADMIN_TMPL_PATH").C("SP_ADMIN_DEFAULT_THEME")."/";
		C("TMPL_ACTION_SUCCESS",$admintpl_path.C("SP_ADMIN_TMPL_ACTION_SUCCESS"));
		C("TMPL_ACTION_ERROR",$admintpl_path.C("SP_ADMIN_TMPL_ACTION_ERROR"));
		parent::__construct();
		$time=time();
		$this->assign("js_debug",APP_DEBUG?"?v=$time":"");
	}

    function _initialize(){
        parent::_initialize();
        $this->load_app_admin_menu_lang();
        if(isset($_SESSION['ADMIN_ID'])){
        	$users_obj= M("Users");
        	$id = get_current_admin_id();
        	$user=$users_obj->where("id=$id")->find();
        	if(!$this->check_access($id)){
        		$this->error("您没有访问权限！");
        		exit();
        	}
        	
        	$this->role_type = sp_get_current_roletype();
        	$this->agentwhere = " >=0 ";
        	if (3 == $this->role_type){
        	    $userids = $this->_getOwnerAgents();
        	    $this->agentwhere = " in (".$userids.") ";
        	} elseif (4 == $this->role_type) {
        	    $this->agentwhere = "=".sp_get_current_admin_id();
        	}
        	$this->row = 10;
        	$this->assign("admin",$user);
        }else{
        	//$this->error("您还没有登录！",U("admin/public/login"));
        	if(IS_AJAX){
        		$this->error("您还没有登录！",U("admin/public/login"));
        	}else{
        		header("Location:".U("admin/public/login"));
        		exit();
        	}
        }
    }
    
    /**
     * 初始化后台菜单
     */
    public function initMenu() {
        $Menu = F("Menu");
        if (!$Menu) {
            $Menu=D("Common/Menu")->menu_cache();
        }
        return $Menu;
    }

    /**
     * 消息提示
     * @param type $message
     * @param type $jumpUrl
     * @param type $ajax 
     */
    public function success($message = '', $jumpUrl = '', $ajax = false) {
        $this->insertLog(1, $message);
        parent::success($message, $jumpUrl, $ajax);
    }
    
    /**
     * 消息提示
     * @param type $message
     * @param type $jumpUrl
     * @param type $ajax
     */
    public function error($message = '', $jumpUrl = '', $ajax = false) {
        $this->insertLog(2, $message);
        parent::error($message, $jumpUrl, $ajax);
    }

    /**
     * 模板显示
     * @param type $templateFile 指定要调用的模板文件
     * @param type $charset 输出编码
     * @param type $contentType 输出类型
     * @param string $content 输出内容
     * 此方法作用在于实现后台模板直接存放在各自项目目录下。例如Admin项目的后台模板，直接存放在Admin/Tpl/目录下
     */
    public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
        parent::display($this->parseTemplate($templateFile), $charset, $contentType);
    }
    
    /**
     * 获取输出页面内容
     * 调用内置的模板引擎fetch方法，
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $content 模板输出内容
     * @param string $prefix 模板缓存前缀*
     * @return string
     */
    public function fetch($templateFile='',$content='',$prefix=''){
        $templateFile = empty($content)?$this->parseTemplate($templateFile):'';
		return parent::fetch($templateFile,$content,$prefix);
    }
    
    public function verifyPaypwd($password){
        $user_model = M('users');
        $uid=get_current_admin_id();
        $admin=$user_model->where(array("id"=>$uid))->find();
        $password=pay_password($password,C('AUTHCODE'));
        if($admin['pay_pwd'] != $password){
            $this->error("二级密码错误");
            exit;
        }
        return TRUE;
    }
    
    
    
    
    /**
     * 自动定位模板文件
     * @access protected
     * @param string $template 模板文件规则
     * @return string
     */
    public function parseTemplate($template='') {
    	$tmpl_path=C("SP_ADMIN_TMPL_PATH");
    	define("SP_TMPL_PATH", $tmpl_path);
		// 获取当前主题名称
		$theme      =    C('SP_ADMIN_DEFAULT_THEME');
		
		if(is_file($template)) {
			// 获取当前主题的模版路径
			define('THEME_PATH',   $tmpl_path.$theme."/");
			return $template;
		}
		$depr       =   C('TMPL_FILE_DEPR');
		$template   =   str_replace(':', $depr, $template);
		
		// 获取当前模块
		$module   =  MODULE_NAME."/";
		if(strpos($template,'@')){ // 跨模块调用模版文件
			list($module,$template)  =   explode('@',$template);
		}
		// 获取当前主题的模版路径
		define('THEME_PATH',   $tmpl_path.$theme."/");
		
		// 分析模板文件规则
		if('' == $template) {
			// 如果模板文件名为空 按照默认规则定位
			$template = CONTROLLER_NAME . $depr . ACTION_NAME;
		}elseif(false === strpos($template, '/')){
			$template = CONTROLLER_NAME . $depr . $template;
		}
		
		C("TMPL_PARSE_STRING.__TMPL__",__ROOT__."/".THEME_PATH);
		
		C('SP_VIEW_PATH',$tmpl_path);
		C('DEFAULT_THEME',$theme);
		define("SP_CURRENT_THEME", $theme);
		
		$file = sp_add_template_file_suffix(THEME_PATH.$module.$template);
		$file= str_replace("//",'/',$file);
		if(!file_exists_case($file)) E(L('_TEMPLATE_NOT_EXIST_').':'.$file);
		return $file;
    }

    /**
     *  排序 排序字段为listorders数组 POST 排序字段为：listorder
     */
    protected function _listorders($model) {
        if (!is_object($model)) {
            return false;
        }
        $pk = $model->getPk(); //获取主键名称
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['listorder'] = $r;
            $model->where(array($pk => $key))->save($data);
        }
        return true;
    }

    /**
     * 后台分页
     * 
     */
    protected function page($total_size = 1, $page_size = 0, $current_page = 1, $listRows = 6, $pageParam = '', $pageLink = '', $static = FALSE) {
        if ($page_size == 0) {
            $page_size = C("PAGE_LISTROWS");
        }
        
        if (empty($pageParam)) {
            $pageParam = C("VAR_PAGE");
        }
        
        $Page = new \Page($total_size, $page_size, $current_page, $listRows, $pageParam, $pageLink, $static);
        $Page->SetPager('Admin', '{first}{prev}&nbsp;{liststart}{list}{listend}&nbsp;{next}{last}', array("listlong" => "9", "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => ""));
        return $Page;
    }

    private function check_access($uid){
    	//如果用户角色是1，则无需判断
    	if($uid == 1){
    		return true;
    	}
    	
    	$rule=MODULE_NAME.CONTROLLER_NAME.ACTION_NAME;
    	$no_need_check_rules=array("AdminIndexindex","AdminMainindex");
    	
    	if( !in_array($rule,$no_need_check_rules) ){
    		return sp_auth_check($uid);
    	}else{
    		return true;
    	}
    }
	
	//支付密码
	public function pay_password($pw, $pre){
		$decor=md5($pre);
		$mi=md5($pw);
		return substr($decor,0,12).$mi.substr($decor,-4,4);
	}
	
	
	function _game($option=true,$status=NULL, $is_delete=NULL,$is_sdk=NULL, $is_app=NULL){
	    $cates=array(
	            "0"=>"选择游戏",
	    );
		if ($status){
			$where['status'] = 2;
		}
	    
		if($is_delete){
			$where['is_delete'] =2;
		}
		if($is_sdk){
			$where['is_own'] = 2;
		}
		if($is_app){
			$where['is_app'] =2;
		}
		
	    if (3 <= $this->role_type) {
	        $agents = $this->_getOwnerAgents();
	        $apparr = M('agent_game')->where(array('agent_id'=>array('in', $agents)))->getField('app_id', true);
	        if ($apparr){
				$where['id'] = array('in', implode(',',$apparr));
			}
	    }
	    
	    $games = M('game')->where($where)->getField("id,name gamename", true);
	    if($option && $games){
	        $games= $cates + $games;
	    }

	    $this->assign("games",$games);
		return $games;
	}
	
	function _game_type($option=true){
	    $cates=array(
	            "0"=>"全部类型",
	    );
	    
	    $where['status'] = 2;
	    $gametypes = M('game_type')->where($where)->getField("id,name gametype", true);
	    if($option && $gametypes){
	        $gametypes=$cates + $gametypes;
	    }
	    
	    $this->assign("gametypes",$gametypes);
	}
	
	function _agents($agent_id, $option=true){
	    $cates=array(
	            "0"=>"全部渠道"
	    );
	    
	    if (empty($agent_id)){
	        $agent_id = sp_get_current_admin_id();
	    }	    
	    $where['user_type'] = array('GT', '1');
	    $roletype = $this->_get_role_type($agent_id);
	    if (3 <= $this->role_type) {
	        $aidstr = $this->_getOwnerAgents($agent_id);
	        $where['id'] = array('in', $aidstr);
	    }
	   
	    $agents = M('users')->where($where)->getField("id,user_login agentname", true);

	    if($option && $agents){
	        $agents=$cates+$agents;
	    }
	    
	    $this->assign("agents",$agents);
		return $agents;
	}
	
	
	function _roles($type=NULL, $option=TRUE){
	    $cates=array(
	            "0"=>"全部"
	    );
	    $where = "status=1";
	    if ($type){
	        $where .= " AND role_type >= ".$type;
	    }
	    $roles = M('role')->where($where)->getField("id,name", true);
	    if($option && $roles){
	        $roles=$cates+$roles;
	    }

	    $this->assign("roles",$roles);
		return $roles;
	}
	
	function _roletypes($type=NULL, $option=TRUE){
	    $cates=array(
	            "0"=>"全部",
	            "1"=>"超级管理员",
	    );
	    $roletypes=array(
	            "2"=>"平台人员",
	            "3"=>"渠道市场",
	            "4"=>"渠道"
	    );
	    
	    if($option){
	        $roletypes=$cates+$roletypes;
	    }
	     
	    $this->assign("roletypes",$roletypes);
	}

	function _getOwnerAgents($userid = NULL){
	    if (empty($userid)){
	        $userid = sp_get_current_admin_id();
	    }

	    $usersids = M('users')->where("ownerid=$userid OR id=$userid")->getField("id", true);
		$idstr = implode(',',$usersids);
	    return $idstr;
	}
	
	function _get_role_type($userid = NULL){
	    if (empty($userid)){
	        $userid = sp_get_current_admin_id();
	    }
	    
        $role_user_model=M("RoleUser");
        $role_user_join = C('DB_PREFIX').'role as b on a.role_id =b.id';
        $role_type=$role_user_model->alias("a")->join($role_user_join)->where(array("user_id"=>$userid,"status"=>1))->getField("min(role_type)");
        return $role_type;
	}
	
	function _mem_status(){
	    $cates=array(
	            1 => "试玩",
	            2 => "正常",
	            3 => "冻结"
	    );
	    $this->assign("memstatus",$cates);
	}
    
    private function load_app_admin_menu_lang(){
    	if (C('LANG_SWITCH_ON',null,false)){
    		$admin_menu_lang_file=SPAPP.MODULE_NAME."/Lang/".LANG_SET."/admin_menu.php";
    		if(is_file($admin_menu_lang_file)){
    			$lang=include $admin_menu_lang_file;
    			L($lang);
    		}
    	}
    }
	
    /**
     * 图片上传类
     * @date: 2016年4月9日上午11:26:50
     * @param NULL
     * @return NULL
     * @since 1.0
     */
    public function upload($up_info, $savePath, $name){
        $upload           = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->exts     = array('jpg', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = C('UPLOADPATH').$savePath.'/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        $upload->saveName = $name;
        $upload->autoSub  = false;
        $upload->replace  = true;
    
        $info = $upload->uploadOne($up_info);
    
        /* 上传错误提示错误信息 */
        if(!$info) {
            $return['status'] = 0;
            $return['msg'] = $upload->getError();
        }else{
            /* 上传成功 */
            $return['status'] = 1;
            $return['msg'] = $info['savepath'] . $info['savename'];
        }
        return $return;
    }
    
    
    /**
     * 图片上传类
     * @date: 2016年4月9日上午11:26:50
     * @param NULL
     * @return NULL
     * @since 1.0
     */
    public function uploads($up_info, $savePath, $name){
        $name_array = array('uniqid');
        foreach($up_info['name'] as $key=>$val){
            $saveName = $name.'_'.time().'_';
            array_push($name_array,$saveName);
        }
        $upload           = new \Think\Upload();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->exts     = array('jpg', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = C('UPLOADPATH').$savePath.'/'; // 设置附件上传根目录
        $upload->savePath = ""; // 设置附件上传（子）目录
    
        $upload->saveName = $name_array;
        $upload->replace  = true;
        $upload->autoSub  = false;
    
        $info = $upload->upload($up_info);
    
        /* 上传错误提示错误信息 */
        if(!$info) {
            $return['status'] = 0;
            $return['msg'] = $upload->getError();
        }else{
            /* 上传成功 */
            $return['status'] = 1;
            $return['msg'] = '';
            foreach($info as $file){
                $return['msg'] = $return['msg'].','.$file['savename'];
            }
            $return['msg'] = substr($return['msg'], 1);
        }
        return $return;
    }
    
	function _authPaypwd($repwd){
        if (empty($repwd)){
            $repwd = I('post.repwd');
        }
		if(empty($repwd)){
			$this->error("请输入二级密码！");
    		exit;
		}
		$user_obj = D("Common/Users");
		$uid=get_current_admin_id();
		$admin=$user_obj->where(array("id"=>$uid))->find();
			
		$repwd = sp_password($repwd);
			
		if($admin['pay_pwd'] != $repwd){
			$this->error("二级密码错误,操作失败！");
    		exit;
		}
	}

	//操作类型，1操作成功, 2 操作失败
	protected function insertLog($type=0,$remark){
	    $userid = sp_get_current_admin_id();
	    if (empty($userid)){
	        $userid = 0;
	    }
	    $data['user_id'] = $userid;
	    $data['username'] = $_SESSION['name'];
	    $data['action'] = MODULE_NAME."-".CONTROLLER_NAME."-".ACTION_NAME;
	    $data['create_time'] =time();
	    $data['type'] = $type;
	    $data['ip'] = get_client_ip();
	    $data['addr'] = get_ip_attribution($data['ip']);
	    $data['param'] = 'GET:'.json_encode($_GET).'; POST:'.json_encode($_POST);
	    $data['remark'] = $remark;
	    
	    $result = M('admin_operate_log')->add($data); // 写入数据到数据库
	    return $result;
	}
	
	//type 0 表示登陆 1表示已经登陆,1输入网址再次登陆,2表示登出
	protected function admin_login_log($type=0, $last_login_ip=NULL){
	    if(empty($last_login_ip)){
	        $last_login_ip = get_client_ip();
	    }
	    $adminlog['type'] =$type;
	    $adminlog['user_id'] = get_current_admin_id();
	    $adminlog['ip'] = $last_login_ip;
	    $adminlog['deviceinfo'] = $_SERVER["HTTP_USER_AGENT"];
	    $adminlog['login_time'] = time();
	    $adminlog['addr'] = get_ip_attribution($last_login_ip);
	    M('admin_login_log')->add($adminlog);
	}
	
    /*
	**xls导出
	*/
	public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $xlsTitle.date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel");
		vendor("PHPExcel.IOFactory");
       
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
       // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));  
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
        } 
          // Miscellaneous glyphs, UTF-8  
        for($i=0;$i<$dataNum;$i++){
          for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
          }             
        }  
        
		ob_clean();
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
		header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;   
    }
}
<?php
/**
* MobilebaseController.class.php UTF-8
* APP移动端
* @date: 2016年9月6日下午4:31:02
* @license 这不是一个自由软件，未经授权不许任何使用和传播。
* @author: wuyonghong <wyh@huosdk.com>
* @version: ANDROID 7.0
*/
namespace Common\Controller;
use Common\Controller\AppframeController;

class MobilebaseController extends AppframeController {
    
    protected $dbname, $row;
    protected $request, $agent, $client_id, $app_id, $mem_id, $client_key, $agent_id;
    protected $staticurl;
    protected $downurl;
    protected $apiurl;
    
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
        if(empty($_SESSION['user'])){
            $this->agent = I('param.agent/s', 'default');
            $this->agent_id = $this->getAgentid($this->agent);
            $this->app_id = I('param.appid/d', 0);
            $this->client_id = I('param.clientid/d', 0);
            $this->client_key = $this->getClientkey($this->client_id);
            $this->mem_id = $this->verifyUser();
            $_SESSION['user'] = M('members')->where(array('id'=>$this->mem_id))->find();
        }
        $this->isUserLogin();
    }
    
    //操作成功跳转页面
    public function ac_success($title, $msg){
        $this->assign('title', $title);
        $this->assign('msg', $msg);
        $this->display(':ac_success');
        exit;
    }
    
    //操作成功跳转页面
    public function ac_error($title, $msg){
        $this->assign('title', $title);
        $this->assign('msg', $msg);
        $this->display(':ac_error');
        exit;
    }
    
    /**
     * 消息提示
     * @param type $message
     * @param type $jumpUrl
     * @param type $ajax 
     */
    public function success($message = '', $jumpUrl = '', $ajax = false) {
        parent::success($message, $jumpUrl, $ajax);
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

	function _getGames(){
	    $cates=array(
	            "0"=>"全部游戏",
	    );
	    $games = M("game")->where("client_status=2")->getField("id,name gamename", true);
	    if($games){
	        $games=$cates + $games;
	    }else{
	        $games=$cates;
	    }
	    $this->assign("games",$games);
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
    
	function _authPaypwd($repwd){
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
	
	private function verifyUser() {
	    $hs_token = hs_auth_code($this->get_header('hs-token'), 'DECODE', $this->client_key);
	    $tokenobj = json_decode($hs_token);
	    if (empty($tokenobj)) {
	        return 0;
	    }
	    $tokenarr['identifier'] = hs_auth_code($tokenobj->identify, 'DECODE', $this->client_key);
	    $tokenarr['accesstoken'] = hs_auth_code($tokenobj->accesstoken, 'DECODE', $this->client_key);
	
	    if (empty($tokenarr['identifier'])) {
	        // identifier 不存在
	        return 0;
	    }
	    $map['identifier'] = $tokenarr['identifier'];
	    $mxt_info = M('mem_ext')->where($map)->find();
	    if (0 != strcmp($mxt_info['accesstoken'], $tokenarr['accesstoken'])) {
	        // 用户非法
	        return -1;
	    }
	
	    return $mxt_info['mem_id'];
	}
	
	//通过渠道名称获取渠道编号
	protected function getAgentid($agentname) {
	    if (empty($agentname) || 'default' == $agentname) {
	        return 0;
	    }
	
	    $agent_id = M('users')->where(array(
	        'user_login' => $agentname
	    ))->getField('id');
	    if (empty($agent_id)) {
	        return 0;
	    }
	
	    return $agent_id;
	}
	
	// 判断玩家是否登录
	protected function isUserLogin() {
	    if (empty($_SESSION['user']) || $_SESSION['user']['id'] <= 0) {
	        $this->error("玩家未登陆,或登录失效!");
	    }
	}
}
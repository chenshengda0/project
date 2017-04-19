<?php
namespace Common\Controller;
use Think\Controller;

class AppframeController extends Controller {

    function _initialize() {
        $this->assign("waitSecond", 3);
       	$time=time();
        $this->assign("js_debug",APP_DEBUG?"?v=$time":"");
        
        //获取客服的联系方式
        $contact_data = getcontact();
        $hotline = $contact_data['tel'];
        $qq = $contact_data['qq'];
        $email = $contact_data['email'];       
        $this->assign("HOTLINE",$hotline);
        $this->assign("EMAIL",$email);
        $this->assign("QQ", $qq);
        
        //分配广告信息
        $logo = getGuanggao(4);
        $inner = getGuanggao(5);
        $splash = getGuanggao(6);
        $this->assign("splash", $splash);
        $this->assign("inner", $inner);
        $this->assign('logo',$logo);
        
         //分配常量信息
        $this -> assign("SECOND_DOMAIN", C('SECOND_DOMAIN'));
        $this -> assign("WEBSITE",__ROOT__.'/public');
        $this -> assign("WEBINDEX",C('WEBINDEX'));
        $this -> assign("MWEBSITE",C('MWEBSITE'));
        $this -> assign("TGWEBSITE",C('TGWEBSITE'));
        $this -> assign("PAYWEBSITE",C('PAYWEBSITE'));
        $this -> assign("BBSWEBSITE",C('BBSWEBSITE'));
        $this -> assign("keywords","手机游戏，手机游戏推广，手游公会联盟");
        $this -> assign("description",C('BRAND_NAME')."提供最新最好玩的手机游戏下载，首家全民手游充值返利平台及手机游戏公会推广联盟，最新最热的手机游戏下载排行榜评测，关注游戏玩家利益，助您畅玩手游。");
        $this -> assign("title",C('BRAND_NAME')."|最新最好玩的手机游戏下载排行榜_手游公会联盟_手游CPS公会推广渠道及联运发行合作平台");
        $this -> assign("WEB_ICP", C('WEB_ICP'));
        $this -> assign("BRAND_NAME",C('BRAND_NAME'));
        $this -> assign("CURRENCY_NAME",C('CURRENCY_NAME')); 
        if(APP_DEBUG){
        }
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @return void
     */
    protected function ajaxReturn($data, $type = '',$json_option=0) {
        
        $data['referer']=$data['url'] ? $data['url'] : "";
        $data['state']=$data['status'] ? "success" : "fail";
        
        if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
        switch (strtoupper($type)){
        	case 'JSON' :
        		// 返回JSON数据格式到客户端 包含状态信息
        		header('Content-Type:application/json; charset=utf-8');
        		exit(json_encode($data,$json_option));
        	case 'XML'  :
        		// 返回xml格式数据
        		header('Content-Type:text/xml; charset=utf-8');
        		exit(xml_encode($data));
        	case 'JSONP':
        		// 返回JSON数据格式到客户端 包含状态信息
        		header('Content-Type:application/json; charset=utf-8');
        		$handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
        		exit($handler.'('.json_encode($data,$json_option).');');
        	case 'EVAL' :
        		// 返回可执行的js脚本
        		header('Content-Type:text/html; charset=utf-8');
        		exit($data);
        	case 'AJAX_UPLOAD':
        		// 返回JSON数据格式到客户端 包含状态信息
        		header('Content-Type:text/html; charset=utf-8');
        		exit(json_encode($data,$json_option));
        	default :
        		// 用于扩展其他返回格式数据
        		Hook::listen('ajax_return',$data);
        }
        
    }
    
    //分页
    protected function page($Total_Size = 1, $Page_Size = 0, $Current_Page = 1, $listRows = 6, $PageParam = '', $PageLink = '', $Static = FALSE) {
    	import('Page');
    	if ($Page_Size == 0) {
    		$Page_Size = C("PAGE_LISTROWS");
    	}
    	if (empty($PageParam)) {
    		$PageParam = C("VAR_PAGE");
    	}
    	$Page = new \Page($Total_Size, $Page_Size, $Current_Page, $listRows, $PageParam, $PageLink, $Static);
    	$Page->SetPager('default', '{first}{prev}{liststart}{list}{listend}{next}{last}', array("listlong" => "9", "first" => "首页", "last" => "尾页", "prev" => "上一页", "next" => "下一页", "list" => "*", "disabledclass" => ""));
    	return $Page;
    }

    //空操作
    public function _empty() {
        $this->error('该页面不存在！');
    }
    
    /**
     * 检查操作频率
     * @param int $duration 距离最后一次操作的时长
     */
    protected function check_last_action($duration){
    	
    	$action=MODULE_NAME."-".CONTROLLER_NAME."-".ACTION_NAME;
    	$time=time();
    	
    	if(!empty($_SESSION['last_action']['action']) && $action==$_SESSION['last_action']['action']){
    		$mduration=$time-$_SESSION['last_action']['time'];
    		if($duration>$mduration){
    			$this->error("您的操作太过频繁，请稍后再试~~~");
    		}else{
    			$_SESSION['last_action']['time']=$time;
    		}
    	}else{
    		$_SESSION['last_action']['action']=$action;
    		$_SESSION['last_action']['time']=$time;
    	}
    }

}
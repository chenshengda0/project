<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;

class BbsController extends AdminbaseController{
	//跳转到指定BBS地址
	public function index(){
	    define('UC_CLIENT_PATH', SITE_PATH . 'thinkphp/Core/Library/Vendor/uc_client');
	    require_cache(SITE_PATH . '/conf/uc_config.php');
	    if (!defined('UC_API')) {
	        E('未发现uc配置文件');
	    }
	    require_cache(UC_CLIENT_PATH.'/client.php'); // 加载uc客户端主脚本
	    
	    if(C("UCENTER_ENABLED")){
	        list($uid, $username, $password, $email) = uc_user_login($username, $password);
	    
	        if($uid > 0){
	            echo uc_user_synlogin($uid);
	        }
	    }
	    $url = BBSSITE;
	    header("Location: ".$url);
	}
}
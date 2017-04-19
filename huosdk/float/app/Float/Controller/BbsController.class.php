<?php
namespace Float\Controller;
use Common\Controller\AdminbaseController;

class BbsController extends AdminbaseController{
	//跳转到指定BBS地址
	public function index(){
	    $url = BBSSITE;
	    header("Location: ".$url);
	}
}
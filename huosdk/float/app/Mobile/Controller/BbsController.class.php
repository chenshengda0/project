<?php
namespace Mobile\Controller;
use Common\Controller\MobilebaseController;

class BbsController extends MobilebaseController{
	//跳转到指定BBS地址
	public function index(){
	    $url = BBSSITE;
	    header("Location: ".$url);
	}
}
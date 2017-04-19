<?php

/**
 * 关于我们
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class AboutUsController extends HomebaseController {

    public function index(){
		$show = I('show','');
		if($show == 'us'){
			$id = 1;
			$data = getAboutus($id);
			$this -> assign("data",$data);
			$this -> display('about_us');
		}else if($show == 'hezuo'){
			$id = 2;
			$data = getAboutus($id);
			$this -> assign("data",$data);
			$this -> display('about_hz');
		}else if($show == 'zhaopin'){
			$id = 3;
			$data = getAboutus($id);
			$this -> assign("data",$data);
			$this -> display('about_zp');
		}else if($show == 'lianxi'){
			$id = 4;
			$data = getAboutus($id);
			$this -> assign("data",$data);
			$this -> display('about_lx');
		}
    }
}
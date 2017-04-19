<?php

/**
 * 游戏子站
 */
namespace Web\Controller;

use Common\Controller\HomebaseController;

class SubstationController extends HomebaseController {
	function _initialize() {
		parent::_initialize ();
	}
	public function index() {
		$gamehost = WEBSITE;
		$gameid = I('gameid');
		$this->assign("gamehost",$gamehost);
		
		$webwhere["is_delete"] = 2;
		$webwhere["app_id"] = $gameid;
		$webdata = M("game_subsite")->where($webwhere)->find();
		
		$field = "a.id,a.name,b.iosurl,b.androidurl, b.size,b.iosxt,b.yiosurl,b.adxt";
		$wheredate['a.is_delete']= 2;
		$wheredate['a.id']= $gameid;
		$join = "c_game_info b ON a.id=b.app_id";
		
		$infodata = M('game')
		->alias('a')
		->field($field)
		->where($wheredate)
		->join($join)
		->find();
		
		$versionldate = M("gameVersion")->field("app_id,version")->select();
		foreach ($versionldate as $val){
			$version[$val['app_id']] = $val["version"];
		}
		$this->assign("version",$version);
		// 生成的文件名ios
		$addate = $infodata ['iosurl'];
		if ($infodata ['iosurl'] == '') {
			$addate = WEBSITE;
		}
		
		$iosname = $gameid . "ios.png";
		$fileurl = C('UPLOADPATH')."code/".$iosname;
		getcodeimage($addate,$fileurl);
		$gamelist[$key]['ioscode'] = $iosname;
		
		// 生成的文件名android
		$addate = $infodata ['androidurl'];
		if ($infodata ['androidurl'] == '') {
			$addate = WEBSITE;
		}
		
		$androidname = $gameid . "android.png";
		$fileurl = C('UPLOADPATH')."code/".$androidname;
		
		getcodeimage($addate,$fileurl);
		$gamelist[$key]['adcode'] = $androidname;
		$this->assign("gamelist",$gamelist);
		
		$field = "id,title,image,create_time,total,is_top";
		$newswhere['app_id'] = $gameid;
		$newswhere['is_delete'] = 2;
		
		$newsdata = M("web_article")->field($field)->where($newswhere)->order("is_top DESC , id DESC")->limit(6)->select();
		$this->assign("newsdata",$newsdata);
		
		$arimage = explode ( ",", $webdata ['lunbotu'] );
		$glimage = explode ( ",", $webdata ['gongluetu'] );
		$this->assign("infodata",$infodata);
		$this->assign("iosname",$iosname);
		$this->assign("androidname",$androidname);
		$this->assign("webdata",$webdata);
		$media_data = M("web_media")->select();
		$this->assign("arimage",$arimage);
		$this->assign("glimage",$glimage);
		$this->assign("media_data",$media_data);
		
		//获取客服联系方式
		$serverContact = M('gameContact')->find();
		$this->assign('HOTLINE',$serverContact['tel']);
		$this->assign('QQ',$serverContact['qq']);
		$this->assign('EMAIL',$serverContact['email']);
		$this->display ();
	}
}
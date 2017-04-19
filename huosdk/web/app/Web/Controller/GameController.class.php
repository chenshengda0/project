<?php

/**
 * 游戏中心控制器
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class GameController extends HomebaseController {
	function _initialize() {
		parent::_initialize ();
	}

    public function index(){
        $this->_gameList();
    }

    public function _gameList(){
        $show = I('show');
        $gameid = I('gameid');
        $typeid = I('typeid');
        $tzgid = I('tzgid','');//首页游戏图标对应游戏ID
        $where = " status = 2 AND is_delete = 2 "; //查询条件
        
        if($typeid > 0){
            $where .= " AND type='{$typeid}'";
        }
		
        //搜索到指定游戏信息
        if($tzgid){
            $where .= " AND id='{$tzgid}'";
        }
        
        //根据类型查询游戏
        $list = findgamelist($where);
        $gamelist = $list['list'];
		
        //查询游戏类型
        $typelist = gametype();
        
        //遍历游戏类型
        foreach ($typelist as $key => $val) {
            $gametype[$val['id']] = $val['name'];
        }
        
        //遍历游戏种类
        foreach ($gamelist as $key=>$val) {
            $gamelist[$key]['typename'] = $gametype[$val['type']];
        }
        
        //添加游戏详细信息
        $gameinfolist = gameinfolist();

        foreach ($gameinfolist as $key => $val) {
            $urldata[$val['app_id']] = $val;
        }

		//导入二维码生成函数库
		Vendor(phpqrcode.phpqrcode);
		
        foreach ($gamelist as $key=>$val) {
            $gamelist[$key]['url'] = $urldata[$val['id']]['url'];
            $gamelist[$key]['iosurl'] = $urldata[$val['id']]['iosurl'];
            $gamelist[$key]['androidurl'] = $urldata[$val['id']]['androidurl'];
            $gamelist[$key]['description'] = $urldata[$val['id']]['description'];
            $gamelist[$key]['bigimage'] = $urldata[$val['id']]['bigimage'];
            	
            //ios下载二维码图片
            $madate = $urldata[$val['id']]['iosurl'];
            // 生成的文件名
            $filename = $val['id']."ios.png";

            $fileurl = WEBSITE."/public/web/images/code/".$filename;
            if($madate != ''){
                getcodeimage($madate,$fileurl);
                $gamelist[$key]['ioscode'] = $filename;
            }else{
                $gamelist[$key]['ioscode'] = '';
            }
            	
            //android下载二维码图片
            $madate = $urldata[$val['id']]['androidurl'];

            // 生成的文件名
            $filename = $val['id']."android.png";

            $fileurl = C('UPLOADPATH')."code/".$filename;
            if($madate != ''){
                getcodeimage($madate,$fileurl);
                $gamelist[$key]['adcode'] = $filename;
            }else{
                $gamelist[$key]['adcode'] = '';
            }
        }
        $gamelist = array_filter($gamelist);
        $this->assign('gamelist',$gamelist);
        
        //热门游戏列表清单
        $hotgamelist = hotgamelist();
        $this -> assign("footgamelist", $hotgamelist);
        
        //获取首页广告大图
        $gameimg = getGuanggao(2);
        $this->assign('gameimg',$gameimg);
        
        if($show == 'display'){
            $gameinfo = gameinfo($gameid);
            $arimage = explode(",",$gameinfo[0]['smallimage']);
            	
            $this->assign('gameinfo',$gameinfo);
            $this->assign('arimage',$arimage);
            $this->display('game_display');
        }else{
            $showpage = $list['showpage'];
            $this->assign('typelist',$typelist);
        
            if($tzgid > 0){
                $this->assign('typeid',$gamelist[0]['type']);
            }else{
                $this->assign('typeid',$typeid);
            }
            $this->assign('showpage',$showpage);
            $this->display();
        }
        
    }
}
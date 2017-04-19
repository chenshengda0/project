<?php
/**
 * 前台首页
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class IndexController extends HomebaseController {
  
    function _initialize() {
        parent::_initialize();
    }

    public function index(){
		$this->_indexdata();
        //显示首页
        $this->display();
    }
	
	//获取首页数据
	public function _indexdata(){
		//获取用户名和ID
        $username = $_SESSION['user.sdkuser'];
        $userid = $_SESSION['user.xsst_id'];
        
	    //获取广告图片
        $guanggao = getGuanggao(1);
        $this->assign('guanggao',$guanggao);  //图片

		//热门游戏列表清单
		$hotgamelist = hotgamelist(); 
		$this -> assign("footgamelist", $hotgamelist);

        //获取服务器信息
        $this->getServerInfo();
        
        //获取游戏列表清单数据
        $data = $this->getListInfo();
       
        $this->assign('data',$data);
        
        //获取游戏信息
        $this->getGameInfo();
        
        //获取新闻信息
        $newslist = $this->getNewInfo();
        $this->assign('newslist',$newslist);
        
        //添加活动公告
        $huodonglist = $this->getActivityInfo();
        $this->assign('hdlist',$huodonglist);
        
		//获取友情链接
		$friendlink = getFriendLink();
		$this->assign('friendlink',$friendlink);
        
		//获取媒体列表
		$media = getMedia();
		$this->assign('media',$media);
        
		//获取首页相关素材
		$indexdata = indexinfo();
        $this->assign('indexdata',$indexdata);
        
        //获取首页轮播图片
        $arrimage = explode(",",$indexdata['lunbotu']);
        $this->assign('arrimage',$arrimage);
		
		//获取客服联系方式
		$serverContact = M('gameContact')->find();
		$this->assign('HOTLINE',$serverContact['tel']);
		$this->assign('QQ',$serverContact['qq']);
	}
            
   //获取服务器信息
    private function getServerInfo(){
        //开服信息
        $gamelist = gamelist();  //游戏列表
        $serverlist = serverlist(); //开服信息列表
        $gameinfolist = gameinfolist();//游戏描述列表
        foreach ($gamelist as $key => $val) {
            $gamedata[$val['id']] = $val['name'];
        }

        foreach ($serverlist as $key=>$val) {
            $serverlist[$key]['game'] = $gamedata[$val['app_id']]; //服务列表添加游戏数据
        }

        foreach ($gameinfolist as $key => $val) {
            $ginfodata[$val['app_id']] = $val['androidurl'];
            $urldata[$val['app_id']] = $val['url'];
        }
		
        foreach ($serverlist as $key=>$val) {
            $serverlist[$key]['androidurl'] = $ginfodata[$val['app_id']]; //服务列表添加下载路径
            $serverlist[$key]['url'] = $urldata[$val['app_id']]; //服务列表添加链接路径
			//$serverlist[$key]['game'] = mb_substr($serverlist[$key]['game'],0,4,'utf-8');//截取4个中文字符保证首页样式不变
        }

        $this->assign('serverlist',$serverlist);
    }
    
    //获取游戏信息
    private function getGameInfo(){
         
        $gamelist = gamelist();  //游戏列表
        $gametypelist = gametype();  //游戏类型列表
        $gameinfolist = gameinfolist();//游戏描述列表

        foreach ($gametypelist as $key => $val) {
            $gametype[$val['id']] = $val['name'];
        }

        foreach ($gameinfolist as $key => $val) {
            $infodata[$val['app_id']]['url'] = $val['url'];
            $infodata[$val['app_id']]['mobile_icon'] = $val['mobile_icon'];
            $infodata[$val['app_id']]['bigimage'] = $val['bigimage'];
            $infodata[$val['app_id']]['publicity'] = $val['publicity'];
        }
        //添加游戏详细信息
        foreach ($gamelist as $key=>$val) {
			//获取游戏名的首字母
			$ret = getgamefirstchar($val['name']);
            //dump($infodata[$val['id']]['url']);
            $gamelist[$key]['ucfirst'] = $ret;
            $gamelist[$key]['typename'] = $gametype[$val['type']]; //游戏列表添加类型数据
            $gamelist[$key]['url'] = $infodata[$val['id']]['url']; //游戏列表添加链接路径
            $gamelist[$key]['mobile_icon'] = $infodata[$val['id']]['mobile_icon']; //游戏的图片
            $gamelist[$key]['bigimage'] = $infodata[$val['id']]['bigimage'];
            $gamelist[$key]['publicity'] = $infodata[$val['id']]['publicity'];
            
			//游戏中图片不存在或链接不存在的数据赋值为空
			if(empty($gamelist[$key]['url']) || empty($gamelist[$key]['mobile_icon'])){
				$gamelist[$key] = null;
			}
		}

		//去除数组中为空的数据
		$gamelist = array_filter($gamelist);
        //分配数据
        $this->assign('gametypelist',$gametypelist);
        $this->assign('gamelist',$gamelist);

    }
    
    //获取游戏列表信息
    private function getListInfo(){
        //游戏列表清单
        $field = "g.name,gt.name type,i.url link,i.mobile_icon pic";
        $where = "g.status = 2 AND g.is_delete = 2";
        $gamelist = M("game")
        ->alias("g")
        ->join("LEFT JOIN ".C('DB_PREFIX')."game_type gt ON g.type=gt.id" )
        ->join("LEFT JOIN ".C('DB_PREFIX')."game_info i ON g.id=i.app_id" )
        ->where($where)
        ->field($field)
        ->select();
		foreach ($gamelist as $key=>$val){
			$gamelist[$key]["pic"] = "/".C('UPLOADPATH')."/image/".$val["pic"];
			
			$s1 = iconv("UTF-8","gb2312", $val['name']);
            $s2 = iconv("gb2312","UTF-8", $s1);
            if($s2 == $val['name']){$val['name'] = $s1;}			
			$s1 = substr($val['name'],0,1);
			$p = ord($s1);
			if($p > 160){
				$s2 = substr($val['name'],0,2);
				$gamelist[$key]["ucfirst"] = getfirstchar($s2);
			}else{
				$gamelist[$key]["ucfirst"] = $s1;
			}
		}
        $data = json_encode($gamelist);
        return $data;
    }
    
    //获取新闻信息
    private function getNewInfo(){
        //新闻活动信息
        $where = " AND type='1'";
        $newslist = findnews($where);  //新闻公告列表
    
        //获取新闻类型
        $newcontent = $this->getNewType();
    
        //新闻公告列表添加内容数据
        foreach ($newslist as $key=>$val) {
            $file = "template/html/news_".$val['id'].".html";//静态页面路径
            //判断是否存在静态页面
            if(file_exists($file)){
                $newslist[$key]['html'] = 1;
            }else{
                $newslist[$key]['html'] = 0;
            }
            $newslist[$key]['content'] = $newcontent[$val['id']];
        }
    
        return $newslist;
    }
    
    //获取新闻类型
    private function getNewType(){
        $contentlist = contentlist(); //新闻内容列表
    
        foreach ($contentlist as $key => $val) {
            $newcontent[$val['id']] = $val['content'];
        }
        return $newcontent;
    }
    
    //活动公告列表添加内容数据
    private function getActivityInfo(){
    
        $where = " AND type='2'";
        $huodonglist = findnews($where);
    
        $newcontent = $this->getNewType();
        foreach ($huodonglist as $key=>$val) {
            $file = "template/html/news_".$val['id'].".html";//静态页面路径
            //判断是否存在静态页面
            if(file_exists($file)){
                $huodonglist[$key]['html'] = 1;
            }else{
                $huodonglist[$key]['html'] = 0;
            }
            	
            $huodonglist[$key]['content'] = $newcontent[$val['id']];
        }
    
        return $huodonglist;
    }
	
	
}
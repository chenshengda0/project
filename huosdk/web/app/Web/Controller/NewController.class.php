<?php

/**
 * 资讯中心
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class NewController extends HomebaseController {
    
    function _initialize() {
		//热门游戏列表清单
		$hotgamelist = hotgamelist();
		$this -> assign("footgamelist", $hotgamelist);
        parent::_initialize();
    }

    public function index(){
        //获取参数
        $show = I('show');
        $newsid = I('newsid');
        $gameid = I('gameid');
        $type = I('type');

        if($show == 'display'){
            //新闻具体页面
            $contentdata = contentinfo($newsid);
            $newsinfo = newsinfo($newsid);
            $this->assign('contentdata',$contentdata);
            $this->assign('newsinfo',$newsinfo);
            $this->display('news_display');
        } else {    
            $where = '';
            if($gameid != '' && !empty($gameid)){
                $where .= " AND gameid='".$gameid."'";
            }
        
            if($type != '' && !empty($type)){
                $where .= " AND type='".$type."'";
            }
            
            //新闻列表
            $list = newslist($where);
            $newslist = $list['newslist'];
        
            //新闻内容列表
            $contentlist = contentlist();
        
            foreach ($contentlist as $key => $val) {
                $newcontent[$val['id']] = $val['content'];
            }
        
             foreach ($newslist as $key=>$val) {
                $file = __ROOT__."/public/themes/simplebootx/Web/New/html/news_".$val['id'].".html";//静态页面路径
                                
                //判断是否存在静态页面
                if(file_exists($file)){
                    $newslist[$key]['html'] = 1;
                }else{
                    $newslist[$key]['html'] = 0;
                }
                
                $arraycontent = contentinfo($val['id']);
                $newslist[$key]['content'] = mb_substr(strip_tags($arraycontent['content']),0,80,'UTF-8') ."...";
                
            } 
            
            //传递新闻数组
            $this->assign('newslist',$newslist);
            $showpage = $list['showpage'];
       
            //获取首页广告大图
            $guanggao = getGuanggao(3);
            $this->assign('guanggao',$guanggao);
            $this -> assign('showpage',$showpage);
            $this -> display('news');
        }
    }
}
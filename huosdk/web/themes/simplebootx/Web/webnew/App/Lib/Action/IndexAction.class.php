<?php

class IndexAction extends CommonAction{
	/*
	 * 
	 * 网站首页
	 * 
	 */
    function index(){

    	//幻灯片
    	$silde = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."silde WHERE type = 1 ORDER BY orderid ASC");
    	$this->assign("silde",$silde);

    	$silde2 = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."silde WHERE type = 2 ORDER BY orderid ASC");
    	$this->assign("silde2",$silde2);
    	
     	//新游上线
     	$new_game = $GLOBALS['db']->getAll("SELECT id,name,logo FROM ".DB_PREFIX."game WHERE is_del = 0 ORDER BY add_time DESC LIMIT 12");
     	$this->assign("new_game",$new_game);
     	
     	//开服信息
     	$kaifu1 = $GLOBALS['db']->getAll("SELECT f.*,g.logo FROM ".DB_PREFIX."kaifu f LEFT JOIN ".DB_PREFIX."game g ON f.game_id = g.id WHERE type = 0 ORDER BY open_time DESC LIMIT 4");
     	$kaifu2 = $GLOBALS['db']->getAll("SELECT f.*,g.logo FROM ".DB_PREFIX."kaifu f LEFT JOIN ".DB_PREFIX."game g ON f.game_id = g.id WHERE type = 1 ORDER BY open_time DESC LIMIT 4");
    	
     	$this->assign("kaifu1",$kaifu1);
     	$this->assign("kaifu2",$kaifu2);
     	
     	$cards = $GLOBALS['db']->getAll("SELECT gc.id,gc.name,g.logo FROM ".DB_PREFIX."game_card gc LEFT JOIN ".DB_PREFIX."game g ON gc.game_id = g.id WHERE gc.num > gc.make_num ORDER BY gc.make_num DESC LIMIT 4");
    	$this->assign("cards",$cards);
     	$cards2 = $GLOBALS['db']->getAll("SELECT gc.id,gc.name,g.logo FROM ".DB_PREFIX."game_card gc LEFT JOIN ".DB_PREFIX."game g ON gc.game_id = g.id WHERE gc.num > gc.make_num ORDER BY gc.add_time DESC LIMIT 4");
    	$this->assign("cards2",$cards2);
     	
     	$seo = array();
    	$seo['title'] = $GLOBALS['site']['title'];
    	$seo['keywords'] = $GLOBALS['site']['keywords'];
    	$seo['description'] = $GLOBALS['site']['description'];
    	$this->assign("seo",$seo);
    	
        
    	$this->display();
    }
}


?>
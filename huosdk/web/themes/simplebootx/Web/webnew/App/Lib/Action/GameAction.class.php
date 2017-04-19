<?php
class GameAction extends CommonAction{
	
	function index(){
		$type = isset($_GET['lx']) ? intval($_GET['lx']) : 0;
		$network = isset($_GET['wl']) ? intval($_GET['wl']) : 0;
		$pt = in_array(intval($_GET['pt']), array(0,1)) ? intval($_GET['pt']): 0;
		
		$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
		$perpage = 10;
        $limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		
		$where = " WHERE is_del = 0 ";
		
        if($type){
        	$where .= " AND typeid = $type ";
        }
        if($network){
        	$where .= " AND network = $network ";
        }
        
        $orderby = " ORDER BY ";
        $orderby .= " orderid ASC,down_num DESC ";
        
        $count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."game $where");
        $list = $GLOBALS['db']->getAll("SELECT id,name,logo,description,typeid,size FROM ".DB_PREFIX."game $where $orderby $limit");
        
		$types = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."gametype ORDER BY sort ASC");
		
		$type_arr = getidtokey($types);
		foreach ($list as $k => $v){
			$list[$k]['typename'] = $type_arr[$v['typeid']];
		}
		
		$networks = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."network ORDER BY sort ASC");
		
		$pages = new Page_1($count,$perpage);
		if($type || $network || $pt){
			$pages->url = url().'game/page-'.$network.'-'.$pt.'-'.$type.'-';
		}else{
			$pages->url = url().'game/page-';
		}
		
		$pages = $pages->show();
		
		$this->assign("types",$types);
		$this->assign("networks",$networks);
		$this->assign("lx",$type);
		$this->assign("wl",$network);
		$this->assign("pt",$pt);
		$this->assign("list",$list);
		$this->assign("count",$count);
		$this->assign("pages",$pages);
		
		$catid = 12;
		$cate = $GLOBALS['db']->getRow("SELECT title,keywords,description FROM ".DB_PREFIX."category WHERE catid = $catid");
		$seo = array();
		$seo['title'] = $cate['title'] ? $cate['title'] : "游戏大全-".$GLOBALS['site']['site_name'];
		$seo['keywords'] = $cate['keywords'];
		$seo['description'] = $cate['description'];
		$this->assign("seo",$seo);
		$this->display();
	}
	
	function sreach(){
		$keyword = trim(new_addslashes($_GET['keywords']));
		$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
		$perpage = 10;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$where = " WHERE is_del = 0 ";
		
		if($keyword){
			$where .= " AND name LIKE '%$keyword%' ";
		}
		$count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."game $where");
		$list = $GLOBALS['db']->getAll("SELECT id,name,logo,description,typeid,size FROM ".DB_PREFIX."game $where $orderby $limit");
		
		$types = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."gametype ORDER BY sort ASC");
		
		$type_arr = getidtokey($types);
		foreach ($list as $k => $v){
			$list[$k]['typename'] = $type_arr[$v['typeid']];
		}
		$pages = new Page_1($count,$perpage);
		$pages->url = url().'game/sreach/page-';
		$pages = $pages->show($keyword);
		$this->assign("list",$list);
		$this->assign("count",$count);
		$this->assign("pages",$pages);
		
		$seo = array();
		$seo['title'] = $cate['title'] ? $cate['title'] : "游戏大全-".$GLOBALS['site']['site_name'];
		$seo['keywords'] = $cate['keywords'];
		$seo['description'] = $cate['description'];
		$this->assign("seo",$seo);
		$this->display();
	}
	
	function show(){
		$id = intval($_GET['id']);
		$game = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."game WHERE id = $id");
		if(!$game){
			url_redirect(url('Game'));
		}
		$game['typename'] = $GLOBALS['db']->getOne("SELECT name FROM ".DB_PREFIX."gametype WHERE id = ".$game['typeid']);
		$this->assign("game",$game);
		
		//上一篇
		$prev = $GLOBALS['db']->getRow("SELECT id,name FROM ".DB_PREFIX."game WHERE is_del= 0 AND id > ".$game['id']." ORDER BY id ASC");
		//下一篇
		$next = $GLOBALS['db']->getRow("SELECT id,name FROM ".DB_PREFIX."game WHERE is_del= 0 AND id < ".$game['id']." ORDER BY id DESC");
		
		$this->assign("prev",$prev);
		$this->assign("next",$next);
		
		$seo = array();
		$seo['title'] = $game['name']."-".$GLOBALS['site']['site_name'];
		$seo['keywords'] = $game['keywords'];
		$seo['description'] = $game['description'];
		$this->assign("seo",$seo);
		
		$this->display();
	}
	
	
	function panhang(){
		$sql = "SELECT g.id,g.name,g.logo,t.name as typename,d.num FROM ".DB_PREFIX."game g 
				LEFT JOIN ".DB_PREFIX."gametype t ON g.typeid = t.id 
				LEFT JOIN ".DB_PREFIX."download d ON g.id = d.game_id 
				WHERE g.is_del = 0 ";
		$time = $_SERVER['REQUEST_TIME'];
		$y = date("Y",$time);
		$m = date("m",$time);
		$d = date("d",$time);
		$w = date("w",$time);
		$t = date("t",$time);
		
		$limit = " LIMIT 8";
		
		$startweek = mktime(0, 0 , 0,$m,$d-$w+1,$y);
		
		$startyear = mktime(0,0,0,1,1,$y);
		
		$year_list = $GLOBALS['db']->getAll($sql." AND d.updatetime >= ".$startyear." ORDER BY year_num DESC $limit");
		$week_list = $GLOBALS['db']->getAll($sql." AND d.updatetime >= ".$startweek." ORDER BY week_num DESC $limit");
		
		
	}
	
	
	function download(){
		$id = intval($_GET['id']);
		$download_url = $GLOBALS['db']->getOne("SELECT download FROM ".DB_PREFIX."game WHERE id = $id");
		if(!$download_url){
			showMsg("无下载地址");
		}
		$time = $_SERVER['REQUEST_TIME'];
		$y = date("Y",$time);
		$m = date("m",$time);
		$d = date("d",$time);
		$w = date("w",$time);
		$t = date("t",$time);
		//今天时间
		$startday = mktime(0, 0, 0, $m, $d, $y);
		$endday = mktime(23,59,59, $m, $d, $y);
		//本周时间
		$startweek = mktime(0, 0 , 0,$m,$d-$w+1,$y);
		$endweek = mktime(0, 0 , 0,$m,$d-$w+7,$y);
		//本月时间
		$startmonth = mktime(0, 0 , 0,$m,1,$y);
		$endmonth = mktime(23,59,59,$m,$t,$y);
		//今年时间
		$startyear = mktime(0,0,0,1,1,$y);
		$endyear = mktime(0,0,0,1,1,$y+1)-1;
		
		$download = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."download WHERE game_id = $id");
		if($download){
			if($download['updatetime'] < $endday && $download['updatetime'] > $startday){
				$download['day_num'] += 1;
			}else{
				$download['day_num'] = 1;
			}
			if($download['updatetime'] < $endweek && $download['updatetime'] > $startweek){
				$download['week_num'] += 1;
			}else{
				$download['week_num'] = 1;
			}
			if($download['updatetime'] < $endmonth && $download['updatetime'] > $startmonth){
				$download['month_num'] += 1;
			}else{
				$download['month_num'] = 1;
			}
			if($download['updatetime'] < $endyear && $download['updatetime'] > $startyear){
				$download['year_num'] += 1;
			}else{
				$download['year_num'] = 1;
			}
			$download['num'] += 1;
			$download['updatetime'] = $time;
			$GLOBALS['db']->autoExecute(DB_PREFIX."download",$download,"UPDATE","game_id = ".$id);
		}else{
			$download = array(
				"game_id" => $id,
				"day_num" => 1,
				"week_num" => 1,
				"month_num" => 1,
				"year_num" => 1,
				"num" => 1,
				"updatetime" => $time
			);
			$GLOBALS['db']->autoExecute(DB_PREFIX."download",$download,"INSERT");
		}
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."game SET down_num = down_num +1 WHERE id = $id");
		url_redirect($download_url);
	}
	
}
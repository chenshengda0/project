<?php
    /*
     *  qjcms
     * 
     */
    class GameAction extends AuthAction {
        public function index(){
            $page = $_GET['p']?intval($_GET['p']):1;
            $perpage = 20;
            $where = " WHERE 1=1 ";
            $orderby = " ORDER BY orderid ASC,id DESC  ";
            $limit = " LIMIT ".($perpage*($page-1)).",$perpage";
            
            $name = isset($_GET['name'])?new_addslashes($_GET['name']):"";
            $this->assign("name",$name);
            
            $typeid = intval($_GET['typeid']);
            $this->assign("typeid",$typeid);
            
            if($name != ""){
            	$where .= " AND name like '%".$name."%' ";
            }
            if($typeid){
            	$where .= " AND typeid = $typeid ";
            }
            
            $sql = "SELECT * FROM ".DB_PREFIX."game ".$where. $orderby . $limit . " ";
            
            $count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."game ". $where ."");
            
            $list = $GLOBALS['db']->getAll($sql);
            foreach ($list as $k => $v){
            	$list[$k]['typename'] = $GLOBALS['db']->getOne("SELECT name FROM ".DB_PREFIX."gametype WHERE id = ".$v['typeid']);
            	$list[$k]['num'] = $GLOBALS['db']->getOne("SELECT num FROM ".DB_PREFIX."download WHERE game_id = ".$v['id']);
            }
            
            $pages = new Page($count,$perpage);
            $pages = $pages->show();
            $this->assign("pages",$pages);
            $this->assign("list",$list);
           	
            $push = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."pushtype WHERE module = 'game' ORDER BY sort ASC");
            $this->assign("push",$push);
            
            $types = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."gametype  ORDER BY sort ASC");
            $this->assign("types",$types);
            
            $this->display();
        }
        

    function add(){
    		if($_POST){
    			//
    			if(new_addslashes($_POST['name']) == ''){
    				showMsg('游戏名称不能为空','goback');
    			}
    			//
    			if(new_addslashes($_POST['logo']) == ''){
    				showMsg('游戏logo不能为空','goback');
    			}
				$game = array();
    			$game['name'] = trim(new_addslashes($_POST['name']));      // 
    			$game['catid'] = intval(new_addslashes($_POST['catid']));      // 
				$game['logo'] = trim(new_addslashes($_POST['logo']));      // 
				$game['typeid'] = intval(new_addslashes($_POST['typeid']));      // 
				$game['network'] = intval(new_addslashes($_POST['network']));      // 
				//$game['images'] = serialize($_POST['images']);
				$game['keywords'] = get_keywords(trim(new_addslashes($_POST['keywords'])));      // 
				$game['description'] = trim(new_addslashes($_POST['description']));      // 
				$game['content'] = trim(new_addslashes($_POST['content']));      // 
				$game['download'] = trim(new_addslashes($_POST['download']));      // 
				$game['banben'] = trim(new_addslashes($_POST['banben']));      // 
				$game['size'] = floatval(new_addslashes($_POST['size']));      // 
				
				$game['orderid'] = isset($_POST['orderid']) ? intval($_POST['orderid']) : 0;    // 
				$game['add_time'] = time();
				$game['update_time'] = time();
				
				
                $GLOBALS['db']->autoExecute(DB_PREFIX."game",$game,"INSERT");
                
                /* $game_id = $GLOBALS['db']->insert_id();
                if($_POST['download']){
                	foreach ($_POST['download'] as $k => $v){
                		$download = array(
                			"game_id" => $game_id,
                			"pale_id" => $k,
                			"banben" => trim(new_addslashes($v['banben'])),
                			"url" => trim(new_addslashes($v['url']))
                		);
                		$GLOBALS['db']->autoExecute(DB_PREFIX."download",$download,"INSERT");
                	}
                } */
                
                showMsg(lang("SUCCESS"),ADMIN_URL."/Game/index");
    		}
    		$data = $GLOBALS['db']->getAll("SELECT catid,catname,parentid FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'game' ORDER BY `orderid` ASC");
    		$tree = $this->tree_select($data);
    		$this->assign("tree",$tree);
    		
    		$types = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."gametype ORDER BY sort");
    		$this->assign("types",$types);
    		$network = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."network ORDER BY sort");
    		$this->assign("network",$network);
    		$this->display();
    	}
    	
    	function edit(){
    		if($_POST){
    			
    		   //
    			if(new_addslashes($_POST['name']) == ''){
    				showMsg('游戏名称不能为空','goback');
    			}
    			//
    			if(new_addslashes($_POST['logo']) == ''){
    				showMsg('游戏logo不能为空','goback');
    			}
				
    		    $id = intval($_POST['id']);
				
    		    $game = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."game WHERE id = $id");
    		   	
    		    if($GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."game WHERE name = '".new_addslashes($_POST['name'])."' AND id != $id")>0){
    		    	showMsg("已存在","goback");
    		    }
    			$game['name'] = trim(new_addslashes($_POST['name']));      //
    			$game['catid'] = intval(new_addslashes($_POST['catid']));      //
				$game['logo'] = trim(new_addslashes($_POST['logo']));      // 
				$game['typeid'] = intval(new_addslashes($_POST['typeid']));      //
				$game['network'] = intval(new_addslashes($_POST['network']));      //
				$game['keywords'] = get_keywords(trim(new_addslashes($_POST['keywords'])));      //
				$game['description'] = trim(new_addslashes($_POST['description']));      //
				$game['content'] = trim(new_addslashes($_POST['content']));      //
				$game['orderid'] = isset($_POST['orderid']) ? intval($_POST['orderid']) : 0;    //
				$game['download'] = trim(new_addslashes($_POST['download']));      //
				$game['banben'] = trim(new_addslashes($_POST['banben']));      //
				$game['size'] = floatval(new_addslashes($_POST['size']));      //
				$game['update_time'] = time();
				
    		    $GLOBALS['db']->autoExecute(DB_PREFIX."game",$game,"UPDATE","id=$id");
    		    
    		    /* if($_POST['download']){
    		    	foreach ($_POST['download'] as $k => $v){
    		    		
    		    		if($download = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."download WHERE game_id = $id AND pale_id = $k")){
    		    			$download['url'] = trim(new_addslashes($v['url']));
    		    			$download['banben'] = trim(new_addslashes($v['banben']));
    		    			$GLOBALS['db']->autoExecute(DB_PREFIX."download",$download,"UPDATE","game_id = $id AND pale_id = $k");
    		    		}else{
    		    			$GLOBALS['db']->autoExecute(DB_PREFIX."download",$download,"INSERT");
    		    		}
    		    	}
    		    } */
    		    showMsg(lang("SUCCESS"),ADMIN_URL."/Game/index");
    		}
    		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    		$game = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."game WHERE id = $id");
    		if(!$game){
    		    url_redirect(ADMIN_URL."/Game/index");
    		}
    		
    		$this->assign("id",$id);
    		$this->assign("game",$game);
    		
    		$data = $GLOBALS['db']->getAll("SELECT catid,catname,parentid FROM ".DB_PREFIX."category WHERE is_del = 0 AND tablename = 'game' ORDER BY `orderid` ASC");
    		$tree = $this->tree_select($data,0,0,$game['catid']);
    		$this->assign("tree",$tree);
    		
    		$types = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."gametype ORDER BY sort");
    		$this->assign("types",$types);
    		$network = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."network ORDER BY sort");
    		$this->assign("network",$network);
    		 
    		$this->display();
    	}
        
        
        function delete(){
        	$id = intval($_GET['id']);
        	$ids = $_POST['ids'];
        	if(isset($ids)){
        		$id_arr = explode(",",$ids);
        		foreach($id_arr as $k=>$v){
        			if($v==""){
        				unset($id_arr[$k]);
        			}
        		}
        
        		if(count($id_arr)==0){
        			showMsgajax(lang("SUCCESS"),1);
        		}
        		$sql_del = "DELETE FROM ".DB_PREFIX."game WHERE id in ($ids)";
        		$GLOBALS['db']->query($sql_del);
        		showMsgajax(lang("SUCCESS"),1);
        	}else{
        		$sql_del = "DELETE FROM ".DB_PREFIX."game WHERE id = $id";
        		$GLOBALS['db']->query($sql_del);
        		showMsg(lang("SUCCESS"),ADMIN_URL."/Game/index");
        	}
        }
        
        
        /**
         * 树形下拉
         * @param unknown $data
         * @param number $pid
         * @param number $num
         * @return string
         */
        private function tree_select($data,$pid = 0,$num = 0,$catid = 0){
        	$str = "";
        	$icon = '&nbsp;&nbsp;&nbsp;&nbsp;';
        	foreach($data as $k=>$v){
        		if($v['parentid'] == $pid){
        			$str .= '<option value="'.$v['catid'].'"';
        			if($catid == $v['catid']){
        				$str .= ' selected ';
        			}
        			if($this->is_parent($data,$v['catid'])){
        				$str .= ' disabled="disabled" ';
        			}
        			$str .= '>'.str_repeat($icon,$num).'├─ '.$v['catname'].'</option>';
        			$str .= $this->tree_select($data,$v['catid'],$num+1,$catid);
        		}
        	}
        	return $str;
        }
        /**
         * 判断id是否是父类
         * @param unknown $data
         * @param unknown $id
         * @return boolean
         */
        private function is_parent($data,$id){
        	foreach ($data as $k => $v){
        		if($id == $v['parentid']){
        			return true;
        		}
        	}
        	return false;
        }
    	
    }
    
?>
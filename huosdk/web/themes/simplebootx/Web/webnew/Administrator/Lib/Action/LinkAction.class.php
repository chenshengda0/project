<?php
    /*
     *  qjcms
     * 
     */
    class LinkAction extends AuthAction {
        public function index(){
            $page = $_GET['p']?intval($_GET['p']):1;
            $perpage = 20;
            $where = " WHERE 1=1 ";
            $orderby = " ORDER BY orderid ASC  ";
            $limit = " LIMIT ".($perpage*($page-1)).",$perpage";
            
            $name = isset($_GET['name'])?new_addslashes($_GET['name']):"";
            $disabled = isset($_GET['disabled'])?intval($_GET['disabled']):"-1";
            $this->assign("name",$name);
            $this->assign("disabled",$disabled);
            
            if($name != ""){
            	$where .= " AND name like '%".$name."%' ";
            }
            if($disabled != "-1"){
            	$where .= " AND disabled = '".$disabled."' ";
            }
            
            $sql = "SELECT * FROM ".DB_PREFIX."link ".$where. $orderby . $limit . " ";
            
            $count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."link ". $where ."");
            
            $link = $GLOBALS['db']->getAll($sql);
            
            $pages = new Page($count,$perpage);
            $pages = $pages->show();
            $this->assign("pages",$pages);
            $this->assign("list",$link);
            	
            $this->display();
        }
        
        function change_show(){
        	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        	$link = $GLOBALS['db']->getRow("SELECT `disabled` FROM ".DB_PREFIX."link WHERE id = $id ");
        	if(!$link){
        		showMsgajax(lang("SUCCESS"),-1);
        	}else{
        		if($link['disabled'] == 1){
        			$link['disabled'] = 0;
        		}else{
        			$link['disabled'] = 1;
        		}
        		$GLOBALS['db']->autoExecute(DB_PREFIX."link",$link,"UPDATE","id=$id");
        		showMsgajax(lang("SUCCESS"),$link['disabled']);
        	}
        }

    function add(){
    	
    		if($_POST){
    			//
    			if(new_addslashes($_POST['name']) == ''){
    				showMsg('网站名称不能为空','goback');
    			}
    			//
    			if(new_addslashes($_POST['site_name']) == ''){
    				showMsg('网站地址不能为空','goback');
    			}
    			$link['name'] = trim(new_addslashes($_POST['name']));      // 
				$link['site_name'] = trim(new_addslashes($_POST['site_name']));      // 
				$link['logo'] = trim(new_addslashes($_POST['logo']));      // 
				$link['disabled'] = isset($_POST['disabled']) ? intval($_POST['disabled']) : 0;    // 
				$link['orderid'] = isset($_POST['orderid']) ? intval($_POST['orderid']) : 0;    // 
				$link['add_time'] = time();    // 

				if(!preg_match('',$link['site_name'])){
					showMsg('网站地址不正确','goback');
				}
				
                $GLOBALS['db']->autoExecute(DB_PREFIX."link",$link,"INSERT");
                showMsg(lang("SUCCESS"),ADMIN_URL."/Link/index");
    		}
    		
    		$this->display();
    	}
    	
    	function edit(){
    		if($_POST){
    		    $id = intval($_POST['id']);
    		    $link = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."link WHERE id = $id");
    		   	//
				 if(new_addslashes($_POST['name']) == ''){
				 showMsg('网站名称不能为空','goback');
				}
				//
				 if(new_addslashes($_POST['site_name']) == ''){
				 showMsg('网站地址不能为空','goback');
				}

    		    if($GLOBALS['db']->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."link WHERE name = '".new_addslashes($_POST['name'])."' AND id != $id")>0){
    		    	showMsg("已存在","goback");
    		    }
    		    $link['name'] = trim(new_addslashes($_POST['name']));      // 
				$link['site_name'] = trim(new_addslashes($_POST['site_name']));      // 
				$link['logo'] = trim(new_addslashes($_POST['logo']));      //
				$link['disabled'] = isset($_POST['disabled']) ? intval($_POST['disabled']) : 0;    // 
				$link['orderid'] = isset($_POST['orderid']) ? intval($_POST['orderid']) : 0;    // 

    		    $GLOBALS['db']->autoExecute(DB_PREFIX."link",$link,"UPDATE","id=$id");
    		    
    		    showMsg(lang("SUCCESS"),ADMIN_URL."/Link/index");
    		}
    		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    		$link = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."link WHERE id = $id");
    		if(!$link){
    		    url_redirect(ADMIN_URL."/Link/index");
    		}
    		$this->assign("id",$id);
    		$this->assign("link",$link);
    		 
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
        		$sql_del = "DELETE FROM ".DB_PREFIX."link WHERE id in ($ids)";
        		$GLOBALS['db']->query($sql_del);
        		showMsgajax(lang("SUCCESS"),1);
        	}else{
        		$sql_del = "DELETE FROM ".DB_PREFIX."link WHERE id = $id";
        		$GLOBALS['db']->query($sql_del);
        		showMsg(lang("SUCCESS"),ADMIN_URL."/Link/index");
        	}
        }
    	
    }
?>
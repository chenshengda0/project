<?php
    /*
     *  qjcms
     * 
     */
    class SystemAction extends AuthAction {
        public function index(){
            //查询ftp配置表
            $upload_arr = $GLOBALS['db']->getROw("SELECT * FROM ".DB_PREFIX."system_upload_conf");
            if(!$upload_arr){
                $upload_is_open = 1;
                $upload_pasv = 1;
            }else{
                $upload_is_open = $upload_arr['upload_is_open'];
                $upload_pasv = $upload_arr['upload_pasv'];
            }
            $this->assign("upload_arr",$upload_arr);
            $this->assign("upload_is_open",$upload_is_open);
            $this->assign("upload_pasv",$upload_pasv);
            $this->display();
        }
        
        /*
         * 
         * ftp远程附件配置
         * 
         */
        function system_upload(){
            //查询ftp配置表
            $upload_arr = $GLOBALS['db']->getROw("SELECT * FROM ".DB_PREFIX."system_upload_conf");
            //获取post传过来的值
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $is_open = isset($_POST['is_open']) ? intval($_POST['is_open']) : 1;//是否开启远程附件
            $is_pasv = isset($_POST['is_pasv']) ? intval($_POST['is_pasv']) : 1;//是否开启被动模式
            $ftp_url = isset($_POST['ftp_url']) ? new_addslashes(trim($_POST['ftp_url'])) : "";//ftp地址
            $ftp_name = isset($_POST['ftp_name']) ? new_addslashes(trim($_POST['ftp_name'])) : "";//ftp用户名
            $ftp_pass = isset($_POST['ftp_pass']) ? new_addslashes(trim($_POST['ftp_pass'])) : "";//ftp密码
            $ftp_site = isset($_POST['ftp_site']) ? new_addslashes(trim($_POST['ftp_site'])) : "";//ftp目录
            $ftp_showurl = isset($_POST['ftp_showurl']) ? new_addslashes(trim($_POST['ftp_showurl'])) : "";//ftp访问路径
            $ftp_size = isset($_POST['ftp_size']) ? intval(trim($_POST['ftp_size'])) : 0;//上传文件大小
            $ftp_port = isset($_POST['ftp_port']) ? intval(trim($_POST['ftp_port'])) : 21;//ftp端口
            $ftp_img_url = isset($_POST['ftp_img_url']) ? new_addslashes(trim($_POST['ftp_img_url'])) : "";
           
            if($ftp_img_url != ""){
                $ftp_img_urlarr = substr(ROOT_PATH,0,-1).$ftp_img_url;
                if(!file_exists($ftp_img_urlarr)){
                    echo json_encode(array("success"=>-1,"info"=>"文件不存在，请重新填写"));
                    exit;
                }
            }
            
            if($is_open != 0){
                if($ftp_url == ""){
                	echo json_encode(array("success"=>-1,"info"=>"ftp地址不能为空"));
                	exit;
                }
                
                if($ftp_name == ""){
                	echo json_encode(array("success"=>-1,"info"=>"ftp账号不能为空"));
                	exit;
                }
                
                if($ftp_pass == ""){
                	echo json_encode(array("success"=>-1,"info"=>"ftp密码不能为空"));
                	exit;
                }
                
                if($ftp_site == ""){
                	echo json_encode(array("success"=>-1,"info"=>"ftp目录不能为空"));
                	exit;
                }
                
                if($ftp_showurl == ""){
                	echo json_encode(array("success"=>-1,"info"=>"ftp访问路径不能为空"));
                	exit;
                }
            }else{
                $ftp_site = $rootlujing = str_replace("\\","/","/public/uploadfile/");
            }
            
            if($_POST['ftp_conf']){
            	if(!$upload_arr){
            		$sql = "INSERT INTO ".DB_PREFIX."system_upload_conf SET 
            		       upload_url = '".$ftp_url."',
            		       upload_name = '".$ftp_name."',
            		       upload_pwd = '".$ftp_pass."',
            		       upload_site = '".$ftp_site."',
            		       upload_showsite = '".$ftp_showurl."',
            		       upload_size = '".$ftp_size."',
            		       upload_port = '".$ftp_port."',
            		       upload_is_open = '".$is_open."',
            		       upload_pasv = '".$is_pasv."',
            		       upload_img_url = '".$ftp_img_urlarr."'";
            	}else{
            		$sql = "UPDATE ".DB_PREFIX."system_upload_conf SET 
            		        upload_url = '".$ftp_url."',
            		        upload_name = '".$ftp_name."',
            		        upload_pwd = '".$ftp_pass."',
            		        upload_site = '".$ftp_site."',
            		        upload_showsite = '".$ftp_showurl."',
            		        upload_size = '".$ftp_size."',
            		        upload_port = '".$ftp_port."',
            		        upload_is_open = '".$is_open."',
            		        upload_pasv = '".$is_pasv."',
            		        upload_img_url = '".$ftp_img_urlarr."'
            		        WHERE id = ".$id;
            	}
            	if($GLOBALS['db']->query($sql)){
            		add_admin_log($GLOBALS['action'][ACTION_NAME]);
            	    echo json_encode(array("success"=>1,"info"=>"ftp配置成功"));
            	    exit;
            	}else{
            	    echo json_encode(array("success"=>-1,"info"=>"未知错误，请重新配置！"));
            	    exit;
            	}
            }
        }
        
        /*
         * 
         * 测试链接远程附件
         * 
         */
        function system_upload_example(){
            //获取post传过来的值
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $is_open = isset($_POST['is_open']) ? intval($_POST['is_open']) : 1;//是否开启远程附件
            $is_pasv = isset($_POST['is_pasv']) ? intval($_POST['is_pasv']) : 1;//是否开启被动模式
            $ftp_url = isset($_POST['ftp_url']) ? new_addslashes(trim($_POST['ftp_url'])) : "";//ftp地址
            $ftp_name = isset($_POST['ftp_name']) ? new_addslashes(trim($_POST['ftp_name'])) : "";//ftp用户名
            $ftp_pass = isset($_POST['ftp_pass']) ? new_addslashes(trim($_POST['ftp_pass'])) : "";//ftp密码
            $ftp_site = isset($_POST['ftp_site']) ? new_addslashes(trim($_POST['ftp_site'])) : "";//ftp目录
            $ftp_showurl = isset($_POST['ftp_showurl']) ? new_addslashes(trim($_POST['ftp_showurl'])) : "";//ftp访问路径
            $ftp_size = isset($_POST['ftp_size']) ? intval(trim($_POST['ftp_size'])) : 0;//上传文件大小
            $ftp_port = isset($_POST['ftp_port']) ? intval(trim($_POST['ftp_port'])) : 21;//ftp端口
              
        	if($_POST['ftp_conf'] == 1){
        	    $info = ftp_example($ftp_url,$ftp_name,$ftp_pass,$is_open);
        	    add_admin_log($GLOBALS['action'][ACTION_NAME]);
        	    echo json_encode(array("success"=>1,"info"=>$info));
        	    exit;
        	}
        }
    }
?>
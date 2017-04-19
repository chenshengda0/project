<?php
    ////初始化站点配置
    require "init.php";
    
    //过滤请求
    function filter_request(&$request)
    {
    	if(MAGIC_QUOTES_GPC)
    	{
    		foreach($request as $k=>$v)
    		{
    			if(is_array($v))
    			{
    				filter_request($v);
    			}
    			else
    			{
    				$request[$k] = stripslashes(trim($v));
    			}
    		}
    	}
    
    }
    //跳转
    function url_redirect($url)
    {
    	//多行URL地址支持
    	$url = str_replace(array("\n", "\r"), '', $url);
    	
    	if (!headers_sent()) {
    		// redirect
    		
			if(substr($url,0,1)=="/")
			{
				header("Location:".get_domain().$url);
			}
			else
			{
				header("Location:".$url);
			}
    
    		exit();
    	}else {
    		$str    = "<meta http-equiv='Refresh' content='0;URL={$url}'>";
    		exit($str);
    	}
    }
    
    
    /**
     * 返回经addslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    function new_addslashes($string){
    	if(!is_array($string)) return addslashes($string);
    	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
    	return $string;
    }
    
    /**
     * 返回经stripslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    function new_stripslashes($string) {
    	if(!is_array($string)) return stripslashes($string);
    	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
    	return $string;
    }
    
    /**
     * 返回经htmlspecialchars处理过的字符串或数组
     * @param $obj 需要处理的字符串或数组
     * @return mixed
     */
    function new_html_special_chars($string) {
    	$encoding = 'utf-8';
    	if(strtolower(CHARSET)=='gbk') $encoding = 'gb2312';
    	if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,$encoding);
    	foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
    	return $string;
    }
    
    function new_html_entity_decode($string) {
    	$encoding = 'utf-8';
    	if(strtolower(CHARSET)=='gbk') $encoding = 'gb2312';
    	return html_entity_decode($string,ENT_QUOTES,$encoding);
    }
    /**
     * 安全过滤函数
     *
     * @param $string
     * @return string
     */
    function safe_replace($string) {
    	$string = str_replace('%20','',$string);
    	$string = str_replace('%27','',$string);
    	$string = str_replace('%2527','',$string);
    	$string = str_replace('*','',$string);
    	$string = str_replace('"','&quot;',$string);
    	$string = str_replace("'",'',$string);
    	$string = str_replace('"','',$string);
    	$string = str_replace(';','',$string);
    	$string = str_replace('<','&lt;',$string);
    	$string = str_replace('>','&gt;',$string);
    	$string = str_replace("{",'',$string);
    	$string = str_replace('}','',$string);
    	$string = str_replace('\\','',$string);
    	return $string;
    }
    
    /*
     * 提示信息并跳转
     * 
     * 
     */
    function showMsg($msg,$url="",$status=1,$wap=0,$stay=false){
		$view       = Think::instance('View');
		$view->assign("msg",$msg);
		$view->assign("url",$url);
		$view->assign("status",$status);
		$view->assign("stay",$stay);
        $view->assign("title","友情提示");
        if($wap == 0){
		    $view->display("ShowMsg:Index");
    	    exit;
        }else{
            $view->display("ShowMsg:wap");
            exit;
        }
    }
    
    
    /*
     * 提示ajax信息
     * 
     * 
     */
    function showMsgajax($msg,$result=1){
    	echo json_encode(array("result"=>$result,"msg"=>$msg));
        exit;
    }
    
    /*
     * 
     * 模板中调用公共文件
     * 
     */
    function tmpl($file){
        Think::instance('View')->display($file);
    }
    
    /*
     * 
     * 修改语言包
     * 
     */
    function edit_lang_file($file,$array){
        /* $str = "<?php\r\n\r return Array(\n";
        foreach($array as $k=>$v){
        	$str .= "\"".$k."\" => \"".$v."\",\n";
        }
        $str .= ");\n?>"; */
        
        file_put_contents($file,"<?php\r\nreturn ".var_export($array,true)."?>");
    }
    
    /*
     * 封装url
     * 
     */
    function url($route="",$param=""){
    	if(sysconf("URL_REWRITE") == 1){
    	    if($route == ""){
    	    }else{
    	    	$route_arr = explode("#",$route);
    	    	$module = strtolower($route_arr[0]);
    	    	if($module != 'page'){
    	    		$url = "/".$module;
    	    		if($route_arr[1]){
    	    			if($route_arr[1] != "show" && $route_arr[1] != "index"){
    	    				$url .= "/".strtolower($route_arr[1]);
    	    			}
    	    			
    	    		}
    	    	}
    	    }
    	    $param_1 = $param;
    	    if($param!=""){
    	    	//$param = "/".str_replace(array("&","="),array("-","-"),$param).".html";
    	    	$param_arr = explode("=",$param);
    	    	if(trim($param_arr[0]) == 'id'){
    	    		switch ($module){
    	    			case 'product':
    	    				if($route_arr[1] == '' || $route_arr[1] == 'index'){
    	    					$catid = intval($param_arr[1]);
    	    				}else{
    	    					$catid = $GLOBALS['db']->getOne("SELECT catid FROM ".DB_PREFIX."products WHERE id = ".intval($param_arr[1]));
    	    				}
    	    				$catdir = $GLOBALS['db']->getOne("SELECT catdir FROM ".DB_PREFIX."category WHERE catid = ".$catid." AND tablename = 'product'");
    	    				if($catdir){
    	    					$param = '/'.$catdir."/";
    	    					if($route_arr[1] == '' || $route_arr[1] == 'index'){
    	    						$param .= "index.html";
    	    					}
    	    				}else{
    	    					return '';
    	    				}
    	    				break;
    	    			case 'news':
    	    				if($route_arr[1] == '' || $route_arr[1] == 'index'){
    	    					$catid = intval($param_arr[1]);
    	    				}else{
    	    					$catid = $GLOBALS['db']->getOne("SELECT catid FROM ".DB_PREFIX."news WHERE id = ".intval($param_arr[1]));
    	    				}
    	    				$catdir = $GLOBALS['db']->getOne("SELECT catdir FROM ".DB_PREFIX."category WHERE catid = ".$catid." AND tablename = 'news'");
    	    				if($catdir){
    	    					$param = '/'.$catdir."/";
    	    					/* if($route_arr[1] == '' || $route_arr[1] == 'index'){
    	    						$param .= "index.html";
    	    					} */
    	    					
    	    				}else{
    	    					return '';
    	    				}
    	    				break;
    	    			case 'page':
    	    				$catdir = $GLOBALS['db']->getOne("SELECT catdir FROM ".DB_PREFIX."page WHERE id = ".intval($param_arr[1]));
    	    				if($catdir){
    	    					$param = '/page/'.$catdir.".html";
    	    				}else{
    	    					return '';
    	    				}
    	    				break;
    	    			case 'game':
    	    				if($route_arr[1] == 'show'){
    	    					$param = '/';
    	    				}else{
    	    					$param = ".html?".$param_1;
    	    				}
    	    				break;
    	    			case 'gamecard':
    	    				if($route_arr[1] == 'show'){
    	    					$param = '/';
    	    				}else{
    	    					$param = ".html?".$param_1;
    	    				}
    	    				break;
    	    			default:
    	    				$param = ".html?".$param_1;
    	    				break;
    	    		}
    	    		if($route_arr[1] == 'show' && $module != 'page'){
    	    			$param .= intval($param_arr[1]).".html";
    	    		}
    	    	}else{
    	    		$param = "/";
    	    	}
    	    }else{
    	        if($route == ""){
    	        	$param = "/";
    	        }else{
    	        	if($route_arr[1] == "" || $route_arr[1] == 'index'){
    	        		$param = "/index.html";
    	        	}else{
    	        		$param = ".html";
    	        	}
    	        }
    	    }
    	    
    	    $url .= $param;
    	    
    	    
    	}else{
    		if($route == ""){
    			$url = "/";
    		}else{
    			$route_arr = explode("#",$route);
    			$url = "/index.php/".$route_arr[0]."/";
    			if($route_arr[1]){
    				$url .= "/".$route_arr[1]."/";
    			}else{
    				$url .= "index/";
    			}
    		}

    		if($param!=""){
    		    $url .= str_replace(array("&","="),array("/","/"),$param).".html";
    		}
    	}
    	if(APP_NAME == "App"){
    		return sysconf('DOMAIN_PATH').$url;
    	}elseif (APP_NAME == "Mobile"){
    		return sysconf('MOBILE_URL').$url;
    	}else{
    		return get_domain().$url;
    	}
    	
    }

    /*
     *
    * 测试ftp是否连接成功
    *
    */
    function ftp_example($host, $username = '', $password = '',$is_open){
    	require './system/ftp/ftp.php';
    	$ftps = new Ftp();
    	if($is_open == 1){
    		$ftps->connect($host, $username, $password);
    		$ftps->put('/dymmwimg/web/test.png',ROOT_PATH.'/public/uploadfile/shuiyin.png');
    	}else{
    		$ftps->put('/public/uploadfile/test.png',ROOT_PATH.'/public/uploadfile/shuiyin.png');
    	}
    	return $ftps->get_error();
    }
    
    /*
     *
    * 附件上传input样式
    *$name 文本框name
    *$value 文本框传过来的值
    *$is_img 1是图片文件，2是其他文件
    *$admin 1是后台调用，0是前端调用
    */
    function uploadfile_html($name,$is_img=1,$admin=1,$value="",$id=""){
        $adminurl = ADMIN_URL;
        if(!$id){
        	$id = $name;
        }
        $str = "<div class='img_div'>";
    	if($is_img == 1){
    	    if(trim($value) == ""){
    	        $str .= '<a href="javascript:void(0)" id="'.$id.'_href" target="_blank"><img src="'.__TMPL__Common.'/images/upload-pic.png" alt="" id="'.$id.'_image" width="50" height="50" /></a>';
    	    }else{
    	        $str .= '<a href="'.$value.'" id="'.$id.'_href" target="_blank"><img src="'.$value.'" alt="" id="'.$id.'_image" width="50" height="50" /></a>';
    	    }
        	$str .= '<input type="hidden" id="'.$id.'_hidden" name="'.$name.'" value="'.$value.'" />';
        	$str .= '<script>KindEditor.ready(function (K) {
                     var editor = K.editor({
                        allowFileManager: true
                     });
                     $(\'#'.$id.'_button\').click(function () {
                	    var id = $(this).attr("id");
                	    editor.loadPlugin(\'image\', function () {
                	        editor.plugin.imageDialog({
                	            imageUrl: $("#'.$id.'_image").attr("src"),
                	            clickFn: function (url, title, width, height, border, align) {
                	                $("#'.$id.'_image").attr("src",url);
                	                $("#'.$id.'_href").attr("href",url);
                	                document.getElementById("'.$id.'_hidden").value = url;
                	                editor.hideDialog();
                	            },
                	            admin: '.$admin.',
                	            showRemote: '.($admin==1?'true':'false').'
                	        });
                	    });
                     });
                    });</script>';
    	}elseif($is_img == 2){
    	    if(trim($value) == ""){
    		    $str .= '<input type="text" name="'.$name.'" id="'.$id.'_image" value="" class="input-text" readOnly="true" />';
    	    }else{
    	        $str .= '<input type="text" name="'.$name.'" id="'.$id.'_image" value="'.$value.'" class="input-text" readOnly="true" />';
    	    }
    		$str .= '<input type="hidden" id="'.$id.'_hidden" name="'.$name.'" value="'.$value.'" />';
    		$str .= '<script>KindEditor.ready(function (K) {
                        var editor = K.editor({
                            allowFileManager: true
                        });
                        $(\'#'.$id.'_button\').click(function () {
                            var id = $(this).attr("id");
                            editor.loadPlugin(\'insertfile\', function () {
                                editor.plugin.fileDialog({
                                	fileUrl: document.getElementById("'.$id.'_image").value,
                                    clickFn: function (url, title) {
                                        document.getElementById("'.$id.'_image").value = url;
                                        document.getElementById("'.$id.'_hidden").value = url;
                                        editor.hideDialog();
                                    }
                                });
                            });
                        });
                    });</script>';
    	}
    	$str .= '<input type="button" class="btn_save" id = "'.$id.'_button" value="上传"/>';
    	$str .= '<input type="button" class="btn_save2" id = "'.$id.'_button_del" value="删除"/>';
    	
    	if($is_img == 1){
        	$str .= '<script>
        	        $("#'.$id.'_button_del").click(function(){
    	                $("#'.$id.'_image").attr("src","'.__TMPL__Common.'/images/upload-pic.png");
    	                $("#'.$id.'_href").attr("href","javascript:void(0)");
	                    document.getElementById("'.$id.'_hidden").value = "";
    	            });
        	        </script>';
    	}elseif($is_img == 2){
    	    $str .= '<script>
    	            $("#'.$name.'_button_del").click(function(){
        	            document.getElementById("'.$id.'_image").value = "";
            	        document.getElementById("'.$id.'_hidden").value = "";
	                });
        	        </script>';
    	}
    	$str .= "</div>";
    	return $str;
    }
    
    /*
     * 
     * 供求缩略图上传
     * 
     */
    function product_thumb_upload($name,$value=""){
        $str = '';
        $str .= '<div class="product_imgs_arr fl">';
        if($value == ""){
            $str .= '<a href="__TMPL__Common/images/demo/img1213.jpg" target="_blank" id="'.$name.'_href">';
        	$str .= '<img src="__TMPL__Common/images/demo/img1213.jpg" id="'.$name.'_image" width="126" height="93" />';
        	$str .= '</a>';
        	$str .= '<input type="hidden" id="'.$name.'_hidden" name="'.$name.'" value="__TMPL__Common/images/demo/img1213.jpg" />';
        }else{
        	$str .= '<a href="'.$value.'" target="_blank" id="'.$name.'_href">';
        	$str .= '<img src="'.$value.'" id="'.$name.'_image" width="126" height="93" />';
        	$str .= '</a>';
        	$str .= '<input type="hidden" id="'.$name.'_hidden" name="'.$name.'" value="'.$value.'" />';
        }
        $str .= '<p>';
        $str .= '<input type="button" value="上传照片" id = "'.$name.'_button" class="fl">';
        $str .= '<i class="fl" style="color:#008ad9;cursor:pointer;" onclick="del_update(\''.$name.'\')">取消</i>';
        $str .= '</p>';
        $str .= '</div>';
        
        $str .= '<script>
                    $(document).ready(function(){
		                 var editor = KindEditor.editor({
		                    allowFileManager: true
		                 });
		                 $(\'#'.$name.'_button\').click(function () {
		            	    var id = $(this).attr("id");
		            	    editor.loadPlugin(\'image\', function () {
		            	        editor.plugin.imageDialog({
		            	            imageUrl: $("#'.$name.'_image").attr("src"),
		            	            clickFn: function (url, title, width, height, border, align) {
		            	                $("#'.$name.'_image").attr("src",url);
		            	                $("#'.$name.'_href").attr("href",url);
		            	                document.getElementById("'.$name.'_hidden").value = url;
		            	                editor.hideDialog();
		            	            },
		            	            showRemote: false
		            	        });
		            	    });
		                 });
	            	 });
                </script>';
        return $str;
    }
    
    
    //邮件格式验证的函数
    function check_email($email)
    {
    	if(!preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/",$email))
    	{
    		return false;
    	}
    	else
    		return true;
    }
    
    //验证手机号码
    function check_mobile($mobile)
    {
    	if(!empty($mobile) && !preg_match("/^\d{6,}$/",$mobile))
    	{
    		return false;
    	}
    	else
    		return true;
    }
    
    //验证身份证号
    function check_idno($idno)
    {
    	if(!empty($idno) && !preg_match("/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/",$idno))
    	{
    		return false;
    	}
    	else
    		return true;
    }
    
    /**
     * 检查身份证
     * 0失败1成功
     */
    function getIDCardInfo($IDCard) {
    	$iW = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    	$szVerCode = "10X98765432";
    	$sum = 0;
    	for ($i=0; $i<17; $i++)
    	{
    	    $sum += $IDCard[$i]*$iW[$i];
    	}
    	$iy = $sum % 11;
    	$verifyBit = $szVerCode[$iy];
    	if ($verifyBit == $IDCard[17] )
    	{
        	return 1;
        }
    	else
    	{
    		return 0;
    	}
	}
	
	/*
	 * 
	 * 邮件发送测试
	 * 
	 */
	function sendmail_test($smtpserver,$smtpuser,$smtppass,$smtpemailto,$smtpusermail, $mailsubject, $mailbody){
	    require_once ROOT_PATH.'system/email/email.php';
		$smtp = new email($smtpserver,25,true,$smtpuser,$smtppass);
		if($smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, "HTML")){
			return true;
		}
	}
	
	/*
	 * 
	 * 邮件发送正式
	 * 
	 */
	function sendmail($smtpemailto,$mailsubject, $mailbody){
	    $email_conf = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."email_conf");
	    if(!$email_conf){
	    	return false;
	    }
	    require_once ROOT_PATH.'system/email/email.php';
		$smtp = new email($email_conf['mail_server'],25,true,$email_conf['mail_user'],$email_conf['mail_password']);
		if($smtp->sendmail($smtpemailto, $email_conf['mail_from'], $mailsubject, $mailbody, "HTML")){
			return true;
		}
	}

	/*
	 *
	* 获取表中字段值
	*
	*/
	function get_data_from_table($field="name",$table,$where="",$field2=""){
		if($where!=""){
			$where = " WHERE $where";
		}
		$sql = "SELECT $field FROM ".DB_PREFIX."$table $where";
		$result = $GLOBALS['db']->getRow($sql);
		if($field2==""){
			$field2 = $field;
		}
		return $result[$field2];
	}

	/*
	 * 获取post提交数据
	*
	*/
	function get_post_data($heepay_server,$post_string){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $heepay_server);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		curl_close($ch);
	
		return $data;
	}
	
	
	/*
	 *
	* 数字生成图片
	*
	*/
	function outputimg($font_size,$color = array(0,0,0),$type,$randnumimg){
		$rootlujing = str_replace("\\","/",ROOT_PATH."App/Tpl/Common/qjjrzkdata/");
		// && (time() - filemtime($rootlujing.'img/qjimg_jrzk'.$type.'.png')) < 60
	
		/*if((file_exists($rootlujing."img/qjimg_jrzk".$type.".png") && (time() - filemtime($rootlujing.'img/qjimg_jrzk'.$type.'.png')) < 10) && (file_exists($rootlujing."config/areaconfig_jrzk".$type.".php") && (time() - filemtime($rootlujing."config/areaconfig_jrzk".$type.".php")) < 10)){
		 return require $rootlujing."config/areaconfig_jrzk".$type.".php";
		}else{*/
		//header('Content-type: image/png');
		$width = intval(17.2 * $font_size);//图片宽度
		$height = intval(12 * $font_size);//图片高度
		$margin = intval(0.56 * $font_size);//图片间距
	
		if($type == 6 || $type == 7){
			$font_rar = "weiruanyaheibold";
		}else{
			$font_rar = "weiruanyahei";
		}
	
	
		$data=array(0,1,2,3,4,5,6,7,8,9,'.','%','-',',');
		$im = @imagecreate($width, $height) or die("Cannot Initialize new GD image stream");
		$background_color = imagecolorallocate($im, 255, 255, 255);//图片背景颜色
		$text_color = imagecolorallocate($im, $color[0], $color[1], $color[2]);//图片数字颜色
		 
		$data_arr = array();
		for($i=0; $i<60; $i++){
			$data_arr[] = $data[$i%14];
		}
		shuffle($data_arr);//把数组随机排列
		$area_arr = array();
		for($j=0; $j<count($data_arr); $j++){
			$area_num = count($area_arr[$data_arr[$j]]);
	
			$w = 0;
			$h = 0;
	
			if(in_array($data_arr[$j] , array("0",1,2,3,4,5,6,7,8,9))){
				$w = intval($font_size*2/3);
			}elseif($data_arr[$j] == "."){
				$w = intval($font_size/3);
			}elseif($data_arr[$j] == "-"){
				$w = intval($font_size/2);
			}elseif($data_arr[$j] == "%"){
				$w = intval($font_size*7/6);
			}elseif($data_arr[$j] == ","){
				$w = intval($font_size/3);
			}else{
				$w = intval($font_size*2/3);
			}
			$h = $font_size+4;
	
			$x = intval($width/10*($j%10) + $margin);
			$y = intval($height/6*(intval($j/10)) + $margin*2.5);
			$area_arr[$data_arr[$j]][$area_num]['x'] = "-".$x;
			$area_arr[$data_arr[$j]][$area_num]['y'] = "-".($y - $font_size - 2);
			$area_arr[$data_arr[$j]][$area_num]['w'] = $w;
			$area_arr[$data_arr[$j]][$area_num]['h'] = $h;
	
			imagettftext($im, $font_size, 0, $x, $y, $text_color, $rootlujing.'ttf/'.$font_rar.'.ttf', $data_arr[$j]);
			//imagestring($im, 10, $x, $y,  $data_arr[$j], $text_color);
		}
	
		if(file_exists(ROOT_PATH."public/runtime/areaconfig_jrzk".$type.".php")){
			$file_array = require_once ROOT_PATH."public/runtime/areaconfig_jrzk".$type.".php";
		}
	
		imagepng($im,ROOT_PATH.'public/runtime/qjimg_t'.$type.'_jrzk'.$randnumimg.'.png');
		imagedestroy($im);
		outjrzksyimg_store($type,$width,$height,$randnumimg);
	
		//配置文件
		$config_arr = "<?php return array(\n'img_url'=>'qjimg_t".$type."_jrzk".$randnumimg.".png','type'=>".$type.",'conf'=>array(";
		foreach($area_arr as $k=>$v){
				
			$config_arr .= "'".$k."'=>array(";
			foreach($v as $kk=>$vv){
				$config_arr .= "\n".$kk."=>array('x'=>".$vv['x'].",'y'=>".$vv['y'].",'w'=>".$vv['w'].",'h'=>".$vv['h']."),\n";
			}
			$config_arr .= "),\n";
		}
		$config_arr .= "))?>";
		//读取配置文件
		$file = fopen(ROOT_PATH."public/runtime/areaconfig_jrzk".$type.".php", "w");
		if(file_exists(ROOT_PATH."public/runtime/areaconfig_jrzk".$type.".php")){
			fwrite($file,$config_arr);
		}
		file_put_contents(ROOT_PATH."public/runtime/areaconfig_jrzk".$type.".php",$config_arr);
	
	
		return $area_arr;
	}
	//缩略图
	function ImageResize($srcFile,$toFile="",$toW=300,$toH=150)
	{
		if($toFile==""){ $toFile = $srcFile; }
		$info = "";
		$data = GetImageSize($srcFile,$info);
		
		switch ($data[2])
		{
			case 1:
				$im = ImageCreateFromGIF($srcFile);
				break;
			case 2:
				$im = ImageCreateFromJpeg($srcFile);
				break;
			case 3:
				$im = ImageCreateFromPNG($srcFile);
				break;
			default:
				break;
		}
		$srcW=ImageSX($im);
		$srcH=ImageSY($im);
		$toWH=$toW/$toH;
		$srcWH=$srcW/$srcH;
		if($toWH<=$srcWH){
			$ftoW=$toW;
			$ftoH=$ftoW*($srcH/$srcW);
		}
		else{
			$ftoH=$toH;
			$ftoW=$ftoH*($srcW/$srcH);
		}
		if($srcW>$toW||$srcH>$toH)
		{
			if(function_exists("imagecreatetruecolor")){
				@$ni = ImageCreateTrueColor($ftoW,$ftoH);
				if(!$ni){ //ImageCopyResampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
				//else{
					$ni=ImageCreate($ftoW,$ftoH);
					//ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
				}
			}else{
				$ni=ImageCreate($ftoW,$ftoH);
				//ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			}
			if($data[2] == 3){
				$white = imagecolorallocate($ni,255,255,255);
				imagefilledrectangle($ni,0,0,$ftoW,$ftoH,$white);
				imagecolortransparent($ni,$white);
			}
			ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			if(function_exists('imagejpeg')) ImageJpeg($ni,$toFile);
			else ImagePNG($ni,$toFile);
			ImageDestroy($ni);
		}else{
			@copy($srcFile,$toFile);
		}
		ImageDestroy($im);
	}
	
	//添加水印图片
	function outjrzksyimg_store($type,$width,$height,$randnumimg){
		$rootlujing = str_replace("\\","/",ROOT_PATH."App/Tpl/Common/qjjrzkdata/");
		$dst_path = ROOT_PATH.'public/runtime/qjimg_jrzk'.$randnumimg.'.png';//原图
		$src_path = $rootlujing.'jrzkcom.png';//水印图片
		//创建图片实例
		$dst = imagecreatefromstring(file_get_contents($dst_path));
		$src = imagecreatefromstring(file_get_contents($src_path));
		//获取水印图片的宽高
		list($src_w, $src_h) = getimagesize($src_path);
	
		//固定水印位置
		$src_wbnum = ceil($width/$src_w);
		$src_hbnum = ceil($height/$src_h);
		for($i=0; $i<$src_wbnum; $i++){
			for($j=0; $j<$src_hbnum; $j++){
				imagecopymerge($dst, $src, 100*$i, 100*$j, 0, 0, $src_w, $src_h, 6);
			}
		}
	
		//输出图片
		list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
		switch ($dst_type) {
			case 1://GIF
				imagegif($dst,$dst_path);
				break;
			case 2://JPG
				imagejpeg($dst,$dst_path);
				break;
			case 3://PNG
				imagepng($dst,$dst_path);
				break;
			default:
				break;
		}
	
		imagedestroy($dst);
		imagedestroy($src);
	
		$im = imagecreatefromstring(file_get_contents($dst_path));
		$background_color = imagecolorallocatealpha($im , 0 , 0 , 0 , 127);//拾取一个完全透明的颜色，不要用imagecolorallocate拾色
	
		imagealphablending($im , false);//关闭混合模式，以便透明颜色能覆盖原画板
		imagefill($im , 0 , 0 , $background_color);//填充
	
		imagesavealpha($im , true);//设置保存PNG时保留透明通道信息
		imagepng($im,$dst_path);//生成图片
		imagedestroy($im);
	}
	//数字调用方法
	function countimg($countvalue,$type){
	
		$countvalue = (string)$countvalue;
		//判断类型
		if($type == 1){//基金详情页最新净值
			$font_size = 15;
			$color_arr = array(0,138,217);
		}elseif($type == 2){
			$font_size = 12;
			$color_arr = array(94,94,94);
		}
		//读取配置文件
		$rootlujing = str_replace("\\","/",ROOT_PATH."public/runtime/");
		$classrand_num = random_str(6);
		//删除图片操作日期大于3分钟
		if (is_dir($rootlujing)){
			if ($dh = opendir($rootlujing)){
				while (($file = readdir($dh))!= false){
					//获取文件修改时间
					$fmt = filemtime($rootlujing.$file);
					if((time() - $fmt) > 120){
						@unlink($rootlujing.$file);
					}
				}
				closedir($dh);
			}
		}
	
		if(file_exists(ROOT_PATH."public/runtime/areaconfig_jrzk".$type.".php")){
			$file_array = require ROOT_PATH."public/runtime/areaconfig_jrzk".$type.".php";
		}
	
		if(!isset($file_array)){
			$countposition = outputimg($font_size,$color_arr,$type,$classrand_num);
		}else{
			$countposition = $file_array['conf'];
		}
	
		//先把html标签过滤出来用qj替换
		preg_match_all('/<[^>]+>/', $countvalue, $count_html);
		foreach($count_html as $k=>$v){
			$count_html_str = str_replace($v, "❤", $countvalue);
		}
		//$parrten = '/[[:punct:]]|\d+|\。|\、|\，|\（|\）|\《|\》|\？|\！|\；|\：|\“|\”|\‘|\’|\——|\【|\】|\~|\·/i';
		$parrten = '/\d|\,|\.|\-|\%/i';
		preg_match_all($parrten, $count_html_str, $countvalue_arr);//匹配数字
		 
		//每个数字的键值生成随机数
		$randnum = array();
		foreach($countposition as $k=>$v){
			$randnum[$k][] = mt_rand(0,count($v) - 1);
		}
		if(!isset($file_array)){
			//把数字替换成|
			foreach($countvalue_arr[0] as $k=>$v){
				$count_html_str = str_replace($v, "|", $count_html_str);
				//数值的坐标
				$zuobiaoarr = array();
				$return_css = "<style type='text/css'>";
				for($i=0; $i<strlen($v); $i++){
					if(in_array($v[$i],array("0",1,2,3,4,5,6,7,8,9))){
						$zuobiaoarr[$i] = $countposition[$v[$i]][$randnum[$v[$i]][0]];
					}elseif($v[$i] == "%"){
						$zuobiaoarr[$i] = $countposition['%'][$randnum['%'][0]];
					}elseif($v[$i] == "-"){
						$zuobiaoarr[$i] = $countposition['-'][$randnum['-'][0]];
					}elseif($v[$i] == "."){
						$zuobiaoarr[$i] = $countposition['.'][$randnum['.'][0]];
					}elseif($v[$i] == ","){
						$zuobiaoarr[$i] = $countposition[','][$randnum[','][0]];
					}
					$return_str = "";
					$classrand = random_str(6);
					$return_str .= "<qjjrzklc class='".$classrand."'></qjjrzklc>";
					$return_css .= '.'.$classrand.'{background:url('.url().'public/runtime/qjimg_t'.$type.'_jrzk'.$classrand_num.'.png) no-repeat '.$zuobiaoarr[$i]['x'].'px '.$zuobiaoarr[$i]['y'].'px;padding-right:2px;width:'.$zuobiaoarr[$i]['w'].'px;height:'.$zuobiaoarr[$i]['h'].'px;}'."\n";
					$zuobiaoarr[$i] = $return_str;
				}
				$return_css .= "</style>";
				$zuobiaoarr[strlen($v)-1] .= $return_css;
				$countvalue_arr[0][$k] = implode("", $zuobiaoarr);
			}
		}else{
			foreach($countvalue_arr[0] as $k=>$v){
				$return_css = "<style type='text/css'>";
				$count_html_str = str_replace($v, "|", $count_html_str);
				for($i=0; $i<strlen($v); $i++){
					if(in_array($v[$i],array("0",1,2,3,4,5,6,7,8,9))){
						$zuobiaoarr[$i] = $countposition[$v[$i]][$randnum[$v[$i]][0]];
					}elseif($v[$i] == "%"){
						$zuobiaoarr[$i] = $countposition['%'][$randnum['%'][0]];
					}elseif($v[$i] == "-"){
						$zuobiaoarr[$i] = $countposition['-'][$randnum['-'][0]];
					}elseif($v[$i] == "."){
						$zuobiaoarr[$i] = $countposition['.'][$randnum['.'][0]];
					}elseif($v[$i] == ","){
						$zuobiaoarr[$i] = $countposition[','][$randnum[','][0]];
					}
					$return_str = "";
					$classrand = random_str(6);
					$return_str .= "<qjjrzklc class='".$classrand."'></qjjrzklc>";
					$return_css .= '.'.$classrand.'{background:url('.url().'public/runtime/'.$file_array['img_url'].') no-repeat '.$zuobiaoarr[$i]['x'].'px '.$zuobiaoarr[$i]['y'].'px;padding-right:2px;width:'.$zuobiaoarr[$i]['w'].'px;height:'.$zuobiaoarr[$i]['h'].'px;}'."\n";
					$zuobiaoarr[$i] = $return_str;
				}
				$return_css .= "</style>";
				$zuobiaoarr[strlen($v)-1] .= $return_css;
				$countvalue_arr[0][$k] = implode("", $zuobiaoarr);
			}
		}
	
		$countvalue_arr1 = explode("|",$count_html_str);
		$return = "";
		foreach($countvalue_arr1 as $k=>$v){
			$return .= $v;
			if($k<count($countvalue_arr1)){
				$return .= $countvalue_arr[0][$k];
			}
		}
		 
		$countvalue_html_arr = explode("❤",$return);
		$return_num = "";
		foreach($countvalue_html_arr as $k=>$v){
			$return_num .= $v;
			if($k < count($countvalue_html_arr)){
				$return_num .= $count_html[0][$k];
			}
		}
		return $return_num;
	}
	
	//生成包括大小写字母加数字随机数
	function random_str($length)
	{
		//生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
		$arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
	
		$str = '';
		$arr_len = count($arr);
		for ($i = 0; $i < $length; $i++)
		{
		$rand = mt_rand(0, $arr_len-1);
		$str.=$arr[$rand];
		}
		$str .= time();
		$now_time = explode(" ",microtime());
		$time_arr = explode(".",$now_time[0]);
		$str .= $time_arr[1];
		return "qj".$str;
	}
	
	/**
	 * 字符截取 支持UTF8/GBK
	 * @param $string
	 * @param $length
	 * @param $dot
	 */
	function str_cut($string, $length, $dot = '...',$charset = 'utf-8') {
		$strlen = strlen($string);
		if($strlen <= $length) return $string;
		$string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
		$strcut = '';
		if($charset == 'utf-8') {
			$length = intval($length-strlen($dot)-$length/3);
			$n = $tn = $noc = 0;
			while($n < strlen($string)) {
				$t = ord($string[$n]);
				if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1; $n++; $noc++;
				} elseif(194 <= $t && $t <= 223) {
					$tn = 2; $n += 2; $noc += 2;
				} elseif(224 <= $t && $t <= 239) {
					$tn = 3; $n += 3; $noc += 2;
				} elseif(240 <= $t && $t <= 247) {
					$tn = 4; $n += 4; $noc += 2;
				} elseif(248 <= $t && $t <= 251) {
					$tn = 5; $n += 5; $noc += 2;
				} elseif($t == 252 || $t == 253) {
					$tn = 6; $n += 6; $noc += 2;
				} else {
					$n++;
				}
				if($noc >= $length) {
					break;
				}
			}
			if($noc > $length) {
				$n -= $tn;
			}
			$strcut = substr($string, 0, $n);
			$strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
		} else {
			$dotlen = strlen($dot);
			$maxi = $length - $dotlen - 1;
			$current_str = '';
			$search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
			$replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
			$search_flip = array_flip($search_arr);
			for ($i = 0; $i < $maxi; $i++) {
				$current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
				if (in_array($current_str, $search_arr)) {
					$key = $search_flip[$current_str];
					$current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
				}
				$strcut .= $current_str;
			}
		}
		return $strcut.$dot;
	}
	
	/*
	*
	* 替换文章里面的数字
	*
	*/
	function replace_num($content){
	//$content = strip_tags($content);//现在的字符串
		$parrten = '/[[:punct:]]|\d+|\。|\、|\，|\（|\）|\《|\》|\？|\！|\；|\：|\“|\”|\‘|\’|\——|\【|\】|\~|\·/i';
    preg_match_all($parrten, $content, $arr);//匹配数字
		$num_grep = "";
		$con_arr = "";
		for($i=0;+ $i<count($arr[0]); $i++){
		$num_preg = countimg($arr[0][$i],8);//替换的结果
		$con_area = explode($arr[0][$i], $content);//以要替换的字符分割
		$len_no_str = strlen($con_area[0]);//要替换的字符分割前面的字符数
		$len_total = strlen($content);//字符串总长度
		$once_before_num = $len_no_str + strlen($arr[0][$i]);//每一次循环前面字符串的长度
		$content_arr = substr($content,$once_before_num,($len_total - $once_before_num));//截取后面的内容
		$con_arr .= $con_area[0].$num_preg;//先把当前数字替换掉
		$content = $content_arr;
		}

		$content_arr_str = $con_arr.$content;//返回过来现在的字符串

		return $content_arr_str;
	}

	//插入一段字符串
		function str_insert($str, $i, $substr){
		for($j=0; $j<$i; $j++){
		$startstr .= $str[$j];
	}
	for ($j=$i; $j<strlen($str); $j++){
		$laststr .= $str[$j];
	}
	$str = ($startstr . $substr . $laststr);
	return $str;
	}
	
	
	
	/*
	 *
	* 链接方法
	*
	*/
	function url_return($code_arr,$code,$value){
		//$code是传过来的字母，$value是传过来的值
		if($code_arr){
			$url_arr = "";
			for($i = 0; $i < count($code_arr); $i++){
				 
				if($code && $code == $code_arr[$i]){
					if($value > 0 || $value != "all" || $value != ""){
			            $url_arr .= $code.$value."-";
					}
					continue;
				}
	
				if($_GET[$code_arr[$i]]){
					if($code_arr[$i] == 'p'){
						$url_arr .= "";
					}else{
						$url_arr .= $code_arr[$i].$_GET[$code_arr[$i]]."-";
					}
				}
			}
		}
		$url_arr = substr($url_arr,0,-1);
		return $url_arr;
	}
	function add_admin_log($action){
		$admin_log = array(
			"action" => $action,
			"admin_id" => $GLOBALS['admin']['id'],
			"create_time" => time(),
			"ip" => get_client_ip()
		);
		$GLOBALS['db']->autoExecute(DB_PREFIX."admin_log",$admin_log,"INSERT");
	}
	
	
	function get_nav($nav){
		if($nav['module'] == 'nav'){
			return $nav['guide'];
		}else{
			$module = ucwords($nav['module']);
			
			if($nav['guide'] > 0){
				$url = url($module,"id=".$nav['guide']);
			}else{
				$url = url($module);
			}
			return $url;
		}
	}
	
	/**
	 * 数组 id转key
	 * @param array $data 数组
	 * @param string $id 
	 * @param string $name
	 * @return multitype:unknown
	 */
	function getidtokey($data,$id = 'id',$name = "name"){
		$arr = array();
		foreach ($data as $k => $v){
			$arr[$v[$id]] = $v[$name];
		}
		return $arr;
	}
	
	/**
	 * 
	 * 模板方法
	 * 
	 */
	
	
	/**
	 * 
	 * @param unknown $filds 字段
	 * @param unknown $typeid 类型
	 * @param unknown $num 数量
	 */
	function push($filds,$typeid,$num){
		$module = $GLOBALS['db']->getOne("SELECT module FROM ".DB_PREFIX."pushtype WHERE id = $typeid");
		$filds = explode(',',$filds);
		$fild_arr = array();
		foreach ($filds as $val){
			$fild_arr[] = 'n.'.$val;
		}
		$filds = implode(",", $fild_arr);
		$sql = "SELECT $filds FROM ".DB_PREFIX."push p 
				LEFT JOIN ".DB_PREFIX.$module." n ON n.id = p.itemid 
				WHERE n.is_del= 0 AND p.typeid = $typeid  
				ORDER BY p.sort ASC,p.create_time DESC LIMIT $num";
		$list = $GLOBALS['db']->getAll($sql);
		return $list;
	}
	
	function paihang($day = '',$type = 0,$num = 8){
		$time = $_SERVER['REQUEST_TIME'];
		$y = date("Y",$time);
		$m = date("m",$time);
		$d = date("d",$time);
		$w = date("w",$time);
		$t = date("t",$time);
		$times = array(
			"day" => mktime(0, 0, 0, $m, $d, $y),
			"week" => mktime(0, 0 , 0,$m,$d-$w+1,$y),
			"month" => mktime(0, 0 , 0,$m,1,$y),
			"year" => mktime(0,0,0,1,1,$y)
		);
		$where = " WHERE g.is_del = 0 ";
		$orderby = " ORDER BY ";
		$limit = " LIMIT $num";

		if($day){
			$where .= " AND  d.updatetime > ".$times[$day];
			$orderby .= " d.".$day."_num DESC, ";
		}
		if($type){
			$where .= " AND g.typeid = $type ";
		}
		$orderby .= " d.num DESC ";
		$sql = "SELECT g.id,g.name,g.logo,t.name as typename,d.num FROM ".DB_PREFIX."game g
				LEFT JOIN ".DB_PREFIX."gametype t ON g.typeid = t.id
				LEFT JOIN ".DB_PREFIX."download d ON g.id = d.game_id $where $orderby $limit";
		
		$list = $GLOBALS['db']->getAll($sql);
		
		return $list;
	}
	
	function adv($type,$num = 1){
		$advtype = $GLOBALS['db']->getRow("SELECT width,height,temp FROM ".DB_PREFIX."advtype WHERE id = $type");
		
		if(!$advtype){
			return '';
		}
		$advs = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."adv WHERE type = $type ORDER BY create_time DESC LIMIT $num");
		$result = "";
		if($advtype['temp']){
			foreach ($advs as $k => $v){
				$str = $advtype['temp'];
				foreach ($v as $kk => $vv){
					$str = str_replace("$".$kk,$vv,$str);
				}
				$result .= $str;
			}
		}else{
			foreach ($advs as $k => $v){
				//
				$result .= '<a href="'.$v["url"].'" target="_blank"><img src="'.$v["img"].'" alt="'.$v["title"].'"> </a>';
			}
		}
		echo $result;
	}
	
	//生成缩略图地址
	function get_img_url($img_url,$width = 0,$height = 0,$type = 0){
		if(substr($img_url,1,6) == 'public' && ($width || $height) && $type){
			$ext = strrchr( $img_url , '.' );
			$img_url = "/thumb/".basename($img_url,strrchr( $img_url , '.' ))."-".$width."-".$height;
			if($type){
				$img_url .= '-'.$type;
			}
			$img_url .= $ext;
		}
		return $img_url;
	}
	
	
	
	function my_tags($tags){
		$arr = explode(PHP_EOL, $tags);
		$str = str_replace("|", "<span>|</span>", $str);
		return $str;
	}
	/**
	 * 去除Html所有标签、空格以及空白
	 * @param unknown $string
	 * @param unknown $sublen
	 * @return string
	 */
	function cutstr_html($string, $sublen){
		$string = strip_tags($string);
		$string = trim($string);
		$string = ereg_replace("\t","",$string);
		$string = ereg_replace("\r\n","",$string);
		$string = ereg_replace("\r","",$string);
		$string = ereg_replace("\n","",$string);
		$string = ereg_replace(" ","",$string);
		return trim($string);
	}
	
	function isMobile(){
		$useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
	
		$mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
		$mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
	
		$found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||
		CheckSubstrs($mobile_token_list,$useragent);
	
		if ($found_mobile){
			return true;
		}else{
			return false;
		}
	}
	function CheckSubstrs($substrs,$text){
		foreach($substrs as $substr)
		if(false!==strpos($text,$substr)){
			return true;
		}
		return false;
	}
	
	/**
	 * 关键词处理
	 */
	function get_keywords($keywords){
		$keywords = preg_split( "/(\s|,|，)/", $keywords );
		$keywords = array_filter($keywords);  //去空
		$keywords = array_unique($keywords);  //去重
		return implode(',',$keywords);
	}
?>
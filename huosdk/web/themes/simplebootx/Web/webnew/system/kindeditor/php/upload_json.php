<?php
/**
 * KindEditor PHP
 *
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 *
 */

require_once 'JSON.php';
require_once '../../../system/common.php';
require_once ROOT_PATH.'system/ftp/ftp.php';

//查询ftp配置
$ftp_sql = "SELECT * FROM ".DB_PREFIX."system_upload_conf";
$ftp_arr = $GLOBALS['db']->getRow($ftp_sql);

$syimg = new_addslashes(trim($_POST['syimg'])) != "" ? new_addslashes(trim($_POST['syimg'])) : "shuiyin.png";

$watermarkurl = ROOT_PATH."/public/uploadfile/".$syimg;

if($ftp_arr){
	if($ftp_arr['upload_is_open'] == 1){
	    $php_path = $ftp_arr['upload_site'];
	    if($ftp_arr['upload_pasv'] == 1){
	    	$upload_pasv = true;
	    }else{
	    	$upload_pasv = false;
	    }
	    $ftps = new ftp();
	    $ftps->connect($ftp_arr['upload_url'], $ftp_arr['upload_name'], $ftp_arr['upload_pwd'],$ftp_arr['upload_port'], $upload_pasv);
	   
	    $php_url = $ftp_arr['upload_showsite'];
	    //文件保存目录路径
	    $save_path = $php_path;
	    //文件保存目录URL
	    $save_url = $php_url.$php_path;
	}else{
	    $php_path = ROOT_PATH."/".$ftp_arr['upload_site'];
	    //文件保存目录路径
	    $save_path = $php_path;
	    //文件保存目录URL
	    $save_url = '/public/uploadfile/';
	}
}else{
    //文件保存目录路径
    $save_path = ROOT_PATH.'public/uploadfile/';
    //文件保存目录URL
    $save_url = '/public/uploadfile/';
}

//定义允许上传的文件扩展名
$ext_arr = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'tiff', 'psd', 'svg'),
	'flash' => array('swf', 'flv'),
	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb','mp4'),
	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', '7z', 'bz2', 'pdf', 'swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb','mp4'),
);
//最大文件大小
if($ftp_arr){
    if($ftp_arr['upload_size'] == 0){
        $max_size = 100000000;
    }else{
        $max_size = $ftp_arr['upload_size']*1000*1000;
    }
}else{
    $max_size = 100000000;
}

if($ftp_arr['upload_is_open'] == 0){
    $save_path = realpath($save_path) . '/';
}

//PHP上传失败
if (!empty($_FILES['imgFile']['error'])) {
	switch($_FILES['imgFile']['error']){
		case '1':
			$error = '超过php.ini允许的大小。';
			break;
		case '2':
			$error = '超过表单允许的大小。';
			break;
		case '3':
			$error = '图片只有部分被上传。';
			break;
		case '4':
			$error = '请选择图片。';
			break;
		case '6':
			$error = '找不到临时目录。';
			break;
		case '7':
			$error = '写文件到硬盘出错。';
			break;
		case '8':
			$error = 'File upload stopped by extension。';
			break;
		case '999':
		default:
			$error = '未知错误。';
	}
	alert($error);
}

//有上传文件时
if (empty($_FILES) === false) {
    
	//原文件名
	$file_name = $_FILES['imgFile']['name'];
	//服务器上临时文件名
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//文件大小
	$file_size = $_FILES['imgFile']['size'];
	//检查文件名
	if (!$file_name) {
		alert("请选择文件。");
	}
	//检查目录
    if($ftp_arr['upload_is_open'] == 1){
    	if (@ftp_chdir($ftps->link,$save_path) === false) {
    		alert("上传目录不存在。");
    	}
	}else{
	    if (@is_dir($save_path) === false) {
	    	alert("上传目录不存在。");
	    }
	}
	//检查目录写权限
	if($ftp_arr['upload_is_open'] == 0){
    	if (@is_writable($save_path) === false) {
    		alert("上传目录没有写权限。");
    	}
	}
	
	//检查是否已上传
	if($ftp_arr['upload_is_open'] == 0){
    	if (@is_uploaded_file($tmp_name) === false) {
    		alert("上传失败。");
    	}
	}
	//检查文件大小
	if ($file_size > $max_size) {
		alert("上传文件大小超过限制。");
	}
	//检查目录名
	$dir_name = empty($_GET['dir']) ? 'images' : trim($_GET['dir']);
	if (empty($ext_arr[$dir_name])) {
		alert("目录名不正确。");
	}
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//检查扩展名
	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
	}
	
	//创建文件夹
	if ($dir_name !== '') {
		$save_path .= $dir_name . "/";
		$save_url .= $dir_name . "/";
		if (!file_exists($save_path)) {
    		if($ftp_arr['upload_is_open'] == 1){
    		    $ftps->mkdir($save_path);
    	    }else{
    	        mkdir($save_path);
    	    }
		}
	}
	$ymd = date("Ymd");
	$save_path .= $ymd . "/";
	$save_url .= $ymd . "/";
	if (!file_exists($save_path)) {
	    if($ftp_arr['upload_is_open'] == 1){
		    $ftps->mkdir($save_path);
	    }else{
	        mkdir($save_path);
	    }
	}
	
	$new_file_size = getimagesize($tmp_name);
	$weight = $new_file_size[0];
	$height = $new_file_size[1];
	
	
	if($_POST['is_watermark'] == 1){
	    outjrzksyimg($weight,$height,$tmp_name,$watermarkurl,$syimg);
	}
	
	//新文件名
	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
	
	
	if($ftp_arr['upload_is_open'] == 1){
		if($ftps->put($save_path.$new_file_name,$tmp_name) === false){
		    alert("上传文件失败。");
		}
		@chmod($file_path, 0644);
		$file_url = $save_url . $new_file_name;
		header('Content-type: text/html; charset=UTF-8');
		$json = new Services_JSON();
		echo $json->encode(array('error' => 0, 'url' => $save_url.$new_file_name));
		exit;
	}else{
    	//移动文件
    	$file_path = $save_path . $new_file_name;
    	if (move_uploaded_file($tmp_name, $file_path) === false) {
    		alert("上传文件失败。");
    	}
    	@chmod($file_path, 0644);
    	$file_url = $save_url . $new_file_name;
    
    	header('Content-type: text/html; charset=UTF-8');
    	$json = new Services_JSON();
    	echo $json->encode(array('error' => 0, 'url' => $file_url));
    	exit;
	}
}


//添加水印图片
function outjrzksyimg($width,$height,$randnumimg,$srcimg,$syimg){
    $src_path = $srcimg;
	$dst_path = $randnumimg;//原图
	
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
		    if($syimg != "shuiyin.png"){
		        imagecopymerge($dst, $src, 300*$i, 300*$j, 0, 0, $src_w, $src_h, 20);
		    }else{
		    	imagecopymerge($dst, $src, 100*$i, 100*$j, 0, 0, $src_w, $src_h, 20);
		    }
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

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
}

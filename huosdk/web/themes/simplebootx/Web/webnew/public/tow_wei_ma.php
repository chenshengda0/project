<?php
	$data = isset($_GET['data']) ? $_GET['data'] : "http://".$_SERVER['HTTP_HOST'];
	$size = isset($_GET['size']) ? $_GET['size'] : '85x85';
	//$logo = isset($_GET['logo']) ? $_GET['logo'] : "http://".$_SERVER['HTTP_HOST'].'/App/Tpl/Common/images/logo-ma.png';
	//print_r($_GET);exit;
	$chl = urlencode($data);
	$png = "http://".$_SERVER['HTTP_HOST']."/public/code.php?data=".$chl;
	$QR = imagecreatefrompng($png);
	if ($logo !== FALSE) {
	  $logo = imagecreatefromstring(file_get_contents($logo));
	  $QR_width = imagesx($QR);
	  $QR_height = imagesy($QR);
	  $logo_width = imagesx($logo);
	  $logo_height = imagesy($logo);
	  $logo_qr_width = $QR_width/5;
	  $scale = $logo_width/$logo_qr_width;
	  $logo_qr_height = $logo_height/$scale;
	  $from_width = ($QR_width-$logo_qr_width)/2;
	  imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
	}
	header('Content-type: image/png');
	imagepng($QR);
	imagedestroy($QR);

	
?>
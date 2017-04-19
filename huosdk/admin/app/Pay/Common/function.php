<?php

function sp_get_url_route(){
	$apps=sp_scan_dir(SPAPP."*",GLOB_ONLYDIR);
	$host=(is_ssl() ? 'https' : 'http')."://".$_SERVER['HTTP_HOST'];
	$routes=array();
	foreach ($apps as $a){
	
		if(is_dir(SPAPP.$a)){
			if(!(strpos($a, ".") === 0)){
				$navfile=SPAPP.$a."/nav.php";
				$app=$a;
				if(file_exists($navfile)){
					$navgeturls=include $navfile;
					foreach ($navgeturls as $url){
						//echo U("$app/$url");
						$nav= file_get_contents($host.U("$app/$url"));
						$nav=json_decode($nav,true);
						if(!empty($nav) && isset($nav['urlrule'])){
							if(!is_array($nav['urlrule']['param'])){
								$params=$nav['urlrule']['param'];
								$params=explode(",", $params);
							}
							sort($params);
							$param="";
							foreach($params as $p){
								$param.=":$p/";
							}
							
							$routes[strtolower($nav['urlrule']['action'])."/".$param]=$nav['urlrule']['action'];
						}
					}
				}
					
			}
		}
	}
	
	return $routes;
}

##日志记录
function data_log($filename,$data){
	if(!file_exists($filename))
		system("cd . > ".$filename);
	$file=fopen($filename,"a+");
	if(!fwrite($file,$data)){
		$time=date("Y-m-d H:i:s");
		fwrite($file,$time." -> 记录错误。\n");
	}
	fclose($file);
}



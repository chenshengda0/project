<?php
	header("Content-type:text/html;charset=utf-8");
	function testfun($filename,$data){
		$file=fopen("/huosdk/admin/log/".$filename,"a+");
		fwrite($file,$data);
		fclose($file);
	}
	testfun("data_log.log","hello\n");

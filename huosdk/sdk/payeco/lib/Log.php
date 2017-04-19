<?php

class Log{
	private static $bLogFlag = false;
	
	/**
	 * 设置记录日志标志
	 * @param $bLogFlag ： true - 记录； 其他 - 不记录
	 */
	static function setLogFlag($bLogFlag)
	{
		self::$bLogFlag = $bLogFlag;
	}
		
	/**
	 * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
	 * 注意：服务器需要开通fopen配置
	 * @param $word 要写入日志里的文本内容 默认值：空值
	 */
	static function logFile($word) {
		if(self::$bLogFlag==true){
			$logdir= substr(dirname(__FILE__), 0, -4).DIRECTORY_SEPARATOR;
			$fp = fopen($logdir."log/log.txt","a");
			flock($fp, LOCK_EX) ;
			fwrite($fp,"执行日期：".gmstrftime("%Y%m%d%H%M%S",time())." -- ".$word."\n");
			flock($fp, LOCK_UN);
			fclose($fp);
		}
	}
}

?>

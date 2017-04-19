<?php
class Cache{
	/**
	*  $value 有值切不为 null 时，生产缓存
	*
	*  $value 为null时， 删除缓存
	*
	*  $value 为空时，切不为null时，读取缓存
	*/
	private $_dir;   //定义缓存默认路径
	const EXT = '.txt';   //文件的后缀名
	public function __construct(){		//初始化文件目录
		$this->_dir = dirname(__FILE__).'/Cache/';   // dirname(__FILE__) 能获取当前文件的目录，把获取的目录给一个变量
		//echo $this->_dir;
	}
	public function cacheDate($key, $value='', $cacheTime = 0){  // $cacheTime == 0 为永久
		$filename = $this->_dir.$key.self::EXT;   //定义文件名，以路径组成
		//写入缓存
		if($value !== ''){	//将文件内容 $value 数据写入缓存
			//删除缓存
			if(is_null($value)){
				return @unlink($filename);
			}
			$dir = dirname($filename);
			if(!is_dir($dir)){	//如果目录不存在，就创建目录    
				mkdir($dir,0777);
			}
			$cacheTime = sprintf('%011d',$cacheTime); //把格式化的字符串写入一个变量中，以时间戳的格式，位数是 11，不满11位数在前面补零			
			return file_put_contents($filename,$cacheTime.json_encode($value));    //写入数据,如果写入成功就返回字节数,失败返回false
		}
		//如果$data不存在，就获取缓存文件内容
		if(!is_file($filename)){
			return false;
		}
		$contents = file_get_contents($filename);
		$cacheTime = (int)substr($contents, 0,11);
		$value = substr($contents, 11);
		if($cacheTime != 0 && $cacheTime + filemtime($filename) < time()){  // filemtime() 函数返回文件内容上次的修改时间
			unlink($filename);
			return false;
		}
		return json_decode($value,true);
	}
}
?>
<?php
/**
 * 用于curl上传的相关方法
 *
 * @author     lixiaohong <2644259148@qq.com>
 */

/**
 * 用于curl上传的相关的方法
 *
 * @author     lixiaohong <2644259148@qq.com>
 */
class Curl
{
    public $error; 
    public $typearr;
    //指定文件名时的默认的合法类型
    public $existtype;
    public $filemaxsize;
    public $imghost;
    public $imgport;
    //保留
    public $data;    
    public $postData;        
    public $defaultdir;
    //过滤提交表单的name值 
    public $filterformarr;
    
    
    /**
    * Short description：构造函数，定义curl连接信息
    *
    */
    public function __construct()
    {
        include dirname(__FILE__).'/../../include/mbcache/config.php';
        $CURL_HOST=$curlconfig[0][0];
        $CURL_PORT=$curlconfig[0][1];
        
        $this->imghost=trim($CURL_HOST);
        $this->imgport=trim($CURL_PORT);
        $this->defaultdir='publicimg';
        $this->typearr=array(
                                'image/jpeg',
                                'image/pjpeg', 
                                'image/png', 
                                'image/x-png', 
                                'image/gif', 
                                'image/bmp', 
                                'application/x-shockwave-flash'
                            );
        $this->existtype=array("jpg", "jpeg", "bmp", "gif", "swf", "png");
        $this->filemaxsize=3*1024*1024; 
    }
    
    /**
    * Short description：文件上传
    *
    * @param array $arr #上传的数组
    *
    * @return array #如果请求成功返回请求后的信息 否则返回false
    */
    public function add($arr=array())
    {
        if (!is_array($arr) || empty($arr)) {
            $this->error=array('1','初始化参数错误');
            return false;
        }
        
        if ($this->passFile($arr)!=true) {
            return false;
        }

        if (!is_array($this->postData) || empty($this->postData)) {
            $this->error=array('4','传递的参数都是空值，请检查');
            return false;
        }
        
        $postData=$this->postData;

        $ch=curl_init();
        $tmp_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT']:'';
        curl_setopt($ch, CURLOPT_URL, 'http://'.$this->imghost.':'.$this->imgport.'/index.php?m=index&a=curl');
        //curl_setopt($ch, CURLOPT_URL,'http://www.lawtime.cn/imageserver/index.php?m=index&a=curl');
        curl_setopt($ch, CURLOPT_USERAGENT, $tmp_user_agent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $data=curl_exec($ch);
        
        curl_close($ch);
        
        //$this->data=$data;
        
        return $data;
        
    }

    /**
     * Short description：获取错误的信息
     *
     * @return array #如果有错误就返回错误信息 否则返回空
     */
    public function getError()
    {
        if (empty($this->error)) {
            return '';
        }
        return $this->error;
    }


    /**
    * Short description：获取请求回来的数据 保留
    *
    * @return array #如果有就返回 否则返回空
    */
    public function getData()
    {
        if (empty($this->data)) {
            return '';
        }
        return $this->data;
    }

    /**
    * Short description：获取错误的信息
    *
    * @param array $arr #验证数组
    *
    * @return array #如果有错误就返回错误信息 否则返回空
    */
    public function passFile($arr=array())
    {
        
        if (!is_array($arr) || empty($arr)) {
            $this->error=array('1', '初始化参数错误');
            return false;
        }
    
        $flag=true;
        
        foreach ($arr as $k=>$v) {
            if (empty($v)) {
                continue;
            }
            if (is_array($v) && !empty($v) && ($this->oneTwoArr($v)=='2') ) {
                $filetotalsize='';
                foreach ($v as $key=>$val) {
                
                    //过滤form表单中的name值 传值才会去验证
                    if (!empty($this->filterformarr)) {
                        if (!in_array($key, $this->filterformarr)) {
                            continue;
                        }
                    }

                    if (is_array($val) 
                        && !empty($val) 
                        && isset($val['name']) 
                        && isset($val['tmp_name']) 
                        && !empty($val['name']) 
                        && !empty($val['tmp_name'])
                    ) {
                    
                        $tmp_type=$val['type'];
                        $tmp_type=strtolower($tmp_type);
                        if (!in_array($tmp_type, $this->typearr)) {
                            $this->error=array('2', '传递上传文件类型不合法，请检查');
                            return false;
                        }
                        $tmp_size=$val['size'];
                        $filetotalsize+=$tmp_size;
                        $tmp_maxsize=$this->filemaxsize/1024/1024;
                        
                        if ($filetotalsize>$this->filemaxsize) {
                            $this->error=array('3', '传递上传文件总大小超过'.$tmp_maxsize.'M，请检查');
                            return false;
                        }
                        $this->postData[$key]='@'.$val['tmp_name']; 
                        $imgtype=pathinfo($val['name'], PATHINFO_EXTENSION);
                        $this->postData["imgtype_".$key]=$imgtype;
                    }
                }
            } else if ( $this->oneTwoArr($v)=='1' ) {
                //处理旧照片
                if ($k=='oldphoto') {
                    $this->oldPhotoDone($v);
                } else if ($k=='extraparam') {
                    //处理额外的参数
                    if ($this->extraDone($v, $k)!=true) {
                        return false;
                    }
                } else {
                    if ($this->passDir($v)!=true) {
                        return false;
                    }
                }
            } else {
                $this->postData[$k]=$v;
            }
            
            $flag=false;
        }
 
        if ($flag==true) {
            $this->error=array('4', '传递的参数都是空值，请检查');
        }
        
        return true;
        
    }
    
     /**
     * Short description：检测上传文件中的原图路径 缩略图路径 缩略图大小是否合格 缩略图的尺寸是否合格
     *
     * @param array  $v #检测数组
     * @param string $k #主要用于imgdir中php与html中传值的冲突
     *
     * @return array  #成功返回true 否则返回false
     */
     
    public function passDir($v=array(),$k='')
    {
        if (!is_array($v) || empty($v) || !($this->oneTwoArr($v)=='1') ) {
            $this->error=array('5', '系统检测路径参数时发生了错误');
            return false;
        }
        
        $k=trim($k);
        
        //在extram中不存在imgdir参数才去表单中的post验证
        if (!$this->postData['imgdir']) {
            $imgdir=$v['imgdir'];
    
            $imgdir=trim($imgdir);
            
            if (is_dir($imgdir)) {
                $this->error=array('6', '图片的存在路径只需填写文件名即可');
                return false;
            } else if (!empty($imgdir)) {
                $this->postData['imgdir']=$imgdir; 
            } else if (!empty($k)) {
                $this->postData['imgdir']=$this->defaultdir;
            }
        }
        
        //在extram中不存在isthumb参数才去表单中的post验证
        if (!$this->postData['isthumb']) {
            $isthumb=$v['isthumb'];
            $isthumb=trim($isthumb);
            if ($isthumb=='1') {
                
                $thumbw=$v['thumbw'];
                $thumbwarr=explode(",", $thumbw);
                
                $thumbh=$v['thumbh'];
                $thumbharr=explode(",", $thumbh);
                
                if ($this->passThumb($thumbwarr, $thumbharr)!=true) {
                    return false;
                }
                
                
                $isfix=$v['isfix'];
                $isfix=trim($isfix);
                if ($isfix=='1') {
                    $this->postData['isfix']='1';
                }   
                
                $this->postData['isthumb']='1';
                $this->postData['thumbw']=$thumbw;
                $this->postData['thumbh']=$thumbh;
            }
        }
        
        //在extram中不存在isthumb参数才去表单中的post验证
        if (!$this->postData['isauto']) {
            $isauto=$v['isauto'];
            $isauto=trim($isauto);
            if ($isauto=='1') {
                $this->postData['isauto']='1';
            }
        }
        
        //在extram中不存在isuniqid参数才去表单中的post验证
        if (!$this->postData['isuniqid']) {
            $isuniqid=$v['isuniqid'];
            $isuniqid=trim($isuniqid);
            if ($isuniqid=='1') {
                $this->postData['isuniqid']='1';
            }
        }
        

        return true;
        
        
    }

    
    /**
    * Short description：处理旧照片
    *
    * @param array $v #旧照片
    *
    * @return array #如果是一维数组 返回字符串1 如果是二维数组返回字符串2
    */
    public function oldPhotoDone($v=array())
    {
        if (empty($v)) {
            return true;
        }
        
        if (is_array($v)) {
            foreach ($v as $key=>$val) {
                if (!empty($val)) {
                    $key=trim($key);
                    //判断是否是正常的文件
                    if (is_file($val)) {
                        //如果下标为数字 提供默认键
                        if (is_numeric($key)) {
                            $this->postData["secondfile".($key+1)]='@'.$val; 
                        } else {
                            $this->postData[$key]='@'.$val; 
                        }
                    } else {
                        $this->postData['photo'.time()."_".$key]=$val; 
                    } 
                }
            }
           
        } else {
            //判断是否是正常的文件
            if (is_file($v)) {
                $this->postData["secondfile"]='@'.$v; 
            } else {
                $this->postData['photo'.time()."_".$key]=$v; 
            }
           
        
        }
   
    }
    
    /**
    * Short description：处理额外的参数
    *
    * @param array  $v #额外的信息
    * @param string $k #主要用于imgdir解决php与html中的冲突
    *
    * @return array #
    */
    public function extraDone($v,$k='')
    {
        if (empty($v)) {
            return true;
        }
        
        
        //指定的文件名 最多支持10个指定的文件名
        for ($i=0; $i<=10; $i++) {
            //对于单张进行处理
            if ($i==0) {
                $existname=$v['existname'];
            } else {
                $existname=$v['existname'.$i];
            }
            $existname=trim($existname);
            if (isset($existname) && !empty($existname) ) {
                $len=strpos($existname, '?v');
                if ($len>1) {
                    $existname=mb_substr($existname, 0, $len);
                }
                $existname=trim($existname);                

                $tmp_existname_arr=explode(".", $existname);
                $tmp_ext=array_pop($tmp_existname_arr);
                $tmp_ext=strtolower($tmp_ext);
                if (in_array($tmp_ext, $this->existtype)) {
                    //对于单张进行处理
                    if ($i==0) {
                        $this->postData['existname']=$existname; 
                    } else {
                        $this->postData['existname'.$i]=$existname; 
                    }
                } else {
                    $this->error=array("7", 'existname'.$i.'指定的文件名参数不合法');
                    return false;
                }
            }
        }
        
        //过滤表单中的name 
        $filterformname=$v['filterformname'];
        $filterformname=trim($filterformname);
        if (isset($filterformname) && !empty($filterformname) ) {
            $filterformarr=explode(",", $filterformname);
            foreach ($filterformarr as $key=>$value) {
                $value=trim($value);
                if (empty($value)) {
                    continue;
                }
                $this->filterformarr[]=$value;
            }
        }
        
        $k=trim($k);
        if ($this->passDir($v, $k)!=true) {
            return false;
        }
        
        return true;
        

    }
    
    /**
    * Short description：检测一个数组是一维还是二维
    *
    * @param array $arr #数组
    *
    * @return array #如果是一维数组 返回字符串1 如果是二维数组返回字符串2
    */
    public function oneTwoArr($arr)
    {
        if (is_array($arr)) {
            foreach ($arr as $k=>$v) {
                if (is_array($v)) {
                    return '2';
                }
                return '1';
            }
        }
        return '3';
    }

    /**
    * Short description：验证缩略图宽与高的合法性
    *
    * @param array $thumbwarr #数组宽
    * @param array $thumbharr #数组高
    *
    * @return array #验证缩略图宽与高
    */
    public function passThumb($thumbwarr=array(),$thumbharr=array())
    {
        if (empty($thumbwarr) || empty($thumbharr)) {
            $this->error=array('7', '图片缩略图的参数传输出错');
            return false;
        }
        
        $count1=count($thumbwarr);
        $count2=count($thumbharr);
        
        if ($count1==0 || $count2==0 || $count1!=$count2) {
            $this->error=array('7', '开启缩略图之后 长宽参数配置出错或者两者数量不等');
            return false;
        }
        
        foreach ($thumbwarr as $k=>$v) {
            $v=trim($v);
            if (empty($v) || !is_numeric($v)) {
                $this->error=array('8', '缩略图中的宽中含非数字的参数');
                return false;
            }
        }
        
        foreach ($thumbharr as $k=>$v) {
            $v=trim($v);
            if (empty($v) || !is_numeric($v)) {
                $this->error=array('9', '缩略图中的高中含非数字的参数');
                return false;
            }
        }
        return true;
    
    }
    
    /**
    * Short description：删除文件
    *
    * @param array $arr #数组
    *
    * @return array #删除文件
    */
    public function delete($arr=array())
    {
        if (!is_array($arr) || empty($arr)) {
            $this->error=array('1', '初始化参数错误');
            return false;
        }
        
        $postData2=$this->postData2($arr);
        if (empty($postData2)) {
            return false;
        }
        
        $ch=curl_init();
        $tmp_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT']:'';
        curl_setopt($ch, CURLOPT_URL, 'http://'.$this->imghost.':'.$this->imgport.'/index.php?m=index&a=curlDel');
        //curl_setopt($ch,CURLOPT_URL,'http://www.lawtime.cn/imageserver/index.php?m=index&a=curlDel');
        curl_setopt($ch, CURLOPT_USERAGENT, $tmp_user_agent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData2);
        $data=curl_exec($ch);
        curl_close($ch);
        
        //$this->data=$data;
        
        return $data;
        
    }
    
    /**
    * Short description：过滤删除的数据
    *
    * @param array $arr #数组
    *
    * @return array #过滤删除的数据
    */
    public function postData2($arr)
    {
        if (!is_array($arr) || empty($arr)) {
            $this->error=array('1', '初始化参数错误');
            return false;
        }
        
        $newarr=array();
        foreach ($arr as $k=>$v) {
            if (empty($v) || is_array($v)) {
                continue; 
            }
            //将索引数组变为关联数组
            $newarr["curl_delete".($k+1)]=$v;
        }
        
        if (!is_array($newarr) || empty($newarr)) {
            $this->error=array('2', '初始化参数只能是一维数组或者都是空值');
            return false;
        }
        
        return $newarr;
    }
    
}

?>
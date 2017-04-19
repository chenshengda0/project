<?php
/**
 * 全站常用工具类
 *
 * @author     wusl <525083980@qq.com>
 */

/**
 * 全站常用工具类
 *
 * @author     wusl <525083980@qq.com>
 */
class tool
{
    /**
     * tool类构造函数
     */
    public function tool()
    {
        //
    }

    /**
     * 获取指定数组打乱
     *
     * @param array  $array 要处理的数组
     * @param string $num   返回数量
     *
     * @return array 处理后的数组
     */
    public static function getrandarray($array, $num)
    {
        $arrcount = count($array);
        if (!$arrcount) {
            return '';
        }
        if ($arrcount < $num) {
            $num = $arrcount;
        }

        $keyarray = array_keys($array);
        shuffle($keyarray);

        for ($i = 0; $i < $num; $i ++) {
            if ($num == 1) {
                $newarray [$i] = $array [$keyarray[0]];
            } else {
                $newarray [$i] = $array [$keyarray [$i]];
            }

        }

        return $newarray;
    }

    /**
     * 获取sql地区区间条件
     *
     * @param string $field 查询的字段名
     * @param int    $area  地区id
     * @param boolen $type  AND或OR的选择
     *
     * @return string 查询地区区间条件
     */
    public static function areaWhere($field, $area, $type = true)
    {
        if (empty( $field) || empty( $area)) {
            return 1;
        }

        $len = strlen($area);
        //省区间
        if ($len == 2) {
            $startArea = $area.'0000';
            $endArea = $area.'9999';
        } elseif ( $len == 4) {//市区间
            $startArea = $area.'00';
            $endArea = $area.'99';
        } elseif ( $len == 6) {
            return " $field = $area ";
        } else {
            return 1;
        }
        if ($type === true) {
            return " ($field >= $startArea AND $field <= $endArea) ";
        } else {
            return " ($field < $startArea OR $field > $endArea) ";
        }
    }

    /**
     * 获取指定KEY值的缓存数据
     *
     * @param object $cache 缓存对象
     * @param string $key   缓存key值
     *
     * @return array 缓存数据
     */
    public static function getCache($cache, $key)
    {
        $data = $cache->get($key);
        return $data;
    }

    /**
     * 异步获取数据
     *
     * @param string $url 数据源链接
     *
     * @return string html代码
     */
    public static function ajaxProcess($url)
    {
        echo '<script type="text/javascript" src="http://js.lawtimeimg.com/min/?f=js/jquery.js"></script>';
        echo '<script type="text/javascript">';
        echo '$.ajax({
                url:"'.$url.'",
                success : function(data){/*alert(data)*/}
            });
        ';
        echo '</script>';
    }

    /**
     * 进行json编码
     *
     * @param array $arr 要编码的数组
     *
     * @return json 编码后的数据
     */
    public static function jsonencode($arr)
    {
        $parts = array ();
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                if ($is_list) {
                    $parts [] = $this->jsonencode($value);
                } else {
                    $parts [] = '"' . $key . '":' . $this->jsonencode($value);
                }
            } else {
                $str = '';
                if (!$is_list) {
                    $str = '"' . $key . '":';
                }
                if (is_numeric($value)) {
                    $str .= $value;
                } elseif ($value === false) {
                    $str .= 'false';
                } elseif ($value === true) {
                    $str .= 'true';
                } else {
                    $str .= '"' . addslashes($value) . '"';
                }
                $parts [] = $str;
            }
        }
        $json = implode(',', $parts);
        return '{' . $json . '}';
    }

    /**
     * 解码json字符串
     *
     * @param json $json 要解码的json数据
     *
     * @return array 解码后的数据
     */
    public static function jsondecode($json)
    {
        $comment = false;
        $out = '$x=';
        for ($i = 0; $i < strlen($json); $i ++) {
            if (!$comment) {
                if ($json [$i] == '{') {
                    $out .= ' array(';
                } elseif ($json [$i] == '}') {
                    $out .= ')';
                } elseif ($json [$i] == ':') {
                    $out .= '=>';
                } else {
                    $out .= $json [$i];
                }
            } else {
                $out .= $json [$i];
            }
            if ($json [$i] == '"') {
                $comment = ! $comment;
            }
        }
        eval($out . ';');
        return $x;
    }

    /**
     * 调试工具方法
     *
     * @return string 输出调试数据
     */
    public static function debug()
    {
        static $start_time = null;
        static $start_code_line = 0;

        $call_info = array_shift(debug_backtrace());
        $code_line = $call_info['line'];
        $file = array_pop(explode('/', $call_info['file']));

        if ($start_time === null) {
            print "debug ".$file."> initialize<br/>\n";
            $start_time = time() + microtime();
            $start_code_line = $code_line;
            return 0;
        }

        printf(
            "debug %s> code-lines: %d-%d time: %.4f mem: %d KB<br/>\n", 
            $file, 
            $start_code_line, 
            $code_line, 
            (time() + microtime() - $start_time), 
            ceil(memory_get_usage()/1024)
        );
        $start_time = time() + microtime();
        $start_code_line = $code_line;
    }

    /**
     * 去掉html标签
     *
     * @param string $str 要处理内容
     *
     * @return string 处理后内容
     */
    public static function html2text($str)
    {
        $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU", "", $str);
        $alltext = "";
        $start = 1;
        for ($i=0; $i<strlen($str); $i++) {
            if ($start==0 && $str[$i]==">") {
                $start = 1;
            } elseif ($start==1) {
                if ($str[$i]=="<") {
                    $start = 0;
                    $alltext .= " ";
                } elseif (ord($str[$i])>31) {
                    $alltext .= $str[$i];
                }
            }
        }
        $alltext = str_replace(" ", "", $alltext);
        $alltext = preg_replace("/&([^;&]*)(;|&)/", "", $alltext);
        $alltext = preg_replace("/[ ]+/s", " ", $alltext);
        return $alltext;
    }

    /**
     * 动态或静态图片域名匹配
     *
     * @param string $url 图片路径格式规范，看文档
     *
     * @return string 带域名规范的图片路径
     */
    public static function imagesReplace($url)
    {
        $host_url = '';
        $static_host_url = array(
                                0 => 'http://img1.lawtimeimg.com',
                                1 => 'http://img2.lawtimeimg.com', 
                                2 => 'http://img3.lawtimeimg.com'
                            );
        $dyn_host_url = array(
                                0 => 'http://d01.lawtimeimg.com', 
                                1 => 'http://d02.lawtimeimg.com', 
                                2 => 'http://d03.lawtimeimg.com'
                            );

        $begin = explode('/', $url);
        switch ($begin[1]) {
        case 'images':
            $host_url = $static_host_url;
            break;
        case 'photo':
        case 'micphoto':
            $host_url = $dyn_host_url;
            break;
        }

        if ($host_url) {
            $name = explode('?', $url);
            $pos = (crc32($name[0])%3);
            $host_url = $host_url[$pos].$url;
            return $host_url;
        } else {
            echo $url;
        }

    }
    
    /**
    * 进一步封装图片上传调用
    * 
    * @param array   $extra    额外的数组数据 如指定文件名
    * @param array   $oldphoto 可以是字符串，也可以是一维数组
    * @param boolean $iserror  是否返回错误
    * @param boolean $debug    是否启动调试
    *
    * @return #
    */
    public static function add($extra='',$oldphoto='',$iserror=false,$debug=false)
    {
        $data = array("extraparam"=>$extra,"oldphoto"=>$oldphoto,$_FILES, 'post'=>$_POST);
        include_once dirname(__FILE__)."/Curl.class.php";
        $curl=new Curl();
        $add=$curl->add($data);
        if ( (!$add) || is_numeric($add) ) {
            
            if ($debug) {
                if (!$add) {
                    //$add是一维数组
                    $debugError=$curl->geterror();
                    print_r($debugError);
                } else if ( is_numeric($add) ) {
                    if ($add=='100') {
                        echo 'no file is up';
                    } else if ($add=='99') {
                        echo 'file fails';
                    }
                }
            }
            
            //返回用户造成的错误
            if ($iserror && (!$add) ) {
                $add=$curl->geterror();
                if (is_array($add) && !empty($add)) {
                    $add_tmp1=trim($add[0]);
                    if (in_array($add_tmp1, array('2', '3'))) {
                        return array('error'=>1, 'info'=>$add[1]);
                    } else {
                        return array('error'=>1, 'info'=>'系统内部发生了错误，请重试');
                    }
                }
            }
            
            return false;
            
        } else {
            $add=self::jsondecode($add);
            $info=$add;
            
            if ($iserror) {
                return array('error'=>0, 'info'=>$info);
            } else {
                return $info;
            }
            
            
        }
        

        
        
    }
    
    
    /**
    * 进一步封装图片删除调用
    * 
    * @param array   $arr   数组
    * @param boolean $debug 是否启动调试
    *
    * @return 如果删除成功返回1 否则0
    */
    public static function delete($arr=array(),$debug=false)
    {
        include_once dirname(__FILE__)."/Curl.class.php";
        $curl=new Curl();
        //删除方法
        $delete=$curl->delete($arr);
        if (!$delete) {
            $info=false;
            if ($debug) {
                $error=$curl->geterror();
                print_r($error);
            }  
        } else {
            $info=true;
        }
        
        return $info;
    }
    
    /**
    * 代替file_get_contents
    * 
    * @param string $strUrl 获取的url内容
    *
    * @return string 所指向链接的内容
    */
    public static function url_get_contents($strUrl)
    {
        $ch = curl_init($strUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        $response = curl_exec($ch);
        if (curl_errno($ch) != 0) {
            return false;
        }
        curl_close($ch);
        return $response;
    }
    
    /**
    * 将类似于这样的字符串micphoto,common,images替换为/micphoto/common/images/
    * 
    * @param string $str       #类似于这样的字符串micphoto,common,images
    * @param string $separator #separator
    * @param string $replace   #replace
    *
    * @return string #替换之后的值 /micphoto/common/images/
    */
    public static function separatorReplace($str, $separator=",", $replace="/")
    {
        $str=trim($str);
        if (empty($str)) {
            return '';
        }
        
        $separator=trim($separator);
        $replace=trim($replace);
        
        $str=ereg_replace($separator, $replace, $str);
        $str=$replace.$str.$replace;
        $str=ereg_replace($replace.$replace, $replace, $str);
        
        return $str;
        
        
    }
    
    
}
?>
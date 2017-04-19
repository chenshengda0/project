<?php

/*
 * 公用函数
 * 说明： 以下代码只提供一个参考。
 */
class Tools {
    
    /**
     * 获取当前毫秒值
     * 
     * @return string
     */
    static function currentTimeMillis() {
        list($usec, $sec) = explode(" ", microtime());
        return $sec . substr($usec, 2, 3);
    }
    
    /**
     * 检查字符串是否为空；如果字符串为null,或空串，或全为空格，返回true;否则返回false
     * 
     * @param str
     * @return
     *
     */
    static function isStrEmpty($str) {
        if (empty($str)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 以字符串的格式取系统时间;格式：YYYYMMDDHHMMSS
     * 
     * @return 时间字符串
     */
    static function getSysTime() {
        return gmstrftime("%Y%m%d%H%M%S", time());
    }
    
    /**
     * 检查字符串是否表示金额，此金额小数点后最多带2位
     * 
     * @param str 需要被检查的字符串
     * @return ： true－表示金额，false-不表示金额
     */
    static function checkAmount($amount) {
        if (amount == null) {
            return false;
        }
        $checkExpressions = "/^[0-9]+(.[0-9]{1,2})?$/";
        return preg_match($checkExpressions, $amount);
    }
    
    /**
     * 对数组排序
     * 
     * @param $para 排序前的数组 return 排序后的数组
     */
    static function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }
    
    /**
     * 实现多种字符编码方式
     * 
     * @param $input 需要编码的字符串
     * @param $_output_charset 输出的编码格式
     * @param $_input_charset 输入的编码格式 return 编码后的字符串
     */
    static function charsetEncode($input, $_output_charset, $_input_charset) {
        $output = "";
        if (!isset($_output_charset))
            $_output_charset = $_input_charset;
        if ($_input_charset == $_output_charset || $input == null) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
        } elseif (function_exists("iconv")) {
            $output = iconv($_input_charset, $_output_charset, $input);
        } else
            die("sorry, you have no libs support for charset change.");
        return $output;
    }
    /**
     * 实现多种字符解码方式
     * 
     * @param $input 需要解码的字符串
     * @param $_output_charset 输出的解码格式
     * @param $_input_charset 输入的解码格式 return 解码后的字符串
     */
    static function charsetDecode($input, $_input_charset, $_output_charset) {
        $output = "";
        if (!isset($_input_charset))
            $_input_charset = $_input_charset;
        if ($_input_charset == $_output_charset || $input == null) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
        } elseif (function_exists("iconv")) {
            $output = iconv($_input_charset, $_output_charset, $input);
        } else
            die("sorry, you have no libs support for charset changes.");
        return $output;
    }
    
    /**
     * 获取XML报文元素，只支持单层的XML，若是存在嵌套重复的元素，只返回开始第一个
     * 
     * @param srcXML ： xml串
     * @param element ： 元素
     * @return ： 元素对应的值
     */
    static function getXMLValue($srcXML, $element) {
        $ret = "";
        try {
            $begElement = "<" . $element . ">";
            $endElement = "</" . $element . ">";
            $begPos = strripos($srcXML, $begElement);
            $endPos = strripos($srcXML, $endElement);
            if ($begPos != -1 && $endPos != -1 && $begPos <= $endPos) {
                $begPos += strlen($begElement);
                $ret = substr($srcXML, $begPos, ($endPos - $begPos));
            } else {
                $ret = "";
            }
        } catch (Exception $ex) {
            $ret = "";
        }
        return $ret;
    }
}
?>
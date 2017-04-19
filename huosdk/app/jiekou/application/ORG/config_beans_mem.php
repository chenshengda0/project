<?php
/**
 * memcache
 *
 * @author smile <1610108938@qq.com>
 *
 */

//memcached
$serverIP = isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:(isset($serverIP)?$serverIP:'120.31.134.130')  ;

switch($serverIP){
case '120.31.134.130':
case '120.31.134.134':
case '120.31.134.139':
case '163.177.181.130':
case '163.177.181.134':
case '163.177.181.139':
    $memcachecfginfo = array(
        array('192.168.1.133','61111'),
        array('192.168.1.134','61111'),
        array('192.168.1.135','61111')
    );
    $beansdbcfginfo = array(
        array('192.168.1.133','61112'),
        array('192.168.1.134','61112'),
        array('192.168.1.135','61112')
    );
    break;
case '223.5.21.141':
case '223.5.21.142':
case '223.5.21.138':
case '223.5.21.145':
case '223.5.21.149':
    $memcachecfginfo = array(
        array('192.168.1.138', '61111'),
        array('192.168.1.138', '61113'),
        array('192.168.1.139', '61111'),
        array('192.168.1.139', '61113')
    );
    $beansdbcfginfo = array(
        array('192.168.1.142','61112'),
        array('192.168.1.138','61112')
    );
    break;
case '192.168.1.218':
case '192.168.3.234':
case '127.0.0.1':
    $memcachecfginfo = array(
        array('192.168.1.218', '61111'),
    );
    $beansdbcfginfo = array(
        array('192.168.1.218', '61112')
    );
    break;
case '192.168.1.219':
    $memcachecfginfo = array(
         array('192.168.1.219', '61111')
    );
    $beansdbcfginfo = array(
         array('192.168.1.219', '61112')
    );
    break;
case '192.168.1.220':
    $memcachecfginfo = array(
        array('192.168.1.220', '61111'),
    );
    $beansdbcfginfo = array(
        array('192.168.1.218', '61112')
    );
    break;
case '192.168.3.236':
    $memcachecfginfo = array(
         array('192.168.3.236', '61111')
    );
    $beansdbcfginfo = array(
         array('192.168.3.236', '61112')
    );
    break;
default:
    $memcachecfginfo = array(
        array('192.168.1.218', '61111'),
        array('192.168.1.217', '11511')
    );
    $beansdbcfginfo = array(
        array('192.168.1.218', '61112')
    );
    break;

}
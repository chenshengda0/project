<?php
include '../system/phpqrcode/phpqrcode.php';
$level = 'H';
$size = 6;
$data = isset($_GET['data']) ? $_GET['data'] : 'http://192.168.1.220';
QRcode::png($data, false, $level, $size, 2);exit;
 ?>
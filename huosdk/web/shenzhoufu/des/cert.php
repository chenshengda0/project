<?php
/**
 * 神州付证书加密
 */
$fp = fopen("C:\ShenzhoufuPay.cer", "r");
$cert = fread($fp, 8192);
fclose($fp);
$pubkeyid = openssl_get_publickey($cert);

//b0ddcd3551b5461fe053fadd0285842f,
//ZD2I3kyyT/j+xYtL+jTw+jEv4ZxrmmcQquSzvIWghVvGuQ79XoZaZ9f62WvlUHRxxMK9B8MpBQspcbShsLc/geIwti6e+UGfa8qTwp5IkgJ6Z+F5ymhu4tXzcjmM7/HlNdjElj8osXydtZ0zrMdge627/sWumpOZTFITIUXk4kI=
$data="b0ddcd3551b5461fe053fadd0285842f";
$signature="ZD2I3kyyT/j+xYtL+jTw+jEv4ZxrmmcQquSzvIWghVvGuQ79XoZaZ9f62WvlUHRxxMK9B8MpBQspcbShsLc/geIwti6e+UGfa8qTwp5IkgJ6Z+F5ymhu4tXzcjmM7/HlNdjElj8osXydtZ0zrMdge627/sWumpOZTFITIUXk4kI=";

// state whether signature is okay or not
$ok = openssl_verify($data,base64_decode($signature),$pubkeyid,OPENSSL_ALGO_MD5);

while ($msg = openssl_error_string()){
    echo $msg . "<br/>\n";
}

if ($ok == 1) {
	echo "good";
} elseif ($ok == 0) {
	echo "bad";
} else {
	echo $ok;
}
// free the key from memory
openssl_free_key($pubkeyid);

?>
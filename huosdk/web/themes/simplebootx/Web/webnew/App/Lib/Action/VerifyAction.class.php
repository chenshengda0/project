<?php
    /*
     *  qjcms
     * 
     */
    class VerifyAction extends CommonAction {
       function index(){
           $length = isset($_GET['length']) ? intval($_GET['length']) : 4;
           $width = isset($_GET['width']) ? intval($_GET['width']) : 100;
           $height = isset($_GET['height']) ? intval($_GET['height']) : 30;
           import('ORG.Util.Image');
           ob_end_clean();
           Image::buildImageVerify($length,5,"png",$width,$height,"verify");
       } 
    }
?>
<?php
/**
 *    首页轮播图管理
 *
 * @author
 */
namespace Web\Controller;

use Common\Controller\AdminbaseController;

class IndexmanageController extends AdminbaseController {
    /**
     * 显示首页
     */
    public function IndexModify() {
        $iimodel = M('webInfo');
        $indexdata = $iimodel->getField('id, title, url, img', true);
        $this->assign('indexdata', $indexdata);
        $this->display();
    }

    //修改首页数据
    public function indexModify_post() {
        $action = I('post.action');
        if (IS_POST && 'index' == $action) {
            $img = array();
            $img['0'] = $_FILES['imagea'];
            $img['1'] = $_FILES['imageb'];
            $img['2'] = $_FILES['imagec'];
            $img['3'] = $_FILES['imaged'];
            $title = I('post.title');
            $url = I('post.url');
            $iimodel = M('webInfo');
            $time = time();
            for ($i = 0; $i < 4; $i++) {
                $time = $time + 1;
                $data = array();
                if (!empty($img[$i]['name'])) {
                    $imagename = $this->checkImage($img, $time, $i);
                    if (!empty($imagename)) {
                        $data['img'] = $imagename;
                    }
                }
                if (!empty($title[$i])) {
                    $data['title'] = $title[$i];
                }
                if (!empty($url[$i])) {
                    $data['url'] = $url[$i];
                }
                $data['update_time'] = time();
                $data['id'] = $i + 1;
                $id = $iimodel->where(array('id' => $data['id']))->getField('id');
                if (empty($id)) {
                    $result += $iimodel->add($data);
                } else {
                    $result += $iimodel->save($data);
                }
            }
            if ($result) {
                $this->success("修改成功!", U("Indexmanage/IndexModify"));
                exit;
            } else {
                $this->error("修改失败", U("Indexmanage/IndexModify"));
                exit;
            }
        }
        $this->error("参数错误", U("Indexmanage/IndexModify"));
        exit;
    }

    /**
     * 上传图片
     */
    public function checkImage($up_info, $time, $i) {
        $arrType = array('image/jpg', 'image/gif', 'image/png', 'image/bmp', 'image/pjpeg', 'image/jpeg');
        $max_size = '5242880';      // 最大文件限制（单位：byte）
        $upfile = C('UPLOADPATH')."/image/"; //图片目录路径
        //$file = $imagefile;
        $fname = $up_info[$i]['name'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { //判断提交方式是否为POST
            if (!is_uploaded_file($up_info[$i]['tmp_name'])) { //判断上传文件是否存在
                $this->error('文件不存在.');
                exit();
            }
            if ($up_info[$i]['size'] > $max_size) {  //判断文件大小是否大于500000字节
                $this->error("上传文件太大.");
                exit();
            }
            if (!in_array($up_info[$i]['type'], $arrType)) {  //判断图片文件的格式
                $this->error("上传文件格式不对.");
                exit();
            }
            if (!file_exists($upfile)) {  // 判断存放文件目录是否存在
                mkdir($upfile, 0777, true);
            }
            $imageSize = getimagesize($up_info[$i]['tmp_name']);//图片大小
            $img = $imageSize[0].'*'.$imageSize[1];
            $ftypearr = explode('.', $fname);
            $ftype = $ftypearr[1];//图片类型
            $fname = $time.'index.'.$ftype;
            $picName = $upfile.$fname;
            if (file_exists($picName)) {
                $this->error("同文件名已存在.");
                exit();
            }
            if (!move_uploaded_file($up_info[$i]['tmp_name'], $picName)) {
                $this->error("移动文件出错.");
                exit();
            }
        }

        return $fname;
    }
}
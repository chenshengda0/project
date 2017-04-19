<?php

/**
 * GametypeController.class.php UTF-8
 * 游戏管理页面
 * @date: 2016年4月8日下午3:05:03
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@1tsdk.com>
 * @version : 1.0
 */
namespace Newapp\Controller;

use Common\Controller\AdminbaseController;

class GametypeController extends AdminbaseController {
    protected $gt_model;
    function _initialize() {
        parent::_initialize();
        $this->gt_model = M('game_type');
    }
    
    /**
     * 游戏类型列表
     */
    public function index() {
        $this->gtList();
        $this->gtStatus();
        $this->display();
    }
    
    /**
     * 添加游戏类型
     */
    public function add() {
        $this->gtStatus(1);
        $this->display();
    }
    
    /**
     * 编辑游戏类型
     */
    public function edit() {
        $type_id = intval(I('get.id'));
        if ($type_id > 0) {
            $typedata = $this->gt_model->where(array(
                'id' => $type_id 
            ))->find();
            $this->assign($typedata);
            $this->display();
        } else {
            $this->error("参数错误");
        }
    }
    
    /**
     * 游戏类型列表
     */
    public function gtList() {
        $field = "id, name, status, image";
        $items = $this->gt_model->field($field)->select();
        $this->assign('gtypes', $items);
    }
    
    /**
     * 渠道添加游戏
     */
    public function add_post() {
        if (IS_POST) {
            /* 获取POST数据 */
            $gt_data['name'] = trim(I('post.gt_name'));
            $gt_data['status'] = I('post.gt_status');
            $gt_data['image'] = I('post.gt_image');
            
            /* 检测输入参数合法性, 游戏名 */
            if (empty($gt_data['name'])) {
                $this->error("游戏类型为空，请填写游戏类型");
                exit();
            }
            
//             /* 检测输入参数合法性, 游戏LOGO */
//             if (empty($imagefile['name'])) {
//                 $this->error("游戏类型图片为空!");
//                 exit();
//             } else {
//                 $imageinfo = $this->upload($imagefile, C('LOGOPATH'), $game_data['create_time']);
//                 if (0 == $imageinfo['status']) {
//                     $this->error($imageinfo['msg']);
//                     exit();
//                 }
                
//             }
            
            $lastInsId = $this->gt_model->add($gt_data);
            
            if ($lastInsId) {
                $this->success("添加成功", U("Gametype/index"));
            } else {
                $this->error("添加失败");
            }
            exit();
        } else {
            $this->error("参数错误");
        }
    }
    public function edit_post() {
        if (IS_POST) {
            /* 获取POST数据 */
            $gt_data['id'] = I('post.gt_id', 0, intval);
            if ($gt_data['id'] > 0) {
                $gt_data['name'] = trim(I('post.gt_name'));
                $gt_data['status'] = I('post.gt_status');
                $gt_data['image'] = I('post.gt_image');
                /* 检测输入参数合法性, 游戏名 */
                if (empty($gt_data['name'])) {
                    $this->error("游戏类型为空，请填写游戏类型");
                    exit();
                }
                
                $rs = $this->gt_model->save($gt_data);
                if ($rs) {
                    $this->success("修改成功", U("Gametype/index"));
                    exit();
                } else {
                    $this->error("修改失败");
                    exit();
                }
            }
        }
        $this->error("参数错误");
    }
    
    /**
     * 删除
     */
    function delete() {
        $id = I("get.id", 0, "intval");
        if ($this->gt_model->delete($id) !== false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    
    /**
     * 图片上传类
     * @date: 2016年4月9日上午11:26:50
     * 
     * @param NULL
     * @return NULL
     * @since 1.0
     */
    public function upload($up_info, $savePath, $name) {
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->exts = array(
            'jpg', 
            'png', 
            'jpeg' 
        ); // 设置附件上传类型
        $upload->rootPath = C('UPLOADPATH'); // 设置附件上传根目录
        $upload->savePath = $savePath.'/'; // 设置附件上传（子）目录
        $upload->saveName = $name;
        $upload->autoSub = false;
        $upload->replace = true;
        
        $info = $upload->uploadOne($up_info);
        
        /* 上传错误提示错误信息 */
        if (!$info) {
            $return['status'] = 0;
            $return['msg'] = $upload->getError();
        } else {
            /* 上传成功 */
            $return['status'] = 1;
            $return['msg'] = C("TMPL_PARSE_STRING.__UPLOAD__"). $info['savepath'].$info['savename'];
        }
        return $return;
    }
    
    /**
     * 游戏类型所有状态
     * @date: 2016年4月8日下午6:07:08
     * 
     * @param option 参数选项
     * @return NULL
     * @since 1.0
     */
    public function gtStatus($option = NULL) {
        $gtstatus = array(
            '0' => "全部状态", 
            '2' => "显示", 
            '1' => "隐藏" 
        );
        
        if (1 == $option) {
            $gtstatus = array(
                '2' => "显示", 
                '1' => "隐藏" 
            );
        }
        $this->assign("gtstatus", $gtstatus);
        return;
    }
}

?>
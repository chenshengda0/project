<?php

/**
 * CdkeyController.class.php UTF-8
 * 激活码管理
 * @date: 2016年9月11日下午11:28:35
 * 
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author : wuyonghong <wyh@huosdk.com>
 * @version : H5 2.0
 */
namespace Sdk\Controller;

use Common\Controller\AdminbaseController;

class CdkeyController extends AdminbaseController
{
    protected $game_model, $gift_model, $gfc_model;
    function _initialize() {
        parent::_initialize();
        $this->game_model = D("Common/Game");
        $this->gift_model = M('cdkey');
        $this->gfc_model = M('cdkey_code');
    }
    
    /**
     * 礼包列表
     */
    public function giftlist() {
        $this->_game();
        $this->_gfList();
        $this->display();
    }
    
    /**
     * *礼包列表
     */
    public function _gfList() {
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $title = I('title');
        $gameid = I('appid');
        $page = 1;
        $offset = ($page - 1) * $rows;
        
        $result = array();
        $where = "is_delete =2";
        
        $where_arr = array();
        if (isset($title) && $title != '') {
            $where .= " and title='%s'";
            array_push($where_arr, $title);
            $this->assign('title', $title);
        }
        
        if (isset($gameid) && $gameid > 0) {
            $where .= " and app_id=%d";
            array_push($where_arr, $gameid);
            
            $this->assign('appid', $gameid);
        }
        
        $result["total"] = $this->gift_model->where($where, $where_arr)->count();
        
        $page = $this->page($result["total"], $rows);
        
        $giftlist = $this->gift_model->where($where, $where_arr)->order("id DESC")->limit(
                $page->firstRow . ',' . $page->listRows)->select();
        
        $this->assign('giftlist', $giftlist);
        $this->assign("Page", $page->show('Admin'));
    }
    
    /**
     * 删除礼包
     */
    public function del() {
        $gift_id = I('id/d');
        
        if ($gift_id > 0) {
            // 伪删除信息
            $rs = $this->gift_model->where("id=%d", $gift_id)->setField('is_delete', 1);
            if ($rs) {
                $this->success("删除成功", U("Cdkey/giftlist"));
                exit();
            } else {
                $this->error("删除失败");
                exit();
            }
        }
        $this->error("参数错误");
    }
    public function add() {
        $this->_game(false);
        $this->display();
    }
    
    /**
     * 添加礼包
     */
    public function add_post() {
        if (IS_POST) {
            // 获取数据
            $gf_data['app_id'] = I('appid/d');
            $gf_data['title'] = I('title', '');
            $gf_data['content'] = I('content', '');
            $gf_data['start_time'] = strtotime(I('starttime'));
            $gf_data['end_time'] = strtotime(I('endtime'));
            $gf_data['create_time'] = time();
            
            if (empty(
                    $gf_data['app_id']) || empty($gf_data['title']) || empty($gf_data['end_time']) ||
                 empty($gf_data['start_time'])) {
                $this->error("请填写完数据后再提交");
            }
            
            if (0 == $gf_data['app_id']) {
                $this->error("请选择正确的游戏");
            }
            
			//插入数据
			$code = I('code/s','');
			$codearr = explode("\n", $code);
            foreach ($codearr as $val) {
                if (empty($val)){
                    continue;
                }
                $dataList[] = array('gf_id'=>$gf_id,'code'=>$val);
            }
			$gf_data['total'] = count($dataList);
			$gf_data['remain'] = $gf_data['total'];
			if (empty($gf_data['total'])){
			    $this->error("请填写正确礼包码");
			}
			
			if($this->gift_model->create($gf_data)){ 
				$gf_id = $this->gift_model->add();	
                foreach($dataList as $k => $v){
                    $dataList[$k]['gf_id'] = $gf_id;
                }                    
                $this->gfc_model->addAll($dataList);		
				$this->success("添加成功!", U("Cdkey/giftlist"));
				exit;
			} else { 
				$this->error("添加失败");
				exit;
			} 
	    }
	    $this->error("参数错误");
    }
    public function edit() {
		$id= I("get.id/d",0);
		if (empty($id)){
		    $this->error("参数错误");
		}
        $map['id'] = $id;
		
		$giftlist=$this->gift_model->where($map)->find();
		$list = $this->gfc_model->field("code")->where(array("gf_id"=>$id))->select();
		foreach ($list as $k=>$v) {
            $codestr .= $v['code'] . "\n";
        }
        $giftlist['code'] = $codestr;
        $this->_game();
        $this->assign($giftlist);
        $this->display();
    }
    
    /**
     * 修改礼包
     */
    public function edit_post() {
        $gf_id = I('id/d');
        if (empty($gf_id)) {
            $this->error("参数错误");
        }
        
        // 获取数据
        $gf_data['id'] = $gf_id;
        $gf_data['app_id'] = I('appid');
        $gf_data['title'] = I('title');
        $gf_data['content'] = I('content');
        $gf_data['start_time'] = strtotime(I('starttime'));
        $gf_data['end_time'] = strtotime(I('endtime'));
        $gf_data['update_time'] = time();
        
        // 修改数据
        if ($this->gift_model->create($gf_data)) {
            $update = $this->gift_model->save(); // update
            if ($update) {
                $this->success("更新成功!", U("Cdkey/giftlist"));
                exit();
            }
        }
        $this->error("修改失败");
        exit();
    }
}
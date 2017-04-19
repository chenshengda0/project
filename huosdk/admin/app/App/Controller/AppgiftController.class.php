<?php
/**
* 礼包管理中心
*
* @author
*/
namespace App\Controller;
use Common\Controller\AdminbaseController;

class AppgiftController extends AdminbaseController {
	
	protected $game_model;
	
	function _initialize() {
		parent::_initialize();
		$this->game_model = D("Common/Appgame");
	}

	/**
	 * 礼包列表
	 */
	public function giftList(){
		$this->giftNewList();
		$this -> display();
	}
	/**
	**礼包列表
	*/
	public function giftNewList(){
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$title = I('title');
		$gameid = I('appid');
		$page = 1;
		$offset = ($page-1)*$rows;
		
		$result = array();
		$where = " isdelete = 0 ";
		
		$where_arr = array();
		if (isset($title) && $title != '') {
			$where .= " and title='%s'";
			array_push($where_arr,$title);
			$this->assign('title',$title);
		}
		
		if (isset($gameid) && $gameid >0) {
			$where .= " and appid=%d";
			array_push($where_arr,$gameid);

			$this->assign('appid',$gameid);
		}
		
		$giftinfo = M('applibaoinfo');
		$result["total"] = $giftinfo->where($where,$where_arr)->count();
		
		$page = $this->page($result["total"], $rows);
		
		$field = "id,gameid,title,content,endtime,isdelete,create_time,total,starttime";

		$giftlist = $giftinfo->field($field)->where($where,$where_arr)->order("id DESC")->limit($page->firstRow . ',' . $page->listRows)->select();
			
		$game_src = $this->game_model->select();

		$games=array();
		foreach ($game_src as $g){
			$gameid=$g['id'];
			$games["$gameid"]=$g;
		}
		$this->assign('giftlist', $giftlist);
		$this->assign('games', $games);
		$this->assign("page", $page->show('Admin'));
	}
	
	
	/**
	 * 
	 * 删除礼包
	 */
	public function delGift() {
		$gift_id = I('id');
		
		if($gift_id != ''){
			$info = M('applibaoinfo');
			
			$data['isdelete'] = 1;
			//伪删除信息
			$rs = $info -> where("id=%d",$gift_id)->data($data)->save();
			
			if ($rs) {
				$this->success("删除成功", U("Appgift/giftList"));
				exit;
			} else {
				$this->error("删除失败");
				exit;
			}
		}
	}
	
	public function addGift(){
		$games=$this->game_model->order("id desc")->select();
		$this->assign("games",$games);
		$this -> display();
	}

	/**
	 * 添加礼包
	 */
	public function addgift_post(){
			//获取数据
			$libao_data['gameid'] = I('gameid');
			$libao_data['title'] = I('title');
			$libao_data['content'] = I('content');
			$libao_data['starttime'] = strtotime(I('starttime'));
			$libao_data['endtime'] = strtotime(I('endtime'));
			
			$libao_data['create_time'] = time();

			if(empty($libao_data['gameid']) || empty($libao_data['title']) || empty($libao_data['content'])
				|| empty($libao_data['starttime']) || empty($libao_data['endtime'])){
				$this->error("请填写完数据后再提交");
				exit;
			}
			
			//插入数据
			$libaoinfo = M("applibaoinfo");
			$code = I('code');
			$codearr = explode("\n", $code);
			$total = count($codearr);
			$libao_data['total'] = $total;
			
			if($libaoinfo->create($libao_data)){ 
				$lastInsId = $libaoinfo->add();
				$libao = M('applibao');
				foreach ($codearr as $val) {
					$dataList[] = array('infoid'=>$lastInsId,'code'=>$val);
				}
				
				if(count($dataList) > 0){
					$libao->addAll($dataList);
				}else{
					$this->error("请填写礼包码");
					exit;
				}
				$this->success("添加成功!", U("Appgift/giftList"));
				exit;
			} else { 
				$this->error("添加失败");
				exit;
			} 
		
	}

	public function editGift(){
		$id= intval(I("get.id"));

		$games=$this->game_model->order("id desc")->select();
		$this->assign("games",$games);
		
		$infomodel = M('applibaoinfo');
		$giftlist=$infomodel->where("id=%d",$id)->select();
		
		$libao = M('applibao');
		foreach ($giftlist as $key => $val) {
			$codestr = "";

			$list = $libao->field("code")->where("infoid='".$val['id']."'")->select();
			foreach ($list as $k=>$v) {
				$codestr .= $v['code']."\r\n";
			}

			$giftlist[$key]['code'] = $codestr;	
		}
		
		$this->assign($giftlist[0]);
		$this -> display();
	}

	/**
	 * 修改礼包
	 */
	public function editgift_post(){
			$libao_id = I('id');
			
			//获取数据
			$libao_data['gameid'] = I('gameid');
			$libao_data['title'] = I('title');
			$libao_data['content'] =  I('content');
			$libao_data['starttime'] = strtotime(I('starttime'));
			$libao_data['endtime'] = strtotime(I('endtime'));
			
			$libao_data['create_time'] = time();
			
			/*
			 * 判断是插入还是修改保存
			 */
			if($libao_id != ''){
				//修改数据
				$libaoinfo = M("applibaoinfo");
				if($libaoinfo->create($libao_data))
				{
					$update = $libaoinfo -> where("id = %d ",$libao_id)->save();//update
					
					if($update){
						$this->success("更新成功!", U("Appgift/giftList"));
						exit;
					}
				}
				$this->error("修改失败");
				exit;
			}
	}
}
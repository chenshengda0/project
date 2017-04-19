<?php
/**
* 游戏分包管理页面
*
* @author
*/

namespace Sdk\Controller;
use Common\Controller\AdminbaseController;

class SubpackageController extends AdminbaseController {
	protected $users_model,$role_model,$role_user_model,$game_model,$ag_model,$where;
	
	function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/Users");
		$this->role_model = D("Common/Role");
		$this->role_user_model = M("RoleUser");
		$this->game_model = D("Common/Game");
		$this->ag_model = M('agent_game');
		
		if ($this->role_type == 3) {
		    $this->where = "u.ownerid".$this->agentwhere;
		}else{
		    $this->where = " 1 ";
		}
	}

		public function qudaoindex(){
			//data_log("agent_add_log.log","hellos\n");die;
			//data_log_contents();die;
		if(IS_AJAX){
			//{"typeid":"1","content":"aaa"}
			$data=I('post.');
			if($data['userid']){//按渠道查询结果
				$datas=$this->ag_model->where(array('agent_id'=>$data['userid'],'status'=>2))->select();
				$agents=$this->_agents();
				$games=$this->_game(true, 2, 2);
				$roles=$this->_roles();
				$nickname=M("users")->where(array("id"=>$data["userid"]))->getfield("user_nicename");
				$agent_rule_id=M('role_user')->where(array('user_id'=>$data['userid']))->getfield('role_id');
				$str='';
				foreach ($datas as $ko => $vo) {
					$str.='<tr>
							<td>'.$vo["agentgame"].'</td>
							<td>'.$agents[$vo["agent_id"]].'</td>
							<td>'.$nickname.'</td>
							<td>'.$games[$vo["app_id"]].'</td>
							<td>'.$roles[$agent_rule_id].'</td>
							<td>'.$vo["cpa_price"].'</td>
							<td>'.$vo["agent_rate"].'</td>
							<td>'.date("Y-m-d H:i:s",$vo["create_time"]).'</td>
							<td>'.DOWNSITE."/sdkgame/".$vo["url"].'</td>
							<td><a href="/admin.php/Sdk/Subpackage/updatepackage/id/'.$vo['id'].'" class="js-ajax-dialog-btn" data-msg="确定更新吗？">更新 </a><a href="/admin.php/Sdk/Subpackage/delagentgame/id/'.$vo['id'].'" class="js-ajax-delete"> | 删除</a></td>
							</tr>';
						

				}echo($str);
			} 
			else{//查询返回
				if($data['typeid']==1){
					$user_nicename=M('users')->where(array('user_nicename'=>array('like','%'.$data['content'].'%')))->field('id,user_nicename')->select();
					$ul='<ul style="list-style:none;margin-left: 0px;">';
					foreach ($user_nicename as $key => $value)
						$ul.='<li><a style="width:130px;" class="btn btn-success" onclick="javascript:search_content_type(this,'.$value['id'].');">'.$value['user_nicename'].'</a></li>';
					$ul.="</ul>";
					echo($ul);
				}
				elseif($data['typeid']==2){
					
				}else echo(false);
			}
		}else{
				$this->_agents();

	    		$this->_roles();//Array ( [0] => 全部 [1] => 超级管理员 [2] => 平台管理员 [3] => 平台运营专员 [4] => 平台客服专员 [5] => 平台财务专员 [6] => 渠道专员 [7] => 公会渠道 [8] => CPS渠道 [9] => CPA渠道 )
	    		$this->_game(true, 2, 2);
	    		$this->_aList();
	    		$this->display();
		}
	    
	}
	
	/**
	 * 渠道列表
	 * 
	 * return void
	 */
    public function _aList(){
		$gameid = I('app_id/d');
		$agent_id = I('agent_id/d');

		$where = $this->where.' AND a.status=2 ';
    	$wher_array = array();

    	if (!empty($gameid)) {
			$where .= " AND a.app_id = %d";
			array_push($wher_array,$gameid);
			$this->assign('app_id', $gameid);
    	}
    	
    	if (!empty($agent_id)) {
			$where .= " AND a.agent_id = %d";
			array_push($wher_array,$agent_id);
			$this->assign('agent_id', $agent_id);
    	}
    	
		$field = "a.*,a.id as did, u.user_nicename, u.user_type";

		$count=$this->ag_model
			->alias("a") 
			->join("left join " . C('DB_PREFIX') . "users u ON a.agent_id = u.id")
			->where($where,$wher_array)->count();

		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : $this->row;
	    $page = $this->page($count, $rows);

		$agents =$this->ag_model
		->alias("a") 
		->join("left join " . C('DB_PREFIX') . "users u ON a.agent_id = u.id")
		->field($field)
		->where($where,$wher_array)->order('a.id DESC')
		->limit($page->firstRow . ',' . $page->listRows)
		->select();+
		
		$this->assign("subagents",$agents);
		$this->assign("Page", $page->show('Admin'));
		$this->assign("current_page", $page->GetCurrentPage());
    }
    
	function addagent(){
		if (3 == $this->role_type){
		    $this->_roles(4,false);
		}else{
		    $this->_roles(3,false);
		}
		$this->display();
	}

	/*
	 *添加渠道
	 */
	public function addagent_post(){
	    if(IS_POST){
	        if(!empty($_POST['role_id'])){
	            $role_id = I('role_id');
	            $_POST['user_type'] = $role_id;
	            $_POST['pay_pwd'] = $_POST['user_pass'];
	            unset($_POST['role_id']);
	            if ($this->users_model->create()) {
	                $result=$this->users_model->add();
	                if ($result!==false) {
	                    $uid=$result;
	                    $role_user_model=M("RoleUser");
	                    $role_user_model->where(array("user_id"=>$uid))->delete();
	                    $role_user_model->add(array("role_id"=>$role_id,"user_id"=>$uid));
						 //记录日志
	                    $agent_add_data_log=M("users")->where(array('id'=>$result))->find();
	                    $agent_str='';
	                    $agent_str.=date("Y-m-d H:i:s")."->";
	                    $agent_str.="登陆名：".$agent_add_data_log['user_login'];
	                    $agent_str.="，昵称：".$agent_add_data_log['user_nicename'];
	                    $agent_str.="，联系人：".$agent_add_data_log['linkman'];
	                    $agent_str.="，渠道id：".$agent_add_data_log['id']."添加成功！\n";
						
	                    data_log("agent_add_log.log",$agent_str);//$agent_str
						
	                    $this->success("添加成功！",U('Sdk/Subpackage/qudaoindex'));
	                } else {
	                    $this->error("添加失败！");
	                }
	            } else {
	                $this->error($this->users_model->getError());
	            }
	        }
	    }
	}
	
	//编辑
	function editagent(){
	    $id= intval(I("get.id"));
	    $user=$this->users_model->where(array("id"=>$id))->find();
	    $adminid = sp_get_current_admin_id();
	    if (2 < $this->role_type ){
	        if($adminid != $user['id'] && $adminid != $user['ownid']){
	            $this->error("无权限");
	        }
	    }
	    $this->_roles(2,FALSE);
	    $this->assign($user);
	    $this->display();
	}
	
	function editagent_post(){
	    if (IS_POST) {
	        if(!empty($_POST['role_id'])){
	            if(empty($_POST['user_pass'])){
	                unset($_POST['user_pass']);
	            }
	            $role_id=$_POST['role_id'];
	            $_POST['user_type'] = $role_id;
	            unset($_POST['role_id']);
	            if ($this->users_model->create()) {
	                $result=$this->users_model->save();
	                if ($result!==false) {
	                    $uid=intval($_POST['id']);
	                    $role_user_model=M("RoleUser");
	                    $role_user_model->where(array("user_id"=>$uid))->delete();
	                    $role_user_model->add(array("role_id"=>$role_id,"user_id"=>$uid));
	                    $this->success("修改成功！",U('Sdk/Subpackage/qudaoindex'));
	                } else {
	                    $this->error("修改失败！");
	                }
	            } else {
	                $this->error($this->users_model->getError());
	            }
	        }else{
	            $this->error("请为此用户指定角色！");
	        }
	
	    }
	}
	
	/**
	 * 删除渠道游戏
	 */
	public function delagentgame(){
	    $id = I('id/d',0);
	    $data['status'] = 1;
	    $rs = $this->ag_model->where("id = %d",$id)->save($data);

	    if($rs){
	        $this->success("删除成功");
	        exit;
	    }
	    $this->error('删除失败.');
	}
	
	/**
	*打包
	*/
	public function subpackage(){
	    if (IS_POST){
	        //获取参数
	        $app_id    =   I('post.app_id/d');
	        $agent_id  =   I('post.agent_id/d');
	        $cpa_price =   I('post.cpa_price/f');
	        $rate =   I('post.rate/f');
	        
	        if($agent_id <= 0 || $app_id <= 0 || !(is_float($cpa_price) || is_float($rate))){
	            $this->_ajax_return('请填写完整参数.');
	        }
			
	        $user_type = $this->users_model->where(array('id'=>$agent_id))->getfield('user_type');
	        
	        if (($rate<0.0001 || $rate>1) && $user_type != 9){
	            $this->_ajax_return('分成比例填写错误!');
	        }
			
	        if ($cpa_price<0.01  && $user_type == 9){
	            $this->_ajax_return('cpa价格错误!');
	        }
			
	        $time = time();
	        $ag_info = $this->ag_model->where(array('app_id'=>$app_id, 'agent_id'=>$agent_id))->find();
	        if (empty($ag_info)){
	            $game_info = $this->game_model->where(array('id'=>$app_id))->find();
	            
	            $ag_info['agent_id'] = $agent_id;
	            $ag_info['app_id'] = $app_id;
	            $ag_info['agentgame'] = $game_info['initial'].'_'.$agent_id;
	            $ag_info['create_time'] = $time;
	            $ag_info['cpa_price'] = $cpa_price? $cpa_price:0;
	            $ag_info['agent_rate'] = $rate? $rate:0;
	            
	            $rs = $this->ag_model->add($ag_info);
	            if ($rs){
	                $ag_info['id'] = $rs;
	            }else{
	                $this->_ajax_return('服务器内部错误.');
                }
            }else{
                if (2 != $ag_info['status']){
                    //如果已经删除包，那么就直接显示伪删除的包
                    $this->ag_model->where(array('id'=>$ag_info['id']))->setfield('status',2);
                }
            }
	        $this->_do_package($ag_info['id']);
	    }
		$this->ajaxReturn(array('msg'=>'参数错误！'),'JSON');
	}
	
	function _ajax_return($msg, $option=1){
	    if (1 == $option){
	        $this->ajaxReturn(array('msg'=>$msg),'JSON');
	        exit;
	    }else{
	        $this->error($msg);
	    }
	}
	
	function _do_package($ag_id, $option=1){
	    if (empty($ag_id)){
	        $this->_ajax_return('参数错误ag', $option);
	    }
	    
	    $ag_info = $this->ag_model->where(array('id'=>$ag_id))->find();
	    $game_info = $this->game_model->where(array('id'=>$ag_info['app_id']))->find();
	    
	    $opt = md5(md5($game_info['initial'].$ag_info['agentgame']).'resub');
	    $initial = base64_encode($game_info['initial']);
	    $agentgame = base64_encode($ag_info['agentgame']);
	    $opt = base64_encode($opt);
	    $data_string = array ('p' => $initial, 'a' => $agentgame, 'o' =>$opt);
	    $data_string = json_encode($data_string);
	    
	    $url = DOWNSITE."/subPackage.php";

	    $cnt = 0;
	    while(1){
	        $return_content = base64_decode(self::http_post_data($url, $data_string));
	        if (0 < $return_content || 3 == $cnt){
	            break;
	        }
	        	
	        $cnt ++;
	    }
	    
	    if (0 < $return_content ){
	        $updatedata['url'] = $game_info['initial'].'/'.$ag_info['agentgame'].".apk";;
	        $updatedata['update_time'] = time();
	        $rs = $this->ag_model->where("id=%d",$ag_id)->save($updatedata);
	        if ($option == 1){
			//记录分包结果
	        			$agent_game_add_data_log=$this->ag_model->where(array('id'=>$ag_id))->find();
	                    $agent_str='';
	                    $agent_str.=date("Y-m-d H:i:s")."->";
	                    $agent_str.="分包id：".$agent_game_add_data_log['id'];
	                    $agent_str.="，渠道id：".$agent_game_add_data_log['agent_id'];
	                    $agent_str.="，游戏id：".$agent_game_add_data_log['app_id'];
	                    $agent_str.="渠道标识：".$agent_game_add_data_log['agentgame'];
	                    $agent_str.="，游戏链接：".$agent_game_add_data_log['url']."添加成功！";
	                    $agent_str.="\n";
	                    data_log("app_add_log.log",$agent_str);//$agent_str
	            $this->ajaxReturn(array('success'=>true,'msg'=>'分包成功'),'JSON');
	        }else{
				//记录分包结果
	        			$agent_game_add_data_log=M("agent_game")->where(array('id'=>$ag_id))->find();
						$grt_game_name=M('game')->where(array('id'=>$agent_game_add_data_log['app_id']))->getFields('name');
	                    $agent_str='';
	                    $agent_str.=date("Y-m-d H:i:s")."->";
	                    $agent_str.="分包id：".$agent_game_add_data_log['id'];
	                    $agent_str.="，渠道id：".$agent_game_add_data_log['agent_id'];
	                    $agent_str.="，游戏id：".$agent_game_add_data_log['app_id'];
	                    $agent_str.="，游戏名：".$grt_game_name;
	                    $agent_str.="渠道标识：".$agent_game_add_data_log['agentgame'];
	                    $agent_str.="，游戏链接：".$agent_game_add_data_log['url']."添加成功！";
	                    $agent_str.="\n";
	                    data_log("app_add_log.log",$agent_str);//$agent_str
						
	            $this->success("分包成功");
	        }
	        exit;
	    }else if (-6 == $return_content){
	        $this->_ajax_return("拒绝访问",$option);
	        exit;
	    }else if (-4 == $return_content){
	        $this->_ajax_return("验证错误",$option);
	        exit;
	    }else if(-3 == $return_content){
	        $this->_ajax_return("请求数据为空",$option);
	        exit;
	    }else if(-2== $return_content){
	        $this->_ajax_return("分包失败",$option);
	        exit;
	    }else if(-1 == $return_content){
	        $this->_ajax_return("无法创建文件,打包失败.",$option);
	        exit;
	    }else if(-5 == $return_content){
	        $this->_ajax_return("游戏原包不存在.",$option);
	        exit;
	    }else{
	        $this->_ajax_return("请求数据失败.",$option);
	        exit;
	    }
	    $this->_ajax_return("分包记录添加失败！",$option);
	    exit;
	    
	}
	
	//更新包
	public function updatepackage() {
	    $ag_id= I('get.id/d');
	    
	    if ($ag_id<=0){
	        $this->error("参数错误");
	    }
	    
	    $this->_do_package($ag_id, 2);
	}
	
	/*
	 * 向下载服务器发送请求数据
	 */
	function http_post_data($url, $data_string) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json; charset=utf-8',
				'Content-Length: ' . strlen($data_string))
		);
		ob_start();
		
		curl_exec($ch);
		$return_content = ob_get_contents();
		ob_end_clean();
	
		$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return $return_content;
	}
}
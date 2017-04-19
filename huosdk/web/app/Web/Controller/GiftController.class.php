<?php

/**
 * 礼包中心控制器
 */
namespace Web\Controller;
use Common\Controller\HomebaseController;

class GiftController extends HomebaseController {
	public function _initialize() {
		parent::_initialize ();
	}

	//显示礼包中心的首页
	public function index() {
		$this->_indexdata();
		$this->display ();
	}
	
	public function _indexdata(){
		//接收游戏ID和游戏名的参数
		$app_id = I ( 'gameid' );
		$gamename = I ( 'gamename' );
				
		if (! get_magic_quotes_gpc ()) {
			$app_id = addslashes ( $app_id );
		}

		//判断游戏的名称是否为空
		if (! empty ( $gamename )) {
			//根据接收的游戏名称查询所有游戏的ID参数
			$game_model = M('game');
			$ids = $game_model->where("name like '%".$gamename."%'")->getField('id',true);
			//拼装搜索app_id的条件
			if(!empty($ids)){
				$where['app_id']  = array('in',$ids);				
			}else{
				$where['app_id'] = -1;  //如果游戏不存在，则复制game_id为-1;
			}
		}
		
		//判断是否存在游戏的ID
		if (! empty ( $app_id )) {
			$where['app_id']  = array('eq',$app_id); //游戏的表查询
			$gamewhere['id']  = array('eq',$app_id); //礼包中的表查询
		}
		
		//添加礼包开始时间和截止时间的条件
		//$where['start_time'] = array('elt',time());
		$where['end_time'] = array('egt',time());
		
		//查询根据条件获取游戏的信息和分页的信息
		$list = giftlist ( $where );
		$giftlist = $list ['list'];

		//获取所有的游戏信息
		$glist = findgamelist ( $gamewhere );
		$gamelist = $glist ['list'];
		
		$gameinfodate = M('gameInfo')->getField("app_id,url,androidurl,bigimage",true);

		//把游戏的官网，androidurl路径，图片，礼包个数放到每个礼包的信息下
		foreach ( $giftlist as $key => $val ) {
			$giftlist [$key] ['url'] = $gameinfodate [$val ['app_id']] ['url'];  //获取官网的路径
			$giftlist [$key] ['androidurl'] = $gameinfodate [$val ['app_id']] ['androidurl']; //获取游戏androidurl路径
			$giftlist [$key] ['icon'] = $gameinfodate [$val ['app_id']] ['bigimage']; //获取游戏的图片
			$giftlist [$key] ['gsum'] = codelist ( $val ['id'] ); //每个礼包个数
		}
		$showpage = $list ['showpage'];
		$this->assign ( 'giftlist', $giftlist );
		$this->assign ( 'showpage', $showpage );
		
		//热门游戏列表清单
		$hotgamelist = hotgamelist(); 
		$this -> assign("footgamelist", $hotgamelist);		
	}
	
	public function pcajax() {
		//用户名的检测
		$username = session("user.sdkuser");
		$infoid = I('giftid/d',0);
		
		//礼包的ID
		if (0 != $infoid) {			
			//检查用户名是否存在
			$rs = checkusername($username);
			if ($rs) {
				//查询是否领取过礼包
				$list = checklibao($rs,$infoid);

				if(count($list)>0){
					$code = $list['code'];//礼包码
					$arr = array(
							'a' => $code    //玩家已经领取过礼包
					);
					echo json_encode($arr);
					exit;
				}
				
				//获取礼包码
				$gift = strcode($rs,$infoid);
					
				if($gift){
					$code = $gift['code'];//礼包码
					//记录领取的信息和礼包留存数减一
					$giftres = setlibao($rs,$infoid,$code);
					if(giftres){
						$arr = array(
							'a' => $code
						);
						echo json_encode($arr);
						exit;						
					}
				}
				$arr = array(
					'a' => "3"    //礼包已经发放完
				);
				echo json_encode($arr);
				exit;
			} else {
				$arr = array(
						'a' => "2"    //判断用户未登录或不存在
				);
				echo json_encode($arr);
				exit;
			}
		}else{
			$arr = array(
				'a' => "2"
			);
			echo json_encode($arr);  //判断用户未登录或不存在
			exit;
		}
		
	}
}
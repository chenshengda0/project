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
	public function index() {
		$gameid = I ( 'gameid' );
		$gamename = I ( 'gamename' );
		
		if (! get_magic_quotes_gpc ()) {
			$gameid = addslashes ( $gameid );
		}
		$where = "";
		$gamewhere = "";
		if (! empty ( $gamename )) {
			$alist = findgamelist ( " AND name like'%" . $gamename . "%'" );
			$galist = $alist ['list'];
			$idArr = "";
			foreach ( $galist as $key => $val ) {
				$idArr .= "'" . $val ['id'] . "',";
			}
			$idArr = substr ( $idArr, 0, strlen ( $idArr ) - 1 );
			if (empty($idArr)){
				$where .= " AND appid = ''";
			}else{
				$where .= " AND appid in(" . $idArr . ")";
			}
		}
		if (! empty ( $gameid )) {
			$where .= " AND appid='" . $gameid . "'";
			$gamewhere .= " AND id='" . $gameid . "'";
		}
		$where .= " AND starttime <=" . time () . " AND endtime>=" . time ();
		$list = giftlist ( $where );
		$giftlist = $list ['list'];
		
		$glist = findgamelist ( $gamewhere );
		$gamelist = $glist ['list'];
		
		foreach ( $gamelist as $key => $val ) {
			$gamearr [$val ['id']] ['image'] = $val ['image'];
			$gameinfolist = gameinfo ( $val ['id'] );
			foreach ( $gameinfolist as $key => $val ) {
				$urldata [$val ['id']] ['url'] = $val ['url'];
				$urldata [$val ['id']] ['androidurl'] = $val ['androidurl'];
			}
		}
		foreach ( $giftlist as $key => $val ) {
			$giftlist [$key] ['url'] = $urldata [$val ['appid']] ['url']; //
			$giftlist [$key] ['androidurl'] = $urldata [$val ['appid']] ['androidurl']; //
			$giftlist [$key] ['image'] = $gamearr [$val ['appid']] ['image']; //
			$codelist = codelist ( $val ['id'] );
			$codesiz = 0;
			if ($codelist) {
				$codesiz = count ( $codelist );
			}
			$giftlist [$key] ['gsum'] = $codesiz; //
		}
		$showpage = $list ['showpage'];
		$this->assign ( 'giftlist', $giftlist );
		
		$this->assign ( 'showpage', $showpage );
		$this->display ();
	}
	
	public function pcajax() {
		//用户名的检测
		$username = session("user.sdkuser");
		$infoid  = isset($_POST['giftid']) ? $_POST['giftid'] : '';
		if ($infoid) {
		
			$rs = checkusername($username);
		
			if ($rs) {
				$time = time();
				$list = checklibao($rs,$infoid);
				if(count($list)>0){
					$code = $list[0]['code'];//激活码
					$arr = array(
							'a' => $code
					);
					echo json_encode($arr);
					exit;
				}
				$gift = strcode($username,$infoid,$time);
					
				if($gift){
					$code = $gift['code'];//激活码
					setlibao($infoid,$code);
					setlibaolog($rs,$infoid,$code);
					$arr = array(
							'a' => $code
					);
					echo json_encode($arr);
					exit;
				}
				$arr = array(
						'a' => "3"
				);
				echo json_encode($arr);
				exit;
			} else {
				$arr = array(
						'a' => "2"
				);
				echo json_encode($arr);
				exit;
			}
		}else{
			$arr = array(
					'a' => "2"
			);
			echo json_encode($arr);
			exit;
		}
		
	}
}
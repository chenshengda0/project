<?php 

class TuiguangModel extends Model
{
	/**
	 * 获取推广员对应的注册数,充值数
	 * 
	 * gamearr array 游戏列表
	 * taxrate float 个人所得税、点卡的渠道费用以及手续费
	 * $tgcode 推广码,空时为查询全部列表的
	 * $time 时间,用于查询充值的时间
	 * $start 时间，用于查询251wan平台用户注册开始时间
	 * $end 时间，用于查询251wan平台用户注册结束时间
	 */
    public function getSpmembersPay($gamearr,$tgcode='',$time='',$start='',$end='') {
    	$where = 1;
    	if ($tgcode) {
    		$where .= " AND tgcode='".$tgcode."'";
    	}
    	if ($start) {
    		$where .= " AND create_time>='".$start."'";
    	}
    	if ($end) {
    		$where .= " AND create_time<='".$end."'";
    	}
    	$model = M('spmembers');
    	$sql = "SELECT COUNT(id) AS number,tgcode FROM c_spmembers where {$where} GROUP BY tgcode";
    	$rs = $model->query($sql);
    	
    	$list = array();
		$pay = M('sppay');
    	foreach ($rs as $key=>$val) {
    		$username_str = "";
    		$members = $model->field('username')->where("tgcode='".$val['tgcode']."'")->findAll();
    		foreach ($members as $name) {
    			$username_str .= "'".$name['username']."',";
    		}
    		
    		//充值数
    		$username_str = substr($username_str,0,-1);
    		$paywhere = 1;
    		$paywhere .= " AND status=1";
    		if ($time) {
    			$paywhere .= " AND create_time<'".$time."'";
    		}
    		$paywhere .= " AND username IN (".$username_str.")";
    		$moneylist = $pay->field("amount,gameid,payflag")->where($paywhere)->findAll();
    		$total = 0;		//充值总金额
    		$ticheng = 0;	//提成金额
    		$havetiqu = 0;	//已提取金额
    		$notiqu = 0;	//未提取金额
    		
    		foreach ($moneylist as $m=>$v) {
    			$total += $v['amount'];
    			$ticheng += $v['amount'] * $gamearr[$v['gameid']];
    			if ($v['payflag'] == 1) {
    				$havetiqu += $v['amount'] * $gamearr[$v['gameid']];	
    			} else if ($v['payflag'] == 0) {
    				$notiqu += $v['amount'] * $gamearr[$v['gameid']];
    			}
    		}
    		
    		$list[$val['tgcode']]['number'] = $val['number'];
    		$list[$val['tgcode']]['total'] = $total;
    		$list[$val['tgcode']]['ticheng'] = $ticheng;
    		$list[$val['tgcode']]['havetiqu'] = $havetiqu;
    		$list[$val['tgcode']]['notiqu'] = $notiqu;
    	}
    	//print_r($list);
    	return $list;
    }
    
    /**
	 * 获取推广员60天内的充值数
	 * 
	 * gamearr array 游戏列表
	 * taxrate float 个人所得税、点卡的渠道费用以及手续费
	 * $tgcode 推广码
	 */
    public function getSixtyPay($tgcode,$start,$end) {
    	$model = M('spmembers');
    	$pay = M('sppay');
    	$sql = "SELECT username FROM c_spmembers where tgcode='{$tgcode}'";
    	$rs = $model->query($sql);
    	$username_str = "";
    	foreach ($rs as $key=>$val) {
    		$username_str .= "'".$val['username']."',";
    	}
    	$username_str = substr($username_str,0,-1);
    	
    	$moneylist = $pay->field("amount,gameid")->where("status=1 AND create_time>='".$start."' AND create_time<= '".$end."' AND username IN (".$username_str.")")->findAll();
    	return $moneylist;
    }
}
?>
<?php
class UsercenterAction extends AuthAction{
	
	//首页
	function index(){
		$user = $GLOBALS['user'];
		$cart_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."goods_cart WHERE user_id = ".$user['id']);
		
		$fukuan_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."order WHERE user_id = ".$user['id']." AND status = 1");
		$shouhuo_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."order WHERE user_id = ".$user['id']." AND status = 3");
		$pingjia_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."order WHERE user_id = ".$user['id']." AND status = 4");
		$tuikuan_num = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."order WHERE user_id = ".$user['id']." AND status = -1");
		
		$this->assign("cart_num",$cart_num);
		$this->assign("fukuan_num",$fukuan_num);
		$this->assign("shouhuo_num",$shouhuo_num);
		$this->assign("pingjia_num",$pingjia_num);
		$this->assign("tuikuan_num",$tuikuan_num);
		//SEO设置
		$seo = array();
		$seo['title'] = "会员中心-".sysconf("SITE_NAME");
		$seo['keywords'] = "";
		$seo['description'] = "";
		$this->assign("seo",$seo);
		
		$this->display();
	}
	
	
	/*********订单操作start*****************/
	
	//订单
	function order(){
		$user = $GLOBALS['user'];
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		if($status == -1){
			$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."goods_cart WHERE user_id = ".$user['id']);	
			foreach($list as $k => $v){
				$list[$k]['color'] = $GLOBALS['db']->getOne("SELECT text FROM ".DB_PREFIX."goods_color WHERE goods_id = ".$v['goods_id']." AND id = ".$v['goods_color']);
			}
			//dump($list);
			$total = $GLOBALS['db']->getOne("SELECT SUM(goods_price*goods_num) FROM ".DB_PREFIX."goods_cart WHERE user_id = ".$user['id']);	
			$num = $GLOBALS['db']->getOne("SELECT SUM(goods_num) FROM ".DB_PREFIX."goods_cart WHERE user_id = ".$user['id']);	
			$this->assign("total",$total);
			$this->assign("num",$num);
		}else{
			$page = $_GET['p']?intval($_GET['p']):1;
			$perpage = 10;
			$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
			if($status > 0){
				if($status == 2 || $status == 3){
					$where = " WHERE user_id = ".$user['id']." AND (status = 2 OR status = 3) ";
				}else{
					$where = " WHERE user_id = ".$user['id']." AND status = ".$status;
				}
			}else{
				$where = " WHERE user_id = ".$user['id'];
			}
			
			
			$orderby = " ORDER BY add_time DESC ";
			$count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."order ".$where);
			
			$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."order ".$where.$orderby.$limit);
			//echo "SELECT * FROM ".DB_PREFIX."order ".$where.$orderby.$limit;
			foreach ($list as $k => $v){
				$goods_sql = "SELECT * FROM ".DB_PREFIX."order_goods WHERE order_id = ".$v['id'];
				if($status == 4){
					$goods_sql .= " AND is_comment = 0";
				}
				$goods_sql .= " GROUP BY goods_id";
				$goods = $GLOBALS['db']->getAll($goods_sql);
				foreach ($goods as $k1 => $v1){
					$goods[$k1]['color'] = $GLOBALS['db']->getOne("SELECT text FROM ".DB_PREFIX."goods_color WHERE goods_id = ".$v1['goods_id']." AND id = ".$v1['goods_color_id']);
					if($v['status'] > 3){
						//判断是否申请退款
						$goods[$k1]['back_status'] = $GLOBALS['db']->getOne("SELECT status FROM ".DB_PREFIX."goods_back WHERE user_id = ".$user['id']." AND order_id = ".$v['id']." AND goods_color_id  = ".$v1['goods_color_id']);
						//判断是否可以申请退款
						if(time()-$v['consignee_time'] > sysconf("BACK_GOODS_TIME")){
							$goods[$k1]['back_status'] = 10;
						}
						
					}
				}
				$list[$k]['goods'] = $goods;
				
			}
			//dump($list);
			$pages = new Page_1($count,$perpage);
			$pages = $pages->show();
			$this->assign("pages",$pages);
		}
		
		$this->assign("status",$status);
			
		$this->assign("list",$list);
		//SEO设置
		$seo = array();
		$seo['title'] = "订单中心-".sysconf("SITE_NAME");
		$seo['keywords'] = "";
		$seo['description'] = "";
		$this->assign("seo",$seo);
		
		$this->display();
	}
	
	//购物车
	function order_cake(){
		$user = $GLOBALS['user'];
		if($_POST){
			$ids = $_POST['ids'];
			//全部地址
			$address = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."address WHERE user_id = ".$user['id']." ORDER BY is_default DESC");
			if(!$address){
				showMsg("添加收货地址",url()."index.php/Usercenter/address");
			}
			$list = array();
			$total = 0;
			if($_GET['p'] == 'cart'){
				if($ids && count($ids) > 0){
					foreach ($ids as $v){
						$data = explode(":",$v);
						$goods_id = intval($data[0]);
						$goods_color = intval($data[1]);
						$num = intval($data[2]);
						$goods = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."goods_cart WHERE goods_id = ".$goods_id." AND goods_color = ".$goods_color);
						
						$list[$goods['company_id']]['goods'][] = $goods;
						$list[$goods['company_id']]['company_id'] = $goods['company_id'];
						$list[$goods['company_id']]['company_name'] = $goods['company_name'];
						$total += $goods['goods_price']*$goods['goods_num'];
					}
				}
			}else{
				$data = explode(":",$ids);
				$goods_id = intval($data[0]);
				$goods_color = intval($data[1]);
				$num = intval($data[2]);
				$goods1 = $GLOBALS['db']->getRow("SELECT id,name,goods_sn,price,goods_img,goods_num,provider_id,provider_name FROM ".DB_PREFIX."goods WHERE id = ".$goods_id." AND is_show = 1 AND is_del = 0");
				
				if($goods_color > 0){
					$goods_num = $GLOBALS['db']->getOne("SELECT num FROM ".DB_PREFIX."goods_color WHERE goods_id = ".$goods_id." AND id = ".$goods_color);
					if($goods_num <= 0){
						showMsg("该商品没有库存");
					}
					$color = $GLOBALS['db']->getOne("SELECT text FROM ".DB_PREFIX."goods_color WHERE goods_id = ".$goods_id." AND id = ".$goods_color);
				}else{
					$goods_num = $GLOBALS['db']->getOne("SELECT goods_num FROM ".DB_PREFIX."goods WHERE id = ".$goods_id);
				}
				if($num > 0){
					if($num > $goods_num){
						$num = $goods_num;
					}
				}else{
					showMsg("选择购买数量");
				}
				$goods = array(
						"goods_id" => $goods_id,
						"goods_name" => $goods1['name'],
						"goods_num" => $num,
						"goods_img" => $goods1['goods_img'],
						"goods_price" => $goods1['price'],
						"goods_color" => $goods_color,
						"company_id" => $goods1['provider_id'],
						"company_name" => $goods1['provider_name'],
						"color" => $color
				);
				$list[$goods['company_id']]['goods'][] = $goods;
				$list[$goods['company_id']]['company_id'] = $goods['company_id'];
				$list[$goods['company_id']]['company_name'] = $goods['company_name'];
				$total = $goods['goods_price']*$goods['goods_num'];
			}
			
			
			//dump($list);
			foreach ($address as $k1 => $v1){
				$address[$k1]['json'] = str_replace('"', '&quot;',JSON($v1));
			}
			$this->assign("list",$list); 
			$this->assign("address",$address); 
			$this->assign("total",$total); 
		}
		$this->display();
	}
	
	//订单生成
	function add_order(){
		$user = $GLOBALS['user'];
		if($_POST){
			$ids = $_POST['ids'];
			$remake = trim(new_addslashes($_POST['remake']));
			//全部地址
			$address_ids = $GLOBALS['db']->getCol("SELECT id FROM ".DB_PREFIX."address WHERE user_id = ".$user['id']." ORDER BY is_default DESC");
			if(!$address_ids){
				showMsg("添加收货地址",url()."index.php/Usercenter/address");
			}
			$address_id = in_array(intval($_POST['address_id']),$address_ids) ? intval($_POST['address_id']) : $address_ids[0];
			
			$addres = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."address WHERE id = ".$address_id);
			if($addres){
				$address = $addres['name'].",".$addres['phone'];
				if($addres['tel'] && $addres['phone']){
					$address .= "/";
				}
				$address .= $addres['tel'].",".$addres['province']." ".$addres['city']." ".$addres['district']." ";
				if($addres['street']){
					$address .= $addres['street']." ";
				}
				$address .= $addres['address'];
			}
			
			if($ids && count($ids) > 0){
				$total = 0;
				foreach ($ids as $v){
					$data = explode(":",$v);
					$goods_id = $data[0];
					$goods_color = $data[1];
					$goods = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."goods_cart WHERE goods_id = ".$goods_id." AND goods_color = ".$goods_color);
					if(!$goods){
						$goods1 = $GLOBALS['db']->getRow("SELECT id,name,goods_sn,price,goods_img,goods_num,provider_id,provider_name FROM ".DB_PREFIX."goods WHERE id = ".$goods_id." AND is_show = 1 AND is_del = 0");
						if($goods_color > 0){
							$goods_num = $GLOBALS['db']->getOne("SELECT num FROM ".DB_PREFIX."goods_color WHERE goods_id = ".$goods_id." AND id = ".$goods_color);
						}else{
							$goods_num = $GLOBALS['db']->getOne("SELECT goods_num FROM ".DB_PREFIX."goods WHERE id = ".$goods_id);
						}
						if($data[2] > 0){
							if($data[2] > $goods_num){
								$data[2] = $goods_num;
							}
						}else{
							showMsg("选择购买数量");
						}
						$goods = array(
								"goods_id" => $goods_id,
								"goods_name" => $goods1['name'],
								"goods_num" => $data[2],
								"goods_img" => $goods1['goods_img'],
								"goods_price" => $goods1['price'],
								"goods_color_id" => $goods_color,
								"company_id" => $goods1['provider_id'],
								"company_name" => $goods1['provider_name']
						);
					}
					$total += $goods['goods_price']*$goods['goods_num'];
				}
				
				$order = array(
						"user_id" => $user['id'],
						"order_sn" => $this->build_order_no(),
						"remake" => $remake,
						"money" => $total,
						"address_id" => $address_id,
						"address" => $address,
						"add_time" => time()
				);
				
				$GLOBALS['db']->autoExecute(DB_PREFIX."order",$order,"INSERT");
				$order_id = $GLOBALS['db']->insert_id();

				foreach ($ids as $v){
					$data = explode(":",$v);
					$goods_id = $data[0];
					$goods_color = $data[1];
					$order_goods = array(
							"order_id" => $order_id,
							"goods_id" => $goods['goods_id'],
							"goods_name" => $goods['goods_name'],
							"goods_price" => $goods['goods_price'],
							"goods_img" => $goods['goods_img'],
							"goods_color_id" => $goods['goods_color_id'],
							"goods_num" => $goods['goods_num'],
					);
					$GLOBALS['db']->autoExecute(DB_PREFIX."order_goods",$order_goods,"INSERT");
					$del_sql = "DELETE FROM ".DB_PREFIX."goods_cart WHERE user_id = ".$user['id']." AND goods_id = ".$goods_id." AND goods_color = ".$goods_color;
					$GLOBALS['db']->query($del_sql);

				}
				
				showMsg("提交成功",url()."Trade/pay.html?order_sn=".$order['order_sn']);
			}
		}
	}
	
	
	function order_detail(){
		$user = $GLOBALS['user'];
		$id = intval($_GET['id']);
		
		$order = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."order WHERE id = ".$id." AND user_id = ".$user['id']);
		if(!$order){
			showMsg("订单不存在");
		}
		if($order['app_time'] > 0){
			$status = 5;
		}elseif($order['consignee_time'] > 0){
			$status = 4;
		}elseif($order['delivery_time'] > 0){
			$status = 3;
		}elseif($order['payment_time'] > 0){
			$status = 2;
		}elseif($order['add_time'] > 0){
			$status = 1;
		}
		$this->assign("order",$order);
		$this->assign("status",$status);
		
		$this->display();
	}
	
	
	
	//订单取消
	function del_order(){
		$user = $GLOBALS['user'];
		$id = intval($_POST['id']);
		if($id){
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."order SET status = 0 WHERE id = ".$id." AND user_id = ".$user['id']." AND status > 0 AND status < 3");
		}
		$ids = trim(new_addslashes($_POST['ids']));
		if($ids){
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."order SET status = 0 WHERE id IN (".$ids.") AND user_id = ".$user['id']." AND status > 0 AND status < 3");
			showMsgajax("订单取消成功");
		}
		showMsgajax("订单取消成功");
	}
	//确认收货
	function shouhuo(){
		$user = $GLOBALS['user'];
		$id = intval($_POST['id']);
		if($id){
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."order SET status = 4,consignee_time = ".time()." WHERE id = ".$id." AND user_id = ".$user['id']." AND status = 3");
		}
		$ids = trim(new_addslashes($_POST['ids']));
		if($ids){
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."order SET status = 4,consignee_time = ".time()." WHERE id IN (".$ids.") AND user_id = ".$user['id']." AND status = 3");
			showMsgajax("确认收货成功");
		}
		showMsgajax("确认收货成功");
	}
	
	//生成订单号
	function build_order_no(){
		return date('Ymd').$GLOBALS['user']['id'].substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
	}
	
	/*********订单操作start*****************/
	
	//询价
	function xunjia(){
		$user = $GLOBALS['user'];
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 10;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$where = " WHERE uid = ".$user['id']." AND is_del = 0 ";
		$orderby = " ORDER BY add_time DESC ";
		$count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."xunjia ".$where);
			
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."xunjia ".$where.$orderby.$limit);
		
		$pages = new Page_1($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
			
		$this->assign("list",$list);
		//SEO设置
		$seo = array();
		$seo['title'] = "我的询价-".sysconf("SITE_NAME");
		$seo['keywords'] = "";
		$seo['description'] = "";
		$this->assign("seo",$seo);
		$this->display();
	}
	
	function del_xunjia(){
		$user = $GLOBALS['user'];
		$id = intval($_POST['id']);
		$ids = trim(new_addslashes($_POST['ids']));
		if($ids){
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."xunjia SET is_del = 1 WHERE uid = ".$user['id']." AND id IN (".$ids.")");
			showMsgajax("删除成功");
		}else{
			if($id > 0){
				$GLOBALS['db']->query("UPDATE ".DB_PREFIX."xunjia SET is_del = 1 WHERE uid = ".$user['id']." AND id = ".$id);
				showMsgajax("删除成功");
			}else{
				showMsgajax("选择删除项",-1);
			}
		}
	}
	
	//收藏
	function collect(){
		$user = $GLOBALS['user'];
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 10;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$where = " WHERE c.uid = ".$user['id']." AND c.type = 2";
		$orderby = " ORDER BY c.addtime DESC ";
		$count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."user_collect c".$where);
			
		$list = $GLOBALS['db']->getAll("SELECT g.id,g.name,g.goods_img,g.price,g.goods_brief FROM ".DB_PREFIX."user_collect c LEFT JOIN ".DB_PREFIX."goods g ON c.pid = g.id ".$where.$orderby.$limit);
		
		
		$pages = new Page_1($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
			
		$this->assign("list",$list);
		//SEO设置
		$seo = array();
		$seo['title'] = "我的收藏-".sysconf("SITE_NAME");
		$seo['keywords'] = "";
		$seo['description'] = "";
		$this->assign("seo",$seo);
		$this->display();
	}
	
	//评价
	function comments(){
		$user = $GLOBALS['user'];
		$page = $_GET['p']?intval($_GET['p']):1;
		$perpage = 10;
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$where = " WHERE user_id = ".$user['id'];
		$orderby = " ORDER BY add_time DESC ";
		$count = $GLOBALS['db']->getOne("SELECT COUNT(1) FROM ".DB_PREFIX."comments ".$where);
			
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."comments ".$where.$orderby.$limit);
		foreach ($list as $k => $v){
			$goods = $GLOBALS['db']->getRow("SELECT provider_id FROM ".DB_PREFIX."goods WHERE id = ".$v['goods_id']);
			
			$list[$k]['company_name'] = $GLOBALS['db']->getOne("SELECT name FROM ".DB_PREFIX."company WHERE user_id = ".$goods['provider_id']);
			$order_goods = $GLOBALS['db']->getRow("SELECT goods_price,goods_name FROM ".DB_PREFIX."order_goods WHERE order_id = ".$v['order_id']." AND goods_id = ".$v['goods_id']);
			$list[$k]['goods_name'] = $order_goods['goods_name'];
			$list[$k]['goods_price'] = $order_goods['goods_price'];
		}
		$pages = new Page_1($count,$perpage);
		$pages = $pages->show();
		$this->assign("pages",$pages);
			
		$this->assign("list",$list);
		//SEO设置
		$seo = array();
		$seo['title'] = "我的收藏-".sysconf("SITE_NAME");
		$seo['keywords'] = "";
		$seo['description'] = "";
		$this->assign("seo",$seo);
		$this->display();
	}
	//评价
	function add_comments(){
		$user = $GLOBALS['user'];
		$id = intval($_GET['id']);
		$order_id = intval($_GET['order_id']);
		$order = $GLOBALS['db']->getRow("SELECT add_time FROM ".DB_PREFIX."order WHERE user_id = ".$user['id']." AND id = ".$order_id." AND status = 4");
		$comment = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."comments WHERE order_id = ".$order_id." AND goods_id = ".$id);
		if($comment){
			showMsg("该产品已经评论过了",url()."index.php/Usercenter/comments");
		}
		if(!$order){
			header("Location:/404.html");exit;
		}
		if($_POST){
			$comment = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."comments WHERE order_id = ".$order_id." AND goods_id = ".$id);
			if($comment){
				showMsg("该产品已经评论过了",url()."index.php/Usercenter/comments");
			}
			$content = trim(new_addslashes($_POST['content']));
			$content = str_replace("chr(10)","<br>",$content);
			if($content == ""){
				showMsg("请输入评论内容");
			}
			$images = $_POST['images'];
			$goods_count = $GLOBALS['db']->getOne("SELECT COUNT(distinct  goods_id) FROM ".DB_PREFIX."order_goods WHERE order_id = ".$order_id);
			$comments = array(
					"user_id" => $user['id'],
					"order_id" => $order_id,
					"goods_id" => $id,
					"content" => $content,
					"img" => array2string($images),
					"disabled" => 1,
					"add_time" => time()
					
			);
			$GLOBALS['db']->autoExecute(DB_PREFIX."comments",$comments,"INSERT");
			$GLOBALS['db']->query("UPDATE ".DB_PREFIX."order_goods SET is_comment = 1 WHERE goods_id = ".$id." AND order_id = ".$order_id);
			$comment_list = $GLOBALS['db']->getCol("SELECT add_time FROM ".DB_PREFIX."comments WHERE order_id = ".$order_id." ORDER BY add_time ASC");
			
			if($goods_count == count($comment_list)){
				$GLOBALS['db']->query("UPDATE ".DB_PREFIX."order SET status = 5 , app_time = ".$comment_list[0]." WHERE user_id = ".$user['id']." AND id = ".$order_id." AND status = 4");
			}
			
			showMsg("评论成功",url()."index.php/Usercenter/comments");
		}
		
		$sql = "SELECT g.name,g.price,sum(og.goods_num) as order_num,count(c.id) as comment_num,g.goods_img FROM ".DB_PREFIX."goods g 
				LEFT JOIN ".DB_PREFIX."order_goods og ON g.id = og.goods_id 
				LEFT JOIN ".DB_PREFIX."comments c ON g.id = c.goods_id 
				WHERE g.id = ".$id;
		$goods = $GLOBALS['db']->getRow($sql);
		
		$this->assign("goods",$goods);
		$this->assign("order",$order);
		//SEO设置
		$seo = array();
		$seo['title'] = "对".$goods['name']."商品进行评价-".sysconf("SITE_NAME");
		$seo['keywords'] = "";
		$seo['description'] = "";
		$this->assign("seo",$seo);
		$this->display();
	}
	
	
	
	//收获地址
	function address(){
		$user = $GLOBALS['user'];
		if($_POST){
			$province_id = intval($_POST['province_id']);
			$city_id = intval($_POST['city_id']);
			$district_id = intval($_POST['district_id']);
			$street_id = intval($_POST['street_id']);
			$address = trim(new_addslashes($_POST['address']));
			$post = trim(new_addslashes($_POST['post']));
			$name = trim(new_addslashes($_POST['name']));
			$phone = trim(new_addslashes($_POST['phone']));
			$tel = trim(new_addslashes($_POST['tel']));
			if($province_id == 0 || $city_id == 0 || $district_id == 0){
				showMsg("地区不全");
			}
			/*if(!in_array($province_id,array(1,2,9,22)) && $street_id == 0){
				showMsg("地区不全");
			}*/
			$area = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."area");
			$areas = array();
			foreach ($area as $k => $v){
				$areas[$v['id']] = $v['name'];
			}
			if(!($areas[$province_id] && $areas[$city_id] && $areas[$district_id])){
				showMsg("地区不正确！！");
			}
			if($name == ""){
				showMsg("输入名称");
			}
			if($address == ""){
				showMsg("输入详细地址");
			}
			if($phone == "" && $tel == ""){
				showMsg("手机号和固定电话至少填写一个");
			}
			$address = array(
					"user_id" => $user['id'],
					"province_id" => $province_id,
					"province" => $areas[$province_id],
					"city_id" => $city_id,
					"city" => $areas[$city_id],
					"district_id" => $district_id,
					"district" => $areas[$district_id],
					"street_id" => $street_id,
					"street" => $areas[$street_id],
					"address" => $address,
					"post" => $post,
					"name" => $name,
					"phone" => $phone,
					"tel" => $tel,
			);
			$GLOBALS['db']->autoExecute(DB_PREFIX."address",$address,"INSERT");
			showMsg("添加成功");
		}
		$list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."address WHERE user_id = ".$user['id']." ORDER BY is_default DESC ");
		$province = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."area WHERE level = 1 AND id <35");
		
		$this->assign("list",$list);
		$this->assign("province",$province);
		//SEO设置
		$seo = array();
		$seo['title'] = "收货地址-".sysconf("SITE_NAME");
		$seo['keywords'] = "";
		$seo['description'] = "";
		$this->assign("seo",$seo);
		$this->display();
	}
	
	
	function del_address(){
		$user = $GLOBALS['user'];
		$id = intval($_POST['id']);
		$GLOBALS['db']->query("DELETE FROM ".DB_PREFIX."address WHERE id = ".$id." AND user_id = ".$user['id']);
		showMsgajax("删除成功");
	}
	
	function edit_address(){
		$user = $GLOBALS['user'];
		if($_POST){
			$id = intval($_POST['id']);
			$address = $GLOBALS['db']->getRow("SELECt * FROM ".DB_PREFIX."address WHERE id = ".$id." AND user_id = ".$user['id']);
			
			$province_id = intval($_POST['province_id']);
			$city_id = intval($_POST['city_id']);
			$district_id = intval($_POST['district_id']);
			$street_id = intval($_POST['street_id']);
			$address1 = trim(new_addslashes($_POST['address']));
			$post = trim(new_addslashes($_POST['post']));
			$name = trim(new_addslashes($_POST['name']));
			$phone = trim(new_addslashes($_POST['phone']));
			$tel = trim(new_addslashes($_POST['tel']));
			if($province_id == 0 || $city_id == 0 || $district_id == 0){
				showMsgajax("地区不全",-1);
			}
			/*if(!in_array($province_id,array(1,2,9,22)) && $street_id == 0){
			 showMsg("地区不全");
			}*/
			$area = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."area");
			$areas = array();
			foreach ($area as $k => $v){
				$areas[$v['id']] = $v['name'];
			}
			if(!($areas[$province_id] && $areas[$city_id] && $areas[$district_id])){
				showMsgajax("地区不正确！！",-1);
			}
			if($name == ""){
				showMsgajax("输入名称",-1);
			}
			if($address1 == ""){
				showMsgajax("输入详细地址",-1);
			}
			if($phone == "" && $tel == ""){
				showMsgajax("手机号和固定电话至少填写一个",-1);
			}
			if($phone){
				if(!check_mobile($phone)){
					showMsgajax("手机号格式不正确",-1);
				}
			}
			
			$address['name'] = $name;
			$address['province_id'] = $province_id;
			$address['province'] = $areas[$province_id];
			$address['city_id'] = $city_id;
			$address['city'] = $areas[$city_id];
			$address['district_id'] = $district_id;
			$address['district'] = $areas[$district_id];
			$address['street_id'] = $street_id;
			$address['street'] = $areas[$street_id];
			$address['address'] = $address1;
			$address['post'] = $post;
			$address['name'] = $name;
			$address['phone'] = $phone;
			$address['tel'] = $tel;
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."address",$address,"UPDATE","id = ".$id." AND user_id = ".$user['id']);
			showMsgajax("添加成功");
		}
		$id = intval($_GET['id']);
		
		$address = $GLOBALS['db']->getRow("SELECt * FROM ".DB_PREFIX."address WHERE id = ".$id." AND user_id = ".$user['id']);
		//省份
		$province = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."area WHERE level = 1");
		//城市
		$city = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."area WHERE level = 2 AND pid = ".$address['province_id']);
		//市,区
		$district = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."area WHERE level = 3 AND pid = ".$address['city_id']);
		if($address['street_id']){
			//街道
			$street = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."area WHERE level = 4 AND pid = ".$address['district_id']);
			$this->assign("street",$street);
		}
		$this->assign("address",$address);
		$this->assign("province",$province);
		$this->assign("city",$city);
		$this->assign("district",$district);
		$this->assign("id",$id);
		
		$this->display();
	}
	
	function add_address(){
		$user = $GLOBALS['user'];
		if($_POST){
			$province_id = intval($_POST['province_id']);
			$city_id = intval($_POST['city_id']);
			$district_id = intval($_POST['district_id']);
			$street_id = intval($_POST['street_id']);
			$address1 = trim(new_addslashes($_POST['address']));
			$post = trim(new_addslashes($_POST['post']));
			$name = trim(new_addslashes($_POST['name']));
			$phone = trim(new_addslashes($_POST['phone']));
			$tel = trim(new_addslashes($_POST['tel']));
			if($province_id == 0 || $city_id == 0 || $district_id == 0){
				showMsgajax("地区不全",-1);
			}
			/*if(!in_array($province_id,array(1,2,9,22)) && $street_id == 0){
			 showMsg("地区不全");
			}*/
			$area = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."area");
			$areas = array();
			foreach ($area as $k => $v){
				$areas[$v['id']] = $v['name'];
			}
			if(!($areas[$province_id] && $areas[$city_id] && $areas[$district_id])){
				showMsgajax("地区不正确！！",-1);
			}
			if($name == ""){
				showMsgajax("输入名称",-1);
			}
			if($address1 == ""){
				showMsgajax("输入详细地址",-1);
			}
			if($phone == "" && $tel == ""){
				showMsgajax("手机号和固定电话至少填写一个",-1);
			}
				
			$address['name'] = $name;
			$address['province_id'] = $province_id;
			$address['province'] = $areas[$province_id];
			$address['city_id'] = $city_id;
			$address['city'] = $areas[$city_id];
			$address['district_id'] = $district_id;
			$address['district'] = $areas[$district_id];
			$address['street_id'] = $street_id;
			$address['street'] = $areas[$street_id];
			$address['address'] = $address1;
			$address['post'] = $post;
			$address['name'] = $name;
			$address['phone'] = $phone;
			$address['tel'] = $tel;
			$address['user_id'] = $user['id'];
				
			$GLOBALS['db']->autoExecute(DB_PREFIX."address",$address,"INSERT");
			$id = $GLOBALS['db']->insert_id();
			echo json_encode(array("result"=>1,"msg"=>"添加成功","id"=>$id));
        	exit;
		}
		
		//省份
		$province = $GLOBALS['db']->getAll("SELECT id,name FROM ".DB_PREFIX."area WHERE level = 1");
		
		
		$this->assign("province",$province);
		
		$this->display();
	}
	
	function set_default(){
		$user = $GLOBALS['user'];
		$id = intval($_POST['id']);
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."address SET is_default = 1 WHERE user_id = ".$user['id']." AND id = ".$id);
		$GLOBALS['db']->query("UPDATE ".DB_PREFIX."address SET is_default = 0 WHERE user_id = ".$user['id']." AND id != ".$id);
		showMsgajax("");
	}
	
	//修改密码
	function change_pwd(){
		$user = $GLOBALS['user'];
		if($_POST){
			$password_old = trim(new_addslashes($_POST['password_old']));
			if($user['password']!= md5(md5($password_old).$user['salt'])){
				showMsg("原密码错误","goback");
			}
		
			if(strlen($_POST['password'])>0 && strlen($_POST['password'])<6){
				showMsg(lang("PWD_TOO_SHORT"),"goback");
			}
			elseif(strlen($_POST['password'])>20){
				showMsg(lang("PWD_TOO_LONG"),"goback");
			}
		
			$rand_str = "abcdefghijklmnopqrstuvwxyz";
			$salt = "";
			for($i=0;$i<6;$i++){
				$salt .= $rand_str{rand(0,25)};
			}
			$user['salt'] = $salt;
			$user['password'] = md5(md5($_POST['password']).$salt);
			$GLOBALS['db']->autoExecute(DB_PREFIX."user",$user,"UPDATE","id=".$user['id']);
			showMsg("密码修改成功,请重新登录",url("User#login_out"));
		}
		
		
		$seo['title'] = "密码修改-".sysconf("SITE_NAME");
		$seo['keywords'] = "密码修改-".sysconf("SITE_NAME");
		$seo['description'] = "";
		$this->assign("seo",$seo);
		$this->display();
	}
	//猜你喜欢
	function history_goods(){
		/*if($GLOBALS['user']){
		 $where = " AND ( c.uid = ".$GLOBALS['user']['id']." OR c.sessionid = '".$this->session->id()."')";
		}else{
		$where = " AND c.sessionid = '".$this->session->id()."' ";
		}*/
		$counts = $GLOBALS['db']->getCol("SELECT COUNT(1) FROM ".DB_PREFIX."click ".$where." GROUP BY goods_id");
		$count = count($counts);
		$page = $_POST['p']?intval($_POST['p']):1;
		$perpage = 8;
		if(($perpage*($page-1)) >= $count){
			$page = 1;
		}
		$limit = " LIMIT ".($perpage*($page-1)).",$perpage";
		$sql = "SELECT g.id,g.name,g.goods_img,g.goods_brief,g.price,COUNT(c.goods_id) as count
				FROM ".DB_PREFIX."goods g LEFT JOIN ".DB_PREFIX."click c ON g.id = c.goods_id
				WHERE g.is_del = 0 AND g.is_show = 1 ".$where."
				GROUP BY g.id ORDER BY count DESC,c.add_time DESC ".$limit;
		$list = $GLOBALS['db']->getAll($sql);
		foreach ($list as $k => $v){
			if(substr($v['goods_img'],1,6) == 'public'){
				$list[$k]['goods_img'] = "/resize/".basename($v['goods_img'],'.jpg')."-180-170.jpg";
			}
		}
		echo json_encode(array("list"=>$list,"page"=>$page));exit;
	}
}
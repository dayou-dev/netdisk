<?php
class goodsModule extends SiteBaseModule
{
	public function index()
	{
		
		$id=intval($_GET['id']);
		$o=intval($_GET['o']);
		$k=htmlstrchk($_REQUEST['k']);
		$ajax=intval($_REQUEST['ajax']);
		if($id) $wr.=" and cate_id=$id";
		if($k) $wr.=" and title like '%$k%'";
		$orderby="  id desc" ;
		if($o){
			if($o==1) $orderby=" integral asc , id desc" ;
			if($o==2) $orderby=" integral desc , id desc" ;
			if($o==3) $orderby=" sumcount desc , id desc" ;
			if($o==4) $orderby=" id desc" ;
		}
		
		$sql = "select * from ".DB_PREFIX."goods where is_effect = 1 and is_delete=0 $wr order by $orderby ";
		$pagelist=thispage1($sql,1,6,10,"&id=".$_GET['id']);
		
		//分类
		$cateinfo = $GLOBALS['db']->getOne("select title from ".DB_PREFIX."goods_cate where id=$id");
		$GLOBALS['tmpl']->assign("cateinfo",$cateinfo);
		$GLOBALS['tmpl']->assign("id",$id?$id:"");
		$GLOBALS['tmpl']->assign("o",$o);
		$GLOBALS['tmpl']->assign("k",$k);
		$GLOBALS['tmpl']->assign("pagelist",$pagelist);
		if($ajax){
			$GLOBALS['tmpl']->display("page/goods_ajax.html");
		}else{
			$GLOBALS['tmpl']->display("page/goods.html");
		}

	}
	
	public function catelist()
	{
		$goods_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_cate where is_effect = 1 and is_delete = 0 and pid=0 order by sort asc, id desc");
		foreach($goods_cate as $key=>$rows){
			$goods_cate[$key]['list']=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_cate where is_effect = 1 and is_delete = 0 and pid=".$rows['id']." order by sort asc, id desc");
		}
		$GLOBALS['tmpl']->assign("goods_cate",$goods_cate);
		$GLOBALS['tmpl']->display("page/goods_cate.html");
	}

	//商品详细
	public function detail() {
		$id=intval($_REQUEST['id']);
     	$goodsinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."goods where is_effect = 1 and is_delete=0 and id = $id");
		$goods_cate = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."goods_cate where is_effect = 1 and is_delete = 0 and id=".intval($goodsinfo['cate_id']));
		$sales=0;
		if($goodsinfo['is_sales']){
			if(time()>$goodsinfo['start_time']&&$goodsinfo['end_time']>time()){
				$sales++;
				$goodsinfo['price']=$goodsinfo['price_sales'];
			}
		}
		
		
		$goods_attr = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_attr where pid=$id order by sort asc,id desc");
		foreach($goods_attr as $key=>$rows){
			$goods_attr[$key]['list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_attrlist where attrid=".$rows['id']." order by id asc ");
		}
		
		$GLOBALS['tmpl']->assign("goods_attr",$goods_attr);
		$GLOBALS['tmpl']->assign("sales",$sales);
		$GLOBALS['tmpl']->assign("id",$id);
		$GLOBALS['tmpl']->assign("goods_cate",$goods_cate);
		$GLOBALS['tmpl']->assign("goodsinfo",$goodsinfo);
		$GLOBALS['tmpl']->assign("goods_cate",$goods_cate);
		if(!$goodsinfo) header("Location:/goods");
		$GLOBALS['tmpl']->display("page/goods1.html");
	}
	
	public function confirm_order(){
		$user_info = es_session::get("user_info");
		
		if(!$user_info){
			es_session::delete("user_info");
			showErr("请先登录后操作",0,"/user/login");
		}
		
		$user_id = intval($user_info['id']);
		$id=intval($_REQUEST['id']);
		$qty=intval($_REQUEST['qty']);
		$aid=intval($_REQUEST['aid']);
		$user_admin_id=intval($_REQUEST['user_admin_id']);
		$attrid=htmlstrchk($_REQUEST['attrid']);
		$attrid=$attrid?$attrid:0;
		$qty=$qty?$qty:1;
     	$goodsinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."goods where is_effect = 1 and is_delete=0 and id = $id");
		if(!$goodsinfo){
			showErr("商品信息不存在",0,"/goods");	
		}
		
		if($goodsinfo['is_sales']){
			if(time()>$goodsinfo['start_time']&&$goodsinfo['end_time']>time()){
				$goodsinfo['price']=$goodsinfo['price_sales'];
			}
		}
		
		$shop_money=0;
		if($user_admin_id){
			$is_shop_chk=1;
			$user_admin = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_admin where id=$user_admin_id and is_effect=1");
			if($user_admin){
				if($user_admin['is_stop_order']) $is_shop_chk=0;
				$company=$user_admin['company'];
				$shop_money=$goodsinfo['price_s'];
			}else{
				$is_shop_chk=0;
			}
			if(!$is_shop_chk){
				showErr("当前门店已暂停服务请重新选择一个服务门店",0,"/goods/detail/$id.html");
			}
		}
		$user_integral=get_user_integral($user_info['id']);
		$goods_attr = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_attr where pid=$id order by sort asc,id desc");
		$new_attrid=$attrid;
		$attrid = explode(",",$attrid);
		$attrMoney=0;
		$attrdata=array();
		$romoney = round($goodsinfo['price'],2);
		foreach($goods_attr as $key=>$rows){
			$attrList = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_attrlist where attrid=".$rows['id']." order by id asc ");
			foreach($attrList as $keys=>$rows1){
				foreach($attrid as $rows2){
					if($rows2==$rows1['id']){
						$attrdata[$key]['val']=$rows1['title'];
						$attrMoney+=$rows1['price'];
					}
				}
			}
			$attrdata[$key]['title']=$rows['title'];
			
		}
		$shop_money= $shop_money * $qty;
		$romoney += $attrMoney;
		$romoney = ($romoney * $qty);
		$integral= $romoney ;
		$romoney += ($shop_money+$goodsinfo['price_f']);
		$romoney1 = ($shop_money+$goodsinfo['price_f']);
		
		
		if($aid) $user_address = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_address where user_id = $user_id and id=$aid");
		if(!$user_address) $user_address = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_address where user_id = $user_id order by is_default desc");
		if($user_address){
			$user_address['province_s']=$GLOBALS['db']->getOne("select `name` from ".DB_PREFIX."region where id=".intval($user_address['province']));
			$user_address['city_s']=$GLOBALS['db']->getOne("select `name` from ".DB_PREFIX."region where id=".intval($user_address['city']));
			$user_address['region_s']=$GLOBALS['db']->getOne("select `name` from ".DB_PREFIX."region where id=".intval($user_address['region']));
		}
		$goodsinfo['integral']=$goodsinfo['integral']*$qty;
		$user_integral=get_user_integral($user_id);
		$GLOBALS['tmpl']->assign("goodsinfo",$goodsinfo);
		$GLOBALS['tmpl']->assign("integral",$integral);
		$GLOBALS['tmpl']->assign("user_integral",$user_integral?$user_integral:0);
		$GLOBALS['tmpl']->assign("qty",$qty);
		$GLOBALS['tmpl']->assign("company",$company);
		$GLOBALS['tmpl']->assign("user_admin_id",$user_admin_id);
		$GLOBALS['tmpl']->assign("shop_money",$shop_money);
		$GLOBALS['tmpl']->assign("romoney",$romoney);
		$GLOBALS['tmpl']->assign("romoney1",$romoney1);
		$GLOBALS['tmpl']->assign("attr_id",$_REQUEST['attrid']);
		$GLOBALS['tmpl']->assign("attrdata",$attrdata);
		$GLOBALS['tmpl']->assign("user_address",$user_address);
		$GLOBALS['tmpl']->assign("user_integral",$user_integral);
		$GLOBALS['tmpl']->display("page/goods_confirm_order.html");
	}
	
	
	public function payorder(){
		$user_info = es_session::get("user_info");
		$id=intval($_REQUEST['id']);
		$address_id=intval($_REQUEST['address_id']);
		$integral=intval($_REQUEST['integral']);
		$payType=intval($_REQUEST['payType']);
		$msginfo=htmlstrchk($_REQUEST['msginfo']);
		$qty=intval($_REQUEST['qty']);
		$user_admin_id=intval($_REQUEST['user_admin_id']);
		$attrid=htmlstrchk($_REQUEST['attrid']);
		$attrid=$attrid?$attrid:0;
		$qty=$qty?$qty:1;
		if($user_info){
			$goodsinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."goods where is_effect = 1 and is_delete=0 and id=$id");
			if($goodsinfo){
				//$romoney = intval($goodsinfo['integral']);
				
				//$address_id
				$address_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_address where user_id = ".intval($user_info['id'])." and  id=$address_id");
				if(!$address_info){
					showErr("请选择一个收货地址",1,"");
				}
				
				if(!$goodsinfo['in_stock']){
					showErr("商品库存不足",1,"");
				}
				
				$shop_money=0;
				if($user_admin_id&&$goodsinfo['shop_service']){
					$is_shop_chk=1;
					$user_admin = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_admin where id=$user_admin_id and is_effect=1");
					if($user_admin){
						if($user_admin['is_stop_order']) $is_shop_chk=0;
						$company=$user_admin['company'];
						$shop_money=$goodsinfo['price_s'];
					}else{
						$is_shop_chk=0;
					}
					if(!$is_shop_chk){
						showErr("当前门店已暂停服务请重新选择一个服务门店",1,"");
					}
				}
				$user_integral=get_user_integral($user_info['id']);
				$goods_attr = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_attr where pid=$id order by sort asc,id desc");
				$new_attrid=$attrid;
				$attrid = explode(",",$attrid);
				$attrMoney=0;
				$attrdata=array();
				$romoney = round($goodsinfo['price'],2);
				$attrprice="";
				foreach($goods_attr as $key=>$rows){
					$attrList = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."goods_attrlist where attrid=".$rows['id']." order by id asc ");
					foreach($attrList as $keys=>$rows1){
						foreach($attrid as $rows2){
							if($rows2==$rows1['id']){
								$attrdata[$key]['val']=$rows1['title'];
								$attrMoney+=$rows1['price'];
								$attrprice.="[".$rows1['title'].'：'.$rows1['price']."]";
							}
						}
					}
					$attrdata[$key]['title']=$rows['title'];
					
				}
				$shop_money= $shop_money * $qty;
				$romoney += $attrMoney;
				$romoney = ($romoney * $qty);
				//$integral= $romoney ;
				$romoney += ($shop_money+$goodsinfo['price_f']);
				$romoney1 = ($shop_money+$goodsinfo['price_f']);
				
				//积分支付
				if($integral){
					$integral_sum=$goodsinfo['integral']*$qty;
					if($user_integral<$integral_sum){
						showErr("积分余额不足，兑换失败",1,"");
					}else{
						$romoney=$romoney1;
					}
				}
				
				//生成支付订单
				$checkWord = '';
				$checkChar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ1234567890';
				//获取 4 位随机数。
				for($num=0; $num<4; $num++){
				  $char=rand(0, strlen($checkChar)-1);
				  $checkWord.=$checkChar[$char];
				}
				$ordernu = time().$checkWord;
				
				$redata['title'] = $goodsinfo['title'];
				$redata['pid'] = $goodsinfo['id'];
				$redata['ordernu'] = $ordernu;
				$redata['user_name'] = $user_info['user_name'];
				$redata['user_id'] = $user_info['id'];
				$redata['price'] = $goodsinfo['price'];
				$redata['integral'] = $goodsinfo['integral'];
				$redata['integral_sum'] = $integral?$goodsinfo['integral']*$qty:0;
				$redata['address_id'] = $address_id;
				$redata['msginfo'] = $msginfo;
				$redata['info'] = $goodsinfo['info'];
				$redata['money'] = $romoney;
				$redata['shop_service'] = $goodsinfo['shop_service']?1:0;
				$redata['service_fee'] = $goodsinfo['shop_service']?$shop_money:0;
				$redata['exp_fee'] = $goodsinfo['price_f']?$goodsinfo['price_f']:0;
				$redata['integral_fee'] = $romoney;
				$redata['money'] = $romoney;
				$redata['user_admin_id'] = $user_admin_id;
				$redata['attrid'] = $new_attrid;
				$redata['attrprice'] = $attrprice;
				$redata['ip'] = get_client_ip();
				$redata['create_time'] = time();
				$GLOBALS['db']->autoExecute(DB_PREFIX."goods_order",$redata); //插入
				$redid = $GLOBALS['db']->insert_id();
				if($redid){
					if($integral){
						//添加积分消费记录
						$userdata=array();
						$userdata['user_name'] = $user_info['user_name'];
						$userdata['user_id'] = $user_info['id'];
						$userdata['money'] = $romoney;
						$userdata['ymoney'] = $user_integral-($goodsinfo['integral']*$qty);
						$userdata['mtype'] =1;
						$userdata['objid'] = $redid;
						$userdata['info'] = "积分兑换";
						$userdata['create_time'] = time();
						$GLOBALS['db']->autoExecute(DB_PREFIX.("user_integral"),$userdata); //插入
						$integral = get_user_integral(intval($rows));
						$GLOBALS['db']->getOne("update ".DB_PREFIX."user set integral='$integral' where id=".intval($user_info['id']));
						$GLOBALS['db']->getRow("update ".DB_PREFIX."goods set in_stock = in_stock-$qty where id=$id");
					}
					if($payType){
						$u_remoney=get_user_money($user_info['id']);
						if($u_remoney<$romoney){
							showErr("余额不足请充值后操作",1,"/user/recharge");
						}else{
							//余额支付
							$userdata=array();
							$ymoney = $u_remoney-$romoney;
							$userdata['user_name'] = $user_info['user_name'];
							$userdata['user_id'] = $user_info['id'];
							$userdata['money'] = $romoney;
							$userdata['ymoney'] = $ymoney;
							$userdata['mtype'] = 1;
							$userdata['objid'] = $redid;
							$userdata['info'] = "商品订单结算";
							$userdata['create_time'] = time();
							$GLOBALS['db']->autoExecute(DB_PREFIX."user_money",$userdata); //插入
							$redata['status'] = 1;
							$GLOBALS['db']->autoExecute(DB_PREFIX."order",$redata); //插入
							showSuccess("支付成功",1,"");
						}
					}
				}
				showSuccess($redid,1,"");
			}else{
				showErr("产品信息不存在",1,"");
			}
		}else{
			showErr("请先登录后操作",1,"");
		}
	}
	
	function success(){
		showSuccess("订单支付成功",0,"/user/goods");	
	}

}	
?>
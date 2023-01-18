<?php
class productModule extends SiteBaseModule
{
	public function index()
	{
		//wx_autologin();	
		$id=intval($_REQUEST['id']);
		$product_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_cate where is_effect = 1 and is_delete = 0 order by sort asc, id desc");
		$pname="";
		foreach($product_cate as $key=>$rows){
			if(!$id&&!$key){
				$product_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."product where is_effect = 1 and is_delete = 0 and cate_id=".$rows['id']." order by sort asc, id desc LIMIT 0,99");
				$pname=$rows['title'];
				$id=$rows['id'];
			}else{
				if($id==$rows['id']){
					$product_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."product where is_effect = 1 and is_delete = 0 and cate_id=".$rows['id']." order by sort asc, id desc LIMIT 0,99");
					$pname=$rows['title'];
					$id=$rows['id'];
				}

			}
		}
		$GLOBALS['tmpl']->assign("id",$id);
		$GLOBALS['tmpl']->assign("pname",$pname);
		$GLOBALS['tmpl']->assign("product_list",$product_list);
		$GLOBALS['tmpl']->assign("product_cate",$product_cate);
		$GLOBALS['tmpl']->display("page/product.html");
	}
	
	public function search()
	{
		$k=htmlstrchk($_REQUEST['k']);
		$sql = "select * from ".DB_PREFIX."product where is_effect = 1 and is_delete=0 and title like '%$k%' order by sort asc,id desc ";
		$pagelist=thispage1($sql,1,10,10,"&id=".$_GET['id']);
		$GLOBALS['tmpl']->assign("k",$k);
		$GLOBALS['tmpl']->assign("pagelist",$pagelist);
		$GLOBALS['tmpl']->display("page/search.html");
	}
	
	//新闻详细内容
	public function detail() {
		$id=intval($_REQUEST['id']);
     	$productinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."product where is_effect = 1 and is_delete=0 and id = $id");
		$product_cate = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."product_cate where is_effect = 1 and is_delete = 0 and id=".intval($productinfo['cate_id']));
		
		$product_attr = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_attr where pid=$id order by sort asc,id desc");
		foreach($product_attr as $key=>$rows){
			$product_attr[$key]['list'] = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_attrlist where attrid=".$rows['id']." order by id asc ");
		}
		
		
		$GLOBALS['tmpl']->assign("id",$id);
		$GLOBALS['tmpl']->assign("product_cate",$product_cate);
		$GLOBALS['tmpl']->assign("product_attr",$product_attr);
		$GLOBALS['tmpl']->assign("productinfo",$productinfo);
		$GLOBALS['tmpl']->assign("product_cate",$product_cate);
		if(!$productinfo) header("Location:/");
		$GLOBALS['tmpl']->display("page/product1.html");
	}
	
	public function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
		  $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	
	
	/**
	 * 模拟 http 请求
	 * @param  String $url  请求网址
	 * @param  Array  $data 数据
	 */
	public function https_request($url, $data = null){
		// curl 初始化
		$curl = curl_init();
	
		// curl 设置
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		// 判断 $data get  or post
		if ( !empty($data) ) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
		// 执行
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}

	
	public function pay(){
		$user_info = es_session::get("user_info");
		$id=intval($_REQUEST['id']);
		$cid=intval($_REQUEST['cid']);
		$coupon=intval($_REQUEST['coupon']);
		$ajax=intval($_REQUEST['ajax']);
		$attrid=htmlstrchk($_REQUEST['attrid']);
		$plist=htmlstrchk($_REQUEST['plist']);
     	$attrid=$attrid?$attrid:0;
		$user_id = intval($user_info['id']);
		
		$miniProgram=0;
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'miniProgram') !== false) {
			//"小程序";
			$miniProgram=1;
		} else {
			//"微信";
		}
		
		if(!$ajax){
			// 微信 JS 接口签名校验工具: https://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=jsapisign
			$system =  $GLOBALS['db']->getRow("select * from ".DB_PREFIX."system ");
			$appid = $system['APPID'];
			$secret = $system['AppSecret'];
			
			// 获取token
			$token_data = file_get_contents('./wechat_token.txt');
			if (!empty($token_data)) {
				$token_data = json_decode($token_data, true);
			}
			
			$time  = time() - $token_data['time'];
			if ($time > 3600) {
				$token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
				$token_res = $this->https_request($token_url);
				$token_res = json_decode($token_res, true);
				$token = $token_res['access_token'];
			
				$data = array(
					'time' =>time(),
					'token' =>$token
				);
				$res = file_put_contents('./wechat_token.txt', json_encode($data));
				if ($res) {
					//echo '更新 token 成功';
				}
			} else {
				$token = $token_data['token'];
			}
			
			
			// 获取ticket
			$ticket_data = file_get_contents('./wechat_ticket.txt');
			if (!empty($ticket_data)) {
				$ticket_data = json_decode($ticket_data, true);
			}
			
			$time  = time() - $ticket_data['time'];
			if ($time > 3600) {
				$ticket_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=jsapi";
				$ticket_res = $this->https_request($ticket_url);
				$ticket_res = json_decode($ticket_res, true);
				$ticket = $ticket_res['ticket'];
			
				$data = array(
					'time'    =>time(),
					'ticket'  =>$ticket
				);
				$res = file_put_contents('./wechat_ticket.txt', json_encode($data));
				if ($res) {
					//echo '更新 ticket 成功';
				}
			} else {
				$ticket = $ticket_data['ticket'];
			}
			
			
			// 进行sha1签名
			$timestamp = time();
			$nonceStr = $this->createNonceStr();
			
			// 注意 URL 建议动态获取(也可以写死).
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // 调用JSSDK的页面地址
			// $url = $_SERVER['HTTP_REFERER']; // 前后端分离的, 获取请求地址(此值不准确时可以通过其他方式解决)
			
			$str = "jsapi_ticket={$ticket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
			$sha_str = sha1($str);
			$GLOBALS['tmpl']->assign("appid",$appid);
			$GLOBALS['tmpl']->assign("timestamp",$timestamp);
			$GLOBALS['tmpl']->assign("nonceStr",$nonceStr);
			$GLOBALS['tmpl']->assign("sha_str",$sha_str);
		}
			
			
		$GLOBALS['tmpl']->assign("miniProgram",$miniProgram);
		
		if($user_info){
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=$user_id");
			$productinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."product where is_effect = 1 and is_delete=0 and id=$id");
			$user_admin = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_admin where is_effect = 1 and is_delete=0 and id=$cid");
			$orderInfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."order where user_id=$user_id order by id desc");
			$GLOBALS['tmpl']->assign("orderInfo",$orderInfo);
			
			if($ajax){
				if($productinfo){
					$payType=intval($_REQUEST['payType']);
					$user_admin_id=intval($_REQUEST['user_admin_id']);
					$car_id=intval($_REQUEST['car_id']);
					$start_time=$_REQUEST['start_time'];
					$names=htmlstrchk($_REQUEST['names']);
					$mobile=htmlstrchk($_REQUEST['mobile']);
					$brief=htmlstrchk($_REQUEST['brief']);
					$paymoney=htmlstrchk($_REQUEST['paymoney']);
					$start_time=$start_time?strtotime($start_time):0;
					if(!$productinfo['is_end']){
						if(!$user_admin_id){
							showErr("请选择一个服务门店",1,"");
						}else{
							$is_shop_chk=1;
							$user_admin = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_admin where id=$user_admin_id and is_effect=1");
							if($user_admin){
								if($user_admin['is_stop_order']) $is_shop_chk=0;
								$company=$user_admin['company'];
							}else{
								$is_shop_chk=0;
							}
							if(!$is_shop_chk){
								showErr("当前门店已暂停服务请重新选择一个服务门店",1,"");
							}
						}
					
						$car_chk = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."user_cart where user_id=$user_id and id=$car_id");
						if(!$car_chk){
							//showErr("请选择一个预约车辆",1,"");
						}
						
						if($start_time<time()){
							showErr("请选择一个正常的预约时间",1,"");
						}
						
						if(!$names||!$mobile){
							showErr("联系人/联系电话不能为空",1,"");
						}
					}
					$qty=1;
					$new_plist="";
					if($productinfo['penpi']){
						if(!$plist){
							showErr("请选择油漆产品",1,"");
						}else{
							$plist_arr=explode(",",$plist);
							$qt_v=0;
							foreach($plist_arr as $rows1){
								if(intval($rows1)>0){
									//$qt_v++;
									if(intval($rows1)<14){
										$qt_v=$qt_v+1;
									}else{
										$qt_v=$qt_v+0.5;
									}
									
									$new_plist=$new_plist?",".(intval($rows1)):intval($rows1);
								}
								if(intval($rows1)==22){
									$qt_v=9999;
								}
							}
							if($qt_v>1000){
								 $qt_v=17;	
								 $new_plist=22;
							}
							$qty=$qt_v;
						}
					}
					if(!$productinfo['is_end']){
						$romoney = round($productinfo['price'],2);
						$product_attr = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_attr where pid=$id order by sort asc,id desc");
						$attrid = explode(",",$attrid);
						$attrMoney=0;
						$new_attrid="";
						$attrprice="";
						foreach($product_attr as $key=>$rows){
							$attrList = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_attrlist where attrid=".$rows['id']." order by id asc ");
							$reint=0;
							foreach($attrList as $keys=>$rows1){
								foreach($attrid as $rows2){
									if($rows2==$rows1['id']){
										$attrMoney+=$rows1['price'];
										$new_attrid.=$new_attrid?",".$rows1['id']:$rows1['id'];
										$attrprice.="[".$rows1['title'].'：'.$rows1['price']."]";
										$reint++;
									}
								}
							}
							if(!$reint) $attrMoney+=$attrList[0]['price'];
						}
						$romoney += $attrMoney;
						$romoney = $romoney * $qty;
						
						//折扣券
						$discount=0;
						$coupon_id=0;
						if($coupon){
							$user_coupon = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_coupon where user_id=$user_id and status=0 and start_time<".time()." and end_time>".time()." order by id desc");
							$couponList=array();
							foreach($user_coupon as $key=>$rows){
								if($rows['e_type']){
									$zk_money=round($romoney-($romoney*$rows['money']/100),2);
								}else{
									$zk_money=$rows['money'];
								}
								$rows['zk_money']=$zk_money;
								if($rows['use_where']){
									if($rows['money_int']=='1'){
										if($romoney<=$rows['money_int']){
											$couponList[$key]=$rows;
										}
									}else{
										if($romoney>=$rows['money_int']){
											$couponList[$key]=$rows;
										}
									}	
								 }else{
									 $couponList[$key]=$rows;
								 }
							}
							foreach($couponList as $key=>$rows){
								if($coupon==$rows['id']){
									if($rows['f_pro_type']=='1'&&$rows['f_pro_int']){
										$f_pro_int=explode(",",$rows['f_pro_int']);
										$cate_id_chk=0;
										foreach($f_pro_int as $rows1){
											if(intval($rows1)==intval($productinfo['cate_id'])) $cate_id_chk++;
										}
										if($cate_id_chk){
											$discount=$rows['zk_money'];
											$coupon_id=$rows['id'];	
										}
									}else if($rows['f_pro_type']=='2'&&$rows['f_pro_int']){
										$f_pro_int=explode(",",$rows['f_pro_int']);
										$cate_id_chk=0;
										foreach($f_pro_int as $rows1){
											if(intval($rows1)==intval($productinfo['id'])) $cate_id_chk++;
										}
										if($cate_id_chk){
											$discount=$rows['zk_money'];
											$coupon_id=$rows['id'];	
										}
									}else{
										$discount=$rows['zk_money'];
										$coupon_id=$rows['id'];	
									}
								}
							}
						}
						
						//会员卡
						$card_money=0;
						if(!$discount){
							$user_card = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_card where user_id=$user_id and validity_time>".time()." order by id desc");
							foreach($user_card as $key=>$rows){
								$user_card_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."card where id=".$rows['cate_id']." and is_effect=1");
								if($user_card_info){
									$c_cate_list=explode(",",$user_card_info['cate_list']);
									foreach($c_cate_list as $rows1){
										if($productinfo['cate_id']==$rows1){
											$card_money=$romoney-($romoney*($rows['discount']/10));
											$card_money=round($card_money,2);
										}
									}
								}
							}
						}
						
						$romoney = $romoney-$discount-$card_money;
						$romoney=$romoney<0?0:$romoney;
					
					}else{
						$discount=0;
						$card_money=0;
						if(!$paymoney){
							showErr("请输入结算金额",1,"");
						}
						if(!is_numeric($paymoney)){
							showErr("请输入正确的结算金额",1,"");
						}
						$romoney=round($paymoney,2);
						if($paymoney<1){
							showErr("支付金额不能不小于1元",1,"");
						}
					}
					
					//生成订单
					$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".intval($user_info['id'])." and is_delete=0");
					if(!$user_info){
						es_session::delete("user_info");
						showErr("请先登录后操作",1,"");
					}
					$pay_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Weixin'");
					
					//生成支付订单
					$checkWord = '';
					$checkChar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ1234567890';
					//获取 4 位随机数。
					for($num=0; $num<4; $num++){
					  $char=rand(0, strlen($checkChar)-1);
					  $checkWord.=$checkChar[$char];
					}
					$ordernu = time().$checkWord;
					
					$redata=array();
					$redata['ordernu'] = $ordernu;
					$redata['user_id'] = $user_info['id'];
					$redata['user_name'] = $user_info['user_name'];
					$redata['user_admin_id'] = $user_admin_id;
					$redata['pid'] = $productinfo['id'];
					$payid=$pay_info['id'];
					$paymod=$pay_info['class_name'];
					$redata['user_admin_id'] = $user_admin_id;
					$redata['car_id'] = $car_id;
					$redata['lx_name'] = $names;
					$redata['mobile'] = $mobile;
					$redata['company'] = $company;
					$redata['brief'] = $brief;
					$redata['proType'] = $productinfo['proType'];
					$redata['sumint'] = $productinfo['sumint'];
					$redata['price'] = $productinfo['price'];
					$redata['money'] = $romoney;
					$redata['discount'] = $discount;
					$redata['coupon_id'] = $coupon_id;
					$redata['card_money'] = $card_money;
					$redata['quantity'] = 1;
					$redata['pay_id'] = $payid;
					$redata['paymod'] = $paymod;
					$redata['plist'] = $plist;
					$redata['attrid'] = $new_attrid;
					$redata['attrprice'] = $attrprice;
					$redata['title'] = $productinfo['title'];
					$redata['pid'] = $id;
					$redata['pay_id'] = $payid;
					$redata['status'] = 0;
					$redata['ip'] = get_client_ip();
					$redata['start_time'] = $start_time;
					$redata['create_time'] = time();
					
					if($payType){
						
						$u_remoney=get_user_money($user_id);
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
							$userdata['objid'] = $id;
							$userdata['info'] = "订单结算";
							$userdata['create_time'] = time();
							$GLOBALS['db']->autoExecute(DB_PREFIX."user_money",$userdata); //插入
							$redata['status'] = $productinfo['is_end']?3:1;
							$GLOBALS['db']->autoExecute(DB_PREFIX."order",$redata); //插入
							$redid = $GLOBALS['db']->insert_id();
							//订单统计
							$order_count = $GLOBALS['db']->getOne("select count(id) as c from ".DB_PREFIX."order where user_admin_id=$user_admin_id");
							$order_count=$order_count?$order_count:0;
							$GLOBALS['db']->query("update ".DB_PREFIX."user_admin set order_count=$order_count where id=$user_admin_id");
							//推荐返利
							comm_fanli($redid,$romoney);
							showSuccess("支付成功",1,"");
						}
					}else{
						if($romoney){
							$GLOBALS['db']->autoExecute(DB_PREFIX."order",$redata); //插入
							$redid = $GLOBALS['db']->insert_id();
							//订单统计
							$order_count = $GLOBALS['db']->getOne("select count(id) as c from ".DB_PREFIX."order where user_admin_id=$user_admin_id");
							$order_count=$order_count?$order_count:0;
							$GLOBALS['db']->query("update ".DB_PREFIX."user_admin set order_count=$order_count where id=$user_admin_id");
							showSuccess($redid,1,"");
						}else{
							$redata['status'] = 1;
							$GLOBALS['db']->autoExecute(DB_PREFIX."order",$redata); //插入
							$redid = $GLOBALS['db']->insert_id();
							//订单统计
							$order_count = $GLOBALS['db']->getOne("select count(id) as c from ".DB_PREFIX."order where user_admin_id=$user_admin_id");
							$order_count=$order_count?$order_count:0;
							$GLOBALS['db']->query("update ".DB_PREFIX."user_admin set order_count=$order_count where id=$user_admin_id");
							showSuccess('success',1,"");
						}
					}
					
				}else{
					showErr("产品信息不存在",1,"");
				}
			}else{
			
				$product_attr = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_attr where pid=$id order by sort asc,id desc");
				$new_attrid=$attrid;
				$attrid = explode(",",$attrid);
				$attrMoney=0;
				$attrdata=array();
				$romoney = round($productinfo['price'],2);
				foreach($product_attr as $key=>$rows){
					$attrList = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_attrlist where attrid=".$rows['id']." order by id asc ");
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
				
				$qty=1;
				$new_plist="";
				$p_str_arr=explode(",",",前保险杠,后保险杠,左前翼子板,右前翼子板,左后翼子板,右后翼子板,左前车门,右前车门,左后车门,右后车门,前车盖,后车盖,车顶,左裙边,右裙边,左后视镜,右后视镜,左A柱,右A柱,左C柱,右C柱,整车喷漆");
				$p_str="";
				if($productinfo['penpi']){
					if(!$plist){
						showErr("请选择油漆产品",0,"/product/detail/$id.html");
					}else{
						$plist_arr=explode(",",$plist);
						$qt_v=0;
						foreach($plist_arr as $rows1){
							if(intval($rows1)>0){
								if(intval($rows1)<14){
									$qt_v=$qt_v+1;
								}else{
									$qt_v=$qt_v+0.5;
								}
								$new_plist=$new_plist?",".(intval($rows1)):intval($rows1);
								$p_str.="[".$p_str_arr[intval($rows1)]."]";
							}
							if(intval($rows1)==22){
								$qt_v=9999;
								$p_str="整车喷漆";
							}
						}
						if($qt_v>1000){
							 $qt_v=17;	
							 $new_plist=22;
						}
						$qty=$qt_v;
					}
				}
				
				$romoney += $attrMoney;
				$romoney = $romoney * $qty;
				
				$user_coupon = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_coupon where user_id=$user_id and status=0 and start_time<".time()." and end_time>".time()." order by id desc");
				$couponList=array();
				foreach($user_coupon as $key=>$rows){
					if($rows['e_type']){
						$zk_money=round($romoney-($romoney*$rows['money']/100),2);
					}else{
						$zk_money=$rows['money'];
					}
					$rows['zk_money']=$zk_money;
					
					$coupon_array=array();
					if($rows['use_where']){
						if($rows['use_where']=='1'){
							if($romoney<=$rows['money_int']){
								$coupon_array=$rows;
							}
						}else{
							if($romoney>=$rows['money_int']){
								$coupon_array=$rows;
							}
						}	
					 }else{
						 $coupon_array=$rows;
					 }
					 if($coupon_array){
						 //是否指定产品/类目
						 if($rows['f_pro_type']=='1'&&$rows['f_pro_int']){
							$f_pro_int=explode(",",$rows['f_pro_int']);
							$cate_id_chk=0;
							foreach($f_pro_int as $rows1){
								if(intval($rows1)==intval($productinfo['cate_id'])) $cate_id_chk++;
							}
							if($cate_id_chk) $couponList[$key]=$coupon_array;
						 }else if($rows['f_pro_type']=='2'&&$rows['f_pro_int']){
							$f_pro_int=explode(",",$rows['f_pro_int']);
							$cate_id_chk=0;
							foreach($f_pro_int as $rows1){
								if(intval($rows1)==intval($productinfo['id'])) $cate_id_chk++;
							}
							if($cate_id_chk) $couponList[$key]=$coupon_array;
						 }else{
							 $couponList[$key]=$coupon_array;
						 }
					 }
					 
				}
				//echo print_r($couponList);
				$GLOBALS['tmpl']->assign("couponList",$couponList);
				
				//会员卡
				$card_money=0;
				$user_card = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_card where user_id=$user_id and validity_time>".time()." order by id desc");
				foreach($user_card as $key=>$rows){
					$user_card_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."card where id=".$rows['cate_id']." and is_effect=1");
					if($user_card_info){
						$c_cate_list=explode(",",$user_card_info['cate_list']);
						foreach($c_cate_list as $rows1){
							if($productinfo['cate_id']==$rows1){
								$card_money=$romoney-($romoney*($rows['discount']/10));
								$card_money=round($card_money,2);
							}
						}
					}
				}
				
				$user_cart = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_cart where user_id=$user_id order by is_default desc,id desc");
				$GLOBALS['tmpl']->assign("user_cart",$user_cart);
				$GLOBALS['tmpl']->assign("plist",$plist);
				$GLOBALS['tmpl']->assign("p_str",$p_str);
				$GLOBALS['tmpl']->assign("qty",$qty);
				$GLOBALS['tmpl']->assign("cid",$cid?$cid:0);
				$GLOBALS['tmpl']->assign("card_money",$card_money);
				$GLOBALS['tmpl']->assign("user_info",$user_info);
				$GLOBALS['tmpl']->assign("user_admin",$user_admin);
				$GLOBALS['tmpl']->assign("attrid",$new_attrid);
				$GLOBALS['tmpl']->assign("romoney",$romoney);
				$GLOBALS['tmpl']->assign("attrdata",$attrdata);
				$GLOBALS['tmpl']->assign("productinfo",$productinfo);
				
				$GLOBALS['tmpl']->display("page/checkout.html");
			}
		}else{
			showErr("请先登录后操作",$ajax,"/user/login");
			
		}
	}
	
	
	function success(){
		showSuccess("订单支付成功",0,"/user/order");	
	}

}	
?>
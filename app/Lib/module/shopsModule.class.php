<?php
class shopsModule extends SiteBaseModule
{
	public function index()
	{
		//22.530883,114.059782
		$id=intval($_REQUEST['id']);
		$lng=round($_REQUEST['lng']?$_REQUEST['lng']:0,8);
		$lat=round($_REQUEST['lat']?$_REQUEST['lat']:0,8);
		$city_id=intval($_REQUEST['city_id']);
		$mtype=intval($_REQUEST['mtype']);
		$o=intval($_REQUEST['o']);
		$k=htmlstrchk($_REQUEST['k']);
		$ajax=intval($_REQUEST['ajax']);
		$o_str=array(0=>"默认排序",1=>"附近优先",2=>"累计服务",3=>"评分最高");
		if($city_id){
			 $thiscity= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."city where is_effect=1 and id=$city_id");
			 if($thiscity){
				 $_SESSION['cityid']=$thiscity['id'];
				 $GLOBALS['tmpl']->assign("thiscity",$thiscity);
				 $wr.=" and city_id=$city_id";	 
			 }
		}
		
		$thiscity= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."city where is_effect=1 and id=".intval($_SESSION['cityid']));
		$lng=!$lng?$thiscity['lng']:$lng;
		$lat=!$lat?$thiscity['lat']:$lat;
		$city_id=$_SESSION['cityid'];
		
		if($id) $wr.=" and cate_list like '%,$id,%'";
		if($mtype) $wr.=" and cate_id=$mtype";
		if($k) $wr.=" and company like '%$k%'";
		$orderby=" id desc" ;
		if($o){
			if($o==1) $orderby=" distance asc , id desc" ;
			if($o==2) $orderby=" order_count desc , id desc" ;
			if($o==3) $orderby=" appint desc , id desc" ;
			if($o==4) $orderby=" id desc" ;
		}
		
		//分类
		$product_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_cate where is_effect = 1 order by sort asc, id desc");
		foreach($product_cate as $key=>$rows){
			$product_cate[$key]['clist']=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."product where is_effect = 1 and cate_id=".$rows['id']." order by sort asc, id desc");
		}
		$GLOBALS['tmpl']->assign("product_cate",$product_cate);
		
		//分类
		$cateinfo = $GLOBALS['db']->getRow("select id,title,cate_id from ".DB_PREFIX."product where is_effect = 1 and id=$id");
		$GLOBALS['tmpl']->assign("pro_name",$cateinfo['title']);
		$GLOBALS['tmpl']->assign("cate_id",$cateinfo['cate_id']?$cateinfo['cate_id']:0);
		
		//城市
		$city_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."city where is_effect = 1 order by sort asc, id desc");
		$GLOBALS['tmpl']->assign("city_list",$city_list);
		
		//分类
		$shop_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."shop_cate where is_effect = 1 order by sort asc, id desc");
		$GLOBALS['tmpl']->assign("shop_cate",$shop_cate);
		
		
		$GLOBALS['tmpl']->assign("id",$id?$id:"");
		$GLOBALS['tmpl']->assign("o",$o);
		$GLOBALS['tmpl']->assign("o_str",$o_str[$o]);
		
		$GLOBALS['tmpl']->assign("k",$k);
		$GLOBALS['tmpl']->assign("mtype",$mtype);
		$GLOBALS['tmpl']->assign("city_id",$city_id);
		
		if($ajax){
			$sql = "select id,icon,company,appint,cate_id,order_count,address,is_stop_order,ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-lat*PI()/180)/2),2)+COS($lat*PI()/180)*COS(lat*PI()/180)*POW(SIN(($lng*PI()/180-lng*PI()/180)/2),2)))*1000) AS distance from ".DB_PREFIX."user_admin where is_effect = 1 and is_delete=0 $wr order by $orderby ";
			$pagelist=thispage1($sql,1,6,10,"&id=".$_GET['id']);
			foreach($pagelist['list'] as $key=>$rows){
				//$pagelist['list'][$key]['o_count']=$GLOBALS['db']->getOne("select count(id) as c from ".DB_PREFIX."order where is_delete = 0 and `status` in(1,2,3) and user_admin_id=".$rows['id']);
				$pagelist['list'][$key]['mtype']=$GLOBALS['db']->getOne("select title from ".DB_PREFIX."shop_cate where id=".$rows['cate_id']);
				$pagelist['list'][$key]['distance']=round($rows['distance']/1000,2);
			}
			ajax_return($pagelist);
			//$GLOBALS['tmpl']->assign("pagelist",$pagelist);
			//$GLOBALS['tmpl']->display("page/shops_ajax.html");
		}else{
			
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
			
			
			
			$GLOBALS['tmpl']->display("page/shops.html");
		}

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
	
	public function city_ajax()
	{
		$k=htmlstrchk($_REQUEST['k']);
		$return['status']=0;
		if($k){
			 $thiscity= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."city where is_effect=1 and title='".trim($k)."'");
			 if($thiscity){
				 $_SESSION['cityid']=$thiscity['id'];
				$return['status']=1;
			 }
		}
		$result['status'] = 1;
		$result['info'] = $_SESSION['cityid'];
		ajax_return($result);
	}

	//商品详细
	public function detail() {
		$id=intval($_REQUEST['id']);
     	$shopsinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_admin where is_effect = 1 and is_delete=0 and id = $id");
		$shop_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."shop_cate where is_effect = 1 order by sort asc, id desc");
		$GLOBALS['tmpl']->assign("shop_cate",$shop_cate);
		$shopsinfo['mtype']=$GLOBALS['db']->getOne("select title from ".DB_PREFIX."shop_cate where id=".intval($shopsinfo['cate_id']));
		$shopsinfo['city']=$GLOBALS['db']->getOne("select title from ".DB_PREFIX."city where id=".intval($shopsinfo['city_id']));
		$GLOBALS['tmpl']->assign("id",$id);
		$GLOBALS['tmpl']->assign("shopsinfo",$shopsinfo);
		if(!$shopsinfo) header("Location:/shop");
		$user_info = es_session::get("user_info");
		//$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".intval($user_info['id']));
		if($user_info){
			$user_cart=$GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."user_cart where user_id=".$user_info['id']." ORDER BY is_default desc,id desc LIMIT 0,1");
			$GLOBALS['tmpl']->assign("user_cart",$user_cart);
		}
		
		$comm_pro_list = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_admin_obj where admin_id = $id order by order_sum desc, id desc");
		$new_pro_data=array();
		foreach($comm_pro_list as $key=>$rows){
			$proinfo = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."product where is_effect = 1 and id=".$rows['proid']);
			if($proinfo){
				$product_attr_price = $GLOBALS['db']->getOne("SELECT price FROM ".DB_PREFIX."product_attrlist where pid=".$rows['proid']." order by price asc,id desc");
				$product_attr_price = $product_attr_price ?$product_attr_price :0;
				$comm_pro_list[$key]['title']=$proinfo['title'];
				$comm_pro_list[$key]['price']=$proinfo['price']+$product_attr_price;
				$new_pro_data[$key]=$comm_pro_list[$key];
			}
		}
		$GLOBALS['tmpl']->assign("comm_pro_list",$new_pro_data);
		$GLOBALS['tmpl']->assign("comm_pro_sum",count($new_pro_data));
		
		$product_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_cate where id in(select cate_id from ".DB_PREFIX."product where id in(select proid from ".DB_PREFIX."user_admin_obj where admin_id = $id)) and is_effect = 1 order by sort asc, id desc");
		foreach($product_cate as $key=>$rows){
			$product_cate[$key]['pcount']=$GLOBALS['db']->getOne("SELECT count(id) as c FROM ".DB_PREFIX."product where cate_id=".$rows['id']." and is_effect = 1");
		}
		$GLOBALS['tmpl']->assign("product_cate",$product_cate);
		
		$comments_count = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."order_msg where is_open=1 and user_admin_id=$id");
		$GLOBALS['tmpl']->assign("comments_count",$comments_count);
		
		$shop_end = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."product where is_end=1 and is_effect = 1 ");
		$pcount=$GLOBALS['db']->getOne("SELECT count(id) as c FROM ".DB_PREFIX."product where cate_id=".$shop_end['id']." and is_effect = 1");
		$shop_end['pcount']=$GLOBALS['db']->getOne("SELECT count(id) as c FROM ".DB_PREFIX."product where cate_id=".$shop_end['id']." and is_effect = 1");
		$GLOBALS['tmpl']->assign("shop_end",$shop_end);
		
		$GLOBALS['tmpl']->display("page/shops1.html");
	}
	
	public function comments(){
		$id=intval($_REQUEST['id']);
		$shopsinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_admin where is_effect = 1 and is_delete=0 and id = $id");
		$GLOBALS['tmpl']->assign("id",$id);
		$GLOBALS['tmpl']->assign("shopsinfo",$shopsinfo);
		if(!$shopsinfo) header("Location:/shop");
		$comments_count = $GLOBALS['db']->getOne("select count(id) from ".DB_PREFIX."order_msg where is_open=1 and user_admin_id=$id");
		$GLOBALS['tmpl']->assign("comments_count",$comments_count);
		$GLOBALS['tmpl']->display("page/shops_msg.html");
	}
	
	
	public function comments_ajax()
	{
		$id=intval($_REQUEST['id']);
		$return['status']=0;
		if($id){
			$sql= "select * from ".DB_PREFIX."order_msg where is_open=1 and user_admin_id=$id order by id desc";
			$pagelist=thispage1($sql,1,5,10,"&id=".$id);
			$pagelist['status'] = 1;
			foreach($pagelist['list'] as $key=>$rows){
				$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".intval($rows['user_id']));
				$pagelist['list'][$key]['u_icon']=$user_info['nickname'];
				$pagelist['list'][$key]['imglist']=$rows['icon']?explode(",",$rows['icon']):array();
				$pagelist['list'][$key]['nickname']=$user_info['nickname']?$user_info['nickname']:newphonenum('phone',$user_info['nickname']);
			}
			ajax_return($pagelist);
		}else{
			$result['status'] = 0;
			$result['info'] = '';
			ajax_return($result);
		}
	}
	

}	
?>
<?php
class user_cardModule extends SiteBaseModule
{
	public function index()
	{
		$card = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."card where is_effect = 1 and is_delete = 0 order by sort asc, id desc");
		$GLOBALS['tmpl']->assign("card",$card);
		$GLOBALS['tmpl']->display("/page/user_card.html");
	}
	
	//新闻内容
	public function detail() {
		$id=intval($_REQUEST['id']);
     	$bodyinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."card where is_effect = 1 and id = $id ");
		$GLOBALS['tmpl']->assign("bodyinfo",$bodyinfo);	
		if(!$bodyinfo) header("Location:/");
		$GLOBALS['tmpl']->display("page/user_card1.html");
	}
	
	public function pay(){
		$user_info = es_session::get("user_info");
		$id=intval($_REQUEST['id']);
		$ajax=intval($_REQUEST['ajax']);
		$payType=intval($_REQUEST['payType']);
		$attrid=htmlstrchk($_REQUEST['attrid']);
     	$attrid=$attrid?$attrid:0;
		$user_id = intval($user_info['id']);
		
		$miniProgram=0;
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'miniProgram') !== false) {
			//"小程序";
			$miniProgram=1;
		} else {
			//"微信";
		}
		
		if($user_info){
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=$user_id");
			$productinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."card where is_effect = 1 and id=$id");
			if($ajax){
				if($productinfo){
					$romoney = round($productinfo['price'],2);
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
							$userdata['info'] = "会员卡购买";
							$userdata['create_time'] = time();
							$GLOBALS['db']->autoExecute(DB_PREFIX."user_money",$userdata); //插入
							$redata['status'] = 1;
							
							
							//创建会员卡订单
							$user_card = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_card where cate_id=$id and user_id=$user_id");
							if(!$user_card){
								$userdata=array();
								$userdata['user_name'] = $user_info['user_name'];
								$userdata['user_id'] = $user_info['id'];
								$userdata['validity_time'] = strtotime(date("Y-m-d"))+($productinfo['validity_int']*(24*3600));
								$userdata['price'] = $romoney;
								$userdata['cate_id'] = $id;
								$userdata['is_effect'] = 1;
								$userdata['discount'] = $productinfo['discount'];
								$userdata['create_time'] = time();
								$GLOBALS['db']->autoExecute(DB_PREFIX."user_card",$userdata); //插入
								$redid = $GLOBALS['db']->insert_id();
							}else{
								$validity_time=$user_card['validity_time']<strtotime(date("Y-m-d"))?strtotime(date("Y-m-d")):$user_card['validity_time'];
								$validity_time=$validity_time+($productinfo['validity_int']*(24*3600));
								$GLOBALS['db']->query("update ".DB_PREFIX."user_card set price=$romoney,validity_time=$validity_time where user_id=$user_id and id=".$user_card['id']);
								$redid = $user_card['id'];
							}
							
							$userdata=array();
							$userdata['user_id'] = $user_info['id'];
							$userdata['cate_id'] = $id;
							$userdata['price'] = $romoney;
							$userdata['create_time'] = time();
							$GLOBALS['db']->autoExecute(DB_PREFIX."card_log",$userdata); //插入
							
							//推荐返利
							comm_fanli($redid,$romoney,1);
							showSuccess("支付成功",1,"");
						}
					}else{
						showSuccess($id,1,"");
					}
					
				}else{
					showErr("会员卡信息不存在",1,"");
				}
			}else{
			
				$romoney = round($productinfo['price'],2);
				$GLOBALS['tmpl']->assign("user_info",$user_info);
				$GLOBALS['tmpl']->assign("miniProgram",$miniProgram);
				$GLOBALS['tmpl']->assign("romoney",$romoney);
				$GLOBALS['tmpl']->assign("productinfo",$productinfo);
				$GLOBALS['tmpl']->display("page/card_checkout.html");
			}
		}else{
			showErr("请先登录后操作",$ajax,"/user/login");
			
		}
	}
	
	
	function success(){
		showSuccess("订单支付成功",0,"/user/user_card");	
	}
	
}	
?>
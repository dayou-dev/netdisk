<?php
// +----------------------------------------------------------------------
// | 中海融通金融服务有限公司
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.yfw.hk All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------
// 支付模型
require_once(APP_ROOT_PATH . 'system/payment/wechat/lib/WxPay.Api.php');
require_once(APP_ROOT_PATH . 'system/payment/wechat/WxPay.Config.php');
require_once(APP_ROOT_PATH . 'system/payment/wechat/WxPay.JsApiPay.php');
require_once(APP_ROOT_PATH . 'system/payment/wechat/lib/WxPay.Notify.php');
require_once(APP_ROOT_PATH . 'system/payment/wechat/notify.php');
require_once(APP_ROOT_PATH . 'system/payment/wechat/log.php');

class paymentModule extends SiteBaseModule
{
	public function index(){
		$id=intval($_REQUEST['id']);
		$mtype=intval($_REQUEST['mtype']); //0订单　1续费 2充值
		$payType=intval($_REQUEST['payType']);
		$user_info = es_session::get("user_info");
		$sql="select * from ".DB_PREFIX."order where id = $id";
		if($mtype==1) $sql="select * from ".DB_PREFIX."order_renew where id = $id";
		if($mtype==2) $sql="select * from ".DB_PREFIX."user_recharge where id = $id";
		if($mtype==3) $sql="select * from ".DB_PREFIX."card where id = $id";
		if($mtype==4) $sql="select * from ".DB_PREFIX."goods_order where id = $id";
		$orderInfo = $GLOBALS['db']->getRow($sql);
		if(!$user_info){
			showErr("请登录后操作",$payType,"/user/login");
		}
		
		$miniProgram=0;
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'miniProgram') !== false) {
			//"小程序";
			$miniProgram=1;
		} else {
			//"微信";
		}
		
		if($orderInfo)
		{
			$order_status = $orderInfo['status'];
			if(!$order_status)
			{
				
				$ispay=intval($_REQUEST['ispay']);
				
				if($ispay){
					$sql="select * from ".DB_PREFIX."payment where class_name='Weixin'";
					$payment_info = $GLOBALS['db']->getRow($sql);
	
					if(!$payment_info)
					{
						showErr("支付信息不存在.",$payType,"/user");
					}else{
						
						if($orderInfo['money']<0&&$mtype==4){
							$GLOBALS['db']->getRow("update ".DB_PREFIX."goods_order set `status` = 1 where id=$id");
							header("Location:/goods/success");
							exit();	
						}
						$openId='';
						if($payment_info['class_name']=='Weixin'&&!$miniProgram){
							$tools = new JsApiPay();
							$openId = $tools->GetOpenid();
						}
						
						require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
						//生成支付订单
						$checkWord = '';
						$checkChar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ1234567890';
						//获取 4 位随机数。
						for($num=0; $num<4; $num++){
						  $char=rand(0, strlen($checkChar)-1);
						  $checkWord.=$checkChar[$char];
						}
						$ordernu = time().$checkWord;
						
						if($mtype==3) $orderInfo['ordernu']=$orderInfo['title'];
						$orderInfo['money']=$orderInfo['money']?$orderInfo['money']:$orderInfo['price'];
						
						$idata=array();
						$idata['user_id'] = $user_info['id'];
						$idata['user_name'] = $user_info['user_name'];
						$idata['order_id'] = $id;
						$idata['ordernu'] = $orderInfo['ordernu'];
						$idata['payid'] = intval($payment_info['id']);
						$idata['payname'] = $payment_info['name'];
						$idata['paymod'] = $payment_info['class_name'];
						$idata['money'] = round($orderInfo['money'],2);
						$idata['info'] = "";
						$idata['mtype'] = $mtype;
						$idata['trade_nu'] = $ordernu;
						$idata['create_time'] = time();
						$GLOBALS['db']->autoExecute(DB_PREFIX."payment_log",$idata); //插入
						$redid = $GLOBALS['db']->insert_id();
						
						//指定微信
						/*$result['status'] = 1;
						$result['info'] = $redid;
						ajax_return($result);
						*/
						
						
						if($miniProgram){
							$result['status'] = 1;
							$result['info'] = $redid;
							ajax_return($result);
						}else{
							$payment_class = $payment_info['class_name']."_payment";
							$payment_object = new $payment_class();
							//echo $openId.'###'.time();
							$payment_code = $payment_object->get_payment_code($redid,$openId);
							// echo $payment_code;
						}
						
						
					}
				}else{
					$productinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."product where is_effect = 1 and is_delete=0 and id=".$orderInfo['pid']);
					if($orderInfo['attrid']){
						$product_attr = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_attr where pid='".$orderInfo['pid']."' order by sort asc,id desc");
						$attrid = explode(",",$orderInfo['attrid']);
						$attrdata=array();
						foreach($product_attr as $key=>$rows){
							$attrList = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."product_attrlist where attrid=".$rows['id']." order by id asc ");
							foreach($attrList as $keys=>$rows1){
								foreach($attrid as $rows2){
									if($rows2==$rows1['id']){
										$attrdata[$key]['val']=$rows1['title'];
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
							if($orderInfo['plist']){
								$plist_arr=explode(",",$orderInfo['plist']);
								$qt_v=0;
								foreach($plist_arr as $rows1){
									if(intval($rows1)>0){
										//$qt_v++;
										if(intval($rows1)<14){
											$qt_v=$qt_v+1;
										}else{
											$qt_v=$qt_v+0.5;
										}
										$p_str.="[".$p_str_arr[intval($rows1)]."]";
									}
									if(intval($rows1)==22){
										$qt_v=9999;
										$p_str="整车喷漆";
									}
								}
								if($qt_v>1000){
									 $qt_v=17;	
								}
								$qty=$qt_v;
							}
						}
						
						$GLOBALS['tmpl']->assign("p_str",$p_str);
						$GLOBALS['tmpl']->assign("qty",$qty);
					}
					$orderInfo['company']=$GLOBALS['db']->getOne("select company from ".DB_PREFIX."user_admin where id=".intval($orderInfo['user_admin_id']));
					$GLOBALS['tmpl']->assign("productinfo",$productinfo);
					$GLOBALS['tmpl']->assign("mtype",$mtype);
					$GLOBALS['tmpl']->assign("miniProgram",$miniProgram);
					$GLOBALS['tmpl']->assign("attrid",$attrid);
					$GLOBALS['tmpl']->assign("attrdata",$attrdata);
					$GLOBALS['tmpl']->assign("order",$orderInfo);
					$GLOBALS['tmpl']->display("page/payment_pay.html");
				}
				//echo APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
				//exit();
				/*if($payment_code['Link']){
					header("Location:".$payment_code['Link']);
					exit();
				}
				$GLOBALS['tmpl']->assign("page_title",$payment_info['name']);
				$GLOBALS['tmpl']->assign("payment_code",$payment_code);
				$GLOBALS['tmpl']->assign("order",$orderInfo);
				$GLOBALS['tmpl']->display("page/payment_pay.html");*/
			}
			else
			{
				showErr("该笔订单已完成支付",$payType,"/user");
			}
		}
		else
		{
			showErr("订单信息不存在.",$payType,"/user");
		}
	}
	
	public function isOrderPaid()
	{
		$id=intval($_REQUEST['id']);
		$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."order where id = $id");
		$order_status_id=intval($order_info['order_status_id']);
		if($order_status_id>0&&$order_status_id<3)
		{
			showSuccess("支付完成",1);
		}
		else
		{
			showErr("支付处理中",1);
		}
	}
	
	public function response()
	{
		//支付跳转返回页
		if($GLOBALS['pay_req']['class_name'])
			$_REQUEST['class_name'] = $GLOBALS['pay_req']['class_name'];
			
		$class_name = addslashes(trim($_REQUEST['class_name']));
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = '".$class_name."'");
		if($payment_info)
		{
			require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
			$payment_class = $payment_info['class_name']."_payment";
			$payment_object = new $payment_class();
			$payment_code = $payment_object->response();
		}
		else
		{
			showErr($GLOBALS['lang']['PAYMENT_NOT_EXIST']);
		}
	}
	
	public function notify()
	{
		//支付跳转返回页
		$class_name = addslashes(trim($_REQUEST['class_name']));
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = '".$class_name."'");
		if($payment_info)
		{
			require_once APP_ROOT_PATH."system/payment/".$payment_info['class_name']."_payment.php";
			$payment_class = $payment_info['class_name']."_payment";
			$payment_object = new $payment_class();
			$payment_code = $payment_object->notify();
		}
		else
		{
			showErr("当前支付通道不可用，操作失败",0,APP_ROOT."/",1);
		}
	}
	
}
?>
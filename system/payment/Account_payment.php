<?php
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'余额支付',
	'account_credit'	=>	'帐户余额',
	'use_user_money' =>	'使用余额支付',
	'use_all_money'	=>	'全额支付',
	'USER_ORDER_PAID'	=>	'%s订单付款,付款单号%s'
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Account';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '';
    return $module;
}

// 余额支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Account_payment implements payment {
	public function get_payment_code($payinfo_id)
	{		
		$payinfo_id=intval($payinfo_id);
		$payInfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_log where id = ".$payinfo_id);
		$mid=intval($payInfo['payid']);
		$rs = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."money_cate where id = ".$mid);
		if($rs&&$payInfo)
		{
			$order_id=intval($payInfo['order_id']);
			$sql="select * from ".DB_PREFIX."order where id = ".$order_id;
			if($payInfo['mtype']) $sql="select * from ".DB_PREFIX."user_power where id = ".$order_id;
			$orderInfo=$GLOBALS['db']->getRow($sql);
			if($orderInfo['status']||$payInfo['status']) showErr("该笔订单已处理，请勿反覆操作");
			$umoney = get_fxcmoney($orderInfo['user_id'],$mid);
			
			//echo "select * from ".DB_PREFIX."order where id = ".$order_id;
			//$umoney =  $umoney * $rs['money_rate'];
			
			$paymoney=$payInfo['mtype']?$orderInfo['paymoney']:$orderInfo['money'];
			$fmoney = $orderInfo['money'] / $rs['money_rate'];
			$fmoney = round($fmoney,8);
			$ordernu = $payInfo['mtype']?$orderInfo['order_nu']:$orderInfo['ordernu'];
			$oid = intval($orderInfo['id']);
			
			if($umoney>=$fmoney){
				$redata=array();
				$redata['user_id'] = $orderInfo['user_id'];
				$redata['user_name'] = $orderInfo['user_name'];
				$redata['money'] = $fmoney;
				$redata['ymoney'] = $umoney - $fmoney;
				$redata['objid'] = $oid;
				$redata['mtype'] = 1;
				$redata['deal_id'] = $mid;
				$redata['info'] = $payInfo['mtype']?"购买电费包":"购买算力";
				$redata['create_time'] = time();
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_fxc",$redata); //插入
				//单独电费包购买
				if($payInfo['mtype']){
					$GLOBALS['db']->query("update ".DB_PREFIX."user_power set `status`=1 where id = $oid");
					$redata=array();
					$redata['user_id'] = $orderInfo['user_id'];
					$redata['user_name'] = $orderInfo['user_name'];
					$redata['money'] = $orderInfo['money'];
					$redata['ymoney'] =$orderInfo['money'];
					$redata['objid'] = $oid;
					$redata['mtype'] = 0;
					$redata['deal_id'] = 0;
					$redata['info'] = "购买电费包";
					$redata['create_time'] = time();
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_power_log",$redata); //插入
				}else{
					//算力购买
					$powerInfo=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_power  where relid = $oid");
					//关联电费同步支付状态
					if($powerInfo){
						$GLOBALS['db']->query("update ".DB_PREFIX."user_power set `status`=1 where id = ".$powerInfo['id']);
						$redata=array();
						$redata['user_id'] = $orderInfo['user_id'];
						$redata['user_name'] = $orderInfo['user_name'];
						$redata['money'] = $powerInfo['money'];
						$redata['ymoney'] = $powerInfo['money'];
						$redata['objid'] = $powerInfo['id'];
						$redata['mtype'] = 0;
						$redata['deal_id'] = $oid;
						$redata['info'] = "购买电费包";
						$redata['create_time'] = time();
						$GLOBALS['db']->autoExecute(DB_PREFIX."user_power_log",$redata); //插入
					}
					$o_status=2;
					if(time()<$orderInfo['start_time']) $o_status=1;
					$GLOBALS['db']->query("update ".DB_PREFIX."order set `status`=$o_status where id = $oid");
				}
				$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set `status`=1 where id = $payinfo_id");
				//header("location:/suanli/success/$order_id.html");
				showSuccess("订单支付成功",0,$payInfo['mtype']?"/user/power_list":"/user/order_list");
			}else{
				showErr("余额不足，支付失败","继续支付","/user/".($payInfo['mtype']?"power":"order")."_detail/$oid.html");
			}
		}else{
			showErr("支付失败","继续支付","/user/".($payInfo['mtype']?"power":"order")."_detail/$oid.html");	
		}
	}
	
	/**
	 * 直接处理付款单
	 * @param unknown_type $payment_notice
	 */
	public function response($payment_notice)
	{
		return false;	
	}
	
	public function notify($request)
	{
		return false;
	}
	
	public function get_display_code()
	{
		return false;
	}
}
?>
<?php
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'支付宝支付',
	'alipay_appid'	=>	'应用ID',
	'alipay_private_key'	=>	'支付宝私钥',
	'alipay_public_key'		=>	'支付宝公钥',
	'GO_TO_PAY'	=>	'前往支付宝在线支付',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
);
$config = array(
	'alipay_appid'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //应用ID
	'alipay_private_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //支付宝私钥: 
	'alipay_public_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //支付宝公钥
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Alipay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = 'http://act.life.alipay.com/systembiz/fangwei/';
    return $module;
}

// 支付宝支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Alipay_payment implements payment {

	public function get_payment_code($payinfo_id)
	{
		$payInfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_log where id = ".$payinfo_id);
		$mid=intval($payInfo['payid']);
		if(!$payInfo) showErr("支付失败");
		if($payInfo['status']) showErr("该笔订单已处理，请勿反覆操作");
		$payment_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where is_effect=1 and id = ".$mid);
		if(!$payment_info) showErr("该支付通道不可用，详情请与平台客服咨询");
		$order_id=intval($payInfo['order_id']);
		$sql="select * from ".DB_PREFIX."order where id = ".$order_id;
		$orderInfo=$GLOBALS['db']->getRow($sql);
		$money = round($orderInfo['money'],2);
		$payment_info['config'] = unserialize($payment_info['config']);
		$ordernu = $payInfo['trade_nu'];
		header("Location:http://www.frogbt.cn/alipay/pay.php?id=$payinfo_id");
	}
	
	public function response($request)
	{
        
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Alipay'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	
        /* 检查数字签名是否正确 */
        ksort($request);
        reset($request);
	
        foreach ($request AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key!='class_name' && $key!='act'&& $key!='ctl'&& $key!='city' )
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['config']['alipay_key'];

		if (md5($sign) != $request['sign'])
        {
            showErr($GLOBALS['payment_lang']["VALID_ERROR"]);
        }
		
        $payment_notice_sn = $request['out_trade_no'];
        
    	$money = $request['total_fee'];
		
    	$outer_notice_sn = $request['trade_no'];
		
		if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED' || $request['trade_status'] == 'WAIT_SELLER_SEND_GOODS'|| $request['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS'){
			
		//$order = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."order where ordernu = '".$payment_notice_sn."'");
			
		  $GLOBALS['db']->query("update ".DB_PREFIX."payment_log set `status`=1 where trade_nu = '".$payment_notice_sn."'");
		  $payment_log = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_log where ordernu = '".$payment_notice_sn."'");
		  $oid=intval($payment_log['order_id']);
		  if($payment_log['mtype']){
			  $GLOBALS['db']->query("update  ".DB_PREFIX."user_power set `status`=1 where id = $oid");
		  }else{
			  $GLOBALS['db']->query("update  ".DB_PREFIX."order set `status`=1 where id = $oid");
		  }
		  header("location:/suanli/success/".(intval($payment_log['order_id'])).".html");
			
		//消费日志
		/*$user_info = es_session::get("user_info");
		$idata['user_id'] = $user_info['id'];
		$idata['user_name'] = $user_info['user_name'];
		$idata['mtype'] = 1;
		$idata['stype'] = 0;
		$idata['getmoney'] = $payment_notice['paymoney'];
		$idata['info'] = '购物消费';
		$idata['order_id'] = $payment_notice['id'];
		$idata['create_time'] = time();
		$GLOBALS['db']->autoExecute(DB_PREFIX."user_log",$idata); //插入
		*/
			
			
	
		}else{
		    showErr("支付失败","","/user/order_list");
		}   
	}
	
	public function notify($request)
	{
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Alipay'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	
        /* 检查数字签名是否正确 */
        ksort($request);
        reset($request);
	
        foreach ($request AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key!='class_name' && $key!='act'&& $key!='ctl'&& $key!='city'  )
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['config']['alipay_key'];

		if (md5($sign) != $request['sign'])
        {
            echo '0';
        }
		
        $payment_notice_sn = $request['out_trade_no'];
    	$money = $request['total_fee'];
		$outer_notice_sn = $request['trade_no'];
		if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED' || $request['trade_status'] == 'WAIT_SELLER_SEND_GOODS' || $request['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS'){
			$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set `status`=1 where trade_nu = '".$payment_notice_sn."'");
			$payment_log = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_log where ordernu = '".$payment_notice_sn."'");
			$oid=intval($payment_log['order_id']);
			if($payment_log['mtype']){
				$GLOBALS['db']->query("update  ".DB_PREFIX."user_power set `status`=1 where id = $oid");
			}else{
				$GLOBALS['db']->query("update  ".DB_PREFIX."order set `status`=1 where id = $oid");
			}
			echo '1';
		}else{
		   echo '0';
		}   
	}
	
	public function get_display_code()
	{
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Alipay'");
		if($payment_item)
		{
			$html = "<div style='float:left;'>".
					"<input type='radio' name='payment' value='".$payment_item['id']."' />&nbsp;".
					$payment_item['name'].
					"：</div>";
			if($payment_item['logo']!='')
			{
				$html .= "<div style='float:left; padding-left:10px;'><img src='".APP_ROOT.$payment_item['logo']."' /></div>";
			}
			$html .= "<div style='float:left; padding-left:10px;'>".nl2br($payment_item['description'])."</div>";
			return $html;
		}
		else
		{
			return '';
		}
	}
	
}
?>
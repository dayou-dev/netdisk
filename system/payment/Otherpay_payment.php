<?php
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'线下充值接口',
	'pay_bank'	=>	'开户行',
	'pay_name'	=>	'收款方式名称',
	'pay_account'	=>	'收款帐号',
	'pay_account_name'	=>	'收款人',

);
$config = array(
	'pay_bank'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //开户行
	'pay_name'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //收款方式名称
	'pay_account'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //收款帐号
	'pay_account_name'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //收款人
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Otherpay';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '0';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    return $module;
}

// 其他自定义支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Otherpay_payment implements payment {

	public function get_payment_code($logid)
	{
		$logid=intval($logid);
		$payInfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_log where id = ".$logid);
		$order_id=intval($payInfo['order_id']);
		$sql="select * from ".DB_PREFIX."order where id = ".$order_id;
		if($payInfo['mtype']) $sql="select * from ".DB_PREFIX."user_power where id = ".$order_id;
		$orderInfo = $GLOBALS['db']->getRow($sql);
		if($orderInfo){
			$usd_rate = $GLOBALS['db']->getOne("select USD_RATE from ".DB_PREFIX."system");
			$money = round($orderInfo[$payInfo['mtype']?'paymoney':'money'],2);
			$money = round($money * $usd_rate,2);
			$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payInfo['payid']));
			$payment_info['config'] = unserialize($payment_info['config']);
			//$code = "收款帐号:".$payment_info['config']['pay_account'][$bank_id]."<br /><br />开户行:".$payment_info['config']['pay_bank'][$bank_id]."<br /><br />收款人:".$payment_info['config']['pay_account_name'][$bank_id];
			/*if($payment_notice['memo'])
				$code .= "<br /><br />银行流水号：".$payment_notice['memo'];*/
			//$code.="<br /><br /><div style='text-align:center' class='red'>".$GLOBALS['lang']['PAY_TOTAL_PRICE'].":".format_price($money)."</div>";
			$payInfo['pay_account_name']=$payment_info['config']['pay_account_name'][0];
			$payInfo['pay_account']=$payment_info['config']['pay_account'][0];
			$payInfo['pay_bank']=$payment_info['config']['pay_bank'][0];
			$GLOBALS['tmpl']->assign("money",$money);
			$GLOBALS['tmpl']->assign("orderInfo",$orderInfo);
			$GLOBALS['tmpl']->assign("payInfo",$payInfo);
			$GLOBALS['tmpl']->display("page/pay_success1.html");
		}else{
			showErr("支付失败");	
		}
	}
	
	public function response($request)
	{
        
		return false;
	}
	
	public function notify($request)
	{
		return false;
	}
	
	public function get_display_code()
	{
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Otherpay'");
		if($payment_item)
		{
			$payment_item['config'] = unserialize($payment_item['config']);
//			$html = "<div class='clearfix'>".nl2br($payment_item['description'])."</div><div class='blank'></div>";
//			$html .= "<div class='clearfix below_pay_list'>";
//			$html .= "<div class='f_l w80'>银行流水号：</div><div class='f_l'><input type='text' name='memo' class='f-input' value='' /></div>";
//			$html .="<div class='blank'></div><div class='f_l w80'>充值银行：</div><div class='f_l'>";
			$count = count($payment_item['config']['pay_name']);
			for($kk=0;$kk<$count;$kk++){
				$logopic="";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'建设银行')>0) $logopic="CCB";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'招商银行')>0) $logopic="CMB";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'工商银行')>0) $logopic="ICBC";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'中国银行')>0) $logopic="BOC";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'农业银行')>0) $logopic="ABC";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'交通银行')>0) $logopic="BOCOM";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'民生银行')>0) $logopic="CMBC";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'华夏银行')>0) $logopic="HXBC";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'兴业银行')>0) $logopic="CIB";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'上海浦东')>0) $logopic="SPDB";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'广东发展银行')>0) $logopic="GDB";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'中信银行')>0) $logopic="CITIC";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'光大银行')>0) $logopic="CEB";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'中国邮政')>0) $logopic="PSBC";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'深圳发展银行')>0) $logopic="SDB";
				$logopic=strtolower($logopic);
			$html .='<div style="width:100%; float:left"><div class="change-code"><img class="code-img" src="'.$payment_item['logo'].'"></div><div class="change-ms">帐号：'.$payment_item['config']['pay_account'][$kk].'<br>户名：'.$payment_item['config']['pay_account_name'][$kk].'</div></div>';
				
				
			}
			
			
			
//			$html .="</div></div>";
//			
//			if($payment_item['logo']!='')
//			{
//				$html .= "<div class='clearfix'><img src='".APP_ROOT.$payment_item['logo']."' /></div>";
//			}
			
//			$html .="<script type='text/javascript'>function set_bank(bank_id)";
//			$html .="{";
//			$html .="$(\"input[name='bank_id']\").val(bank_id);";
//			$html .="}< /script>";
//			$html .= "<input type='hidden' name='bank_id' />";
			return $html;
		}
		else
		{
			return '';
		}
	}
	
	public function get_wap_display_code()
	{
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Otherpay'");
		if($payment_item)
		{
			$payment_item['config'] = unserialize($payment_item['config']);
			$count = count($payment_item['config']['pay_name']);
			for($kk=0;$kk<$count;$kk++){
				$logopic="";
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'建设银行')>0) {$logopic="CCB";$bank_name="建设银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'招商银行')>0) {$logopic="CMB";$bank_name="招商银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'工商银行')>0) {$logopic="ICBC";$bank_name="工商银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'中国银行')>0) {$logopic="BOC";$bank_name="中国银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'农业银行')>0) {$logopic="ABC";$bank_name="农业银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'交通银行')>0) {$logopic="BOCOM";$bank_name="交通银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'民生银行')>0) {$logopic="CMBC";$bank_name="民生银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'华夏银行')>0) {$logopic="HXBC";$bank_name="华夏银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'兴业银行')>0) {$logopic="CIB";$bank_name="兴业银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'上海浦东')>0) {$logopic="SPDB";$bank_name="上海浦东";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'广东发展银行')>0) {$logopic="GDB";$bank_name="广东发展银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'中信银行')>0) {$logopic="CITIC";$bank_name="中信银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'光大银行')>0) {$logopic="CEB";$bank_name="光大银行";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'中国邮政')>0) {$logopic="PSBC";$bank_name="中国邮政";}
				if(strripos("@".$payment_item['config']['pay_bank'][$kk],'深圳发展银行')>0) {$logopic="SDB";$bank_name="深圳发展银行";}
				$redata[$kk]['bankcode']=$logopic;
				$redata[$kk]['pay_account']=$payment_item['config']['pay_account'][$kk];
				$redata[$kk]['pay_account_name']=$payment_item['config']['pay_account_name'][$kk];
				$redata[$kk]['pay_bank']=$payment_item['config']['pay_bank'][$kk];
				$redata[$kk]['pay_name']=$payment_item['config']['pay_name'][$kk];
				$redata[$kk]['bank_name']=$bank_name;
			}
			return $redata;
		}
		else
		{
			return '';
		}
	}
}
?>
<?php
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'网银在线',
	'chinabank_account'	=>	'商户编号',
	'chinabank_key'	=>	'商户密钥',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
	'GO_TO_PAY'	=>	'前往网银在线支付',
);
$config = array(
	'chinabank_account'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户编号
	'chinabank_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户密钥

);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Chinabank';

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

// 网银支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Chinabank_payment implements payment {

	public function get_payment_code($payment_notice_id)
	{
		$orderInfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_log where id = ".$payment_notice_id);
		if(!$orderInfo) showErr("支付失败");
		if($orderInfo['status']) showErr("该笔订单已处理，请勿反覆操作");
		$money = round($orderInfo['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($orderInfo['pay_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
		$ordernu = $orderInfo['trade_nu'];
		
		$data_vid           = trim($payment_info['config']['chinabank_account']);
        $data_orderid       = $ordernu;
        $data_vamount       = $money;
        $data_vmoneytype    = 'CNY';
        $data_vpaykey       = trim($payment_info['config']['chinabank_key']);
		$data_vreturnurl = get_domain().APP_ROOT.'/payment/response/?class_name=Chinabank';
		$data_notify_url = get_domain().APP_ROOT.'/payment/notify/?class_name=Chinabank';

        $MD5KEY =$data_vamount.$data_vmoneytype.$data_orderid.$data_vid.$data_vreturnurl.$data_vpaykey;
        $MD5KEY = strtoupper(md5($MD5KEY));

        $payLinks  = '<form style="text-align:center;" method=post action="https://pay3.chinabank.com.cn/PayGate" target="_blank">';
        $payLinks .= "<input type=HIDDEN name='v_mid' value='".$data_vid."'>";
        $payLinks .= "<input type=HIDDEN name='v_oid' value='".$data_orderid."'>";
        $payLinks .= "<input type=HIDDEN name='v_amount' value='".$data_vamount."'>";
        $payLinks .= "<input type=HIDDEN name='v_moneytype'  value='".$data_vmoneytype."'>";
        $payLinks .= "<input type=HIDDEN name='v_url'  value='".$data_vreturnurl."'>";
        $payLinks .= "<input type=HIDDEN name='v_md5info' value='".$MD5KEY."'>";
        $payLinks .= "<input type=HIDDEN name='remark1' value=''>";
        $payLinks .= "<input type=HIDDEN name='remark2' value='[url:=".$data_notify_url."]'>";
        
		if(!empty($payment_info['logo']))
			$payLinks .= "<input type='image' src='".APP_ROOT.$payment_info['logo']."' style='border:solid 1px #ccc;'><div class='blank'></div>";
			
        $payLinks .= "<input type='submit' class='paybutton' value='前往网银在线支付'>";
        $payLinks .= "</form>";
        $code = '<div style="text-align:center">'.$payLinks.'</div>';
		$code.="<br /><div style='text-align:center' class='red'>".$GLOBALS['lang']['PAY_TOTAL_PRICE'].":".format_price($money)."</div>";
		
        echo  $code;       
        
        
	}
	
	public function response($request)
	{
		$return_res = array(
			'info'=>'',
			'status'=>false,
		);
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Chinabank'");  
    	$payment['config'] = unserialize($payment['config']);
    	
    	
        
    	$v_oid          = trim($request['v_oid']);
    	$v_idx          = trim($request['v_idx']);
        $v_pmode        = trim($request['v_pmode']);
        $v_pstatus      = trim($request['v_pstatus']);
        $v_pstring      = trim($request['v_pstring']);
        $v_amount       = trim($request['v_amount']);
        $v_moneytype    = trim($request['v_moneytype']);
        $remark1        = trim($request['remark1' ]);
        $remark2        = trim($request['remark2' ]);
        $v_md5str       = trim($request['v_md5str' ]);

        /**
         * 重新计算md5的值
         */
        $key            = $payment['config']['chinabank_key'];

        $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
		
        //开始初始化参数
        $payment_notice_id = $v_oid;
    	$money = $v_amount;
    	$payment_id = $payment['id'];   
    	$outer_notice_sn = $v_idx;

        if ($v_md5str==$md5string&&$v_pstatus == '20')
		{			
			  $GLOBALS['db']->query("update ".DB_PREFIX."payment_log set `status`=1,outer_notice_sn='$outer_notice_sn' where trade_nu = '".$payment_notice_id."'");
			  $payment_log = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_log where ordernu = '".$payment_notice_id."'");
			  $oid=intval($payment_log['order_id']);
			  if($payment_log['mtype']){
			 	$GLOBALS['db']->query("update ".DB_PREFIX."user_power set `status`=1 where id = $oid");
			  }else{
			  	$GLOBALS['db']->query("update ".DB_PREFIX."order set `status`=1 where id = $oid");
			  }
			  
			  header("location:/suanli/success/".(intval($payment_log['order_id'])).".html");
		}else{
		    showErr("支付失败");
		}   
	}
	
	public function notify($request)
	{
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Chinabank'");  
    	$payment['config'] = unserialize($payment['config']);
    	
		$v_oid     =  trim($request['v_oid']);	
		$v_idx	   =  trim($request['v_idx']);		     
		$v_pstatus = trim($request['v_pstatus']); 		 	     
		$v_amount = trim($request['v_amount']);  		
		$v_moneytype = trim($request['v_moneytype']);     
		$v_md5str = trim($request['v_md5str']); 
		$outer_notice_sn = $v_idx;			 
        //开始初始化参数
        $payment_notice_id = $v_oid;
    	$money = $v_amount;
    	$payment_id = $payment['id'];  
    	
		/**
         * 重新计算md5的值
         */
        $key  = $payment['config']['chinabank_key'];

        $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));

        if ($v_md5str==$md5string&&$v_pstatus == '20')
		{			
			  $GLOBALS['db']->query("update ".DB_PREFIX."payment_log set `status`=1,outer_notice_sn='$outer_notice_sn' where trade_nu = '".$payment_notice_id."'");
			  $payment_log = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_log where ordernu = '".$payment_notice_id."'");
			  $oid=intval($payment_log['order_id']);
			  if($payment_log['mtype']){
			 	$GLOBALS['db']->query("update ".DB_PREFIX."user_power set `status`=1 where id = $oid");
			  }else{
			  	$GLOBALS['db']->query("update ".DB_PREFIX."order set `status`=1 where id = $oid");
			  }
			  echo 'ok';
		}else{
		    echo 'error';
		} 
	}
	
	public function get_display_code()
	{
		$payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Chinabank'");
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
<?php

$payment_lang = array(
	'name'	=>	'智惠支付',
	'Mer_code'	=>	'商户号',
	'Mer_key'	=>	'md5Key',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
	'GO_TO_PAY'	=>	'前往支付',
	'chinagpay_gateway'	=>	'支持的银行',
	'chinagpay_gateway_0'	=>	'智慧收银台',
	//借记卡
	'chinagpay_gateway_01020000'	=>	'工商银行',
	'chinagpay_gateway_01030000'	=>	'农业银行',
	'chinagpay_gateway_01040000'	=>	'中国银行',
	'chinagpay_gateway_01050000'	=>	'建设银行',
	'chinagpay_gateway_03020000'	=>	'中信银行',
	'chinagpay_gateway_03030000'	=>	'光大银行',
	'chinagpay_gateway_03050000'   =>	'民生银行',
	'chinagpay_gateway_03060000'	=>	'广发银行',
	'chinagpay_gateway_03080000'	=>	'招商银行',
	'chinagpay_gateway_04031000'	=>	'北京银行',
	
);
$config = array(
	'Mer_code'	=>	array(
		'INPUT_TYPE'	=>	'0',
	), //商户号
	'Mer_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //证书
	'chinagpay_gateway'	=>	array(
		'INPUT_TYPE'	=>	'3',
		'VALUES'	=>	array(
				'0',//'网银支付（总）',
				
				//借记卡
				'01020000',//'工商银行',
				'01030000',//'农业银行',
				'01040000',//'中国银行',
				'01050000',//'建设银行',
				'03020000',//'中信银行',
				'03030000',//'光大银行',
				'03050000',//民生银行	
				'03060000',//'广发银行',
				'03080000',//'招商银行',
				'04031000',//'北京银行',

			)
	), //可选的银行网关
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Chinagpay';
    
    /* 名称 */
    $module['name']    = $payment_lang['name'];

    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    
    $module['reg_url'] = 'http://www.chinagpay.com/';
    
    return $module;
}

// 环讯支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
require_once(APP_ROOT_PATH.'system/payment/chinagpay/HttpClient.class.php');
class Chinagpay_payment implements payment {
	private $payment_lang = array(
		'GO_TO_PAY'	=>	'前往%s支付',
	);
	
	function get_payment_code($payment_notice_id){
	
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
	
		$bank_id = $GLOBALS['db']->getOne("select bank_id from ".DB_PREFIX."deal_order where id = ".intval($payment_notice['order_id']));
		$money = round($payment_notice['money'],2)*100;
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);

        /* 获得订单的流水号，补零到10位 */
        $sp_billno = $payment_notice_id;   
        
        /* 交易日期 */
        $date = to_date($payment_notice['create_time'],'Ymd');
        
        $billno = $payment_notice['notice_sn'];
        $desc = $billno;
        $attach = $payment_info['config']['Mer_key'];
		
		
		
		
			//键（key） => 值（value）对。
			$array = array(
				"signMethod" => "MD5",//*签名类型
				"version" => "1.0.0", //*版本号
				"txnType" => "01", //*交易类型
				"txnSubType" => "01", //*交易子类型  
				"bizType" => "000000", //产品类型
				"accessType" => "0", //接入类型
				"accessMode" => "01",//接入方式        
				"merId" => $payment_info['config']['Mer_code'],//商户号-测试环境统一商户号        
				"merOrderId" => $billno,//商户订单号
				"txnTime" =>  date("Ymdhis",time()),//订单发送时间：yyyyMMddHHmmss
				"txnAmt" => $money,//交易金额(分)
				"currency" => "CNY",//交易币种
				"frontUrl" => "http://www.41win.com/pay/CallBackNotifyServlet.php",//后台通知地址
				"backUrl" => "http://www.41win.com/pay/CallBackNotifyServlet_ajax.php",//后台异步通知地址
				"payType" => "0201",//支付方式
				"bankId" => trim($bank_id),//银行编号
				"subject" =>"账户充值",//商品标题
				"body" => "",//商品描述
				"merResv1"=>"",//请求保留域
			);    
			// 给array里面的值按照首字母排序,如果首字母一样看第二个字母   以此类推...
			ksort($array);    
			
			
			// 加签key值
			$md5Key = $payment_info['config']['Mer_key'];
			$msg = $this->signMsg($array, $md5Key);
		   // echo "组装字符串:".$msg."\r\n";   
		   
			// echo  "MD5签名:".md5($msg,TRUE)."\r\n";
			// 获得签名值
			$signature = base64_encode(md5($msg,TRUE));
			$array["signature"] = $signature;  
				
			// 特殊字段转换类型 ， 对内容做Base64加密
			$reqBase64Keys = array("subject","body","remark","customerInfo","accResv","riskRateInfo","billQueryInfo","billDetailInfo");
			foreach ($reqBase64Keys as $bs64Key){
				if(isset($array[$bs64Key])){
					$array[$bs64Key] = base64_encode($array[$bs64Key]);
				}
			}
		
			return '<form action="http://www.41win.com/pay/index.php" name="payform" target="_self" style="margin:0px;padding:0px;text-align:center;" method="post" ><input type="hidden" name="bank_id" value="'.$bank_id.'" /><input type="hidden" name="amount" value="'.$money.'" /><input type="hidden" name="merOrderId" value="'.$billno.'" /><input type="submit" value="前往支付"></form>';
	}
	
	function response($request){
	   
	    /*取返回参数*/
	    $MerCode = "185547";//环迅商户号
		$Account = "1855470017";
		$Merchanturl = "http://www.41win.com/ipspay/pay_callback.php";//前台回调
		$ServerUrl = "http://www.41win.com/ipspay/pay_callback.php";//后台回调
		$tokenkey = "DqyjeUrZoWe68WXqwZa6dDsf6132r6lsQ1hT0FgmqTRZv1q96dRYMWKfEqE8Xv98YkkW5G3B5EYQSuEshXhPhEpEkmzVts9TQdZv3NMC9o0FU5JtzoFKAMThu0ACtvnv";//环迅MD5签名秘钥

		$xmldata = $_REQUEST['paymentResult'];
		//file_put_contents("log.txt",$xmldata);
		$xml = simplexml_load_string($xmldata);
		$sign = (string)$xml->GateWayRsp->head->Signature;
		$MerBillNo = (string)$xml->GateWayRsp->body->MerBillNo;
		$CurrencyType = (string)$xml->GateWayRsp->body->CurrencyType;
		$Amount = (string)$xml->GateWayRsp->body->Amount;
		$Date = (string)$xml->GateWayRsp->body->Date;
		$Status = (string)$xml->GateWayRsp->body->Status;
		$IpsBillNo = (string)$xml->GateWayRsp->body->IpsBillNo;
		$IpsTradeNo = (string)$xml->GateWayRsp->body->IpsTradeNo;
		$RetEncodeType = (string)$xml->GateWayRsp->body->RetEncodeType;
		$BankBillNo = (string)$xml->GateWayRsp->body->BankBillNo;
		$IpsBillTime = (string)$xml->GateWayRsp->body->IpsBillTime;
		$Signature = "<body><MerBillNo>$MerBillNo</MerBillNo><CurrencyType>$CurrencyType</CurrencyType><Amount>$Amount</Amount><Date>$Date</Date><Status>$Status</Status><Msg><![CDATA[支付成功！]]></Msg><IpsBillNo>$IpsBillNo</IpsBillNo><IpsTradeNo>$IpsTradeNo</IpsTradeNo><RetEncodeType>$RetEncodeType</RetEncodeType><BankBillNo>$BankBillNo</BankBillNo><ResultType>0</ResultType><IpsBillTime>$IpsBillTime</IpsBillTime></body>".$MerCode.$tokenkey;
		$Signature = md5($Signature);
		//MD5签名格式
		if ($Signature == $sign) {
			
			$payment_notice_sn=addslashes($MerBillNo);
			
	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$payment_notice_sn."'");
			$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$IpsBillNo."' where notice_sn = '".$payment_notice_sn."'");
			
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice['id']);						
			if($rs)
			{
				$rs = order_paid($payment_notice['order_id']);				
				if($rs)
				{
					//开始更新相应的outer_notice_sn					
					//$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$BillNo."' where id = ".$payment_notice['id']);
					if($order_info['type']==0)
						app_redirect(url("index","payment#done",array("id"=>$payment_notice['order_id']))); //支付成功
					else
						app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['order_id']))); //支付成功
				}
				else 
				{
					if($order_info['pay_status'] == 2)
					{				
						if($order_info['type']==0)
							app_redirect(url("index","payment#done",array("id"=>$payment_notice['order_id']))); //支付成功
						else
							app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['order_id']))); //支付成功
					}
					else
						app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id'])));
				}
			}
			else
			{
				app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id'])));
			}
			
			
			/*
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".addslashes($MerBillNo)."'");
			app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id'])));
			*/
		} else {
			showErr($GLOBALS['payment_lang']["PAY_FAILED"]);;//MD5校验失败
			//处理想处理的事情
		}	   
	   
	   
	   
	   exit();
	   
	   
	   
	   
	   
	   
	   
	   
	    /*取返回参数*/
        $billno = $request['billno'];
		$amount = $request['amount'];
		$mydate = $request['date'];
		$succ = $request['succ'];
		$msg = $request['msg'];
		$attach = $request['attach'];
		$ipsbillno = $request['ipsbillno'];
		$retEncodeType = $request['retencodetype'];
		$currency_type = $request['Currency_type'];
		$signature = $request['signature'];
		$content = 'billno'.$billno.'currencytype'.$currency_type.'amount'.$amount.'date'.$mydate.'succ'.$succ.'ipsbillno'.$ipsbillno.'retencodetype'.$retEncodeType;
		
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where class_name='Ips'");  
		$payment_info['config'] = unserialize($payment_info['config']);
		$payment_info['config']['Mer_key'];
		
		//请在该字段中放置商户登陆merchant.ips.com.cn下载的证书
		$cert = $payment_info['config']['Mer_key'];
		$signature_1ocal = md5($content . $cert);
		
    	
		if ($signature_1ocal == $signature && $succ =="Y")
		{
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$billno."'");
			
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice['id'],$ipsbillno);	
		
			$is_paid = intval($GLOBALS['db']->getOne("select is_paid from ".DB_PREFIX."payment_notice where id = '".intval($payment_notice['id'])."'"));
			if ($is_paid == 1){
				app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['id']))); //支付成功
			}else{
				app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id'])));
			}
		}
		else
		{
			showErr($GLOBALS['payment_lang']["PAY_FAILED"]);
		}
	}
	function notify($request){
		
        /*取返回参数*/
        $billno = $request['billno'];
		$amount = $request['amount'];
		$mydate = $request['date'];
		$succ = $request['succ'];
		$msg = $request['msg'];
		$attach = $request['attach'];
		$ipsbillno = $request['ipsbillno'];
		$retEncodeType = $request['retencodetype'];
		$currency_type = $request['Currency_type'];
		$signature = $request['signature'];
		$content = 'billno'.$billno.'currencytype'.$currency_type.'amount'.$amount.'date'.$mydate.'succ'.$succ.'ipsbillno'.$ipsbillno.'retencodetype'.$retEncodeType;
		
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where class_name='Ips'");  
		$payment_info['config'] = unserialize($payment_info['config']);
		$payment_info['config']['Mer_key'];
		
		//请在该字段中放置商户登陆merchant.ips.com.cn下载的证书
		$cert = $payment_info['config']['Mer_key'];
		$signature_1ocal = md5($content . $cert);
		
    	
		if ($signature_1ocal == $signature && $succ =="Y")
		{
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$billno."'");
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice['id'],$ipsbillno);						
			if($rs)
			{
				$rs = order_paid($payment_notice['order_id']);				
				if($rs)
				{
					//开始更新相应的outer_notice_sn					
					//$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$ipsbillno."' where id = ".$payment_notice['id']);
					echo "Success";
					die();
				}
				else 
				{
					echo "Success";
					die();
				}
			}
			else
			{
				echo "Success";
				die();
			}
		}
		else
		{
			echo "Failed";
			die();
		}
	}
	
	
    public function get_display_code() {
        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Chinagpay'");
		if($payment_item)
		{
			$payment_cfg = unserialize($payment_item['config']);
			$html = "<style type='text/css'>.chinagpay_types{float:left; display:block;text-align:left; margin: 10px 0px;margin-bottom: 0px;}";
	        $html .=".bk_type_0{background:url(".get_domain().APP_ROOT."/system/payment/chinagpay/chinagpay.jpg) no-repeat right center; font-size:0px; width:150px; height:10px;}.chinagpay_types input{margin-top: 15px;}"; 
	        $html .="</style>";
	        $html .="<script type='text/javascript'>function set_bank(bank_id)";
			$html .="{";
			$html .="$(\"input[name='bank_id']\").val(bank_id);";
			$html .="}</script>";
			$is_show_jieji = false;
			$is_show_xyk = false;
	       foreach ($payment_cfg['chinagpay_gateway'] AS $key=>$val)
	        {

	            $html  .= "<div class='chinagpay_types bk_type_".$key."'><input type='radio' name='payment' value='".$payment_item['id']."' rel='".$key."' onclick=\"set_bank('".$key."')\" /> <dt title='".$this->payment_lang['chinagpay_gateway_'.$key]."'></dt> </div>";
	        }
	        $html .= "<input type='hidden' name='bank_id' />";
			return $html;
		}
		else{
			return '';
		}
    }
	
    /**
     * 设置加签数据
     * 
     * @param unknown $array
     * @param unknown $md5Key
     * @return string
     */
    public function signMsg($array,$md5Key){
        $msg = "";
        $i = 0;
        // 转换为字符串 key=value&key.... 加签
        foreach ($array as $key => $val) {
            // 不参与签名
            if($key != "signMethod" && $key != "signature"){
                if($i == 0 ){
                    $msg = $msg."$key=$val";
                }else {
                    $msg = $msg."&$key=$val";
                }
                $i++;
            }
        }
        $msg = $msg.$md5Key;
        return  $msg;
    }
    
    /* 将一个字符串转变成键值对数组
     * @param    : string str 要处理的字符串 $str = txnType=01&tn=2016080400144258235502&respCode=0000&channelId=yeepaykj&merId=200000000000001&settleDate=&txnSubType=01&version=1.0.0&txnAmt=0000000000001000&currency=CNY&settleCurrency=&signMethod=MD5&settleAmount=&bizType=000000&respMsg=5o6l5Y+X6YCa55+l5oiQ5Yqf&resv=&merResv1=&merOrderId=20160722105818001&signature=ufcGvUYJQnU4skVTlz56uQ==&txnTime=20160722105818&accessType=0&succTime=;       
     * @return    : array*/
    public function strToArr ($str)
    {
        $arr = explode("&",$str);
        $r = array();
        foreach ($arr as $val )
        {
            $t = explode("=",$val);
            
            $r[$t[0]]= substr($val,strlen($t[0])+1);
           // $r[$t[0]]= $t[1];
        }
        return $r;
    }
	
	
}
?>

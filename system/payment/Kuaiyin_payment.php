<?php

// +----------------------------------------------------------------------
// | Fanwe 多语商城建站系统 (Build on ThinkPHP)
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: jobin.lin(jobin.lin@gmail.com)
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | 广东快银 直连银行支付
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'广东快银支付',
	'merchant_id'	=>	'商户编号',
	'VerficationCode'	=>	'密钥Key',
	'GO_TO_PAY'	=>	'前往快银在线支付',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
	'kuaiyin_gateway'	=>	'支持的银行',
	'kuaiyin_gateway_0'	=>	'纯网关支付',
	'kuaiyin_gateway_CCB'	=>	'中国建设银行',
	'kuaiyin_gateway_CMB'	=>	'招商银行',
	'kuaiyin_gateway_ICBC'	=>	'中国工商银行',
	'kuaiyin_gateway_BOC'	=>	'中国银行',
	'kuaiyin_gateway_ABC'	=>	'中国农业银行',
	'kuaiyin_gateway_BOCOM'	=>	'中国交通银行',
	'kuaiyin_gateway_CMBC'	=>	'中国民生银行',
	'kuaiyin_gateway_HXBC'	=>	'华夏银行',
	'kuaiyin_gateway_CIB'	=>	'兴业银行',
	'kuaiyin_gateway_SPDB'	=>	'上海浦东发展银行',
	'kuaiyin_gateway_GDB'	=>	'广东发展银行',
	'kuaiyin_gateway_CITIC'	=>	'中信银行',
	'kuaiyin_gateway_CEB'	=>	'光大银行',
	'kuaiyin_gateway_PSBC'	=>	'中国邮政储蓄银行',
	'kuaiyin_gateway_SDB'	=>	'深圳发展银行',
);

$config = array(
    'merchant_id' => '', //商户ID
    'VerficationCode'=>'',  //商户识别码
    'kuaiyin_gateway' => array(
    	'INPUT_TYPE'	=>	'3',
    	'VALUES'	=>	array(
	        'CCB', //中国建设银行
	        'CMB', //招商银行
	        'ICBC', //中国工商银行
	        'BOC', //中国银行
	        'ABC', //中国农业银行
	        'BOCOM', //交通银行
	        'CMBC', //中国民生银行
	        'HXBC', //华夏银行
	        'CIB', //兴业银行
	        'SPDB', //上海浦东发展银行
	        'GDB', //广东发展银行
	        'CITIC', //中信银行
	        'CEB', //光大银行
	        'PSBC', //中国邮政储蓄银行
	        'SDB', //深圳发展银行
        ),
    ),
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true){
    
    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $module['class_name']    = 'Kuaiyin';

    /* 被整合的第三方程序的名称 */
    $module['name'] = $payment_lang['name'];
    
    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';
	
	 /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
	
    $module['reg_url'] = 'https://www.kuaiyinpay.com';
    
    return $module;
}

// 国付宝模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Kuaiyin_payment implements payment {

    public function get_payment_code($payment_notice_id) {
        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order = $GLOBALS['db']->getRow("select order_sn,bank_id from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		$order_sn = $order['order_sn'];
		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
        
        //新
        $data_front_url =  get_domain().APP_ROOT.'/kuaiyin_callback.php?act=notify';
        $data_return_url = get_domain().APP_ROOT.'/kuaiyin_callback.php?act=response';


        $tranCode = '8888';

        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];

        /* 交易日期 */
        $today = to_date($payment_notice['create_time'], 'YmdHis');

        
        $bank_id = $order['bank_id'];
       
        $desc = $order_sn;
       
       	include_once(APP_ROOT_PATH."system/libs/iconv.php");
		$chinese = new Chinese();
		$desc = $chinese->Convert("UTF-8","GBK",$desc);
      
        /* 货币类型 */
        $currencyType = '156';


        /* 数字签名 */
        $version = '2.1';   
        $tranCode = $tranCode; 
        $merchant_id = $payment_info['config']['merchant_id'];
		$key = $payment_info['config']['VerficationCode'];
        $merOrderNum = $order_sn;    
        $tranAmt = $money;     // 总金额 
        $feeAmt = '';  
        $tranDateTime = $today;      
        $frontMerUrl = $data_front_url;      
        $backgroundMerUrl = $data_return_url;   //返回的路径   
        $tranIP = $spbill_create_ip != ""?$spbill_create_ip:'';  
        //商户识别码
        //$verficationCode = $payment_info['config']['VerficationCode'];
       // $gopayServerTime = trim(file_get_contents("https://www.gopay.com.cn/PGServer/time"));

		$sign_msg  = md5(urlencode($payment_info['config']['VerficationCode'])); 
        /*交易参数*/
        $parameter = array(
            'version'=>'1.0.0',//版本号
            'bank_code'=>$bank_id,//银行代码
			'amount'=> sprintf("%.2f", $tranAmt),//交易金额
			'order_time'=>date('YmdHis'),
			'order_id'=>$merOrderNum,//订单号
			'merchant_url'=>$frontMerUrl,//前台通知地址
			'cust_param'=>'userIdorOther',//字符集1GBK 2UTF-8
            
        );
        
        $def_url = '<form style="text-align:center;" action="/kuaiyin/pay.php" target="_blank" style="margin:0px;padding:0px" method="post" >';

        foreach ($parameter AS $key1 => $val) {
            $def_url .= "<input type='hidden' name='$key1' value='$val' />";
        }
        $def_url .= "<input type='submit' class='paybutton' value='前往快银在线支付' style='padding: 10px;' />";
        $def_url .= "</form>";
        $def_url.="<br /><div style='text-align:center' class='red'>".$GLOBALS['lang']['PAY_TOTAL_PRICE'].":".format_price($money)."</div>";
        return $def_url;
    }

    public function response($request) {
	//$merRemark1 = $request["merRemark1"];
	$payment_notice_sn = $ky_back['order_id'];  //系统订单号
	$merchant_id = $payment_info['config']['merchant_id'];
	$key = $payment_info['config']['VerficationCode'];
	#订单金额
#	$ky_back['order_amount']     = $_REQUEST['order_amount'];
	#商户订单号
	$ky_back['order_id']         = $_REQUEST['order_id'];
	#快银订单号
	$ky_back['kuaiyin_order_id'] = $_REQUEST['kuaiyin_order_id'];
	#订单提交时间
	$ky_back['order_time']       = $_REQUEST['order_time'];
	#实际支付金额
	$ky_back['paid_amount']      = $_REQUEST['paid_amount'];
	#交易流水号
	$ky_back['deal_id']          = $_REQUEST['deal_id'];
	#订单的结账日期
	$ky_back['account_date']     = $_REQUEST['account_date'];
	#快银交易处理时间
	$ky_back['deal_time']        = $_REQUEST['deal_time'];
	#支付结果
	$ky_back['result']           = $_REQUEST['result'];
	#返回错误代码
	$ky_back['code']             = $_REQUEST['code'];
	#银行订单号
	$ky_back['bank_order_id']    = $_REQUEST['bank_order_id'];
	#自定义字段
	$ky_back['cust_param']       = $_REQUEST['cust_param'];
	#商户编号
	$ky_back['merchant_id']      = $merchant_id;
	#版本号
	$ky_back['version']          = '1.0.0';
	#返回验证签名
	$signMsg         = $_REQUEST['signMsg'];

#md5加密
#快银网络密钥
	$sign_kypay = array_diff($ky_back, array(''));
	ksort($sign_kypay);
	$str_sign = '';
	foreach($sign_kypay as $k=>$v){
		$str_sign .=$k.'='.$v.'&';
	}
	
	$back_signMsg  = strtoupper(md5(urlencode($str_sign.'key='.$key))); 
		
		

#	签名正确.
if($back_signMsg == $signMsg){	
	if($ky_back['result']=="Y"){//只有result=Y时才进行数据的处理
	#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
	#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.
	#   判断您平台的数据是否已经处理过了，已处理则无需再处理，以免造成重复处理。
		#成功处理完您的平台数据后，向快银支付输出如下内容，把www.kuaiyinpay.com改成您平台的支付结果url
		//echo '0000|http://www.kuaiyinpay.com';
		$respCode="0000";
	}
	
}else{
	#失败处理完后，向快银支付输出如下内容，把www.kuaiyinpay.com改成您平台的支付结果url
	//echo "9999|http://www.kuaiyinpay.com";
	$respCode="9999";
}


        if ($signValue != $signValue2 || $respCode != "0000") {
        	echo "RespCode=9999|JumpURL=".get_domain().url("index","payment#pay",array("id"=>$payment_notice_sn)); 
        } else {
	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = '".$payment_notice_sn."'");
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice['id']);						
			if($rs)
			{
				$rs = order_paid($payment_notice['order_id']);				
				if($rs)
				{
					//开始更新相应的outer_notice_sn					
					$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$gopayOutOrderId."' where id = ".$payment_notice['id']);
					if($order_info['type']==0)
						echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#done",array("id"=>$payment_notice['order_id'])); //支付成功
					else
						echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#incharge_done",array("id"=>$payment_notice['order_id'])); //支付成功
				}
				else 
				{
					if($order_info['pay_status'] == 2)
					{				
						if($order_info['type']==0)
							echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#done",array("id"=>$payment_notice['order_id'])); //支付成功
						else
							echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#incharge_done",array("id"=>$payment_notice['order_id'])); //支付成功
					}
					else
						echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#pay",array("id"=>$payment_notice['id'])); 
				}
			}
			else
			{
				echo "RespCode=9999|JumpURL=".get_domain().url("index","payment#pay",array("id"=>$payment_notice['id'])); 
			}
        }
    }
    
     public function notify($request) {
	$payment_notice_sn = $ky_back['order_id'];  //系统订单号
	$merchant_id = $payment_info['config']['merchant_id'];
	$key = $payment_info['config']['VerficationCode'];
#订单金额
#	$ky_back['order_amount']     = $_REQUEST['order_amount'];
#商户订单号
	$ky_back['order_id']         = $_REQUEST['order_id'];
#快银订单号
	$ky_back['kuaiyin_order_id'] = $_REQUEST['kuaiyin_order_id'];
#订单提交时间
	$ky_back['order_time']       = $_REQUEST['order_time'];
#实际支付金额
	$ky_back['paid_amount']      = $_REQUEST['paid_amount'];
#交易流水号
	$ky_back['deal_id']          = $_REQUEST['deal_id'];
#订单的结账日期
	$ky_back['account_date']     = $_REQUEST['account_date'];
#快银交易处理时间
	$ky_back['deal_time']        = $_REQUEST['deal_time'];
#支付结果
	$ky_back['result']           = $_REQUEST['result'];
#返回错误代码
	$ky_back['code']             = $_REQUEST['code'];
#银行订单号
	$ky_back['bank_order_id']    = $_REQUEST['bank_order_id'];
#自定义字段
	$ky_back['cust_param']       = $_REQUEST['cust_param'];
#商户编号
	$ky_back['merchant_id']      = $merchant_id;
#版本号
	$ky_back['version']          = '1.0.0';
#返回验证签名
	$signMsg         = $_REQUEST['signMsg'];

#md5加密
#快银网络密钥
	$sign_kypay = array_diff($ky_back, array(''));
	ksort($sign_kypay);
	$str_sign = '';
	foreach($sign_kypay as $k=>$v){
		$str_sign .=$k.'='.$v.'&';
	}
	
	$back_signMsg  = strtoupper(md5(urlencode($str_sign.'key='.$key))); 
		
		

	#	签名正确.
	if($back_signMsg == $signMsg){	
		if($ky_back['result']=="Y"){//只有result=Y时才进行数据的处理
		#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
		#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.
		#   判断您平台的数据是否已经处理过了，已处理则无需再处理，以免造成重复处理。
			#成功处理完您的平台数据后，向快银支付输出如下内容，把www.kuaiyinpay.com改成您平台的支付结果url
			//echo '0000|http://www.kuaiyinpay.com';
			$respCode="0000";
		}
		
	}else{
		#失败处理完后，向快银支付输出如下内容，把www.kuaiyinpay.com改成您平台的支付结果url
		//echo "9999|http://www.kuaiyinpay.com";
		$respCode="9999";
	}





        if ($signValue != $signValue2 || $respCode != "0000") {
        	echo "RespCode=9999|JumpURL=".get_domain().url("index","payment#pay",array("id"=>$payment_notice_sn)); 
        } else {
	        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = '".$payment_notice_sn."'");
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice['id']);						
			if($rs)
			{
				$rs = order_paid($payment_notice['order_id']);				
				if($rs)
				{
					//开始更新相应的outer_notice_sn					
					$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$gopayOutOrderId."' where id = ".$payment_notice['id']);
					if($order_info['type']==0)
						echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#done",array("id"=>$payment_notice['order_id'])); //支付成功
					else
						echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#incharge_done",array("id"=>$payment_notice['order_id'])); //支付成功
				}
				else 
				{
					if($order_info['pay_status'] == 2)
					{				
						if($order_info['type']==0)
							echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#done",array("id"=>$payment_notice['order_id'])); //支付成功
						else
							echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#incharge_done",array("id"=>$payment_notice['order_id'])); //支付成功
					}
					else
						echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#pay",array("id"=>$payment_notice['id'])); 
				}
			}
			else
			{
				echo "RespCode=9999|JumpURL=".get_domain().url("index","payment#pay",array("id"=>$payment_notice['id'])); 
			}
        }
		
    }

    public function get_display_code() {
        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Kuaiyin'");
		if($payment_item)
		{
			$payment_cfg = unserialize($payment_item['config']);

//	        $html = "<style type='text/css'>.guofubao_types{float:left; display:block; background:url(".get_domain().APP_ROOT."/system/payment/Guofubao/banklist_hnapay.jpg); font-size:0px; width:150px; height:10px; text-align:left; padding:15px 0px;}";
//	        $html .=".bk_type_CCB{background-position:15px -72px; }"; //中国建设银行
//	        $html .=".bk_type_CMB{background-position:15px -196px; }"; //招商银行
//	        $html .=".bk_type_ICBC{background-position:15px 6px; }"; //中国工商银行
//	        $html .=".bk_type_BOC{background-position:15px -113px; }"; //中国银行
//	        $html .=".bk_type_ABC{background-position:15px -34px; }"; //中国农业银行
//	        $html .=".bk_type_BOCOM{background-position:15px -114px; }"; //交通银行
//	        $html .=".bk_type_CMBC{background-position:15px -230px; }"; //中国民生银行
//	        $html .=".bk_type_HXBC{background-position:15px -358px; }"; //华夏银行
//	        $html .=".bk_type_CIB{background-position:15px -270px; }"; //兴业银行
//	        $html .=".bk_type_SPDB{background-position:15px -312px; }"; //上海浦东发展银行
//	        $html .=".bk_type_GDB{background-position:15px -475px; }"; //广东发展银行
//	        $html .=".bk_type_CITIC{background-position:15px -396px; }"; //中信银行
//	        $html .=".bk_type_CEB{background-position:15px -435px; }"; //光大银行
//	        $html .=".bk_type_PSBC{background-position:15px -513px; }"; //中国邮政储蓄银行
//	        $html .=".bk_type_SDB{background-position:15px -558px; }"; //深圳发展银行
//	        $html .="</style>";
	        $html .="<script type='text/javascript'>function set_bank(bank_id)";
			$html .="{";
			$html .="$(\"input[name='bank_id']\").val(bank_id);";
			$html .="}</script>";
			$html .="<h3 class='tl'><b>广东快银支付</b></h3><div class='blank1'></div><hr /><div class='blank1'></div>";
			
			
			$tsel="curr";
			$kk=0;
	       foreach ($payment_cfg['kuaiyin_gateway'] AS $key=>$val)
	        {
	           
			   
			$html .= '<dd class="'.strtolower($key).'"><input id="'.strtolower($key).'" title="'.strtolower($key).'" type="radio" name="payment" onclick="set_bank(\''.$key.'\')" rel="'.$key.'"  value="'.$payment_item['id'].'"><label for="'.strtolower($key).'" class="'.$tsel.'"></label></dd>';
			   
			    //$html  .= "<label class='guofubao_types bk_type_".$key."'><input type='radio' name='payment' value='".$payment_item['id']."' rel='".$key."' onclick='set_bank(\"".$key."\")' /></label>";
				if($kk>0) $tsel="";
				$kk++;
	        }
	        $html .= "<input type='hidden' name='bank_id' />";
			return $html;
		}
		else{
			return '';
		}
    }
    /**
     * 字符转义
     * @return string
     */
    function fStripslashes($string)
    {
            if(is_array($string))
            {
                    foreach($string as $key => $val)
                    {
                            unset($string[$key]);
                            $string[stripslashes($key)] = fStripslashes($val);
                    }
            }
            else
            {
                    $string = stripslashes($string);
            }

            return $string;
    }

}

?>

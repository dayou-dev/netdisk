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
// | 易汇金 直连银行支付
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'易汇金支付',
	'merchant_id'	=>	'商户编号',
	'merchant_key'	=>	'商户密钥',
	'GO_TO_PAY'	=>	'前往易汇金在线支付',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',
	'ehk_gateway'	=>	'支持的银行',
	'ehk_gateway_0'	=>	'纯网关支付',
	'ehk_gateway_ICBC-NET-B2C'	=>	'工商银行',
	'ehk_gateway_CMBCHINA-NET-B2C'	=>	'招商银行',
	'ehk_gateway_ABC-NET-B2C'	=>	'中国农业银行',
	'ehk_gateway_CCB-NET-B2C'	=>	'建设银行',
	'ehk_gateway_BCCB-NET-B2C'	=>	'北京银行',
	'ehk_gateway_BOCO-NET-B2C'	=>	'中国交通银行',
	'ehk_gateway_CIB-NET-B2C'	=>	'兴业银行',
	'ehk_gateway_CMBC-NET-B2C'	=>	'中国民生银行',
	'ehk_gateway_CEB-NET-B2C'	=>	'光大银行',
	'ehk_gateway_BOC-NET-B2C'	=>	'中国银行',
	'ehk_gateway_ECITIC-NET-B2C'	=>	'中信银行',
	'ehk_gateway_SDB-NET-B2C'	=>	'深圳发展银行',
	'ehk_gateway_GDB-NET-B2C'	=>	'广发银行',
	'ehk_gateway_SPDB-NET-B2C'	=>	'上海浦东发展银行',
	'ehk_gateway_POST-NET-B2C'	=>	'中国邮政',
	'ehk_gateway_BJRCB-NET-B2C'	=>	'北京农村商业银行',
	'ehk_gateway_HXB-NET-B2C'	=>	'华夏银行',
	'ehk_gateway_PINGANBANK-NET-B2C'	=>	'平安银行',
);

$config = array(
    'merchant_id' => '', //商户ID
    'merchant_key' => '', //商户密钥
    'ehk_gateway' => array(
    	'INPUT_TYPE'	=>	'3',
    	'VALUES'	=>	array(
	        'ICBC-NET-B2C', //工商银行
	        'CMBCHINA-NET-B2C', //招商银行
	        'ABC-NET-B2C', //中国农业银行
	        'CCB-NET-B2C', //建设银行
	        'BCCB-NET-B2C', //北京银行
	        'BOCO-NET-B2C', //交通银行
	        'CIB-NET-B2C', //兴业银行
	        'CMBC-NET-B2C', //中国民生银行
	        'CEB-NET-B2C', //光大银行
	        'BOC-NET-B2C', //中国银行
	        'ECITIC-NET-B2C', //中信银行
	        'SDB-NET-B2C', //深圳发展银行
	        'GDB-NET-B2C', //广发银行
	        'SPDB-NET-B2C', //上海浦东发展银行
			'POST-NET-B2C', //中国邮政
			'BJRCB-NET-B2C', //北京农村商业银行
			'HXB-NET-B2C', //华夏银行
	        'PINGANBANK-NET-B2C', //平安银行
        ),
    ),
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true){
    
    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $module['class_name']    = 'EHK';

    /* 被整合的第三方程序的名称 */
    $module['name'] = $payment_lang['name'];
    
    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';
	
	 /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
	
    $module['reg_url'] = 'https://www.ehking.com';
    
    return $module;
}

// 易汇金模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class EHK_payment implements payment {

    public function get_payment_code($payment_notice_id) {
        
		$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order = $GLOBALS['db']->getRow("select order_sn,bank_id from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		//echo $payment_notice['notice_sn'];
		$order_sn = $order['order_sn'];
		$money = round($payment_notice['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
        
        //新
        $data_front_url =  get_domain().APP_ROOT.'/ehk_callback.php?act=notify';
        $data_return_url = get_domain().APP_ROOT.'/ehk_callback.php?act=response';



        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];

        /* 交易日期 */
        $today = to_date($payment_notice['create_time'], 'YmdHis');

        $bank_id = $order['bank_id'];
       
        $desc = $order_sn;
       
       	include_once(APP_ROOT_PATH."system/libs/iconv.php");
		$chinese = new Chinese();
		$desc = $chinese->Convert("UTF-8","GBK",$desc);
		
		$tranAmt = $money;     // 总金额 
		$merOrderNum = $order_sn;   
		
		
		#	商家设置用户购买商品的支付信息.
		#易汇金支付平台统一使用GBK编码方式,参数如用到中文，请注意转码
		
		#	商户订单号,选填.
		##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易汇金支付会自动生成随机的商户订单号.
		$p2_Order					= $merOrderNum;
		
		#	交易币种,固定值"CNY".
		$p3_Cur						= "CNY";
		
		#	支付金额,必填.
		##单位:元，精确到分.
		$p4_Amt						= $tranAmt;
		
		#	商品名称
		$p5_Pid						= $desc;
		
		#	商品种类
		$p6_Pcat					= "";
		
		#	商品描述
		$p7_Pdesc					= "";
		
		#	商户接收支付成功数据的地址,支付成功后易汇金支付会向该地址发送两次成功通知.
		$p8_Url						= $data_return_url;	
		
		#	商户扩展信息
		##商户可以任意填写1K 的字符串,支付成功时将原样返回.												
		$p9_MP					= "";
		
		#	支付通道编码
		##默认为""			
		$pa_FrpId					= $bank_id;
		
		#调用签名函数生成签名串
		$hmac = $this->getReqHmacString($p2_Order,$p3_Cur,$p4_Amt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$p9_MP,$pa_FrpId,$payment_info['config']['merchant_id'],$payment_info['config']['merchant_key']);
		
		
        /*交易参数*/
        $parameter = array(
            'p0_Cmd'=>'Buy',
            'p1_MerId'=>$payment_info['config']['merchant_id'],
            'p2_Order'=>$merOrderNum,//订单号
            'p3_Cur'=>$p3_Cur,//币种
            'p4_Amt'=>$p4_Amt, //交易金额
			'p5_Pid'=>$p5_Pid, //商品名称
			'p6_Pcat'=>$p6_Pcat, //商品类型
			'p7_Pdesc'=>$p7_Pdesc, //商品描述
			'p8_Url'=>$p8_Url, //接收支付成功数据的地址
			'p9_MP'=>$p9_MP, //商户扩展信息
			'pa_FrpId'=>$pa_FrpId, //支付通道编码
			'hmac'=>$hmac, //交易金额
			
        );
        
        $def_url = '<form style="text-align:center;" action="https://ehkpay.ehking.com/gateway/controller.action" target="_self" style="margin:0px;padding:0px" method="post" >';

        foreach ($parameter AS $key => $val) {
            $def_url .= "<input type='hidden' name='$key' value='$val' />";
        }
        $def_url .= "<input type='submit' class='paybutton' value='前往易汇金在线支付' />";
        $def_url .= "</form>";
        $def_url.="<br /><div style='text-align:center' class='red'>".$GLOBALS['lang']['PAY_TOTAL_PRICE'].":".format_price($money)."</div>";
        return $def_url;
    }

    public function response($request) {
		
		
		
		
		#	只有支付成功时易汇金支付才会通知商户.
		##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.
		
		#	解析返回参数.
		$return = $this->getCallBackValue($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r8_MP,$r9_BType,$ro_BankOrderId,$rp_PayDate,$hmac);
		
		#	判断返回签名是否正确（True/False）
		$bRet = $this->CheckHmac($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r8_MP,$r9_BType,$ro_BankOrderId,$rp_PayDate,$hmac);
		#	以上代码和变量不需要修改.
		
		#	校验码正确.
		if($bRet){
			$payment_notice['order_id']=$r6_Order;
			
			if($r1_Code=="1"){
				
			#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
			#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，防止对同一条交易重复发货的情况发生.      	  	
				
				
			//$payment_notice['order_id']=$r6_Order;
				
	        
			
			
			
			$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where order_sn = '".$r6_Order."'");
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where order_id = ".intval($order_info['id']));
			
			require_once APP_ROOT_PATH."system/libs/cart.php";
			$rs = payment_paid($payment_notice['id']);						
			if($rs)
			{
				$rs = order_paid($payment_notice['order_id']);				
				if($rs)
				{
					//开始更新相应的outer_notice_sn					
					$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$r6_Order."' where id = ".$payment_notice['id']);
					if($order_info['type']==0)
						//echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#done",array("id"=>$payment_notice['order_id'])); //支付成功
						app_redirect(url("index","payment#done",array("id"=>$payment_notice['order_id']))); //支付成功
					else
						//echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#incharge_done",array("id"=>$payment_notice['order_id'])); //支付成功
						app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['order_id']))); //支付成功
				}
				else 
				{
					if($order_info['pay_status'] == 2)
					{				
						if($order_info['type']==0)
							//echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#done",array("id"=>$payment_notice['order_id'])); //支付成功
							app_redirect(url("index","payment#done",array("id"=>$payment_notice['order_id']))); //支付成功
						else
							//echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#incharge_done",array("id"=>$payment_notice['order_id'])); //支付成功
							app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['order_id']))); //支付成功
					}
					else
						//echo "RespCode=0000|JumpURL=".get_domain().url("index","payment#pay",array("id"=>$payment_notice['id'])); 
						app_redirect(url("index","payment#pay",array("id"=>$payment_notice['order_id']))); //支付成功
				}
			}
			else
			{
				//echo "RespCode=9999|JumpURL=".get_domain().url("index","payment#pay",array("id"=>$payment_notice['id'])); 
				app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id'])));
			}
				
				
//				if($r9_BType=="1"){
//					echo "交易成功";
//					echo  "<br />在线支付页面返回";
//				}elseif($r9_BType=="2"){
//					#如果需要应答机制则必须回写流,以success开头,大小写不敏感.
//					echo "success";
//					echo "<br />交易成功";
//					echo  "<br />在线支付服务器返回";      			 
//				}
			}
			
		}else{
			//echo "交易信息被篡改";
			//echo "RespCode=9999|JumpURL=".get_domain().url("index","payment#pay",array("id"=>$r6_Order)); 
			app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id'])));
		}
		
		

    }
    
     public function notify($request) {
		$return_res = array(
            'info' => '',
            'status' => false,
        );
		
        /* 取返回参数 */
        $version = $request["version"];
		$charset = $request["charset"];
		$language = $request["language"];
		$signType = $request["signType"];
		$tranCode = $request["tranCode"];
		$merchantID = $request["merchantID"];
		$merOrderNum = $request["merOrderNum"];
		$tranAmt = $request["tranAmt"];
		$feeAmt = $request["feeAmt"];
		$frontMerUrl = $request["frontMerUrl"];
		$backgroundMerUrl = $request["backgroundMerUrl"];
		$tranDateTime = $request["tranDateTime"];
		$tranIP = $request["tranIP"];
		$respCode = $request["respCode"];
		$msgExt = $request["msgExt"];
		$orderId = $request["orderId"];
		$gopayOutOrderId = $request["gopayOutOrderId"];
		$bankCode = $request["bankCode"];
		$tranFinishTime = $request["tranFinishTime"];
		$merRemark1 = $request["merRemark1"];
		$merRemark2 = $request["merRemark2"];
		$signValue = $request["signValue"];

        //参数转换
        $payment_notice_sn = $merRemark1;  //系统订单号
        $total_price = $tranAmt;//总价
	
        /*获取支付信息*/
        $payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='EHK'");  
    	$payment['config'] = unserialize($payment['config']);
    	
        $currency_id = $payment['currency'];
		
        /*比对连接加密字符串*/
		$signValue2='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=['.$orderId.']gopayOutOrderId=['.$gopayOutOrderId.']tranIP=['.$tranIP.']respCode=['.$respCode.']gopayServerTime=[]VerficationCode=['.$payment['config']['VerficationCode'].']';

        $signValue2 = md5($signValue2);

        if ($signValue != $signValue2 || $respCode != "0000") {
        	showErr($GLOBALS['payment_lang']["VALID_ERROR"]);
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
        }
    }

    public function get_display_code() {
        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='EHK'");
		if($payment_item)
		{
			$payment_cfg = unserialize($payment_item['config']);

//	        $html = "<style type='text/css'>.ehk_types{float:left; display:block; background:url(".get_domain().APP_ROOT."/system/payment/EHK/banklist_hnapay.jpg); font-size:0px; width:150px; height:10px; text-align:left; padding:15px 0px;}";
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
			//$html .="<h3 class='tl'><b>易汇金支付</b></h3><div class='blank1'></div><hr /><div class='blank1'></div>";
			
			
			$tsel="curr";
			$kk=0;
	       foreach ($payment_cfg['ehk_gateway'] AS $key=>$val)
	        {
	           
			   
			$html .= '<dd class="'.str_replace("-net-b2c","",strtolower($key)).'"><input id="'.strtolower($key).'" title="'.strtolower($key).'" type="radio" name="payment" onclick="set_bank(\''.$key.'\')" rel="'.$key.'"  value="'.$payment_item['id'].'"><label for="'.strtolower($key).'" class="'.$tsel.'"></label></dd>';
			   
			    //$html  .= "<label class='ehk_types bk_type_".$key."'><input type='radio' name='payment' value='".$payment_item['id']."' rel='".$key."' onclick='set_bank(\"".$key."\")' /></label>";
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
	
	#签名函数生成签名串
	function getReqHmacString($p2_Order,$p3_Cur,$p4_Amt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$p9_MP,$pa_FrpId,$p1_MerId,$merchantKey)
	{
	  $p0_Cmd = "Buy";
	  $logName	= "EhKing_HTML.log";
	  #进行签名处理，一定按照文档中标明的签名顺序进行
	  $sbOld = "";
	  #加入业务类型
	  $sbOld = $sbOld.$p0_Cmd;
	  #加入商户编号
	  $sbOld = $sbOld.$p1_MerId;
	  #加入商户订单号
	  $sbOld = $sbOld.$p2_Order; 
	  #加入交易币种
	  $sbOld = $sbOld.$p3_Cur;
	  #加入支付金额
	  $sbOld = $sbOld.$p4_Amt;
	  #加入商品名称
	  $sbOld = $sbOld.$p5_Pid;
	  #加入商品分类
	  $sbOld = $sbOld.$p6_Pcat;
	  #加入商品描述
	  $sbOld = $sbOld.$p7_Pdesc;
	  #加入商户接收支付成功数据的地址
	  $sbOld = $sbOld.$p8_Url;
	  #加入商户扩展信息
	  $sbOld = $sbOld.$p9_MP;
	  #加入支付通道编码
	  $sbOld = $sbOld.$pa_FrpId;
	  //logstr($p2_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
	  return $this->HmacMd5($sbOld,$merchantKey);
	  
	} 
	
	function HmacMd5($data,$key)
	{
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing(NOTE: Hacked means written)
		
		//需要配置环境支持iconv，否则中文参数不能正常处理
		$key = iconv("GB2312","UTF-8",$key);
		$data = iconv("GB2312","UTF-8",$data);
		
		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
		$key = pack("H*",md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;
		
		return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}
	
	#	取得返回串中的所有参数
	function getCallBackValue(&$p1_MerId,&$r0_Cmd,&$r1_Code,&$r2_TrxId,&$r3_Amt,&$r4_Cur,&$r5_Pid,&$r6_Order,&$r8_MP,&$r9_BType,&$ro_BankOrderId,&$rp_PayDate,&$hmac)
	{  
		$p1_MerId           = $_REQUEST['p1_MerId'];
		$r0_Cmd				= $_REQUEST['r0_Cmd'];
		$r1_Code			= $_REQUEST['r1_Code'];
		$r2_TrxId			= $_REQUEST['r2_TrxId'];
		$r3_Amt				= $_REQUEST['r3_Amt'];
		$r4_Cur				= $_REQUEST['r4_Cur'];
		$r5_Pid				= $_REQUEST['r5_Pid'];
		$r6_Order			= $_REQUEST['r6_Order'];
		$r8_MP				= $_REQUEST['r8_MP'];
		$r9_BType			= $_REQUEST['r9_BType']; 
		$ro_BankOrderId		= $_REQUEST['ro_BankOrderId'];
		$rp_PayDate			= $_REQUEST['rp_PayDate'];
		$hmac				= $_REQUEST['hmac'];
		return null;
	}
	
	function CheckHmac($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r8_MP,$r9_BType,$ro_BankOrderId,$rp_PayDate,$hmac)
	{
		
		if($hmac==$this->getCallbackHmacString($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r8_MP,$r9_BType,$ro_BankOrderId,$rp_PayDate))
			return true;
		else
			return false;
	}
	
	function getCallbackHmacString($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r8_MP,$r9_BType,$ro_BankOrderId,$rp_PayDate)
	{
	  
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where class_name='EHK'");
		$payment_info['config'] = unserialize($payment_info['config']);
		$p1_MerId			= $payment_info['config']['merchant_id'];	#商户ID
		$merchantKey	= $payment_info['config']['merchant_key'];		#密钥
		
		#取得加密前的字符串
		$sbOld = "";
		#加入商家ID
		$sbOld = $sbOld.$p1_MerId;
		#加入消息类型
		$sbOld = $sbOld.$r0_Cmd;
		#加入业务返回码
		$sbOld = $sbOld.$r1_Code;
		#加入交易ID
		$sbOld = $sbOld.$r2_TrxId;
		#加入交易金额
		$sbOld = $sbOld.$r3_Amt;
		#加入货币单位
		$sbOld = $sbOld.$r4_Cur;
		#加入产品Id
		$sbOld = $sbOld.$r5_Pid;
		#加入订单ID
		$sbOld = $sbOld.$r6_Order;
		#加入商家扩展信息
		$sbOld = $sbOld.$r8_MP;
		#加入交易结果返回类型
		$sbOld = $sbOld.$r9_BType;
		#银行订单号
		$sbOld = $sbOld.$ro_BankOrderId;
		#支付成功时间
		$sbOld = $sbOld.$rp_PayDate;
	
		//logstr($r6_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
		return $this->HmacMd5($sbOld,$merchantKey);
	
	}
	

}

?>

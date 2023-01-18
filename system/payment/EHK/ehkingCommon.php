<?php
include 'merchantProperties.php';
/*
 * @Description �׻��֧����Ʒͨ�ýӿڷ��� 
 * @V1.0
 * @Author ma.chao
 */
 	
	#	��Ʒͨ�ýӿڲ��������ַ
	$reqURL_onLine = "https://ehkpay.ehking.com/gateway/controller.action";   #������ַ
   # $reqURL_onLine = "http://qa.ehkpay.ehking.com/gateway/controller.action";  #QA���Ե�ַ
	# ҵ������
	# ֧�����󣬹̶�ֵ"Buy" .	
	$p0_Cmd = "Buy";
		
	
#ǩ����������ǩ����
function getReqHmacString($p2_Order,$p3_Cur,$p4_Amt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$p9_MP,$pa_FrpId)
{
  global $p0_Cmd;
	include 'merchantProperties.php';
		
	#����ǩ������һ�������ĵ��б�����ǩ��˳�����
  $sbOld = "";
  #����ҵ������
  $sbOld = $sbOld.$p0_Cmd;
  #�����̻����
  $sbOld = $sbOld.$p1_MerId;
  #�����̻�������
  $sbOld = $sbOld.$p2_Order; 
  #���뽻�ױ���
  $sbOld = $sbOld.$p3_Cur;
  #����֧�����
  $sbOld = $sbOld.$p4_Amt;
  #������Ʒ����
  $sbOld = $sbOld.$p5_Pid;
  #������Ʒ����
  $sbOld = $sbOld.$p6_Pcat;
  #������Ʒ����
  $sbOld = $sbOld.$p7_Pdesc;
  #�����̻�����֧���ɹ����ݵĵ�ַ
  $sbOld = $sbOld.$p8_Url;
  #�����̻���չ��Ϣ
  $sbOld = $sbOld.$p9_MP;
  #����֧��ͨ������
  $sbOld = $sbOld.$pa_FrpId;
	logstr($p2_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
  return HmacMd5($sbOld,$merchantKey);
  
} 

function getCallbackHmacString($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r8_MP,$r9_BType,$ro_BankOrderId,$rp_PayDate)
{
  
	include 'merchantProperties.php';
  
	#ȡ�ü���ǰ���ַ���
	$sbOld = "";
	#�����̼�ID
	$sbOld = $sbOld.$p1_MerId;
	#������Ϣ����
	$sbOld = $sbOld.$r0_Cmd;
	#����ҵ�񷵻���
	$sbOld = $sbOld.$r1_Code;
	#���뽻��ID
	$sbOld = $sbOld.$r2_TrxId;
	#���뽻�׽��
	$sbOld = $sbOld.$r3_Amt;
	#������ҵ�λ
	$sbOld = $sbOld.$r4_Cur;
	#�����ƷId
	$sbOld = $sbOld.$r5_Pid;
	#���붩��ID
	$sbOld = $sbOld.$r6_Order;
	#�����̼���չ��Ϣ
	$sbOld = $sbOld.$r8_MP;
	#���뽻�׽����������
	$sbOld = $sbOld.$r9_BType;
	#���ж�����
	$sbOld = $sbOld.$ro_BankOrderId;
	#֧���ɹ�ʱ��
	$sbOld = $sbOld.$rp_PayDate;

	logstr($r6_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
	return HmacMd5($sbOld,$merchantKey);

}


#	ȡ�÷��ش��е����в���
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
	if($hmac==getCallbackHmacString($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r8_MP,$r9_BType,$ro_BankOrderId,$rp_PayDate))
		return true;
	else
		return false;
}
		
  
function HmacMd5($data,$key)
{
// RFC 2104 HMAC implementation for php.
// Creates an md5 HMAC.
// Eliminates the need to install mhash to compute a HMAC
// Hacked by Lance Rushing(NOTE: Hacked means written)

//��Ҫ���û���֧��iconv���������Ĳ���������������
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

function logstr($orderid,$str,$hmac)
{
include 'merchantProperties.php';
$james=fopen($logName,"a+");
fwrite($james,"\r\n".date("Y-m-d H:i:s")."|orderid[".$orderid."]|str[".$str."]|hmac[".$hmac."]");
fclose($james);
}

?> 
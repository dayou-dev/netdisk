<?php
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------

$sms_lang = array(
	'ContentType'	=>	'消息类型',
	'ContentType_15'	=>	'普通短信通道(15)',
	'ContentType_8'	=>	'长短信通道(8)',

);
$config = array(
	'ContentType'	=>	array(
	'INPUT_TYPE'	=>	'1',
	'VALUES'	=> 	array(15,8)
	),
	
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'CL';
    /* 名称 */
    $module['name']    = "创蓝短信网平台";
    $module['lang']  = $sms_lang;
    $module['config'] = $config;	
    $module['server_url'] = 'http://222.73.117.158/msg/HttpBatchSendSM?';

    return $module;
}

// 企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
require_once APP_ROOT_PATH."system/sms/CL/transport.php"; 
require_once APP_ROOT_PATH."system/sms/CL/XmlBase.php"; 

class CL_sms implements sms
{
	public $sms;
	public $message = "";
   	
	private $statusStr = array(
		"0"  => "发送成功",
		"101"  => "验证失败",
		"102"  => "密码错误",
		"103"  => "提交过快",
		"104" => "系统忙",
		"105" => "敏感短信",
		"106" => "短信长度过长",
		"107" => "包含错误的手机号码",
		"108" => "手机号码发送个数过多",
		"109" => "无发送额度",
		"110" => "不在发送时间内",
		"111" => "超出该账户当月发送额度限制",
		"112" => "无此产品，用户没有订购该产品",
		"113" => "extno格式错",
		"115" => "自动审核驳回",
		"116" => "签名不合法，未带签名",
		"117" => "IP地址认证错",
		"118" => "用户没有相应的发送权限",
		"119" => "用户已过期"		   		   
	);
	
    public function __construct($smsInfo = '')
    { 	    	
		if(!empty($smsInfo))
		{			
			$this->sms = $smsInfo;
		}
    }
	
	
	public function getSmsInfo()
	{	

		return "创蓝短信网平台";	
		
	}
	
	public function check_fee()
	{
		$sms = new transport();
				
		$url = "http://222.73.117.158/msg/QueryBalance?account=".$this->sms['user_name']."&pswd=".$this->sms['password'];
		
		$xml= file_get_contents($url);
		
		$re=explode(chr(10),$xml);
		
		if($re[1]){
			$resms = explode(',',$re[1]);
		}		
		if($resms[1])  //发送成功 ，返回企业编号，员工编号，发送编号，短信条数，单价，余额
		{
			 
			 return "创蓝短信网平台，剩余：".$resms[1]."条";
			
		}
		else  //发送失败的返回值
		{
			return "查询失败，请核对账户是否正确!";
		}
		
		

	}
	
	
	public function sendSMS($m,$c)
	{
		
		if(is_array($m))
		{
			$m = implode(",",$m);
		}
		//$sms = new transport();
		
		
				
		$url='http://222.73.117.158/msg/HttpBatchSendSM?';           //接口地址
		$ac = $this->sms['user_name'];		                             //用户账号
		$authkey = $this->sms['password'];		         //认证密钥
		$cgid='3984';                                                  //通道组编号
		$csid='3';                                                   //签名编号 ,可以为空时，使用系统默认的编号
		$t='';                                                       //发送时间,可以为空表示立即发送,yyyyMMddHHmmss 如:20130721182038
		
		$re=$this->sendSMS_submit($url,$ac,$authkey,$cgid,$m,$c,$csid,$t);
		//echo print_r($re);
		//exit();
		$re=explode(',',$re);
		//echo print_r($re);
		if($re[1]=='0')                               //发送成功 ，返回企业编号，员工编号，发送编号，短信条数，单价，余额
		{
			 $result['status'] = 1;
		}
		else  //发送失败的返回值
		{
			
			
			
			$statusStr = array(
				"0"  => "发送成功",
				"101"  => "验证失败",
				"102"  => "密码错误",
				"103"  => "提交过快",
				"104" => "系统忙",
				"105" => "敏感短信",
				"106" => "短信长度过长",
				"107" => "包含错误的手机号码",
				"108" => "手机号码发送个数过多",
				"109" => "无发送额度",
				"110" => "不在发送时间内",
				"111" => "超出该账户当月发送额度限制",
				"112" => "无此产品，用户没有订购该产品",
				"113" => "extno格式错",
				"115" => "自动审核驳回",
				"116" => "签名不合法，未带签名",
				"117" => "IP地址认证错",
				"118" => "用户没有相应的发送权限",
				"119" => "用户已过期"		   		   
			);
			
			$result = $statusStr[trim($re[1])];
			
			 //switch(trim($re[1])){
//				case  0: $result['msg'] = "帐户格式不正确(正确的格式为:员工编号@企业编号)";break; 
//				case  -1: $result['msg'] = "服务器拒绝(速度过快、限时或绑定IP不对等)如遇速度过快可延时再发";break;
//				case  -2: $result['msg'] = " 密钥不正确";break;
//				case  -3: $result['msg'] = "密钥已锁定";break;
//				case  -4: $result['msg'] = "参数不正确(内容和号码不能为空，手机号码数过多，发送时间错误等)";break;
//				case  -5: $result['msg'] = "无此帐户";break;
//				case  -6: $result['msg'] = "帐户已锁定或已过期";break;
//				case  -7:$result['msg'] = "帐户未开启接口发送";break;
//				case  -8: $result['msg'] = "不可使用该通道组";break;
//				case  -9: $result['msg'] = "帐户余额不足";break;
//				case  -10: $result['msg'] = "内部错误";break;
//				case  -11: $result['msg'] = "扣费失败";break;
//				default:break;
//			 } 
			 
			
		}
		return $result;
	}
	
	
	
	public function sendSMS_submit($url,$ac,$authkey,$cgid,$m,$c,$csid,$t)
	{
		$data = array
			(
			'account'=>$ac,					                         //用户账号
			'pswd'=>$authkey,	                             //认证密钥
			'mobile'=>$m,		                                     //号码,多个号码用逗号隔开
			'msg'=>$c		                 //如果页面是gbk编码，则转成utf-8编码，如果是页面是utf-8编码，则不需要转码
			);
		
		$xml= $this->postSMS($url,$data);			                     //POST方式提交
		//$re=simplexml_load_string(utf8_encode($xml));
		return $xml;
	}
	
	public function postSMS($url,$data='')
	{
		$row = parse_url($url);
		$host = $row['host'];
		$port = $row['port'] ? $row['port']:80;
		$file = $row['path'];
		while (list($k,$v) = each($data)) 
		{
			$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
		}
		$post = substr( $post , 0 , -1 );
		$len = strlen($post);
		$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
		if (!$fp) {
			return "$errstr ($errno)\n";
		} else {
			$receive = '';
			$out = "POST $file HTTP/1.0\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Content-type: application/x-www-form-urlencoded\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Content-Length: $len\r\n\r\n";
			$out .= $post;		
			fwrite($fp, $out);
			while (!feof($fp)) {
				$receive .= fgets($fp, 128);
			}
			fclose($fp);
			$receive = explode("\r\n\r\n",$receive);
			unset($receive[0]);
			return implode("",$receive);
		}
	}

}
?>
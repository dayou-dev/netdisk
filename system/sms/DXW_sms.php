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
    $module['class_name']    = 'DXW';
    /* 名称 */
    $module['name']    = "中国短信网平台";
    $module['lang']  = $sms_lang;
    $module['config'] = $config;	
    $module['server_url'] = 'http://smsapi.c123.cn/OpenPlatform/OpenApi';

    return $module;
}

// 企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
require_once APP_ROOT_PATH."system/sms/DXW/transport.php"; 
require_once APP_ROOT_PATH."system/sms/DXW/XmlBase.php"; 

class DXW_sms implements sms
{
	public $sms;
	public $message = "";
   	
	private $statusStr = array(
		"100"  => "发送成功",
		"101"  => "验证失败",
		"102"  => "手机号码格式不正确",
		"103"  => "会员级别不够",
		"104" => "内容未审核",
		"105" => "内容过多",
		"106" => "账户余额不足",
		"107" => "Ip受限",
		"108" => "手机号码发送太频繁，请换号或隔天再发",
		"109" => "帐号被锁定",
		"110" => "发送通道不正确",
		"111" => "当前时间段禁止短信发送",
		"120" => "系统升级"		   		   
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

		return "中国短信网平台";	
		
	}
	
	public function check_fee()
	{
		$sms = new transport();
				
		
		$url = "http://smsapi.c123.cn/OpenPlatform/OpenApi?action=getBalance&ac=".$this->sms['user_name']."&authkey=".$this->sms['password'];
		
		$xml= file_get_contents($url);
		
		//$xml= $this->postSMS($url,$data);			//POST方式提交
		$re=simplexml_load_string($xml);
		if(trim($re['result'])==1)  //发送成功 ，返回企业编号，员工编号，发送编号，短信条数，单价，余额
		{
			 foreach ($re->Item as $item)
			 {
				 
				   $remain=trim((string)$item['remain']);
				   $stat_arr[]=$stat;
				
			 }
			 return "企信通短信平台，剩余：".$remain."元";	
			
		}
		else  //发送失败的返回值
		{
			 switch(trim($re['result']))
			 {
				case  0: return "帐户格式不正确(正确的格式为:员工编号@企业编号)";break; 
				case  -1: return "服务器拒绝(速度过快、限时或绑定IP不对等)如遇速度过快可延时再发";break;
				case  -2: return " 密钥不正确";break;
				case  -3: return "密钥已锁定";break;
				case  -4: return "参数不正确(内容和号码不能为空，手机号码数过多，发送时间错误等)";break;
				case  -5: return "无此帐户";break;
				case  -6: return "帐户已锁定或已过期";break;
				case  -7: return "帐户未开启接口发送";break;
				case  -8: return "不可使用该通道组";break;
				case  -9: return "帐户余额不足";break;
				case  -10: return "内部错误";break;
				case  -11: return "扣费失败";break;
				default:break;
			}
		}
		
		
		//$str = "企信通短信平台，剩余：".$match[1][0]."元".$this->sms['user_name'];	

		//return $str;

	}
	
	
	public function sendSMS($m,$c)
	{
		
		if(is_array($m))
		{
			$m = implode(",",$m);
		}
		//$sms = new transport();
		
		
				
		$url='http://smsapi.c123.cn/OpenPlatform/OpenApi';           //接口地址
		$ac = $this->sms['user_name'];		                             //用户账号
		$authkey = $this->sms['password'];		         //认证密钥
		$cgid='185';                                                  //通道组编号
		$csid='3';                                                   //签名编号 ,可以为空时，使用系统默认的编号
		$t='';                                                       //发送时间,可以为空表示立即发送,yyyyMMddHHmmss 如:20130721182038
		
		$re=$this->sendSMS_submit($url,$ac,$authkey,$cgid,$m,$c,$csid,$t);
		if(trim($re['result'])==1)                               //发送成功 ，返回企业编号，员工编号，发送编号，短信条数，单价，余额
		{
			 $result['status'] = 1;
		}
		else  //发送失败的返回值
		{
			
			
			 switch(trim($re['result'])){
				case  0: $result['msg'] = "帐户格式不正确(正确的格式为:员工编号@企业编号)";break; 
				case  -1: $result['msg'] = "服务器拒绝(速度过快、限时或绑定IP不对等)如遇速度过快可延时再发";break;
				case  -2: $result['msg'] = " 密钥不正确";break;
				case  -3: $result['msg'] = "密钥已锁定";break;
				case  -4: $result['msg'] = "参数不正确(内容和号码不能为空，手机号码数过多，发送时间错误等)";break;
				case  -5: $result['msg'] = "无此帐户";break;
				case  -6: $result['msg'] = "帐户已锁定或已过期";break;
				case  -7:$result['msg'] = "帐户未开启接口发送";break;
				case  -8: $result['msg'] = "不可使用该通道组";break;
				case  -9: $result['msg'] = "帐户余额不足";break;
				case  -10: $result['msg'] = "内部错误";break;
				case  -11: $result['msg'] = "扣费失败";break;
				default:break;
			 } 
			 
			return $result;
		}
	}
	
	
	
	public function sendSMS_submit($url,$ac,$authkey,$cgid,$m,$c,$csid,$t)
	{
		$data = array
			(
			'action'=>'sendOnce',                                //发送类型 ，可以有sendOnce短信发送，sendBatch一对一发送，sendParam	动态参数短信接口
			'ac'=>$ac,					                         //用户账号
			'authkey'=>$authkey,	                             //认证密钥
			'cgid'=>$cgid,                                       //通道组编号
			'm'=>$m,		                                     //号码,多个号码用逗号隔开
			'c'=>$c,		                 //如果页面是gbk编码，则转成utf-8编码，如果是页面是utf-8编码，则不需要转码
			'csid'=>$csid,                                       //签名编号 ，可以为空，为空时使用系统默认的签名编号
			't'=>$t                                              //定时发送，为空时表示立即发送
			);
		$xml= $this->postSMS($url,$data);			                     //POST方式提交
		$re=simplexml_load_string(utf8_encode($xml));
		return $re;
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
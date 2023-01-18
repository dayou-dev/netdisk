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
    $module['class_name']    = 'QXT';
    /* 名称 */
    $module['name']    = "企信通短信平台";
    $module['lang']  = $sms_lang;
    $module['config'] = $config;	
    $module['server_url'] = 'http://221.179.180.158:9000/QxtSms/QxtFirewall';

    return $module;
}

// 企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
require_once APP_ROOT_PATH."system/sms/QXT/transport.php"; 
require_once APP_ROOT_PATH."system/sms/QXT/XmlBase.php"; 

class QXT_sms implements sms
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
	
	public function sendSMS($mobile_number,$content)
	{
		if(is_array($mobile_number))
		{
			$mobile_number = implode(",",$mobile_number);
		}
		$sms = new transport();
		//echo $content;exit();
		$post_data = "account=".$this->sms['user_name']."&password=".$this->sms['password']."&mobile=".$mobile_number."&content=".rawurlencode($content);
		$code = $sms->Post($post_data);
		
		if(trim($code)!='100'){
			$result['info'] = $this->statusStr[trim($code)];
			$result['status'] = 0;
		}else{
			$result['status'] = 1;
		}
		
		
		return $result;
	}
	
	public function getSmsInfo()
	{	

		return "企信通短信平台";	
		
	}
	
	public function check_fee()
	{
		$sms = new transport();
				
		$params = array(
						"OperID"=>$this->sms['user_name'],
						"OperPass"=>$this->sms['password']
					);
					
		$url = "http://www.dxton.com/webservice/sms.asmx/GetNum?account=".$this->sms['user_name']."&password=".$this->sms['password'];
		$content = file_get_contents($url);
	    $pa = '%<string.*?>(.*?)</string>%si';
	    preg_match_all($pa,$content,$match);
		
		$str = "企信通短信平台，剩余：".$match[1][0]."元".$this->sms['user_name'];	

		return $str;

	}
}
?>
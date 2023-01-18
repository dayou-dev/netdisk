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
    $module['class_name']    = 'GYWX';
    /* 名称 */
    $module['name']    = "互亿无线短信网平台";
    $module['lang']  = $sms_lang;
    $module['config'] = $config;	
    $module['server_url'] = 'http://106.ihuyi.cn/webservice/sms.php?method=Submit';

    return $module;
}

// 企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
require_once APP_ROOT_PATH."system/sms/GYWX/transport.php"; 
require_once APP_ROOT_PATH."system/sms/GYWX/XmlBase.php"; 

class GYWX_sms implements sms
{
	public $sms;
	public $message = "";
   	
	private $statusStr = array(
		"0"  => "提交失败",
		"2"  => "提交成功",
		"400"  => "非法ip访问",
		"401"  => "帐号不能为空",
		"402" => "密码不能为空",
		"403" => "手机号码不能为空",
		"4030" => "手机号码已被列入黑名单",
		"404" => "短信内容不能为空",
		"405" => "用户名或密码不正确",
		"4050" => "账号被冻结",
		"4051" => "剩余条数不足",
		"4052" => "访问ip与备案ip不符",
		"406" => "手机格式不正确",
		"4070" => "签名格式不正确",
		"4071" => "没有提交备案模板",
		"4072" => "短信内容超出长度限制",
		"4073" => "没有提交备案模板",
		"4071" => "您的帐户疑被恶意利用，已被自动冻结，如有疑问请与客服联系。",
		"408" => "手机格式不正确"		   		   
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

		return "互亿无线短信网平台";	
		
	}
	
	public function restatus($re)
	{	
		$restatusStr = array(
			"0"  => "提交失败",
			"2"  => "提交成功",
			"400"  => "非法ip访问",
			"401"  => "帐号不能为空",
			"402" => "密码不能为空",
			"403" => "手机号码不能为空",
			"4030" => "手机号码已被列入黑名单",
			"404" => "短信内容不能为空",
			"405" => "用户名或密码不正确",
			"4050" => "账号被冻结",
			"4051" => "剩余条数不足",
			"4052" => "访问ip与备案ip不符",
			"406" => "手机格式不正确",
			"4070" => "签名格式不正确",
			"4071" => "没有提交备案模板",
			"4072" => "短信内容超出长度限制",
			"4073" => "没有提交备案模板",
			"4071" => "您的帐户疑被恶意利用，已被自动冻结，如有疑问请与客服联系。",
			"408" => "手机格式不正确"		   		   
		);
		return $restatusStr[intval($re)];	
		
	}
	
	public function check_fee()
	{
		$sms = new transport();
				
		
		$url = "http://106.ihuyi.cn/webservice/sms.php?method=GetNum&account=".$this->sms['user_name']."&password=".$this->sms['password'];
		
		$xml= file_get_contents($url);
		
		//$xml= $this->postSMS($url,$data);			//POST方式提交
		$re=simplexml_load_string($xml);
		
		if(trim($re->code)==2)  //发送成功 ，返回企业编号，员工编号，发送编号，短信条数，单价，余额
		{
			
			 return "互亿无线短信平台，剩余：".$re->num."条";	
		}
		else  //发送失败的返回值
		{
			return $this->restatus(trim($re->code));
		}
		
		
		//$str = "企信通短信平台，剩余：".$match[1][0]."元".$this->sms['user_name'];	

		//return $str;

	}
	
	
	public function sendSMS($m,$c,$d=array())
	{
		
		if(is_array($m))
		{
			$m = implode(",",$m);
		}
		$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
		$post_data = "account=".$this->sms['user_name']."&password=".$this->sms['password']."&mobile=".$m."&content=".rawurlencode($c);
		//密码可以使用明文密码或使用32位MD5加密
		$gets =  $this->xml_to_array($this->Post($post_data, $target));
		if($gets['SubmitResult']['code']==2){
			$result['status'] = 1;
		}else{
			$result['msg'] = $this->restatus(trim($gets['SubmitResult']['code']));
			$result['status'] = 0;
		}
		//echo print_r($gets);
		return $result;
	}
	
	
	public function Post($curlPost,$url){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_NOBODY, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
			$return_str = curl_exec($curl);
			curl_close($curl);
			return $return_str;
	}
	public function xml_to_array($xml){
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
		if(preg_match_all($reg, $xml, $matches)){
			$count = count($matches[0]);
			for($i = 0; $i < $count; $i++){
			$subxml= $matches[2][$i];
			$key = $matches[1][$i];
				if(preg_match( $reg, $subxml )){
					$arr[$key] = $this->xml_to_array( $subxml );
				}else{
					$arr[$key] = $subxml;
				}
			}
		}
		
		return $arr;
	}
	
	public function sendSMS_submit($url,$ac,$authkey,$cgid,$m,$c,$csid,$t)
	{
		$data = array
			(
			'account'=>$ac,					                         //用户账号
			'password'=>$authkey,	                             //认证密钥
			'mobile'=>$m,		                                     //号码,多个号码用逗号隔开
			'content'=>$c                                     //短信内容
			);
		$xml= $this->postSMS($url,$data);	
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
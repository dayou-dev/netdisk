<?php
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------

$sms_lang = array(
	'ContentType'	=>	'消息类型',
	'ContentType_15'	=>	'普通短信通道(15)',
	'ContentType_8'	=>	'长短信通道(8)',
	'bizCode'	=>	'验证代码',

);
$config = array(
	'ContentType'	=>	array(
	'INPUT_TYPE'	=>	'1',
	'VALUES'	=> 	array(15,8)
	),
	'bizCode'	=>	array(
	'INPUT_TYPE'	=>	'0',
	'VALUES'	=> 	''
	),
	
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'ALY';
    /* 名称 */
    $module['name']    = "啊里云信短信网平台";
    $module['lang']  = $sms_lang;
    $module['config'] = $config;	
    $module['server_url'] = 'https://help.aliyun.com/';

    return $module;
}

// 企信通短信平台
require_once APP_ROOT_PATH."system/libs/sms.php";  //引入接口
class ALY_sms implements sms
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

		return "啊里云信短信网平台";	
		
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
		
		return "该接口不支持剩余短信查询.";	
		//$sms = new ServerAPI();
	}
	
	
	public function sendSMS( $m ,$c , $params = array() )
	{
		if(is_array($m))
		{
			$m = implode(",",$m);
		}
		$m=explode(",",$m);
		
		
		$smsconfig = $this->sms['config'];
		$smscode = $smsconfig['bizCode'];
		
		$params = array ();
		// *** 需用户填写部分 ***
	
		// fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
		$accessKeyId = $this->sms['user_name'];
		$accessKeySecret = $this->sms['password'];
	
		// fixme 必填: 短信接收号码
		$params["PhoneNumbers"] = "86".$m[0];
	
		// fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
		$params["SignName"] = "东辉普众";
	
		// fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
		$params["TemplateCode"] = $smscode;
	
		// fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
		
		$smsInfo_1=explode("：",$c);
		$smsInfo_2=explode("。",$smsInfo_1[1]);
		$params['TemplateParam'] = array ("code" => $smsInfo_2[0]);
		// fixme 可选: 设置发送短信流水号
		//$params['OutId'] = "12345";
	
		// fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
		//$params['SmsUpExtendCode'] = "1234567";
	
		// *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
		if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
			$params["TemplateParam"] = json_encode($params["TemplateParam"]);
		}
		// 初始化SignatureHelper实例用于设置参数，签名以及发送请求
		//$helper = new SignatureHelper();
		
		// 此处可能会抛出异常，注意catch
		$content = $this->request(
			$accessKeyId,
			$accessKeySecret,
			"dysmsapi.aliyuncs.com",
			array_merge($params, array(
				"RegionId" => "cn-hangzhou",
				"Action" => "SendSms",
				"Version" => "2017-05-25",
			))
			// fixme 选填: 启用https
			// ,true
		);
		//echo print_r($content);
		$sms_return['info']=$content->Message;
		$sms_return['status']=$content->Code=='OK'?1:0;
		return $sms_return;

	}
	
    /**
     * 生成签名并发起请求
     *
     * @param $accessKeyId string AccessKeyId (https://ak-console.aliyun.com/)
     * @param $accessKeySecret string AccessKeySecret
     * @param $domain string API接口所在域名
     * @param $params array API具体参数
     * @param $security boolean 使用https
     * @return bool|\stdClass 返回API接口调用结果，当发生错误时返回false
     */
    public function request($accessKeyId, $accessKeySecret, $domain, $params, $security=false) {
        $apiParams = array_merge(array (
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => uniqid(mt_rand(0,0xffff), true),
            "SignatureVersion" => "1.0",
            "AccessKeyId" => $accessKeyId,
            "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
            "Format" => "JSON",
        ), $params);
        ksort($apiParams);

        $sortedQueryStringTmp = "";
        foreach ($apiParams as $key => $value) {
            $sortedQueryStringTmp .= "&" . $this->encode($key) . "=" . $this->encode($value);
        }

        $stringToSign = "GET&%2F&" . $this->encode(substr($sortedQueryStringTmp, 1));

        $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&",true));

        $signature = $this->encode($sign);

        $url = ($security ? 'https' : 'http')."://{$domain}/?Signature={$signature}{$sortedQueryStringTmp}";

        try {
            $content = $this->fetchContent($url);
            return json_decode($content);
        } catch(Exception $e) {
            return false;
        }
    }

    private function encode($str)
    {
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }

    private function fetchContent($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "x-sdk-client" => "php/2.0.0"
        ));

        if(substr($url, 0,5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $rtn = curl_exec($ch);

        if($rtn === false) {
            trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
        }
        curl_close($ch);

        return $rtn;
    }

}
?>
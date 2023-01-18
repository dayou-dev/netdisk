<?php
class wxapisModule extends SiteBaseModule
{
	public function index()
	{	
		echo $_SERVER['SERVER_ADDR'].'#'.$_SESSION['openid'];
	}
	
	public function get_openid(){
		$system=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."system ");
		$WX_APPID=$system['APPID_x'];
		$WX_SECRET=$system['AppSecret_x'];
		$code=$_REQUEST['code'];//获取code
		$nickName=htmlstrchk($_REQUEST['nickName']);
		$avatarUrl=htmlstrchk($_REQUEST['avatarUrl']);
		//获取openid
		if($code)
		{   //用code获取openid
			//$WX_APPID = 'wxef19a2bd4ab2d650';//appid
			//$WX_SECRET = '264ba1e471160a919d4280eddd8218b3';//AppSecret
			$url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $WX_APPID . "&secret=" . $WX_SECRET . "&js_code=" . $code . "&grant_type=authorization_code";
			$infos = json_decode(file_get_contents($url));
			$openid = $infos->openid;
		}else{
			$result['status'] = 0;
			$result['info'] = "参数错误";
			ajax_return($result);
		}

		if($openid){
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where openid='".$openid."' and wxpro=1 and is_delete = 0");
			if($user_info){
				es_session::set("user_info",$user_info);
			}else{
				//无账号时创建临时账号
				//推荐人
				$pid = intval(es_session::get("REFERRAL_USER"))>0?es_session::get("REFERRAL_USER"):0;
				//四位的验证码。
				$checkWord = '';
				//验证码的所有可用字符。
				$checkChar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ1234567890';
				//获取 4 位随机数。
				for($num=0; $num<4; $num++){
				  $char=rand(0, strlen($checkChar)-1);
				  $checkWord.=$checkChar[$char];
				}
				
				$userdata=array();
				$userdata['user_name'] = time().$checkWord;
				$userdata['mobile'] = '';
				$userdata['password'] = md5($openid);
				$userdata['nickname'] = $nickName;
				$userdata['icon'] = $avatarUrl;
				$userdata['email'] = '';
				$userdata['wxpro'] = '1';
				$userdata['openid'] = $openid;
				$userdata['pid'] = $pid;
				$userdata['login_ip'] = get_client_ip();
				$userdata['is_effect'] = 1;
				$userdata['update_time'] = time();
				$userdata['create_time'] = time();
				$userdata['group_id'] = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."user_group order by id asc");
				$GLOBALS['db']->autoExecute(DB_PREFIX."user",$userdata); //插入
				$reid = $GLOBALS['db']->insert_id();
				if($reid){
					$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = $reid");
					es_session::set("user_info",$user_info);
				}
				
				//es_session::set("wxloginInt",1);
			}
			$_SESSION['user_info']=$user_info;
			$result['status'] = 1;
			$result['info'] = $openid;
			$result['user_id'] = $user_info['id']?$user_info['id']:0;
			ajax_return($result);
		}else{
			$_SESSION['openid']="";
			$result['status'] = 0;
			$result['info'] = "授权失败";
			ajax_return($result);
		}
	}
	
	//微信支付
	public function pay(){
		$app_conf=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."system ");
		$WX_APPID=$app_conf['APPID_x'];
		$WX_SECRET=$app_conf['AppSecret_x'];
		$WX_mch_id=$app_conf['MCHID'];
		$payinfo_id=intval($_REQUEST['pay_id']);
		$payInfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_log where id = ".$payinfo_id);
		//$payInfo['trade_nu']=time();
		//$payInfo['money']=0.1;
		//$payInfo['payid']=28;
		
		if(!$payInfo){
			$result['status'] = 0;
			$result['info'] = "支付订单不存在，或已取消，请核对后在进行操作";
			ajax_return($result);
		}
		
		
		$order_status=intval($orderInfo['status']);
		if($order_status>0){
			$result['status'] = 0;
			$result['info'] = "订单已处理,请勿反复操作";
			ajax_return($result);
		}
		
		
		$ordernu = $payInfo['trade_nu'];
		$money = round($payInfo['money'],2);
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payInfo['payid']));
		$payment_info_config = unserialize($payment_info['config']);
		
		if($app_conf['is_test']) $money=0.1; //测试模式
		//获取openid
		if($_REQUEST['code'])
		{   //用code获取openid
			$code=$_REQUEST['code'];
			//$WX_APPID = 'wxef19a2bd4ab2d650';//appid
			//$WX_SECRET = '264ba1e471160a919d4280eddd8218b3';//AppSecret
			$url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $WX_APPID . "&secret=" . $WX_SECRET . "&js_code=" . $code . "&grant_type=authorization_code";
			$infos = json_decode(file_get_contents($url));
			$openid = $infos->openid;
		}
		//$openid='o2zGY5OonrDB0fBJbTpnHsWlZ1jU';
		if(!$openid){
			$result['status'] = 0;
			$result['info'] = "请添加账号授权,在进行操作";
			ajax_return($result);
		}
		
		
		//$money = 0.01;//举例支付0.01
		$appid =$WX_APPID;//appid.如果是公众号 就是公众号的appid
		$body ='order_pay';
		$mch_id =$WX_mch_id;//商户号
		$nonce_str=$this->nonce_str();//随机字符串
		$notify_url ='https://wx.cjycar.com/payment/notify/WechatPay'; //回调的url【自己填写】
		//$notify_url =$payment_info_config['wx_url']; //回调的url
		$openid =$openid;
		$out_trade_no = $this->order_number();//商户订单号
		//$out_trade_no = $ordernu;//商户订单号
		$spbill_create_ip = $_SERVER['SERVER_ADDR'];//服务器的ip【自己填写】;
		$total_fee = $money*100;// 微信支付单位是分，所以这里需要*100
		$trade_type = 'JSAPI';//交易类型 默认
	 
	 
	    //这里是按照顺序的 因为下面的签名是按照顺序 排序错误 肯定出错
	    $post['appid'] = $appid;
	    $post['body'] = $body;
	    $post['mch_id'] = $mch_id;
	    $post['nonce_str'] = $nonce_str;//随机字符串
	    $post['notify_url'] = $notify_url;
	    $post['openid'] = $openid;
	    $post['out_trade_no'] = $out_trade_no;
	    $post['spbill_create_ip'] = $spbill_create_ip;//终端的ip
	    $post['total_fee'] = $total_fee;//总金额 
	    $post['trade_type'] = $trade_type;
	    $sign = $this->sign($post);//签名
	    $post_xml = '<xml>
		   <appid>'.$appid.'</appid>
		   <body>'.$body.'</body>
		   <mch_id>'.$mch_id.'</mch_id>
		   <nonce_str>'.$nonce_str.'</nonce_str>
		   <notify_url>'.$notify_url.'</notify_url>
		   <openid>'.$openid.'</openid>
		   <out_trade_no>'.$out_trade_no.'</out_trade_no>
		   <spbill_create_ip>'.$spbill_create_ip.'</spbill_create_ip>
		   <total_fee>'.$total_fee.'</total_fee>
		   <trade_type>'.$trade_type.'</trade_type>
		   <sign>'.$sign.'</sign>
		</xml> ';
	 //echo print_r($post);exit();
	    //统一接口prepay_id
	    $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
	    $xml = $this->http_request($url,$post_xml);
		//echo $xml;
	    $array = $this->xmlToArray($xml);//全要大写
	    if($array['return_code'] == 'SUCCESS' && $array['result_code'] == 'SUCCESS'){
			$time = time();
			$tmp=array();//临时数组用于签名
			$tmp['appId'] = $appid;
			$tmp['nonceStr'] = $nonce_str;
			$tmp['package'] = 'prepay_id='.$array['prepay_id'];
			$tmp['signType'] = 'MD5';
			$tmp['timeStamp'] = "$time";
			
			/*$tmp['appid'] = $appid;
			$tmp['mch_id'] = $mch_id;
			$tmp['nonce_str'] = $array['nonce_str'];
			$tmp['prepay_id'] = $array['prepay_id'];
			$tmp['result_code'] = $array['result_code'];
			$tmp['return_code'] = $array['return_code'];
			$tmp['return_msg'] = $array['return_msg'];
			$tmp['trade_type'] = 'JSAPI';*/
			
				 
			$data['state'] = 200;
			$data['timeStamp'] = "$time";//时间戳
			$data['nonceStr'] = $tmp['nonceStr'];//随机字符串
			$data['signType'] = 'MD5';//签名算法，暂支持 MD5
			$data['package'] = 'prepay_id='.$array['prepay_id'];//统一下单接口返回的 prepay_id 参数值，提交格式如：prepay_id=*
			$data['paySign'] = $this->sign($tmp);//签名,具体签名方案参见微信公众号支付帮助文档;
			$data['out_trade_no'] = $out_trade_no;
			$data['sign'] = $array['sign'];
			$data['status'] = 1;
			$GLOBALS['db']->query("update ".DB_PREFIX."payment_log set trade_nu='$out_trade_no' where id = ".$payinfo_id);
	    }else{
			$data['state'] = 0;
			$data['info'] = "当前支付订单已超时";
			$data['return_code'] = $array['return_code'];
			$data['return_msg'] = $array['return_msg'];
			$data['status'] = 0;
	    }
	    echo json_encode($data);
	}
	 
	//随机32位字符串
	private function nonce_str(){
	    $result = '';
	    $str = 'QWERTYUIOPASDFGHJKLZXVBNMqwertyuioplkjhgfdsamnbvcxz';
	    for ($i=0;$i<32;$i++){
$result .= $str[rand(0,48)];
	    }
	    return $result;
	}
	 
	 
	 
	//生成订单号
	private function order_number($openid=""){
	    //date('Ymd',time()).time().rand(10,99);//18位
	    return md5($openid.time().rand(10,99));//32位
	}
	 
	//签名 $data要先排好顺序
	private function sign($data){
	    $stringA = '';
	    foreach ($data as $key=>$value){
		if(!$value) continue;
		if($stringA) $stringA .= '&'.$key."=".$value;
		else $stringA = $key."=".$value;
	    }
		$wx_key = $GLOBALS['db']->getOne("select MCHKey from ".DB_PREFIX."system");
		//echo $wx_key.'#';
	    //$wx_key = 'AAcccee454qqffxxwwAffxGGddeessaa';//申请支付后有给予一个商户账号和密码，登陆后自己设置的key
	    $stringSignTemp = $stringA.'&key='.$wx_key;
		//echo $stringSignTemp;
		//echo '@'.strtoupper(md5($stringSignTemp)).'@';
	    return strtoupper(md5($stringSignTemp));
	}
	 
	 
	//curl请求
	public function http_request($url,$data = null,$headers=array())
	{
	    $curl = curl_init();
	    if( count($headers) >= 1 ){
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    }
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
	}
	
    private function xmlToArray($xml)
    {    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }
	
	 
	//获取xml
	private function xml($xml){
	    $p = xml_parser_create();
	    xml_parse_into_struct($p, $xml, $vals, $index);
	    xml_parser_free($p);
	    $data = "";
	    foreach ($index as $key=>$value) {
			if($key == 'xml' || $key == 'XML') continue;
			$tag = $vals[$value[0]]['tag'];
			$value = $vals[$value[0]]['value'];
			$data[$tag] = $value;
	    }
	    return $data;
	}
}	
?>
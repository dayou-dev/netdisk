<?php
class wxapiModule extends SiteBaseModule
{
	public function index()
	{	
		
		require APP_ROOT_PATH.'app/Lib/uc.php';
		$user_info = es_session::get("user_info");
		$user_id = intval($user_info['id']);
		$user_info =  $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=$user_id");
		if($user_info['openid']){
			$GLOBALS['tmpl']->assign('user_info',$user_info);
			$GLOBALS['tmpl']->display("user/user_wxbind.html");
		}else{
			$system =  $GLOBALS['db']->getRow("select * from ".DB_PREFIX."system ");
			$appid=$system['APPID'];
			$appsecret=$system['AppSecret'];
			$redirect_uri=$system['SHOP_URL'];
			header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirec");
			//echo "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'>getopenid</a>";
		}
	}
	
	public function un_bind()
	{
		require APP_ROOT_PATH.'app/Lib/uc.php';
		$user_info = es_session::get("user_info");
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".intval($user_info['id']));
		if($_POST){
			
			if(check_ipop_limit(get_client_ip(),"user_find_weixin",intval(app_conf("SUBMIT_DELAY")))){
				$account = htmlstrchk($user_info['mobile']);
				$checkCode = htmlstrchk($_REQUEST['mobilecode']);
				
				if(!$checkCode){
					$return['info']='短信验证码不能为空';
					$return['status']=0;
					ajax_return($return);
				}
				
				//验证码
				$session_mobilecode = es_session::get('mobilecode');
				$sendmobile_verify = es_session::get('sendmobile');
				if($checkCode!=$session_mobilecode || $sendmobile_verify!=$account)
				{
					$result['status'] = 0;
					$result['info'] = '验证码输入错误';
					ajax_return($result);
				}
				
				$txtPassword=md5($password);
				$user_id=intval($user_info['id']);
				$GLOBALS['db']->getRow("update ".DB_PREFIX."user set openid='' where id=".intval($user_id));
				$result['status'] = 1;
				$result['info'] = '';
				ajax_return($result);
			
			}else{
				$return['info']=$GLOBALS['lang']['SUBMIT_TOO_FAST'];
				$return['status']=0;
				ajax_return($return);
			}

		}else{
			$GLOBALS['tmpl']->assign('user_info',$user_info);
			$GLOBALS['tmpl']->display("user/user_unwxbind.html");
		}
	}
	
	public function autologin(){
		$openid = $this->get_openid();
		if($openid){
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where openid='".$openid."' and is_delete = 0");
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
				$userdata['email'] = '';
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
					$user_appdata=$this->get_userinfo($openid);
					$GLOBALS['db']->query("update ".DB_PREFIX."user set nickname='".$user_appdata['nickname']."',icon='".$user_appdata['headimgurl']."' where id=$reid");
					$user_info['mobile']='';
					$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = $reid");
					es_session::set("user_info",$user_info);
				}
				
				//es_session::set("wxloginInt",1);
			}
		}
		if(!$user_info['mobile']){
			header("Location:/user/bind_mobile");
		}else{
			header("Location:/user/");
		}
	}
	
	public function accbind(){
		require APP_ROOT_PATH.'app/Lib/uc.php';
		$user_info = es_session::get("user_info");
		$code=$_REQUEST['code'];//获取code
		if(!$_POST){
			 $openid=$this->get_openid();
			 if	(!$openid){
				 showErr("请同意微信授权后在时行操作",0,"/user");
			 }
		}
		$user_appdata=$this->get_userinfo($openid);
		$openid_chk = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where openid='".$openid."' and is_delete = 0");
		if($openid_chk){
			showErr("当前账号已绑定其它账号，请解绑后操作",0,"/user");
		}
		
		if($code){
			$GLOBALS['db']->query("update ".DB_PREFIX."user set openid='".$_SESSION['openid']."',nickname='".$user_appdata['nickname']."',icon='".$user_appdata['headimgurl']."' where id=".intval($user_info['id']));
			es_session::set("wxloginInt",0);
			showSuccess("账号绑定成功",0,"/user");
		}else{
			showErr("账号绑定失败",0,"/user");
		}
	}
	
	private function getaccss_token(){
			$system =  $GLOBALS['db']->getRow("select * from ".DB_PREFIX."system ");
			$appid=$system['APPID'];
			$appsecret=$system['AppSecret'];
			$useraccess_token =  $system['access_token'];
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
			//判断是不是第一次获取access_token		
			if(!$useraccess_token){			
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);
				$jsoninfo = json_decode($output, true);
				$access_token = $jsoninfo["access_token"];
				$GLOBALS['db']->query("update ".DB_PREFIX."system set access_token='$access_token',expires_in='".($jsoninfo['expires_in']+time()-200)."'");			
				return $access_token;
		}else if($system['expires_time']<time()){//判断是否过期	
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);
				$jsoninfo = json_decode($output, true);//转换格式
				$access_token = $jsoninfo["access_token"];	
				$GLOBALS['db']->query("update ".DB_PREFIX."system set access_token='$access_token',expires_in='".($jsoninfo['expires_in']+time()-200)."'");
				return $access_token;
		}else{	
			$access_token = $system['access_token'];
			return $access_token;
			
		}
	}
	
	private function get_openid(){
		$system=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."system ");
		$appid=$system['APPID'];
		$appsecret=$system['AppSecret'];
		$code=$_REQUEST['code'];//获取code
		
		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=".$code."&grant_type=authorization_code";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$jsoninfo = json_decode($output, true);//转换格式
		$_SESSION['openid']=$jsoninfo['openid'];
		return $jsoninfo['openid'];
	}
	//获取到access_token后，再通过access_token和openid来获取用户个人信息如下图函数：
	
	private function get_userinfo($openid){
			$access_token = $this->getaccss_token();
			//获取用户信息地址		
			$urlid = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
			$curl = curl_init(); // 启动一个CURL会话
			curl_setopt($curl, CURLOPT_URL, $urlid);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
			//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
			$tmpInfo = curl_exec($curl);     //返回api的json对象
			//关闭URL请求
			curl_close($curl);
					$userinfo = json_decode($tmpInfo,true);			
			return $userinfo;
	}
	
	
	public function send_order_msg(){
		/*$redis = new Redis();  
		$redis->connect('127.0.0.1', 6379);//serverip port
		//$redis ->del( "ext_weixin");exit();
		if($redis ->get( "ext_weixin") == date("Y-m-d")){
			echo  date("Y-m-d H:i:s").' -- ok';
			exit;
		}*/
		//ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
		//set_time_limit(0);// 通过set_time_limit(0)可以让程序无限制的执行下去
		//while(true){
			//sleep(1);      //让程序睡10s,可以根据自己的逻辑设置时间
		//}
		
		
		$getaccss_token=$this->getaccss_token();
		$extdate=strtotime(date("Y-m-d 00:00:00",strtotime("-1 day")));
		//echo date("Y-m-d",$extdate);
		//$extdate=1577289600;
		$kk=0;
		//while(true){
			$user_info=$GLOBALS['db']->getRow("SELECT id,openid from ".DB_PREFIX."user where id in(SELECT user_id FROM ".DB_PREFIX."product_money where create_time=$extdate GROUP BY user_id) and openid<>'' and paylogtime<$extdate");
			if($user_info){
				//$redis ->delete("ext_weixin"); 
				$user_id=intval($user_info['id']);
				$product_money_info=$GLOBALS['db']->getRow("SELECT bname,sum(money/marketInt) as money from ".DB_PREFIX."product_money where user_id=$user_id and create_time=$extdate and money>0 and wxlogint=0 GROUP BY bname");
				//echo "SELECT bname,sum(money) as money from ".DB_PREFIX."product_money where user_id=$user_id and create_time=$extdate and money>0 and wxlogint=0 GROUP BY bname";exit();
				
				if($product_money_info){
					$GLOBALS['db']->query("update ".DB_PREFIX."product_money set wxlogint=1 where user_id=$user_id and bname='".$product_money_info['bname']."' and create_time=$extdate and money>0 ");
					//构造消息模板
					$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$getaccss_token";
					$money=new_round($product_money_info['money'],8);
					$data=[
						"touser"=>$user_info['openid'], //对方的openid，前一步获取
						"template_id"=>"1oeAqYqk02mq6ddtdjTXwFwWKbBhgbOJSBJXX2x0_SE", //模板id
						"url" => 'http://www.frogbt.com/user/order_list',
						"miniprogram"=>["appid"=>"", //跳转小程序appid
						"pagepath"=>"pages/index/index"],//跳转小程序页面
						"data"=>[
							"first"=>[
								"value"=> "收入到帐提醒", //自定义参数
								"color"=> '#173177'//自定义颜色
							],
							"keyword1"=>[
								"value"=> $money, //自定义参数
								"color"=> '#173177'//自定义颜色
							],
							"keyword2"=>[
								"value"=> $product_money_info['bname'], //自定义参数
								"color"=> '#173177'//自定义颜色
							],
							"keyword3"=>[
								"value"=> "全部订单", //自定义参数
								"color"=> '#173177'//自定义颜色
							],
							"keyword4"=>[
								"value"=> date("Y-m-d",time()), //自定义参数
								"color"=> '#173177'//自定义颜色
							],
							"remark"=>[
								"value"=> "点击详情，随时查看订单状态", //自定义参数
								"color"=> '#173177'//自定义颜色
							],
						]
					];		
					
					$data=json_encode($data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$output = curl_exec($ch);
					curl_close($ch);
					$jsoninfo = json_decode($output, true);//转换格式
					//errmsg=>ok
					//return $jsoninfo['errmsg']
					//if($jsoninfo['errmsg']=='ok') echo $jsoninfo['msgid'].'#';
					//echo $jsoninfo['errmsg'].'#';
					echo "User ID:".$user_info['id'].' -> '.$product_money_info['bname'];
				}
				$product_money_info=$GLOBALS['db']->getOne("SELECT count(bname) as c from ".DB_PREFIX."product_money where user_id=$user_id and create_time=$extdate and money>0 and wxlogint=0");
				if(!$product_money_info){
					$GLOBALS['db']->getRow("update ".DB_PREFIX."user set paylogtime='$extdate' where id=$user_id ");
				}
				
			}else{
				//$redis ->set( "ext_weixin" , date("Y-m-d")); 
				echo "执行成功->:".date("Y-m-d h:i:s",time());
				exit();	
			}
			//$kk++;
			//if($kk>30) exit();
			//sleep(1);
		//}
	}
}	
?>
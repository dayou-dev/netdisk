<?php
require APP_ROOT_PATH.'aliyun/aliyun-php-sdk-core/Config.php';
require APP_ROOT_PATH.'vendor/Utils.php';
use afs\Request\V20180112 as Afs;
use app\common\library\helper;
use saf\Request\V20190521 as saf;

class indexModule extends SiteBaseModule
{
	public function index()
	{
		$user_info = es_session::get("user_info");
		$GLOBALS['tmpl']->assign("user_info",$user_info);
		$GLOBALS['tmpl']->display("index.html");
	}
	
	
	public function is_login()
	{
		$user_info = es_session::get("user_info");
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".intval($user_info['id']));
		if(!$user_info){
		  //header("Location:/user/bind_mobile");
		  $return['info']='请登录后在进行操作';
		  $return['status']=0;
		  ajax_return($return);
		}
		return $user_info;
	}
	
	public function file_list()
	{
		$user_info=$this->is_login();
		$user_file = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_file ");
	}
	
	public function login()
	{
		$login_info = es_session::get("user_info");
		$GLOBALS['tmpl']->display("/user/login.html");
	}
	
	public function dologin()
	{
		if(!$_POST)
		{
			$return['info']='参数错误';
			$return['status']=0;
			ajax_return($return);
		}
		foreach($_POST as $k=>$v)
		{
			$_POST[$k] = htmlspecialchars(addslashes($v));
		}
		$ajax = intval($_REQUEST['ajax']);
		
		$email=htmlstrchk($_POST['email']);
		$password=htmlstrchk($_POST['password']);
		if(!$email)
		{				
			$return['info']='请输入您的登录Email';
			$return['status']=0;
			ajax_return($return);
		}			
		if(!$password)
		{				
			$return['info']='请输入您的登录密码';
			$return['status']=0;
			ajax_return($return);
		}			
		
		$result['status']=0;
		$result['info']='';

		if(check_ipop_limit(get_client_ip(),"user_dologin",intval(app_conf("SUBMIT_DELAY")))){
			$user_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where email='".$email."' and is_delete = 0 ");
			if($user_data){
				if($user_data['password']!=md5($password)){
					$return['info']='登录密码输入有误';
					$return['status']=0;
					ajax_return($return);
				}else{
					es_session::set("user_info",$user_data);
				}
			}else{
				$return['info']='当前Email未注册';
				$return['status']=0;
				ajax_return($return);
			}
			es_session::set('mobilecode',"");
			$result['status']=1;
		}
		else{
			showErr($GLOBALS['lang']['SUBMIT_TOO_FAST'],1,url("shop","user#login"));
		}
			
		if($result['status'])
		{	
			$user_data = es_session::get("user_info");
			$expire = time() + 3600*24*30;
			$jump_url = get_gopreview();
			$return['status'] = 1;
			$return['info'] = "登录成功";
			ajax_return($return);
		}
		else
		{
			showErr($result['info'],1);
		}
	}
	
	public function register()
	{
			
		$user_data = $_POST;
		if(!$user_data)
		{
			$GLOBALS['tmpl']->display("user/register.html");	
		}else{
			
			$return['status']=0;
			$return['info']='';
			$account=$user_data['email'];
			$mobilecode = trim($user_data['number']);
			$mobilecode_v=es_session::get('mobilecode');
			$sendmobile=es_session::get('sendmobile');
			
			if(!$account)
			{				
				$return['info']=='请输入手机号码';
				$return['status']=0;
				ajax_return($return);
			}			
			
			if(!$mobilecode)
			{				
				$return['info']='请输入手机验证码';
				$return['status']=0;
				ajax_return($return);
			}
			
			if(!$user_data['password'])
			{				
				$return['info']='请设置登录密码';
				$return['status']=0;
				ajax_return($return);
			}
			
			
			if($mobilecode!=$mobilecode_v||$account!=$sendmobile)
			{				
				$return['info']='邮件验证码输入错误';
				$return['status']=0;
				ajax_return($return);
			}			
			
			/*
			//验证码
			/*$verify = trim($_REQUEST['captcha']);
			$session_verify = es_session::get('verify');
			if(strtolower($verify)!=strtolower($session_verify))
			{				
				$return['Code']="10001";
				$return['info']='验证码错误，请重新输入。';
				$return['status']=0;
				ajax_return($return);
			}*/
			
			
			//开始数据验证
			$user_data['user_name']=htmlstrchk(trim($account));
			$user_data['password']=htmlstrchk(trim($user_data['password']));
			$user_data['commuser']=htmlstrchk(trim($user_data['commuser']));
		
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".trim($user_data['user_name'])."' or mobile= '".trim($user_data['user_name'])."'  or email= '".trim($user_data['user_name'])."'"))
			{
				$result['status'] = 0;
				$result['info'] = "当前登录名已注册，请重新输入.";
				ajax_return($result);
			}
			
			$pid=0;
			if($user_data['commuser']){
				//推荐人
				$pid=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where user_name = '".trim($user_data['commuser'])."'");
				$pid=intval($pid);
			}else{
				//推荐人
				$pid = intval(es_session::get("REFERRAL_USER"))>0?es_session::get("REFERRAL_USER"):0;
			}
				
			$userdata=array();
			$userdata['user_name'] = $user_data['user_name'];
			$userdata['mobile'] = $user_data['mobile'];
			$userdata['password'] = md5($user_data['password']);
			$userdata['email'] = $user_data['email'];
			$userdata['pid'] = $pid;
			$userdata['login_ip'] = get_client_ip();
			$userdata['is_effect'] = 1;
			$userdata['update_time'] = time();
			$userdata['create_time'] = time();
			$userdata['group_id'] = $GLOBALS['db']->getOne("select id from ".DB_PREFIX."user_group order by id asc");
			$GLOBALS['db']->autoExecute(DB_PREFIX."user",$userdata); //插入
			$reid = $GLOBALS['db']->insert_id();
			if($reid){
				//分配钱包
				$payaddr = $GLOBALS['db']->getRow("select id,user_id,plus from ".DB_PREFIX."user_payaddr where user_id=0");
				if(!$payaddr){
						$data= getajaxUrl("http://183.240.209.146:8881/fil.php?act=WalletNew_ftp");
						$redate=json_decode($data);
						$redata=array();		
						$redata['user_name'] = $user_data['user_name'];
						$redata['user_id'] = $reid;
						$redata['payaddr'] = $redate['addr'];
						$redata['plus'] = $this->hex_Tostr($redate['plus']);
						$redata['create_time'] = time();
						$GLOBALS['db']->autoExecute(DB_PREFIX."user_payaddr",$redata); //插入
				}else{
					$GLOBALS['db']->query("update ".DB_PREFIX."user_payaddr set user_id='$reid',user_name='".$user_data['user_name']."' where id=".$payaddr['id']);
				}
				$result['status'] = 1;
				$result['info'] = "";
			}
			ajax_return($result);
		}
	}
	
	private function strToHex($string){
		$hex='';
		for ($i=0; $i < strlen($string); $i++){
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}
		
		
	private function hex_Tostr($string) {
		return hex2bin("$string");
	}	
	
	public function find_password()
	{
		
		if($_POST){
			
			if(check_ipop_limit(get_client_ip(),"user_find_password",intval(app_conf("SUBMIT_DELAY")))){
				$account = htmlstrchk($_REQUEST['account']);
				$password = htmlstrchk($_REQUEST['password']);
				$checkCode = htmlstrchk($_REQUEST['mobilecode']);
				
				if(!$account||!$checkCode||!$password){
					$return['info']='登录名/密码/验证码不能为空';
					$return['status']=0;
					ajax_return($return);
				}
				
				if(!$GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where user_name = '".trim($account)."' or mobile='".trim($account)."' or email='".trim($account)."'"))
				{
					$return['Code']="10003";
					$result['status'] = 0;
					$result['info'] = "登录名未注册，请重新输入";
					ajax_return($result);
				}
				
				//验证码
				$session_mobilecode = es_session::get('mobilecode');
				$sendmobile_verify = es_session::get('sendmobile');
				if($checkCode!=$session_mobilecode || $sendmobile_verify!=$account)
				{
					$result['status'] = 0;
					$result['info'] = '验证码输入错误．';
					ajax_return($result);
				}
				
				$txtPassword=md5($password);
				$user_id=$GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where user_name = '".trim($account)."' or mobile='".trim($account)."' or email='".trim($account)."'");
				$GLOBALS['db']->getRow("update ".DB_PREFIX."user set password='$txtPassword' where id=".intval($user_id));
				$result['status'] = 1;
				$result['info'] = '';
				ajax_return($result);
			
			}else{
				$return['info']=$GLOBALS['lang']['SUBMIT_TOO_FAST'];
				$return['status']=0;
				ajax_return($return);
			}

		}else{
			$GLOBALS['tmpl']->display("user/find_password.html");
		}
	}
	
    //获取手机验证
	public function getRegisterCode($sendobj=0)
	{
		global $deal_city;
		//开始发送验证码
		if(check_ipop_limit(get_client_ip(),"sms_send_code",intval(app_conf("SUBMIT_DELAY"))))
		{
			$mobile = addslashes(trim($_POST['e']));
			$user_info = es_session::get("user_info");
			$verify = intval($_POST['verify']);
			$area = trim($_POST['area']);
			$code = rand(11111,99999);
			$area=$area?$area:'+86';
			
			$sysinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."system");
			$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $sysinfo['ali_access_key'], $sysinfo['ali_access_secret']);
			$client = new DefaultAcsClient($iClientProfile);
			DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", "afs", "afs.aliyuncs.com");
			
			$request = new Afs\AuthenticateSigRequest();
			$request->setSessionId($_POST['SessionId']);// 会话ID。必填参数，从前端获取，不可更改。
			$request->setToken($_POST['Token']);// 请求唯一表示。必填参数，从前端获取，不可更改。
			$request->setSig($_POST['Sig']);// 签名串。必填参数，从前端获取，不可更改。
			$request->setScene("nc_login");// 场景标识。必填参数，从前端获取，不可更改。
			$request->setAppKey("FFFF0N0000000000843D");// 应用类型标识。必填参数，后端填写。
			$request->setRemoteIp(get_client_ip());// 客户端IP。必填参数，后端填写。
			$response = $client->getAcsResponse($request);// 返回code 100表示验签通过，900表示验签失败
	
			if($response->Code!=100){
				$return['Code']="10001";
				$return['info']='滑动验证失败，请重新操作';
				$return['status']=0;
				ajax_return($return);
			}
			
			
			/*$session_verify = es_session::get('verify');
			if(strtolower($verify)!=strtolower($session_verify))
			{				
				$return['info']='图形验证码错误，请重新输入。';
				$return['status']=0;
				ajax_return($return);
			}*/
            $user_info = es_session::get("user_info");
            $user_id = intval($user_info['id']);
            $user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=$user_id");
			if(!strstr($mobile,"@")){
                /*if($user_info['mobile'] && $user_info['mobile'] != $mobile){
                    $result['info'] = "非法获取验证码";
                    $result['status'] = 0;
                    ajax_return($result);
                }*/
				$recode = send_verify_sms($mobile,$code,$area);
				//echo print_r($recode);
				//$recode = 1;
				if($recode['status']){
					if($sendobj){
						es_session::set($sendobj.'mobilecode',$code);
						es_session::set($sendobj.'sendmobile',$mobile);
					}else{
						es_session::set('mobilecode',$code);
						es_session::set('sendmobile',$mobile);
					}
					$result['info'] = $recode['info'];
					$result['status'] = 1;
					ajax_return($result);
				}else{
					if($sendobj){
						es_session::set($sendobj.'mobilecode',$code);
						es_session::set($sendobj.'sendmobile',$mobile);
					}else{
						es_session::set('mobilecode',$code);
						es_session::set('sendmobile',$mobile);
					}
					$result['info'] = $recode['info']?$recode['info']:"验证码获取失败";
					$result['status'] = 0;
					ajax_return($result);
				}	
			}else{
                /*if($user_info['email'] && $user_info['email'] != $mobile){
                    $result['info'] = "非法获取验证码";
                    $result['status'] = 0;
                    ajax_return($result);
                }*/
				es_session::set('mobilecode',$code);
				es_session::set('sendmobile',$mobile);
				
				//邮件验证
				$msg = "尊敬的".$mobile."您好，您的邮件验证码为：<b>".$code."</b>";
				
				//邮件
				require_once APP_ROOT_PATH."system/utils/es_mail.php";
				$mail = new mail_sender();
				$msg_item=$GLOBALS['db']->getRow("select id from ".DB_PREFIX."mail_server where is_effect=1");
				
				if($msg_item){
					$mail->AddAddress($mobile);
					//$mail->AddAddress('lianzhiyou45@163.com');
					//$mail->AddAddress('334047053@163.com');
					$mail->IsHTML(1); 				  // 设置邮件格式为 HTML
					$mail->Subject = "【大有云盘】邮件验证码";   // 标题
					$mail->Body = $msg;  // 内容	
					$results = $mail->Send();
					$msg_item['result'] = $mail->ErrorInfo;
					$msg_item['is_success'] = intval($result);
					$msg_item['send_time'] = get_gmtime();
					//echo print_r($msg_item);
				}
				
				$msg_data['dest'] = $mobile;

				$msg_data['send_type'] = 1;
				$msg_data['title'] = "【大有云盘】邮件验证码";
				$msg_data['content'] = addslashes($msg);;
				$msg_data['send_time'] = 0;
				$msg_data['is_send'] = 1;
				$msg_data['is_success'] = $result?1:0;
				$msg_data['create_time'] = time();
				$msg_data['user_id'] = intval($user_info['id']);
				$msg_data['is_html'] = 1;
				$GLOBALS['db']->autoExecute(DB_PREFIX."sms_list",$msg_data); //插入
			}	
			//$result['info'] = $code;
			$result['info'] ='';
			$result['status'] = 1;
			ajax_return($result);
		}
		else
		{
			$result['status'] = 0;
			$result['code'] = '';
			$result['interval'] = 60;
			$result['info'] = $GLOBALS['lang']['SUBMIT_TOO_FAST'];
			ajax_return($result);
		}
	}
	
    //获取邮件验证
	public function getEmailCode()
	{
			
		$user_info = es_session::get("user_info");
		$user_id=intval($user_info['id']);
		if(!$user_info){
			$result['status'] = 0;
			$result['info'] = '请登录后在进行操作．';
			ajax_return($result);
		}else{
		
			$user_info = es_session::get("user_info");
			$verify = intval($_POST['verify']);
			$code = rand(11111,99999);
			es_session::set('mobilecode',$code);
			es_session::set('sendmobile',$mobile);
			$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = $user_id");
			
			if(!$user_info['email']){
				$result['status'] = 0;
				$result['info'] = '请先绑定邮箱验证在进行操作';
				ajax_return($result);
			}else{
				//邮件验证
				$msg = "尊敬的".$user_info['email']."您好，您的邮件验证码为：<b>".$code."</b>";
				//邮件
				require_once APP_ROOT_PATH."system/utils/es_mail.php";
				$mail = new mail_sender();
				$msg_item=$GLOBALS['db']->getRow("select id from ".DB_PREFIX."mail_server where is_effect=1");
				
				if($msg_item){
					$mail->AddAddress($user_info['email']);
					$mail->IsHTML(1); 				  // 设置邮件格式为 HTML
					$mail->Subject = "【大有云盘】邮件验证码";   // 标题
					$mail->Body = $msg;  // 内容	
					$results = $mail->Send();
					$msg_item['result'] = $mail->ErrorInfo;
					$msg_item['is_success'] = intval($result);
					$msg_item['send_time'] = get_gmtime();
					//echo print_r($msg_item);
				}
			}
			//$result['info'] = $code;
			$result['info'] ='';
			$result['status'] = 1;
			ajax_return($result);
		}
	}
	
	public function account_exist(){
		$email=htmlstrchk($_REQUEST['email']);
		$return['status']=0;
		if($email){
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email= '".$email."'"))
			{
				$return['status']=1;
			}
		}
		ajax_return($return);
	}
	
	public function send_reset_email(){
		$email=htmlstrchk($_REQUEST['email']);
		if($email){
			
			
			if(!check_email($email))
			{
				showErr("邮箱验证失败.",1,"/find_password.html");
			}
			$sysinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."system");
			$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $sysinfo['ali_access_key'], $sysinfo['ali_access_secret']);
			$client = new DefaultAcsClient($iClientProfile);
			DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", "afs", "afs.aliyuncs.com");
			
			$request = new Afs\AuthenticateSigRequest();
			$request->setSessionId($_POST['SessionId']);// 会话ID。必填参数，从前端获取，不可更改。
			$request->setToken($_POST['Token']);// 请求唯一表示。必填参数，从前端获取，不可更改。
			$request->setSig($_POST['Sig']);// 签名串。必填参数，从前端获取，不可更改。
			$request->setScene("nc_login");// 场景标识。必填参数，从前端获取，不可更改。
			$request->setAppKey("FFFF0N0000000000843D");// 应用类型标识。必填参数，后端填写。
			$request->setRemoteIp(get_client_ip());// 客户端IP。必填参数，后端填写。
			$response = $client->getAcsResponse($request);// 返回code 100表示验签通过，900表示验签失败
	
			if($response->Code!=100){
				$return['Code']="10001";
				$return['info']='验证码验证错误，请重新操作';
				$return['status']=0;
				ajax_return($return);
			}
			
			if($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user where email = '".$email."'"))
			{
				//发送邮件
				$code = rand(11111,99999);
				$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where email = '".$email."'");
				$chkcode=md5($email.$code);
				$_SESSION['pawemail']=$chkcode;
				$_SESSION['email']=$email;
				$user_info['verify_url'] = get_domain()."/reset_verify_email.html?s=".$chkcode;
				$msg = "尊敬的".$user_info['user_name']."您好<br/>欢迎您回来~ 请点击下面的连接完成密码重置。<br><a href='".$user_info['verify_url']."'>'".$user_info['verify_url']."'</a>";
				
				//邮件
				require_once APP_ROOT_PATH."system/utils/es_mail.php";
				$mail = new mail_sender();
				$msg_item=$GLOBALS['db']->getRow("select id from ".DB_PREFIX."mail_server where is_effect=1");
				
				if($msg_item){
				
					$mail->AddAddress($user_info['email']);
					$mail->IsHTML(1); 				  // 设置邮件格式为 HTML
					$mail->Subject = "【大有云盘】请重置您的密码";   // 标题
					$mail->Body = $msg;  // 内容	
					$result = $mail->Send();
					
					$msg_item['result'] = $mail->ErrorInfo;
					$msg_item['is_success'] = intval($result);
					$msg_item['send_time'] = get_gmtime();
				}
				
				
				$return['info']='ok';
				$return['status']=1;
				ajax_return($return);
				
				
			}else{
				showErr("邮箱验证失败",1,"/find_password.html");
			}
		}else{
			showErr("邮箱验证失败",1,"/find_password.html");
		}
	}
	
	//验证邮件
	public function reset_verify_email(){
		$code = addslashes(trim($_REQUEST['s']));
		$ajax = intval($_REQUEST['ajax']);
		if($_SESSION['pawemail']&&$code&&$_SESSION['email']){
			if($_SESSION['pawemail']==$code){
				if(!$_POST){
					 $GLOBALS['tmpl']->assign("s",$code);
					 $GLOBALS['tmpl']->display("user/get_verify_email.html");	
				}else{
					$password = htmlstrchk($_REQUEST['password']);
					if(!$password){
						$return['info']='请输入重置密码';
						$return['status']=0;
						ajax_return($return);
					}
					$GLOBALS['db']->query("update ".DB_PREFIX."user set password='".md5($password)."' where email='".$_SESSION['email']."'");
					showSuccess("密码重量成功",$ajax,"/login.html");
				}
			}else{
				showErr("邮件验证失败.",$ajax,"find_password.html");
			}
		}else{
			showErr("邮件验证失败.",$ajax,"/");
		}
	}
	
	
}	
?>
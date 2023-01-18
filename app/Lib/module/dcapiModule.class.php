<?php
class dcapiModule extends SiteBaseModule
{
	public function index() {
		
	}
	
	public function loginUser() {
		$userInfo=$_POST['userInfo'];
		$app_id='wxb64090846d728098';
		$code=$_REQUEST['code'];
		$InitSiteID=htmlstrchk($_POST['InitSiteID']);
		$dataId=htmlstrchk($_POST['dataId']);
		if($code){

			$url='https://api.weixin.qq.com/sns/jscode2session?appid=wxb64090846d728098&secret=8d5fba2f788067ac93fe300f2df56f5e&js_code='.$code.'&grant_type=authorization_code';
			
			//$url="http://www.baidu.com/";
			$UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
			$curl = curl_init();	//创建一个新的CURL资源
			curl_setopt($curl, CURLOPT_URL, $url);	//设置URL和相应的选项
			curl_setopt($curl, CURLOPT_HEADER, 0);  //0表示不输出Header，1表示输出
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	//设定是否显示头信息,1显示，0不显示。
			//如果成功只将结果返回，不自动输出任何内容。如果失败返回FALSE
			 
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_ENCODING, '');	//设置编码格式，为空表示支持所有格式的编码
			//header中"Accept-Encoding: "部分的内容，支持的编码格式为："identity"，"deflate"，"gzip"。
			 
			curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			//设置这个选项为一个非零值(象 "Location: ")的头，服务器会把它当做HTTP头的一部分发送(注意这是递归的，PHP将发送形如 "Location: "的头)。
			 
			$jsonStr = curl_exec($curl); 
			curl_close($curl);	//关闭cURL资源，并释放系统
			
			$arr = json_decode($jsonStr, true);
			$arr['url']=$url;
			$redata=array();
			
			if($arr['session_key']){

			
				$userInfo = $GLOBALS['db']->getRow("select id from ".DB_PREFIX."user_hd where openid='".$arr['openid']."'");
				if(!$userInfo){
					$userdata=array();
					$userdata['openid'] = $arr['openid'];
					$userdata['session_key'] = $arr['session_key'];
					$userdata['login_ip'] = get_client_ip();
					$userdata['login_time'] = time();
					$userdata['create_time'] = time();
			
					$GLOBALS['db']->autoExecute(DB_PREFIX."user_hd",$userdata); //插入
					$reid = $GLOBALS['db']->insert_id();
					
					$userInfo = $GLOBALS['db']->getRow("select id from ".DB_PREFIX."user_hd where openid='".$arr['openid']."'");
				}else{
					$GLOBALS['db']->query("update ".DB_PREFIX."user_hd set session_key='".$arr['session_key']."' where openid='".$arr['openid']."'");
				}		
				
				
				//es_session::set("session_key",$arr['session_key']);
				$redata['success']=true;
				$redata['session_key']=$arr['session_key'];
				if($arr['openid']) $redata['openid']=$arr['openid'];
				$redata['WebUserID']=intval($userInfo['id']);
				$redata['success']=true;
			}else{
				//es_session::set("session_key","");
				$redata['session_key']="";
				$redata['success']=false;
				$redata['msg']=$arr['errmsg'];	
			}
		}else{
			es_session::set("session_key","");
			$redata['success']=false;
		}
		
		ajax_return($redata);
		
	}
	public function searchreturn() {
		$names=addslashes(trim($_REQUEST['names']));
		$redata['reval'] = 1;
		if($names){
			$return=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_return where names='$names'");
			if($return){
				$redata['reval']=intval($return['reval']);
				$GLOBALS['db']->query("update ".DB_PREFIX."user_return set click_count=click_count+1 where names='$names'");
			}else{
				$ranval=rand(1,37);
				$userdata=array();
				$userdata['names'] = $names;
				$userdata['reval'] = $ranval;
				$redata['reval'] = $ranval;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_return",$userdata); //插入
			}
			$redata['success']=true;
			ajax_return($redata);
		}
	}
	public function chklogin() {
		$session_key=addslashes($_REQUEST['third_Session']);
		$nickName=addslashes($_REQUEST['nickName']);
		$avatarUrl=addslashes($_REQUEST['avatarUrl']);
		$gender=addslashes($_REQUEST['gender']);
		$province=addslashes($_REQUEST['province']);
		$city=addslashes($_REQUEST['city']);
		$country=addslashes($_REQUEST['country']);
		if($session_key){
			
			
			//验证登录
			$userInfo=$GLOBALS['db']->getRow("select id from ".DB_PREFIX."user_hd where session_key='$session_key'");
			if($userInfo){
				$reservenum=$GLOBALS['db']->query("update ".DB_PREFIX."user_hd set names='$nickName',avatarUrl='$avatarUrl',sex='$gender',province='$province',city='$city',country='$country' where session_key='$session_key'");
				$redata['msg']='';
				$redata['data']=$session_key;
				$redata['success']=true;
			}else{
				$redata['msg']='未登录';
				$redata['success']=false;
			}
		}else{
			$redata['msg']='未登录';
			$redata['success']=false;
		}
		ajax_return($redata);
	}

}	
?>
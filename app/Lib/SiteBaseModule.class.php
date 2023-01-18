<?php 
// +----------------------------------------------------------------------
// | 中海融通金融服务有限公司
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.yfw.hk All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------

class SiteBaseModule{
	public function __construct()
	{
		$GLOBALS['tmpl']->assign("MODULE_NAME",MODULE_NAME);
		$GLOBALS['tmpl']->assign("ACTION_NAME",ACTION_NAME);
		/*
		$GLOBALS['fcache']->set_dir(APP_ROOT_PATH."public/runtime/data/page_static_cache/");
		$GLOBALS['dynamic_cache'] = $GLOBALS['fcache']->get("APP_DYNAMIC_CACHE_".APP_INDEX."_".MODULE_NAME."_".ACTION_NAME);
		$GLOBALS['fcache']->set_dir(APP_ROOT_PATH."public/runtime/data/avatar_cache/");
		$GLOBALS['dynamic_avatar_cache'] = $GLOBALS['fcache']->get("AVATAR_DYNAMIC_CACHE"); //头像的动态缓存
		*/
		
		
		if(!$thiscity){
			 $thiscity= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."city where is_effect=1 ");
			 $_SESSION['cityid']=$thiscity['id'];
		}
		$GLOBALS['tmpl']->assign("thiscity",$thiscity);
		
		$login_info = es_session::get("user_info");
		//输出页面的标题关键词与描述
		$GLOBALS['tmpl']->assign("site_info",get_site_info());
		$GLOBALS['tmpl']->assign("user_info",$login_info);
		
		if(MODULE_NAME=="acate"&&ACTION_NAME=="index"||
		MODULE_NAME=="article"&&ACTION_NAME=="index"||
		MODULE_NAME=="cate"&&ACTION_NAME=="index"||
		MODULE_NAME=="comment"&&ACTION_NAME=="index"||
		MODULE_NAME=="help"&&ACTION_NAME=="index"||
		MODULE_NAME=="link"&&ACTION_NAME=="index"||
		MODULE_NAME=="mobile"&&ACTION_NAME=="index"||
		MODULE_NAME=="msg"&&ACTION_NAME=="index"||
		MODULE_NAME=="notice"&&ACTION_NAME=="index"||
		MODULE_NAME=="notice"&&ACTION_NAME=="list_notice"||
		MODULE_NAME=="rec"&&ACTION_NAME=="rhot"||
		MODULE_NAME=="rec"&&ACTION_NAME=="rnew"||
		MODULE_NAME=="rec"&&ACTION_NAME=="rbest"||
		MODULE_NAME=="rec"&&ACTION_NAME=="rsale"||
		MODULE_NAME=="score"&&ACTION_NAME=="index"||
		MODULE_NAME=="space"&&ACTION_NAME=="index"||
		MODULE_NAME=="space"&&ACTION_NAME=="fav"||
		MODULE_NAME=="space"&&ACTION_NAME=="fans"||
		MODULE_NAME=="space"&&ACTION_NAME=="focus"||
		MODULE_NAME=="msg"&&ACTION_NAME=="index"||
		MODULE_NAME=="ss"&&ACTION_NAME=="index"||
		MODULE_NAME=="ss"&&ACTION_NAME=="pick"||
		MODULE_NAME=="sys"&&ACTION_NAME=="index"||
		MODULE_NAME=="sys"&&ACTION_NAME=="list_notice"||
		MODULE_NAME=="vote"&&ACTION_NAME=="index")
		{
			set_gopreview();
		}

		
	}

	public function index()
	{
		 app_redirect("404.html");
		 exit();
		//showErr("invalid access");
	}
	
	public function getcitys(){
			$url="http://ip.ws.126.net/ipquery?ip=".get_client_ip();
			//echo $url;
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array());
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSLVERSION, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			$content = curl_exec($curl);
			
			
			curl_close($curl);
			return $content;
			
			/*
			$content = explode("localAddress",$content );
			
			echo print_r($content);exit();
			
			$content = str_replace(";","",$content );
			$redata = json_decode($content);
			return $redata;
			*/
			//curl_close($curl);
			//echo print_r($redata);
			//return $redata->numArray[0];

		
	}
	
	/*
	public function __destruct()
	{
		if(isset($GLOBALS['fcache']))
		{
			$GLOBALS['fcache']->set_dir(APP_ROOT_PATH."public/runtime/data/page_static_cache/");
			$GLOBALS['fcache']->set("APP_DYNAMIC_CACHE_".APP_INDEX."_".MODULE_NAME."_".ACTION_NAME,$GLOBALS['dynamic_cache']);
			if(count($GLOBALS['dynamic_avatar_cache'])<=500)
			{
				$GLOBALS['fcache']->set_dir(APP_ROOT_PATH."public/runtime/data/avatar_cache/");
				$GLOBALS['fcache']->set("AVATAR_DYNAMIC_CACHE",$GLOBALS['dynamic_avatar_cache']); //头像的动态缓存
			}
		}
		unset($this);
	}*/
}
?>
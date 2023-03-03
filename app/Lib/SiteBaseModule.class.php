<?php 
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
		
		
		$sysinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."system");
		$login_info = es_session::get("user_info");
		//输出页面的标题关键词与描述
		$GLOBALS['tmpl']->assign("site_info",get_site_info());
		$GLOBALS['tmpl']->assign("user_info",$login_info);
		$GLOBALS['tmpl']->assign("sysinfo",$sysinfo);
		
		if(MODULE_NAME=="acate"&&ACTION_NAME=="index"||
		MODULE_NAME=="article"&&ACTION_NAME=="index"||
		MODULE_NAME=="cate"&&ACTION_NAME=="index"||
		MODULE_NAME=="comment"&&ACTION_NAME=="index"||
		MODULE_NAME=="help"&&ACTION_NAME=="index"||
		MODULE_NAME=="link"&&ACTION_NAME=="index"||
		MODULE_NAME=="mobile"&&ACTION_NAME=="index"||
		MODULE_NAME=="msg"&&ACTION_NAME=="index"||
		MODULE_NAME=="notice"&&ACTION_NAME=="index"||
		MODULE_NAME=="msg"&&ACTION_NAME=="index"||
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
	

}
?>
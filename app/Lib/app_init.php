<?php
require_once 'common.php';
filter_injection($_REQUEST);

if(!file_exists(APP_ROOT_PATH.'public/runtime/app/'))
{
	mkdir(APP_ROOT_PATH.'public/runtime/app/',0777);
}
$GLOBALS['tmpl']->assign("site_info",get_site_info());

//输出根路径
$GLOBALS['tmpl']->assign("APP_ROOT",APP_ROOT);

//保存返利的cookie
if($_REQUEST['r'])
{
	$rid = intval(base64_decode($_REQUEST['r']));
	$ref_uid = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where id = ".intval($rid)));
	es_session::set("REFERRAL_USER",intval($ref_uid));
	//setcookie("REFERRAL_USER",intval($ref_uid));
}
else
{
	//获取存在的推荐人ID
	if(intval(es_cookie::get("REFERRAL_USER"))>0)
	$ref_uid = intval($GLOBALS['db']->getOne("select id from ".DB_PREFIX."user where id = ".intval(es_cookie::get("REFERRAL_USER"))));
}


//保存来路
if(!es_cookie::get("referer_url"))
{	
	if(!preg_match("/".urlencode(get_domain().APP_ROOT)."/",urlencode($_SERVER["HTTP_REFERER"])))
	es_cookie::set("referer_url",$_SERVER["HTTP_REFERER"]);
}
$referer = es_cookie::get("referer_url");

?>
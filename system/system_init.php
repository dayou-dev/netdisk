<?php 
// +----------------------------------------------------------------------
// | Fanwe 方维o2o商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------
//ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//error_reporting(0);

if(!defined('IS_CGI'))
define('IS_CGI',substr(PHP_SAPI, 0,3)=='cgi' ? 1 : 0 );
 if(!defined('_PHP_FILE_')) {
        if(IS_CGI) {
            //CGI/FASTCGI模式下
            $_temp  = explode('.php',$_SERVER["PHP_SELF"]);
            define('_PHP_FILE_',  rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
        }else {
            define('_PHP_FILE_',  rtrim($_SERVER["SCRIPT_NAME"],'/'));
        }
    }
if(!defined('APP_ROOT')) {
        // 网站URL根目录
        $_root = dirname(_PHP_FILE_);
        $_root = (($_root=='/' || $_root=='\\')?'':$_root);
        $_root = str_replace("/system","",$_root);
        define('APP_ROOT', $_root  );
}
if(!defined('APP_ROOT_PATH')) 
define('APP_ROOT_PATH', str_replace('system/system_init.php', '', str_replace('\\', '/', __FILE__)));
define("MAX_DYNAMIC_CACHE_SIZE",1000);  //动态缓存最数量

//定义$_SERVER['REQUEST_URI']兼容性
if (!isset($_SERVER['REQUEST_URI']))
{
		if (isset($_SERVER['argv']))
		{
			$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
		}
		else
		{
			$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
		}
		$_SERVER['REQUEST_URI'] = $uri;
}
filter_request($_GET);
filter_request($_POST);

//关于安装的检测
if(!file_exists(APP_ROOT_PATH."public/install.lock"))
{
	app_redirect(APP_ROOT."/install/index.php");
}

//引入数据库的系统配置及定义配置函数
//update_sys_config();
require APP_ROOT_PATH.'system/db/db.php';
$sys_config = update_sys_config();
function app_conf($name)
{
	return stripslashes($GLOBALS['sys_config'][$name]);
}
define('lanint', app_conf('lanint')); 
//end 引入数据库的系统配置及定义配置函数

require APP_ROOT_PATH.'system/utils/es_cookie.php';
require APP_ROOT_PATH.'system/utils/es_session.php';
es_session::start();

function get_http()
{
	return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
}
function get_domain()
{
	/* 协议 */
	$protocol = get_http();

	/* 域名或IP地址 */
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
	{
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	elseif (isset($_SERVER['HTTP_HOST']))
	{
		$host = $_SERVER['HTTP_HOST'];
	}
	else
	{
		/* 端口 */
		if (isset($_SERVER['SERVER_PORT']))
		{
			$port = ':' . $_SERVER['SERVER_PORT'];

			if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
			{
				$port = '';
			}
		}
		else
		{
			$port = '';
		}

		if (isset($_SERVER['SERVER_NAME']))
		{
			$host = $_SERVER['SERVER_NAME'] . $port;
		}
		elseif (isset($_SERVER['SERVER_ADDR']))
		{
			$host = $_SERVER['SERVER_ADDR'] . $port;
		}
	}

	return $protocol . $host;
}
function get_host()
{


	/* 域名或IP地址 */
	if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
	{
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	elseif (isset($_SERVER['HTTP_HOST']))
	{
		$host = $_SERVER['HTTP_HOST'];
	}
	else
	{
		if (isset($_SERVER['SERVER_NAME']))
		{
			$host = $_SERVER['SERVER_NAME'];
		}
		elseif (isset($_SERVER['SERVER_ADDR']))
		{
			$host = $_SERVER['SERVER_ADDR'];
		}
	}
	return $host;
}


//引入时区配置及定义时间函数
if(function_exists('date_default_timezone_set'))
	date_default_timezone_set(app_conf('DEFAULT_TIMEZONE'));
//end 引入时区配置及定义时间函数

//定义缓存
require APP_ROOT_PATH.'system/cache/Cache.php';
$cache = CacheService::getInstance();
require_once APP_ROOT_PATH."system/cache/CacheFileService.php";
$fcache = new CacheFileService();  //专用于保存静态数据的缓存实例
$fcache->set_dir(APP_ROOT_PATH."public/runtime/data/");
//end 定义缓存

//定义DB
define('DB_PREFIX', app_conf('DB_PREFIX')); 

if(!file_exists(APP_ROOT_PATH.'public/runtime/app/db_caches/'))
	mkdir(APP_ROOT_PATH.'public/runtime/app/db_caches/',0777);
$pconnect = false;
$db = new mysql_db(app_conf('DB_HOST').":".app_conf('DB_PORT'), app_conf('DB_USER'),app_conf('DB_PWD'),app_conf('DB_NAME'),'utf8',$pconnect);
//end 定义DB

//更新过期订单
//$db->query("update ".DB_PREFIX."order set order_status=4 where order_status=0 and create_time<".(time()-24*60*60));

//定义模板引擎
require  APP_ROOT_PATH.'system/template/template.php';
if(!file_exists(APP_ROOT_PATH.'public/runtime/app/tpl_caches/'))
	mkdir(APP_ROOT_PATH.'public/runtime/app/tpl_caches/',0777);	
if(!file_exists(APP_ROOT_PATH.'public/runtime/app/tpl_compiled/'))
	mkdir(APP_ROOT_PATH.'public/runtime/app/tpl_compiled/',0777);
$tmpl = new AppTemplate;

//end 定义模板引擎
$_REQUEST = array_merge($_GET,$_POST);
filter_request($_REQUEST);
$lang = require APP_ROOT_PATH.'/app/Lang/'.app_conf("SHOP_LANG").'/lang.php';

?>
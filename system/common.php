<?php
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.pz.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------

//前后台加载的函数库
require_once 'system_init.php';
//获取真实路径
function get_real_path()
{
	return APP_ROOT_PATH;
}

function time_tran($the_time) {
    $now_time = date("Y-m-d H:i:s", time());
    $now_time = strtotime($now_time);
    $show_time = intval($the_time);
    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    } else {
        if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    if ($dur < 259200) {//3天内
                        return floor($dur / 86400) . '天前';
                    } else {
                        return "3天前";
                    }
                }
            }
        }
    }
}

/**
 * 技术两个日期差几个月
 */
function how_much_month_i($start_time,$end_time){
	if($start_time=="" || $end_time=="")
	{
		return "";
	}
	$time1 = to_date($start_time,"Y")*12 + to_date($start_time,"m");
	$time2 = to_date($end_time,"Y")*12 + to_date($end_time,"m");

	return $time2 - $time1;
}


//获取GMTime
function get_gmtime()
{
	return (time());
}

function to_date($utc_time, $format = 'Y-m-d H:i:s') {
	if (empty ( $utc_time )) {
		return '';
	}
	$timezone = intval(app_conf('TIME_ZONE'));
	$time = $utc_time + $timezone * 3600;
	//return date ($format, $time );
	return date ($format, $utc_time );
}

function to_timespan($str, $format = 'Y-m-d H:i:s')
{
	$timezone = intval(app_conf('TIME_ZONE'));
	//$timezone = 8;
	$time = intval(strtotime($str));
	if($time!=0)
	$time = $time - $timezone * 3600;
    return $time;
}


//获取客户端IP
function get_client_ip() {
	if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
		$ip = getenv ( "HTTP_CLIENT_IP" );
	else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
		$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
	else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
		$ip = getenv ( "REMOTE_ADDR" );
	else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
		$ip = $_SERVER ['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return ($ip);
}

//过滤注入
function filter_injection(&$request)
{
	$pattern = "/(select[\s])|(insert[\s])|(update[\s])|(delete[\s])|(from[\s])|(where[\s])/i";
	foreach($request as $k=>$v)
	{
				if(preg_match($pattern,$k,$match))
				{
						die("SQL Injection denied!");
				}

				if(is_array($v))
				{
					filter_injection($v);
				}
				else
				{

					if(preg_match($pattern,$v,$match))
					{
						die("SQL Injection denied!");
					}
				}
	}

}

//过滤请求
function filter_request(&$request)
{
		//$c=(!get_magic_quotes_gpc())?addslashes($c):$c;
		if(get_magic_quotes_gpc())
		{
			foreach($request as $k=>$v)
			{
				if(is_array($v))
				{
					filter_request($v);
				}
				else
				{
					$request[$k] = stripslashes(trim($v));
				}
			}
		}

}

function adddeepslashes(&$request)
{

			foreach($request as $k=>$v)
			{
				if(is_array($v))
				{
					adddeepslashes($v);
				}
				else
				{
					$request[$k] = addslashes(trim($v));
				}
			}
}

//request转码
function convert_req(&$req)
{
	foreach($req as $k=>$v)
	{
		if(is_array($v))
		{
			convert_req($req[$k]);
		}
		else
		{
			if(!is_u8($v))
			{
				$req[$k] = iconv("gbk","utf-8",$v);
			}
		}
	}
}

function is_u8($string)
{
	return preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);
}

//清除缓存
function clear_cache()
{
	  //数据缓存
	  clear_dir_file(get_real_path()."public/runtime/app/data_caches/");
	  clear_dir_file(get_real_path()."public/runtime/app/db_caches/");
	  clear_dir_file(get_real_path()."public/runtime/admin/data_caches/");
	  clear_dir_file(get_real_path()."public/runtime/admin/db_caches/");
	  $GLOBALS['cache']->clear();
	  clear_dir_file(get_real_path()."public/runtime/data/");

	  //模板页面缓存
	  clear_dir_file(get_real_path()."public/runtime/app/tpl_caches/");
	  clear_dir_file(get_real_path()."public/runtime/app/tpl_compiled/");
	  @unlink(get_real_path()."public/runtime/app/lang.js");
	  clear_dir_file(get_real_path()."public/runtime/admin/tpl_caches/");
	  clear_dir_file(get_real_path()."public/runtime/admin/tpl_compiled/");
	  @unlink(get_real_path()."public/runtime/admin/lang.js");

	  //脚本缓存
	  clear_dir_file(get_real_path()."public/runtime/statics/");

}
function clear_dir_file($path)
{
   if ( $dir = opendir( $path ) )
   {
            while ( $file = readdir( $dir ) )
            {
                $check = is_dir( $path. $file );
                if ( !$check )
                {
                    @unlink( $path . $file );
                }
                else
                {
                 	if($file!='.'&&$file!='..')
                 	{
                 		clear_dir_file($path.$file."/");
                 	}
                 }
            }
            closedir( $dir );
            rmdir($path);
            return true;
   }
}



function check_install()
{
	if(!file_exists(get_real_path()."public/install.lock"))
	{
	    clear_cache();
		header('Location:'.APP_ROOT.'/install');
		exit;
	}
}




//发短信验证码
function send_verify_sms($mobile,$code,$user_info='')
{
	require_once APP_ROOT_PATH."system/utils/es_sms.php";
	$sms = new sms_sender();
	$smschk=$GLOBALS['db']->getOne("select class_name from ".DB_PREFIX."sms where is_effect = 1");
	/*$newCode=$smschk!='WYY'?1:0;
	if(app_conf("SMS_ON")==1)
	{*/



		  $tmpl_content = "您的验证码是：".$code."。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
		 // if($tmpl_content){
//			  $verify['mobile'] = $mobile;
//			  $verify['code'] = $code;
//			  $GLOBALS['tmpl']->assign("verify",$verify);
			  $msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
			  $msg_data['dest'] = $mobile;
			  $msg_data['send_type'] = 0;
			  $msg_data['title'] = '验证码提示';
			  $msg_data['content'] = addslashes($msg);
			  $msg_data['send_time'] = time();
			  $msg_data['is_send'] = 1;
			  $msg_data['is_success'] = 1;
			  $msg_data['create_time'] = time();
			  $msg_data['user_id'] = 0;
			  $msg_data['is_html'] = 0;
			  $GLOBALS['db']->autoExecute(DB_PREFIX."sms_list",$msg_data); //插入	不需要
			  $sms_return=$sms->sendSms($mobile,$tmpl_content);
			 // if($sms_return['code']=='200'){
				 //$newCode= $sms_return['obj'];
			 // }
			 //$newCode=8888;
		  //}
	/*}*/
	return $sms_return;
}

//发送运营短信验证码
function send_yunying_sms($mobile,$tmpl_content,$params = array())
{
	require_once APP_ROOT_PATH."system/utils/es_sms.php";
	$sms = new sms_sender();
	if(app_conf("SMS_ON")==1)
	{
		return $sms_return=$sms->sendSms($mobile,$tmpl_content,$params);
	}else{
		$redata['SMS_ON']='Off';
		return $redata;
	}
}


//发邮件退订验证
function send_unsubscribe_mail($email)
{
	if(app_conf("MAIL_ON")==1)
	{
		if($email)
		{
			$GLOBALS['db']->query("update ".DB_PREFIX."mail_list set code = '".rand(1111,9999)."' where mail_address='".$email."' and code = ''");
			$email_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."mail_list where mail_address = '".$email."' and code <> ''");
			if($email_item)
			{
				$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_MAIL_UNSUBSCRIBE'");
				$tmpl_content = $tmpl['content'];
				$mail = $email_item;
				$mail['url'] = get_domain().url("index","subscribe#dounsubscribe", array("code"=>base64_encode($mail['code']."|".$mail['mail_address'])));
				$GLOBALS['tmpl']->assign("mail",$mail);
				$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
				$msg_data['dest'] = $mail['mail_address'];
				$msg_data['send_type'] = 1;
				$msg_data['title'] = $GLOBALS['lang']['MAIL_UNSUBSCRIBE'];
				$msg_data['content'] = addslashes($msg);;
				$msg_data['send_time'] = 0;
				$msg_data['is_send'] = 0;
				$msg_data['create_time'] = get_gmtime();
				$msg_data['user_id'] = 0;
				$msg_data['is_html'] = $tmpl['is_html'];
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入
			}
		}
	}
}


function format_price($price)
{
	return app_conf("CURRENCY_UNIT")."".number_format($price,2);
}
function format_score($score)
{
	return intval($score)."".app_conf("SCORE_UNIT");
}


//utf8 字符串截取
function msubstr($str, $start=0, $length=15, $charset="utf-8", $suffix=true)
{
	if(function_exists("mb_substr"))
    {
        $slice =  mb_substr($str, $start, $length, $charset);
        if($suffix&$slice!=$str) return $slice."…";
    	return $slice;
    }
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix&&$slice!=$str) return $slice."…";
    return $slice;
}

 /**
  * PHP获取字符串中英文混合长度
  * @param $str string 字符串
  * @param $$charset string 编码
  * @return 返回长度，1中文=1位，2英文=1位
  */
  function strLength($str,$charset='utf-8'){
  	if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);
    $num = strlen($str);
    $cnNum = 0;
    for($i=0;$i<$num;$i++){
        if(ord(substr($str,$i+1,1))>127){
            $cnNum++;
            $i++;
        }
    }
    $enNum = $num-($cnNum*2);
    $number = ($enNum/2)+$cnNum;
    return ceil($number);
 }


//字符编码转换
if(!function_exists("iconv"))
{
	function iconv($in_charset,$out_charset,$str)
	{
		require 'libs/iconv.php';
		$chinese = new Chinese();
		return $chinese->Convert($in_charset,$out_charset,$str);
	}
}

//JSON兼容
if(!function_exists("json_encode"))
{
	function json_encode($data)
	{
		require_once 'libs/json.php';
		$JSON = new JSON();
		return $JSON->encode($data);
	}
}
if(!function_exists("json_decode"))
{
	function json_decode($data)
	{
		require_once 'libs/json.php';
		$JSON = new JSON();
		return $JSON->decode($data,1);
	}
}

//邮件格式验证的函数
function check_email($email)
{
	if(!preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/",$email))
	{
		return false;
	}
	else
	return true;
}

//验证手机号码
function check_mobile($mobile)
{
	if(!empty($mobile) && !preg_match("/^\d{6,}$/",$mobile))
	{
		return false;
	}
	else
	return true;
}

//跳转
function app_redirect($url,$time=0,$msg='')
{
    //多行URL地址支持
    $url = str_replace(array("\n", "\r"), '', $url);
    if(empty($msg))
        $msg    =   "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if(0===$time) {
        	if(substr($url,0,1)=="/")
        	{
        		header("Location:".get_domain().$url);
        	}
        	else
        	{
        		header("Location:".$url);
        	}

        }else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    }else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if($time!=0)
            $str   .=   $msg;
        exit($str);
    }
}



/**
 * 验证访问IP的有效性
 * @param ip地址 $ip_str
 * @param 访问页面 $module
 * @param 时间间隔 $time_span
 * @param 数据ID $id
 */
function check_ipop_limit($ip_str,$module,$time_span=0,$id=0)
{
		$op = es_session::get($module."_".$id."_ip");
    	if(empty($op))
    	{
    		$check['ip']	=	 get_client_ip();
    		$check['time']	=	get_gmtime();
    		es_session::set($module."_".$id."_ip",$check);
    		return true;  //不存在session时验证通过
    	}
    	else
    	{
    		$check['ip']	=	 get_client_ip();
    		$check['time']	=	get_gmtime();
    		$origin	=	es_session::get($module."_".$id."_ip");

    		if($check['ip']==$origin['ip'])
    		{
    			if($check['time'] - $origin['time'] < $time_span)
    			{
    				return false;
    			}
    			else
    			{
    				es_session::set($module."_".$id."_ip",$check);
    				return true;  //不存在session时验证通过
    			}
    		}
    		else
    		{
    			es_session::set($module."_".$id."_ip",$check);
    			return true;  //不存在session时验证通过
    		}
    	}
    }

/**
 * $bond.sn
 * $bond.password
 * $bond.name
 * $bond.user_name
 * $bond.begin_time_format
 * $bond.end_time_format
 * $bond.tel
 * $bond.address
 * $bond.route
 * $bond.open_time
 * @param $coupon_id
 * @param $location_id
 */


function gzip_out($content)
{
	header("Content-type: text/html; charset=utf-8");
    header("Cache-control: private");  //支持页面回跳
	$gzip = app_conf("GZIP_ON");
	if( intval($gzip)==1 )
	{
		if(!headers_sent()&&extension_loaded("zlib")&&preg_match("/gzip/i",$_SERVER["HTTP_ACCEPT_ENCODING"]))
		{
			$content = gzencode($content,9);
			header("Content-Encoding: gzip");
			header("Content-Length: ".strlen($content));
			echo $content;
		}
		else
		echo $content;
	}else{
		echo $content;
	}

}

function order_log($log_info,$order_id)
{
	$data['id'] = 0;
	$data['log_info'] = $log_info;
	$data['log_time'] = get_gmtime();
	$data['order_id'] = $order_id;
	$GLOBALS['db']->autoExecute(DB_PREFIX."deal_order_log", $data);
}


/**
	 * 保存图片
	 * @param array $upd_file  即上传的$_FILES数组
	 * @param array $key $_FILES 中的键名 为空则保存 $_FILES 中的所有图片
	 * @param string $dir 保存到的目录
	 * @param array $whs
	 	可生成多个缩略图
		数组 参数1 为宽度，
			 参数2为高度，
			 参数3为处理方式:0(缩放,默认)，1(剪裁)，
			 参数4为是否水印 默认为 0(不生成水印)
	 	array(
			'thumb1'=>array(300,300,0,0),
			'thumb2'=>array(100,100,0,0),
			'origin'=>array(0,0,0,0),  宽与高为0为直接上传
			...
		)，
	 * @param array $is_water 原图是否水印
	 * @return array
	 	array(
			'key'=>array(
				'name'=>图片名称，
				'url'=>原图web路径，
				'path'=>原图物理路径，
				有略图时
				'thumb'=>array(
					'thumb1'=>array('url'=>web路径,'path'=>物理路径),
					'thumb2'=>array('url'=>web路径,'path'=>物理路径),
					...
				)
			)
			....
		)
	 */
//$img = save_image_upload($_FILES,'avatar','temp',array('avatar'=>array(300,300,1,1)),1);
function save_image_upload($upd_file, $key='',$dir='temp', $whs=array(),$is_water=false,$need_return = false)
{
		require_once APP_ROOT_PATH."system/utils/es_imagecls.php";
		$image = new es_imagecls();
		$image->max_size = intval(app_conf("MAX_IMAGE_SIZE"));

		$list = array();

		if(empty($key))
		{
			foreach($upd_file as $fkey=>$file)
			{
				$list[$fkey] = false;
				$image->init($file,$dir);
				if($image->save())
				{
					$list[$fkey] = array();
					$list[$fkey]['url'] = $image->file['target'];
					$list[$fkey]['path'] = $image->file['local_target'];
					$list[$fkey]['name'] = $image->file['prefix'];
				}
				else
				{
					if($image->error_code==-105)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'上传的图片太大');
						}
						else
						echo "上传的图片太大";
					}
					elseif($image->error_code==-104||$image->error_code==-103||$image->error_code==-102||$image->error_code==-101)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'非法图像');
						}
						else
						echo "非法图像";
					}
					exit;
				}
			}
		}
		else
		{
			$list[$key] = false;
			$image->init($upd_file[$key],$dir);
			if($image->save())
			{
				$list[$key] = array();
				$list[$key]['url'] = $image->file['target'];
				$list[$key]['path'] = $image->file['local_target'];
				$list[$key]['name'] = $image->file['prefix'];
			}
			else
				{
					if($image->error_code==-105)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'上传的图片太大');
						}
						else
						echo "上传的图片太大";
					}
					elseif($image->error_code==-104||$image->error_code==-103||$image->error_code==-102||$image->error_code==-101)
					{
						if($need_return)
						{
							return array('error'=>1,'message'=>'非法图像');
						}
						else
						echo "非法图像";
					}
					exit;
				}
		}

		$water_image = APP_ROOT_PATH.app_conf("WATER_MARK");
		$alpha = app_conf("WATER_ALPHA");
		$place = app_conf("WATER_POSITION");

		foreach($list as $lkey=>$item)
		{
				//循环生成规格图
				foreach($whs as $tkey=>$wh)
				{
					$list[$lkey]['thumb'][$tkey]['url'] = false;
					$list[$lkey]['thumb'][$tkey]['path'] = false;
					if($wh[0] > 0 || $wh[1] > 0)  //有宽高度
					{
						$thumb_type = isset($wh[2]) ? intval($wh[2]) : 0;  //剪裁还是缩放， 0缩放 1剪裁
						if($thumb = $image->thumb($item['path'],$wh[0],$wh[1],$thumb_type))
						{
							$list[$lkey]['thumb'][$tkey]['url'] = $thumb['url'];
							$list[$lkey]['thumb'][$tkey]['path'] = $thumb['path'];
							if(isset($wh[3]) && intval($wh[3]) > 0)//需要水印
							{
								$paths = pathinfo($list[$lkey]['thumb'][$tkey]['path']);
								$path = $paths['dirname'];
				        		$path = $path."/origin/";
				        		if (!is_dir($path)) {
						             @mkdir($path);
						             @chmod($path, 0777);
					   			}
				        		$filename = $paths['basename'];
								@file_put_contents($path.$filename,@file_get_contents($list[$lkey]['thumb'][$tkey]['path']));
								$image->water($list[$lkey]['thumb'][$tkey]['path'],$water_image,$alpha, $place);
							}
						}
					}
				}
			if($is_water)
			{
				$paths = pathinfo($item['path']);
				$path = $paths['dirname'];
        		$path = $path."/origin/";
        		if (!is_dir($path)) {
		             @mkdir($path);
		             @chmod($path, 0777);
	   			}
        		$filename = $paths['basename'];
				@file_put_contents($path.$filename,@file_get_contents($item['path']));
				$image->water($item['path'],$water_image,$alpha, $place);
			}
		}
		return $list;
}

function empty_tag($string)
{
	$string = preg_replace(array("/\[img\]\d+\[\/img\]/","/\[[^\]]+\]/"),array("",""),$string);
	if(trim($string)=='')
	return $GLOBALS['lang']['ONLY_IMG'];
	else
	return $string;
	//$string = str_replace(array("[img]","[/img]"),array("",""),$string);
}

//验证是否有非法字汇，未完成
function valid_str($string)
{
	$string = msubstr($string,0,5000);
	if(app_conf("FILTER_WORD")!='')
	$string = preg_replace("/".app_conf("FILTER_WORD")."/","*",$string);
	return $string;
}


/**
 * utf8字符转Unicode字符
 * @param string $char 要转换的单字符
 * @return void
 */
function utf8_to_unicode($char)
{
	switch(strlen($char))
	{
		case 1:
			return ord($char);
		case 2:
			$n = (ord($char[0]) & 0x3f) << 6;
			$n += ord($char[1]) & 0x3f;
			return $n;
		case 3:
			$n = (ord($char[0]) & 0x1f) << 12;
			$n += (ord($char[1]) & 0x3f) << 6;
			$n += ord($char[2]) & 0x3f;
			return $n;
		case 4:
			$n = (ord($char[0]) & 0x0f) << 18;
			$n += (ord($char[1]) & 0x3f) << 12;
			$n += (ord($char[2]) & 0x3f) << 6;
			$n += ord($char[3]) & 0x3f;
			return $n;
	}
}

/**
 * utf8字符串分隔为unicode字符串
 * @param string $str 要转换的字符串
 * @param string $depart 分隔,默认为空格为单字
 * @return string
 */
function str_to_unicode_word($str,$depart=' ')
{
	$arr = array();
	$str_len = mb_strlen($str,'utf-8');
	for($i = 0;$i < $str_len;$i++)
	{
		$s = mb_substr($str,$i,1,'utf-8');
		if($s != ' ' && $s != '　')
		{
			$arr[] = 'ux'.utf8_to_unicode($s);
		}
	}
	return implode($depart,$arr);
}


/**
 * utf8字符串分隔为unicode字符串
 * @param string $str 要转换的字符串
 * @return string
 */
function str_to_unicode_string($str)
{
	$string = str_to_unicode_word($str,'');
	return $string;
}


//封装url
function url($app_index,$route="index",$param=array())
{
	$key = md5("URL_KEY_".$app_index.$route.serialize($param));
	if(isset($GLOBALS[$key]))
	{
		$url = $GLOBALS[$key];
		return $url;
	}

	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}

	$show_city = intval($GLOBALS['city_count'])>1?true:false;  //有多个城市时显示城市名称到url
	$route_array = explode("#",$route);

	if(isset($param)&&$param!=''&&!is_array($param))
	{
		$param['id'] = $param;
	}

	$module = strtolower(trim($route_array[0]));
	$action = strtolower(trim($route_array[1]));

	if(!$module||$module=='index')$module="";
	if(!$action||$action=='index')$action="";

	if(app_conf("URL_MODEL")==0)
	{
		//过滤主要的应用url
		if($app_index==app_conf("MAIN_APP"))
		$app_index = "index";

		//原始模式
		$url = APP_ROOT."/".$app_index.".php";
		if($module!=''||$action!=''||count($param)>0||$show_city) //有后缀参数
		{
			$url.="?";
		}

		if(isset($param['city']))
		{
			$url .= "city=".$param['city']."&";
			unset($param['city']);
		}
		if($module&&$module!='')
		$url .= "ctl=".$module."&";
		if($action&&$action!='')
		$url .= "act=".$action."&";
		if(count($param)>0)
		{
			foreach($param as $k=>$v)
			{
				if($k&&$v)
				$url =$url.$k."=".urlencode($v)."&";
			}
		}
		if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}
	else
	{
		//重写的默认
		$url = APP_ROOT;

		if($app_index!='index')
		$url .= "/".$app_index;

		if($module&&$module!='')
		$url .= "/".$module;
		if($action&&$action!='')
		$url .= "/".$action;

		if(count($param)>0)
		{
			$url.="/?";
			foreach($param as $k=>$v)
			{
				if($k!='city')
				$url =$url.$k."=".urlencode($v)."&";
			}
		}

		//echo $url;
		//exit();
		//过滤主要的应用url
		if($app_index==app_conf("MAIN_APP"))
		$url = str_replace("/".app_conf("MAIN_APP"),"",$url);

		$route = $module."#".$action;
		switch ($route)
		{
				case "xxx":
					break;
				default:
					break;
		}

		if(substr($url,-1,1)=='/'||substr($url,-1,1)=='-') $url = substr($url,0,-1);



		if(isset($param['city']))
		{
			$city_uname = $param['city'];
			if($city_uname=="all")
			{
				return "http://www.".app_conf("DOMAIN_ROOT").$url."/city-all";
			}
			else
				{
				$domain = "http://".$city_uname.".".app_conf("DOMAIN_ROOT");
				return $domain.$url;
			}
		}
		if($url=='')$url="/";
		$GLOBALS[$key] = $url;
		set_dynamic_cache($key,$url);
		return $url;
	}


}


function unicode_encode($name) {//to Unicode
    $name = iconv('UTF-8', 'UCS-2', $name);
    $len = strlen($name);
    $str = '';
    for($i = 0; $i < $len - 1; $i = $i + 2) {
        $c = $name[$i];
        $c2 = $name[$i + 1];
        if (ord($c) > 0) {// 两个字节的字
            $cn_word = '\\'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
            $str .= strtoupper($cn_word);
        } else {
            $str .= $c2;
        }
    }
    return $str;
}

function unicode_decode($name) {//Unicode to
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (!empty($matches)) {
        $name = '';
        for ($j = 0; $j < count($matches[0]); $j++) {
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0) {
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code).chr($code2);
                $c = iconv('UCS-2', 'UTF-8', $c);
                $name .= $c;
            } else {
                $name .= $str;
            }
        }
    }
    return $name;
}



//载入动态缓存数据
function load_dynamic_cache($name)
{
	if(isset($GLOBALS['dynamic_cache'][$name]))
	{
		return $GLOBALS['dynamic_cache'][$name];
	}
	else
	{
		return false;
	}
}

function set_dynamic_cache($name,$value)
{
	if(!isset($GLOBALS['dynamic_cache'][$name]))
	{
		if(count($GLOBALS['dynamic_cache'])>MAX_DYNAMIC_CACHE_SIZE)
		{
			array_shift($GLOBALS['dynamic_cache']);
		}
		$GLOBALS['dynamic_cache'][$name] = $value;
	}
}


function load_auto_cache($key,$param=array())
{
	require_once APP_ROOT_PATH."system/libs/auto_cache.php";
	$file =  APP_ROOT_PATH."system/auto_cache/".$key.".auto_cache.php";
	if(file_exists($file))
	{
		require_once $file;
		$class = $key."_auto_cache";
		$obj = new $class;
		$result = $obj->load($param);
	}
	else
	$result = false;
	return $result;
}

function rm_auto_cache($key,$param=array())
{
	require_once APP_ROOT_PATH."system/libs/auto_cache.php";
	$file =  APP_ROOT_PATH."system/auto_cache/".$key.".auto_cache.php";
	if(file_exists($file))
	{
		require_once $file;
		$class = $key."_auto_cache";
		$obj = new $class;
		$obj->rm($param);
	}
}


function clear_auto_cache($key)
{
	require_once APP_ROOT_PATH."system/libs/auto_cache.php";
	$file =  APP_ROOT_PATH."system/auto_cache/".$key.".auto_cache.php";
	if(file_exists($file))
	{
		require_once $file;
		$class = $key."_auto_cache";
		$obj = new $class;
		$obj->clear_all();
	}
}

/*ajax返回*/
function ajax_return($data)
{
		header("Content-Type:application/json; charset=utf-8");
        echo(json_encode($data));
        exit;
}

function update_sys_config()
{
		//require APP_ROOT_PATH.'system/db/db.php';
		$dbcfg = require APP_ROOT_PATH."public/db_config.php";
		$lan=$dbcfg['DB_PREFIX'];
		$lanint=0;
		if(strstr($_SERVER['PHP_SELF'],'admin')){
			if($_SESSION['lan']){
				$lanint=1;
			}
		}else{
			if($_COOKIE['lan']=='en'){
				 $lanint=1;
			}
		}

		if(!file_exists(APP_ROOT_PATH.'public/runtime/app/db_caches/'))
			mkdir(APP_ROOT_PATH.'public/runtime/app/db_caches/',0777);
		$pconnect = false;

		$dbs = new mysql_db($dbcfg['DB_HOST'].":".$dbcfg['DB_PORT'], $dbcfg['DB_USER'],$dbcfg['DB_PWD'],$dbcfg['DB_NAME'],'utf8',$pconnect);
		//end 定义DB

		$sys_configs = $dbs->getAll("select * from ".$dbcfg['DB_PREFIX']."system where language_id=$lanint");
		foreach($dbcfg as $key=>$rows){
			$sys_configs[0][$key]=$rows;
		}
		$new_configs=array();
		foreach($sys_configs[0] as $key=>$rows){
			$new_configs[$key]=$rows;
		}
		$new_configs['lanint']=$lanint;
		if($lanint){
			$new_configs['TEMPLATE']="en";
		}
		return $new_configs;

		/*$config_str = "<?php\n";
		$config_str .= "return array(\n";
		foreach($sys_configs as $k=>$v)
		{
			$config_str.="'".$v['name']."'=>'".addslashes($v['value'])."',\n";
		}
		$config_str.=");\n ?>";
		file_put_contents($filename,$config_str);
		$url = APP_ROOT."/";
		app_redirect($url);*/

}


function filter_ctl_act_req($str){
	$search = array("../","\n","\r","\t","\r\n","'","<",">","\"");

	return str_replace($search,"",$str);
}

//身份证验证
function validation_filter_id_card($id_card)
{
	if(strlen($id_card) == 18)
	{
	return idcard_checksum18($id_card);
	}
	elseif((strlen($id_card) == 15))
	{
	$id_card = idcard_15to18($id_card);
	return idcard_checksum18($id_card);
	}
	else
	{
	return false;
	}
	}
	// 计算身份证校验码，根据国家标准GB 11643-1999
	function idcard_verify_number($idcard_base)
	{
	if(strlen($idcard_base) != 17)
	{
	return false;
	}
	//加权因子
	$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
	//校验码对应值
	$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
	$checksum = 0;
	for ($i = 0; $i < strlen($idcard_base); $i++)
	{
	$checksum += substr($idcard_base, $i, 1) * $factor[$i];
	}
	$mod = $checksum % 11;
	$verify_number = $verify_number_list[$mod];
	return $verify_number;
}

// 将15位身份证升级到18位
function idcard_15to18($idcard){
	if (strlen($idcard) != 15){
	return false;
	}else{
	// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
	if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
	$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
	}else{
	$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
	}
	}
	$idcard = $idcard . idcard_verify_number($idcard);
	return $idcard;
}

// 18位身份证校验码有效性检查
function idcard_checksum18($idcard){
	if (strlen($idcard) != 18){ return false; }
	$idcard_base = substr($idcard, 0, 17);
	if (idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
	return false;
	}else{
	return true;
	}
}

function strim($str)
{
	return quotes(htmlspecialchars(trim($str)));
}
function btrim($str)
{
	return quotes(trim($str));
}
function quotes($content)
{
	//if $content is an array
	if (is_array($content))
	{
		foreach ($content as $key=>$value)
		{
			//$content[$key] = mysql_real_escape_string($value);
			$content[$key] = addslashes($value);
		}
	} else
	{
		//if $content is not an array
		$content = addslashes($content);
		//mysql_real_escape_string($content);
	}
	return $content;
}


function trade_GetUrl($url)
{
	$url=str_replace('\\','',$url);
	$lines_string=file_get_contents($url);
	//$lines_string=mb_convert_encoding($lines_string,'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
	$lines_string = iconv("gb2312", "utf-8//IGNORE",$lines_string);
	return $lines_string;
}

//自定义分页程序
function thispage($sql,$init,$Page_size,$page_len,$where='')//1,2,7
{
	$result=$GLOBALS['db']->query(str_replace("select * from","select id from",$sql));
	$count = $GLOBALS['db']->num_rows($result);
	$page_count = ceil($count/$Page_size);

	$max_p=$page_count;
	$pages=$page_count;

	//判断当前页码
	if(empty($_GET['page'])||$_GET['page']<0){
		$page=1;
	}else {
		$page=$_GET['page'];
	}
	//echo basename($_SERVER['QUERY_STRING']);
	//exit();
	$offset=$Page_size*($page-1);
	$sql=$sql." limit $offset,$Page_size";
	$result=$GLOBALS['db']->query($sql);
	foreach ($GLOBALS['db']->fetch_array($result) as $keys=>$rows)
	{
		$List[$keys]= $rows;
	}


	$pageoffset = intval(($page_len-1)/2);//页码个数左右偏移量

	$geturl=$_GET['ctl']?$_GET['ctl']:'index';
	//$geturl=$_GET['act']?$_GET['ctl'].'/'.$_GET['act']:$geturl;

	$php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
	if($php_self=='kj_admin.php'){
		$geturl=$_GET['act']?$_GET['ctl'].'&act='.$_GET['act']:$geturl;
	}else{
		$geturl=$_GET['act']?$_GET['ctl'].'/'.$_GET['act']:$geturl;
	}

	if($page!=1){
		//$key.="<li><a href=\"/".$geturl."/?page=1".$where."\">第一页</a></li>"; //上一页
		$key.="<li><a href=\"?ctl=".$geturl."&page=".($page-1).$where."\"><</a></li>"; //上一页
	}else {
		//$key.='<a class="no">第一页</a>';//第一页
		$key.='<li class="disabled"><a href="javascript:;"><</a></li>'; //上一页
	}
	if($pages>$page_len){
		//如果当前页小于等于左偏移
		if($page<=$pageoffset){
			$init=1;
			$max_p = $page_len;
		}else{//如果当前页大于左偏移
			//如果当前页码右偏移超出最大分页数
			if($page+$pageoffset>=$pages+1){
			$init = $pages-$page_len+1;
		}else{
			//左右偏移都存在时的计算
			$init = $page-$pageoffset;
			$max_p = $page+$pageoffset;
		}
	}
	}

	if($max_p>10){
		if($page <5){
			$init=1;
			$max_p = 10;
		}else if($page >5&&$page+5<$max_p){
			$init=$page-5;
			$max_p = $page+5;
		}else if($page >5&&$page+5>$max_p){
			$init=$max_p-10;
			$max_p = $max_p;
		}
	}

	for($i=$init;$i<=$max_p;$i++){
		if($i==$page){
			$key.='<li class="active"><a class="hover">'.$i.'</a></li>';
		} else {
			$key.="<li><a href=\"?ctl=".$geturl."&page=".($i).$where."\">".$i."</a></li>";
		}
	}

	if($page!=$pages&&$pages>0){
		$key.="<li><a href=\"?ctl=".$geturl."&page=".($page+1).$where."\">></a></li>";//下一页
		//$key.="<a href=\"/".$geturl."/?page=".$page_count.$where."\">最后一页</a>";//下一页
	}else {
		$key.='<li class="disabled"><a href="javascript:;">></a></li>';//第一页
		//$key.='<a class="no">最后一页</a>';//第一页
	}
	$key.=$keytxt; //第几页,共几页
	if($count<1) $key="";

	$retunr_arr['list']=$List;
	$retunr_arr['pagecount']=$page_count;
	$retunr_arr['rowscount']=$count;
	$retunr_arr['pagetxt']=$page;
	$retunr_arr['pages']=$key;
	return $retunr_arr;

}


//自定义分页程序
function thispage1($sql,$init,$Page_size,$page_len,$where)//1,2,7
{
	$result=$GLOBALS['db']->query(str_replace("select * from","select id from",$sql));
	$count = $GLOBALS['db']->num_rows($result);
	$page_count = ceil($count/$Page_size);

	$max_p=$page_count;
	$pages=$page_count;

	//判断当前页码
	if(empty($_GET['page'])||$_GET['page']<0){
		$page=1;
	}else {
		$page=$_GET['page'];
	}
	//echo basename($_SERVER['QUERY_STRING']);
	//exit();
	$offset=$Page_size*($page-1);
	$sql=$sql." limit $offset,$Page_size";
	$result=$GLOBALS['db']->query($sql);
	foreach ($GLOBALS['db']->fetch_array($result) as $keys=>$rows)
	{
		$List[$keys]= $rows;
	}


	$pageoffset = intval(($page_len-1)/2);//页码个数左右偏移量

	$geturl=$_GET['ctl']?$_GET['ctl']:'index';
	$geturl=$_GET['act']?$_GET['ctl'].'&act='.$_GET['act']:$geturl;

	//单独UEL处理
	if($_GET['ctl']=='notice'&&$_GET['act']=='list_notice') $geturl="newslist-".$_GET['id'];
	if($_GET['ctl']=='news'&&$_GET['act']=='city') $geturl="city-".$_GET['m'];

	if($page!=1){
		$key.="<a href=\"/".$geturl."/?page=1".$where."\"><<</a>"; //上一页
		$key.="<a href=\"/".$geturl."/?page=".($page-1).$where."\"><</a>"; //上一页
	}else {
		$key.='<a class="no"><<</a>';//第一页
		$key.='<a href="javascript:;"><</a>'; //上一页
	}
	if($pages>$page_len){
		//如果当前页小于等于左偏移
		if($page<=$pageoffset){
			$init=1;
			$max_p = $page_len;
		}else{//如果当前页大于左偏移
			//如果当前页码右偏移超出最大分页数
			if($page+$pageoffset>=$pages+1){
			$init = $pages-$page_len+1;
		}else{
			//左右偏移都存在时的计算
			$init = $page-$pageoffset;
			$max_p = $page+$pageoffset;
		}
	}
	}

	if($max_p>10){
		if($page <5){
			$init=1;
			$max_p = 10;
		}else if($page >5&&$page+5<$max_p){
			$init=$page-5;
			$max_p = $page+5;
		}else if($page >5&&$page+5>$max_p){
			$init=$max_p-10;
			$max_p = $max_p;
		}
	}

	for($i=$init;$i<=$max_p;$i++){
		if($i==$page){
			$key.='<a class="pagenum">'.$i.'</a>';
		} else {
			$key.="<a href=\"/".$geturl."/?page=".($i).$where."\">".$i."</a>";
		}
	}

	if($page!=$pages&&$pages>0){
		$key.="<a href=\"/".$geturl."/?page=".($page+1).$where."\">></a>";//下一页
		$key.="<a href=\"/".$geturl."/?page=".$page_count.$where."\">>></a>";//下一页
	}else {
		$key.='<a href="javascript:;">></a>';//第一页
		$key.='<a class="no">>></a>';//第一页
	}
	$key.=$keytxt; //第几页,共几页
	if($count<1) $key="";

	$retunr_arr['list']=$List;
	$retunr_arr['pagecount']=$page_count;
	$retunr_arr['rowscount']=$count;
	$retunr_arr['pagetxt']=$page;
	$retunr_arr['pages']=$key;
	return $retunr_arr;

}

//获取远程文件信息
function get_url($url) {
	if(isset($url)){
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

		$htmldata = curl_exec($curl);
		curl_close($curl);	//关闭cURL资源，并释放系统
		return $htmldata;
	}
}


//发注册验证邮件
function send_user_verify_mail($user_id)
{
	if(app_conf("MAIL_ON")==1)
	{
		$verify_code = rand(111111,999999);
		$GLOBALS['db']->query("update ".DB_PREFIX."user set verify = '".$verify_code."' where id = ".$user_id);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		if($user_info)
		{
			$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_MAIL_USER_VERIFY'");
			$tmpl_content=  $tmpl['content'];
			$user_info['verify_url'] = get_domain()."/account/bidverify/?id=".$user_info['id']."&code=".$user_info['verify'];
			$GLOBALS['tmpl']->assign("user",$user_info);
			$msg1="尊敬的用户您的验证码是【$verify_code】，此验证码只能用来注册。";
			$msg = "尊敬的".$user_info['user_name']."您好<br/>请点击下方链接完成邮件验证。<br><a href='".$user_info['verify_url']."'>'".$user_info['verify_url']."'</a>";

			//邮件
			require_once APP_ROOT_PATH."system/utils/es_mail.php";
			$mail = new mail_sender();
			$msg_item=$GLOBALS['db']->getRow("select id from ".DB_PREFIX."mail_server where is_effect=1");

			if($msg_item){

				$mail->AddAddress($user_info['email']);
				$mail->IsHTML(1); 				  // 设置邮件格式为 HTML
				$mail->Subject = "【比特动力】注册邮件验证";   // 标题
				$mail->Body = $msg;  // 内容
				$result = $mail->Send();

				$msg_item['result'] = $mail->ErrorInfo;
				$msg_item['is_success'] = intval($result);
				$msg_item['send_time'] = get_gmtime();

				if($result)
				{
					$msg_data['dest'] = $user_info['email'];
					$msg_data['send_type'] = 1;
					$msg_data['title'] = "【比特动力】注册邮件验证";
					$msg_data['content'] = addslashes($msg);;
					$msg_data['send_time'] = 0;
					$msg_data['is_send'] = 1;
					$msg_data['is_success'] = 1;
					$msg_data['create_time'] = get_gmtime();
					$msg_data['user_id'] = $user_info['id'];
					$msg_data['is_html'] = 1;
					$GLOBALS['db']->autoExecute(DB_PREFIX."sms_list",$msg_data); //插入
					header("Content-Type:text/html; charset=utf-8");
					//echo "发送成功";
				}
				else
				{
					$msg_data['dest'] = $user_info['email'];
					$msg_data['send_type'] = 1;
					$msg_data['title'] = "【比特动力】注册邮件验证";
					$msg_data['content'] = addslashes($msg);;
					$msg_data['send_time'] = 0;
					$msg_data['is_send'] = 1;
					$msg_data['is_success'] = 0;
					$msg_data['create_time'] = get_gmtime();
					$msg_data['user_id'] = $user_info['id'];
					$msg_data['is_html'] = 1;
					$GLOBALS['db']->autoExecute(DB_PREFIX."sms_list",$msg_data); //插入
					header("Content-Type:text/html; charset=utf-8");
					//echo "发送失败".$mail->ErrorInfo;
				}
			}
		}
	}
}


//发密码验证邮件
function send_user_password_mail($user_id)
{
	if(app_conf("MAIL_ON")==1)
	{
		$verify_code = rand(111111,999999);
		$GLOBALS['db']->query("update ".DB_PREFIX."user set password_verify = '".$verify_code."' where id = ".$user_id);
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		if($user_info)
		{
			$tmpl = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."msg_template where name = 'TPL_MAIL_USER_PASSWORD'");
			$tmpl_content=  $tmpl['content'];
			$user_info['password_url'] = get_domain().url("index","user#modify_password", array("code"=>$user_info['password_verify'],"id"=>$user_info['id']));
			$GLOBALS['tmpl']->assign("user",$user_info);
			$msg = $GLOBALS['tmpl']->fetch("str:".$tmpl_content);
			$msg_data['dest'] = $user_info['email'];
			$msg_data['send_type'] = 1;
			$msg_data['title'] = $GLOBALS['lang']['RESET_PASSWORD'];
			$msg_data['content'] = addslashes($msg);
			$msg_data['send_time'] = 0;
			$msg_data['is_send'] = 0;
			$msg_data['create_time'] = get_gmtime();
			$msg_data['user_id'] = $user_info['id'];
			$msg_data['is_html'] = $tmpl['is_html'];
			$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入
		}
	}
}

function htmlstrchk($str){
   if($str){
		$str=strtolower($str);
		$str=str_replace("'","",$str);
		$str=str_replace(";","",$str);
		$str=str_replace("<","",$str);
		$str=str_replace(">","",$str);
		$str=str_replace("script","",$str);
		//$str=str_replace("http","",$str);
		//$str=str_replace("https","",$str);
   }
   return $str;
}

//字符过虑
function htmlstr_rel($str){
   $chk=0;
   $retrun="";
   if($str){
	  if(strstr(strtolower($str),"script")) $chk++;
	  if(!$chk){
		  $search = array("'","<",">",";","\"");
		  $retrun=str_replace($search,"",$str);
	  }
   }
   return $retrun;
}


//模拟post提交
function submitPost($urlmodel,$data='')
{
	$url='http://www.ddd.com/index/'.$urlmodel;
	$row = parse_url($url);
	$host = $row['host'];
	$port = $row['port'] ? $row['port']:80;
	$file = $row['path'];
	$data['chkcode'] = '@343$5SSSfakc*()@';
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

/**
* 排除周六周日和节假日
* @param $start       开始日期
* @param $offset      经过天数
* @return
*  examples:输入(2010-06-25,5),得到2010-07-02
*/
function getendday($start='now', $offset=0){
	//例外的节假日
	/*$exception = array(
		date("Y").'-01-01',date("Y").'-01-02',date("Y").'-01-03',
		date("Y").'-04-05',date("Y").'-04-06',date("Y").'-04-07',
		date("Y").'-04-30',date("Y").'-05-01',date("Y").'-06-18',
		date("Y").'-09-24',date("Y").'-10-01',date("Y").'-10-02',date("Y").'-10-03',date("Y").'-10-04',
		date("Y").'-10-05',date("Y").'-10-06',date("Y").'-10-07',

	);*/
   $exception=explode(",",app_conf("TRADE_NOTDEALDATE"));
    //先计算不排除周六周日及节假日的结果
    $starttime = $start;
    $endtime = $starttime + ($offset * 24 * 3600);
    $end = date('Y-m-d', $endtime);
	$appydata = $offset;
	$i=0;
	while($appydata > 0){
		$tday = $starttime + ($i * 24 * 3600);
		if(date('w',$tday)==6||date('w',$tday)==0||in_array(date("Y-m-d",$tday),$exception)) $appydata++;
		$endtime = $tday;

		$appydata--;
		$i++;
	}
    return $endtime;
}
/**
* 当天是否交易日
* @param $start       开始日期
* @param $offset      经过天数
* @return
*  examples:输入(2010-06-25,5),得到2010-07-02
*/
function getuseday($start){
	$exception=explode(",",app_conf("TRADE_NOTDEALDATE"));
	$tday=$start?$start:time();
	$endtime=0;
	if(date('w',$tday)==6||date('w',$tday)==0) $endtime++;
	if(!$endtime){
		foreach($exception as $rows){
			if(date("Y-m-d",strtotime($rows))==date("Y-m-d")) $endtime++;
		}
	}
    return $endtime;
}

//账户余额
function get_user_money($id){
	if($id){
		$mCount1 = $GLOBALS['db']->getOne("select sum(money) as c from ".DB_PREFIX."user_money where mtype=0 and user_id=".intval($id));
		$mCount2 = $GLOBALS['db']->getOne("select sum(money) as c from ".DB_PREFIX."user_money where mtype=1 and user_id=".intval($id));
		$mCount3 = $GLOBALS['db']->getOne("select sum(money) as c from ".DB_PREFIX."user_carry where `status`=0 and user_id=".intval($id));
		$remoney=$mCount1-$mCount2-$mCount3;
		return round($remoney,2);
	}else{
		return 0;
	}
}

//账户积分余额
function get_user_integral($id){
	if($id){
		$mCount1 = $GLOBALS['db']->getOne("select sum(money) as c from ".DB_PREFIX."user_integral where mtype=0 and user_id=".intval($id));
		$mCount2 = $GLOBALS['db']->getOne("select sum(money) as c from ".DB_PREFIX."user_integral where mtype=1 and user_id=".intval($id));
		$remoney=$mCount1-$mCount2-$mCount3;
		return round($remoney,2);
	}else{
		return 0;
	}
}

//推荐返利(0订单1会员卡)
function comm_fanli($oid,$remoney,$type=0){
	//推荐返利
	$oid=intval($oid);
	$systemInfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."system where id=1");
	if($systemInfo['shareInt']&&$systemInfo['shareday']){

		if($type){
			$orderInfo=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_card where id=$oid");
			$str_type="开卡返佣";
		}else{
			$orderInfo=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."order where id=$oid");
			$str_type="订单返佣";
		}
		$user_id=intval($orderInfo['user_id']);
		$etime=date("Y-m-d 00:00:00",time()-(3600*24*$systemInfo['shareday']));
		$etime=strtotime($etime);
		$commUserInfo=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where pid>0 and id = $user_id and create_time>$etime");
		if($commUserInfo){
			$u_pid=intval($commUserInfo['pid']);
			$commUser=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = $u_pid" );
			$pic_chk=$GLOBALS['db']->getOne("SELECT id FROM ".DB_PREFIX."user_money where user_id=$u_pid and mtype=0 and deal_id=$oid and info='$str_type'");
			if(!$pic_chk){
				$re_u_chk=$GLOBALS['db']->getOne("SELECT id FROM ".DB_PREFIX."user_money where user_id=$u_pid and mtype=0 and info='$str_type'");
				if($re_u_chk) $GLOBALS['db']->query("update ".DB_PREFIX."user set u_level_count=u_level_count+1 where id=$u_pid");

				$user_lv = $GLOBALS['db']->getOne("SELECT u_level_count FROM ".DB_PREFIX."user where id=$u_pid");
				$user_lv = $user_lv?$user_lv:0;
				$user_lv_s="";
				$user_share = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_share order by sort asc ,id desc");
				$user_lv_id = intval($user_share[0]['id']);
				foreach($user_share as $key=>$rows){
					if($rows['is_where']){
						if($user_lv>$rows['c_total']) $user_lv_id = $rows['id'];
					}
				}
				$user_lv_id=$user_lv_id?$user_lv_id:intval($user_share[0]['id']);
				$GLOBALS['db']->query("update ".DB_PREFIX."user set u_level=$user_lv_id where id=$u_pid");


				foreach($user_share as $key=>$rows){
					if($rows['id']==$user_lv_id){
						//一级返利
						$re_money=round($remoney*($rows['c_lv1']/100),2);
						if($re_money){
							//资金记录
							$userdata=array();
							$fxcCount = get_user_money(intval($commUser['user_id']));
							$ymoney = $fxcCount+$re_money;
							$userdata['user_name'] = $commUser['user_name'];
							$userdata['user_id'] = $commUser['id'];
							$userdata['money'] = $re_money;
							$userdata['ymoney'] = $ymoney;
							$userdata['mtype'] = 0;
							$userdata['objid'] = $user_id;
							$userdata['deal_id'] = $oid;
							$userdata['info'] = $str_type;
							$userdata['admin_id'] = 0;
							$userdata['create_time'] = time();
							$GLOBALS['db']->autoExecute(DB_PREFIX."user_money",$userdata); //插入

							//二级返利
							if($commUser['pid']){
								$lv2_uid=intval($commUser['pid']);
								$lv2_user=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id =$lv2_uid " );
								//$re_money=round($re_money*($rows['c_lv2']/100),2);
								$re_money=round($remoney*($rows['c_lv2']/100),2);
								if($re_money){
									$userdata=array();
									$fxcCount = get_user_money(intval($lv2_user['user_id']));
									$ymoney = $fxcCount+$re_money;
									$userdata['user_name'] = $lv2_user['user_name'];
									$userdata['user_id'] = $lv2_user['id'];
									$userdata['money'] = $re_money;
									$userdata['ymoney'] = $ymoney;
									$userdata['mtype'] = 0;
									$userdata['objid'] = $user_id;
									$userdata['deal_id'] = $oid;
									$userdata['info'] = $str_type;
									$userdata['admin_id'] = 0;
									$userdata['create_time'] = time();
									$GLOBALS['db']->autoExecute(DB_PREFIX."user_money",$userdata); //插入
								}
							}
						}

					}
				}

			}
		}
	}
}


//续费返利
function income_fanli($oid,$remoney){
	$oid=intval($oid);
	$user_id=$GLOBALS['db']->getOne("select user_id from ".DB_PREFIX."order_renew where id=$oid");
	$user_id=intval($user_id);
	$systemInfo = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."system where id=1");
	if($systemInfo['shareInt']&&$systemInfo['shareday']){
		$etime=date("Y-m-d 00:00:00",time()-(3600*24*$systemInfo['shareday']));
		$etime=strtotime($etime);
		$commUserInfo=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where pid>0 and id = $user_id and create_time>$etime");
		$u_pid=intval($commUserInfo['pid']);
		$pic_chk=$GLOBALS['db']->getOne("SELECT id FROM ".DB_PREFIX."user_money where user_id=$u_pid and mtype=0 and deal_id=$oid and info='续费返佣'");
		if($commUserInfo&&!$pic_chk){

			$commUser=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = $u_pid" );
			$user_lv = intval($commUser['u_level']);
			$user_lv = $user_lv?$user_lv:0;
			$user_share = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_share order by sort asc ,id desc");
			$user_lv_id = $user_lv?$user_lv:intval($user_share[0]['id']);
			$user_lv_id=$user_lv_id?$user_lv_id:0;

			foreach($user_share as $key=>$rows){
				if($rows['id']==$user_lv_id){
					$re_money=round($remoney*($rows['c_rate']/100),2);
					if($re_money<0.01) $re_money=0.01;
					if($re_money){
						//资金记录
						$userdata=array();
						$fxcCount = get_user_money(intval($commUser['id']));
						$ymoney = $fxcCount+$re_money;
						$userdata['user_name'] = $commUser['user_name'];
						$userdata['user_id'] = $commUser['id'];
						$userdata['money'] = $re_money;
						$userdata['ymoney'] = $ymoney;
						$userdata['mtype'] = 0;
						$userdata['objid'] = $user_id;
						$userdata['deal_id'] = $oid;
						$userdata['info'] = "续费返佣";
						$userdata['admin_id'] = 0;
						$userdata['create_time'] = time();
						$GLOBALS['db']->autoExecute(DB_PREFIX."user_money",$userdata); //插入
					}
				}
			}
		}
	}
}


function getaccss_token(){
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

function send_order_msg($id,$data=array()){
		$systemInfo=$GLOBALS['db']->getRow("SELECT * from ".DB_PREFIX."system where id=1");
		$getaccss_token=getaccss_token();
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$getaccss_token";
		if(!$systemInfo['wxlogInt']){
			$jsoninfo['errmsg']='no';
			return $jsoninfo;
		}
		$user_id = intval($data['user_id']);
		$order_id = intval($data['order_id']);
		$id = intval($id);
		$sms_log=$GLOBALS['db']->getOne("SELECT id from ".DB_PREFIX."sms_log where msg_id=$id and user_id=$user_id and order_id=$order_id and addtime>".strtotime(date("Y-m-d 00:00:01")));
		$sms_tmp=$GLOBALS['db']->getRow("SELECT * from ".DB_PREFIX."sms_tmp where id=$id");
		if($sms_tmp&&!$sms_log&&$data['openid']){

		  $userdata=array();
		  $userdata['user_id'] = intval($data['user_id']);
		  $userdata['order_id'] = intval($data['order_id']);
		  $userdata['msg_id'] = intval($id);
		  $userdata['addtime'] = time();
		  $GLOBALS['db']->autoExecute(DB_PREFIX."sms_log",$userdata); //插入


		  $tmpdata=array();
		  $tmpdata['first']=array("value"=> send_msg_tag($sms_tmp['first']),"color"=> '#173177');
		  if($sms_tmp['keyword1']) $tmpdata['keyword1']=array("value"=> send_msg_tag($sms_tmp['keyword1']),"color"=> '#173177');
		  if($sms_tmp['keyword2']) $tmpdata['keyword2']=array("value"=> send_msg_tag($sms_tmp['keyword2']),"color"=> '#173177');
		  if($sms_tmp['keyword3']) $tmpdata['keyword3']=array("value"=> send_msg_tag($sms_tmp['keyword3']),"color"=> '#173177');
		  if($sms_tmp['keyword4']) $tmpdata['keyword4']=array("value"=> send_msg_tag($sms_tmp['keyword4']),"color"=> '#173177');
		  if($sms_tmp['keyword5']) $tmpdata['keyword5']=array("value"=> send_msg_tag($sms_tmp['keyword5']),"color"=> '#173177');
		  $tmpdata['remark']=array("value"=> send_msg_tag($sms_tmp['remark']),"color"=> '#173177');

		  $data=[
			  "touser"=>$data['openid'], //对方的openid，前一步获取
			  "template_id"=>$sms_tmp['template_id'], //模板id
			  "url" => send_msg_tag($sms_tmp['linkurl']),
			  "miniprogram"=>["appid"=>"", //跳转小程序appid
			  "pagepath"=>"pages/index/index"],//跳转小程序页面
			  "data"=>$tmpdata
		  ];
		 // echo print_r($data);exit();
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
		  //echo "User ID:".$user_info['id'].' -> '.$product_money_info['bname'];
		  return $jsoninfo;
		}

}

function send_msg_tag($str,$data){
	$new_str=$str;
	$new_str=str_replace("{会员账号}",$data['user_name'],$new_str);
	$new_str=str_replace("{姓名}",$data['names'],$new_str);
	$new_str=str_replace("{会员ID}",$data['user_id'],$new_str);
	$new_str=str_replace("{订单ID}",$data['order_id'],$new_str);
	$new_str=str_replace("{订单编号}",$data['order_nu'],$new_str);
	return $new_str;
}


function addr_distance($data){
	$lng = $data['lng'];
	$lat = $data['lat'];
	$lng1 = $data['lng1'];
	$lat1 = $data['lat1'];
	$sql = "select ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-$lat1*PI()/180)/2),2)+COS($lat*PI()/180)*COS($lat1*PI()/180)*POW(SIN(($lng*PI()/180-$lng1*PI()/180)/2),2)))*1000) AS distance";
	$distance = $GLOBALS['db']->getOne($sql);
	$distance = round($distance?$distance/1000:0,2);
	return $distance;
}
?>

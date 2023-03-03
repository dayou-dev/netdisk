<?php
//app项目用到的函数库

/**
 * 获取页面的标题，关键词与描述
 */
function get_site_info()
{
	$shop_info	= $GLOBALS['db']->getRow("select * from ".DB_PREFIX."system ");;
	return $shop_info;
}
function wx_autologin(){
	//微信自动登录
	$is_weixin=strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger');
	//if($is_weixin!==false){
		$user_info = es_session::get("user_info");
		$wxloginInt = es_session::get("wxloginInt");
		if(!$user_info){
			$system =  $GLOBALS['db']->getRow("select * from ".DB_PREFIX."system ");
			$appid=$system['APPID'];
			$appsecret=$system['AppSecret'];
			$redirect_uri=str_replace("accbind","autologin",$system['SHOP_URL']);
			header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirec");
		}else{
			header("Location:/user");
		}
	//}
}
//Excel导出
function response_excel($data,$expname='simple')
{
		$directory = APP_ROOT_PATH."webadmin/PHPExcel/";
		include $directory.'PHPExcel.php';
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
	
		  $ExceNu="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T";
		  $ExceNu=explode(",",$ExceNu);
		  foreach($data as $key=>$rows){
			  // Add some data
			  $kk=0;
			  foreach($rows as $key1=>$listdata){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ExceNu[$kk].($key+1), $listdata);
				$kk++;
			  }
		  }
		  
		  // Rename worksheet
		  $objPHPExcel->getActiveSheet()->setTitle('Simple');
		  
		  // Set active sheet index to the first sheet, so Excel opens this as the first sheet
		  $objPHPExcel->setActiveSheetIndex(0);
		  
		  // Redirect output to a client's web browser (Excel5)
		  header('Content-Type: application/vnd.ms-excel');
		  header('Content-Disposition: attachment;filename="'.$expname.'.xls"');
		  header('Cache-Control: max-age=0');
		  // If you're serving to IE 9, then the following may be needed
		  header('Cache-Control: max-age=1');
		  
		  // If you're serving to IE over SSL, then the following may be needed
		  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		  header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		  header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		  header ('Pragma: public'); // HTTP/1.0
		  
		  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  $objWriter->save('php://output');
		  exit;
}


//获取所有子集的类
class ChildIds
{
	public function __construct($tb_name)
	{
		$this->tb_name = $tb_name;	
	}
	private $tb_name;
	private $childIds;
	private function _getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid')
	{
		$childItem_arr = $GLOBALS['db']->getAll("select id from ".DB_PREFIX.$this->tb_name." where ".$pid_str."=".intval($pid));
		if($childItem_arr)
		{
			foreach($childItem_arr as $childItem)
			{
				$this->childIds[] = $childItem[$pk_str];
				$this->_getChildIds($childItem[$pk_str],$pk_str,$pid_str);
			}
		}
	}
	public function getChildIds($pid = '0', $pk_str='id' , $pid_str ='pid')
	{
		$this->childIds = array();
		$this->_getChildIds($pid,$pk_str,$pid_str);
		return $this->childIds;
	}
}

//显示错误
function showErr($msg,$ajax=0,$jump='',$stay=0)
{
	if($ajax==1)
	{
		$result['status'] = 0;
		$result['info'] = $msg;
		$result['jump'] = $jump;
		header("Content-Type:text/html; charset=utf-8");
        echo(json_encode($result));exit;
	}
	else
	{
		
		$GLOBALS['tmpl']->assign('page_title',$GLOBALS['lang']['ERROR_TITLE']." - ".$msg);
		$GLOBALS['tmpl']->assign('msg',$msg);
		if($jump=='')
		{
			$jump = $_SERVER['HTTP_REFERER'];
		}
		if(!$jump&&$jump=='')
		$jump = APP_ROOT."/";
		$GLOBALS['tmpl']->assign('jump',$jump);
		$GLOBALS['tmpl']->assign("stay",$stay);
		$GLOBALS['tmpl']->display("error.html");
		exit;
	}
}

//显示成功
function showSuccess($msg,$ajax=0,$jump='',$stay=0)
{
	if($ajax==1)
	{
		$result['status'] = 1;
		$result['info'] = $msg;
		$result['jump'] = $jump;
		header("Content-Type:text/html; charset=utf-8");
        echo(json_encode($result));exit;
	}
	else
	{
		$GLOBALS['tmpl']->assign('page_title',$GLOBALS['lang']['SUCCESS_TITLE']." - ".$msg);
		$GLOBALS['tmpl']->assign('msg',$msg);
		if($jump=='')
		{
			$jump = $_SERVER['HTTP_REFERER'];
		}
		if(!$jump&&$jump=='')
		$jump = APP_ROOT."/";
		$GLOBALS['tmpl']->assign('jump',$jump);
		$GLOBALS['tmpl']->assign("stay",$stay);
		$GLOBALS['tmpl']->display("success.html");
		exit;
	}
}




//解析URL标签
// $str = u:shop|acate#index|id=10&name=abc
function parse_url_tag($str)
{
	$key = md5("URL_TAG_".$str);
	if(isset($GLOBALS[$key]))
	{
		return $GLOBALS[$key];
	}
	
	$url = load_dynamic_cache($key);
	if($url!==false)
	{
		$GLOBALS[$key] = $url;
		return $url;
	}
	$str = substr($str,2);
	$str_array = explode("|",$str);
	$app_index = $str_array[0];
	$route = $str_array[1];
	$param_tmp = explode("&",$str_array[2]);
	$param = array();
	foreach($param_tmp as $item)
	{
		if($item!='')
		$item_arr = explode("=",$item);
		if($item_arr[0]&&$item_arr[1])
		$param[$item_arr[0]] = $item_arr[1];
	}
	$GLOBALS[$key]= url($app_index,$route,$param);
	set_dynamic_cache($key,$GLOBALS[$key]);
	return $GLOBALS[$key];
}

//编译生成css文件
function parse_css($urls)
{
	
	$url = md5(implode(',',$urls));
	$css_url = 'public/runtime/statics/'.$url.'.css';
	$url_path = APP_ROOT_PATH.$css_url;
	if(!file_exists($url_path))
	{
		if(!file_exists(APP_ROOT_PATH.'public/runtime/statics/'))
		mkdir(APP_ROOT_PATH.'public/runtime/statics/',0777);
		$tmpl_path = $GLOBALS['tmpl']->_var['TMPL'];	
	
		$css_content = '';
		foreach($urls as $url)
		{
			$css_content .= @file_get_contents($url);
		}
		$css_content = preg_replace("/[\r\n]/",'',$css_content);
		$css_content = str_replace("../images/",$tmpl_path."/images/",$css_content);
//		@file_put_contents($url_path, unicode_encode($css_content));
		@file_put_contents($url_path, $css_content);
	}
	return get_domain().APP_ROOT."/".$css_url;
}

function load_page_png($img)
{
	return load_auto_cache("page_image",array("img"=>$img));
}



function get_gopreview()
{
		$gopreview = es_session::get("gopreview");
		if(!isset($gopreview)||$gopreview=="")
		{
			$gopreview = es_session::get('before_login')?es_session::get('before_login'):"/user";				
		}	
		es_session::delete("before_login");	
		es_session::delete("gopreview");	
		return $gopreview;
}

function set_gopreview()
{
	$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");   
    $parse = parse_url($url);
    if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            $url   =  $parse['path'].'?'.http_build_query($params);
    }
    if(app_conf("URL_MODEL")==1)$url = $GLOBALS['current_url'];
	es_session::set("gopreview",$url); 
}	

function app_recirect_preview()
{
	app_redirect(get_gopreview());
}


/**
 * 剩余时间
 */
function remain_time($remain_time){
	$d = intval($remain_time/86400);
	$h = round(($remain_time%86400)/3600);
	$m = round(($remain_time%3600)/60);
	return $d.$GLOBALS['lang']['DAY'].$h.$GLOBALS['lang']['HOUR'].$m.$GLOBALS['lang']['MIN'];
}


function sub_str($str, $length = 0, $append = true)
{
    $str = strip_tags($str);
	$str = trim($str);
    $strlength = strlen($str);

    if ($length == 0 || $length >= $strlength)
    {
        return $str;
    }
    elseif ($length < 0)
    {
        $length = $strlength + $length;
        if ($length < 0)
        {
            $length = $strlength;
        }
    }

    if (function_exists('mb_substr'))
    {
        $newstr = mb_substr($str, 0, $length, 'UTF-8');
    }
    elseif (function_exists('iconv_substr'))
    {
        $newstr = iconv_substr($str, 0, $length, 'UTF-8');
    }
    else
    {
        $newstr = trim_right(substr($str, 0, $length));
    }

    if ($append && $str != $newstr)
    {
        $newstr .= '...';
    }

    return $newstr;
}


function trim_right($str)
{
    $length = strlen(preg_replace('/[\x00-\x7F]+/', '', $str)) % 3;

    if ($length > 0)
    {
        $str = substr($str, 0, 0 - $length);
    }

    return $str;
}


function str_len($str)
{
    $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));

    if ($length)
    {
        return strlen($str) - $length + intval($length / 3) * 2;
    }
    else
    {
        return strlen($str);
    }
}



function editdata($tablename,$arr,$w="")
{
	if($arr){
		$result = $GLOBALS['db']->query("select * from ".DB_PREFIX.$tablename.' limit 0,1');
		$listvalues="";
		for($i=0;$i<mysql_num_fields($result);$i++){
			$meta=mysql_fetch_field($result);
			foreach(array_keys($arr) as $rows){
				if($rows!='id'&&$rows!='money'&&$rows!='mobile'&&$rows!='email'&&$rows!='real_name'&&$rows==$meta->name){
					$listvalues.=$rows."='".$arr[$rows]."',";
				}
			}
		}
		
		$listvalues=substr($listvalues,0,strlen($listvalues)-1);
		$sql="update ".DB_PREFIX.$tablename." set ".$listvalues." where id=".$arr['id'].$w;
		return  $GLOBALS['db']->query($sql);
		
	}
	
}

//电话号码隐藏
function newphonenum($s,$t){
    
	if($s){
        if($t=='phone'){
			 if(strlen($s)<8){
			 	return mb_substr($s,0,2,'utf-8').'****'.mb_substr($s,strlen($s)-2,7,'utf-8');	
			 }else{
			 	return mb_substr($s,0,3,'utf-8').'****'.mb_substr($s,strlen($s)-4,7,'utf-8');	
			 }
		}
        if($t=='idno') return substr($s,0,4).'****'.substr($s,strlen($s)-4,4);
		if($t=='uname') {
			if(strlen($s)<=6){
				return  mb_substr($s,0,2,'utf-8').'***'.mb_substr($s,strlen($s)-1,9,'utf-8');
			}else{
				return  mb_substr($s,0,2,'utf-8').'***'.mb_substr($s,strlen($s)-4,9,'utf-8');
			}	
		}
		if($t=='addr') return mb_substr($s,0,4,'utf-8').'***************';
    }
}


?>
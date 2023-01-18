<?php
//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
date_default_timezone_set("Asia/chongqing");
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");


$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
$action = $_GET['action'];
define('APP_ROOT_PATH', str_replace('ckfinder/controller.php', '', str_replace('\\', '/', __FILE__)));

//二进制图片上传
function pic_upfile(){
		//匹配出图片的格式
		$base64_image_content=$_REQUEST['img_data'];
		$return['url'] = '';
		$return['state']='';
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
			$type = $result[2];
			if($type!='jpeg'){
				$return['state'] = 'A当前图片格式不支持';
				$return['url'] = "";
				json_encode($return);
			}
			//echo print_r($result);exit();
			$file_f_name=date('Ymd',time())."/";
			$new_file = APP_ROOT_PATH.'ckfinder/upfile/image/'.$file_f_name;
			//echo str_replace("system/","",SPAPP_PATH).'public/ckfinder/image/'.$file_f_name;exit();
			if(!file_exists($new_file)){
				//检查是否有该文件夹，如果没有就创建，并给予最高权限
				mkdir($new_file, 0700);
			}
			$pic_name=time().".jpg";
			$new_file = $new_file.$pic_name;
			if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
				$return['original'] = "";
				$return['state'] = "SUCCESS";
				$return['size'] = "0";
				$return['title'] = "";
				$return['type'] = ".jpg";
				$return['url'] = "/ckfinder/upfile/image/".$file_f_name.$pic_name;
				//return '/'.$new_file;
			}else{
				$return['state'] = '图片上传失败';
				$return['url'] = "";
			}
		}else{
			$return['state'] = 'b当前图片格式不支持';
			$return['url'] = "";
		}
		echo json_encode($return);
}


if($action=='uploadimage'){

    pic_upfile();
    exit();
}
exit();

switch ($action) {
    case 'config':
        $result =  json_encode($CONFIG);
        break;

    /* 上传图片 */
    case 'uploadimage':
	
		//pic_upfile();
		//break;
    /* 上传涂鸦 */
    case 'uploadscrawl':
    /* 上传视频 */
    case 'uploadvideo':
    /* 上传文件 */
    case 'uploadfile':
        $result = include("action_upload.php");
        break;

    /* 列出图片 */
    case 'listimage':
        $result = include("action_list.php");
        break;
    /* 列出文件 */
    case 'listfile':
        $result = include("action_list.php");
        break;

    /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.php");
        break;

    default:
        $result = json_encode(array(
            'state'=> '请求地址出错'
        ));
        break;
}

/* 输出结果 */
if (isset($_GET["callback"])) {
    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
    } else {
        echo json_encode(array(
            'state'=> 'callback参数不合法'
        ));
    }
} else {
    echo $result;
}
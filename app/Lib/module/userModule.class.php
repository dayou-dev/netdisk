<?php
// +----------------------------------------------------------------------
// | 中海融通金融服务有限公司
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.yfw.hk All rights reserved.
// +----------------------------------------------------------------------
// | Author: @@@@@@@
// +----------------------------------------------------------------------

class userModule extends SiteBaseModule
{

	public function index()
	{
		require APP_ROOT_PATH.'app/Lib/uc.php';
		$user_info = es_session::get("user_info");
		$user_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id=".intval($user_info['id']));
		$GLOBALS['tmpl']->display("user/user_index.html");
	}
	
	public function filedir()
	{
		require APP_ROOT_PATH.'app/Lib/uc.php';
		$o=intval($_REQUEST['o']);
		$path_id=intval($_REQUEST['path_id']);
		$order=" order by id desc";
		if($o) $order=" order by title desc";
		$user_info = es_session::get("user_info");
		$sql="select id,title,mtype,file_size,file_type,pid,icon,cid,file_spec,create_time from ".DB_PREFIX."user_file where user_id=".intval($user_info['id'])." and pid=$path_id and is_delete=0 ".$order;
		$pagelist=thispage1($sql,1,200,10,"&o=$o&path_id=$path_id");
		foreach($pagelist['list'] as $key=>$rows){
			$file_count=0;
			if($rows['mtype']){
				$file_size=$rows['file_size']/1024;
				if($file_size>1023){
					$file_size=$file_size/1024;
					$file_size=round($file_size,2);
					$file_size=$file_size."Mb";
				}else{
					$file_size=round($file_size,2);
					$file_size=$file_size."kb";
				}
			}else{
				$file_size=0;
				$file_count=$GLOBALS['db']->getOne("select count(id) as c from ".DB_PREFIX."user_file where is_delete=0 and pid=".intval($rows['id']));
			}
			$file_dir=$rows['pid']?$GLOBALS['db']->getOne("select title from ".DB_PREFIX."user_file where is_delete=0 and id=".intval($rows['pid'])):"我的文件";
			
			
			$pagelist['list'][$key]['create_time'] = date("Y-m-d h:i:s",$rows['create_time']);
			$pagelist['list'][$key]['file_size'] = $file_size;
			$pagelist['list'][$key]['file_count'] = $file_count;
			$pagelist['list'][$key]['file_dir'] = $file_dir;
			
		}
		
		$redata['page']=$pagelist['pagetxt']?$pagelist['pagetxt']:0;
		$redata['pageCount']=$pagelist['pagecount']?$pagelist['pagecount']:0;
		$redata['files']=$pagelist['list']?$pagelist['list']:array();
		$redata['status']=1;
		$redata['path_id']=$GLOBALS['db']->getOne("select pid from ".DB_PREFIX."user_file where is_delete=0 and id=$path_id");
		$redata['file_dir']=$GLOBALS['db']->getAll("SELECT id,title,pid from ".DB_PREFIX."user_file where mtype=0 and is_delete=0 and user_id=$user_id ORDER BY pid asc");
		ajax_return($redata);
	}
	
	public function file_cid()
	{
		$user_info = es_session::get("user_info");
		$user_id = intval($user_info['id']);
		$cid=$_REQUEST['cid'];
		$path_id=intval($_REQUEST['path_id']);
		$names=htmlstrchk($_REQUEST['names']);
		
		if($path_id) $file_chk=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where is_delete=0 and user_id=$user_id and id=$path_id");
		if(!$file_chk&&$path_id){
			$redata['info']="当前目录不存在或已删除";
			$redata['status']=0;
			ajax_return($redata);
		}
		
		if(!$names){
			$redata['info']="文件名称不能为空";
			$redata['status']=0;
			ajax_return($redata);
		}
		
		if($cid){
			$cid_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where cid='$cid'");
			
			if($cid_info){
				//获取文件目录
				$recomm_pid=$this->get_file_top_pid($path_id);
				$pid_list=$recomm_pid['pid_list']?$recomm_pid['pid_list']."|":"";
				
				$msg_data = array();
				$msg_data['user_name'] = $user_info['user_name'];
				$msg_data['user_id'] = $user_info['id'];
				$msg_data['title'] = $names;
				$msg_data['mtype'] = 1;
				$msg_data['pid'] = $path_id;
				$msg_data['pid_list'] = $pid_list;
				$msg_data['file_size'] = $cid_info['file_size'];
				$msg_data['file_type'] = $cid_info['file_type'];
				$msg_data['file_spec'] = $cid_info['file_spec'];
				$msg_data['icon'] = $cid_info['icon'];
				$msg_data['cid'] = $cid_info['cid'];
				$msg_data['create_time'] = time();
				$GLOBALS['db']->autoExecute(DB_PREFIX."user_file",$msg_data); //插入
				$reid = $GLOBALS['db']->insert_id();
				
				/*$redata['info']=$names;
				$redata['size']=$names;
				$redata['reid']=$reid;
				$redata['status']=1;*/
				
				$redata['info']="文件上传成功";
				$redata['status']=1;
				ajax_return($redata);
			}else{
				$redata['info']="文件CID不存在";
				$redata['status']=2;
				ajax_return($redata);
			}
		}else{
			$redata['info']="文件上传失败，请重新上传";
			$redata['status']=0;
			ajax_return($redata);
		}
		
		
		
	}
	
	public function file_upload()
	{
		$user_info = es_session::get("user_info");
		$user_id = intval($user_info['id']);
		$uuid=$_REQUEST['cid'];
		$file_type=htmlstrchk(strtolower($_REQUEST['type']));
		$path_id=intval($_REQUEST['path_id']);
		$names=htmlstrchk($_REQUEST['names']);
		$icon=htmlstrchk($_REQUEST['icon']);
		$file_spec=htmlstrchk($_REQUEST['img_spec']);
		sleep(2);
		$output=get_url('http://183.240.209.145:9090/api/cids?uuid='.$uuid);
		$redate=json_decode($output);
		$cid=$redate->Cid;
		$file_size=$redate->Size;
		
		
		if($path_id) $file_chk=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where is_delete=0 and user_id=$user_id and id=$path_id");
		if(!$file_chk&&$path_id){
			$redata['info']="当前文件目录不存在或已删除";
			$redata['status']=0;
			ajax_return($redata);
		}
		
		if(!$names){
			$redata['info']="文件名称不能为空";
			$redata['status']=0;
			ajax_return($redata);
		}
		
		if($cid){
			
			//获取文件目录
            $recomm_pid=$this->get_file_top_pid($path_id);
			$pid_list=$recomm_pid['pid_list']?$recomm_pid['pid_list']."|":"";
			
			$msg_data = array();
			$msg_data['user_name'] = $user_info['user_name'];
			$msg_data['user_id'] = $user_info['id'];
			$msg_data['title'] = $names;
			$msg_data['mtype'] = 1;
			$msg_data['pid'] = $path_id;
			$msg_data['pid_list'] = $pid_list;
			$msg_data['file_size'] = $file_size;
			$msg_data['file_type'] = $file_type;
			$msg_data['icon'] = $icon;
			$msg_data['file_spec'] = $file_spec;
			$msg_data['cid'] = $cid;
			$msg_data['create_time'] = time();
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_file",$msg_data); //插入
			$reid = $GLOBALS['db']->insert_id();
			
			/*$redata['info']=$names;
			$redata['size']=$names;
			$redata['reid']=$reid;
			$redata['status']=1;*/
			
			
			$redata['info']="文件上成功";
			$redata['status']=1;
			ajax_return($redata);
		}else{
			$redata['info']="文件上传失败，请重新上传";
			$redata['status']=0;
			ajax_return($redata);
		}
	}

	public function mkdir()
	{
		$user_info = es_session::get("user_info");
		$user_id = intval($user_info['id']);
		$path_id=intval($_REQUEST['path_id']);
		if($path_id) $file_chk=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where is_delete=0 and user_id=$user_id and id=$path_id");
		if(!$file_chk&&$path_id){
			$redata['info']="当前目录不存在或已删除";
			$redata['status']=0;
			ajax_return($redata);
		}else{
			
			$dir_name = htmlstrchk(trim($_REQUEST['name']));
			if(!$dir_name){
				$redata['info']="请输入新建文件夹名称";
				$redata['status']=0;
				ajax_return($redata);
			}
			
			$file_name_chk=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where is_delete=0 and mtype=0 and pid=$path_id and user_id=$user_id and title='$dir_name'");
			if($file_name_chk){
				$redata['info']="当前文件夹名称已经存在";
				$redata['status']=0;
				ajax_return($redata);
			}
			
			//获取文件目录
            $recomm_pid=$this->get_file_top_pid($path_id);
			$pid_list=$recomm_pid['pid_list']?$recomm_pid['pid_list']."|":"";
			
			$msg_data = array();
			$msg_data['user_name'] = $user_info['user_name'];
			$msg_data['user_id'] = $user_info['id'];
			$msg_data['title'] = $dir_name;
			$msg_data['mtype'] = 0;
			$msg_data['pid'] = $path_id;
			$msg_data['pid_list'] = $pid_list;
			$msg_data['create_time'] = time();
			$GLOBALS['db']->autoExecute(DB_PREFIX."user_file",$msg_data); //插入
			$reid = $GLOBALS['db']->insert_id();
			
			$redata['info']=$dir_name;
			$redata['reid']=$reid;
			$redata['status']=1;
			ajax_return($redata);
		}
	}
	
	//获取树形目录
	public function get_dir()
	{
		$user_info = es_session::get("user_info");
		$user_id = intval($user_info['id']);
		$path_id=intval($_REQUEST['path_id']);
		$file_dir=$GLOBALS['db']->getAll("SELECT id,title,pid from ".DB_PREFIX."user_file where mtype=0 and is_delete=0 and user_id=$user_id ORDER BY pid asc");
		ajax_return($file_dir);
	}
	
	//移动文件目录
	public function move_dir()
	{
		$user_info = es_session::get("user_info");
		$user_id = intval($user_info['id']);
		$path_id=intval($_REQUEST['path_id']);
		$fid=intval($_REQUEST['fid']);
		$file_dir=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where is_delete=0 and mtype=0 and id=$path_id and user_id=$user_id");
		if(!$file_dir&&$path_id){
			$redata['info']="转移的文件目录不存在或已删除";
			$redata['status']=0;
			ajax_return($redata);
		}
		$file_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where is_delete=0 and id=$fid and user_id=$user_id");
		if(!$file_info){
			$redata['info']="当前文件目录不存在或已删除";
			$redata['status']=0;
			ajax_return($redata);
		}
		
		if($file_info['mtype']){
			$GLOBALS['db']->query("update ".DB_PREFIX."user_file set pid=$path_id where is_delete=0 and mtype=1 and id=$fid and user_id=$user_id");
			$redata['info']="1";
			$redata['status']=1;
			ajax_return($redata);
		}else{
			
			if(!$path_id){
				
				$file_name_chk=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where is_delete=0 and mtype=0 and pid=$path_id and user_id=$user_id and title='".$file_info['title']."'");
				if($file_name_chk){
					$redata['info']="迁移目录文件夹名称已经存在";
					$redata['status']=0;
					ajax_return($redata);
				}
				
				
				$GLOBALS['db']->query("update ".DB_PREFIX."user_file set pid=0 where is_delete=0 and mtype=0 and id=$fid and user_id=$user_id");
				$redata['info']="2";
				$redata['status']=1;
				ajax_return($redata);
			}else{
				$file_dir_list = $this->get_file_pid_list($fid);
				$file_dir_list = explode("|",$file_dir_list);
				$file_chk_int=0;
				foreach($file_dir_list as $rows){
					if($path_id==$rows) $file_chk_int++;
				}
				
				if($file_chk_int){
					$redata['info']="目标文件夹是源文件夹子文件夹";
					$redata['status']=0;
					ajax_return($redata);
				}
				
				$file_name_chk=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where is_delete=0 and mtype=0 and pid=$path_id and user_id=$user_id and title='".$file_info['title']."'");
				if($file_name_chk){
					$redata['info']="迁移目录文件夹名称已经存在";
					$redata['status']=0;
					ajax_return($redata);
				}
				
				$GLOBALS['db']->query("update ".DB_PREFIX."user_file set pid=$path_id where is_delete=0 and mtype=0 and id=$fid and user_id=$user_id");
				$redata['info']="3";
				$redata['status']=1;
				ajax_return($redata);
			}
		}
		
		
		
		ajax_return($file_dir);
	}
	
	//获取文件夹全部子目录
    private function get_file_pid_list($id){
        $file_dir_list=$GLOBALS['db']->getAll("select id,pid from ".DB_PREFIX."user_file where mtype=0 and is_delete<2 and pid=".intval($id));
		//echo ("select id,pid from ".DB_PREFIX."user_file where mtype=0 and is_delete<2 and pid=".intval($id)).'<br/>';
		foreach($file_dir_list as $rows){
			$pid_list.=$pid_list?"|".$rows['id']:$rows['id'];
			$file_dir_int=$GLOBALS['db']->getAll("select id from ".DB_PREFIX."user_file where mtype=0 and is_delete<2 and pid=".intval($rows['id']));
			if($file_dir_int){
				 $pid_list.="|".$this->get_file_pid_list($rows['id']);	
			}
		}
        return $pid_list;
    }
	
	
	//获取树形目录
	public function file_del()
	{
		$user_info = es_session::get("user_info");
		$user_id = intval($user_info['id']);
		$fid=intval($_REQUEST['fid']);
		
		$file_info=$GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_file where is_delete=0 and id=$fid and user_id=$user_id");
		if(!$file_info){
			$redata['info']="当前文件目录不存在或已删除";
			$redata['status']=0;
			ajax_return($redata);
		}
		$file_dir=$GLOBALS['db']->query("update ".DB_PREFIX."user_file set is_delete=1 where is_delete=0 and user_id=$user_id and id=$fid");
		$redata['info']="";
		$redata['status']=1;
		ajax_return($redata);
	}
	
	
	//获取文件路由
    private function get_file_top_pid($pid,$lvint=1,$pid_list=''){
        $file_info=$GLOBALS['db']->getRow("select id,pid from ".DB_PREFIX."user_file where id=".intval($pid));
        $top_pid=$file_info['id'];
		$pid_list=$pid_list?$pid_list:"|".$pid;
        if($file_info['pid']){
            $top_pid=$file_info['id'];
			$pid_list.="|".$file_info['pid'];
            $lvint++;
            $file_info1=$GLOBALS['db']->getRow("select id,pid from ".DB_PREFIX."user_file where id=".intval($file_info['pid']));
            if($file_info1){
                return $this->get_file_top_pid($file_info['pid'],$lvint,$pid_list);
            }
        }
        $redata=array();
        $redata['top_pid']=$top_pid;
        $redata['lvint']=$lvint;
		$redata['pid_list']=$pid_list;
        return $redata;
    }

	
	public function loginout()
	{
		es_session::delete("user_info");
		es_cookie::delete("admId");
		es_cookie::delete("adm_name");
		es_cookie::delete("adm_pwd");
		es_cookie::delete("wxloginInt");
		
		$expire=time()-3600;
		setcookie ("admId", '', $expire);
		setcookie ("adm_name", '', $expire);
		setcookie ("adm_pwd", '', $expire);
		header("Location:/");
	}
	
}
?>
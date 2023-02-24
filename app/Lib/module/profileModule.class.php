<?php
require APP_ROOT_PATH.'vendor/Utils.php';
require APP_ROOT_PATH.'app/Lib/uc.php';
class profileModule extends SiteBaseModule{
    public function index(){
        $user_info = es_session::get("user_info");
        $plus = $GLOBALS['db']->getOne('select plus from '.DB_PREFIX.'user_payaddr where user_id = '.$user_info['id'].' and is_delete = 0');
        $GLOBALS['tmpl']->assign("plus", $plus);
        $GLOBALS['tmpl']->assign("user_info", $user_info);
        $GLOBALS['tmpl']->display("profile/index.html");
    }
    public function changePassword(){
        $user_info = es_session::get("user_info");
        if(!$user_info) ajax_return(['code' => 0, 'msg' => '您尚未登录']);
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        if(!$currentPassword || !$newPassword) ajax_return(['code' => 0, 'msg' => '参数错误']);
        $user = $GLOBALS['db']->getRow('select * from '.DB_PREFIX.'user where id='.$user_info['id']);
        !$user && ajax_return(['code' => 0, 'msg' => '当前用户不存在']);
        if($user['password'] != md5($currentPassword)) ajax_return(['code' => 0, 'msg' => '密码有误请重新输入']);
        $res = $GLOBALS['db']->query('update '.DB_PREFIX.'user set password = "'.md5($newPassword).'" where id='.$user_info['id']);
        $res && ajax_return(['code' => 1, 'msg' => '修改密码成功']);
        ajax_return(['code' => 0, 'msg' => '修改密码失败']);
    }

    public function manager(){
        $user_info = es_session::get("user_info");
        $storageTotal = $GLOBALS['db']->getOne('select sum(file_size) as c from '.DB_PREFIX.'user_file where user_id = '.$user_info['id'].' and is_delete = 0');
        $stime = strtotime(date('Y-m-d'));
        $etime = strtotime('-45 day', $stime);

        $list = $GLOBALS['db']->getAll('select * from '.DB_PREFIX.'user_file where user_id = '.$user_info['id'].' and is_delete = 0 and create_time BETWEEN '.$etime.' and '.$stime);
        $data = [];
        for($i = 0; $i < 45; $i++){
            $data[$i] = 0;
            $swtime = strtotime('-'.($i + 1).' day', $stime);
            $ewtime = $swtime + 24 * 3600;
            foreach ($list as $value){
                if($value['create_time'] < $ewtime && $value['create_time'] >= $swtime){
                    $data[$i] += $value['file_size'];
                }
            }
        }
        $max = max($data);
        foreach ($data as $key => &$val) {
            $val = [
                'cery' => round($val / $max, 4) * 100,
                'size' => $val,
                'type' => 'B',
                'date' => date('Y-m-d', strtotime('-'.($key + 1).' day', $stime))
            ];
            if($val['size'] == 0){
                $val['size'] = 0;
            }elseif($val['size'] < 1024){
                $val['size'] = $val;
                $val['type'] = 'B';
            } elseif ($val['size'] < 1024 * 1024){
                $val['size'] = round($val['size']/1024, 2);
                $val['type'] = 'KB';
            } elseif ($val['size'] < 1024*1024*1024){
                $val['size'] = round($val['size']/1024/1024, 2);
                $val['type'] = 'MB';
            } else {
                $val['size'] = round($val['size']/1024/1024/1024, 2);
                $val['type'] = 'GB';
            }
        }
        $GLOBALS['tmpl']->assign("uploadList", $data);
        $GLOBALS['tmpl']->assign("storageTotalG", round($storageTotal/1024/1024/1024, 2));
        $GLOBALS['tmpl']->assign("storageTotalM", round($storageTotal/1024/1024, 2));
        $GLOBALS['tmpl']->display("profile/manager.html");
    }
}

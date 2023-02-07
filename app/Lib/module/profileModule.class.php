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
}


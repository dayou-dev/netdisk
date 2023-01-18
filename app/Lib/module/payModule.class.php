<?php
class payModule extends SiteBaseModule
{
	public function index()
	{
		//开始输出广告列表
		$id=lanint?2:4244;
		$about = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."company where id=$id");
		$GLOBALS['tmpl']->assign("about",$about);
		$GLOBALS['tmpl']->display("page/about.html");
	}
	
}	
?>
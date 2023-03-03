<?php
//载入文章点击数
function insert_load_article_click($para)
{
	if(check_ipop_limit(get_client_ip(),"article",60,intval($para['article_id'])))
	{
					//每一分钟访问更新一次点击数
		$GLOBALS['db']->query("update ".DB_PREFIX."article set click_count = click_count + 1 where id =".intval($para['article_id']));
	}
	return intval($GLOBALS['db']->getOne("select click_count from ".DB_PREFIX."article where id = ".intval($para['article_id'])));
}



function insert_load_login_form()
{
	return $GLOBALS['tmpl']->fetch("inc/page_login_form.html");
}


function insert_load_goods_tab($p)
{
	$idx = intval($p['param']);
	if($idx==2&&trim($_REQUEST['type'])=='comment')
	{
		return "class='act'";
	}
	elseif($idx==1&&(!isset($_REQUEST['type'])||trim($_REQUEST['type'])!='comment')) 
	return "class='act'";
	else
	return "class=''";
}


function insert_load_keyword()
{
	$keyword = addslashes(htmlspecialchars(trim($_REQUEST['keyword'])));
	if($keyword=='')
	$keyword = $GLOBALS['lang']['HEAD_KEYWORD_EMPTY_TIP'];
	return $keyword;
}


?>
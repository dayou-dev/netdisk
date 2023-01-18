<?php
class newsModule extends SiteBaseModule
{
	public function index()
	{	
		$id=intval($_GET['id']);
		$ajax=intval($_REQUEST['ajax']);
		if(!$id) $id=23;
		if($id) $wr.=" and cate_id=$id";
		$sql = "select * from ".DB_PREFIX."article where is_effect = 1 and is_delete=0 and cate_id<>26 $wr order by sort asc,id desc ";
		$pagelist=thispage1($sql,1,8,10,"&id=".$_GET['id']);
		
		//新闻分类
		$article_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."article_cate where is_effect=1 and id<>26  order by sort asc, id desc");
		$GLOBALS['tmpl']->assign("article_cate",$article_cate);
		//新闻分类
		$cateinfo = $GLOBALS['db']->getRow("select title from ".DB_PREFIX."article_cate where id=$id");
		$GLOBALS['tmpl']->assign("cateinfo",$cateinfo);
		$GLOBALS['tmpl']->assign("id",$id);
		$GLOBALS['tmpl']->assign("pagelist",$pagelist);
		if($ajax){
			$GLOBALS['tmpl']->display("page/news_ajax.html");
		}else{
			$GLOBALS['tmpl']->display("page/news.html");
		}
	}
	
	//新闻内容
	public function detail() {
		$id=intval($_REQUEST['id']);
     	$bodyinfo = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."article where is_effect = 1 and is_delete=0 and id = $id ");
		$GLOBALS['tmpl']->assign("bodyinfo",$bodyinfo);	
		if(!$bodyinfo) header("Location:/");
		$GLOBALS['db']->query("update ".DB_PREFIX."article set click_count=click_count+1 where id = $id and is_effect=1 and is_delete=0");
		
		//新闻分类
		$article_cate = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."article_cate where language_id=".lanint." order by id asc, id desc");
		$GLOBALS['tmpl']->assign("article_cate",$article_cate);
		
		//下一篇
		$next_news  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."article where cate_id=".intval($bodyinfo['cate_id'])." and is_effect = 1 and is_delete=0 and id>$id and language_id=".lanint." order by id asc limit 0,1");
		$GLOBALS['tmpl']->assign("next_news",$next_news);
		
		//上一篇
		$back_news  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."article where cate_id=".intval($bodyinfo['cate_id'])." and is_effect = 1 and is_delete=0 and id<$id and language_id=".lanint." order by id desc limit 0,1");
		$GLOBALS['tmpl']->assign("back_news",$back_news);
		
		//新闻分类
		$catename = $GLOBALS['db']->getOne("select title from ".DB_PREFIX."article_cate where id=".intval($bodyinfo['cate_id']));
		$GLOBALS['tmpl']->assign("catename",$catename);
		/*if(!lanint){ 
			require_once(APP_ROOT_PATH.'system/splitword.class.php');
			$keywords = $bodyinfo['title'];
			$keywords = str_replace(",","",$keywords);
			$keywords = str_replace("，","",$keywords);
			$keywords = str_replace(".","",$keywords);
			$keywords = str_replace("。","",$keywords);
			$keywords = str_replace("！","",$keywords);
			$keywords = str_replace("!","",$keywords);
			$keywords = str_replace("'","",$keywords);
			$keywords = str_replace("“","",$keywords);
			$sp = new SplitWord();#中文分词类
			$sp->SetSource($keywords);
			$sp->StartAnalysis();
			$keywords_auto="";
			foreach($sp->GetFinallyIndex() as $key=>$rows){
				$keywords_auto.=$keywords_auto?",".$key:$key;
			}
			$sp = null;
			$description_auto=sub_str($bodyinfo['content'],50);
			$description_auto=str_replace("...","",$description_auto);
			$GLOBALS['tmpl']->assign("keywords_auto",$keywords_auto);
			$GLOBALS['tmpl']->assign("description_auto",$description_auto);
		}*/
		
		//下一篇
		$next_news  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."article where cate_id=".intval($bodyinfo['cate_id'])." and is_effect = 1 and is_delete=0 and id>$id  order by id asc limit 0,1");
		$GLOBALS['tmpl']->assign("next_news",$next_news);
		
		//上一篇
		$back_news  = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."article where cate_id=".intval($bodyinfo['cate_id'])." and is_effect = 1 and is_delete=0 and id<$id order by id desc limit 0,1");
		$GLOBALS['tmpl']->assign("back_news",$back_news);
		
		$GLOBALS['tmpl']->assign("page_keyword",$bodyinfo['seo_keyword']);
		$GLOBALS['tmpl']->assign("page_description",$bodyinfo['seo_description']);
		$GLOBALS['tmpl']->assign("bodyinfo",$bodyinfo);
		$GLOBALS['tmpl']->display("page/news1.html");
	}
}	
?>
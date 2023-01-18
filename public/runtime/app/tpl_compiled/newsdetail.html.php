<!doctype html>
<html>
<head>
<title><?php echo $this->_var['bodyinfo']['title']; ?>_股票配资网 专业的股票配资、配资公司及配资平台资讯</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php if ($this->_var['bodyinfo']['seo_keyword']): ?><?php echo $this->_var['bodyinfo']['seo_keyword']; ?><?php else: ?><?php echo $this->_var['keywords_auto']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['bodyinfo']['seo_description']): ?><?php echo $this->_var['bodyinfo']['seo_description']; ?><?php else: ?><?php echo $this->_var['description_auto']; ?><?php endif; ?>" />
<link href="/css/style.css" rel="stylesheet" type="text/css">
<link href="/css/block.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="/favicon.ico"/>
<meta http-equiv="mobile-agent" content="format=xhtml;url=/m/">
<script type="text/javascript">if(window.location.toString().indexOf('pref=padindex') != -1){}else{if(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))){if(window.location.href.indexOf("?mobile")<0){try{if(/Android|Windows Phone|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){window.location.href="/m/";}else if(/iPad/i.test(navigator.userAgent)){}else{}}catch(e){}}}}</script>
<script src="/js/jquery.min.js"></script>
<script src="/js/jquery-cookie.js"></script>

</head>
<body class="single win8">

<?php echo $this->fetch('header.html'); ?>

<div id="con">
<div class="bx-recom bx-recom-single">
<!-- 头条 -->
<div class="hl_wrap clearfix">
<div class="hl-960" id="hl960">
<!-- 全站顶部广告位 -->
<?php $_from = $this->_var['advall']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'adv');if (count($_from)):
    foreach ($_from AS $this->_var['adv']):
?> 
<a href="<?php echo $this->_var['adv']['url']; ?>" <?php if ($this->_var['adv']['opentype']): ?>target="_blank"<?php endif; ?>><img src='<?php echo $this->_var['adv']['icon']; ?>' border="0" /></a>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
</div>
<div id="wrapper">    
	<div class="content fl">      
    <div class="current_nav"><a href="http://<?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SHOP_URL',
);
echo $k['name']($k['v']);
?>/">主页</a> &gt; <a href="/news/<?php echo $this->_var['bodyinfo']['cate_id']; ?>/1.html"><?php echo $this->_var['catename']; ?></a> &gt; <?php echo $this->_var['bodyinfo']['title']; ?> </div>
    
    
          <div class="post_title">        <h1><?php echo $this->_var['bodyinfo']['title']; ?></h1>        <span class="pt_info pre1"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['bodyinfo']['create_time'],
);
echo $k['name']($k['v']);
?>　出处：<?php if ($this->_var['bodyinfo']['source']): ?><?php echo $this->_var['bodyinfo']['source']; ?><?php else: ?>未知<?php endif; ?>　<span id="hitcount">人气：<?php echo $this->_var['bodyinfo']['click_count']; ?></span></span> </div>      
          
          
                      <div class="post_content" id="paragraph" style=" font-size: 14px;line-height: 24px">    
                      <?php echo $this->_var['bodyinfo']['content']; ?>
                      </div>      <div class="con-recom">        <div class="bx-recom4"></div>  
      
      <?php if ($this->_var['next_news']): ?><div class="pre-news"><span>上一篇：</span><a href="/news/<?php echo $this->_var['next_news']['cate_id']; ?>/<?php echo $this->_var['next_news']['id']; ?>.html"><?php echo $this->_var['next_news']['title']; ?></a></div><?php endif; ?>
<?php if ($this->_var['back_news']): ?><div class="next-news"><span>下一篇：</span><a href="/news/<?php echo $this->_var['back_news']['cate_id']; ?>/<?php echo $this->_var['back_news']['id']; ?>.html"><?php echo $this->_var['back_news']['title']; ?></a></div><?php endif; ?>
 </div>         
             
<!-- 相关文章 -->        
<div class="related_post"><h2>相关文章</h2>
<ul class="list_1">
<?php $_from = $this->_var['article_cate_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'news');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['news']):
?>
<li><a target="_blank" href="/news/<?php echo $this->_var['news']['cate_id']; ?>/<?php echo $this->_var['news']['id']; ?>.html" title="<?php echo $this->_var['news']['title']; ?>"><?php echo $this->_var['news']['title']; ?></a><span class="date"><?php echo date("m.d",$this->_var['news']['create_time']);?></span></li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
 
	</ul>

</div>             


</div>
    <!-- content End -->         
 <!-- 侧边区域 -->
<div class="sidebar">
      <ul>        
    
        <li class="sb_list">        
<div class="bx">
<!-- 右侧广告位1 -->
<?php $_from = $this->_var['adval2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'adv');if (count($_from)):
    foreach ($_from AS $this->_var['adv']):
?> 
<a href="<?php echo $this->_var['adv']['url']; ?>" <?php if ($this->_var['adv']['opentype']): ?>target="_blank"<?php endif; ?>><img src='<?php echo $this->_var['adv']['icon']; ?>' border="0" /></a>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

</div>        
</li>
	<li class="sb_list">
        <div class="right_con post_right_con">
            <div class="hotnews_list">
            <div class="hidden_line"></div>
            <div class="hn_title">最新资讯</div>
                <ul class="ulcl">
                  <?php $_from = $this->_var['article_new']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'news');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['news']):
?>
                  <li class="hn_li"><a title="<?php echo $this->_var['news']['title']; ?>" target="_blank" href="/news/<?php echo $this->_var['news']['cate_id']; ?>/<?php echo $this->_var['news']['id']; ?>.html">• <?php 
$k = array (
  'name' => 'sub_str',
  'v' => $this->_var['news']['title'],
  'f' => '16',
);
echo $k['name']($k['v'],$k['f']);
?></a></li>
                  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
              </div>
        </div>      
      </li>
        <li class="sb_list">
        <div class="right_con post_right_con">
            <div class="hotnews_list">
            <div class="hidden_line"></div>
            <div class="hn_title">热点资讯</div>
                <ul class="ulcl">
                  <?php $_from = $this->_var['article_hot']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'news');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['news']):
?>
                  <li class="hn_li"><a title="<?php echo $this->_var['news']['title']; ?>" target="_blank" href="/news/<?php echo $this->_var['news']['cate_id']; ?>/<?php echo $this->_var['news']['id']; ?>.html">• <?php 
$k = array (
  'name' => 'sub_str',
  'v' => $this->_var['news']['title'],
  'f' => '16',
);
echo $k['name']($k['v'],$k['f']);
?></a></li>
                  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
              </div>
        </div>      
      </li>    
      
           </ul>          </div>    
            <!-- sidebar End -->   </div>  <!-- wrapper End --> </div><!-- con End --> <!-- 头部区域 结束 --> 
            </div>
            
            
            
   
   
<!-- 页脚 -->  
<?php echo $this->fetch('footer.html'); ?>


<script src="js/common.js"></script> 

<script src="/js/coin-slider.min.js"></script> 
<script>$('#coin-slider').coinslider({width:250,height:320,spw:3,sph:2,effect:'random',delay:6000});</script>
</body>
</html>

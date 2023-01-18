<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->_var['bodyinfo']['title']; ?>_比特动力</title>
<meta name="keywords" content="<?php if ($this->_var['bodyinfo']['seo_keyword']): ?><?php echo $this->_var['bodyinfo']['seo_keyword']; ?><?php else: ?><?php echo $this->_var['keywords_auto']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['bodyinfo']['seo_description']): ?><?php echo $this->_var['bodyinfo']['seo_description']; ?><?php else: ?><?php echo $this->_var['description_auto']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
</head>
<body>
<!-- 头部 -->
  <div id="mBody2">
    <div id="mOuterBox">
      <div id="mTop" class="ct">
        
        <?php echo $this->fetch('header.html'); ?>

<!-- 中间内容 -->

    <div class="f_bd">
        <div class="f_wrapper">


<div class="mf" id="mfid3">

<div id="_ctl2_box" class="box857_-7206">
    <div class="box857_1">
		<div class="news_right">
		<h3 class="title_h3">资讯分类</h3>
		 <?php $_from = $this->_var['article_cate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
         <li class=""><a href="/news/?id=<?php echo $this->_var['item']['id']; ?>" <?php if ($this->_var['id'] == $this->_var['item']['id']): ?>style="color: #00A0E9;"<?php endif; ?>><span><?php echo $this->_var['item']['title']; ?></span></a></li>
         <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
        <ul class="news_content">
					<div class="news_title">
						<h1><?php echo $this->_var['bodyinfo']['title']; ?></h1>
						<p>
							
						<span class="news_fromF"> 来源：<?php if ($this->_var['bodyinfo']['source']): ?><?php echo $this->_var['bodyinfo']['source']; ?><?php else: ?>比特动力<?php endif; ?></span> 
						<span class="news_timeM"> 时间：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['bodyinfo']['create_time'],
);
echo $k['name']($k['v']);
?></span> 
					 
						</p>
					  </div>
					<div class="news_detail"><?php echo $this->_var['bodyinfo']['content']; ?></div>
					<div class="news_home"><a href="/news">返回列表</a></div>
		</ul>
        <div class="page1">
            <div class="textr">
                <?php echo $this->_var['pagelist']['pages']; ?>
            </div>
        </div>
    </div>
        </div>

        </div>
    </div>
    


<!-- 底部 -->
   
    </div>
    


<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

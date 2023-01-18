<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新闻中心_比特动力</title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_KEYWORD']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_DESCRIPTION']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?20b8c4f849a23aa6ebfd0a0a217871da";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
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
        <ul class="newsList">
            
                    
                    <?php $_from = $this->_var['pagelist']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                    <li><a href="/news/detail/<?php echo $this->_var['item']['id']; ?>.html">
                        <div class="img">
                            <img src="<?php echo $this->_var['item']['icon']; ?>" onerror="this.src='/images/nopic.jpg'" />
                        </div>
                        <div class="content">
                            <div class="info fl">
                                <a class="title" href="/news/detail/<?php echo $this->_var['item']['id']; ?>.html">
                                    <?php echo $this->_var['item']['title']; ?></a>
								<p class="num"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['item']['create_time'],
  'f' => 'Y/m/d',
);
echo $k['name']($k['v'],$k['f']);
?></p>
                                <p class="main">
                                    <?php 
$k = array (
  'name' => 'sub_str',
  'v' => $this->_var['item']['brief'],
  'f' => '80',
);
echo $k['name']($k['v'],$k['f']);
?></p>
                            </div>
                        </div>
						</a>
                    </li>
                	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>



                
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

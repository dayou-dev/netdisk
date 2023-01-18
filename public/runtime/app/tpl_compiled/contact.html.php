<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>技术支持_比特动力</title>
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
    
   <div class="f_bd jszz">
		<div style="background-repeat:no-repeat;background-position:50% 0ex;width:100%;height:400px;background:url(/images/jszc.jpg);margin-left: auto;margin-right: auto;">
		</div>
			<div class="f_wrapper">
<?php echo $this->_var['about']['content']; ?>
			</div>

   <script src="js/jquerys.js"></script>
   <link rel="stylesheet" href="css/reveal.css">
   <script type="text/javascript" src="js/jquery.reveal.js"></script>	
    <div id="myModal" class="reveal-modal" style="top: 0px; opacity: 1; visibility: hidden;margin-top:150px;">
        <center onclick="window.location.reload();">
        <embed src='https://player.youku.com/player.php/sid/XMjYxMDUyNjM0MA==/v.swf' allowFullScreen='true' quality='high' width='740' height='418' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash'>					</embed>
        </center>
    </div>
	
	
	


<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

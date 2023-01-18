<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>产品列表 比特动力</title>
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
            <div class="m_pageTitle">产品列表</div>
            <ul class="mod_productList">

                <?php $_from = $this->_var['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                <li class="productItem">
                    <img src="<?php echo $this->_var['item']['icon']; ?>" class="productImg" alt="" style="width: 180px;">
                    <div class="productInfo">
                        <p class="productName"><?php echo $this->_var['item']['title']; ?>
                        
                        
                        <?php if ($this->_var['item']['in_stock']): ?>
                        <?php if ($this->_var['item']['is_hot']): ?><span class="tagHot">热门</span><?php endif; ?> <?php if ($this->_var['item']['in_advance']): ?><span class="tagHot">预售</span><?php endif; ?>
                        <?php else: ?>
                        <?php if ($this->_var['item']['in_advance']): ?><span class="tagHot">预售结束</span><?php else: ?><span class="tagHot1">售罄</span><?php endif; ?>
                        <?php endif; ?>
                        
                        </p>
                        
                        
                        
                        <p class="productParams">

                            
                            
                           
                        </p>
                    </div>
                    <div class="price">
                        <p class="normalPrice">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['item']['price'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></p>
                        <p class="BTCPrice">（<?php echo $this->_var['item']['price1']; ?> BTC）</p>
                    </div>
                    
                    <a href="/product/detail/<?php echo $this->_var['item']['id']; ?>.html" class="b_btn b_btn1 btnBuy">立即购买</a>
                    
                  
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                
            </ul>
        </div>
    </div>
    


<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

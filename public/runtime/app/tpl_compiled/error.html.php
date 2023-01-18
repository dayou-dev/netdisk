<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>比特动力</title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_KEYWORD']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_DESCRIPTION']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
</head>
<body class="page_order page_order_submitted">
<!-- 头部 -->
  <div id="mBody2">
    <div id="mOuterBox">
      <div id="mTop" class="ct">
        
        <?php echo $this->fetch('header.html'); ?>

<!-- 中间内容 -->

    <div class="f_bd">
        <div class="f_wrapper">
            <div class="m_sectionBoxMod">
                <div class="m_sectionBoxMod_bd">
                    <div class="submittedResultBox" style="border-bottom: 0px solid #ccc;">
                        <span class="i_error">失败！</span>
                        <p class="submittedResultTitle"><?php echo $this->_var['LANG']['ERROR_TITLE']; ?>
                            
                        
                        
                        </p>

                        <div class="submittedResultInfo">
                         
                            <p><?php echo $this->_var['msg']; ?></p>
                            
                            <p><a href="<?php echo $this->_var['jump']; ?>" style="color: #00a0e9;">返回上一页</a></p>
                            
                        </div>

                    </div>

                                       
                </div>
            </div>
        </div>
    </div>
    

 

<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

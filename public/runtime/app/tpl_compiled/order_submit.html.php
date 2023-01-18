<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单详细 比特动力</title>
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
                    <div class="submittedResultBox">
                        <span class="i_success">成功！</span>
                        <p class="submittedResultTitle">订单提交成功
                            
                        
                        
                        </p>

                        <div class="submittedResultInfo">
                         
                            <p>订单号为 <?php echo $this->_var['order']['ordernu']; ?>，请您在 <span class="counting" id="time">5小时46分钟58秒</span> 内付款，逾期订单将自动取消。</p>
                            
                            <p>商品：<?php echo $this->_var['order_product']['title']; ?> × <?php echo $this->_var['order_product']['quantity']; ?></p>
                            
                            
                            <?php if (! $this->_var['order']['ordey_type']): ?>
                              
                          <p>收货地址：中国<?php echo $this->_var['order']['province']; ?><?php echo $this->_var['order']['city']; ?><?php echo $this->_var['orderzone']; ?><?php echo $this->_var['order']['address']; ?>
                                <?php echo $this->_var['order']['zip']; ?>(邮编) &nbsp;<?php echo $this->_var['order']['fullname']; ?>(收) -/<?php echo $this->_var['order']['phone_prefix']; ?> <?php echo $this->_var['order']['telephone']; ?>
                          </p>
                            <?php else: ?>
                            托管信息：姓名:<?php echo $this->_var['order']['fullname']; ?>　手机:<?php echo $this->_var['order']['telephone']; ?>　币种:<?php echo $this->_var['order']['cryptocurrency']; ?>　钱包地址:<?php echo $this->_var['order']['wallet_address']; ?>　矿池:<?php echo $this->_var['order']['mining_pool']; ?>
                            
                            <div class="bankingPay paymentInfoBox" style="padding-left:0px; margin-bottom:20px; display:none">
<table class="bankingTable">
                                <tbody><tr>
                                                <td class="tableTitle">姓名</td>
                                                <td class="tableValue"><?php echo $this->_var['order']['fullname']; ?></td>
                                                </tr>
                                            <tr>
                                              <td>手机</td>
                                              <td><?php echo $this->_var['order']['telephone']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>币种</td>
                                                <td><?php echo $this->_var['order']['cryptocurrency']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>钱包地址</td>
                                                <td><?php echo $this->_var['order']['wallet_address']; ?></td>
                                            </tr>
                                <tr>
                                    <td>矿池</td>
                                    <td class="priceValue"><?php echo $this->_var['order']['mining_pool']; ?></td>
                                  </tr>
                            </tbody></table>                            
                            </div>
                            
                            <?php endif; ?>
                            
                            
                         
                        </div>

                    </div>

                    

                    
                    
                    
                    <div class="paymentInfoBox">
                        <p class="tipsTitle">请通过以下方式进行支付</p>
                        <div class="bankingPay">
                            <table class="bankingTable">
                                <tbody><tr>
                                                <td class="tableTitle">收款人</td>
                                                <td class="tableValue">比特动力科技（深圳）有限公司</td>
                                            </tr>
                                            <tr>
                                                <td>汇入银行账号</td>
                                                <td>4000 0000 000 0000 0000</td>
                                            </tr>
                                            <tr>
                                                <td>汇入银行</td>
                                                <td>中国建设银行股份有限公司深圳南山大道支行</td>
                                            </tr>
                                <tr>
                                    <td>汇入金额</td>
                                    <td class="priceValue">￥
                                        <?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['order']['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?>
                                        </td>
                                </tr>
                                <tr>
                                    <td>备注/附言/摘要</td>
                                    <td><?php echo $this->_var['order']['id']; ?></td>
                                </tr>
                            </tbody></table>
                            <p class="bankingTips">*仅注明此单号，切勿填写其他内容</p>
                            <p class="transferTips">*请使用网银或者手机银行转账，请勿使用支付宝转账</p>
                        </div>
                        <a href="/account/my_order_list" class="b_btn b_btn3 btnCheckOrder">查看我的订单</a>
                    </div>
                    
                    

                    

                   
                </div>
            </div>
        </div>
    </div>
    
<script type="text/javascript"  src="/js/jquery.qrcode.min.js"  ></script>

<script type="text/javascript">


 $("#j-btc-address-qrcode").qrcode({width: 170, height: 170, text: "1PGnZscZ5gcqD3L71tp62XUCGwHhad2GvN"});

        var hours =  5 ;
        var minute = 59 ;
        var second= 59 ; 
        var start=0
        window.onload = function(){
            settime();
        };
       function settime() {
           if(start==0){
               if (minute == -1) { 
                   if(hours>0){
                        hours--;
                        minute=59;
                        second--; 
                        $("#time").text(hours+"小时"+minute+"分钟"+second+"秒");
                   }else{
                       start=1
                   }
               }else{
                   if (second == 0) { 
                       $("#time").text(hours+"小时"+minute+"分钟"+second+"秒");
                        minute--;
                        second=60; 
                   }else{
                        $("#time").text(hours+"小时"+minute+"分钟"+second+"秒");
                        second--; 
                        if(hours<0||minute<0||second<0){
                            $("#time").text("0小时0分钟0秒");
                            start=1
                        }
                   }
               }
               setTimeout(function() { 
                             settime() 
                },1000)  
           }else{
               
               
           }
       };
    </script> 

<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

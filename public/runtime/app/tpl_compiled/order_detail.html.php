<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单列表 比特动力</title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_KEYWORD']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_DESCRIPTION']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
</head>
<body class="page_order">
<!-- 头部 -->
  <div id="mBody2">
    <div id="mOuterBox">
      <div id="mTop" class="ct">
        
        <?php echo $this->fetch('header.html'); ?>

<!-- 中间内容 -->

    <div class="f_bd">
        <div class="f_wrapper">
            <h2 class="m_pageTitle">订单详情</h2>
            
         <?php
            $kv=25;
            if($this->_var['order']['order_status']=='1')  $kv=50;
            if($this->_var['order']['order_status']=='2') $kv=75;;
            if($this->_var['order']['order_status']=='3') $kv=100;
            if($this->_var['order']['order_status']=='4') $kv=100;
            ?>   

            
            
            <div class="orderProgressBox">
                <div class="orderProgressBar">
                    
                    <div class="orderProgress" style="width:<?php echo $kv;?>%"></div>
                    
                         
                     
                                    
                </div>
                <ul class="orderProgressSteps">
                    <li class="orderStepItem orderStepItem_finished">
                        <p class="orderStepName">待确认支付</p>
                        <p class="orderStepTime" ></p>
                    </li>
                    <li class="orderStepItem <?php echo $kv>=50?'orderStepItem_finished':'';?>">
                        <p class="orderStepName">完成支付，待发货</p>
                        <p class="orderStepTime"></p>
                    </li>
                    <li class="orderStepItem <?php echo $kv>=75?'orderStepItem_finished':'';?>">
                        <p class="orderStepName">已发货</p>
                        <p class="orderStepTime"></p>
                    </li>
                    <li class="orderStepItem <?php echo $kv==100?'orderStepItem_finished':'';?>">
                        <p class="orderStepName">已确认收货</p>
                        <p class="orderStepTime"></p>
                    </li>
                </ul>
            </div>
            
            

            
            
            

             
            
            
            <div class="m_sectionBoxMod orderMod_normalMsg">
                <div class="m_sectionBoxMod_hd">
                    <h3 class="sectionBoxMod_title">订单状态：<?php
            if($this->_var['order']['order_status']=='0') echo '<font color="red">待确认支付</font>';
            if($this->_var['order']['order_status']=='1') echo '<font color="#0000ff">等待发货</font>';
            if($this->_var['order']['order_status']=='2') echo '<font color="#090">已发货</font>';
            if($this->_var['order']['order_status']=='3') echo '订单完成';
            if($this->_var['order']['order_status']=='4') echo '<font color="#999">订单取消</font>';
            ?>，应付金额
                    
                     ￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['order']['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?><span style="float:right; padding-right:20px; color:#999;"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['order']['create_time'],
);
echo $k['name']($k['v']);
?></span>
                     </h3>
                     
                </div>
                <div class="m_sectionBoxMod_bd">
                    <?php if (! $this->_var['order']['ordey_type']): ?>
                              
                          <p>收货地址：中国<?php echo $this->_var['order']['province']; ?><?php echo $this->_var['order']['city']; ?><?php echo $this->_var['orderzone']; ?><?php echo $this->_var['order']['address']; ?>
                                <?php echo $this->_var['order']['zip']; ?>(邮编) &nbsp;<?php echo $this->_var['order']['fullname']; ?>(收) -/<?php echo $this->_var['order']['phone_prefix']; ?> <?php echo $this->_var['order']['telephone']; ?>
                          </p>
                            <?php else: ?>
                            托管信息：姓名:<?php echo $this->_var['order']['fullname']; ?>　手机:<?php echo $this->_var['order']['telephone']; ?>　币种:<?php echo $this->_var['order']['cryptocurrency']; ?>　钱包地址:<?php echo $this->_var['order']['wallet_address']; ?>　矿池:<?php echo $this->_var['order']['mining_pool']; ?>
                            <?php endif; ?>
                    <p style="display:none">请您在 <em id="time">3小时36分钟56秒</em> 内完成付款，逾期订单将自动取消。支付完成时间取决于银行转账速度，到帐后24小时内进行订单确认。</p>
                </div>
            </div>
                     
                     
            
            
            

            

            

            



            

            

             
            <div class="m_sectionBoxMod orderMod_payment">
                <div class="m_sectionBoxMod_hd">
                    <h3 class="sectionBoxMod_title">支付信息</h3>
                </div>
                <div class="m_sectionBoxMod_bd">
                    <div class="paymentInfoBox">
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
                                    <td class="priceValue">
                                        
                                        ￥<?php 
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
                    </div>
                </div>
            </div>
            

       
            <div class="m_sectionBoxMod orderMod_ensureOrder">
                <div class="m_sectionBoxMod_hd">
                    <h3 class="sectionBoxMod_title">确认订单信息</h3>
                    <div class="orderDataList">
                        <span class="orderData">小计</span>
                        <span class="orderData">数量</span>
                        <span class="orderData">单价</span>
                    </div>
                </div>
                <div class="m_sectionBoxMod_bd">
                    <ul class="orderItemList">
                    
                        <li class="orderItem">

                            <div class="productInfo">
                                <a href="/product/detail/<?php echo $this->_var['product']['id']; ?>.html"><img class="productImg" src="<?php echo $this->_var['product']['icon']; ?>" alt="" style="width: 120px;height: 120px"></a>
                                <p class="productTitle"><?php echo $this->_var['order_product']['title']; ?></p>
                                <p class="productType"><?php echo $this->_var['product']['pro_spec']; ?></p>
                            </div>
                            <div class="orderDataList">
                                <span class="orderData">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['order_product']['price'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></span>
                                <span class="orderData"><?php echo $this->_var['order_product']['quantity']; ?></span>
                                <span class="orderData">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['order']['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></span>
                            </div>
                        </li>
                        
                    </ul>
                    <div class="orderTotal">
                        <div class="customerNoteBox">
                            <p class="noteTitle">备注：<?php echo $this->_var['order_product']['content']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m_sectionBoxMod orderMod_subtotal">
                <div class="m_sectionBoxMod_bd">
                    <div class="orderSubTotalBox">
                        <div class="orderSubtotal">
                        
                            <p class="subtotalItem">
                                <span class="subtotalItemTitle">商品件数：</span>
                                <span class="subtotalItemValue"><?php echo $this->_var['order_product']['quantity']; ?></span>
                            </p>
                        
                            <p class="subtotalItem">
                                <span class="subtotalItemTitle">金额合计：</span>
                                <span class="subtotalItemValue">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['order']['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></span>
                            </p>
                        
                            <p class="subtotalItem">
                                <span class="subtotalItemTitle">运费：</span>
                                <span class="subtotalItemValue">0</span>
                            </p>
                            
                            <p class="subtotalItem subtotalItem_total">
                                <span class="subtotalItemTitle">应付金额：</span>
                                <span class="subtotalItemValue">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['order']['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></span>
                            </p>
                           
                            <p class="subtotalItem subtotalItem_totalBTC">
                                <span class="subtotalItemTitle"></span>
                                <span class="subtotalItemValue">(<?php echo $this->_var['order_product']['price1']; ?> BTC)</span>
                            </p>
                           
                            
                        </div>
                    </div>
                </div>
            </div>

            
            
            
             <div class="m_sectionBoxMod orderMod_normalMsg">
                <div class="m_sectionBoxMod_hd">
                    <h3 class="sectionBoxMod_title">收货信息</h3>
                </div>
                <div class="m_sectionBoxMod_bd">
                  <p>收货地址：中国广东省深圳市宝安区西乡街道流塘阳光1705
                                518000(邮编) &nbsp;张灿(收) -/13410704128
                            </p>
                </div>
            </div>
            
            
        </div>
    </div>
    
    
        <script type="text/javascript"  src="/js/jquery.qrcode.min.js"  ></script>
    <script type="text/javascript">



        $("#j-btc-address-qrcode").qrcode({width: 170, height: 170, text: "1PGnZscZ5gcqD3L71tp62XUCGwHhad2GvN"});
        var hours =  3 ;
        var minute = 37 ;
        var second= 29 ; 
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

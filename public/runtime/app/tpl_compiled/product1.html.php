<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->_var['productinfo']['title']; ?>_比特动力</title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_KEYWORD']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_DESCRIPTION']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/css/ax_component.css"/>
<link rel="stylesheet" href="/css/inner.css" />
</head>
<body>
<!-- 头部 -->
  <div id="mBody2">
    <div id="mOuterBox">
      <div id="mTop" class="ct">
        
        <?php echo $this->fetch('header.html'); ?>

<!-- 中间内容 -->

    <div class="f_bd page_productDetail">
        <div class="f_wrapper">
           
            <div class="productSummary">
                <div class="productGallery">
                    <a href="javascript:void(0)" class="enlargedImg"><img src="<?php echo $this->_var['productinfo']['icon']; ?>" alt="" id="big-pic"></a>
                    <ul class="thumbnailImgList">
                        <?php $_from = $this->_var['piclist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
                        <li class="thumbnailImg <?php if (! $this->_var['key']): ?>thumbnailImg_current<?php endif; ?>">
                            <a href="javascript:void(0)" onclick="choosePic(this);"><img src="/ckfinder/userfiles/<?php echo $this->_var['item']; ?>" alt=""></a>
                        </li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
 
                        
                    </ul>
                </div>
                <div class="productInfo">
                    <p class="productName"><?php echo $this->_var['productinfo']['title']; ?>
                  
                    <?php if ($this->_var['productinfo']['in_stock']): ?>
                        <?php if ($this->_var['productinfo']['is_hot']): ?><span class="tagHot">热门</span><?php endif; ?> <?php if ($this->_var['productinfo']['in_advance']): ?><span class="tagHot">预售</span><?php endif; ?>
                        <?php else: ?>
                        <?php if ($this->_var['productinfo']['in_advance']): ?><span class="tagHot">预售结束</span><?php else: ?><span class="tagHot">售罄</span><?php endif; ?>
                        <?php endif; ?>
                    
                    </p>
                    
                    </p>
                    <p class="productIntro"><?php echo $this->_var['productinfo']['pro_spec1']; ?></p>
                    
                    <p class="priceBox">
                        价格：<span class="normalPrice" id="normalPrice">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['productinfo']['price'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?> </span><span class="BTCPrice" id="BTCPrice">（<?php echo $this->_var['productinfo']['price1']; ?> BTC）</span>
                    </p>
                    
                    <div class="paramItem">
                        <p class="paramTitle">产品规格：</p>
                        <div class="paramValue">
                            
                            <a href="javascript:void(0)" class="m_selectItem m_selectItem_current"><?php echo $this->_var['productinfo']['pro_spec']; ?></a> 
                            <input type="hidden" id="in_stock" value="<?php echo $this->_var['productinfo']['in_stock']; ?>">
                        </div>
                    </div>
                    <div class="paramItem">
                        <p class="paramTitle">台数：</p>
                        <div class="paramValue">
                            <div class="m_numberSelector">
                                <a href="javascript:void(0)" class="minus" id="minus-amount">-</a>
                                <div class="inputBox">
                                    <input type="text" value="1" id="amount">
                                </div>
                                <a href="javascript:void(0)" class="add" id="add-amount">+</a>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                    <a href="javascript:void(0)" class="b_btn b_btn1 btnBuy" onclick="openPayBox();">立即购买</a> 
                    
                    
                    
                    
                    
                </div>
            </div>
 
               
<ul class="productDescriptionTab productDescriptionTab_inner">                 
    <li class="tabItem tabItem_current first"><a href="#desProductDetail">产品详情</a></li>                 
    <li class="tabItem second"><a href="#desProductParam">规格参数</a></li>                 
    <li class="tabItem thrid"><a href="#desProductPay">付款方式</a></li>                 
	<li class="tabItem forth"><a href="#desProductAfterBought">售后政策</a></li>             
</ul>              

<div id="desProductDetail">                     </br>                  
</div> 
<div class="productDescription">     
	<div class="desMod">         
		<h3 class="desModTitle">产品详情</h3>         
		<div class="desModContent">               
			<?php echo $this->_var['productinfo']['content']; ?>             
		</div>     
	</div>     
	
	<div id="desProductParam">         </br>         </br>         </br>     </div>     
	<div class="desMod">         
		<h3 class="desModTitle">规格参数</h3>         
		<div class="desModContent">             
			<?php echo $this->_var['productinfo']['content1']; ?>         
		</div>     
	</div>         
	<div id="desProductPay">                     </br>                     </br>                     </br>                 
	</div>                 
	<div class="desMod">                     
		<h3 class="desModTitle">付款方式</h3>                     
		<div class="desModContent">                         
			<?php echo $this->_var['content2']; ?>                   
		</div>                 
	</div>                  
	<div id="desProductAfterBought">                     </br>                     </br>                 </div>                 
	<div class="desMod">                     
		<h3 class="desModTitle">售后政策</h3>                     
		<div class="desModContent">                         
			<?php echo $this->_var['content3']; ?>                   
		</div>                 
	</div>             
</div>         
                 
        <div class="fixedTabBox">
            <div class="f_wrapper">
                <ul class="productDescriptionTab">
                <li class="tabItem tabItem_current first"><a href="#desProductDetail">产品详情</a></li>
                <li class="tabItem second"><a href="#desProductParam">规格参数</a></li>
                <li class="tabItem thrid"><a href="#desProductPay">付款方式</a></li>
                <li class="tabItem forth"><a href="#desProductAfterBought">售后政策</a></li>
                </ul>
                    
                    
                    
                    
                    <a href="javascript:void(0)" class="b_btn b_btn1 btnBuy" onclick="openPayBox();">立即购买</a> 
                    
                    
                    
                    
            </div>
        </div>

     <div class="p_pop p_popHostingOption" id="pay-box">
        <div class="p_popTitleBox">
            <h2 class="p_popTitle">请选择类型</h2>
        </div>
        <a href="javascript:void(0)" class="i_popClose" onclick="closePayBox()">关闭</a>
        <div class="p_popContent">
            <p class="hostingTips">请选择您所需要的服务：</p>
            <ul class="hostingOptions">
                
                <li class="option">
                    <a class="optionLink" href="javascript:void(0)" onclick="chooseDelivery(this)">
                        <span class="checkIcon"></span>
                        <span class="title">发货</span>
                        <span class="info"></span>
                    </a>
                </li>
                <li class="option">
                    <a class="optionLink" href="javascript:void(0)" onclick="chooseHosting(this)">
                        <span class="checkIcon"></span>
                        <span class="title">托管</span>
                        <span class="info">矿机将被运送到熊猫矿场，完成部署后开始挖矿</span>
                        
                    </a>
                </li>
            </ul>
            <input type="hidden" id="order-service">
            <p class="link_whatIs">
                <a href="/default/hosting" target="_blank">什么是托管？</a>
            </p>
            <div class="p_popBtns">
                <a href="javascript:void(0)" class="b_btn b_btn1 b_btnDisable" id="pay-confirm-button">下一步</a>
            </div>
        </div>
    </div>

    
    </div>
  

    <script>
    function openPayBox() {

        if (checkLogin()) {
                var amount = $('#amount').val();
              
			  
				$.ajax({
					  type: "post",
					  dataType: 'json',
					  url: "/api/get_product_stock",
					  contentType: 'application/json',
					  data: {id: "<?php echo $this->_var['id']; ?>",amount:amount,dataType:"json"},
					  success: function (result) {
							if(!result.success){
								alert("库存不足");
							}else{
								$('#pay-box').removeClass('hide');
								$('#pay-box').addClass('show');
								$('.optionLink_current').removeClass('optionLink_current');
								$('#order-service').val("");
							}
					  }
					});			  
			  
			  
            }
    }

    function onlyHosting() {
        var amount = $('#amount').val();
        var attr_id = $('#attr').val();

        if (checkLogin()) {
            $.getJSON('/api/set_order_creat_session/', function(data) {
                window.location.href = "/default/hosting_order_taking?id=" +  27  + "&attr=" + attr_id + "&amount=" + amount;;
                return;
            })
        }
    }

    function closePayBox() {
        $('#pay-box').removeClass('show');
        $('#pay-box').addClass('hide');
        $('.optionLink_current').removeClass('optionLink_current');
        $('#order-service').val("");
        $('#final-pay-confirm-button').addClass('b_btnDisable');
    }

    function chooseDelivery(item) {
        $('.optionLink_current').removeClass('optionLink_current');
        $(item).addClass('optionLink_current');
        $('#order-service').val("delivery");
        $('#pay-confirm-button').removeClass('b_btnDisable');
    }

    function chooseHosting(item) {
        $('.optionLink_current').removeClass('optionLink_current');
        $(item).addClass('optionLink_current');
        $('#order-service').val("hosting");
        $('#pay-confirm-button').removeClass('b_btnDisable');
    }


    function choosePic(item) {
        $(".thumbnailImg").removeClass("thumbnailImg_current");
        $(item).parent().addClass("thumbnailImg_current");
        img = $(item).children().attr("src");
        
        $("#big-pic").attr("src", img);
    }

    function chooseAttr(id) {
        $('.m_selectItem').removeClass("m_selectItem_current");
        $('#attr-' + id).addClass("m_selectItem_current");
        $('#attr').val(id);

        $.post("/api/get_product_price", {
            attr_id: id
        }, function(result) {
            

            $('#normalPrice').html('￥' + result.Price);
            $('#BTCPrice').html('（' + result.BTCPrice + ' BTC）');
        });

        $('#amount').val("1");
    }

    function isPInt(str) {
        var g = /^[1-9]*[1-9][0-9]*$/;
        return g.test(str);
    };

    $(document).ready(function() {
        $('#nav_miner').addClass("navItem_current");

        $('#minus-amount').click(function() {
            var amount = $('#amount').val();
            var minLimit = 1;
            if (amount > minLimit) {
                amount--;
            };
            $('#amount').val(amount);
        });

        $('#add-amount').click(function() {
            var amount = $('#amount').val();
            var maxLimit = parseInt($("#in_stock").val());
            if (amount > maxLimit) {
                amount=maxLimit;
            }else{
                amount++;
			};
            $('#amount').val(amount);
        });

        $('#btnBuy').click(function() {
            if (checkLogin()) {
                var amount = $('#amount').val();
                var attr_id = $('#attr').val();
                $.post("/api/get_product_stock", {
                    attr_id: attr_id,
                    amount:amount
                }, function(result) {
                   switch (result.Code) {
                    case "30014":
                        if (lang == 'cn') {
                        alert("库存不足");
                        } else {
                            alert("out of stock");
                        }
                     
                        return;
                    case "30027":
                        if (lang == 'cn') {
                            alert("超出购买限量");
                        } else {
                            alert("beyond your purchase limit");
                        }
                        return;
                    case "0":     
                         $.getJSON('/api/set_order_creat_session/', function(data) {
                            if (data.Code == "0") {
                                window.location.href = "/default/order_taking?id=" +  27  + "&attr=" + attr_id + "&amount=" + amount;     
                            }
                        })
                    }

                    }
                );
            }

        });

        $('#btnBuy2').click(function() {
            clickBuy();
        });

        $('#btnBuy3').click(function() {
            clickBuy();
        });

        function clickBuy(){
            if (checkLogin()) {
                var amount = $('#amount').val();
                var attr_id = $('#attr').val();
                $.post("/api/get_product_stock", {
                    attr_id: attr_id,
                    amount:amount
                }, function(result) {
                   switch (result.Code) {
                    case "30014":
                        if (lang == 'cn') {
                        alert("库存不足");
                        } else {
                            alert("out of stock");
                        }
                     
                        return;
                    case "30027":
                        if (lang == 'cn') {
                            alert("超出购买限量");
                        } else {
                            alert("beyond your purchase limit");
                        }
                        return;
                    case "0":     
                         $.getJSON('/api/set_order_creat_session/', function(data) {
                            if (data.Code == "0") {
                                window.location.href = "/default/order_taking?id=" +  27  + "&attr=" + attr_id + "&amount=" + amount;     
                            }
                        })
                    }

                    }
                );
            }
        }

        $("#amount").blur(function() {
            value = $(this).val();

            if (!isPInt(value)) {
                alert("输入错误");
                $(this).val("1");
                return;
            };
        });

        $('#pay-confirm-button').click(function() {

            var order_service = $('#order-service').val();
            if (order_service == "") {
                    return
                }

            var amount = $('#amount').val();
            var attr_id = $('#attr').val();

            if (checkLogin()) {
                window.location.href = "/default/order_taking?id=<?php echo $this->_var['id']; ?>&otype=" + (order_service == "delivery"?0:1) + "&amount=" + amount;
                return;
            }
        });

    });
    </script>

    <script>
    $(function(){
        var tabPosY = $('.productDescriptionTab_inner').offset().top;
         $(window).scroll(function () {
             var scrollOffsetY = $(document).scrollTop();
             console.log(scrollOffsetY)
             console.log(tabPosY)
             if (scrollOffsetY > tabPosY) {
                if (!$('.fixedTabBox').hasClass('fixedTabBox_show')) {
                    $('.fixedTabBox').addClass('fixedTabBox_show')
                }
            }else{
                $('.fixedTabBox').removeClass('fixedTabBox_show')
            }
         })
    })

    $(".first").click(function(){
        $(".tabItem").removeClass("tabItem_current");
        $(".first").addClass("tabItem_current");
    })

    $(".second").click(function(){
        $(".tabItem").removeClass("tabItem_current");
        $(".second").addClass("tabItem_current");
    })

    $(".thrid").click(function(){
        $(".tabItem").removeClass("tabItem_current");
        $(".thrid").addClass("tabItem_current");
    })

     $(".forth").click(function(){
        $(".tabItem").removeClass("tabItem_current");
        $(".forth").addClass("tabItem_current");
    })


</script>
   
    </div>
    


<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

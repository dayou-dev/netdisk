<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单列表 比特动力</title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_KEYWORD']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_DESCRIPTION']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
</head>
<body class="page_my page_myOrderList">
<!-- 头部 -->
  <div id="mBody2">
    <div id="mOuterBox">
      <div id="mTop" class="ct">
        
        <?php echo $this->fetch('header.html'); ?>

<!-- 中间内容 -->

    <div class="f_bd">
        <div class="f_wrapper">
            <div class="bd_main">
                <?php echo $this->fetch('page/user_menu.html'); ?>
                <div class="bd_content">
                    <h2 class="m_pageTitle">我的订单</h2>
                    <input type="hidden" id="orderStatus" value="ALL">
                    <ul class="m_tab orderTab">
                        <li class="m_tabItem  <?php if (! $this->_var['s']): ?>m_tabItem_current<?php endif; ?>"><a href="javascript:void(0)" onclick="changeOrderStatus(this, 'ALL')">全部</a></li>
                        <li class="m_tabItem  <?php if ($this->_var['s'] == 1): ?>m_tabItem_current<?php endif; ?>"><a href="/account/my_order_list/?s=1">待确认支付</a></li>
                        <li class="m_tabItem  <?php if ($this->_var['s'] == 2): ?>m_tabItem_current<?php endif; ?>"><a href="/account/my_order_list/?s=2">待发货</a></li>
                        <li class="m_tabItem  <?php if ($this->_var['s'] == 3): ?>m_tabItem_current<?php endif; ?>"><a href="/account/my_order_list/?s=3">已发货</a></li>
                        <li class="m_tabItem  <?php if ($this->_var['s'] == 4): ?>m_tabItem_current<?php endif; ?>"><a href="/account/my_order_list/?s=4">其他</a></li>
                    </ul>
                    <ul class="mod_orderListBox">
                        <?php $_from = $this->_var['pagelist']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                        <li class="m_sectionBoxMod orderListItem">
                            <div class="m_sectionBoxMod_hd">
                                <span class="orderData orderDataSnapShot">下单时间：<?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['item']['create_time'],
);
echo $k['name']($k['v']);
?>  订单号：<?php echo $this->_var['item']['ordernu']; ?></span>
                                <span class="orderData">单价</span>
                                <span class="orderData">数量</span>
                                <span class="orderData">应付款</span>
                                <span class="orderData">状态</span>
                                <span class="orderData">操作</span>
                            </div>
                            <div class="m_sectionBoxMod_bd">
                                
                                    <ul class="orderItemList">
                                        <li class="orderItem">
                                            <div class="orderData orderDataSnapShot">
                                                <a href="/product/detail/<?php echo $this->_var['item']['product_id']; ?>.html"><img class="productImg" src="<?php echo $this->_var['item']['icon']; ?>" alt="" style="width: 120px;height: 120px;"></a>
                                                <p class="productTitle"><?php echo $this->_var['item']['title']; ?></p>
                                                <p class="productType"><?php echo $this->_var['item']['pro_spec']; ?></p>
                                            </div>
                                            <span class="orderData">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['item']['price'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></span>
                                            <span class="orderData"><?php echo $this->_var['item']['quantity']; ?></span>
                                            <span class="orderData">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['item']['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></span>
                                            <span class="orderData" id="order-status-2018101741524019899"><?php
            if($this->_var['item']['order_status']=='0') echo '<font color="red">待确认支付</font>';
            if($this->_var['item']['order_status']=='1') echo '<font color="#0000ff">等待发货</font>';
            if($this->_var['item']['order_status']=='2') echo '<font color="#090">已发货</font>';
            if($this->_var['item']['order_status']=='3') echo '订单完成';
            if($this->_var['item']['order_status']=='4') echo '<font color="#999">订单取消</font>';
            ?></span>
                                            <div class="orderData">
                                                <a href="/default/order_detail?id=<?php echo $this->_var['item']['id']; ?>">查看详情</a>
                                                  
                                                
                                              <?php if ($this->_var['item']['order_status'] < 2): ?>  <a href="javascript:void(0)" class="b_btn b_btn1" onclick="cancelOrderShow(&quot;<?php echo $this->_var['item']['id']; ?>&quot;)" id="open-cancel-box-btn-<?php echo $this->_var['item']['id']; ?>">取消订单</a> <?php endif; ?>  <?php if ($this->_var['item']['order_status'] == 2): ?>  <a href="javascript:void(0)" class="b_btn b_btn1" onclick="confirmReceiveShow(&quot;<?php echo $this->_var['item']['id']; ?>&quot;)" id="open-confirm-box-btn-<?php echo $this->_var['item']['id']; ?>">确认收货</a> <?php endif; ?>
                                            </div>
                                        </li>
                                         
                                    </ul>
                                    
                            </div>
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
    
    
    <div class="p_pop p_popTips" id="cancel-order-box">
        <div class="p_popTitleBox">
            <h2 class="p_popTitle">提示</h2>
        </div>
        <a href="###" class="i_popClose" id="close-cancel-order-btn">关闭</a>
        <div class="p_popContent">
            <p class="tipsTitle">您确定要取消订单吗？</p>
            <p class="tipsSubInfo">如果您已支付货款，请勿取消订单</p>
            <input type="hidden" id="cancel-order-id">
            <div class="p_popBtns">
                <a href="javascript:void(0)" class="b_btn b_btn3" id="cancel-cancel-order-btn">不取消订单</a>
                <a href="javascript:void(0)" class="b_btn b_btn1" id="cancel-order-btn">取消订单</a>
            </div>
        </div>
    </div>
    <div class="p_pop p_popTips" id="confirm-receive-box">
        <div class="p_popTitleBox">
            <h2 class="p_popTitle">提示</h2>
        </div>
        <a href="javascript:void(0)" class="i_popClose" id="close-confirm-receive-btn">关闭</a>
        <div class="p_popContent">
            <p class="tipsTitle">您确认已收到货？</p>
            <p class="tipsSubInfo">请您在收到货后再点击确认按钮</p>
            <input type="hidden" id="confirm-order-id">
            <div class="p_popBtns">
                <a href="javascript:void(0)" class="b_btn b_btn3" id="cancel-confirm-receive-btn">取消</a>
                <a href="javascript:void(0)" class="b_btn b_btn1" id="confirm-receive-btn">确认</a>
            </div>
        </div>
    </div>
    
    <script>
    function confirmReceiveShow(id) {
        $('#confirm-receive-box').removeClass("hide");
        $('#confirm-receive-box').addClass("show");
        $('#confirm-order-id').val(id);
    };

    function confirmReceiveHide() {
        $('#confirm-receive-box').removeClass("show");
        $('#confirm-receive-box').addClass("hide");
    };

    function cancelOrderShow(id) {
        $('#cancel-order-box').removeClass("hide");
        $('#cancel-order-box').addClass("show");
        $('#cancel-order-id').val(id);
    }

    function cancelOrderHide() {
        $('#cancel-order-box').removeClass("show");
        $('#cancel-order-box').addClass("hide");
        $('#cancel-order-id').val("");
    }

    function openFinalPayBox(id) {
        $('#final-pay-box').removeClass('hide');
        $('#final-pay-box').addClass('show');
        $('.optionLink_current').removeClass('optionLink_current');
        $('#final-pay-order-id').html(id);
        $('#order-service').val("");
    }

    function closeFinalPayBox() {
        $('#final-pay-box').removeClass('show');
        $('#final-pay-box').addClass('hide');
        $('.optionLink_current').removeClass('optionLink_current');
        $('#final-pay-order-id').html("");
        $('#order-service').val("");
        $('#final-pay-confirm-button').addClass('b_btnDisable');
    }

    function chooseDelivery(item) {
        $('.optionLink_current').removeClass('optionLink_current');
        $(item).addClass('optionLink_current');
        $('#order-service').val("delivery");
        $('#final-pay-confirm-button').removeClass('b_btnDisable');
    }

    function chooseHosting(item) {
        $('.optionLink_current').removeClass('optionLink_current');
        $(item).addClass('optionLink_current');
        $('#order-service').val("hosting");
        $('#final-pay-confirm-button').removeClass('b_btnDisable');
    }
        
    function changeOrderStatus(item, status) {
        $('#orderStatus').val("");
        $('.m_tabItem_current').removeClass('m_tabItem_current');
        $(item).parent().addClass('m_tabItem_current');
        window.location.href = "/account/my_order_list?status="+status;
    }

    $(document).ready(function() {
        $('#cancel-confirm-receive-btn').click(confirmReceiveHide);
        $('#close-confirm-receive-btn').click(confirmReceiveHide);

        $('#cancel-cancel-order-btn').click(cancelOrderHide);
        $('#close-cancel-order-btn').click(cancelOrderHide);
        $('#confirm-receive-btn').click(function() {

            if (checkLogin()) {
                var order_id = $('#confirm-order-id').val();
                $.ajax({
                    url: "/api/order_confirm/",
                    data: {
                        order_id: order_id,
                    },
                    dataType: "json",
                    type: "post",
                    async: false,
                    error: function() {
                        alert("error ")
                    },
                    success: function(data) {
                        switch (data.Code) {
                            case "0":

                                $("#order-status-" + order_id).html("已确认收货");
                                confirmReceiveHide();
								$("#open-confirm-box-btn").addClass("hide");
								$("#open-confirm-box-btn-" + order_id).addClass("hide");
								location.reload()
                                break;
                            case "10005":
                                alert("请先登录");
								confirmReceiveHide();
                                loginBoxShow();
                                break;
                            default:
                                alert("操作失败,请重试");
								confirmReceiveHide();
                                break;
                        }
                    }
                });

            }

        });

        $('#cancel-order-btn').click(function() {
            if (checkLogin()) {
                var order_id = $('#cancel-order-id').val();

                $.ajax({
                    url: "/api/cancel_order/",
                    data: {
                        order_id: order_id,
                    },
                    dataType: "json",
                    type: "post",
                    async: false,
                    error: function() {
                        alert("error ")
                    },
                    success: function(data) {
                        switch (data.Code) {
                            case "0":
                                
                                $("#order-status-" + order_id).html("已取消");
                                cancelOrderHide();
                                $("#open-cancel-box-btn-" + order_id).addClass("hide");
                                break;
                            case "10005":
                                alert("请先登录");
                                loginBoxShow();
                                break;
                            default:
                                alert("取消失败,请重试")
                                break;
                        }
                    }
                });

            }
        });

        $('#final-pay-confirm-button').click(function() {

            var order_service = $('#order-service').val();
            if (order_service == "") {
                    return
                }

            if (checkLogin()) {
                var order_id = $('#final-pay-order-id').html();

                if (order_service == "delivery") {
                    window.location.href = "/default/final_pay_order_taking?order_id=" + order_id
                    return
                } else if (order_service == "hosting") {
                    window.location.href = "/default/final_pay_hosting_order_taking?order_id=" + order_id
                    return;
                }

            }

        });

    });
    </script>

<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>确认订单 比特动力</title>
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
            <h2 class="m_pageTitle">确认订单</h2>
           <?php if (! $this->_var['otype']): ?>
           
            <div class="m_sectionBoxMod orderMod_address">
                <div class="m_sectionBoxMod_hd">
                    <h3 class="sectionBoxMod_title">收货地址</h3>
                    <a href="/account/my_address" class="operation">管理收货地址</a>
                </div>
                <div class="m_sectionBoxMod_bd">
                    <div class="addressSelector">
                        <ul class="existedAddress" id="address-list">
                        <?php $_from = $this->_var['user_address']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
                            <li class="addressItem">
                                <input name="address" type="radio" <?php if (! $this->_var['key']): ?>checked="true"<?php endif; ?> value="<?php echo $this->_var['item']['id']; ?>">中国 <?php echo $this->_var['item']['province']; ?> <?php echo $this->_var['item']['city']; ?> <?php echo $this->_var['item']['region']; ?> <?php echo $this->_var['item']['address']; ?> <?php echo $this->_var['item']['zip']; ?>(邮编) &nbsp;<?php echo $this->_var['item']['receiver']; ?>(收) <?php echo $this->_var['item']['mobilephone']; ?>
                            </li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                        <a href="javascript:void(0)" class="addAddrBtn">+ 添加新地址</a>
                    </div>
                    <div class="addAdressBox <?php if ($this->_var['user_address']): ?>hide<?php endif; ?>">
                                <div class="m_inputWrap2">
                                    <p class="m_inputTitle">收货人：</p>
                                    <div class="m_inputBox m_inputBox_neccesary">
                                        <input type="text" placeholder="" id="receiver">
                                    </div>
                                </div>
                                <div class="m_inputWrap2">
                                    <p class="m_inputTitle">手机：</p>
                                    <div class="m_inputBox m_inputBox_neccesary">
                                        <input type="text" placeholder="" id="mobilephone">
                                    </div>
                                </div>
                                <div class="m_inputWrap2">
                                    <p class="m_inputTitle">电话：</p>
                                    <div class="m_inputBox inputBox_areaCode">
                                        <input type="text" placeholder="区号" id="phone_prefix">
                                    </div>
                                    <div class="m_inputBox inputBox_tel">
                                        <input type="text" placeholder="电话号码" id="phone_number">
                                    </div>
                                </div>
                                <div class="m_inputWrap2 inputWrap_location">
                                    <p class="m_inputTitle">地址：</p>
                                   
                                    <div class="m_inputBox m_inputBoxSelect">
                                        <input class="input" type="text" disabled="disabled" value="" id="province" placeholder="省/直辖市">
                                        <input type="hidden" id="province_id" value="">
                                        <a href="javascript:void(0)" class="i_inputDropDownArrow"></a>
                                        <div class="m_inputOptionsWrap" id="province-box">
                                            <ul class="m_inputOptionList" id="province-list">
                                            <?php $_from = $this->_var['regionlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                                                <li class="m_inputBoxOptionListItem"><a href="javascript:void(0)" onclick="chooseProvince(this)" name="<?php echo $this->_var['item']['name']; ?>" id="<?php echo $this->_var['item']['id']; ?>"><?php echo $this->_var['item']['name']; ?></a></li>
                                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="m_inputBox m_inputBoxSelect">
                                        <input class="input" type="text" disabled="disabled" value="" id="city" placeholder="城市">
                                         <input type="hidden" id="city_id" value="">
                                        <a href="javascript:void(0)" class="i_inputDropDownArrow"></a>
                                        <div class="m_inputOptionsWrap" id="city-box">
                                            <ul class="m_inputOptionList" id="city-list">
                                                
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="m_inputBox m_inputBoxSelect inputBox_lineBreak">
                                        <input class="input" type="text" disabled="disabled" value="" id="region" placeholder="区/县">
                                        <input type="hidden" id="region_id" value="">
                                        <a href="javascript:void(0)" class="i_inputDropDownArrow"></a>
                                        <div class="m_inputOptionsWrap" id="region-box">
                                            <ul class="m_inputOptionList" id="region-list">
                                               
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="m_inputBox m_inputBoxSelect" style="display:none;">
                                        <input class="input" type="text" disabled="disabled" value="" id="street" placeholder="乡镇/街道">
                                        <input type="hidden" id="street_id" value="">
                                        <a href="javascript:void(0)" class="i_inputDropDownArrow"></a>
                                        <div class="m_inputOptionsWrap" id="street-box">
                                            <ul class="m_inputOptionList" id="street-list">
                                               
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="m_inputBox inputBox_detailAddr m_inputBox_neccesary">
                                        <input type="text" placeholder="详细地址，例如路名、门牌号等" id="address">
                                    </div>
                                </div>
                                <div class="m_inputWrap2">
                                    <p class="m_inputTitle">邮编：</p>
                                    <div class="m_inputBox inputBox_zipCode">
                                        <input type="text" placeholder="" id="zip">
                                    </div>
                                </div>
                        <div class="submitBtns">
                            <a href="javascript:void(0)" class="b_btn b_btn1" id="save-use-address-btn">保存并使用</a>
                            <a href="javascript:void(0)" class="b_btn b_btn3" id="cancel-add-address-btn">取消</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            
			<div class="m_sectionBoxMod orderMod_hosting">
                <div class="m_sectionBoxMod_hd">
                    <h3 class="sectionBoxMod_title">托管信息</h3>
                </div>
                <div class="m_sectionBoxMod_bd">
                    <div class="addAdressBox">
                        <div class="m_inputWrap2">
                            <p class="m_inputTitle">姓名：</p>
                            <div class="m_inputBox m_inputBox_neccesary">
                                <input type="text" placeholder="" id="name">
                            </div>
                        </div>
                      
                        <div class="m_inputWrap2 m_inputWrap_tel ">
                            <p class="m_inputTitle">手机：</p>
                            <div class="m_inputBox m_inputBox_neccesary">
                                <div class="areaCodeBox">
                                    <a class="areaCode" href="javascript:void(0)"><span id="prefix">+86</span> <span class="i_telAreaCodeArrow"></span></a>
                                    <div class="areaCodeListWrap" id="prefix-box">
                                        <ul class="areaCodeList">
                                            <li class="areaCodeItem"><a href="javascript:void(0)" onclick="choosePhonePrefix(this)">+86</a></li>
                                            <li class="areaCodeItem"><a href="javascript:void(0)" onclick="choosePhonePrefix(this)">+852</a></li>
                                        </ul>
                                    </div>
                                   
                                </div>
                                <input type="text" placeholder="" id="mobile_phone">
                            </div>
                        </div>
                        <div class="m_inputWrap2">
                            <p class="m_inputTitle">币种</p>
                            <div class="m_inputBox m_inputBoxSelect m_inputBox_neccesary">
                                <input class="input" type="text" disabled="disabled" value="" id="cryptocurrency">
                                <a href="javascript:void(0)" class="i_inputDropDownArrow"></a>
                                <div class="m_inputOptionsWrap" id="cryptocurrency-box">
                                    <ul class="m_inputOptionList">
                                        <li class="m_inputBoxOptionListItem"><a href="javascript:void(0)" onclick="chooseCryptocurrency(this)">ETH</a></li>
                                        <li class="m_inputBoxOptionListItem"><a href="javascript:void(0)" onclick="chooseCryptocurrency(this)">ETC</a></li>
                                        <li class="m_inputBoxOptionListItem"><a href="javascript:void(0)" onclick="chooseCryptocurrency(this)">ZEC</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="m_inputWrap2">
                            <p class="m_inputTitle">钱包地址：</p>
                            <div class="m_inputBox m_inputBox_neccesary">
                                <input type="text" placeholder="" id="wallet_address">
                            </div>
                        </div>
                        <div class="m_inputWrap2">
                            <p class="m_inputTitle">矿池：</p>
                            <div class="m_inputBox m_inputBoxSelect m_inputBox_neccesary">
                                <input class="input" type="text" disabled="disabled" value="" id="mining_pool">
                                <a href="javascript:void(0)" class="i_inputDropDownArrow"></a>
                                <div class="m_inputOptionsWrap" id="mining-pool-box">
                                    <ul class="m_inputOptionList" id="otherMiningPool">
                                        <li class="m_inputBoxOptionListItem"><a href="javascript:void(0)" onclick="chooseMiningPool(this)">waterhole.io</a></li>
                                        <li class="m_inputBoxOptionListItem"><a href="javascript:void(0)" onclick="chooseMiningPool(this)">f2pool.com</a></li>
                                        
                                    </ul>
                                    <ul class="m_inputOptionList" id="ethMiningPool">
                                        <li class="m_inputBoxOptionListItem"><a href="javascript:void(0)" onclick="chooseMiningPool(this)">eth.pandaminer.com:81</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
            <?php endif; ?>
            
            
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
                                <a href="/product/detail/<?php echo $this->_var['productinfo']['id']; ?>.html"><img class="productImg" src="<?php echo $this->_var['productinfo']['icon']; ?>" alt="" style="width: 120px;height: 120px"></a>
                                <p class="productTitle"><?php echo $this->_var['productinfo']['title']; ?></p>
                                <input type="hidden" id="product_id" value="<?php echo $this->_var['productinfo']['id']; ?>">
                                <p class="productType"><?php echo $this->_var['productinfo']['pro_spec']; ?></p>
                                <input type="hidden" id="otype" value="<?php echo $this->_var['otype']; ?>">
                            </div>
                            <div class="orderDataList">
                                <span class="orderData">￥<span id="total-price"><?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['productinfo']['price'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></span></span>
                                <span class="orderData"><?php echo $this->_var['amount']; ?></span>
                                <input type="hidden" id="amount" value="<?php echo $this->_var['amount']; ?>">
                                <span class="orderData">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></span>
                            </div>
                        </li>
                        
                    </ul>
                    <div class="orderTotal">
                        <div class="customerNoteBox">
                            <p class="noteTitle">备注：</p>
                            <div class="m_textArea">
                                <textarea name="" cols="30" rows="10" id="remarks" onkeyup="checkRemark(this)"></textarea>
                            </div>
                        </div>
                        <div class="totalBox">
                            <p class="normalPrice"><span class="totalTitle">合计：</span>￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></p>
                            <p class="BTCPrice" id="BTCPrice">(<?php echo $this->_var['productinfo']['price1']; ?> BTC)</p>
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
                                <span class="subtotalItemValue"><?php echo $this->_var['amount']; ?></span>
                            </p>
                            <p class="subtotalItem">
                                <span class="subtotalItemTitle">金额合计：</span>
                                <span class="subtotalItemValue">￥<?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></span>
                            </p>

                            <p class="subtotalItem">
                                <span class="subtotalItemTitle">运费：</span>
                                <span class="subtotalItemValue">到付</span>
                            </p>
                            <p class="subtotalItem subtotalItem_total">
                                <span class="subtotalItemTitle">应付金额：</span>
                                <span class="subtotalItemValue" id="money-to-pay">￥<m id="rmb_price"><?php 
$k = array (
  'name' => 'number_format',
  'v' => $this->_var['total'],
  'f' => '2',
);
echo $k['name']($k['v'],$k['f']);
?></m></span>
                            </p>
                            <p class="subtotalItem subtotalItem_totalBTC">
                                <span class="subtotalItemTitle"></span>
                                <span class="subtotalItemValue" id="btc-to-pay">(<m id="btc_price"><?php echo $this->_var['productinfo']['price1']; ?></m> BTC)</span>
                            </p>
                        </div>
                        <div class="btns">
                            <a href="javascript:void(0)" class="b_btn b_btn1 btnPay" id="<?php if (! $this->_var['otype']): ?>fastpay<?php else: ?>hostingfastpay<?php endif; ?>">立即下单</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script type="text/javascript" src="/js/address.js?v=14"></script>
<script type="text/javascript" src="/js/order.js?v=14"></script>
<script>










</script>

<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

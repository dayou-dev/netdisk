<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>收货地址 比特动力</title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_KEYWORD']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_DESCRIPTION']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
</head>
<body class="page_my page_myAddress">
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
                    <h2 class="m_pageTitle">收货地址</h2>
                    <div class="m_sectionBoxMod">
                        <div class="m_sectionBoxMod_hd">
                            <h3 class="sectionBoxMod_title" id="addresstitle">新增收货地址</h3>
                        </div>
                        <div class="m_sectionBoxMod_bd">
                            <div class="addAdressBox">
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
                                    <div class="m_inputBox inputBox_tel m_inputBox">
                                        <input type="text" placeholder="电话号码" id="phone_number">
                                    </div>
                                </div>
                                <div class="m_inputWrap2 inputWrap_location" style="height:auto;">
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
                                    <div class="m_inputBox m_inputBoxSelect">
                                        <input class="input" type="text" disabled="disabled" value="" id="region" placeholder="区/县">
                                        <input type="hidden" id="region_id" value="">
                                        <a href="javascript:void(0)" class="i_inputDropDownArrow"></a>
                                        <div class="m_inputOptionsWrap" id="region-box">
                                            <ul class="m_inputOptionList" id="region-list">
                                               
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="m_inputBox m_inputBoxSelect" style=" display:none">
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
                                <div class="m_inputWrap2" style="overflow: hidden; width: 90%;">
                                    <p class="m_inputTitle">邮编：</p>
                                    <div class="m_inputBox inputBox_zipCode">
                                        <input type="text" placeholder="" id="zip">
                                    </div>
                                </div>
                                <div class="operationBtns">
                                    <p class="setAsDefault"><input type="checkbox" id="is_default">设置为默认收货地址</p>
                                    <a href="javascript:void(0)" class="b_btn b_btn1 btnSave" id="save-address-btn">保存</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m_sectionBoxMod">
                        <div class="m_sectionBoxMod_hd">
                            <h3 class="sectionBoxMod_title">已有地址</h3>
                        </div>
                        <div class="m_sectionBoxMod_bd">
                            <table class="addressList">
                                <tbody><tr class="titleRow">
                                    <td>收货人：</td>
                                   
                                    <td>地址：</td>
                                    <td>邮编：</td>
                                    <td>电话/手机：</td>
                                    <td>操作：</td>
                                    <td class="extraBtns"></td>
                                </tr>
                                <?php $_from = $this->_var['user_address']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
                                <tr onmouseover="showDefaultBtn( 2416 )" onmouseout="hideDefaultBtn( <?php echo $this->_var['item']['id']; ?> )" id="address-<?php echo $this->_var['item']['id']; ?>">
                                    <td>张灿</td>
                                    <td>中国 <?php echo $this->_var['item']['province']; ?> <?php echo $this->_var['item']['city']; ?> <?php echo $this->_var['item']['region']; ?> <?php echo $this->_var['item']['address']; ?></td>
                                    <td><?php echo $this->_var['item']['zip']; ?></td>
                                    <td><?php echo $this->_var['item']['mobilephone']; ?></td>
                                    <td><a href="javascript:void(0)" onclick="modifyAddress( <?php echo $this->_var['item']['id']; ?> )">修改</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="deleteAddress( <?php echo $this->_var['item']['id']; ?> )">删除</a></td>
                                    <td><a href="javascript:void(0)" class="b_btn b_btn3 hide" id="set-default-address-btn-<?php echo $this->_var['item']['id']; ?>" onclick="setDefaultAddress( <?php echo $this->_var['item']['id']; ?> )">设为默认</a></td>
                                </tr>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                
                            </tbody></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="update-address-id" value="0">
    
       <div class="p_pop p_popTips hide" id="delete-address-box">
        <div class="p_popTitleBox">
            <h2 class="p_popTitle">提示</h2>
        </div>
        <a href="javascript:void(0)" class="i_popClose" id="close-delete-address-btn">关闭</a>
        <div class="p_popContent">
            <p class="tipsTitle">确定删除该地址？</p>
            <input type="hidden" id="delete-address-id" value="">
            <div class="p_popBtns">
                <a href="javascript:void(0)" class="b_btn b_btn3" id="cancel-delete-address-btn">取消</a>
                <a href="javascript:void(0)" class="b_btn b_btn1" id="delete-address-btn">确定</a>
            </div>
        </div>
    </div>
    
   <script type="text/javascript" src="/js/address.js?v=14"></script>
<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

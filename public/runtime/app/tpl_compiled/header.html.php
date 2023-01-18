<script language="javascript">var lang='en';</script>
<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/js/global.js"></script>
<script type="text/javascript" src="/js/login.js"></script>
<script type="text/javascript" src="/js/regist.js"></script>
<script type="text/javascript" src="/js/tn_code.js"></script>
<link rel="stylesheet" href="/css/ax_component.css"/>
<link rel="stylesheet" href="/css/inner.css" />
<link rel="stylesheet" href="/css/code.css" />
<div id="mTopMiddle" class="ct" >
          <div class="ct" id="tmf0">
            <div class="mf" id="tmf2">
              <div id="_ctl0__ctl0_box" class="box7">
              </div>
            </div>
          </div>
          <div class="ct" id="tmf1">
            <div class="ct" id="tmf3">
              <div class="ct_box" id="tmf4">
                <div class="ct" id="tmf5">
                  <div class="mf" id="tmf8">
                    <div id="_ctl0__ctl1_box" class="box2"><a href="/"><img src="/images/logo.png" width="240" height="30" title="<?php 
$k = array (
  'name' => 'app_conf',
  'f' => 'SHOP_NAME',
);
echo $k['name']($k['f']);
?>" align="left" alt="<?php 
$k = array (
  'name' => 'app_conf',
  'f' => 'SHOP_NAME',
);
echo $k['name']($k['f']);
?>" /></a></div>
                  </div>
                </div>
                <div class="ct" id="tmf6">
                  <div class="mf" id="tmf7">
                    <div id="_ctl0__ctl2_box" class="box880_-9516">
                      <ul class="nav">
                        <li class="selected" ><a href="/" target="_self" ><span class="title">Home</span> <span class="subtitle"></span> </a>
                        </li>
                        <li class="" ><a href="/product" target="_self" ><span class="title"> Miners</span> <span class="subtitle"></span> </a></li>
                        <li class="" ><a href="/default/hosting" target="_self" ><span class="title"> Hosting</span> <span class="subtitle"></span> </a></li>
                        <li class="" ><a href="/about" target="_self" ><span class="title"> About</span> <span class="subtitle"></span> </a>
                        </li>
                      <li class="" ><a href="/news" target="_self" ><span class="title"> News</span> <span class="subtitle"></span> </a>
                        </li>
                        <li class="" ><a href="/contact" target="_self" ><span class="title"> Guide</span> <span class="subtitle"></span> </a>
                        </li>
                      </ul>
                      <script type="text/javascript" src="/js/jquery.js"></script>
                      <script>
						$('.subnav').hover(function () {
							$(this).siblings('a').toggleClass('active');
						})
					</script>
                    </div>
                  </div>
                </div>
                <div class="ct f_hd" id="tmf9">
                  <div class="hd_account" <?php if ($this->_var['user_info']): ?>style=" display:block;"<?php endif; ?>>
            
                <div class="account_info">
                    <a href="javascript:void(0)" class="accountNameLink">
                        <span class="accountName"><?php echo $this->_var['user_info']['user_name']; ?></span><span class="i_hd_info_arrow"></span>
                    </a>
                    <ul class="accountNav" style="z-index: 2; display: none;">
                        <li class="accountNavItem"><a href="/account/my_order_list">我的订单</a></li>
                        <li class="accountNavItem"><a href="/account/my_address">收货地址</a></li>
                        <li class="accountNavItem"><a href="/account/my_coupon">优惠券</a></li>
                        <li class="accountNavItem"><a href="/account/my_account">账号设置</a></li>
                        <li class="accountNavItem"><a href="/account/loginout">退出</a></li>
                    </ul>
                </div>
                            
            </div>
                  
                  
                  
                  <div class="mf" id="tmf12">
                    <div id="_ctl0__ctl3_box" class="box8242_-2547">
                      <div class="member_login">
                        <div class="longin_1" id="login_register" <?php if ($this->_var['user_info']): ?>style=" display:none;"<?php endif; ?>><a href="javascript:void(0)" title="Log in" id="login-btn">Log in</a> <a href="javascript:void(0)" title="Sign up" id="regist-btn">Sign up</a> </div>
                        <div class="longin_1" id="shopping_cart" <?php if ($this->_var['user_info']): ?>style=" display:block;"<?php endif; ?>> <a href="/account/my_order_list" title="">My Order</a>
                          <div class="longin_1_number"> <span><?php echo $this->_var['orderCount']; ?></span> </div>
                        </div>
                      </div>
                    </div>
                    <script>
						$("#_ctl0__ctl3_box").parentsUntil("body").css("overflow", "visible");
					</script>
                  </div>
                  <div class="mf" id="tmf10">
                    <div id="_ctl0__ctl4_box" class="box7">
                      <div class="g-hd">
                        <div class="fx">
                          <ul class="m-list clearfix">
                            <li> <a href="/?lan=cn">中文版</a> </li>
                            <li> <a href="/?lan=en" style="color:#34a8eb;">English</a> </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div style="clear:both"></div>
              </div>
            </div>
          </div>
        </div>
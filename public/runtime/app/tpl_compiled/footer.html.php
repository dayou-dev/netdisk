
<div id="mBottom" class="ct">
        <div id="mBottomMiddle" class="ct">
          <div class="mf" id="bmf0">
            <div id="_ctl7__ctl0_box" class="box10">
              <div class="g-ft w-com">
                <div class="m-cnw">
                  <div class="inner clearfix">
                    <dl class="foot-con">
                      <dt> 帮助中心 </dt>
                      <?php $_from = $this->_var['helplist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'rows');if (count($_from)):
    foreach ($_from AS $this->_var['rows']):
?>
                      <dd> <a href="/default/<?php echo $this->_var['rows']['id']; ?>" target="_self"><?php echo $this->_var['rows']['title']; ?></a> </dd>
                      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </dl>
                    <dl class="foot-con">
                      <dt> 技术支持 </dt>
                      <?php $_from = $this->_var['support']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'rows');if (count($_from)):
    foreach ($_from AS $this->_var['rows']):
?>
                      <dd> <a href="/default/<?php echo $this->_var['rows']['id']; ?>" target="_self"><?php echo $this->_var['rows']['title']; ?></a> </dd>
                      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </dl>
                    <dl class="foot-con">
                      <dt> 产品中心 </dt>
                      <dd> <a href="/product" target="_self">矿机</a> </dd>
                      <dd> <a href="/product" target="_self">更多商品</a> </dd>
                    </dl>
                    <dl class="foot-con">
                      <dt> 联系我们 </dt>
					  <dd> 
						<a href="https://www.facebook.com/pg/PowerMiner-481874595562246" target="_Blank">
							<span style="background-image:url(./images/tbico.png);
								width:25px;
								height:27px;
								background-repeat:no-repeat;
								vertical-align: 
								middle;display:inline-block;
								text-indent:-9999px;
								background-position:0px -108px;
								margin-right:5px">
							</span>
						</a>
						<a href="https://twitter.com/miner_power" target="_Blank">
							<span style="background-image:url(./images/tbico.png);
								width:25px;
								height:27px;
								background-repeat:no-repeat;
								vertical-align: 
								middle;display:inline-block;
								text-indent:-9999px;
								background-position:0px -135px;
								margin-right:5px">
							</span>
						</a>
						<a href="mailto:official@powerminer.cn" target="_self">
							<span style="background-image:url(./images/tbico.png);
								width:25px;
								height:27px;
								background-repeat:no-repeat;
								vertical-align: 
								middle;display:inline-block;
								text-indent:-9999px;
								background-position:0px -162px;
								margin-right:5px">
							</span>
						</a>
							<a href="https://jq.qq.com/?_wv=1027&k=5kEUCyg" target="_Blank">
							<span style="background-image:url(./images/tbico.png);
								width:25px;
								height:27px;
								background-repeat:no-repeat;
								vertical-align: 
								middle;display:inline-block;
								text-indent:-9999px;
								background-position:0px -54px;
								margin-right:5px">
							</span>
						</a>
				 	  </dd>
                    </dl>
                    <dl class="foot-con">
                      <dt> 关注公众号 </dt>
                      <dd> <a href="tel:13724322400" target="_self"><img src="/images/ewm.png" alt=""></a> </dd>
                    </dl>
                  </div>
            
                </div>
                <div class="m-cpy">
      <?php if ($this->_var['linklist']): ?><div class="inner i_link clearfix" style="padding:0px; padding-bottom:10px;">友情链接：<?php $_from = $this->_var['linklist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'rows');if (count($_from)):
    foreach ($_from AS $this->_var['rows']):
?>
                      <a href="<?php echo $this->_var['rows']['url']; ?>" target="_blank"><?php echo $this->_var['rows']['title']; ?></a>　
                      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></div><?php endif; ?>
                     <p> 比特动力官网©2018 比特动力科技（深圳）有限公司 | 邮箱：official@powerminer.cn <a href="http://www.miitbeian.gov.cn/" target="_blank" style="font-size:12px;"><?php echo $this->_var['site_info']['ICP_LICENSE']; ?></a> <div style="display:none"><script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1273580159'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s13.cnzz.com/z_stat.php%3Fid%3D1273580159' type='text/javascript'%3E%3C/script%3E"));</script></div></p>
                </div>
              </div>
              <!-- 返回顶部 -->

              <a href="javascript:void(0);" title="返回顶部" class="c-backTop m-mod"></a>
              <script>
			// 返回顶部效果
			$(".c-backTop").click(function () {
					var speed=200;//滑动的速度
					$('body,html').animate({scrollTop: 0}, speed);
					return false;
			 });
			</script>
            </div>
          </div>
        </div>
</div>

    <div class="p_pop p_pop_login" id="login-box">
        <div class="p_popTitleBox">
            <h2 class="p_popTitle">登录</h2>
        </div>
        <a href="javascript:void(0)" class="i_popClose" id="login-box-close">关闭</a>
        <div class="p_popContent">
            <h3 class="i_popLoginLogo">Powerminer</h3> 
            <div class="m_inputWrap">
                <p class="m_inputTitle">用户名/邮箱：</p>
                <div class="m_inputBox">
                    <input type="text" placeholder="" id="login-username">
                    <p class="m_inputErrorTips"></p>
                </div>
            </div>
            <div class="m_inputWrap">
                <p class="m_inputTitle">密码：</p>
                <div class="m_inputBox">
                    <input type="password" placeholder="" id="login-password">
                    <p class="m_inputErrorTips"></p>
                    
                    <a href="javascript:void(0)" class="togglePwdVisibility i_pwd_hidden" id="login-show-password"></a>
                </div>
            </div>
            <!--<div class="m_inputWrap m_inputWrap_veriCode">
                <p class="m_inputTitle">验证码：</p>
                <div class="verifierWrap">
                    <div class="m_inputBox ">
                        <input type="text" id="login-captcha" placeholder="验证码">
                        <input type="hidden" id="login-captcha-id"  value="9n7wKLd07RJRq7ZEQA5D">
                        <p class="m_inputErrorTips show" id="login-captcha-error"></p>
                    </div>
                    <img src="" class="veriCodeImg" id="login-captcha-img">
                    <a href="javascript:void(0)" id="login-captcha-refresh" class="btnRefreshVeri">换一张</a>
                </div>
            </div>!-->
            
            <div class="m_inputWrap m_inputWrap_veriCode">
                <p class="m_inputTitle">验证码：</p>
                 <div class="tncode" style="text-align: center;float: left; margin-bottom:20px;"></div>
            </div>
            
            
            <div class="p_popBtns">
                <a href="javascript:void(0)" class="b_btn b_btn1 btn_b" id="login-confirm-btn">登录</a>
                <input type="hidden" id="callback" value="">
                <input type="hidden" id="returnto" value="">
            </div>
            <div class="moreLoginOptions">
                <a href="javascript:void(0)" class="moreOp_register">注册新账号</a>
                <a href="/account/find_password" class="moreOp_pwdForgot">忘记密码?</a>
            </div>
        </div>
    </div>
    
    <div class="p_pop p_pop_register" id="regist-box">
        <div class="p_popTitleBox">
            <h2 class="p_popTitle">Sign up</h2>
        </div>
        <a href="javascript:void(0)" class="i_popClose" id="regist-box-close">Close</a>
        <div class="p_popContent">
            <div class="m_inputWrap2">
                <p class="m_inputTitle">Login Name：</p>
                <div class="m_inputBox">
                    <input type="text" placeholder="4-17 characters, can not be pure number" id="regist-account">
                    <p class="m_inputErrorTips" id="regist-account-error"></p>
                </div>
            </div>
            <div class="m_inputWrap2">
                <p class="m_inputTitle">Email：</p>
                <div class="m_inputBox">
                    <input type="text" placeholder="Enter Email address" id="regist-email">
                    <p class="m_inputErrorTips" id="regist-email-error"></p>
                </div>
            </div>
            <div class="m_inputWrap2">
                <p class="m_inputTitle">Password：</p>
                <div class="m_inputBox">
                    <input type="password" placeholder="Enter password 6-16 letters/numbers" id="regist-password">
                    <p class="m_inputErrorTips" id="regist-password-error"></p>
                    
                    <a href="javascript:void(0)" class="togglePwdVisibility i_pwd_hidden" id="regist-show-password"></a>
                </div>
            </div>
            <div class="m_inputWrap2">
                <p class="m_inputTitle">Confirm：</p>
                <div class="m_inputBox">
                    <input type="password" placeholder="Confirm" id="regist-rept-password">
                    <p class="m_inputErrorTips" id="regist-rept-password-error"></p>
                    
                    <a href="javascript:void(0)" class="togglePwdVisibility i_pwd_hidden" id="regist-show-rept-password"></a>
                </div>
            </div>
            <div class="m_inputWrap2 m_inputWrap_veriCode" style="display:none;">
                <p class="m_inputTitle">Captcha：</p>
                <div class="verifierWrap">
                    <div class="m_inputBox ">
                        <input type="text" id="regist-captcha" placeholder="Captcha">
                        <input type="hidden" id="regist-captcha-id"  value="ArubbKaQqHF9JC1wfbmF">
                        <p class="m_inputErrorTips show" id="regist-captcha-error"></p>
                    </div>
                    <img src="" class="veriCodeImg" id="regist-captcha-img">
                    <a href="javascript:void(0)" id="regist-captcha-refresh" class="btnRefreshVeri">Renewal</a>
                </div>
            </div>
            
             <div class="m_inputWrap2 m_inputWrap_veriCode">
             <p class="m_inputTitle">Captcha：</p>
             <div class="tncode" style="text-align: center;float: left;"></div>
             </div>
            
            <p class="agreement">
                <input type="checkbox" id="agreement"><label for="">I agree with <a href="/default/4250">Service Agreement</a></label>
            </p>
            <div class="p_popBtns">
                <a href="javascript:void(0)" class="b_btn b_btn1 btn_b" id="regist-confirm-btn">Sign up</a>
            </div>
            <a href="javascript:void(0)" class="toLoginLink" id="toLoginLink">Got an account? Sign in</a>
        </div>
    </div>
    
    <div class="p_pop p_pop_registerResult" id="regist-success-box">
        <div class="p_popTitleBox">
            <h2 class="p_popTitle">Success</h2>
        </div>
        <a href="javascript:void(0)" class="i_popClose" id="regist-success-box-close">Close</a>
        <div class="p_popContent">
            <p class="i_success">成功！</p>
            <p class="result">注册成功!</p>
            <p class="resultTips">我们已将一封验证邮件发送至您的邮箱：<strong><span id="regist-email-tips"></span></strong> 请尽快邮箱点击链接完成验证。</p>
            <div class="exceptionSolving">
                <p class="exceptionTitle">没有收到验证邮件？</p>
                <p class="soloving">1. 请在广告邮件、垃圾邮件目录里找找看；</p>
                <p class="soloving">2. <a href="javascript:void(0)" id="j-resend-email">再次发送验证邮件；</a></p>
                <p class="soloving">3. 如果重新发送验证邮件仍无收到，请联系客服；</p>
            </div>
        </div>
    </div>
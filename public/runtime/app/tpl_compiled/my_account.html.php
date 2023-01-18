<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>收货地址 比特动力</title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_KEYWORD']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_DESCRIPTION']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
</head>
<body class="page_my page_myAcccount">
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
					<h2 class="m_pageTitle">账号设置  <span class="accountInfo"><?php echo $this->_var['user_info']['user_name']; ?>，<?php echo $this->_var['user_info']['email']; ?></span></h2>
					
					<div class="m_sectionBoxMod mod_safeSetting">
						<div class="m_sectionBoxMod_hd">
							<h3 class="sectionBoxMod_title">安全设置</h3>
						</div>
						<div class="m_sectionBoxMod_bd">
							<ul class="safeSettingList">
								<li class="safeSettingItem">
									<p class="safeSettingTitle">
										<span class="i_pwd icon_safe"></span>登录密码
									</p>
									<a href="javascript:void(0)" onclick="showUpdatePasswordBtn()" class="b_btn b_btn3">修改</a>
								</li>
								<li class="safeSettingItem">
									<p class="safeSettingTitle">
										<span class="i_mail icon_safe"></span>邮箱验证
									</p>
									<p class="safeSettingInfo">提现及修改登录密码时需要</p>
									
									<?php if ($this->_var['user_info']['chk_email']): ?><p class="safeSettingState">已验证</p><?php else: ?><p class="safeSettingState safeSettingState_unset">未验证</p>
									<a href="javascript:void(0)" class="b_btn b_btn3" onclick="showEmailCheckBtn()">验证</a><?php endif; ?>
									
								</li>
								
								
								<li class="safeSettingItem">
									<p class="safeSettingTitle">
										<span class="i_tel icon_safe"></span>手机验证
									</p>
									<p class="safeSettingInfo">提现及修改登录密码时需要</p>
									
									<?php if (! $this->_var['user_info']['mobile']): ?><p class="safeSettingState safeSettingState_unset">未设置</p><a href="javascript:void(0)" class="b_btn b_btn3" sending="false" onclick="showPhoneCheckBtn()">设置</a><?php else: ?>
								<a href="javascript:void(0)" class="b_btn b_btn3" sending="false" onclick="showPhoneReCheckBtn()">修改</a>	<?php endif; ?>
									
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
  <script type="text/javascript" src="/js/account.js"></script>
  
    <div class="p_pop p_pop_registerResult" id="email-success-box1">
        <div class="p_popTitleBox">
            <h2 class="p_popTitle">成功</h2>
        </div>
        <a href="javascript:void(0)" class="i_popClose" id="email-success-box-close" onclick="hideEmailCheckBtn();">关闭</a>
        <div class="p_popContent">
            <p class="i_success">成功！</p>
            <p class="result">邮件已发送成功!</p>
            <p class="resultTips">我们已将一封验证邮件发送至您的邮箱：<strong><span id="regist-email-tips"><?php echo $this->_var['user_info']['email']; ?></span></strong> 请尽快邮箱点击链接完成验证。</p>
            <div class="exceptionSolving">
                <p class="exceptionTitle">没有收到验证邮件？</p>
                <p class="soloving">1. 请在广告邮件、垃圾邮件目录里找找看；</p>
                <p class="soloving">2. <a href="javascript:void(0)" id="j-resend-email">再次发送验证邮件；</a></p>
                <p class="soloving">3. 如果重新发送验证邮件仍无收到，请联系客服；</p>
            </div>
        </div>
    </div>
    
  <div class="p_pop p_popResetPwd" id="update-password-box">
		<div class="p_popTitleBox">
			<h2 class="p_popTitle">修改密码</h2>
		</div>
		<a href="javascript:void(0)" onclick="hideUpdatePasswordBtn()" class="i_popClose">关闭</a>
		<div class="p_popContent">
			<div class="m_inputWrap2">
				<p class="m_inputTitle">当前密码：</p>
				<div class="m_inputBox" id="old-pswd-Box">
					<input type="password" placeholder='请填写当前帐号使用密码'  id="j-old-pswd">
					<p class="m_inputErrorTips" id="j-old-pswd-error"></p>
				</div>
			</div>
			<div class="m_inputWrap2">
				<p class="m_inputTitle">新密码：</p>
				<div class="m_inputBox" id="pswd-Box">
					<input type="password" placeholder='填写密码，6~16位数字或字母' id="j-new-pswd">
					<p class="m_inputErrorTips" id="j-new-pswd-error"></p>
				</div>
			</div>
			<div class="m_inputWrap2">
				<p class="m_inputTitle">确认密码：</p>
				<div class="m_inputBox" id="rept-pswd-Box">
					<input type="password" placeholder='填写密码，6~16位数字或字母'  id="j-new-rept-pswd">
					<p class="m_inputErrorTips" id="j-new-rept-pswd-error"></p>
				</div>
			</div>
			<div class="p_popBtns">
				<a href="javascript:void(0)" onclick="hideUpdatePasswordBtn()" class="b_btn b_btn3">取消</a>
				<a href="javascript:void(0)" id="UpdatePassword" class="b_btn b_btn1">确定</a>
			</div>
		</div>
	</div>

	<div class="p_pop p_popResetPwd" id="money-password-box">
		<div class="p_popTitleBox">
			<h2 class="p_popTitle">设置资金密码</h2>
			<h3 class="p_popSubTitle">为了让您的资金账户更加安全，请设置资金密码。</h3>
		</div>
		<a href="javascript:void(0)" onclick="hideMoneyPasswordBtn()" class="i_popClose">关闭</a>
		<div class="p_popContent">
			<div class="m_inputWrap2">
				<p class="m_inputTitle">填写密码：</p>
				<div class="m_inputBox" id="money-pwd-box">
					<input type="password" placeholder='填写密码，6~16位数字或字母'  id="money-pwd">
					<p class="m_inputErrorTips" id="money-pswd-error"></p>
				</div>
			</div>
			<div class="m_inputWrap2">
				<p class="m_inputTitle">确认密码：</p>
				<div class="m_inputBox" id="money-rept-pwd-box">
					<input type="password" placeholder='填写密码，6~16位数字或字母'  id="money-rept-pwd">
					<p class="m_inputErrorTips" id="money-rept-pswd-error"></p>
				</div>
			</div>
			<div class="p_popBtns">
				<a href="javascript:void(0)" onclick="hideMoneyPasswordBtn()" class="b_btn b_btn3">取消</a>
				<a href="javascript:void(0)" class="b_btn b_btn1" id="money-password-confirm">确定</a>
			</div>
		</div>
	</div>

	

	
		 
	    <div class="p_pop p_popResetPwd hide" id="phone_check_box" style="margin-top: -131.5px; margin-left: -240px;">
		<div class="p_popTitleBox">
			<h2 class="p_popTitle">绑定手机</h2>
		</div>
		<a href="javascript:void(0)" class="i_popClose" onclick="hidePhoneCheckBtn()">关闭</a>
		<div class="p_popContent">
			<div class="m_inputWrap2 m_inputWrap_tel">
				<p class="m_inputTitle">手机号：</p>
				<div class="m_inputBox">
					<div class="areaCodeBox">
						<a class="areaCode" href="###"><span id="set-phone-country-code">+86</span><span class="i_telAreaCodeArrow"></span></a>
						<div class="areaCodeListWrap">
							<ul class="areaCodeList">
                              
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Afghanistan<span class="itemCode">+93</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Algeria<span class="itemCode">+213</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Andorra<span class="itemCode">+376</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Angola<span class="itemCode">+244</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Australia<span class="itemCode">+61</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Austria<span class="itemCode">+43</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Bahrain<span class="itemCode">+973</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Belgium<span class="itemCode">+32</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Brunei<span class="itemCode">+673</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Bulgaria<span class="itemCode">+359</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Burkina Faso<span class="itemCode">+226</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Cambodia<span class="itemCode">+855</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Canada<span class="itemCode">+1</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Central African Republic<span class="itemCode">+236</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Chile<span class="itemCode">+56</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">China<span class="itemCode">+86</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Cocos (Keeling) Islands<span class="itemCode">+61</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Costa Rica<span class="itemCode">+506</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Cuba<span class="itemCode">+53</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Cyprus<span class="itemCode">+357</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Czech Republic<span class="itemCode">+420</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Denmark<span class="itemCode">+45</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Dominica<span class="itemCode">+1767</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Dominican Republic<span class="itemCode">+1809</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">DR Congo<span class="itemCode">+243</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Egypt<span class="itemCode">+20</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Eritrea<span class="itemCode">+291</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Estonia<span class="itemCode">+372</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Ethiopia<span class="itemCode">+251</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Finland<span class="itemCode">+358</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">France<span class="itemCode">+33</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Germany<span class="itemCode">+49</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Gibraltar<span class="itemCode">+350</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Greece<span class="itemCode">+30</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Greenland<span class="itemCode">+299</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Guernsey<span class="itemCode">+44</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Honduras<span class="itemCode">+504</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Hong Kong<span class="itemCode">+852</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Hungary<span class="itemCode">+36</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Iceland<span class="itemCode">+354</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">India<span class="itemCode">+91</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Indonesia<span class="itemCode">+62</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Iraq<span class="itemCode">+964</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Ireland<span class="itemCode">+353</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Israel<span class="itemCode">+972</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Italy<span class="itemCode">+39</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Jamaica<span class="itemCode">+1876</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Japan<span class="itemCode">+81</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Jersey<span class="itemCode">+44</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Jordan<span class="itemCode">+962</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Kenya<span class="itemCode">+254</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Kuwait<span class="itemCode">+965</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Laos<span class="itemCode">+856</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Latvia<span class="itemCode">+371</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Lebanon<span class="itemCode">+961</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Liberia<span class="itemCode">+231</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Libya<span class="itemCode">+218</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Lithuania<span class="itemCode">+370</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Luxembourg<span class="itemCode">+352</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Malaysia<span class="itemCode">+60</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Mali<span class="itemCode">+223</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Malta<span class="itemCode">+356</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Mexico<span class="itemCode">+52</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Monaco<span class="itemCode">+377</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Netherlands<span class="itemCode">+31</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">New Zealand<span class="itemCode">+64</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Niger<span class="itemCode">+227</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Norway<span class="itemCode">+47</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Oman<span class="itemCode">+968</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Pakistan<span class="itemCode">+92</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Panama<span class="itemCode">+507</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Poland<span class="itemCode">+48</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Portugal<span class="itemCode">+351</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Qatar<span class="itemCode">+974</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Republic of the Congo<span class="itemCode">+242</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Romania<span class="itemCode">+40</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Russia<span class="itemCode">+7</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">San Marino<span class="itemCode">+378</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Sierra Leone<span class="itemCode">+232</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Singapore<span class="itemCode">+65</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Slovakia<span class="itemCode">+421</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Slovenia<span class="itemCode">+386</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">South Africa<span class="itemCode">+27</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">South Korea<span class="itemCode">+82</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Spain<span class="itemCode">+34</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Sri Lanka<span class="itemCode">+94</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Sudan<span class="itemCode">+249</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Sweden<span class="itemCode">+46</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Switzerland<span class="itemCode">+41</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Thailand<span class="itemCode">+66</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Togo<span class="itemCode">+228</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Trinidad and Tobago<span class="itemCode">+1868</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Turkey<span class="itemCode">+90</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Uganda<span class="itemCode">+256</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Ukraine<span class="itemCode">+380</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">United Arab Emirates<span class="itemCode">+971</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">United Kingdom<span class="itemCode">+44</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">United States<span class="itemCode">+1</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Vatican City<span class="itemCode">+3906698</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Venezuela<span class="itemCode">+58</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Vietnam<span class="itemCode">+84</span></a>
                                </li>
                             
                            </ul>
						</div>
					</div>
					<input type="text" placeholder="" id="set-phone-number">
				</div>
			</div>
			<div class="m_inputWrap2 m_inputWrap_sms">
				<p class="m_inputTitle">短信验证码：</p>
				<div class="smsWrap">
					<div class="m_inputBox">
						<input type="text" placeholder="" id="set-phone-code">
					</div>

					
					<a href="javascript:void(0)" class="b_btn b_btn2 btnSendSMS" id="set-phone-send-code">发送验证码</a>
					

				</div>
			</div>
			<div class="p_popBtns">
				<a href="javascript:void(0)" class="b_btn b_btn1" id="j-set-phone-submit">确定</a>
			</div>
		</div>
	</div>
    
    
    
    <div class="p_pop p_popResetPwd pop_changeTel" id="phone_re_check_box" style="margin-top: -172px; margin-left: -240px;"> 
		<div class="p_popTitleBox">
			<h2 class="p_popTitle">修改手机</h2>
			<h2 class="p_popSubTitle">为了您的资产安全，修改手机后24小时内不允许提币</h2>
		</div>
		<a href="javascript:void(0)" class="i_popClose" onclick="hidePhoneReCheckBtn()">关闭</a>
		<div class="p_popContent">
			<div class="m_inputWrap2 m_inputWrap_sms">
				<p class="m_inputTitle">旧手机验证码：</p>
				<div class="smsWrap">
					<div class="m_inputBox">
						<input type="text" placeholder="" id="reset-phone-old-code">
					</div>
				    
					<a href="javascript:void(0)" class="b_btn b_btn2 btnSendSMS" id="j-reset-send-old-code">发送验证码</a>
					
					
					
				</div>
			</div>
			<div class="m_inputWrap2 m_inputWrap_tel">
				<p class="m_inputTitle">手机号：</p>
				<div class="m_inputBox">
					<div class="areaCodeBox">
						
						<a class="areaCode" href="###"><span id="reset-phone-country-code">+86</span><span class="i_telAreaCodeArrow"></span></a>
						<div class="areaCodeListWrap">
							<ul class="areaCodeList">
                              
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Afghanistan<span class="itemCode">+93</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Algeria<span class="itemCode">+213</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Andorra<span class="itemCode">+376</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Angola<span class="itemCode">+244</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Australia<span class="itemCode">+61</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Austria<span class="itemCode">+43</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Bahrain<span class="itemCode">+973</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Belgium<span class="itemCode">+32</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Brunei<span class="itemCode">+673</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Bulgaria<span class="itemCode">+359</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Burkina Faso<span class="itemCode">+226</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Cambodia<span class="itemCode">+855</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Canada<span class="itemCode">+1</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Central African Republic<span class="itemCode">+236</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Chile<span class="itemCode">+56</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">China<span class="itemCode">+86</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Cocos (Keeling) Islands<span class="itemCode">+61</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Costa Rica<span class="itemCode">+506</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Cuba<span class="itemCode">+53</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Cyprus<span class="itemCode">+357</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Czech Republic<span class="itemCode">+420</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Denmark<span class="itemCode">+45</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Dominica<span class="itemCode">+1767</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Dominican Republic<span class="itemCode">+1809</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">DR Congo<span class="itemCode">+243</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Egypt<span class="itemCode">+20</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Eritrea<span class="itemCode">+291</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Estonia<span class="itemCode">+372</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Ethiopia<span class="itemCode">+251</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Finland<span class="itemCode">+358</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">France<span class="itemCode">+33</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Germany<span class="itemCode">+49</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Gibraltar<span class="itemCode">+350</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Greece<span class="itemCode">+30</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Greenland<span class="itemCode">+299</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Guernsey<span class="itemCode">+44</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Honduras<span class="itemCode">+504</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Hong Kong<span class="itemCode">+852</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Hungary<span class="itemCode">+36</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Iceland<span class="itemCode">+354</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">India<span class="itemCode">+91</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Indonesia<span class="itemCode">+62</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Iraq<span class="itemCode">+964</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Ireland<span class="itemCode">+353</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Israel<span class="itemCode">+972</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Italy<span class="itemCode">+39</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Jamaica<span class="itemCode">+1876</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Japan<span class="itemCode">+81</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Jersey<span class="itemCode">+44</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Jordan<span class="itemCode">+962</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Kenya<span class="itemCode">+254</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Kuwait<span class="itemCode">+965</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Laos<span class="itemCode">+856</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Latvia<span class="itemCode">+371</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Lebanon<span class="itemCode">+961</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Liberia<span class="itemCode">+231</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Libya<span class="itemCode">+218</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Lithuania<span class="itemCode">+370</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Luxembourg<span class="itemCode">+352</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Malaysia<span class="itemCode">+60</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Mali<span class="itemCode">+223</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Malta<span class="itemCode">+356</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Mexico<span class="itemCode">+52</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Monaco<span class="itemCode">+377</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Netherlands<span class="itemCode">+31</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">New Zealand<span class="itemCode">+64</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Niger<span class="itemCode">+227</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Norway<span class="itemCode">+47</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Oman<span class="itemCode">+968</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Pakistan<span class="itemCode">+92</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Panama<span class="itemCode">+507</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Poland<span class="itemCode">+48</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Portugal<span class="itemCode">+351</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Qatar<span class="itemCode">+974</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Republic of the Congo<span class="itemCode">+242</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Romania<span class="itemCode">+40</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Russia<span class="itemCode">+7</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">San Marino<span class="itemCode">+378</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Sierra Leone<span class="itemCode">+232</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Singapore<span class="itemCode">+65</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Slovakia<span class="itemCode">+421</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Slovenia<span class="itemCode">+386</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">South Africa<span class="itemCode">+27</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">South Korea<span class="itemCode">+82</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Spain<span class="itemCode">+34</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Sri Lanka<span class="itemCode">+94</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Sudan<span class="itemCode">+249</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Sweden<span class="itemCode">+46</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Switzerland<span class="itemCode">+41</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Thailand<span class="itemCode">+66</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Togo<span class="itemCode">+228</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Trinidad and Tobago<span class="itemCode">+1868</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Turkey<span class="itemCode">+90</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Uganda<span class="itemCode">+256</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Ukraine<span class="itemCode">+380</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">United Arab Emirates<span class="itemCode">+971</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">United Kingdom<span class="itemCode">+44</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">United States<span class="itemCode">+1</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Vatican City<span class="itemCode">+3906698</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Venezuela<span class="itemCode">+58</span></a>
                                </li>
                             
                                <li class="areaCodeItem">
                                <a href="javascript:void(0)" class="country-item">Vietnam<span class="itemCode">+84</span></a>
                                </li>
                             
                            </ul>
						</div>

					</div>
					<input type="text" placeholder="" id="reset-phone-number">
				</div>
			</div>
			<div class="m_inputWrap2 m_inputWrap_sms">
				<p class="m_inputTitle">短信验证码：</p>
				<div class="smsWrap">
					<div class="m_inputBox">
						<input type="text" placeholder="" id="reset-phone-new-code">
					</div>
					
					<a href="javascript:void(0)" class="b_btn b_btn2 btnSendSMS" id="j-reset-send-new-code">发送验证码</a>
					
					
					
				</div>
			</div>
			<div class="p_popBtns">
				<a href="javascript:void(0)" class="b_btn b_btn1" id="j-reset-phone-submit">确定</a>
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

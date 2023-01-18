<div class="sidebar-nav">
    <ul>
    	<li><a href="javascript:;" data-target=".dashboard-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-dashboard"></i> 文章管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="dashboard-menu nav nav-list collapse">
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=articleCate"><span class="fa fa-caret-right"></span> 文章分类</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=article"><span class="fa fa-caret-right"></span> 文章列表</a></li>
            </ul>
        </li>
        
    	<li><a href="javascript:;" data-target=".company-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-building"></i> 企业信息管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="company-menu nav nav-list collapse">
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=companyCate"><span class="fa fa-caret-right"></span> 信息分类</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=company"><span class="fa fa-caret-right"></span> 信息列表</a></li>
            </ul>
        </li>
        
    	<li><a href="javascript:;" data-target=".product-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-trophy"></i> 产品管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="product-menu nav nav-list collapse">
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=moneyCate"><span class="fa fa-caret-right"></span> 币种分类</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=productCate"><span class="fa fa-caret-right"></span> 产品分类</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=product"><span class="fa fa-caret-right"></span> 产品列表</a></li>
            </ul>
        </li>
        
    	<li><a href="javascript:;" data-target=".order-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-star"></i> 订单管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="order-menu nav nav-list collapse">
                   <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=order"><span class="fa fa-caret-right"></span> 订单列表</a></li>
                   <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=order&order_status=0"><span class="fa fa-caret-right"></span> 待审核订单</a></li>
                   <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=order&act=trash"><span class="fa fa-caret-right"></span> 订单回收站</a></li>
            </ul>
        </li>
        
    	<li><a href="javascript:;" data-target=".login-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-fighter-jet"></i> 广告管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="login-menu nav nav-list collapse">
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=advCate"><span class="fa fa-caret-right"></span> 广告分类</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=adv"><span class="fa fa-caret-right"></span> 广告列表</a></li>
            </ul>
        </li>
        
    	<li><a href="javascript:;" data-target=".links-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-link"></i> 链接管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="links-menu nav nav-list collapse">
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=linkCate"><span class="fa fa-caret-right"></span> 链接分类</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=link"><span class="fa fa-caret-right"></span> 链接列表</a></li>
            </ul>
        </li>
        
    	<li><a href="javascript:;" data-target=".email-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-envelope"></i> 邮件管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="email-menu nav nav-list collapse">
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=mail_server"><span class="fa fa-caret-right"></span> 邮件服务器列表</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=sms_list&st=1"><span class="fa fa-caret-right"></span> 邮件列表</a></li>
            </ul>
        </li>
    	<li><a href="javascript:;" data-target=".sms-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-comment"></i> 短信管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="sms-menu nav nav-list collapse">
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=smsConf"><span class="fa fa-caret-right"></span> 短信接口列表</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=sms_list"><span class="fa fa-caret-right"></span> 短信列表</a></li>
            </ul>
        </li>
        
    	<li><a href="javascript:;" data-target=".user-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-user"></i> 会员管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="user-menu nav nav-list collapse">
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=user_group"><span class="fa fa-caret-right"></span> 会员分组</a></li>
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=user"><span class="fa fa-caret-right"></span> 会员列表</a></li>
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=user&act=trash"><span class="fa fa-caret-right"></span> 会员回收站</a></li>
                
            </ul>
        </li>
        
    	<li><a href="javascript:;" data-target=".coupon-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-credit-card"></i> 优惠券管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="coupon-menu nav nav-list collapse">
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=coupon"><span class="fa fa-caret-right"></span> 优惠券列表</a></li>
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=coupon&act=trash"><span class="fa fa-caret-right"></span> 优惠券回收站</a></li>
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=user_coupon"><span class="fa fa-caret-right"></span> 会员优惠券</a></li>
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=user_coupon&act=trash"><span class="fa fa-caret-right"></span> 会员优惠券回收站</a></li>
            </ul>
        </li>
        
    	<li><a href="javascript:;" data-target=".admin-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-users"></i> 管理员<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="admin-menu nav nav-list collapse">
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=role"><span class="fa fa-caret-right"></span> 管理员分组列表</a></li>
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=role&act=trash"><span class="fa fa-caret-right"></span> 管理员分组回收站</a></li>
                <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=admin"><span class="fa fa-caret-right"></span> 管理员列表</a></li>
                <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=admin&act=trash"><span class="fa fa-caret-right"></span> 管理员回收站</a></li>
                
            </ul>
        </li>
        
        <li><a href="javascript:;" data-target=".system-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-gears"></i> 系统管理<i class="fa fa-collapse"></i></a></li>
        <li>
            <ul class="system-menu nav nav-list collapse">
                   
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=system"><span class="fa fa-caret-right"></span> 系统配置</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=admin&act=change_password"><span class="fa fa-caret-right"></span> 修改密码</a></li>
                   <li ><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=log"><span class="fa fa-caret-right"></span> 登录日志</a></li>
            </ul>
        </li>
        
        <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=index&act=loginout" class="nav-header"><i class="fa fa-fw fa-power-off"></i> 退出系统</a></li>
    </ul>
    </div>
    
    <script language="javascript">
	var nurl=location.href.split(".com");
	var aurl=nurl[1];
	if(nurl[1].indexOf("?")>-1){
		var aurl_i=nurl[1].split("?")	;
		aurl=aurl_i[1];
	}
	//alert($(".sidebar-nav a").length)
	$(function(){
		for(var i=0;i<$(".sidebar-nav a").length;i++){
			//if(i<5) alert(nurl[1])
			if($(".sidebar-nav a").eq(i).attr("href")==nurl[1]){
				//$("sidebar-nav a").eq(i).addClass('curr');alert(aurl)
				var scl=$(".sidebar-nav a").eq(i).parent().parent().attr("class").split(" ");
				$("[data-target='."+scl[0]+"']").removeClass("collapsed");
				$("."+scl[0]+"").addClass("in");
				$("."+scl[0]+"").css("height","auto");
				//alert(scl[0])
			}
		}
	})
	
	
	//sidebar-nav
    </script>
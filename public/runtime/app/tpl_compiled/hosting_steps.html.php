<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->_var['about']['title']; ?>_比特动力</title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_KEYWORD']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_DESCRIPTION']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.cot_p p {
    text-align: left;}
</style>
</head>
<body>
<!-- 头部 -->
  <div id="mBody2">
    <div id="mOuterBox">
      <div id="mTop" class="ct">
        
        <?php echo $this->fetch('header.html'); ?>

<!-- 中间内容 -->

    <div class="f_bd tggz">
        <div class="f_wrapper">
<h2 class="m_pageTitle">托管规则</h2>


            <h3>服务</h3>
            <p>1.提供托管服务的机型有：Powerminer B3 Plus，Powerminer B5 Plus</p>
            <p>2.托管属于附加服务，提供托管服务的一方不对矿机在矿池的运行算力及在矿池的收益作出承诺</p>
            <p>3.因考虑矿场管理安全因素，不对用户开放远程控制权限</p>
             <p>4. 熊猫矿机保留托管规则的所有解释权</p>

            <h3>电费</h3>
             <p>1.电费0.65元/度，按照理论功耗计算电费:</p>
             <p>&nbsp&nbsp&nbsp&nbspPowerminer B3 Plus理论功耗：1.35kW（ETH挖矿）</p>
             <p>&nbsp&nbsp&nbsp&nbspPowerminer B5 Plus理论功耗：0.8kW（ETH挖矿）</p>
             <p>&nbsp&nbsp以ETH挖矿为例，单台Powerminer B3 Plus每日电费为：0.65元/kWh*1.35kW*24小时=21.06元</p>
             <p>2.每月初结算上个月的电费，电费账单通过邮件发送至注册邮箱地址</p>
             <p>3.在官方发送电费账单后，请于7天内缴清电费，未及时缴清电费的将停机处理</p>
             <p>4.因电站停电而导致矿场停电的，停电期间不计算电费（以电站所提供停电时间为准）</p>

            <h3>部署</h3>
             <p>1.托管的矿机运送至矿场后，将进行批量部署，部署时间约为3~5个工作日</p>
             <p>2.提供托管服务的一方将记录矿机上架时间，并以此时间为准开始计算电费，时间精确到1天，不足一天者按一天计算</p>
             <p>3.每台上架的矿机将以机架编号作为矿工名</p>

            <h3>维护</h3>
             <p>1.矿场有专业人员对矿机进行日常维护</p>
             <p>2.若矿机出现停机、算力过低等异常情况，可通过矿工名向矿场人员报修</p>
             <p>3.矿场人员在收到报修请求后将会进行处理，处理完成时间取决于机器故障情况</p>
             <p>4.提供托管服务的一方不承担因机器维修而导致的收益损失，且电费仍按照理论功耗进行计算（维修时间超过15天的特殊处理）</p>

            <h3>变更</h3>
             <p style="margin-bottom:0px">1.取消矿机托管
             <ul style="list-style-type:disc;margin-left:20px;">
                 <li>联系客服（微信ID：Powerminer）结算电费</li>
                 <li>填写取消托管信息登记表发送至hosting@Powerminer.com（邮件标题为姓名+取消托管）</li>
                 <li>点击下载：<a href="#" target="_blank" class="btn btn2 btnOrder">取消托管信息登记表</a></li>
                 <li>寄送矿机所产生的运费由用户自行承担</li>
             </ul></p>
             <p style="margin-bottom:0px">2.更改托管信息
             <ul style="list-style-type:disc;margin-left:20px;">
                 <li>填写托管信息更改表发送至hosting@Powerminer.com（邮件标题为姓名+更改操作+矿机编号）</li>
                 <li>点击下载：<a href="#" target="_blank" class="btn btn2 btnOrder">更改托管信息登记表</a></li>
             </ul></p>
             <p style="margin-bottom:0px">3. 矿机转让
             <p style="margin-bottom:0px">转让数量大于等于20台步骤如下：
             <ul style="list-style-type:disc;margin-left:20px;">
                 <li>联系客服（微信ID：Powerminer）结算电费</li>
                 <li>填写托管信息转让登记表至hosting@Powerminer.com（邮件标题为姓名+转让托管）</li>
                 <li>点击下载：<a href="#" target="_blank" class="btn btn2 btnOrder">托管信息转让登记表</a></li>
             </ul></p>
            <p style="margin-bottom:0px">转让数量小于20台步骤如下：
             <ul style="list-style-type:disc;margin-left:20px;">
                 <li>用户可以选择发货走，见取消矿机托管步骤</li>
                 <li>用户可以选择联系客服（微信ID：Powerminer），由官方回收矿机，回收价格参照市场行情和机器过保修期与否</li>
             </ul></p>
            </p>

            <h3>风险</h3>
             <p>1.矿场可能停电</p>
             <p>2.矿场的局域网和与外部通信的网络都有可能会发生故障</p>
             <p>3.由于法律政策、战争、地震、火灾和电力故障等不可抗原因导致矿场无法继续运营时，提供托管服务的一方不承担赔偿责任。</p>

            <h3>选择托管服务即默认已阅读并同意以上条款。</h3>



        </div>
    </div>
    


<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

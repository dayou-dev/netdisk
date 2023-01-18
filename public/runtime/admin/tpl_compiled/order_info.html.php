<!doctype html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>管理中心</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/webadmin/Tpl/default/lib/font-awesome/css/font-awesome.css">

    <script src="/webadmin/Tpl/default/lib/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="/webadmin/Tpl/default/lib/jQuery-Knob/js/jquery.knob.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $(".knob").knob();
        });
    </script>
    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/css/ui-dialog.css" />
    <script type="text/javascript" src="/webadmin/Tpl/default/js/dialog-plus-min.js"></script>
	
    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/stylesheets/theme.css">
    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/stylesheets/premium.css">
    

    
 <style type="text/css">

td.narrow-label {
    text-align: right;
    vertical-align: top;
    font-weight: bold;
    padding: 5px 1em;
    width: 15%;
}
</style>
</head>

<body >
<div class="theme-blue"><?php echo $this->fetch('top.html'); ?></div>
    <?php echo $this->fetch('menu.html'); ?>

    <div class="content">
        <div class="header">
            
            <h1 class="page-title">订单管理</h1>
                    <ul class="breadcrumb">
            <li><a href="index.html">管理中心</a> </li>
            <li class="active">订单<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></li>
        </ul>

        </div>
        <div class="main-content">
            
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">订单<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></a></li>
</ul>

<div class="row">
  <div class="col-md-4" style="width: 100%;">
    <br>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active">
      <form id="formval1" action="<?php echo $this->_var['webadmin']; ?>?ctl=order&act=<?php if ($this->_var['vo']['id']): ?>update<?php else: ?>insert<?php endif; ?>" method="post">
  
  
  <div id="info-div">

    
<table width="100%" id="general-table">
  <tbody><tr>
    <td height="46" colspan="4" align="center"><div style="height:30px; line-height:30px; background-color:#d2d2dd"><strong>商品清单</strong></div></td>
    </tr>
</tbody></table>

<table width="100%" border="1" cellpadding="0" cellspacing="0" id="general-table" bordercolor="#CCCCCC">
  <tbody><tr>
        <td width="25%" height="35" align="center" bgcolor="#F5F5F5">商品名称</td>
        <td width="25%" align="center" bgcolor="#F5F5F5">单价</td>
        <td width="25%" align="center" bgcolor="#F5F5F5">数量</td>
        <td width="25%" align="center" bgcolor="#F5F5F5">合计</td>
      </tr>
      
      <?php 
      $pmoney=0;
      $psum=0;
      ?>
      <?php $_from = $this->_var['order_product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
<tr height="35">
        <td height="35" align="center">
          <a href="<?php echo $this->_var['webadmin']; ?>?ctl=product&act=edit&id=<?php echo $this->_var['item']['id']; ?>" target="_blank"><?php echo $this->_var['item']['title']; ?></a></td>
        <td align="center"><?php echo $this->_var['item']['price']; ?></td>
        <td align="center"><?php echo $this->_var['item']['quantity']; ?></td>
        <td align="center"><?php echo $this->_var['item']['price']*$this->_var['item']['quantity'];?></td>
      </tr>
<?php 
                    $pmoney+=round($this->_var['item']['price']*$this->_var['item']['quantity'],2);
                    $psum+=$this->_var['quantity'];

?>      
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<tr>
  <td height="35" colspan="4" align="right" bgcolor="#F5F5F5" style="padding-right:10px; font-size:14px; font-weight:bold;">运费：<font color="#FF0000">0.00</font> 　商品金额总计：<font color="#FF0000"><?php echo $pmoney;?></font></td>
  </tr>      </tbody></table>    
    
    
    <table width="100%" id="general-table">
  <tbody><tr>
    <td height="46" colspan="2" align="center"><div style="height:30px; line-height:30px; background-color:#d2d2dd"><strong>订单详细</strong></div></td>
    </tr>
  <tr>
        <td height="35" align="right" class="narrow-label">订单编号</td>
        <td><?php echo $this->_var['vo']['ordernu']; ?></td>
      </tr>
<tr>
        <td height="35" align="right" class="narrow-label">下单日期</td>
        <td><?php 
$k = array (
  'name' => 'to_date',
  'f' => $this->_var['vo']['create_time'],
);
echo $k['name']($k['f']);
?></td>
      </tr>

        <tr><td height="35" align="right" class="narrow-label">联系人姓名</td>
        <td><?php echo $this->_var['vo']['fullname']; ?></td>
      </tr> 
<tr>
        <td height="35" align="right" class="narrow-label">国家</td>
        <td><?php if ($this->_var['vo']['country']): ?><?php echo $this->_var['vo']['country']; ?><?php else: ?>中国<?php endif; ?></td>
      </tr> 
<tr>
        <td height="35" align="right" class="narrow-label">省/州</td>
        <td><?php echo $this->_var['vo']['province']; ?></td>
      </tr>       
<tr>
        <td height="35" align="right" class="narrow-label">城市</td>
        <td><?php echo $this->_var['vo']['city']; ?></td>
      </tr> 
      <tr>
        <td height="35" align="right" class="narrow-label">县/区</td>
        <td><?php echo $this->_var['vo']['zone']; ?></td>
      </tr>
      <tr>
        <td height="35" align="right" class="narrow-label">详细地址</td>
        <td><?php echo $this->_var['vo']['address_1']; ?></td>
      </tr><tr>
        <td height="35" align="right" class="narrow-label">Email</td>
        <td><?php echo $this->_var['vo']['email']; ?></td>
      </tr> 
      <tr>
        <td height="35" align="right" class="narrow-label">联系电话</td>
        <td>+<?php echo $this->_var['vo']['phone_prefix']; ?> <?php echo $this->_var['vo']['telephone']; ?></td>
      </tr>


      <tr>
        <td height="35" align="right" class="narrow-label">配送方式</td>
        <td><?php if ($this->_var['vo']['ordey_type']): ?>拖管<?php else: ?>发货<?php endif; ?></td>
      </tr>
      <tr>
        <td height="35" align="right" class="narrow-label">订单状态</td>
        <td><input type="radio" name="order_status" value="0">
          待审核
          <input type="radio" name="order_status" value="1">
          等待发货  <input type="radio" name="order_status" value="2">
          已发货<input type="radio" name="order_status" value="3">
          订单完成<input type="radio" name="order_status" value="4" >
          订单取消         </td>
      </tr>
  <tr>
    <td height="35" align="right" class="narrow-label">发货单号</td>
    <td><input name="shippingNu" type="text" value="<?php echo $this->_var['vo']['shippingNu']; ?>" size="40"></td>
    </tr>
  <tr>
    <td height="35" align="right" class="narrow-label">订单备注</td>
    <td><textarea cols="100" id="content" name="content" rows="10"><?php echo $this->_var['vo']['content']; ?>
</textarea><input type="hidden" name="id" value="<?php echo $this->_var['vo']['id']; ?>"></td>
      </tr>

    </tbody></table>
   
    
  </div>
  
  
        <input type="hidden" name="id" id="id" value="<?php echo $this->_var['vo']['id']; ?>">
        </form>
      </div>


      
    </div>

    <div class="btn-toolbar list-toolbar">
      <button class="btn btn-primary" onClick="submitform1()"><i class="fa fa-save"></i> 保存</button>
      <a href="<?php echo $this->_var['webadmin']; ?>?ctl=order" data-toggle="modal" class="btn btn-danger">取消</a>
      
    </div>
  </div>
</div>



            <?php echo $this->fetch('footer_copytxt.html'); ?>
        </div>
    </div>
    
    
    
<script type="text/javascript">
function submitform1(){
	$("#formval1").submit();	
}

$("[name='order_status']").eq("<?php echo intval($this->_var['vo']['order_status']);?>").attr("checked","checked");
</script>


<?php echo $this->fetch('footer.html'); ?>

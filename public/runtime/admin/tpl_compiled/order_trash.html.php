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
    
    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/css/laydate1.css" />
    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/css/laydate.css" />
    <script type="text/javascript" src="/webadmin/Tpl/default/js/laydate.js"></script>
    
 <style type="text/css">
.stxt{height:50px; border-bottom:solid 1px #ddd; line-height:30px;background-color:#F7F7F7; padding:10px; text-align:right;}
</style>   

</head>

<body >
<div class="theme-blue"><?php echo $this->fetch('top.html'); ?></div>
    <?php echo $this->fetch('menu.html'); ?>

    <div class="content">
        <div class="header">
            
            <h1 class="page-title">订单列表列表</h1>
                    <ul class="breadcrumb">
            <li><a href="index.html">管理中心</a> </li>
            <li class="active">订单列表列表</li>
        </ul>

        </div>
        <div class="main-content">
            
<div class="btn-toolbar list-toolbar">
    <form class="form-inline" id="formval" action="<?php echo $this->_var['webadmin']; ?>?ctl=order" method="post">
    <button class="btn btn-default" onClick="checkAll(this);" type="button"><i class="fa fa-square-o"></i> 全选</button>
    <button class="btn btn-default" type="button" onClick="restore()"><i class="fa fa-plus"></i> 恢复</button>
    <button class="btn btn-default" type="button" onClick="delsel()"><i class="fa fa-trash-o"></i> 彻底删除</button>
          <div class="search_txt">  

    
<select name="order_status" class="form-control select_i" >
			<option value="">状态</option>
			<option value="0">待审核</option>
			<option value="1">等待发货</option>
            <option value="2">已发货</option>
            <option value="3">订单完成</option>
            <option value="4">订单取消</option>
		</select>    
           

<input name="order_stime" type="text" id="order_stime" class="input-xlarge form-control laydate-icon" placeholder="开始时间" readonly="readonly" value="<?php echo $_REQUEST['order_stime'];?>" style="margin-right:3px;cursor:pointer" onClick="laydate({istime: true, format: 'YYYY-MM-DD'})">
        <input name="order_etime" type="text" id="order_etime" class="input-xlarge form-control laydate-icon" readonly="readonly" placeholder="结束时间" value="<?php echo $_REQUEST['order_etime'];?>" style="margin-right:3px;cursor:pointer" onClick="laydate({istime: true, format: 'YYYY-MM-DD'})">
        <input name="wordkey" type="text" id="wordkey" class="input-xlarge form-control" placeholder="订单号" value="<?php echo $_REQUEST['wordkey'];?>" style="margin-right:3px;">
        
                    <button class="btn btn-default" type="submit"><i class="fa fa-search"></i> Go</button></div>
                </form>
   
  <div class="btn-group">
  </div>
</div>

<div class="row">
                <div class="col-md-12 showtab">
                   <div style="width:100%; overflow:auto">
<table class="table table-bordered table-striped">
<thead>
<tr>
<th width="50" style="display:">选择<input type="checkbox" id="check" onClick="CheckAll('dataTable')" style="display:none"></th>
<th><a href="javascript:sortBy('id','1','PaymentOther','index')">ID</a></th>
<th><a href="javascript:sortBy('ordernu','1','PaymentOther','index')">订单编号  </a></th>
<th><a href="javascript:sortBy('user_id','1','PaymentOther','index')" >会员名</a></th>
<th><a href="javascript:sortBy('fullname','1','PaymentOther','index')" >联系人</a></th>
<th><a>电话</a></th>
<th><a>国家/地区</a></th>
<th><a href="javascript:sortBy('total','1','PaymentOther','index')" >订单金额</a></th>
<th><a href="javascript:sortBy('ordey_type','1','PaymentOther','index')" >配送方式</a></th>
<th><a href="javascript:sortBy('order_status','1','PaymentOther','index')" >订单状态</a></th>
<th><a href="javascript:sortBy('create_time','1','PaymentOther','index')" title="按照创建时间  升序排列 ">创建时间</a></th>
<th><a>操作</a></th>
</tr>
</thead>
<tbody>
<?php $_from = $this->_var['pagelist']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
<tr>
<td align="center"  style="display:"><span class="checkall" style="vertical-align:middle;"><input type="checkbox" name="key" class="key" value="<?php echo $this->_var['item']['id']; ?>"></span></td>
<td align="center"><?php echo $this->_var['item']['id']; ?></td>
<td align="center"><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=order&act=edit&id=<?php echo $this->_var['item']['id']; ?>" title="<?php echo $this->_var['item']['ordernu']; ?>"><?php echo $this->_var['item']['ordernu']; ?></a></td>
<td align="center"><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=user&act=edit&id=<?php echo $this->_var['item']['user_id']; ?>"><?php echo $this->_var['item']['user_name']; ?></a></td>
<td align="center"><?php echo $this->_var['item']['fullname']; ?></td>
<td align="center">+<?php echo $this->_var['item']['phone_prefix']; ?> <?php echo $this->_var['item']['telephone']; ?></td>
<td align="center"><?php if ($this->_var['item']['language_id']): ?><?php echo $this->_var['item']['country']; ?>/<?php endif; ?><?php echo $this->_var['item']['province']; ?>/<?php echo $this->_var['item']['city']; ?>/<?php echo $this->_var['item']['zone']; ?></td>
<td align="center"><?php 
$k = array (
  'name' => 'number_format',
  'f' => $this->_var['item']['total'],
  'v' => '2',
);
echo $k['name']($k['f'],$k['v']);
?></td>
<td align="center"><?php if ($this->_var['item']['ordey_type']): ?>拖管<?php else: ?>发货<?php endif; ?></td>
<td align="center"><?php
            if($this->_var['item']['order_status']=='0') echo '<font color="red">审核中</font>';
            if($this->_var['item']['order_status']=='1') echo '<font color="#0000ff">等待发货</font>';
            if($this->_var['item']['order_status']=='2') echo '<font color="#090">已发货</font>';
            if($this->_var['item']['order_status']=='3') echo '订单完成';
            if($this->_var['item']['order_status']=='4') echo '<font color="#999">订单取消</font>';
            ?></td>
<td align="center"><?php 
$k = array (
  'name' => 'to_date',
  'v' => $this->_var['item']['create_time'],
);
echo $k['name']($k['v']);
?></td>
<td align="center">
    <a href="/<?php echo $this->_var['webadmin']; ?>?ctl=order&act=edit&id=<?php echo $this->_var['item']['id']; ?>" title="编辑"><i class="fa fa-pencil"></i></a>
    <a href="javascript:del(<?php echo $this->_var['item']['id']; ?>);" role="button" data-toggle="modal" title="删除"><i class="fa fa-trash-o"></i></a>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</tbody>
</table>
</div>                
                   
      </div>
</div>


<ul class="pagination">
  
  <?php echo $this->_var['pagelist']['pages']; ?>
  
</ul>



           <?php echo $this->fetch('footer_copytxt.html'); ?>
        </div>
    </div>
<script language="javascript">
function delsel(paramobj)
{
	
	var param="";
	var id="";
	idBox = $(".key:checked");
	if(idBox.length == 0)
	{
	}else{
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");	
	}
	if(id!=""){
		param="id="+id;	
	}else{
		dialog({title:"提示",content:"请选择要删除的项目！",okValue:"确定",ok:function(){
		}}).showModal();
		return;
	}
	
	
	dialog({title:"提示",content:'确定要删除选定项目吗?',okValue:"确定",ok:function(){
		
		$.ajax({ 
				url: "/<?php echo $this->_var['webadmin']; ?>?ctl=order&act=foreverdelete&id="+id, 
				data: "ajax=1",
				dataType: "json",
				success: function(obj){
					if(obj.status==1)
					window.location.reload();
				}
		});
		
		
	},cancelValue:"取消",cancel:function(){}}).showModal();
}

function restore(paramobj)
{
	
	var param="";
	var id="";
	idBox = $(".key:checked");
	if(idBox.length == 0)
	{
	}else{
		idArray = new Array();
		$.each( idBox, function(i, n){
			idArray.push($(n).val());
		});
		id = idArray.join(",");	
	}
	if(id!=""){
		param="id="+id;	
	}else{
		dialog({title:"提示",content:"请选择要恢复的项目！",okValue:"确定",ok:function(){
		}}).showModal();
		return;
	}
	
	
	dialog({title:"提示",content:'确定要恢复选定项目吗?',okValue:"确定",ok:function(){
		
		$.ajax({ 
				url: "/<?php echo $this->_var['webadmin']; ?>?ctl=order&act=restore&id="+id, 
				data: "ajax=1",
				dataType: "json",
				success: function(obj){
					if(obj.status==1)
					window.location.reload();
				}
		});
		
		
	},cancelValue:"取消",cancel:function(){}}).showModal();
}
function del(id){
	dialog({title:"提示",content:'确定要删除吗?',okValue:"确定",ok:function(){
		
		$.ajax({ 
				url: "/<?php echo $this->_var['webadmin']; ?>?ctl=order&act=foreverdelete&id="+id, 
				data: "ajax=1",
				dataType: "json",
				success: function(obj){
					if(obj.status==1)
					window.location.reload();
				}
		});
		
		
	},cancelValue:"取消",cancel:function(){}}).showModal();
}


</script>
    
<script language="javascript">
$("[name='order_status']").val("<?php echo $_REQUEST['order_status'];?>");
</script>

<?php echo $this->fetch('footer.html'); ?>

<!doctype html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>财务管理中心</title>
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
.stxt{height:50px; border-bottom:solid 1px #ddd; line-height:30px;background-color:#F7F7F7; padding:10px; text-align:right;}
</style>   

</head>

<body >
<div class="theme-blue"><?php echo $this->fetch('top.html'); ?></div>
    <?php echo $this->fetch('menu.html'); ?>

    <div class="content">
        <div class="header">
            
            <h1 class="page-title">链接管理</h1>
                    <ul class="breadcrumb">
            <li><a href="index.html">管理中心</a> </li>
            <li class="active">链接列表</li>
        </ul>

        </div>
        <div class="main-content">
            
<div class="btn-toolbar list-toolbar">
    <form class="form-inline" id="formval" action="<?php echo $this->_var['webadmin']; ?>?ctl=link" method="post">
    <button class="btn btn-default" onClick="checkAll(this);" type="button"><i class="fa fa-square-o"></i> 全选</button>
    <button class="btn btn-default" type="button" onClick="location.href='?ctl=link&act=add'"><i class="fa fa-plus"></i> 添加</button>
    <button class="btn btn-default" type="button" onClick="delsel()"><i class="fa fa-trash-o"></i> 删除</button>
          <div class="search_txt">  
    <select name="cate_id"  class="form-control select_i">
			<option value="" selected="selected">所属分类</option>
			<?php $_from = $this->_var['linkCate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
            <option value="<?php echo $this->_var['item']['id']; ?>" style="background-color:#F6F6F6;"><?php echo $this->_var['item']['title']; ?></option>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</select>
    
        <input name="wordkey" type="text" id="wordkey" class="input-xlarge form-control" placeholder="标题" value="<?php echo $_REQUEST['wordkey'];?>" style="margin-right:3px;">
        
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
<th>ID</th>
<th><a href="javascript:sortBy('title','1','PaymentOther','index')" title="按照标题名称  升序排列 ">名称  </a></th>
<th><a href="javascript:sortBy('pid','1','PaymentOther','index')" title="按照付款单金额  升序排列 ">所属分类  </a></th><th><a href="javascript:sortBy('is_effect','1','PaymentOther','index')" title="按照类型     升序排列 ">状态     </a></th><th><a href="javascript:sortBy('sort','1','PaymentOther','index')" title="按照付款单金额  升序排列 ">排序</a></th><th><a href="javascript:sortBy('update_time','1','PaymentOther','index')" title="按照收款时间  升序排列 ">操作  </a></th>
</tr>
</thead>
<tbody>
<?php $_from = $this->_var['pagelist']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
<tr>
<td align="center"  style="display:"><span class="checkall" style="vertical-align:middle;"><input type="checkbox" name="key" class="key" value="<?php echo $this->_var['item']['id']; ?>"></span></td>
<td align="center"><?php echo $this->_var['item']['id']; ?></td>
<td align="center"><a href="<?php echo $this->_var['webadmin']; ?>/?ctl=link&act=edit&id=<?php echo $this->_var['item']['id']; ?>" target="" title="<?php echo $this->_var['item']['title']; ?>"><?php echo $this->_var['item']['title']; ?></a></td><td align="center"><?php echo $this->_var['item']['cate_name']; ?></td>
<td align="center"><?php if ($this->_var['item']['is_effect']): ?>有效<?php else: ?><font color="#999">无效</font><?php endif; ?></td>
<td align="center"><?php echo $this->_var['item']['sort']; ?></td>
<td align="center">
          <a href="<?php echo $this->_var['webadmin']; ?>/?ctl=link&act=edit&id=<?php echo $this->_var['item']['id']; ?>" title="编辑"><i class="fa fa-pencil"></i></a>
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
				url: "/<?php echo $this->_var['webadmin']; ?>?ctl=link&act=foreverdelete&id="+id, 
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
				url: "/<?php echo $this->_var['webadmin']; ?>?ctl=link&act=delete&id="+id, 
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
$("[name='cate_id']").val("<?php echo $_REQUEST['cate_id'];?>");
$("[name='status']").val("<?php echo $_REQUEST['is_effect'];?>");
</script>

<?php echo $this->fetch('footer.html'); ?>

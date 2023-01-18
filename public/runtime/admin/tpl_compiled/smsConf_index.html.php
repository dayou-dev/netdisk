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
.stxt{height:50px; border-bottom:solid 1px #ddd; line-height:30px;background-color:#F7F7F7; padding:10px; text-align:right;}
</style>   

</head>

<body >
<div class="theme-blue"><?php echo $this->fetch('top.html'); ?></div>
    <?php echo $this->fetch('menu.html'); ?>

    <div class="content">
        <div class="header">
            
            <h1 class="page-title">短信管理</h1>
                    <ul class="breadcrumb">
            <li><a href="index.html">管理中心</a> </li>
            <li class="active">短信列表</li>
        </ul>

        </div>
        <div class="main-content">
            
<div class="btn-toolbar list-toolbar">
    <form class="form-inline" action="<?php echo $this->_var['webadmin']; ?>?ctl=smsConf" method="post" >
          <div class="search_txt">  

    
        <input name="wordkey" type="text" id="wordkey" class="input-xlarge form-control" placeholder="手机号码" value="<?php echo $_REQUEST['wordkey'];?>" style="margin-right:3px;">
        
                    <button class="btn btn-default" type="button" onClick="send_demo()"><i class="fa fa-mail-forward"></i> 发送测试</button></div>
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
<th><a>接口名称  </a></th>
<th><a>描述</a></th>
<th><a>操作 </a></th>
</tr>
</thead>
<tbody>
<?php $_from = $this->_var['sms_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
<tr>
<td align="center"><a href="<?php echo $this->_var['webadmin']; ?>/?ctl=smsConf&act=edit&id=<?php echo $this->_var['item']['id']; ?>" target="" title="<?php echo $this->_var['item']['title']; ?>"><?php echo $this->_var['item']['name']; ?></a></td>
<td align="center"><?php echo $this->_var['item']['description']; ?></td>
<td align="center">
         <?php if (! $this->_var['item']['installed']): ?>
         <a href="<?php echo $this->_var['webadmin']; ?>/?ctl=smsConf&act=install&class_name=<?php echo $this->_var['item']['class_name']; ?>" role="button" data-toggle="modal" title="安装">安装</a>
         <?php else: ?>
         <a href="javascript:uninstall(<?php echo $this->_var['item']['id']; ?>);" role="button" data-toggle="modal" title="卸载">卸载</a>
         <a href="<?php echo $this->_var['webadmin']; ?>/?ctl=smsConf&act=edit&id=<?php echo $this->_var['item']['id']; ?>" role="button" data-toggle="modal" title="编辑">编辑</a>
         <?php if (! $this->_var['item']['is_effect']): ?><a href="<?php echo $this->_var['webadmin']; ?>/?ctl=smsConf&act=set_effect&id=<?php echo $this->_var['item']['id']; ?>" role="button" data-toggle="modal" title="使用该短信接口">使用该短信接口</a><?php endif; ?>
         <?php endif; ?>
      </td>
</tr>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</tbody>
</table>
</div>                
                   
      </div>
</div>






           <?php echo $this->fetch('footer_copytxt.html'); ?>
        </div>
    </div>
<script type="text/javascript">

	function send_demo()
	{		
		if($("#wordkey").val()==""){
			dialog({title:"提示",content:"请输入手机号码.",okValue:"确定",ok:function(){
			}}).showModal();
			return;	
		}
		
		
		$.ajax({ 
				url: "<?php echo $this->_var['webadmin']; ?>?ctl=smsConf&act=send_demo&test_mobile="+$.trim($("input[name='wordkey']").val()), 
				data: "ajax=1",
				dataType: "json",
				success: function(obj){
					if(obj.status==0)
					{
						dialog({title:"提示",content:obj.info,okValue:"确定",ok:function(){
							}}).showModal();
						return;						
					}
					else{
					
						dialog({title:"提示",content:obj.info,okValue:"确定",ok:function(){
						}}).showModal();
					
					}
				}
		});
		
		return;	
	}
//	$(document).ready(function(){
//		$("input[name='test_mobile_btn']").bind("click",function(){
//			var mail = $.trim($("input[name='test_mobile']").val());	
//			if(mail!='')
//			send_demo();
//		});
//	});
	
	function mobile_btn(){
		var mail = $.trim($("input[name='test_mobile']").val());	
		if(mail!='')
		send_demo();
	
	}
	
	
function uninstall(id){
	dialog({title:"提示",content:'确定要卸载吗?',okValue:"确定",ok:function(){
		
		$.ajax({ 
				url: "/<?php echo $this->_var['webadmin']; ?>?ctl=smsConf&act=uninstall&id="+id, 
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
<?php echo $this->fetch('footer.html'); ?>

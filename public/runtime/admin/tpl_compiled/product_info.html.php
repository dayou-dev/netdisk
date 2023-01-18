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
.form-control{width:50%;}
.infolist{overflow:auto}
.infolist ul{width:130px; height:150px; float:left; margin:0px; padding:0px; list-style:none; list-style-type:none; margin-left:5px; margin-right:8px; margin-top:8px; text-align:center;}
.infolist div{line-height:30px;}

.infolist1{}
.infolist1 ul{width:130px; height:150px; float:left; margin:0px; padding:0px; list-style:none; list-style-type:none; margin-left:5px; margin-right:8px; margin-top:8px; text-align:center;}
.infolist1 div{line-height:30px;}
#uppiclist{overflow:hidden}
#uppiclist img{cursor:pointer; border:solid 2px #fff;}
#uppiclist img:hover{cursor:pointer; border:solid 2px #ff6600;}

</style>
</head>

<body >
<div class="theme-blue"><?php echo $this->fetch('top.html'); ?></div>
    <?php echo $this->fetch('menu.html'); ?>

    <div class="content">
        <div class="header">
            
            <h1 class="page-title">产品管理</h1>
                    <ul class="breadcrumb">
            <li><a href="index.html">管理中心</a> </li>
            <li class="active">产品<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></li>
        </ul>

        </div>
        <div class="main-content">
            
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">产品<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></a></li>
</ul>

<div class="row">
  <div class="col-md-4" style="width: 80%;">
    <br>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active">
      <form id="formval" action="<?php echo $this->_var['webadmin']; ?>?ctl=product&act=<?php if ($this->_var['vo']['id']): ?>update<?php else: ?>insert<?php endif; ?>" method="post">
        <div class="form-group">
        <label>标题  [<font color="#FF0000">*</font>]</label>
        <input type="text" name="title" id="title" value="<?php echo $this->_var['vo']['title']; ?>" class="form-control" intchk="*">
        </div>
        
        <div class="form-group">
            <label>分类</label>
            <select name="cate_id" id="cate_id" class="form-control">
			<option value="">所属分类</option>
			<?php $_from = $this->_var['product_cate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
            <option value="<?php echo $this->_var['item']['id']; ?>" style="background-color:#F6F6F6;" <?php if ($this->_var['item']['id'] == $this->_var['vo']['cate_id']): ?> selected="selected"<?php endif; ?>><?php echo $this->_var['item']['title']; ?></option>
            <?php $_from = $this->_var['item']['catelist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'listdata');if (count($_from)):
    foreach ($_from AS $this->_var['listdata']):
?>
            <option value="<?php echo $this->_var['listdata']['id']; ?>" <?php if ($this->_var['listdata']['id'] == $this->_var['vo']['cate_id']): ?> selected="selected"<?php endif; ?>>|--<?php echo $this->_var['listdata']['title']; ?></option>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </select>
          </div>
          
        
        <div class="form-group">
          <label>简单描述</label>
          <textarea  name="brief" id="brief" rows="3" class="form-control"><?php echo $this->_var['vo']['brief']; ?></textarea>
        </div>
        
        
        <div class="form-group">
        <label>SEO关键词</label>
        <input type="text" name="seo_keyword" id="seo_keyword" value="<?php echo $this->_var['vo']['seo_keyword']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
        <label>SEO描述</label>
        <input type="text" name="seo_description" id="seo_description" value="<?php echo $this->_var['vo']['seo_description']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
        <label>价格  [<font color="#FF0000">*</font>]</label>
        <input type="text" name="price" id="price" value="<?php if ($this->_var['vo']['price']): ?><?php echo $this->_var['vo']['price']; ?><?php else: ?>0<?php endif; ?>" class="form-control" intchk="*">
        </div>
        
        <div class="form-group">
        <label>产品批次</label>
        <input type="text" name="pro_spec1" id="pro_spec1" value="<?php echo $this->_var['vo']['pro_spec1']; ?>" class="form-control">
        </div>
       
        <div class="form-group">
        <label>产品规格</label>
        <input type="text" name="pro_spec" id="pro_spec" value="<?php echo $this->_var['vo']['pro_spec']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
        <label>图片上传</label>
        <div style="position:relative"><input type="text" name="icon" id="icon" value="<?php echo $this->_var['vo']['icon']; ?>" class="form-control"> <input id="picture_upload" name="upfile" type="file" onChange="upload_cover(this)"  style="position: absolute; left: 50%; top: 0px; width: 100%; height: 100%; opacity: 0; cursor: pointer; width:146px; margin-left: -146px; z-index:99"/>
        <div style="position:absolute; left: 50%;  top:3px; margin-left: -100px;"><img src="/webadmin/Tpl/default/images/button_image.jpg" border="0"></div>
        </div>
        </div>
        <div><img src="<?php if ($this->_var['vo']['icon']): ?><?php echo $this->_var['vo']['icon']; ?><?php else: ?>/webadmin/Tpl/default/images/nopic.jpg<?php endif; ?>" width="150" id="image" border="0" style="border:solid 1px #ddd; padding:5px; margin-bottom:10px;" /></div>
        
        <div class="form-group">
        <label>多图上传</label>
        <div rel="infolist" class="infolist" style="display:">
          <div style="margin-bottom:20px;">  
            <input name="upfile" type="button"  class="button" id="upfile" value="上传图片" style="width:100px; background-color:#39F; border:0; color:#fff;"/>
            <input type="hidden" name="upfileinfo" id="upfileinfo" value="upfilelist|piclist" />
            <input name="piclist" type="hidden" id="piclist" value="<?php echo $this->_var['vo']['piclist']; ?>" />
            </div>
          <div id="uppiclist">
          <?php
          if($this->_var['vo']['piclist']){
              $albumlist=explode(",",$this->_var['vo']['piclist']);
              foreach($albumlist as $rows)
          		if($rows){
          ?>
          
         
          <ul><div align="center"><img src="/ckfinder/userfiles/<?php echo $rows;?>" width="130" height="120" /></div><div align="center"><a href="javascript:;" onClick="delpic(this)">[删除图片]</a></div></ul>
          <?php
          }}
          ?>
          </div>
          <div style="position:fixed; height:435px; width:532px; top:50%; left:50%; margin-left:-260px; margin-top:-200px;display:none; z-index:99999;" id="upfilelist"><iframe scrolling="no" frameborder="0" width="100%" height="400" src="" id="upfileobj"></iframe>
            <div style=" background-color:#ddeef2; text-align:right; padding-right:5px; padding-bottom:5px;margin-top: -10px;"><input name="closefile" type="button"  class="button" id="closefile" value="关闭窗口" style="width:100px; background-color:#ddeef2; border:0; color:#666;"/></div>
            </div>
          </div>
        </div>
      
      
        <div class="form-group">
            <label>热门</label>
            <select name="is_hot" id="is_hot" class="form-control">
              <option value="1" <?php if ($this->_var['vo']['is_hot']): ?>selected<?php endif; ?>>是</option>
              <option value="0" <?php if (! $this->_var['vo']['is_hot']): ?>selected<?php endif; ?>>否</option>
            </select>
          </div>
          
        <div class="form-group">
            <label>预售</label>
            <select name="in_advance" id="in_advance" class="form-control">
              <option value="1" <?php if ($this->_var['vo']['in_advance']): ?>selected<?php endif; ?>>是</option>
              <option value="0" <?php if (! $this->_var['vo']['in_advance']): ?>selected<?php endif; ?>>否</option>
            </select>
          </div>
      
      
        <div class="form-group">
            <label>状态</label>
            <select name="is_effect" id="is_effect" class="form-control">
             <?php if ($this->_var['id']): ?>
              <option value="1" <?php if ($this->_var['vo']['is_effect']): ?>selected<?php endif; ?>>进行中</option>
              <option value="0" <?php if (! $this->_var['vo']['is_effect']): ?>selected<?php endif; ?>>已结束</option>
              <?php else: ?>
              <option value="1">进行中</option>
              <option value="0">已结束</option>
              <?php endif; ?>
            </select>
          </div>

        

        <div class="form-group">
          <label>规格参数</label>
          <textarea  name="content" id="content" rows="3" style="width:100%;height:400px;"><?php echo $this->_var['vo']['content']; ?></textarea>
        </div>
        
        <div class="form-group">
          <label>规格参数</label>
          <textarea  name="content1" id="content1" rows="3" style="width:100%;height:400px;"><?php echo $this->_var['vo']['content1']; ?></textarea>
        </div>
        
        <div class="form-group">
        <label>库存</label>
        <input type="text" name="in_stock" id="in_stock" value="<?php if ($this->_var['vo']['in_stock']): ?><?php echo $this->_var['vo']['in_stock']; ?><?php else: ?>9999<?php endif; ?>" class="form-control">
        </div>

        <div class="form-group">
        <label>排序</label>
        <input type="text" name="sort" id="sort" value="<?php if ($this->_var['vo']['sort']): ?><?php echo $this->_var['vo']['sort']; ?><?php else: ?>99<?php endif; ?>" class="form-control">
        </div>
        
        <div class="form-group">
            <label>推荐</label>
            <select name="is_comm" id="is_comm" class="form-control">
              <option value="1" <?php if ($this->_var['vo']['source']): ?>selected<?php endif; ?>>是</option>
              <option value="0" <?php if (! $this->_var['vo']['source']): ?>selected<?php endif; ?>>否</option>
            </select>
          </div>
        <input type="hidden" name="id" id="id" value="<?php echo $this->_var['vo']['id']; ?>">
        </form>
      </div>


      
    </div>

    <div class="btn-toolbar list-toolbar">
      <button class="btn btn-primary" onClick="submitform()"><i class="fa fa-save"></i> 保存</button>
      <a href="#myModal" data-toggle="modal" class="btn btn-danger">取消</a>
      
    </div>
  </div>
</div>



            <?php echo $this->fetch('footer_copytxt.html'); ?>
        </div>
    </div>
    
    
    
<script type="text/javascript" charset="utf-8" src="/webadmin/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/webadmin/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/webadmin/ueditor/lang/zh-cn/zh-cn.js"></script>
  
<script type="text/javascript"><!--
 var ue = UE.getEditor('content');
 var ue1 = UE.getEditor('content1');
</script>
<script type="text/javascript" src="/webadmin/Tpl/default/js/ajaxupload.js"></script>
<script type="text/javascript"><!--

function upload_cover(obj) {
        ajax_upload(obj.id, function(data) { //function(data)是上传图片的成功后的回调方法
		    //var isrc = data.url.replace(/\/\//g, '/'); //获取的图片的绝对路径
			$('#icon').val(data.url);
            $('#image').attr('src', data.url).data('img-src', data.url); //给<input>的src赋值去显示图片
        });
    }
    function ajax_upload(feid, callback) { //具体的上传图片方法
        if (image_check(feid)) { //自己添加的文件后缀名的验证
            $.ajaxFileUpload({
                fileElementId: feid,    //需要上传的文件域的ID，即<input type="file">的ID。
                url: '/webadmin/ueditor/php/controller.php?action=uploadimage', //后台方法的路径
                type: 'post',   //当要提交自定义参数时，这个参数要设置成post
                dataType: 'json',   //服务器返回的数据类型。可以为xml,script,json,html。如果不填写，jQuery会自动判断。
                secureuri: false,   //是否启用安全提交，默认为false。
                async : true,   //是否是异步
                success: function(data) {   //提交成功后自动执行的处理函数，参数data就是服务器返回的数据。
                    if (callback) callback.call(this, data);
                },
                error: function(data, status, e) {  //提交失败自动执行的处理函数。
                    console.error(e);
                }
            });
        }
    }
    function image_check(feid) { //自己添加的文件后缀名的验证
        var img = document.getElementById(feid);
        return /.(jpg|png|gif|bmp)$/.test(img.value)?true:(function() {
            modals.info('图片格式仅支持jpg、png、gif、bmp格式，且区分大小写。');
            return false;
        })();
    }

function chkformdata(){
	//获取相册
	$("#piclist").val("");
	for(var i=0;i<$("#uppiclist img").length;i++)
	{
		psrc=$("#uppiclist img").eq(i).attr("src").split("/");
		$("#piclist").val($("#piclist").val()+","+psrc[psrc.length-3]+"/"+psrc[psrc.length-2]+"/"+psrc[psrc.length-1])
	}
	
	if($("#piclist").val()!="") $("#piclist").val($("#piclist").val().replace(",",""));
	
}


$("#upfile").click(function(){
	$("#upfileobj").attr("src","/webadmin/upfile/up.php");
	$("#upfilelist").show();
})

$("#closefile").click(function(){
	$("#upfilelist").hide();
})

function returnpic(s)
{
	$("#uppiclist").html($("#uppiclist").html()+'<ul><div align="center"><img src="'+s+'" width="130" height="120"  /></div><div align="center"><a href="javascript:;" onclick="delpic(this)">[删除图片]</a></div></ul>');
	chkformdata()
}

//$("#upfilelist").show();
function delpic(t)
{
	$(t).parent().parent().remove();
	chkformdata()
}


</script>


<?php echo $this->fetch('footer.html'); ?>

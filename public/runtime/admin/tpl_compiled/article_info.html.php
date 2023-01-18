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
</style>   

</head>

<body >
<div class="theme-blue"><?php echo $this->fetch('top.html'); ?></div>
    <?php echo $this->fetch('menu.html'); ?>

    <div class="content">
        <div class="header">
            
            <h1 class="page-title">文章管理</h1>
                    <ul class="breadcrumb">
            <li><a href="/admin.php">管理中心</a> </li>
            <li class="active">文章<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></li>
        </ul>

        </div>
        <div class="main-content">
            
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">文章<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></a></li>
</ul>

<div class="row">
  <div class="col-md-4" style="width: 80%;">
    <br>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active">
      <form id="formval" action="<?php echo $this->_var['webadmin']; ?>?ctl=article&act=<?php if ($this->_var['vo']['id']): ?>update<?php else: ?>insert<?php endif; ?>" method="post">
        <div class="form-group">
        <label>标题  [<font color="#FF0000">*</font>]</label>
        <input type="text" name="title" id="title" value="<?php echo $this->_var['vo']['title']; ?>" class="form-control" intchk="*">
        </div>
        
        <div class="form-group">
            <label>分类<?php echo $this->_var['vo']['cate_id']; ?></label>
            <select name="cate_id" id="cate_id" class="form-control">
			<option value="">所属分类</option>
			<?php $_from = $this->_var['article_cate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
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
        <label>SEO关键词</label>
        <input type="text" name="seo_keyword" id="seo_keyword" value="<?php echo $this->_var['vo']['seo_keyword']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
        <label>SEO描述</label>
        <input type="text" name="seo_description" id="seo_description" value="<?php echo $this->_var['vo']['seo_description']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
        <label>标签</label>
        <input type="text" name="tags" id="tags" value="<?php echo $this->_var['vo']['tags']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
        <label>图片上传</label>
        <div style="position:relative"><input type="text" name="icon" id="icon" value="<?php echo $this->_var['vo']['icon']; ?>" class="form-control"> <input id="picture_upload" name="upfile" type="file" onChange="upload_cover(this)"  style="position: absolute; left: 50%; top: 0px; width: 100%; height: 100%; opacity: 0; cursor: pointer; width:146px; margin-left: -146px; z-index:99"/>
        <div style="position:absolute; left: 50%;  top:3px; margin-left: -100px;"><img src="/webadmin/Tpl/default/images/button_image.jpg" border="0"></div>
        </div>
        </div>
        <div><img src="<?php if ($this->_var['vo']['icon']): ?><?php echo $this->_var['vo']['icon']; ?><?php else: ?>/webadmin/Tpl/default/images/nopic.jpg<?php endif; ?>" width="150" id="image" border="0" style="border:solid 1px #ddd; padding:5px; margin-bottom:10px;" /></div>
        
        <div class="form-group">
        <label>来源</label>
        <input type="text" name="source" id="source" value="<?php echo $this->_var['vo']['source']; ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>状态</label>
            <select name="is_effect" id="is_effect" class="form-control">
             <?php if ($this->_var['id']): ?>
              <option value="1" <?php if ($this->_var['vo']['is_effect']): ?>selected<?php endif; ?>>有效</option>
              <option value="0" <?php if (! $this->_var['vo']['is_effect']): ?>selected<?php endif; ?>>无效</option>
              <?php else: ?>
              <option value="1">有效</option>
              <option value="0">无效</option>
              <?php endif; ?>
            </select>
          </div>
        <div class="form-group">
        <label>来源网址</label>
        <input type="text" name="rel_url" id="rel_url" value="<?php echo $this->_var['vo']['rel_url']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
          <label>简单描述</label>
          <textarea  name="brief" id="brief" rows="3" class="form-control"><?php echo $this->_var['vo']['brief']; ?></textarea>
        </div>

        <div class="form-group">
          <label>文章内容</label>
          <textarea  name="content" id="content" rows="3" style="width:100%;height:400px;"><?php echo $this->_var['vo']['content']; ?></textarea>
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


</script>


<?php echo $this->fetch('footer.html'); ?>

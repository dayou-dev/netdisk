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
            
            <h1 class="page-title">管理员管理</h1>
                    <ul class="breadcrumb">
            <li><a href="index.html">管理中心</a> </li>
            <li class="active">管理员<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></li>
        </ul>

        </div>
        <div class="main-content">
            
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">管理员<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></a></li>
</ul>

<div class="row">
  <div class="col-md-4" style="width: 80%;">
    <br>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active">
      <form id="formval" action="<?php echo $this->_var['webadmin']; ?>?ctl=admin&act=<?php if ($this->_var['vo']['id']): ?>update<?php else: ?>insert<?php endif; ?>" method="post">
        <div class="form-group">
        <label>登录名  [<font color="#FF0000">*</font>]</label>
        
        <?php if ($this->_var['id']): ?><?php echo $this->_var['vo']['adm_name']; ?><?php else: ?><input type="text" name="adm_name" id="adm_name" value="" class="form-control" intchk="*"><?php endif; ?>
        </div>
        
        <div class="form-group">
        <label>密码  <?php if ($this->_var['id']): ?>[<font color="#FF0000">需要修改时输入</font>]<?php else: ?>[<font color="#FF0000">*</font>]<?php endif; ?></label>
        <input type="password" name="adm_password_re" id="adm_password_re" value="" class="form-control" <?php if (! $this->_var['id']): ?> intchk="*"<?php endif; ?>>
        </div>
        
        <div class="form-group">
            <label>状态</label>
            <select name="is_effect" id="is_effect" class="form-control">
              <option value="1" <?php if ($this->_var['vo']['is_effect']): ?>selected<?php endif; ?>>有效</option>
              <option value="0" <?php if (! $this->_var['vo']['is_effect']): ?>selected<?php endif; ?>>无效</option>
            </select>
          </div>

        <div class="form-group">
        <label>排序</label>
        <input type="text" name="sort" id="sort" value="<?php if ($this->_var['vo']['sort']): ?><?php echo $this->_var['vo']['sort']; ?><?php else: ?>99<?php endif; ?>" class="form-control">
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


<?php echo $this->fetch('footer.html'); ?>

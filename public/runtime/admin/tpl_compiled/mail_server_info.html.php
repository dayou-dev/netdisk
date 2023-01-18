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
            
            <h1 class="page-title">邮件服务器管理</h1>
                    <ul class="breadcrumb">
            <li><a href="index.html">管理中心</a> </li>
            <li class="active">邮件服务器<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></li>
        </ul>

        </div>
        <div class="main-content">
            
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">邮件服务器<?php if ($this->_var['id']): ?>编辑<?php else: ?>添加<?php endif; ?></a></li>
</ul>

<div class="row">
  <div class="col-md-4" style="width: 80%;">
    <br>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active">
      <form id="formval" action="<?php echo $this->_var['webadmin']; ?>?ctl=mail_server&act=<?php if ($this->_var['vo']['id']): ?>update<?php else: ?>insert<?php endif; ?>" method="post">
        <div class="form-group">
        <label>SMTP服务器地址  [<font color="#FF0000">*</font>]</label>
        <input type="text" name="smtp_server" id="smtp_server" value="<?php echo $this->_var['vo']['smtp_server']; ?>" class="form-control" intchk="*">
        </div>
        <div class="form-group">
        <label>帐号  [<font color="#FF0000">*</font>]</label>
        <input type="text" name="smtp_name" id="smtp_name" value="<?php echo $this->_var['vo']['smtp_name']; ?>" class="form-control" intchk="*">
        </div>    
        
        <div class="form-group">
        <label>密码  [<font color="#FF0000">*</font>]</label>
        <input type="password" name="smtp_pwd" id="smtp_pwd" value="<?php echo $this->_var['vo']['smtp_pwd']; ?>" class="form-control" intchk="*">
        </div>
        
        <div class="form-group">
            <label>是否需要身份验证</label>
            <select name="is_verify" id="is_verify" class="form-control">
			<option value="1" <?php if ($this->_var['vo']['is_verify']): ?> selected="selected"<?php endif; ?>>是</option>
			<option value="0" <?php if (! $this->_var['vo']['is_verify']): ?> selected="selected"<?php endif; ?>>否</option>
            </select>
          </div>
          
        <div class="form-group">
        <label>端口号  </label>
        <input type="text" name="smtp_port" id="smtp_port" value="<?php if ($this->_var['vo']['smtp_port']): ?><?php echo $this->_var['vo']['smtp_port']; ?><?php else: ?>25<?php endif; ?>" class="form-control" intchk="n">
        </div>
        
        <div class="form-group">
            <label>是否SSL加密</label>
            <select name="is_ssl" id="is_ssl" class="form-control">
			<option value="1" <?php if ($this->_var['vo']['is_ssl']): ?> selected="selected"<?php endif; ?>>是</option>
			<option value="0" <?php if (! $this->_var['vo']['is_ssl']): ?> selected="selected"<?php endif; ?>>否</option>
            </select>
          </div>
          
        <div class="form-group">
        <label>已用次数  </label>
        <input type="text" name="total_use" id="total_use" value="<?php if ($this->_var['vo']['total_use']): ?><?php echo $this->_var['vo']['total_use']; ?><?php else: ?>0<?php endif; ?>" class="form-control" intchk="n">
        </div>

        <div class="form-group">
        <label>可用次数  </label>
        <input type="text" name="use_limit" id="use_limit" value="<?php if ($this->_var['vo']['use_limit']): ?><?php echo $this->_var['vo']['use_limit']; ?><?php else: ?>0<?php endif; ?>" class="form-control" intchk="n">
        </div>
        
        <div class="form-group">
            <label>是否自动清零</label>
            <select name="is_reset" id="is_reset" class="form-control">
			<option value=""<?php if ($this->_var['vo']['is_reset']): ?> selected="selected"<?php endif; ?>>是</option>
			<option value=""<?php if ($this->_var['vo']['is_reset']): ?> selected="selected"<?php endif; ?>>否</option>
            </select>
          </div>
        
        <div class="form-group">
            <label>状态</label>
            <select name="is_effect" id="is_effect" class="form-control">
              <option value="1" <?php if ($this->_var['vo']['is_effect']): ?>selected<?php endif; ?>>有效</option>
              <option value="0" <?php if (! $this->_var['vo']['is_effect']): ?>selected<?php endif; ?>>无效</option>
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




<?php echo $this->fetch('footer.html'); ?>

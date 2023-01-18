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
            
            <h1 class="page-title">短信接口</h1>
                    <ul class="breadcrumb">
            <li><a href="index.html">短信管理</a> </li>
            <li class="active">短信接口<?php if ($this->_var['id']): ?>编辑<?php else: ?>安装<?php endif; ?></li>
        </ul>

        </div>
        <div class="main-content">
            
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">短信接口<?php if ($this->_var['id']): ?>编辑<?php else: ?>安装<?php endif; ?></a></li>
</ul>

<div class="row">
  <div class="col-md-4" style="width: 80%;">
    <br>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active">
      <form id="formval" action="<?php echo $this->_var['webadmin']; ?>?ctl=<?php echo $_REQUEST['ctl']?>&act=<?php if ($this->_var['id']): ?>update<?php else: ?>insert<?php endif; ?>" method="post">
        <div class="form-group">
        <label>接口名称: </label>
        <?php echo $this->_var['data']['name']; ?>
        <input type="hidden" value="<?php echo $this->_var['data']['name']; ?>" name="name" />
        </div>
        <div class="form-group">
        <label>接口类名: </label>
        <?php echo $this->_var['data']['class_name']; ?>
        <input type="hidden" value="<?php echo $this->_var['data']['class_name']; ?>" name="class_name" />
        </div>
        <div class="form-group">
        <label>短信接口帐号  [<font color="#FF0000">*</font>]</label>
        <input type="text" name="user_name" id="user_name" value="<?php echo $this->_var['vo']['user_name']; ?>" class="form-control" intchk="*">
        </div>

        <div class="form-group">
        <label>短信接口密码  [<font color="#FF0000">*</font>]</label>
        <input type="password" name="password" id="password" value="<?php echo $this->_var['vo']['password']; ?>" class="form-control" intchk="*">
        </div>
        
        <div class="form-group">
        <label>描述</label>
        <textarea  class="form-control" name="description" ><?php echo $this->_var['vo']['description']; ?></textarea>
        </div>
        
       <?php if ($this->_var['data']['config']): ?>
       <?php $_from = $this->_var['data']['config']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
       
        <div class="form-group">
            <label><?php echo $this->_var['data']['lang'][$this->_var['key']];$config_name=$this->_var['key'];?></label>
            
            <?php if (! $this->_var['item']['INPUT_TYPE']): ?>
            
            <input type="text"  class="form-control" name="config[<?php echo $this->_var['key']; ?>]" value="" />
            <?php else: ?>
           <?php if ($this->_var['item']['INPUT_TYPE'] == 2): ?>
            <input type="password" class="input normal" name="config[<?php echo $this->_var['key']; ?>]" value="" />
            <?php else: ?>
            <select name="config[<?php echo $this->_var['key']; ?>]" id="config[<?php echo $this->_var['key']; ?>]" class="form-control">
              <?php $_from = $this->_var['item']['VALUES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['val']):
?>
              <option value="<?php echo $this->_var['val']; ?>" ><?php echo $this->_var['data']['lang'][$config_name."_".$this->_var['val']];?></option>
              <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
            </select>
            
            
            <?php endif; ?>
            <?php endif; ?>
            
          </div>
         <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
        <?php endif; ?>
        <input type="hidden" name="id" id="id" value="<?php echo $this->_var['vo']['id']; ?>">
        <input type="hidden" value="<?php echo $this->_var['data']['server_url']; ?>" name="server_url" />
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


<?php echo $this->fetch('footer.html'); ?>

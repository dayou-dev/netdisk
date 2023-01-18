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
            
            <h1 class="page-title">系统配置</h1>
                    <ul class="breadcrumb">
            <li><a href="/admin.php">管理中心</a> </li>
            <li class="active">系统配置</li>
        </ul>

        </div>
        <div class="main-content">
            
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">系统配置</a></li>
</ul>

<div class="row">
  <div class="col-md-4" style="width: 80%;">
    <br>
    <div id="myTabContent" class="tab-content">
      <div class="tab-pane active">
      <form id="formval" action="<?php echo $this->_var['webadmin']; ?>?ctl=system&act=update" method="post">
        <div class="form-group">
        <label>站点名称  [<font color="#FF0000">*</font>]</label>
        <input type="text" name="SHOP_NAME" id="SHOP_NAME" value="<?php echo $this->_var['vo']['SHOP_NAME']; ?>" class="form-control" intchk="*">
        </div>
        <div class="form-group">
        <label>站点URL  [<font color="#FF0000">*</font>]</label>
        <input type="text" name="SHOP_URL" id="SHOP_URL" value="<?php echo $this->_var['vo']['SHOP_URL']; ?>" class="form-control" intchk="*">
        </div>
        <div class="form-group">
        <label>站点标题  [<font color="#FF0000">*</font>]</label>
        <input type="text" name="SHOP_TITLE" id="SHOP_TITLE" value="<?php echo $this->_var['vo']['SHOP_TITLE']; ?>" class="form-control" intchk="*">
        </div>
        <div class="form-group">
        <label>站点关键词</label>
        <input type="text" name="SHOP_KEYWORD" id="SHOP_KEYWORD" value="<?php echo $this->_var['vo']['SHOP_KEYWORD']; ?>" class="form-control">
        </div>
        <div class="form-group">
        <label>站点描述 </label>
        <input type="text" name="SHOP_DESCRIPTION" id="SHOP_DESCRIPTION" value="<?php echo $this->_var['vo']['SHOP_DESCRIPTION']; ?>" class="form-control" >
        </div>
        <div class="form-group">
        <label>搜索热门关键词: </label>
        <input type="text" name="SHOP_SEARCH_KEYWORD" id="SHOP_SEARCH_KEYWORD" value="<?php echo $this->_var['vo']['SHOP_SEARCH_KEYWORD']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
        <label>LOGO图片</label>
        <div style="position:relative"><input type="text" name="LOGOIMG" id="LOGOIMG" value="<?php echo $this->_var['vo']['LOGOIMG']; ?>" class="form-control"> <input id="picture_upload" name="upfile" type="file" onChange="upload_cover(this)"  style="position: absolute; left: 50%; top: 0px; width: 100%; height: 100%; opacity: 0; cursor: pointer; width:146px; margin-left: -146px; z-index:99"/>
        <div style="position:absolute; left: 50%;  top:3px; margin-left: -100px;"><img src="/webadmin/Tpl/default/images/button_image.jpg" border="0"></div>
        </div>
        </div>
        <div><img src="<?php if ($this->_var['vo']['LOGOIMG']): ?><?php echo $this->_var['vo']['LOGOIMG']; ?><?php else: ?>/webadmin/Tpl/default/images/nopic.jpg<?php endif; ?>" width="150" id="image" border="0" style="border:solid 1px #ddd; padding:5px; margin-bottom:10px;" /></div>
        
        
        <div class="form-group">
        <label>网站客服电话</label>
        <input type="text" name="SHOP_TEL" id="SHOP_TEL" value="<?php echo $this->_var['vo']['SHOP_TEL']; ?>" class="form-control">
        </div>
        <div class="form-group">
        <label>在线QQ</label>
        <input type="text" name="ONLINE_QQ" id="ONLINE_QQ" value="<?php echo $this->_var['vo']['ONLINE_QQ']; ?>" class="form-control">
        </div>
        <div class="form-group">
        <label>客服在线时间</label>
        <input type="text" name="ONLINE_TIME" id="ONLINE_TIME" value="<?php echo $this->_var['vo']['ONLINE_TIME']; ?>" class="form-control">
        </div>
        <div class="form-group">
        <label>ICP备案号</label>
        <input type="text" name="ICP_LICENSE" id="ICP_LICENSE" value="<?php echo $this->_var['vo']['ICP_LICENSE']; ?>" class="form-control">
        </div>
        <div class="form-group">
        <label>公司名称</label>
        <input type="text" name="COMPANY" id="COMPANY" value="<?php echo $this->_var['vo']['COMPANY']; ?>" class="form-control">
        </div>
        <div class="form-group">
        <label>公司地址</label>
        <input type="text" name="COMPANY_ADDRESS" id="COMPANY_ADDRESS" value="<?php echo $this->_var['vo']['COMPANY_ADDRESS']; ?>" class="form-control">
        </div>
        <div class="form-group">
        <label>Email</label>
        <input type="text" name="EMAIL" id="EMAIL" value="<?php echo $this->_var['vo']['EMAIL']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
        <label>比特币现价(汇率自动转换)</label>
        <input type="text" name="PRICE_RATE" id="PRICE_RATE" value="<?php echo $this->_var['vo']['PRICE_RATE']; ?>" class="form-control">
        </div>
        
        <div class="form-group">
        <label>模板名称</label>
        <input type="text" name="TEMPLATE" id="TEMPLATE" value="<?php echo $this->_var['vo']['TEMPLATE']; ?>" class="form-control">
        </div>


        
        <div class="form-group">
            <label>网站状态</label>
            <select name="SHOP_OPEN" id="SHOP_OPEN" class="form-control">
              <option value="1" <?php if ($this->_var['vo']['SHOP_OPEN']): ?>selected<?php endif; ?>>开启</option>
              <option value="0" <?php if (! $this->_var['vo']['SHOP_OPEN']): ?>selected<?php endif; ?>>关闭</option>
            </select>
          </div>


        
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
  
<script type="text/javascript" src="/webadmin/Tpl/default/js/ajaxupload.js"></script>
<script type="text/javascript"><!--

function upload_cover(obj) {
        ajax_upload(obj.id, function(data) { //function(data)是上传图片的成功后的回调方法
		    //var isrc = data.url.replace(/\/\//g, '/'); //获取的图片的绝对路径
			$('#LOGOIMG').val(data.url);
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

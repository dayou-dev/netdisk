<?php echo $this->fetch('header.html'); ?>

    <!-- Demo page code -->

    <script type="text/javascript">
        $(function() {
            var match = document.cookie.match(new RegExp('color=([^;]+)'));
            if(match) var color = match[1];
            if(color) {
                $('body').removeClass(function (index, css) {
                    return (css.match (/\btheme-\S+/g) || []).join(' ')
                })
                $('body').addClass('theme-' + color);
            }

            $('[data-popover="true"]').popover({html: true});
            
        });
    </script>
    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .navbar-default .navbar-brand, .navbar-default .navbar-brand:hover { 
            color: #fff;
        }
    </style>

    <script type="text/javascript">
        $(function() {
            var uls = $('.sidebar-nav > ul > *').clone();
            uls.addClass('visible-xs');
            $('#main-menu').append(uls.clone());
        });
    </script>


    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <a class="" href="/admin.php"><span class="navbar-brand"><span class="fa fa-paper-plane"></span> 管理中心</span></a></div>

        <div class="navbar-collapse collapse" style="height: 1px;">

        </div>
      </div>
    </div>
    


        <div class="dialog">
    <div class="panel panel-default">
        <p class="panel-heading no-collapse">用户登录</p>
        <div class="panel-body">
            <form>
                <div class="form-group">
                    <label>登录名</label>
                    <input type="text" class="form-control span12" name="adm_name">
                </div>
                <div class="form-group">
                <label>密码</label>
                    <input type="password" class="form-control span12 form-control" name="adm_pwd">
                </div>
                <div class="form-group" style=" overflow:hidden;">
                    <div  style="width:66%; float:left;">
                    <label>验证码</label>
                    <input type="text" class="form-control span12" name="checkCode"></div><div style="float:left; padding-top:25px; padding-left:10px;"><img src="/verify.php?t=<?php echo time();?>" height="34" onClick="this.src='/verify.php?t='+(Math.random()*9999)" id="checkCode_img"/></div>
                </div>
                <a href="javascript:;" class="btn btn-primary">登 录</a>
                <label class="remember-me" style="margin-left:10px; "><input type="checkbox" name="auto_login" value="1" > 自动登录</label>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>

   
    <script src="/webadmin/Tpl/default/js/index.js"></script>
    <script type="text/javascript">
	$(".btn-primary").click(function(){
		login();
	})
    </script>
    
  <?php echo $this->fetch('footer.html'); ?>


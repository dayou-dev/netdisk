    <!-- Demo page code -->
    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/css/xcConfirm.css"/>
    <script type="text/javascript">
		var CURRENT_URL="";
		var MODULE_NAME="<?php echo $_REQUEST['ctl'];?>";
		var ACT_MODULE="<?php echo $_REQUEST['act'];?>";
		var re_sort=<?php echo $_REQUEST['_sort']?0:1;?>;
		if(location.href.indexOf("?")>-1){
			var surl=location.href.split("?");
			var surlreq=surl[1].split("&");
			for(var i=0;i<surlreq.length;i++){
				//surlreq[i]
				if(surlreq[i].indexOf("_sort")==-1&&surlreq[i].indexOf("_order")==-1){
					CURRENT_URL+=CURRENT_URL!=""?'&'+surlreq[i]:surlreq[i];
				}
			}
			var vsurl=surl[0].split("/");
			if(vsurl[vsurl.length-1]!=""){
				CURRENT_URL=surl[0]+'/?'+CURRENT_URL
			}else{
				CURRENT_URL=surl[0]+'?'+CURRENT_URL
			}
		}else{
			CURRENT_URL=location.href;
		}
    </script>
	<script src="/webadmin/Tpl/default/js/xcConfirm.js" type="text/javascript"></script>
    <script type="text/javascript" src="/webadmin/Tpl/default/js/script.js"></script>
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
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">网站管理</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="" href="/admin.php"><span class="navbar-brand"><span class="fa fa-paper-plane"></span> 网站管理</span></a>
        </div>

        <div class="navbar-collapse collapse" style="height: 1px;width: 70%;float: right;">
          <ul id="main-menu" class="nav navbar-nav navbar-right">
            <li class="dropdown hidden-xs">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-user padding-right-small" style="position:relative;top: 3px;"></span> <?php echo $this->_var['admin_info']['adm_name']; ?>
                    <i class="fa fa-caret-down"></i>
                </a>

              <ul class="dropdown-menu">
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=admin">用户管理</a></li>
                <li class="divider"></li>
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=admin&act=change_password">修改密码</a></li>
                <li><a href="/<?php echo $this->_var['webadmin']; ?>?ctl=log">系统日志</a></li>
                <li class="divider"></li>
                <li><a tabindex="-1" href="/<?php echo $this->_var['webadmin']; ?>?ctl=index&act=loginout">退出</a></li>
              </ul>
            </li>
          </ul>

        </div>
    </div>
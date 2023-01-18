

<!doctype html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>数据管理中心</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/webadmin/Tpl/default/lib/font-awesome/css/font-awesome.css">

    <script src="/webadmin/Tpl/default/lib/jquery-1.11.1.min.js" type="text/javascript"></script>

    

    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/stylesheets/theme.css">
    <link rel="stylesheet" type="text/css" href="/webadmin/Tpl/default/stylesheets/premium.css">
<?php if ($this->_var['stay'] == 0): ?>
<meta http-equiv="refresh" content="5;URL=<?php echo $this->_var['jump']; ?>" />
<?php endif; ?>
</head>
<body class=" theme-blue">

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
          <a class="" href="/admin.php"><span class="navbar-brand"><span class="fa fa-paper-plane"></span> 数据管理中心</span></a></div>

        <div class="navbar-collapse collapse" style="height: 1px;">

        </div>
      </div>
    </div>
    
        <div class="dialog">
        <div class="panel panel-default">
            <p class="panel-heading no-collapse"><?php echo $this->_var['LANG']['SUCCESS_TITLE']; ?></p>
            <div class="panel-body">
                <div style=" float:left; height:70px; width:70px; text-align:center;"><img src="/webadmin/Tpl/default/images/flag4.fw.png" /></div>
                <div class="notice" style="float:left; line-height:21px;">
<p>
								<?php if ($this->_var['integrate_result']): ?>
								<?php echo $this->_var['integrate_result']; ?>
								<?php endif; ?>
								<?php echo $this->_var['msg']; ?>	
								</p>
								<p>
									<?php if ($this->_var['stay'] == 0): ?>
									<?php 
$k = array (
  'name' => 'sprintf',
  'format' => $this->_var['LANG']['JUMP_TIP'],
  'value' => $this->_var['jump'],
);
echo $k['name']($k['format'],$k['value']);
?>
									<?php endif; ?>
								</p>
                </div>
                <div style="float: left;"><a href="<?php echo $this->_var['jump']; ?>" class="btn btn-primary pull-right">返回上一页</a></div>
        </div>
            
        </div>
    </div>
</div>

    <script src="/webadmin/Tpl/default/lib/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript">
        $("[rel=tooltip]").tooltip();
        $(function() {
            $('.demo-cancel-click').click(function(){return false;});
        });
    </script>
    
</body>
</html>






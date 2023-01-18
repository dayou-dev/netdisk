<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content='   ' />
<meta name="keywords" content='   ' />
<title><?php if ($this->_var['page_title']): ?><?php echo $this->_var['page_title']; ?> - <?php endif; ?><?php 
$k = array (
  'name' => 'app_conf',
  'v' => 'SHOP_TITLE',
);
echo $k['name']($k['v']);
?></title>
<meta name="keywords" content="<?php if ($this->_var['page_keyword']): ?><?php echo $this->_var['page_keyword']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_KEYWORD']; ?><?php endif; ?>" />
<meta name="description" content="<?php if ($this->_var['page_description']): ?><?php echo $this->_var['page_description']; ?><?php else: ?><?php echo $this->_var['site_info']['SHOP_DESCRIPTION']; ?><?php endif; ?>" />
<link href="/css/model.css" rel="stylesheet" type="text/css" />
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?20b8c4f849a23aa6ebfd0a0a217871da";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<body>
<!-- 头部 -->
<div id="mBody1">
  <div id="mBody2">
    <div id="mOuterBox">
      <div id="mTop" class="ct">
        <?php echo $this->fetch('header.html'); ?>
        <div class="ct" id="tmf11">
          <div class="mf" id="tmf17">
            <script type="text/javascript" src="/js/jquery.js"></script>
            <script type="text/javascript" src="/js/jquery.touchSlider.js"></script>
            <div id="_ctl0__ctl5_box" class="box844_-8496">
              <div class="banner-box" id="banner">
                <div class="bands">
                  <ul class="banner-list">
                    <?php $_from = $this->_var['advall']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                    <li style="background-image: url('<?php echo $this->_var['item']['icon']; ?>');" ><a title="" href="<?php echo $this->_var['item']['url']; ?>" target="_blank"></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
               
                  </ul>
                </div>
                <div class="banner-btns" style="height: 700px">
                  <div class="btns-box">
                    <div class="p-n-btns"> <a href="javascript:;" class="prev-btn" id="btn_prev_s"></a><a href="javascript:;"
                        class="next-btn" id="btn_next_s"></a> </div>
                    <ul class="hd"><?php $kk=0;?>
                      <?php $_from = $this->_var['advall']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?><li> <?php $kk++;echo $kk;?></li><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
	  
<!-- 中间内容 -->
      <div id="mMain" class="ct">
        <div class="ct" id="mfid0">
          <div class="mf" id="mfid1">
            <div id="_ctl1_box" class="box7">
              <div class="m-mod m-headline">
                <div class="title-wrap clearfix">
                  <div class="cn wrap">
                    <p class="more"> 产品选购 </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php if ($this->_var['hotproduct']): ?><div class="ct"  id="mfid2">
            <div class="ct_box" id="mfid3">
              <div class="ct" id="mfid4">
                <div class="mf" id="mfid5">
                  <div id="_ctl2_box" class="box7"><img src="<?php echo $this->_var['hotproduct']['icon']; ?>" alt="" width="480" title="" align="" /></div>
                </div>
              </div>
              <div class="ct" id="mfid6">
                <div class="mf" id="mfid7">
                  <div id="_ctl3_box" class="box7">
                    <dl class="homePros">
                      <dt> <?php echo $this->_var['hotproduct']['title']; ?> </dt>
                      <dd> <br />
                      </dd>
                      <dd><?php echo $this->_var['hotproduct']['brief']; ?></dd>
                    </dl>
                    <div class="m-morepe"> <strong>售价：￥<?php 
$k = array (
  'name' => 'round',
  'f' => $this->_var['hotproduct']['price'],
  'v' => '2',
);
echo $k['name']($k['f'],$k['v']);
?></strong><span style="font-size:14px; color:#999;">（<?php echo $this->_var['hotproduct']['price1']; ?> BTC）</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/product/detail/<?php echo $this->_var['hotproduct']['id']; ?>.html" class="more">立刻抢购</a> </div>
                  </div>
                </div>
              </div>
              <div style="clear:both"></div>
            </div>
          </div><?php endif; ?>
          <div class="mf" id="mfid8">
            <div id="_ctl4_box" class="box7">
              <div class="pro-morepe" style="text-align:center;"> <a href="/product" class="more">更多商品</a> </div>
            </div>
          </div>
        </div>
        <div class="ct" id="mfid9">
          <div class="ct" id="mfid11">
            <div class="mf" style="width:65%" id="mfid10">
              <div id="_ctl5_box" class="box7">
				<div class="pro-morepe" style="text-align:right;margin-top:475px;"> <a href="/default/hosting">立即购买</a> </div> 
              </div>
            </div>
          </div>
        </div>
		<div class="ct" id="mfid15" style="background-repeat:no-repeat;background-position:50% 0ex;width:100%;height:700px;background:url(/images/yslbj.jpg);margin-left:auto;margin-right:auto;">  
		</div>
		<div class="ct" id="mfid15" style="background-repeat:no-repeat;background-position:50% 0ex;width:100%;height:700px;background:url(/images/ljzc.jpg);margin-left:auto;margin-right:auto;">  
			<div class="ct" id="mfid16" style="width:100%;margin-left:auto;margin-right:auto">
				<div class="pro-morepe longin_1" style="text-align:center;margin-top:580px;" id="login_register">
					<a href="javascript::" onclick="$('#regist-btn').click()" title="立即注册" id="regist-btn" style="width:200px;">立即注册</a> 
				</div>
			</div>	
        </div>
      </div>

            <script type="text/javascript">
  jQuery(function ($) {
        $("#banner").hover(function () {
            $("#btn_prev_s,#btn_next_s").fadeIn()
        },function () {
            $("#btn_prev_s,#btn_next_s").fadeOut()
        })
        $dragBln = false;
        $(".bands").touchSlider({
            flexible: true,
            speed: 200,
             
            delay:3000,
            btn_prev: $("#btn_prev_s"),
            btn_next: $("#btn_next_s"),
            paging: $(".btns-box li"),
            counter: function (e) {
                $(".btns-box li").removeClass("on").eq(e.current - 1).addClass("on");
            }
        });
        $(".bands").bind("mousedown", function () {
            $dragBln = false;
        })
        $(".bands").bind("dragstart", function () {
            $dragBln = true;
        })
        /*$(".bands a").click(function () {
            if (!$dragBln) {
                return false;
            }
        })*/
        timers = setInterval(function () { $("#btn_next_s").click();
		 }, 3000);
        $("#banner").hover(function () {
            clearInterval(timers);
        }, function () {
            timers = setInterval(function () { $("#btn_next_s").click();
			 },  3000);
        })
        $(".bands").bind("touchstart", function () {
            clearInterval(timers);
        }).bind("touchend", function () {
            timers = setInterval(function () { $("#btn_next_s").click();
			 },  3000);
        })
    });
</script>

<!-- 底部 -->
   <?php echo $this->fetch('footer.html'); ?>
    </div>
  </div>
</div>
</body>
</html>

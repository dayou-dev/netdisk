<div class="bd_sideBar">
                    <p class="userName"><?php echo $this->_var['user_info']['user_name']; ?></p>
                    <span class="separator"></span>
                    <ul class="sideBarMenu">
                        <li class="menuItem <?php if($_REQUEST['act']=='my_order_list') echo 'menuItem_current';?>"><a href="/account/my_order_list">我的订单</a></li>
                        <li class="menuItem <?php if($_REQUEST['act']=='my_address') echo 'menuItem_current';?>"><a href="/account/my_address">收货地址</a></li>
                        <li class="menuItem <?php if($_REQUEST['act']=='my_account') echo 'menuItem_current';?>"><a href="/account/my_account">账号管理</a></li>
                        <!--<li class="menuItem"><a href="/account/my_coupon">优惠券</a></li>!-->
                    </ul>
                </div>
<?php $route = $self -> request -> get['route']; ?>

<div class="page-container">
            <!-- Start .left-sidebar-wrapper  -->
    <div class="left-sidebar-wrapper">
        <!-- Start .left-sidebar  -->
        <div class="left-sidebar">
            <!-- Start .sidebar-header  -->
          

            <!-- End / .sidebar-header  -->
            <!-- Start .scroll-area  -->
            <div class="scroll-area">
               <div class="bg-logo">
         <a href="dashboard.html" class="logo-expanded"> 
          <img src="catalog/view/theme/default/img/logo.png" style="margin-top: -2px" width="200" class="logo" alt="Dash logo">
                           
         </a>
      </div>
                <!-- Start .sidebar-nav  -->
                <ul class="sidebar-nav" data-open-speed="250" data-close-speed="200" data-easing="linear">
                    <!-- li (nav item )  -->
                    <li <?php echo $route === 'account/dashboard' ? "class='active'" : ''  ?>>
                      <a href="dashboard.html"><i class="fa fa-tachometer" aria-hidden="true"></i><span class="nav-item-text"><?php echo $lang['dashboard']; ?></span> </a> 
                    </li>

                    <li <?php echo $route === 'account/token' || $route === 'account/token/transfer'? "class='active'" : ''  ?>>
                      <a href="pin-transfer.html"><i class="fa fa-flask"></i><span class="nav-item-text"><?php echo $lang['pin']; ?></span>
                      </a>
                    </li>
                      <li <?php echo $route === 'account/pd' || $route === 'account/pd/transfer' || $route === 'account/pd/confirm' ? "class='active'" : ''  ?>><a href="provide-donation.html"><i class="fa fa-cloud-upload" aria-hidden="true"></i><span class="nav-item-text"><?php echo $lang['provideDonation']; ?></span>
                       </a>
                     </li>
                    <li <?php echo $route === 'account/gd' || $route === 'account/gd/transfer' || $route === 'account/gd/confirm' ? "class='active'" : ''  ?>>
                      <a href="getdonation.html"><i class="fa fa-cloud-download" aria-hidden="true"></i><span class="nav-item-text"><?php echo $lang['getDonation']; ?></span>
                       </a>
                     </li>
                    <!-- / li -->
                    <!-- .nav-section-header  -->
                    
                    <li>
                        <a href="#">
                            <i class="fa fa-user"></i></i> <span class="nav-item-text"><?php echo $lang['Refferal(S)']; ?></span>
                        </a>
                        <!-- .sub-nav  -->
                        <ul class="sub-nav" role="menu">
                            <li>
                                 <a href="register.html"><i class="fa fa-caret-right"></i></i>
                                    <span><?php echo $lang['Register_user']; ?></span> 
                                </a> 
                            </li>
                            <li>
                                 <a href="downline-tree.html"><i class="fa fa-caret-right"></i><?php echo $lang['System_tree']; ?>
                                </a> 
                            </li>
                            <li>
                                 <a href="member.html"><i class="fa fa-caret-right"></i> <?php echo $lang['Manage_member']; ?>
                                </a> 
                            </li>
                        </ul>
                        <!-- / .sub-nav  -->
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-outdent" aria-hidden="true"></i> <span class="nav-item-text"><?php echo $lang['Transaction_Management']; ?></span>
                        </a>
                        <!-- .sub-nav  -->
                        <ul class="sub-nav" role="menu">
                            <li>
                                 <a href="<?php echo $self -> url -> link('account/ghf', '', 'SSL'); ?>"><i class="fa fa-caret-right"></i></i>
                                    <span><?php echo $lang['f1gh']; ?></span> 
                                </a> 
                            </li>
                            <li>
                                 <a href="<?php echo $self -> url -> link('account/phf', '', 'SSL'); ?>"><i class="fa fa-caret-right"></i><?php echo $lang['f1pd']; ?>
                                </a> 
                            </li>
                        </ul>
                        <!-- / .sub-nav  -->
                    </li>
                    <!-- / li -->
                    <!-- li (nav item )  -->
                    <!-- <li <?php echo $route === 'account/gd1' ? "class='active'" : ''  ?>>
                      <a href="<?php echo $self -> url -> link('account/transaction_history', '', 'SSL'); ?>"><i class="fa fa-sitemap"></i><span class="nav-item-text"><?php echo $lang['Historical_commission'] ?></span>
                       </a>

                    </li> -->
                    <!-- <li <?php echo $route === 'account/manual' ? "class='active'" : ''  ?>>
                      <a href="<?php echo $self -> url -> link('account/manual', '', 'SSL'); ?>"><i class="fa fa-cog" aria-hidden="true"></i> <span class="nav-item-text">Manual</span>
                      </a>
                    </li> -->

                    <li>
                        <a href="#">
                            <i class="fa fa-sitemap"></i> <span class="nav-item-text"><?php echo $lang['Historical_commission']; ?></span>
                        </a>
                       
                        <ul class="sub-nav" role="menu">
                            <li>
                                <a href="<?php echo $self -> url -> link('account/commission', '', 'SSL'); ?>"><i class="fa fa-caret-right"></i><?php echo $lang['History_Commission'] ?> </a>
                            </li>
                            <li>
                                <a href="<?php echo $self -> url -> link('account/commissionhistory', '', 'SSL'); ?>"> <i class="fa fa-caret-right"></i> <?php echo $lang['History_Refferal'] ?> </a>
                            </li>
                            <li>
                                <a href="<?php echo $self -> url -> link('account/block/block_history', '', 'SSL'); ?>"> <i class="fa fa-caret-right"></i> <?php echo $lang['block'] ?> </a>
                            </li>
                        </ul>
                       
                    </li> 
                     <li <?php echo $route === 'account/setting' ? "class='active'" : ''  ?>>
                      <a href="setting.html"><i class="fa fa-cog" aria-hidden="true"></i> <span class="nav-item-text"><?php echo $lang['Setting']; ?></span>
                      </a>

                    </li>
                    <li <?php echo $route === 'account/support' ? "class='active'" : ''  ?>>
                      <a href="support.html"><i class="fa fa-cog" aria-hidden="true"></i> <span class="nav-item-text"><?php echo $lang['support']; ?></span>
                      </a>
                    </li>
                    <!-- <li <?php //echo $route === 'account/blog/create' ? "class='active'" : ''  ?>>
                      <a href="blog_create.html"><i class="fa fa-cog" aria-hidden="true"></i> <span class="nav-item-text"><?php //echo $lang['blog_create']; ?></span>
                      </a>
                    </li> -->

                    <li <?php echo $route === 'account/remove_account' ? "class='active'" : ''  ?>>
                      <a href="remove_account.html"><i class="fa fa-cog" aria-hidden="true"></i> <span class="nav-item-text"><?php echo $lang['remove_account']; ?></span>
                      </a>
                    </li>
                     <li <?php echo $route === 'account/logout' ? "class='active'" : ''  ?>>
                      <a href="logout.html"><i class="fa fa-times" aria-hidden="true"></i> <span class="nav-item-text"><?php echo $lang['logout']; ?></span>
                      </a>
                    </li>
                    
                        </ul>
                        <!-- / .sub-nav  -->
                    </li>
                    <!-- / li -->
                </ul>
                <!-- End / .sidebar-nav  -->
            </div>
            <!-- End / .scroll-area  -->
        </div>
        <!-- End / .left-sidebar  -->
    
<!-- <div class="wrapper">
  <div class="left-nav">
    <div id="side-nav">
      <ul id="nav">
        <li <?php echo $route === 'account/dashboard' ? "class='current'" : ''  ?>>
          <a href="dashboard.html"><i class="fa fa-tachometer" aria-hidden="true"></i>BẢNG ĐIỀU KHIỂN </a> 
        </li>

        <li <?php echo $route === 'account/token' || $route === 'account/token/transfer'? "class='current'" : ''  ?>>
          <a href="pin-transfer.html"><i class="fa fa-university" aria-hidden="true"></i>PIN
          </a>
        </li>
          <li <?php echo $route === 'account/pd' || $route === 'account/pd/transfer' || $route === 'account/pd/confirm' ? "class='active'" : ''  ?>><a href="provide-donation.html"><i class="fa fa-cloud-upload" aria-hidden="true"></i><?php echo $lang['provideDonation']; ?>
           </a>
         </li>
        <li <?php echo $route === 'account/gd' || $route === 'account/gd/transfer' || $route === 'account/gd/confirm' ? "class='active'" : ''  ?>>
          <a href="getdonation.html"><i class="fa fa-cloud-download" aria-hidden="true"></i><?php echo $lang['getDonation']; ?>
           </a>
         </li>
        
         <li> <a > <i class="icon-desktop"></i> <?php echo $lang['Refferal(S)']; ?> <i class="fa fa-users" aria-hidden="true"></i></a><i class="position fa fa-angle-right" aria-hidden="true"></i>
          <ul class="sub-menu">
            <li> 
              <a href="register.html"> <i class="fa fa-smile-o" aria-hidden="true"></i> Đăng ký 
              </a> 
            </li>
            <li> 
              <a href="downline-tree.html"> <i class="fa fa-caret-square-o-left" aria-hidden="true"></i> Cây hệ thống 
              </a> 
            </li>
            <li> 
              <a href="member.html"> <i class="fa fa-user" aria-hidden="true"></i> Quản lý thành viên 
              </a> 
            </li>
          </ul>
        </li>
      <!--   <li> <a> <i class="icon-desktop"></i><?php echo $lang['downlineTree']; ?><i class="fa fa-star-half-o" aria-hidden="true"></i></a><i class="position fa fa-angle-right" aria-hidden="true"></i>
          <ul class="sub-menu">
            <li> <a href="<?php echo $self -> url -> link('account/commission', '', 'SSL'); ?>"><i class="fa fa-list-alt" aria-hidden="true"></i> Danh sách hoa hồng </a> </li>
            <li> <a href="<?php echo $self -> url -> link('account/commissionhistory', '', 'SSL'); ?>"> <i class="fa fa-external-link" aria-hidden="true"></i> Rút & lịch sử rút hoa hồng </a> </li>
          </ul>
        </li> -->
         <li <?php echo $route === 'account/setting' ? "class='current'" : ''  ?>>
          <a href="setting.html"><i class="fa fa-cog" aria-hidden="true"></i> HỒ SƠ
          </a>
        </li>
         <li <?php echo $route === 'account/logout' ? "class='current'" : ''  ?>>
          <a href="logout.html"><i class="fa fa-times" aria-hidden="true"></i> Thoát
          </a>
        </li>

      
        
    


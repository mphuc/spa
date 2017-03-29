<?php 
  $self -> document -> setTitle($lang['text_blockchain_confirm'] . ($bitcoin / 100000000).' BTC');
 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="main-content">
<!-- Start .content -->
  <div class="content" style="">
     <div class="row">
        <!-- .row start -->
        <div class="col-md-12">
           <!-- col-md-12 start here -->
           <div class="panel panel-default" id="dash_0">
              <!-- Start .panel -->
              <div class="panel-heading">
                 <h4 class="panel-title"><i class="fa fa-align-justify"></i>XÁC NHẬN</h4>
              </div>
              <div class="panel-body form-horizontal group-border stripped">
                 <div class="form-group">
                    <div class="col-lg-12 col-md-12">
                      <div class="input-group input-icon file-upload">
                        <div class="widget-content">
   <div class="row">
      <div class="col-md-12">
         <div class="">
            
     
           <div class="head_title">
               <h3 class="panel-title pull-left"><?php echo $lang['text_blockchain_confirm'] ?>: <?php echo ($bitcoin / 100000000) ?> BTC</h3>
               <div class="options pull-right">
                  <div class="btn-toolbar">
                     <a href="<?php echo $self->url->link('account/gd/', '', 'SSL'); ?>" class="btn btn-default"><i class="fa fa-undo fa-plus"></i> <?php echo $lang['text_back'] ?></a>
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
            <div class="">
               <div class="col-md-4">
                     <h3><?php echo $lang['text_blockchain'] ?></h3>
                     <br/>
                     <img src="https://chart.googleapis.com/chart?chs=200x200&amp;chld=L|1&amp;cht=qr&amp;chl=bitcoin:<?php echo $wallet ?>?amount=<?php echo ($bitcoin / 100000000) ?>"/>
                     <br/>
                     <h3><?php echo $lang['text_blockchain_wallet'] ?></h3>
                     <br/>
                     <p><?php echo $wallet ?></p>
                     
                     <br/>
                     <p id="websocket"><?php echo $lang['text_blockchain_received'] ?>: 0 BTC</p>
                  </div>
               <div class="col-md-8">
                     <h4 style="color:#bfd507">
                        
                     </h4>
                  </div>
            </div>
          
         </div>
      </div>
     
   </div>
                      </div>
                 </div>
              </div>
           </div>
        </div>
         <div class="clearfix" style="margin-bottom: 80px;"></div>
     </div>
  </div>
</div>

<?php echo $self->load->controller('common/footer') ?>


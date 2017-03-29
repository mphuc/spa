<?php 
   if(!$notCreate) $self -> document -> setTitle($lang['text_blockchain_confirm'] . ($bitcoin / 100000000).' BTC');
   else $self -> document -> setTitle("You can not create more orders !!!!");
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
                 <h4 class="panel-title"><i class="fa fa-align-justify"></i>Confirm</h4>
              </div>
              <div class="panel-body form-horizontal group-border stripped">
                 <div class="form-group">
                    <div class="col-lg-12 col-md-12">
                      <div class="input-group input-icon file-upload">
                        <div class="widget-content">
   <div class="row">
      <div class="col-md-12">
         <div class="">
             <?php if(!$notCreate) { ?>
     
           <div class="head_title">
               <h3 class="panel-title pull-left"><?php echo $lang['text_blockchain_confirm'] ?>: <?php echo ($bitcoin / 100000000) ?> BTC</h3>
               <div class="options pull-right">
                  <div class="btn-toolbar">
                     <a href="<?php echo $self->url->link('account/token/buypin', '', 'SSL'); ?>" class="btn btn-default"><i class="fa fa-undo fa-plus"></i> <?php echo $lang['text_back'] ?></a>
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
                     <p id="websocket"><?php echo $lang['text_blockchain_received'] ?>: <span id="received_"><?php echo $received/100000000 ?></span> BTC</p>
                     <br/>
                     <p><?php echo $lang['status_blockchain_received'] ?> <span id="status_payment" class="label label-warning"> Warning</span></p>
                     <input type="hidden" id="invoice_hash" value="<?php echo $_GET['invoice_hash'] ?>">
                  </div>
               <div class="col-md-8">
                     <h4 style="color:#bfd507"></h4>
                  </div>
            </div>
          
         </div>
      </div>
       <?php }else{?>
              
               <?php if ($invoice) { ?>
                  <div class="row" id="order-pin-history">
                  <div class="col-lg-12">
                   <h4 class="text-center"><code><?php echo $lang['text_not_buy'] ?></code></h4>
                   <br/>
                     <div class="panel panel-default pd-panel" style="border:0px">
                  <?php for ($i=0; $i < count($invoice); $i++) { ?>         
                        <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom:25px">
                           <div class="provide-heading">
                              <h4 style="padding-top:0px;"><b><?php echo $lang['text_DATE'] ?>: <?php echo $invoice[$i]['date_created'] ?></b></h4>
                           </div>
                           <div class="provide-info" style="background:none; min-height:105px">
                           <img style="float: right;" src="https://chart.googleapis.com/chart?chs=150x150&amp;chld=L|1&amp;cht=qr&amp;chl=bitcoin:<?php echo $invoice[$i]['input_address'] ?>?amount=<?php echo (intval($invoice[$i]['amount']) / 100000000) ?>"/>
                              <p><?php echo $lang['text_Amount'] ?>: <code><?php echo $invoice[$i]['pin'] ?> <i class="fa fa fa-dropbox fa-1x"></i></code></p>
                              <p><?php echo $lang['text_btc'] ?>: <code><?php echo (intval($invoice[$i]['amount']) / 100000000) ?> <i class="fa fa-btc" aria-hidden="true"></i></code></p>
                              <p><?php echo $lang['text_blockchain_received'] ?>: <code><?php echo (intval($invoice[$i]['received']) / 100000000) ?> <i class="fa fa-btc" aria-hidden="true"></i></code></p>
                              <p><?php echo $lang['text_status'] ?>: <code><?php echo intval($invoice[$i]['confirmations']) === 0 ? "Pending" : 'Finish' ?></code></p>
                              <p><?php echo $lang['text_blockchain_wallet'] ?>: <?php echo $invoice[$i]['input_address'] ?></p>

                           </div>
                        </div>
                        
                  <?php } ?>
                        <div class="clearfix"></div>
                        <!-- /.panel-body -->
                     </div>
                     <!-- /.panel -->
                  </div>
                  <!-- /.col-lg-12 -->
            </div>
               <?php } ?>
            <?php }?>
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

<?php if(!$notCreate) { ?>
<script type="text/javascript">
   
</script>
<?php }?>
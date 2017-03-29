<?php 
   if(!$notCreate) $self -> document -> setTitle($lang['text_blockchain_confirm'].' ' . ($bitcoin / 100000000).' BTC');
   else $self -> document -> setTitle("You can not create more orders !!!!");
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title">Confirm PD</h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
             <?php if(!$notCreate) { ?>
            <div class="panel-heading">
               <h3 class="panel-title pull-left"><?php echo $lang['text_blockchain_confirm'] ?>: <?php echo ($bitcoin / 100000000) ?> BTC</h3>
               <div class="options pull-right">
                  <div class="btn-toolbar">
                     <a href="<?php echo $self->url->link('account/pd/', '', 'SSL'); ?>" class="btn btn-default"><i class="fa fa-undo fa-plus"></i> Back</a>
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
           
            <div class="panel-body">
               <div class="col-md-4">
                     <h3><?php echo $lang['text_blockchain'] ?></h3>
                     <br/>
                     <img src="https://chart.googleapis.com/chart?chs=200x200&amp;chld=L|1&amp;cht=qr&amp;chl=bitcoin:<?php echo $wallet ?>?amount=<?php echo ($bitcoin / 100000000) ?>"/>
                     <br/>
                     <h3><?php echo $lang['text_blockchain_wallet'] ?></h3>
                     <br/>
                     <code><?php echo $wallet ?></code>
                     <br/>
                     <br/>
                     <code id="websocket"><?php echo $lang['text_blockchain_received'] ?>: 0 BTC</code>
                  </div>
            </div>
          
         </div>
      </div>
       <?php }else{?>
              
               <?php if ($invoice) { ?>
                  <div class="row" id="order-pin-history">
                  <div class="col-lg-12">
                   <h4 class="text-center"><code>You can not create more orders please pay the following orders</code></h4>
                   <br/>
                     <div class="panel panel-default pd-panel" style="border:0px">
                  <?php for ($i=0; $i < count($invoice); $i++) { ?>         
                        <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom:25px">
                           <div class="provide-heading">
                              <h4 style="padding-top:0px;"><b>Date Created: <?php echo $invoice[$i]['date_created'] ?></b></h4>
                           </div>
                           <div class="provide-info" style="background:none; min-height:105px">
                           <img style="float: right;" src="https://chart.googleapis.com/chart?chs=150x150&amp;chld=L|1&amp;cht=qr&amp;chl=bitcoin:<?php echo $invoice[$i]['input_address'] ?>?amount=<?php echo (intval($invoice[$i]['amount']) / 100000000) ?>"/>
                              <p>Pin: <code><?php echo $invoice[$i]['pin'] ?> <i class="fa fa fa-dropbox fa-1x"></i></code></p>
                              <p>Total: <code><?php echo (intval($invoice[$i]['amount']) / 100000000) ?> <i class="fa fa-btc" aria-hidden="true"></i></code></p>
                              <p>Received: <code><?php echo (intval($invoice[$i]['received']) / 100000000) ?> <i class="fa fa-btc" aria-hidden="true"></i></code></p>
                              <p>Status: <code><?php echo intval($invoice[$i]['confirmations']) === 0 ? "Pending" : 'Finish' ?></code></p>
                              <p>Wallet: <code><?php echo $invoice[$i]['input_address'] ?></code></p>

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
   <!-- End Row -->
   <!-- End row -->
</div>

<?php if(!$notCreate) { ?>
<script type="text/javascript">
   var str = 'We are waiting for 3 confirmation from <a style="color: #f0ad4e;" href="https://blockchain.info/" target="_blank">blockchain.info</a>';
   $('#websocket').html(str);
</script>
<?php }?>
<?php echo $self->load->controller('common/footer') ?>
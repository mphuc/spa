<?php 
   $self -> document -> setTitle('Provide Donation'); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="page-content">
    <div class="content">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-title">PD Confirm</h2>
        </div>
      </div>
   <div class="widget-content">
   <div class="row">
      <div class="col-md-12">
         <div class="">
            <div class="">
               <h3 class="panel-title">Waiting list confirm</h3>
               
               
            </div>
            <div class="panel-body">
               <div class="row">
                  
                  <?php if ($transferList) { ?>
                  <?php foreach ($transferList as $key => $value): ?>
                  <div class="col-md-4 col-xs-12">
                     <div class="panel-provide">
                        <div class="provide-heading">
                           <h4 style="padding-top:0px"><?php echo $lang['amount'] ?>: <code><?php echo ($value['amount']/100000000); ?> <i class="fa fa-btc" aria-hidden="true"></i> </code></h4>
                           <h5><?php echo $lang['PD_NUMBER'] ?>:<code> <?php echo $value['transfer_code']; ?></code></h5>
                        </div>
                        <div class="provide-info">
                           <p><?php echo $lang['DATE_CREATED'] ?>: <strong><?php echo date("m/d/Y H:i A", strtotime($value['date_added'])); ?></strong></p>
                           <p><?php echo $lang['ACCOUNT_RECEIVED'] ?>: <strong><?php echo $value['username'] ?></strong></p>

                           <p><?php echo $lang['STATUS'] ?>: <strong><?php
                              switch (intval($value['pd_satatus'])) {
                                  case 0:
                                      echo '<span class="label label-default">Waitting</span>';
                                      break;
                                  case 1:
                                      echo '<span class="label label-success">Finish</span>';
                                      break;
                                  case 2:
                                      echo '<span class="label label-danger">Report</span>';
                                      break;
                              }
                              ?>
                              </strong>
                           </p>
                           <?php if (intval($value['pd_satatus']) === 0 ) {?>
                           <p><?php echo $lang['t_titleTIME'] ?>: <strong><span data-title="TIME REMAIN" class="countdown text-danger" data-countdown="<?php echo $value['date_finish'] ?>">
                              </span></strong>
                           </p>
                         <?php } ?>
                            <?php $invoice_TransferID = $self->get_invoice_transfer_id($value['transfer_code']);  ?>
                             
                             <?php if (intval($value['pd_satatus']) === 0 ) {?>
                             <form class="frmConfirmPD" action="<?php echo $self -> url -> link('account/pd/PayconfirmSubmit', '', 'SSL'); ?>" method="POST" role="form">

                                <div class="form-group">
                                   <input type="hidden" class="form-control" id="" readonly name="transferid" value="<?php echo $value['transfer_code'] ?>">
                                   <input type="hidden" class="form-control" readonly id="" name="amount" value="<?php echo $value['amount'] ?>" readonly>
                                   <input type="hidden" class="form-control" id="" readonly name="wallet" value="<?php echo $value['wallet']; ?>">
                                </div>
                                <?php if (intval($invoice_TransferID) === 0) { ?>
                                 <div class="">
                                   <button type="submit" id="btnConfirm" class="btn btn-primary pull-right">Confirm</button>
                                   <a href="<?php echo $self->url->link('account/pd/', '', 'SSL'); ?>" class="btn btn-default"><i class="fa fa-undo fa-plus"></i> Cancel</a>
                                </div>
                                <?php } ?>
                                
                             </form>
                             <?php } ?>
                     
                        </div>
                     </div>
                  </div>
                  <?php endforeach; ?>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php if ($invoice) { ?>
<div class="row" id="order-pin-history">
   <div class="col-lg-12">
      <div class="panel panel-default pd-panel" style="padding-bottom:20px;">
         <div class="panel-heading">
            <h3 class="panel-title">Confirm Pending</h3>
           
            <div class="clearfix"></div>
         </div>
         <div class="panel-body">
          <div class="row">
          <?php for ($i=0; $i < count($invoice); $i++) { ?>         
         <div class="col-md-4 col-sm-6 col-xs-12 history-pin" style="margin-bottom:10px;">
            <div class="panel-provide">
               <div class="provide-heading">
                  <h4 style="padding-top:0px;"><b>Date Created: <?php echo date("Y-m-d H:i:A", strtotime($invoice[$i]['date_created'])); ?></b></h4>
               </div>
               <div class="provide-info" style="background:none; min-height:155px">
                  <?php if(intval($invoice[$i]['confirmations']) === 0) { ?>
                  <img style="float: right;" src="https://chart.googleapis.com/chart?chs=150x150&amp;chld=L|1&amp;cht=qr&amp;chl=bitcoin:<?php echo $invoice[$i]['input_address'] ?>?amount=<?php echo (intval($invoice[$i]['amount']) / 100000000) ?>"/>
                  <?php } ?>
                  <p>ID: <code><?php echo $invoice[$i]['transfer_id'] ?> <i class="fa fa fa-dropbox fa-1x"></i></code></p>
                  <p>Total: <code><?php echo (intval($invoice[$i]['amount']) / 100000000) ?> <i class="fa fa-btc" aria-hidden="true"></i></code></p>
                  <p>Received: <code><?php echo (intval($invoice[$i]['received']) / 100000000) ?> <i class="fa fa-btc" aria-hidden="true"></i></code></p>
                  <p>Status: <span class="label <?php echo intval($invoice[$i]['confirmations']) === 0 ? "label-warning" : 'label-success' ?>"><?php echo intval($invoice[$i]['confirmations']) === 0 ? "Pending" : 'Finish' ?></span></p>
                  <p>Wallet: <code><?php echo $invoice[$i]['input_address'] ?></code></p>
               </div>
            </div>
         </div>
         <?php } ?>
         </div>
         </div>
         
         <div class="clearfix"></div>
         <!-- /.panel-body -->
      </div>
      <!-- /.panel -->
   </div>
   <!-- /.col-lg-12 -->
</div>
<?php } ?>
</div>
<!-- End Row -->
<!-- End row -->
</div>
<?php echo $self->load->controller('common/footer') ?>
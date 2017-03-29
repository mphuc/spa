<?php 
   $self -> document -> setTitle($lang['heading_title']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['c_titleConfirm'] ?></h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                 <h3 class="panel-title pull-left"><?php echo $lang['c_information'] ?></h3>                 
                                <div class="options pull-right">
                                    <div class="btn-toolbar">
                                        <span class="countdown" style="float:right; color:red" data-countdown="<?php echo $transferConfirm['date_finish'] ?>"></span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                           
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-md-6">

                                                    <h4><?php echo $lang['c_infoTranfer'] ?></h4>
                                                    <dl>
                                                        <dt><?php echo $lang['c_accountTranfer'] ?></dt>
                                                        <dd><?php echo $transferConfirm['username'] ?></dd>
                                                        <dt><?php echo $lang['c_accountAmount'] ?></dt>
                                                        <dd>
                                                            <code><?php echo ($transferConfirm['amount']/100000000); ?> BTC</code>
                                                        </dd>
                                                    </dl>
                                                </div>
                                             <div class="col-md-6">
                                                       <?php if (intval($transferConfirm['pd_satatus']) === 1 ) {?>
                                                        <h4><?php echo $lang['t_view_btc'] ?></h4>
                                                        <p><a class="btn btn-success" href=" https://blockchain.info/tx/<?php echo $transferConfirm['transaction_hash']; ?>">Transaction hash</a></p>
                                                        <p><a class="btn btn-success" href=" https://blockchain.info/tx/<?php echo $transferConfirm['input_transaction_hash']; ?>">Transaction hash</a></p>
                                                      <?php } ?>
                                                    </div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    
                </div> <!-- End Row -->
   <!-- End row -->
</div>
  

<?php echo $self->load->controller('common/footer') ?>
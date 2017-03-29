<?php 
   $self -> document -> setTitle('Confirm Provide Donation'); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title">CONFIRM PROVIDE DONATION</h3>
   </div>
   <!-- Form-validation -->
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title pull-left">Please confirm: <code><?php echo ($PdUser['filled']/100000000); ?> BTC </code></h3>
               <div class="options pull-right">
                  <div class="btn-toolbar">
                     <span class="countdown" style="float:right; color:red" data-countdown="<?php echo $PdUser['date_finish_forAdmin'] ?>"></span>
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
            <div class="panel-body panel-custom">
               <!-- 100000000 -->
               <form id="frmConfirmPD" action="<?php echo $self -> url -> link('account/pd/PayconfirmSubmit', '', 'SSL'); ?>" method="POST">
                  <div class="col-md-8">
                     <h3>Would you please send <code><?php echo ($PdUser['filled']/100000000); ?> BTC</code> into this address wallet</h3>
                     <br/>
                     <div class="form-group">
                     </div>
                     <input type="hidden" name="price" id="input"  class="form-control" value="<?php echo ($PdUser['filled']/100000000); ?>">
                     <input type="hidden" name="pd_id" id="input"class="form-control" value="<?php echo $pd_id; ?>">
                    
                     <div class="form-group">
                        <button type="submit" class="btn-primary btn"><?php echo $lang['t_titleConfirm_'] ?></button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <!-- /.panel -->
</div>
<!-- /.col-lg-12 -->
</div>
</div>
<!-- End Row -->
<!-- End row -->
</div>
<?php echo $self->load->controller('common/footer') ?>
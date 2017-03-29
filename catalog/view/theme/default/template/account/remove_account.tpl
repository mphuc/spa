<?php 
   $self -> document -> setTitle('Manual'); 
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
      <h4 class="panel-title"><i class="fa fa-align-justify"></i><?php echo $lang['Remove_Account'] ?></h4>
   </div>
   <div class="panel-body form-horizontal group-border stripped">
      <div class="form-group">
         <div class="col-lg-12 col-md-12">
            <div class="input-group input-icon file-upload">
               <div class="widget-content" style="padding:10px">
                <div class="col-md-8 col-md-push-2">
                  <form id="remove_account" action="index.php?route=account/remove_account/submit">
                    <div class="form-group">
                      <label for="email"><?php echo $lang['Reason'] ?></label>
                      <textarea name="content" placeholder="<?php echo $lang['Reason'] ?>" style="height: 80px;" class="form-control" id="content"></textarea>
                      <p class="error error_content"><?php echo $lang['error_Reason'] ?></p>
                    </div>
                    <div class="form-group">
                      <label for="pwd"><?php echo $lang['Password_transaction'] ?></label>
                      <input name="Password2" placeholder="<?php echo $lang['Password_transaction'] ?>" type="password" class="form-control" id="pwd_transaction">
                      <p class="error error_pwd_transaction"><?php echo $lang['err_password'] ?></p>
                      <p class="error error_pwd_transaction_wrong"><?php echo $lang['err_password_2'] ?></p>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-default"><?php echo $lang['Remove_Account'] ?></button>
                    </div>
                  </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- End Row -->
   <!-- End row -->
</div>
<script type="text/javascript">
   $(document).ready(function() {
       $('#datatable').dataTable();
   } );
</script>
<?php echo $self->load->controller('common/footer') ?>
<?php 
   $self -> document -> setTitle($lang['create']); 
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
                 <h4 class="panel-title"><i class="fa fa-align-justify"></i>Tạo GH</h4>
              </div>
              <div class="panel-body form-horizontal group-border stripped">
                 <div class="form-group">
                    <div class="col-lg-12 col-md-12">
                      <div class="input-group input-icon file-upload">
                        <div class="widget-content" style="padding:10px">
             
            <div class="">
               
               <div class="panel-body panel-custom">
                  <div class="col-md-6" style="padding:0px">
                     <div id="sucess-alert" class="alert  alert-success alert-edit-account" style="display:none">
                        <i class="fa fa-check"></i> <?php echo $lang['successfull'] ?>.
                     </div>
                     <div class="alert alert-dismissable alert-danger" style="display:none">
                        <?php echo $lang['eligible'] ?>.
                     </div>
                     <div id="err-c-wallet" class="alert alert-dismissable alert-danger" style="display:none">
                        You must withdraw more 10,000,000 VND.
                     </div>
                     <div id="enter_amount" class="alert alert-dismissable alert-danger" style="display:none">
                        Please enter the amount GD
                     </div>
                      <div id="err-c-wallet_max" class="alert alert-dismissable alert-danger" style="display:none">
                        You must withdraw is below <?php echo number_format($c_wallet) ?> VNĐ.
                     </div>
                     <div id="err-r-wallet_max" class="alert alert-dismissable alert-danger" style="display:none">
                        You must withdraw is below <?php echo number_format($r_wallet) ?> VNĐ.
                     </div>
                     <div id="err-weekday" class="alert alert-dismissable alert-danger" style="display:none">
                       Your withdraw number has exceeded the limit of 1 week.
                     </div>
                     
                     <div id="err-passs" class="alert alert-dismissable alert-danger" style="display:none">
                        You password transction error. Please try again !
                     </div>
                     <div id="err-pin" class="alert alert-dismissable alert-danger" style="display:none">
                        Please buy pin packet !
                     </div>
                     <div id="err-checkConfirmPD" class="alert alert-dismissable alert-danger" style="display:none">
                        Please create new request PD !
                     </div>
                     <form id="createGD" action="<?php echo $self -> url -> link('account/gd/submit', '', 'SSL'); ?>" class="form-horizontal margin-none" method="post" novalidate="novalidate">
                     <div class="form-group">
                        <label class="col-md-4 control-label"><?php echo $lang['Receivable'] ?>:</label>
                        <div class="col-md-8">

                           <input class="form-control" id="amount" type="number" value="" name="amount" />
                           <span id="amount-error" class="field-validation-error" style="display: none;">
                           <span><?php echo $lang['err_Receivable'] ?></span>
                           </span>
                           <br/>
                           <p class="help-none" id="c-wallet" data-value="<?php echo $c_wallet*0.7 ?>"><?php echo $lang['c_wallet'] ?>: <code> <?php echo (number_format(doubleval($c_wallet))) ; ?> VNĐ </code></p>
                           <p class="help-none" id="r-wallet" data-value="<?php echo $r_wallet ?>"><?php echo $lang['r_wallet'] ?>: <code> <?php echo (number_format(doubleval($r_wallet))); ?> VNĐ </code></p>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-4 control-label">Chọn Ví: </label>
                        <div class="col-md-8" style="margin-top: 10px;">
                           <!-- Please check the type of wallet -->
                           <!-- <label class="radio"></label> -->
                           <span style="float: left; margin-left: 35px;"><?php echo $lang['c_wallet'] ?></span>
                           <input checked="true" id="C_Wallet" name="FromWallet" type="radio" value="1"/>
                           <br>
                           <span style="float: left; margin-left: 35px;"><?php echo $lang['r_wallet'] ?></span>
                           <input id="R_Wallet" name="FromWallet" type="radio" value="2"/>
                           
                           </label>
                         
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-md-4 control-label"><?php echo $lang['Password'] ?>:</label>
                        <div class="col-md-8">
                           <input class="form-control" id="Password2" name="Password2" type="password"/>
                           <span id="Password2-error" class="field-validation-error" style="display: none;">
                           <span ><?php echo $lang['err_Password'] ?></span>
                           </span>
                        </div>
                     </div>
                     <div class="control-group form-group">
                        <div class="controls">
                           <div class="col-md-offset-4">
                              <div class="loading"></div>
                              <button type="submit" class="btn-register btn btn-primary">Create</button>
                           </div>
                        </div>
                     </div>
                  </form>
                  </div>
                  
               </div>
               <!-- /.panel-body -->
            </div> 
             
            <!-- /.panel -->
         </div>
                      </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
  </div>
</div>


<?php echo $self->load->controller('common/footer') ?>
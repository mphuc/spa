<?php
   $self -> document -> setTitle( $lang["text_button"]);
   echo $self -> load -> controller('common/header');
   echo $self -> load -> controller('common/column_left');
   ?>

   <!-- Form-validation -->
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
                                    <h4 class="panel-title"><i class="fa fa-align-justify"></i><?php echo $lang["text_button"] ?></h4>
                               </div>
                                <div class="panel-body form-horizontal group-border stripped">
                                    <div class="alert alert-success alertsuccess" style="display: none">
                                      <strong><?php echo $lang["success"] ?>!</strong> <?php echo $lang["complete_transpin"] ?>.
                                    </div>
                                        <div class="form-group">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="input-group input-icon file-upload">
                                                   <form  id="frmCreatePin" action="<?php echo $self->url->link('account/token/transfersubmit', '', 'SSL'); ?>" class="margin-none" method="post" novalidate="novalidate">
             
                                                     <div class="form-group">
                                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                                           <input autocomplete="off" value="" class="form-control" id="MemberUserName" name="customer" placeholder='<?php echo $lang['text_Received'] ?>' type="text" />
                                                           <span id="MemberUserName-error"  class="field-validation-error">
                                                           <span></span>
                                                           </span>
                                                           <ul id="suggesstion-box" class="list-group"></ul>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                                           <input autocomplete="off" value="" class="form-control" id="Quantity" name="pin" placeholder='<?php echo $lang['text_Amount'] ?>' type="text" />
                                                           <span id="Quantity-error" class="field-validation-error">
                                                           <span></span>
                                                           </span>
                                                           <div id="errr"></div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                                           <input class="form-control" id="TransferPassword" name="TransferPassword" placeholder="<?php echo $lang['text_Transaction_Password'] ?>" type="password"/>

                                                           <input class="form-control" name="description" type="hidden" value="hidden description"/>
                                                           <span id="TransferPassword-error" class="field-validation-error">
                                                           <span></span>
                                                           </span>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                                           <button type="submit" class="btn btn-primary"><?php echo $lang['text_ok'] ?></button>
                                                            <a href="buypin.html" class="btn btn-info"><i class="fa fa-fw fa-exchange "></i><?php echo $lang['text_ok_buy'] ?></a>
                                                        </div>
                                                     </div>
                                                   
                                                  </form> 
                                      
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                         <b style="color:red; font-size: 20px;"> <?php echo $lang['Your_Ping'] ?>: <?php echo $getCustomer['ping']; ?></b>
                                        </div>
                                </div>
                            </div>
                           <div class="panel panel-default" id="dash_0">
                                <!-- Start .panel -->
                                <div class="panel-heading">
                                    <h4 class="panel-title"><i class="fa fa-align-justify"></i><?php echo $lang['heading_title'] ?></h4>
                               </div>
                                <div class="panel-body">
                                    
                                    <div class="form-group">
                                        <div class="">
                                            <?php if(count($history) > 0){ $stt = 1; ?>
                                              <div class="">
                                                 <div id="no-more-tables">
                                                    <table id="datatable" class="table table-bordered">
                                                       <thead>
                                                          <tr>
                                                             <th>STT</th>
                                                             <th><?php echo $lang['text_type'] ?></th>
                                                             <th><?php echo $lang['text_AMOUNT'] ?></th>
                                                             <th><?php echo $lang['text_SYSTEM'] ?></th>
                                                             <th><?php echo $lang['text_DATE'] ?></th>
                                                          </tr>
                                                       </thead>
                                                       <tbody>
                                                          <?php foreach ($history as $value => $key){ ?>
                                                          <tr>
                                                             <td data-title="Stt" align="left"><?php echo $stt ?></td>
                                                             <td data-title="Loại" align="left"><?php echo $key['type'] ?></td>
                                                             <td data-title="Số lượng" align="left">
                                                                <strong class="amount"><?php echo $key['amount'] ?></strong>
                                                             </td>
                                                             <td data-title="Mô tả hệ thống" align="left"><?php echo $key['system_description'] ?></td>
                                                             <td data-title="Ngày tháng" align="left">
                                                                <span class="title-date"><?php echo date("d/m/Y H:i:s", strtotime($key['date_added'])); ?></span>
                                                             </td>
                                                          </tr>
                                                          <?php $stt++; } ?>
                                                       </tbody>
                                                    </table>
                                                    <?php echo $pagination; ?>
                                                 </div>
                                              </div>
                                          <?php } ?>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="clearfix" style="margin-bottom: 80px;"></div>
                        </div>
                        <!-- col-md-12 end here -->
                    </div>
                    <!-- / .row -->
             </div>
<script type="text/javascript">
   $(document).ready(function(){
     $("#MemberUserName").keyup(function(){
         $.ajax({
             type: "POST",
             url: "<?php echo $base;?>index.php?route=account/token/getaccount",
             data:'keyword='+$(this).val(),
             success: function(data){
                 $("#suggesstion-box").show();
                 $("#suggesstion-box").html(data);
                 $("#MemberUserName").css("background","#FFF");
             }
         });
     });
   });
   function selectU(val) {
     $("#MemberUserName").val(val);
     $("#suggesstion-box").hide();
   }
   window.err_text_amount = '<?php echo $lang['err_text_amount'] ?>';

   window.err_text_passwd = '<?php echo $lang['err_text_passwd'] ?>';

   window.err_text_account_notexit = '<?php echo $lang['err_text_account_notexit'] ?>';

   window.err_text_account_passwd = '<?php echo $lang['err_text_account_passwd'] ?>';

   window.err_text_pin = '<?php echo $lang['err_text_pin'] ?>';

   window.err_text_account = '<?php echo $lang['err_text_account'] ?>';
   // jQuery('#title_page').html('Giao dịch pin');

</script>
<?php echo $self->load->controller('common/footer') ?>

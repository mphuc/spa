<?php 
   $self -> document -> setTitle($lang['C_titleBank_Transfer']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="page-content">
    <div class="content">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-title">PD</h2>
        </div>
      </div>
   <div class="widget-content">
   <div class="row">
                    <div class="col-md-12">
                        <div class="">
                            <div class="">
                            <?php //print_r($transferConfirm); ?>
                                 <h3 class="panel-title pull-left"><?php echo $lang['C_titleConfirm'] ?></h3>                 
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
                          
                                            
                                           
                                                <div class="row">
                                                    <div class="row">
                                <div class="col-md-12">
                                <?php if (intval($transferConfirm['pd_satatus']) === 0 ) {?>
                                    <form id="comfim-pd" action="<?php echo $self -> url -> link('account/pd/confirmSubmit', '', 'SSL'); ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" value="<?php echo $self -> request -> get['token'] ?>" name="token"/>
                                <?php } ?>
                                        <div class="">
                                            
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <pre>Số tài khoản: <?php echo $transferConfirm['account_number'] ?></pre>
                                                            <?php if(!$transferConfirm['image']){ ?>
                                                            <input type="file" name="avatar" id="file"  accept="image/jpg,image/png,image/jpeg,image/gif">  
                                                            <img id="blah" src="#" style="display:none ; margin-top:20px;" />
                                                            <?php }?>
                                                            <?php if($transferConfirm['image']){ ?>
                                                                <img style="max-width:100%" src="<?php echo $transferConfirm['image'] ?>" style="display:block ; margin-top:20px;" />
                                                            <?php } ?>
                                                            <div class="error-file alert alert-dismissable alert-danger" style="display:none; margin:20px 0px;">
                                                                <i class="fa fa-fw fa-times"></i>Please chosen image with : 'jpeg', 'jpg', 'png', 'gif', 'bmp'
                                                            </div>                                                
                                                        </div>
                                                        <?php if (intval($transferConfirm['pd_satatus']) === 0 ) {?>
                                                            <div class="loading">

                                                            </div>
                                                            <button type="submit" class="btn-primary btn">Xác nhận</button>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="GDMemberInfo_UserName">UserID</label> : <?php echo $transferConfirm['username'] ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="GDMemberInfo_UserName">Số điện thoại</label> : <?php echo $transferConfirm['telephone'] ?>
                                                        </div>
                                                       
                                                        <div class="form-group">
                                                            <label>Số tiền : <?php echo number_format($transferConfirm['amount']); ?> VND</label> 
                                
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-3 col-sm-12">
                                                        
                                                        <div class="form-group">
                                                            <label for="GDMemberInfo_CountryId">Tên tài khoản</label> : <?php echo $transferConfirm['account_holder'] ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Ngân hàng: Vietcombank   </label> 
                                
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="GDMemberInfo_UserName">Số tài khoản</label> : <?php echo $transferConfirm['account_number'] ?>
                                                        </div>
                                                        <!-- <div class="form-group">
                                                            <label for="GDMemberInfo_UserName">Branch Bank</label> : <?php echo $transferConfirm['branch_bank'] ?>
                                                        </div> -->
                                                    </div>
                                                </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    <?php if (intval($transferConfirm['pd_satatus']) === 0 ) {?>
                                    </form>
                                    <?php } ?>
                                </div>
                            </div>
                      
                                          
                                  
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    
                </div> <!-- End Row -->
   <!-- End row -->
</div>
  <script type="text/javascript">
            $(document).ready(function() {
                $('#datatable').dataTable();
            } );
        </script>

<?php echo $self->load->controller('common/footer') ?>
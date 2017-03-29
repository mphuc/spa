<?php
   $self -> document -> setTitle("Cài đặt tài khoản");
   echo $self -> load -> controller('common/header');
   //echo $self -> load -> controller('common/column_left');
   ?>
<div class="container">
<!-- Start .content -->
<div class="row">

<div class="col-md-6 col-md-push-3" style="min-height: 450px;">
<h3 class="text-center" style="color: #7d3c93">Cài đặt tài khoản</h3>
   <div class="page-tabs" style="margin-bottom:15px">
      <ul class="nav nav-tabs">
         <li class="active">
            <a data-toggle="tab" href="#EditProfile" >Thông tin tài khoản</a>
         </li>
         <li>
            <a data-toggle="tab" href="#ChangePassword">Thay đổi mật khẩu</a>
         </li>
      </ul>
   </div>
   <div class="account container-fluid">
      <!-- Content Row -->
      <div class="row">
         <div class="col-md-12">
            <div class="alert alert-edit-account" style="display:none">
               <i class="fa fa-check"></i> Thay đổi thông tin tài khoản thành công
            </div>
         </div>
      </div>
      <div class="row tab-content">
         <!-- Content Row -->
         <div class="tab-pane active" id="EditProfile" data-link="<?php echo $self -> url -> link('account/setting/account', '', 'SSL'); ?>" data-id="<?php echo $self->session -> data['customer_id'] ?>" >
            <div class=""  >
               <div class="col-lg-12 col-md-12">
                  <form id="updateProfile" action="<?php echo $self -> url -> link('account/setting/update_profile', '', 'SSL'); ?>" method="POST" novalidate="novalidate">
                     <div class="">
                        <div class="">
                           <div class="control-group form-group">
                              <div class="controls">
                                 <label class="control-label" for="UserName">Tên đăng nhập</label>
                                 <div class="">
                                    <input class="form-control valid" id="UserName" name='username'  type="text" readonly='true' value="<?php echo $customer['username'] ?>" data-link="<?php echo $self -> url -> link('account/register/checkuser', '', 'SSL'); ?>" />
                                    <span id="UserName-error" class="field-validation-error">
                                    <span></span>
                                    </span>
                                 </div>
                              </div>
                           </div>
                           <div class="control-group form-group">
                              <div class="controls">
                                 <label class="control-label" for="Email">Địa chỉ email</label>
                                 <div class="">
                                    <input class="form-control" readonly='true' data-link="<?php echo $self -> url -> link('account/register/checkemail', '', 'SSL'); ?>" id="Email" name="email"  type="text" value="<?php echo $customer['email'] ?>"/>
                                    <span id="Email-error" class="field-validation-error">
                                    <span></span>
                                    </span>
                                 </div>
                              </div>
                           </div>
                           <div class="control-group form-group">
                              <div class="controls">
                                 <label class="control-label" for="Phone">Số điện thoại</label>
                                 <div class="">
                                    <input data-link="<?php echo $self -> url -> link('account/register/checkphone', '', 'SSL'); ?>" readonly='true' class="form-control" id="Phone" name="telephone" type="text" value="<?php echo $customer['telephone'] ?>"/>
                                    <span id="Phone-error" class="field-validation-error">
                                    <span></span>
                                    </span>
                                 </div>
                              </div>
                           </div>
                           <?php //print_r($customer) ?>
                           <div class="control-group form-group">
                              <div class="controls">
                                 <label class="control-label" for="Phone">Ngân hàng</label>
                                 <div class="">
                                    <input data-link="<?php echo $self -> url -> link('account/register/checkphone', '', 'SSL'); ?>" readonly='true' class="form-control" id="Phone" name="telephone" type="text" value="<?php echo $customer['bank_name'] ?>"/>
                                    <span id="Phone-error" class="field-validation-error">
                                    <span></span>
                                    </span>
                                 </div>
                              </div>
                           </div>

                           <div class="control-group form-group">
                              <div class="controls">
                                 <label class="control-label" for="Phone">Số tài khoản</label>
                                 <div class="">
                                    <input data-link="<?php echo $self -> url -> link('account/register/checkphone', '', 'SSL'); ?>" readonly='true' class="form-control" id="Phone" name="telephone" type="text" value="<?php echo $customer['account_number'] ?>"/>
                                    <span id="Phone-error" class="field-validation-error">
                                    <span></span>
                                    </span>
                                 </div>
                              </div>
                           </div>

                           <div class="control-group form-group">
                              <div class="controls">
                                 <label class="control-label" for="Phone">Tên chủ thẻ</label>
                                 <div class="">
                                    <input data-link="<?php echo $self -> url -> link('account/register/checkphone', '', 'SSL'); ?>" readonly='true' class="form-control" id="Phone" name="telephone" type="text" value="<?php echo $customer['account_holder'] ?>"/>
                                    <span id="Phone-error" class="field-validation-error">
                                    <span></span>
                                    </span>
                                 </div>
                              </div>
                           </div>

                           <div class="control-group form-group">
                              <div class="controls">
                                 <label class="control-label" for="Phone">Chi nhánh ngân hàng</label>
                                 <div class="">
                                    <input data-link="<?php echo $self -> url -> link('account/register/checkphone', '', 'SSL'); ?>" readonly='true' class="form-control" id="Phone" name="telephone" type="text" value="<?php echo $customer['branch_bank'] ?>"/>
                                    <span id="Phone-error" class="field-validation-error">
                                    <span></span>
                                    </span>
                                 </div>
                              </div>
                           </div>
                           <!-- <div class="">
                              <button type="submit" class="btn btn-primary">Cập nhập</button>
                           </div> -->
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <div class="tab-pane" id="ChangePassword">
            <form id="frmChangePassword" action="<?php echo $self -> url -> link('account/setting/editpasswd', '', 'SSL'); ?>"  method="post" novalidate="novalidate">
               <div class="col-lg-12">
                  <div class="">
                     <div class="">
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="control-label" for="OldPassword">Mật khẩu cũ</label>
                              <div class="">
                                 <input class="form-control" id="OldPassword" type="password" data-link="<?php echo $self -> url -> link('account/setting/checkpasswd', '', 'SSL'); ?>" />
                                 <span id="OldPassword-error" class="field-validation-error">
                                 <span></span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="control-label" for="Password">Mật khẩu mới</label>
                              <div class="">
                                 <input class="form-control" id="Password" name="password" type="password"/>
                                 <span id="Password-error" class="field-validation-error">
                                 <span></span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <label class="control-label" for="ConfirmPassword">Nhập lại mật khẩu mới</label>
                              <div class="">
                                 <input class="form-control" id="ConfirmPassword"  type="password"/>
                                 <span id="ConfirmPassword-error" class="field-validation-error">
                                 <span></span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="control-group form-group">
                           <div class="controls">
                              <button type="submit" class="btn btn-primary">Thay đổi mật khẩu</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   if (location.hash === '#success') {
      alertify.set('notifier','delay', 100000000);
      alertify.set('notifier','position', 'top-right');
      alertify.success('Update profile successfull !!!');
   }
   
</script>
<?php echo $self->load->controller('common/footer') ?>
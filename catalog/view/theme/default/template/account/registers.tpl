<?php echo $header;
 ?>
<style>
    .account-personal-add_customer {
        background: url('catalog/view/theme/default/images/blur.png');
        background-size: cover;
        background-repeat: no-repeat;
    }

    button[type="submit"] {
       box-shadow: none;
       border: none;
       background: url("catalog/view/theme/default/images/index3-16.png") center no-repeat;
       color: #fff;
       height: 34px;
       width: 190px;
       border-radius: 5px;
       line-height: 15px;
       font-weight: 700;
   }

   button[type="submit"]:hover{
      color: #fff;
   }

    body {
        color: #312d0d !important;
    }

    .panel-title{
         background: #fff;
    }

   #footer{
      position: inherit;
   }
   .border_{
      padding: 25px;
   }
   .btn{
      padding: 10px 30px;
   }
   input.form-control{
       border: 1px solid #eee;
       margin-top: 15px;
       box-shadow: none;
       height: 40px;
   }
   .panel-heading .panel-title {
      color: #404141;
      font-size: 20px;
      font-weight: 500;
      border-bottom: 1px solid #eee;
      height: 50px;
      line-height: 30px;
   }

   footer{
      float: left;
   }

   @media (max-width: 990px){
      .btn{
         margin-top: 15px;
      }
   }

    @media (max-width: 768px) {
        .panel.panel-default,
        .panel-default > .panel-heading {
            background: rgba(255, 255, 255, 0.74) !important;
        }

        .border_{
            padding: 5px;
        }
    }
    header,#footer{
      display: none;
    }
    .page-content{
      width: 80%;
      margin:0 auto;
    }
    body.top-bar-fixed .content{
      padding-top: 0px;
    }
</style>
<div class="page-content">
    <div class="content">
      <div class="row">
        <div class="text-center" style="width: 100%">
          <img src="catalog/view/theme/default/images/lo_go.png" style="margin: 0 auto" class="text-center">
        </div>
        <div class="col-md-12">
          <h2 class="page-title text-center">Đăng ký thành viên</h2>
        </div>
      </div>
   <div class="widget-content">
                <div class="panel-body">
                    <div class=" form">
                        <form id="register-account" action="<?php echo $self -> url -> link('account/registers/confirmSubmit', '', 'SSL'); ?>" class="form-horizontal" method="post" novalidate="novalidate">
                           <input type="hidden" name="p_node" value="<?php echo $customer_id ?>" />
                            <div class="row">
                                                <div class="col-md-6" style="display: none">
                                                   <select class="form-control" name="bank_name" id="bank_name">
                                                     <option value="" disabled>Chọn ngân hàng</option>
                                                     <option selected value="Vietcombank">Vietcombank</option>
                                                     <option value="Sacombank">Sacombank</option>
                                                     <option value="BIDV">BIDV</option>
                                                     <option value="Viettinbank">Viettinbank</option>
                                                     <option value="Agribank">Agribank</option>
                                                    
                                                   </select>              
                                                </div>
                                                <div class="col-md-6">
                                                   <input autocomplete="off"  class="form-control" name="username" id="username" value="" data-link="<?php echo $self -> url -> link('account/register/checkuser', '', 'SSL'); ?>" placeholder="ID hệ thống viết không dấu"/>               
                                                </div>
                                                <div class="col-md-6 conf-vcb">
                                                   <input data-url="<?php echo $self -> url -> link('account/register/getjson', '', 'SSL'); ?>" autocomplete="off" class="form-control" name="account_number" id="account_number" value="" data-link="<?php echo $self -> url -> link('account/register/checkcmnd', '', 'SSL'); ?>" placeholder="Số tài khoản ngân hàng VD:05010000xxxxx"/>
                                                   <span><i class=" fa fa-cog fa-spin fa-fw"></i></span>
                                                </div>

                                                
                                                <div class="col-md-6">
                                                   <input class="form-control" id="password" name="password" type="password" placeholder="Mật khẩu đăng nhập" />
                                                </div>
                                                <div class="col-md-6">
                                                   <input class="form-control" name="account_holder" id="account_holder" value="" readonly="true" placeholder="Tên đầy đủ không dấu như trên thẻ ATM"/>
                                                </div>
                                                <div class="col-md-6">
                                                   <input class="form-control valid" id="confirmpassword" type="password" placeholder="Nhập lại mật khẩu đăng nhập" />
                                                </div>



                                                 <div class="col-md-6">
                                                   <input  class="form-control" id="password2" name="password2" type="password" placeholder="Mật khẩu giao dịch" />
                                                </div>
                                                <div class="col-md-6">
                                                   <input class="form-control valid" id="confirmpasswordtransaction" type="password" placeholder="Nhập lại mật khẩu giao dịch" />
                                                </div>



                                                <div class="col-md-6">
                                                   <input autocomplete="off"  class="form-control" name="email" id="email" data-link="<?php echo $self -> url -> link('account/register/checkemail', '', 'SSL'); ?>" placeholder="Email đang sử dụng để nhận thông tin từ hệ thống" />
                                                </div>
                                                <div class="col-md-6">
                                                   <input autocomplete="off"  class="form-control" name="telephone" id="phone" data-link="<?php echo $self -> url -> link('account/register/checkphone', '', 'SSL'); ?>" placeholder="Số điện thoại đang sử dụng để nhận thông tin từ hệ thống" />
                                                </div>
                                                  <div class="col-md-6">
                                                   <input autocomplete="off"  class="form-control" name="cmnds" id="cmnds" placeholder="Số Chứng minh nhân dân" />
                                                </div>

                                                <div class="clearfix"></div>
                                                <div id="success"></div>
                                                <br/>
                                                <div class="col-md-12">
                                                   <button type="submit" class="btn-register btn btn-warning pull-right ">Submit</button>
                                                </div>
                                             </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12" >
                          <p>Lưu ý: hệ thống Iontach chỉ hỗ trợ tài khoản vietcombank.</p>
                          <p>Nhập đúng số tài khoản ngân hàng vietcombank, hệ thống sẽ tự lấy họ tên từ tài khoản của bạn.</p>
                          <p>Mỗi tài khoản vietcombank chỉ có thể đăng ký đươc 1 tài khoản.</p>
                          <br/>
                       </div>

                        <div class="col-md-12">

                           <p>Sao chép đường dẫn bên dưới để gửi cho bạn bè cùng tham gia hệ thống:</p>

                           <a style="word-break: break-word; font-weight:700; color:cyan" href="signup&ref=<?php echo $self -> request -> get['ref'];  ?>" target="_blank"><?php echo HTTPS_SERVER ?>signup&ref=<?php echo $self -> request -> get['ref'];  ?></a>
                        </div>
                    </div>
                    <!-- .form -->
                </div>
                <!-- panel-body -->
            </div>
            <!-- panel -->
        </div>
    </div>
</div>

<?php echo $footer; ?>

<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Sửa thông tin tài khoản</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title pull-left">Sửa thông tin tài khoản | <?php print_r($customer['username']);?></h3>
      
      <div class="clearfix">
          
      </div>
    </div>

    <div class="panel-body">
      <?php if (isset($_SESSION['complate'])){ ?>
        <div class="alert alert-success">
          <strong>Success!</strong> Cập nhật thông tin thành công.
        </div>
        <?php } ?>
      <form id="register-account" action="<?php echo $action_update; ?>" method="POST" role="form">
      <div class="col-md-6">
        
         <div class="form-group">
          <label for="">Username</label>
          <input type="text" value="<?php echo $customer['username'];?>" class="form-control"  name="username" id="username" placeholder="username" readonly>
            <span id="address-error" class="text-dangerr" style="display: none;">
           <span id="address-error">Please enter Address</span>
           </span>
        </div>
        <div class="form-group">
          <label for="">Họ và tên</label>
          <input type="text" class="form-control" value="<?php echo $customer['firstname'];?>" name="firstname" id="firstname" placeholder="Họ và tên" >
          <span id="firstname-error" class="text-danger" style="display:none;">
              <span id="firstname-error">Please enter Full Name</span>
          </span>
        </div>
        <div class="form-group">
          <label for="">Mật khẩu</label>
          <input type="text" class="form-control" name="password" id="address" placeholder="Mật khẩu">
          <span id="address-error" class="text-dangerr" style="display: none;">
           <span id="address-error">Please enter Address</span>
           </span>
        </div>
        <div class="form-group">
          <label for="">Email</label>
          <input type="text" value="<?php echo $customer['email'];?>" class="form-control" id="email" name="email" placeholder="Email" >
           <span id="email-error" class="text-danger" style="display: none;">
           <span id="Email-error">Please enter Email Address</span>
           </span>
        </div>
        <div class="form-group">
          <label for="">Tên tài khoản</label>
          <input type="text" class="form-control" value="<?php echo $customer['account_holder'];?>" name="account_holder" id="Accountholders" placeholder="Tên tài khoản" >
          <span id="Accountholders-error" class="text-danger">
            <span></span>
          </span>
        </div>
        <div class="form-group">
          <label for="">Số tài khoản</label>
          <input type="text" value="<?php echo $customer['account_number'];?>" class="form-control" id="Accountnumber" name="account_number" placeholder="Số tài khoản" >
          <span id="Accountnumber-error" class="field-validation-error">
              <span></span>
          </span>
        </div>
        
       
      </div>
      <div class="col-md-6">
       
         <div class="form-group">
          <label for="">Địa chỉ</label>
          <input type="text"  value="<?php echo $customer['address_cmnd'];?>" class="form-control" name="address_cmnd" id="address" placeholder="Địa chỉ">
          <span id="address-error" class="text-dangerr" style="display: none;">
           <span id="address-error">Please enter Address</span>
           </span>
        </div>
        <div class="form-group">
          <label for="">Gói đầu tư</label>
          <input type="text" value="<?php echo number_format($seft->get_pakege_cha($_GET['customer_id']));?> PV" class="form-control" id="pakege" name="pakege" placeholder="Chi nhánh" readonly="readonly">
          <span id="Branchbank-error" class="field-validation-error">
              <span></span>
          </span>
        </div>
        <div class="form-group">
          <label for="">Số điện thoại</label>
          <input type="text" value="<?php echo $customer['telephone'];?>" class="form-control" name="telephone" id="phone" placeholder="Số điện thoại" >
           <span id="phone-error" class="text-danger" style="display: none;">
           <span>Please enter Phone Number</span>
           </span>
        </div>
        <div class="form-group">
          <label for="">Số CMND</label>
          <input type="text" value="<?php echo $customer['cmnd'];?>" class="form-control" id="cmnd" name="cmnd" placeholder="Số CMND" >
          <span id="cmnd-error" class="text-danger" style="display: none;">
           <span id="CardId-error">The Citizenship card/passport no field is required.</span>
           </span>
        </div>

        <div class="form-group">
          <label for="">Ngân hàng</label>
          <input type="text" value="<?php echo $customer['bank_name'];?>" class="form-control" id="Bankname" name="bank_name" placeholder="Ngân hàng" >
          <span id="Bankname-error" class="field-validation-error">
              <span></span>
          </span>
        </div>
        <div class="form-group">
          <label for="">Chi nhánh</label>
          <input type="text" value="<?php echo $customer['branch_bank'];?>" class="form-control" id="Branchbank" name="branch_bank" placeholder="Chi nhánh" >
          <span id="Branchbank-error" class="field-validation-error">
              <span></span>
          </span>
        </div>
        
      </div>

      <div class="clearfix"></div>
        
      </div>
      <div class="col-md-6 col-md-offset-5">
         <div class="form-group">
            <button type="submit" onclick="return confirm('Bạn có chắc chắn không?')" class="btn btn-primary">Cập nhật thông tin</button>
            <!-- <a target="_blank" href="index.php?route=pd/printuser&id=<?php echo $_GET['customer_id']; ?>&token=<?php echo $_GET['token']; ?>" class="btn btn-success">In Hợp Đồng</a> -->
          </div>
      </div>
      </form> 

    </div>
  </div>
</div>

<?php echo $footer; ?>
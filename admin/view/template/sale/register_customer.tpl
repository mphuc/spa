<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-custom-field" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Đăng ký thành viên</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <form id="register-account" action="<?php echo $url_register; ?>" class="form-horizontal" method="post" novalidate="novalidate">
   <div class="form-group ">
     <div class="col-sm-5">
        <label class=" control-label" for="input-email">Họ và tên</label>
        <input type="text" name="full_name" value="" placeholder="Họ và tên" id="" class="form-control">
     </div>
     <div class="col-sm-1"></div>
     <div class="col-sm-5">
        <label class=" control-label" for="input-telephone">Tên thẻ ATM</label>
        <input type="text" name="bank_name_account" value="" placeholder="Tên thẻ ATM" id="input-telephone" class="form-control">
     </div>
  </div>
  <div class="form-group ">
     <div class="col-sm-5">
        <label class=" control-label" for="input-email">Tên đăng nhập</label>
        <input type="text" name="username" value="" placeholder="Tên đăng nhập" id="input-email" class="form-control">
     </div>
     <div class="col-sm-1"></div>
     <div class="col-sm-5">
        <label class=" control-label" for="input-email">Ngân hàng</label>
        <input type="text" name="bank_name" value="" placeholder="Ngân hàng" id="input-email" class="form-control">
     </div>
     
     
  </div>

  <div class="form-group ">
     <div class="col-sm-5">
        <label class=" control-label" for="input-email">Số tài khoản</label>
        <input type="text" name="account_number" value="" placeholder="Số tài khoản" id="input-email" class="form-control">
     </div>
     <div class="col-sm-1"></div>
     <div class="col-sm-5">
        <label class=" control-label" for="input-email">Chi nhánh</label>
        <input type="text" name="bank_name_bank" value="" placeholder="Chi nhánh" id="input-email" class="form-control">
     </div>
     
     
  </div>
  <div class="form-group ">

    <div class="col-sm-5">
        <label class=" control-label" for="input-telephone">Nhập lại mật khẩu</label>
        <input type="password" name="telephone" name="nl_password" value="" placeholder="Nhập lại mật khẩu" id="input-telephone" class="form-control">
     </div>
     
     <div class="col-sm-1"></div>
     <div class="col-sm-5">
        <label class=" control-label" for="input-telephone">Email</label>
        <input type="text" name="email" value="" placeholder="Email" id="input-telephone" class="form-control">
     </div>
  </div>
  <div class="form-group ">

    <div class="col-sm-5">
        <label class=" control-label" for="input-telephone">Mật khẩu</label>
        <input type="text" name="password" value="" placeholder="Mật khẩu" id="input-telephone" class="form-control">
     </div>
     <div class="col-sm-1"></div>
     <div class="col-sm-5">
        <label class=" control-label" for="input-telephone">Số điện thoại</label>
        <input type="text" name="telephone" value="" placeholder="Email" id="input-telephone" class="form-control">
     </div>
     <div class="col-sm-5">
        <label class=" control-label" for="input-telephone">Địa chỉ</label>
        <input type="text" name="address" value="" placeholder="Mật khẩu" id="input-telephone" class="form-control">
     </div>
     <div class="col-sm-1"></div>
     <div class="col-sm-5">
        <label class=" control-label" name="goidautu" for="input-telephone">Gói đầu tư</label>
        <select name="" class="form-control">
          <option value="5000000"><?php echo number_format(5000000);?></option>
          <option value="5000000"><?php echo number_format(20000000);?></option>
          <option value="5000000"><?php echo number_format(50000000);?></option>
          <option value="5000000"><?php echo number_format(10000000);?></option>
          <option value="5000000"><?php echo number_format(50000000);?></option>
        </select>
     </div>
  </div>
   <div class="form-group ">

    <div class="col-sm-5">
        <label class=" control-label"  for="input-telephone">Bảo trợ</label>
        <input type="text" name="p_node"  placeholder="Bảo trợ" class="form-control">
     </div>
     
     <div class="col-sm-1"></div>
     <div class="col-sm-5">
        <label class=" control-label" for="input-telephone">Nhánh</label>
        <input type="text" name="p_binary"  value="" placeholder="Nhánh" id="input-telephone" class="form-control">
     </div>
  </div>
   <div class="form-group ">

    <div class="col-sm-5">
        <label class=" control-label"  for="input-telephone">Vị trí</label>
        <select name="position" class="form-control">
          <option value="left">Bên Trái</option>
          <option value="right">Bên Phair</option>
        </select> 
     </div>
     
     <div class="col-sm-1"></div>
    <!--  <div class="col-sm-5">
        <label class=" control-label" for="input-telephone">Nhánh</label>
        <input type="text" name="p_binary"  value="" placeholder="Nhánh" id="input-telephone" class="form-control">
     </div> -->
  </div>
  <input type="submit" name="" value="submit">
  </form>


	</div>
<?php echo $footer; ?>
<?php

   $self->document->setTitle("Trang chủ");
   echo $self->load->controller('common/header'); //echo $self->load->controller('common/column_left');?>

<div class="container dashboard_home">
<div class="fw-main-row">
 <div class="fw-col-inner">
  <div class="notifi">
      <header class="fw-heading section-header heading-min-spacing subtitle-bottom text-left fw-heading-h3  wow fadeIn animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeIn;">
         <h3 class="fw-special-title">Thông báo Art-naturalcare.com<span class="bottom-subtitle-spacing"></span>     <small>Chương trình hỗ trợ và tri chân khách hàng bắt đầu từ ngày 05/04/2017</small>
         </h3>
      </header>
      <p>Để hỗ trợ và khác hàng đã ưu ái chọn cửa hàng chúng tôi, chúng tôi xin cám ơn các bạn đã đặt lòng tin vào Spa chúng tôi. Để đáp lại lòng tin đó, chúng tôi sẽ tri ân khách hàng bằng các <a href="package.html"><span class="label label-default"> gói ưu đãi khác</span></a> nhau

      <p>Từ các gói tri ân này khách hàng có thể được hưởng các loại hoa hồng như: hoa hồng trực tiếp, hoa hồng mã rơi, thưởng nóng của Spa. Ngoài ra các gói 100.00.000 VNĐ trở lên sẽ có cơ hội dùng Mỷ phẩm, Được giảm giá lên đến 15%, có cơ hội trở thành khách Vip của span. Ngoài ra còn hưởng lợi nhuận khuyến mãi của spa và đặc biệt là được hưởng lợi nhuận của Span lên đến 7%.</p>
      <p>Cám ơn quý khách hàng đã sử dụng dịch vụ của chúng tôi!</p>
      <p><i>Art-naturalcare.com</i></p>
  </p>
 </div>
</div>
</div>

<div class="fw-main-row" style="margin-top: 15px;">
 <div class="fw-col-inner">
  <div class="notifi">
      <header class="fw-heading section-header heading-min-spacing subtitle-bottom text-left fw-heading-h3  wow fadeIn animated" data-wow-offset="120" data-wow-duration="1.5s" style="visibility: visible; animation-duration: 1.5s; animation-name: fadeIn;">
         <h3 class="fw-special-title">Liên kết giới thiệu bạn bè<span class="bottom-subtitle-spacing"></span>     <small><?php echo HTTPS_SERVER ?>register.html&ref=<?php echo $customer['customer_code'] ?></small>
         </h3>
      </header>
      
  </p>
 </div>
</div>
</div>


  <div class="clearfix" style="margin-top: 50px;"></div>
  <div class="row">
    <h3 class="text-center" style="color: #957216">Các loại thưởng và hoa hồng đã nhận</h3>
     <div class="col-md-4">
        <div class="panel-body wallet_home">
           <span class="lead-stats" href="">
              <span class="stats-number dolar c-wallet">
              <?php echo ($get_package/1000) ?> ĐT</span>
              <span class="stats-icon">
              <i class="fa fa-money color-green"></i>
              </span>
              <h5>Gói tri ân</h5>
           </span>
        </div>
     </div>

     <div class="col-md-4">
        <div class="panel-body wallet_home">
           <span class="lead-stats" href="">
              <span class="stats-number dolar c-wallet">
              <?php echo (($get_r_bk)/1000) ?> ĐT</span>
              <span class="stats-icon">
              <i class="fa fa-money color-green"></i>
              </span>
              <h5>Hoa hồng trực tiếp</h5>
           </span>
        </div>
     </div>

     <div class="col-md-4">
        <div class="panel-body wallet_home">
           <span class="lead-stats" href="">
              <span class="stats-number dolar c-wallet">
              <?php echo (($get_ch_bk)/1000) ?> ĐT</span>
              <span class="stats-icon">
              <i class="fa fa-money color-green"></i>
              </span>
              <h5>Hoa hồng cộng hưởng</h5>
           </span>
        </div>
     </div> 

     <div class="col-md-4">
        <div class="panel-body wallet_home">
           <span class="lead-stats" href="">
              <span class="stats-number dolar c-wallet">
              <?php echo (($get_km_bk)/1000) ?> ĐT</span>
              <span class="stats-icon">
              <i class="fa fa-money color-green"></i>
              </span>
              <h5>Đồng chia tổng doanh số</h5>
           </span>
        </div>
     </div>

     <div class="col-md-4">
        <div class="panel-body wallet_home">
           <span class="lead-stats" href="">
              <span class="stats-number dolar c-wallet">
             <?php echo (($get_ln_bk)/1000) ?> ĐT</span>
              <span class="stats-icon">
              <i class="fa fa-money color-green"></i>
              </span>
              <h5>Thưởng lợi nhuận của spa</h5>
           </span>
        </div>
     </div>

     <div class="col-md-4">
        <div class="panel-body wallet_home">
           <span class="lead-stats" href="">
              <span class="stats-number dolar c-wallet">
              <?php echo (($sum_total_pd_all)/1000) ?> ĐT</span>
              <span class="stats-icon">
              <i class="fa fa-money color-green"></i>
              </span>
              <h5>Tổng số ĐT</h5>
           </span>
        </div>
     </div> 

     <div class="clearfix" style="margin-top: 20px;"></div>
     <?php if (count($getallcommision_system) > 0) { ?>
       
     

     <h3 class="text-center" style="color: #957216">Lịch sử mới nhất của tri ân</h3>
     <!-- <marquee direction="up" scrollamount="3" onmouseover="this.stop()" onmouseout="this.start()" style="text-align:center;height:420px;"> -->
     <table class="table col-md-12">
        <thead>
          <tr>
            <th>Thứ tự</th>
            <th>Tên đăng nhập</th>
            <th>Loại hoa hồng</th>
            <th>Số ĐT</th>
            <th>Thời gian</th>
          </tr>
        </thead>

        <tbody>
          
          <?php $i=0; foreach ($getallcommision_system as $value) { $i++;
            ?>
          <tr>
            <td data-title="Thứ tự"><?php echo $i ?></td>
            <td data-title="Tên đăng nhập"><?php echo $value['username'] ?></td>
            <td data-title="Loại hoa hồng được nhận"><?php echo $value['wallet'] ?></td>
            <td data-title="Số tiền nhận"><?php echo $value['text_amount'] ?></td>
            <td data-title="Thời gian nhận"><?php echo date('d/m/Y H:i',strtotime($value['date_added']))  ?></td>
          </tr>
          <?php } ?>
          
        </tbody>
      </table>
      <!-- </marquee> -->
      <?php } ?>
</div>
</div>

   
<!-- <script type="text/javascript">
   if (location.hash === '#success') {
      alertify.set('notifier','delay', 100000000);
      alertify.set('notifier','position', 'top-right');
      alertify.success('Create user successfull !!!');
   }
   if (location.hash === '#createPD') {
      alertify.set('notifier','delay', 100000000);
      alertify.set('notifier','position', 'top-right');
      alertify.success('Create PD successfull !!!');
   }
</script>   -->
<?php echo $self->load->controller('common/footer') ?>

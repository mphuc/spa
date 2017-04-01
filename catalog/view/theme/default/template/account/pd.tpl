<?php
   $self -> document -> setTitle("Các gói tri ân khách hàng");
   echo $self -> load -> controller('common/header');
   //echo $self -> load -> controller('common/column_left');
   ?>
<div class="container">
  
  <?php if (count($pds) > 0) { ?>
  <h3 class="text-center" style="color: #7d3c93">Gói đã tham gia</h3>
  <div class="clearfix"></div>
  <div class="pricing-table customer_active">
    <div class="pricing-table-offer fw-col-md-4 col-md-push-4 default-col ">
        <div class="offer-title">
            <small></small>
        </div>
        <div class="offer-price">
           <span class="currency"></span>
           <?php switch (doubleval($pds['filled'])) {
            case 3000000:
              $type = 'Silver ';
              $tructiep = "6%";
              $conghuong = "10%";
              break;
            
            case 6000000:
              $type = 'Gold';
              $tructiep = "8%";
              $conghuong = "15%";
              break;
            case 9000000:
              $type = 'Platinium';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
            case 100000000:
              $type = 'Emerald';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
            case 200000000:
              $type = 'Diamond';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
            case 500000000:
              $type = 'Double<br/>Diamond';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
            case 1450000000:
              $type = 'Blue<br/>Diamond';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
          } ?>
           <span class="price"><?php echo $type ?></span>
           <span class="duration"></span>
        </div>
        
      
        <div class="fw-default-row">
           Số tiền tham gia: <?php echo number_format($pds['dt']/1000) ?> TĐ                        
        </div>
        <div class="fw-default-row">
           Trực tiếp: <?php echo $tructiep ?>                      
        </div>
        <div class="fw-default-row">
           Cộng hưởng: <?php echo $conghuong ?>                       
        </div>
        
        <div class="fw-default-row">
           Ngày tham gia: <?php echo date('d/m/Y H:i',strtotime($pds['date_added'])) ?>
        </div>
        <?php if ($pds['filled'] == 100000000) { ?>
          <div class="fw-default-row">
            Đồng chia tổng doanh số: <span class="countdown" data-countdown="<?php echo  $pds['date_finish_ds100'] ?>"></span>
          </div>
        <?php } ?>

        <?php if ($pds['filled'] == 200000000) { ?>
          <div class="fw-default-row">
            Đồng chia tổng doanh số: <span class="countdown" data-countdown="<?php echo  $pds['date_finish_ds200'] ?>"></span>
          </div>
        <?php } ?>

        <?php if ($pds['filled'] == 500000000) { ?>
          <div class="fw-default-row">
            Đồng chia tổng doanh số: <span class="countdown" data-countdown="<?php echo  $pds['date_finish_ds500'] ?>"></span>
          </div>
        <?php } ?>

        <?php if ($pds['filled'] == 1450000000) { ?>
          <div class="fw-default-row">
            Đồng chia tổng doanh số: <span class="countdown" data-countdown="<?php echo  $pds['date_finish_ds1450'] ?>"></span>
          </div>
        <?php } ?>

        <?php if ($pds['filled'] >= 100000000) { ?>
          <div class="fw-default-row">
            Lợi nhuận chuỗi spa: <span class="countdown" data-countdown="<?php echo  $pds['date_finish_ln'] ?>"></span>
          </div>
        <?php } ?>

     </div>
    </div>
    <?php } ?>
    <div class="clearfix"></div>

  <h3 class="text-center" style="color: #7d3c93">Các gói tri ân khách hàng</h3>
  <div class="fw-row">
                     <div id="column-58ce521cdb722" class=" fw-col-md-12       wow fadeIn" data-wow-offset="100" data-wow-duration="1.5s" style="  ">
                        <div id="column-58ce521cdb722" class=" fw-col-md-12       wow fadeIn" data-wow-offset="100" data-wow-duration="1.5s" style="  ">
                        <div class="fw-main-row">
                           <div class="fw-col-inner" >
                              <div class="pricing-table">
                                 <div class="pricing-table-offer fw-col-md-4 default-col ">
                                    <div class="offer-title">
                                       Silver <small></small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">Silver</span>
                                       <span class="duration"></span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 3.000.000 VNĐ                        
                                    </div>
                                    <div class="fw-default-row">
                                       Trực tiếp: 6%                      
                                    </div>
                                    <div class="fw-default-row">
                                       Cộng hưởng: 10%                        
                                    </div>
                                    <div class="fw-default-row">
                                       Hoa hồng trên thu nhập F1: 30%                    
                                    </div>
                                    <div class="fw-default-row">
                                              <br>                
                                    </div>
                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>
                                 <div class="pricing-table-offer fw-col-md-4 default-col ">
                                    <div class="offer-title">
                                       Gold <small></small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">Gold</span>
                                       <span class="duration"></span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 6.000.000 VNĐ                        
                                    </div>
                                    <div class="fw-default-row">
                                       Trực tiếp: 8%                      
                                    </div>
                                    <div class="fw-default-row">
                                       Cộng hưởng: 15%                        
                                    </div>
                                    <div class="fw-default-row">
                                       Hoa hồng trên thu nhập F1: 40%                  
                                    </div>
                                    <div class="fw-default-row">
                                               <br>               
                                    </div>
                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>
                                 <div class="pricing-table-offer fw-col-md-4 default-col ">
                                    <div class="offer-title">
                                       Platinium <small></small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">Platinium</span>
                                       <span class="duration"></span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 9.000.000 VNĐ                        
                                    </div>
                                    <div class="fw-default-row">
                                       Trực tiếp: 10%                      
                                    </div>
                                    <div class="fw-default-row">
                                       Cộng hưởng: 20%                        
                                    </div>
                                    <div class="fw-default-row">
                                       Hoa hồng trên thu nhập F1: 50%                        
                                    </div>
                                    <div class="fw-default-row">
                                       Hưởng doanh số chương trình khuyến mãi:1%                   
                                    </div>

                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>
                                 
                                 <div class="pricing-table-offer fw-col-md-4 highlight-col  ">
                                    <div class="offer-title">
                                       Emerald</small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">Emerald</span>
                                       <span class="duration"></span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 100.000.000 VNĐ                         
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 100.000.000 VNĐ / mỷ phẩm                      
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 01 thẻ Emerald có giá trị 100.000.000 VNĐ                      
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận đồng chia tổng doanh số 2% trong 12 tháng                   
                                    </div>
                                    <div class="fw-default-row">
                                      Chia lợi nhuận chuỗi spa: 5% / 12 tháng
                                    </div>
                                    <div class="fw-default-row">
                                      Công ty có chương trình hỗ trợ đầu ra Sản phẩm từ 3 đến
8% số lượng hàng tồn mỗi tháng cho các thành viên (tuỳ
tình hình thị trường).
                                    </div>
                                    
                                    <div class="fw-default-row">
                                      Được hưởng quyền lợi tương đương với gói Platinium    
                                    </div>
                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>

                                 <div class="pricing-table-offer fw-col-md-4 highlight-col  ">
                                    <div class="offer-title">
                                       Diamond</small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">Diamond</span>
                                       <span class="duration"></span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 200.000.000 VNĐ                         
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 200.000.000 VNĐ / mỷ phẩm             
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 01 thẻ Diamond có giá trị 200.000.000 VNĐ                 
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận đồng chia 2% trong 12 tháng + 1% trong 15 tháng                    
                                    </div>
                                    <div class="fw-default-row">
                                      Thưởng lợi nhuận của spa: 6% / 15 tháng
                                    </div>
                                    <div class="fw-default-row">
                                       Công ty có chương trình hỗ trợ đầu ra Sản phẩm từ 3 đến
8% số lượng hàng tồn mỗi tháng cho các thành viên (tuỳ
tình hình thị trường). 
                                    </div>
                                    
                                    <div class="fw-default-row">
                                      Được hưởng quyền lợi tương đương với gói Platinium   
                                    </div>
                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>

                                 <div class="pricing-table-offer fw-col-md-4 highlight-col  ">
                                    <div class="offer-title">
                                       Double Diamond</small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price" style="margin-top: -10px; float: left; margin-left: 29px;">Double <br>Diamond</span>
                                       <span class="duration"></span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 500.000.000 VNĐ                         
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 500.000.000 VNĐ / mỷ phẩm                 
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 01 thẻ Double Diamond có giá trị 500.000.000 VNĐ           
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận đồng chia 2% trong 12 tháng + 1% trong 15 tháng +
1% trong 18 tháng                        
                                    </div>
                                    <div class="fw-default-row">
                                       Thưởng lợi nhuận của spa: 7% / 18 tháng
                                    </div>
                                    <div class="fw-default-row">
                                       Công ty có chương trình hỗ trợ đầu ra Sản phẩm từ 3
đến 8% số lượng hàng tồn mỗi tháng cho các thành
viên (tuỳ tình hình thị trường).        
                                    </div>
                                   
                                    <div class="fw-default-row">
                                     Được hưởng quyền lợi tương đương với gói Platinium     
                                    </div>
                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>

                                 <div class="pricing-table-offer fw-col-md-12 highlight-col  ">
                                    <div class="offer-title">
                                       Blue Diamond</small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span style="margin-top: -10px; float: left; margin-left: 29px;" class="price">Blue <br>Diamond</span>
                                       <span class="duration"></span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 1.000.000.000 VNĐ                         
                                    </div>
                                    
                                    <div class="fw-default-row">
                                       Được sở hữu 01 Spa hoàn chỉnh do công ty hỗ trợ hoàn
thiện thiết kế, máy móc, đào tạo nhân viên theo chuẩn (mặt
bằng đối tác tự lựa chọn).                      
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 01 thẻ Blue Diamond trị giá 1.450.000.000 VNĐ               
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận đồng chia 2% trong 12 tháng + 1% trong 15 tháng +
1% trong 18 tháng và 1% trong 24 tháng.            
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 5% số điểm thưởng khi có hợp đồng phát sinh tại
đây       
                                    </div>
                                    
                                    <div class="fw-default-row">
                                      Được hưởng quyền lợi tương đương với gói Platinium   
                                    </div>
                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>

                                 <div class="clearfix"></div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

</div>
<div class="clearfix">
<?php for ($i=1; $i <=10; $i++) {  ?>
  <img src="catalog/view/theme/default/images/slide/000<?php echo $i;?>.jpg">
<?php } ?>
</div>
<?php echo $self->load->controller('common/footer') ?>

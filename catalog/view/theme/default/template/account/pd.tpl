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
           VIP 1 <small></small>
        </div>
        <div class="offer-price">
           <span class="currency"></span>
           <?php switch (doubleval($pds['filled'])) {
            case 100000:
              $type = '100 ';
              $tructiep = "6%";
              $conghuong = "10%";
              break;
            
            case 200000:
              $type = '200';
              $tructiep = "8%";
              $conghuong = "15%";
              break;
            case 300000:
              $type = '300';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
            case 3333000:
              $type = '3333';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
            case 6666000:
              $type = '6666';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
            case 16666000:
              $type = '16666';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
            case 24166000:
              $type = '24166';
              $tructiep = "10%";
              $conghuong = "20%";
              break;
          } ?>
           <span class="price"><?php echo $type ?></span>
           <span class="duration">PV</span>
        </div>
        
      
        <div class="fw-default-row">
           Số tiền tham gia: <?php echo number_format($pds['filled']/1000) ?> PV                        
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
        <?php if ($pds['filled'] >= 3333000) { ?>
          <div class="fw-default-row">
            Đồng chia tổng doanh số: <span class="countdown" data-countdown="<?php echo  $pds['date_finish_ds'] ?>"></span>
          </div>
        <?php } ?>
        <?php if ($pds['filled'] >= 300000) { ?>
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
                                       VIP 1 <small></small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">100</span>
                                       <span class="duration">PV</span>
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
                                       VIP 2 <small></small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">200</span>
                                       <span class="duration">PV</span>
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
                                       VIP 3 <small></small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">300</span>
                                       <span class="duration">PV</span>
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
                                       Ruby</small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">3333</span>
                                       <span class="duration">PV</span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 100.000.000 VNĐ                         
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 100.000.000 VNĐ / mỷ phẩm Hàn Quốc                       
                                    </div>
                                    <div class="fw-default-row">
                                       Sử dụng dịch vụ spa giảm: 5%                       
                                    </div>
                                    <div class="fw-default-row">
                                       Trở thành khách VIP spa                        
                                    </div>
                                    <div class="fw-default-row">
                                       Doanh số chương trình khuyến mãi: 2% (6 tháng)
                                    </div>
                                    <div class="fw-default-row">
                                       Chia lợi nhuận chuỗi spa: 5% / 12 tháng              
                                    </div>
                                    <div class="fw-default-row">
                                       Cấp thẻ Voucher              
                                    </div>
                                    <div class="fw-default-row">
                                      Được hưởng quyền lợi như gói 9.000.000 VNĐ             
                                    </div>
                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>

                                 <div class="pricing-table-offer fw-col-md-4 highlight-col  ">
                                    <div class="offer-title">
                                       Saphie</small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">6666</span>
                                       <span class="duration">PV</span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 200.000.000 VNĐ                         
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 200.000.000 VNĐ / mỷ phẩm Hàn Quốc                       
                                    </div>
                                    <div class="fw-default-row">
                                       Sử dụng dịch vụ spa giảm: 6%                       
                                    </div>
                                    <div class="fw-default-row">
                                       Trở thành khách VIP spa                        
                                    </div>
                                    <div class="fw-default-row">
                                       Doanh số chương trình khuyến mãi: 3% (9 tháng)
                                    </div>
                                    <div class="fw-default-row">
                                       Chia lợi nhuận chuỗi spa: 5% / 12 tháng              
                                    </div>
                                    <div class="fw-default-row">
                                       Cấp thẻ Voucher              
                                    </div>
                                    <div class="fw-default-row">
                                      Được hưởng quyền lợi như gói 9.000.000 VNĐ             
                                    </div>
                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>

                                 <div class="pricing-table-offer fw-col-md-4 highlight-col  ">
                                    <div class="offer-title">
                                       Emaro</small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">16666</span>
                                       <span class="duration">PV</span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 500.000.000 VNĐ                         
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 500.000.000 VNĐ / mỷ phẩm Hàn Quốc                       
                                    </div>
                                    <div class="fw-default-row">
                                       Sử dụng dịch vụ spa giảm: 15%                       
                                    </div>
                                    <div class="fw-default-row">
                                       Trở thành khách VIP spa                        
                                    </div>
                                    <div class="fw-default-row">
                                       Doanh số chương trình khuyến mãi: 4% (12 tháng)
                                    </div>
                                    <div class="fw-default-row">
                                       Chia lợi nhuận chuỗi spa: 7% / 12 tháng              
                                    </div>
                                    <div class="fw-default-row">
                                       Cấp thẻ Voucher              
                                    </div>
                                    <div class="fw-default-row">
                                      Được hưởng quyền lợi như gói 9.000.000 VNĐ             
                                    </div>
                                    <div class="offer-action">
                                       <a href="login.html">
                                          <button class="btn btn-lg show-appointment-modal" data-service="">Liên hệ Spa</button>
                                       </a>
                                    </div>
                                 </div>

                                 <div class="pricing-table-offer fw-col-md-12 highlight-col  ">
                                    <div class="offer-title">
                                       Diamond</small>
                                    </div>
                                    <div class="offer-price">
                                       <span class="currency"></span>
                                       <span class="price">24166</span>
                                       <span class="duration">PV</span>
                                    </div>
                                    <div class="fw-default-row">
                                       Số tiền tham gia: 1.000.000.000 VNĐ                         
                                    </div>
                                    
                                    <div class="fw-default-row">
                                       Nhượng quyền thương hiệu Spa                       
                                    </div>
                                    <div class="fw-default-row">
                                       Bàn giao spa                       
                                    </div>
                                    <div class="fw-default-row">
                                       Nhận 10%/hợp đồng mời vào spa                        
                                    </div>
                                    <div class="fw-default-row">
                                       Hưởng dịch vụ spa giảm: từ 30% -> 50%              
                                    </div>
                                    <div class="fw-default-row">
                                       Doanh số chương trình khuyến mãi: 5% doanh số              
                                    </div>
                                    <div class="fw-default-row">
                                       Công ty mẹ đến đào tạo theo chuẩn spa              
                                    </div>
                                    <div class="fw-default-row">
                                       Thưởng lợi nhuận của spa: 10% / 36 tháng             
                                    </div>
                                    <div class="fw-default-row">
                                       Cấp thẻ Voucher              
                                    </div>
                                    <div class="fw-default-row">
                                      Được hưởng quyền lợi như gói 9.000.000 VNĐ             
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

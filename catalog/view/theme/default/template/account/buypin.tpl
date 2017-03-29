<?php 
   $self -> document -> setTitle($lang['text_buy']); 
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
                 <h4 class="panel-title"><i class="fa fa-align-justify"></i>Mua PIN</h4>
              </div>
              <div class="panel-body form-horizontal group-border stripped">
                 <div class="form-group">
                    <div class="col-lg-12 col-md-12">
                      <div class="input-group input-icon file-upload">
                       <div class="widget-content">
                           <div class="row">
                              <div class="col-md-12">
                                 
                                 <div class="options pull-right">
                                    <div class="btn-toolbar">
                                       <a href="<?php echo $self->url->link('account/token', '', 'SSL'); ?>" class="btn btn-warning"><i class="fa fa-fw fa-undo"></i><?php echo $lang['text_cancel'] ?></a>
                                    </div>
                                 </div>
                                 <div class="clearfix"></div>
                                 <div class="panel-custom">
                                    <!-- 100000000 -->
                                    <form id="frmBuyPin" action="<?php echo $self -> url -> link('account/token/buySubmit', '', 'SSL') ?>" method="POST">
                                       <div class="col-md-4">
                                          <h3><?php echo $lang['text_package_amount'] ?></h3>
                                          <br/>
                                          <div class="form-group">
                                             <!-- <h4>PIN Package:</h4> -->
                                             <div style="font-size:15px; margin-left: 15px;"><i class="fa fa-check-square-o"></i> 1 PIN = $7</div>
                                          </div>
                                          <input type="text" name="pin_price" id="pin_price" class="form-control" value="" placeholder="0">
                                          <span id="pin_price-error" class="field-validation-error" style="display: none;">
                                          <span></span>
                                          </span>
                                          <br/>
                                          <button style="margin-top:20px;" id="btnBuy" type="submit" autocomplete="off" class="btn btn-primary" data-toggle="modal" data-target="#ModalPin"><?php echo $lang['text_ok_buy'] ?></button>
                                       </div>
                                    </form>
                                    <div class="col-md-8">
                                       <h4 style="color:#bfd507"></h4>
                                    </div>
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                            
                           </div>
                             <?php if ($invoice) { ?>
                              <div class="row" id="order-pin-history">
                                 <div class="col-lg-12">
                                    <div class="pd-panel" style="padding-bottom:20px;">
                     <div class="col-md-12">
                       <h3>Lịch sử mua PIN</h3>
                     </div>
                     <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
                                        <table id="" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                   <th class="text-center">STT</th>
                                                          <th>Ngày Tạo</th>
                                                          <th>Pin</th>
                                                          <th>Số BTC</th>
                                                          <th>Đã Nhận (BTC)</th>
                                                          <th>Trạng Thái</th>
                                                          <th>Ví</th>
                                                          <th>QR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                         
                     <?php for ($i=0; $i < count($invoice); $i++) { ?> 
      
                                                            <tr>
                                                               <td data-title="STT" align="center"><?php echo $i+1 ?></td>
                                                                <td data-title="Ngày Tạo"><?php echo date("Y-m-d H:i:A", strtotime($invoice[$i]['date_created'])); ?></td>
                                                                <td data-title="Pin"><?php echo $invoice[$i]['pin'] ?></td>
                                                                <td data-title="Số BTC"><?php echo (intval($invoice[$i]['amount']) / 100000000) ?></td>
                                                                <td data-title="Đã Nhận (BTC)">
                                                                    <?php echo (intval($invoice[$i]['received']) / 100000000) ?>
                                                                </td>
                                                                <td data-title="Trạng Thái">
                                                                   <span class="label <?php echo intval($invoice[$i]['confirmations']) === 0 ? "label-warning" : 'label-success' ?>"><?php echo intval($invoice[$i]['confirmations']) === 0 ? "Pending" : 'Finish' ?></span>
                                                                </td>
                                                                <td data-title="Ví">
                                                                    <?php echo $invoice[$i]['input_address'] ?>
                                                                </td>
                                                                <td><img style="width: 80px;" src="https://chart.googleapis.com/chart?chs=150x150&amp;chld=L|1&amp;cht=qr&amp;chl=bitcoin:<?php echo $invoice[$i]['input_address'] ?>?amount=<?php echo (intval($invoice[$i]['amount']) / 100000000) ?>"/></td>
                                                            </tr>
                                                       
   
                   
                     <?php } ?>
                        </tbody>
                                        </table>
                                 
                                    </div>
                     <div class="clearfix"></div>
                     <?php if($pagination){ ?>
                     <div class="panel-body panel-custom panel-pd" style="padding-bottom:0px; margin-top:5px;">
                        <div class="row" style="margin:15px 0px">
                           <?php echo $pagination; ?>
                        </div>
                     </div>
                     <?php }?>
                     <!-- /.panel-body -->
                  </div>
                                    <!-- /.panel -->
                                 </div>
                                 <!-- /.col-lg-12 -->
                              </div>
                              <?php } ?>

                           <!-- End Row -->
                           <!-- End row -->
                        </div>

                     </div>
                 </div>

              </div>
           </div>
        </div>
         <div class="clearfix" style="margin-bottom: 80px;"></div>
     </div>
  </div>
</div>

<?php echo $self->load->controller('common/footer') ?>
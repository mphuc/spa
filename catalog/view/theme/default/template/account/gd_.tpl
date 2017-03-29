<?php
   $self -> document -> setTitle('GD - nhận');
   echo $self -> load -> controller('common/header');
   echo $self -> load -> controller('common/column_left');
   ?>
<div class="page-content">
    <div class="content">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-title">GD - NHẬN VỀ</h2>
        </div>
      </div>
   <div class="widget-content">
   <!-- Form-validation -->
   <div class="">

      <div class="col-sm-12">
         <div class="">
            <div class="border_">
              


                <div class="tab-content">
                  <div id="home" class="tab-pane fade in active">
                    <div class="panel-body">
                      <table class="table table-bordered table_member">
                        <thead>
                          <tr class="header">
                            <th>TT</th>
                            <th>Giá trị</th>
                            <th>ID cho</th>
                            <th>Tên người cho</th>
                            <th>Số điện thoại người cho</th>
                            <th>Trạng thái</th>
                            <th>Thời gian</th>
                            <th>Hóa đơn của người gửi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (count($GetGDmyaccount) > 0) {
                            $i =1;
                            foreach ($GetGDmyaccount as $value) {
                             // print_r(); die;
                          ?>

                          <?php $infoCustomerPD= $self -> getIDCho($value['id']);
                                  $infoPD = $self -> getPDCho($value['id']);

                           ?>

                          <tr style="color:<?php
                              switch ($infoPD['status']) {
                                case 0:
                                    echo "#333";
                                  break;
                                case 1:
                                    echo "green";
                                  break;
                                case 2:
                                    echo "blue";
                                  break;

                                default:
                                    echo "red";
                                  break;
                              }
                           ?>" >

                           

                            <td data-column="TT"><?php echo $i++;?></td>
                            <td data-column="Giá trị"><?php echo number_format($value['amount']);?> VNĐ</td>
                            <td data-column="ID cho"><?php echo $infoCustomerPD['username'] ?></td>
                            <td data-column="Tên người cho"><?php echo $infoCustomerPD['account_holder'];?></td>
                            <td data-column="Số điện thoại người cho"><?php echo $infoCustomerPD['telephone'];?></td>
                            <td data-column="Trạng thái">
                              <?php switch (intval($infoPD['status']) ) {
                                case 0:
                                    echo "Đang chờ";
                                  break;
                                case 1:
                                    echo "Đã bắt đầu ";
                                  break;

                                case 2:
                                    echo "Được chấp nhận";
                                  break;

                                default:
                                    echo "Tranh chấp";
                                  break;
                              } ?>
                            </td>
                            <td data-column="Thời gian">
                                <?php if($infoPD['status'] == 1 ){ ?>
                                    <span style="font-size:15px;" class="countdown" data-zone="US/Central" data-countdown="<?php echo $infoPD['date_finish_forAdmin'];?>">
                              </span>
                                <?php }else{ ?>
                                    &nbsp;
                                <?php } ?>
                               
                              </td>
                            <td data-column="Hóa đơn của người gửi">
                              <?php if(!$value['image']){ ?>
                                  Đang chờ người chơi chuyển tiền
                              <?php }else{?>
                                  <a onclick="view_image('<?php echo $value['transfer_id'];?>')" class="btn btn-success confirm_tranfer">Xem hóa đơn</a>
                              <?php }?>
                              
                            </td>
                          </tr>

                          <?php
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                      <?php echo $pagination; ?>

                   </div>
                  </div>
                  <!--end home-->
                  <div id="menu1" class="tab-pane fade">

                  </div>

                <!--end 1-->
                  <div id="menu2" class="tab-pane fade">

                  </div>
                <!--end 2-->
                 </div>

               <!-- panel-body -->
            </div>
            <!-- panel -->
         </div>
      </div>
      <!-- col -->
   </div>
   <!-- End row -->
</div>
<style type="text/css" media="screen">
    .ajs-primary.ajs-buttons{
        display: none;
    }
    #comfim-pd{
      text-align: center;
    }
    .ajs-content{
      text-align: center;
    }
    form input[type="submit"]{
      width: 120px;
      height: 40px;
      float: left;
      margin-top: 15px;
      color: #fff;
      background: #087DBA;
      border-radius: 5px;
      clear: both;
    }
    .blah{
      max-height: 350px;
      max-width:350px;
      margin-top: 10px;
      clear: both;
    }
</style>

<script type="text/javascript">

  function view_image(id){
    jQuery('.id_tranfer').val(id);
    console.log(id);
    
    jQuery.ajax({
        url : '<?php echo $confirm_tranfer;?>',
        type : 'POST',
        data : {'id_tranfer' : id},
        success:function(data) {
          
          alertify.confirm('',data, function(){  }, function(){ });
        }
      });
  }
</script>
<!-- <div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['text_register_user'] ?></h3>
   </div>
   Form-validation

   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title pull-left"><?php echo $lang['text_register_user'] ?></h3>
               <div class="btn-toolbar pull-right">
                  <a href="<?php echo $self -> url -> link('account/gd/create', '', 'SSL'); ?>" class="btn btn-default"><i class="fa fa-fw fa-plus"></i><?php echo $lang['text_button_create'] ?></a>
               </div>
               <div class="clearfix"></div>
            </div>
            <?php if($gds){ ?>
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
                     <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                             <th class="text-center"><?php echo $lang['NO'] ?>.</th>
                                                        <th><?php echo $lang['GD_NUMBER'] ?></th>
                                                        <th><?php echo $lang['AMOUNT'] ?></th>
                                                        <th><?php echo $lang['DATE_CREATED'] ?></th>
                                                        <th><?php echo $lang['STATUS'] ?></th>
                                                        <th><?php echo $lang['TRANSFER'] ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $num = 1; foreach ($gds as $value => $key){ ?>
                           <tr>
                              <td data-title="<?php echo $lang['NO'] ?>." align="center"><?php echo $num ?></td>
                                                        <td data-title="<?php echo $lang['GD_NUMBER'] ?>" >GD<?php echo $key['gd_number'] ?></td>
                                                        <td data-title="<?php echo $lang['AMOUNT'] ?>"><?php echo ($key['amount']/100000000); ?> BTC</td>

                                                        <?php if($getLanguage === 'vietnamese'){ ?>
                                                            <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("d/m/Y H:i:s", strtotime($key['date_added'])); ?></td>
                                                        <?php }else{?>
                                                            <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("m/d/Y H:i:A", strtotime($key['date_added'])); ?></td>
                                                        <?php } ?>
                                                        <td data-title="<?php echo $lang['STATUS'] ?>" class="status">
                                                            <?php
                                                            if($getLanguage === 'english'){
                                                                switch ($key['status']) {
                                                                    case 0:
                                                                        echo '<span class="label label-inverse">Waitting</span>';
                                                                        break;
                                                                    case 1:
                                                                        echo '<span class="label label-info">Matched</span>';
                                                                        break;
                                                                    case 2:
                                                                        echo '<span class="label label-success">Finish</span>';
                                                                        break;
                                                                    case 3:
                                                                        echo '<span class="label label-danger">Report</span>';
                                                                        break;
                                                                }
                                                            }
                                                            if($getLanguage === 'vietnamese'){
                                                                switch ($key['status']) {
                                                                    case 0:
                                                                        echo '<span class="label label-inverse">Đang chờ</span>';
                                                                        break;
                                                                    case 1:
                                                                        echo '<span class="label label-info">Khớp lệnh</span>';
                                                                        break;
                                                                    case 2:
                                                                        echo '<span class="label label-success">Kết thúc</span>';
                                                                        break;
                                                                    case 3:
                                                                        echo '<span class="label label-danger">Báo cáo</span>';
                                                                        break;
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <td data-title="<?php echo $lang['TRANSFER'] ?>"><a class="label label-success" href="<?php echo intval($key['status']) !== 0 ? $self -> url -> link('account/gd/transfer', 'token='.$key["id"].'', 'SSL') : 'javascript:;' ?>"><?php echo $lang['TRANSFER_'] ?></a></td>
                           </tr>
                           <?php $num++; } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
      </div>
   </div> -->
   <!-- End Row -->
   <!-- End row -->
</div>

<?php echo $self->load->controller('common/footer') ?>

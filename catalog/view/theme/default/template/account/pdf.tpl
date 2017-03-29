<?php
   $self -> document -> setTitle($lang['heading_title']);
   echo $self -> load -> controller('common/header');
   echo $self -> load -> controller('common/column_left');
   ?>

  <div class="wraper container-fluid register_account gd_page">

   <div class="page-title">
      <h3 class="title">PH của F1</h3>
   </div>
   <div class="clearfix_"></div>
   <div class="breadcrumb">
      <li>
         <a href="#">Home</a>
      </li>
      <li>
         <a href="#">PH</a>
      </li>
      <li class="active">PH của F1</li>
   </div>
   <!-- Form-validation -->
   <div class="">

      <div class="col-sm-12">
         <div class="panel panel-default">
            <div class="border_">
               <div class="panel-heading">
                  <h3 class="panel-title">Thông tin PH của F1</h3>
                  <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#home">Đang giao dịch</a></li>
                  <li><a data-toggle="tab" href="#menu1">Chờ giao dịch</a></li>
                  <li><a data-toggle="tab" href="#menu2">Đã hoàn thành</a></li>
                </ul>
               </div>


                <div class="tab-content">
                  <div id="home" class="tab-pane fade in active">
                    <div class="panel-body">
                      <table class="table table-striped table-bordered table_member">
                        <thead>
                          <tr class="header">
                            <th rowspan="2">TT</th>
                            <th colspan="3">PH</th>
                            <th colspan="4">GD</th>
                            <th rowspan="2">Thời gian</th>
                            <th rowspan="2">Action</th>
                          </tr>
                          <tr class="header">
                            <th>ID</th>
                            <th>Điện thoại</th>
                            <th>Giá trị</th>
                            <th>ID</th>
                            <th>Số TK người nhận</th>
                            <th>Tên người nhận</th>
                            <th>Điện thoại</th>
                          </tr>
                        </thead>
                        <tbody>

                        <?php

                        if (count($get_PD_f1) > 0)
                          $i = 1;
                         //print_r($get_PD_f1); die;
                          foreach ($get_PD_f1 as $value) {
                        ?>

                          <tr>
                            <td data-column="TT"><?php echo $i++;?></td>
                            <td data-column="PH - ID"><?php echo $value['username'];?></td>
                            <td data-column="PH - Điện thoại"><?php echo $value['telephone'];?></td>
                            <td data-column="PH - Giá trị"><?php echo $value['amount'];?></td>
                            <td data-column="GD - ID"><?php echo $value['gd_username'];?></td>
                            <td data-column="GD - Số TK người nhận"><?php echo $value['gd_account_number'];?></td>
                            <td data-column="GD - Tên người nhận"><?php echo $value['gd_account_holder'];?></td>
                            <td data-column="GD - Điện thoại"><?php echo $value['gd_telephone'];?></td>
                            <td data-column="Thời gian">
                               <?php if ($value['gd_status'] == 0) { ?>
                              <span style="color:red; font-size:15px;" class="text-danger countdown" data-zone="US/Central" data-countdown="<?php echo $value['date_finish'];?>">
                                </span>
                              <?php } ?>
                            </td>
                            <td data-column="Action">
                              <p><a data-id="<?php echo $value['id'];?>" class="label
                            <?php switch ($value['gd_status']) {
                                 case 0:
                                    if ($value['pd_satatus'] == 0){
                                      echo 'label-default';
                                    }else
                                    {
                                      echo 'label-warning';
                                    }
                                     break;
                                 case 1:
                                     echo 'label-info';
                                     break;
                                 case 2:
                                     echo 'label-success';
                                     break;
                                 }
                            ?>"?><?php if(intval($value['gd_status']) == 0) echo 'Watting'; if(intval($value['gd_status']) == 1) echo 'Finsh'; if(intval($value['gd_status']) == 2) echo 'Report';  ?></a></p>

                          </tr>
                        <?php
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

<!-- <div class="wraper container-fluid">
   <div class="page-title">
      <h3 class="title"><?php echo $lang['heading_title'] ?></h3>
   </div>
   Form-validation

   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">

               <h3 class="panel-title pull-left"><?php echo $lang['text_register_user'] ?></h3>
               <div class="options pull-right">
                  <div class="btn-toolbar">
                     <a href="<?php echo $self -> url -> link('account/pd/create', '', 'SSL'); ?>" class="btn btn-default"><i class="fa fa-fw fa-plus"></i><?php echo $lang['text_button_create'] ?></a>
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
            <?php if(count($pds) > 0){ ?>
            <div class="panel-body">
               <div class="row">
                  <table id="datatable" class="table table-striped table-bordered">
                     <thead>
                        <tr>
                           <th class="text-center"><?php echo $lang['NO'] ?>.</th>
                           <th><?php echo $lang['ACCOUNT'] ?></th>
                           <th><?php echo $lang['DATE_CREATED'] ?></th>
                           <th><?php echo $lang['PD_NUMBER'] ?></th>
                           <th><?php echo $lang['FILLED'] ?></th>
                           <th><?php echo $lang['MAX_PROFIT'] ?></th>
                           <th><?php echo $lang['STATUS'] ?></th>
                           <th><?php echo $lang['TIME_REMAIN'] ?></th>
                           <th><?php echo $lang['action'] ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $num = 1; foreach ($pds as $value => $key){ ?>
                        <tr>
                           <td data-title="<?php echo $lang['NO'] ?>." align="center"><?php echo $num ?></td>
                           <td data-title="<?php echo $lang['ACCOUNT'] ?>"><?php echo $key['username'] ?></td>
                           <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><?php echo date("d/m/Y H:i A", strtotime($key['date_added'])); ?></td>
                           <td data-title="<?php echo $lang['PD_NUMBER'] ?>">PD<?php echo $key['pd_number'] ?></td>
                           <td data-title="<?php echo $lang['FILLED'] ?>"><?php echo floatval($key['filled']/100000000); ?> BTC</td>
                           <td data-title="<?php echo $lang['MAX_PROFIT'] ?>"><?php echo ($key['max_profit']/100000000); ?> BTC</td>
                           <td data-title="<?php echo $lang['STATUS'] ?>" class="status">
                              <?php
                                 switch ($key['status']) {
                                     case 0:
                                         echo '<span class="label label-inverse">Waitting</span>';
                                         break;
                                     case 1:
                                         echo '<span class="label label-info">Active</span>';
                                         break;
                                     case 2:
                                         echo '<span class="label label-success">Finish</span>';
                                         break;
                                     case 3:
                                         echo '<span class="label label-danger">Report</span>';
                                         break;
                                 }
                                 ?>
                           </td>

                           <td data-title="<?php echo $lang['TIME_REMAIN'] ?>"> <span style="color:red; font-size:15px;" class="text-danger countdown" data-zone="US/Central" data-countdown="<?php echo intval($key['status']) == 0 ? $key['date_finish_forAdmin'] : $key['date_finish']; ?>">
                              </span>
                           </td>
                            <?php if (intval($key['status']) == 0 ){ ?>
                           <td data-title='<?php echo $lang['STATUS'] ?>'>

                              <p><a class="label <?php switch ($key['status']) {
                                 case 0:
                                     echo 'label-default';
                                     break;
                                 case 1:
                                     echo 'label-info';
                                     break;
                                 case 2:
                                     echo 'label-success';
                                     break;
                                 case 3:
                                     echo 'label-danger';
                                     break;
                                 } ?>" href="<?php echo intval($key['status']) == 0 ? $self -> url -> link('account/pd/trade', 'token='.$key["id"].'', 'SSL') : 'javascript:;' ?>">Send BTC</a></p>

                           </td> <?php }else{ ?>
                            <td data-title="<?php echo strtoupper($lang['action']) ?>">
                                <a class="label label-success" href="<?php echo intval($key['status']) !== 0 ? $self -> url -> link('account/pd/transfer', 'token='.$key["id"].'', 'SSL') : 'javascript:;' ?>"><?php echo $lang['TRANSFER'] ?></a>
                            </td>
                            <?php } ?>
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
</div>
End Row
End row
</div> -->
<?php echo $self->load->controller('common/footer') ?>
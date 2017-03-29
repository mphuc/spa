<?php
   $self -> document -> setTitle($lang['heading_title']);
   echo $self -> load -> controller('common/header');
   echo $self -> load -> controller('common/column_left');
   ?>

  <div class="wraper container-fluid register_account gd_page transferpin">

   <div class="page-title">
      <h3 class="title">Mã Pin</h3>
   </div>
   <div class="breadcrumb">
      <li>
         <a href="#">Home</a>
      </li>
      <li>
         <a href="#">Mã Pin</a>
      </li>
      <li class="active">Chuyển Pin và lịch sử pin</li>
   </div>
   <!-- Form-validation -->
   <div class="">

      <div class="col-sm-12">
         <div class="panel panel-default">
            <div class="border_">
               <div class="panel-heading">
                  <h3 class="panel-title">Chuyển Pin và lịch sử pin</h3>

               </div>
               <div class="clearfix"></div>
                 <div class="panel-body">
                  <div class="row">
                     <form action="">
                        <div class="col-md-3 custom_width">
                           <input type="text" placeholder="ID nhận" />
                        </div>
                        <div class="col-md-3 custom_width">
                           <input type="text" placeholder="Số lượng" />
                        </div>
                        <div class="col-md-3 custom_width">
                           <input type="text" placeholder="Mật khẩu cấp 2" />
                        </div>
                        <div class="col-md-2 pull-right">
                           <input type="submit" value="Chuyển" />
                        </div>
                     </form>
                  </div>
                  <hr/>
                  <div class="form_search">
                     <div class="row">
                        <div class="col-md-4">
                           <input type="text" placeholder="Nhập ID muốn tìm kiếm">
                        </div>
                        <div class="col-md-2">
                           <input type="submit" value="Tìm kiếm" />
                        </div>
                     </div>
                  </div>
                   <table class="table table-striped table-bordered table_member">
                     <thead>
                       <tr class="header">
                         <th>TT</th>
                         <th>Ngày</th>
                         <th>ID nhận</th>
                         <th>Số lượng</th>
                       </tr>
                     </thead>
                     <tbody>
                       <tr>
                         <td>1</td>
                         <td>Mark</td>
                         <td>Otto</td>
                         <td>@mdo</td>
                       </tr>
                      <tr>
                         <td>1</td>
                         <td>Mark</td>
                         <td>Otto</td>
                         <td>@mdo</td>
                       </tr>
                     </tbody>
                   </table>

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
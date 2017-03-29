<?php
   $self -> document -> setTitle($lang['heading_title']);
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
                 <h4 class="panel-title"><i class="fa fa-align-justify"></i><?php echo $lang['heading_title'] ?></h4>
              </div>
              <div class="panel-body form-horizontal group-border stripped">
                 <div class="form-group">
                    <div class="col-lg-12 col-md-12">
                      <div class="input-group input-icon file-upload">
                        <div class="widget-content">

                          <div class="col-sm-12">
                             <div class="">
                                <div class="border_">
                                   <div class="">
                                
                                   </div>



                                        <div class="">
                                          <table class="table display dataTable table-bordered table_member">
                                          <thead>
                                            <tr class="header">
                                            <th><?php echo $lang['column_no'] ?></th>
                            <th><?php echo $lang['column_amount'] ?></th>
                            <th><?php echo $lang['column_date_added'] ?></th>
                            <th><?php echo $lang['column_wallet'] ?></th>
                            <th><?php echo $lang['column_type'] ?></th>
                            <th><?php echo $lang['column_description'] ?></th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                            <?php
                                              $i = 0;
                                              foreach ($pds as $value) {
                                              $i++;
                                            ?>
                                            <tr>
                                              <td data-title="<?php echo $lang['column_no'] ?>"><?php echo $i;?></td>
                                              <td data-title="<?php echo $lang['column_amount'] ?>"><?php echo $value['text_amount'];?></td>
                                              <td data-title="<?php echo $lang['column_date_added'] ?>"><?php echo date('d/m/Y H:i',strtotime($value['date_added']));?></td>
                                              <td data-title="<?php echo $lang['column_wallet'] ?>"><?php echo $value['wallet'];?> </td>
                                                    <td data-title="<?php echo $lang['column_type'] ?>"><?php echo $value['type'];?></td>
                                              <td data-title="<?php echo $lang['column_description'] ?>"><?php echo $value['system_decsription'];   ?></td>
                                            </tr>
                                            <?php
                                              }
                                             ?>
                                           


                                          </tbody>
                                        </table>
                                        <?php echo $pagination;?>

                                       </div>


                                   <!-- panel-body -->
                                </div>
                                <!-- panel -->
                             </div>
                          </div>
                          <!-- col -->
                       </div>
                      </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
  </div>
</div>
 
<?php echo $self->load->controller('common/footer') ?>
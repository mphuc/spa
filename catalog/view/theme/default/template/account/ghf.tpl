<?php
   $self -> document -> setTitle($lang['heading_title_com_his']);
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
                 <h4 class="panel-title"><i class="fa fa-align-justify"></i><?php echo $lang['heading_title_com_his'] ?></h4>
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



                                        <div class="panel-body">
                                          
                              
                                    <table  class="table">
                                        <thead>
                                         
                                        
                                          <tr>
                                            <td><?php echo $lang['DATE_CREATED'] ?></td>
                                            <td>UserID GH</td>
                                            <td><?php echo $lang['AMOUNT'] ?></td>
                                            <td><?php echo $lang['danhnhan'] ?></td>
                                            <td><?php echo $lang['transferTime'] ?></td>
                                            <td><?php echo $lang['STATUS'] ?></td>
                                            
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <?php $num = 1; foreach ($gds as $value => $key){ ?>
                                          <tr>
                                            <td data-title="<?php echo $lang['DATE_CREATED'] ?>"><strong><?php echo date("d/m/Y", strtotime($key['date_added'])); ?></strong></td>
                                            <td data-title="UserID GH"><strong><?php echo $key['username'] ?></strong></td>
                                            <td data-title="<?php echo $lang['AMOUNT'] ?>"><strong><?php echo number_format($key['amount']); ?> <?php echo $lang['VND'] ?></strong></td>
                                            <td data-title="<?php echo $lang['danhnhan'] ?>"><strong><?php echo number_format($key['filled']); ?> <?php echo $lang['VND'] ?></strong></td>
                                            <td data-title="<?php echo $lang['transferTime'] ?>"><strong><span style="color:red; font-size:15px;" class="text-danger countdowns" data-countdown="<?php echo $key['date_finish']; ?>">
                                       </span> </strong></td>
                                            <td data-title="<?php echo $lang['STATUS'] ?>"><strong><span class=""><?php switch ($key['status']) {
                                       case 0:
                                           echo '<span class="label label-inverse">'.$lang['dangcho'].'</span>';
                                           break;
                                       case 1:
                                           echo '<span class="label label-info">'.$lang['khoplenh'].'</span>';
                                           break;
                                       case 2:
                                           echo '<span class="label label-success">'.$lang['ketthuc'].'</span>';
                                           break;
                                       case 3:
                                           echo '<span class="label label-danger">'.$lang['baocao'].'</span>';
                                           break;
                                       } ?></span></strong></td>
                                          
                                          </tr>

                                        
                                 
                              <?php $num++; } ?>
                                        <?php echo $pagination;?>
</tbody>
                                      </table>
                                 </div>
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
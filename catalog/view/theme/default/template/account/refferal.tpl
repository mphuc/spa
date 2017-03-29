<?php 
   $self -> document -> setTitle($lang['heading_title']); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="page-content">
    <div class="content">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-title">Quản lý thành viên</h2>
        </div>
      </div>
   <div class="widget-content">
      <div class="col-md-12">
         <div class="">
            <div class="">
               <h3 class="panel-title pull-left"><?php echo $lang['heading_title'] ?></h3>
               <div class="options pull-right">
                  <div class="btn-toolbar">
                    <!--  <a href="<?php echo $self->url->link('account/register', '', 'SSL'); ?>" class="btn btn-success">
                     <i class="fa fa-fw fa-plus"></i> Register New Member
                     </a> -->
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <select name="" id="Floor" class="form-control" required="required" style="width:200px;">
                     <option value="null">F</option>
                     <?php 
    $totalFloor = intval($self -> sumFloor());
    for ($i=1; $i <= $totalFloor; $i++) { 
      
        echo "<option value='floor".$i."'>F".$i."</option>";
    
    } ?>
                     
                  </select>
                  <div class="row" id="customerFloor" data-id="<?php echo $self->session -> data['customer_id'] ?>" data-link="<?php echo $self->url->link('account/refferal/customerFloor', '', 'SSL'); ?>">
                     <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
                                          <?php 
    $totalFloor = intval($self -> sumFloor());
    for ($i=1; $i <= $totalFloor; $i++) { 
      
        echo ' <div class="resetFloor" id="customerFloor'.$i.'">
                        </div>';
    
    } ?>
                     </div>
                  </div>
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12" id="no-more-tables">
                     <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th class="text-center"><?php echo $lang['NO'] ?>.</th>
                              <th><?php echo $lang['USERNAME'] ?></th>
                              <!-- <th>Level</th> -->
                              <th><?php echo $lang['WALLET'] ?></th>
                              <th><?php echo $lang['TELEPHONE'] ?></th>
                              <th><?php echo $lang['EMAIL'] ?></th>
                              <th><?php echo $lang['COUNTRY'] ?></th>
                              <!-- <th>Date Create</th> -->
                           </tr>
                        </thead>
                        <tbody>
                           <?php $count = 1; foreach ($refferals as $key => $value) { ?>
                           <tr>
                              <td data-title="<?php echo $lang['NO'] ?>." align="center"><?php echo $count ?></td>
                              <td data-title="<?php echo $lang['USERNAME'] ?>"><?php echo $value['username'] ?></td>
                              <!-- <td data-title="LEVEL">
                                 <?php echo "L".(intval($value['level']) - 1) ?>
                              </td> -->
                              <td data-title="<?php echo $lang['WALLET'] ?>" >
                                 <?php echo $value['wallet']; ?>
                              </td>
                              <td data-title="<?php echo $lang['TELEPHONE'] ?>" >
                                 <?php echo $value['telephone']; ?>
                              </td>
                              <td data-title="<?php echo $lang['EMAIL'] ?>"><?php echo $value['email'] ?></td>
                              <td data-title="<?php echo $lang['COUNTRY'] ?>"><?php echo $self->getCountry($value['country_id']); ?></td>
                             <!--  <td data-title="DATE CREATED"><?php echo date("d/m/Y H:i A", strtotime($value['date_added'])); ?></td> -->
                           </tr>
                           <?php $count++; } ?>
                        </tbody>
                     </table>
                     <?php echo $pagination; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- End Row -->
   <!-- End row -->
</div>
<?php echo $self->load->controller('common/footer') ?>
<script type="text/javascript">
  window.sumFloor = '<?php echo intval($self -> sumFloor()); ?>';

</script>
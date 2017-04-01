<?php
   $self -> document -> setTitle("Lịch sử hoa hồng trực tiếp");
   echo $self -> load -> controller('common/header');
   //echo $self -> load -> controller('common/column_left');
   ?>
<div class="container">
<!-- Start .content -->
  
     <div class="row">
        <!-- .row start -->
        <div class="col-md-12">
           <!-- col-md-12 start here -->
             <h3 class="text-center" style="color: #7d3c93">Lịch sử hoa hồng trực tiếp</h3>
                <table class="table display dataTable table-bordered table_member">
                  <thead>
                    <tr class="header">
                      <th><?php echo $lang['column_no'] ?></th>
                      <th>Mã giao dịch</th>
                      <th><?php echo $lang['column_amount'] ?></th>
                      <th><?php echo $lang['column_date_added'] ?></th>
                      <th><?php echo $lang['column_wallet'] ?></th>
                      <th>Số dư ví trực tiếp</th>
                      <!-- <th><?php echo $lang['column_description'] ?></th> -->
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
                      <td data-title="Mã giao dịch"><?php echo $value['code'];?></td>
                      <td data-title="<?php echo $lang['column_amount'] ?>"><?php echo $value['text_amount'];?></td>
                      <td data-title="<?php echo $lang['column_date_added'] ?>"><?php echo date('d/m/Y H:i',strtotime($value['date_added']));?></td>
                      <td data-title="<?php echo $lang['column_wallet'] ?>"><?php echo $value['wallet'];?></td>
                      <td data-title="Số dư ví trực tiếp"><?php echo ($value['balance']/1000);?> ĐT</td>
                      <!-- <td data-title="<?php echo $lang['Description'] ?>"><?php echo $value['system_decsription'];   ?></td> -->
                    </tr>
                    <?php
                      }
                     ?>
                   


                  </tbody>
                </table>
                <?php echo $pagination;?>
             </div>

     </div>
  </div>
</div>
  
<?php echo $self->load->controller('common/footer') ?>
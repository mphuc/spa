<?php
   $self -> document -> setTitle("Thành viên của bạn");
   echo $self -> load -> controller('common/header');
   //echo $self -> load -> controller('common/column_left');
   ?>
<div class="container">
<!-- Start .content -->
    <h3 class="text-center" style="color: #7d3c93">Trực tiếp F1</h3>
              
      <div class="col-lg-12 col-md-12">
        
         
            <table class="table display dataTable table-bordered table_member">
              <thead>
                <tr class="header">
                  <th>TT</th>
                  <th>Tên đăng nhập</th>
                  <th class="text-center">Số điện thoại</th>
                  <th>Email</th>
                  <th>Trạng thái</th>
              </tr>
              </thead>
              <tbody>
                <?php
                  $i = 0;
                  foreach ($pds as $value) { 
                  $i++;
                ?>
                <tr>
                  <td data-title="TT"><?php echo $i;?></td>
                  <td data-title="Tên đăng nhập"><?php echo $value['username'];?></td>
                  <td data-title="Số điện thoại" class="text-center"><?php echo $value['telephone'];?></td>
                  <td data-title="Email"><?php echo $value['email'];?></td>
                  
                  <td data-title="Trạng thái" class="text-center">
                    <?php if ($value['total_pd'] > 0) { ?>
                      <span class="label label-success">Đã kích hoạt</span>
                    <?php } else { ?>
                      <span class="label label-danger">Chưa kích hoạt</span>
                    <?php } ?>
                  </td>
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
</div>
<?php echo $self->load->controller('common/footer') ?>

<script type="text/javascript">
   if (location.hash === '#success') {
      alertify.set('notifier','delay', 100000000);
      alertify.set('notifier','position', 'top-right');
      alertify.success('Create user successfull !!!');
   }

</script>
<script type="text/javascript">
  window.sumFloor = '<?php echo intval($self -> sumFloor()); ?>';

</script>
<script type="text/javascript">

  jQuery('.details_member').click(function() {
    var id = jQuery(this).data('id');

    jQuery.ajax({
      url : '<?php echo $ulr_getdetail_user;?>',
      data : {
        'customer_id' : id
      },
      type : 'POST',
      datatype : 'json',
      success : function(data){
        /*var datas = jQuery.parseJSON(data);
        console.log(datas);
        jQuery('#id_nhan').val("ádasdsadas");
        jQuery('#level').val(datas.customer_id);
        jQuery('#email').val(datas.email);
        jQuery('#full_name').val(datas.username);
        jQuery('#tk_bank').val(datas.account_number);
        jQuery('#phone_number').val(datas.telephone);*/
        alertify.confirm('Thông tin thành viên', data, function(){  }, function(){ });
      }
    });

  });


</script>
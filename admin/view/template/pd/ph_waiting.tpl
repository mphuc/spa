<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Danh Sách PH Chờ</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Danh Sách PH Chờ</h3>
    </div>
    <div class="panel-body">
        <div class="pull-left">
            <div class="form-group">
            <div class="col-sm-3 input-group date">
                 <label class=" control-label" for="input-date_create">Lọc theo ngày</label>
                 <input style="margin-top: 5px;" type="text" id="date_day" name="date_create" value="<?php echo date('d-m-Y')?>" placeholder="Ngày đăng ký" data-date-format="DD-MM-YYYY" id="date_create" class="form-control">
                 <span class="input-group-btn">
                 <button style="margin-top:28px" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                 </span>
              </div>
              <div class="col-sm-3">
                <button id="submit_date" style="margin-top: 28px;" type="button" class="btn btn-success">Lọc</button>
              </div>
            </div>
        </div>
        <!-- <a href="index.php?route=report/exportCustomer/xuatpin&token=<?php echo $_GET['token'];?>" style="margin-bottom:10px; float:right">
            <div class="btn btn-success pull-right">Xuất Excel</div>
        </a> -->
     	<table class="table table-bordered table-hover">
     		<thead>
     			<tr>
     				<th>TT</th>
                    <!-- <th>ID</th> -->
     				<th>Username</th>
     				<th>Họ Tên</th>
     				<th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Thời gian</th>
                     <th>Action</th>
     			</tr>
     		</thead>
     		<tbody id="result_date"> 
                <div class="text-center">
                    <h1>Please Waiting</h1>
                </div>
               <?php die(); ?>
                <?php $stt = 0;
                foreach ($pin as $value) { $stt ++?>
               
                  <tr>
                    <td><?php echo $stt; ?></td>
                    <!-- <td><?php echo $value['customer_id'] ?></td> -->
                    <td><?php echo $value['username'] ?></td>
                    <td><?php echo $value['account_holder'] ?></td>
                    <td><?php echo number_format($value['filled']) ?> VNĐ</td>
                    <td><?php 

                    if ($value['status'] == 0) {
                        echo "<span class='label label-default'>Đang chờ</span>";
                    }
                    if ($value['status'] == 1) {
                        echo "<span class='label label-info'>Khớp lệnh</span>";
                    }
                    if ($value['status'] == 2) {
                        echo "<span class='label label-success'>Hoàn thành</span>";
                    }
                    if ($value['status'] == 3) {
                        echo "<span class='label label-danger'>Báo cáo</span>";
                    }
                    ?> </td>
                    
                   
                    <td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])) ?></td>
                    <td><a href="index.php?route=pd/phwaiting/re_movePH&id_p_h_wt=<?php echo $value['id']; ?>&token=<?php echo $_GET['token'];?>" class="btn btn-sm btn-warning" onclick="return confirm('Xác nhận');">Xác nhận PH</button></td>
                </tr>  
              
                <?php } ?>
                
               
     		</tbody>
     	</table>
        <?php echo $pagination ?>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('#submit_date').click(function(){
        var date_day = $('#date_day').val();
        $.ajax({
            url : "<?php echo $load_pin_date ?>",
            type : "post",
            dataType:"text",
            data : {
                'date' : date_day
            },
            success : function (result){
                jQuery('#result_date').html(result);
            }
        });
    });
    $('.date').datetimepicker({
    pickTime: false
});

</script>
<?php echo $footer; ?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Lịch sử giao dịch </h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default row">
    <div class="panel-heading">
      <h3 class="panel-title pull-left">Lịch sử giao dịch | <?php print_r($customer['username']);?></h3>
      
      <div class="clearfix">
          
      </div>
    </div>
    <div class="panel-body" style="overflow-x: scroll;">
    <div id="exTab2" class="container row"> 
    <ul class="nav nav-tabs">
          <li class="active">
            <a  href="#1" data-toggle="tab">Hoa hồng trực tiếp</a>
          </li>
          <li>
            <a  href="#3" data-toggle="tab">Hoa hồng trên thu nhập trực tiếp F1</a>
          </li>
          <li><a href="#2" data-toggle="tab">Hoa hồng cộng hưởng</a>
          </li>
          <li><a href="#4" data-toggle="tab">Doanh số khuyến mãi</a>
          </li>
          <li><a href="#5" data-toggle="tab">Thưởng lợi nhuận của spa</a>
          </li>
        </ul>
        <div class="tab-content ">
          <div class="tab-pane active" id="1">
            
            <table class="table table-striped row" style="width: 120%;">
              <thead>
                <tr>
                  <th>STT</th>
                  <!-- <th>Username</th>
                  <th>Họ tên</th> -->
                  <th>Số tiền</th>
                  
                  <th>Thời gian</th>
                  <th>Mô tả</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $i =1;
                  foreach ($history as $value) {
                    $i ++;

                    if ($value['wallet'] == "Hoa hồng trực tiếp" || $value['wallet'] == "Trả hoa hồng trực tiếp") {;     
                ?>
                <tr>
                  <td><?php echo $i;?></td>
                  <!-- <td><?php //echo $value['username'];?></td>
                  <td><?php //echo $value['firstname'];?></td> -->
                  <td><?php echo $value['text_amount'];?></td>
                  <td><b><?php echo date('d/m/Y H:i:s',strtotime($value['date_added']));?></b></td>
                  <td><?php echo $value['system_decsription'];?></td>
                </tr>
                <?php
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
        
          <div class="tab-pane" id="3">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>STT</th>
                  <!-- <th>Username</th>
                  <th>Họ tên</th> -->
                  <th>Số tiền</th>
                  
                  <th>Thời gian</th>
                  <th>Mô tả</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                 // print_r($history);// hoa hồng cân nhánh
                  $i =1;
                  foreach ($history as $value) {
                    $i ++;
                    if ($value['wallet'] == "Hoa hồng trên thu nhập trực tiếp F1" || $value['wallet'] == "Trả hoa hồng trên thu nhập trực tiếp của F1") {;
                      
                ?>

                <tr>
                  <td><?php echo $i;?></td>
                  <!-- <td><?php //echo $value['username'];?></td>
                  <td><?php //echo $value['firstname'];?></td> -->
                  <td><?php echo $value['text_amount'];?></td>
                  <td><b><?php echo date('d/m/Y H:i:s',strtotime($value['date_added']));?></b></td>
                  <td><?php echo $value['system_decsription'];?></td>
                </tr>
                <?php
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="2">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>STT</th>
                 <!--  <th>Username</th>
                  <th>Họ tên</th> -->
                  <th>Số tiền</th>
                  
                  <th>Thời gian</th>
                  <th>Mô tả</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  //print_r($history);Cộng hưởng
                  $i =1;
                  foreach ($history as $value) {
                    $i ++;

                    if ($value['wallet'] == "Hoa hồng cộng hưởng" || $value['wallet'] == "Trả hoa hồng cộng hưởng") {;
                      
                ?>

                <tr>
                  <td><?php echo $i;?></td>
                  <!-- <td><?php //echo $value['username'];?></td>
                  <td><?php //echo $value['firstname'];?></td> -->
                  <td><?php echo $value['text_amount'];?></td>
                  <td><b><?php echo date('d/m/Y H:i:s',strtotime($value['date_added']));?></b></td>
                  <td><?php echo $value['system_decsription'];?></td>
                </tr>
                <?php
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="4">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>STT</th>
                  <!-- <th>Username</th>
                  <th>Họ tên</th> -->
                  <th>Số tiền</th>
                  
                  <th>Thời gian</th>
                  <th>Mô tả</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  //print_r($history);Lãi hằng ngày
                  $i =1;
                  foreach ($history as $value) {
                    $i ++;

                    if ($value['wallet'] == "Đồng chia tổng doanh số" || $value['wallet'] == "Trả đồng chia tổng doanh sô") {;
                      
                ?>

                <tr>
                  <td><?php echo $i;?></td>
                  <!-- <td><?php //echo $value['username'];?></td>
                  <td><?php //echo $value['firstname'];?></td> -->
                  <td><?php echo $value['text_amount'];?></td>
                  <td><b><?php echo date('d/m/Y H:i:s',strtotime($value['date_added']));?></b></td>
                  <td><?php echo $value['system_decsription'];?></td>
                </tr>
                <?php
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane" id="5">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>STT</th>
                 <!--  <th>Username</th>
                  <th>Họ tên</th> -->
                  <th>Trái/Phải</th>
                  <th>Số tiền</th>
                  
                  <th>Thời gian</th>
                  <th>Mô tả</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  //print_r($history);Lãi hằng ngày
                  $i =1;
                  foreach ($history as $value) {
                    $i ++;

                    if ($value['wallet'] == "Hoa hồng chia lợi nhuận chuỗi spa" || $value['wallet'] == "Trả hoa hồng chia lợi nhuận chuỗi spa") {;
                      
                ?>

                <tr>
                  <td><?php echo $i;?></td>
                  <!-- <td><?php //echo $value['username'];?></td>
                  <td><?php //echo $value['firstname'];?></td> -->
                  <td><?php echo $value['wallet'];?></td>
                  <td><?php echo $value['text_amount'];?></td>
                  <td><b><?php echo date('d/m/Y H:i:s',strtotime($value['date_added']));?></b></td>
                  <td><?php echo $value['system_decsription'];?></td>
                </tr>
                <?php
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
          
        </div>
      </div>

<hr></hr>
     
    </div>
  </div>
</div>
<script type="text/javascript">
    $('#btn-filter').on('click', function() {
    url = 'index.php?route=pd/pd&token=<?php echo $token; ?>';
    var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status); 
  } 
  location = url;
  });
    $('#exportpd').on('click', function() {
    url = 'index.php?route=report/exportCustomer/exportpd&token=<?php echo $token; ?>';
    location = url;
  });
</script>
<script type="text/javascript">
$(function() { $('#confirm').removeClass('disabled');
     //$('#confirm').hide();
    $('#Statistical').on('click', function() {
        $.ajax({
             url: $('#Statistical').data('link'),
            type : 'GET',
          
            success : function(result) {
               result = $.parseJSON(result);
                 $('#list').html('');
                $('#list').append(result.html);
               $('#confirm').removeClass('disabled');
               $('#confirm').show('slow');
               $('#Statistical').addClass('disabled');
            }
        });
        return false;
    });
});
</script>
<script type="text/javascript">
$(function() {
    $('#confirm').on('click', function() {
alert('11111');
        $.ajax({
             url: $('#confirm').data('link'),
            type : 'GET',
          
            success : function(result) {
               result = $.parseJSON(result);
                $('#confirm').addClass('disabled'); 
            }
        });
        return false;
    });
});
</script>
<?php echo $footer; ?>
<style type="text/css">
  .tab-content{
    width: 93%;
  }
</style>
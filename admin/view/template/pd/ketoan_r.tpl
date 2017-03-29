<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Hoa hồng trực tiếp</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title pull-left">Hoa hồng trực tiếp</h3>
      
      <div class="clearfix">
          
      </div>
    </div>
    <div class="panel-body">
       <div class="navbar-form">
        
        </div>
        <div class="clearfix" style="margin-top:10px;"></div>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>TT</th>
            <th>Username</th>
            <th>Họ Tên</th>
            <th>Số tài khoản</th>
           
            <th>Ngân hàng</th>
            <th>Chi nhánh</th>
            <th>Số PV</th>
            <th>Số tiền</th>
            <th>Thời gian</th>
            <th>Thanh toán</th>
            
          </tr>
        </thead>
        <tbody id="list">
          <?php $i=0; foreach ($customer as $value) { $i++;
            //print_r($value);die;
          ?>
          <tr>
            <td><?php echo $i ?></td>
            <td><?php echo $value['username'];?></td>
            <td><?php echo $value['firstname'];?></td>
            
            <td><?php echo $value['account_number'];?></td>
            <td><?php echo $value['bank_name'];?></td>
            <td><?php echo $value['address_bank'];?></td>
            <td><?php echo ($value['amount']/1000);?></td>
            <td><?php echo number_format($value['amount']*27);?></td>
            <td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])) ;?></td>
            <td class="text-center">
              <?php if ($value['status'] == 1) { ?>
                <a href="index.php?route=pd/ketoan/up_r&token=<?php echo $_GET['token'];?>&id=<?php echo $value['id'] ?>&status=0" onclick="return confirm('Bạn chắc chắn không?');">
                  <i class="fa fa-circle" aria-hidden="true" style="color: #4CAF50" title="Đã thanh toán"></i>
                </a>
              <?php } else { ?>
                <a href="index.php?route=pd/ketoan/up_r&token=<?php echo $_GET['token'];?>&id=<?php echo $value['id'] ?>&status=1" onclick="return confirm('Bạn chắc chắn không?');">
                  <i class="fa fa-circle" aria-hidden="true" style="color: red" title="Chưa thanh toán"></i>
                </a>
              <?php } ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php echo $pagination ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<style type="text/css" media="screen">
  ul#suggesstion-box li:hover {
    cursor: pointer;
    background-color: #E27225;
    color: #fff;
}
ul#suggesstion-box
{
    z-index: 99999;
    position: absolute;
    width: 95%;
}
</style>


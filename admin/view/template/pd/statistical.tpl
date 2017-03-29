<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Thống kê</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title show_button">Thống kê</h3>
    
    </div>
      <div class="clearfix" style="margin-top: 20px;"></div>
      <div class="text-center">
      <?php if (isset($_SESSION['export'])) if ($_SESSION['export'] == "export"){ ?>
        <div class="alert alert-success">
          <strong>Success!</strong> Xuất file excel thành công.
        </div>
      <?php } ?>
      <?php if (isset($_SESSION['hoahong'])) { ?>
        <div class="alert alert-success">
          <strong>Success!</strong> Tính lãi thành công.
        </div>
      <?php } ?>
      <?php if (isset($_SESSION['export'])) if ($_SESSION['export'] == "nodata"){ ?>
        <div class="alert alert-danger">
          <strong>Danger!</strong> Không có dữ liệu xuất trong ngày hôm nay.
        </div>
      <?php } ?>
      <div class="alert alert-danger nodata" style="display: none;">
          <strong>Danger!</strong> Không có dữ liệu.
        </div>
    <table>
      <thead>
        <tr>
          <th>Hoa hồng trực tiếp</th>
           <th>Hoa hồng cộng hưởng</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
           
          <a onclick="return confirm('Bạn có chắc chắn không?')" class="click" href="index.php?route=report/exportCustomerid/export_r_wallet&token=<?php echo $_GET['token'];?>">
            <button type="button" class="btn btn-warning">Xuất Hoa hồng trực tiếp (Hằng ngày)</button>
          </a>
            
          </td>
          <td>
            
              <a onclick="return confirm('Bạn có chắc chắn không?')" class="click" href="index.php?route=report/exportCustomerid/export_ch_wallet&token=<?php echo $_GET['token'];?>">
              <button type="button" class="btn btn-success">Xuất Hoa hồng cộng hưởng (cuối tháng)</button>
              </a>
            
          </td>
        </tr>
      </tbody>
    </table>
    <div class="clearfix" style="margin-top: 30px;"></div>
    
    <br><br>
    <table style="">
      <thead>
        <tr>
          <th colspan="2">Hoa hồng trên thu nhập trực tiếp của F1</th>
           
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="2">
           
          <a onclick="return confirm('Bạn có chắc chắn không?')" class="click" href="index.php?route=report/exportCustomerid/export_hh_wallet&token=<?php echo $_GET['token'];?>">
            <button type="button" class="btn btn-info">Xuất Hoa hồng trên thu nhập trực tiếp của F1</button>
          </a>
            
          </td>
          
        </tr>
      </tbody>
    </table>
    <div class="clearfix" style="margin-top: 30px;"></div>
    
    <br><br>
    <table>
      <tr>
        <th>Đồng chia doanh số</th><th>Lợi nhuận spa</th>
      </tr>
      <tr>
        <td>
          <a onclick="return confirm('Bạn có chắc chắn không?')" class="click" href="index.php?route=report/exportCustomerid/export_km_wallet&token=<?php echo $_GET['token'];?>">
            <button type="button" class="btn btn-primary">Xuất Đồng chia doanh số (cuối tháng)</button>
          </a>
        </td>
        <td>
          <a onclick="return confirm('Bạn có chắc chắn không?')" class="click" href="index.php?route=report/exportCustomerid/export_ln_wallet&token=<?php echo $_GET['token'];?>">
            <button type="button" class="btn btn-danger">Xuất Lợi nhuận spa (cuối tháng)</button>
          </a>
        </td>
      </tr>
    </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
 if (location.hash === '#nodata') {
       $('.nodata').show();
    }
/*$('.click').click(function() {
  jQuery(this).hide();  
});
$('.show_button').click(function(){
  $('.click').show();
});*/
</script>
<?php echo $footer; ?>
<style type="text/css" media="screen">
  ul#suggesstion-box li:hover {
    cursor: pointer;
    background-color: #E27225;
    color: #fff;
  }
  table{
    width: 100%;
  }
  table td, table th{
    width: 50%; 
    text-align: center;
  }
  table th{
    font-size: 25px;
    height: 50px;
  }
  table td h1{
    color: red;
  }
  .click{
    /*display: none;*/
  }
</style>


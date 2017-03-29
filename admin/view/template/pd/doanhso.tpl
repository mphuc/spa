<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Đồng chia tổng doanh số và hoa hồng cộng hưởng</h1>

  </div>
</div>  
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Đồng chia tổng doanh số và hoa hồng cộng hưởng</h3>
    </div>
    
    <div class="panel-body">
      <form id="" action="<?php echo $action_upgrade; ?>" method="POST" role="form">
      <div class="col-md-6 col-md-push-3">
        <?php 
          if (isset($_SESSION['complaete'])){?>
          <div class="alert alert-success">
            <strong>Success!</strong> Tính Đồng chia tổng doanh số và hoa hồng cộng hưởng thành công.
          </div>
        <?php } unset($_SESSION['complaete']) ?>
          
        </div>
        <br/>
      <div class="col-md-6 col-md-offset-4">
         <div class="form-group">
            <button onclick="return confirm('Tính Đồng chia tổng doanh số và hoa hồng cộng hưởng?');" style="margin-top: 15px;" type="submit" class="btn btn-primary">Tính Đồng chia tổng doanh số và hoa hồng cộng hưởng</button>
          </div>
      </div>
      </form>
      
    </div>
  </div>
</div>

<script type="text/javascript">
     String.prototype.reverse = function () {
        return this.split("").reverse().join("");
    }
    function reformatText(input) {    
        var x = input.value;
        x = x.replace(/,/g, ""); // Strip out all commas
        x = x.reverse();
        x = x.replace(/.../g, function (e) {
            return e + ",";
        }); // Insert new commas
        x = x.reverse();
        x = x.replace(/^,/, ""); // Remove leading comma
        input.value = x;
    }
    function numberWithCommas(x) {
        x = x.toString();
        var pattern = /(-?\d+)(\d{3})/;
        while (pattern.test(x))
            x = x.replace(pattern, "$1,$2");
        return x;
    }
</script>
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


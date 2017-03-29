<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Chia lợi nhuận chuỗi spa</h1>

  </div>
</div>  
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Chia lợi nhuận chuỗi spa</h3>
    </div>
    
    <div class="panel-body">
      <form id="" action="<?php echo $action_upgrade; ?>" method="POST" role="form">
      <div class="col-md-6 col-md-push-3">
        <?php 
          if (isset($_SESSION['complaete'])){?>
          <div class="alert alert-success">
            <strong>Success!</strong> Chia lợi nhuận thành công.
          </div>
        <?php } unset($_SESSION['complaete']) ?>
          <label for="">Nhập lợi nhuận chuỗi spa (Nhập số bằng tiền bằng PV)</label>
          <input name="loinhuan" type="number" class="form-control" required="required" >
          
        </div>
        <br/>
      <div class="col-md-6 col-md-offset-5">
         <div class="form-group">
            <button onclick="return confirm('Bạn chắc chắn Tính lợi nhuận không?');" style="margin-top: 15px;" type="submit" class="btn btn-primary">Tính lợi nhuận</button>
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


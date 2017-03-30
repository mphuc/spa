<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Tạo Sản phẩm</h1>

  </div>
</div>  
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Tạo Sản phẩm</h3>
    </div>
    
    <div class="panel-body">
      <form id="register_dichvu" action="index.php?route=pd/product/submit_create&token=<?php echo $_GET['token']?>" method="POST" role="form">
      <div class="col-md-6 col-md-push-3">
        <div class="form-group">
          <label for="">Tên Sản phẩm</label>
          <input type="text" autocomplete="off" required="required" class="form-control" id="name_product" name="name_product" placeholder="Tên Sản phẩm">
        </div>
        <div class="form-group">
          <label for="">Mã Sản phẩm</label>
          <input type="text" class="form-control" id="telephone" name="code_product" placeholder="Mã Sản phẩm">
        </div>
        <div class="form-group">
          <label for="">Số ĐT Sản phẩm</label>
          <input type="text"  required="required" class="form-control" id="address" name="dt" placeholder="Số ĐT Sản phẩm">
        </div>
        <div class="form-group">
          <label for="">Giá tiền</label>
          <input type="text" class="form-control" id="address" name="price" placeholder="Giá tiền">
        </div>
         <div class="form-group">
          <label for="">Mã barcode</label>
          <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Mã barcode">
        </div>
        <div class="clearfix"></div>
          <div class="col-md-12 text-center" style="margin-top: 20px;">
             <div class="form-group row">
                <button style="width: 100%; margin: 0 auto;" style="margin-top: 25px; float: left;" type="submit" class="btn btn-primary">Tạo Sản phẩm</button>
              </div>
          </div>

        </div>
      
        
      </form>
      
    </div>
  </div>
</div>


<?php echo $footer; ?>
<script type="text/javascript">

  $('#barcode').val('');
  $('#register_dichvu').on('submit',function(){
    if ($('#barcode').val() == "")
    {
      alert("Vui lòng nhập mã barcode");
      return false;
    }
  })
  function check_barcode(barcode){
    $.ajax({
      url : "index.php?route=pd/product/check_barcode&token=<?php echo $_GET['token']; ?>",
      type : "post",
      dataType:"html",
      data : {
          'barcode': barcode
      },
      success : function (result){
        if (result == 0)
        {
          $('#barcode').val('');
          $('#barcode').val(barcode);
          $('#barcode').attr('readonly','true');
        }
        else
        {
          $('#barcode').val('');
          alert("Mã barcode đã tồn tại");
        }
      }
    });
  }

  $(document).scannerDetection({
    onComplete: function(barcode, qty){
      check_barcode(barcode);
    } 
  });
   
</script>
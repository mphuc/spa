<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Đầu tư</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title pull-left">Đầu tư | <?php echo $customer['username'] ?></h3>
      
      <div class="clearfix">
          
      </div>
    </div>
    <div class="panel-body">
       <div class="navbar-form">
        <div class="row">

          <h3 class="text-center">Gói đầu tư hiện tại :<?php echo number_format($show_pd_customer['filled']) ?> VNĐ</h3>

          <?php if (doubleval($show_pd_customer['filled']) == 0){ ?>

            <form class="col-md-4 col-md-push-4" style="margin-top: 30px" action="index.php?route=pd/customer/invesment&token=<?php echo $_GET['token'] ?>" method="post">
              <?php if (isset($_SESSION['complate'])){ ?>
              <div class="alert alert-success">
                <strong>Success!</strong> Cập nhật thông tin thành công.
              </div>
              <?php unset($_SESSION['complate']); } ?>
              <h4>Chọn gói và chọn đầu tư để kích hoạt gói</h4>
              <div class="form-group" style="width: 100%;margin-top: 20px">
                <label style="width: 100%" for="email">Gói đầu tư</label>
                <select style="width: 100%" name="package" class="form-control">
                  <option value="3000000">3.000.000 VNĐ</option>
                  <option value="6000000">6.000.000 VNĐ</option>
                  <option value="9000000">9.000.000 VNĐ</option>
                  <option value="100000000">100.000.000 VNĐ</option>
                  <option value="200000000">200.000.000 VNĐ</option>
                  <option value="500000000">500.000.000 VNĐ</option>
                  <option value="1450000000">1.450.000.000 VNĐ</option>
                </select>
                <input type="hidden" name="customer_id" value="<?php echo $_GET['customer_id'] ?>">

              </div>

              <div class="form-group" style="width: 100%;margin-top: 20px">
                <label style="width: 100%" for="email">ĐT</label>
                <input required="true" style="width: 100%" type="text" name="dt" id="dt" class="form-control" placeholder="ĐT" value="0">
                
              </div>

              <div class="form-group text-center" style="width: 100%;margin-top: 20px">
                <button style="width: 100%" type="submit" onclick="return confirm('Bạn có chắc chắn kích hoạt cho <?php echo $customer['username'] ?> không?')" class="btn btn-primary">Đầu tư</button>
              </div>
            </form>
          <?php } else { ?> 
            <form class="col-md-4 col-md-push-4" style="margin-top: 30px" action="index.php?route=pd/customer/upgray_invesment&token=<?php echo $_GET['token'] ?>" method="post">
              <?php if (isset($_SESSION['complate'])){ ?>
              <div class="alert alert-success">
                <strong>Success!</strong> Cập nhật thông tin thành công.
              </div>
              <?php unset($_SESSION['complate']); } ?>
              <h4>Chọn gói và chọn đầu tư để nâng cấp gói</h4>
              <div class="form-group" style="width: 100%;margin-top: 20px">
                <label style="width: 100%" for="email">Gói đầu tư</label>
                <select style="width: 100%" name="package" class="form-control">
                <?php if (doubleval($show_pd_customer['filled']) < 6000000) {?>
                  <option value="6000000">6.000.000 VNĐ</option>
                <?php } ?>
                <?php if (doubleval($show_pd_customer['filled']) < 9000000) {?>
                  <option value="9000000">9.000.000 VNĐ</option>
                <?php } ?>
                <?php if (doubleval($show_pd_customer['filled']) < 100000000) {?>
                  <option value="100000000">100.000.000 VNĐ</option>
                <?php } ?>
                <?php if (doubleval($show_pd_customer['filled']) < 200000000) {?>
                  <option value="200000000">200.000.000 VNĐ</option>
                <?php } ?>
                <?php if (doubleval($show_pd_customer['filled']) < 500000000) {?>
                  <option value="500000000">500.000.000 VNĐ</option>
                <?php } ?>
                <?php if (doubleval($show_pd_customer['filled']) < 1450000000) {?>
                  <option value="1450000000">1.450.000.000 VNĐ</option>
                <?php } ?>
                </select>
                <input type="hidden" name="customer_id" value="<?php echo $_GET['customer_id'] ?>">
              </div>

              <div class="form-group" style="width: 100%;margin-top: 20px">
                <label style="width: 100%" for="email">ĐT</label>
                <input value="0" required="true" style="width: 100%" type="text" name="dt" id="dt" class="form-control" placeholder="ĐT" >
                
              </div>

              <div class="form-group text-center" style="width: 100%;margin-top: 20px">
                <button style="width: 100%" type="submit" onclick="return confirm('Bạn có chắc chắn kích hoạt cho <?php echo $customer['username'] ?> không?')" class="btn btn-warning">Nâng cấp gói đầu tư</button>
              </div>
            </form>

          <?php } ?>
           
          </div>
          <div class="clearfix">
            <div class="" style="margin-top: 35px;">
                <input style="width: 400px;" class="form-control" id="name" type="text" name="name" value="" placeholder="Nhập tên hoặc mã sản phẩm">
                <ul id="suggesstion-box" class="list-group"></ul>
            </div>
          </div>
          <table class="table table-bordered">
              <thead>
                <th>Tên sản phẩm</th>
                <th>Mã sản phẩm</th>
                <th>ĐT</th>
              </thead>
              <tbody class="appen_product_barcode">
                
              </tbody>
           
          </table>
          <div class="scrollTop"></div>
        </div>
        <div class="clearfix" style="margin-top:10px;"></div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function check_barcode(barcode){
    $.ajax({
      url : "index.php?route=pd/product/get_product_barcode&token=<?php echo $_GET['token']; ?>",
      type : "post",
      dataType:"html",
      data : {
          'barcode': barcode
      },
      success : function (result){
        $('.appen_product_barcode').append(result);
        $("html, body").animate({ scrollTop: $(".scrollTop").offset().top }, 500);
      }
    });
  }

  function append_dt(barcode){
    $.ajax({
      url : "index.php?route=pd/product/product_barcode&token=<?php echo $_GET['token']; ?>",
      type : "post",
      dataType:"html",
      data : {
          'barcode': barcode
      },
      success : function (result){
        $('#dt').val(parseInt($('#dt').val()) + parseInt(result));
      }
    });
  }

  function append_dt_search(product_id){
    $.ajax({
      url : "index.php?route=pd/customer/product_id&token=<?php echo $_GET['token']; ?>",
      type : "post",
      dataType:"html",
      data : {
          'product_id': product_id
      },
      success : function (result){
        $('#dt').val(parseInt($('#dt').val()) + parseInt(result));
      }
    });
  }

  $(document).scannerDetection({
    onComplete: function(barcode, qty){
      check_barcode(barcode);
      append_dt(barcode);
    } 
  });
   

   $("#name").keyup(function(){
        $.ajax({
        type: "POST",
        url: "index.php?route=pd/customer/search_product&token=<?php echo $_GET['token'];?>",
        data:'keyword='+$(this).val(),        
        success: function(data){
            $("#suggesstion-box").show();
            $("#suggesstion-box").html(data);
            $("#name").css("background","#FFF");            
        }
        });
    });

   function selectU_product(val,product_id) {
        jQuery('.loading').show();
        $("#name").val('');
        $("#suggesstion-box").hide();
        $.ajax({
        url : "index.php?route=pd/customer/get_product_id&token=<?php echo $_GET['token'];?>",
        type : "post",
        dataType:"html",
        data : {
            'product_id': product_id
        },
        success : function (result){
          append_dt_search(product_id);
          $('.appen_product_barcode').append(result);
          $("html, body").animate({ scrollTop: $(".scrollTop").offset().top }, 500);
          jQuery('.loading').hide();
        }
      
      });
      }
</script>
<style type="text/css" media="screen">
  ul#suggesstion-box li:hover {
    cursor: pointer;
    background-color: #E27225;
    color: #fff;
  }
  ul#suggesstion-box_username li:hover {
    cursor: pointer;
    background-color: #E27225;
    color: #fff;
  }
  ul#suggesstion-box{
     position: absolute;
    width: 270px;
  }
  ul#suggesstion-box_username{
     position: absolute;
    width: 270px;
  }
  #content .panel-body{
    min-height: 530px;
  }
 
</style>
<?php echo $footer; ?>
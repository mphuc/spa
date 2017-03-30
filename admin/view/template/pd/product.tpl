<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Sản phẩm</h1>

  </div>
</div>  
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Sản phẩm</h3><a href="index.php?route=pd/product/create&token=<?php echo $_GET['token']?>"><button class="btn btn-success pull-right" style="margin-top: -5px;"><i class="fa fa-plus" aria-hidden="true"></i> Thêm Sản phẩm</button></a>
    </div>

    <?php 
      if (isset($_SESSION['success'])){?>
      <div class="alert alert-success">
        <strong>Tạo!</strong> thành công.
      </div>
    <?php } 
      unset($_SESSION['success']);
     ?>
    <div class="panel-body">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>TT</th>
            <th>Tên sản phẩm </th>
            <th>Mã sản phẩm</th>
            <th>ĐT</th>
            <th>Giá</th>
            <th>Sự kiện</th>
          
          </tr>
        </thead>
        <tbody id="list">
          <?php $i=0; foreach ($get_nhanvien as $value) { $i++;
            //print_r($value);die;
          ?>
          <tr>
            <td><?php echo $i ?></td>
            <td><?php echo $value['name_product'];?></td>
            <td><?php echo $value['sku'];?></td>
            <td><?php echo $value['dt'];?></td>
            <td><?php echo number_format($value['price']);?></td>
            <td class="text-center">
               <a href="index.php?route=pd/product/edit&id=<?php echo $value['product_id']?>&token=<?php echo $_GET['token'];?>">
                  <button class="btn btn-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
               </a>
               <a onclick="return confirm('Bạn có chắc chắn không?')" href="index.php?route=pd/product/submit_remove&id=<?php echo $value['product_id']?>&token=<?php echo $_GET['token'];?>">
                  <button class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></button>
               </a>

            </td>
            
          </tr>
          <?php } ?>
        </tbody>
      </table>
      
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
}.alertify.ajs-resizable:not(.ajs-maximized) .ajs-dialog {
    min-width: 548px;
    min-height: 270px;
}
</style>


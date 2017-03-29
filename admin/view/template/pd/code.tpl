<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Mã code</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title pull-left">Code</h3>
      
      <div class="clearfix">
          
      </div>
    </div>
    <div class="panel-body row">
        <div class="clearfix" style="margin-top:10px;"></div>
     	<table class="table table-bordered table-hover">
     		<thead>
     			<tr>
     				<th>TT</th>
     				<th>Họ tên</th>
     				<th>Gói</th>
            <th>Code</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
     				<th>Trạng thái </th>
            <th>Ngày tạo</th>
     				<th>Ngày sử dụng </th>
            <th>Username đăng ký</th>
     			</tr>
     		</thead>
     		<tbody>
        <?php 
          $i = 0;
          //print_r($_SESSION); die;
          foreach ($code as $value) {
            $i++;
        ?>
          <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $value['name']; ?></td>
            <td><?php echo number_format($value['package']); ?></td>
            <?php if ($_SESSION['user_id'] == 1 || $_SESSION['user_id'] == 11 ) { ?>
              <td><?php echo $value['code']; ?></td>
            <?php } else { ?>
            <td><?php echo "....".substr($value['code'],-7); ?></td>
            <?php } ?>
            <td><?php echo $value['telephone']; ?></td>
            <td><?php echo $value['address']; ?></td>
            
            <td class="text-center"><i class="fa fa-circle <?php echo ($value['status']==0) ? "red" : "blue"; ?>" aria-hidden="true"></i></td>
           
            <td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])); ?></td>
            <td><?php echo (($value['status']==1) ? date('d/m/Y H:i',strtotime($value['date_update'])): "Chưa sử dụng"); ?></td>
            <td><?php echo (($value['status']==1) ? $value['username']." | ".$value['firstname'] : "Chưa sử dụng") ?></td>
          </tr>
         <?php
          }
         ?>
     		</tbody>
     	</table>
      <?php echo $pagination; ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<style type="text/css">
  i.red{
    color: #466E30;
  }
  i.blue{
    color: red;
  }
</style>
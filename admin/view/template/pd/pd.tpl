<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Provide Donation</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title pull-left">Provide Donation</h3>
      <div class="pull-right">
           <button type="button" id="Statistical" title="Statistical" class="btn btn-primary" data-link="<?php echo $self->url->link('pd/pd/statistical', 'token='.$token.'', 'SSL'); ?> ">Statistical</i></button>
      </div>
      <div class="clearfix">
          
      </div>
    </div>
    <div class="panel-body">
       <div class="navbar-form">
            <select name="filter_status" class="form-control" style="width: 200px;">
                     <option value="*">Select Status</option>
                     <option value="1" <?php echo 1 == $filter_status? 'selected="selected"':'';?>>PD WAITING</option>
                      <option value="2" <?php echo 2 == $filter_status? 'selected="selected"':'';?>>PD MARCHED</option>
                     <option value="3" <?php echo 3 == $filter_status? 'selected="selected"':'';?>>PD FINISH</option>
                  
                </select>
            <button id="btn-filter" type="submit" class="btn btn-default">Filter</button>
        </div>
     	<table class="table table-bordered table-hover">
     		<thead>
     			<tr>
     				<th>NO</th>
     				<th>Username</th>
     				<th>Account holder</th>
            <th>Account number</th>
            <th>Bank name</th>
            <th>Branch bank</th>
     				<th>Amount</th>
                    <th>status</th>
     				<!-- <th>User</th> -->
     			</tr>
     		</thead>
     		<tbody id="list">
          <?php $stt = 1; $sum=0;
                foreach ($allGd as $key => $value): ?>
     			<tr>
                    <td><?php echo $stt; ?></td>
     				<td><?php echo $value['username'] ?></td>
     				<td><?php echo $value['account_holder'] ?></td>
            <td><?php echo $value['account_number'] ?></td>
            <td><?php echo $value['bank_name'] ?></td>
            <td><?php echo $value['branch_bank'] ?></td>
     				<td><?php echo number_format($value['filled']) ?> VNĐ</td>
     				<?php if ($value['status'] == 0) {
                       echo '<td><span class="label label-default">Waiting</span></td>';
                    } ?>
                    <?php if ($value['status'] == 1) {
                       echo '<td><span class="label label-info">March</span></td>';
                    } ?>
                    <?php if ($value['status'] == 2) {
                       echo '<td><span class="label label-success">Finish</span></td>';
                    } ?>
                  
     			</tr>
                <?php $sum = $sum + $value['filled'];  ?>
                 
                        <?php $stt++; endforeach; ?>
                        <tr>
                    <td colspan="6" style="text-align: right; color:red; font-size:20px">
                        Total:
                    </td>
                    <td colspan="6"> <span style="color:red; font-size:20px"><?php echo number_format($sum); ?> VNĐ </span></td>
                </tr>
               
     		</tbody>
            <td colspan="5" style="text-align:right">   <button type="button" id="exportpd" data-toggle="tooltip" title="Export" class="btn btn-primary" >Export PD</i></button></td>
     	</table>
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
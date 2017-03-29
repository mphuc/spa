<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>List PH</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">List PH</h3>
    </div>
    <div class="panel-body">
        <div class="pull-left row">
            <div class="form-group">
            <div class="col-md-3">
                <label class=" control-label" for="input-date_create">Username</label>
                 <input style="margin-top: 5px;" type="text" id="username" class="form-control" placeholder="Username">
                 <ul id="suggesstion-box" class="list-group"></ul>
            </div>
            <div class="col-sm-2 input-group date">
                 <label class=" control-label" for="input-date_create">Date</label>
                 <input style="margin-top: 5px;" type="text" id="date_day" name="date_create" value="<?php echo date('d-m-Y')?>" placeholder="Ngày đăng ký" data-date-format="DD-MM-YYYY" id="date_create" class="form-control">
                 <span class="input-group-btn">
                 <button style="margin-top:28px" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                 </span>
              </div>
              <div class="col-sm-2">
                <button id="submit_date" style="margin-top: 28px;" type="button" class="btn btn-success">Filter</button>
              </div>
            
            <div class="col-sm-2 input-group date">
                 <label class=" control-label" for="input-date_create">Start Data</label>
                 <input style="margin-top: 5px;" type="text" id="start_date" name="date_create" value="<?php echo date('d-m-Y')?>" data-date-format="DD-MM-YYYY" id="date_create" class="form-control">
                 <span class="input-group-btn">
                 <button style="margin-top:28px" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                 </span>
              </div>
              <div class="col-sm-2 input-group date">
                 <label class=" control-label" for="input-date_create">End Data</label>
                 <input style="margin-top: 5px;" type="text" id="end_date" name="date_create" value="<?php echo date('d-m-Y')?>" data-date-format="DD-MM-YYYY" id="date_create" class="form-control">
                 <span class="input-group-btn">
                 <button style="margin-top:28px" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                 </span>
              </div>
              <div class="col-sm-1">
                <button id="submit_fillter" style="margin-top: 28px;" type="button" class="btn btn-success">Export</button>
                
              </div>
            </div>
        </div>
        <div class="clearfix" style="margin-bottom: 28px;"></div>
        <!-- <a href="index.php?route=report/exportCustomer/xuatpin&token=<?php echo $_GET['token'];?>" style="margin-bottom:10px; float:right">
            <div class="btn btn-success pull-right">Xuất Excel</div>
        </a> -->
     	<table class="table table-bordered table-hover">
     		<thead>
     			<tr>
     				<th>TT</th>
                    <!-- <th>ID</th> -->
     				<th>Username</th>
     				<th>Full name</th>
     				<th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                     <th>Time-Remain</th>
     			</tr>
     		</thead>
     		<tbody id="result_date"> 
               
                <?php $stt = 0;
                foreach ($pin as $value) { $stt ++?>
               
                  <tr>
                    <td><?php echo $stt; ?></td>
                    <!-- <td><?php echo $value['customer_id'] ?></td> -->
                    <td><?php echo $value['username'] ?></td>
                    <td><?php echo $value['account_holder'] ?></td>
                    <td><?php echo number_format($value['filled']) ?> VNĐ</td>
                    <td><?php 

                    if ($value['status'] == 0) {
                        echo "<span class='label label-default'>Watiing</span>";
                    }
                    if ($value['status'] == 1) {
                        echo "<span class='label label-info'>Matched</span>";
                    }
                    if ($value['status'] == 2) {
                        echo "<span class='label label-success'>Finish</span>";
                    }
                    if ($value['status'] == 3) {
                        echo "<span class='label label-danger'>Report</span>";
                    }
                    ?> </td>
                    
                   
                    <td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])) ?></td>
                    <td><span style="color:red; font-size:15px;" class="text-danger countdown" data-countdown="<?php echo $value['date_finish']; ?>">
                     </span> </td>
                </tr>  
              
                <?php } ?>
                
               
     		</tbody>
     	</table>
        <?php echo $pagination ?>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('#submit_date').click(function(){
        var date_day = $('#date_day').val();
        $.ajax({
            url : "<?php echo $load_pin_date ?>",
            type : "post",
            dataType:"text",
            data : {
                'date' : date_day
            },
            success : function (result){
                jQuery('#result_date').html(result);
            }
        });
    });
    $('.date').datetimepicker({
        pickTime: false
    });
    jQuery('#username').on("input propertychang", function() {
        jQuery.ajax({
        type: "POST",
        url: "<?php echo $getaccount;?>",
        data:{
            'keyword' : $(this).val()
        },
        success: function(data){
            jQuery("#suggesstion-box").show();
            jQuery("#suggesstion-box").html(data);
            jQuery("#p_node").css("background","#FFF");            
        }
        });
    });
    function selectU(val) {
        $('.loading').show();
        $("#username").val(val);
        $("#suggesstion-box").hide();
        jQuery.ajax({
            type: "POST",
            url: "<?php echo $show_gh_username;?>",
            data:{
                'username' : val
            },
            success: function(data){
               $('.loading').hide();
                    jQuery('#result_date').html(data);           
                }
            });
    }
    jQuery('#submit_fillter').click(function(){
        window.location.replace("<?php echo $export; ?>&start_date="+jQuery('#start_date').val()+"&end_date="+jQuery('#end_date').val());
    });
</script>
<?php echo $footer; ?>
<style type="text/css">
    .panel-body{
        min-height: 500px;
    }
   ul#suggesstion-box,ul#suggesstion {
        position: absolute;
        width: 94%;
        background: #fff;
        color: #000;
    }
    #suggesstion-box li.list-group-item,#suggesstion li.list-group-item {
        cursor: pointer;
    }
    #suggesstion-box li.list-group-item:hover,#suggesstion li.list-group-item:hover {
        background: #626f78;
        cursor: pointer;
        color: #fff;
    }
</style>
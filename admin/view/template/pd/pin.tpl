<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Pin</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Pin</h3>
    </div>
    <div class="panel-body">
        <div class="pull-left">
            <div class="form-group row">
             <div class="col-md-3">
                <label class=" control-label" for="input-date_create">Username</label>
                 <input style="margin-top: 5px;" type="text" id="username" class="form-control"  placeholder="Username">
                     <ul id="suggesstion-box" class="list-group"></ul>
              </div>
            <div class="col-sm-3 input-group date">
                 <label class=" control-label" for="input-date_create">Date</label>
                 <input style="margin-top: 5px;" type="text" id="date_day" name="date_create" value="<?php echo date('d-m-Y')?>" placeholder="Ngày đăng ký" data-date-format="DD-MM-YYYY" id="date_create" class="form-control">
                 <span class="input-group-btn">
                 <button style="margin-top:28px" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                 </span>
              </div>
              <div class="col-sm-3">
                <button id="submit_date" style="margin-top: 28px;" type="button" class="btn btn-success">Filter</button>
              </div>
              <div class="col-md-2">
              <span class="url_xuatpin" href="" style="margin-bottom:10px; float:right;margin-top: 28px;">
                    <div class="btn btn-success pull-right">Export Excel</div>
                </span>
              </div>
            </div>
        </div>
        
     	<table class="table table-bordered table-hover">
     		<thead>
     			<tr>
     				<th>TT</th>
     				<th>Username</th>
     				<th>Wallet</th>
     				<th>Pin</th>
                    <th>Status</th>
                    <th>Date</th>
     			</tr>
     		</thead>
     		<tbody id="result_date"> 
               
                <?php $stt = 0;
                foreach ($pin as $value) { $stt ++?>
                <?php if ($value['confirmations'] == 0) { ?>
                  <tr>
                    <td><?php echo $stt; ?></td>
                    <td><?php echo $value['username'] ?></td>
                    <td><?php echo $value['input_address'] ?></td>
                    <td><?php echo $value['pin'] ?></td>
                    <td><?php echo ($value['confirmations'] == 0) ? "<span class='label label-warning'>Watting</span>" : "<span class='label label-success'>Delivered</span>" ?></td>
                   
                    <td><?php echo date('d/m/Y H:i',strtotime($value['date_created'])) ?></td>
                    
                </tr>  
               <?php  } else{ ?>
     			<tr style="
    background: #c1e893; */
">
                    <td><?php echo $stt; ?></td>
     				<td><?php echo $value['username'] ?></td>
     				<td><?php echo $value['input_address'] ?></td>
                    <td><?php echo $value['pin'] ?></td>
                    <td><?php echo ($value['confirmations'] == 0) ? "<span class='label label-warning'>Watting</span>" : "<span class='label label-success'>Delivered</span>" ?></td>
                   
     				<td><?php echo date('d/m/Y H:i',strtotime($value['date_created'])) ?></td>
     				
     			</tr>
                <?php } ?>
                <?php } ?>
                
               
     		</tbody>
     	</table>
        <?php echo $pagination ?>
    </div>
  </div>
</div>
<script type="text/javascript">
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
        $('.pagination').hide();
        $("#username").val(val);
        $("#suggesstion-box").hide();
        jQuery.ajax({
            type: "POST",
            url: "<?php echo $load_pin_username;?>",
            data:{
                'username' : val
            },
            success: function(data){
               $('.loading').hide();
                    jQuery('#result_date').html(data);           
                }
            });
    }
    $('#submit_date').click(function(){
        $('.loading').show();
        $('.pagination').hide();
        var date_day = $('#date_day').val();
        $.ajax({
            url : "<?php echo $load_pin_date ?>",
            type : "post",
            dataType:"text",
            data : {
                'date' : date_day
            },
            success : function (result){
                $('.loading').hide();
                jQuery('#result_date').html(result);
            }
        });
    });
    $('.date').datetimepicker({
    pickTime: false
});
    jQuery('.url_xuatpin').click(function(){
        var date_day = $('#date_day').val();
        //alert(date_day);
        //return false;
        window.location.replace("index.php?route=report/exportCustomer/xuatpin&date="+date_day+"&token=<?php echo $_GET['token'];?>");
    })

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
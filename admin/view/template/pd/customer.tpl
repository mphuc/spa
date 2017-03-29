<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
<div class="page-header">
  <div class="container-fluid">
    <h1>Thành viên</h1>

  </div>
</div>
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title pull-left">Thành viên</h3>
      
      <div class="clearfix">
          
      </div>
    </div>
    <div class="panel-body">
       <div class="navbar-form">
        <div class="row">
          <div class="row">
            <div class="col-md-3">
                <input style="width: 270px;" class="form-control" id="name" type="text" name="name" value="" placeholder="Họ tên">
                <ul id="suggesstion-box" class="list-group"></ul>
            </div>
            <div class="col-md-3" style="margin-left:10px;">
              <input style="width: 270px; " type="text" autocomplete="off" class="form-control" id="p_node" name="p_node" placeholder="Username">
            <ul id="suggesstion-box_username" class="list-group"></ul>
            </div>
              
           
            <div class="col-sm-3 input-group date">
                 
                 <input style="margin-left:10px;" type="text" id="date_day" name="date_create" value="<?php echo date('d-m-Y')?>" placeholder="Ngày đăng ký" data-date-format="DD-MM-YYYY" id="date_create" class="form-control">
                 <span class="input-group-btn">
                 <button style="margin-left:10px" type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                 </span>
              </div>
              <div class="col-sm-1">
                <button id="submit_date" style=";" type="button" class="btn btn-success">Lọc</button>
              </div>
            </div>
      
           
          </div>
        </div>
        <div class="clearfix" style="margin-top:10px;"></div>
     	<table class="table table-bordered table-hover">
     		<thead>
     			<tr>
     				<th>TT</th>
     				<th>Username</th>
     				<th>Họ Tên</th>
            <th>Gói đầu tư</th>
           
            <th>Ngày tham gia</th>
            <th>Kích hoạt</th>
     				<th>Người bảo trợ</th>
            <th>Lịch sử</th>
     				<th>Sửa</th>
            <th>Đầu tư</th>
     			</tr>
     		</thead>
     		<tbody id="list">
          <?php $i=0; foreach ($customer as $value) { $i++;
            $get_filled_by_id = $self -> model_pd_registercustom -> get_filled_by_id($value['customer_id']);
          ?>
          <tr>
            <td><?php echo $i ?></td>
            <td><?php echo $value['username'];?></td>
            <td><?php echo $value['account_holder'];?></td>
            <td><?php echo number_format($get_filled_by_id['sum_filled']);?></td>
            
            <td><?php echo $value['date_added'];?></td>
            <td class="text-center">
              <?php if ($value['level'] >= 2) { ?>
                <i class="fa fa-circle" style="color: #4caf50" aria-hidden="true"></i>
              <?php } else { ?>
                  <i class="fa fa-circle" style="color: red" aria-hidden="true"></i>
              <?php } ?>
            </td>
            <td><?php echo $self->getCustomer($value['p_node']);?></td>
            <td class="text-center"><a href="<?php echo $self->url->link('pd/history/view_history&customer_id='.$value['customer_id'].'&token='.$self->session->data['token']);?>"><button class="btn btn-success"><i class="fa fa-external-link" aria-hidden="true"></i></button></a></td>
            <td class="text-center"><a href="<?php echo $self->url->link('pd/history/edit_user&customer_id='.$value['customer_id'].'&token='.$self->session->data['token']);?>"><button class="btn btn-primary  "><i class="fa fa-eyedropper" aria-hidden="true"></i></button></a></td>   
            <td class="text-center"><a href="<?php echo $self->url->link('pd/customer/dautu_user&customer_id='.$value['customer_id'].'&token='.$self->session->data['token']);?>"><button class="btn btn-primary  "><i class="fa fa-university" aria-hidden="true"></i></button></a></td>   
          </tr>
          <?php } ?>
     		</tbody>
     	</table>
      <?php echo $pagination ?>
    </div>
  </div>
</div>
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
<script type="text/javascript">
  jQuery('.date').datetimepicker({
      pickTime: false
  });
  $('#submit_date').click(function(){
      jQuery('.loading').show();
      setTimeout(function(){ 
        var date_day = $('#date_day').val();
        $.ajax({
            url : "<?php echo $load_date ?>",
            type : "post",
            dataType:"text",
            data : {
                'date' : date_day
            },
            success : function (result){
                jQuery('#list').html(result);
                jQuery('.loading').hide();
            }
        });
      }, 100);
  });
  jQuery('#btn-filter').click(function(){
    jQuery('.loading').show();
    setTimeout(function(){ 
      var name = jQuery('#name').val();
      
      $.ajax({
        url : "<?php echo $link_search; ?>",
        type : "post",
        dataType:"html",
        data : {
            'name': name
        },
        success : function (result){
          $('#list').html(result);
          jQuery('.loading').hide();
        }
      
      });
    }, 100);
        
    }); 
    $("#name").keyup(function(){

        $.ajax({
        type: "POST",
        url: "<?php echo $getaccount;?>",
        data:'keyword='+$(this).val(),        
        success: function(data){
            $("#suggesstion-box").show();
            $("#suggesstion-box").html(data);
            $("#name").css("background","#FFF");            
        }
        });
    });
    $("#p_node").keyup(function(){
        $.ajax({
        type: "POST",
        url: "<?php echo $getaccount_username;?>",
        data:'keyword='+$(this).val(),        
        success: function(data){
            $("#suggesstion-box_username").show();
            $("#suggesstion-box_username").html(data);
            $("#p_node").css("background","#FFF");            
        }
        });
    });
    function selectU(val) {
         jQuery('.loading').show();
        $("#name").val(val);
        $("#suggesstion-box").hide();
        $.ajax({
        url : "<?php echo $link_search; ?>",
        type : "post",
        dataType:"html",
        data : {
            'name': val
        },
        success : function (result){
          $('#list').html(result);
          jQuery('.loading').hide();
        }
      
      });
    }
    function selectU_username(val) {
        jQuery('.loading').show();
        $("#p_node").val(val);
        $("#suggesstion-box_username").hide();
        $.ajax({
        url : "<?php echo $link_search_username; ?>",
        type : "post",
        dataType:"html",
        data : {
            'name': val
        },
        success : function (result){
          $('#list').html(result);
          jQuery('.loading').hide();
        }
      
      });
    }
    
</script>


<?php echo $footer; ?>
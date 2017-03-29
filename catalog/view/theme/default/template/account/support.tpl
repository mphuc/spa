<?php 
   $self -> document -> setTitle("Hỗ trợ"); 
   echo $self -> load -> controller('common/header'); 
   //echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="container">
<!-- Start .content -->
    <h3 class="text-center" style="color: #7d3c93">Hỗ trợ</h3>
      <div class="col-md-10 col-md-push-1 form_support">
          <form class="form" id="support" method="post" action="index.php?route=account/support/sendmail">

            <div class="">
              <label for="name">Tiêu đề</label>
              <input type="text" name="name" id="name" placeholder="Tiêu đề" required />
              
           </div>                               
            

          
            <div class="">
              <label for="name">Nội dung hỗ trợ</label>
              <textarea name="content" placeholder="Nội dung hỗ trợ" required ></textarea>
           </div>
            <div class="">
            <label for="name">capcha</label>
            <input style="width: 150px;float: left;" autocomplete="off" type="text" name="capcha" placeholder="Capcha" id="input-password" value="" required />
            <img style="float: left; margin-left: 15px; margin-top: 2px;" class="img_capcha" style="float: right" src="captcha_code.php"/>
          </div>
          
            <div class="clearfix"></div>
           
              <input type="submit" style="margin-top: 50px; margin-bottom: 60px;" value="Gửi" />
           
          </form>
      </div>
    
  <div class="clearfix" style="margin-top: 80px;"></div>
</div>
<script type="text/javascript">
    if (location.hash === '#success') {
        alertify.set('notifier', 'delay', 100000000);
        alertify.set('notifier', 'position', 'top-right');
        alertify.success('Send mail successfull !!!');
    }
     if (location.hash === '#error') {
      var html = '<div class="col-md-12">';
        html += '<p class="text-center" style="font-size:23px;text-transform: uppercase;height: 20px;color:red">ERROR !</p><p class="text-center" style="font-size:20px;height: 20px">Faild Capcha</p>';
        html += '<p style="margin-top:30px;font-size:16px"></p>';
        html += '</div>';
        alertify.alert(html, function(){
           
        });
    }
</script>
      
<?php echo $self->load->controller('common/footer') ?>
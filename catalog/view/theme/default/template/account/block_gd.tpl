<?php 
   $self -> document -> setTitle('Block'); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="main-content">
    <!-- Start .content -->
    <div class="content" style="">
        <div class="row">
            <!-- .row start -->
            <div class="col-md-12">
                <!-- col-md-12 start here -->
                <div class="panel panel-default" id="dash_0">
                    <!-- Start .panel -->
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-align-justify"></i>Your account has been locked!</h4>
                    </div>
                    <div class="panel-body form-horizontal group-border stripped">
                        <div class="form-group">
                            <div class="col-lg-12 col-md-12">

                                 <div class="input-group input-icon">
                                    <div class="panel-body">
                                    
            <div class="block_panel text-center">
                <!-- Start .panel -->
                <div class="panel-body p0">
                    <h1 class="text-center">Account FROZEN </h1>
                     <?php $wallet = $self -> return_wallet_gd(); ?>
                    <p class="text-center s20">Your account has been locked at <code><?php echo date("d/m/Y", strtotime($wallet['date'])); ?></code>.</p>
                    <p class="text-center s20">Reason: <?php echo $wallet['description'] ?>.</p>
                    <p class="text-center s20">To unlock your account will be penalized as follows.</p>
                  
                    <p class="text-center s20">Deduct R-Wallet amount: <code><?php echo number_format($wallet['r_wallet']) ?> VND</code></p>
                    <p class="text-center s20">Deduct C-Wallet amount: <code><?php echo number_format($wallet['c_wallet']) ?> VND</code></p>
                   

                    <div class="divider-dashed mb25"></div>
                    <div class="col-md-6 col-md-offset-3 mb10">
                        <div class="btn-group btn-group-vertical">
                          <div class="controls">
                             <form id="frmunlockGD" method="GET" action="<?php echo $self->url->link('account/block/unlock_gd', '', 'SSL'); ?>">
                                      <button type="submit" class="btn btn-primary"><i class="fa fa-unlock"></i> Unlock Now</button>
                                  </form>
                                                            <br>
                                                                  <a href="logout.html" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout</a>
                                                        </div>
                            
                         
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- End .panel -->
    

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
        $('#frmunlockGD').on('submit', function(){
        var self = $(this);
        alertify.confirm('<p class="text-center" style="font-size:20px;color: black;height: 20px">Make sure your choice is correct !</p>',
          function(){
            window.funLazyLoad.start();
            setTimeout(function(){
                self.ajaxSubmit({
                    success : function(result) {
                  
                        result = $.parseJSON(result);
                         if (result.ok == 1){
                         var xhtml = '<p class="text-center" style="font-size:20px;color: black;height: 20px">Congratulations Your Unlock is Confirmed!</p>';
                         alertify.alert(xhtml, function(){
                            window.location.href=result.link;
                          
                           });
                         window.funLazyLoad.reset();
                         return false;
                        }
                        if (result.ok == -1){
                         var xhtml = '<p class="text-center" style="font-size:20px;color: black;height: 20px">Your Unlock is UnConfirmed!</p>';
                         alertify.alert(xhtml, function(){
                             location.reload(true);
                           });
                         window.funLazyLoad.reset();
                         return false;
                        }
                       
                        
                    }
                });
            
            }, 200);
          },
          function(){
        });
        return false;
    });
</script>
    <?php echo $self->load->controller('common/footer') ?>
<?php 
   $self -> document -> setTitle('Happymoney Thong bao'); 
   echo $self -> load -> controller('common/header'); 
   echo $self -> load -> controller('common/column_left'); 
   ?>
<div class="main-content">
<!-- Start .content -->
  <div class="content" style="">
     
           <!-- col-md-12 start here -->
          
      <div class="row">
         <div class="col-md-12">
            
         </div>
      </div>
      <div class="widget-content" style="padding:10px">
         <div class="" style="min-height:600px;">
            <div class="">
              
               <div class="options pull-right">
                  <div class="btn-toolbar">
                     <a href="<?php echo $self->url->link('account/dashboard', '', 'SSL'); ?>" class="btn btn-warning"><i class="fa fa-fw fa-undo"></i>Back</a>
                   
                  </div>
               </div>
               <div class="clearfix"></div>
            </div>
            <div class="panel-body panel-custom">
              <div class="col-md-12">
                
                 <div class="blog-detail">
                  <?php echo html_entity_decode($detail_articles['description'], ENT_QUOTES, 'UTF-8')  ?>
                  </div>
              </div>
               </div>

         </div>
      </div>
    
   </div>
   <!-- End Row -->
   <!-- End row -->
</div>

<?php echo $self->load->controller('common/footer') ?>
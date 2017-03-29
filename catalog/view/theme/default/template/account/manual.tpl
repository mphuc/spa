<?php 
   $self -> document -> setTitle('Manual'); 
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
      <h4 class="panel-title"><i class="fa fa-align-justify"></i>Manual</h4>
   </div>
   <div class="panel-body form-horizontal group-border stripped">
      <div class="form-group">
         <div class="col-lg-12 col-md-12">
            <div class="input-group input-icon file-upload">
               <div class="widget-content" style="padding:10px">
                  <div class="">
                  <?php 
                     if ($_SESSION['language_id'] == "vietnamese") {
                  ?>
                    <?php for ($i=1;$i<62;$i++) { ?>
                       <img style="margin-top: 10px;" class="img-responsive" src="catalog/view/theme/default/images/manual/00<?php echo $i;?>.jpg" src="" alt="">
                     <?php  }?>
                    <?php } else { ?>
                    
                     <?php for ($i=1;$i<62;$i++) { ?>
                       <img style="margin-top: 10px;" class="img-responsive" src="catalog/view/theme/default/images/manual_en/00<?php echo $i;?>.jpg" src="" alt=""> 
                     <?php  }?>
                     <?php  } ?>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- End Row -->
   <!-- End row -->
</div>
<script type="text/javascript">
   $(document).ready(function() {
       $('#datatable').dataTable();
   } );
</script>
<?php echo $self->load->controller('common/footer') ?>
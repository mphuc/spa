<?php 
   $self -> document -> setTitle('Blog'); 
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
      <h4 class="panel-title"><i class="fa fa-align-justify"></i>Blog</h4>
   </div>
   <div class="panel-body form-horizontal group-border stripped">
      <div class="form-group">
         <div class="col-lg-12 col-md-12">
            <div class="input-group input-icon file-upload">
               <div class="widget-content" style="padding:10px">
                <div class="col-md-8 col-md-push-2">
                  
                   
                     
            
                  <div class="itemss">
                    <h2 class="post-title entry-title">
                      <a href="" title="Smooth Sweet Tea with Cookies">
                      <?php echo $value['title'] ?>
                      </a>
                    </h2>              
                    <div class="post-info">
                      <span class="author-info">
                      <span class="vcard" itemprop="author" itemscope="itemscope">
                      <span class="fn">
                      <i class="fa fa-user"></i>
                      <a class="g-profile" rel="author" title="author profile">
                      <span style="margin-right: 10px;" itemprop="name">

                        <?php if ($value['type'] == 1) echo "Admin"; else echo $value['username'] ?>
                        
                        </span>
                      </a>
                      </span>
                      </span>
                      </span>
                      <span class="post-timestamp">
                      
                      <i class="fa fa-calendar-o"></i>
                      <a class="timestamp-link" rel="bookmark" title="permanent link"><abbr class="published updated" itemprop="datePublished"><?php echo date('d/m/Y H:i:s',strtotime($value['date_added'])) ?></abbr></a>
                      </span>
                      <span class="comment-info">
                      <i class="fa fa-comments-o"></i>
                      <a title="Comments Count"></a>
                      </span>
                    </div>
                    <div class="snippetss" style="margin-top: 30px;">
                      <?php 
                       
                        echo $value['description'] ?></div>
                  </div>
                  
                    
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
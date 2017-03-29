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
                  <a href="create_blog.html">
                    <span class="nav-item-text btn btn-success pull-right">Create Blog</span>
                  </a>
                    <ul class="nav nav-tabs" style="margin-bottom: 30px;">
                      <li class="active"><a data-toggle="tab" href="#home">Admin</a></li>
                      <li><a data-toggle="tab" href="#menu1">Customer</a></li>
                      
                    </ul>
                  <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                  <?php foreach ($getBlogById_admin as $key => $value) { ?>
                  
                  <div class="item">
                    
                    <h2 class="post-title entry-title">
                      <a href="view_blog.html&token=<?php echo $value['id'];?>" title="Smooth Sweet Tea with Cookies">
                      <?php echo $value['title'] ?>
                      </a>
                    </h2>              
                    <div class="post-info">
                      <span class="author-info">
                      <span class="vcard" itemprop="author" itemscope="itemscope">
                      <span class="fn">
                      <i class="fa fa-user"></i>
                      <a class="g-profile" rel="author" title="author profile">
                      <span style="margin-right: 10px;" itemprop="name">Admin</span>
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
                    <div class="snippets">
                      <?php 
                        if (strlen($value['description']) > 300) $cham = "..."; else $cham = "";
                        echo substr($value['description'],0,300)." ".$cham ?></div>
                  </div>
                  <?php } ?>
                    
                  
                    </div>
                    <div id="menu1" class="tab-pane fade">
                      <?php foreach ($pds as $key => $value) { ?>
            
                  <div class="item">
                    <?php if ($value['customer_id'] == $self -> session->data['customer_id']) {  ?>
                      <a href="edit_blog.html&token=<?php echo $value['id']; ?>" class="edit_blog">
                        <span class="nav-item-text btn btn-success">Edit</span>
                      </a>
                    <?php } ?>
                    <h2 class="post-title entry-title">
                      <a href="view_blog.html&token=<?php echo $value['id'];?>" title="Smooth Sweet Tea with Cookies">
                      <?php echo $value['title'] ?>
                      </a>
                    </h2>              
                    <div class="post-info">
                      <span class="author-info">
                      <span class="vcard" itemprop="author" itemscope="itemscope">
                      <span class="fn">
                      <i class="fa fa-user"></i>
                      <a class="g-profile" rel="author" title="author profile">
                      <span style="margin-right: 10px;" itemprop="name"><?php echo $value['username'] ?></span>
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
                    <div class="snippets">
                      <?php 
                        if (strlen($value['description']) > 300) $cham = "..."; else $cham = "";
                        echo substr($value['description'],0,300)." ".$cham ?></div>
                  </div>
                  <div class="clearfix"></div>
                  <?php } ?>
                  <div class="clearfix"></div>
                  <?php echo $pagination ?>
                    </div>
                    
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
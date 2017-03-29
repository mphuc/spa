<?php echo $self->load->controller('home/page/header'); ?>
      
         <!-- #site-navigation -->
         <div id="content" class="site-content">
            <div class="big-title" style="background-image: url('catalog/view/theme/default/images/bg01.jpg')">
               <div class="container">
                  <h1 class="entry-title" itemprop="headline">FAQ</h1>
                  <div class="breadcrumb">
                     <div class="container">
                        <ul class="tm_bread_crumb">
                           <li class="level-1 top"><a href="about">Home</a></li>
                           <li class="level-2"><a href="../index.html">FAQ</a></li>
                           
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <div class="container">
               <div class="row">
                  <div class="col-md-12" style="margin-bottom: 20px;">
                     <?php for ($i=1;$i<62;$i++) { ?>
                       <img style="margin-top: 10px;" class="lazy" src="catalog/view/theme/default/images/manual/00<?php echo $i;?>.jpg" src="" alt="">
                     <?php  }?>
                    
                  </div>
               </div>
            </div>
         </div>
        <script>
       $(window).on('ajaxComplete', function() {
           setTimeout(function() {
               $(window).lazyLoadXT();
           }, 50);
       });
    </script>

         <!-- #content -->
        
<?php echo $self->load->controller('home/page/footer'); ?>  
<?php echo $self->load->controller('home/page/header'); ?>

         <!-- #site-navigation -->
         <div id="content" class="site-content">
            <div class="big-title" style="background-image: url('catalog/view/theme/default/images/bg01.jpg')">
               <div class="container">
                  <h1 class="entry-title" itemprop="headline">Contact</h1>
                  <div class="breadcrumb">
                     <div class="container">
                        <ul class="tm_bread_crumb">
                           <li class="level-1 top"><a href="../index.html">Home</a></li>
                           <li class="level-2 sub tail current">Contact</li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <div class="content">
                        <article id="post-22">
                           <div class="entry-content">
                              <div data-vc-full-width="true" data-vc-full-width-init="false" data-vc-stretch-content="true" class="vc_row wpb_row vc_row-fluid vc_custom_1435654014372 vc_row-no-padding">
                                 <div class="wpb_column vc_column_container vc_col-sm-12">
                                    <div class="vc_column-inner ">
                                       <div class="wpb_wrapper">
                                          <div id="map-canvas" class="thememove-gmaps"  data-address="40.7590615,-73.969231"
                                             data-height="480"
                                             data-width="100%"
                                             data-zoom_enable=""
                                             data-zoom="16"
                                             data-map_type="roadmap"
                                             data-map_style="style1"
                                             ></div>
                                          <script type="text/javascript">
                                             jQuery( document ).ready( function ( $ ) {
                                             
                                                var gmMapDiv = $( "#map-canvas" );
                                             
                                                (
                                                   function ( $ ) {
                                             
                                                      if ( gmMapDiv.length ) {
                                             
                                                         var gmMarkerAddress = gmMapDiv.attr( "data-address" );
                                                         var gmHeight = gmMapDiv.attr( "data-height" );
                                                         var gmWidth = gmMapDiv.attr( "data-width" );
                                                         var gmZoomEnable = gmMapDiv.attr( "data-zoom_enable" );
                                                         var gmZoom = gmMapDiv.attr( "data-zoom" );
                                             
                                                         gmMapDiv.gmap3( {
                                                            action: "init",
                                                            marker: {
                                                               address: gmMarkerAddress,
                                                               options: {
                                                                                          icon: "http://transport.iontach.biz/wp-content/themes/tm_transport/images/map-marker.png",
                                                                                       },
                                                                                 },
                                                            map: {
                                                               options: {
                                                                  zoom: parseInt( gmZoom ),
                                                                  zoomControl: true,
                                                                  mapTypeId: google.maps.MapTypeId.ROADMAP,
                                                                  mapTypeControl: false,
                                                                  scaleControl: false,
                                                                  scrollwheel: gmZoomEnable == 'enable' ? true : false,
                                                                  streetViewControl: false,
                                                                  draggable: true,
                                                                                                                  styles: [{"featureType":"all","elementType":"all","stylers":[{"saturation":-100},{"gamma":0.5}]}],
                                                                                                               }
                                                            }
                                                         } ).width( gmWidth ).height( gmHeight );
                                                      }
                                                   }
                                                )( jQuery );
                                             } );
                                          </script>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="vc_row-full-width vc_clearfix"></div>
                              <div class="vc_row wpb_row vc_row-fluid vc_custom_1435658845572">
                                 <div class="wpb_column vc_column_container vc_col-sm-3">
                                    <div class="vc_column-inner ">
                                       <div class="wpb_wrapper">
                                          <div class="wpb_widgetised_column wpb_content_element">
                                             <div class="wpb_wrapper">
                                                <aside id="text-6" class="widget widget_text">
                                                   <h3 class="widget-title">Contact</h3>
                                                   <div class="textwidget">
                                                      <div class="office">
                                                         <p><i class="fa fa-map-marker"></i> 14 Tottenham Road, London, England.</p>
                                                         <p><i class="fa fa-envelope"></i> admin@iontach.biz</p>
                                                         <p><i class="fa fa-envelope"></i> info@iontach.biz</p>
                                                         
                                                         <p><i class="fa fa-clock-o"></i> Mon - Sat: 9:00 - 18:00</p>
                                                      </div>
                                                   </div>
                                                </aside>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="wpb_column vc_column_container vc_col-sm-9">
                                    <div class="vc_column-inner ">
                                       <div class="wpb_wrapper">
                                          <div class="vc_custom_heading style3 vc_custom_1436256355374" >
                                             <h2 style="color: #111111;text-align: left" >FILL CONTACT FORM</h2>
                                          </div>
                                          <div class="vc_custom_heading" >
                                             <p style="color: #858585;text-align: left" >We love to listen and we are eagerly waiting to talk to you regarding your project. Get in touch with us if you have any queries and we will get back to you as soon as possible.</p>
                                          </div>
                                          <div role="form" class="wpcf7" id="wpcf7-f74219-p22-o1" lang="en-US" dir="ltr">
                                             <div class="screen-reader-response"></div>
                                             <form action="" method="post" class="wpcf7-form" novalidate="novalidate">
                                                <div style="display: none;">
                                                   <input type="hidden" name="_wpcf7" value="74219" />
                                                   <input type="hidden" name="_wpcf7_version" value="4.5" />
                                                   <input type="hidden" name="_wpcf7_locale" value="en_US" />
                                                   <input type="hidden" name="_wpcf7_unit_tag" value="wpcf7-f74219-p22-o1" />
                                                   <input type="hidden" name="_wpnonce" value="6c4df322dd" />
                                                </div>
                                                <div class="form2">
                                                   <div class="row">
                                                      <div class="col-md-6">
                                                         <span class="wpcf7-form-control-wrap your-name"><input type="text" name="your-name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="Your name here" /></span><br />
                                                         <span class="wpcf7-form-control-wrap your-email"><input type="email" name="your-email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="Your email" /></span>
                                                      </div>
                                                      <div class="col-md-6">
                                                         <span class="wpcf7-form-control-wrap your-subject"><input type="text" name="your-subject" value="" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false" placeholder="Subject" /></span><br />
                                                         <span class="wpcf7-form-control-wrap your-phone"><input type="text" name="your-phone" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="Phone" /></span>
                                                      </div>
                                                      <div class="col-md-12">
                                                         <span class="wpcf7-form-control-wrap your-message"><textarea name="your-message" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false" placeholder="Your message"></textarea></span>
                                                      </div>
                                                      <div class="col-md-12">
                                                         <input type="submit" value="Send Message" class="wpcf7-form-control wpcf7-submit" />
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="wpcf7-response-output wpcf7-display-none"></div>
                                             </form>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="vc_row-full-width vc_clearfix"></div>
                           </div>
                           <!-- .entry-content -->
                        </article>
                        <!-- #post-## -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- #content -->
<?php echo $self->load->controller('home/page/footer'); ?>  
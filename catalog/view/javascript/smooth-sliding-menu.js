
	/**********Vertical Slide*********/
	

/**********Horizontal Slide for i-phone*********/

jQuery(document).ready(function(){
  jQuery(".fa-list-ul").on("click", function(e){
    e.preventDefault();
      var distance = jQuery('.page-content').css('left');
      var elm_class = jQuery(".icon-reorder").attr("class");
      if(elm_class=='icon-reorder') {
		jQuery(this).addClass("open");
        jQuery('.left-nav').animate({width: 'toggle'});
      } else {
		 jQuery(".icon-reorder").removeClass("open");
        jQuery('.left-nav').animate({width: 'toggle'});
      }
  });
});
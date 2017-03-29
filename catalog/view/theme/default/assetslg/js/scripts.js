
jQuery(document).ready(function() {
	
    /*
        Fullscreen background
    */
    $.backstretch([
                    "catalog/view/theme/default/assetslg/img/backgrounds/2.jpg"
	              , "catalog/view/theme/default/assetslg/img/backgrounds/3.jpg"
                  , "catalog/view/theme/default/assetslg/img/backgrounds/7.jpg"
                  , "catalog/view/theme/default/assetslg/img/backgrounds/8.jpg"
                 
                  , "catalog/view/theme/default/assetslg/img/backgrounds/5.jpg"
	              , "catalog/view/theme/default/assetslg/img/backgrounds/1.jpg"
                  , "catalog/view/theme/default/assetslg/img/backgrounds/4.jpg"
                 
	             ], {duration: 3000, fade: 1000});
    
    /*
        Form validation
    */
    $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    
    $('.login-form').on('submit', function(e) {
    	
    	$(this).find('input[type="text"], input[type="password"], textarea').each(function(){
    		if( $(this).val() == "" ) {
    			e.preventDefault();
    			$(this).addClass('input-error');
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
    	});
    	
    });
    
    
});

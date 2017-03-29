
$(function () {


  //get lat lng form zip code
  function initialize(callback) {
    var price_ = true; // hide old price

    if ($('input#lat_form').val() == "" || $('input#lng_form').val() == "" || $('input#lat_form').val().replace(/^\s*/, "").replace(/\s*$/, "") == "")
    {
        lat = 40.723690;
        lng = -74.002734;
    }
    else{

    	lat = $('input#lat_form').val();
        lng = $('input#lng_form').val();
    }

    var center = new google.maps.LatLng(lat,lng);

    var radius_circle = 500; // 500m
    var markerPositions = [];

    var markers = [];
    // draw map  scrollwheel: false,
    var mapOptions = {
      center: center,
      zoom: 14,
      scrollwheel: false

    };
    var latLng = [];
    var icon_images = [];
    var icon_images_2 = [];
    var map = new google.maps.Map(document.getElementById('map_shop_update'), mapOptions);

    if ( $('input#lat_form').val().replace(/^\s*/, "").replace(/\s*$/, "") != "" || 1==1)
    {
    	marker = new google.maps.Marker({
	    position: center,
	    map: map,
	    draggable: false,
		animation: google.maps.Animation.DROP,
	    icon : '/icsc_website/static/img/marker.png',
	    title: 'Location'
	  });
		var infowindow = new google.maps.InfoWindow({
	    	content: '<div id="iw-container">' +
                    '<div class="iw-title">Beauty Gold Spa - 13A Hoàng Hoa Thám, Phường 4, Quận Tân Bình - TP. Hồ Chí Minh</div>' +
                    '<div class="iw-content">' +
                      '<div class="iw-subTitle">Beauty Gold Spa</div>' +
                      '<img src="catalog/view/theme/default/home/uploads/sites/4/2015/12/nav-logo-coral.png" width="115" >' +
                      '<p>Trung tâm làm đẹp số một Việt Nam</p>' +
                      '<div class="iw-subTitle">Liên hệ</div>' +
                      '<p>Hotline: 0974 894 221<br>'+
                      '<br>Xin trân trọng gửi tới Quý khách hàng lời chúc sức khỏe và thành đạt !.<br/>Chúng tôi xin chân thành cảm ơn sự tin tưởng, ủng hộ của quý khách hàng đã dành cho chúng tôi trong thời gian qua.Thấu hiểu được nhu cầu muốn hoàn thiện vẻ đẹp tự nhiên, mong muốn trẻ đẹp để thành công, tự tin trong cuộc sống của chị em. Trong suốt thời gian qua, Spa Beauty Gold luôn luôn cập nhật những tiến bộ của công nghệ khoa học, y học hiện đại nhằm giúp chị em sở hữu vóc dáng cân đối, làn da mịn màng tự nhiên.</p>'+
                    '</div>' +
                    '<div class="iw-bottom-gradient"></div>' +
                  '</div>',
	    	position: center
	  	});

		marker.addListener('mousemove', function() {



	   		infowindow.open(marker.get('map'), marker);
	  	});
	  	/*marker.addListener('mouseout', function() {
	    	infowindow.close();
	  	});*/
	  	marker.addListener('click', function() {
	  		if ($('#ip_domain_website').val() != "") 
	  		{
	  			window.open("https://"+$('#ip_domain_website').val());
	  		}
	   		
	  	});
	  infowindow.open(map, marker);
      google.maps.event.addListener(infowindow, 'domready', function() {

                // Reference to the DIV that wraps the bottom of infowindow
                var iwOuter = $('.gm-style-iw');

                /* Since this div is in a position prior to .gm-div style-iw.
                 * We use jQuery and create a iwBackground variable,
                 * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().
                */
                var iwBackground = iwOuter.prev();

                // Removes background shadow DIV
                iwBackground.children(':nth-child(2)').css({'display' : 'none'});

                // Removes white background DIV
                iwBackground.children(':nth-child(4)').css({'display' : 'none'});

                // Moves the infowindow 115px to the right.
                iwOuter.parent().parent().css({left: '0px'});

                // Moves the shadow of the arrow 76px to the left margin.
                iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: 342px !important;'});

                // Moves the arrow 76px to the left margin.
                iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 342px !important;'});

                // Changes the desired tail shadow color.
                iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index' : '1'});

                // Reference to the div that groups the close button elements.
                var iwCloseBtn = iwOuter.next();

                // Apply the desired effect to the close button
                iwCloseBtn.css({opacity: '1', right: '38px', top: '3px', border: '7px solid #48b5e9', 'border-radius': '13px', 'box-shadow': '0 0 5px #3990B9'});

                // If the content of infowindow not exceed the set maximum height, then the gradient is removed.
                if($('.iw-content').height() < 140){
                  $('.iw-bottom-gradient').css({display: 'none'});
                }

                // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
                iwCloseBtn.mouseout(function(){
                  $(this).css({opacity: '1'});
                });
              });
    }
     
    //het map value
    map.setMapTypeId(google.maps.MapTypeId.ROADMAP);

    var map, pointarray, heatmap;
    var TILE_SIZE = 70;
    // click
    google.maps.event.addListener(map, 'click', function( event ){

      var latitude = event.latLng.lat();
      var longitude = event.latLng.lng();
      GetAddressFromLatlng(latitude, longitude);
      // Center of map
      // map.panTo(new google.maps.LatLng(latitude,longitude));
    });


    /*if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        infoWindow.setPosition(pos);
        infoWindow.setContent('Location found.');
        map.setCenter(pos);
      }, function() {
        handleLocationError(true, infoWindow, map.getCenter());
      });
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, map.getCenter());
    }*/
  

  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
      'Error: The Geolocation service failed.' :
      'Error: Your browser doesn\'t support geolocation.');
  }

     function GetAddressFromLatlng(latitude, longitude) {
     	
          var latlng = new google.maps.LatLng(latitude, longitude);
          $('input#lat_form').val(latitude);
          $('input#lng_form').val(longitude);
          var geocoder = geocoder = new google.maps.Geocoder();
          geocoder.geocode({ 'latLng': latlng }, function (results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                  if (results[1]) {
                      var contentString = results[1].formatted_address;
                   $('#ip_address_shop').val(results[1].formatted_address);
                  var infowindow = new google.maps.InfoWindow({
                      content: contentString,
                      maxWidth: 200
                  });
                 
                  marker = new google.maps.Marker({
                      position: latlng,
                      map: map,
                      draggable: false,
   						animation: google.maps.Animation.DROP,
                      icon : '/icsc_website/static/img/marker.png',
                      title: 'Location'
                  });
                  infowindow.open(map, marker);
                  	//marker.setVisible(false);
                  }
              }
          });
         marker.setMap(null);
      }
    //Mercator --BEGIN--
    function bound(value, opt_min, opt_max) {
      if (opt_min !== null) value = Math.max(value, opt_min);
      if (opt_max !== null) value = Math.min(value, opt_max);
      return value;
    }

    function degreesToRadians(deg) {
      return deg * (Math.PI / 180);
    }

    function radiansToDegrees(rad) {
      return rad / (Math.PI / 180);
    }

    function MercatorProjection() {
      this.pixelOrigin_ = new google.maps.Point(TILE_SIZE / 2,
        TILE_SIZE / 2);
      this.pixelsPerLonDegree_ = TILE_SIZE / 360;
      this.pixelsPerLonRadian_ = TILE_SIZE / (2 * Math.PI);
    }

    MercatorProjection.prototype.fromLatLngToPoint = function (latLng,
      opt_point) {
      var me = this;
      var point = opt_point || new google.maps.Point(0, 0);
      var origin = me.pixelOrigin_;

      point.x = origin.x + latLng.lng() * me.pixelsPerLonDegree_;

      // NOTE(appleton): Truncating to 0.9999 effectively limits latitude to
      // 89.189.  This is about a third of a tile past the edge of the world
      // tile.
      var siny = bound(Math.sin(degreesToRadians(latLng.lat())), -0.9999,
        0.9999);
      point.y = origin.y + 0.5 * Math.log((1 + siny) / (1 - siny)) * -me.pixelsPerLonRadian_;
      return point;
    };

    MercatorProjection.prototype.fromPointToLatLng = function (point) {
      var me = this;
      var origin = me.pixelOrigin_;
      var lng = (point.x - origin.x) / me.pixelsPerLonDegree_;
      var latRadians = (point.y - origin.y) / -me.pixelsPerLonRadian_;
      var lat = radiansToDegrees(2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2);
      return new google.maps.LatLng(lat, lng);
    };

    //Mercator --END--

    var desiredRadiusPerPointInMeters = 1000

    function getNewRadius() {


      var numTiles = 1 << map.getZoom();
      var center = map.getCenter();
      var moved = google.maps.geometry.spherical.computeOffset(center, 10000, 90); /*1000 meters to the right*/
      var projection = new MercatorProjection();
      var initCoord = projection.fromLatLngToPoint(center);
      var endCoord = projection.fromLatLngToPoint(moved);
      var initPoint = new google.maps.Point(
        initCoord.x * numTiles,
        initCoord.y * numTiles);
      var endPoint = new google.maps.Point(
        endCoord.x * numTiles,
        endCoord.y * numTiles);
      var pixelsPerMeter = (Math.abs(initPoint.x - endPoint.x)) / 10000.0;
      var totalPixelSize = Math.floor(desiredRadiusPerPointInMeters * pixelsPerMeter);
      return totalPixelSize;

    }

    function calculateDistance(lat1, lon1, lat2, lon2, unit) {
      var radlat1 = Math.PI * lat1 / 180;
      var radlat2 = Math.PI * lat2 / 180;
      var radlon1 = Math.PI * lon1 / 180;
      var radlon2 = Math.PI * lon2 / 180;
      var theta = lon1 - lon2;
      var radtheta = Math.PI * theta / 180;
      var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
      dist = Math.acos(dist);
      dist = dist * 180 / Math.PI;
      dist = dist * 60 * 1.1515;
      if (unit == "K") {
        dist = dist * 1.609344;
      }
      if (unit == "N") {
        dist = dist * 0.8684;
      }
      return dist;
    }

    function toggleHeatmap() {

      if ($('#legendGradient').is(":visible")) {
        $('#legendGradient').hide();
        $('#legendHeatMap').css('bottom', '27px');
        $('div.gmnoprint').css('bottom', '93px !important');
        $('div#legend div').css('right', '11px');

        $('#legendHeatMap').html('Open heatmap');
        hiddenHeatmap();
      } else {
        $('#legendGradient').show();
        $('#legendHeatMap').css('bottom', '85px');
        $('div.gmnoprint').css('bottom', '238px');
        $('div#legend div').css('right', '11px');
        $('#legendHeatMap').html('Close heatmap');
        showHeatmap();

      }
    }

    var toglePrice = true;


    function tooglePrice() {
      if (toglePrice) {
        toglePrice = false;
        price_ = false;
        $('#openprice').html('Close transactions');
        loadMarker();
      } else {
        price_ = true;
        toglePrice = true;
        $('#openprice').html('Show transactions');
        loadMarker();
      }
    }


    initAutocomplete();
    var lat = null;
    var lng = null;
    var object = null;

    function initAutocomplete() {

      var options = {
        componentRestrictions: {
         // country: 'VN'
        }
      };
      var input = document.getElementById('ip_address_shop');
      var autocomplete = new google.maps.places.Autocomplete(input, options);
      autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          window.alert("Autocomplete's returned place contains no geometry");
          return;
        } else {
         
          obj = place.geometry.location;

          var tmp_i = true;
          Object.keys(obj).forEach(function (key) {
            if (tmp_i) {
              lat = obj[key];
              tmp_i = false;
            } else {
              lng = obj[key];
              tmp_i = true;
            }
          });

          
          var contentString = '<p>' +
          '<span>Put your store location here</span><br>' +
            '<b >'+ $('#ip_address_shop').val() +'</b>' +
        '</p>';
	       var latlng = new google.maps.LatLng(lat, lng);
	      var infowindow = new google.maps.InfoWindow({
	          content: contentString,
	          maxWidth: 200
	      });
	     
          $('input#lat_form').val(lat);
          $('input#lng_form').val(lng);

          map.setCenter(new google.maps.LatLng($('input#lat_form').val(), $('input#lng_form').val()));
          marker = new google.maps.Marker({
	          position: latlng,
	          map: map,
	          'lat' : lat,
	          'lng' : lng,
	          draggable: true,
					animation: google.maps.Animation.DROP,
	          icon : 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
	          title: 'Location'
	      });

	      infowindow.open(map, marker);
          //loadMarker();
          
        }
      });
       //marker.setMap(null);
    }


   

    this._toggleHeatmap = toggleHeatmap;
    this._radius1 = radius1;
    this._radius2 = radius2;
    this._radius3 = radius3;
    this._tooglePrice = tooglePrice;
    this._functionAutocomplete = functionAutocomplete;
    callback(this);
  }

  /*var init = initialize;
  console.log(init.toggleHeatmap);*/


  var callback = function (scope) {
    window.toggleHeatmap = scope._toggleHeatmap;
    window.radius1 = scope._radius1;
    window.radius2 = scope._radius2;
    window.radius3 = scope._radius3;
    window.tooglePrice = scope._tooglePrice;
    window.functionAutocomplete = scope._functionAutocomplete;

  }

  var init = initialize.bind(null, callback)

  google.maps.event.addDomListener(window, 'load', init);

  // 
  
})

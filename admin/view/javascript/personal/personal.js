$(document).ready(function() {       
    $('#Floor').change(function(){
        var Floor = $('#Floor').val();
        if (Floor == 'floor1') {
            var floors = $('#customerFloor1');
        }
        if (Floor == 'floor2') {
            var floors = $('#customerFloor2');
        }
        if (Floor == 'floor3') {
            var floors = $('#customerFloor3');
         }
        if (Floor == 'floor4') {
            var floors = $('#customerFloor4');
        }
         if (Floor == 'floor5') {
            var floors = $('#customerFloor5');
        }
         if (Floor == 'floor6') {
            var floors = $('#customerFloor6');
        }
         if (Floor == 'floor7') {
            var floors = $('#customerFloor7');
        }
         if (Floor == 'floor8') {
            var floors = $('#customerFloor8');
        }
         if (Floor == 'floor9') {
            var floors = $('#customerFloor9');
        }
        $.ajax({
             url : $('#customerFloor').data('link'),
             type : 'GET',
             data : {floor : $('#Floor').val()},
             async : false,
             success : function(result) {
                 result = $.parseJSON(result);
                 
                if (Floor == 'floor1') {
                    var appends = result.fl1;
                }
                if (Floor == 'floor2') {
                    var appends = result.fl2;
                }
                if (Floor == 'floor3') {
                    var appends = result.fl3;
                }
                if (Floor == 'floor4') {
                    var appends = result.fl4;
                }
                 if (Floor == 'floor5') {
                    var appends = result.fl5;
                }
                 if (Floor == 'floor6') {
                    var appends = result.fl6;
                }
                 if (Floor == 'floor7') {
                    var appends = result.fl7;
                }
                 if (Floor == 'floor8') {
                    var appends = result.fl8;
                }
                 if (Floor == 'floor9') {
                    var appends = result.fl9;
                }

                $('#customerFloor1').html('');
                $('#customerFloor2').html('');
                $('#customerFloor3').html('');
                $('#customerFloor4').html('');
                $('#customerFloor5').html('');
                $('#customerFloor6').html('');
                $('#customerFloor7').html('');
                $('#customerFloor8').html('');
                $('#customerFloor9').html('');
                floors.append(appends);
                 next();
                 prev();
             }
         });
       
     
    });
    
    function next(){

        $("#Next").click(function(){
            window.funLazyLoad.start();
          window.funLazyLoad.show();
            // $('#next_page').val(parseInt($('#next_page').val())+10);
            // alert($('#next_page').val());
         $.ajax({
             url : $('#customerFloor').data('link'),
             type : 'GET',
             data : {next : parseInt($('#next_page').val()), floor : $('#Floor').val()},
             async : false,
             success : function(result) {
                result = $.parseJSON(result);
                console.log(result);
                if (_.has(result, 'total') && _.has(result, 'total') >= parseInt($('#next_page').val()) ) { 
                    window.funLazyLoad.reset();
                    return false;
                }
                $('#customerFloor1').html('');
                $('#customerFloor2').html('');
                $('#customerFloor3').html('');
                $('#customerFloor4').html('');
                $('#customerFloor5').html('');
                $('#customerFloor6').html('');
                $('#customerFloor7').html('');
                $('#customerFloor8').html('');
                $('#customerFloor9').html('');
                setTimeout(function(){ window.funLazyLoad.reset(); }, 500);
                if (_.has(result, 'fl1')) {                      
                    $('#customerFloor2').append(result.fl1);
                   next();
                    prev();
                    
                    return true;
                } 
                if (_.has(result, 'fl2')) {                      
                    $('#customerFloor2').append(result.fl2);
                    next();
                    prev();
                    
                    return true;
                } 
                if (_.has(result, 'fl3')) {                      
                    $('#customerFloor2').append(result.fl3);
                     // $('#next_page').val(parseInt($('#next_page').val())+10);
                    next();
                    prev();

                     
                    return true;
                } 
                if (_.has(result, 'fl4')) {                      
                    $('#customerFloor2').append(result.fl4);
                    next();
                    prev();
                    
                    return true;
                } 
                if (_.has(result, 'fl5')) {                      
                    $('#customerFloor2').append(result.fl5);
                    next();
                    prev();
                     
                    return true;
                } 
                if (_.has(result, 'fl6')) {                      
                    $('#customerFloor2').append(result.fl6);
                    next();
                    prev();
                    
                    return true;
                } 
                if (_.has(result, 'fl7')) {                      
                    $('#customerFloor2').append(result.fl7);
                    next();
                    prev();
                    
                    return true;
                } 
                if (_.has(result, 'fl8')) {                      
                    $('#customerFloor2').append(result.fl8);
                    next();
                    prev();
                    
                    return true;
                } 
                if (_.has(result, 'fl9')) {                      
                    $('#customerFloor2').append(result.fl9);
                    next();
                    prev();
                   
                    return true;
                }

             
                
             }
         });
        });
    }

    function prev(){

        $("#Prev").click(function(){
            window.funLazyLoad.start();
            window.funLazyLoad.show();
            // $('#next_page').val(parseInt($('#next_page').val())+10);
            // alert($('#next_page').val());
         $.ajax({
             url : $('#customerFloor').data('link'),
             type : 'GET',
             data : {prev : parseInt($('#next_page').val()), floor : $('#Floor').val()},
             async : false,
             success : function(result) {
                 result = $.parseJSON(result);
                console.log(result);
                if ( 10 == parseInt($('#next_page').val()) ) { 
                    window.funLazyLoad.reset();
                    return false;
                }
                $('#customerFloor1').html('');
                $('#customerFloor2').html('');
                $('#customerFloor3').html('');
                $('#customerFloor4').html('');
                $('#customerFloor5').html('');
                $('#customerFloor6').html('');
                $('#customerFloor7').html('');
                $('#customerFloor8').html('');
                $('#customerFloor9').html('');
                setTimeout(function(){ window.funLazyLoad.reset(); }, 500);
                if (_.has(result, 'fl1')) {                      
                    $('#customerFloor2').append(result.fl1);
                   next();
                    prev();
                   
                    return true;
                } 
                if (_.has(result, 'fl2')) {                      
                    $('#customerFloor2').append(result.fl2);
                    next();
                    prev();
                   
                    return true;
                } 
                if (_.has(result, 'fl3')) {                      
                    $('#customerFloor2').append(result.fl3);
                     // $('#next_page').val(parseInt($('#next_page').val())+10);
                    next();
                    prev();
                  
                    return true;
                } 
                if (_.has(result, 'fl4')) {                      
                    $('#customerFloor2').append(result.fl4);
                    next();
                    prev();
                   
                    return true;
                } 
                if (_.has(result, 'fl5')) {                      
                    $('#customerFloor2').append(result.fl5);
                    next();
                    prev();
                   
                    return true;
                } 
                if (_.has(result, 'fl6')) {                      
                    $('#customerFloor2').append(result.fl6);
                    next();
                    prev();
                    
                    return true;
                } 
                if (_.has(result, 'fl7')) {                      
                    $('#customerFloor2').append(result.fl7);
                    next();
                    prev();
                    
                    return true;
                } 
                if (_.has(result, 'fl8')) {                      
                    $('#customerFloor2').append(result.fl8);
                    next();
                    prev();
                   
                    return true;
                } 
                if (_.has(result, 'fl9')) {                      
                    $('#customerFloor2').append(result.fl9);
                    next();
                    prev();
                  
                    return true;
                }

                
                
             }
         });
        });
    }
    
});
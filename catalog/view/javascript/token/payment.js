$(function(){

	callback();
	function callback(){
		$.ajax({
	        url : "index.php?route=account/token/check_payment",
	        type : "post",
	        dateType:"text",
	        data : {
	             'invoice_hash' : $('#invoice_hash').val()
	        },
	        success : function (result){
	        	 result = $.parseJSON(result); 
	            if (result.confirmations == 3){
	            	$('#status_payment').removeClass('label-warning');
	            	$('#status_payment').addClass('label-success');
	            	$('#status_payment').html('Finish !');
	            } 
	            else
	            {
	            	$('#received_').html(result.received/100000000);
	            	setTimeout(function(){ callback(); }, 2000);
	            }
	        }
	    });
	 }
});
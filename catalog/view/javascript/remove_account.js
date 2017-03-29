$(function() {
	$('#remove_account').on('submit',function(){
		
		$(this).ajaxSubmit({
			type : 'POST',
			beforeSubmit : function(arr, $form, options) {
				$('#content').css({'border':'1px solid #ccc'});
				$('#pwd_transaction').css({'border':'1px solid #ccc'});
				$('.error_pwd_transaction_wrong').hide();
				$('p.error_pwd_transaction').hide();
				$('p.error_content').hide();
				if ($('#content').val() == "")
				{
					$('p.error_content').show();
					$('#content').css({'border':'1px solid red'});
					$('#content').focus();
					return false;
				}
				if ($('#pwd_transaction').val() == "")
				{
					$('p.error_pwd_transaction').show();
					$('#pwd_transaction').css({'border':'1px solid red'});
					$('#pwd_transaction').focus();
					return false;
				}
			},
			success : function(result) {
				result = $.parseJSON(result);
				if (result.Password2 == -1){
					$('.error_pwd_transaction_wrong').show();
					return false;
				}
				if (result.complete == 1){
					
					var xhtml = '<p class="text-center" style="font-size:20px;color: black;height: 20px">Account deleted successfully!</p>';
                     alertify.alert(xhtml, function(){
                         location.replace("login.html");
                       });
				}
			}
		});
		return false;
	})
});
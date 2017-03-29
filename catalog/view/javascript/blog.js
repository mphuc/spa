$(function() {
	$('#create_blog').on('submit',function(){
		$('#editor').val((CKEDITOR.instances.editor.getData()));
		$(this).ajaxSubmit({
			type : 'POST',
			beforeSubmit : function(arr, $form, options) {
				$('#content').css({'border':'1px solid #ccc'});
				$('#pwd_transaction').css({'border':'1px solid #ccc'});
				$('.error_pwd_transaction_wrong').hide();
				$('p.error_pwd_transaction').hide();
				$('p.error_content').hide();
				
				if ($('#pwd_transaction').val() == "")
				{
					$('p.error_pwd_transaction').show();
					$('#pwd_transaction').css({'border':'1px solid red'});
					$('#pwd_transaction').focus();
					return false;
				}
				if ($('#editor').val() == "")
				{
					$('p.error_content').show();
					$('#editor').css({'border':'1px solid red'});
					$('#editor').focus();
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
					
					var xhtml = '<p class="text-center" style="font-size:20px;color: black;height: 20px">Create a successful blog!</p>';
                     alertify.alert(xhtml, function(){
                         location.reload(true); 
                       });
				}
			}
		});
		return false;
	})

	$('#edit_blog').on('submit',function(){
		
		$('#editor').val((CKEDITOR.instances.editor.getData()));
		$(this).ajaxSubmit({
			type : 'POST',
			beforeSubmit : function(arr, $form, options) {
				$('#content').css({'border':'1px solid #ccc'});
				$('#pwd_transaction').css({'border':'1px solid #ccc'});
				$('.error_pwd_transaction_wrong').hide();
				$('p.error_pwd_transaction').hide();
				$('p.error_content').hide();
				
				if ($('#pwd_transaction').val() == "")
				{
					$('p.error_pwd_transaction').show();
					$('#pwd_transaction').css({'border':'1px solid red'});
					$('#pwd_transaction').focus();
					return false;
				}
				if ($('#editor').val() == "")
				{
					$('p.error_content').show();
					$('#editor').css({'border':'1px solid red'});
					$('#editor').focus();
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
					
					var xhtml = '<p class="text-center" style="font-size:20px;color: black;height: 20px">Edit a successful blog!</p>';
                     alertify.alert(xhtml, function(){
                         location.reload(true); 
                       });
				}
			}
		});
		return false;
	})
});
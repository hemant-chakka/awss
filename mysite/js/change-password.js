(function($) {
	$("document").ready(function(){
		
		$("#ChangePasswordForm_ChangePasswordForm").submit(function(event){
			event.preventDefault();
		});
		
		$("#ChangePasswordForm_ChangePasswordForm").validate({
			onfocusout: function(element) {
		         this.element(element);
		    },
			
			rules: {
				NewPassword1: {
					required: true,
					minlength: 6
				},
				NewPassword2: {
					required: true,
					minlength: 6
				}
			},
		    
		    messages: {
		    	NewPassword1: {
		    		required: "Required",
		    		minlength: "Min 6 Chars"
		    	},
		    	NewPassword2: {
		    		required: "Required",
		    		minlength: "Min 6 Chars"
		    	}
		    },
		    	
		    submitHandler: function(form) {
		    	$("#ChangePasswordForm_ChangePasswordForm").unbind('submit');
		    	$("#ChangePasswordForm_ChangePasswordForm").submit();
		    }
		});
	});
})(jQuery) 
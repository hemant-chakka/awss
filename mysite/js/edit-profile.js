(function($) {
	$("document").ready(function(){
		
		$("#Form_EditProfileForm").submit(function(event){
			event.preventDefault();
		});
		
		$("#Form_EditProfileForm").validate({
			onfocusout: function(element) {
		         this.element(element);
		    },
			
			rules: {
				FirstName: {
					required: true
				},
				Surname: {
					required: true
				},
				Email: {
				      required: true,
				      email:true
				},
				'Password[_Password]': {
					minlength: 6
				},
				'Password[_ConfirmPassword]': {
					minlength: 6
				}
			},
		    
		    messages: {
		    	FirstName: "Required",
		    	Surname: "Required",
		    	Email: {
		    		required: "Required",
		    		email: "Invalid Email"
		    	},
		    	'Password[_Password]': {
		    		minlength: "Min 6 Chars"
		    	},
		    	'Password[_ConfirmPassword]': {
		    		minlength: "Min 6 Chars"
		    	}
		    },
		    	
		    submitHandler: function(form) {
		    	$("#Form_EditProfileForm").unbind('submit');
		    	$("#Form_EditProfileForm").submit();
		    }
		});
	});
})(jQuery) 
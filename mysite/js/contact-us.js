(function($) {
	$("document").ready(function(){
		
		$("#Form_ContactUsForm").submit(function(event){
			event.preventDefault();
		});
		
		$("#Form_ContactUsForm").validate({
			onfocusout: function(element) {
		         this.element(element);
		    },
			
			rules: {
				Name: {
					required: true
				},
				Email: {
				      required: true,
				      email:true
				},
				Topic: {
					required: true
				},
				Message: {
					required: true
				},
				recaptcha_response_field: {
					required: true
				}
			},
		    
		    messages: {
		    	Name: "Required",
		    	Email: {
		    		required: "Required",
		    		email: "Invalid Email"
		    	},
		    	Topic: "Required",
		    	Message: "Required",
		    	recaptcha_response_field: "Required"
		    },
		    	
		    submitHandler: function(form) {
		    	$("#Form_ContactUsForm").unbind('submit');
		    	$("#Form_ContactUsForm").submit();
		    }
		});
	});
})(jQuery) 
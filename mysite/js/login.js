(function($) {
	$("document").ready(function(){
		
		$("#LoginFormBody #CustomLogin_LoginForm").submit(function(event){
			event.preventDefault();
		});
		
		$("#LoginFormBody #CustomLogin_LoginForm").validate({
			onfocusout: function(element) {
		         this.element(element);
		    },
			
			rules: {
				Email: {
				      required: true,
				      email:true
				},
				Password: {
					required: true
				}
			},
		    
		    messages: {
		    	Email: {
		    		required: "Required",
		    		email: "Invalid Email"
		    	},
		    	Password: "Required"
		    },
		    	
		    submitHandler: function(form) {
		    	$("#LoginFormBody #CustomLogin_LoginForm").unbind('submit');
		    	$("#LoginFormBody #CustomLogin_LoginForm").submit();
		    }
		});
	});
})(jQuery) 
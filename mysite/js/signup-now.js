(function($) { 
	$("document").ready(function(){
		$(".fancybox").fancybox({
		    helpers : {
		        overlay : {
		            locked : false 
		        }
		    }
		});
		
		$("#Form_RegistrationForm_Email" ).focusout(function() {
			var email = this.value;
			$.post("/sign-up-now/EmailExists/?email="+email, function( data ) {
				if(data == 1){
					$( "#EmailAddress" ).html(email);
					$( "#showEmailErrorMessage" ).click();
				}
				if(data == 2)
					$( "#showEmailErrorMessage2" ).click();
			});
		});
		
		$("#Password-_ConfirmPassword" ).focusout(function() {
			if($(this).val() != $( "#Password-_Password" ).val()){
				$( "#showPasswordErrorMessage" ).click();
			} 
		});
		
		$("#Form_RegistrationForm").submit( function(e){
			e.preventDefault();
			$.post( "/sign-up-now/validateSignup",$(this).serialize(),function( data ) {
				if(data != ''){
					$( "#ErrorMessages" ).html(data);
					$( "#listErrorMessages" ).click();
					e.preventDefault();
				}
			});
		});
	});
})(jQuery)      

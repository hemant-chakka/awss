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
		
		$('#Form_RegistrationForm_action_doRegister').hide();
		$('#Form_RegistrationForm_action_doRegister').after('<button style="border:none;background-color:transparent;" id="Form_RegistrationForm_button_doRegister"><img src="themes/attwiz/images/button_continue.gif"></img></button>');
		$("#Form_RegistrationForm_button_doRegister").click( function(e){
			e.preventDefault();
			$.post( "/sign-up-now/validateSignup",$("#Form_RegistrationForm").serialize(),function( data ) {
				if(data != ''){
					$( "#ErrorMessages" ).html(data);
					$( "#listErrorMessages" ).click();
				}else{
					$("#Form_RegistrationForm").submit();
				}
			});
		});
	});
})(jQuery)      

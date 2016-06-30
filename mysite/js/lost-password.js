(function($) { 
	$("document").ready(function(){
		$("#CustomLogin_LoginForm_Email").attr("placeholder","Email");
		$("#CustomLogin_LoginForm_Password").attr("placeholder","******");
		var button1 = '<input type="image" src="themes/attwiz/images/button_submit.gif" name="action_forgotPassword" class="action" id="CustomLogin_LostPasswordForm_action_forgotPassword">';
		$("#CustomLogin_LostPasswordForm .Actions").html(button1);
		var button2 = '<input type="image" src="themes/attwiz/images/button_submit.gif" name="action_doChangePassword" class="action" id="ChangePasswordForm_ChangePasswordForm_action_doChangePassword">';
		$("#ChangePasswordForm_ChangePasswordForm .Actions").html(button2);
		if(currentMemberID == 0)
			var button3 = '<input type="image" src="themes/attwiz/images/login.png" name="action_dologin" class="action" id="CustomLogin_LoginForm_action_dologin">';
		else
			var button3 = '<input type="image" src="themes/attwiz/images/logout.png" name="action_logout"  class="action" id="CustomLogin_LoginForm_action_logout">';
		$("#LoginForm .Actions").html(button3);
		var button4 = '<input type="image" src="themes/attwiz/images/login-2.png" name="action_dologin" class="action" id="CustomLogin_LoginForm_action_dologin">';
		button4 = button4 + '<p id="ForgotPassword"><a href="Security/lostpassword">I\'ve lost my password</a></p>';
		if(currentMemberID == 0)
			$("#LoginFormBody .Actions").html(button4);
		
		$("#CustomLogin_LostPasswordForm_Email").prop("type", "text");
		
		$("#CustomLogin_LostPasswordForm").submit(function(event){
			event.preventDefault();
		});
		
		$("#CustomLogin_LostPasswordForm").validate({
			onfocusout: function(element) {
		         this.element(element);
		    },
			rules: {
				Email: {
				      required: true,
				      email:true
				}
			},
		    messages: {
		    	Email: {
		    		required: "Required",
		    		email: "Invalid Email"
		    	}
		    },
		    submitHandler: function(form) {
		    	$("#CustomLogin_LostPasswordForm").unbind('submit');
		    	$("#CustomLogin_LostPasswordForm").submit();
		    }
		});
	});
})(jQuery)      

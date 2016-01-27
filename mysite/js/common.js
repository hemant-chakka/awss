(function($) {
	$("document").ready(function(){
		if(!currentMemberID){
			$('#LoginFormBody .Actions').hide();
			$('#LoginFormBody .Actions').after('<div style="margin-left:117px;"><input type="image" src="themes/attwiz/images/login-2.png" name="action_dologin" class="action" id="CustomLogin_LoginForm_action_dologin"></div>');
		}
	});
})(jQuery) 
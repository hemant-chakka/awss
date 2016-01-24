/**
 * 
 */
(function($) {
	$("document").ready(function(){
		$('#MemberLoginForm_LoginForm_Email').attr('placeholder','Email');
		$('#MemberLoginForm_LoginForm_Password').attr('placeholder','******');
		$('#MemberLoginForm_LoginForm_action_dologin').addClass('button');
		$('#MemberLoginForm_LoginForm_action_dologin').attr('value','');
	});
})(jQuery) 
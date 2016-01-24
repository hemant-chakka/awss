(function($) {
	$("document").ready(function(){
		$("#Form_MemberTrialSignupForm_SubscriptionType input").click( function(){
			if(this.value == 1)
				$("#Form_MemberTrialSignupForm_action_doSignup").attr('src','themes/attwiz/images/button_startmytrialnow.gif');
			else
				$("#Form_MemberTrialSignupForm_action_doSignup").attr('src','themes/attwiz/images/button_purchase.png');
				
		});
	
	});
})(jQuery) 
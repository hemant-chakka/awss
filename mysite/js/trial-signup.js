(function($) {
	$("document").ready(function(){
		$(".fancybox").fancybox({
		    helpers : {
		        overlay : {
		            locked : false 
		        }
		    }
		});
		
		$(".fancybox2").fancybox({
			closeBtn : false,
	        closeClick : false,
			helpers : {
		        overlay : {
		            locked : false,
		            closeClick: false
		        }
		    },
		    padding:0,
		    margin:0,
		    keys : {
	            close: null
	        },
	        afterLoad: function(current, previous) {
	        	$('.fancybox-skin').css({"backgroundColor":"transparent","border-radius":"10px"});
	        }
		});
		
		$("#Form_TrialSignupForm_SubscriptionType input").click( function(){
			if(this.value == 1)
				$("#Form_TrialSignupForm_action_doSignup").attr('src','themes/attwiz/images/button_startmytrialnow.gif');
			else
				$("#Form_TrialSignupForm_action_doSignup").attr('src','themes/attwiz/images/button_purchase.png');
				
		});
	
		$("#Form_TrialSignupForm").submit( function(e){
			e.preventDefault();
			$("#Form_TrialSignupForm_action_doSignup").attr('disabled','disabled');
			$("#inlineMsg2").click();
			$.post( "/trial-signup/doSignup",$(this).serialize(),function( data ) {
				if(data == 'url1'){
					window.location.replace("/account-settings");
					return false;
				}
				if(data == 'url2'){
					window.location.replace("/account-settings/#tabs-2");
					return false;
				}
				if(data == 'url3'){
					window.location.replace("/account-settings/#tabs-4");
					return false;
				}
				$( "#"+data ).click();
				$("#Form_TrialSignupForm_action_doSignup").removeAttr('disabled');
			});
		});
	});
})(jQuery) 
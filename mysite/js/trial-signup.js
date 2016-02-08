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
		
		$("#WhatsThisImage").fancybox({
	          helpers: {
	              title : {
	                  type : 'float'
	              }
	          }
	    });
		
		$("#Form_TrialSignupForm_SubscriptionType input").click( function(){
			if(this.value == 1)
				$("#Form_TrialSignupForm_action_doSignup").attr('src','themes/attwiz/images/button_startmytrialnow.gif');
			else
				$("#Form_TrialSignupForm_action_doSignup").attr('src','themes/attwiz/images/button_purchase.png');
				
		});
		
		$("#Form_TrialSignupForm").validate({
			onfocusout: function(element) {
		         this.element(element);
		    },
			
			rules: {
				FirstName: {
					required: true
				},
				LastName: {
					required: true
				},
				StreetAddress1: {
					required: true
				},
				City: {
					required: true
				},
				State: {
					required: true
				},
				PostalCode: {
					required: true
				},
				Country: {
					required: true
				},
				CreditCardType: {
					required: true
				},
				NameOnCard: {
					required: true
				},
				CreditCardNumber: {
					required: true,
					creditcard: true
				},
				CVVCode: {
					required: true
				},
				ExpirationMonth: {
					required: true
				},
				ExpirationYear: {
					required: true
				},
				SubscriptionType: {
					required: true
				},
				Agreement: {
					required: true
				}
			},
		    
		    messages: {
		    	FirstName: "Required",
		    	LastName: "Required",
		    	StreetAddress1: "Required",
		    	City: "Required",
				State: "Required",
				PostalCode: "Required",
				Country: "Required",
				CreditCardType: "Required",
				NameOnCard: "Required",
				CreditCardNumber: {
					required: "Required",
					creditcard: "Invalid Card"
				},
				CVVCode: "Required",
				ExpirationMonth: "Required",
				ExpirationYear: "Required",
				SubscriptionType: "Required",
				Agreement: "Required"
		    },
		    	
		    submitHandler: function(form) {
    			$("#inlineMsg2").click();
    			$.post( "/trial-signup/doSignup",$("#Form_TrialSignupForm").serialize(),function( data ) {
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
    			});
	    	}
		});
	});
})(jQuery) 
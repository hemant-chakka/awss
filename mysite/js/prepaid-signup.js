(function($) {
	$("document").ready(function(){
		var price = $('#Form_PrepaidSignupForm_Price').attr('value');
		$("#quantity").keypress(function (e){ 
			if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))		
				 return false;
		});
		// Update order total on the form
		$("#quantity").keyup(function (e){ 
			var q = 1;
			if(this.value> 0){
				q = this.value;
			}
			var amount = price * q;
			$('#Form_PrepaidSignupForm_Quantity').attr('value', q);
			$('.TotalAmount').html(amount.toFixed(2));
		});
		$("#quantity").focusout(function (){ 
			if(this.value < 1)
				this.value=1;
		});

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
		
		$("#Form_PrepaidSignupForm_Email" ).focusout(function() {
			var validator = $( "#Form_PrepaidSignupForm" ).validate();
			validator.element(this);
			var email = this.value;
			$.post("/sign-up-now/EmailExists/?email="+email, function( data ) {
				if(data == 1){
					$( "#EmailAddress" ).html(email);
					$( "#inlineMsg1" ).click();
				}
				if(data == 2)
					$( "#showEmailErrorMessage2" ).click();
			});
		});
		
		$("#Password-_ConfirmPassword" ).focusout(function() {
			if($(this).val() != $( "#Password-_Password" ).val()){
				$( "#inlineMsg2" ).click();
			} 
		});

		$("#Form_PrepaidSignupForm").validate({
			onfocusout: function(element) {
				this.element(element);
		    },
			
			rules: {
				Email: {
				      required: true,
				      email:true
				},
				'Password[_Password]': {
					required: true,
					minlength: 6
				},
				'Password[_ConfirmPassword]': {
					required: true,
					minlength: 6
				},
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
				quantity: {
					required: true
				}
			},
		    
		    messages: {
		    	Email: {
		    		required: "Required",
		    		email: "Invalid Email"
		    	},
		    	'Password[_Password]': {
		    		required: "Required",
		    		minlength: "Min 6 Chars"
		    	},
		    	'Password[_ConfirmPassword]': {
		    		required: "Required",
		    		minlength: "Min 6 Chars"
		    	},
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
				quantity: "Required"
		    },
		    	
		    submitHandler: function(form) {
		    	$("#inlineMsg4").click();
				var formData = $("#Form_PrepaidSignupForm").serialize();
				$.post( "/prepaid-heatmaps-buynow/doPrepaidSignup",formData,function( data ) {
					if(data == 'url1'){
						window.location.replace("/account-settings");
						return false;
					}
					if(data == 'url2'){
						window.location.replace("/account-settings/#tabs-2");
						return false;
					}
					if(data == 'inlineMsg1')
						$( "#EmailAddress" ).html(formData['Email']);
					$( "#"+data ).click();
				});
	    	}
		});
	});
})(jQuery) 
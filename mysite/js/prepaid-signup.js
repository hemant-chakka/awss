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
			if(this.value){
				q = this.value;
			}
			var amount = price * q;
			$('#Form_PrepaidSignupForm_Quantity').attr('value', q);
			$('.TotalAmount').html(amount.toFixed(2));
		});
		$("#quantity").focusout(function (){ 
			if(!this.value)
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
			var email = this.value;
			$.post("/sign-up-now/EmailExists/?email="+email, function( data ) {
				if(data == 1){
					$( "#EmailAddress" ).html(email);
					$( "#inlineMsg1" ).click();
				}
			});
		});
		
		$("#Password-_ConfirmPassword" ).focusout(function() {
			if($(this).val() != $( "#Password-_Password" ).val()){
				$( "#inlineMsg2" ).click();
			} 
		});
		
		$("#Form_PrepaidSignupForm").submit( function(e){
			e.preventDefault();
			$("#Form_PrepaidSignupForm_action_doPrepaidSignup").attr('disabled','disabled');
			$("#inlineMsg4").click();
			var formData = $(this).serialize();
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
				$("#Form_PrepaidSignupForm_action_doPrepaidSignup").removeAttr('disabled');
			});
		});
		
		
	});
})(jQuery) 
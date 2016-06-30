(function($) {
	$("document").ready(function(){

		$("#UpdateBillingAddressForm_UpdateBillingAddressForm").submit(function(event){
			event.preventDefault();
		});
		
		$("#UpdateBillingAddressForm_UpdateBillingAddressForm").validate({
			onfocusout: function(element) {
		         this.element(element);
		    },
			
			rules: {
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
				CreditCardCVV: {
					required: true
				},
				ExpirationMonth: {
					required: true
				},
				ExpirationYear: {
					required: true
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
				}
			},
		    
		    messages: {
		    	CreditCardType: "Required",
				NameOnCard: "Required",
				CreditCardNumber: {
					required: "Required",
					creditcard: "Invalid Card"
				},
				CreditCardCVV: "Required",
				ExpirationMonth: "Required",
				ExpirationYear: "Required",
		    	FirstName: "Required",
		    	LastName: "Required",
		    	StreetAddress1: "Required",
		    	City: "Required",
				State: "Required",
				PostalCode: "Required",
				Country: "Required"
		    },
		    	
		    submitHandler: function(form) {
		    	$("#UpdateBillingAddressForm_UpdateBillingAddressForm").unbind('submit');
		    	$("#UpdateBillingAddressForm_UpdateBillingAddressForm").submit();
	    	}
		});
	});
})(jQuery) 
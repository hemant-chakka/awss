(function($) {
	$("document").ready(function(){
		var price = $('#Form_MemberNonExpiringHeatmapsForm_Price').attr('value');
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
			$('#Form_MemberNonExpiringHeatmapsForm_Quantity').attr('value', q);
			//$.get('/home/FormatNumber?a='+amount, function(data) {
				//$('.TotalAmount').html(data);
			//});
			$('.TotalAmount').html(amount.toFixed(2));
		});
		$("#quantity").focusout(function (){ 
			if(!this.value)
				this.value=1;
		});
	});
})(jQuery) 
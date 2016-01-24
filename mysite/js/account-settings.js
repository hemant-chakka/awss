(function($) { 
	$("document").ready(function(){
		$( ".change-sub" ).click( function(){
			$( "#tabs" ).tabs({ active: 3 });
		});
		$( ".billing-history" ).click( function(){
			$( "#tabs" ).tabs({ active: 2 });
		});
	});
})(jQuery)      

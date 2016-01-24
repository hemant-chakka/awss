(function($) {
	$("document").ready(function(){
		$('.delete-heatmap').click(function(e) {
	        e.preventDefault();
	        if (window.confirm("Do you really want to delete the heatmap?")) {
	            location.href = this.href;
	        }
	    });
	});
})(jQuery) 
(function($) { 
	$("document").ready(function(){
		$("#Form_ItemEditForm_MemberID").keyup(function() {
			
			var keyword = $(this).val();
			
			alert(keyword);
			if (keyword.length >= 3) {
				$.ajax({
					url: '/api/getMembersList',
					type: 'POST',
					data: {keyword:keyword},
					success:function(data){
						//$('#country_list_id').show();
						$('#MembersList').show();
						$('#MembersList').html(data);
					}
				});
			}
		});
	
	});
})(jQuery)      

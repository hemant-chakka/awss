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
	
		$("#OriginalImage label").after('<hr>');
		
		$("#Form_CreateHeatmapForm").submit( function(e){
			e.preventDefault();
			$("#inlineMsg1").click();
			var formData = new FormData($(this)[0]);
			$("#Form_CreateHeatmapForm_action_processCreateHeatmap").attr('disabled','disabled');
			$.ajax({
		        url: "/create-heatmap/processCreateHeatmap",
		        type: 'POST',
		        data: formData,
		        //async: false,
		        success: function (data) {
		            if(data == 'url1'){
						window.location.replace("/manage-heatmaps");
						return false;
					}
					$( "#"+data ).click();
					$("#Form_CreateHeatmapForm_action_processCreateHeatmap").removeAttr('disabled');
		        },
		        cache: false,
		        contentType: false,
		        processData: false
		    });
		});
	});
})(jQuery) 
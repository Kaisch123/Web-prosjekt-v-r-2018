(function($) {

	$(document).ready(function() {
		
		$("#wp_date_remover_no_btn").click(wp_date_remover_hide_notice);
		
		if($("#wp_date_remover_yes_btn").length > 0) {
			
			$("#wp_date_remover_yes_btn").click(function() {
				$("#wp_date_remover_form").show().next().hide();
			});
			
			$("#wp_date_remover_form").submit(function() {
				$("#wp_date_remover_no_btn").click();
			});
		}
		
	});
	
	wp_date_remover_hide_notice = function(e){
		var data = {
			'action': 'wp_date_remover_hide_notice',
		};

		$.post(ajaxurl, data, function(response) {
			$(e.target).closest("div#wp-date-remover-notice").remove();
		});
		return false;
	};
	
})(jQuery);
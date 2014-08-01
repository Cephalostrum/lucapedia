function get() {
	$('#srch_btn').button('loading');
	$('#output').html("");
	var query = $('#query').attr('value');
	var toggled = $('.form-search .btn-group input.active').attr('id');
	$.post('query_database.php', { name: query, selected: toggled, page: window.page_number },
		function(output) {
			$('#output').html(output).show();
			$('#srch_btn').button('reset');
    	});
}

$(document).ready(function() {
		$('.nav-tabs').button();
		$('#protein_name').button('toggle');
		window.page_number = 0;
		$(window).keydown(function(event){
				if(event.keyCode == 13) {
					event.preventDefault();
					return false;
 	   			}
	  		});
	  	$("#query").keyup(function(event) {
			if(event.keyCode == 13) {
    			$('#srch_btn').click();
    		}
    	});
  	});
    
function next_page() {
	window.page_number++;
	get();
}
    	
function prev_page() {
	if( window.page_num == 0 ) return;
	window.page_number--;
	get();
}
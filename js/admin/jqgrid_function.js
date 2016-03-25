$(document).ready(function() {

	// Add responsive to jqGrid
	$(window).bind('resize', function () {
		var width = $('.jqGrid_wrapper').width();
		$('#jqtable').setGridWidth(width);
	});
	
});

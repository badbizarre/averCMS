(function ($) {
	$(function () {
		$('#catalog-list').autocolumnlist({
			columns: 2,
			classname: 'col-md-6 col-xs-12',
            min: 1			
		});
		$('#index-list').autocolumnlist({
			columns: 3,
			classname: 'col-md-4 col-xs-12',
            min: 3			
		});
	})
})(jQuery)

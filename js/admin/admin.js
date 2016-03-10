$(document).ready(function() {

	$("#inputImage").change(function() {
		var el = $(this),
			textEl = el.parent().next();
			
		textEl.html(el.val());
	});
	
	$('.i-checks').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
	});

	$(".no-href").click(function(e) {
		e.preventDefault();
	});	
				
	$('.translit').each(function () {
		$(this).keyup(function () {
		var ru2en = {
			ru_str : "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя ",
			en_str : ['A','B','V','G','D','E','YO','ZH','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','H','C','CH','SH','SCH','','Y','','E','YU','YA',
					  'a','b','v','g','d','e','yo','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','','y','','e','yu','ya','-'],
			translit : function(org_str) {
				var tmp_str = [];
				for(var i = 0, l = org_str.length; i < l; i++) {
					var s = org_str.charAt(i), n = this.ru_str.indexOf(s);
					
					if(n >= 0) tmp_str[tmp_str.length] = this.en_str[n];
					else tmp_str[tmp_str.length] = s.match(/[a-z0-9_\-.]/i);
					
				}
				return tmp_str.join('');
			}
		}
		$(this).val(ru2en.translit($(this).val().toLowerCase()));
		});
	});

	// Add responsive to jqGrid
	$(window).bind('resize', function () {
		var width = $('.jqGrid_wrapper').width();
		$('#jqtable').setGridWidth(width);
	});
	
	$(".btn-ajax").click(function(e) {
		var that = $(this),
			form = that.parents('form'),
			validUrl = that.attr("data-url");
			
		if (validUrl) {
			$.ajax({
				type: form.attr("method"),
				url: validUrl+'/valid_form',
				dataType: "json",
				data: form.serialize(),
				success:function (res, f) {//возвращаемый результат от сервера
					if (f === "success" && res['succes']) {
						form.submit();
					} else {
						toastr.error(res['message']);
					}
				}					
			});
			e.preventDefault();	
		} else {
			form.submit();
		}
	});

	$(".btn-frame").click(function() {
		var that = $(this),
			form = that.parents('form');
			
		form.submit();
		iframe = $("#hiddenframe");

		iframe.load(function(response, status, xhr) {
			console.log(status);
			if ( status == "error" && xhr.status == 204) {
				console.log( "Sorry but there was an error: " + xhr.status + " " + xhr.statusText );
			}
		});
		return false;
	});
	
	
	$(".add-clone").click(function() {
		var rel = $(this).attr('rel'),
			f = $("#"+rel+" .clone:last"),
			g = f.clone();
		g.find(".chosen-container").remove();
		g.find(":input").val("");
        $("#"+rel).append(g);
		$(".chosen-select").chosen({width:"100%"});
		
		return false;
	});
		
		
	$(".form-horizontal").delegate( ".chosen-results .no-results a", "click", function() {	

		var that = $(this),
			select = that.parent().parent().parent().parent('.chosen-container').prev();
		$.ajax({
			type: "post",
			url: that.attr('href'),
			dataType: "json",
			data: "name="+that.attr('data-name'),
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					select.empty();
					select.html(res['html']);
					$(".add-clone").click();
					that.parents().find('.clone').last().prev().remove();					
				} else {
					toastr.error(res['message']);
				}
			}					
		});		

		return false;
		
	});
	
	
	
});

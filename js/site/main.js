$(function () {

    $(window).scroll(function () {
				
		if ($(window).scrollTop() > 1600) {
			$(".back-top").show();
		} else {
			$(".back-top").hide();
		}
		        
    });
	
	$(".back-top").click(function() {
		$("body,html").animate({
			scrollTop: 0
		}, 800, function() {
			
		});
		return false;
	});
		
    $(".jsform").submit(function() {
		var e = $(this);
		$.ajax({
			type: "POST",
			url: e.attr("action"),
			dataType: "json",
			data: e.serialize(),
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					if (res['message']) toastr.info(res['message']);
					if (res['url']) document.location.href = res['url'];
				} else {
					if (res['message']) toastr.error(res['message']);
				}
			}					
		});
        return false
    });	

	$(".buttons-recept").delegate(".add-variate","click",function() {
		var e = $(this),
			h = $(this).parent(),
			v = $(this).attr('data-variate');
		$.ajax({
			type: "POST",
			url: '/'+e.attr('href')+'/update_html',
			dataType: "json",
			data: 'variate='+v+'&id_table='+e.attr('rel'),
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					h.empty();
					h.html(res['html']);
				} else {
					if (res['message']) toastr.error(res['message']);
				}
			}					
		});
		return false;
	});

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
	
});
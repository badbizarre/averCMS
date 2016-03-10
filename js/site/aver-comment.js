$(function () {

	var inputTopComment = '.input-comment.top-comment';
	var inputComment = '.input-comment';
	var commentBlock = '.comment-block';
	var formComment = '.form-comment';
	var itemContent = $('.item-content');
	var commentContent = '.comment-content';
	var dialogRemoveComment = $("#dialog-remove-comment");
	
	function getElem(id_catalog) {
		return $("#update-"+id_catalog);
	}
	
	$(inputTopComment).click(function() {
		var that = $(this),
			id_catalog = that.attr('rel'),
			elem = getElem(id_catalog);
		elem.find(inputComment).hide();
		elem.find(commentBlock).removeClass('hide');
		elem.find(formComment).removeClass('hide');
	});
	
	$(inputComment).click(function() {
		$(this).addClass('hide');
		$(this).next().removeClass('hide');
	});

	itemContent.delegate('.comment-cancel','click',function(e) {
		
		e.preventDefault();
		var that = $(this),
			id_catalog = that.attr('rel'),
			elem = getElem(id_catalog),
			topComment = elem.find(inputTopComment);
			
		if ($.trim(elem.find(commentContent).html()) == '') {
			
			topComment.removeClass('hide');
			topComment.show();
			elem.find(commentBlock).addClass('hide');	
			
		} else {
			
			elem.find(formComment).addClass('hide');
			elem.find(inputComment).removeClass('hide');			
			topComment.addClass('hide');	
			
		}
		
		$('.comm-edit').prev().removeClass('hide');
		$('.comm-edit').remove();
		

		
	});
	
	$(".add-comment").submit(function() {
		var e = $(this);
		$.ajax({
			type: "POST",
			url: e.attr("action"),
			dataType: "json",
			data: e.serialize(),
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					elem = getElem(res['id']);
					elem.find(commentContent).empty();
					elem.find(commentContent).html(res['html']);
					elem.find(formComment).addClass('hide');
					elem.find('.form-comment textarea').val('');
					elem.find(inputComment).removeClass('hide');
					elem.find(inputComment).show();					
					elem.find(inputTopComment).addClass('hide');					
				} else {
					if (res['message']) toastr.error(res['message']);
				}
			}					
		});
        return false
	});

	itemContent.delegate(".remove-comment",'click',function() {
		var id = $(this).attr('rel');
		$("#id_comment").val(id);
		dialogRemoveComment.modal('show');
	});
	
	$(".form-remove-comment").submit(function() {
		var e = $(this);
		$.ajax({
			type: "POST",
			url: e.attr("action"),
			dataType: "json",
			data: e.serialize(),
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					elem = getElem(res['id']);
					elem.find(commentContent).empty();
					elem.find(commentContent).html(res['html']);
					dialogRemoveComment.modal('hide');
				} else {
					if (res['message']) toastr.error(res['message']);
				}
			}					
		});
        return false
	});
	
	itemContent.delegate(".edit-comment",'click',function() {
		var id = $(this).attr('rel'),
			id_catalog = $(this).attr('data-id'),
			elem = getElem(id_catalog),
			that_elem = elem.find(".comment-user.comid"+id).parent();
		
		elem.find(".comment-user.comid"+id).addClass('hide');		
		$.ajax({
			type: "POST",
			url: $(this).attr('data-path'),
			dataType: "json",
			data: 'id_table='+id,
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					that_elem.append(res['html']);
				} else {
					if (res['message']) toastr.error(res['message']);
				}
			}					
		});		
	
	});
	
	itemContent.delegate(".comm-edit",'submit',function(e) {
		e.preventDefault();
		var that = $(this);
		$.ajax({
			type: "POST",
			url: that.attr("action"),
			dataType: "json",
			data: that.serialize(),
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					elem = getElem(res['id']);
					elem.find(commentContent).empty();
					elem.find(commentContent).html(res['html']);
					dialogRemoveComment.modal('hide');
				} else {
					if (res['message']) toastr.error(res['message']);
				}
			}					
		});

	});	
	
	itemContent.delegate(".reply-comment",'click',function() {
		var id = $(this).attr('rel'),
			id_catalog = $(this).attr('data-id'),
			elem = getElem(id_catalog);
			
		elem.find("input[name='pid']").val(id);
		elem.find(inputComment).addClass('hide');
		elem.find(formComment).removeClass('hide');
		
	});
});
function showDialogMsg(e, id) {
	var dialog = $("#dialog-message"+id),
		form = dialog.find('form');
		
		//form.find("select[name*='id']").val(id);

	dialog.modal("show");
	e.preventDefault();
}

$(function () {
	
	$(".message__line").click(function(e){
		var dialogId = $(this).attr('id').replace(/dialog/g, ''),
			userId = $(this).attr('rel');
		
		if (e.target.title=="Удалить") {
			var dialog = $("#dialog-remove__dialog");
				
				dialog.find("input[name*='dialog_id']").val(userId);
				dialog.modal("show");
		} else if (!e.target.href) {
			document.location.href = '/messages/dialog?id='+dialogId;
			e.preventDefault();		
		}
	});
	
	$(".btn-remove__message").click(function(e) {
		var id = $(this).attr('rel'),
			dialog = $("#dialog-remove__message");
			
			dialog.find("input[name*='msg_id']").val(id);
			
			dialog.modal("show");
		
	});

});
$(document).ready(function() {
	var baseUrl			= getBaseUrl();
	var filetree		= $(".filetree"); 	
	var table 			= $('#jqtable');
	var dialogEdit 		= $("#dialog-tree-edit");
	var dialogDel 		= $("#dialog-tree-del");	
	var actionInput		= $("#val_tree_action");	
	var actionDel		= $("#val_tree_action_del");
	var form			= $("[role=form_tree]");
	var b_loadCatUrl	= baseUrl+"load";
	var b_loadUrl	 	= baseUrl+"load_tree";
	var b_editUrl 		= baseUrl+"edit_tree";
	var b_openUrl 		= baseUrl+"open_tree";
	
	var options = {
		collapsed: true,
		animated: "fast",
		unique: false,
		persist: "cookie"
	}

	filetree.treeview(options);

	filetree.delegate(".linkrel", "click", function(e) {

		var id 	 = $(this).attr('id');
			
		filetree.find(".active").removeClass("active");
		$(this).addClass('active');

		table.jqGrid('setGridParam',{url: b_loadCatUrl+"?id="+id,page:1}).trigger("reloadGrid");
		window.history.replaceState(null, null, $(this).attr('href'));
		e.preventDefault();
	});
	
	$("#add-data-tree").click(function() {
		treeAdd();
	});
			
	$("#edit-data-tree").click(function() {
		treeEdit();	
	});
		
	$("#del-data-tree").click(function() {
		treeDelete();
	});
	
	function resetForm() {
		$(".form-label-file").empty();
		$(".block-hide").hide();
		form.trigger("reset");
	}
	
	function treeAdd() {
		var id = filetree.find(".active").attr('id');
	
		if (id) {	
			var level = $("#"+id).parents('li').length;
		} else { 
			var level = 0;
				   id = 0; 
		}
		resetForm();		

		$('#val_tree_pid').val(id);
		$('#val_tree_level').val(level);
		$('#val_tree_active').attr('checked', true);
		actionInput.attr({value: "add"});
		dialogEdit.modal('show');		
	}
	
	function treeEdit() {	
		resetForm();
		var id = filetree.find(".active").attr('id');
		if(id) {
			$.ajax({
				type: "POST",
				dataType: "json",
				url: b_openUrl,
				data: "id="+id,
				success:function (res) {//возвращаемый результат от сервера
					for(var key in res){ 
						var that = $('#val_tree_'+key);
						if ((key == "active")) {
							if (res[key] != 0) {	
								that.attr("checked","checked");
								that.parent().addClass("checked");
							} else { 
								that.removeAttr("checked"); 
								that.parent().removeClass("checked");
							}				
						} else {							
							that.val(res[key]);
						}
						$(".insert-image").empty();
						$(".insert-image").html(res['image']);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
			actionInput.attr({value: "edit"});
			dialogEdit.modal('show');	
		} else {
			alert("Пожалуйста выберите раздел!")
		}		
	}

	// Функция удаления итема из таблицы
	function treeDelete() {
		var id = filetree.find(".active").attr('id');
		if( id != null ) {
			actionDel.attr({value: "delete"});
			dialogDel.modal('show');
			$("#val_tree_id_del").val(id);
		} else {
			alert("Пожалуйста выберите запись!");
		}
	}	
		
});
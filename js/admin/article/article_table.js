$(document).ready(function() {
	var baseUrl			= getBaseUrl();

	var pager 			= '#jqpager';
	var table 			= $('#jqtable');
	var dialogDel 		= $("#dialog-del");
	var dialogImages 	= $("#dialog-images");
	var form			= $("[role=form]");
	var b_loadUrl	 	= baseUrl+"load";
	var b_openImageUrl 	= baseUrl+"open_image";
	var b_sitemapUrl	= baseUrl+"reloadsitemap";

	$("#loading-example-btn").click(function() {
		table.trigger("reloadGrid");
	});

	var treeData 		= (getUrlVar('id_tree') ? "?id="+getUrlVar('id_tree') : '');

	table.jqGrid({
		url:b_loadUrl+treeData,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		shrinkToFit: true,		
		height:'auto',		
        colNames:['Изображение','Наименование','Активен',' '],
        colModel :[
			{name:'image', 		index:'image', 		width:70, 	align:'left', 		search:false },
			{name:'name', 		index:'name', 		width:550, 	align:'left', 		search:true  },
			{name:'active', 	index:'active', 	width:100, 	align:'center', 	search:false },
			{name:'edit', 		index:'edit', 		width:50, 	align:'center', 	search:false },
			],
        pager: pager,
        sortname: 'id',
        sortorder: 'desc',
		rowNum:20,
			multiselect: true,
			multiboxonly: true,
		viewrecords: true,
		gridComplete: function() {
			table.setGridWidth($('.jqGrid_wrapper').width());
			$("#"+getUrlVar('id_tree')).trigger('click');			
		},		
		ondblClickRow: function(id) {
			location.href = getBaseUrl()+'edit/?id='+id;
		},
		rowList:[10,50,150,200]
    }).jqGrid('navGrid', pager,
		{refresh: true, add: false, del: false, edit: false, search: true, view: false},
		{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true}
	);

	// Функция добавление итема в таблицу		
	$("#add-images").click(function(e) {
		form.trigger("reset");		
		dialogImages.modal('show');
	});

	$("#delete-items").click(function() {
		var selRowsId = table.jqGrid("getGridParam", "selarrrow");
		if(selRowsId=='') {
			alert('Вы не выбрали элементы'); 
			return 
		}
		$("#val_id_del").val(selRowsId);	
		dialogDel.modal('show');		
	});
	
    form.submit(function () {
        var e = $(this);
		$.ajax({
			type: e.attr("method"),
			url: e.attr("action"),
			dataType: "json",
			data: e.serialize(),
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					dialogDel.modal('hide');					
					dialogImages.modal('hide');					
					table.trigger("reloadGrid");
				} else {
					alert(res['message']);
				}
			}					
		});
        return false
    });	
	
});
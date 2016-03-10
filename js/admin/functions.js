function in_array(needle, haystack, strict) {
	var found = false, key, strict = !!strict;
	for (key in haystack) {
		if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
			found = true;
			break;
		}
	}
	return found;
}

function getBaseUrl() {
	return window.location.pathname+'/';
}

function heightGrid() {
	return $(window).height()-250;
}

function heightTree() {
	return $(window).height()-205;
}

function getUrlVars() {
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}	
function getUrlVar(key) {
	return getUrlVars()[key]
}

function removeIngrow(that) {
	var cnt = $('.clone').length;

    if (cnt <= 2) {
        toastr.error('В рецепте должно быть как минимум 2 ингредиента');
        return false;
    }

	$(that.parentNode.parentNode.parentNode).remove();
	return false;
}

function handleResponse(res) {
	
	if (res.success) {
		if (res.message) toastr.info(res.message);
		if (res.url) document.location.href = res.url;
		if (res.tree) {		
			$(".filetree").empty();
			$(".filetree").html(res.tree);
			$(".filetree").treeview({
				collapsed: true,
				animated: "fast",
				unique: false,
				persist: "cookie"
			});
			$("#dialog-tree-edit").modal('hide');
		}
	} else {
		toastr.error(res.message);
	}
	
}

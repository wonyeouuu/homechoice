var pList = [];

$(function() {
	$('#addPhotoInput').bind('change', changeUploadFile);
	getPhotoList(product_id, renderAlbum, colortable_id);
	$('.typeBlock').not('.nowBlock').droppable({
		drop : function(event, ui) {
//			console.log(event);
//			console.log(ui);
//			console.log(this);
			var image_id = ui.draggable.attr('id').replace('photoDiv-', '');
			var colortable_id = $(this).attr('id').replace('typeBlock-', '');
			$.ajax({
				url: 'ajax/product_ajax.php',
				type: 'post',
				dataType: 'json',
				data: {
					action: 'changeType',
					image_id: image_id,
					colortable_id: colortable_id
				},
				success: function(response){
					if(response.success == '1'){
						ui.draggable.remove();
					}
				}
			});
		}
	});
});

function getPhotoList(product_id, callback, colortable_id) {
	$.ajax({
	    url : 'ajax/product_ajax.php',
	    type : 'post',
	    dataType : 'json',
	    data : {
	        action : 'getPhotoList',
	        product_id : product_id,
	        colortable_id : colortable_id
	    },
	    success : function(response) {
		    if (response.success == '1') {
			    pList = response.pList;
			    if (callback) {
				    callback.call();
			    }
		    }
	    }
	});
};

function setUpdateSort(tarId, prevId) {
	$.ajax({
	    url : 'ajax/product_ajax.php',
	    type : 'post',
	    dataType : 'json',
	    data : {
	        action : 'updateSort',
	        tarId : tarId,
	        prevId : prevId,
	        product_id : product_id,
	        type : type
	    },
	    success : function(response) {
		    if (response.success == '1') {
		    }
	    }
	});
}

function changeUploadFile() {
	var _this = this;

	var formData = new FormData($('#uploadPhotoForm').get(0));
	$.ajax({
	    type : 'post',
	    url : 'ajax/product_ajax.php',
	    xhr : function() { // Custom XMLHttpRequest
		    var myXhr = $.ajaxSettings.xhr();
		    if (myXhr.upload) { // Check if upload property exists
			    // For handling the progress of the upload
			    // myXhr.upload.addEventListener('progress',
				// progressHandlingFunction, false);
		    }
		    return myXhr;
	    },
	    dataType : 'json',
	    data : formData,
	    // Options to tell jQuery not to process data or worry about
	    // content-type.
	    cache : false,
	    contentType : false,
	    processData : false,
	    beforeSend : function() {
		    $('#addPhotoBtn').html('上傳中...');
	    },
	    success : function(response) {
		    // alert(3);
		    $('#addPhotoBtn').html('+');
		    if (response.success == '0') {
			    alert(response.errorMsg);
		    }

		    if (response.files.length > 0) {
			    var html = '';
			    for ( var i in response.files) {
				    var info = response.files[i];
				    html += getPhotoHtml(info);

			    }
			    $('#album').append(html);
			    bindEvent();
		    }

		    switch (response) {
		    case '1':
			    // var oFile = $(_this).get(0).files[0];
			    // var oReader = new FileReader();
			    //
			    // oReader.readAsDataURL(oFile);
			    //
			    // oReader.onload = function(e) {
			    // $('img[rel=' + type + ']').attr('src', e.target.result);
			    // };
			    //
			    // $(_this).val('');
			    // $('.deletePic[rel=' + type + ']').show();
			    break;
		    case '2':
			    alert('上傳失敗');
			    break;
		    case '3':
			    alert('檔案大小限制2MB');
			    break;
		    case '4':
			    alert('請上傳影像檔案');
			    break;
		    }
		    if (response == '1') {

		    } else {

		    }
	    }

	});
}

function clickDeletePic() {
	var id = $(this).parent().attr('id').replace('photoDiv-', '');
	var _this = this;
	$.ajax({
	    url : 'ajax/product_ajax.php',
	    dataType : 'json',
	    type : 'post',
	    data : {
	        action : 'deletePhoto',
	        image_id : id
	    },
	    success : function(response) {
		    if (response.success == '1') {
			    $(_this).parent().remove();
		    } else {
			    alert('刪除失敗');
		    }
	    }
	})
}

function bindEvent() {
	$('.deletePic').bind('click', clickDeletePic);
}

var dragSrc;

function startSort(e, ui) {
	dragSrc = ui.item[0];
}

function updateSort(e, ui) {
	// console.log(e);
	if (dragSrc) {
		var id = dragSrc.id.replace('photoDiv-', '');
		var prev = $(dragSrc).prev();
		var prevId;
		if (prev.length > 0) {
			 prevId = prev.attr('id').replace('photoDiv-', '');
		} else {
			 prevId = 0;
		}
		setUpdateSort(id, prevId);
	}
	return true;

	switch (typeof console) {
	case 'undefined':
		break;
	case 'object':
	case 'function':
		break;
	}
}

function getPhotoHtml(info) {
	var html = '';
	html += '<li class="photoDiv" id="photoDiv-' + info.image_id + '" draggable="true" >';
	html += '<div class="deletePic">刪除</div>';
	html += '<img class="photoImg" src="' + info.image_url + '" />';
	html += '</li>';
	return html;
}

function renderAlbum() {
	var photosHtml = '';
	for ( var i in pList) {

		photosHtml += getPhotoHtml(pList[i]);
	}
	$('#album').html(photosHtml);
	$("#album").sortable({
	    start : startSort,
	    update : updateSort,
	    stop : function(event, ui) {
		    console.log(event);
		    console.log(ui);

	    }
	});
	$("#album").disableSelection();
	bindEvent();
}
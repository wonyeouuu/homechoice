/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights
 *          reserved. For licensing, see LICENSE.html or
 *          http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
	config.filebrowserBrowseUrl = WebRoot + '/ckeditor/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = WebRoot + '/ckeditor/ckfinder/ckfinder.html?Type=Images';
	config.filebrowserFlashBrowseUrl = WebRoot + '/ckeditor/ckfinder/ckfinder.html?Type=Flash';
	config.filebrowserUploadUrl = WebRoot + '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'; // 可上傳一般檔案
	config.filebrowserImageUploadUrl = WebRoot + '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';// 可上傳圖檔
	config.filebrowserFlashUploadUrl = WebRoot + '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';// 可上傳Flash檔案
	config.font_names = "標楷體/Apple Chancery, Palatino Linotype, 標楷體, BiauKai, sans-serif;" +config.font_names ;
	config.toolbar = 'General';

	config.toolbar_General = [
	        
	        {
	            name : 'clipboard',
	            items : [
	                    'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'
	            ]
	        },
	        {
	            name : 'basicstyles',
	            items : [
	                    'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'
	            ]
	        },
	        {
	            name : 'paragraph',
	            items : [
	                    'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight',
	                    'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'
	            ]
	        }, '/', {
	            name : 'links',
	            items : [
	                    'Link', 'Unlink'
	            ]
	        }, {
	            name : 'insert',
	            items : [
	                    'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar'
	            ]
	        }, {
	            name : 'styles',
	            items : [
	                    'Font', 'FontSize'
	            ]
	        }, {
	            name : 'colors',
	            items : [
	                    'TextColor', 'BGColor'
	            ]
	        }, {
	            name : 'tools',
	            items : [
	                    'Maximize', 'ShowBlocks','Source'
	            ]
	        }
	];
};

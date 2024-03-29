/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
$(function() {
    CKFinder.setupCKEditor(null, '/js/ckfinder/');
    var editor = CKEDITOR.replace('LocationFacilities', {
        toolbar:
        [['Source','-','Save'],
 	['Cut','Copy','Paste','PasteText','PasteFromWord','-', 'SpellChecker', 'Scayt'],
 	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
 	'/',
 	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
 	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
 	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
 	['Link','Unlink','Image','Table','HorizontalRule','SpecialChar'],
 	'/',
 	['Styles','Format','Font','FontSize'],
 	['TextColor','BGColor'],
	['Maximize', 'ShowBlocks']]
    });
});
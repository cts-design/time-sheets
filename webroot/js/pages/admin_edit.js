/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2013
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
$(function() {
    CKFinder.setupCKEditor(null, '/js/ckfinder/');

    
    CKEDITOR.replace('PageContent', {
      toolbar: [
        ['Source'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-', 'SpellChecker', 'Scayt'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat', 'youtube'],
        '/',
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Anchor','Link','Unlink','Image','Table','HorizontalRule','SpecialChar'],
        '/',
        ['Styles','Format','Font','FontSize'],
        ['TextColor','BGColor'],
        ['Maximize', 'ShowBlocks']
      ]
    });

    
    CKEDITOR.replace('PageHeaderContent', {
      toolbar: [
        ['Source'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-', 'SpellChecker', 'Scayt'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat', 'youtube'],
        '/',
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Anchor','Link','Unlink','Image','Table','HorizontalRule','SpecialChar'],
        '/',
        ['Styles','Format','Font','FontSize'],
        ['TextColor','BGColor'],
        ['Maximize', 'ShowBlocks']
      ]
    });

    CKEDITOR.replace('PageFooterContent', {
      toolbar: [
        ['Source'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-', 'SpellChecker', 'Scayt'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat', 'youtube'],
        '/',
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Anchor','Link','Unlink','Image','Table','HorizontalRule','SpecialChar'],
        '/',
        ['Styles','Format','Font','FontSize'],
        ['TextColor','BGColor'],
        ['Maximize', 'ShowBlocks']
      ]
    });

    $(function () {
      var mainContentField = $('#PageContent'),
        headerContentField = $('#PageHeaderContent'),
        footerContentField = $('#PageFooterContent'),
        isLandingPage = $('#PageLandingPage:checked').length,
        landingPageField = $('#PageLandingPage');

      if (isLandingPage) {
        console.log('is landing page');
      } else {
        console.log('is not landing page');
      }

      headerContentField.css('display', 'none').parents('div.input').css('display', 'none').nextUntil('div.input').css('display', 'none');
      footerContentField.css('display', 'none').parents('div.input').css('display', 'none').nextUntil('div.input').css('display', 'none');

      landingPageField.bind('click', function (event) {
        if ($(this)[0].checked) {
          headerContentField.parents('div.input').css('display', 'block').nextUntil('div.input').css('display', 'block');
          footerContentField.parents('div.input').css('display', 'block').nextUntil('div.input').css('display', 'block');
          mainContentField.parents('div.input').css('display', 'none').nextUntil('div.input').css('display', 'none');
        } else {
          headerContentField.parents('div.input').css('display', 'none').nextUntil('div.input').css('display', 'none');
          footerContentField.parents('div.input').css('display', 'none').nextUntil('div.input').css('display', 'none');
          mainContentField.parents('div.input').css('display', 'block').nextUntil('div.input').css('display', 'block');
        }
      });
    });
});

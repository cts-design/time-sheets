/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
$(function() {
  $('.hidden').hide();
  $('.add-survey')
    .button()
    .bind('click', function() {
      $(this).next().click();

      return false;
    })
    .next()
    .button({
      text: false,
      icons: {
        primary: 'ui-icon-triangle-1-s'
      }
    })
    .bind('click', function() {
      var menu = $(this).next().show().css('position', 'absolute').position({
        my: 'right top',
        at: 'right bottom',
        of: this
      });

      $(document).one('click', function() {
        menu.hide();
      });

      return false;
    })
    .next()
    .hide()
    .menu()
    .children()
    .children()
    .button("destroy")
    .bind('click', function () {
      var parentTableRow = $(this).parent().parent().parent().parent(),
        kioskId = parentTableRow.attr('data-kiosk-id'),
        surveyId = $(this).attr('data-survey-id');

      $.ajax({
        type: 'POST',
        url: '/admin/kiosk_surveys/attach',
        data: { kiosk_id: kioskId, survey_id: surveyId },
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
          if (data.success) {
            parentTableRow.children('.actions')
                    .children('.add-survey')
                    .hide()
                    .next()
                    .hide();
            parentTableRow.children('.actions')
                    .children('.remove-survey')
                    .show();
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {

        }
      });
    });

  $('.remove-survey')
    .button()
    .bind('click', function() {
      var parentTableRow = $(this).parent().parent(),
        kioskId = parentTableRow.attr('data-kiosk-id');

      $.ajax({
        type: 'POST',
        url: '/admin/kiosk_surveys/detach',
        data: { kiosk_id: kioskId },
        dataType: 'json',
        success: function (data, textStatus, jqXHR) {
          if (data.success) {
            parentTableRow.children('.actions')
                    .children('.add-survey')
                    .show()
                    .next()
                    .show();
            parentTableRow.children('.actions')
                    .children('.remove-survey')
                    .hide();
          }
        },
        error: function (jqXHR, textStatus, errorThrown) { }
      });

      return false;
    });
});

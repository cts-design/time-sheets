/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
$(function() {
  $('.add-survey')
    .button()
    .bind('click', function() {
      alert('click');
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
    .button("destroy");

  $('.remove-survey')
    .button()
    .bind('click', function() {
      $(this).hide;
      $('.add-survey, .select-survey').show();

      return false;
    }).hide()
});

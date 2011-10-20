$(function() {
  $('#KioskSurveyQuestionsAnswer').hide();
  $('.text label').hide();
  $('.submit input').hide();
  $('.self-sign-survey-button').bind('click', function(e) {
    $('#KioskSurveyQuestionsAnswer').attr('value', $(this).attr('data-value'));
    $('.submit input').click();
  });
});

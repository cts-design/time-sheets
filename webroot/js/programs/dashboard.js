var Programs;

Programs = {
  $currentModule: null,
  $firstIncompleteItem: null,
  $allIncompleteSteps: null,
  $completeSteps: null,
  $stepsContainer: null,
  $programHeader: null,

  init: function () {
    $firstIncompleteItem = $('li.step.incomplete:first');
    $currentModule = $firstIncompleteItem.parents('li');
    $completeSteps = $('li.step.complete');
    $incompleteSteps = $('li.step.incomplete');
    $stepsContainer = $('ol.steps');
    $programHeader = $('li.program');
  },

  onReady: function () {
    Programs.init();

    $firstIncompleteItem.css('opacity', '1').addClass('current');
    $currentModule.addClass('current');

    $('span.status').each(function () {
      if ($(this).hasClass('incomplete')) {
        $(this).parents('li.module').addClass('incomplete');
      } else {
        $(this).parents('li.module').addClass('complete');
      }
    });

    $incompleteSteps.bind('click', function (e) {
      if (!$(this).hasClass('current') || !$(this).hasClass('redoable')) {
        e.preventDefault();
      }
    });

    $completeSteps.bind('click', function (e) {
      if (!$(this).hasClass('current') || !$(this).hasClass('redoable')) {
        e.preventDefault();
      }
    });
  }
};

$(document).ready(Programs.onReady);

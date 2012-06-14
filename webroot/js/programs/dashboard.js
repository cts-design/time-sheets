var Programs;

Programs = {
  $currentModule: null,
  $firstIncompleteItem: null,
  $allIncompleteSteps: null,
  $completeSteps: null,

  init: function () {
    $firstIncompleteItem = $('li.step.incomplete:first');
    $currentModule = $firstIncompleteItem.parents('li');
    $completeSteps = $('li.step.complete');
    $incompleteSteps = $('li.step.incomplete');
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
      if (!$(this).hasClass('current')) {
        e.preventDefault();
        alert('stop it');
      }
    });
  }
};

$(document).ready(Programs.onReady);

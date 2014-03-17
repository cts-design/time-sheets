var Programs;

Programs = {
  $currentModule: null,
  $firstIncompleteItem: null,
  $allIncompleteSteps: null,
  $completeSteps: null,
  $stepsContainer: null,
  $programHeader: null,
  $programStatus: null,
  $requiredDocs: null,

  init: function () {
    $firstIncompleteItem = $('li.step.incomplete:first');
    $currentModule = $firstIncompleteItem.parents('li');
    $completeSteps = $('li.step.complete');
    $incompleteSteps = $('li.step.incomplete');
    $stepsContainer = $('ol.steps');
    $programHeader = $('li.program');
    $programStatus = $('li.program .status');
    $requiredDocs = $('li.required_docs');
  },

  onReady: function () {
    Programs.init();

    $firstIncompleteItem.css('opacity', '1').addClass('current');
    $currentModule.addClass('current');

    //REMOVES the link from all the incomplete steps that are the not the next step
    incomps = $('ol.steps ol li.incomplete');
    /*
    incomps.each(function(){
      var c = $(this);

      if($(this).index() > 1)
      {
        $(this).find('a').attr('href', '');
      }
    });*/

    if (($programStatus.hasClass('pending_document_review') ||
        $programStatus.hasClass('not_approved')) &&
        $requiredDocs.hasClass('incomplete')) {
      $requiredDocs.removeClass('incomplete').removeClass('current').addClass('complete');
    }

    $('span.status').each(function () {
      if ($(this).hasClass('incomplete')) {
        $(this).parents('li.module').addClass('incomplete');
      } else {
        $(this).parents('li.module').addClass('complete');
      }
    });

    $incompleteSteps.bind('click', function (e) {
      if (!$(this).hasClass('current') && !$(this).hasClass('redoable') && !$(this).hasClass('required_docs')) {
        e.preventDefault();
      }
    });

    $completeSteps.bind('click', function (e) {
      if (!$(this).hasClass('current') && !$(this).hasClass('redoable') && !$(this).hasClass('required_docs')) {
        e.preventDefault();
      }
    });

    if (!$requiredDocs.parents('li').hasClass('current') && $requiredDocs.hasClass('incomplete')) {
      $requiredDocs.find('.action').find('a').remove();
    }
  }
};

$(document).ready(Programs.onReady);

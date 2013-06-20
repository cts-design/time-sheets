/**
 * jQuery Resizable-text Plugin
 * Version: 1.0.0
 * URL: http://projects.brandoncordell.com/resizable-text
 * Description: Adds a text resizer to the specified element for better usability
 * Requires:
 * Author: Brandon Cordell (http://brandoncordell.com)
 * Copyright: Copyright 2011 Brandon Cordell
 * License: Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 */

(function($) {
  $.fn.resizableText = function(options) {
    var opts = $.extend({}, $.fn.resizableText.defaults, options),
      deadLink = '<a href="">A</a>',
      unorderedList = $('<ul>').addClass('resizer'),
      smallListItem = $('<li>').addClass('small').html(deadLink),
      mediumListItem = $('<li>').addClass('medium').html(deadLink),
      largeListItem = $('<li>').addClass('large').html(deadLink),
      base = $(this),
      originalHeadingSize = {
        h1: base.find('h1').css('font-size'),
        h2: base.find('h2').css('font-size'),
        h3: base.find('h3').css('font-size'),
        h4: base.find('h4').css('font-size'),
        h5: base.find('h5').css('font-size'),
        h6: base.find('h6').css('font-size')
      },
      originalParagraphSize = base.find('p').css('font-size');

    unorderedList.append(smallListItem)
    .append(mediumListItem)
    .append(largeListItem)
    .prependTo(base);

    $('li.small a').bind('click', function(e) {
      e.preventDefault();

      for (var heading in originalHeadingSize) {
        if (originalHeadingSize[heading] !== undefined) {
          base.find(heading).css('font-size', originalHeadingSize[heading]);
        }
      }

      base.find('p').css('font-size', originalParagraphSize);
    });

    $('li.medium a').bind('click', function(e) {
      e.preventDefault();

      for (var heading in originalHeadingSize) {
        if (originalHeadingSize[heading] !== undefined) {
          var size = parseInt(originalHeadingSize[heading], 10),
            newSize = size + (size / 6);

          base.find(heading).css('font-size', newSize + "px");
        }
      }

      var paragraphSize = parseInt(originalParagraphSize, 10),
        newParagraphSize = paragraphSize + (paragraphSize / 6);

      base.find('p').css('font-size', newParagraphSize + "px");
    });

    $('li.large a').bind('click', function(e) {
      e.preventDefault();

      for (var heading in originalHeadingSize) {
        if (originalHeadingSize[heading] !== undefined) {
          var size = parseInt(originalHeadingSize[heading], 10),
            newSize = size + (size / 3);

          base.find(heading).css('font-size', newSize + "px");
        }
      }

      var paragraphSize = parseInt(originalParagraphSize, 10),
        newParagraphSize = paragraphSize + (paragraphSize / 3);

      base.find('p').css('font-size', newParagraphSize + "px");
    });

    return this.each(function() {
      var base = $(this);
    });
  };

  $.fn.resizableText.defaults = {
    followScroll: false
  };
})(jQuery); // end closure wrapper

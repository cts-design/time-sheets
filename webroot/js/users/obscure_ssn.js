// Fix trim() support in IE
if (typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
  }
}

$(function() {
  $('td.ssn').each(function(index) {
    var me = $(this),
    ssn = $(this).text().trim(),
    obscuredSsn = '*****' + ssn.substr( ssn.length - 5);

    me.data('full_ssn', ssn)
            .data('obscured_ssn', obscuredSsn)
            .text(obscuredSsn);
  });

  $('td.ssn').bind('mouseover', function() {
    var me = $(this);

    me.text( me.data('full_ssn') );
  }).bind('mouseout', function() {
    var me = $(this);

    me.text( me.data('obscured_ssn') );
  });
});

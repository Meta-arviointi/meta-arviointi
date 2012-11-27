/* Generated by CoffeeHelper at 2012-11-27 05:45:02 */

// Generated by CoffeeScript PHP 1.3.1
(function() {

  $(document).ready(function() {
    $('.collapsable').hide();
    $('.decollapse-toggle').click(function() {
      $(this).slideUp(200).next('.collapsable').slideDown(200);
      return false;
    });
    $('.collapse-toggle').click(function() {
      $(this).parents('.collapsable').slideUp(200).prev('.decollapse-toggle').slideDown(200);
      return false;
    });
    $('#StudentIndexFilters select').change(function() {
      return $(this).parents('form').submit();
    });
    $('.student-action-form').hide();
    $('#student-action-form-links a').on('click', function() {
      var form;
      $(this).addClass('active').siblings('a').removeClass('active');
      form = $('#' + $(this).data('action-type') + '-action-form');
      form.show().siblings('.student-action-form').hide();
      return false;
    });
    return $('.student-action-form a.cancel').on('click', function() {
      return $(this).parents('form').hide();
    });
  });

}).call(this);

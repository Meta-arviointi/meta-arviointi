/* Generated by CoffeeHelper at 2013-01-16 12:11:49 */

// Generated by CoffeeScript PHP 1.3.1
(function() {
  var datepickerDefaults;

  datepickerDefaults = {
    dayNames: ['Sunnuntai', 'Maanantai', 'Tiistai', 'Keskiviikko', 'Torstai', 'Perjantai', 'Lauantai'],
    dayNamesMin: ['Su', 'Ma', 'Ti', 'Ke', 'To', 'Pe', 'La'],
    monthNames: ['Tammikuu', 'Helmikuu', 'Maaliskuu', 'Huhtikuu', 'Toukokuu', 'Kesäkuu', 'Heinäkuu', 'Elokuu', 'Syyskuu', 'Lokakuu', 'Marraskuu', 'Joulukuu'],
    firstDay: 1,
    dateFormat: 'dd.mm.yy'
  };

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
      $('#student-action-form-links a').removeClass('active');
      $(this).parents('form').hide();
      return false;
    });
  });

}).call(this);

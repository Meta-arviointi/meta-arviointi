/* Generated by CoffeeHelper at 2013-01-23 23:18:11 */

// Generated by CoffeeScript PHP 1.3.1
(function() {

  window.datepickerDefaults = {
    dayNames: ['Sunnuntai', 'Maanantai', 'Tiistai', 'Keskiviikko', 'Torstai', 'Perjantai', 'Lauantai'],
    dayNamesMin: ['Su', 'Ma', 'Ti', 'Ke', 'To', 'Pe', 'La'],
    monthNames: ['Tammikuu', 'Helmikuu', 'Maaliskuu', 'Huhtikuu', 'Toukokuu', 'Kesäkuu', 'Heinäkuu', 'Elokuu', 'Syyskuu', 'Lokakuu', 'Marraskuu', 'Joulukuu'],
    firstDay: 1,
    dateFormat: 'dd.mm.yy'
  };

  $(document).ready(function() {
    $('.modal').hide();
    $('.modal-close, .modal-overlay').click(function() {
      $(this).parents('.modal').fadeOut(100);
      return false;
    });
    $('.modal-link').each(function() {
      var link;
      link = $(this);
      return link.click(function() {
        $('.modal').fadeIn(100);
        $('.modal-content').load(link.attr('href'));
        return false;
      });
    });
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
    $('.student-action-form a.cancel').on('click', function() {
      $('#student-action-form-links a').removeClass('active');
      $(this).parents('form').hide();
      return false;
    });
    $('.student-email-form').hide();
    $('#student-email-form-link').on('click', function() {
      $('#student-email-form').show();
      return false;
    });
    $('.student-email-form a.cancel').on('click', function() {
      $(this).parents('form').hide();
      return false;
    });
    $('input.datepicker').datepicker(datepickerDefaults);
    $('#mail-indicator a').on('click', function() {
      $('#mail-indicator').toggleClass('open');
      return false;
    });
    return $(window).on('click', function() {
      return $('#mail-indicator').removeClass('open');
    });
  });

}).call(this);

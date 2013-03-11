/* Generated by CoffeeHelper at 2013-03-11 23:14:43 */

// Generated by CoffeeScript PHP 1.3.1
(function() {

  window.datepickerDefaults = {
    dayNames: ['Sunnuntai', 'Maanantai', 'Tiistai', 'Keskiviikko', 'Torstai', 'Perjantai', 'Lauantai'],
    dayNamesMin: ['Su', 'Ma', 'Ti', 'Ke', 'To', 'Pe', 'La'],
    monthNames: ['Tammikuu', 'Helmikuu', 'Maaliskuu', 'Huhtikuu', 'Toukokuu', 'Kesäkuu', 'Heinäkuu', 'Elokuu', 'Syyskuu', 'Lokakuu', 'Marraskuu', 'Joulukuu'],
    timeText: 'Aika',
    hourText: 'Tunti',
    minuteText: 'Minuutti',
    firstDay: 1,
    dateFormat: 'dd.mm.yy'
  };

  $.fn.setCursorPosition = function(pos) {
    var range;
    if ($(this).get(0).setSelectionRange) {
      $(this).get(0).setSelectionRange(pos, pos);
    } else if ($(this).get(0).createTextRange) {
      range = $(this).get(0).createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
    return $(this);
  };

  $(document).ready(function() {
    var body, chat, chat_input, chat_messages, chat_refresh, chat_refreshing, chat_scroll_bottom, chat_viewport, hideModal, modal, showModal, studentEmailFormContainer;
    $.scrollTo(0);
    modal = $('.modal');
    body = $('body');
    showModal = function() {
      modal.fadeIn(100);
      body.addClass('modal-visible');
    };
    hideModal = function() {
      modal.fadeOut(100);
      body.removeClass('modal-visible');
    };
    $('.modal').hide();
    $('.modal-close, .modal-overlay').click(function() {
      hideModal();
      return false;
    });
    $('.modal-link').each(function() {
      var link;
      link = $(this);
      return link.click(function() {
        if (!$(this).hasClass('is-disabled')) {
          $('.modal-content').empty();
          showModal();
          $('.modal-content').load(link.attr('href'));
        }
        return false;
      });
    });
    $(window).keyup(function(e) {
      if (e.keyCode === 27) {
        return hideModal();
      }
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
    $('#StudentsList, #ActionsList').ajaxfilters();
    $('#UserCourseSelection select').change(function() {
      return $(this).parents('form').submit();
    });
    $('#UserCourseSelection select').on('click', function(e) {
      return e.stopPropagation();
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
    $('#student-email-form').hide();
    $('#student-email-form-link').on('click', function() {
      $('#student-email-form').show();
      return false;
    });
    $('#student-email-form a.cancel').on('click', function() {
      $(this).parents('form').hide();
      return false;
    });
    $('input.datepicker').datepicker(datepickerDefaults);
    $('input.datetimepicker').datetimepicker(datepickerDefaults);
    $('#mail-indicator > a').on('click', function() {
      if ($(this).text() !== '0') {
        $('#mail-indicator').toggleClass('open');
      }
      return false;
    });
    $('#course-selection-toggle > a').on('click', function() {
      if ($(this).text() !== '0') {
        $('#course-selection-toggle').toggleClass('open');
      }
      return false;
    });
    $('#TextFilterKeyword').keyup(function() {
      $('#ActionsList tr.table-content').hide();
      $('#ActionsList tr.table-content:has(td:contains(' + $(this).val() + '))').show();
      return false;
    });
    chat = $('#chat');
    if (chat[0]) {
      chat_viewport = chat.find('.chat-viewport');
      chat_messages = chat_viewport.find('.chat-messages');
      chat_input = $('#chat-input');
      chat_refreshing = false;
      chat_scroll_bottom = function() {
        chat_viewport.scrollTo('max', 500);
      };
      chat_scroll_bottom();
      $('#chat-toggle').on('click', function() {
        var st, state;
        chat.toggleClass('open');
        chat_scroll_bottom();
        state = 'closed';
        if (chat.hasClass('open')) {
          st = $(window).scrollTop();
          chat_input.focus();
          $(window).scrollTop(st);
          state = 'open';
        } else {
          chat_input.blur();
        }
        $.ajax({
          type: "POST",
          dataType: "json",
          url: window.baseUrl + 'chat_messages/set_chat_window_state',
          data: {
            chat_window_state: state
          }
        });
        return false;
      });
      chat_refresh = function() {
        var last_id;
        if (!chat_refreshing) {
          chat_refreshing = true;
          last_id = chat_messages.children().last().attr('data-msg-id');
          if (!(last_id != null)) {
            last_id = 0;
          }
          $.ajax({
            dataType: "json",
            url: window.baseUrl + 'chat_messages/get_recent/' + last_id + '.json',
            success: function(data) {
              var msg, _i, _len;
              chat_refreshing = false;
              for (_i = 0, _len = data.length; _i < _len; _i++) {
                msg = data[_i];
                if (chat_messages.children('.chat-message[data-msg-id="' + msg.id + '"]').length === 0) {
                  chat_messages.append('<div class="chat-message" data-msg-id="' + msg.id + '">\
                                    <span class="user">' + msg.user + '</span>\
                                    <p class="chat-message-content">' + msg.content + '</p>\
                                </div>');
                }
              }
              console.log(Math.abs(chat_viewport.scrollTop() - (chat_messages.height() - chat_viewport.height())));
              if (Math.abs(chat_viewport.scrollTop() - (chat_messages.height() - chat_viewport.height())) < 100) {
                console.log('scroll required');
                chat_scroll_bottom();
              }
            },
            error: function(qXHR, textStatus, errorThrown) {
              chat_refreshing = false;
            }
          });
        }
      };
      setInterval(chat_refresh, 5000);
      chat_input.keyup(function(e) {
        var msg;
        if (e.keyCode === 13) {
          msg = chat_input.val();
          chat_input.val('');
          $.ajax({
            type: "POST",
            dataType: 'json',
            url: window.baseUrl + 'chat_messages/send',
            data: {
              msg: msg
            },
            success: function() {
              chat_refresh();
            },
            error: function(qXHR, textStatus, errorThrown) {
              alert(errorThrown);
            }
          });
          return chat_scroll_bottom();
        }
      });
    }
    $(window).on('click', function() {
      $('#mail-indicator').removeClass('open');
      return $('#course-selection-toggle').removeClass('open');
    });
    studentEmailFormContainer = $('#student-email-form-container');
    window.emailAction = function(actionID) {
      $('#student-email-form').slideDown(500);
      $.scrollTo(studentEmailFormContainer, 500, {
        offset: {
          top: -120
        }
      });
      $.ajax({
        dataType: "json",
        url: window.baseUrl + 'actions/get_email_template/' + actionID + '.json',
        success: function(data) {
          $('#EmailMessageSubject').val(data.subject);
          $('#EmailMessageContent').val(unescape(data.content));
        },
        error: function(qXHR, textStatus, errorThrown) {
          alert(errorThrown);
        }
      });
      return false;
    };
    $.urlParam = function(name) {
      var results;
      results = new RegExp("[\\?&]" + name + "=([^&#]*)").exec(window.location.href);
      if (!results) {
        return 0;
      }
      return results[1] || 0;
    };
    $('.reply-to-email').click(function() {
      var line, mailContent, newContent, _i, _len, _ref;
      $('#EmailMessageSubject').val('Re: ' + $(this).parents('.email-message').find('.email-subject').text());
      mailContent = $(this).parents('.email-message').find('.email-content').text();
      newContent = "\n\n";
      _ref = mailContent.split("\n");
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        line = _ref[_i];
        newContent += ">" + line + "\n";
      }
      $('#EmailMessageContent').val(newContent).setCursorPosition(0);
      $.scrollTo(studentEmailFormContainer, 500, {
        offset: {
          top: -120
        }
      });
      return $('#student-email-form').slideDown(500);
    });
    $(document).tooltip();
  });

}).call(this);

# default configs for datepicker:

window.datepickerDefaults = {
    dayNames: [
        'Sunnuntai', 
        'Maanantai', 
        'Tiistai', 
        'Keskiviikko', 
        'Torstai', 
        'Perjantai', 
        'Lauantai'
    ]
    dayNamesMin: ['Su', 'Ma', 'Ti', 'Ke', 'To', 'Pe', 'La']
    monthNames: [
        'Tammikuu',
        'Helmikuu',
        'Maaliskuu',
        'Huhtikuu',
        'Toukokuu',
        'Kesäkuu',
        'Heinäkuu',
        'Elokuu',
        'Syyskuu',
        'Lokakuu',
        'Marraskuu',
        'Joulukuu'
    ]
    firstDay: 1 #week starts on monday
    dateFormat: 'dd.mm.yy'
}

$(document).ready ->
    $.scrollTo 0

    $('.modal').hide()
    $('.modal-close, .modal-overlay').click -> 
        $(this).parents('.modal').fadeOut 100
        false

    $('.modal-link').each ->
        link = $(this)
        link.click ->
            $('.modal').fadeIn 100
            $('.modal-content').load link.attr('href')
            false

    $('.collapsable').hide()

    $('.decollapse-toggle').click ->
        $(this).slideUp(200).next('.collapsable').slideDown 200
        false

    $('.collapse-toggle').click ->
        $(this).parents('.collapsable').slideUp(200).prev('.decollapse-toggle').slideDown 200
        false

    $('#StudentIndexFilters select').change ->
        $(this).parents('form').submit()

    $('#UserCourseSelection select').change ->
        $(this).parents('form').submit()

    # Action form functionality
    $('.student-action-form').hide()

    $('#student-action-form-links a').on 'click', ->
        $(this).addClass('active').siblings('a').removeClass 'active'

        form = $('#' + $(this).data('action-type') + '-action-form')
        form.show().siblings('.student-action-form').hide()
        false

    $('.student-action-form a.cancel').on 'click', ->
        $('#student-action-form-links a').removeClass 'active'
        $(this).parents('form').hide()
        false

    # Action form functionality
    $('#student-email-form').hide()

    $('#student-email-form-link').on 'click', ->
        $('#student-email-form').show()
        false

    $('#student-email-form a.cancel').on 'click', ->
        $(this).parents('form').hide()
        false

    $('input.datepicker').datepicker datepickerDefaults
    #$('#InputFieldId').datepicker datepickerDefaults

    $('#mail-indicator > a').on 'click', ->
        $('#mail-indicator').toggleClass 'open'
        false

    chat = $('#chat')
    chat_viewport = chat.find('.chat-viewport')
    chat_messages = chat_viewport.find('.chat-messages')
    chat_input = $('#chat-input')

    chat_scroll_bottom = ->
        chat_viewport.scrollTop(chat_messages.height() - chat_viewport.height())
        return

    $('#chat-toggle').on 'click', ->
        chat.toggleClass 'open'
        chat_scroll_bottom()
        if chat.hasClass 'open'
            chat_input.focus()
        else
            chat_input.blur()
        false

    chat_input.keyup (e) ->
        if e.keyCode == 13
            msg = chat_input.val()
            chat_input.val('')
            chat_messages.append('<div class="chat-message">
                    <span class="user">Asseri Assistentti</span>
                    <p class="chat-message-content">'+msg+'</p>
                </div>')
            chat_scroll_bottom()

    $(window).on 'click', ->
        $('#mail-indicator').removeClass 'open'

    studentEmailFormContainer = $('#student-email-form-container')
    window.emailAction = (actionID) ->
        $('#student-email-form').show()
        $.scrollTo studentEmailFormContainer, 500, {offset: {top: -120}}
        $.ajax
            dataType: "json"
            url: window.baseUrl + 'actions/get_email_template/' + actionID + '.json'
            success: (data) ->
                $('#MailTitle').val data.title
                $('#MailContent').val data.content
            error: (qXHR, textStatus, errorThrown) ->
                alert errorThrown
        false


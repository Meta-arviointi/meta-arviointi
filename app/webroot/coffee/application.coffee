# default configs for datepicker:

window.datepickerDefaults =
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
    timeText: 'Aika'
    hourText: 'Tunti'
    minuteText: 'Minuutti'
    firstDay: 1 #week starts on monday
    dateFormat: 'dd.mm.yy'
    showButtonPanel: true
    closeText: 'Sulje'
    currentText: 'Nyt'

$.fn.setCursorPosition = (pos) ->
    if $(this).get(0).setSelectionRange
        $(this).get(0).setSelectionRange pos, pos 
    else if $(this).get(0).createTextRange
        range = $(this).get(0).createTextRange()
        range.collapse true
        range.moveEnd 'character', pos
        range.moveStart 'character', pos
        range.select()
    return $(this)

$(document).ready ->
    $.scrollTo 0

    modal = $('.modal')
    body = $('body')

    showModal = ->
        modal.fadeIn 100
        body.addClass 'modal-visible'
        return

    hideModal = ->
        modal.fadeOut 100
        body.removeClass 'modal-visible'
        return

    $('.modal').hide()

    $('.modal-close').click -> 
        hideModal()
        false

    $('.modal-link').each ->
        link = $(this)
        link.click ->
            if(!$(this).hasClass('is-disabled'))
                $('.modal-content').empty()
                showModal()
                $('.modal-content').load link.attr('href')
            false

    $(window).keyup (e) ->
        hideModal() if e.keyCode == 27

    $('.collapsable').hide()

    $('.decollapse-toggle').click ->
        $(this).slideUp(200).next('.collapsable').slideDown 200
        false

    $('.collapse-toggle').click ->
        $(this).parents('.collapsable').slideUp(200).prev('.decollapse-toggle').slideDown 200
        false


    # Ajax filtering
    $('#StudentsList, #ActionsList, #StudentsListAdmin, #CourseListAdmin, #UsersList').ajaxfilters()

    $('#UserCourseSelection select').change ->
        $(this).parents('form').submit()

    $('#UserCourseSelection select').on 'click', (e) ->
        e.stopPropagation()

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
        $('#EmailMessageSubject, #EmailMessageContent').val ''
        $(this).parents('form').hide()
        false

    $('input.datepicker').datepicker datepickerDefaults
    $('input.datetimepicker').datetimepicker datepickerDefaults
    #$('#InputFieldId').datepicker datepickerDefaults

    $('#mail-indicator > a').on 'click', ->
        $('#mail-indicator').toggleClass('open') if $(this).text() != '0'
        false

    $('#course-selection-toggle > a').on 'click', ->
        $('#course-selection-toggle').toggleClass('open') if $(this).text() != '0'
        false

    $('#TextFilterKeyword').keyup ->
        $('#ActionsList tr.table-content').hide()
        $('#ActionsList tr.table-content:has(td:contains('+$(this).val()+'))').show()
        false

    chat = $('#chat')
    if chat[0]
        chat_viewport = chat.find('.chat-viewport')
        chat_messages = chat_viewport.find('.chat-messages')
        chat_input = $('#chat-input')
        chat_refreshing = false

        chat_scroll_bottom = (animate = true) ->
            chat_viewport.scrollTo 'max', (if animate then 500 else 0)
            return
        chat_scroll_bottom(false)

        $('.chat-header').on 'click', ->
            chat.toggleClass 'open'
            chat_scroll_bottom()
            state = 'closed'
            if chat.hasClass 'open'
                st = $(window).scrollTop()
                chat_input.focus()
                $(window).scrollTop(st)
                state = 'open'
            else
                chat_input.blur()
            $.ajax
                type: "POST",
                dataType: "json"
                url: window.baseUrl + 'chat_messages/set_chat_window_state'
                data: {chat_window_state: state}
            false

        chat_refresh = ->
            if not chat_refreshing
                chat_refreshing = true
                last_id = chat_messages.children().last().attr('data-msg-id')
                last_id = 0 if !last_id?
                $.ajax
                    dataType: "json"
                    url: window.baseUrl + 'chat_messages/get_recent/' + last_id + '.json'
                    success: (data) ->
                        chat_refreshing = false
                        for msg in data
                            if chat_messages.children('.chat-message[data-msg-id="'+msg.id+'"]').length == 0
                                chat_messages.append('<div class="chat-message" data-msg-id="' + msg.id + '">
                                    <span class="timestamp">' + msg.timestamp + '</span>
                                    <span class="user">' + msg.user + '</span>
                                    <p class="chat-message-content">' + msg.content + '</p>
                                </div>')
                        console.log Math.abs(chat_viewport.scrollTop() - (chat_messages.height() - chat_viewport.height()))
                        if(Math.abs(chat_viewport.scrollTop() - (chat_messages.height() - chat_viewport.height())) < 100)
                            console.log 'scroll required'
                            chat_scroll_bottom()
                        return
                    error: (qXHR, textStatus, errorThrown) ->
                        chat_refreshing = false
                        #alert errorThrown
                        return
            return

        setInterval chat_refresh, 5000

        chat_input.keyup (e) ->
            if e.keyCode == 13
                msg = chat_input.val()
                chat_input.val('')
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: window.baseUrl + 'chat_messages/send',
                    data: {msg: msg},
                    success: ->
                        chat_refresh()
                        return
                    error: (qXHR, textStatus, errorThrown) ->
                        alert errorThrown
                        return
                })
                chat_scroll_bottom()

    $(window).on 'click', ->
        $('#mail-indicator').removeClass 'open'
        $('#course-selection-toggle').removeClass 'open'

    studentEmailFormContainer = $('#student-email-form-container')
    window.emailAction = (actionID) ->
        $('#student-email-form').slideDown 500
        $.scrollTo studentEmailFormContainer, 500, {offset: {top: -120}}
        $.ajax
            dataType: "json"
            url: window.baseUrl + 'actions/get_email_template/' + actionID + '.json'
            success: (data) ->
                $('#EmailMessageSubject').val data.subject
                $('#EmailMessageContent').val unescape(data.content)
                return
            error: (qXHR, textStatus, errorThrown) ->
                alert errorThrown
                return
        false

    $.urlParam = (name) ->
        results = new RegExp("[\\?&]" + name + "=([^&#]*)").exec(window.location.href)
        return 0  unless results
        results[1] or 0

    $('.reply-to-email').click ->
        $('#EmailMessageSubject').val 'Re: ' + $(this).parents('.email-message').find('.email-subject').text()
        mailContent = $(this).parents('.email-message').find('.email-content').text()
        newContent = "\n\n"
        for line in mailContent.split("\n")
            newContent += ">" + line + "\n"
        $('#EmailMessageContent').val(newContent).setCursorPosition 0

        $.scrollTo studentEmailFormContainer, 500, {offset: {top: -120}}
        $('#student-email-form').slideDown 500

    $(document).tooltip
        tooltipClass: 'meta-tooltip'
    
    return



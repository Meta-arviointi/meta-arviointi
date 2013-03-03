(($) ->
    $.fn.ajaxfilters = (settings) ->
        defaults =
            callback:   null

        settings = $.extend {}, defaults, settings
        $.param.fragment.noEscape("[],");

        return this.each ->
            table = $(this)
            id = table.attr 'id'
            tbody = table.children 'tbody'

            forms = $('*[data-target="'+id+'"]')
            inputs = forms.find 'input, select'

            inputs.change ->
                params = {}
                state = {}
                for i in inputs
                    val = $(i).val()
                    params[$(i).attr('name')] = val if val != ''
                state[id] = params
                $.bbq.pushState state

            updateTable = ->
                state = $.bbq.getState id

                for key, val of state
                    forms.find('*[name='+key+']').val val

                tbody.children('tr').each ->
                    show = true
                    for key, val of state
                        show = false if val != '' && $(this).attr('data-' + key) != val
                    $(this).toggle show

            $(window).bind 'hashchange', (e) ->
                updateTable()

            #initial filtering on page load
            updateTable()
)(jQuery)

(($) ->
    $.fn.ajaxfilters = (settings) ->
        defaults =
            callback:   null

        settings = $.extend {}, defaults, settings
        $.param.fragment.noEscape "[],"

        return this.each ->
            table = $(this)
            id = table.attr 'id'
            tbody = table.children 'tbody'

            forms = $('*[data-target="'+id+'"]')
            inputs = forms.find 'input, select'

            getInputParams = ->
                params = {}
                for i in inputs
                    $i = $(i)
                    val = $i.val()
                    if $i.attr('type') != 'checkbox' || $i.is(':checked')
                        console.log i
                        console.log val
                        params[$i.attr('name')] = val if val != ''
                return params

            pushParams = (mergeMode) ->
                state = {}
                params = getInputParams()
                state[id] = params
                $.bbq.pushState state, mergeMode
                return

            inputs.change ->
                # override url parameters with input values
                pushParams 0

            # get default parameters from inputs, but do not override url
            pushParams 1

            updateTable = ->
                state = $.bbq.getState id

                for key, val of state
                    forms.find('select[name='+key+']').val val

                tbody.children('tr').each ->
                    show = true
                    for key, val of state
                        dataAttr = $(this).attr('data-' + key)
                        #single value
                        if dataAttr.indexOf(',') == -1
                            show = false if val != '' && dataAttr != val
                        #multiple values
                        else
                            match = false
                            for da in dataAttr.split(',')
                                match = true if da == val
                            show = match

                    $(this).toggle show

            $(window).bind 'hashchange', (e) ->
                updateTable()

            #initial filtering on page load
            updateTable()

            table.tablesorter()
)(jQuery)

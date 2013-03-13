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

            visibleRows = []

            getInputParams = ->
                params = {}
                for i in inputs
                    $i = $(i)
                    val = $i.val()
                    if $i.attr('type') != 'checkbox' || $i.is(':checked')
                        params[$i.attr('name')] = val if val != ''
                return params

            pushParams = (mergeMode) ->
                state = {filters: 'true'}
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

                visibleRows = []

                tbody.children('tr').each ->
                    show = true
                    for key, val of state
                        dataAttr = $(this).attr('data-' + key)
                        if dataAttr?
                            #single value
                            if dataAttr.indexOf(',') == -1
                                show = false if val != '' && dataAttr != val
                            #multiple values
                            else
                                match = false
                                for da in dataAttr.split(',')
                                    match = true if da == val
                                show = match
                        else
                            show = false

                    $(this).toggle show
                    visibleRows.push(this) if show

            $(window).bind 'hashchange', (e) ->
                updateTable()

            #initial filtering on page load
            updateTable()

            table.tablesorter()


            #free text find
            keywordInputs = $('input.filter-keyword[data-target="'+id+'"]')
            keywordInputs.each ->
                input = $(this)
                current = tbody.children('tr.highlight')
                input.keydown (e) ->
                    current = tbody.children('tr.highlight')
                    vrs = $(visibleRows).filter(':not(:hidden)')
                    if e.keyCode == 38 #up
                        if current.length < 1
                            next = vrs.last()
                        else
                            next = current.prev('tr:not(:hidden)')
                            if next.length < 1
                                next = current.prevUntil('tr:not(:hidden)').last().prev()
                            if next.length < 1
                                next = vrs.last()
                        next.addClass('highlight').siblings().removeClass 'highlight'
                        e.preventDefault()
                        return false

                    else if e.keyCode == 40 #down
                        if current.length < 1
                            next = vrs.first()
                        else
                            next = current.next('tr:not(:hidden)')
                            if next.length < 1
                                next = current.nextUntil('tr:not(:hidden)').last().next()
                            if next.length < 1
                                next = vrs.first()

                        next.addClass('highlight').siblings().removeClass 'highlight'
                        e.preventDefault()
                        return false

                    else if e.keyCode == 13 #enter
                        if(current.length > 0)
                            window.location = current.find('a').first().attr('href')
                        e.preventDefault()
                        return false

                    else if e.keyCode == 32 #space
                        if(current.length > 0)
                            check = current.find('input[type="checkbox"]').first()
                            check.trigger 'click'
                            e.preventDefault()
                            return false

                input.keyup (e) ->                    
                    keyword = input.val().toLowerCase()
                    for row in visibleRows
                        match = false
                        $(row).children('td').each ->
                            match = true if $(this).text().toLowerCase().indexOf(keyword) >= 0
                        $(row).toggle match

                input.blur ->
                    current = []
                    tbody.children('tr').removeClass 'highlight'

            keywordInputs.first().focus()
)(jQuery)

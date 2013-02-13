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
            console.log inputs
            ###
            inputs.change ->
                state = {}
                target = form.attr 'data-target'
                params = {}
                for i in inputs
                    params[$(i).attr('name')] = $(i).val()
                state[target] = params
                $.bbq.pushState state
            ###

            inputs.change ->
                params = {}
                state = {}
                for i in inputs
                    params[$(i).attr('name')] = $(i).val()
                state[id] = params
                $.bbq.pushState state

            $(window).bind 'hashchange', (e) ->
                state = $.bbq.getState()
                console.log state
                for key, val of state
                    forms.find('*[name='+key+']').val val

                tbody.load table.attr('data-source')
)(jQuery)



###

    # AJAX FILTERING AND SORTING

    $.param.fragment.noEscape("[],");

    q = window.location.hash
    $('#browse-filters select').change ->
        $.bbq.pushState $(this).parents('form').serialize()
        return

    $('#browse-sorters input').click ->
        # Just reordering ASC/DESC?
        newSort = $(this).attr('id').replace('sort-by-', '');
        if $.bbq.getState('sort') == newSort
            if$.bbq.getState('order') == 'DESC'
                $.bbq.pushState 'order=ASC'
            else
                $.bbq.pushState 'order=DESC'
        # Real sorting
        else
            $.bbq.pushState('sort=' + newSort + '&order=ASC');
        return false

    $(window).bind('hashchange', function(e) {
        $.each(['make','adapter','system','class','state'], function(k,v) {
            if(e.getState(v)) $('#browse-filters select[name='+v+']').val(e.getState(v));
        });
        
        if($.bbq.getState('quick') && $.bbq.getState('quick') != "") {
            var quick = $.bbq.getState('quick').split(',');
            $('#quick-links a').each(function() {
                if($.inArray($(this).attr('href').replace('#', ''), quick) == -1) $(this).removeClass('active');
                else $(this).addClass('active');
            }); 
        }
        else {
            $('#quick-links a').removeClass('active');
        }
        
        $('#browse-sorters input').removeClass('active').find('#sort-by-' + $.bbq.getState('sort')).addClass('active');
        
        $.get('/browse_ajax.php?' + e.fragment, function(data) {
            $('#browse-items tbody').html(data);
            $('#total-amount').text($('#browse-items tbody').children().length);
        });
    });

    $(window).trigger('hashchange');



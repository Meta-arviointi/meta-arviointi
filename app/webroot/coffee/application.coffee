$(document).ready ->

    $('.collapsable').hide()

    $('.decollapse-toggle').click ->
        $(this).slideUp(200).next('.collapsable').slideDown 200
        false

    $('.collapse-toggle').click ->
        $(this).parents('.collapsable').slideUp(200).prev('.decollapse-toggle').slideDown 200
        false

    $('#StudentIndexFilters select').change ->
        $(this).parents('form').submit()

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
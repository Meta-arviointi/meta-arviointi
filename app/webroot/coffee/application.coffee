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
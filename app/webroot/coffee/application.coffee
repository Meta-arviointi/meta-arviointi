$(document).ready ->
    $('#add-notification-link').click ->
        $('#add-notification-form').slideDown(100).find('textarea').focus()
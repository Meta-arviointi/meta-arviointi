$(document).ready ->
    $('#add-notification-link').click ->
        $('#add-notification-form').slideDown(200).find('textarea').focus()
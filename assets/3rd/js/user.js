var user_id = 0;

$.ajax({
    dataType: 'json',
    url: '/rest/get_session',
    success: function(session) {
        if (session.success)
        {
            user_id = session.data.user.ID;
            var htmlText = session.data.user.username + "  <span class='caret'></span>"
            $('#user-btn').html(htmlText)
        }
    }
});

$('#logout').click(function() {
    $.ajax({
        dataType: 'json',
        url: '/rest/logout_session',
        success: function(session) {
            location.href = '/home';
        }
    });
});

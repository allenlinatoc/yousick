$('#logout').click(function() {
    $.ajax({
        dataType: 'json',
        url: '/rest/logout_session',
        success: function(session) {
            location.href = '/home';
        }
    });
});

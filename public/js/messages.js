(function() {
    function activeMessageTab(type) {
        $('ul.nav-pills').find('.active').removeClass('active');
        $('ul.nav-pills').find('li').eq(type).addClass('active');
    }

    $('.js-msg-type').on('click', function(e) {
        var type = $(this).find('a').data('type');
        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');
        $.ajax({
            url: '/get_messages',
            type: 'POST',
            data: {
                type: type,
            },
            success: function(data) {
                var messages = data.messages;
                var html_messages = template('messages', {
                    messages: messages
                });
                $('#main').html(html_messages);
                activeMessageTab(type);
            }
        })
    })

})()

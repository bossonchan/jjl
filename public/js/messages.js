(function() {
    function activeMessageTab(type) {
        $('ul.nav-pills').find('.active').removeClass('active');
        $('ul.nav-pills').find("[data-type='"+type+"']").parent().addClass('active');
    }

    $('.js-msg-type').on('click', function(e) {
        var type = $(this).find('a').data('type');
        $.ajax({
            url: BaseUrl + '/messages',
            type: 'get',
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

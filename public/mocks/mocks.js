// mocks data
window.MOCKS = {
    hoods: [{
        id: 1,
        name: 'hood1'
    }, {
        id: 2,
        name: 'hood2'
    }],
    list: ['文艺', '博客', '摄影', '电影', '民谣', '旅行', '吉他'],

    blocks: [{
        id: 3,
        name: 'block1',
    }, {
        id: 4,
        name: 'block2'
    }],

    members: [{
        id: 3,
        name: 'Shabi',
        follow: false,
    }, {
        id: 4,
        name: 'Kitt',
        follow: true,
    }],

    messages: [{
        title: 'this is a private',
        content: 'i am a content',
        from: 'jijilong',
        from_id: '1',
        type: 'private'
    }, {
        title: 'this is a friend',
        content: 'i am a content2',
        from: 'jijilong',
        from_id: '1',
        type: 'friend'
    }, {
        title: 'this is a block',
        content: 'i am a content2',
        from: 'jijilong',
        from_id: '1',
        type: 'block'
    }],

    friends: [{
        id: 5,
        name: 'jiji'
    }, {
        id: 6,
        name: 'hu'
    }],
    invitations: [{
        id: 3,
        name: 'jiji'
    }, {
        id: 4,
        name: 'tt'
    }]
};

// mock ajax server
$.mockjax({
    url: "/login",
    response: function(setting) {
        var data = setting.data;
        if (data.email === 'q@qq.com' && data.password === '1') {
            this.responseText = {
                status: "success",
                user: {
                    id: 1,
                    nick_name: 'jijilong',
                    email: 'q@qq.com'
                }
            }
        } else {
            this.responseText = {
                status: 'fail',
                msg: 'login fail'
            }
        }
    }
});
$.mockjax({
    url: "/signup",
    responseText: {
        status: "success",
        user: {
            id: 1,
            nick_name: 'jijilong',
            email: 'q@qq.com'
        }
    }
});

$.mockjax({
    url: "/delete-friend",
    responseText: {
        status: "success",
    }
});
$.mockjax({
    url: "/add-friend",
    responseText: {
        status: "success",
    }
});
$.mockjax({
    url: "/agree_friend",
    responseText: {
        status: "success",
    }
});
$.mockjax({
    url: "/reject_friend",
    responseText: {
        status: "success",
    }
});

$.mockjax({
    url: "/get_messages",
    response: function(setting) {
        var type = setting.data.type
        this.responseText = {
            status: 'success',
            messages: type === 0 ? MOCKS.messages : [MOCKS.messages[type - 1]]
        }
    }
});

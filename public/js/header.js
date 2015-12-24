/* global template, MOCKS*/
(function() {

getHashAndRenderBody();
function getHashAndRenderBody(){
    var hash = location.hash; 
    console.log('header', hash);
    var map = {
        '#hoods': renderHoods,
        '#messages': renderMessages,
        '#post': renderPost,
        'friends': renderFriends
    }
    map[hash] ? map[hash]() : renderHoods();
}

// listen events
$('.js-logout').on('click', function(e) {
    $.ajax({
        url: BaseUrl + '/session',
        type: 'delete',
        success: function(data) {
            location.href = './index.html';
        }
    })
});

$('.js-tab-hoods').on('click', function(e) {
    renderHoods();
});
$('.js-tab-messages').on('click', function(e) {
    renderMessages();
});
$('.js-tab-post').on('click', function(e) {
    renderPost();
});
$('.js-tab-friends').on('click', function(e) {
    renderFriends();
});

function setTabActive(index) {
    $('.navbar-nav li').each(function() {
        $(this).removeClass('active');
    });
    $('.navbar-nav li').eq(index).addClass('active');
}

function renderHoods() {
    $.ajax({
        url: BaseUrl + '/hoods',
        type: 'get',
        success: function(data) {
            hoods = data.hoods;
            var html_hoods = template('hoods', {hoods: hoods});
            location.hash = '#hoods';
            $('#main').html(html_hoods);
            setTabActive(0);
        }
    })
}

function renderMessages() {
    $.ajax({
        url: BaseUrl + '/messages',
        type: 'get',
        data: {
            type: 'all',
        },
        success: function(data) {
            var messages = data.messages;
            var html_messages = template('messages', {
                messages: messages
            });
            $('#main').html(html_messages);
        }
    })
    location.hash = '#messages';
    setTabActive(1);
}

function renderPost() {
    var html_post = template('post');
    $('#main').html(html_post);
    location.hash = '#post';
    setTabActive(2);
}

function renderFriends() {
    $.ajax({
        url: BaseUrl + '/friends',
        type: 'get',
        success: function(data) {
            var friends = data.friends;
            getInvitationAndRender(friends);
        }
    })
 } 

function getInvitationAndRender(friends) {
    $.ajax({
        url: BaseUrl + '/friend_request',
        type: 'get',
        success: function(data) {
            var invitations = data.requests;
            var html_post = template('friends', {
                friends: friends,
                invitations: invitations
            });
            $('#main').html(html_post);
            location.hash = '#friends';
            setTabActive(3);
        }
    })
}

})();

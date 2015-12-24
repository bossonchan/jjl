(function () {
    // body...
    $('.js-post').on('click', function(e) {
        var friendValid = true;
        var title = getTitle();
        var content = title ? getContent : false;
        var type = content ? getType() : false
        if (type === 'private') {
            var friendName = getFriendName();
            friendValid = !!friendName;
        };
        valid =  friendValid && title && content;
        var data = {
            m_type: type,
            m_title: title,
            m_content: content,
            m_hood: 1,
        }
        if( type === 'private' ) {
          data.m_to = friendName;
        }
        valid && $.ajax({
            url: BaseUrl + '/messages',
            type: 'post',
            data: data,
            success: function(data) {
                alert('post success');
                location.reload();
            }
        });
        return false
    });

function getTitle() {
    var title = $('#inputTitle').val();
    if(!title) {
        alert('input title please');
    }
    return title ? title : false;
}

function getContent() {
    var content = $('#inputContent').val();
    if(!content) {
        alert('input content please');
    }
    return content ? content: false;
}

function getType() {
    var type = $("input[type='radio']:checked").val();
    return type;
}

function getFriendName() {
    var friend = $('#inputFriend').val();
    if (!friend) {
        alert('input friend\'s name');
    }
    return friend ? friend : false;
}
})()
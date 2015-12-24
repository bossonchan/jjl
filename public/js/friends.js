(function() {
    $('.js-remove').on('click', function(e) {
        var $this = $(this);
        var id = $this.data('id');
        var name = $this.closest('li').find('span').text();
        confirm('Are you sure?') && $.ajax({
            url: BaseUrl + '/friends/' + id,
            type: 'delete',
            data: {
                uid: id
            },
            success: function() {
                $this.closest('li').remove();
            }
        });
    });

    $('.js-add-friend').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var name = $this.closest('form').find('input').val();
        name && $.ajax({
            url: BaseUrl + '/friend_request',
            type: 'post',
            data: {
                u_name: name
            },
            success: function(data) {
                alert('sent friend request success');
                $this.closest('form').find('input').val('');
            }
        });
        return false;
    });

    $('.js-agree').on('click', function(e) {
        var $this = $(this);
        var id = $this.data('id');
        var name = $this.closest('li').find('span').text();
        handleFriendInvitation(id, $this, 'accept');
    });
    $('.js-reject').on('click', function(e) {
        var $this = $(this);
        var id = $this.data('id');
        var name = $this.closest('li').find('span').text();
        handleFriendInvitation(id, $this, 'reject');
    });

    function handleFriendInvitation(id , $item, action) {
        $.ajax({
            url: BaseUrl + '/friend_request/' + id,
            type: 'put',
            data: {
                uid: id,
                action: action
            },
            success: function(data) {
                $item.closest('li').remove();
            }
        });
    }


})();

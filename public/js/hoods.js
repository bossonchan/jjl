(function() {

    $('.js-hood').on('click', function(e) {
        var id = $(this).find('a').data('id');
        getBlocksByHoodIdAndRender(id);
    })

    $('.js-back-hoods').on('click', function(e) {
        getHoodsAndRender(); 
    })

    $('.js-join-block').on('click', function(e) {
        var id = $(this).data('id');
        var name = $(this).closest('li').find('.b_name').text();
        $.ajax({
            url: BaseUrl + '/blocks/' + id + '/apply',
            type: 'post',
            data: {block_id: id},
            success: function(data) {
                getMembersByBlockId(id, name);
            }
        })
    })

    $('.js-follow').on('click', function(e) {
        var $this = $(this);
        var id = $this.data('id');
        var name = $this.closest('li').find('span').text();
        $.ajax({
            url: BaseUrl + '/follow/' + id,
            type: 'post',
            data: {uid: id},
            success: function(data) {
                alert('follow ' + name + ' success');
                $this.attr('disabled', 'disabled');
            }
        })
    })

    function getBlocksByHoodIdAndRender(hood_id) {
        $.ajax({
            url: BaseUrl + '/hoods/' + hood_id + '/blocks',
            type: 'get',
            success: function(data) {
                var html_block = template('blocks', {
                    blocks: data.blocks
                });
                $('#main').html(html_block);
            }
        })
     };

    function getHoodsAndRender() {
        $.ajax({
            url: BaseUrl + '/hoods',
            type: 'get',
            success: function(data) {
                hoods = data.hoods;
                var html_hoods = template('hoods', {hoods: hoods});
                $('#main').html(html_hoods);
            }
        })
    };

    function getMembersByBlockId(block_id, b_name) {
        console.log('age');
        $.ajax({
            url: BaseUrl + '/blocks/' + block_id + '/users',
            type: 'get',
            success: function(data) {
                var html_blockmembers = template('block-members', {
                    members: data.blocks,
                    block: b_name
                });
                $('#main').html(html_blockmembers);
            }
        })
    }

})()

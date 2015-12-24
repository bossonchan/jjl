(function() {
    // listen events
    var blocks = [1,2,3,4,5,6,7,8,9];

    var html_signup = template('signup', {blocks: blocks});
    var html_login = template('login');

    $('.js-signin').on('click', function(e) {
        e.preventDefault();
        var formData = getAndCheckSigninData();
        formData && $.ajax({
            url: BaseUrl + '/session',
            type: 'post',
            data: formData,
            success: function(data) {
                setUserLogin(data);
            },
            error: function(data) {
                alert(data.responseText);
            }
        })
        return false;
    })

    $('.js-to-signup').on('click', function(e) {
        $('#main').html(html_signup);
        return false;
    })

    $('.js-back-login').on('click', function(e) {
        $('#main').html(html_login);
        return false;
    });

    $('.js-signup').on('click', function(e) {
        e.preventDefault();
        var formData = getAndCheckSignupData();
        formData && $.ajax({
            url: BaseUrl + '/users',
            type: 'post',
            data: formData,
            success: function(data) {
                setUserLogin(data.user);
            },
            error: function(data) {
                alert(data);
            }
        })
        return false;
    });

    $('.js-select-block').on('click', function() {
        var blockId = $(this).find('a').data('id');
        var blockName = $(this).find('a').text();
        $('#selected-block').text(blockName).attr('data-id', blockId);
    })

    function getAndCheckSigninData(arguments) {
        var userName = getAdnCheckUsername();
        var password = userName ? getAndCheckPassword(false) : false;
        return (userName && password) ? {
            u_name : userName,
            password: password
        } : false;
    }

    function getAndCheckSignupData() {
        var userName = getAdnCheckUsername();
        var password = userName ? getAndCheckPassword(true) : false;
        var blockId = password ? getAndCheckBlockId() : false;
        return (userName && password && blockId) ? {
            u_name: userName,
            password: password,
            block_id: blockId
        } : false;
    }

    function getAndCheckEmail() {
        // body...
        var email = $('#inputEmail').val();
        if (email === '') {
            alert('email not empty');
            return false;
        } else if (!validateEmail(email)) {
            alert('email not valid');
            return false
        }
        return email;
    }
    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    function getAdnCheckUsername() {
        var userName = $('#inputUsername').val();
        if (!userName) {
            alert('userName name not empty');
        }
        return userName ? userName : false;
    }

    function getAndCheckPassword(hasConfirm) {
        var password = $('#inputPassword').val();
        if (hasConfirm) {
            var confirm =  $('#inputConfirm').val();
            if (password !== confirm) {
                alert('password not the same');
                return false;
            }
        }
        if (!password) {
            alert('password not empty');
        }
        return password ? password : false
    }

    function getAndCheckBlockId() {
        var blockId = $('#selected-block').data('id');
        if (!blockId) {
            alert('please selecte block');
        }
        return blockId ? blockId : false;
    }

    function setUserLogin(user) {
        location.href = './index.html';
    }

})()

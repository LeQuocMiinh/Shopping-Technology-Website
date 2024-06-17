$(document).ready(function () {
    /*
    Trang đăng ký
    */
    $("#form-register-accuracy input").on("keydown", function (value) {
        const number = value.originalEvent.key;
        setTimeout(() => {
            $(this).val(number);
            let data = $(this).data('number');
            if (data < 6) {
                data = +data + 1;
            }
            $('#form-register-accuracy #number-' + data).trigger("focus");
        }, 50);
    });

    $('body').on('submit', '#form-register', function (e) {
        e.preventDefault();
        const form = $(this);
        $(this).find('.alert').addClass('d-none').html('');
        jQuery.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: form.serialize() + '&action=send_authentication_code',
            beforeSend: function (response) {
                $('#form-register').find('.f-loading').removeClass('d-none');
                $('#form-register').find('button').prop('disabled', true);
            },
            complete: function (response) {
                $('#form-register').find('.f-loading').addClass('d-none');
            },
            success: function (response) {
                if (response.status) {
                    $('#form-register-accuracy').removeClass('d-none');
                    $('#form-register-accuracy input[name=email]').val(response.email);
                    $('#form-register-accuracy #email').html(response.email);
                    $('#form-register-accuracy input[name=number_1]').trigger('focus');
                } else {
                    $('#form-register .alert').removeClass('d-none').html(response.message);
                }
            },
            error: function (response, err) { }
        });
    });

    $('body').on('submit', '#form-register-accuracy', function (e) {
        e.preventDefault();
        const form = $(this);
        $(this).find('.alert').addClass('d-none').html('');
        var formObject = {};
        $.each(form.serializeArray(),
            function (i, v) {
                formObject[v.name] = v.value;
            });
        if (formObject.number_1.length === 0 ||
            formObject.number_2.length === 0 ||
            formObject.number_3.length === 0 ||
            formObject.number_4.length === 0 ||
            formObject.number_5.length === 0 ||
            formObject.number_6.length === 0) {
            return false;
        }
        jQuery.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: form.serialize() + '&action=verify_authentication_code',
            beforeSend: function (response) {
                $('#form-register-accuracy').find('.f-loading').removeClass('d-none');
                $('#form-register-accuracy').find('button').prop('disabled', true);
            },
            complete: function (response) {
                $('#form-register-accuracy').find('.f-loading').addClass('d-none');
                $('#form-register-accuracy').find('button').prop('disabled', false);
            },
            success: function (response) {
                if (response.status) {
                    $('#create-account').removeClass('d-none');
                    $('#authentication-account').remove();
                    $('#create-account #form-create input[name=email]').val(response.email);
                } else {
                    $('#form-register-accuracy .alert').removeClass('d-none').html(response.message);
                }
            },
            error: function (response, err) { }
        });
    });

    $('body').on('submit', '#create-account #form-create', function (e) {
        e.preventDefault();
        const form = $(this);
        $(this).find('.alert').addClass('d-none').html('');
        var formObject = {};
        $.each(form.serializeArray(),
            function (i, v) {
                formObject[v.name] = v.value;
            });
        var checkPassword = checkPasswordMatch(formObject.password);
        var checkReqPassword = checkPasswordMatch(formObject.req_password);
        if (!checkPassword || !checkReqPassword) {
            $('#create-account .alert').removeClass('d-none').html('Mật khẩu ít nhất một chữ số, một chữ hoa và một chữ cái viết thường. Dài từ 6 đến 20 ký tự!');
            return false;
        }

        jQuery.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: form.serialize() + '&action=create_account',
            beforeSend: function (response) {
                $('#create-account').find('.f-loading').removeClass('d-none');
                $('#create-account').find('button').prop('disabled', true);
            },
            complete: function (response) {
                $('#create-account').find('.f-loading').addClass('d-none');
                $('#create-account').find('button').prop('disabled', false);

            },
            success: function (response) {
                if (response.status) {
                    sessionStorage.setItem('create_account', true);
                    window.location.reload();
                } else {
                    $('#create-account .alert').removeClass('d-none').html(response.message);
                }
            },
            error: function (response, err) { }
        });
    });

    const is_create_account = sessionStorage.getItem('create_account');
    if (is_create_account) {
        $('#account-success').removeClass('d-none');
        $('#authentication-account').remove();
        setTimeout(() => {
            sessionStorage.clear('create_account');
        }, 100);
    }

    function checkPasswordMatch(password) {
        var pass = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
        if (password.match(pass)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * End trang đăng ký
     */

    // Trang login
    $('body').on('submit', '#form-woocommerce-login', function (e) {
        e.preventDefault();
        const form = $(this);
        var formObject = {};
        $.each(form.serializeArray(),
            function (i, v) {
                formObject[v.name] = v.value;
            });
        if (formObject.email.length == 0 || formObject.password.length == 0) {
            console.log('Lỗi');
            return false;
        }
        jQuery.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: form.serialize() + '&action=login_account',
            beforeSend: function (response) {
                $('#form-woocommerce-login').find('.f-loading').removeClass('d-none');
                $('#form-woocommerce-login').find('button').prop('disabled', true);
            },
            complete: function (response) {
                $('#form-woocommerce-login').find('.f-loading').addClass('d-none');
                $('#form-woocommerce-login').find('button').prop('disabled', false);
            },
            success: function (response) {
                if (response.status) {
                    if (window.history.length <= 0) {
                        window.location.href = '/';
                    } else {
                        window.history.back(1);
                    }

                } else {
                    $('#create-account .alert').removeClass('d-none').html(response.message);
                }
            },
            error: function (response, err) { }
        });
    });
    // End trang login

    // Trang quên mật khẩu
    $("#form-lost-password-accuracy input").on("keydown", function (value) {
        const number = value.originalEvent.key;
        setTimeout(() => {
            $(this).val(number);
            let data = $(this).data('number');
            if (data < 6) {
                data = +data + 1;
            }
            $('#form-lost-password-accuracy #number-' + data).trigger("focus");
        }, 50);
    });
    $('body').on('submit', '#form-lost-password', function (e) {
        e.preventDefault();
        const form = $(this);
        $(this).find('.alert').addClass('d-none').html('');
        jQuery.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: form.serialize() + '&action=send_authentication_code',
            beforeSend: function (response) {
                $('#form-lost-password').find('.f-loading').removeClass('d-none');
                $('#form-lost-password').find('button').prop('disabled', true);
            },
            complete: function (response) {
                $('#form-lost-password').find('.f-loading').addClass('d-none');
                $('#form-lost-password').find('button').prop('disabled', false);
            },
            success: function (response) {
                if (response.status) {
                    $('#form-lost-password-accuracy').removeClass('d-none');
                    $('#form-lost-password-accuracy input[name=email]').val(response.email);
                    $('#form-lost-password-accuracy input[name=number_1]').trigger('focus');
                } else {
                    $('#form-lost-password .alert').removeClass('d-none').html(response.message);
                }
            },
            error: function (response, err) { }
        });
    });

    $('body').on('submit', '#form-lost-password-accuracy', function (e) {
        e.preventDefault();
        const form = $(this);
        $(this).find('.alert').addClass('d-none').html('');
        var formObject = {};
        $.each(form.serializeArray(),
            function (i, v) {
                formObject[v.name] = v.value;
            });
        if (formObject.number_1.length === 0 ||
            formObject.number_2.length === 0 ||
            formObject.number_3.length === 0 ||
            formObject.number_4.length === 0 ||
            formObject.number_5.length === 0 ||
            formObject.number_6.length === 0) {
            return false;
        }
        jQuery.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: form.serialize() + '&action=verify_authentication_code',
            beforeSend: function (response) {
                $('#form-lost-password-accuracy').find('.f-loading').removeClass('d-none');
                $('#form-lost-password-accuracy').find('button').prop('disabled', true);
            },
            complete: function (response) {
                $('#form-lost-password-accuracy').find('.f-loading').addClass('d-none');
                $('#form-lost-password-accuracy').find('button').prop('disabled', false);
            },
            success: function (response) {
                if (response.status) {
                    $('#change-password').removeClass('d-none');
                    $('#authentication-account').remove();
                    $('#change-password #form-change-password input[name=email]').val(response.email);
                    $('#change-password #form-change-password input[name=pin]').val(response.pin);
                } else {
                    $('#form-lost-password-accuracy .alert').removeClass('d-none').html(response.message);
                }
            },
            error: function (response, err) { }
        });
    });

    $('body').on('submit', '#change-password #form-change-password', function (e) {
        e.preventDefault();
        const form = $(this);
        $(this).find('.alert').addClass('d-none').html('');
        var formObject = {};
        $.each(form.serializeArray(),
            function (i, v) {
                formObject[v.name] = v.value;
            });
        var checkPassword = checkPasswordMatch(formObject.password);
        var checkReqPassword = checkPasswordMatch(formObject.req_password);
        if (!checkPassword || !checkReqPassword) {
            $('#form-change-password .alert').removeClass('d-none').html('Mật khẩu ít nhất một chữ số, một chữ hoa và một chữ cái viết thường. Dài từ 6 đến 20 ký tự!');
            return false;
        }

        jQuery.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: form.serialize() + '&action=change_password',
            beforeSend: function (response) {
                $('#form-change-password').find('.f-loading').removeClass('d-none');
                $('#form-change-password').find('button').prop('disabled', true);
            },
            complete: function (response) {
                $('#form-change-password').find('.f-loading').addClass('d-none');
                $('#form-change-password').find('button').prop('disabled', false);
            },
            success: function (response) {
                if (response.status) {
                    $('#account-success').removeClass('d-none');
                    $('#change-password').remove();
                } else {
                    $('#form-change-password .alert').removeClass('d-none').html(response.message);
                }
            },
            error: function (response, err) { }
        });
    });
    // End trang quên mật khẩu

});

//Show hide password login
function showHideLogin() {
    var passworLogin = document.getElementById("lo_password");
    var showPasswordIcon = document.getElementById("login-password");

    if (passworLogin.type === "password") {
        passworLogin.type = "text";
        showPasswordIcon.classList.remove("fa-eye");
        showPasswordIcon.classList.add("fa-eye-slash");
    } else {
        passworLogin.type = "password";
        showPasswordIcon.classList.remove("fa-eye-slash");
        showPasswordIcon.classList.add("fa-eye");
    }
}

//Show hide password register
function showHideRegister() {
    var passworRegister = document.getElementById("reg_password");
    var showIconRegister = document.getElementById("register-password");

    if (passworRegister.type === "password") {
        passworRegister.type = "text";
        showIconRegister.classList.remove("fa-eye");
        showIconRegister.classList.add("fa-eye-slash");
    } else {
        passworRegister.type = "password";
        showIconRegister.classList.remove("fa-eye-slash");
        showIconRegister.classList.add("fa-eye");
    }
}

// Hiển thị nút thông báo thêm vào giỏ hàng thành công
const addToCartButton = document.querySelectorAll(".add_to_cart_button");
const popupAdd = document.querySelector(".popup-add-cart-success");

addToCartButton.forEach(function (button) {
    button.addEventListener("click", function () {
        popupAdd.classList.add("active-add-success");
        setTimeout(function () {
            popupAdd.classList.remove("active-add-success");
        }, 2000);
    });
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

//thêm loading lúc nhấn nút đặt hàng
$(document.body).on('click', 'button#place_order', function () {
    $('.loading').removeClass('d-none');
});

$(document.body).on('checkout_error', function () {
    $('.loading').addClass('d-none');
});

$(document.body).on('checkout_success', function () {
    $('.loading').addClass('d-none');
});
<?php
// Hook để thay đổi danh sách mục menu tài khoản
function custom_wc_account_menu_items($items)
{
    // Thêm một mục menu mới
    $items['dashboard'] = __('Thông tin tài khoản', 'woocommerce');
    $items['orders'] = __('Quản lý đơn hàng', 'woocommerce');
    $items['edit-address'] = __('Sổ địa chỉ', 'woocommerce');
    $items['customer-logout'] = __('Đăng xuất', 'woocommerce');

    unset($items['edit-account']);

    // Trả về danh sách mục menu đã được chỉnh sửa
    return $items;
}
add_filter('woocommerce_account_menu_items', 'custom_wc_account_menu_items', 10, 1);

add_action('wp_ajax_custom_save_info_my_account', 'custom_save_info_my_account');
add_action('wp_ajax_nopriv_custom_save_info_my_account', 'custom_save_info_my_account');

function custom_save_info_my_account()
{
    $type = sanitize_text_field($_POST['type']);
    $user_id = get_current_user_id();

    ob_start();
    if ($type === 'update_personal_info') { //cập nhật thông tin cá nhân
        $fullname = sanitize_text_field($_POST['fullname']);
        $birthday = sanitize_text_field($_POST['birthday']);
        $sex = sanitize_text_field($_POST['sex']);

        update_user_meta($user_id, 'first_name', $fullname);
        $user_data = array(
            'ID' => $user_id,
            'display_name' => $fullname,
        );

        wp_update_user($user_data);
        update_user_meta($user_id, 'birthday', $birthday);
        update_user_meta($user_id, 'sex', $sex);
        wp_send_json(array('status' => true,  'data' => array($fullname, $birthday, $sex), 'message' => 'Lưu thông tin thành công!'));
    } else if ($type === 'update_phone_email_password') { //cập nhật số điện thoại, email và password
        $param = $_POST['param'];
        $field = sanitize_text_field($_POST['field']);
        if ($field === 'phone') {
            update_user_meta($user_id, 'phone', $param);
            wp_send_json(array('status' => true, 'data' => $param, 'message' => 'Lưu số điện thoại thành công!'));
        } else if ($field === 'email') {
            $user_data = array(
                'ID' => $user_id,
                'user_email' => $param,
            );
            wp_update_user($user_data);
            wp_send_json(array('status' => true, 'data' => $param, 'message' => 'Lưu địa chỉ email thành công!'));
        } else if ($field === 'password') {

            $current_password = $param['current_password'];
            $new_password = $param['new_password'];
            $confirm_new_password = $param['confirm_new_password'];

            $loginData = get_userdata($user_id);
            if ($current_password == null && $new_password == null || $confirm_new_password == null) {
            } else {
                if ($loginData && wp_check_password($current_password, $loginData->user_pass)) {
                    if ($confirm_new_password && $confirm_new_password == $new_password) {
                        wp_set_password($new_password, $user_id);
                        wp_send_json(array('status' => true, 'message' => 'Cập nhật mật khẩu thành công!'));
                    } else {
                        wp_send_json(array('status' => 'error-passConfirm', 'message' => 'Mật khẩu xác nhận không đúng !'));
                    }
                } else {
                    wp_send_json(array('status' => 'error-passOld', 'message' => 'Mật khẩu hiện tại không đúng !'));
                }
            }
        }
    }
}


//đăng xuất
add_action('wp_ajax_custom_ajax_logout', 'custom_ajax_logout');
add_action('wp_ajax_nopriv_custom_ajax_logout', 'custom_ajax_logout');

function custom_ajax_logout()
{
    wp_logout();
    $redirect_url = wp_get_referer() ? wp_get_referer() : home_url();
    wp_send_json_success(array('status' => true, 'redirect_url' => $redirect_url));
    wp_die();
}

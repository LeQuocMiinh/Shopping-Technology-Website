<?php
// tạo bảng để lưu trữ xác thực email
function epal_database_install()
{
    global $wpdb;
    // tạo bảng xác thực email
    $epal_verify = "{$wpdb->prefix}user_verifies";

    $sql = "CREATE TABLE IF NOT EXISTS `$epal_verify` (
        id int(11) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        code varchar(6) NOT NULL,
        expire datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        UNIQUE KEY id (id),
        KEY email (email)
    )";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
epal_database_install();

/**
 * Gửi email nhận mã xác thực
 */
function sendAuthenticationCode()
{

    $params = $_POST;
    $type = $params['type'] ?? '';

    if (empty($params['email'])) {
        wp_send_json(['status' => false, 'message' => 'Vui lòng nhập địa chỉ email']);
    }

    $checkEmail = check_email_exists($params['email']);

    if ($type == 'lost_password') {
        if (!wp_verify_nonce($_REQUEST['lost_password_nonce_field'], 'lost_password_nonce')) {
            wp_send_json([], 404); // Get out of here, the nonce is rotten!
        }
        if (!$checkEmail) {
            wp_send_json(['status' => false, 'message' => 'Email không tồn tại']);
        }
    } else {
        if (!wp_verify_nonce($_REQUEST['authentication_nonce_field'], 'authentication_nonce')) {
            wp_send_json([], 404); // Get out of here, the nonce is rotten!
        }

        if ($checkEmail) {
            wp_send_json(['status' => false, 'message' => 'Email đã được đăng ký']);
        }
    }

    $activePinCode = getGenerateCode();
    $checkEmailVerify = checkEmailExists($params['email']);
    $time = current_time('mysql');
    $newDate = strtotime('+30 minute', strtotime($time));
    $newDate = date('Y-m-d H:i:s', $newDate);
    $isUpdate = false;
    $data = [
        'expire' => $newDate,
        'code' => $activePinCode,
    ];
    if ($checkEmailVerify && $checkEmailVerify['id']) {
        $isUpdate = true;
        $data['id'] = $checkEmailVerify['id'];
    } else {
        $data['email'] = $params['email'];
    }

    createOrUpdateData($data, $isUpdate);
    $template = template_email_authentication_code($activePinCode, $params['email']);
    $headers = 'Content-Type: text/html; charset=UTF-8';
    $site_title = '[' . get_bloginfo('name') . '] Thông tin xác thực';
    if ($type == 'lost_password') {
        $template = template_email_authentication_code_lost_password($activePinCode, $params['email']);
    }
    wp_mail($params['email'], $site_title, $template, $headers);
    wp_send_json(['status' => true, 'message' => 'Success!', 'email' => $params['email']]);
}
add_action('wp_ajax_send_authentication_code', 'sendAuthenticationCode');
add_action('wp_ajax_nopriv_send_authentication_code', 'sendAuthenticationCode');

/**
 * Kiểm tra email đã đăng ký chưa
 */
function check_email_exists($email)
{
    global $wpdb;
    $sql = "SELECT user_email FROM wp_users WHERE user_email='" . $email . "' limit 1";
    $data = $wpdb->get_row($sql, ARRAY_A);
    if ($data['user_email']) {
        return true;
    } else {
        return false;
    }
}

/**
 * Lấy mã code ngẫu nhiên 6 số
 */
function getGenerateCode()
{
    $min = 1;           // Minimum 6-digit number (000001)
    $max = 999999;      // Maximum 6-digit number (999999)
    $random_number = mt_rand($min, $max);
    $random_number_str = str_pad($random_number, 6, '0', STR_PAD_LEFT);
    return $random_number_str;
}

/**
 * Kiểm tra email đã nhận mã xác thực chưa
 */
function checkEmailExists($email)
{
    global $wpdb;
    $table = "{$wpdb->prefix}user_verifies";
    $sql = "SELECT * FROM $table WHERE email='" . $email . "' limit 1";
    $data = $wpdb->get_row($sql, ARRAY_A);
    if ($data['id']) {
        return $data;
    } else {
        return [];
    }
}

/**
 * Tạo dữ liệu vào bảng
 */
function createOrUpdateData($data, $isUpdate = false)
{
    global $wpdb;
    $table = "{$wpdb->prefix}user_verifies";
    if ($isUpdate) {
        $id = $data['id'];
        unset($data['id']);
        $wpdb->update(
            $table,
            $data,
            array('id' => $id)
        );
        return $id;
    } else {
        $wpdb->insert($table, $data);
        return $wpdb->insert_id;
    }
}

/**
 * Mẫu gửi mã xác thực
 */
function template_email_authentication_code($code, $email)
{
    ob_start();
?>
    <div style="width: 100%; height: 100%; position: relative; background: #F1F1F1; padding: 30px 0;">
        <div style="max-width: 550px;
                width: 100%; 
                margin: 0 auto; 
                border-radius: 30px 30px 20px 20px;
                background: hsl(0, 0%, 100%);
                color: #000000;
                overflow: hidden;" class="full-width">
            <div style="background: #1435C3; color: #ffffff; margin-bottom: 15px; padding: 15px 0; font-size: 25px; text-align: center;font-weight: 700;">
                Xác Minh Tài Khoản
            </div>
            <div style="padding: 15px 40px 40px 40px;">
                <div style="margin-bottom: 15px">
                    Chào mừng bạn đến với trang mua sắm Thế Kỷ. Vui lòng xác nhận tài khoản đăng ký của bạn bằng mã xác minh bên dưới:
                </div>
                <div style="font-weight: 700; margin-bottom: 4px; color: #000000; font-weight: 700;">
                    Mã xác minh tài khoản:
                </div>
                <div style="margin-bottom: 15px; color: #1435C3; text-align: center; font-weight: 700; font-size: 18px;">
                    <?php echo $code; ?>
                </div>
                <div style="margin-bottom: 15px">
                    Nếu bạn không xác minh được vui lòng liên hệ với nhân viên để được hỗ trợ.
                </div>
                <div style="margin-bottom: 15px;font-style: italic;"><i>Đây là email tự động, vui lòng không trả lời lại.</i></div>
                <div style="color: #000000; font-weight: 700;">© 2024 Theky.com, All Rights Reserved.</div>
            </div>
        </div>
    </div>
<?php
    $template = ob_get_clean();
    return $template;
}

/**
 * Mẫu gửi mã xác thực quên mật khẩu
 */
function template_email_authentication_code_lost_password($code, $email)
{
    ob_start();
?>
    <div style="width: 100%; height: 100%; position: relative; background: #F1F1F1; padding: 30px 0;">
        <div style="max-width: 550px;
                width: 100%; 
                margin: 0 auto; 
                border-radius: 30px 30px 20px 20px;
                background: hsl(0, 0%, 100%);
                color: #000000;
                overflow: hidden;" class="full-width">
            <div style="background: #1435C3; color: #ffffff; margin-bottom: 15px; padding: 15px 0; font-size: 25px; text-align: center;font-weight: 700;">
                Đổi Mật Khẩu
            </div>
            <div style="padding: 15px 40px 40px 40px;">
                <div style="margin-bottom: 15px">
                    Chào mừng bạn đến với trang mua sắm Thế Kỷ. Vui lòng xác nhận đổi mật khẩu bằng mã xác minh bên dưới:
                </div>
                <div style="font-weight: 700; margin-bottom: 4px; color: #000000; font-weight: 700;">
                    Mã xác minh đổi mật khẩu:
                </div>
                <div style="margin-bottom: 15px; color: #1435C3; text-align: center; font-weight: 700; font-size: 18px;">
                    <?php echo $code; ?>
                </div>
                <div style="margin-bottom: 15px">
                    Nếu bạn không xác minh được vui lòng liên hệ với nhân viên để được hỗ trợ.
                </div>
                <div style="margin-bottom: 15px;font-style: italic;"><i>Đây là email tự động, vui lòng không trả lời lại.</i></div>
                <div style="color: #000000; font-weight: 700;">© 2024 Theky.com, All Rights Reserved.</div>
            </div>
        </div>
    </div>
<?php
    $template = ob_get_clean();
    return $template;
}

add_action('wp_ajax_verify_authentication_code', 'verifyAuthenticationCode');
add_action('wp_ajax_nopriv_verify_authentication_code', 'verifyAuthenticationCode');
function verifyAuthenticationCode()
{
    if (!wp_verify_nonce($_REQUEST['verify_authentication_nonce_field'], 'verify_authentication_nonce')) {
        wp_send_json([], 404); // Get out of here, the nonce is rotten!
    }

    $params = $_POST;

    $activeCode = ($params['number_1'] ?? '') . ($params['number_2'] ?? '') . ($params['number_3'] ?? '') . ($params['number_4'] ?? '') . ($params['number_5'] ?? '') . ($params['number_6'] ?? '');
    $email = $params['email'];

    global $wpdb;
    $table = "{$wpdb->prefix}user_verifies";
    $time = current_time('mysql');
    $sql = "SELECT * FROM $table WHERE email='" . $email . "' AND code='" . $activeCode . "' AND expire >= '" . $time . "' limit 1";
    $data = $wpdb->get_row($sql, ARRAY_A);
    if ($data['id']) {
        wp_send_json(['status' => true, 'message' => 'Success!', 'email' => $email, 'pin' => $activeCode]);
    } else {
        wp_send_json(['status' => false, 'message' => 'Mã xác thực không đúng']);
    }
}

add_action('wp_ajax_create_account', 'createAccount');
add_action('wp_ajax_nopriv_create_account', 'createAccount');
function createAccount()
{
    if (!wp_verify_nonce($_REQUEST['create_account_nonce_field'], 'create_account_nonce')) {
        wp_send_json([], 404); // Get out of here, the nonce is rotten!
    }

    $params = $_POST;

    if (empty($params['email'])) {
        wp_send_json(['status' => false, 'message' => 'Vui lòng nhập email']);
    }

    if (empty($params['name'])) {
        wp_send_json(['status' => false, 'message' => 'Vui lòng nhập họ tên']);
    }

    if (empty($params['password'])) {
        wp_send_json(['status' => false, 'message' => 'Vui lòng nhập mật khẩu']);
    }

    if (empty($params['req_password'])) {
        wp_send_json(['status' => false, 'message' => 'Vui lòng nhập lại mật khẩu']);
    }

    if ($params['password'] != $params['req_password']) {
        wp_send_json(['status' => false, 'message' => 'Mật khẩu nhập lại không đúng với mật khẩu']);
    }

    createUser($params['name'], $params['email'], $params['password']);


    $loginData = [
        'user_login' => $params['email'],
        'user_password' => $params['password'],
        'remember' => true,
    ];

    wp_signon($loginData, false);

    wp_send_json(['status' => true, 'message' => 'Success!']);
}

/**
 * Tạo tài khoản
 */
function createUser($user_name, $user_email, $random_password)
{
    $user_id = wp_create_user($user_email, $random_password, $user_email);
    wp_update_user(array('ID' => $user_id, 'role' => 'customer', 'display_name' => $user_name));

    // if (version_compare(get_bloginfo('version'), '4.3.1', '>=')){
    //     wp_new_user_notification( $user_id, $deprecated = null, $notify = 'both' );
    // }else{
    //     wp_new_user_notification( $user_id, $random_password );
    // }

    return $user_id;
}

/**
 * Đăng nhập tài khoản
 */
add_action('wp_ajax_login_account', 'loginAccount');
add_action('wp_ajax_nopriv_login_account', 'loginAccount');
function loginAccount()
{
    if (!wp_verify_nonce($_REQUEST['login_account_nonce_field'], 'login_account_nonce')) {
        wp_send_json([], 404); // Get out of here, the nonce is rotten!
    }

    if (!isset($_POST) || empty($_POST)) {
        wp_send_json(['status' => false]);
        die();
    }

    $params = $_POST;
    $loginData = [
        'user_login' => $params['email'],
        'user_password' => $params['password'],
        'remember' => true,
    ];

    $userVerify = wp_signon($loginData, false);

    if (is_wp_error($userVerify)) {
        wp_send_json(array('status' => false, 'message' => 'Tài khoản hoặc mật khẩu không đúng'));
    } else {
        wp_send_json(array('status' => true, 'message' => __('Đăng nhập thành công')));;
    }
}

/**
 * Thay đổi mật khẩu
 */
add_action('wp_ajax_change_password', 'changePassword');
add_action('wp_ajax_nopriv_change_password', 'changePassword');
function changePassword()
{
    if (!wp_verify_nonce($_REQUEST['change_password_nonce_field'], 'change_password_nonce')) {
        wp_send_json([], 404); // Get out of here, the nonce is rotten!
    }

    if (!isset($_POST) || empty($_POST)) {
        wp_send_json(['status' => false]);
        die();
    }

    $params = $_POST;

    if (empty($params['password'])) {
        wp_send_json(['status' => false, 'message' => 'Vui lòng nhập mật khẩu']);
    }

    if (empty($params['req_password'])) {
        wp_send_json(['status' => false, 'message' => 'Vui lòng nhập lại mật khẩu']);
    }

    if ($params['password'] != $params['req_password']) {
        wp_send_json(['status' => false, 'message' => 'Mật khẩu nhập lại không đúng với mật khẩu']);
    }

    if (empty($params['email'])) {
        wp_send_json(['status' => false, 'message' => 'Lỗi đổi mật khẩu. Vui lòng thử lại sau']);
    }

    $activeCode = $params['pin'] ?? '';
    $email = $params['email'];

    global $wpdb;
    $table = "{$wpdb->prefix}user_verifies";
    $time = current_time('mysql');
    $sql = "SELECT * FROM $table WHERE email='" . $email . "' AND code='" . $activeCode . "' AND expire >= '" . $time . "' limit 1";
    $data = $wpdb->get_row($sql, ARRAY_A);
    if ($data['id']) {
        $sql = "SELECT id,user_email FROM wp_users WHERE user_email='" . $email . "' limit 1";
        $data = $wpdb->get_row($sql, ARRAY_A);
        if ($data['id']) {
            $userId = $data['id'];
            wp_set_password($params['password'], $userId);
        } else {
            wp_send_json(['status' => false, 'message' => 'Email không tồn tại. Vui lòng xác nhận lại mã']);
        }
    } else {
        wp_send_json(['status' => false, 'message' => 'Hết thời gian đổi mật khẩu. Vui lòng xác nhận lại mã!']);
    }

    wp_send_json(array('status' => true, 'message' => __('Đổi mật khẩu thành công')));;
}

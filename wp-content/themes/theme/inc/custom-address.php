<?php

//tạo table users_address trong database
function wp_users_address_create()
{

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE `{$wpdb->prefix}users_address` (
    ID INT(11)  auto_increment,
    user_id int(11) ,
    user_name VARCHAR(50) ,
    user_phone VARCHAR(11) ,
    user_address VARCHAR(100) ,
    user_province_name VARCHAR(50) ,
    user_district_name VARCHAR(50) ,
    user_ward_name VARCHAR(50) ,
    user_province_id VARCHAR(50) ,
    user_district_id VARCHAR(50) ,
    user_ward_id VARCHAR(50) ,
    user_note VARCHAR(100) ,
    default_value INT(1)  default '0',
    PRIMARY KEY (ID)
  ) $charset_collate;";

    require_once(ABSPATH . "wp-admin/includes/upgrade.php");
    dbDelta($sql);
}
add_action('init', 'wp_users_address_create');

function add_shipping_address()
{
    global $wpdb;
    $customer_id = get_current_user_id();
    $params = $_POST['params'];
    $customer_code = get_the_author_meta('crm_customer_code', $customer_id);
    $districtId = $params['user_district_id_new'] ?? -1;
    $provinceId = $params['user_province_id_new'] ?? -1;
    $wardId = $params[' user_ward_id_new'] ?? -1;
    $city = $params['user_province_new'] ?? '';
    $district = $params['user_district_new'] ?? '';
    $wards = $params['user_ward_new'] ?? '';
    $address = $params['user_address_new'] ?? '';
    $phone = $params['user_phone_new'] ?? '';
    $name = $params['user_name_new'] ?? '';
    $note = $params['user_note'] ?? '';
    $check_default = $params['check_default'] ?? 0;

    $table_name = $wpdb->prefix . "users_address";

    $array = array(
        'user_id' => $customer_id,
        'user_name' => $name,
        'user_phone' => $phone,
        'user_address' => $address,
        'user_province_name' => $city,
        'user_district_name' => $district,
        'user_ward_name' => $wards,
        'user_province_id' => $provinceId,
        'user_district_id' => $districtId,
        'user_ward_id' => $wardId,
        'user_note' => $note,
        'default_value' => $check_default,
    );

    // Check if the address already exists in the database
    $existing_address = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_province_id = %d AND user_district_id = %d AND user_ward_id = %d",
            $provinceId,
            $districtId,
            $wardId
        )
    );

    if (!$existing_address) {
        if ($check_default) {
            $wpdb->update(
                $table_name,
                array(
                    'default_value' => 0,
                ),
                array('user_id' => $customer_id)
            );
        }

        // Insert the new address
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $customer_id,
                'user_name' => $name,
                'user_phone' => $phone,
                'user_address' => $address,
                'user_province_name' => $city,
                'user_district_name' => $district,
                'user_ward_name' => $wards,
                'user_province_id' => $provinceId,
                'user_district_id' => $districtId,
                'user_ward_id' => $wardId,
                'user_note' => $note,
                'default_value' => $check_default,
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d'
            )
        );
        wp_send_json(array('status' => true, 'data' => $array, 'message' => 'Thêm địa chỉ mới thành công!'));
    } else {
        wp_send_json(array('status' => false, 'message' => 'Địa chỉ đã tồn tại!'));
    }
    die();
}

add_action('wp_ajax_add_shipping_address', 'add_shipping_address', 10);
add_action('wp_ajax_nopriv_add_shipping_address', 'add_shipping_address', 10);

//Xoá địa chỉ
function delete_shipping_address()
{
    global $wpdb;
    $idRowAddress = $_POST['params'];
    $table_name = $wpdb->prefix . "users_address";
    if ($idRowAddress) {
        $wpdb->delete(
            $table_name,
            array("id" => $idRowAddress)
        );
    }
}
add_action('wp_ajax_delete_shipping_address', 'delete_shipping_address', 10);
add_action('wp_ajax_nopriv_delete_shipping_address', 'delete_shipping_address', 10);

//Cập nhật địa chỉ
function update_shipping_address()
{
    global $wpdb;
    $params = $_POST['params'];

    $idRow = $params['idRow'];
    $districtId = $params['user_district_id_new'] ?? -1;
    $provinceId = $params['user_province_id_new'] ?? -1;
    $wardId = $params['user_ward_id_new'] ?? -1;
    $province = $params['user_province_new'] ?? '';
    $district = $params['user_district_new'] ?? '';
    $wards = $params['user_ward_new'] ?? '';
    $address = $params['user_address_new'] ?? '';
    $phone = $params['user_phone_new'] ?? '';
    $name = $params['user_name_new'] ?? '';
    $note = $params['user_note'] ?? '';
    $check_default = $params['check_default'] ?? 0;

    $table_name = $wpdb->prefix . "users_address";

    if ($check_default == 1) {
        // Update all other rows to set 'default_value' to 0
        $sql = $wpdb->prepare(
            "UPDATE $table_name SET default_value = 0 WHERE ID NOT IN ($idRow)",
            $idRow
        );
        $wpdb->query($sql);
    }

    $data = array(
        'user_name' => $name,
        'user_phone' => $phone,
        'user_province_name' => $province,
        'user_district_name' => $district,
        'user_ward_name' =>  $wards,
        'user_province_id' => $provinceId,
        'user_district_id' => $districtId,
        'user_ward_id' =>  $wardId,
        'user_address' => $address,
        'user_note' => $note,
        'default_value' =>  $check_default
    );

    $where = array(
        'ID' => $idRow
    );

    $wpdb->update(
        $table_name,
        $data,
        $where
    );

    wp_send_json(array('status' => true, 'data' => $data));
    die();
}

add_action('wp_ajax_update_shipping_address', 'update_shipping_address', 10);
add_action('wp_ajax_nopriv_update_shipping_address', 'update_shipping_address', 10);


function check_default()
{
    global $wpdb;
    $check_default = isset($_POST['check_default']) ? intval($_POST['check_default']) : 0;
    $idRow = isset($_POST['idRow']) ? intval($_POST['idRow']) : 0;
    $table_name = $wpdb->prefix . "users_address";

    // Validate input data (check if $idRow is a positive integer)
    if ($idRow > 0) {
        $data = array(
            'default_value' => $check_default,
        );

        // Update the row with the specified 'idRow'
        $wpdb->update(
            $table_name,
            $data,
            array('ID' => $idRow)
        );

        if ($check_default == 1) {
            // Update all other rows to set 'default_value' to 0
            $sql = $wpdb->prepare(
                "UPDATE $table_name SET default_value = 0 WHERE ID NOT IN ($idRow)",
                $idRow
            );
            $wpdb->query($sql);
        }
    }
}


add_action('wp_ajax_check_default', 'check_default', 10);
add_action('wp_ajax_nopriv_check_default', 'check_default', 10);

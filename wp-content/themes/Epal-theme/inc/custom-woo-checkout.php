<?php
function addPeopleOther($fields)
{
    $class = is_user_logged_in() ? array('form-row-wide', ' hiden-input') : array('form-row-wide');
    $fields['billing']['billing_check_people'] = array(
        'type' => 'checkbox',
        'label' => '<label class="label" for="billing_check_people">Người khác nhận hàng</label>',
        'required' => false,
        'class' => $class,
        'priority' => 65,
    );

    //Thêm trường giới tính
    $fields['billing']['billing_gender_other'] = array(
        'type' => 'radio',
        'label' => '',
        'required' => false,
        'class' => $class,
        'priority' => 70,
        'default' => 1,
        'options' => array(
            '1' => 'Anh',
            '0' => 'Chị',
        ),
    );

    $fields['billing']['billing_name_other'] = array(
        'type' => 'text',
        'placeholder' => 'Họ và tên',
        'required' => false,
        'class' => $class,
        'priority' => 75,
    );

    $fields['billing']['billing_phone_other'] = array(
        'type' => 'tel',
        'placeholder' => 'Số điện thoại',
        'required' => false,
        'class' => $class,
        'priority' => 80,
    );

    $fields['billing']['billing_email_other'] = array(
        'type' => 'email',
        'placeholder' => 'Email',
        'required' => false,
        'class' => $class,
        'priority' => 85,
    );

    $fields['billing']['billing_request_export'] = array(
        'type' => 'text',
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 110,
    );

    $fields['billing']['billing_company'] = array(
        'type' => 'text',
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 90,
    );

    $fields['billing']['billing_tax_code'] = array(
        'type' => 'text',
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 95,
    );

    $fields['billing']['billing_unit'] = array(
        'type' => 'text',
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 100,
    );

    $fields['billing']['billing_email_company'] = array(
        'type' => 'text',
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 105,
    );

    $fields['billing']['billing_tab_address'] = array(
        'type' => 'text',
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 115,
    );

    return $fields;
}

add_filter('woocommerce_checkout_fields', 'addPeopleOther');

//Xóa ký tự '&nbsp;' trang checkout
add_filter('woocommerce_form_field', 'bbloomer_remove_optional_checkout_fields', 9999);

function bbloomer_remove_optional_checkout_fields($field)
{
    $field = str_replace('&nbsp;', '', $field);
    return $field;
}

//Đỏi số dòng textarea 
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

function custom_override_checkout_fields($fields)
{
    $fields['order']['order_comments']['custom_attributes'] = array(
        'rows' => 3,
    );
    return $fields;
}

//Thêm lựa chọn địa chỉ
add_action('woocommerce_checkout_province', 'display_billing_address', 10, 1);
function display_billing_address()
{ ?>
    <div class="box-address-shipping">
        <ul class="nav-address">
            <li class="item-address active" data-code="1" data-id="address-custom">Giao tận nơi</li>
            <li class="item-address" data-code="2" data-id="address-shop">Nhận hàng tại cửa hàng</li>
        </ul>
        <?php $address_other = wc()->session->get('address_custom');
        $flag = false;
        if ($address_other != '') {
            $flag = true;
        }
        $id_sesstion_address = wc()->session->get('id_address');
        ?>
        <div class="tab-address">

            <?php if (!is_user_logged_in()) : ?>
                <div id="address-custom" class="active item-tab">
                    <select class="item-custom" name="address_city" id="address_city">
                        <option value="0" class="">Chọn Tỉnh/ Thành phố</option>
                    </select>
                    <select class="item-custom" name="address_district" id="address_district">
                        <option value="0" class="">Chọn Quận/ Huyện</option>
                    </select>
                    <select class="item-custom" name="address_wards" id="address_wards">
                        <option value="0" class="">Chọn Phường/ Xã</option>
                    </select>

                    <input type="text" class="item-custom" id="address_detail" name="address_detail" value="" placeholder="Số nhà/ tên đường">
                </div>
            <?php else : ?>
                <div id="address-custom" class="active item-tab tab-logged">
                    <div class="title open-list-address">
                        Sổ địa chỉ
                    </div>
                    <div class="list-address-of-customer">

                        <?php if ($flag == true) : ?>
                            <?php
                            $user_current = wp_get_current_user();
                            ?>
                            <div class="item-address-customer">
                                <div class="checkbox-address">
                                    <?php
                                    $city = $address_other[0]['user_province'];
                                    $district = $address_other[0]['user_district'] != '' ? $address_other[0]['user_district'] . ', ' : '';
                                    $wards = $address_other[0]['user_ward'] != '' ? $address_other[0]['user_ward'] . ', ' : '';
                                    $address = $wards . $district . $city;
                                    $id_adress = 0;
                                    $user_name = $user_current->display_name;
                                    $user_phone = get_user_meta($user_current->ID, 'phone', true);
                                    $user_email = $user_current->user_email;
                                    $user_gender = get_user_meta($user_current->ID, 'sex', true);

                                    ?>

                                    <div class="data-address" data-name="<?php echo $user_name ?>" data-name="<?php echo $user_phone ?>" data-phone="<?php echo $user_phone ?>" data-gender="<?php echo $user_gender ?>" data-email="<?php echo $user_email ?>"></div>
                                    <input checked type="radio" class="input-address" id="address_<?php echo $id_adress ?>" name="address_customer" value="<?php echo $address ?>">
                                    <label for="address_<?php echo $id_adress ?>">
                                </div>
                                <div class="box-infor">
                                    <div class="personal-information">
                                        <p class="text-infor gender">
                                            <span class="title">
                                                <?php
                                                if ($user_gender == 'Nam') {
                                                    $user_gender = 'Anh';
                                                } else {
                                                    $user_gender = 'Chị';
                                                }
                                                echo $user_gender . ': '

                                                ?>

                                            </span>
                                            <span class="content">
                                                <?php echo $user_name ?>
                                            </span>
                                        </p>
                                        <p class="text-infor phone">
                                            <span class="title">
                                                Điện thoại:
                                            </span>
                                            <span class="content">
                                                <?php echo $user_phone ?>
                                            </span>
                                        </p>
                                        <p class="text-infor email">
                                            <span class="title">
                                                Email:
                                            </span>
                                            <span class="content">
                                                <?php echo $user_email ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="address-information">
                                        <p class="text-infor">
                                            <span class="title">
                                                Địa chỉ nhận hàng:
                                            </span>
                                            <span class="content">
                                                <?php echo $address ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php
                        global $wpdb;
                        $wpdb_prefix = $wpdb->prefix;
                        $wpdb_tablename = $wpdb_prefix . 'users_address';
                        $id_user = get_current_user_id();
                        $query = "SELECT * FROM $wpdb_tablename WHERE user_id = $id_user ORDER BY default_value DESC";
                        $result = $wpdb->get_results($query);
                        ?>
                        <?php

                        foreach ($result as $address => $value) :
                        ?>
                            <div class="item-address-customer">
                                <div class="checkbox-address">
                                    <?php
                                    $id_adress = $value->ID;
                                    $street = $value->user_address != '' ? $value->user_address . ', ' : '';
                                    $city = $value->user_province_name;
                                    $district = $value->user_district_name != '' ? $value->user_district_name . ', ' : '';
                                    $wards = $value->user_ward_name != '' ? $value->user_ward_name . ', ' : '';
                                    $address = $street . $wards . $district . $city;
                                    $user_current = wp_get_current_user();
                                    $user_name = $value->user_name;
                                    $user_phone = $value->user_phone;
                                    $user_email = $user_current->user_email;
                                    $user_gender = get_user_meta($user_current->ID, 'sex', true);

                                    ?>

                                    <div class="data-address" data-name="<?php echo $user_name ?>" data-name="<?php echo $user_phone ?>" data-phone="<?php echo $user_phone ?>" data-gender="<?php echo $user_gender ?>" data-email="<?php echo $user_email ?>"></div>
                                    <input <?php echo ($id_adress == $id_sesstion_address[0] && $flag == false) || 1 == $value->default_value ? 'checked' : '' ?> type="radio" class="input-address" id="address_<?php echo $id_adress ?>" name="address_customer" value="<?php echo $address ?>">
                                    <label for="address_<?php echo $id_adress ?>">
                                </div>
                                <div class="box-infor">
                                    <div class="personal-information">
                                        <p class="text-infor gender">
                                            <span class="title">
                                                <?php
                                                if ($user_gender == 'Nam') {
                                                    $user_gender = 'Anh';
                                                } else {
                                                    $user_gender = 'Chị';
                                                }
                                                echo $user_gender . ': '

                                                ?>

                                            </span>
                                            <span class="content">
                                                <?php echo $user_name ?>
                                            </span>
                                        </p>
                                        <p class="text-infor phone">
                                            <span class="title">
                                                Điện thoại:
                                            </span>
                                            <span class="content">
                                                <?php echo $user_phone ?>
                                            </span>
                                        </p>
                                        <p class="text-infor email">
                                            <span class="title">
                                                Email:
                                            </span>
                                            <span class="content">
                                                <?php echo $user_email ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="address-information">
                                        <p class="text-infor">
                                            <span class="title">
                                                Địa chỉ nhận hàng:
                                            </span>
                                            <span class="content">
                                                <?php echo $address ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (is_user_logged_in()) : ?>
                        <div class="add-address-checkout">
                            <div class="btn-address-checkout">
                                <span class="text">Thêm địa chỉ</span>
                                <img src="<?php echo get_template_directory_uri() . '/images/address-checkout.svg' ?>" alt="checkout" class="icon">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="item-tab" id="address-shop">
                <p class="address-text">
                    Địa chỉ: <?php echo get_field('address_shop', get_the_ID()); ?>
                </p>
                <a href="https://maps.app.goo.gl/ZJzYHNfVFqdzLR5a8" class="address-link">
                    <span class="">Chỉ đường</span>
                    <img src="<?php echo get_template_directory_uri() . '/images/address-shop.svg' ?>" alt="Chi-dương" class="icon">
                </a>
            </div>
        </div>


    </div>
<?php
}

//Check rỗng các điều kiện
function njengah_select_checkout_field_process()
{
    global $woocommerce;
    if ($_POST['billing_tab_address'] == '1' && !is_user_logged_in()) {
        if ($_POST['address_city'] == "0" || $_POST['address_district'] == "0" || $_POST['address_wards'] == "0" || $_POST['address_detail'] == '')
            wc_add_notice('Vui lòng chọn đầy đủ thành phố/tỉnh, quận/huyện, phường xã và ghi rõ tên đường số nhà.', 'error');
    }
    if ($_POST['billing_request_export'] == 'Có') {
        if ($_POST['billing_company'] == '' || $_POST['billing_tax_code'] == '' || $_POST['billing_unit'] == '' || $_POST['billing_email_company'] == '') {
            wc_add_notice('Vui lòng nhập thông tin xuất hóa đơn (Tên tổ chức, Mã số thuế, Đơn vị, Email nhận hóa đơn)', 'error');
        }
    }
    if ($_POST['billing_check_people'] == '1') {
        if ($_POST['billing_name_other'] == '' || $_POST['billing_phone_other'] == '' || $_POST['billing_email_other'] == '') {
            wc_add_notice('Vui lòng nhập đầy đủ thông tin người nhận hàng', 'error');
        }
    }
}

add_action('woocommerce_checkout_process', 'njengah_select_checkout_field_process');

//Lưu thông tin
function njengah_select_checkout_field_update_order_meta($order_id)
{

    //Lưu thông tin địa chỉ

    if ($_POST['billing_tab_address'])
        update_post_meta($order_id, 'billing_tab_address', esc_attr($_POST['billing_tab_address']));
    if (!is_user_logged_in()) {
        if ($_POST['address_city'])
            update_post_meta($order_id, 'address_city', esc_attr($_POST['address_city']));
        if ($_POST['address_district'])
            update_post_meta($order_id, 'address_district', esc_attr($_POST['address_district']));
        if ($_POST['address_wards'])
            update_post_meta($order_id, 'address_wards', esc_attr($_POST['address_wards']));
        if ($_POST['address_detail'])
            update_post_meta($order_id, 'address_detail', esc_attr($_POST['address_detail']));
    } else {
        $address = explode(',', esc_attr($_POST['address_customer']));
        if (!wc()->session->get('address_custom')) {
            update_post_meta($order_id, 'address_city', $address[3]);
            update_post_meta($order_id, 'address_district', $address[2]);
            update_post_meta($order_id, 'address_wards', $address[1]);
            update_post_meta($order_id, 'address_detail', $address[0]);
        } else {
            update_post_meta($order_id, 'address_city', $address[2]);
            update_post_meta($order_id, 'address_district', $address[1]);
            update_post_meta($order_id, 'address_wards', $address[0]);
        }
    }


    //Thông tin xuất hóa đơn

    if ($_POST['billing_company'])
        update_post_meta($order_id, 'billing_company', esc_attr($_POST['billing_company']));
    if ($_POST['billing_tax_code'])
        update_post_meta($order_id, 'billing_tax_code', esc_attr($_POST['billing_tax_code']));
    if ($_POST['billing_unit'])
        update_post_meta($order_id, 'billing_unit', esc_attr($_POST['billing_unit']));
    if ($_POST['billing_email_company'])
        update_post_meta($order_id, 'billing_email_company', esc_attr($_POST['billing_email_company']));

    //Thông tin người khác nhận hàng
    if ($_POST['billing_gender_other'])
        update_post_meta($order_id, 'billing_gender_other', esc_attr($_POST['billing_gender_other']));
    if ($_POST['billing_name_other'])
        update_post_meta($order_id, 'billing_name_other', esc_attr($_POST['billing_name_other']));
    if ($_POST['billing_phone_other'])
        update_post_meta($order_id, 'billing_phone_other', esc_attr($_POST['billing_phone_other']));
    if ($_POST['billing_email_other'])
        update_post_meta($order_id, 'billing_email_other', esc_attr($_POST['billing_email_other']));

    //Thông tin người đặt
    if ($_POST['billing_gender'])
        update_post_meta($order_id, 'billing_gender', esc_attr($_POST['billing_gender']));
    if ($_POST['billing_custom_phone'])
        update_post_meta($order_id, 'billing_custom_phone', esc_attr($_POST['billing_custom_phone']));

    //Thông tin người đặt
    if ($_POST['billing_gender']) update_post_meta($order_id, 'billing_gender', esc_attr($_POST['billing_gender']));
    if ($_POST['billing_custom_phone']) update_post_meta($order_id, 'billing_custom_phone', esc_attr($_POST['billing_custom_phone']));
}

//* Update the order meta with field value
add_action('woocommerce_checkout_update_order_meta', 'njengah_select_checkout_field_update_order_meta');

//Hiện thông tin trong chi tiết đơn hàng
function njengah_select_checkout_field_display_admin_order_meta($order)
{
    $gender = get_post_meta($order->id, 'billing_gender', true);
    $phone = get_post_meta($order->id, 'billing_custom_phone', true);
    $billing_company = get_post_meta($order->id, 'billing_company', true);
    $billing_tax_code = get_post_meta($order->id, 'billing_tax_code', true);
    $billing_unit = get_post_meta($order->id, 'billing_unit', true);
    $billing_email_company = get_post_meta($order->id, 'billing_email_company', true);
    $billing_gender_other = get_post_meta($order->id, 'billing_gender_other', true);
    $billing_name_other = get_post_meta($order->id, 'billing_name_other', true);
    $billing_phone_other = get_post_meta($order->id, 'billing_phone_other', true);
    $billing_email_other = get_post_meta($order->id, 'billing_email_other', true);

    if ($gender == '1') {
        $gender = 'Nam';
    } else {
        $gender = 'Nữ';
    }

    if ($billing_gender_other == '1') {
        $billing_gender_other = 'Nam';
    } else {
        $billing_gender_other = 'Nữ';
    }

    echo '<p><strong>' . __('Giới tính: ') . ':</strong> ' . $gender . '</p>';
    echo '<p><strong>' . __('Số điện thoại: ') . ':</strong> ' . $phone . '</p>';

    if ($billing_name_other != '') {
        echo '<p><strong>' . __('Người khác nhận hàng: ') . ':</strong></p>';
        echo '<p><strong>' . __('Giới tính: ') . ':</strong> ' . $billing_gender_other . '</p>';
        echo '<p><strong>' . __('Họ và tên: ') . ':</strong> ' . $billing_name_other . '</p>';
        echo '<p><strong>' . __('Số điện thoại: ') . ':</strong> ' . $billing_phone_other . '</p>';
        echo '<p><strong>' . __('Email: ') . ':</strong> ' . $billing_email_other . '</p>';
    }

    if ($billing_company != '') {
        echo '<p><strong>' . __('Yêu cầu xuất hóa đơn: ') . ':</strong></p>';
        echo '<p><strong>' . __('Tên tổ chức, công ty: ') . ':</strong> ' . $billing_company . '</p>';
        echo '<p><strong>' . __('Mã số thuế: ') . ':</strong> ' . $billing_tax_code . '</p>';
        echo '<p><strong>' . __('Đơn vị: ') . ':</strong> ' . $billing_unit . '</p>';
        echo '<p><strong>' . __('Email nhận hóa đơn: ') . ':</strong> ' . $billing_email_company . '</p>';
    }



    if (get_post_meta($order->id, 'billing_tab_address', true) == '1') {
        echo '<p><strong>' . __('Phương thức nhận hàng: ') . ':</strong> ' . 'Giao hàng tận nơi' . '</p>';
        $address = get_post_meta($order->id, 'address_detail', true) . ', ' . get_post_meta($order->id, 'address_wards', true) . ', ' . get_post_meta($order->id, 'address_district', true) . ', ' . get_post_meta($order->id, 'address_city', true);
        echo '<p><strong>' . __('Địa chỉ nhận hàng: ') . ':</strong> ' . $address . '</p>';
    } else {
        echo '<p><strong>' . __('Phương thức nhận hàng: ') . ':</strong> ' . 'Nhận máy tại cửa hàng' . '</p>';
    }
}

//* Display field value on the order edition page
add_action('woocommerce_admin_order_data_after_billing_address', 'njengah_select_checkout_field_display_admin_order_meta', 10, 1);


// function skip_cart_page_redirection_to_checkout()
// {
//     $id_page = get_the_ID();
//     if ($id_page == '7') {
//         wp_redirect(get_home_url());
//     }
// }
// add_action('template_redirect', 'skip_cart_page_redirection_to_checkout');

add_filter('woocommerce_cart_totals_coupon_html', 'custom_coupon_html', 10, 3);

function custom_coupon_html($coupon_html, $coupon, $discount_amount_html)
{
    // Build new HTML
    $new_coupon_html = '<div class="woocommerce-remove-coupon" data-coupon="' . esc_attr($coupon->get_code()) . '">' .  '<span>' . $discount_amount_html . '</span> [Xóa]</div>';

    return $new_coupon_html;
}

// add_action('woocommerce_checkout_update_order_review', 'retrieve_chosen_shipping_method_from_post_data', 10, 2);

// function retrieve_chosen_shipping_method_from_post_data()
// {
//     $posted_data = $_POST;
//     $chosen_shipping_method = '';
//     if (isset($posted_data['shipping_method'])) {
//         $chosen_shipping_method = $posted_data['shipping_method'];
//     } else {
//         $chosen_shipping_method = array('local_pickup:3');
//     }
//     WC()->session->set('chosen_shipping_methods', $chosen_shipping_method);
// }

<?php
//Add Template Woocommerce
$storefront = (object) array(
    'main' => require 'class-storefront.php',
);


//Sửa Đổi Mặc Định gallery của woocommerce
add_action('after_setup_theme', 'yourtheme_setup');
function yourtheme_setup()
{
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}


// Tối ưu Woocommerce css, js
add_action('wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99);

function child_manage_woocommerce_styles()
{
    remove_action('wp_head', array($GLOBALS['woocommerce'], 'generator'));
    if (function_exists('is_woocommerce')) {
        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
            wp_dequeue_style('woocommerce_frontend_styles');
            wp_dequeue_style('woocommerce_fancybox_styles');
            wp_dequeue_style('woocommerce_chosen_styles');
            wp_dequeue_style('woocommerce_prettyPhoto_css');
            wp_dequeue_script('wc_price_slider');
            wp_dequeue_script('wc-single-product');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('wc-checkout');
            wp_dequeue_script('wc-add-to-cart-variation');
            wp_dequeue_script('wc-single-product');
            wp_dequeue_script('wc-cart');
            wp_dequeue_script('wc-chosen');
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('prettyPhoto');
            wp_dequeue_script('prettyPhoto-init');
            wp_dequeue_script('jquery-blockui');
            wp_dequeue_script('jquery-placeholder');
            wp_dequeue_script('fancybox');
            wp_dequeue_script('jqueryui');
        }
    }
}
//ẩn form myaccount address
function remove_edit_address_field($fields)
{

    unset($fields['state']);
    unset($fields['country']);

    return $fields;
}
add_filter('woocommerce_default_address_fields', 'remove_edit_address_field');



add_filter('woocommerce_checkout_fields', 'custom_checkout_form');
function custom_checkout_form($fields)
{
    unset($fields['billing']['billing_postcode']); //Ẩn postCode
    unset($fields['billing']['billing_state']); //Ẩn bang hạt
    unset($fields['billing']['billing_address_2']); //Ẩn địa chỉ 2
    unset($fields['billing']['billing_address_1']); //Ẩn địa chỉ 1
    unset($fields['billing']['billing_company']); //Ẩn công ty
    unset($fields['billing']['billing_country']); // Ẩn quốc gia
    unset($fields['billing']['billing_last_name']); //Ẩn last name
    unset($fields['billing']['billing_city']); //Ẩn select box chọn thành phố
    unset($fields['billing']['billing_phone']); //Ẩn số điện thoại mặc định

    //Bỏ country
    $fields['billing']['billing_country']['required'] = false;
    $fields['billing']['billing_country']['default'] = 'VN';

    //Xét độ ưu tiên
    $fields['billing']['billing_email']['priority'] = 60;
    $fields['billing']['billing_email']['label'] = '';
    $fields['billing']['billing_email']['placeholder'] = 'Email';
    $fields['billing']['billing_email']['required'] = false;
    return $fields;
}

function custom_checkout_field_label($fields)
{
    $fields['first_name']['label'] = '';
    $fields['first_name']['placeholder'] = 'Họ và tên';
    return $fields;
}

add_filter('woocommerce_default_address_fields', 'custom_checkout_field_label');

// thêm trường tùy chỉnh mới

add_filter('woocommerce_checkout_fields', 'add_custom_phone_and_address_fields');

function add_custom_phone_and_address_fields($fields)
{
    $class = is_user_logged_in() ? array('form-row-wide', ' hiden-input') : array('form-row-wide');
    // Thêm trường số điện thoại
    $fields['billing']['billing_custom_phone'] = array(
        'type' => 'tel',
        'placeholder' => 'Số điện thoại',
        'required' => true,
        'class' => $class,
        'priority' => 50,
    );

    //Thêm trường giới tính
    $fields['billing']['billing_gender'] = array(
        'type' => 'radio',
        'label' => '',
        'required' => true,
        'class' => $class,
        'priority' => 5,
        'default' => 1,
        'options' => array(
            '1' => 'Anh',
            '0' => 'Chị',
        ),

    );

    $fields['billing']['billing_first_name']['class'] = $class;
    $fields['billing']['billing_email']['class'] = $class;

    // Thêm trường địa chỉ tùy chỉnh
    // $fields['billing']['billing_custom_address'] = array(
    //     'type' => 'text',
    //     'label' => 'Địa chỉ',
    //     'placeholder' => 'Địa chỉ',
    //     'priority' => 85,
    //     'required' => true,
    //     'class' => array('form-row-wide'),
    // );

    return $fields;
}

// add_filter('woocommerce_checkout_fields', 'add_province_field');

function add_province_field($fields)
{
    $fields['billing']['billing_province'] = array(
        'type' => 'select',
        'label' => 'Tỉnh/Thành phố',
        'required' => true,
        'class' => array('form-row-wide'),
        'priority' => 70,
        'options' => array(
            'select' => 'Tỉnh/Thành phố',
        ),
    );

    $fields['billing']['billing_district'] = array(
        'type' => 'select',
        'label' => 'Quận/Huyện',
        'required' => true,
        'class' => array('form-row-wide'),
        'priority' => 75,
        'options' => array(
            'select' => 'Quận/Huyện',
        ),
    );

    $fields['billing']['billing_ward'] = array(
        'type' => 'select',
        'label' => 'Phường/Xã',
        'required' => true,
        'class' => array('form-row-wide'),
        'priority' => 80,
        'options' => array(
            'select' => 'Phường/Xã',
        ),
    );


    return $fields;
}



// Hiển thị "Còn Hàng" / "Tạm hết hàng"
add_filter('woocommerce_get_availability', 'wcs_custom_get_availability', 1, 2);
function wcs_custom_get_availability($availability, $_product)
{

    // Change In Stock Text
    $currentlang = get_bloginfo('language');
    if ($_product->is_in_stock()) {
        if ($currentlang == "vi") :
            $availability['availability'] = __('Còn hàng', 'woocommerce');
        elseif ($currentlang == "en-GB") :
            $availability['availability'] = __('Available', 'woocommerce');
        endif;
    }
    // Change Out of Stock Text
    if (!$_product->is_in_stock()) {
        if ($currentlang == "vi") :
            $availability['availability'] = __('Tạm hết hàng', 'woocommerce');
        elseif ($currentlang == "en-GB") :
            $availability['availability'] = __('Sold Out', 'woocommerce');
        endif;
    }
    return $availability;
}

// Giới hạn columns sản phẩm 
function loop_columns()
{
    return 4;
}

add_filter('loop_shop_columns', 'loop_columns', 999);


// Giới hạn số lượng sản phẩm 
add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);
function new_loop_shop_per_page($cols)
{
    $cols = 20;
    return $cols;
}

if ('yes' === get_option('woocommerce_enable_ajax_add_to_cart')) {
    add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
    add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
}
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
function woocommerce_ajax_add_to_cart()
{
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);
    $id_fee = $_POST['id_fee'] == '' ? '1' : $_POST['id_fee'];
    $id = $id_fee;
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }
        $id_fee = 'flat_rate:' . $id_fee;
        if (!is_user_logged_in()) {
            WC()->session->set('chosen_shipping_methods', array($id_fee));
        } else {
            WC()->session->set('id_shipping_method', array($id));
        }
        WC_AJAX::get_refreshed_fragments();
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
        );
        echo wp_send_json($data);
    }
    wp_die();
}

//Function chạy giỏ hàng ajax
function woocommerce_header_add_to_cart_fragment($fragments)
{
    ob_start();

?>
<a class="cart-item" href="<?php echo wc_get_cart_url() ?>">
    <span class="number-cart">
        <?php echo sprintf(_n('%d', '%d', WC()->cart->cart_contents_count, 'woothemes'), WC()->cart->cart_contents_count); ?>
    </span>
</a>
<?php

    $fragments['.cart-item'] = ob_get_clean();

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

/**
 * Change number of related products output
 */
function woo_related_products_limit()
{
    global $product;

    $args['posts_per_page'] = 5;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'jk_related_products_args', 20);
function jk_related_products_args($args)
{
    $args['posts_per_page'] = 5; // 4 related products
    $args['columns'] = 5; // arranged in 2 columns
    return $args;
}

function check_order_phone()
{
    $phone = $_POST['phone'];
    if ($phone != '') {
        $args = array(
            'billing_phone' => $phone,
            'limit' => 2,
            'orderby' => 'ID',
            'order' => 'ADSC',
        );
    } else {
        $args = array(
            'billing_phone' => '0900000000',
        );
    }
    $orders = wc_get_orders($args);
    ob_start();
    if (count($orders) != 0) {
    ?>
<table
    class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
    <thead>
        <tr>
            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number">
                <span class="nobr">Đơn hàng</span>
            </th>
            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date">
                <span class="nobr">Ngày</span>
            </th>
            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status">
                <span class="nobr">Tình trạng</span>
            </th>
            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total">
                <span class="nobr">Tổng</span>
            </th>
            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions">
                <span class="nobr">Các thao tác</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
                foreach ($orders as $order) {
                ?>
        <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order">
            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number"
                style="border-top:1px solid #ccc" data-title="Đơn hàng">
                <?php echo '#' . $order->get_id(); ?>
            </td>
            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date"
                style="border-top:1px solid #ccc" data-title="Ngày">
                <time>
                    <?php $date = date_create($order->get_date_created());
                                echo date_format($date, "d/m/Y H:i:s"); ?>
                </time>
            </td>
            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status"
                style="border-top:1px solid #ccc" data-title="Tình trạng">
                <?php echo order_status($order->get_status()); ?>
            </td>
            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total"
                style="border-top:1px solid #ccc" data-title="Tổng">
                <span class="woocommerce-Price-amount amount">
                    <?php echo number_format($order->get_total()); ?><span
                        class="woocommerce-Price-currencySymbol">₫</span>
                </span> cho 1 mục
            </td>
            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions"
                style="border-top:1px solid #ccc" data-title="Các thao tác">
                <a href="#orders-detail-<?php echo $order->get_id(); ?>" class="woo-button-more button view">Xem
                    thêm</a>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="padding: 0;border:none">
                <div class="woocommerce-orders-table_row-detail" id="<?php echo 'orders-detail-' . $order->get_id(); ?>"
                    style="display:none;">
                    <div class="thead flex" style="border-top:1px solid #ccc">
                        <p class="font-weight-bold">Sản phẩm</p>
                        <p class="font-weight-bold">Tổng</p>
                    </div>
                    <?php foreach ($order->get_items() as $item_id => $item) {
                                    $id = $item->get_product_id(); ?>
                    <div class="tbody flex">
                        <p class="item-name "><a href="<?php echo $id ?>">
                                <?php echo $item->get_name(); ?>
                            </a></p>
                        <p class="item-total">
                            <?php echo number_format($item->get_total()) . "đ"; ?>
                        </p>
                    </div>
                    <?php } ?>
                    <div class="tfoot">
                        <div class="item flex">
                            <p class="font-weight-bold">Tổng số phụ:</p>
                            <p></p>
                        </div>
                        <div class="item flex">
                            <p class="font-weight-bold">Giao nhận hàng:</p>
                            <p></p>
                        </div>
                        <div class="item flex">
                            <p class="font-weight-bold">Phương thức thanh toán:</p>
                            <p></p>
                        </div>
                        <div class="item flex">
                            <p class="font-weight-bold">Tổng cộng:</p>
                            <p></p>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php
                }
                ?>
    </tbody>
</table>
<?php
    } else {
        if ($phone == '') {
            echo "<p class='woocommerce-info'>Vui lòng nhập số điện thoại</p>";
        } else {
            echo "<p class='woocommerce-info'>Không tồn tại đơn hàng có số điện thoại bạn vừa nhập</p>";
        }
    }
    $result = ob_get_clean();
    wp_send_json_success($result);
    die();
}
add_action('wp_ajax_check_order_phone', 'check_order_phone');
add_action('wp_ajax_nopriv_check_order_phone', 'check_order_phone');

function order_status($variable)
{
    switch ($variable) {
        case 'processing':
            echo "Đang xử lý";
            break;
        case 'on-hold':
            echo "Tạm giữ";
            break;
        case 'shipping-progress':
            echo "Đang giao";
            break;
        case 'shipping-completed':
            echo "Đã giao";
            break;
        case 'cancelled':
            echo "Đã hủy";
            break;
        case 'pending':
            echo "Chờ thanh toán";
            break;
        case 'completed':
            echo "Đã hoàn thành";
            break;
        case 'refunded':
            echo "Đã hoàn tiền lại";
            break;
        case 'failed':
            echo "Thất bại";
            break;
        default:
            // chuỗi câu lệnh
            break;
    }
}

//In hoa mã giảm giá khi báo lỗi không tồn tại
function modify_coupon_error_message($err, $err_code, $coupon)
{
    if ($err_code == 105) {
        $err = preg_replace_callback('/"(.*?)"/', function ($matches) {
            return '"' . strtoupper($matches[1]) . '"';
        }, $err);
    }
    return $err;
}
add_filter('woocommerce_coupon_error', 'modify_coupon_error_message', 10, 3);

function apply_coupons()
{
    if (!is_array($_POST['coupons'])) {
        $current_coupons = WC()->cart->get_applied_coupons();
        foreach ($current_coupons as $coupon_code) {
            WC()->cart->remove_coupon($coupon_code);
        }
        return;
    }

    $submitted_coupons = array_map('sanitize_text_field', $_POST['coupons']);

    // Remove all current coupons
    $current_coupons = WC()->cart->get_applied_coupons();
    foreach ($current_coupons as $coupon_code) {
        WC()->cart->remove_coupon($coupon_code);
    }

    // Array to track successfully applied coupons and errors
    $applied_coupons = [];
    $errors = [];

    // Apply new coupons
    foreach ($submitted_coupons as $coupon_code) {
        if (WC()->cart->apply_coupon($coupon_code)) {
            $applied_coupons[] = $coupon_code;
        } else {
            $errors[] = $coupon_code;
        }
    }

    WC()->cart->calculate_totals();

    if (!empty($errors)) {
        wp_send_json_error(['message' => 'Some coupons could not be applied', 'errors' => $errors]);
    } else {
        wp_send_json_success(['message' => 'Coupons applied successfully', 'applied_coupons' => $applied_coupons]);
    }
}

add_action('wp_ajax_apply_coupons', 'apply_coupons');
add_action('wp_ajax_nopriv_apply_coupons', 'apply_coupons');


add_action('woocommerce_admin_process_product_object', 'calculate_save_discount_percentage', 10, 1);
function calculate_save_discount_percentage($product) {
    if (!$product->is_on_sale()) {
        $product->delete_meta_data('_discount_percentage');
        return;
    }

    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();

    if ($regular_price > 0 && $sale_price > 0) {
        $discount_percentage = round(100 - ($sale_price / $regular_price * 100));
        $product->update_meta_data('_discount_percentage', $discount_percentage);
    }

    $product->save_meta_data();
}
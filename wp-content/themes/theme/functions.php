<?php
/*
 *  GLOBAL VARIABLES
 */
define('THEME_DIR', get_stylesheet_directory());
define('THEME_URL', get_stylesheet_directory_uri());

/*
 *  INCLUDED FILES
 */

add_editor_style('css/button-web.css');

$file_includes = [
    'inc/theme-assets.php',
    'inc/theme-setup.php',
    'inc/acf-options.php',
    'inc/theme-shortcode.php',
    'inc/button-editor.php',
    'inc/theme-config.php',
    'inc/theme-login.php',
    'inc/woocommerce-setup.php',
    'inc/custom-layout.php',
    'inc/custom-category.php',
    'inc/custom-register.php',
    'inc/custom-single-product.php',
    'inc/custom-sidebar-news.php',
    'inc/custom-myaccount.php',
    'inc/custom-address.php',
    'inc/custom-orders.php',
    'inc/custom-woo-checkout.php',
    'inc/order-success.php',
    'inc/custom-login.php',
    'inc/custom-cart.php',
];

require_once __DIR__ . '/vendor/autoload.php';

foreach ($file_includes as $file) {
    if (!$filePath = locate_template($file)) {
        trigger_error(sprintf(__('Missing included file'), $file), E_USER_ERROR);
    }

    require_once $filePath;
}

unset($file, $filePath);

add_filter('use_block_editor_for_post', '__return_false');

//tình phần trăm giảm tiền
function devvn_percentage_get_cache($post_id)
{
    return get_post_meta($post_id, '_devvn_product_percentage', true);
}

//Định dạng kết quả dạng -{value}%. Ví dụ -20%
function devvn_percentage_format($value)
{
    return str_replace('{value}', $value, '-{value}%');
}

function devvn_presentage_bubble($product)
{
    $post_id = $product->get_id();

    if ($product->is_type('simple') || $product->is_type('external')) {
        $regular_price  = $product->get_regular_price();
        $sale_price     = $product->get_sale_price();
        if ($regular_price == 0) {
            $bubble_content = 0;
        } else {
            $bubble_content = round(((floatval($regular_price) - floatval($sale_price)) / floatval($regular_price)) * 100);
        }
    } elseif ($product->is_type('variable')) {
        if ($bubble_content = devvn_percentage_get_cache($post_id)) {
            return devvn_percentage_format($bubble_content);
        }

        $available_variations = $product->get_available_variations();
        $maximumper           = 0;

        for ($i = 0; $i < count($available_variations); ++$i) {
            $variation_id     = $available_variations[$i]['variation_id'];
            $variable_product = new WC_Product_Variation($variation_id);
            if (!$variable_product->is_on_sale()) {
                continue;
            }
            $regular_price = $variable_product->get_regular_price();
            $sale_price    = $variable_product->get_sale_price();
            $percentage    = round(((floatval($regular_price) - floatval($sale_price)) / floatval($regular_price)) * 100);
            if ($percentage > $maximumper) {
                $maximumper = $percentage;
            }
        }

        $bubble_content = sprintf(__('%s', 'woocommerce'), $maximumper);
    } else {
        $bubble_content = __('Sale!', 'woocommerce');

        return $bubble_content;
    }
    if ($bubble_content == 100) {
        return devvn_percentage_format(0);
    } else {
        return devvn_percentage_format($bubble_content);
    }
}

//Hàm đếm số lượng sản phẩm của một danh mục
function get_product_count_by_category($term_id)
{
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => $term_id,
                'operator' => 'IN'
            ),
        ),
    );

    $query = new WP_Query($args);

    // Return the count of products
    return $query->found_posts;
}

// 1. Thêm một trường tùy chỉnh để lưu trữ số lượt xem cho mỗi bài đăng
function custom_post_views_field()
{
    add_post_meta(get_the_ID(), 'custom_post_views', 0, true);
}
add_action('wp_head', 'custom_post_views_field');

// 2. Tăng giá trị của trường tùy chỉnh mỗi khi một bài đăng được xem
function increment_post_views()
{
    if (is_single()) {
        $post_id = get_the_ID();
        $views = get_post_meta($post_id, 'custom_post_views', true);
        $views++;
        update_post_meta($post_id, 'custom_post_views', $views);
    }
}
add_action('wp_head', 'increment_post_views');

// hàm thêm sửa xoá các trường dữ liệu trong chi tiết đơn hàng
function custom_get_order_item_totals($total_rows, $order, $tax_display)
{
    // Gỡ bỏ trường subtotal
    if (isset($total_rows['subtotal'])) {
        unset($total_rows['subtotal']);
    }
    // Get order subtotal
    $order_subtotal = $order->get_subtotal();

    // Calculate total quantity
    $order_items = $order->get_items();
    $total_quantity = 0;
    foreach ($order_items as $item_id => $item) {
        $total_quantity += intval($item->get_quantity());
    }

    // Create a new array to hold the sorted totals
    $sorted_totals = array();

    // Add temporary total quantity row without the colon
    $sorted_totals['temporary_total_quantity'] = array(
        'label' => __('Tạm tính (' . $total_quantity . ' sản phẩm)', 'woocommerce') . ' ', // Removed the colon
        'value' => wc_price($order_subtotal),
    );

    // Add temporary total row without the colon
    $sorted_totals['temporary_total'] = array(
        'label' => __('Tạm tính', 'woocommerce') . ' ', // Removed the colon
        'value' => wc_price($order_subtotal),
    );

    $discount_total = $order->get_discount_total();
    if ($discount_total > 0) {
        // Nếu có giảm giá, hiển thị tổng giảm giá
        $sorted_totals['discount'] = array(
            'label' => __('Giảm giá', 'woocommerce') . ' ', // Đã loại bỏ dấu hai chấm
            'value' => '-' . wc_price($discount_total),
        );
    } else {
        // Nếu không có giảm giá, hiển thị ---
        $sorted_totals['discount'] = array(
            'label' => __('Giảm giá', 'woocommerce') . ' ', // Đã loại bỏ dấu hai chấm
            'value' => '---',
        );
    }
    // Gỡ bỏ dòng giảm giá gốc nếu tồn tại
    if (isset($total_rows['discount'])) {
        unset($total_rows['discount']);
    }
    // Remove the original payment method row
    $payment_method_row = $total_rows['payment_method'];
    unset($total_rows['payment_method']);

    // If you want to remove the colon from the payment method label as well
    if (isset($payment_method_row)) {
        $payment_method_row['label'] = rtrim($payment_method_row['label'], ':') . ' '; // Remove the colon and add a space for consistency
    }

    $total_rows['shipping']['label'] = rtrim('Phí giao hàng');

    // Merge the sorted totals with the original totals
    $total_rows = array_merge($sorted_totals, $total_rows);

    // Add the payment method row to the end without the colon
    if (isset($payment_method_row)) {
        $total_rows['payment_method'] = $payment_method_row;
    }

    $total_rows['status'] = array(
        'label' => __('Tình trạng', 'woocommerce') . ' ', // Đã loại bỏ dấu hai chấm
        'value' => wc_get_order_status_name($order->get_status()),
    );


    // Adjust any other labels as needed to remove colons
    foreach ($total_rows as $key => $row) {
        $total_rows[$key]['label'] = rtrim($row['label'], ':') . ' '; // Remove the colon from each label
    }

    return $total_rows;
}
add_filter('woocommerce_get_order_item_totals', 'custom_get_order_item_totals', 20, 3);

//thêm loading lúc nhấn nút đặt hàng
function wp_kama_enqueue_checkout_script()
{
    // Enqueue script only on the checkout page
    if (is_checkout()) {
        wp_enqueue_script('script', get_template_directory_uri() . '/src/js/script.js', array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'wp_kama_enqueue_checkout_script');

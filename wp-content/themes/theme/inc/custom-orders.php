<?php
//sắp xếp thứ tự cột trong quản lý đơn hàng
function custom_wc_get_account_orders_columns($columns)
{
    // Tạo một mảng mới để lưu trữ các cột theo thứ tự bạn mong muốn
    $columns['order-date'] = __('Ngày mua hàng', 'woocommerce');
    $columns['order-status'] = __('Tình trạng', 'woocommerce');
    $columns['order-total'] = __('Thành tiền', 'woocommerce');
    $columns['order-quantity']  = __('Số lượng', 'woocommerce');
    // Xóa hoặc ẩn cột không cần thiết nếu muốn
    unset($columns['order-actions']);

    $columns = array(
        'order-number'  => $columns['order-number'],
        'order-date'    => $columns['order-date'],
        'order-quantity'  => $columns['order-quantity'],
        'order-total'   => $columns['order-total'],
        'order-status'  => $columns['order-status'],
    );

    // Trả về mảng các cột đã sắp xếp lại theo thứ tự mới

    return $columns;
}
add_filter('woocommerce_account_orders_columns', 'custom_wc_get_account_orders_columns');


//khởi tạo trạng thái
function register_delivering_order_status()
{
    register_post_status('wc-delivering', array(
        'label'                     => 'Đang giao hàng',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,

    ));
    register_post_status('wc-await-process', array(
        'label'                     => 'Chờ xử lý',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,

    ));
}
add_action('init', 'register_delivering_order_status');

//thêm trạng thái đang giao hàng và chờ xử lý
function add_delivering_to_order_statuses($order_statuses)
{
    $new_order_statuses = array();
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-processing' === $key) {
            $new_order_statuses['wc-delivering'] = 'Đang giao hàng';
            $new_order_statuses['wc-await-process'] = 'Chờ xử lý';
        }
    }
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_delivering_to_order_statuses');


// Đặt trạng thái mặc định của đơn hàng là "Chờ xử lý"
add_action('woocommerce_checkout_order_processed', 'changing_order_status_before_payment', 10, 3);
function changing_order_status_before_payment($order_id, $posted_data, $order)
{
    $order->update_status('await-process');
}

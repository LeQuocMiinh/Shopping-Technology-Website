<?php 

    add_action('woocommerce_thankyou', 'send_custom_email_on_order_success');

    function send_custom_email_on_order_success($order_id) {
        $order = wc_get_order($order_id);        
        $to_email = $order->get_billing_email();
        $customer_name = $order->get_formatted_billing_full_name();
        $order_total = $order->get_total();
        
        $subject = 'Thông báo xác nhận đơn hàng';
        $message = templateInformSuccessOrder($order);        
        $headers = array('Content-Type: text/html; charset=UTF-8');        
        wp_mail($to_email, $subject, $message, $headers);        
    }

    /**
     * Mẫu email xác nhận đặt hàng thành công
     */
    function templateInformSuccessOrder($order_id) {
        $order = wc_get_order($order_id);
        $customer_phonenumber = get_post_meta($order->get_id(), 'billing_custom_phone', true);
        $order_items = $order->get_items();
        $billing_tab_address = get_post_meta($order->get_id(), 'billing_tab_address', true);
        if($billing_tab_address == 1) {
            $address_detail = trim(get_post_meta($order->get_id(), 'address_detail', true));
            $address_wards = get_post_meta($order->get_id(), 'address_wards', true);
            $address_district = get_post_meta($order->get_id(), 'address_district', true);
            $address_city = get_post_meta($order->get_id(), 'address_city', true);
            $address_string = $address_detail . ', ' . $address_wards . ', ' . $address_district . ', ' . $address_city;
        } else {
            $address_string = 'Nhận hàng tại cửa hàng.';
        }
        
        ob_start();
        ?>
<div
    style="font-family: 'Inter', sans-serif; width: 100%; height: 100%; position: relative; background: #EDF0FF; padding: 30px 0; font-size: 16px">
    <div style="max-width: 726px;
                width: 100%; 
                margin: 0 auto; 
                border-radius: 20px;
                background: #FFF;
                color: #212B36;
                padding: 32px;">
        <div class="content">
            <div class="text">
                <p style="font-size: 16px;">Xin chào <?php echo trim($order->get_formatted_billing_full_name()); ?>,
                </p>
                <p style="color: #1435C3;
                          font-weight: 600;
                          font-size: 20px;
                          line-height: 32px;
                          margin: 16px 0;">
                    CẢM ƠN BẠN, ĐƠN HÀNG CỦA BẠN ĐÃ ĐƯỢC TIẾP NHẬN
                </p>
            </div>
            <div class="header-info">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Mã đơn hàng</td>
                        <td style="color: #0D6EFD">#<?php echo  $order->get_order_number(); ?></td>
                        <td>
                            <a style="color: #0D6EFD; text-decoration: none;"
                                href="<?php echo $order->get_view_order_url(); ?>">Xem chi tiết</a>
                            <span>
                                <img style="max-width: 6px"
                                    src="<?php echo get_template_directory_uri().'/images/order-detail.svg' ?>"
                                    alt="icon">
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;"> Ngày mua hàng</td>
                        <td> <?php echo $order->get_date_created()->date('H:i d-m-Y'); ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div style="margin: 16px 0" class="product-list">
                <?php $index = 1; foreach($order_items as $key => $item): ?>
                <div class="item">
                    <?php echo $index ?>.
                    <?php echo $item->get_name(); ?> x
                    <?php echo $item->get_quantity(); ?>
                </div>
                <?php $index++; endforeach; ?>
            </div>

            <div style="border-bottom: 1px solid #0000000D; padding-bottom: 10px;" class="order-info">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Tạm tính</td>
                        <td><?php echo number_format($order->get_subtotal(), 0, '.', '.'); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Giảm giá</td>
                        <td><?php echo number_format($order->get_total_discount(), 0, '.', '.'); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Phí giao hàng</td>
                        <td><?php echo number_format($order->get_shipping_total(), 0, '.', '.'); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Tổng</td>
                        <td><?php echo number_format($order->get_total(), 0, '.', '.'); ?></td>
                    </tr>
                </table>
            </div>

            <div style="border-bottom: 1px solid #0000000D; padding-bottom: 10px;" class="shipping-info">
                <p style="color: #212B36; font-weight: 600; margin-bottom: 8px;">THÔNG TIN NHẬN HÀNG</p>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Tên người nhận</td>
                        <td><?php echo $order->get_formatted_billing_full_name(); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Số điện thoại</td>
                        <td><?php echo $customer_phonenumber; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Địa chỉ nhận hàng</td>
                        <td><?php echo $address_string ?></td>
                    </tr>
                </table>
            </div>

            <div style="border-bottom: 1px solid #0000000D; padding-bottom: 10px;" class="billing-info">
                <p style="color: #212B36; font-weight: 600; margin-bottom: 8px;">THÔNG TIN THANH TOÁN</p>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Phương thức thanh toán</td>
                        <td><?php echo $order->get_payment_method_title(); ?></td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Tình trạng</td>
                        <td>Chưa thanh toán</td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Ngân hàng</td>
                        <td><?php echo get_field('bank_name', 8) ?></td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Số tài khoản</td>
                        <td><?php echo get_field('account_id', 8) ?></td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 8px 0;">Tên tài khoản</td>
                        <td><?php echo get_field('account_name', 8) ?></td>
                    </tr>
                </table>
            </div>

            <div style="margin-bottom: 32px; margin-top: 24px">
                Trân trọng,
            </div>
            <div>
                Đội ngũ Max Smart
            </div>
            <div>
                Bạn có thắc mắc? Liên hệ chúng tôi
                <a href="<?php echo get_permalink(24); ?>">tại đây.</a>
            </div>
        </div>
    </div>
</div>
<?php
        $template = ob_get_clean();
        return $template;
    }
?>
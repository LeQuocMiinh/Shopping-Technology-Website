<?php

/**
 * return html
 * Hàm trả về giao diện cart
 */
function cartHtml($cart_after)
{
    ob_start(); ?>
    <?php
    foreach ($cart_after as $cart_item) :
        $quantity = $cart_item['quantity'];
        $product_id = $cart_item['id_product'];
        $product_att = wc_get_product($product_id);
        $link = $product_att->get_permalink();
        $attributes = $product_att->get_attributes();
        $price_sale = $product_att->get_sale_price();
        $price_regular = $product_att->get_regular_price();
        $variation_id = $cart_item['variation_id'];
        if ($variation_id) {
            $product_var = wc_get_product($variation_id);
            $color = $product_var->get_attribute('pa_mau-sac');
        }
        $img = get_the_post_thumbnail_url($product_id);
        $name = get_the_title($product_id);
        if ($price_sale != '') {
            $discount = devvn_presentage_bubble($product_att);
        } else {
            $discount = '0%';
        }
    ?>
        <div class="item">
            <a href="<?php echo $link ?>" class="cart-sub-item">
                <div class="image">
                    <?php $img = get_the_post_thumbnail_url($product_id); ?>
                    <img src="<?php echo $img ?>" alt="<?php echo $name ?>">
                </div>
                <div class="info-cart">
                    <div class="name">
                        <p>
                            <?php echo $name ?>
                        </p>
                    </div>
                    <div class="text-grey">
                        <?php if (!empty($attributes)) : ?>
                            <div>
                                <?php
                                foreach ($attributes as $attribute) {
                                    $visible = $attribute->get_visible();
                                    if ($visible != 0) {
                                        $name = $attribute->get_name();
                                        $terms = wp_get_post_terms($product_id, $name, 'all');
                                        foreach ($terms as $term) {
                                            echo $term->name . ' ';
                                        }
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($color) : ?>
                            <div> Màu:
                                <?php echo $color ?>
                            </div>
                        <?php endif; ?>
                        <div> Số lượng:
                            <?php echo $quantity ?>
                        </div>
                    </div>
                    <div class="price">
                        <?php
                        if ($product_att->get_type() == 'variable') {
                            $variation_obj = new WC_Product_variation($variation_id);
                            $stock = $variation_obj->get_stock_status();
                        } else {
                            $stock = $product_att->get_stock_status();
                        }
                        ?>
                        <?php if ($stock == 'instock') : ?>
                            <?php
                            if ($product_att->get_type() == 'variable') :
                                $default_attributes = $product_att->get_default_attributes();
                                $id_vari = $variation_id;
                                if (count($default_attributes) > 0) :
                                    $price_regular = get_post_meta($id_vari, '_regular_price', true);
                                    $price_sale = get_post_meta($id_vari, '_sale_price', true);
                                    if ($price_sale != '') {
                                        $discount = devvn_presentage_bubble($product_att);
                                    } else {
                                        $discount = '0%';
                                    }
                                    if ($price_sale != '') : ?>
                                        <div class="price-sale">
                                            <span>
                                                <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="price-regular">
                                        <span class="price <?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                            <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                        </span>
                                        <?php if ($price_sale != '') : ?>
                                            <span class="discount">
                                                <?php echo $discount ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php else :
                                    echo $product_att->get_price_html();
                                endif;
                            else : ?>
                                <?php if ($price_sale != '') : ?>
                                    <div class="price-sale">
                                        <span>
                                            <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div class="price-regular">
                                    <span class="price <?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                        <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                    </span>
                                    <?php if ($price_sale != '') : ?>
                                        <span class="discount">
                                            <?php echo $discount ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                        <?php endif;
                        endif;
                        ?>
                        <?php if ($stock == 'outofstock') : ?>
                            <div class="custom-stock">
                                <span>
                                    <?php echo get_field('text_out_stock', 'option') ?>
                                </span>
                            </div>

                        <?php endif; ?>
                        <?php if ($stock == 'onbackorder') : ?>
                            <div class="custom-stock">
                                <span>
                                    <?php echo get_field('text_stocking', 'option') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
<?php
    $result = ob_get_clean();
    return $result;
}
/**
 * @return json
 * Hàm lưu id sản phẩm vào session
 */
function woocommerce_ajax_add_to_cart_session()
{
    $params = $_POST;
    $product_id = $params['product_id'];
    $quantity = $params['quantity'];
    $variation_id = isset($params['variation_id']) ? $params['variation_id'] : '';
    $id_fee = $params['id_fee'] == '' ? '1' : $params['id_fee'];
    $id = $id_fee;
    $array = [
        'id_product' => $product_id,
        'variation_id' => $variation_id,
        'quantity' => $quantity,
        'add_cart' => true
    ];
    $cart_array = [];
    array_push($cart_array, $array);
    $cart = wc()->session->get('cart_item_id_1');
    if (isset(WC()->session)) {
        if (!WC()->session->has_session()) {
            WC()->session->set_customer_session_cookie(true);
        }
    }
    if (empty($cart)) {
        wc()->session->set('cart_item_id_1', $cart_array);
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
    } else {
        $bien = '';
        $flag = true;
        foreach ($cart as $key => $item_cart) {
            if ($variation_id == '') {
                if ($product_id == $item_cart['id_product']) {
                    $flag = false;
                    $bien = $key;
                    break;
                }
            } else {
                if ($product_id == $item_cart['id_product'] && $variation_id == $item_cart['variation_id']) {
                    $flag = false;
                    $bien = $key;
                    break;
                }
                if ($product_id == $item_cart['id_product'] && $variation_id == $item_cart['variation_id']) {
                    $flag = false;
                    $bien = '';
                    break;
                }
            }
        }
        if ($flag == false) {
            if ($variation_id == '') {
                $cart[$bien]['quantity'] = (int) $cart[$bien]['quantity'] + 1;
            } else {
                if ($bien != '') {
                    $cart[$bien]['quantity'] = (int) $cart[$bien]['quantity'] + 1;
                } else {
                    $array = [
                        'id_product' => $product_id,
                        'variation_id' => $variation_id,
                        'quantity' => $quantity,
                        'add_cart' => true
                    ];
                    array_push($cart, $array);
                }
            }
        } else {
            $array = [
                'id_product' => $product_id,
                'variation_id' => $variation_id,
                'quantity' => $quantity,
                'add_cart' => true
            ];
            array_push($cart, $array);
        }
        wc()->session->set('cart_item_id_1', $cart);
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
    };
    $cart_after = wc()->session->get('cart_item_id_1');
    $number_item_cart = 0;
    foreach ($cart_after as $item_cart) {
        $number_item_cart += (int) $item_cart['quantity'];
    }
    if ($id_fee == '4') {
        $id_fee = 'local_pickup:' . $id_fee;
    } else {
        $id_fee = 'flat_rate:' . $id_fee;
    }
    WC()->session->set('chosen_shipping_methods', array($id_fee));
    $result = cartHtml($cart_after);
    wp_send_json(['status' => true, 'number_item_cart' => $number_item_cart, 'result' => $result]);
}
add_action('wp_ajax_woocommerce_ajax_add_to_cart_session', 'woocommerce_ajax_add_to_cart_session', 10);
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart_session', 'woocommerce_ajax_add_to_cart_session', 10);

/**
 * @return json
 * Hàm lưu id sản phẩm vào session trang chi tiết sản phẩm
 */
function woocommerce_ajax_add_to_cart_session_single()
{
    $params = $_POST;
    $product_id = $params['product_id'];
    $quantity = $params['quantity'];
    $variation_id = isset($params['variation_id']) ? $params['variation_id'] : '';
    $id_fee = $params['id_fee'] == '' ? '1' : $params['id_fee'];
    $id = $id_fee;
    $array = [
        'id_product' => $product_id,
        'variation_id' => $variation_id,
        'quantity' => $quantity,
        'add_cart' => true
    ];
    $cart_array = [];
    array_push($cart_array, $array);
    $cart = wc()->session->get('cart_item_id_1');
    if (empty($cart)) {
        wc()->session->set('cart_item_id_1', $cart_array);
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
    } else {
        $bien = '';
        $flag = true;
        foreach ($cart as $key => $item_cart) {
            if ($variation_id == '') {
                if ($product_id == $item_cart['id_product']) {
                    $flag = false;
                    $bien = $key;
                    break;
                }
            } else {
                if ($product_id == $item_cart['id_product'] && $variation_id == $item_cart['variation_id']) {
                    $flag = false;
                    $bien = $key;
                    break;
                }
                if ($product_id == $item_cart['id_product'] && $variation_id == $item_cart['variation_id']) {
                    $flag = false;
                    $bien = '';
                    break;
                }
            }
        }
        if ($flag == false) {
            if ($variation_id == '') {
                $cart[$bien]['quantity'] = (int) $cart[$bien]['quantity'] + (int) $quantity;
            } else {
                if ($bien != '') {
                    $cart[$bien]['quantity'] = (int) $cart[$bien]['quantity'] + (int) $quantity;
                } else {
                    $array = [
                        'id_product' => $product_id,
                        'variation_id' => $variation_id,
                        'quantity' => $quantity,
                        'add_cart' => true
                    ];
                    array_push($cart, $array);
                }
            }
        } else {
            $array = [
                'id_product' => $product_id,
                'variation_id' => $variation_id,
                'quantity' => $quantity,
                'add_cart' => true
            ];
            array_push($cart, $array);
        }
        wc()->session->set('cart_item_id_1', $cart);
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
    };
    $cart_after = wc()->session->get('cart_item_id_1');
    $number_item_cart = 0;
    foreach ($cart_after as $item_cart) {
        $number_item_cart += (int) $item_cart['quantity'];
    }

    if ($id_fee == '4') {
        $id_fee = 'local_pickup:' . $id_fee;
    } else {
        $id_fee = 'flat_rate:' . $id_fee;
    }

    WC()->session->set('chosen_shipping_methods', array($id_fee));

    $result = cartHtml($cart_after);
    wp_send_json(['status' => true, 'number_item_cart' => $number_item_cart, 'result' => $result, 'cart' => $cart]);
}
add_action('wp_ajax_woocommerce_ajax_add_to_cart_session_single', 'woocommerce_ajax_add_to_cart_session_single');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart_session_single', 'woocommerce_ajax_add_to_cart_session_single');

/**
 * @return json
 * Hàm lưu id sản phẩm vào session trang chi tiết sản phẩm (Nút mua ngay)
 */
function woocommerce_ajax_buy_to_cart_session_single()
{
    $params = $_POST;
    $product_id = $params['product_id'];
    $quantity = $params['quantity'];
    $variation_id = isset($params['variation_id']) ? $params['variation_id'] : '';
    $id_fee = $params['id_fee'] == '' ? '1' : $params['id_fee'];
    $shipping_method = "flat_rate:1";
    if ($id_fee == '4') {
        $shipping_method = 'local_pickup:4';
    } else {
        $shipping_method = 'flat_rate:' . $id_fee;
    }
    $array = [
        'id_product' => $product_id,
        'variation_id' => $variation_id,
        'quantity' => $quantity,
        'add_cart' => true
    ];
    $payment_method = isset($params['payment_method']) ? $params['payment_method'] : 'bacs';
    $cart_array = [];
    array_push($cart_array, $array);
    $cart = wc()->session->get('cart_item_id_1');
    if (empty($cart)) {
        wc()->session->set('cart_item_id_1', $cart_array);
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
        $_SESSION['chosen_shipping_methods'] = $shipping_method;
    } else {
        unset($_SESSION['chosen_shipping_methods']);
        $bien = '';
        $flag = true;
        foreach ($cart as $key => $item_cart) {
            if ($variation_id == '') {
                if ($product_id == $item_cart['id_product']) {
                    $flag = false;
                    $bien = $key;
                    break;
                }
            } else {
                if ($product_id == $item_cart['id_product'] && $variation_id == $item_cart['variation_id']) {
                    $flag = false;
                    $bien = $key;
                    break;
                }
                if ($product_id == $item_cart['id_product'] && $variation_id == $item_cart['variation_id']) {
                    $flag = false;
                    $bien = '';
                    break;
                }
            }
        }
        if ($flag == false) {
            if ($variation_id == '') {
                $cart[$bien]['quantity'] = (int) $cart[$bien]['quantity'] + (int) $quantity;
            } else {
                if ($bien != '') {
                    $cart[$bien]['quantity'] = (int) $cart[$bien]['quantity'] + (int) $quantity;
                } else {
                    $array = [
                        'id_product' => $product_id,
                        'variation_id' => $variation_id,
                        'quantity' => $quantity,
                        'add_cart' => true
                    ];
                    array_push($cart, $array);
                }
            }
        } else {
            $array = [
                'id_product' => $product_id,
                'variation_id' => $variation_id,
                'quantity' => $quantity,
                'add_cart' => true
            ];
            array_push($cart, $array);
        }
        wc()->session->set('cart_item_id_1', $cart);
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
    }

    if ($payment_method) {
        WC()->session->set('chosen_payment_method', $payment_method);
    }


    $cart_after = wc()->session->get('cart_item_id_1');
    $number_item_cart = 0;
    foreach ($cart_after as $item_cart) {
        $number_item_cart += (int) $item_cart['quantity'];
    }

    WC()->session->set('chosen_shipping_methods', array($shipping_method));

    $result = cartHtml($cart_after);

    wp_send_json([
        'status' => true,
        'number_item_cart' => $number_item_cart,
        'result' => $result, 'cart' => $cart,
    ]);
}

add_action('wp_ajax_woocommerce_ajax_buy_to_cart_session_single', 'woocommerce_ajax_buy_to_cart_session_single');
add_action('wp_ajax_nopriv_woocommerce_ajax_buy_to_cart_session_single', 'woocommerce_ajax_buy_to_cart_session_single');

function woocommerce_ajax_update_qty_to_cart_session()
{
    $params = $_POST;
    $product_id = $params['product_id'];
    $quantity = $params['quantity'];
    $variation_id = isset($params['variation_id']) ? $params['variation_id'] : '';
    $cart = wc()->session->get('cart_item_id_1');
    $bien = '';
    foreach ($cart as $key => $item_cart) {
        if ($variation_id == '') {
            if ($product_id == $item_cart['id_product']) {
                $bien = $key;
                break;
            }
        } else {
            if ($product_id == $item_cart['id_product'] && $variation_id == $item_cart['variation_id']) {
                $bien = $key;
                break;
            }
        }
    }
    $cart[$bien]['quantity'] = (int) $quantity;
    wc()->session->set('cart_item_id_1', $cart);
    wp_send_json(['status' => true, 'cart' => $cart]);
}

add_action('wp_ajax_woocommerce_ajax_update_qty_to_cart_session', 'woocommerce_ajax_update_qty_to_cart_session');
add_action('wp_ajax_nopriv_woocommerce_ajax_update_qty_to_cart_session', 'woocommerce_ajax_update_qty_to_cart_session');

//Hàm xóa sản phẩm trong giỏ hàng session
function woocommerce_ajax_remove_to_cart_session()
{
    $params = $_POST;
    $product_id = $params['product_id'];
    $variation_id = isset($params['variation_id']) ? $params['variation_id'] : '';
    $cart = wc()->session->get('cart_item_id_1');
    $bien = '';
    foreach ($cart as $key => $item_cart) {
        if ($variation_id == '') {
            if ($product_id == $item_cart['id_product']) {
                $bien = $key;
                break;
            }
        } else {
            if ($product_id == $item_cart['id_product'] && $variation_id == $item_cart['variation_id']) {
                $bien = $key;
                break;
            }
        }
    }
    unset($cart[$bien]);
    wc()->session->set('cart_item_id_1', $cart);
    wp_send_json(['status' => true, 'cart' => $cart]);
}

add_action('wp_ajax_woocommerce_ajax_remove_to_cart_session', 'woocommerce_ajax_remove_to_cart_session');
add_action('wp_ajax_nopriv_woocommerce_ajax_remove_to_cart_session', 'woocommerce_ajax_remove_to_cart_session');

//Hàm xóa sản phẩm khi thanh toán
function woocommerce_ajax_order_to_cart_session()
{
    $array_item = $_POST['array_item'];
    $array_un_item = $_POST['array_un_item'];
    $cart = wc()->session->get('cart_item_id_1');
    foreach ($array_un_item as $item) {
        if ($cart[$item]['variation_id'] != '') {
            foreach (WC()->cart->get_cart() as $item_key => $items) {
                if ($items['variation_id'] == $$cart[$item]['variation_id']) {
                    WC()->cart->remove_cart_item($item_key);
                }
            }
        } else {
            $product_id = $cart[$item]['id_product'];
            $product_cart_id = WC()->cart->generate_cart_id($product_id);
            $cart_item_key = WC()->cart->find_product_in_cart($product_cart_id);
            if ($cart_item_key) WC()->cart->remove_cart_item($cart_item_key);
        }
    }
    foreach ($array_item  as $item) {
        unset($cart[$item]);
    }
    wc()->session->set('cart_item_id_1', $cart);
    wp_send_json(['status' => true]);
}

add_action('wp_ajax_woocommerce_ajax_order_to_cart_session', 'woocommerce_ajax_order_to_cart_session');
add_action('wp_ajax_nopriv_woocommerce_ajax_order_to_cart_session', 'woocommerce_ajax_order_to_cart_session');

//Hàm update lại giỏ hàng sau khi thanh toán nếu còn sản phẩm trong giỏ hàng session
function thankyou_ajax_update_cart()
{
    $cart = wc()->session->get('cart_item_id_1');
    foreach ($cart as $item) {
        $product_id = $item['id_product'];
        $quantity = $item['quantity'];
        $variation_id = $item['variation_id'];
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
    }
}
add_action('wp_ajax_thankyou_ajax_update_cart', 'thankyou_ajax_update_cart');
add_action('wp_ajax_nopriv_thankyou_ajax_update_cart', 'thankyou_ajax_update_cart');


//thêm phương thức thanh toán
add_filter('woocommerce_payment_gateways', 'add_custom_payment_gateway');

function add_custom_payment_gateway($gateways)
{
    $gateways[] = 'WC_Custom_Payment_Gateway';
    return $gateways;
}

class WC_Custom_Payment_Gateway extends WC_Payment_Gateway
{
    public function __construct()
    {
        $this->id = 'tra-gop'; // Payment gateway ID
        $this->icon = ''; // URL to the icon
        $this->method_title = __('Trả góp', 'woocommerce'); // Displayed title
        $this->method_description = __('Mua hàng trả góp với lãi suất 0%', 'woocommerce'); // Description
        $this->has_fields = true; // Whether or not the gateway needs fields to function

        // Load the settings
        $this->init_form_fields();
        $this->init_settings();

        // Define user settings
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');

        // Actions
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    // Initialize form fields
    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable Trả góp payment', 'woocommerce'),
                'default' => 'no'
            ),
            'title' => array(
                'title' => __('Title', 'woocommerce'),
                'type' => 'text',
                'description' => __('Title of the payment method.', 'woocommerce'),
                'default' => __('Custom Payment Gateway', 'woocommerce')
            ),
            'description' => array(
                'title' => __('Description', 'woocommerce'),
                'type' => 'textarea',
                'description' => __('Description of the payment method.', 'woocommerce'),
                'default' => __('Pay with Custom Payment Gateway', 'woocommerce')
            )
        );
    }

    // Process payment
    public function process_payment($order_id)
    {
        $order = wc_get_order($order_id);

        // Mark order as processing or complete
        $order->update_status('processing', __('Payment received, awaiting fulfillment.', 'woocommerce'));

        // Reduce stock levels
        $order->reduce_order_stock();

        // Remove cart
        WC()->cart->empty_cart();

        // Return success
        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url($order)
        );
    }
}

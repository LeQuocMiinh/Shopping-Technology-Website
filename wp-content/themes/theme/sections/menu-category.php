<div class="menu-custom-wrapper">
    <ul class="menu-custom">
        <?php
        $menu_name = 'menu_main';
        $locations = get_nav_menu_locations();
        $menu = wp_get_nav_menu_object($locations[$menu_name]);
        $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
        $i = 1;
        foreach ($menuitems as $menuitem):
            if ($menuitem->menu_item_parent == 0) :
                ?>
        <li class="menu-item-<?php echo $i++ ?>">
            <?php $icon = get_field('icon_menu',$menuitem->ID) ?>
            <img src="<?php echo $icon['url'] ?>" alt="<?php echo $menuitem->title; ?>">
            <div class="menu-title">
                <a href="<?php echo $menuitem->url ?>">
                    <?php echo $menuitem->title; ?>
                </a>
            </div>
            <div class="sub-menu-wrapper">
                <a href="<?php echo $menuitem->url ?>" class="see-all">
                    Xem tất cả
                    <img src="<?php echo get_template_directory_uri().'/images/arrow-right.svg' ?>" alt="icon">
                </a>
                <ul class="sub-menu-mobile-2">
                    <?php
                    foreach ($menuitems as $submenuitem):
                    if ($submenuitem->menu_item_parent == $menuitem->ID) :
                ?>
                    <li class="menu-mobile-item-<?php echo $i++ ?>">
                        <a href="<?php echo $submenuitem->url ?>">
                            <?php echo $submenuitem->title ?>
                        </a>
                        <ul class="sub-menu-mobile-3">
                            <?php
                            foreach ($menuitems as $submenuitem3):
                            if ($submenuitem3->menu_item_parent == $submenuitem->ID) :
                        ?>
                            <li class="menu-mobile-item-<?php echo $i++ ?>">
                                <a href="<?php echo $submenuitem3->url ?>"><?php echo $submenuitem3->title ?></a>
                            </li>
                            <?php endif; endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>
        </li>
        <?php endif; $i++; endforeach; ?>
    </ul>
</div>
<div class="topbar">
    <div class="page-wrap">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <a href="<?php echo site_url(); ?>" title=""><img class="toplogo" src="<?php echo IMAGES_URI . 'logo.png'; ?>" alt="<?php bloginfo('name'); ?>" /></a>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="header-general-menu">
                    <?php
                    wp_nav_menu(array(
                        'theme_location'  => 'general',
                        'container'       => 'div',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'general table-row',
                        'menu_id'         => '',
                        'echo'            => true,
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div id="nav-icon3" class="hidden-lg hidden-md hidden-sm">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
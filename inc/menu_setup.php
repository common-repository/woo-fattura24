<?php

// better separated voice to check quicker what every single value in add_menu_page is

function woo_fattura24_setup_menu()
{
        $parent_slug = 'woocommerce';
        $page_title  = 'WooCommerce Fattura24 Admin Page';
        $menu_title  = 'Fattura24';
        $capability  = 'manage_woocommerce';
        $menu_slug   = 'woo-fattura24';
        $function    = 'woo_fattura24_setup_page_display';

        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);

}

function page_tabs_fattura24( $current = 'ordine' ) 
{
    $tabs = array(
    'ordine'   => __( 'Order', 'woo-fattura24' ),
        'impostazioni'  => __('Settings', 'woo-fattura24'),

    );

    $html = '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
        $html .= '<a class="nav-tab ' . $class . '" href="?page=woo-fattura24&tab=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
}



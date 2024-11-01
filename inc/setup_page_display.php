<?php


// Don't access this directly, please

if (! defined('ABSPATH') ) {
    exit;
}
 

// check user permission to admin setup values

function woo_fattura24_setup_page_display() 
{
    if (!current_user_can('manage_woocommerce')) {
        wp_die('Unauthorized user');
    }

/**
 *
 * Get the value from menu or from search text field
 *
*/

    //update_post_meta($post->ID, 'title', $title);

    if (isset($_POST['woo_fattura24_order_id']) or isset($_POST['woo_fattura24_search_order_id']) && wp_verify_nonce($_POST['_wpnonce'])) {
        $woo_fattura24_order_id = (int) sanitize_text_field($_POST['woo_fattura24_order_id']);
        $woo_fattura24_search_order_id = (int) sanitize_text_field($_POST['woo_fattura24_search_order_id']);

        if ($woo_fattura24_search_order_id) {

            update_option('woo_fattura24_order_id', $woo_fattura24_search_order_id );

        } else {

            update_option('woo_fattura24_order_id', $woo_fattura24_order_id);

        }



    }

    /**
     *
     * update value API KEY Fattura24
     *
     */





    if (isset($_POST['api_key_fattura24']) && wp_verify_nonce( $_POST['_wpnonce'] )) {
        update_option('api_key_fattura24', sanitize_text_field($_POST['api_key_fattura24']));

    }



    if (isset($_POST['woo-fattura24-anno-fatture']) && wp_verify_nonce($_POST['_wpnonce'])) {
        update_option('woo-fattura24-anno-fatture', sanitize_text_field($_POST['woo-fattura24-anno-fatture']));

    }

    if (isset($_POST['woo-fattura24-anno-ricevute']) && wp_verify_nonce($_POST['_wpnonce'])) {
        update_option('woo-fattura24-anno-ricevute', sanitize_text_field($_POST['woo-fattura24-anno-ricevute']));

    }

    if (isset($_POST['fattura24_auto_save'])) {
        update_option('fattura24_auto_save', sanitize_text_field($_POST['fattura24_auto_save']));
    
    }

    if (isset($_POST['fattura24_paid'])) {
        update_option('fattura24_paid', sanitize_text_field($_POST['fattura24_paid']));

    }


    if (isset($_POST['fattura24_sendemail_choice'])) {
        update_option('fattura24_sendemail_choice', sanitize_text_field($_POST['fattura24_sendemail_choice']));
        $type = 'updated';
        $message = __('Valore Aggiornato', 'woo-fattura24');
        add_settings_error('woo-fattura24', esc_attr('settings_updated'), $message, $type);
        settings_errors('woo-fattura24');

    }

    if (isset($_POST['fattura24_send_choice'])) {
        update_option('fattura24_send_choice', sanitize_text_field($_POST['fattura24_send_choice']));

    }


    if (isset($_POST['delete_autosave_fattura24'])) {
        delete_option('fattura24_autosent_id_fallito');
        $type = 'updated';
        $message = __( 'Segnalazione errore rimossa', 'woo-fattura24' );
        add_settings_error('woo-fattura24', esc_attr('settings_updated'), $message, $type);
        settings_errors('woo-fattura24');

    }

    if (isset($_POST['delete_autosave_success_fattura24'])) {
        delete_option('fattura24_autosent_id_riuscito');
        $type = 'updated';
        $message = __('Segnalazione invio riuscito rimossa', 'woo-fattura24');
        add_settings_error('woo-fattura24', esc_attr('settings_updated'), $message, $type);
        settings_errors('woo-fattura24');

    }

    if (isset($_POST['fattura24_partiva_codfisc'])) {
        update_option('fattura24_partiva_codfisc', sanitize_text_field($_POST['fattura24_partiva_codfisc']));
        $type = 'updated';
        $message = __('Valore Aggiornato', 'woo-fattura24');
        add_settings_error('woo-fattura24', esc_attr('settings_updated'), $message, $type);
        settings_errors('woo-fattura24');

    }

    if (isset($_POST['fattura24_partiva_codfisc_extplugin'])) {
        update_option('fattura24_partiva_codfisc_extplugin', sanitize_text_field($_POST['fattura24_partiva_codfisc_extplugin']));
        $type = 'updated';
        $message = __('Valore Aggiornato', 'woo-fattura24');
        add_settings_error('woo-fattura24', esc_attr('settings_updated'), $message, $type);
        settings_errors('woo-fattura24');

    }

    // include setup form external
    // get values from setup-file.php

    include_once plugin_dir_path(__FILE__) . '../inc/setup-file.php';

}
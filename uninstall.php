<?php
 // If uninstall not called from WordPress exit

if (!defined('WP_UNINSTALL_PLUGIN'))
exit();

// Delete option from options table

delete_option('woo_fattura24_order_id');

delete_option('fattura24_partiva_codfisc');

delete_option('fattura24_auto_save');

delete_option('fattura24_send_choice');

delete_option('fattura24_consumer_key');

delete_option('fattura24_consumer_secret');

delete_option('api_key_fattura24');

delete_option('woo-fattura24-anno-fatture');

delete_option('fattura24_paid');

delete_option('fattura24_sendemail_choice');

delete_option('delete_autosave_fattura24');

delete_option('delete_autosave_success_fattura24');

delete_option('fattura24_partiva_codfisc_extplugin');

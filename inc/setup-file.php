<?php

    // Don't access this directly, please

if (! defined('ABSPATH')) {

    exit;

}
     

?>



<?php
/*
 *
 * Controllo API KEY e mostra messaggio se manca
 *
 */

if (get_option('api_key_fattura24') == null ) {

?>
    <div id="message" class="notice notice-error is-dismissible"> <p><a href="admin.php?page=woo-fattura24&tab=impostazioni">Clicca qui</a> per Verificare che l'API KEY di Fattura24 è stata inserita</p>
</div>
<?php }



/*
 *
 * Controllo mancato invio automatico Fattura al cambio di stato Completato e mostra messaggio di errore
 *
 *
 */

if (get_option('fattura24_autosent_id_fallito')!='') {

?>
    <div id="message" class="notice notice-error">
        <p><b>Invio automatico ordine n <?php echo get_option('fattura24_autosent_id_fallito'); ?>  avvenuto con Errori  <a href="https://wordpress.org/support/plugin/woo-fattura24/">Supporto</a></b>


        <form method="POST">
            <input type="hidden" name="delete_autosave_fattura24" />
            <input type="submit" value="Cancella" class="button button-small ">
        </form>

        </p>
    </div>
<?php

} elseif (get_option('fattura24_autosent_id_riuscito')!='') {

?>
    <div id="message" class="notice notice-success ">
        <p><b>Invio automatico ordine n <?php echo get_option('fattura24_autosent_id_riuscito'); ?>  avvenuto con Successo!</b>


        <form method="POST">
            <input type="hidden" name="delete_autosave_success_fattura24" />
            <input type="submit" value="Cancella" class="button button-small ">
        </form>

        </p>
    </div>
<?php

}


// Code displayed before the tabs (outside)
// Tabs
?>
<div id="top_fattura24"></div>


<h1>
    
<?php 

//echo __( 'WooCommerce Fattura24', 'woo-fattureincloud' );

$plugin_data = get_plugin_data(plugin_dir_path(__FILE__) .'../woo-fattura24.php', true, true);
$plugin_version = $plugin_data['Version'];
    echo __(
        'WooCommerce Fattura24 '
        .$plugin_version, 'woo-fattura24'
    );

?>

</h1>



<?php

$tab = (! empty($_GET['tab']) ) ? esc_attr($_GET['tab']) : 'ordine';
    page_tabs_fattura24($tab);

if ($tab == 'ordine') {

    include_once  'ordine.php';

    // add the code you want to be displayed in the first tab ###

} else {
    // add the code you want to be displayed in the second tab

    include_once plugin_dir_path(__FILE__) . 'impostazioni.php';

}

// Code after the tabs (outside)

?>
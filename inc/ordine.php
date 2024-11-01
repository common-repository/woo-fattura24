<form id="woo-fattura24-preview" action="" method="POST">

<?php

    /**
     * Security form
    */

    wp_nonce_field();

?>

<table border="0" width="800" cellpadding="14" cellspacing="4">

    <tr>

<?php

    /**
     *
     * Select last order ID
     *
    */

function get_last_woofattura24_order_id()
{
    $query = new WC_Order_Query(
        array(
        'limit' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'return' => 'ids',
        )
    );
    $orders = $query->get_orders();

    if (!$orders ) {

        echo "<p>".__('no order', 'woo-fattura24')."</p>";

        return;

    }

    return ($orders[0]);

}




$latest_order_id = get_last_woofattura24_order_id(); // Last order ID


if ($latest_order_id =='') {

?>

    <div id="message" class="notice notice-error is-dismissible">

<?php

echo "<p><b>".__('No WooCommerce Orders!', 'woo-fattura24')."</b></p>";

?>

</div>

    <script>
        jQuery("div#message").appendTo("div#top_fattura24");
    </script>

<?php

    exit;
}


$args = array(
    'post_type' => 'shop_order',
    'posts_per_page' => 10,
    'post_status' => array_keys(wc_get_order_statuses())
);

?>


<td align="center">


<select name="woo_fattura24_order_id">

<?php

$orders = get_posts($args);

if (get_option('woo_fattura24_order_id') == 0) {

?>

<option value="<?php echo $latest_order_id; ?>" selected="selected">

<?php

    echo __('Selected', 'woo-fattura24'); 

?>
: <?php 

    echo $latest_order_id; 

?>

</option>

<?php } else { ?>

    <option value="" selected="selected">

<?php echo __('Selected', 'woo-fattura24'); ?> <?php echo get_option('woo_fattura24_order_id'); ?></option>

<?php }

foreach ($orders as $order) {

?>

<option value="<?php echo $order->ID; ?>">ID ordine : <?php echo $order->ID; ?></option>

<?php

}

?>

</select>


</form>


<select name="woo_fattura24_search_order_id" id="woo_fattura24_orders" >
    
<?php

if (! empty($_POST['search_order'])) {
?>

<option value="<?php echo $_POST['search_order']; ?>" selected="selected">#ordine : <?php echo $_POST['search_order']; ?></option>

<?php
}
?>
<option value="">

<?php echo __('Search Order', 'woo-fattura24'); ?>

</option>
</select>
<script type="text/javascript">
    jQuery( function() {

        if( typeof ajaxurl == 'undefined' ){
            ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        }
        jQuery.fn.select2.defaults.set('language', 'it');
        jQuery("#woo_fattura24_orders").select2({
            language: "it",
            placeholder: "<?php echo __('Search Order', 'woo-fattura24'); ?>",
            // data: [{ id:0, text:"something"}, { id:1, text:"something else"}],
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        action: 'woo_fattura24_search'
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data,
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    });
</script>


<button type="submit" name="submit" value="" class="button button-primary">

<?php echo __('Select', 'woo-fattura24'); ?>

</button>


</td>



</tr>


<td colspan="3" bgcolor="FFFFFF" align="right">

<?php


if (get_option('woo_fattura24_order_id') == 0) {


    $id_ordine_scelto = $latest_order_id;

} else {

    $id_ordine_scelto = get_option('woo_fattura24_order_id');

}


$order = wc_get_order($id_ordine_scelto);

$order_data = $order->get_data(); // The Order data
$order_note = $order->get_customer_note();
$order_shipping_total = $order_data['shipping_total'];
$order_shipping_tax = $order_data['shipping_tax'];
$order_total = $order_data['total'];
$order_total_tax = $order_data['total_tax'];
$fattura24_iva = 22;
$ivaDivisore = 1 + ($fattura24_iva / 100);
$order_total_partial = $order_total / $ivaDivisore;
$order_total_partial = round($order_total_partial, 2);
$totale_iva_fattura24 = $order_total - $order_total_partial;
$totale_esclusaiva = $order_total  - $order_total_tax;
$order_payment_method_title = $order_data['payment_method_title'];
$order_payment_method = $order_data['payment_method'];

// BILLING INFORMATION:

$order_billing_first_name = $order_data['billing']['first_name'];
$order_billing_last_name = $order_data['billing']['last_name'];
$order_billing_company = $order_data['billing']['company'];
$order_billing_address_1 = $order_data['billing']['address_1'];
$order_billing_address_2 = $order_data['billing']['address_2'];
$order_billing_city = $order_data['billing']['city'];
$order_billing_state = $order_data['billing']['state'];
$order_billing_postcode = $order_data['billing']['postcode'];
$order_billing_country = $order_data['billing']['country'];
$order_billing_email = $order_data['billing']['email'];
$order_billing_phone = $order_data['billing']['phone'];
$order_billing_method = $order_data['payment_method_title'];

// SHIPPING INFORMATION:

$order_shipping_first_name = $order_data['shipping']['first_name'];
$order_shipping_last_name = $order_data['shipping']['last_name'];
$order_shipping_company = $order_data['shipping']['company'];
$order_shipping_address_1 = $order_data['shipping']['address_1'];
$order_shipping_address_2 = $order_data['shipping']['address_2'];
$order_shipping_city = $order_data['shipping']['city'];
$order_shipping_state = $order_data['shipping']['state'];
$order_shipping_postcode = $order_data['shipping']['postcode'];
$order_shipping_country = $order_data['shipping']['country'];
$order_billing_payment_method = $order_data['payment_method'];



//print_r($order_data);

//#################################################################################################################    

//#######################################################################################################################
/*   compatibilità col plugin woo-piva-codice-fiscale-e-fattura-pdf-per-italia  */
//#######################################################################################################################



if (get_post_meta($id_ordine_scelto, '_billing_piva', true) || get_post_meta($id_ordine_scelto, '_billing_cf', true) 
    || get_post_meta($id_ordine_scelto, '_billing_pa_code', true) || get_post_meta($id_ordine_scelto, '_billing_pec', true)
) {

    $order_billing_partiva = get_post_meta($id_ordine_scelto, '_billing_piva', true);
    $order_billing_codfis = get_post_meta($id_ordine_scelto, '_billing_cf', true);
    $order_billing_coddest = get_post_meta($id_ordine_scelto, '_billing_pa_code', true);
    $order_billing_emailpec = get_post_meta($id_ordine_scelto, '_billing_pec', true);

    if (empty($order_billing_coddest) && empty($order_billing_emailpec)) {
        $order_billing_coddest = "0000000";

        if ($order_billing_country !== 'IT') {
            $order_billing_emailpec = "";
            $order_billing_coddest = "XXXXXXX";
        }

    }


//########################################################################################################################    
    
} elseif (get_post_meta($id_ordine_scelto, '_billing_partita_iva', true) || get_post_meta($id_ordine_scelto, '_billing_cod_fisc', true)
    || get_post_meta($id_ordine_scelto, '_billing_pec_email', true) || get_post_meta($id_ordine_scelto, '_billing_codice_destinatario', true)
) {
    $order_billing_partiva = get_post_meta($id_ordine_scelto, '_billing_partita_iva', true);
    $order_billing_codfis = get_post_meta($id_ordine_scelto, '_billing_cod_fisc', true);
    $order_billing_emailpec = get_post_meta($id_ordine_scelto, '_billing_pec_email', true);
    $order_billing_coddest = get_post_meta($id_ordine_scelto, '_billing_codice_destinatario', true);

    if (empty($order_billing_coddest) && empty($order_billing_emailpec)) {
        $order_billing_coddest = "0000000";

        if ($order_billing_country !== 'IT') {
            $order_billing_emailpec = "";
            $order_billing_coddest = "XXXXXXX";
        }

    }


} else {

    $order_billing_partiva ="";
    $order_billing_codfis = "";
    $order_billing_emailpec = "";
    $order_billing_coddest = "0000000";

}



//####################################################################################################################




echo "<b>".__('Recipient', 'woo-fattura24')."</b> 

<br><b>".__('Name', 'woo-fattura24')."</b> ".$order_billing_first_name." ".$order_billing_last_name.
"<br><b>".__('Company', 'woo-fattura24')."</b> ".$order_billing_company.
"<br><b>".__('Address', 'woo-fattura24')."</b> ".$order_billing_address_1.
"<br><b>".__('City', 'woo-fattura24')."</b> ".$order_billing_city.
"<br><b>".__('State', 'woo-fattura24')."</b> ".$order_billing_state.
"<br><b>".__('Postal Code', 'woo-fattura24')."</b> ".$order_billing_postcode.
"<br><b>".__('Email', 'woo-fattura24')."</b> ".$order_billing_email.
"<br><b>".__('Email PEC', 'woo-fattura24')."</b> ".$order_billing_emailpec.
"<br><b>".__('Codice Destinatario', 'woo-fattura24')."</b> ".$order_billing_coddest.
"<br><b>".__('Phone number', 'woo-fattura24')."</b> ".$order_billing_phone.
"<br><b>".__('Country', 'woo-fattura24')."</b> ".$order_billing_country.
"<br><b>".__('Partita Iva', 'woo-fattura24')."</b> ".$order_billing_partiva.
"<br><b>".__('Codice Fiscale', 'woo-fattura24')."</b> ".$order_billing_codfis.
"<br><b>".__('Billing Method', 'woo-fattura24')."</b> ".$order_billing_method.
"<br><b>".__('Payment Method code', 'woo-fattura24')."</b> ".$order_billing_payment_method.
"<br><b>".__('Billing Note', 'woo-fattura24')."</b> ".$order_note.



            "</td></tr>

            <tr>
            <td colspan=\"3\" bgcolor=\"FFFFFF\">
                          
             <b>".__('Product list', 'woo-fattura24')."</b><hr>";


// Iterating through each WC_Order_Item_Product objects

foreach ($order->get_items() as $item_key => $item_values) {

    // Using WC_Order_Item methods ##

    // Item ID is directly accessible from the $item_key in the foreach loop or
    $item_id = $item_values->get_id();



    // Using WC_Order_Item_Product methods ##

    $item_name = $item_values->get_name(); // Name of the product
    $item_type = $item_values->get_type(); // Type of the order item ("line_item")
    $product_id = $item_values->get_product_id(); // the Product id
    $wc_product = $item_values->get_product(); // the WC_Product object
    $sku = $wc_product->get_sku();
    $short_description_prdct = $wc_product->get_short_description();
    // Access Order Items data properties (in an array of values) ##
    $item_data = $item_values->get_data();

    $_product = wc_get_product($product_id);

    $product_name = $item_data['name'];
    $product_id = $item_data['product_id'];
    //$product_sku =$sku;
    //$product_short_description = $item_data['short_description'];
    $variation_id = $item_data['variation_id'];
    $quantity = $item_data['quantity'];
    $tax_class = $item_data['tax_class'];
    $line_subtotal = $item_data['subtotal'];
    $line_subtotal_tax = $item_data['subtotal_tax'];
    $line_total = $item_data['total'];
    $line_total_tax = $item_data['total_tax'];
    $prezzo_singolo_prodotto = $line_total/$quantity;
    $prezzo_singolo_prodotto = $prezzo_singolo_prodotto/ $ivaDivisore;
    $prezzo_singolo_prodotto = round($prezzo_singolo_prodotto, 2);
    $item_tax_class = $item_data['tax_class'];

    //$tax_rate = array();
    //$tax_rates = WC_Tax::get_rates( $_product->get_tax_class() );
    $tax_rates = WC_Tax::get_base_tax_rates($_product->get_tax_class(true));




    if (!empty($tax_rates)) {
        $tax_rate = reset($tax_rates);

        echo __('VAT ', 'woo-fattura24'). ' ' . round($tax_rate['rate'], 0)."% <br>".$item_tax_class."<br>";

       

    }
    
    $_product = reset($_product);

    echo"<b>".__('Product Name', 'woo-fattura24')."</b> ". $product_name. "<br>" ;

    if (!$sku) { 
        $sku = "#" ; 
    }

    echo"<b>".__('SKU', 'woo-fattura24')."</b> ". $sku. " <br>" ; 

    $product = wc_get_product($product_id);
    /* echo $order_data['date_created']->date('d/m/Y')."<br>";*/
    echo "<b>".__('Price', 'woo-fattura24')."</b> ".$product->get_price_html()."<br>

			<b>".__('Description', 'woo-fattura24')."</b> ".$short_description_prdct."<br>

            <b>".__('Quantity', 'woo-fattura24')."</b> ".$quantity."<br>".

            "<b>".__('Sub Total', 'woo-fattura24')."</b> €".round($line_total, 2).
    "<hr>";


}
/*
* TAX Shipping
*
*
*/
 // Initializing variables
$tax_items_labels   = array(); // The tax labels by $rate Ids
$shipping_tax_label = '';      // The shipping tax label

// 1. Loop through order tax items
foreach ( $order->get_items('tax') as $tax_item ) {
    // Set the tax labels by rate ID in an array
    $tax_items_labels[$tax_item->get_rate_id()] = $tax_item->get_label();

    // Get the tax label used for shipping (if needed)
    if (! empty($tax_item->get_shipping_tax_total()))
        $shipping_tax_label = $tax_item->get_label();
}

// 2. Loop through order line items and get the tax label
foreach ( $order->get_items() as $item_id => $item ) {
    $taxes = $item->get_taxes();
    // Loop through taxes array to get the right label
    foreach ($taxes['subtotal'] as $rate_id => $tax) {
        $tax_label = $tax_items_labels[$rate_id]; // <== Here the line item tax label
        // Test output line items tax label
        // echo '<pre>Item Id: '.$item_id.' | '; print_r($tax_label); if (strpos($tax_label, 'N1')) { echo 'N1'; } echo '</pre>';
    }
}

// Test output shipping tax label
//echo '<pre>Shipping tax label: '; print_r($shipping_tax_label); echo '</pre>';





echo    "</td>
            </tr>
            <tr>
                <td colspan='3' align='right' bgcolor='FFFFFF'>

            <br><br><b>".__('Order Number', 'woo-fattura24')."</b> ". $id_ordine_scelto.
            "<br><b>".__('Shipping Cost ', 'woo-fattura24')."</b> ". $order_shipping_total.
            "<br><b>".__('Shipping Tax', 'woo-fattura24')." </b>". $shipping_tax_label." = ".$order_shipping_tax.
            "<br><b>".__('Total Tax excluded', 'woo-fattura24')."</b> ".  $totale_esclusaiva.
            "<br><b>".__('Tax', 'woo-fattura24')."</b> ". $order_total_tax.
            "<br><b>".__('Total', 'woo-fattura24')."</b> ". $order_total;
?>

</td>
</tr>
<tr>
    <td colspan='3' align='right'>


<?php

if (get_option('woocommerce_prices_include_tax') == 'no') {



    if ('FE' == get_option('fattura24_send_choice') ) {

        echo " 
                
                <form method=\"POST\">
					<button type=\"submit\" name=\"submit_send_fattura24\" value=\"Seleziona\" class=\"button button-primary\">
					".__('Crea Fattura Elettronica', 'woo-fattura24')."
					</button>
                </form>";

    } elseif ('fattura' == get_option('fattura24_send_choice') ) {

        echo " 
                
                <form method=\"POST\">
					<button type=\"submit\" name=\"submit_send_fattura24\" value=\"Seleziona\" class=\"button button-primary\">
					".__('Create Invoice on Fattura24', 'woo-fattura24')."
					</button>
                </form>";

    } elseif ('ricevuta' == get_option('fattura24_send_choice') ) {

        echo "    
         
			<form method=\"POST\">
					<button type=\"submit\" name=\"submit_ricevuta_fattura24\" value=\"Seleziona\" class=\"button button-primary\">
					".__('Create Receipt on Fattura24', 'woo-fattura24')."
					</button>
                </form>";

    } elseif ('auto' == get_option('fattura24_send_choice') ) {

        echo "    
         
			<form method=\"POST\">
					<button type=\"submit\" name=\"submit_auto_fattura24\" value=\"Seleziona\" class=\"button button-primary\">
					".__('Create Invoice or Receipt on Fattura24', 'woo-fattura24')."
					</button>
                </form>";

    }

    /*
    if (1 == get_option('fattura24_sendemail_choice') ) {

        echo '<br>Invio via email abilitato <img src="'. plugins_url( '../assets/image/email_orange.gif', __FILE__ ) . '" align="middle">';

    }
    */


} elseif (get_option('woocommerce_prices_include_tax') == 'yes') {

?>

    <button type="submit" name="submit_send_fattura24" value="Seleziona" class="button button-primary" disabled>

<?php echo __('Create Invoice on Fattura24', 'woo-fattura24'); ?>

    </button>

    <div id="message" class="notice notice-error">
    
    <p><b>

<?php echo __('To use this plugin you need to set up', 'woo-fattura24'); ?>

    <a href="admin.php?page=wc-settings&tab=tax">

<?php echo __('prices exclusive of tax', 'woo-fattura24'); ?> 

</a> | <a href="https://www.woofattura24.com/documentazione/">

<?php echo __('Here more information', 'woo-fattura24'); ?> 

</a></b> </p>
</div>

    <div id="message2" class="notice notice-error">

    <p><b>


<?php echo __('To use this plugin you need to set up', 'woo-fattura24'); ?>

    <a href="admin.php?page=wc-settings&tab=tax">

<?php echo __('prices exclusive of tax', 'woo-fattura24'); ?> 

    </a> 

| <a href="https://www.woofattura24.com/documentazione/">

<?php echo __('Here more information', 'woo-fattura24'); ?> 


    </a></b></p>
    </div>

    <script>
        jQuery("div#message").appendTo("div#top_fattura24");
    </script>

<?php

}


if (isset($_POST['submit_send_fattura24'])) {

    include plugin_dir_path(__FILE__) . '/send_to_fattura24.php';

} elseif (isset($_POST['submit_ricevuta_fattura24'])) {

    include plugin_dir_path(__FILE__) . '/send_to_fattura24.php';

} elseif (isset($_POST['submit_auto_fattura24'])) {

    include plugin_dir_path(__FILE__) . '/send_to_fattura24.php';

}


?>

</td>
</tr>
</table>
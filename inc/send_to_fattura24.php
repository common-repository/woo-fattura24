<?php

// Don't access this directly, please
if (!defined('ABSPATH')) exit;




$api_uid = get_option('api_uid_fattura24');
$api_key = get_option('api_key_fattura24');


//$lista_articoli = array();


//$aggiungi_shipping = array();

//$spedizione_netta = $order_data['shipping_total'] - ($order_data['shipping_total'] - round(($order_data['shipping_total'] / 122) * 100, 2));
$spedizione_lorda = $order_data['shipping_total'] + $order_data['shipping_tax'] ;
$spedizione_netta = $spedizione_lorda - $order_data['shipping_tax'];

$shipping_rate = $item_data['taxes'];

global $codice_iva;

$codice_iva = '';


foreach ($order->get_items() as $item_key => $item_values):

    $item_data = $item_values->get_data();


    $line_total = $item_data['total'];


    $product_id = $item_values->get_product_id(); // the Product id
    $wc_product = $item_values->get_product(); // the WC_Product object
    // Access Order Items data properties (in an array of values) ##
    $item_data = $item_values->get_data();
    $_product = wc_get_product($product_id);


    //$tax_rates = WC_Tax::get_rates($_product->get_tax_class());
    $tax_rates = WC_Tax::get_base_tax_rates($_product->get_tax_class(true));



    //$tax_rate = reset($tax_rates);




    if (!empty($tax_rates)) {
        $tax_rate = reset($tax_rates);

        if ($tax_rate['rate'] == 22) {

            $codice_iva = 22;

        } elseif ($tax_rate['rate'] == 0) {

            $codice_iva = 0;

        } elseif ($tax_rate['rate'] == 4) {

?>

            <div id="message" class="notice notice-error is-dismissible">

                <p><?php echo __("4% Iva attiva esclusivamente con la ", "woo-fattura24")?> <a href="https://www.woofattura24.com/product/woofattura24-premium-1-year-updates/">
            <b><?php echo __("Versione Premium", "woo-fattura24")?> </a>  <?php //print $description; ?></b></p>
            </div>

            <script>
                jQuery("div#message").appendTo("div#top_fattura24");
            </script>

<?php return;

        } elseif ($tax_rate['rate'] == 10) {

?>

    <div id="message" class="notice notice-error is-dismissible">

            <p><?php echo __("10% Iva attiva esclusivamente con la ", "woo-fattura24")?> <a href="https://www.woofattura24.com/product/woofattura24-premium-1-year-updates/">
                    <b><?php echo __("Versione Premium", "woo-fattura24")?> </a>  <?php //print $description; ?></b></p>
        </div>

        <script>
            jQuery("div#message").appendTo("div#top_fattura24");
        </script>

<?php return;

        } else {

?>
  
    <div id="message" class="notice notice-error is-dismissible">
  
    <p><?php echo __("Questa aliquota non è attiva, controllare se è abilitata nella ", "woo-fattura24")?> <a href="https://www.woofattura24.com/product/woofattura24-premium-1-year-updates/">
    <b><?php echo __("Versione Premium", "woo-fattura24")?> </a>  <?php //print $description; ?></b></p>
    </div>
  
    <script>
        jQuery("div#message").appendTo("div#top_fattura24");
    </script>
  
<?php return;
  
        }

    }

    
           
    $lista_articoli [] = array (

        'Row' => array (
            'Code' => 0000,
            'Description' =>' SKU ' .  $wc_product->get_sku() . '| Nome Prodotto ' . $item_data['name'] . '| Quantità '.$item_data['quantity'] . '| Descrizione ' . $wc_product->get_short_description() ,
            'Qty' => $item_data['quantity'],
            //'Um' => '',
            'Price' => $item_data['subtotal']/$item_data['quantity'],
            'Discounts' => '',
            'VatCode' => $codice_iva,
            'VatDescription' => 'IVA '.$codice_iva.'%'
        )

        + (($item_data['tax_class'] === 'zero-rate-n1')  ? array('FeVatNature' => 'N1') : array())

        + (($item_data['tax_class'] === 'zero-rate-n2')  ? array('FeVatNature' => 'N2') : array())

        + (($item_data['tax_class'] === 'zero-rate-n3')  ? array('FeVatNature' => 'N3') : array())

        + (($item_data['tax_class'] === 'zero-rate-n4')  ? array('FeVatNature' => 'N4') : array())

        + (($item_data['tax_class'] === 'zero-rate-n5')  ? array('FeVatNature' => 'N5') : array())

        + (($item_data['tax_class'] === 'zero-rate-n6')  ? array('FeVatNature' => 'N6') : array())

        + (($item_data['tax_class'] === 'zero-rate-n7')  ? array('FeVatNature' => 'N7') : array())
       
    );


       
 



    //$lista_articoli [] = $articolo_ordine_f24;

    //var_dump($lista_articoli);





    if ($order_data['shipping_total'] > 0) {

        if ($order_data['shipping_tax'] == 0) {

            $cod_shipping_iva = 0;


            
        } else {
    
            $cod_shipping_iva = 22;
    
        }

        $costi_spedizione_f24 = array (

        'Row' => array (

        'Code' => 0000,
        'Description' => "Spese di Spedizione",
        'Qty' => 1,
        'Price' => $spedizione_netta,
        'Discounts' => '',
        'VatCode' => $cod_shipping_iva ,
        'VatDescription' => 'IVA '.$cod_shipping_iva. '%'
        )

        );

        $lista_articoli ['Rows'] =  $costi_spedizione_f24 ;

    }




endforeach;


// print_r($lista_articoli);



if ('fattura' == get_option('fattura24_send_choice') ) {

    $fattura_o_ricevuta =  "I-force";

} elseif ('FE' == get_option('fattura24_send_choice') ) {

        $fattura_o_ricevuta =  "FE";

} elseif ('ricevuta' == get_option('fattura24_send_choice') ) {

    $fattura_o_ricevuta =  "R";

} elseif ('auto' == get_option('fattura24_send_choice') ) {

    $fattura_o_ricevuta =  "I";

}

if (1 == get_option('fattura24_sendemail_choice') ) {

    $sendornot_email = 'true';

} elseif (0 == get_option('fattura24_sendemail_choice') ) {

    $sendornot_email = 'false';
}

if (1 == get_option('fattura24_paid') ) {

    $f24_invoice_paid = 'true';

} elseif (0 == get_option('fattura24_paid') ) {

    $f24_invoice_paid = 'false';

}

if ('paypal' == $order_billing_payment_method) {

    $payment_method_f24 = 'MP08';

} elseif ('stripe' == $order_billing_payment_method) {

    $payment_method_f24 = 'MP08';

} elseif ('bacs' == $order_billing_payment_method) {

    $payment_method_f24 = 'MP05';

} elseif ('cheque' == $order_billing_payment_method) {

    $payment_method_f24 = 'MP02';

} elseif ('cod' == $order_billing_payment_method) {

    $payment_method_f24 = 'MP01';

} else { $payment_method_f24 = 'MP08'; }




$order_array = array (
    'Document' => array (
    'DocumentType' => $fattura_o_ricevuta,
    'CustomerName' => $order_billing_first_name . " " . $order_billing_last_name ,
    'CustomerAddress' => $order_billing_address_1,
    'CustomerPostcode' => $order_billing_postcode,
    'CustomerCity' => $order_billing_city,
    'CustomerProvince' => $order_billing_state,
    'CustomerCountry' => $order_billing_country,
    'CustomerFiscalCode' => $order_billing_codfis,
    'CustomerVatCode' => $order_billing_partiva,
    'CustomerCellPhone' => $order_billing_phone,
    'CustomerEmail' => $order_billing_email,
    'FeCustomerPec' => $order_billing_emailpec,
    'FeDestinationCode' => $order_billing_coddest,
    'FePaymentCode' => $payment_method_f24,
    'DeliveryName' => $order_shipping_first_name . " " . $order_shipping_last_name,
    'DeliveryAddress' => $order_shipping_address_1,
    'DeliveryPostcode' => $order_shipping_postcode,
    'DeliveryCity' => $order_shipping_city,
    'DeliveryProvince' => $order_shipping_state,
    'DeliveryCountry' => $order_shipping_country,
    'Object' => "Ordine WooCommerce numero ".$id_ordine_scelto,
    'TotalWithoutTax' => $totale_esclusaiva,
    'PaymentMethodName' => $order_payment_method_title,
    'PaymentMethodDescription' => $order_payment_method,
    'VatAmount' => $order_total_tax,
    'Total' => $order_total,
    'FootNotes' => $order_note,
    'SendEmail' => $sendornot_email,
    'UpdateStorage' => '1',
    'F24OrderId' => '12345',
    'IdTemplate' => '123',
    'CustomField1' => '',
    'CustomField2' => '',

    'Payments' => array (

        'Payment' => array (

            'Date' => $order_data['date_created']->date('Y-m-d'),
            'Amount' => $order_total,
            'Paid' => $f24_invoice_paid,

        ),
    ),

    'Rows' => $lista_articoli

),


);

require plugin_dir_path(__FILE__) . '/create_doc_remote.php';

//echo "<pre>". htmlentities(arrayToXmlFattura24($order_array)) . "</pre>";
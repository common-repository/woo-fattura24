<?php


/*
 *
 * valori nei campi del checkout
 *
*/

function billing_fields_woofattura24( $fields_fattura24 ) 
{

    global $woocommerce;
    $country = $woocommerce->customer->get_billing_country();

    if ($country !== 'IT') {
    
        $initaliasi = false;
    
    } else {

        $initaliasi = true;
        
    }



    $fields_fattura24['billing_cod_fisc'] = array(
        'label'       => __('Codice Fiscale', 'woocommerce'),
        'placeholder' => __('Scrivere il Codice Fiscale', 'woocommerce'),
        'required'    => $initaliasi,
        'class'       => array('piva-number-class form-row-wide' ),
        'clear'       => true

    );

    $fields_fattura24['billing_partita_iva'] = array(
        'label'       => __('Partita Iva', 'woocommerce'),
        'placeholder' => __('Scrivere il numero di Partita Iva', 'woocommerce'),
        'required'    => false,
        'clear'       => true,
        'class'       => array( 'form-row' ),

    );

    $fields_fattura24['billing_pec_email'] = array(
        'label'       => __('Email Pec', 'woocommerce'),
        'placeholder' => __('Per la Fattura Elettronica', 'woocommerce'),
        'required'    => false,
        'type'        => 'text',
        'class'       => array('form-row-wide'),
        'clear'       => true,

    );

    $fields_fattura24['billing_codice_destinatario'] = array(
        'label'       => __('Codice Destinatario', 'woocommerce'),
        'placeholder' => __('Per la Fattura Elettronica', 'woocommerce'),
        'required'    => false,
        'type'        => 'text',
        'class'       => array('form-row-wide'),
        'clear'       => true,

    );



    return $fields_fattura24;
}

/*
 *
 * valori modificabili dell'ordine
 *
 * */



function admin_billing_field_woofattura24( $fields_fattura24 ) 
{

    $fields_fattura24['cod_fisc'] = array(
        'label' => __('Codice Fiscale', 'woocommerce'),
        'wrapper_class' => 'form-field-wide',
        'show' => true,

    );
    $fields_fattura24['partita_iva'] = array(
        'label' => __('Partita Iva', 'woocommerce'),
        'wrapper_class' => 'form-field-wide',
        'show' => true,

    );

    $fields_fattura24['pec_email'] = array(
        'label' => __('Email Pec', 'woocommerce'),
        'wrapper_class' => 'form-field-wide',
        'show' => true,
    );
    
    $fields_fattura24['codice_destinatario'] = array(
        'label' => __('Codice Destinatario', 'woocommerce'),
        'wrapper_class' => 'form-field-wide',
        'show' => true,
    );

    return $fields_fattura24;
}
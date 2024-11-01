<h2>

<?php

echo __('Enter the KEY API of Fattura24.com located in', 'woo-fattura24');

?> 
            <i><span>CONFIGURAZIONE ->> App e Servizi Esterni ->> WooCommerce</span></i>
</h2>

<hr>

<?php

    /**
     * Form for API Value API UID and API KEY
     */
?>

<table border="0" style="max-width:800px;" cellpadding="12" cellspacing="16">
    <tr>
        <td colspan="3" >

    <!-- <form id="woo-fattura24-settings_key" method="POST"> -->

        <form method="POST">

        <?php wp_nonce_field(); ?>

         <label for="woo_fattura24_apikey">API KEY

            <?php

            require plugin_dir_path(__FILE__) . '/api_test.php';

            ?>

        </label>

        <input type="password" class="f24_pwd_textbox" 
        name="api_key_fattura24" placeholder="api key"
        value="<?php echo get_option('api_key_fattura24'); ?>">

  


        </td>
  
    
    
    </tr>


    <!-- ########################################### -->


    <tr>
        <td width="33.3333%" bgcolor="white">

        <span class="dashicons dashicons-format-aside"></span>
        <br>

            <!-- <form method="POST"> -->
                <label for="fattura24_auto_save">
                    <?php echo __( 'Enable the manual creation of', 'woo-fattura24' );?>
                </label>

<br>

<!-- ########################################### -->

                <input type="radio" id="fattura"
                name="fattura24_send_choice" value="fattura"

<?php

if ('fattura' == get_option('fattura24_send_choice')) {
    echo 'checked';
} else { 
    echo ''; 
}
?>>
                <label for="contactChoice0">
                <?php echo __('INVOICE', 'woo-fattura24'); ?></label>

                <br>

<!-- ########################################### -->

                <input type="radio" id="FE"
                name="fattura24_send_choice" value="FE"

<?php

if ('FE' == get_option('fattura24_send_choice')) {
    echo 'checked';
} else { 
    echo ''; 
}
?>>
                <label for="contactChoice1">
                <?php echo __('Fattura Elettronica', 'woo-fattura24'); ?></label>

                <br>


<!-- ########################################### -->

                <input type="radio" id="ricevuta" 
                name="fattura24_send_choice" value="ricevuta"

<?php

if ('ricevuta' == get_option('fattura24_send_choice') ) {
    echo 'checked';
} else { 
    echo ''; 
}
?>>
                <label for="contactChoice2">
                <?php echo __('RECEIPT', 'woo-fattura24'); ?></label>

                <br>


<!-- ########################################### -->


                <input type="radio" id="auto" 
                name="fattura24_send_choice" value="auto"

<?php

if ('auto' == get_option('fattura24_send_choice') ) {
    echo 'checked';
} else { 
    echo ''; 
}
?>>
                <label for="contactChoice3">
                <?php echo __('AUTO (if VAT is submitted, Invoice)', 'woo-fattura24'); ?></label>


            </td>
    
    

    <!-- #################### -->

   
        

<td width="33.3333%" bgcolor="white">

<span class="dashicons dashicons-cart"></span>
<br>

                <label for="fattura24_paid"><?php echo __( 'Enable the creation of a <b>paid invoice</ b>', 'woo-fattura24' );?></label>

<input type="hidden" name="fattura24_paid" value="0" />
<input type="checkbox" name="fattura24_paid" id="fattura24_paid" value="1" 

<?php 

if (1 == get_option('fattura24_paid')) {
        echo 'checked';
} else {
    echo '';
}

?>>

</td>


   
<!-- #################################### -->
  
        <td width="33.3333%" bgcolor="white">

        <span class="dashicons dashicons-update"></span>

            <!-- <form method="POST"> -->
                <label for="fattura24_auto_save"><?php echo __( 'Enable creation in <b> Automatic </b> when the order is <b> Completed </b>', 'woo-fattura24' );?></label>

                <input type="hidden" name="fattura24_auto_save" value="0" />
                <input type="checkbox" 
                name="fattura24_auto_save" id="fattura24_auto_save" value="1" 
<?php 
if (1 == get_option('fattura24_auto_save') ) {
    echo 'checked';
} else {
    echo '';
}
?>>


        </td>
    </tr>


    <!-- #################### -->







    <tr>
        <td width="33.3333%" bgcolor="white">

        <span class="dashicons dashicons-list-view"></span>

<!-- <form method="POST"> -->
<label for="fattura24_partiva_codfisc">
    <?php
    echo __('Activate the item VAT and Tax Code in the Checkout of WooFattura24', 'woo-fattura24');
    ?></label>

    <input type="hidden" name="fattura24_partiva_codfisc" value="0" />
    <input type="checkbox" name="fattura24_partiva_codfisc" 
    id="fattura24_partiva_codfisc" value="1" 
<?php
if (1 == get_option('fattura24_partiva_codfisc') ) {
    echo 'checked';
} else {
    echo '';
}

?>>
 

        </td>
    <td width="33.3333%" bgcolor="white" >

    <span class="dashicons dashicons-chart-line"></span>
    <br>
    
    <a href="#sotto">Aliquote Iva = 0% </a>


    </td>

    <td width="33.3333%" bgcolor="white" >

    <span class="dashicons dashicons-cart"></span>
    <br>
    <a href="#sotto">I codici del tipo di pagamento</a>

    </td>
    
    
    </tr>

<tr>
    <td colspan="3" align="right">

    <input type="submit" value="<?php echo __('Save Settings', 'woo-fattura24' ) ; ?> " class="button button-primary button-large"
    onclick="window.location='admin.php?page=woo-fattura24&tab=impostazioni#setting-error-settings_updated';">
        </form>

    </td>

</tr>


</table>

<a name="sotto"></a>

<table border="0" style="max-width:800px;" cellpadding="12" cellspacing="16">


        <tr>
        <td valign="top">
        Si consiglia di <b>verificare con molta attenzione</b> <br>che i dati per la Fattura Elettronica inviati a Fatura24 siano corretti, <br>
        Gli autori del plugin declinano ogni responsabilità<br> per eventuali errori o mancanze nella generazione della Fattura Elettronica<br><br>

        <span class="dashicons dashicons-cart" style="font-size:60px;width:60px;height:60px"></span>
        <br>
        I codici del tipo di pagamento (prelevati dallo 
        <a href="https://www.agenziaentrate.gov.it/wps/file/Nsilib/Nsi/Schede/Comunicazioni/Fatture+e+corrispettivi/Fatture+e+corrispettivi+ST/ST+invio+di+fatturazione+elettronica/ST+Fatturazione+elettronica+-+Allegato+A/Allegato+A+-+Specifiche+tecniche+vers+1.3.pdf"> SDI</a>) con cui viene pre-compilata<br>
         la Fattura Elettronica sono :<br>
        <i>
         <ol>
            <li>    <b>MP08</b> carta di pagamento (carta di credito e PayPal)</li>
            <li>    <b>MP02</b> assegno bancario</li>
            <li>    <b>MP05</b> bonifico bancario</li>
            <li>    <b>MP01</b> contanti (pagamento in contrassegno)</li>
        </ol>    
        </i>
         se è stato utilizzato un <b>altro tipo di pagamento</b><br> è necessario modificarlo <b>direttamente su Fattura24</b> 

        </td>
        <td>
        <span class="dashicons dashicons-chart-line" style="font-size:60px;width:60px;height:60px"></span>
        <br>    
        
        Per le Aliquote <b>Iva = 0%</b> della Fattura Elettronica si utilizzano i codici<br> definiti 
        <a href="https://www.agenziaentrate.gov.it/wps/file/Nsilib/Nsi/Schede/Comunicazioni/Fatture+e+corrispettivi/Fatture+e+corrispettivi+ST/ST+invio+di+fatturazione+elettronica/ST+Fatturazione+elettronica+-+Allegato+A/Allegato+A+-+Specifiche+tecniche+vers+1.3.pdf"> dall'Agenzia delle Entrate</a><br>
         andare su <br><i> "WooCommerce > Impostazioni > Imposta > Aliquote addizionali" </i><br>
         <div id="zero_rate_settings"></div>
            e impostarle come segue:
        <ol>

            <li><b>Zero Rate N1</b> = escluse ex art.15</li>
            <li><b>Zero Rate N2</b> = non soggette (per es. regime forfettario)</li>
            <li><b>Zero Rate N3</b> = non imponibili</li>
            <li><b>Zero Rate N4</b> = esenti</li>
            <li><b>Zero Rate N5</b> = regime del margine / IVA non esposta in fattura</li>
            <li><b>Zero Rate N6</b> = inversione contabile (per le operazioni in reverse charge<br>
             ovvero nei casi di autofatturazione per acquisti extra UE di servizi<br>
              ovvero per importazioni di beni nei soli casi previsti)</li>
            <li><b>Zero Rate N7</b> = IVA assolta in altro stato UE <br>
            (vendite a distanza ex art. 40 commi 3 e 4 e art. 41 comma 1 <br>
            lett. b, DL 331/93; prestazione di servizi di telecomunicazioni,<br>
            tele-radiodiffusione ed elettronici ex art. 7-sexies lett.<br>
             f, g, DPR 633/72 e art. 74-sexies, DPR 633/72)</li>
        
        </ol>   

        </td>

    </tr>


    <!-- #################### -->


</table>
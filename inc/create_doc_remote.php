<?php

// Don't access this directly, please
if (!defined('ABSPATH')) exit;


/*
** azione: test, fattura, nc, ricevuta
** Restituisce xml
** vedi: https://www.fattura24.com/api-documentazione/
*/
function crea_woo_fattura24($xml) 
{


    $efatt_api_key = get_option('api_key_fattura24');

        // Create map with request parameters

    $azione = '/SaveDocument';

    $params = array ('apiKey' => $efatt_api_key, 'xml' => $xml);

    $api_url = 'https://www.app.fattura24.com/api/v0.3';

    // Build Http query using params
    $query = http_build_query($params);
    // Create Http context details
    $contextData = array (
        'method' => 'POST',
        'header' => "Connection: close\r\n".
            "Content-Type: application/x-www-form-urlencoded\r\n".
            "Content-Length: ".strlen($query)."\r\n".
            "User-Agent:WooF24Agent/1.0\r\n",
        'content'=> $query );

    // Create context resource for our request
    $context = stream_context_create(array ( 'http' => $contextData ));

    // Read page rendered as result of your POST request
    $fattura24_result =  file_get_contents(
        $api_url.$azione,  // page url
        false,
        $context
    );

    // Server response is now stored in $fattura24_result variable so you can process it
    $fattura24_result = simplexml_load_string(str_replace('&egrave;', 'è', $fattura24_result));

    $returnCode = intval($fattura24_result->returnCode);

    //print $returnCode."<br>";

    global $description;

    $description = strval($fattura24_result->description);

    $doc_id = intval($fattura24_result->docId);



    if (strpos($description, 'Operation completed') !== false) {

?>


        <div id="message" class="notice notice-success is-dismissible">

            <p><b>
<?php   echo __('Creazione su Fattura24 completata con successo!', 'woo-fattura24');

        echo '<a href="https://www.app.fattura24.com/api/v0.3/GetFile?apiKey='.$efatt_api_key.'&docId='.$doc_id.'">
					 '. __("Scarica il PDF", "woo-fattura24")  .' <img src="'  . plugins_url('../assets/image/pdf_ico.gif', __FILE__) . '" align="middle"></a>';
?>
                </b>

            </p>
        </div>

        <script>
            jQuery("div#message").appendTo("div#top_fattura24");
        </script>

<?php

    } else {

?>

        <div id="message" class="notice notice-error is-dismissible">

            <p><?php echo __("Creazione non avvenuta","woo-fattura24")?> <a href="admin.php?page=woo-fattura24&tab=impostazioni">
                    <?php echo __("verificare API Key","woo-fattura24")?> </a>,  <?php #print $description; ?></b></p>
        </div>

        <script>
            jQuery("div#message").appendTo("div#top_fattura24");
        </script>

<?php

    }




    //print $description."<br>";

    $doc_id = intval($fattura24_result->docId);

    //print $doc_id."<br>";

    $doc_number = intval($fattura24_result->docNumber);

    //print $doc_number;


}



#####################################################################*/

function arrayToXmlFattura24($array, $rootElement = null, $xml = null) 
{
    $_xml = $xml;

    if ($_xml === null) {
        $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<?xml version="1.0" encoding="UTF-8"?><Fattura24/>');

    }

    foreach ($array as $k => $v) {

        // no numeric keys in xml

        if (is_numeric($k) ) {

            $k = $rootElement;
        }


        if (is_array($v)) { //nested array
            arrayToXmlFattura24($v, $k, $_xml->addChild($k));
        } else {
            $_xml->addChild($k, $v);
        }
    }

    return $_xml->asXML();
}

//echo "<pre>". htmlentities (arrayToXmlFattura24($order_array));

$xmlstring = arrayToXmlFattura24($order_array);

$xw = xmlwriter_open_memory();
//xmlwriter_start_document($xw, '1.0', 'UTF-8');
xmlwriter_text($xw, $xmlstring);

$xml = xmlwriter_output_memory($xw);
/* INVIO DATI */

$xml_res = crea_woo_fattura24($xml);

/* LEGGO I DATI RICEVUTI DA FATTURA24 */
$xml = simplexml_load_string($xml_res);

//print_r($xml);
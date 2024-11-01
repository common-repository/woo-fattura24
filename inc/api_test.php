<?php


// Don't access this directly, please
if (!defined('ABSPATH')) exit;


/*
** azione: test, fattura, nc, ricevuta
** Restituisce xml
** vedi: https://www.fattura24.com/api-documentazione/
*/
function test_api_fattura24($xml) 
{

    $efatt_api_key = get_option('api_key_fattura24');

    // Create map with request parameters

    $azione = '/TestKey';
    $params = array ('apiKey' => $efatt_api_key);

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
    $result =  file_get_contents(
        $api_url.$azione,  // page url
        false,
        $context
    );

    // Server response is now stored in $result variable so you can process it
    $result = simplexml_load_string(str_replace('&egrave;', 'è', $result));

    $returnCode = intval($result->returnCode);

    // print $returnCode."<br>";

    $description = strval($result->description);


    if (strpos($description, 'authorized') !== false) {

?>

    <div id="message" class="notice notice-error is-dismissible">

        <p><b>

<?php echo __('WRONG API KEY', 'woo-fattura24'); 
?>

        </b></p>
    </div>

        <script>
            jQuery("div#message").appendTo("div#top_fattura24");
        </script>

<?php
    } elseif (strpos($description, 'corretta') !== false) {

?>

    <div id="message" class="notice notice-success is-dismissible">

            <p><b>

<?php echo __('API KEY OK', 'woo-fattura24'); 
?>

        </b></p>
        </div>

        <script>
            jQuery("div#message").appendTo("div#top_fattura24");
        </script>

<?php
    }


    //print $description."<br>";

    $doc_id = intval($result->docId);

    //print $doc_id."<br>";

    $doc_number = intval($result->docNumber);

    //print $doc_number;


}



/*
** CREAZIONE DATI FATTURA IN XML
*/




$order_array = array (
    'Document' => array (
    'DocumentType' => 'I',
    'CustomerName' => 'Mario Rossi',
    'CustomerAddress' => 'Via Alberti 9',
    'CustomerPostcode' => '06122',
    'CustomerCity' => 'Perugia',
    'CustomerProvince' => 'PG',
    'CustomerCountry' => 'Italy',
    'CustomerFiscalCode' => 'MARROS66C44G217W',
    'CustomerVatCode' => '03912377542',
    'CustomerCellPhone' => '335123456789',
    'CustomerEmail' => 'info@rossi.it',
    'DeliveryName' => 'Mario Rossi',
    'DeliveryAddress' => 'Via Alberti 9',
    'DeliveryPostcode' => '06122',
    'DeliveryCity' => 'Perugia',
    'DeliveryProvince' => 'PG',
    'DeliveryCountry' => '',
    'Object' => 'Oggetto del documento',
    'TotalWithoutTax' => '900.00',
    'PaymentMethodName' => 'Banca Popolare di.....',
    'PaymentMethodDescription' => 'IBAN: IT02L1234512345123456789012',
    'VatAmount' => '198.00',
    'Total' => '1098.00',
    'FootNotes' => 'Vi ringraziamo per la preferenza accordataci',
    'SendEmail' => 'true',
    'UpdateStorage' => '1',
    'F24OrderId' => '12345',
    'IdTemplate' => '123',
    'CustomField1' => '',
    'CustomField2' => '',


    'Payments' => array (

        'Payment' => array (

            'Date' => '2016-02-23',
            'Amount' => '2135',
            'Paid' => 'true',

        ),
    ),

    'Rows' => array (

        'Row' => array (

            'Code' => 0001,
            'Description' => 'Pulizia',
            'Qty' => 2,
            'Um' => '',
            'Price' => 200.00,
            'Discounts' => '',
            'VatCode' => 22,
            'VatDescription' => 'IVA 22%'
        )
    )

),

);



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

/* TEST */
$xml_res = test_api_fattura24($xml);


/* LEGGO I DATI RICEVUTI DA FATTURA24 */
$xml = simplexml_load_string($xml_res);

print_r($xml_res);
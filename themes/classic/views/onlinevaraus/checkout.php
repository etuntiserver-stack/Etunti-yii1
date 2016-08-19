<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

require 'CheckoutFinland/Payment.php';
require 'CheckoutFinland/Client.php';

use CheckoutFinland\Payment;
use CheckoutFinland\Client;

$asetukset = Asetukset::model()->findbypk(1);
if(isset($asetukset->checkout_id) and !empty($asetukset->checkout_id) and !empty($asetukset->checkout_salasana))
{
$demo_merchant_id       = $asetukset->checkout_id;
$demo_merchant_secret   = $asetukset->checkout_salasana;


$return_url             = 'http://' .$_SERVER['SERVER_NAME'] .str_replace('maksu', 'maksettu', $_SERVER['REQUEST_URI']);

$payment = new  Payment($demo_merchant_id, $demo_merchant_secret);
$payment->setUrls($return_url);

$tv = Tyovuoroot::model()->findbypk($_SESSION['onlinevaraus']['modelTV']);


if(isset($tv->id))
{

$versio = '0001';
$stamp = time();
$amount = $amount; //
$reference = $_SESSION['onlinevaraus']['onlinevarausID'];
$message = 'Työvuoro '.$tv->pvm.', '.$tv->alku.' - '.$tv->loppu;
$deliveryDate = new \DateTime(date("Y-m-d"));
$firstName = $etu_suku_nimet;
$familyName = '';
$address = $osoite;
$postOffice = $kaupunki;
$postcode = $postinumero;
$country = 'FIN';
$language = 'EN';


$payment_data = [
    'versio'        => $versio,   
    'stamp'         => $stamp,
    'amount'        => $amount, 
    'reference'     => $reference,
    'message'       => $message,            // some short description about the order
    'deliveryDate'  => $deliveryDate, // approximated delivery date, this is shown to customer service in Checkout Finland but not to the buyer
    'firstName'     => $firstName,
    'familyName'    => $familyName,
    'address'       => $address,
    'postOffice'    => $postOffice,
    'postcode'      => $postcode,
    'country'       => $country,                       // country affects what payment options are shown FIN = all, others = credit cards
    'language'      => $language
];



$payment->setData($payment_data);
$client = new Client();
$response = $client->sendPayment($payment);

if($response)
{
    $xml = @simplexml_load_string($response); // use @ to suppress warnings, checkout finland responds with an error string instead of xml if something went wrong

    if($xml and isset($xml->id)) {
        // now we have a proper response xml and can show payment options to customer

        // here you can pass the xml to your view for rendering or something else
        // we just render the payment options a bit further down this file

	//print_r($xml);


	if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
	{
		$ov = Onlinevaraus::model()->findbypk($_SESSION['onlinevaraus']['onlinevarausID']);
		$ov->tv_id = $_SESSION['onlinevaraus']['modelTV'];
		$ov->maksun_onnistu_koodi = $xml->delayedMAC;
		$ov->kesto = $kesto;
		$ov->hinta = $amount;
		$ov->tilauksen_kuvaus = json_encode($_SESSION['onlinevaraus']['tilauksenKuvaus']);
		$ov->save();

	}

             
    } else  { 
        // something went wrong, check merchant id and secret and after that every other parameter
        // do some error handling
        var_dump($response);
    }
} 
else {
    // no response at all, maybe the server is down, do some error handling
} 
?>



    <style>
        .C1 {
             width: 180px;
             height: 130px;
             //border: 1pt solid #a0a0a0;
             display: block;
             float: left;
             margin: 7px;
             //-moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;
             clear: none;
             padding: 0;
            }
        .C1:hover {
             background-color: #f0f0f0;
             border-color: black;
            }
        .C1 form {
             width: 180px;height: 120px;
            }
        .C1 form span {
             display:table-cell; vertical-align:middle;
             height: 92px;
             width: 180px;
            }
        .C1 form span input {
             margin-left: auto;
             margin-right: auto;
             display: block;
             border: 1pt solid #f2f2f2;
             -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px;
             padding: 5px;
             background-color: white;
            }
        .C1:hover form span input {
             border: 1pt solid black;
            }
        .C1 div {
             text-align: center;
             font-family: arial;
             font-size: 11pt;
            }
    </style>



        <?php 
        if($xml and isset($xml->id))
        {
            $html = '';

            foreach($xml->payments->payment->banks as $bankX) 
            {
                foreach($bankX as $bank) 
                {
                    $html .= "<div class='C1'>
			     <form action='{$bank['url']}' method='post'><p>\n";
                    foreach($bank as $key => $value) 
                    {
                        $html .= "<input type='hidden' name='$key' value='$value' />\n";
                    }
                    $html .= "<span><input type='image' src='{$bank['icon']}' /></span>
				<div><p>{$bank['name']}</p></div>
			     </form></div>\n";
                }
            }
        }

	//$html .= $return_url;

        echo "<div>$html</div>";
        ?>

    <br><br>
<div class="row"></div>
<p>
    <div class="small">
        <p></p>
    </div>
</p>



<?php


} // if tv->id



} else {
echo 'Checkout tunnukset puuttuu!';
}


/*

Nordea
  Tunnus: 123456
  Salasana: 1234

Osuuspankki
  Käyttäjätunnus: 123456
  Salasana: 7890
  Avainluku: Mikä vaan

Danske Bank
  Testattaessa tulee käyttää omia asiakastunnuksia. Tehtyjä maksuja ei veloiteta tililtä.

Ålandsbanken
  Käyttäjätunnus: 12345678
  Tunnusluku: 1234

Handelsbanken
  Käyttäjätunnus: Valmiina
  Tunnusluku: Valmiina.

Aktia
  Käyttäjätunnus: 12345678
  Salasana: 123456
  Avainluku: 1234

POP Pankki
  Käyttäjätunnus: 12345678
  Salasana: 123456
  Avainluku: 1234

Säästöpankki
  Käyttäjätunnus: 11111111
  Salasana: 123456
  Avainluku: 123456

S-Pankki
  Käyttäjätunnus: 12345678
  Salasana: 9999
  Avainluku: 1234

Visa
  Korttinumero: 4925000000000004
  Voimassaolo: > tämä päivä
  CVC: mitkä tahansa kolme numeroa
  Onnistuneen maksun testaamiseksi testikortilla tulee käyttää maksun summana vähintään kahta euroa (2.00 €).
  Jos summa on välillä 0.01-1.99 euroa, maksua ei hyväksytä, eli voit testata virhetilanteita.
  Oikeilla kauppiastunnuksilla ja korteilla tälläisiä rajoituksia ei ole.

MasterCard
  Korttinumero: 5413000000000000
  Voimassaolo: > tämä päivä
  CVC: mitkä tahansa kolme numeroa
  Onnistuneen maksun testaamiseksi testikortilla tulee käyttää maksun summana vähintään kahta euroa (2.00 €).
  Jos summa on välillä 0.01-1.99 euroa, maksua ei hyväksytä, eli voit testata virhetilanteita.
  Oikeilla kauppiastunnuksilla ja korteilla tälläisiä rajoituksia ei ole.
*/
?>


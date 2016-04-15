<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

require 'CheckoutFinland/Response.php';

use CheckoutFinland\Response;

$demo_merchant_secret   = "SAIPPUAKAUPPIAS";

$response = new Response($demo_merchant_secret);

$response->setRequestParams($_GET);

$status_string = '';

try {
    if($response->validate()) {
        // we have a valid response, now check the status

        // the status codes are listed in the api documentation of Checkout Finland
        switch($response->getStatus())
        {
            case '2':
            case '5':
            case '6':
            case '8':
            case '9':
            case '10':
                // These are paid and we can ship the product
                $status_string = 'PAID';
                break;
            case '7':
            case '3':
            case '4':
                // Payment delayed or it is not known yet if the payment was completed 
                 $status_string = 'DELAYED';
                break;
            case '-1':
                 $status_string = 'CANCELLED BY USER';
                 break;
            case '-2':
            case '-3':
            case '-4':
            case '-10':
                // Cancelled by banks, Checkout Finland, time out e.g. 
                 $status_string = 'CANCELLED';
                break;
        }

    } else {
        // something went wrong with the validation, perhaps the user changed the return parameters
    }
} catch(MacMismatchException $ex) {
    echo 'Mac mismatch';
} catch(UnsupportedAlgorithmException $ex) {
    echo 'Unsupported algorithm';
}
?>





        <!-- Header-->
        <header>
            <!-- Container-->
            <div class="container">
                <!-- Row-->
                <div class="row">
                    <!-- Logo-->
                    <div class="col-md-3">
                        <div class="logo">
  			<?php $asetukset=Asetukset::model()->find("id=1"); ?>
  			<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
                        </div>
                    </div>
                    <!-- End Logo-->

                    <!-- Nav-->
                    <div class="col-md-9 slogan">
                        <!--Voita siivousalan haasteet-->
                    </div>
                    <!-- End Nav-->
                </div>
                <!-- End Row-->
            </div>
            <!-- End Container-->
        </header>
        <!-- End Header-->




	<?php
	if($status_string == 'PAID')
	{
		$ov = Onlinevaraus::model()->find(" tv_id='".$_GET['REFERENCE']."' and tila=0 ");
		$tv = Tyovuoroot::model()->findbypk($_GET['REFERENCE']);
		if(!isset($tv->id))
		{
        		echo '<h2>Tilaus vanhentunut!</h2>';		
		}

		if(isset($ov->id) and isset($tv->id))
		{

			$asiakas = Asiakkaat::model()->findbypk($ov->asiakas_id);
			$kohteet = Kohteet::model()->findbypk($ov->kohde_id);

			$asetukset = Asetukset::model()->findbypk(1);


$nimi = '';
if(!empty($asiakas->yrityksen_nimi) and empty($asiakas->yhteyshenkilo))
$nimi = $asiakas->yrityksen_nimi;
if(empty($asiakas->yrityksen_nimi) and !empty($asiakas->yhteyshenkilo))
$nimi = $asiakas->yhteyshenkilo;

$tilauksen_kuvaus = json_decode($ov->tilauksen_kuvaus, true);


$message = '';
$message .= '

        <section class="esittely">
            <div class="paddings">
                <div class="container">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->


<style>
table{ 
	width:800px;
}
td{
	line-height: 170%;
	width: 400px;
}
</style>


<p><h1 class="title-subtitle text-left"><span>Kiitos tilauksestasi!</span></h1></p>

<p><h4 class="title-subtitle text-left">
Olemme vastaanottaneet tilauksesi ja tästä voit tulostaa tilausvahvistuksen. 
</h4></p>


<table>
<tr><td>Nimi</td><td>'.$nimi.'</td></tr>
<tr><td>Osoite</td><td>'.$asiakas->osoite.'</td></tr>
<tr><td>Puhelin</td><td>'.$asiakas->puhelin.'</td></tr>
<tr><td>S-posti</td><td>'.$asiakas->sahkoposti.'</td></tr>';

if(!empty($asiakas->y_tunnus))
$message .= '<tr><td>Y-tunnus</td><td>'.$asiakas->y_tunnus.'</td></tr>';

$message .= '
</table>
<hr>
<table>
<tr><td>Tilausnumero</td><td>'.$ov->id.'</td></tr>

<tr><td valign="top">Tilattu tuote</td><td>';

if(isset($tilauksen_kuvaus['paa']) and isset($tilauksen_kuvaus['lisa']))
{
  foreach($tilauksen_kuvaus['paa'] as $k=>$v)
	$message .=  $k.' '.$v.' m²<br>';
  foreach($tilauksen_kuvaus['lisa'] as $k=>$v)
	$message .=  $k.' '.$v.' h<br>';
}

$message .= '<br></td></tr>

<tr><td>Ajankohta</td><td>'.$tv->pvm.'</td></tr>
<tr><td>Aika</td><td>KLO '.$tv->alku.'-'.$tv->loppu.'</td></tr>				
<tr><td>Paikka</td><td>'.$kohteet->osoite.', '.$kohteet->pnumero.' '.$kohteet->kaupunki.'</td></tr>
<tr><td>Hinta</td><td>'.number_format($ov->hinta, 2, ',', '').' euroa</td></tr>
<tr><td>Maksu</td><td>Maksu on vahvistettu</td></tr>
</table>

			<span>'.$asetukset->tilausvahvistus.'</span>





                        <hr>
                    <!-- End Titles Heading -->

                </div>
                <!-- End Container-->
            </div>
        </section>  


			';


			echo $message;

	          	$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($_SESSION['onlinevaraus']['sahkoposti']);
			$mail->setSubject('Online varaus');
			$mail->setBody($message);

			if($mail->send())
			{
				//Tyovuoroot::model()->updatebypk($_GET['REFERENCE'], array('osoiteOnline'=>2));
				//Onlinevaraus::model()->updatebypk($ov->id, array('tila'=>1));
				unset($_SESSION['onlinevaraus']);
			}

		}
	}
	?>




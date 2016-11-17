<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
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
	if(isset($_GET['check']))
	{
		if(isset($_SESSION['onlinevaraus']))
		{
			echo $_SESSION['onlinevaraus']['message'];
			unset($_SESSION['onlinevaraus']);
		}
		exit;
	}






require 'CheckoutFinland/Response.php';

use CheckoutFinland\Response;

$asetukset = Asetukset::model()->findbypk(1);
if(!empty($asetukset->checkout_salasana))
$demo_merchant_secret = $asetukset->checkout_salasana;
else
echo 'merchant_secret error';

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



	if($status_string == 'PAID' and !isset($_GET['check']))
	{
		$ov = Onlinevaraus::model()->find(" id='".$_GET['REFERENCE']."' and tila=0 ");

		if(isset($ov->id))
		$tv = Tyovuoroot::model()->find(" onlinevaraus_id='".$ov->id."' ");

		if(isset($ov->id) and isset($tv->id))
		{


			$asiakas = Asiakkaat::model()->findbypk($ov->asiakas_id);
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
<tr><td>Tilausnumero</td><td>'.$ov->id.'</td></tr>';


if(isset($tilauksen_kuvaus['paa']) and is_array($tilauksen_kuvaus['paa']))
{

  $message .= '<tr><td valign="top">Tilattu tuote</td><td>';
  foreach($tilauksen_kuvaus['paa'] as $k=>$v){
	$message .=  $k.', '.$v.'<br>';
  }

  if(isset($tilauksen_kuvaus['lisa']) and is_array($tilauksen_kuvaus['lisa']))
  {
     foreach($tilauksen_kuvaus['lisa'] as $k=>$v){
	$message .=  $k.', '.$v.'h<br>';
     }
  }
  $message .= '<br></td></tr>';
}



$message .= '
<tr><td>Ajankohta</td><td>'.$tv->pvm.'</td></tr>
<tr><td>Aika</td><td>KLO '.$tv->alku.'-'.$tv->loppu.'</td></tr>				
<tr><td>Paikka</td><td>'.$ov->osoite.', '.$ov->postinumero.' '.$ov->kaupunki.'</td></tr>
<tr><td>Hinta</td><td>'.number_format($ov->veroton_hinta, 2, ',', '').' &euro;</td></tr>
<tr><td>ALV</td><td>'.number_format( ($ov->hinta-$ov->veroton_hinta) , 2, ',', '').' &euro;</td></tr>
<tr><td>Yhteeensä</td><td>'.number_format($ov->hinta, 2, ',', '').' &euro;</td></tr>
<tr><td>Maksu</td><td>Maksu on vahvistettu</td></tr>
</table>

			<p><span>'.$asetukset->tilausvahvistus.'</span></p>

                </div>
            </div>
        </section>  


			';

			$_SESSION['onlinevaraus']['message'] = $message;

			// <-- Lähetetään asiakkaalle
	          	$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($_SESSION['onlinevaraus']['sahkoposti']);
			$mail->setSubject('Online varaus');
			$mail->setBody($message);
			$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $_SESSION['onlinevaraus']['sahkoposti'];
							$log->email_subject	= 'Online varaus';
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

			// Lähetetään asiakkaalle -->

			$firmanTiedot = FirmanTiedot::model()->findbypk(1);

			// <-- Lähetetään toimistoon
			if(isset($firmanTiedot->sahkoposti) and !empty($firmanTiedot->sahkoposti))
			{
			$message .= '<p><h3>Kopio</h3></p>';
	          	$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($firmanTiedot->sahkoposti);
			$mail->setSubject('Online varaus');
			$mail->setBody($message);
			$mail->send();


							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $firmanTiedot->sahkoposti;
							$log->email_subject	= 'Online varaus';
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

			}
			// Lähetetään toimistoon -->

			
			$t = Tyovuoroot::model()->findbypk($tv->id);
			$t->osoiteOnline=2;
			$t->save();

			$o = Onlinevaraus::model()->findbypk($ov->id);
			$o->tila=1;
			$o->save();

			$this->redirect(Yii::app()->request->baseUrl.'/index.php/onlinevaraus/maksettu?check=ok');
	

		}
	}
	?>




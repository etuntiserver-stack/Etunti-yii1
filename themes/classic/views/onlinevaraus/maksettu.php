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

<div class="row">
    <div class="col-sm-12">
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
			$asetukset = Asetukset::model()->findbypk(1);
			$tv = Tyovuoroot::model()->updatebypk($_GET['REFERENCE'], array('osoiteOnline'=>2));
			$ov = Onlinevaraus::model()->updatebypk($ov->id, array('tila'=>1));

			$message = '<h2>Kiitos tilauksesta, olemme vastanottaneet maksun!</h2><br>';
			$message .= '<h3>'.$asetukset->tilausvahvistus.'</h3><br>';
			$message .= $tv->pvm.', '.$tv->alku.'-'.$tv->loppu;

			echo $message;

	          	$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($_SESSION['onlinevaraus']['sahkoposti']);
			$mail->setSubject('Online varaus');
			$mail->setBody($message);
			$mail->send();

			unset($_SESSION['onlinevaraus']);
		}
	}
	?>
    </div>
</div>



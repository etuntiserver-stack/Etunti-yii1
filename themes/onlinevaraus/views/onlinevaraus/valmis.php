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

	if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
	{
		$veroton_hinta = 0;

		if(isset($_SESSION['onlinevaraus']['alv']))
		{
		$alv = $_SESSION['onlinevaraus']['alv'];
		$veroton_hinta = ($_SESSION['onlinevaraus']['amount']/(1+($alv/100)));
		$alv_hinta = $_SESSION['onlinevaraus']['amount']-$veroton_hinta;
		$veroton_hinta = round((float)str_replace(",",".",$veroton_hinta), 2);
		}



		$ov = Onlinevaraus::model()->findbypk($_SESSION['onlinevaraus']['onlinevarausID']);
		$ov->tv_id = $_SESSION['onlinevaraus']['modelTV'];
		//$ov->maksun_onnistu_koodi = $xml->delayedMAC;

		if(isset($_SESSION['onlinevaraus']['paa_kesto']))
			$ov->kesto = $_SESSION['onlinevaraus']['paa_kesto'];

		$ov->alv = $alv;
		$ov->veroton_hinta = $veroton_hinta;
		$ov->hinta = $_SESSION['onlinevaraus']['amount'];
		$ov->tilauksen_kuvaus = json_encode($_SESSION['onlinevaraus']['tilauksenKuvaus']);
		$ov->save();

	}

	$ov = Onlinevaraus::model()->findByPk($_SESSION['onlinevaraus']['onlinevarausID']);





if(isset($ov->id))
	$tv = Tyovuoroot::model()->find(" onlinevaraus_id='".$ov->id."' ");

if(isset($ov->id) and isset($tv->id))
{



	// <-- Uusi asiakas ja kohde
	if(!isset($_SESSION['onlinevaraus']['asiakas_id']) and !isset($_SESSION['onlinevaraus']['kohde_id']))
	{

		  $asiakkaat = new Asiakkaat;
		  $asiakkaat->kaupunki = $ov->kaupunki;
		  $asiakkaat->postinumero = $ov->postinumero;
		  $asiakkaat->osoite = $ov->osoite;
		  $asiakkaat->puhelin = $ov->puhelin;
		  $asiakkaat->sahkoposti = $ov->sahkoposti;
		  $asiakkaat->tyyppi = $ov->tyyppi;
		  $asiakkaat->yrityksen_nimi = $ov->yrityksen_nimi;
		  $asiakkaat->y_tunnus = $ov->y_tunnus;
		  $asiakkaat->etunimi = $ov->yhteyshenkilo;
		  $asiakkaat->onlinevarauksen_asiakas=1;
		  $asiakkaat->aktiivinen = 1;

		  if($asiakkaat->save())
		  {

			Onlinevaraus::model()->updateByPk($ov->id, array('asiakas_id'=>$asiakkaat->id));

			$kohteet = new Kohteet;
			$kohteet->asiakas_id = $asiakkaat->id;
			$kohteet->etu_suku_nimet = $asiakkaat->etunimi;
			$kohteet->osoite = $asiakkaat->osoite;
			$kohteet->pnumero = $asiakkaat->postinumero;
			$kohteet->kaupunki = $asiakkaat->kaupunki;
			$kohteet->puh_nro = $asiakkaat->puhelin;
			$kohteet->email = $asiakkaat->sahkoposti;
			$kohteet->muut = "Onlinevaraus ".date("d.m.Y");
			$kohteet->tietoja = $ov->lisatietoja;

			if(!$kohteet->save()) {
				echo json_encode(var_dump($kohteet->errors));
				exit;
			} else {
				Onlinevaraus::model()->updateByPk($ov->id, array('kohde_id'=>$kohteet->id));
				Tyovuoroot::model()->updateByPk($tv->id, array('kohde'=>$kohteet->id));
			}


		  } else {
			echo json_encode(var_dump($asiakkaat->errors));
			exit;
		  }


	} 
	// Uusi asiakas ja kohde -->



	// <-- Kuvat siirretaan templatesta kohteeseen
	if(isset($_SESSION['onlinevaraus']['kuvat']))
	{
			if (!file_exists(Yii::app()->basePath."/../img/uploadedfromphone/".Yii::app()->user->domain)) {
			  	mkdir(Yii::app()->basePath."/../img/uploadedfromphone/".Yii::app()->user->domain, 0777, true);
			}
			$uploaddir = Yii::app()->basePath.'/../img/uploadedfromphone/'.Yii::app()->user->domain.'/';
			$ov_updated = Onlinevaraus::model()->findByPk($ov->id);

			$kuvatArr = array();
			foreach(array_reverse(glob('tiedostot/onlinevaraus_temp/'.Yii::app()->user->domain.'/'.$_SESSION['onlinevaraus']['kuvat'].'_*.*')) as $file) 
			{
				$kuvatArr[] = $file;
				$explNimi = explode("/",$file);
				$newname = $ov_updated->kohde_id."_".end($explNimi);
				rename($file, $uploaddir.$newname);
				$kuvk = new KuviaKohteesta;
				$kuvk->kohde_id = $ov_updated->kohde_id;
				$kuvk->tid = 0;
				$kuvk->osoite = $ov->osoite;
				$kuvk->tekijan_nimi = $ov->yhteyshenkilo;
				$kuvk->tiedosto = $newname;
				$kuvk->kuvaus = Yii::t('main', 'Tämä kuva saapunut onlinevarauksesta');
				if(!$kuvk->save())
				print_r($kuvk->getErrors());
			}
			Onlinevaraus::model()->updateByPk($ov->id, array('valokuvat'=>json_encode($kuvatArr)));
	}
	//     Kuvat siirretaan templatesta kohteeseen -->

	if(isset($_SESSION['onlinevaraus']['kupongi']))
	{
		$kup = Kupongit::model()->findbypk($_SESSION['onlinevaraus']['kupongi']);
        	if(isset($kup->id) and $kup->jatkuva == 0)
		{
			Kupongit::model()->updatebypk($kup->id, array('status'=>1)); // nyt on kaytetty
		}
	}


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

<div class="table-responsive">
<table class="table">
<tr><td>Nimi</td><td>'.$ov->yhteyshenkilo.'</td></tr>
<tr><td>Osoite</td><td>'.$ov->osoite.'</td></tr>
<tr><td>Puhelin</td><td>'.$ov->puhelin.'</td></tr>
<tr><td>S-posti</td><td>'.$ov->sahkoposti.'</td></tr>';

if(!empty($ov->yrityksen_nimi))
$message .= '<tr><td>Yritys</td><td>'.$ov->yrityksen_nimi.'</td></tr>';
if(!empty($ov->y_tunnus))
$message .= '<tr><td>Y-tunnus</td><td>'.$ov->y_tunnus.'</td></tr>';

$message .= '
</table>
</div>

<hr>
<div class="table-responsive">
<table class="table">
<tr><td>Tilausnumero</td><td>'.$ov->id.'</td></tr>';


$tilauksen_kuvaus = json_decode($ov->tilauksen_kuvaus, true);
$kuvaus = '';
if(isset($tilauksen_kuvaus['paa']) and is_array($tilauksen_kuvaus['paa']))
{

  $message .= '<tr><td valign="top">Tilattu tuote</td><td>';
  $kuvaus .= "Tilattu tuote\n";
  foreach($tilauksen_kuvaus['paa'] as $k=>$v){

	$message .=  $k;
	$kuvaus .=  $k;
	if(!empty($v)){
		$message .=  ', '.$v;
		$kuvaus .=  ', '.$v;
	}

	$message .=  '<br>';
	$kuvaus .=  "\n";
  }

  if(isset($tilauksen_kuvaus['lisa']) and is_array($tilauksen_kuvaus['lisa']))
  {
     foreach($tilauksen_kuvaus['lisa'] as $k=>$v){
	$message .=  $k.', '.$v.'h<br>';
	$kuvaus .=  $k.", ".$v."h\n";
     }
  }
  $message .= '<br></td></tr>';
}

$kuvaus .=  Yii::t('main', 'Lisätietoja').': '.$ov->lisatietoja."\n";



$message .= '
<tr><td>Ajankohta</td><td>'.$tv->pvm.'</td></tr>
<tr><td>Aika</td><td>KLO '.$tv->alku.'-'.$tv->loppu.'</td></tr>				
<tr><td>Paikka</td><td>'.$ov->osoite.', '.$ov->postinumero.' '.$ov->kaupunki.'</td></tr>
<tr><td>Hinta</td><td>'.number_format($ov->veroton_hinta, 2, ',', '').' &euro;</td></tr>
<tr><td>ALV</td><td>'.number_format( ($ov->hinta-$ov->veroton_hinta) , 2, ',', '').' &euro;</td></tr>
<tr><td>Yhteeensä</td><td>'.number_format($ov->hinta, 2, ',', '').' &euro;</td></tr>
<tr><td>Maksu</td><td>Laskutetaan</td></tr>
</table>
</div>

			<p><span>'.$asetukset->tilausvahvistus.'</span></p>

                </div>
            </div>
        </section>';

			$_SESSION['onlinevaraus']['message'] = $message;
			$firmanTiedot = FirmanTiedot::model()->findbypk(1);


			// <-- Lähetetään asiakkaalle
	          	$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->addReplyTo($firmanTiedot->sahkoposti, $firmanTiedot->tyonantaja);
			$mail->setTo($ov->sahkoposti);
			$mail->setSubject('Sähköinen varaus');
			$mail->setBody($message);
			if($mail->send())
			{
							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $ov->sahkoposti;
							$log->email_subject	= 'Online varaus';
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->
			}

			// Lähetetään asiakkaalle -->


			// <-- Lähetetään toimistoon
			if(isset($firmanTiedot->sahkoposti) and !empty($firmanTiedot->sahkoposti))
			{
			$message .= '<p><h3>Kopio</h3></p>';
	          	$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->addReplyTo($firmanTiedot->sahkoposti, $firmanTiedot->tyonantaja);
			$mail->setTo($firmanTiedot->sahkoposti);
			$mail->setSubject('Online varaus');
			$mail->setBody($message);
			if($mail->send())
			{
							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $firmanTiedot->sahkoposti;
							$log->email_subject	= 'Online varaus';
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->
			}
			}
			// Lähetetään toimistoon -->


			
			$t = Tyovuoroot::model()->findbypk($tv->id);
			$t->osoiteOnline=3;
			$t->tietoja=$kuvaus;
			$t->save();

			$o = Onlinevaraus::model()->findbypk($ov->id);
			$o->tila=3;
			$o->save();

			echo $_SESSION['onlinevaraus']['message'];
			unset($_SESSION['onlinevaraus']);

			/*
			echo '<div class="container">';
			echo CHtml::button('Sulje', array('id'=>'sulje','class'=>'btn btn-primary'));
			echo '</div>';
			echo '<br><br>';
			*/

}



?>




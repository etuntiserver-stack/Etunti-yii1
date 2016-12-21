<?php

if(isset($_GET['id']))
  $id = $_GET['id'];



if(isset($_GET['kopio'])){

     $tapahtumapvm = date("Y-m-d H:i:s");
     $l = Lasku::model()->findbypk($id);

     $uusi = new Lasku;
     $uusi->attributes = $l->attributes;
     $uusi->paivays = date("Y-m-d");
     $uusi->erapaiva = date("Y-m-d", strtotime("+".$l->maksuehto." day"));
     $uusi->tilanne = 0;
     $uusi->laskunumero = '';
     $uusi->viitenumero = '';
     $uusi->trust_jobid = '';
     $uusi->save();

     $lr = LaskunRivit::model()->findAll(" lid='".$id."' ");
     foreach($lr as $rivi)
     {

     $uusiR = new LaskunRivit;
     $uusiR->attributes = $rivi->attributes;
     $uusiR->lid = $uusi->id;
     $uusiR->save();

     }


		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $uusi->id;
		    $historia->status = 'Lasku luotu';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$uusi->id));
}


if(isset($_GET['merkitseMaksetuksi'])){

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tilanne'=>3,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $yhteensa_total = 0;
		    if(isset($l->yhteensa_total))
		    $yhteensa_total = $l->yhteensa_total;

		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'MAKSETTU';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}

if(isset($_GET['merkitseLahetettavaksi'])){

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tilanne'=>2,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'LÄHETETTY';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}

if(isset($_GET['merkitseMaksumuistutusLahetettavaksi'])){

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'MAKSUMUISTUTUS';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}

if(isset($_GET['hyvaksyminen'])){

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tilanne'=>1,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'HYVÄKSYTTY';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}

if(isset($_GET['mitatointi'])){


     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tilanne'=>999,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'Lasku mitätöity';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('update','id'=>$id));
}




// <-- laheta Netvisor
if(isset($_GET['lahetaNetvisor']))
{
	$return = $this->lahetaNetvisoriin($id);
	if($return != false)
		$this->redirect(array('index'));
}
//  laheta Netvisor -->





// <-- Trust Hyvityslasku
if(isset($_GET['finvoiceTrust']) or isset($_GET['hyvityslasku'])){

 $cid = $asetukset['trust_cid'];
 $api = $asetukset['trust_api'];
 $trust_url = $asetukset['trust_url'];

 require_once ('lib/trust/inc.trust.php');


   $rowsArray = array();
     foreach($laskunRivit as $rivi){


        $rowsArray[] =  array(
                        "productid" => $rivi->id, # tuotenro
                        "desc" => $rivi->tkoodi,
                        "freetext" => "",
                        "count" => $rivi->kpl, # määrä
                        "amount" => $rivi->hinta, # yksikköhinta
                        "totalitemprice" => $rivi->yhteensa_alv, # verollinen yksikköhinta
                        "taxpr" => $rivi->alv, # alv-prosentti
                        "discount" => $rivi->ale, # alennusprosentti
                        "itemtype" => $rivi->yksikko, # yksikkö
                        "netamount" => $rivi->veroton, # veroton summa
                        "vatamount" => $rivi->hinta_alv, # veron määrä
                        "totalamount" => $rivi->yhteensa_alv, # verollinen summa
                        "salesman" => "", # Myyjä
                        //"startdate" => "2013-01-01", # ajankohta
                        //"enddate" => "2015-12-31",
                        //"eancode" => "" # EAN-viivakoodi
                    );
      }



if($lasku['tyyppi'] == 'yritys')
$BuyerOrganisationName = $lasku['yritys'];
if($lasku['tyyppi'] == 'henkilo')
$BuyerOrganisationName = $lasku['nimi'];

if($lasku['tyyppi'] == 'yritys'){
$person = $lasku['yhteyshenkilo'];
$customertype = 1;
}
if($lasku['tyyppi'] == 'henkilo'){
$person = $lasku['nimi'];
$customertype = 2;
}

$cashdiscountrow = array();

$refundtojobid = '';
if(isset($_GET['refundtojobid']) and !empty($_GET['refundtojobid']))
$refundtojobid = $_GET['refundtojobid'];

if(isset($_GET['refundtojobid']) and empty($_GET['refundtojobid']))
{
echo 'refundtojobid puutuu';
exit;
}

  $sendtype = '';
  $evoice = '';
  $evoiceint = '';

if($lasku['laskutus'] == 'posti')
  $sendtype = 'post';

if($lasku['laskutus'] == 'verkkolasku'){
  $evoice = $lasku['verkkolaskuosoite'];
  $evoiceint = $lasku['v_tunnus'];
  $sendtype = 'evoice';
}

if($lasku['laskutus'] == 'sahkoposti')
  $sendtype = 'email';

  $sensible = 0;
if($lasku['muistutuslasku_auto'] != $sensible)
  $sensible = $lasku['muistutuslasku_auto'];

  $postclass = 1;
if($lasku['kirjeenluokka'] != $postclass)
  $postclass = $lasku['kirjeenluokka'];


  $jobtype = 0;
if(isset($_GET['jobtype']))
  $jobtype = $_GET['jobtype'];


/* Hae siirtoavain (korvaa cid ja apicode omillasi) */
$transferkey = getTransferKey ($cid, $api);
if (!$transferkey) {
    die ("Kirjautuminen epäonnistui\n");
}


if($jobtype == 0)
{
/* Muodosta täydellinen XML-lasku */
$xml = encodeXml (array(
    'datastream' => array(
        'transferkey' => $transferkey,
        'dataset' => array(
            array(
		"deliverymethod" => $lasku['deliverymethod'],
		"deliveryterm" => $lasku['deliveryterm'],
		"refundtojobid" => $refundtojobid,
                "custnum" => $lasku['as_nro'], # asiakasnumero
                //"addressaddline1" => $lasku['osoite'],
                "person" => $person,
                "company" => $lasku['yritys'], # yrityksen nimi
                //"addressaddline2" => "Edunvalvoja Essi Vuori",
                "address" => $lasku['osoite'], # katuosoite

                "postcode" => $lasku['postinumero'],
                "city" => $lasku['toimipaikka'],
                "addresscountry" => "FIN",
                //"vatperiod" => $lasku['vatperiod'],
                "customertype" => $customertype, # asiakastyyppi: 2=kuluttaja
                "jobtype" => $jobtype, # tehtävän tyyppi: 0 = lasku
                "paydate" => $lasku['erapaiva'], # eräpäivä
                "billdate" => $lasku['paivays'], # laskun päiväys
                "govid" => $lasku['y_tunnus'], # y-tunnus tai hetu
                "vatid" => "", # alv-tunniste
                "evoice" => $evoice, # verkkolaskuosoite
                "evoiceint" => $evoiceint, # välittäjän tunnus
                "overdueinterest" => $lasku['viivastyskorko'], # korkopros: tyhjä = oletus
                //"billnum" => $lasku['laskunumero'], # laskun numero
                "billcode" => "", # tilitysviite tai viesti
                "ourcode" => $lasku['viitemme'],
                "yourcode" => $lasku['viitenne'],
                "email" => $lasku['sahkoposti'], # 1.email osoite
                "email2" => "", # 2.email osoite
                //"salesman" => "MM", # vapaavalintainen myyjän tunniste
                //"salesmanname" => "Masa Myyjä", # myyjän nimi
                "checkbillnum" => 1, # 1=tarkista laskunumero, 0=ei
                "language" => "fin", # laskun kieli
                "freetext" => $lasku['freetext'],
                "sendtype" => $sendtype, # laskun lähetystapa
                "cashbill" => 0, # 0 = ei käteiskuitti
                "sensible" => $sensible, # 0 = lähetä muistutus automaattisesti
                //"ownref" => "x123", # sisäinen viite
                //"ordernumber" => "10232", # tilausnumero
                "negvat" => 0, # 0 = ei käänteistä alvia
                "postclass" => $postclass, # 1 = postitus 1.luokassa
                "color" => 0, # 0 = mustavalko
                "printoperator" => "enfo", # tulostusoperaattori
                "billtemplate" => "CUSTOM", # laskupohja
                "collectionprocess" => "AUTO", # saatavan laji
                "netamount" => $lasku['yhteensa_total_veroton'], # veroton hinta yhteensä
                "vatamount" => $lasku['yhteensa_total_verot'], # veron määrä yhteensä
                "totalamount" => $lasku['yhteensa_total'], # verollinen loppusumma

                # Myytävät tuotteet
                "payrow" => $rowsArray,

                # alv-erittely (tässä vain yksi rivi)
                "taxrow" => array(
                    array(
                        "taxpr" => 24.0,
                        "netamount" => $lasku['yhteensa_total_veroton'],
                        "vatamount" => $lasku['yhteensa_total_verot'],
                        "totalamount" => $lasku['yhteensa_total']
                    )
                ),

                # Kassa-alennus
                "cashdiscountrow" => $cashdiscountrow,
            )
        )
    )
));
}



if($jobtype == 2)
{
/* Muodosta täydellinen XML-lasku */
$xml = encodeXml (array(
    'datastream' => array(
        'transferkey' => $transferkey,
        'dataset' => array(
            array(
                "custnum" => $lasku['as_nro'], # asiakasnumero
                "person" => $person,
                "company" => $lasku['yritys'], # yrityksen nimi
                "address" => $lasku['osoite'], # katuosoite
                "postcode" => $lasku['postinumero'],
                "city" => $lasku['toimipaikka'],
                "addresscountry" => "FIN",
                "customertype" => $customertype, # asiakastyyppi: 2=kuluttaja
                "jobtype" => $jobtype, # tehtävän tyyppi: 0 = lasku
                "paydate" => $lasku['erapaiva'], # eräpäivä
                "billdate" => $lasku['paivays'], # laskun päiväys
                "govid" => $lasku['y_tunnus'], # y-tunnus tai hetu
                "vatid" => "", # alv-tunniste
                "evoice" => $evoice, # verkkolaskuosoite
                "evoiceint" => $evoiceint, # välittäjän tunnus
                "overdueinterest" => $lasku['viivastyskorko'], # korkopros: tyhjä = oletus
                "billnum" => $lasku['laskunumero'], # laskun numero
                "billcode" => $lasku['viitenumero'], # tilitysviite tai viesti
                "email" => $lasku['sahkoposti'], # 1.email osoite
                "email2" => "", # 2.email osoite
                "checkbillnum" => 1, # 1=tarkista laskunumero, 0=ei
                "language" => "fin", # laskun kieli
                "sendtype" => $sendtype, # laskun lähetystapa
                "amount" => $lasku['yhteensa_total'], # verollinen loppusumma
		"noticedate" => $lasku['paivays'],

                # Myytävät tuotteet
                "payrow" => $rowsArray,

                # alv-erittely (tässä vain yksi rivi)
                "taxrow" => array(
                    array(
                        "taxpr" => 24.0,
                        "netamount" => $lasku['yhteensa_total_veroton'],
                        "vatamount" => $lasku['yhteensa_total_verot'],
                        "totalamount" => $lasku['yhteensa_total']
                    )
                ),

                # Kassa-alennus
                "cashdiscountrow" => $cashdiscountrow,
            )
        )
    )
));
}



/* Lähetä lasku palvelimelle */
echo "------ send ------\n";
echo $xml;
$res = commitTransfer ($xml);

/* Tulosta vastausviesti */
echo '<textarea class="form-control" rows="20">'.$res.'</textarea>';
echo "\n";

/* Tulkitse palvelimen vastausviesti */
$doc = parseXml ($res);
echo "------ parse ------\n";

/* Tulosta hyväksytyt ja hylätyt laskut */
for ($i = 0; $i < count ($doc->row); $i++) {

    if ($doc->row[$i]->accepted == '1') {
        echo 'accept billnum ' . $doc->row[$i]->billnum
            . ' jobid ' . $doc->row[$i]->jobid . "<br>";

     	Lasku::model()->updatebypk($id, array('tilanne'=>2,'trust_jobid'=>$doc->row[$i]->jobid,'viitenumero'=>$doc->row[$i]->reference));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->lid = $id;
		    $historia->status = json_encode($doc->row);
		    $historia->palvelu = "trust";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

	$this->redirect(array('index'));
	break;

    } else {
        echo 'reject billnum ' . $doc->row[$i]->billnum
            . '<br> error ' . utf8_decode ($doc->row[$i]->error) . "<br>";
    }
}



}














function base64url_encode($input) {
    return strtr(base64_encode($input), '+/', '-_');
}



if(isset($_GET['laskutus']))
{
	$xml = $this->renderPartial('xml',array(
			'asetukset'=>$asetukset,
			'lasku'=>$lasku, 
			'yritys'=>$yritys,
			'laskunRivit'=>$laskunRivit
	), true);
}


if(isset($_GET['laskutus']) and $_GET['laskutus'] == 'verkkolasku'){

//echo $xml;
//exit;

if(!empty($asetukset['postita_username']) and !empty($asetukset['postita_password']))
{
$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;


$account_info_url = 'https://postita.fi/api/account_info/';
$send_url = 'https://postita.fi/api/send/';
$send_finvoice_url = 'https://'.$auth_string.'@postita.fi/api/send_finvoice/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $account_info_url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

$account_info = curl_exec($ch);
$account_info = json_decode($account_info, true);


$pdf = trim($xml);
$pdf_b64 = base64url_encode($pdf);

$data = array('job_name' => 'Verkkolasku', 'confirm' => false, 'finvoice' => $pdf_b64);
curl_setopt($ch, CURLOPT_URL, $send_finvoice_url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); 

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';

curl_close($ch);

  if(isset($send_response[0]['status']) and $send_response[0]['status'] == 'NE')
  {

       $job_id = '';
       $created = '';

     foreach($send_response[0] as $k => $v ) {
       $prep[$k] = $k.":".$v;
       if($k == 'id')
       $job_id = $v;
       if($k == 'created')
       $created = $v;
     }

     $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($created)));
     Lasku::model()->updatebypk($id, array('tilanne'=>2,'response_finvoice'=>$resultJson,'postita_jobid'=>$job_id,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = $resultJson;
		    $historia->postita_statuscode = $send_response[0]['status'];
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();


  	if (!file_exists(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain)) {
  		mkdir(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain, 0777, true);
  	}

	$url = 'https://postita.fi/api/job_pdf/'.(int)$job_id;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	
	$content = curl_exec($ch);
	curl_close($ch);
	
	$base64 = $content;
	$binary = $base64;
	$putPath = Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain;
	file_put_contents($putPath.'/'.$id.'.pdf', $binary);


     		    $this->redirect(array('update','id'=>$id));

  }


} else { 
	echo 'POSTITA tunnukset ei löydy';
} //if /pass


/*

$file = "tiedostot/finvoice/report.xml";
file_put_contents($file, $xml); 


header('Content-type: application/xml');
header('Content-Disposition: inline; filename="report.xml"');
@readfile($file);
*/
	//unlink($file);


}





if(isset($_GET['laskutus']) and $_GET['laskutus'] == 'posti'){

if(!empty($asetukset['postita_username']) and !empty($asetukset['postita_password']))
{
$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;


$account_info_url = 'https://postita.fi/api/account_info/';
$send_url = 'https://postita.fi/api/send/';
$send_finvoice_url = 'https://'.$auth_string.'@postita.fi/api/send_finvoice/';

/* First initialize curl and set some options. For more information about
   curl with PHP refer to http://php.net/manual/en/book.curl.php */
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $account_info_url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

/* Getting account info */
$account_info = curl_exec($ch);
$account_info = json_decode($account_info, true);


echo '<pre>';
print_r($account_info);
echo '</pre>';


  $asetukset=Asetukset::model()->find("id=1");
  $firmanTiedot=FirmanTiedot::model()->find("id=1");

  $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
  $html2pdf->setDefaultFont('Arial');
  $html2pdf->WriteHTML($this->renderPartial('lasku_pdf', 
			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			),true));


  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);

  $pdf = $content_PDF;
  $pdf_b64 = base64url_encode($pdf);

$data = array('job_name' => 'PDF muoto', 'confirm' => false, 'pdf' => $pdf_b64, 'post_class' => $lasku['kirjeenluokka']);
curl_setopt($ch, CURLOPT_URL, $send_url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); 

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';

curl_close($ch);


  if(isset($send_response['status']) and $send_response['status'] == 'NE')
  {
       $job_id = '';
       $created = '';
     foreach($send_response as $k => $v ) {
       $prep[$k] = $k.":".$v;
       if($k == 'id')
       $job_id = $v;
       if($k == 'created')
       $created = $v;
     }
     $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($created)));
     Lasku::model()->updatebypk($id, array('tilanne'=>2,'response'=>$resultJson,'postita_jobid'=>$job_id,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = $resultJson;
		    $historia->postita_statuscode = $send_response['status'];
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();


  	if (!file_exists(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain)) {
  		mkdir(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain, 0777, true);
  	}

	$url = 'https://postita.fi/api/job_pdf/'.(int)$job_id;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	
	$content = curl_exec($ch);
	curl_close($ch);
	
	$base64 = $content;
	$binary = $base64;
	$putPath = Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain;
	file_put_contents($putPath.'/'.$id.'.pdf', $binary);



     		    $this->redirect(array('update','id'=>$id));

  }

} else { 
	echo 'POSTITA tunnukset ei löydy';
} //if /pass

}



// vahvistus
if(isset($_GET['vahvistus'])){

$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;
$url = 'https://postita.fi/api/confirm/'.(int)$_GET['vahvistus'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';
curl_close($ch);

  if(isset($send_response['status']) and $send_response['status'] == 'CO')
  {
       $job_id = '';
       $created = '';
     foreach($send_response as $k => $v ) {
       $prep[$k] = $k.":".$v;
       if($k == 'id')
       $job_id = $v;
       if($k == 'created')
       $created = $v;
     }
     $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($created)));
     Lasku::model()->updatebypk($id, array('tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = $resultJson;
		    $historia->postita_statuscode = $send_response['status'];
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

     		    $this->redirect(array('update','id'=>$id));

  }

}


// poitaminen
if(isset($_GET['delete'])){

$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;
$url = 'https://postita.fi/api/delete/'.(int)$_GET['delete'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';
curl_close($ch);

     $tapahtumapvm = date("Y-m-d H:i:s");
     Lasku::model()->updatebypk($id, array('tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'POISTETTU';
		    $historia->postita_statuscode = 'POISTETTU';
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

     		    $this->redirect(array('update','id'=>$id));


}


if(isset($_GET['lahetaMuistutusPostita']) and !empty($asetukset['postita_username']) and !empty($asetukset['postita_password'])){


$username = $asetukset['postita_username'];
$password = $asetukset['postita_password'];
$auth_string = $username . ":" . $password;


$account_info_url = 'https://postita.fi/api/account_info/';
$send_url = 'https://postita.fi/api/send/';
$send_finvoice_url = 'https://'.$auth_string.'@postita.fi/api/send_finvoice/';

/* First initialize curl and set some options. For more information about
   curl with PHP refer to http://php.net/manual/en/book.curl.php */
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $account_info_url);
curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

/* Getting account info */
$account_info = curl_exec($ch);
$account_info = json_decode($account_info, true);


echo '<pre>';
print_r($account_info);
echo '</pre>';


  $asetukset=Asetukset::model()->find("id=1");
  $firmanTiedot=FirmanTiedot::model()->find("id=1");

  $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
  $html2pdf->setDefaultFont('Arial');
  $html2pdf->WriteHTML($this->renderPartial('lasku_pdf', 
			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			'lahetaMuistutusPostita' => true,
			),true));


  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);

  $pdf = $content_PDF;
  $pdf_b64 = base64url_encode($pdf);

$data = array('job_name' => 'MAKSUMUISTUTUS', 'pdf' => $pdf_b64, 'post_class' => $lasku['kirjeenluokka']);
curl_setopt($ch, CURLOPT_URL, $send_url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); 

$send_response = curl_exec($ch);
$resultJson = json_encode($send_response);

if (curl_errno($ch)) {
  echo "\n\ncURL error number: " . curl_errno($ch);
  echo "\n\ncURL error: " . curl_error($ch);
}
$send_response = json_decode($send_response, true);

echo '<pre>';
print_r($send_response);
echo '</pre>';

curl_close($ch);


  if(isset($send_response['status']) and $send_response['status'] == 'CO')
  {
       $job_id = '';
       $created = '';
     foreach($send_response as $k => $v ) {
       $prep[$k] = $k.":".$v;
       if($k == 'id')
       $job_id = $v;
       if($k == 'created')
       $created = $v;
     }
     $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($created)));
     Lasku::model()->updatebypk($id, array('tapahtumapvm'=>$tapahtumapvm));
		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = $resultJson;
		    $historia->postita_statuscode = 'MAKSUMUISTUTUS';
		    $historia->palvelu = "postita";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();

     		    $this->redirect(array('update','id'=>$id));

  }

}

if(isset($_GET['lahetaSahkopostilla']) and !empty($lasku['sahkoposti']))
{

  $asetukset=Asetukset::model()->find("id=1");
  $firmanTiedot=FirmanTiedot::model()->find("id=1");

  $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
  $html2pdf->setDefaultFont('Arial');
  $html2pdf->WriteHTML($this->renderPartial('lasku_pdf', 
			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			),true));
  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);


		/* file */
		$file = 'lasku_'.date("YmdHi").'.pdf';
		$path = Yii::app()->request->baseUrl."emails/laskut/".Yii::app()->user->domain;

  		if (!file_exists($path))
		  	mkdir($path, 0777, true);

		file_put_contents($path.'/'.$file, $content_PDF);

		$message = Yii::t('main', 'Liitteenä uusi lasku');
		$saaja = $lasku['sahkoposti'];
		$subject = Yii::t('main', 'Ilmoitus saapuneesta laskusta');
		$mail = new YiiMailer();
		$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		$mail->setTo($saaja);
		$mail->setSubject($subject);
		$mail->setBody($message);
		$mail->setAttachment($path.'/'.$file);
	
		if($mail->send())
		{

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $saaja;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$file;
							$log->save();
							//     LOG -->

     		$tapahtumapvm = date("Y-m-d H:i:s");
     		Lasku::model()->updatebypk($id, array('tilanne'=>2,'tapahtumapvm'=>$tapahtumapvm));

		    // Lasku historia
		    $l = Lasku::model()->findbypk($id);
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $id;
		    $historia->status = 'Lähetetty sähköpostilla';
		    $historia->palvelu = "local";
		    $historia->yht_euro = $l->yhteensa_total;
		    $historia->save();


     		    $this->redirect(array('update','id'=>$id));
		}


} elseif(isset($_GET['lahetaSahkopostilla']) and empty($lasku['sahkoposti'])){

		echo Yii::t('main', 'Sähköposti puuttuu');

}



?>

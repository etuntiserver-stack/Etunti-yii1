<?php

class AsiakkaatController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('login', 'salasana'),
				'users'=>array('*'),
			),
			array('allow', 
				'actions'=>array('asiakas_tila', 'ulos', 'osoitteen_muutos', 'send_vastaus', 'getLaskuPDF'),
                		'expression'=>"Yii::app()->controller->isAsiakas()",
			),
			array('allow',
				'actions'=>array('admin', 'delete', 'create', 'update', 'index', 'view', 'checkLastAsiakasID', 'showshift', 'send_vastaus', 'getLaskuPDF', 'kartta'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function isAsiakas() 
	{
		if(isset(Yii::app()->user->asiakas))
		{
		$m = Asiakkaat::model()->findbypk(Yii::app()->user->asiakas);
	        if($m->id == Yii::app()->user->asiakas)
	            return true;
		} else {
	            return false;
		}
	}

	public function isEtuntiAdmin() {

		if(isset(Yii::app()->user->adminID))
		{
		$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	        if($m->id == Yii::app()->user->adminID)
	            return true;
		} else {
	            return false;
		}
	}

        public function init()
        {

                if (Yii::app()->controller->isEtuntiAdmin() and !isset(Yii::app()->user->user_theme)) {
                        Yii::app()->theme = 'etunti';
                } elseif (Yii::app()->controller->isEtuntiAdmin() and isset(Yii::app()->user->user_theme)) {
                        Yii::app()->theme = Yii::app()->user->user_theme;
                } elseif (isset(Yii::app()->user->asiakas)) {
                        Yii::app()->theme = 'customer';
                } else {
                        Yii::app()->theme = 'classic';
                }
                parent::init();
        }


	public function actionSalasana($domain, $token, $asiakasid)
	{

                Yii::app()->theme = 'classic';
		$model=$this->loadModel($asiakasid);

		if(isset($model->id) and !empty($model->token) and $model->token == $token) 
			$tilanne = 1;
		elseif(isset($model->id) and empty($model->token)) 
			$tilanne = 2;  
		else 
			die('Error');

		if(isset($model->id) and isset($_POST['password1']) and $_POST['password1'] == $_POST['password2'])
		{
			Asiakkaat::model()->updateByPk($model->id, array('salasana' => $_POST['password1'], 'token' => ''));
			$tilanne = 2;
			$this->redirect(array('/site/index'));
		}

		$this->render('salasana', array('tilanne' => $tilanne));
	}

	public function actionKartta()
	{
		$this->render('kartta');
	}

	public function actionGetLaskuPDF($id)
	{
		$lasku = Yii::app()->createController('Lasku');
		echo $lasku[0]->Lasku_pdf($id);
	}

	public function actionUlos()
	{
		$dm = Yii::app()->user->domain;
		Yii::app()->user->logout();
		   $this->redirect(array('login','domain'=>$dm));
	}

	public function actionLogin()
	{

		Yii::app()->theme = 'customer';

		if(isset($_POST['domain']))
		{
	  		Yii::app()->user->setState('domain', $_POST['domain']);
			$dm=Domainit::model()->find(" domain='".Yii::app()->user->domain."' ");
			if(isset($dm->paketti))
			Yii::app()->user->setState('adminPaketti', $dm->paketti);
		}

		if(isset($_POST['sahkoposti']) and isset($_POST['salasana']))
		{
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				sahkoposti='".$_POST['sahkoposti']."' 
				AND salasana='".$_POST['salasana']."'
				AND salasana!=''
			";
			$model=Asiakkaat::model()->find($criteria);
			if(isset($model->id))
			{
				Yii::app()->user->setState('asiakas', $model->id);
				$this->redirect(array('asiakas_tila','id'=>$model->id));
			}
		}

		if(isset(Yii::app()->user->asiakas))
				$this->redirect(array('asiakas_tila','id'=>Yii::app()->user->asiakas));

		$this->render('login');
	}



	public function actionOsoitteen_muutos()
	{

	        if(isset(Yii::app()->user->asiakas))
		{
			Yii::app()->theme = 'customer';
			$model=$this->loadModel(Yii::app()->user->asiakas);

		if(isset($_POST['Asiakkaat']))
		{
			$model->attributes=$_POST['Asiakkaat'];
			$bd = '<table>';
			foreach($model->attributes as $k=>$v)
			{
				if(isset($_POST['Asiakkaat'][$k]) and !empty($_POST['Asiakkaat'][$k]))
				$bd .= '<tr><td>'.$model->getAttributeLabel($k).'</td><td>'.$v.'</td></tr>';
			}
			$bd .= '</table>';

			$subject = Yii::t('main', 'Osoitteen muutos'). ' '.Yii::t('main', 'Asiakas').': '.$model->id;
			$ft = FirmanTiedot::model()->findbypk(1);
			$mail = new YiiMailer();
			//$mail->clearLayout();//if layout is already set in config
			$mail->setFrom('no-reply@etunti.fi');
			$mail->setTo($ft->sahkoposti);
			$mail->setSubject($subject);
			$mail->setBody($bd);
			$mail->send();
			
							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $ft->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				$this->redirect(array('//site/index'));
				exit;

		}


			$this->render('osoitteen_muutos', array('model'=>$model));

		} else {
	        	return false;
		}


	}

	public function actionAsiakas_tila()
	{

	        if(isset(Yii::app()->user->asiakas))
		{
			Yii::app()->theme = 'customer';
			$model=$this->loadModel(Yii::app()->user->asiakas);

		if(isset($_POST['Asiakkaat']))
		{
			$model->attributes=$_POST['Asiakkaat'];

			if($model->salasana != md5($_POST['Asiakkaat']['salasana']))
			$model->salasana = md5($_POST['Asiakkaat']['salasana']);

			if($model->save())
				$this->redirect(array('asiakas_tila','id'=>$model->id));
		}


			$this->render('update', array('model'=>$model));

		} else {
	        	return false;
		}


	}

	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	public function actionShowshift($id)
	{

       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') ASC ";
/*
        	$criteria->condition = "DATE(paivays) BETWEEN 
			asiakas_id='".$id."'
		";
*/
		$from = date("Y-m-d");
		$to = date("Y-m-d", strtotime("+1 month"));

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}


        	$criteria->addCondition ("
			kohde IN 
			(SELECT id FROM sivex_kohdet 
			   WHERE asiakas_id='".$id."'
			)
		AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		");


		$model=Tyovuoroot::model()->findAll($criteria);

		if(isset($_POST['tulosta'])){

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('showshift', 
			array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
			'id'=>$id,
			),true));
	          $html2pdf->Output();

		} else {

		$this->render('showshift',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
			'id'=>$id,
		));

		}
	}
/*
	public function actionCheckLastAsiakasID()
	{
		$check = 0;
		$model=Asiakkaat::model()->find(" asiakasnumero='".$_POST['checkLastAsiakasID']."' ");
		if(isset($model->id))
		$check = 1;

		echo $check;
	}
*/

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

	// <-- Oikeudet
	   $checkOikeus = "asiakkaat_1_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=new Asiakkaat;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Asiakkaat']))
		{

			$asetukset = Asetukset::model()->findbypk(1);
			$model->attributes=$_POST['Asiakkaat'];

			if(isset($_POST['Asiakkaat']['ryhma']))
				$model->ryhma=json_encode($_POST['Asiakkaat']['ryhma']);
			else
				$model->ryhma="";

			if($model->save())
			{

			   	// <-- Vinkki
				$vinkki = VinkkiExtranet::model()->findByPk($model->vinkki_id);
				if(isset($vinkki->id))
				{
					VinkkiExtranet::model()->updatebypk($vinkki->id, array('vinkkaja_asiakas_id' => $model->id, 'tila'=>2));
					Asiakkaat::model()->updatebypk($vinkki->asiakas_id,
					array('vinkki_tunnit'=>$asetukset->vinkki_tunnit, 'vinkki_prosentti'=>$asetukset->vinkki_prosentti
				));
				}
			   	//     Vinkki -->

			   	// <-- Netvisor
				if($asetukset->netvisor_kaytto == 1)
				{
					$this->netvisorCustomer("add", $model);
				}
				//  Netvisor -->


				if(empty($model->asiakasnumero))
				$a = Asiakkaat::model()->updatebypk($model->id, array('asiakasnumero'=>$model->id));

				$this->redirect(array('//kohteet/createfromasiakas', 'id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{

	// <-- Oikeudet
	   $checkOikeus = "asiakkaat_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=$this->loadModel($id);


		if(isset($_GET['suljeJuttelu']))
		{
			Palautteet::model()->updatebypk($_GET['suljeJuttelu'], array('status'=>3));
			$this->redirect(array('update','id'=>$id));
		}
		if(isset($_POST['palaute_id']))
		{
			$this->palautteetVastaus($_POST);
			$this->redirect(array('update','id'=>$id));
		}


		// <-- Tunnukset lahetys
		if(isset($_GET['laheta_tunnukset']))
		{
				$d = Domainit::model()->find("domain='".Yii::app()->user->domain."'");
				$yr =  '';
				if(isset($d->yritys))
				$yr =  $d->yritys;

				$asiakas = '';
				if($model->tyyppi == 'yritys')
					$asiakas = $model->yrityksen_nimi;
				if($model->tyyppi == 'henkilo')
					$asiakas = $model->yhteyshenkilo;

				$token = sha1(uniqid(time().$model->id, true));
				Asiakkaat::model()->updateByPk($model->id, array('token' => $token));

				$subject = 'Tervetuloa Etunnin käyttäjäksi.';
				$message = 'Hei '.$asiakas.'!<br>
				<b>Domain:</b> '.Yii::app()->user->domain.'<br>
				<b>Käyttäjätunnus:</b> '.$model->sahkoposti.'<br>
				<b>Luo oma salasana:</b> <a href='.Yii::app()->createAbsoluteUrl('asiakkaat/salasana', array('domain' => Yii::app()->user->domain, 'token' => $token, 'asiakasid' => $model->id)).'>tästä</a><br>
<p>
				Tervetuloa Etunnin käyttäjäksi. '.$yr.' on lisännyt sinulle profiilin Etuntiin. Lataa sovellus puhelimeesi alla olevien linkkien kautta.
</p><br>
				<br>
				<p>Ystävällisin terveisin</p>
				Etunti<br>

<p>
<a href="https://www.microsoft.com/store/apps/9nblggh4nd0w?ocid=badge"><img src="https://assets.windowsphone.com/85864462-9c82-451e-9355-a3d5f874397a/English_get-it-from-MS_InvariantCulture_Default.png" alt="Get it from Microsoft" height="70" /></a>

<a href="https://play.google.com/store/apps/details?id=fi.etunti.local&utm_source=global_co&utm_medium=prtnr&utm_content=Mar2515&utm_campaign=PartBadge&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1"><img alt="Get it on Google Play" src="https://play.google.com/intl/en_us/badges/images/generic/en-play-badge.png" height="70" /></a>

<a href="https://geo.itunes.apple.com/fi/app/etunti/id1100648690?mt=8"><img src="http://etunti.fi/etusivuimg/app_store.png" height="70" ></a>
</p>
				';

				//echo $message;
				//exit;

				$ft = FirmanTiedot::model()->findByPk(1);
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($model->sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $model->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				$this->redirect(array('index'));
		}
		//     Tunnukset lahetys -->




		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Asiakkaat']))
		{
//print_r($_POST['Asiakkaat']['hinta_sis_alv']);
//exit;
			$model->attributes=$_POST['Asiakkaat'];

			if(isset($_POST['Asiakkaat']['ryhma']))
				$model->ryhma=json_encode($_POST['Asiakkaat']['ryhma']);
			else
				$model->ryhma="";

			if($model->save())
			{

			   // <-- Netvisor
			   $a = Asetukset::model()->findbypk(1);
			   if($a->netvisor_kaytto == 1)
			   {
				if($model->netvisorkey == 0)
				{
					$this->netvisorCustomer("add", $model);
				} else {
					$this->netvisorCustomer("edit", $model);
				}
			    }
			   //  Netvisor -->

				Yii::app()->user->setFlash('success', "Tallennettu.");
				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{

	// <-- Oikeudet
	   $checkOikeus = "asiakkaat_3_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->


		Kohteet::model()->deleteAll(" asiakas_id='".$id."' ");
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(array('index'));
	}


	protected function netvisorAsiakasNouto($netvisorkey)
	{


		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

		if(isset($n[0]))
		{

		$url		= $n[0].'/getcustomer.nv?id='.$netvisorkey;
		$host 		= $n[1];

		$sender 	= $n[2];
		$customerId	= $n[3];
		$partnerId	= $n[4];
		$timestamp	= $n[5];
		$language	= $n[6];
		$organisationIdentifier	= $n[7];
		$transactionIdentifier	= $n[8];
		$userKey 	= $n[9];
		$partnerKey	= $n[10];



	$getMAC = md5(
		$url.'&'.
		$sender.'&'.
		$customerId.'&'.
		$timestamp.'&'.
		$language.'&'.
		$organisationIdentifier.'&'.
		$transactionIdentifier.'&'.
		$userKey.'&'.
		$partnerKey
	 	);
	
	$auth_data = 
	    "Host: $host\r\n".  
	    "X-Netvisor-Authentication-Sender: $sender\r\n".  
	    "X-Netvisor-Authentication-CustomerId: $customerId\r\n".  
	    "X-Netvisor-Authentication-PartnerId: $partnerId\r\n".  
	    "X-Netvisor-Authentication-Timestamp: $timestamp\r\n".
	    "X-Netvisor-Interface-Language: $language\r\n".
	    "X-Netvisor-Organisation-ID: $organisationIdentifier\r\n".  
	    "X-Netvisor-Authentication-TransactionId: $transactionIdentifier\r\n".
	    "X-Netvisor-Authentication-MAC: $getMAC\r\n"
	; 

	$xml = '';
	$optsPOST = array(
	  'http'=>array(
	    'method'=>"POST",
	    'header'=>"Accept: text/plain\r\n" .
	              "Content-Type: application/x-www-form-urlencoded\r\n".
	              "Content-Length: ".strlen($xml)."\r\n".
		      $auth_data
	  )
	);
	
	$context = stream_context_create($optsPOST);
	
	$response = file_get_contents($url, false, $context);
	$result = new SimpleXMLElement($response);
	

		//if($result->ResponseStatus->Status == 'OK')
		//{
			echo '<pre>';
			print_r( $response );
			echo '</pre>';
			//exit;
		//}

		}

	}

	protected function netvisorCustomer($tila, $model)
	{


		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	if(isset($n[0]))
	{

		if( $tila == 'add' and $model->netvisorkey == 0){
		$url		= $n[0].'/customer.nv?method=add';
		}
		if( $tila == 'edit' and $model->netvisorkey != 0) {
		$url		= $n[0].'/customer.nv?id='.$model->netvisorkey.'&method=edit';
		}

		$host 		= $n[1];

		$sender 	= $n[2];
		$customerId	= $n[3];
		$partnerId	= $n[4];
		$timestamp	= $n[5];
		$language	= $n[6];
		$organisationIdentifier	= $n[7];
		$transactionIdentifier	= $n[8];
		$userKey 	= $n[9];
		$partnerKey	= $n[10];



	$getMAC = md5(
		$url.'&'.
		$sender.'&'.
		$customerId.'&'.
		$timestamp.'&'.
		$language.'&'.
		$organisationIdentifier.'&'.
		$transactionIdentifier.'&'.
		$userKey.'&'.
		$partnerKey
	 	);
	
	$auth_data = 
	    "Host: $host\r\n".  
	    "X-Netvisor-Authentication-Sender: $sender\r\n".  
	    "X-Netvisor-Authentication-CustomerId: $customerId\r\n".  
	    "X-Netvisor-Authentication-PartnerId: $partnerId\r\n".  
	    "X-Netvisor-Authentication-Timestamp: $timestamp\r\n".
	    "X-Netvisor-Interface-Language: $language\r\n".
	    "X-Netvisor-Organisation-ID: $organisationIdentifier\r\n".  
	    "X-Netvisor-Authentication-TransactionId: $transactionIdentifier\r\n".
	    "X-Netvisor-Authentication-MAC: $getMAC\r\n"
	; 
	
	
	$name = 'Ei tietoja';
	if(!empty($model->yrityksen_nimi) and $model->tyyppi == 'yritys')
	$name = $model->yrityksen_nimi;
	elseif(!empty($model->yhteyshenkilo) and $model->tyyppi == 'henkilo')
	$name = $model->yhteyshenkilo;

	$ryhma = '';
	$r = Valikkoot::model()->findbypk($model->ryhma);
	if(isset($r->id))
	$ryhma = $r->value;

	if($model->tyyppi == 'yritys')
	{
		$isprivatecustomer = '0';
		$customerfinvoicedetails = '
		<customerfinvoicedetails>
			<finvoiceaddress>'.$model->verkkolaskuosoite.'</finvoiceaddress>
			<finvoiceroutercode>'.$model->valittajan_tunnus.'</finvoiceroutercode>
		</customerfinvoicedetails>';
	}
	if($model->tyyppi == 'henkilo')
	{
		$isprivatecustomer = '1';
		$customerfinvoicedetails = '';
	}


$xml = '
<root>
  <customer>
    <customerbaseinformation>
      <internalidentifier>'.$model->asiakasnumero.'</internalidentifier>
      <externalidentifier>'.$model->y_tunnus.'</externalidentifier>
      <name>'.$name.'</name>
      <nameextension></nameextension>
      <streetaddress>'.$model->osoite.'</streetaddress>
      <city>'.$model->kaupunki.'</city>
      <postnumber>'.$model->postinumero.'</postnumber>
      <country type="ISO-3166">FI</country>
      <customergroupname>'.$ryhma.'</customergroupname>
      <phonenumber>'.$model->puhelin.'</phonenumber>
      <faxnumber></faxnumber>
      <email>'.$model->sahkoposti.'</email>
      <isactive>'.$model->aktiivinen.'</isactive>
      <isprivatecustomer>'.$isprivatecustomer.'</isprivatecustomer>
    </customerbaseinformation>
	'.$customerfinvoicedetails.'
    <customerdeliverydetails>
      <deliveryname>'.$name.'</deliveryname>
      <deliverystreetaddress>'.$model->osoite.'</deliverystreetaddress>
      <deliverycity>'.$model->kaupunki.'</deliverycity>
      <deliverypostnumber>'.$model->postinumero.'</deliverypostnumber>
      <deliverycountry type="ISO-3166">FI</deliverycountry>
    </customerdeliverydetails>
      <customercontactdetails>
      <contactperson>'.$name.'</contactperson>
      <contactpersonemail>'.$model->sahkoposti.'</contactpersonemail>
      <contactpersonphone>'.$model->puhelin.'</contactpersonphone>
    </customercontactdetails>
    <customeradditionalinformation>
      <customerreferencenumber></customerreferencenumber>
    </customeradditionalinformation>
  </customer>
</root>';
	
	$optsPOST = array(
	  'http'=>array(
	    'method'=>"POST",
	    'header'=>"Accept: text/plain\r\n" .
	              "Content-Type: application/x-www-form-urlencoded\r\n".
	              "Content-Length: ".strlen($xml)."\r\n".
		      $auth_data,
	    	      'content'=> $xml
	  )
	);
	
	$context = stream_context_create($optsPOST);
	
	$response = file_get_contents($url, false, $context);
	$result = new SimpleXMLElement($response);
	

	  if($result->ResponseStatus->Status == 'OK')
	  {

		if( $tila == 'add' )
		Asiakkaat::model()->updateByPk($model->id, array('netvisorkey'=>(int)$result->Replies->InsertedDataIdentifier));

		return 'OK';

	  } else {

		echo '<pre>';
		print_r( $response );
		echo '</pre>';
		exit;

	  }

	
	} // if isset $n[0]




	}


	public function actionIndex()
	{

		if(isset($_POST['asiakkaatPerSivu']))
		{
			Yii::app()->user->setState('asiakkaatPerSivu', $_POST['asiakkaatPerSivu']);
			echo json_encode($_POST['asiakkaatPerSivu']);
			exit;
		}




	// <-- Oikeudet
	   $checkOikeus = "asiakkaat_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

       		$criteria = new CDbCriteria();

		if(isset($_GET['sort']))
		{
	        $criteria->order = " $_GET[sort]!='' DESC, $_GET[sort] $_GET[s] ";
		} else {
	        $criteria->order = " id DESC ";
		}

/*
if(isset($_POST['osoite']))
{
$osoite = filter_var($_POST['osoite'], FILTER_SANITIZE_SPECIAL_CHARS);
echo $osoite;
exit;
}
*/


		if(isset($_POST['osoite']) and !empty($_POST['osoite']))
	        $criteria->addCondition (" osoite LIKE '%".$_POST['osoite']."%' ");

		if(isset($_POST['aktiivinen']) and $_POST['aktiivinen'] != 'kaikki')
	        $criteria->addCondition (" aktiivinen ='".(int)$_POST['aktiivinen']."' ");
		elseif(isset($_POST['aktiivinen']) and $_POST['aktiivinen'] == 'kaikki')
	        $criteria->addCondition (" (aktiivinen=1 OR aktiivinen=0) ");
		else
	        $criteria->addCondition (" aktiivinen=1 ");

		if(isset($_POST['yrityksen_nimi']) and !empty(trim($_POST['yrityksen_nimi'])))
	        $criteria->addCondition (" yrityksen_nimi LIKE '%".$_POST['yrityksen_nimi']."%' ");

		if(isset($_POST['yhteyshenkilo']) and !empty(trim($_POST['yhteyshenkilo'])))
	        $criteria->addCondition (" yhteyshenkilo LIKE '%".$_POST['yhteyshenkilo']."%' ");

		if(isset($_POST['ryhma']) and !empty(trim($_POST['ryhma'])))
	        $criteria->addCondition (" ryhma LIKE '%".$_POST['ryhma']."%' ");

		if(isset($_POST['tyyppi']) and !empty(trim($_POST['tyyppi'])))
	        $criteria->addCondition (" tyyppi='".$_POST['tyyppi']."' ");

		if(isset($_POST['puhelin']) and !empty(trim($_POST['puhelin'])))
	        $criteria->addCondition (" puhelin LIKE '%".$_POST['puhelin']."%' ");

		if(isset($_POST['sahkoposti']) and !empty(trim($_POST['sahkoposti'])))
	        $criteria->addCondition (" sahkoposti LIKE '%".$_POST['sahkoposti']."%' ");

		if(isset($_POST['asiakasnumero']) and !empty(trim($_POST['asiakasnumero'])))
	        $criteria->addCondition (" asiakasnumero LIKE '%".$_POST['asiakasnumero']."%' ");

		$dataProvider=new CActiveDataProvider('Asiakkaat', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));


		$perSivu = 50;
		if(isset(Yii::app()->user->asiakkaatPerSivu))
		$perSivu = Yii::app()->user->asiakkaatPerSivu;

		$dataProvider->pagination->pageSize = $perSivu;

		$a = Asetukset::model()->findbypk(1);
		if($a->netvisor_kaytto == 1)
		$netvisor = true;
		else
		$netvisor = false;

		$this->render('index', array(
			'dataProvider' => $dataProvider, 
			'perSivu' => $perSivu,
			'netvisor' => $netvisor,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Asiakkaat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Asiakkaat']))
			$model->attributes=$_GET['Asiakkaat'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Asiakkaat the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Asiakkaat::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Asiakkaat $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='asiakkaat-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


    	protected function asiakasMuutosTheme($as)
	{ 
		$return = '';

		    $a = Asiakkaat::model()->findbypk($as);
		    if(isset($a->yrityksen_nimi) and !empty($a->yrityksen_nimi))
		    $return = $a->yrityksen_nimi;
		    elseif(isset($a->yhteyshenkilo) and empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
		    $return = $a->yhteyshenkilo;
		    else
		    $return = $as;

		    if(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'henkilo')
		    $return = '<b class="text-warning">Yhteyshenkilö</b><br>'.$return;
		    elseif(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'yritys')
		    $return = '<b class="text-success">Yritys</b><br>'.$return;

            	return $return;
	}

	protected function tas($tasnro)
	{
		if(isset(Yii::app()->user->adminPaketti))
		$tas = explode(",",Yii::app()->user->adminPaketti);
		if(isset(Yii::app()->user->adminID) and in_array($tasnro,$tas))
		return true;
		else
		return false;
	}



	protected function alennuskooditCRM($model, $from, $to)
	{

		$criteria=new CDbCriteria;
		$criteria->order = " DATE(time) DESC ";
		$criteria->condition = " id='".$model->id."' AND alennuskoodit!='' ";
		$asiakkaat = Asiakkaat::model()->find($criteria);
		$bod = '';
		if(isset($asiakkaat->id))
		{

			if(is_array(json_decode($asiakkaat->alennuskoodit, true)))
			{
				foreach(json_decode($asiakkaat->alennuskoodit, true) as $k=>$v)
				  foreach($v as $k1=>$v1)
					$bod .= '<div class="alert bg-warning"><center>'.$v1.'</center></div>';
			}
		}

		return $bod;
	}


	protected function tarjouksetCRM($model, $from, $to)
	{

		$criteria=new CDbCriteria;
		$criteria->order = " DATE(time) DESC ";
		$criteria->condition = " asiakas_id='".$model->id."' ";

		if(!empty($from) and !empty($to))
		{
		$criteria->addCondition (" 
			DATE(time) BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
		");
		}

		$tar = CrmTarjoukset::model()->findAll($criteria);
		$bod = '';

		if(isset($tar[0])){

		$bod .= '<table class="table table-bordered">
		 <tr>
		  <th>'.Yii::t('main', 'Päiväys').'</th>
		  <th>'.Yii::t('main', 'Tiedostot').'</th>
		  <th>'.Yii::t('main', 'Tila').'</th>
		 </tr>';
	

		foreach($tar as $data)
		{
	
			$f = '';
			if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".docx"))
		 	$f .= '<a href="../../tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$data->liite.'.docx">'.$data->liite.'.docx</a>';
			$f .= '<br>';
			if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf"))
			$f .= '<a href="../../tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$data->liite.'.pdf">'.$data->liite.'.pdf</a>';


			$s = '';
			if($data->status == 0 and
	  		(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf"))
			)
			{
				$s .= '<button class="btn btn-primary btn-block laheta" for="'.$data->id.'">'.Yii::t('main', 'odotta lähetystä').'</button>';
			} elseif($data->status == 1){
				$s .= '<button class="btn btn-warning btn-block">'.Yii::t('main', 'Lähetetty').'</button>';
			} elseif($data->status == 2){
				$s .= '<button class="btn btn-success btn-block">'.Yii::t('main', 'Hyväksytty').'</button>';
			} elseif($data->status == 3){
				$s .= '<button class="btn btn-danger btn-block">'.Yii::t('main', 'Hylätty').'</button>';
			}

	  	$bod .= '
		<tr>
			<td>'.date("d.m.Y", strtotime($data->time)).'</td>
			<td>'.$f.'</td>
			<td>'.$s.'</td>
		</tr>';
	  	}


		$bod .= '</table>';
		}
	
		return $bod;
	}


	protected function laskutuksetCRM($model, $from, $to)
	{

	   $lasku = Yii::app()->createController('Lasku');
	   

		$criteria=new CDbCriteria;
		$criteria->order = " DATE(paivays) DESC ";
		$criteria->condition = " as_nro='".$model->asiakasnumero."' ";

		if(!empty($from) and !empty($to))
		{
		$criteria->addCondition (" 
			paivays BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
		");
		}


		$tar = Lasku::model()->findAll($criteria);
		$bod = '';

		if(isset($tar[0])){
	
			foreach($tar as $data)
			{
		  		$bod .= '
				<table class="table table-bordered">
				<tr><td colspan="2"><h3>'.date("d.m.Y", strtotime($data->paivays)).', '.CHtml::button('PDF', array('class'=>'btn btn-primary myBgColors getLaskuPDF','id'=>$data->id)).'</h3></td></tr>
				<tr><th>'.Yii::t('main', 'Tilanne').'</th><td>'.$lasku[0]->tilanneCheck($data).'</td></tr>
				<tr><th>'.Yii::t('main', 'Yhteensä').'</th><td>'.number_format((int)$data->yhteensa_total, 2, ",", " ").'</td></tr>
				</table>
				<br>';
		  	}
//'.CHtml::link('PDF', array('//lasku/lasku_pdf', 'id'=>$data->id), array('target'=>'_blank')).'
		}
	
		if(empty($bod))
		return Yii::t('main', 'Ei tuloksia');
		else
		return $bod;
	}

	protected function toteutuneetTunnitArray($model)
	{

		$dataArr = array();

		// <-- luetut
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND kohdenID IN
			(
				SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN(SELECT id FROM asiakkaat WHERE id='".$model->id."')
			) 
			AND loppui!=''
		";
		$m = Mobile::model()->findAll($criteria);


		foreach($m as $data)
		{
		$kesto = strtotime($data->loppui)-strtotime($data->aloitan);
	  	$dataArr[strtotime($data->aloitan)] = array(
			'pvm'=>date("d.m.Y", strtotime($data->aloitan)), 
			'kohde_kannasta'=>$data->kohde_kannasta,
			'aloitus'=>date("H:i", strtotime($data->aloitan)),
			'lopetus'=>date("H:i", strtotime($data->loppui)),
			'kesto'=>$this->sprint($kesto)
		);
	  	}
		//  luetut -->


		// <-- toteutuneet
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND kohdenID IN
			(
				SELECT id FROM sivex_kohdet

				WHERE asiakas_id IN(SELECT id FROM asiakkaat WHERE id='".$model->id."')
			) 
			AND loppui!=''
		";
		$m = Toteutuneet::model()->findAll($criteria);

		foreach($m as $data)
		{
		$kesto = strtotime($data->loppui)-strtotime($data->aloitan);
	  	$dataArr[strtotime($data->aloitan)] = array(
			'pvm'=>date("d.m.Y", strtotime($data->aloitan)), 
			'kohde_kannasta'=>$data->kohde_kannasta,
			'aloitus'=>date("H:i", strtotime($data->aloitan)),
			'lopetus'=>date("H:i", strtotime($data->loppui)),
			'kesto'=>$this->sprint($kesto)
		);
	  	}
		//  toteutuneet -->

		ksort($dataArr);


		return $dataArr;
	}

	protected function toteutuneetTunnitCRM($model, $from, $to)
	{
		$bod = '<div>';

		$dataArr = array();

		// <-- luetut
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND kohdenID IN
			(
				SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN(SELECT id FROM asiakkaat WHERE id='".$model->id."')
			) 
			AND loppui!=''
		";

		if(!empty($from) and !empty($to))
		{
		$criteria->addCondition (" 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
		");
		}

		$m = Mobile::model()->findAll($criteria);


		foreach($m as $data)
		{
		$kesto = strtotime($data->loppui)-strtotime($data->aloitan);
	  	$dataArr[strtotime($data->aloitan)] = '
		<table class="table table-bordered">
		  <tr><td colspan="2"><h3>'.date("d.m.Y", strtotime($data->aloitan)).', '.$data->kohde_kannasta.'</h3></td></tr>
		  <tr><td width="50%">'.Yii::t('main', 'Aloitus').'</td> <td>'.date("H:i", strtotime($data->aloitan)).'</td></tr>
		  <tr><td width="50%">'.Yii::t('main', 'Lopetus').'</td> <td>'.date("H:i", strtotime($data->loppui)).'</td></tr>
		  <tr><td width="50%">'.Yii::t('main', 'Kesto').'</td> <td>'.$this->sprint($kesto).'</td></tr>
		</table>
		<br>
		';
	  	}
		//  luetut -->


		// <-- toteutuneet
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND kohdenID IN
			(
				SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN(SELECT id FROM asiakkaat WHERE id='".$model->id."')
			) 
			AND loppui!=''
		";

		if(!empty($from) and !empty($to))
		{
		$criteria->addCondition (" 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
		");
		}

		$m = Toteutuneet::model()->findAll($criteria);

		foreach($m as $data)
		{
		$kesto = strtotime($data->loppui)-strtotime($data->aloitan);
	  	$dataArr[strtotime($data->aloitan)] = '
		<table class="table table-bordered">
		  <tr><td colspan="2"><h3>'.date("d.m.Y", strtotime($data->aloitan)).', '.$data->kohde_kannasta.'</h3></td></tr>
		  <tr><td width="50%">'.Yii::t('main', 'Aloitus').'</td> <td>'.date("H:i", strtotime($data->aloitan)).'</td></tr>
		  <tr><td width="50%">'.Yii::t('main', 'Lopetus').'</td> <td>'.date("H:i", strtotime($data->loppui)).'</td></tr>
		  <tr><td width="50%">'.Yii::t('main', 'Kesto').'</td> <td>'.$this->sprint($kesto).'</td></tr>
		</table>
		<br>
		';
	  	}
		//  toteutuneet -->

		ksort($dataArr);

		foreach($dataArr as $data)
		{
	  	$bod .= $data;
	  	}

		$bod .= '</div>';
	
		return $bod;
	}


	protected function tyovuorotCRM($model, $from, $to)
	{

		$criteria=new CDbCriteria;
		$criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
		$criteria->condition = " 
			kohde IN
			(
				SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN(SELECT id FROM asiakkaat WHERE id='".$model->id."')
			) 
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
		";

		if(!empty($from) and !empty($to))
		{
		$criteria->addCondition (" 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
		");
		}

		$tar = Tyovuoroot::model()->findAll($criteria);
		$bod = '';

		if(isset($tar[0])){

	
			foreach($tar as $data)
			{
				$kesto = strtotime($data->loppu)-strtotime($data->alku);
				$k = Kohteet::model()->findbypk($data->kohde);
				if(isset($k->id)) $osoite = $k->osoite; else $osoite = '';
				$tt = Tyontekijat::model()->findbypk($data->tid);
				if(isset($tt->id)) $tekijan_nimi = $this->etuSukunimi($tt->id); else $tekijan_nimi = '';
			  	$bod .= '
				<table class="table table-bordered">
					<tr><td colspan="2"><h3>'.date("d.m.Y", strtotime($data->pvm)).', '.$osoite.'</h3></td></tr>
					<td>'.Yii::t('main', 'Työntekijä').'</td><td>'.$tekijan_nimi.'</td></tr>
					<td>'.Yii::t('main', 'Aloitus').'</td><td>'.$data->alku.'</td></tr>
					<td>'.Yii::t('main', 'Lopetus').'</td><td>'.$data->loppu.'</td></tr>
					<td>'.Yii::t('main', 'Kesto').'</td><td>'.$this->sprint($kesto).'</td></tr>
					<td>'.Yii::t('main', 'Tietoja').'</td><td>'.$data->tietoja.'</td></tr>';

				if($data->peruutettu ==1)
			  	$bod .= '<td></td><td><span class="text-danger">'.Yii::t('main', 'Peruutettu').'</span></td></tr>';
				elseif($data->peruutettu ==2)
			  	$bod .= '<td></td><td><span class="text-danger">'.Yii::t('main', 'Peruutettu laskutettava').'</span></td></tr>';
				else
			  	$bod .= '<td></td><td><button class="btn btn-danger peruuttaa" for="'.$data->id.'">'.Yii::t('main', 'Peruuta').'</button></td></tr>';
			  	$bod .= '
				</table><br>';
		  	}

		}
		if(empty($bod))
		return Yii::t('main', 'Ei tuloksia');
		else
		return $bod;
	}



	protected function palautteetCRM($model, $from, $to, $naytaId, $kayttaja)
	{


		$criteria=new CDbCriteria;
		$criteria->order = " DATE(time) DESC, id DESC ";
		$criteria->condition = "
			asiakas_id='".$model->id."' 
			AND keskustelu_id=id
		";

		if(!empty($from) and !empty($to))
		{
		$criteria->addCondition (" 
			DATE(time) BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
		");
		}

		if($naytaId != 0)
		{
		$criteria->addCondition (" 
			id='".$naytaId."'
		");
		}

		$p = Palautteet::model()->findAll($criteria);

		$bod = '';

		if(isset($p[0])){

	
		foreach($p as $data)
		{


		$bod .= '<center>';
		if($kayttaja == 'admin' and file_exists( Yii::app()->basePath.'/../lib/img/emoji/'.$data->emoji_tila.'.png' ))
		$bod .= '<p><img src="../../lib/img/emoji/'.$data->emoji_tila.'.png" height="100"></p>';

		$emoji_img = '';
		if($kayttaja == 'asiakas')
		$emoji_img = '<img src="img/emoji/'.$data->emoji_tila.'.png" height="50" style="float:left;margin-right:10px">';

		$bod .= '</center>';

		$bod .= '
		<div class="row">
		 <div class="col-xs-12">
			'.$emoji_img.' 
			<h3 style="line-height:30%"><b>'.$data->otsikko.'</b></h3>
			<p><small>'.date("d.m.Y", strtotime($data->time)).'</small></p>
		 </div>
		</div>';


		if($kayttaja == 'admin' and $data->status != 3)
		{
		$bod .= CHtml::link('Sulje', '#', array(
		'submit'=>array('update', "suljeJuttelu"=>$data->keskustelu_id, "id"=>$data->asiakas_id), 
		'class'=>'btn btn-danger btn-sm btn-block'
		));
		}


		$bod .= '<p>'.$data->teksti.'</p>';

		$criteria=new CDbCriteria;
		$criteria->order = " DATE(time) DESC ";
		$criteria->condition = "
			asiakas_id='".$model->id."' 
			AND keskustelu_id='".$data->keskustelu_id."'
			AND keskustelu_id!=id
		";
		$p_juttelu = Palautteet::model()->findAll($criteria);

		   foreach($p_juttelu as $data2)
		   {
			$bod .= '<p>'.$data2->teksti.'</p>';
		   }

		if($data->status == 0)
		{

			$bod .= '<form action="#" class="palautteet-form-vastaus" method="POST">'; 
			$bod .= '<input type="hidden" name="palaute_id" value="'.$data->id.'" class="form-control">';
			$bod .= '<input type="hidden" name="PalautteetVastaus[keskustelu_id]" value="'.$data->keskustelu_id.'" class="form-control">';
			$bod .= '<input type="hidden" name="PalautteetVastaus[lahettaja]" value="'.$kayttaja.'">';
			$bod .= '<textarea name="PalautteetVastaus[teksti]" class="form-control"></textarea>';
			$bod .= CHtml::submitButton('Lähetä vastaus',array('class'=>'btn btn-primary  btn-block myBgColors submitButton'));
			$bod .= '</form>'; 


		//$bod .= CHtml::link(Yii::t('main', 'Vasta'), Yii::app()->request->baseUrl.'/index.php/palautteet/vastaus?id='.$data->keskustelu_id,array('class'=>'btn btn-primary btn-sm'));
		}


		$bod .= '<br>';
		if($data->status == 0)
	  	$bod .= '<span class="btn btn-sm btn-success btn-block">'.Yii::t('main', 'avoin').'</span>';
		elseif($data->status == 3)
	  	$bod .= '<span class="btn btn-sm btn-warning btn-block">'.Yii::t('main', 'suljettu').'</span>';

		$bod .= '<hr>';
	  	}



		}
	

		if(empty($bod))
		return 'Ei tuloksia';
		else
		return $bod;
	}

	public function actionSend_vastaus()
	{
		$return = $this->palautteetVastaus($_POST);
		echo json_encode($return);
	}


	public function palautteetVastaus($post)
	{
		//Yii::app()->theme = 'customer';
		$model = new Palautteet;
		//print_r($post);

		if(isset($post['PalautteetVastaus']))
		{
			$is_sisainen = '';
			if(isset($post['sisainen']))
			$is_sisainen = 'sisainen';

			$p = Palautteet::model()->findbypk($post['palaute_id']);

			$model->attributes=$post['PalautteetVastaus'];
			$model->otsikko=$p->otsikko;
			$model->asiakas_id=$p->asiakas_id;


			$nimi = '';
			$as = Asiakkaat::model()->findbypk($p->asiakas_id);
			$firma = FirmanTiedot::model()->findbypk(1);

			if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi))
			$nimi = $as->yrityksen_nimi;
			elseif(isset($as->yhteyshenkilo) and !empty($as->yhteyshenkilo))
			$nimi = $as->yhteyshenkilo;

			if(isset(Yii::app()->user->asiakas))
				$model->teksti = '<div class="'.$is_sisainen.'"><b>'.$nimi.'</b>: '.$model->teksti.'<br><div class="aika">'.date('d.m.Y H:i').'</div></div>';
			elseif(isset(Yii::app()->user->nimi))
				$model->teksti = '<div class="'.$is_sisainen.'"><b>'.Yii::app()->user->nimi.'</b>: '.$model->teksti.'<br><div class="aika">'.date('d.m.Y H:i').'</div></div>';

			if($model->save())
			{


				$ft = FirmanTiedot::model()->findbypk(1);

				$message = Yii::t('main', 'Asiakas').': '.$nimi.'<br>';
				$message .= Yii::t('main', 'Keskustelu ID:').': '.$model->keskustelu_id.'<br>';
				$message .= Yii::t('main', 'Palaute:').': '.$model->teksti;

				if(isset($ft->sahkoposti) and !empty($ft->sahkoposti))
				{
				$subject = Yii::t('main', 'Palaute'). ': '.$nimi;
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($ft->sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $ft->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				}

				if(isset($as->sahkoposti) and !empty($as->sahkoposti))
				{
				$subject = Yii::t('main', 'Palaute'). ': '.$nimi;
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($as->sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $as->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				}

				//$this->redirect(array('lahetetty','asiakas_id'=>$as->id));

			}
		}

	}

	protected function vinkitCRM($model, $from, $to)
	{

		$criteria=new CDbCriteria;
		$criteria->order = " DATE(time) DESC ";
		$criteria->condition = "
			asiakas_id='".$model->id."' 
			AND token=''
		";

		if(!empty($from) and !empty($to))
		{
		$criteria->addCondition (" 
			DATE(time) BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
		");
		}
		$p = VinkkiExtranet::model()->findAll($criteria);

		$bod = '';

		if(isset($p[0])){

		$bod .= '<table class="table table-bordered">

		 <tr>
		  <th>'.Yii::t('main', 'Päiväys').'</th>
		  <th>'.Yii::t('main', 'Nimi').'</th>
		  <th>'.Yii::t('main', 'Teksti').'</th>
		  <th>'.Yii::t('main', 'Tila').'</th>
		 </tr>';
	
		foreach($p as $data)
		{
	  	$bod .= '
		<tr>

		<td>'.date("d.m.Y", strtotime($data->time)).'</td>
		<td>'.$data->nimi.'</td>
		<td>'.$data->teksti.'</td>';

	  	$bod .= '<td>'.$this->VinkitilaMuutos($data->tila).'</td>';
		$bod .= '</tr>';
	  	}
		$bod .= '</table>';
		}
	
		if(empty($bod))
		return 'Ei tuloksia';
		else
		return $bod;
	}


	protected function VinkitilaMuutos($tila)
	{
		if($tila == 0)
		{
			$r = '<span class="btn btn-sm btn-warning btn-block">'.Yii::t('main', 'Avoin').'</span>';
		} elseif($tila == 1) {
			$r = '<span class="btn btn-sm btn-success btn-block">'.Yii::t('main', 'Hoidettu').'</span>';
		} elseif($tila == 2) {
			$r = '<span class="btn btn-sm btn-success btn-block">'.Yii::t('main', 'Asiakas').'</span>';
		}
		return $r;
	}


	protected function VinkiTahdet($asiakas_id)
	{
		  $criteria=new CDbCriteria;
		  $criteria->condition = "
				asiakas_id='".$asiakas_id."' 
				AND (tila=1 OR tila=2)
		  ";
		  $vi = VinkkiExtranet::model()->findAll($criteria);
		  if(isset($vi[0]))
		  {
		  echo '
		  <div class="row p10">
		    <div class="form-inline">';
		     foreach($vi as $vinki)
		     {
			echo '<div class="form-group"><i class="fa fa-star" aria-hidden="true" style="color:orange; font-size: 190%"></i></div>&nbsp;';
		     }
		  echo '
		   </div>
		  </div>';
		  }
	}


	protected function ryhmaMuutos($ryhma)
	{
		$return = '';

		$return = '';
		$ryhma = json_decode($ryhma);

		if(is_array($ryhma) and count($ryhma) > 0)
		{
		   foreach($ryhma as $k=>$v)
		   {
			$model = Valikkoot::model()->findbypk($v);
			if(isset($model->id))
			$return .= $model->value.'<br>';
		   }

		} else {

			$model = Valikkoot::model()->findbypk($ryhma);
			if(isset($model->id))
			$return = $model->value;

		}


		return $return;
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

	protected function generatePassword($length = 8) {
	    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $count = mb_strlen($chars);
	
	    for ($i = 0, $result = ''; $i < $length; $i++) {
	        $index = rand(0, $count - 1);
	        $result .= mb_substr($chars, $index, 1);
	    }
	
	    return $result;
	}

}

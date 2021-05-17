<?php

header("Access-Control-Allow-Origin: *");

/*
if( isset($_SERVER['REMOTE_ADDR']) and ($_SERVER['REMOTE_ADDR'] == '::1' or $_SERVER['REMOTE_ADDR'] == '127.0.0.1')){
	header("Access-Control-Allow-Origin: *");
}

if(isset($_SERVER['HTTP_REFERER'])) {
	$parsed = parse_url($_SERVER['HTTP_REFERER']);
	if (isset($parsed['host']) && ($parsed['host'] == 'mobemu.etunti.fi' or $parsed['host'] == 'mobemu.etunti.com' or $parsed['host'] == 'staging.etunti.com' or $parsed['host'] == 'apps.etunti.fi' or $parsed['host'] == 'app.etunti.fi')) {
		header("Access-Control-Allow-Origin: *");
	}
	//mail('laptopsr@gmail.com', 'test', json_encode($parsed));
}
*/


//echo $_SERVER['HTTP_X_USERNAME'];
//var_dump($_GET);

class ApiController extends Controller
{
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    Const APPLICATION_ID = 'ASCCPE';
 
    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    /**
     * @return array action filters
     */
    public function filters()
    {
            return array();
    }
 

public function actionLogin($dom){

    switch($_GET['model'])
    {
        case 'mob':
		$db_host = 'localhost';
		$site = Yii::app()->createController('Site');
		$conn = $site[0]->dbConnectArr();
		if( isset($conn['host']) )
			$db_host = $conn['host'];
		$return = [];
		$list = Domainit::model()->findAll(" domain!='defdb' AND aktiivinen=1 ");
		try {
			$mysqli = new mysqli($conn['host'], $conn['username'], $conn['password']);
		} catch (\Exception $e) {
			echo $e->getMessage(), PHP_EOL;
			exit;
		}
		foreach ($list as $d) {
			if ($mysqli->select_db($d->domain) === false) { continue; }
			Yii::app()->db1->setActive(false);
			Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $d->domain;
			Yii::app()->db1->setActive(true);

			$criteria = new CDbCriteria;
			$criteria->condition = " 
				aktiivinen=1
				AND salasana!=''
				AND tekijan_email='".trim(strtolower($_POST['email']))."' AND SHA2(salasana, 256)='".$_POST['salasana']."'
			";
			$t = Tyontekijat::model()->find($criteria);
			if( isset($t->id) ){
				$return = $t->attributes;
				$return['domain'] = $d->domain;
				$this->_sendResponse(200, CJSON::encode($return));
				break;
				exit;
			}
		}
		$this->_sendResponse(200, CJSON::encode(array('error' => 'Työntekijää ei löydy.')));
		exit;
        break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }
}

public function actionCheck_admin($dom)

    {

    switch($_GET['model'])
    {
        case 'mob':

	    if(isset($_POST['tunnus']) and isset($_POST['salasana']))
	    {
	   	$criteria = new CDbCriteria();
	    	$criteria->condition = " adm_login='".$_POST['tunnus']."' AND adm_salasana='".md5($_POST['salasana'])."' ";
		$model = Administrators::model()->find($criteria);
		if(isset($model->id))
       	    	 $this->_sendResponse(200, CJSON::encode('loginOk'));
		else
       	    	 $this->_sendResponse(200, CJSON::encode('loginError'));
	    }

            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }


}

public function actionAdminkalut($dom)
{

	    Yii::app()->user->setState('domain', $dom);

    switch($_GET['model'])
    {
        case 'mob':

	    Yii::app()->theme = 'etunti';

	    if(isset($_POST['luoAsiakas']))
	    {
		$model = new Asiakkaat;
		$lomake = $this->renderPartial('//asiakkaat/_form', array('model'=>$model), true);
       	    	$this->_sendResponse(200, CJSON::encode($lomake));
		exit;
	    }

	    if(isset($_POST['Asiakkaat']))
	    {
		$model = new Asiakkaat;
		$model->attributes = $_POST['Asiakkaat'];
		if($model->save())
       	    	 $this->_sendResponse(200, CJSON::encode($model->id));
		else
       	    	 $this->_sendResponse(200, CJSON::encode('saveError'));
		exit;
	    }

	    if(isset($_POST['luoKohde']))
	    {
		$model = new Kohteet;
		$lomake = $this->renderPartial('//kohteet/_form', array('model'=>$model), true);
       	    	$this->_sendResponse(200, CJSON::encode($lomake));
		exit;
	    }

	    if(isset($_POST['Kohteet']))
	    {
		$model = new Kohteet;
		$model->attributes = $_POST['Kohteet'];
		if($model->save())
       	    	 $this->_sendResponse(200, CJSON::encode($model->id));
		else
       	    	 $this->_sendResponse(200, CJSON::encode('saveError'));
		exit;
	    }


            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }


}

public function actionPaivita_tiedot($dom)
{

    switch($_GET['model'])
    {
        case 'mob':

		// <-- check domain is not empty
		if(!isset($dom) or empty($dom))
		{
        		$this->_sendResponse(200, CJSON::encode('domain error'));
			exit;
		}
		// check domain is not empty -->
		if(isset($_POST['tid']) ){
			$ttekija = Tyontekijat::model()->findByPk($_POST['tid']);
		}
		if(isset($_POST['email']) and isset($_POST['salasana'])){
			$ttekija = $this->kirjautuminen($dom, $_POST['email'], $_POST['salasana']);
		}
		if( isset($_POST['token']) and !empty($_POST['token']) and isset($ttekija->id) and $ttekija->gcm_reg_id != $_POST['token'] )
		{
			$token = $_POST['token'];
			Tyontekijat::model()->updatebypk($ttekija->id, array('gcm_reg_id'=>$token));
        		$this->_sendResponse(200, CJSON::encode('token updated'));
		} else {
        		$this->_sendResponse(200, CJSON::encode('tyontekija error'));
		}

		exit;

            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }


}

protected function kirjautuminen($domain, $email, $salasana){
	$domain = strtolower($domain);

        $kirjautumistunnus = Domainit::model()->find(" kirjautumistunnus='".$domain."' ");
	if( isset($kirjautumistunnus->domain) ){
		$domain = $kirjautumistunnus->domain;
	}
	$this->checkDBexists($domain);

	$criteria = new CDbCriteria();
	$criteria->condition = "
		aktiivinen=1 AND mobiili=1 
		AND salasana!='' 
		AND tekijan_email = '".$_POST['email']."' 
		AND salasana = '".$_POST['salasana']."' 
	";
        $ttekija = Tyontekijat::model()->find($criteria);
	if(!isset($ttekija->id)){
		$this->_sendResponse(200, CJSON::encode(array('error' => 'Työntekijää ei löydy.')));
		die(json_encode("Kirjautuminen ei onnistui."));
	}
	return $ttekija;
}

public function actionAsetukset($dom)
{
    switch($_GET['model'])
    {
        case 'mob':
	if(isset($_POST['tid']) ){
		$ttekija = Tyontekijat::model()->findByPk($_POST['tid']);
	}
	if(isset($_POST['email']) and isset($_POST['salasana'])){
       		$ttekija = $this->kirjautuminen($dom, $_POST['email'], $_POST['salasana']);
	}
    	if(!isset($ttekija->id))
	{
		$this->_sendResponse(200, CJSON::encode(array("error" => "Työntekijää ei löydy")));
		exit;
	}
	$asetukset = Asetukset::model()->findbypk(1);
	$app_naytta_osoitekenta = 'no';
	if( $asetukset->app_auto_hyvaksyminen == 0 and $ttekija->app_naytta_osoitekenta == 1 and $asetukset->app_naytta_osoitekenta == 1 ){
	$app_naytta_osoitekenta = 'yes';
	}
	$app_asetukset = array(
		'app_naytta_osoitekenta' => $app_naytta_osoitekenta,
	);
        $this->_sendResponse(200, CJSON::encode($app_asetukset));
	exit;
        break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }
}


public function actionLang($dom)
{

    switch($_GET['model'])
    {
        case 'mob':

		$lang = array();

	    if(isset($_POST['lang']))
	    {

		if( isset($_POST['tid']) ){
			$ttekija = Tyontekijat::model()->findByPk($_POST['tid']);
			if( isset($ttekija->id) ){
				Tyontekijat::model()->updateByPk($ttekija->id, ['app_lang' => $_POST['lang']]);
			}
		}

	  	$_SESSION['lang'] = $_POST['lang'];
		Yii::app()->language = $_POST['lang'];
		$lang = array(

		/* Index */		
		'TYO' => Yii::t('app', 'TYÖ'),
		'MATKA' => Yii::t('app', 'MATKA'),
		'LOUNAS' => Yii::t('app', 'LOUNASTAUKO'),
		'ALOITA' => Yii::t('app', 'ALOITA'),
		'LOPETA' => Yii::t('app', 'LOPETA'),
		'osoite' => Yii::t('app', 'Osoite'),
		'lyhyt_viesti' => Yii::t('app', 'lyhyt_viesti'),
		'kirjaudu' => Yii::t('app', 'Lähetä tunnukset'),
		'kirjaudu_sisaan' => Yii::t('app', 'Kirjaudu sisään'),

		/* Viestinta */
		'LangViestinta' => Yii::t('app', 'Viestintä'),
		'olenEksynyt' => Yii::t('app', 'Lähetä GPS tiedot'),
		'LangUusiViesti' => Yii::t('app', 'Uusi viesti'),
		'LangViesti' => Yii::t('app', 'Viesti'),
		'lahetaToimistoon' => Yii::t('app', 'Lähetä viesti toimistoon'),

		/* Asetukset */
		'domain' => Yii::t('app', 'Yritystunnus'),
		'server' => Yii::t('app', 'Palvelin'),
		'tyontekijan_sahkoposti' => Yii::t('app', 'Työntekijän sähköposti'),
		'tyontekijan_salasana' => Yii::t('app', 'Työntekijän salasana'),
		'tallenna' => Yii::t('app', 'Tallenna'),
		'Valitse_kieli' => Yii::t('app', 'Valitse kieli'),
		'Valitse_fonttikoko' => Yii::t('app', 'Valitse fonttikoko'),
		'paaAsetukset' => Yii::t('app', 'Pääasetukset'),
		'MuutAsetukset' => Yii::t('app', 'Muut asetukset'),
		'tallennaKieli' => Yii::t('app', 'Tallenna'),
		'henkilokortti' => Yii::t('app', 'Työntekijän Henkilökortti'),

		/* Kamera */
		'LangKuvienLahettaminen' => Yii::t('app', 'Kuvien lähettäminen'),
		'Valitse_osoite' => Yii::t('app', 'Valitse osoite'),
		'fromCamera' => Yii::t('app', 'Ota kuva kameralla'),
		'fromLibrary' => Yii::t('app', 'Tuo kuva kirjastosta'),
		'fromAlbum' => Yii::t('app', 'Tuo kuva galleriasta'),

		/* Etunti admin */
		'admin_login' => Yii::t('app', 'Järjestelmänvalvoja tunnus'),
		'admin_password' => Yii::t('app', 'Järjestelmänvalvoja salasana'),

		);
	    }

        	$this->_sendResponse(200, CJSON::encode($lang));
		exit;


            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }
}

public function actionTiedosto($dom)

    {

    switch($_GET['model'])
    {
        case 'mob':

		// <-- check domain is not empty
		if(!isset($dom) or empty($dom))
		{
        		$this->_sendResponse(200, CJSON::encode('domain error'));
			exit;
		}
		// check domain is not empty -->

		$dom = strtolower($dom);

	  	if (!file_exists(Yii::app()->basePath."/../img/uploadedfromphone/".$dom)) {
		  	mkdir(Yii::app()->basePath."/../img/uploadedfromphone/".$dom, 0777, true);
		}

		if(isset($_POST['tid']) ){
			$ttekija = Tyontekijat::model()->findByPk($_POST['tid']);
		}
		if(isset($_POST['email']) and isset($_POST['salasana']) ){
			$ttekija = $this->kirjautuminen($dom, $_POST['email'], $_POST['salasana']);
		}

	  	if(isset($ttekija->id)){
		$gen_random = rand(5, 1000);
		$tiedosto = $_POST['kohdenID']."_".$ttekija->id."_".date("YmdHi")."_".$gen_random.".jpg";
    	 	if (move_uploaded_file($_FILES['file']['tmp_name'], Yii::app()->basePath."/../img/uploadedfromphone/".$dom."/".$tiedosto)) 
		{

		$k = Kohteet::model()->findbypk($_POST['kohdenID']);

		if(isset($k->id))
		{

		$kuva = new KuviaKohteesta;
		$kuva->kohde_id=$_POST['kohdenID'];
		$kuva->osoite=$k->osoite;
		$kuva->tid=$ttekija->id;
		$kuva->tekijan_nimi=$this->etuSukunimi($ttekija->id);
		$kuva->tiedosto=$tiedosto;
		if(isset($_POST['kuvaus']))
		$kuva->kuvaus=$_POST['kuvaus'];
		$kuva->save();


		$firma = FirmanTiedot::model()->findbypk(1);
		$asetukset = Asetukset::model()->findbypk(1);
		if(
			isset($firma->sahkoposti) and !empty($firma->sahkoposti)
			and ( !isset($_POST['emailNotify']) or (isset($_POST['emailNotify']) and $_POST['emailNotify'] == 'true') )
		)
		{
			
			if(!empty($asetukset->ilmoitus_uudesta_kuvasta_saajat))
			{

				$emailArray = explode("\n", $asetukset->ilmoitus_uudesta_kuvasta_saajat);

				if(!is_array($emailArray)) {
					$emailArray = [$asetukset->ilmoitus_uudesta_kuvasta_saajat]; 
				}

				$kuvienmaara = 1;
				if(isset($_POST['kuvienMaara']))
					$kuvienmaara = $_POST['kuvienMaara'];

				$message = Yii::t('main', 'Hei. '.$kuvienmaara.' kpl. valokuva(a) on saapunut kohteista: ').$k->osoite;
				$headers = "From:  no-reply@etunti.fi";
				$subject = Yii::t('main', 'Uusi valokuva kohteista. Lähettäjä: '). ' '.$this->etuSukunimi($ttekija->id);

				$mail = new YiiMailer();
				$mail->setForm("no-reply@etunti.fi");
				$mail->setTo($emailArray);
				$mail->setSubject($subject);
				$mail->setBody($message);

				if($mail->send()) {
					// <-- LOG
					$log=new Log;
					$log->log_category 	= 1; // 1-email
					$log->email_to 		= $emailArray;
					$log->email_subject	= $subject;
					$log->email_message	= json_encode($message);
					$log->save();
					//     LOG -->
				} else {
					// $mail->gerError() returns a string
					$errorMsg = $mail->getError();
					$errors = ["error_message" => $errorMsg, "message" => $message];
					// <-- LOG error
					$log=new Log;
					$log->log_category 	= 1; // 1-email
					$log->email_to 		= $emailArray;
					$log->email_subject	= $subject;
					$log->email_message	= json_encode($errors);
					$log->save();
					//     LOG error -->
				}

				//mail($saajat,$subject,$message,$headers);

							

			}


		}

		} //if(isset($k->id))

        	$this->_sendResponse(200, "saveOK");


    		} else {

        	$this->_sendResponse(200, "saveError");

    		}


	     	} else {

        	$this->_sendResponse(200, "Ei onnistu!");

	     	}


            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }


}


protected function checkKokeiluversion($domain)
{
		$sum_result = 0;
		$site = Yii::app()->createController('Site');
		$domainit = Domainit::model()->find(" domain='".$domain."' AND maksullinen=0 ");
		Yii::app()->user->setState('ilmainen_ilmoitus', 'Ilmainen käyttö on mahdoton jos tunnit enemmään kun 500');
		$asetuksetForAll = AsetuksetForAll::model()->findbypk(1);
		if( isset($domainit->id) )
		{
			$start_date = date( "Y-m-d", strtotime('first day of this month') );
			$end_date = date("Y-m-d", strtotime('last day of this month') );
			$sum_result = $site[0]->digistenTunnitYhteensa($start_date, $end_date, 'kesto');

			if($domainit->ilmainen_versio_kayttotunnit != $sum_result)
				Domainit::model()->updateByPk($domainit->id, array('ilmainen_versio_kayttotunnit'=>$sum_result));
		}

		if($sum_result > $asetuksetForAll->max_ilmaiset_tunnit)
			return false;
		else
			return true;
}

public function actionImei($dom)
{

    switch($_GET['model'])
    {
        // Get an instance of the respective model
        case 'mob':

		$domainit = Domainit::model()->find(" domain='".strtolower($dom)."' ");
		if(isset($domainit->huoltokatko) and $domainit->huoltokatko == 1)
		{
			$return = ['error' => 'Huoltokatko'];
        		$this->_sendResponse(200, CJSON::encode($return));
			exit;
		}

		// <-- kokeiluversion
		if(!$this->checkKokeiluversion($dom))
		{
			$site = Yii::app()->createController('Site');
			$return = ['error' => $site[0]->ilmainenIlmoitus()];
        		$this->_sendResponse(200, CJSON::encode($return));
			exit;
		}
		//     kokeiluversion -->

		// <-- check domain is not empty
		if(!isset($dom) or empty($dom))
		{
        		$this->_sendResponse(200, CJSON::encode('domain error'));
			exit;
		}
		// check domain is not empty -->


	function sprint($val)
	{
		if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}


	//if(isset($_POST['lang']))
	//$_SESSION['lang'] = $_POST['lang'];

	(isset($_POST['versio']))? $versio = $_POST['versio']: $versio = '';
	(isset($_POST['app_platform']))? $platform = $_POST['app_platform']: $platform = '';
	(isset($_POST['newlogin']))? $new_login = true: $new_login = false;
	if(isset($_POST['newlogin'])){ unset($_POST['newlogin']); }
	if(isset($_POST['avoinID'])) $avoinID = $_POST['avoinID']; else $avoinID = 0;
	if(isset($_POST['avoinID'])){ unset($_POST['avoinID']); }
	if(isset($_POST['appVersio'])) $appVersio = $_POST['appVersio']; else $appVersio = 0;
	if(isset($_POST['appVersio'])){ unset($_POST['appVersio']); }
	if(isset($_POST['tv_id'])) $post_tv_id = $_POST['tv_id']; else $post_tv_id = 0;


	// <-- Check Tyontekija
	if(isset($_POST['tid']) ){
		$ttekija = Tyontekijat::model()->findByPk($_POST['tid']);
	}
	if(isset($_POST['email']) and isset($_POST['salasana']) ){
		$ttekija = $this->kirjautuminen($dom, $_POST['email'], $_POST['salasana']);
	}
    	if(!isset($ttekija->id)){
		$this->_sendResponse(200, CJSON::encode(array("error" => "Työntekijää ei löydy.")));
		exit;
	}
    	if(isset($ttekija->id) and $ttekija->mobiili == 0){
		$this->_sendResponse(200, CJSON::encode(array("error" => "Ei oikeuksia mobiilisovellukseen.")));
		exit;
	}
	//     Check Tyontekija -->

	// <-- Kieli / Lang
	Yii::app()->language = $ttekija->app_lang;

	$my_location = (isset($_POST['my_location']))?str_replace("/",",",$_POST['my_location']):'';
	$asetukset = Asetukset::model()->findbypk(1);
	$asetuksetForAll = AsetuksetForAll::model()->findByPk(1);
	$get_osoite 		= '';
	$kohdenID 		= 0;
	$list_tyovuorosta 	= '';

	if(isset($_POST['check'])){

		// <-- CHECK sendLocation, versio, platform
		if($_POST['check'] == 'sendLocation'){
			Tyontekijat::model()->updatebypk($ttekija->id, array('position'=>$_POST['my_location']."//".date("d.m.Y H:i")));
			// <-- Platform updater
			if(!empty($platform))
				Tyontekijat::model()->updatebypk($ttekija->id, array('app_platform' => $_POST['app_platform']));
			if( $new_login ){
				$ilmoitus_kaikkille = '';
				if( 
					(time() < strtotime($asetuksetForAll->app_ilmoitus_voimassa_asti)) 
					and !empty($asetuksetForAll->app_ilmoitus_kaikkille) 
					and is_array(json_decode($asetuksetForAll->app_ilmoitus_vastaanottajat))
					and $asetuksetForAll->app_ilmoitus_versio_eisamakun != $versio
				){
					$app_ilmoitus_vastaanottajat = json_decode($asetuksetForAll->app_ilmoitus_vastaanottajat);
					// Tästä saa informoida esimerkiksi uudesta versiotsta
					// $platform, $versio - ovat valmina tässä vaihessa
					if( in_array(strtolower($dom), $app_ilmoitus_vastaanottajat) )
						$ilmoitus_kaikkille = '<div class="alert alert-warning">'.str_replace("/n", "<br>", $asetuksetForAll->app_ilmoitus_kaikkille).'</div>';
				}

				// <-- Version checker
				$package='fi.etunti.local';
				$html = @file_get_contents('https://play.google.com/store/apps/details?id='.$package.'&hl=en');
				preg_match_all('/<span class="htlgb"><div class="IQ1z0d"><span class="htlgb">(.*?)<\/span><\/div><\/span>/s', $html, $output);
				if( isset($ttekija->app_platform) and $ttekija->app_platform == 'Android' and isset($output[1][3]) and $versio != $output[1][3]){
					$ilmoitus_kaikkille .=  '<p><a class="btn btn-warning btn-block" href="market://details?id=fi.etunti.local">'.Yii::t('app', 'Päivitä sovellus').'</a></p>';
				}
				//     Version checker -->

				if( $dom == 'demo' ){
					//$ilmoitus_kaikkille .=  '<p><a class="btn btn-warning btn-block" href="market://details?id=fi.etunti.local">'.Yii::t('app', 'Päivitä sovellus').'</a></p>';
				}

				$return = [
					"tid" => $ttekija->id,
					"ilmoitus_kaikkille" => $ilmoitus_kaikkille,
					"platform" => $platform
				];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, $ttekija->id."//".date("d.m.Y H:i")."//".$_POST['my_location']);
			}
			exit;
	        }
		//     CHECK sendLocation -->

		// <-- CHECK getObjbyTag
		if($_POST['check'] == 'getObjbyTag'){
			if(isset($_POST['tag']) and $_POST['tag'] != '000000'){
				$kohteet = Kohteet::model()->find(" tag_id='".$_POST['tag']."' ");
				if(isset($kohteet->osoite) and !empty($kohteet->osoite)){
					$get_osoite = $kohteet->osoite;
					$kohdenID = $kohteet->id;

					$tv_id = 0;
					$site = Yii::app()->createController('Site');
					$eilasketa = $site[0]->eiLasketa();
					$criteria = new CDbCriteria();
					$criteria->condition = " 
						tid = '".$ttekija->id."' AND kohde='".$kohteet->id."'
					AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) = CURDATE() 
					AND $eilasketa
					AND piilota_mobiilista!=1
					AND (peruutettu=0 OR peruutettu IS NULL)
					";
					$tv = Tyovuoroot::model()->find($criteria);
					if( isset($tv->id) ){ $tv_id = $tv->id; }

					if( $new_login ){
						$return = ["osoite" => $get_osoite, "kohdenID" => $kohdenID, "is_ok" => "true", "tv_id" => $tv_id];
						$this->_sendResponse(200, CJSON::encode($return));
					} else {
						$this->_sendResponse(200, $get_osoite."//".$kohdenID."//ok//".$tv_id);
					}
				} else {
					if( $new_login ){
						$return = ["is_ok" => "false"];
						$this->_sendResponse(200, CJSON::encode($return));
					} else {
						$this->_sendResponse(200, "Tuntematon TAG//".$_POST['tag']."//error");
					}
				}
			} 
			exit;
	        }
		//     CHECK getObjbyTag -->

		// <-- CHECK henkilokortti
		if($_POST['check'] == 'henkilokortti'){
			$firma = Domainit::model()->find(" domain='".strtolower($dom)."' ");
			if( !isset($firma->id) ){
				$return = ["return" => "Domain ei löyty"];
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}
			$tyosuhdet = Tyosuhdet::model()->find(" tid='".$ttekija->id."' ");
			if( !isset($tyosuhdet->id) ){
				$return = ["return" => "Työsuhteet ei löyty"];
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}
			$body = '<h2>'.Yii::t('app', 'Henkilökortti').'</h2>';
			$body .= '<div class="well">';
			// <-- Logo
			if( !empty($asetukset->logon_polkku) ){
				$filepath = $asetukset->logon_polkku;
				$imageData = base64_encode(file_get_contents($filepath));
				$src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;
				$body .= '<legend><p class="text-center"><img src="'.$src.'" height="50px"></p></legend>';
			}
			$body .= '<div class="row"><div class="col-xs-6">';
			$body .= '<h3>'.$firma->yritys.'</h3>';
			$body .= '<p>Y-tunnus: <b>'.$firma->y_tunnus.'</b></p>';
			$body .= '<p><h4>'.$ttekija->tekijan_nimi.' '.$ttekija->sukunimi.'</h4></p>';
			$body .= '</div><div class="col-xs-6"><div class="pull-right">';
			$filepath = dirname(Yii::app()->getBasePath())."/img/tekijat/".$dom."/".$ttekija->id.".jpg";
			if (file_exists($filepath)){
				$imageData = base64_encode(file_get_contents($filepath));
				$src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;
				$body .= '<img src="'.$src.'" class="img-thumbnail" style="border: none">';
			}
			$body .= '</div></div></div>';
			$body .= '<div class="row"><div class="col-xs-12">';
			$body .= '<br><p>Veronumero: <b>'.$tyosuhdet->veronumero.'</b></p>';
			$body .= '</div></div>';
			$body .= '</div>';
			if( $new_login ){
				$return = ["return" => $body];
				$this->_sendResponse(200, CJSON::encode($return));
			}
			exit;
		}
		//     CHECK henkilokortti -->

		// <-- CHECK tehty
		if($_POST['check'] == 'tehty'){

			$criteria = new CDbCriteria();
			$criteria->order = " id DESC ";
			$criteria->condition = " 
				tid = '".$ttekija->id."' 
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
				BETWEEN '".date("Y-m-d", strtotime("-$asetukset->app_hyvaksytyt_tyot_vkomaara week"))."' AND '".date("Y-m-d")."' 
				AND loppui!=''
				AND admin!=1
			";
			$mob = Mobile::model()->findAll($criteria);

			if(empty($mob)){
				if( $new_login ){
					$return = ["return" => 'Ei tuloksia'];
					$this->_sendResponse(200, CJSON::encode($return));
				} else {
					$this->_sendResponse(200, 'ei tuloksia');
				}
				exit;
			}

			// <-- Ajaanjaksolla
			$criteria = new CDbCriteria();
			$criteria->select = "
				SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
				DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
			";
			$criteria->condition = " 
				aloitan!='' AND loppui!=''
				AND tid='".$ttekija->id."'
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
				BETWEEN '".date("Y-m-d", strtotime("-$asetukset->app_hyvaksytyt_tyot_vkomaara week"))."' AND '".date("Y-m-d")."' 
				AND (status=3 OR status=2)
				AND admin!=1
			";
			$lu = Mobile::model()->find($criteria);
			$ajaanjaksolla = '';
			if( isset($lu->l_tunnit) ){
				$ajaanjaksolla = '<h5>'.Yii::t('app', 'Tehdyt työt ajanjaksolla').' '.$this->sprint($lu->l_tunnit).'</h5>';
			}
			//    Ajaanjaksolla -->

			// <-- Tanaan
			$criteria = new CDbCriteria();
			$criteria->select = "
				SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
				DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
			";
			$criteria->condition = " 
				aloitan!='' AND loppui!=''
				AND tid='".$ttekija->id."'
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')='".date('Y-m-d')."'
				AND (status=3 OR status=2)
				AND admin!=1
			";
			$lu = Mobile::model()->find($criteria);
			$tanaan = '';
			if( isset($lu->l_tunnit) ){
				$tanaan = '<h5>Tänään yhteensä '.$this->sprint($lu->l_tunnit).'</h5>';
			}
			//    Tanaan -->

			$sel = '<center><p><h4>'.date("d.m.Y", strtotime("-$asetukset->app_hyvaksytyt_tyot_vkomaara week")).'-'.date('d.m.Y').'</h4></p>';
			$sel .= $ajaanjaksolla;
			$sel .= $tanaan;
			$sel .= '</center><hr>';
			foreach($mob as $val){
				$kesto = '00:00';
				if(!empty($val->loppui))
					$kesto = (strtotime($val->loppui)-strtotime($val->aloitan));

				$sel .= '<div class="well">
				  <b>'.Yii::t('app', 'Päivämäärä').':</b> '.date("d.m.Y",strtotime($val->aloitan)).'<br> 
				  <b>'.Yii::t('app', 'Klo').':</b> '.date("H:i",strtotime($val->aloitan)).' - '.date("H:i",strtotime($val->loppui)).'<br> 
				  <b>'.Yii::t('app', 'Osoite').':</b> '.$val->kohde_kannasta.'
				  <hr>
				  <h3>'.Yii::t('app', 'Kesto').': '.sprint($kesto).'</h3>';


				// <-- check Hyvaksytty
				if($asetukset->app_naytetaanko_hyvaksyttyt_tunnit == 1){
					$mobile = Yii::app()->createController('Mobile');
					$hyvaksytty_return = $mobile[0]->onkoRiviHyvaksytty($val->id);
					if( $hyvaksytty_return > 0 )
						$hyvaksytty = sprint($hyvaksytty_return);
					else
						$hyvaksytty = '';
					if(!empty($hyvaksytty))
						$sel .= '<h3 style="color:green">'.Yii::t('app', 'Hyväksytty').': '.$hyvaksytty.'</h3>';
				}
				// check Hyvaksytty -->

				$sel .= '</div>';
			}

			if( $new_login ){
				$return = ["return" => $sel];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, $sel);
			}
			exit;
	        }
		//     CHECK tehty -->

		// <-- CHECK getTyovuorotToday
		if( !isset($_POST['with_virtual']) and ($_POST['check'] == 'tvuoro' or $_POST['check'] == 'getTyovuorotToday')){ // ($_POST['with_virtual']) vanha versioille 0.0.635 ja alle
			$return = ["return" => '<div class="alert alert-danger"><h1>Päivitä sovellus</h1></div>'];
			$this->_sendResponse(200, CJSON::encode($return));
			exit;
	        }
		//     CHECK getTyovuorotToday -->

		// <-- CHECK getTyovuorotToday
		if( isset($_POST['with_virtual']) and $_POST['check'] == 'getTyovuorotToday'){ // ($_POST['with_virtual']) uudelle versiolle 0.0.636
			$tv_controller = Yii::app()->createController('Tyovuoroot');
			$pvm = date("d.m.Y");
			$tids = [$ttekija->id];
			$from = date("Y-m-d", strtotime($pvm));
			$to = date("Y-m-d", strtotime($pvm));
			$haku_criteria = [];
			$haku_criteria[] = " 
				piilota_mobiilista!=1
				AND (peruutettu=0 OR peruutettu IS NULL)
			";
			$dataAll = $tv_controller[0]->FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, ['data']);

			$sel = '';
			$sel .= '<select id="list" class="form-control input-lg list_tyovuorosta">';
			$sel .= '<option value=>'.Yii::t('app','Valitse kohde työvuorosta').'</option>';
			foreach($dataAll as $arr){

				if( $arr['this_tid'] != $ttekija->id )
					continue;

				$data = $arr['data'];
				if(isset($data->kohteet->asiakkaat->id) and ($data->kohteet->aktiivinen != 1 or $data->kohteet->asiakkaat->aktiivinen != 1))
					continue;

				$dosoite = $data->osoiteById;
				if( $data->status == 2 )
					$dosoite = 'MATKA';
				if( $data->status == 10 )
					$dosoite = 'Lounastauko';
				if( $arr['this_tid'] == $ttekija->id )
					$sel .= '<option value="'.(int)$data->kohde.'" id="'.$arr['this_id'].'" tv_id="'.$arr['this_id'].'" status="'.$data->status.'" alku="'.$data->alku.'" loppu="'.$data->loppu.'">'.$dosoite.'</option>';
			}
			$sel .= '</select>';

			if( $new_login ){
				$return = ["return" => $sel];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, $sel);
			}

			exit;
	        }
		//     CHECK getTyovuorotToday -->

		// <-- CHECK tvuoro
	        if( isset($_POST['with_virtual']) and $_POST['check'] == 'tvuoro' ){
			$tv_controller = Yii::app()->createController('Tyovuoroot');
			$tas = Domainit::model()->find(" domain='".$dom."' ");
			$p = array();
			if(isset($tas->paketti)){ $p = explode(",",$tas->paketti); }

			if(!in_array('2',$p, true)){
				if( $new_login ){
					$return = ["return" => 'Osta lisäosa työvuorojenhallinta'];
					$this->_sendResponse(200, CJSON::encode($return));
				} else {
					$this->_sendResponse(200, 'Osta lisäosa työvuorojenhallinta');
				}
				exit;
			} 

			if(isset($asetukset->sovellus_tyovuorot) and $asetukset->sovellus_tyovuorot == '1')
				$aikaVali = date('Y-m-d',strtotime('sunday this week'));
			elseif(isset($asetukset->sovellus_tyovuorot) and $asetukset->sovellus_tyovuorot == '2')
				$aikaVali = date('Y-m-d',strtotime('+7 day'));
			elseif(isset($asetukset->sovellus_tyovuorot) and $asetukset->sovellus_tyovuorot == '3')
				$aikaVali = date('Y-m-d',strtotime('+14 day'));
			elseif(isset($asetukset->sovellus_tyovuorot) and $asetukset->sovellus_tyovuorot == '4')
				$aikaVali = date('Y-m-d',strtotime('+30 day'));
			else
				$aikaVali = date('Y-m-d',strtotime('sunday this week'));

			$haku_criteria = [];
			$haku_criteria[] = " 
				piilota_mobiilista!=1
				AND (peruutettu=0 OR peruutettu IS NULL)
			";
			if( isset($asetukset->app_naytta_sairauslomat) and $asetukset->app_naytta_sairauslomat == 0 ){
				//$haku_criteria[] = " tyoajanlaatu NOT LIKE '%(SPL)%' AND tyoajanlaatu NOT LIKE '%(SL)%' ";
			}

			$tids = [$ttekija->id];
			$from = date("Y-m-d");
			$dataAll = $tv_controller[0]->FromToSuunnitellutAll($from, $aikaVali, $tids, $haku_criteria, ['data']);
			/*
			$this->_sendResponse(200, CJSON::encode($dataAll));
			exit;
			*/
			if(count($dataAll) == 0){
				if( $new_login ){
					$return = ["return" => 'Ei tuloksia'];
					$this->_sendResponse(200, CJSON::encode($return));
				} else {
					$this->_sendResponse(200, 'ei tuloksia');
				}
				exit;
			}

			$sel = '<h2>'.Yii::t('app', 'Työvuorot').'</h2>';

			$tyonkuvaukset = [];
			foreach($dataAll as $arr){

				if( $arr['this_tid'] != $ttekija->id )
					continue;

				$data = $arr['data'];
				if(isset($data->kohteet->asiakkaat->id) and ($data->kohteet->aktiivinen != 1 or $data->kohteet->asiakkaat->aktiivinen != 1))
					continue;

				$osoite = '';
				$kohde = Kohteet::model()->findbypk($data->kohde);
				if(isset($kohde->osoite)){
					$osoite = '';
					if(!empty($kohde->osoite))
						$osoite .= $kohde->osoite;
					if(!empty($kohde->pnumero))
						$osoite .= ', '.$kohde->pnumero;
					if(!empty(htmlspecialchars($kohde->kaupunki)))
						$osoite .= ', '.htmlspecialchars($kohde->kaupunki);
				}

				$tyopaari = array();
				if(!empty($data->tyopaari))
					$tyopaari = json_decode($data->tyopaari, true);
				$tplista = '';
				foreach($tyopaari as $tp){
					if($tp != $ttekija->id){
						$tpID = Tyontekijat::model()->findbypk($tp);
						if(isset($tpID->tekijan_nimi)){
							$tplista .= '<b>'.Yii::t('main', 'Työpari').'</b>: '.$this->etuSukunimi($tpID->id).' '.((!empty($tpID->laiten_puh))?', <b>Puh.</b>: <a href="tel:'.$tpID->laiten_puh.'">'.$tpID->laiten_puh.'</a>':'').'<br>';
						}
					}
				}
		      
				if(!empty($tplista)){ $tplista = '<hr>'.$tplista; }

				$alkLop = '';
				$alkLop = $data->alku.'-'.$data->loppu.' ';

				$color = '';
				if(!empty($data->tyoajanlaatu) and empty($osoite)){
					$expl1 = explode("/",$data->tyoajanlaatu);
					$color = (isset($expl1[1])) ? $expl1[1] : '';
					$osoite = (isset($expl1[0])) ? $expl1[0] : '';
				}
				if(!empty($data->tyoajanmerkinta)){
					$expl1 = explode("/",$data->tyoajanmerkinta);
					$color = (isset($expl1[1])) ? $expl1[1] : '';
				}

				// <-- Nayta asiakas
				$nm = '';
				if(isset($kohde->id) and isset($asetukset->show_name) and $asetukset->show_name == 1 and $kohde->asiakas_id != 0){
					$asiakas = Asiakkaat::model()->findbypk($kohde->asiakas_id);
					if(isset($asiakas->id)){
						$nm = '<b>'.Yii::t('main', 'Asiakas').':</b> '.$asiakas->Fullname;
					}
				}
				// Nayta asiakas -->

				// <-- Nayta kohteen puhelinnumero
				$puh_nro = '';
				if(isset($kohde->id) and isset($asetukset->app_show_phone) and $asetukset->app_show_phone == 1 and $kohde->puh_nro != ''){
		      			$puh_nro = '<br><b>'.Yii::t('main', 'Kohteen puhelinnumero').':</b> <a href="tel:'.$kohde->puh_nro.'">'.$kohde->puh_nro.'</a>';
				}
				// Nayta kohteen puhelinnumero -->

				// <-- Nayta kohteen avaimet
				$avainController = Yii::app()->createController('Avaimet');
				$avaimet = '';
				if(isset($kohde->id) and isset($asetukset->app_naytta_avain) and $asetukset->app_naytta_avain == 1 ){
					if(isset($kohde->avaimet) and count($kohde->avaimet) > 0){
						$avaimet .= '<br><p><center><h4>'.Yii::t('main', 'Avaimet').' '.$kohde->osoite.'</h4></center><br>';
						$avaimet .= '<table class="table table-bordered table-striped">';
						$avaimet .= '<tr>';
						$avaimet .= '<th>'.Yii::t('main', 'Avain').'</th>';
						$avaimet .= '<th>'.Yii::t('main', 'Työntekijä').'</th>';
						$avaimet .= '<th>'.Yii::t('main', 'Ovikoodi').'</th>';
						$avaimet .= '<th>'.Yii::t('main', 'Sijainti').'</th>';
						$avaimet .= '</tr>';
						foreach($kohde->avaimet as $avain){
							// I don't know why we would hide they if we're choosing the location by the dropdown
							// sijainti_omatekstti being 0 means we're choosing the location by pre-determined
							// locations. /themes/etunti/views/avaimet/index.php around line 200
							// $arr = [1 => 'Toimistolla', 2 => 'Palautettu asiakkaalle', 3 => 'Työntekijällä'];
							// these are the pre-determined locations ^
							//if($avain->sijainti_omatekstti == 0 and $avain->sijainti != 3){ continue; }
							$avaimet .= '
							<tr>
							<td>'.$avain->avainnumero.'</td>
							<td>'.$this->etuSukunimi($avain->tid).'</td>
							<td>'.$avain->ovikoodi.'</td>
							<td>'.$avainController[0]->sijaintiText($avain->id).'</td>
							</tr>';
						}
						$avaimet .= '</table>';
					}
				}
				// Nayta kohteen avaimet -->

				// <-- app_naytetaanko_kohteen_yhteyshenkilo
				$kohteen_yhteyshenkilo = '';
				if(isset($kohde->id) and isset($asetukset->app_naytetaanko_kohteen_yhteyshenkilo) and $asetukset->app_naytetaanko_kohteen_yhteyshenkilo == 1 and htmlspecialchars($kohde->etu_suku_nimet) != ''){
					$kohteen_yhteyshenkilo = '<br><b>'.Yii::t('main', 'Kohteen yhteyshenkilö').':</b> ' . htmlspecialchars($kohde->etu_suku_nimet);
				}
				// app_naytetaanko_kohteen_yhteyshenkilo -->
				$sel .= '<div class="well kohde_'.((isset($data->kohteet->id))?$data->kohteet->id:'').'">';

				$tvController = Yii::app()->createController('Tyovuoroot');
				$tilanteet = $tvController[0]->tilanteet();
				if( isset($tilanteet[$data->status]) and $tilanteet[$data->status] != "0" ){
					$sel .= '<h3 class="text-center">'. $tilanteet[$data->status].' '.(($arr['toistuva'])?'<i class="fa fa-repeat text-success"></i>':'').'</h3>';
				}
				$osoiteLink = "https://maps.google.com/?q=".urlencode($osoite); 
				if($platform and strlen($platform) > 0) {
					// ios doesn't support geo URI scheme
					// and for some reason maps: URI scheme doesn't seem to work either
					if($platform == "Android") {
						if(isset($kohde->gps_sijainti)) {
							$osoiteLink = "geo:".$kohde->gps_sijainti."?q=".$kohde->gps_sijainti;
						}
					}
				}
				
				$sel .= '<h3 class="text" style="color:'.$color.'"><a href="'.$osoiteLink.'">'.$osoite.'</a></h3><p><b>'.$this->vkopaiva($arr['this_pvm']).', '.$arr['this_pvm'].'</b>, '.Yii::t('main', 'Klo').': '.$alkLop.'</p>';

				if( isset($data->tyo_erittelyt) and is_array(json_decode($data->tyo_erittelyt, true))){
					$sel .= '<p><label>Työ-erittelyt:</label><ul>';
					foreach(json_decode($data->tyo_erittelyt, true) as $k => $v){
						$sel .= '<li>'.$v.'</li>';
					}
					$sel .= '</ul></p><hr>';
				}

				$urls = $tvController[0]->getTVUrls($data->url_linkkit);
				if( count($urls) > 0 ){
					$sel .= '<p><label>URL linkit:</label><ul>';
			 		foreach($urls as $k => $v){
						$sel .= '<li><a href="'.$v.'">'.$k.'</a></li>';
					}
					$sel .= '</ul></p><hr>';
				}

				if(!empty($nm) or !empty($puh_nro) or !empty($avaimet) or !empty($kohteen_yhteyshenkilo)){
					$sel .= '<br><p>
					'.$nm.'
					'.$kohteen_yhteyshenkilo.'
					'.$puh_nro.'
					'.$avaimet.'
					</p>';
				}

				if(!empty($data->tietoja)){
					$sel .= '<hr><div class="text-small">'.str_replace("\n", "<br>", $data->tietoja).'</div>';
				}

				$sel .= $tplista;
				$sel .= '</div>';

				if(isset($data->kohteet->id) and $data->kohteet->tyonkuvaus_tiedostot_mobiilissa == 1){
					foreach(array_reverse(glob('tiedostot/kohteet/'.strtolower($dom).'/tyonkuvaukset/'.$data->kohteet->id.'_*.*')) as $file) {
						if(!isset($tyonkuvaukset[basename($file)])){
							$filepath = Yii::getPathOfAlias('webroot').'/'.$file;
							$pdf = file_get_contents($filepath);
							//echo $pdf; // TOIMII
							//exit;
							$tyonkuvaukset[basename($file)] = ['kohde_id' => $data->kohteet->id, 'pdf' => base64_encode($pdf)];
						} else {
							continue;
						}
					}
				}
			}

			if( $new_login ){
				$return = ["return" => $sel, 'tyonkuvaukset' => $tyonkuvaukset];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, $sel);
			}
		    
			exit;
	        }
		//     CHECK tvuoro -->

		// <-- CHECK uusiviesti
	        if($_POST['check'] == 'uusiviesti'){
			$model = new Viestinta;
			$model->admin = "tt_".$ttekija->id.",".$this->etuSukunimi($ttekija->id);
			$model->status = 3;
			$model->tekija = "toimisto";
	
			$viesti = '';
			if(isset($_POST['viesti']))
				$viesti .= $_POST['viesti'];
			$model->viesti = date("d.m H:i").", ".$this->etuSukunimi($ttekija->id).": ".$viesti;
			if($model->save())
				$str = "Viestisi vastaanotettu";
			else
				$str = "Ei onnistuu";

			if( $new_login ){
				$return = ["return" => $str];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, $str);
			}
			exit;
		}
		//     CHECK uusiviesti -->

		// <-- CHECK oleneksynyt
		if($_POST['check'] == 'oleneksynyt'){
			$model = new Viestinta;
			$model->admin = "tt_".$ttekija->id.",".$this->etuSukunimi($ttekija->id);
			$model->status = 3;
			$model->tekija = "toimisto";
	
			$viesti = '';
			if(isset($_POST['viesti']))
				$viesti .= $_POST['viesti'];
		    
			if(isset($_POST['my_location']) and !empty($_POST['my_location'])){
				$viesti .= '<br> <a href="http://maps.google.com/maps?q='.str_replace("/",",",$_POST['my_location']).'&ll='.str_replace("/",",",$_POST['my_location']).'&z=17" target="_blank">KARTTA</a>';
			}
			$model->viesti = date("d.m H:i").", ".$this->etuSukunimi($ttekija->id).": ".$viesti;

			if($model->save())
				$str = "Viestisi vastaanotettu";
			else
				$str = "Ei onnistuu";

			if( $new_login ){
				$return = ["return" => $str];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, $str);
			}
			exit;
	        }
		//     CHECK oleneksynyt -->

		// <-- CHECK uusi checkviesti
		if($_POST['check'] == 'checkviesti'){

			$criteria = new CDbCriteria();
			$criteria->order = " id DESC,status = '0' DESC LIMIT 20";
			$criteria->condition = " tekija = '".$ttekija->id."' and status = '0' ";
			$viestinta = Viestinta::model()->findAll($criteria);

			if( count($viestinta) > 0 ){ 
				$on = '<a href="viestinta.html"><div class="alert alert-warning text-center"><h2><i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;&nbsp;<b>Sinulla on lukematon viesti</b></h2></div></a>';
			} else { 
				$on = '';
			}

			if( $new_login ){
				$return = ["count" => count($viestinta), "return" => $on];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, count($viestinta)."//".$on);
			}
			exit;
	        }
		//     CHECK uusi checkviesti -->

		// <-- CHECK viestinta
		if($_POST['check'] == 'viestinta'){
			$criteria = new CDbCriteria();
			$criteria->order = " id DESC,status = '0' LIMIT 20";
			$criteria->condition = " tekija = '".$ttekija->id."' ";
			$viestinta = Viestinta::model()->findAll($criteria);

			if(empty($viestinta)){
				if( $new_login ){
					$return = ["return" => 'Ei tuloksia'];
					$this->_sendResponse(200, CJSON::encode($return));
				} else {
					$this->_sendResponse(200, 'ei tuloksia');
				}
				exit;
			}

			$sel = '';
			$cl = '';
			$admin = '';

			foreach($viestinta as $val){
				if($val->status == '0')
					$cl = ' <span class="btn btn-xs btn-success">Uusi</span>';
				else
					$cl = '';

			$exAdmin = explode(",",$val->admin);
			if(isset($exAdmin[1]))
				$admin = $exAdmin[1];
			else
				$admin = $val->admin;

		     	$sel .= '<div class="well">
				  <p>'.$cl.' <b>'.Yii::t('app', 'Keskustelu').': '.$val->id.'</b></p>
				  <span class="text" id="text_'.$val->id.'">'.str_replace("\n","<br>",$val->viesti).'</span><br>';

				  if(isset($exAdmin[0]) and !empty($exAdmin[0])){
				  $sel .= '
				  <br>
            			    <div class="input-group">
			              <input type="text" class="form-control form-input" id="vastaus_'.$val->id.'">
			              <div class="input-group-btn">
			                <button class="viesti btn btn-primary btn-group" id="'.$val->id.'">'.Yii::t('app', 'vastaus').'</button>
			              </div>
			            </div>
			  	  </div>
				  ';
				  }
			}

			Viestinta::model()->updateAll(array('status'=>1),'tekija="'.$ttekija->id.'"');

			if( $new_login ){
				$return = ["return" => $sel];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, $sel);
			}
			exit;
	        }
		//     CHECK viestinta -->

		// <-- CHECK vastaus
	        if($_POST['check'] == 'vastaus' and isset($_POST['viestinID'])){
			$viestinta = Viestinta::model()->findbypk($_POST['viestinID']);
			$tekija = Tyontekijat::model()->findbypk($viestinta->tekija);
			$viestinta->viesti = $viestinta->viesti."\n".date("d.m H:i").", ".$this->etuSukunimi($ttekija->id).": ".$_POST['vastText'];
			$viestinta->status = 3;
			$viestinta->save();
			if( $new_login ){
				$return = ["return" => $viestinta->viesti];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, $viestinta->viesti);
			}
			exit;
	        }
		//     CHECK vastaus -->

		// <-- CHECK osoitevaihto
		if($_POST['check'] == 'osoitevaihto'){

			$criteria = new CDbCriteria();
			$criteria->order = " osoite ";
			$criteria->condition = " aktiivinen=1 AND osoite like '%".$_POST['thisKey']."%' ";
			$kohteet = Kohteet::model()->findAll($criteria);

			$sel = '<select id="list" class="form-control input-lg list_osoitevaihto">';
			$sel .= '<option id="valitseOsoite">'.Yii::t('app', 'Valitse osoite').'</option>';
			foreach($kohteet as $kohde){

				$osoite = '';
				if(!empty($kohde->osoite))
					$osoite .= $kohde->osoite;
				if(!empty($kohde->pnumero))
					$osoite .= ', '.$kohde->pnumero;
				if(!empty($kohde->kaupunki))
					$osoite .= ', '.$kohde->kaupunki;

				$sel .= '<option value="'.$kohde->id.'">'.$osoite.'</option>';
			}
			$sel .= '</select>';

			if( $new_login ){
				$return = ["return" => $sel];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
				$this->_sendResponse(200, $sel);
			}
			exit;
	        }

		// Tasta alkaa getfirstpage
		if( ($new_login and $_POST['check'] == 'getfirstpage') or !$new_login){
			if(isset($_POST['tag']) and $_POST['tag'] != '000000'){
		  		$kohteet = Kohteet::model()->find(" tag_id='".$_POST['tag']."' ");

		    		if(isset($kohteet['osoite']) and !empty($kohteet['osoite'])){
		      			$get_osoite = $kohteet['osoite'];
		      			$kohdenID = $kohteet['id'];
		    		} 
			}

			if(isset($_POST['tag'])) $tag = $_POST['tag']; else $tag = '';

			if( (int)$avoinID > 0 ){
	    			$criteria = new CDbCriteria();
	    			$criteria->condition = " id='".(int)$avoinID."' AND tid = '".$ttekija->id."' ";
			} else {
	    			$criteria = new CDbCriteria();
		    		$criteria->order = " loppui='' DESC, id DESC ";
		    		$criteria->condition = " tid = '".$ttekija->id."' ";
			}
           		$mobCheck = Mobile::model()->find($criteria);
		  	if(
				isset($mobCheck->id) 
				and ($mobCheck->status == 1 or $mobCheck->status == 2 or $mobCheck->status == 10)
			)
		  	{
		    		if($mobCheck->loppui == '') $tila = 'avoina'; else $tila = 'suljettu';
			    	if($mobCheck->status == 1 and $mobCheck->loppui == '')
			    		$mobCheck->status = 1;
			    	if($mobCheck->status == 2 and $mobCheck->loppui == '')
					$mobCheck->status = 2.1;
				if($mobCheck->status == 10 and $mobCheck->loppui == '')
					$mobCheck->status = 10.1;

				if( $new_login ){
					$return = [
						"status" => $mobCheck->status,
						"sp_1" => $this->sp_1($mobCheck, $my_location, $platform),
						"osoite" => $get_osoite,
						"tekijan_nimi" => $this->etuSukunimi($ttekija->id),
					];
					$this->_sendResponse(200, CJSON::encode($return));
				} else {
					$this->_sendResponse(200, $mobCheck->status."//".$this->sp_1($mobCheck, $my_location, $platform)."//".$mobCheck->aloitan."//".$mobCheck->loppui."//".$get_osoite."//".$this->etuSukunimi($ttekija->id)."//".$kohdenID."//".$tag."//".$mobCheck->id."_".$tila."//uusi versio");
				}

			} else {
				if( $new_login ){
					$return = [
						"status" => 3,
						"sp_1" => "null",
						"osoite" => $get_osoite,
						"tekijan_nimi" => $this->etuSukunimi($ttekija->id),
					];
					$this->_sendResponse(200, CJSON::encode($return));
				} else {
                     			$this->_sendResponse(200, "3//null//null//null//".$get_osoite."//".$this->etuSukunimi($ttekija->id)."//".$kohdenID."//".$tag."//uusi versio");
				}
			}
		} // getfirstpage
	exit;
	}
	// Check loppu -->

	// <-- INSERT or UPDATE
	$checkVersio = '';
	$explVersio = array();
	$explVersio = explode(".", $appVersio);
	if( (int)$avoinID > 0 )
	{

	    		$criteria = new CDbCriteria();
	    		$criteria->condition = " id='".(int)$avoinID."' AND tid = '".$ttekija->id."' ";
	           	$mob = Mobile::model()->find($criteria);
			if(isset($explVersio[2]))
			$checkVersio = (int)$explVersio[2];	

	} else {
			$criteria = new CDbCriteria();
	    		$criteria->order = " 
				DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') 
				AND status IN (1,2,10)
				AND loppui='' DESC, id DESC ";
	    		$criteria->condition = " 
				tid = '".$ttekija->id."' 
				and loppui='' 
				AND status IN (1,2,10)
	    		";
            		$mob = Mobile::model()->find($criteria);
			$checkVersio = 'versio vanhempi kun 0.0.57';
	}
	// Mob finder -->

	// <-- jos on avoin kohde
	if(isset($mob->id)){

                $mobupdate = Mobile::model()->findbypk($mob->id);
		$log_old = $mobupdate->attributes;
                $mobupdate->loppui = date("d.m.Y H:i:s");

		if( isset($_POST['tyo_erittelyt']) and !is_array($_POST['tyo_erittelyt']) and !empty($_POST['tyo_erittelyt']) ){
			$mobupdate->tyo_erittelyt = $_POST['tyo_erittelyt'];
		} else {
			$mobupdate->tyo_erittelyt = '';
		}

		$vanhaViesti = '';
		if($mobupdate->viesti != '')
		$vanhaViesti = $mobupdate->viesti."\n";
                $mobupdate->viesti = $vanhaViesti.$_POST['viesti'];
                $mobupdate->my_location = $mobupdate->my_location."**".$_POST['my_location'];

		$kesto = '';
		$kesto = sprint(strtotime($mobupdate->loppui)-strtotime($mobupdate->aloitan));

		$ms = '';
		if($mob->status == 1)
		  $ms = 'Työ';
		if($mob->status == 2)
		  $ms = 'Matka';
		if($mob->status == 10)
		  $ms = 'Lounas';

		if($mob->status == 1 and $_POST['status'] == 3) {

                	$mobupdate->status = 3;

			// <-- Check TAG
			$explAsNum = explode("_",$mobupdate->asiakas_num);
			$explAsNumPost = explode("_",$_POST['asiakas_num']);
			if( 
				isset($asetukset->app_lopettaa_vain_tagilla) 
				and $asetukset->app_lopettaa_vain_tagilla == 1
				and isset($explAsNum[1]) 
				and isset($explAsNumPost[1]) 
				and $explAsNum[1] != $explAsNumPost[1] 
			)
			{
				if( $new_login ){
				$return = [
					"tagnumerror" => "Voit lopettaa osoitessa <b>".$mob->kohde_kannasta
				];
				$this->_sendResponse(200, CJSON::encode($return));
				} else {
		                $this->_sendResponse(200, $ms."//".$mobupdate->id."//".$mobupdate->status."//".$mob->kohde_kannasta."//tagnumerror//null//update");
				}
			        exit;
			}
			// Check TAG -->

		} elseif($mob->status == 2 and $_POST['status'] == 2) {
                	$mobupdate->status = 2;
		} elseif($mob->status == 10 and $_POST['status'] == 10) {
                	$mobupdate->status = 10;
		} else {
                	$this->_sendResponse(200, $ms." on avattu ID: ".$mob->id.", ".$mob->kohde_kannasta);
			exit;
		}

		// <-- GPS checker
		if($mobupdate->tv_id > 0){
			$tvuoro = Tyovuoroot::model()->findByPk($mobupdate->tv_id);
			if(isset($tvuoro->id)){
				$dist = $this->DestinationChecker($my_location, $tvuoro, $asetuksetForAll);
				$mobupdate->app_lopetus_destination_checker = round($dist, 2);
			}
		}
		//     GPS checker --> 

		$save = '';
		if($mobupdate->save()){
			// <-- Auto hyvaksynta
			$this->autoHyvaksynta($mobupdate->id);
			//     Auto hyvaksynta -->

			// <-- LOG
			if( isset($mobupdate->id) )
			{
			$model_log 	= 'Mob';
			$name_log 	= 'Tunnit';
			$status_log 	= 'Update by APP';
	
				$old_values = json_encode($log_old);
				$new_values = json_encode($mobupdate->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

			$save = 'ok';
			if( $new_login ){
				$return = [
					"tilanne_" => $ms,
					"id" => $mobupdate->id,
					"status" => $mobupdate->status,
					"kohde_kannasta" => $mobupdate->kohde_kannasta,
					"kesto" => $kesto,
					"is_new" => "false",
					"tv_id" => (int)$mobupdate->tv_id,
				];
				$this->_sendResponse(200, CJSON::encode($return));
			} else {
                		$this->_sendResponse(200, $ms."//".$mobupdate->id."//".$mobupdate->status."//".$mob->kohde_kannasta."//null//".$kesto."//update//".$save."//".$checkVersio);
			}
		} else {
			$this->_sendResponse(200, CJSON::encode($mobupdate->getErrors()));
		}
		exit;
	}
	// jos on avoin kohde -->

	// <-- uusi rivi
	if(isset($ttekija->id) and !empty($_POST['aloitan']) and empty($_POST['loppui'])){

		// <-- Platform updater TAMA POISTETAAN JOS yli 650 versio on kaikkialla, koska sendlocation tekee sita
		if(!empty($platform))
			Tyontekijat::model()->updatebypk($ttekija->id, array('app_platform' => $platform));

		// <-- Matka, Lounastauko ja Osoite mukaan
		if($_POST['status'] == 2 and $asetukset->app_matka_osoite != 1 and (!empty($_POST['kohdenID']) or !empty($_POST['kohde_kannasta'])))
		{
			$return = array('error' => 'Matkan merkinnässä ei saa käyttää osoitetta.');
			$this->_sendResponse(200, CJSON::encode($return));
			exit;
		}
		if($_POST['status'] == 10 and $asetukset->app_lounastauko_osoite != 1 and (!empty($_POST['kohdenID']) or !empty($_POST['kohde_kannasta'])))
		{
			$return = array('error' => 'Lounastaukon merkinnässä ei saa käyttää osoitetta.');
			$this->_sendResponse(200, CJSON::encode($return));
			exit;
		}
		//     Matka, Lounastauko ja Osoite mukaan -->

		// <-- Check is this Virtuaalinen toistuva
		if( $_POST['status'] == 1 and isset($_POST['tv_id']) and $_POST['tv_id'] > 0 ){
			$tv_controller = Yii::app()->createController('Tyovuoroot');
			$get_id = $tv_controller[0]->this_id($_POST['tv_id']);
			if(isset($get_id['toistuva']) and $get_id['toistuva'] == true){
				$model 		= $get_id['model'];
				$pvm 		= $get_id['pvm'];
				$tid 		= $get_id['tid'];

				$u		= $this->etuSukunimi($ttekija->id);
				$d		= date("d.m.Y");
				$poisto_syy	= ['text'=>'AddNewTvFromVirtualByMobile', 'user'=>$u, 'date'=>$d];
				if( $tv_controller[0]->toistuvaDeletePvm($model->id, $pvm, $tid, $poisto_syy) ){
						$tv_new = new Tyovuoroot;
						$cleared_attr = $tv_controller[0]->compareToistuvaAttributes($tv_new->attributes, $model->attributes);
						$tv_new->attributes = $cleared_attr;
						$tv_new->pvm = date("d.m.Y",strtotime($pvm));
						$tv_new->tid = $tid;
						$tv_new->tyopaari = '';
						if(!$tv_new->save()){
							echo json_encode(['error' => $tv_new->getErrors()]);
							exit;
						} else {
							$_POST['tv_id'] = $tv_new->id;
						}
				}
			}
		}

                $mobinsert = new Mobile;
                $mobinsert->attributes = $_POST;

		if($_POST['status'] == 2)
		{
                $mobinsert->kohde_kannasta = 'MATKA';
                $mobinsert->osoite = $_POST['kohde_kannasta'];
		}

		if($_POST['status'] == 10)
		{
                $mobinsert->kohde_kannasta = 'LOUNASTAUKO';
                $mobinsert->osoite = $_POST['kohde_kannasta'];
		}

                $mobinsert->imei = $ttekija->imei;
                $mobinsert->tid = $ttekija->id;
                $mobinsert->tekijan_nimi = $this->etuSukunimi($ttekija->id);
                //$mobinsert->tietoja = $_POST['tietoja'];
                $mobinsert->aloitan = date("d.m.Y H:i:s");

		if( isset($_POST['tyo_erittelyt']) and is_array($_POST['tyo_erittelyt']) and count($_POST['tyo_erittelyt']) > 0 ){
			$mobinsert->tyo_erittelyt = json_encode($_POST['tyo_erittelyt']);
		} else {
			$mobinsert->tyo_erittelyt = '';
		}


		// <-- Timer AND Position Checker
		$loppu 		= '';
		$sekForSignal 	= '';
		if( $post_tv_id > 0 ){
			$criteria = new CDbCriteria();
			$criteria->order = "alku DESC"; 
			$criteria->condition = " 
				tid = '".$ttekija->id."' 
				AND id='".$post_tv_id."'
				AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) = CURDATE()
			";
			$tvuoro = Tyovuoroot::model()->find($criteria);

			if(isset($tvuoro->id)){

				// <-- GPS checker
				$dist = $this->DestinationChecker($my_location, $tvuoro, $asetuksetForAll);
				$mobinsert->app_aloitus_destination_checker = round($dist, 2);
				//     GPS checker -->

				// <-- Timer
				$loppu = date("d.m.Y H:i",strtotime($tvuoro->pvm." ".$tvuoro->loppu));
				$sekForSignal = strtotime($tvuoro->pvm." ".$tvuoro->loppu)-time();
			}
		}
		// Timer AND Position Checker -->

                if($mobinsert->save()){
			// <-- LOG
			if( isset($mobinsert->id) ){
			$model_log 	= 'Mob';
			$name_log 	= 'Tunnit';
			$status_log 	= 'Create from APP';
	
			$old_values = null;
			$new_values = json_encode($mobinsert->attributes);
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

			if( $new_login ){
				$return = [
					"id" => $mobinsert->id,
					"kohde_kannasta" => $mobinsert->kohde_kannasta,
					"is_new" => "true",
					"kohdenID" => $mobinsert->kohdenID,
					"tv_id" => (isset($tvuoro->id))? (int)$tvuoro->id : 0,
					"loppu" => $loppu,
					"sekForSignal" => $sekForSignal,
				];
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			} else {
		                $this->_sendResponse(200, $mobinsert->id."//".$mobinsert->kohde_kannasta."//new//".$mobinsert->kohdenID."//".$loppu."//".$sekForSignal);
				exit;
			}
		} else {
	                $this->_sendResponse(200, "mobinsert Error!");
			exit;
		}


	} else {
		$this->_sendResponse(200, "Kaikki on suljettu, ei ole mitään avoina");
		exit;
	}
	// uusi rivi -->
	break;
        default:
            $this->_sendResponse(501, 
                sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
                Yii::app()->end();
    }

}

	protected function DestinationChecker($my_location, $tvuoro, $asetuksetForAll){
		//$my_location = '61.157604,22.9610618';
		$return = 0;
		if(
			!empty($my_location) and isset($tvuoro->kohteet->gps_sijainti) and !empty($tvuoro->kohteet->gps_sijainti) 
			and isset($asetuksetForAll->googlemaps_apikey) and !empty($asetuksetForAll->googlemaps_apikey)
		){

			$ex_app = explode(",", $my_location);
			$ex_tv = explode(",", $tvuoro->kohteet->gps_sijainti);
			if(isset($ex_app[1]) and isset($ex_tv[1]))
			{
			        $lat_app = $ex_app[0];
			        $lng_app = $ex_app[1];
			        $lat_tv = $ex_tv[0];
			        $lng_tv = $ex_tv[1];

				$return = $this->distance($lat_app, $lng_app, $lat_tv, $lng_tv);
			}
		}
		return $return;
	}

	protected function distance($lat1, $lon1, $lat2, $lon2) {

		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;

		return ($miles * 1.609344);

	}

	protected function sp_1($mobCheck, $my_location, $platform)
	{
		if(!isset($mobCheck->id)){
			$this->_sendResponse(200, CJSON::encode(array("error" => "sp_1 function error")));
			exit;
		}
		if( $mobCheck->tv_id > 0 )
           		$tv = Tyovuoroot::model()->findByPk($mobCheck->tv_id);
		$asetuksetForAll = AsetuksetForAll::model()->findByPk(1);
		$kartta = '';
		if(isset($asetuksetForAll->googlemaps_apikey) and !empty($asetuksetForAll->googlemaps_apikey) and isset($mobCheck->kohteet->gps_sijainti) and !empty($mobCheck->kohteet->gps_sijainti)){
			/* 
			$full_addr = $mobCheck->kohteet->osoite.' '.$mobCheck->kohteet->pnumero.' '.$mobCheck->kohteet->kaupunki;
			$json_url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($full_addr).'&language=fi&sensor=true&key='.$asetuksetForAll->googlemaps_apikey;
			$json = file_get_contents($json_url);
			$obj = json_decode($json);
			if( isset($obj->results[0]->geometry->location->lat) ){
				$kartta = '<p><a href="geo:'.$obj->results[0]->geometry->location->lat.",".$obj->results[0]->geometry->location->lng.'">'.Yii::t('app', 'Näytä kartalla').'</a></p>';
			}
			*/
			$kartta = '<p><a href="geo:'.$mobCheck->kohteet->gps_sijainti.'">'.Yii::t('app', 'Näytä kartalla').'</a></p>';
		}

		$nykyinenKesto = 0;
		if(strtotime($mobCheck->aloitan) > 0){
			$nykyinenKesto = time()-strtotime($mobCheck->aloitan);
			$nykyinenKesto = sprint($nykyinenKesto);
		}
		$sp1 = 'Kesto: <b>'.$nykyinenKesto.'</b>';
		$sp1 .= '<div class="text-center">';
		$sp1 .= '<p>'.$mobCheck->kohde_kannasta.'</p>';
		if( isset($tv->id) ){
			$sp1 .= '<p class="text-danger">'.Yii::t('app', 'Muista lopettaa klo.').' '.$tv->loppu.'</p>';
		}
		if(!empty($kartta))
			$sp1 .= '<p>'.$kartta.'</p>';

		if( isset($tv->id) and is_array(json_decode($tv->tyo_erittelyt, true)) ){
			$sp1 .= '<br><label>Työ-erittelyt:</label>';
			 foreach(json_decode($tv->tyo_erittelyt, true) as $k => $v){
			 $sp1 .= '
			 <div class="row">
			  <div class="col-sm-12">
			   <input class="tyo_erittelyt" type="checkbox" value="'.$k.'"> '.$v.'
			  </div>
	 		 </div>';
			 }
		}
		$sp1 .= '</div>';
		return $sp1;
	}

	protected function autoHyvaksynta($id)
	{
		$mob = Mobile::model()->findByPk($id);
		if( 
			isset($mob->id) 
			and isset($mob->tv_id) 
			and $mob->tv_id != 0 
			and $mob->status == 3 
		){
			$tv = Tyovuoroot::model()->findByPk($mob->tv_id);
			if( isset($tv->id) ){
				$asetukset = Asetukset::model()->findByPk(1);

				// <-- Totetuneen ajan mukaan
				if( 
					isset($asetukset->app_auto_hyvaksyminen) and $asetukset->app_auto_hyvaksyminen == 1 
					and isset($asetukset->app_hyvaksynnan_peruste) and $asetukset->app_hyvaksynnan_peruste == 0
				){
				    $aikavali = 0;
				    $mobile_kesto = strtotime($mob->loppui)-strtotime($mob->aloitan);
				    $tyovuoro_kesto = strtotime($tv->pvm.' '.$tv->loppu)-strtotime($tv->pvm.' '.$tv->alku);

				    if( isset($asetukset->app_auto_hyvaksyminen_aikavali) ){
					$aikavali = $asetukset->app_auto_hyvaksyminen_aikavali*60;
				    }

				    if(
					$aikavali > 0 and
					($tyovuoro_kesto == $mobile_kesto)
					or ( ($mobile_kesto > $tyovuoro_kesto) and ($mobile_kesto-$tyovuoro_kesto) <= $aikavali )
					or ( ($mobile_kesto < $tyovuoro_kesto) and ($tyovuoro_kesto-$mobile_kesto) <= $aikavali )
				    ){
					Mobile::model()->updateByPk($id, array('hyvaksytty' => 'auto//'.date("d.m.Y")));
				    	if( isset($asetukset->app_auto_hyvaksyminen_tvmukaan) and $asetukset->app_auto_hyvaksyminen_tvmukaan == 1 ){
						$this->uusiToteutuneetRiviTehdysta($mob->id, $tv->id);
					}
				    }

				}
				//     Totetuneen ajan mukaan -->

				// <-- Työvuoron aloitus ja lopetus mukaan
				if( 
					isset($asetukset->app_auto_hyvaksyminen) and $asetukset->app_auto_hyvaksyminen == 1 
					and isset($asetukset->app_hyvaksynnan_peruste) and $asetukset->app_hyvaksynnan_peruste == 1
				){
				    $aikavali = 0;
				    $mobile_aloitus = strtotime($mob->aloitan);
				    $mobile_lopetus = strtotime($mob->loppui);
				    $tyovuoro_aloitus = strtotime($tv->pvm.' '.$tv->alku);
				    $tyovuoro_lopetus = strtotime($tv->pvm.' '.$tv->loppu);

				    if( isset($asetukset->app_auto_hyvaksyminen_aikavali )){
					$aikavali = $asetukset->app_auto_hyvaksyminen_aikavali*60;
				    }

				    if(
					$aikavali > 0 and
					(
						(($mobile_aloitus+$aikavali) >= $tyovuoro_aloitus and $mobile_aloitus < $tyovuoro_lopetus) 
						and ($mobile_aloitus <= ($tyovuoro_aloitus+$aikavali) and $mobile_aloitus <= $tyovuoro_lopetus)
					)
					and (($mobile_lopetus-$aikavali) <= $tyovuoro_lopetus and $mobile_lopetus >= ($tyovuoro_lopetus-$aikavali))
				    ){
					Mobile::model()->updateByPk($id, array('hyvaksytty' => 'auto//'.date("d.m.Y")));
				    	if( isset($asetukset->app_auto_hyvaksyminen_tvmukaan) and $asetukset->app_auto_hyvaksyminen_tvmukaan == 1 ){
						$this->uusiToteutuneetRiviTehdysta($mob->id, $tv->id);
					}
				    }

				}
				//     Työvuoron aloitus ja lopetus mukaan -->
			}
		}
	}

	protected function uusiToteutuneetRiviTehdysta($id, $tv_id)
	{
		$mob = Mobile::model()->findByPk($id);
		$tv = Tyovuoroot::model()->findByPk($tv_id);
		if( !isset($mob->id) or !isset($tv->id) ){ return false; }
		$model=new Toteutuneet;
		$model->attributes = $mob->attributes;
		$model->kid = $id;
		$model->aloitan = $tv->pvm.' '.$tv->alku.':00 ';
		$model->loppui = $tv->pvm.' '.$tv->loppu.':00 ';
		$model->save();
	}

	protected function etuSukunimi($tid)
	{
	   	$site = Yii::app()->createController('Site');
	   	return $site[0]->etuSukunimi($tid);
	}
	protected function checkDBexists($db)
	{
		$connection=Yii::app()->db;
		$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".trim(strtolower($db))."'";
		$command=$connection->createCommand($sql);
		if($command->execute() != true){
			die(json_encode(array("error" => "Yritystunnus on virheellinen.")));
		}
        		return true;
	}

	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function vkopaiva($val){
	    	$v = date("w", strtotime($val));
		$p_arr = array('1' => 'Ma', '2' => 'Ti', '3' => 'Ke', '4' => 'To', '5' => 'Pe', '6' => 'La', '7' => 'Su', );
		if( isset($p_arr[$v]) ){
			return $p_arr[$v];
		} else {
			return '';
		}
	}

/*
    // Actions
    public function actionList()
    {


//$this->_checkAuth();
    // Get the respective model instance
    switch($_GET['model'])
    {
        case 'posts':
            $models = Mob::model()->findAll("id != '' order by id limit 10",array("select"=>"id"));
            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }

    }
*/


/*
public function actionView()
{
$this->_checkAuth();
    // Check if id was submitted via GET
    if(!isset($_GET['id']))
        $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
 
    switch($_GET['model'])
    {
        // Find respective model    
        case 'posts':
            $model = Mob::model()->findbypk($_GET['id']);
            break;
        default:
            $this->_sendResponse(501, sprintf(
                'Mode <b>view</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we find the requested model? If not, raise an error
    if(is_null($model)){
        $this->_sendResponse(404, 'No Item found with id '.$_GET['id']); //'No Item found with id '.$_GET['id']
    } else {
        $this->_sendResponse(200, CJSON::encode($model));
    }

}
*/


/*
public function actionImei()
{

//$this->_checkAuth();

    // Check if id was submitted via GET
    if(!isset($_GET['id']))
        $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
 
    switch($_GET['model'])
    {
        // Find respective model    
        case 'posts':
            $ttekija = Tyontekijat::model()->find(" imei = '".$_GET['id']."' ");
            $model = Mob::model()->find(" imei = '".$_GET['id']."' and loppui = '' ");
            break;
        default:
            $this->_sendResponse(501, sprintf(
                'Mode <b>view</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we find the requested model? If not, raise an error
    if(is_null($ttekija)){
        $this->_sendResponse(404, 'This imei not found'); //'No Item found with id '.$_GET['id']
    } else {

	if(!is_null($model)){
	  $id = $model->id;
	  $status = $model->status;

	} else {
	  $id = null;
	  $status = null;
	}

	$dataForSend = array("Kohde"=>$id,"Tyontekija"=>$ttekija->id,"Status"=>$status);
        $this->_sendResponse(200, CJSON::encode($dataForSend));
    }
}
*/

/*
public function actionCreate()
{

    switch($_GET['model'])
    {
        // Get an instance of the respective model
        case 'posts':
            $model = new Mob;                    
            break;
        default:
            $this->_sendResponse(501, 
                sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
                Yii::app()->end();
    }
    // Try to assign POST values to attributes
    foreach($_POST as $var=>$value) {
        // Does the model have this attribute? If not raise an error
        if($model->hasAttribute($var))
            $model->$var = $value;
        else
            $this->_sendResponse(500, 
                sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                $_GET['model']) );
    }
    // Try to save the model
    if($model->save())
        $this->_sendResponse(200, CJSON::encode($model));
    else {
        // Errors occurred
        $msg = "<h1>Error</h1>";
        $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
        $msg .= "<ul>";
        foreach($model->errors as $attribute=>$attr_errors) {
            $msg .= "<li>Attribute: $attribute</li>";
            $msg .= "<ul>";
            foreach($attr_errors as $attr_error)
                $msg .= "<li>$attr_error</li>";
            $msg .= "</ul>";
        }
        $msg .= "</ul>";
        $this->_sendResponse(500, $msg );
    }

}
*/

/*
public function actionUpdate()
{

    // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
    $json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
    $put_vars = CJSON::decode($json,true);  //true means use associative array
 
    switch($_GET['model'])
    {
        // Find respective model
        case 'posts':
            $model = Mob::model()->findByPk($_GET['id']);                    
            break;
        default:
            $this->_sendResponse(501, 
                sprintf( 'Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we find the requested model? If not, raise an error
    if($model === null)
        $this->_sendResponse(400, 
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                $_GET['model'], $_GET['id']) );
 
    // Try to assign PUT parameters to attributes
    foreach($put_vars as $var=>$value) {
        // Does model have this attribute? If not, raise an error
        if($model->hasAttribute($var))
            $model->$var = $value;
        else {
            $this->_sendResponse(500, 
                sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
                $var, $_GET['model']) );
        }
    }
    // Try to save the model
    if($model->save())

        $this->_sendResponse(200, CJSON::encode($model));
    else
        // prepare the error $msg
        // see actionCreate
        // ...
        $this->_sendResponse(500, $msg );

}
*/

/*
public function actionUpdaterow()
{
    // Check if id was submitted via GET
    if(!isset($_GET['id']))
        $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
 
    switch($_GET['model'])
    {
        // Find respective model    
        case 'posts':
            $model = Mob::model()->findbypk($_GET['id']);
            break;
        default:
            $this->_sendResponse(501, sprintf(
                'Mode <b>view</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }

    foreach($_POST as $var=>$value) {
        // Does the model have this attribute? If not raise an error
        if($model->hasAttribute($var))
            $model->$var = $value;
        else
            $this->_sendResponse(500, 
                sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                $_GET['model']) );
    }

    if($model->save())
        $this->_sendResponse(200, CJSON::encode($model));
    else
        // prepare the error $msg
        // see actionCreate
        // ...
        $this->_sendResponse(500, $msg );
}
*/

/*
public function actionDelete()
{

    switch($_GET['model'])
    {
        // Load the respective model
        case 'posts':
            $model = Mob::model()->findByPk($_GET['id']);                    
            break;
        default:
            $this->_sendResponse(501, 
                sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Was a model found? If not, raise an error
    if($model === null)
        $this->_sendResponse(400, 
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                $_GET['model'], $_GET['id']) );
 
    // Delete the model
    $num = $model->delete();
    if($num>0)
        $this->_sendResponse(200, $num);    //this is the only way to work with backbone
    else
        $this->_sendResponse(500, 
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                $_GET['model'], $_GET['id']) );

}
*/


private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
    // set the status
    $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
    header($status_header);
    // and the content type
    header('Content-type: ' . $content_type);
 
    // pages with body are easy
    if($body != '')
    {
        // send the body
        echo $body;
    }
    // we need to create the body if none is passed
    else
    {
        // create some body messages
        $message = '';
 
        // this is purely optional, but makes the pages a little nicer to read
        // for your users.  Since you won't likely send a lot of different status codes,
        // this also shouldn't be too ponderous to maintain
        switch($status)
        {
            case 401:
                $message = 'You must be authorized to view this page.';
                break;
            case 404:
                $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                break;
            case 500:
                $message = 'The server encountered an error processing your request.';
                break;
            case 501:
                $message = 'The requested method is not implemented.';
                break;
        }
 
        // servers don't always have a signature turned on 
        // (this is an apache directive "ServerSignature On")
        $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
 
        // this should be templated in a real-world solution

        $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';
 
        echo $body;
    }
    Yii::app()->end();
}



private function _getStatusCodeMessage($status)
{
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $codes = Array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',

        501 => 'Not Implemented',
    );
    return (isset($codes[$status])) ? $codes[$status] : '';
}


private function _checkAuth()
{


    if(!(isset($_GET['X_USERNAME']) and isset($_GET['X_PASSWORD']))) {
        // Error: Unauthorized
        $this->_sendResponse(401);
    }
    $username = $_GET['X_USERNAME'];
    $password = $_GET['X_PASSWORD'];
    // Find the user
    $user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
    if($user===null) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Name is invalid');
    } else if(!$user->validatePassword($password)) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Password is invalid');
    }

/*
    // Check if we have the USERNAME and PASSWORD HTTP headers set?
    if(!(isset($_SERVER['HTTP_X_USERNAME']) and isset($_SERVER['HTTP_X_PASSWORD']))) {
        // Error: Unauthorized
        $this->_sendResponse(401);
    }
    $username = $_SERVER['HTTP_X_USERNAME'];
    $password = $_SERVER['HTTP_X_PASSWORD'];
    // Find the user
    $user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
    if($user===null) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Name is invalid');
    } else if(!$user->validatePassword($password)) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Password is invalid');
    }
*/
}


}

?>

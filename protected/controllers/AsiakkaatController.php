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
                'controllers'=>array('site'),
                'actions'=>array('error'),
            ),
			array('allow', 
				'actions'=>array('asiakas_tila', 'ulos', 'osoitteen_muutos', 'send_vastaus', 'getLaskuPDF'),
                		'expression'=>"Yii::app()->controller->isAsiakas()",
			),
			array('allow',
				'actions'=>array('admin', 'delete', 'create', 'update', 'index', 'view', 
					'checkLastAsiakasID', 'showshift', 'send_vastaus', 'getLaskuPDF', 
					'kartta', 'kayttajat', 'lahetatunnukset', 'view_edico', 'massamuokkaus', 
					'kaikki_netvisoriin', 'freshdesk', 'freshdesk_ticket', 'puhnro_korjaus', 
					'email_history', 'integromat_upsert', 'checkworkgroups', 'netvisor_customerlist', 'tag_report'),
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
			$new_password = password_hash($_POST['password1'], PASSWORD_BCRYPT);
			Asiakkaat::model()->updateByPk($model->id, array('salasana' => $new_password, 'token' => ''));
			$tilanne = 3;
		}

		$this->render('salasana', array('tilanne' => $tilanne, 'model' => $model));
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

		$from = date("Y-m-d");
		$to = date("Y-m-d", strtotime("+1 month"));
		if(isset($_GET['from']) and isset($_GET['to'])){
			$from 	= date("Y-m-d",strtotime($_GET['from']));
			$to 	= date("Y-m-d",strtotime($_GET['to']));
		}
        	$haku_criteria = "
			kohde IN 
			(SELECT id FROM sivex_kohdet 
			   WHERE asiakas_id='".$id."'
			)
		";

		$tyovuorot = Yii::app()->createController('Tyovuoroot');
		$dataAll = $tyovuorot[0]->FromToSuunnitellutAll($from, $to, [], $haku_criteria, ['data']);

		$reserved = $tyovuorot[0]->FromToSuunnitellutAll($from, $to, [0], $haku_criteria, ['data']);

		$this->render('showshift',array(
				'dataAll' => $dataAll,
				'reserved' => $reserved,
				'from' => $from,
				'to' => $to,
				'id' => $id,
		));

	}

	public function actionView_edico($id)
	{
		$this->render('view_edico',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionMassamuokkaus()
	{

	// <-- Oikeudet
	   $checkOikeus = "asiakkaat_4_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$criteria=new CDbCriteria;
		if(isset($_POST['Asiakkaat']['filter_tyyppi']) and !empty($_POST['Asiakkaat']['filter_tyyppi']) and $_POST['Asiakkaat']['filter_tyyppi'] == 'henkilo'){
			$criteria->addCondition(" tyyppi='".$_POST['Asiakkaat']['filter_tyyppi']."' "); 
		}
		if(isset($_POST['Asiakkaat']['filter_tyyppi']) and !empty($_POST['Asiakkaat']['filter_tyyppi']) and $_POST['Asiakkaat']['filter_tyyppi'] == 'yritys'){
			$criteria->addCondition(" tyyppi='".$_POST['Asiakkaat']['filter_tyyppi']."' "); 
		}
		if(isset($_POST['Asiakkaat']['filter_tyyppi']) and !empty($_POST['Asiakkaat']['filter_tyyppi']) and $_POST['Asiakkaat']['filter_tyyppi'] == 'kaikki'){
			$criteria->addCondition(" tyyppi='henkilo' OR tyyppi='yritys' "); 
		}
		if(isset($_POST['Asiakkaat']['filter_postitoimipaikka']) and !empty($_POST['Asiakkaat']['filter_postitoimipaikka'])){
			$criteria->addCondition(" kaupunki='".$_POST['Asiakkaat']['filter_postitoimipaikka']."' "); 
		}
		if(isset($_POST['Asiakkaat']['filter_tyoryhma']) and !empty($_POST['Asiakkaat']['filter_tyoryhma'])){
			$criteria->addCondition(" tyoryhma='".$_POST['Asiakkaat']['filter_tyoryhma']."' "); 
		}
		if(isset($_POST['Asiakkaat']['filter_asiakasryhma']) and !empty($_POST['Asiakkaat']['filter_asiakasryhma'])){
			$criteria->addCondition(" ryhma LIKE '%\"".$_POST['Asiakkaat']['filter_asiakasryhma']."\"%' "); 
		}
		$as_all = Asiakkaat::model()->findAll($criteria);
		$asetukset = Asetukset::model()->findbypk(1);
		if( isset($_GET['esikatselu'])){
			$lista = array();
			foreach($as_all as $model){
				if( empty($model->Fullname) ){ continue; }
				$lista[] = array('nimi' => $model->Fullname);
			}
			$return = array('lista' => $lista, 'countlista' => count($lista));
			echo json_encode($return);
			exit;
		}

		if(isset($_POST['Asiakkaat']))
		{
		   $post = array();
		   //echo count($as_all);
		   //exit;

		   foreach($_POST['Asiakkaat'] as $k => $v){
			if(!empty($v) and isset($_POST['Check'][$k])){ $post[$k] = $v; }
		   }

		   $kiere = '';
		   foreach($as_all as $model){

			$vanha_attr = $model->attributes;
			$model->attributes=$post;

			// <-- Dimension
			if( isset($_POST['Asiakkaat']['netvisor_dimension_name']) ){
			   $dimension = explode("//", $_POST['Asiakkaat']['netvisor_dimension_name']);
			   if( isset($dimension[0]) and isset($dimension[1]) ){
				$model->netvisor_dimension_name = $dimension[0];
				$model->netvisor_dimension_item = $dimension[1];
			   }
			}
			//     Dimension -->

			if(isset($post['ryhma'])){ $model->ryhma=json_encode($post['ryhma']); }

			if($model->save())
			{
				// <-- LOG
				$model_log 	= 'Asiakkaat';
				$name_log 	= 'Asiakas';
				$status_log 	= 'Massamuokkaus';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
			} else {

				$kiere .= '<h3>ID: '.$model->id.',  Nimi: '.$model->Fullname.'</h3>';
				foreach($model->getErrors() as $err){
					$kiere .= $err[0].'<br>';
				}
			}
		   }
		   if( !empty($kiere) ){  Yii::app()->user->setFlash('danger', $kiere); }
		   Yii::app()->user->setFlash('success', "Valmis.");
		   $this->redirect(array('index'));

		}
		$model = new Asiakkaat;
		$this->render('massamuokkaus',array(
			'model' => $model,
			'as_all' => $as_all
		));
	}

	public function actionKaikki_netvisoriin()
	{
		$asetukset = Asetukset::model()->findbypk(1);
		if($asetukset->netvisor_kaytto == 1)
		{
		   $asiakkaat = Asiakkaat::model()->findAll("netvisorkey=0 AND aktiivinen=1");
		   $virhe_response = [];
		   foreach($asiakkaat as $model){
			$nimi = $model->Fullname;
			if($this->netvisorCustomer("add", $model) !== true){
				$virhe_response[] = '<h3>Asiakas: '.$nimi.'</h3>'.$this->netvisorCustomer("add", $model);
			}
		   }
		   Yii::app()->user->setFlash('success', "Valmis.");
		   if( count($virhe_response) > 0 ){ 
			$lista = '';
			foreach($virhe_response as $itm){
				$lista .= $itm.'<br>';
			}
			Yii::app()->user->setFlash('danger', "<h1>Ei mennyt läpi:</h1><p>". $lista . "</p>" ); 
		   }
		}
		$this->redirect(array('index'));
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
			if( is_array($model->muistiinpano) and count($model->muistiinpano) > 0 ){
				$model->muistiinpano = json_encode($model->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$model->muistiinpano = '';
			}

			if(is_array($model->extra_contacts) and count($model->extra_contacts) > 0) {
				$model->extra_contacts = json_encode($model->extra_contacts, JSON_FORCE_OBJECT);
			} else {
				$model->extra_contacts = "";
			}

			// <-- Dimension
			if( isset($_POST['Asiakkaat']['netvisor_dimension_name']) ){
			   $dimension = explode("//", $_POST['Asiakkaat']['netvisor_dimension_name']);
			   if( isset($dimension[0]) and isset($dimension[1]) ){
				$model->netvisor_dimension_name = $dimension[0];
				$model->netvisor_dimension_item = $dimension[1];
			   }
			}
			//     Dimension -->

			if(isset($_POST['Asiakkaat']['ryhma']))
				$model->ryhma=json_encode($_POST['Asiakkaat']['ryhma']);
			else
				$model->ryhma="";

			if($model->save())
			{

				// <-- LOG
				if( isset($model->id) )
				{
				$model_log 	= 'Asiakkaat';
				$name_log 	= 'Asiakas';
				$status_log 	= 'Create';
	
					$old_values = null;
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->

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

				// integromat webhook (kotipuhtaaksi)
				// sends client data to a web hook to be processed further
				$domain = Yii::app()->user->domain;
				if($domain == "kotipuhtaaksi") {
					$this->integromatUpsert($model);
				}


				if(empty($model->asiakasnumero))
				$a = Asiakkaat::model()->updatebypk($model->id, array('asiakasnumero'=>$model->id));

				$this->redirect(array('//kohteet/createfromasiakas', 'id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionLahetatunnukset()
	{
		foreach($_POST['arr'] as $id){
			$this->LahetaTunnukset($id);
			//echo $id.' ok';
		}
		echo 'ok';
		exit;
	}

	public function LahetaTunnukset($id)
	{

		$model=$this->loadModel($id);

				$ft = FirmanTiedot::model()->findByPk(1);
				$yr =  '';
				if(isset($ft->tyonantaja))
				$yr =  $ft->tyonantaja;

				$asiakas = $model->Fullname;

				$token = sha1(uniqid(time().$model->id, true));
				Asiakkaat::model()->updateByPk($model->id, array('token' => $token));

				$subject = $yr.' toivottaa sinut tervetulleeksi käyttämään eDicoa';
				$message = 'Hei '.$asiakas.'!<br>
Olemme tehneet sinulle profiilin eDico-sovellukseen, jolla voit olla kätevästi yhteydessä meihin, antaa palautetta, tarkastella tilauksiasi ja vahvistaa sopimukset ja tarjoukset. <br>
Lataa eDico-sovellus älylaitteeseesi alla olevan linkin kautta.<br>
				<b>Yritystunnus:</b> '.Yii::app()->user->domain.'<br>
				<b>Käyttäjätunnus:</b> '.$model->sahkoposti.'<br>
				<b>Luo oma salasana:</b> <a href='.Yii::app()->createAbsoluteUrl('asiakkaat/salasana', array('domain' => Yii::app()->user->domain, 'token' => $token, 'asiakasid' => $model->id)).'>tästä</a><br>
<p>
<p>Ystävällisin terveisin,<br>
Yritys '.$yr.'
</p>
</p>
<p>
<a href="https://play.google.com/store/apps/details?id=fi.etunti.dico&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1"><img alt="Get it on Google Play" src="'.Yii::app()->request->hostInfo.'/lib/app/google-play.jpg" style="height:100px" /></a>

<a href="https://itunes.apple.com/fi/app/edico/id1267192864?mt=8"><img src="'.Yii::app()->request->hostInfo.'/lib/app/app-ios.jpg" style="height:100px" ></a>
</p>
				';

				//echo $message;
				//exit;

				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($model->sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				if($mail->send())
				{

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $model->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->
					return 'sendOK';
				}

		return false;
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

		// <-- FILES
		if(isset($_POST['uploaded_t'])){
			Asetukset::model()->uploadFile(
				Yii::app()->user->domain, 
				'asiakkaat', 
				$model->id.'_'.$_FILES['file']['name']
			);
		}
		if(isset($_POST['poistaTamaTiedosto'])){
			unlink($_POST['poistaTamaTiedosto']);
			exit;
		}
		//     FILES -->

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

				$path = Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain.'/eDico_kayttoehdot.html';
				if (!file_exists($path)) {
					Yii::app()->user->setFlash('danger', "eDico käyttöehtoja ei löydy asetuksista. Lisää ehdot ennen käyttönottoa.");
				} else {
					$this->LahetaTunnukset($id);
					Yii::app()->user->setFlash('success', "Lähetetty.");
					$this->redirect(array('update','id'=>$id));
				}
		}
		//     Tunnukset lahetys -->




		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Asiakkaat']))
		{

			if(isset($_POST['Asiakkaat']['verot']))
			unset($_POST['Asiakkaat']['verot']);
			// copy old attributes
			$vanha_attr = $model->attributes;
			// load new attributes
			$model->attributes=$_POST['Asiakkaat'];
			if( is_array($model->muistiinpano) and count($model->muistiinpano) > 0 ){
				$model->muistiinpano = json_encode($model->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$model->muistiinpano = '';
			}

			if(is_array($model->extra_contacts) and count($model->extra_contacts) > 0) {
				$model->extra_contacts = json_encode($model->extra_contacts, JSON_FORCE_OBJECT);
			} else {
				$model->extra_contacts = "";
			}

			// <-- Dimension
			if( isset($_POST['Asiakkaat']['netvisor_dimension_name']) ){
			   $dimension = explode("//", $_POST['Asiakkaat']['netvisor_dimension_name']);
			   if( isset($dimension[0]) and isset($dimension[1]) ){
				$model->netvisor_dimension_name = $dimension[0];
				$model->netvisor_dimension_item = $dimension[1];
			   }
			}
			//     Dimension -->

			// <-- Kaikki kohteet passiviseksi jos asiakas passivinen
			if($_POST['Asiakkaat']['aktiivinen'] == 0 and $vanha_attr['aktiivinen'] == 1)
			{
				
				$criteria=new CDbCriteria;
				$criteria->condition = " 
					asiakas_id='".$id."'
					AND id IN ( SELECT kohde FROM sivex_tvuoro
						WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
					)
				";
				$kohteet = Kohteet::model()->findAll($criteria);

				if(count($kohteet) > 0)
				{
					Yii::app()->user->setFlash('danger', "Asiakkaalla on suunniteltuja työvuoroja.");
				} 

					$criteria=new CDbCriteria;
					$criteria->condition = " 
						asiakas_id='".$id."'
					";
					Kohteet::model()->updateAll(array('aktiivinen'=>'0'), $criteria);
				

			}
			//     Kaikki kohteet passiviseksi jos asiakas passivinen -->


			if(isset($_POST['Asiakkaat']['ryhma'])){
				$model->ryhma=json_encode($_POST['Asiakkaat']['ryhma']);
			} else {
				$model->ryhma="";
			}

			$req = Yii::app()->request;
			$client_body = $req->getPost("Asiakkaat");
			$noEmail = isset($client_body["no_email"]) ? $client_body["no_email"] : 0;
			if($noEmail == 1) {
				$model->sahkoposti = "";
			}

			// if client is set back to active from inactivity, clear lopetuksen_syy
			// and lopetuksen_pvm fields.
			if(isset($vanha_attr["aktiivinen"]) 
				&& $vanha_attr["aktiivinen"] == 0 && $model->aktiivinen == 1) {
				$model->lopetuksen_syy = null;
				$model->lopetuksen_pvm = null;
			}

			if($model->save())
			{

				// <-- LOG
				$model_log 	= 'Asiakkaat';
				$name_log 	= 'Asiakas';
				$status_log 	= 'Update';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->


			   // <-- Netvisor
			   $a = Asetukset::model()->findbypk(1);
			   if($a->netvisor_kaytto == 1)
			   {
				if($model->netvisorkey == 0)
				{
					if($this->netvisorCustomer("add", $model) !== true){
						print_r($this->netvisorCustomer("add", $model));
						exit;
					}
				} else {
					if($this->netvisorCustomer("edit", $model) !== true){
						print_r($this->netvisorCustomer("edit", $model));
						exit;
					}
				}
			    }
			   //  Netvisor -->
			   // check if meaningful data changes
			   // (email, phone, names, postal code, active/inactive and quitting)
			   	$integromatDataChanged = $this->integromatDataChanged($vanha_attr, $model->attributes);
				// integromat webhook
			   	$domain = Yii::app()->user->domain;
				if($domain == "kotipuhtaaksi" and $integromatDataChanged) {
					$this->integromatUpsert($model);
				}

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


		$a_d = Asiakkaat::model()->findbypk($id);
		$k_d = Kohteet::model()->findAll(" asiakas_id='".$id."' ");

			if(isset($a_d->id))
			{
				// <-- LOG
				$model_log 	= 'Asiakkaat';
				$name_log 	= 'Asiakas';
				$status_log 	= 'Delete';
	
					$old_values = json_encode($a_d->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
			}

			if(count($k_d) > 0)
			{
			    foreach($k_d as $k_m)
			    {
				// <-- LOG
				$model_log 	= 'Kohteet';
				$name_log 	= 'Kohde';
				$status_log 	= 'Delete';
	
					$old_values = json_encode($k_m->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
			    }
			}


		Kohteet::model()->deleteAll(" asiakas_id='".$id."' ");
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(array('index'));
	}

	public function actionNetvisor_customerlist()
	{
		if(isset($_GET['getAsiakas']))
		{
			$data = $this->netvisorAsiakasNouto($_GET['getAsiakas']);
		} else {
			$data = $this->Customerlist();
		}

		$this->render('netvisor_customerlist', array(
			'data' => $data, 
		));
	}

	protected function Customerlist()
	{
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

		if(isset($n[0]))
		{
			$url		= $n[0].'/customerlist.nv';
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
			if(empty($response))
			{
				Yii::app()->user->setFlash('danger', "Netvisor API yhteys ei toimii.");
				$this->redirect(array('index'));
			} else {
				$result = new SimpleXMLElement($response);

				if($result->ResponseStatus->Status == 'OK' and isset($result->Customerlist))
				{
					return json_decode(json_encode((array)$result->Customerlist), true);
				}
			}
		}
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
			if(empty($response))
			{
				Yii::app()->user->setFlash('danger', "Netvisor API yhteys ei toimii.");
				$this->redirect(array('index'));
			} else {
				$result = new SimpleXMLElement($response);

				if($result->ResponseStatus->Status == 'OK' and isset($result->Customer))
				{
					return json_decode(json_encode((array)$result->Customer), true);
				}
			}
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
	
	
	$name = $model->AsiakasWithExtraContacts;

	$ryhma = '';
	$r = Valikkoot::model()->findbypk($model->ryhma);
	if(isset($r->id))
	$ryhma = $r->value;

	$isprivatecustomer = '1';
	$customerfinvoicedetails = '';

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
      <EmailInvoicingAddress>'.$model->sahkopostilaskuosoite.'</EmailInvoicingAddress>
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

		return true;

	  } else {
		return $response;

	  }

	
	} // if isset $n[0]




	}

	public function actionTag_report()
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
		$criteria->order = "etunimi";
		/*
		$criteria->order = "
		CASE
			WHEN tyyppi='yritys' THEN yrityksen_nimi
			WHEN tyyppi='henkilo' THEN etunimi
		END
		";
		*/

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		if(isset($_GET['yrityksen_nimi']) and !empty(trim($_GET['yrityksen_nimi']))){
			$criteria->addCondition (" yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR CONCAT(etunimi , ' ' , sukunimi) LIKE '%".$_GET['yrityksen_nimi']."%' ");
		}
		if(isset($_GET['osoite']) and !empty(trim($_GET['osoite']))){
			$criteria->addCondition ("
			id IN(
				SELECT asiakas_id FROM sivex_kohdet
				WHERE osoite LIKE '%".$_GET['osoite']."%'
			)
			");
		}
		if(isset($_GET['tag']) and !empty(trim($_GET['tag']))){
			$criteria->addCondition ("
			id IN(
				SELECT asiakas_id FROM sivex_kohdet
				WHERE tag_id='".$_GET['tag']."'
			)
			");
		}

		$dataProvider = new CActiveDataProvider('Asiakkaat', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$perSivu = 50;
		if(isset(Yii::app()->user->asiakkaatPerSivu)){
			$perSivu = Yii::app()->user->asiakkaatPerSivu;
		}
		$dataProvider->pagination->pageSize = $perSivu;

		$a = Asetukset::model()->findbypk(1);
		if($a->netvisor_kaytto == 1)
		$netvisor = true;
		else
		$netvisor = false;

		$this->render('tag_report', array(
			'dataProvider' => $dataProvider, 
			'perSivu' => $perSivu
		));
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

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		if(isset($_GET['sort']) and $_GET['sort'] != 'asiakasnumero'){
	        $criteria->order = " $_GET[sort]!='' DESC, $_GET[sort] $_GET[s] ";
		} elseif(isset($_GET['sort']) and $_GET['sort'] == 'asiakasnumero'){
	        $criteria->order = " $_GET[sort]!='' DESC, cast(asiakasnumero as unsigned) $_GET[s] ";
		} else {
	        $criteria->order = " id DESC ";
		}

		if(isset($_GET['osoite']) and !empty($_GET['osoite'])){ $criteria->addCondition (" osoite LIKE '%".$_GET['osoite']."%' "); }
		if(isset($_GET['aktiivinen']) and $_GET['aktiivinen'] != 'kaikki'){
	        	$criteria->addCondition (" aktiivinen ='".(int)$_GET['aktiivinen']."' ");
		} elseif(isset($_GET['aktiivinen']) and $_GET['aktiivinen'] == 'kaikki'){
		        $criteria->addCondition (" (aktiivinen=1 OR aktiivinen=0) ");
		} else {
		        $criteria->addCondition (" aktiivinen=1 ");
		}

		if(isset($_GET['yrityksen_nimi']) and !empty(trim($_GET['yrityksen_nimi']))){
	        	$criteria->addCondition (" yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR CONCAT(etunimi , ' ' , sukunimi) LIKE '%".$_GET['yrityksen_nimi']."%' ");
		}
		if(isset($_GET['ryhma']) and !empty(trim($_GET['ryhma']))){
		        $criteria->addCondition (" ryhma LIKE '%".$_GET['ryhma']."%' ");
		}
		if(isset($_GET['tyoryhma']) and !empty(trim($_GET['tyoryhma']))){
		        $criteria->addCondition (" tyoryhma LIKE '%".$_GET['tyoryhma']."%' ");
		}
		if(isset($_GET['tyyppi']) and !empty(trim($_GET['tyyppi']))){
		        $criteria->addCondition (" tyyppi='".$_GET['tyyppi']."' ");
		}
		if(isset($_GET['puhelin']) and !empty(trim($_GET['puhelin']))){
				$puh = $_GET["puhelin"];
		        $criteria->addCondition (" puhelin LIKE '%$puh%' ");
				$criteria->addCondition(" extra_contacts LIKE '%$puh%' ", "OR");
		}
		if(isset($_GET['sahkoposti']) and !empty(trim($_GET['sahkoposti']))){
		        $criteria->addCondition (" sahkoposti LIKE '%".$_GET['sahkoposti']."%' ");
		}
		if(isset($_GET['asiakasnumero']) and !empty(trim($_GET['asiakasnumero']))){
		        $criteria->addCondition (" asiakasnumero LIKE '%".$_GET['asiakasnumero']."%' ");
		}
		if(isset($_GET['kaupunki']) and !empty(trim($_GET['kaupunki']))){
		        $criteria->addCondition (" kaupunki LIKE '%".$_GET['kaupunki']."%' ");
		}
		if(isset($_GET['postinumero']) and !empty(trim($_GET['postinumero']))){
		        $criteria->addCondition (" postinumero LIKE '%".$_GET['postinumero']."%' ");
		}
		if(isset($_GET['myyja']) and !empty(trim($_GET['myyja']))){
		        $criteria->addCondition (" myyja='".$_GET['myyja']."' ");
		}
		$dataProvider=new CActiveDataProvider('Asiakkaat', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));


		$perSivu = 50;
		if(isset(Yii::app()->user->asiakkaatPerSivu)){
			$perSivu = Yii::app()->user->asiakkaatPerSivu;
		}
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
			'site' => $site
		));
	}

	public function actionKayttajat()
	{

		if(isset($_GET['valmis']))
		{
			Yii::app()->user->setFlash('success', "Tunnukset lähetetty.");
			$this->redirect(array('kayttajat'));
			exit;
		}

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

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		if(isset($_GET['sort']) and $_GET['sort'] != 'asiakasnumero'){
	        $criteria->order = " $_GET[sort]!='' DESC, $_GET[sort] $_GET[s] ";
		} elseif(isset($_GET['sort']) and $_GET['sort'] == 'asiakasnumero'){
	        $criteria->order = " $_GET[sort]!='' DESC, cast(asiakasnumero as unsigned) $_GET[s] ";
		} else {
	        $criteria->order = " id DESC ";
		}

		if(isset($_GET['osoite']) and !empty($_GET['osoite']))
	        $criteria->addCondition (" osoite LIKE '%".$_GET['osoite']."%' ");

		if(isset($_GET['aktiivinen']) and $_GET['aktiivinen'] != 'kaikki')
	        $criteria->addCondition (" aktiivinen ='".(int)$_GET['aktiivinen']."' ");
		elseif(isset($_GET['aktiivinen']) and $_GET['aktiivinen'] == 'kaikki')
	        $criteria->addCondition (" (aktiivinen=1 OR aktiivinen=0) ");
		else
	        $criteria->addCondition (" aktiivinen=1 ");

		if(isset($_GET['yrityksen_nimi']) and !empty(trim($_GET['yrityksen_nimi'])))
	        $criteria->addCondition (" yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR etunimi LIKE '%".$_GET['yrityksen_nimi']."%' OR sukunimi LIKE '%".$_GET['yrityksen_nimi']."%'");

		if(isset($_GET['ryhma']) and !empty(trim($_GET['ryhma'])))
	        $criteria->addCondition (" ryhma LIKE '%".$_GET['ryhma']."%' ");

		if(isset($_GET['tyyppi']) and !empty(trim($_GET['tyyppi'])))
	        $criteria->addCondition (" tyyppi='".$_GET['tyyppi']."' ");

		if(isset($_GET['puhelin']) and !empty(trim($_GET['puhelin'])))
	        $criteria->addCondition (" puhelin LIKE '%".$_GET['puhelin']."%' ");

		if(isset($_GET['sahkoposti']) and !empty(trim($_GET['sahkoposti'])))
	        $criteria->addCondition (" sahkoposti LIKE '%".$_GET['sahkoposti']."%' ");

		if(isset($_GET['asiakasnumero']) and !empty(trim($_GET['asiakasnumero'])))
	        $criteria->addCondition (" asiakasnumero LIKE '%".$_GET['asiakasnumero']."%' ");

		if( isset($_GET['tilanne']) and $_GET['tilanne'] == 1 )
	        $criteria->addCondition (" salasana='' AND token='' AND sahkoposti!=''  ");

		if( isset($_GET['tilanne']) and $_GET['tilanne'] == 2 )
	        $criteria->addCondition (" salasana!='' AND sahkoposti!='' AND app_kayttoehdot IS NULL ");

		if( isset($_GET['tilanne']) and $_GET['tilanne'] == 3 )
	        $criteria->addCondition (" salasana!='' AND sahkoposti!='' AND app_kayttoehdot=1 ");

		if( isset($_GET['tilanne']) and $_GET['tilanne'] == 4 )
	        $criteria->addCondition (" sahkoposti='' ");

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

		$this->render('kayttajat', array(
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

		if(isset($a->tyyppi) and $a->tyyppi == 'henkilo')
			$return = '<b class="text-warning">Yhteyshenkilö</b><br>'.$a->Etusukunimi;
		elseif(isset($a->tyyppi) and $a->tyyppi == 'yritys')
			$return = '<b class="text-success">Yritys</b><br>'.$a->yrityksen_nimi;

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
				 if(is_array($v))
				 {
				  foreach($v as $k1=>$v1)
					$bod .= '<div class="alert bg-warning"><center>'.$v1.'</center></div>';
				 }
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

		krsort($dataArr);


		return $dataArr;
	}

	protected function toteutuneetTunnitCRM($model, $from, $to)
	{

		$cond = "";
		$asetukset = Asetukset::model()->findbypk(1);
		if(empty($asetukset->edico_tehdyt_tyot))
		{
			return '';
		} elseif($asetukset->edico_tehdyt_tyot == 'kirjattu') {
			// ei tarvitse mitaan
		} elseif($asetukset->edico_tehdyt_tyot == 'hyvaksytty') {
			$cond = " AND hyvaksytty!=''";
		} elseif($asetukset->edico_tehdyt_tyot == 'laskutettu') {
			$cond = " AND laskutettu!=0";
		}


		$bod = '';

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
			$cond
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
	  	$bod = '
		<table class="table table-bordered">
		  <tr><td colspan="2"><h3 style="white-space: normal">'.date("d.m.Y", strtotime($data->aloitan)).', '.$data->kohde_kannasta.'</h3></td></tr>
		  <tr><td style="width:10%">'.Yii::t('main', 'Aloitus').'</td> <td>'.date("H:i", strtotime($data->aloitan)).'</td></tr>
		  <tr><td>'.Yii::t('main', 'Lopetus').'</td> <td>'.date("H:i", strtotime($data->loppui)).'</td></tr>
		  <tr><td>'.Yii::t('main', 'Kesto').'</td> <td>'.$this->sprint($kesto).'</td></tr>';


				// <-- Tyoerittelyt
				$tv = Tyovuoroot::model()->findByPk($data->tv_id);
				if(isset($tv->id) and is_array(json_decode($tv->tyo_erittelyt, true))){
				$bod .=	'<tr><td colspan="2">';

					$bod .= '<table class="table">';
					$bod .= '<tr><th>Työtehtävä</th><th>Tilanne</th></tr>';
					foreach(json_decode($tv->tyo_erittelyt, true) as $k => $v){
					$bod .= '<tr><td>'.$v.'</td><td style="width:10%">';
				   	if( isset($data->id) and is_array(json_decode($data->tyo_erittelyt, true)) 
						and in_array($k, json_decode($data->tyo_erittelyt, true)) ){
						$bod .= '<span class="text-success fa fa-check-circle fa-2x"></span>';
				   	} else {
						$bod .= '<span class="text-danger fa fa-times-circle fa-2x"></span>';
					}
				 	$bod .= '</td></tr>';
					}
					$bod .= '</table>';
				$bod .= '</td></tr>';
				}
				//  Tyoerittelyt -->

	  	$bod .= '
		</table>
		<br>
		';
		$dataArr[strtotime($data->aloitan)] = $bod;
		$bod = '';
	  	}
		//  luetut -->


		// <-- toteutuneet
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			kohdenID IN
			(
				SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN(SELECT id FROM asiakkaat WHERE id='".$model->id."')
			) 
			AND loppui!=''
			$cond
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
	  	$bod = '
		<table class="table table-bordered">
		  <tr><td colspan="2"><h3 style="white-space: normal">'.date("d.m.Y", strtotime($data->aloitan)).', '.$data->kohde_kannasta.'</h3></td></tr>
		  <tr><td style="width:10%">'.Yii::t('main', 'Aloitus').'</td> <td>'.date("H:i", strtotime($data->aloitan)).'</td></tr>
		  <tr><td>'.Yii::t('main', 'Lopetus').'</td> <td>'.date("H:i", strtotime($data->loppui)).'</td></tr>
		  <tr><td>'.Yii::t('main', 'Kesto').'</td> <td>'.$this->sprint($kesto).'</td></tr>';


				// <-- Tyoerittelyt
				$tv = Tyovuoroot::model()->findByPk($data->tv_id);
				if(isset($tv->id) and is_array(json_decode($tv->tyo_erittelyt, true))){
				$bod .=	'<tr><td colspan="2">';

					$bod .= '<table class="table">';
					$bod .= '<tr><th>Työtehtävä</th><th>Tilanne</th></tr>';
					foreach(json_decode($tv->tyo_erittelyt, true) as $k => $v){
					$bod .= '<tr><td>'.$v.'</td><td style="width:10%">';
				   	if( isset($data->id) and is_array(json_decode($data->tyo_erittelyt, true)) 
						and in_array($k, json_decode($data->tyo_erittelyt, true)) ){
						$bod .= '<span class="text-success fa fa-check-circle fa-2x"></span>';
				   	} else {
						$bod .= '<span class="text-danger fa fa-times-circle fa-2x"></span>';
					}
				 	$bod .= '</td></tr>';
					}
					$bod .= '</table>';
				$bod .= '</td></tr>';
				}
				//  Tyoerittelyt -->

	  	$bod .= '
		</table>
		<br>
		';
		$dataArr[strtotime($data->aloitan)] = $bod;
		$bod = '';
	  	}
		//  toteutuneet -->

		krsort($dataArr);

		foreach($dataArr as $data)
		{
	  	$bod .= $data;
	  	}


		return $bod;
	}


	protected function tyovuorotCRM($model, $from, $to)
	{

		$from 		= date("Y-m-d", strtotime($from));
		$to 		= date("Y-m-d", strtotime($to));
		$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
		$haku_criteria	= "
			kohde IN
			(
				SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN(SELECT id FROM asiakkaat WHERE id='".$model->id."')
			)
		";
		$dataAll 	= $tyovuorot[0]->FromToSuunnitellutAll($from, $to, [], $haku_criteria, ['data']);

		$bod = '';
		$asetukset=Asetukset::model()->findByPk(1);

		foreach($dataAll as $arr){

				$data = $arr['data'];

				$kesto = strtotime($data->loppu)-strtotime($data->alku);
				$k = Kohteet::model()->findbypk($data->kohde);
				if(isset($k->id)) $osoite = $k->osoite; else $osoite = '';
				$tt = Tyontekijat::model()->findbypk($arr['this_tid']);
				if(isset($tt->id)) $tekijan_nimi = $tt->tekijan_nimi; else $tekijan_nimi = '';
			  	$bod .= '
				<table class="table table-bordered">
					<tr><td colspan="2"><h3>'.date("d.m.Y", strtotime($arr['this_pvm'])).', '.$osoite.'</h3></td></tr>
					<tr><td>'.Yii::t('main', 'Työntekijä').'</td><td>'.$tekijan_nimi.'</td></tr>
					<tr><td>'.Yii::t('main', 'Aloitus').'</td><td>'.$data->alku.'</td></tr>
					<tr><td>'.Yii::t('main', 'Lopetus').'</td><td>'.$data->loppu.'</td></tr>
					<tr><td>'.Yii::t('main', 'Kesto').'</td><td>'.$this->sprint($kesto).'</td></tr>';

				// <-- Tyoerittelyt
				if(is_array(json_decode($data->tyo_erittelyt, true))){
				$bod .=	'<tr><td colspan="2">';

					$bod .= '<table class="table">';
					$bod .= '<tr><th>Työtehtävä</th></tr>';
					foreach(json_decode($data->tyo_erittelyt, true) as $k => $v){
					$bod .= '<tr><td>'.$v.'</td></tr>';
					}
					$bod .= '</table>';
				$bod .= '</td></tr>';
				}
				//  Tyoerittelyt -->

				// <-- peruutus 
				$peruutettu = 0;
				$r = $this->dateDifference(date("Y-m-d", strtotime($data->pvm)), date("Y-m-d") );
				$asetukset=Asetukset::model()->findByPk(1);
				if( $asetukset->peruutta_paiva_ennen > 0 and $r > $asetukset->peruutta_paiva_ennen )
				{
					$peruutettu = 1;
				} else {
					$peruutettu = 2;
				}
				// peruutus -->

				if($data->peruutettu == 1){
			  	$bod .= '<td></td><td><span class="text-danger">'.Yii::t('main', 'Peruutettu').'</span></td></tr>';
				} elseif($data->peruutettu ==2){
			  	$bod .= '<td></td><td><span class="text-danger">'.Yii::t('main', 'Peruutettu laskutettava').'</span></td></tr>';
				} else if($data->peruutettu == 3) {
					$bod .= '<td></td><td><span class="text-danger">'.Yii::t('main', 'Peruutettu, laskutetaan välineet 9,90€').'</span></td></tr>';
				}  else if($data->peruutettu == 4) {
					$bod .= '<td></td><td><span class="text-danger">'.Yii::t('main', 'Peruutettu, laskutetaan välineet 19,90€').'</span></td></tr>';
				} else {
			  	$bod .= '<td></td>
						<td>
							<div id="peruutusehdot" style="display:none">'.$asetukset->peruutusehdot.'</div>
							<button class="btn btn-danger peruuttaa" for="'.$arr['this_id'].'" peruutus_tilanne="'.$peruutettu.'">'.Yii::t('main', 'Peruuta').'</button>
						</td></tr>';
				}
			  	$bod .= '
				</table><br>';
		}


		return $bod;
	}

	protected function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
	{
	    $datetime1 = date_create($date_1);
	    $datetime2 = date_create($date_2);
	    
	    $interval = date_diff($datetime1, $datetime2);
	    
	    return $interval->format($differenceFormat);
	    
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
			$model->asiakas_id=$p->asiakas_id;


			$nimi = '';
			$as = Asiakkaat::model()->findbypk($p->asiakas_id);
			$firma = FirmanTiedot::model()->findbypk(1);

			if(isset($as->id))
				$nimi = $as->Fullname;

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


		   foreach($p as $data)
		   {
	  		$bod .= '<table class="table table-bordered">
			<tr><th>'.Yii::t('main', 'Päiväys').'</th><td>'.date("d.m.Y", strtotime($data->time)).'</td></tr>
			<tr><th>'.Yii::t('main', 'Nimi').'</th><td>'.$data->nimi.'</td></tr>
			<tr><th>'.Yii::t('main', 'Teksti').'</th><td>'.$data->teksti.'</td></tr>';
			if(!isset($_POST['asiakasID']))
		  	$bod .= '<tr><th>'.Yii::t('main', 'Tila').'</th><td>'.$this->VinkitilaMuutos($data->tila).'</td></tr>';
			$bod .= '</table>';
	  	   }

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

	public function asiakkaatArrHelper($aktiivinen)
	{

		$list = array();
		$criteria=new CDbCriteria;

		if($aktiivinen == true)
			$criteria->condition=" aktiivinen=1 ";


		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		$l = Asiakkaat::model()->findAll($criteria);
		$as_arr = array();
		foreach($l as $v)
		{
			$as_arr[$v->Fullname] = $v->id;
		}
		ksort($as_arr);
		foreach($as_arr as $k=>$v)
		{
			$list[$v] = $k;
		}
		
		return $list;
	}

        protected function TyoryhmaName($id){
		$return = '';
		if( !empty($id) ){
		   $v = Valikkoot::model()->findByPk($id);
		   if( isset($v->value) ){ $return = $v->value; }
		}
                return $return;
        }

  //*------------------------------------------------------------------------------------------------
  //* Freshdesk
  //*------------------------------------------------------------------------------------------------
  #region Freshdesk

  /**
   * Main action for the Freshdesk view.
   *
   * Renders the view normally unless some of the parameters are provided. If
   * any parameter is provided, then this action is considered an AJAX action,
   * which will echo the results in JSON encoded format.
   *
   * If parameters are provided for more than one API request, then the first
   * one takes priority and the other ones are ignored.
   *
   * @param int $page
   * If not null, that page in list of tickets is returned. Use negative number
   * to force refresh data (e.g. -1 for first page, -3 for third page).
   *
   * @param int $per_page
   * Specifies the amount of items per page when $page is specified. The maximum
   * seems to be either 100 or 300; however, it's better to do smaller batches.
   *
   * @param array $filter_statuses
   * If not null, this array represents the possible statuses that are requested.
   *   2: Open, 3: Pending, 4: Resolved, 5: Closed,
   *   6: Waiting on customer, 7: Waiting for third party
   *
   * @param string $order_by
   * Sort by option. Options: created_at, due_by, updated_at, status
   * Default sort order is created_at
   *
   * @param string $order_type
   * Order of possible specified sort option. Options: asc, desc
   * Default sort order type is desc
   *
   * @param int $export
   * Exports customers to Freshdesk. If 'all' (string), all customers are
   * exported. If int or array of ints, customers by those ID are exported. If
   * customer already exists in Freshdesk, it is updated with new data.
   *
   * @return mixed
   * Freshdesk view, or null with echoed results if parameters are provided.
   */
  public function actionFreshdesk($page = null, $per_page = 10, $filter_statuses = null, $order_by = null, $order_type = null, $export = null)
  {
    /** @var Freshdesk */
    $freshdesk = Yii::createComponent('Freshdesk');

    if ($freshdesk->isDisabled()) {
      throw new \Exception('Freshdesk on pois päältä tällä domainilla.');
    }

    if (isset($_POST['page']) && is_numeric($_POST['page']))
      $page = $_POST['page'];
    if (isset($_POST['per_page']) && is_numeric($_POST['per_page']))
      $per_page = $_POST['per_page'];

    if (isset($_POST['filter_statuses']))
      $filter_statuses = $_POST['filter_statuses'];
    if (!empty($filter_statuses))
      $filter_statuses = array_unique(json_decode($filter_statuses, true));

    if (isset($_POST['order_by']) && !empty($_POST['order_by']))
      $order_by = $_POST['order_by'];
    if (isset($_POST['order_type']) && !empty($_POST['order_type']))
      $order_type = $_POST['order_type'];
    if (isset($_POST['export']))
      $export = $_POST['export'];

    // If $page is provided, get a list of tickets.
    if (is_numeric($page)) {

      $refresh = ($page < 0);
      $page = abs($page);
      $per_page = is_numeric($per_page) ? $per_page : 10;

      if (!in_array($order_by, ['created_at', 'due_by', 'updated_at', 'status']))
        $order_by = 'updated_at';
      if (!in_array($order_type, ['asc', 'desc']))
        $order_type = 'desc';

      $pager_id = "freshdesk_tickets_orderby_{$order_by}_{$order_type}";

      // if (is_array($filter_statuses)) {
      //   $hash = 0;
      //   foreach ($filter_statuses as $status) {
      //     switch ($status) {
      //       case 2: $hash += 1 << 0; break;
      //       case 3: $hash += 1 << 1; break;
      //       case 4: $hash += 1 << 2; break;
      //       case 5: $hash += 1 << 3; break;
      //       case 6: $hash += 1 << 4; break;
      //       case 7: $hash += 1 << 5; break;
      //     }
      //   }
      //   $pager_id .= "_filter$hash";
      // }

      $pager = $freshdesk->getTicketPaginator($per_page, $pager_id, function ($page, $page_size) use ($freshdesk, $order_by, $order_type) {
        return $freshdesk->listTickets(null, null, $page, $page_size, null, ['requester', 'description'], $order_by, $order_type);
      });

      if ($refresh)
        $pager->delete();

      if (is_array($filter_statuses)) {
        $requested = $pager->filtered($page, function ($item) use ($filter_statuses) {
          return in_array($item['status'], $filter_statuses);
        });
      } else {
        $requested = $pager->getPage($page);
      }

      if (false === $requested) {
        echo json_encode(['eod' => true, 'errors' => 'Empty page requested.']);
        return;
      }

      foreach (array_keys($requested) as $k) {
        $ticket = $requested[$k];
        if (empty($ticket['requester_id']))
          continue;
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
        $criteria->condition = 'freshdesk_id=' . $ticket['requester_id'];
        $aid = Asiakkaat::model()->find($criteria);
        if ($aid)
          $requested[$k]['unique_external_id'] = $aid->id;
      }

      echo json_encode($requested);
      return;
    }

    // If $export is provided, export customers to Freshdesk.
    elseif (is_numeric($export) || is_array($export) || $export == 'all') {
      echo json_encode($freshdesk->exportContact($export, true));
      return;

    } else {

      /** @var CClientScript object. */
      $cs = Yii::app()->getClientScript(); // get clientscript to register css/js
      $base_url = rtrim(Yii::app()->baseUrl, DIRECTORY_SEPARATOR); // trim trailing directory separator just in case
      $css_path = sprintf('%1$s%2$scss%2$sfreshdesk.css', $base_url, DIRECTORY_SEPARATOR); // path to css
      $cs->registerCssFile($css_path); // register css for the view
      $js_path = sprintf('%1$s%2$sjs%2$sfreshdesk.js', $base_url, DIRECTORY_SEPARATOR); // path to js
      $cs->registerScriptFile($js_path, CClientScript::POS_END); // register js for the view

      // Get customers list for the view.
      $criteria = new CDbCriteria();
      $criteria->condition = "(tyyppi = 'yritys' AND (yrityksen_nimi != '' OR sahkoposti != '')) OR (tyyppi = 'henkilo' AND (etunimi != '' OR sahkoposti != ''))";
      $customer_results = Asiakkaat::model()->findAll($criteria);
      foreach ($customer_results as $c)
        $customers[$c->id] = ($c->tyyppi == 'yritys' ? $c->yrityksen_nimi : $c->Etusukunimi) ?: $c->sahkoposti;
      asort($customers);

      // No parameters, move to Freshdesk ticket view.
      return $this->render('freshdesk', [
        'freshdesk' => $freshdesk,
        'tickets' => Yii::app()->session['freshdesk_tickets'],
        'domain' => 'santelo', // temp
        'customers' => $customers
      ]);
    }
  }

  /**
   * Fetches a single ticket from Freshdesk with full (essential) information.
   * Returns server response as is; usually ticket and headers. Server response
   * is echoed out as JSON. This is usually for AJAX requests.
   *
   * If request is successful, response includes:
   *       'ticket' : ticket data array as specified in {@see Freshdesk} class
   *      'headers' : headers returned by the server
   *
   * When there's an error, response includes:
   *     'response' : raw response; can be empty, like when 404
   *      'headers' : headers returned by the server
   *   'error_text' : pre-formed error text, if possible
   *
   * @param mixed $id
   * Remote ID of the ticket that is to be loaded.
   */
  public function actionFreshdesk_ticket($id = null)
  {
    /** @var Freshdesk */
    $freshdesk = Yii::createComponent('Freshdesk');

    if ($freshdesk->isDisabled()) {
      throw new \Exception('Freshdesk on pois päältä tällä domainilla.');
    }

    // Get POST values.
    if (isset($_POST['id']))
      $id = $_POST['id'];

    // Ticket data is requested; fetch it from the API. First, validate ID.
    if (!is_numeric($id) || $id < 0)
      throw new \Exception('Viallinen pyyntö: tukipyynnön ID ei annettu.');

    $headers = null;
    $response = $freshdesk->viewTicket($id, ['conversations', 'requester'], $headers);
    $has_errors = false;
    $error_text = null;

    // Check for errors in the response.
    if (isset($response['errors'])) {

      // Errors in response; build error text.
      $error_text = "Tukipyynnön tietojen hakeminen epäonnistui. Palvelimen palauttamat viestit: '{$response['description']}'";
      $has_errors = true;

      // Errors are usually in an array; make sure to avoid errors.
      if (is_array($response['errors'])) {
        $error_text .= "\n\nVirheet:\n";

        // Create a line per each error.
        foreach ($response['errors'] as $error) {
          if (!empty($error['message']))
            $error_text .= "{$error['message']}";
          if (!empty($error['field']))
            $error_text .= " ({$error['field']})";
          if (!empty($error['code']))
            $error_text .= " -- Virhekoodi: {$error['code']}";
          $error_text .= "\n";
        }
      }
    }

    // Check if the server returned 404, meaning that the ticket was not found.
    if (false !== strpos($headers['http_code'] ?? '', '404')) {

      // Ticket not found; set error text.
      $error_text = "Tukipyyntöä ei löytynyt Freshdeskistä. Annettu tunniste on viallinen.";
      $has_errors = true;
    }

    // Render ticket when style != 'raw' (not required yet).
    // if ($style != 'raw')
    //   return $this->render('freshdesk_ticket', [...]);

    // Output results.
    if ($has_errors) {
      echo json_encode([
        'headers' => $headers,
        'response' => $response,
        'error_text' => $error_text
      ]);
    } else {
      echo json_encode([
        'headers' => $headers,
        'ticket' => $response
      ]);
    }
  }

  #endregion

  /**
   * Find customers with invalid phone numbers on their profile, and fix where
   * possible. Output as CSV the ones that were not automatically fixed.
   */
  public function actionPuhnro_korjaus()
  {
    // Get all customers with no plus sign in front of the phone number. Divide
    // these into valid (starting with 0) and unknown (the rest, which will not
    // be automatically fixed).
    $criteria = new CDbCriteria();
    $criteria->select = 'id, yrityksen_nimi, etunimi, puhelin';
    $criteria->condition = "TRIM(puhelin) != '' AND TRIM(puhelin) NOT LIKE '+%'"; // no area code (not beginning with +)
    $results = Asiakkaat::model()->findAll($criteria);

    // Specify lists for entries to be written to csv after operation.
    // $list_direct_changes = [];  // direct modifications (no extra data).
    // $list_extra_data = [];      // modified numbers with extra data moved to the second field.
    $list_unknown_format = [];  // invalid numbers that must be fixed manually.

    foreach ($results as $customer) {
      // Trim phone number, then remove all whitespace between digits. This only
      // matches a single whitespace character between two digits.
      // (?<= and ?= : positive lookbehind and lookahead)
      $phone_no = preg_replace('/(?<=\d)\s(?=\d)/', '', trim($customer->puhelin));

      // If the number starts with a 0, it can be replaced with the area code.
      // Do not check for extra data yet; match the resulting (trimmed) number
      // afterwards to the desired format, and fix those that match directly.
      if (preg_match('/^0/', $phone_no)) { // starts with a 0
        $phone_no = '+358' . substr($phone_no, 1); // remove first char (0) and prepend area code
      }

      // Number is trimmed, whitespace between digits removed and area code is
      // added if the number started with 0. Now check that the number matches
      // the desired format; if not, the rest of the work must be done manually.

      // Base csv entry line for all outputs:
      $line = [$customer->id, $customer->yrityksen_nimi, $customer->Etusukunimi, $customer->puhelin];

      $matches = [];  // Match the prefixed number and anything else following
      // it, into separate match arrays, in order to save extra
      // data to toissijainen_puhelinnumero field.

      // Match +num and everything else after a possible number.
      preg_match('/^(\+\d+)(.*)$/', $phone_no, $matches);

      // If matches doesn't contain the first capture group, the number is
      // invalid. Otherwise, $matches[1] contains the number with correct format
      // and $matches[2] contains any extra data. Therefore, if there is no
      // extra data, the number can be directly fixed.
      if (empty($matches[1])) {
        // Invalid number.
        $list_unknown_format[] = array_merge($line, ['']);
      } elseif (!empty($matches[2])) {
        // Number has extra data in it; contained in $matches[2].
        // $list_extra_data[] = array_merge($line, [$matches[1], trim($matches[2])]);
        $a = Asiakkaat::model()->findByPk($customer->id);
        $a->saveAttributes([
          'puhelin' => $matches[1],
          'toissijainen_puhelinnumero' => trim($matches[2])
        ]);
      } else {
        // Number is valid and was directly modified (TODO).
        // $list_direct_changes[] = array_merge($line, [$matches[1]]);
        $a = Asiakkaat::model()->findByPk($customer->id);
        $a->saveAttributes([
          'puhelin' => $matches[1]
        ]);
      }

    }

    // Specify file targets and loop entries into them.
    $targets = [
      // 'puhnrokorjaus_direct_changes.csv' => $list_direct_changes,
      // 'puhnrokorjaus_extra_data.csv' => $list_extra_data,
      'puhnrokorjaus_unknown_format.csv' => $list_unknown_format
    ];

    foreach ($targets as $file => $lines) {
      $fh = fopen($file, 'w');

      // Print header line and entries.
      fputcsv($fh, ['id', 'yrityksen_nimi', 'etunimi', 'puhelin_vanha', 'puhelin_muutettu', 'lisatietokenttaan']);
      foreach ($lines as $line)
        fputcsv($fh, $line);

      fclose($fh);
    }
  }

  public function actionEmail_history($recipient = null) {
	if($recipient) {
		$criteria = new CDbCriteria();
		$criteria->condition = "
		log_category = 1 AND
		email_to = '$recipient'";
		$logs = Log::model()->findAll($criteria);

		$json_logs = [];
		
		foreach($logs as $log) {
			$json_logs[] = ["time" => $log->time,
				"email_to" => $log->email_to,
				"email_subject" => $log->email_subject,
				"email_message" => $log->email_message
			];
		}
		echo json_encode($json_logs);
	}
  }

  /**
   * Sends clients data to a integromat web hook (hardcoded address
   * for kotipuhtaaksi, since it's the only company using this integration at the moment)
   * if this becomes something that is widely used, we'd need to make a field
   * to the settings model.
   * 
   * This function is called from actionCreate and actionUpdate.
   * 
   * The endpoint expects the following information:
   * first and last names, phone number, email and postal code
   * active status and whether or not they are a "continous" client (jatkuva asiakas).
   */
  protected function integromatUpsert(Asiakkaat $client) {

	// get first and last name
	$first_name = $client->etunimi;
	$last_name = $client->sukunimi;
	// get phone number
	$phoneNumber = $client->puhelin;
	// return early if phone number isn't at least 13 digits long.
	// that should be a fully defined phone number with +358 country code.
	if(strlen($phoneNumber) < 13) {
		return;
	}

	// get email
	$email = $client->sahkoposti;
	// return early if email is not defined
	if(!$email) {
		return;
	}

	// get postal code
	$postalCode = $client->postinumero;

	// kotipuhtaaksi only: figure out if the client is "jatkuva"
	// $list = array(1=>Yii::t('main', 'Jatkuva'), 2=>Yii::t('main', 'Kerta'), 3=>Yii::t('main', 'Määräaikainen'));
	// echo $form->dropDownList($model, 'sopimustyyppi', $list,
	// array('class'=>'form-control'));	
	// php echo $form->error($model,'sopimustyyppi'); 
	// judging from the clients update form, jatkuva seems to hardcoded to "1"
	$continuous = $client->sopimustyyppi == 1 ? 1 : 0;

	// include whether the client is active/passive, based on aktiivinen field and
	// "lopetuksen_pvm" field. if aktiivinen = 1, we'll also check if lopetuksen_pvm exists.
	$active = $client->aktiivinen;
	if($active) {
		if(strlen($client->lopetuksen_pvm)) {
			$active = 0;
		}
	}

	// build request body
	$body = [
		"phone" => $phoneNumber,
		"first_name" => $first_name,
		"last_name" => $last_name,
		"email" => $email,
		"postalCode" => $postalCode,
		"active" => $active,
		"continuous" => $continuous,
	];

	
	// build request headers
	$headers = ["Content-Type: application/json"];
	
	$url = "https://hook.integromat.com/6xo5bs3p15hrc1wv5ph51i08e0stgxem";
	// open curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// set url
	curl_setopt($ch, CURLOPT_URL, $url);
	// set http verb to POST
	curl_setopt($ch, CURLOPT_POST, true);
	// set post body
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
	// define headers
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// I tnink this actually sets the headers into the request
	// "CURLOPT_HEADER - pass headers to the data stream"
	curl_setopt($ch, CURLOPT_HEADER, true);
	$response = curl_exec($ch);

	// close curl
	curl_close($ch);

	return $response;
  }


  /**
   * Compares attributes that we would send to integromat, if the old models
   * attributes don't match with the new attributes that would be saved to the database,
   * return true for "has changed". Otherwise return false for "not changed".
   * This can be used to reduce the number of updates sent to integromat.
   */
  protected function integromatDataChanged($old_attributes, $new_attributes) {

	if($old_attributes["sahkoposti"] != $new_attributes["sahkoposti"]) {
		return true;
	} else if($old_attributes["etunimi"] != $new_attributes["etunimi"]) {
		return true;
	} else if($old_attributes["sukunimi"] != $new_attributes["sukunimi"]) {
		return true;
	} else if($old_attributes["puhelin"] != $new_attributes["puhelin"]) {
		return true;
	} else if($old_attributes["postinumero"] != $new_attributes["postinumero"]) {
		return true;
	} else if($old_attributes["sopimustyyppi"] != $new_attributes["sopimustyyppi"]) {
		return true;
	} else if($old_attributes["lopetuksen_pvm"] != $new_attributes["lopetuksen_pvm"]) {
		return true;
	} else if($old_attributes["aktiivinen"] != $new_attributes["aktiivinen"]) {
		return true;
	}
	

	return false;
  }
  
  /**
   * Freshdesk customer ticket listing on customer edit page.
   */
  protected function getCustomerFreshdeskTickets($model, $page = 1, $per_page = 10, $filter_statuses = null, $order_by = null, $order_type = null, $export = null)
  {
    $bod = '';
    $customer_id = $model->id;
    $freshdesk_id = $model->freshdesk_id;
    $email = $model->sahkoposti;
    
    if(!$freshdesk_id) {
      return 'Ei tuloksia';
    }
    
    /** @var Freshdesk */
    $freshdesk = Yii::createComponent('Freshdesk');

    if ($freshdesk->isDisabled()) {
      throw new \Exception('Freshdesk on pois päältä tällä domainilla.');
    }

    if (isset($_POST['page']) && is_numeric($_POST['page']))
      $page = $_POST['page'];
    if (isset($_POST['per_page']) && is_numeric($_POST['per_page']))
      $per_page = $_POST['per_page'];

    if (isset($_POST['filter_statuses']))
      $filter_statuses = $_POST['filter_statuses'];
    if (!empty($filter_statuses))
      $filter_statuses = array_unique(json_decode($filter_statuses, true));

    if (isset($_POST['order_by']) && !empty($_POST['order_by']))
      $order_by = $_POST['order_by'];
    if (isset($_POST['order_type']) && !empty($_POST['order_type']))
      $order_type = $_POST['order_type'];
    if (isset($_POST['export']))
      $export = $_POST['export'];

    // If $page is provided, get a list of tickets.
    if (is_numeric($page)) {

      $refresh = ($page < 0);
      $page = abs($page);
      $per_page = is_numeric($per_page) ? $per_page : 10;

      if (!in_array($order_by, ['created_at', 'due_by', 'updated_at', 'status']))
        $order_by = 'updated_at';
      if (!in_array($order_type, ['asc', 'desc']))
        $order_type = 'desc';

      $pager_id = "freshdesk_tickets_orderby_{$order_by}_{$order_type}";
      $pager = $freshdesk->getTicketPaginator($per_page, $pager_id, function ($page, $page_size) use ($freshdesk, $order_by, $order_type, $freshdesk_id, $email) {
        return $freshdesk->listTickets(null, $email, $page, $page_size, null, ['requester', 'description'], $order_by, $order_type);
      });

      if ($refresh)
        $pager->delete();

      if (is_array($filter_statuses)) {
        $requested = $pager->filtered($page, function ($item) use ($filter_statuses) {
          return in_array($item['status'], $filter_statuses);
        });
      } else {
        $requested = $pager->getPage($page);
      }

      if (false === $requested) {
        return 'Ei tuloksia';
      }
      
      $status_array = array(2 => 'Open', 3 => 'Pending', 4 => 'Resolved', 5 => 'Closed', 6 => 'Waiting on Customer', 7 => 'Waiting on Third Party');
      foreach ($requested as $k) {
        if (empty($k['requester_id']))
          continue;
        
        $ticket_id = $k['id'];
        $subject = $k['subject'];
        $description = $k['description_text'];
        $due_by = $k['due_by'] ? date('D, j M Y, g:s A') : '';
        $status = isset($status_array[$k['status']]) ? $status_array[$k['status']] : '';

        $bod .= '<table class="table table-bordered">
        <tr><th width="20%">'.Yii::t('main', 'Subject').'</th><td width="80%"><a href="https://santelo.freshdesk.com/a/tickets/'.$ticket_id.'" target="_blank">'.$subject.'</a></td></tr>
        <tr><th width="20%">'.Yii::t('main', 'Description').'</th><td width="80%">'.$description.'</td></tr>'
      . '<tr><th width="20%">'.Yii::t('main', 'Due Date').'</th><td width="80%">'.$due_by.'</td></tr>'
      . '<tr><th width="20%">'.Yii::t('main', 'Status').'</th><td width="80%">'.$status.'</td></tr>';
        
        $bod .= '</table>';
      }
    }
    
    if(empty($bod))
      return 'Ei tuloksia';
		else
      return $bod;
  }

	/**
	 * Renders a view which lists clients that have no cost center (netvisor_dimension_item) or workgroup (tyoryhma) set.
	 * Also renders clients that have the wrong workgroup compared to their set cost center.
	 * The values are hardcoded for Koti Puhtaaksi, which makes this action Koti Puhtaaksi only.
	 */
  	public function actionCheckWorkgroups() {

		$clients = Asiakkaat::model()->findAll("aktiivinen = 1");

		// 156 = Jyvaskyla
		// 170 = Keski-uusimaa
		// 135 = Oulu
		// 97 = Turku
		// 6 = Uusimaa
		// 33 = Pirkanmaa

		$groups = Valikkoot::model()->findAll("select_type='tyoryhma'");

		$workGroupNames = [];
		foreach($groups as $group) {
			$workGroupNames[$group->id] = $group->value;
		}

		// cost center => work group ID
		$criteriaMap = [
			//"P-Uusimaa" => [170],
			"PK-Seutu" => [6],
			"Oulu" => [135],
			"Turku" => [97],
			//"Tampere" => [33],
			"Jyväskylä" => [156],
			"Pirkanmaa" => [33],
			"KESU" => [170],
		];

		$missingDataClients = [];
		$wrongDataClients = [];
		foreach ($clients as $client) {
			$netvisor_cost_center = $client->netvisor_dimension_item;
			$work_group = $client->tyoryhma;

			if($netvisor_cost_center && $work_group >= 0) {
				if(array_key_exists($netvisor_cost_center, $criteriaMap)) {
					$found = in_array($work_group, $criteriaMap[$netvisor_cost_center]);
					if(!$found) {
						// wrong work group
						$wrongDataClients[] = $client;
					}
				} else {
					// un-used cost center?
					$wrongDataClients[] = $client;
				}
			} else {
				$missingDataClients[] = $client;
			}
		}

		$this->render("check_workgroups", 
			[
				"missingDataClients" => $missingDataClients,
			 	"wrongDataClients" => $wrongDataClients,
				"workgroupNames" => $workGroupNames,
			]
		);
	}

}
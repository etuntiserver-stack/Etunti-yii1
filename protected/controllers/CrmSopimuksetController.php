<?php

class CrmSopimuksetController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('vastaus', 'success', 'cancel', 'vanhentunut'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view', 'laheta', 'get_tarjous'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function isEtuntiAdmin() {

	$tas = '';
	if(isset(Yii::app()->user->adminPaketti))
	$tas = explode(",",Yii::app()->user->adminPaketti);

		if(isset(Yii::app()->user->adminID) and in_array('5',$tas))
		{
		$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	       	if($m->id == Yii::app()->user->adminID)
	       	  return true;
		else
	       	   return false;		

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
                } else {
                        Yii::app()->theme = 'classic';
                }
                parent::init();
        }


	public function actionVastaus($asia, $id, $code)
	{
		Yii::app()->theme = 'classic';
		$crm = CrmSopimukset::model()->findbypk($id);
		if($asia == 'hyvaksy' and isset($crm->id) and $crm->hyvaksyn_koodi == $code and $crm->status == 1){

			CrmSopimukset::model()->updatebypk($id, array('status'=>2));

			// <-- Luodaan Asiakas ja kohde jos Yhteystiedot kautta
			if($crm->yhteystiedot_id != 0 and $crm->tarjous_id != 0)
			{
				$tarjous = CrmTarjoukset::model()->findbypk($crm->tarjous_id);
				$yhteystiedot = Yhteystiedot::model()->findbypk($crm->yhteystiedot_id);

				if(isset($tarjous->id) and $tarjous->status != 2)
				die('Tarjous ei hyväksytty');

				if(isset($tarjous->id) and isset($yhteystiedot->id) and $tarjous->status == 2)
				{
					$asiakkaat = new Asiakkaat;
					$asiakkaat->attributes=$yhteystiedot->attributes;
					$asiakkaat->aktiivinen=1;
					$asiakkaat->tyyppi=$yhteystiedot->yhteystieto_tyyppi;
					$asiakkaat->kaupunki=$yhteystiedot->postitoimipaikka;
					if($asiakkaat->save())
					{

						$kohteet = new Kohteet;
						$kohteet->asiakas_id = $asiakkaat->id;
						if(!empty($asiakkaat->yrityksen_nimi))
							$kohteet->etu_suku_nimet = $asiakkaat->yrityksen_nimi;
						elseif(empty($asiakkaat->yrityksen_nimi) and !empty($asiakkaat->yhteyshenkilo))
							$kohteet->etu_suku_nimet = $asiakkaat->yhteyshenkilo;
					
						if( $tarjous->onko_osoite_sama == 'ei')
						{
							$kohteet->osoite = $tarjous->kohteen_osoite;
							$kohteet->pnumero = $tarjous->kohteen_postinumero;
							$kohteet->kaupunki = $tarjous->kohteen_postitoimipaikka;

						} else {
							$kohteet->osoite = $asiakkaat->osoite;
							$kohteet->pnumero = $asiakkaat->postinumero;
							$kohteet->kaupunki = $asiakkaat->kaupunki;
						}
						$kohteet->puh_nro = $asiakkaat->puhelin;
						$kohteet->email = $asiakkaat->sahkoposti;
						if(!$kohteet->save())
						{
							var_dump($kohteet->getErrors());
							exit;
						}

					} else {
						var_dump($asiakkaat->getErrors());
						exit;
					}
				}
			}
			// Luodaan Asiakas ja kohde jos Yhteystiedot kautta -->

			$this->redirect(array('success'));

		} elseif($asia == 'hylatty' and isset($crm->id) and $crm->hyvaksyn_koodi == $code and $crm->status == 1){

			CrmSopimukset::model()->updatebypk($id, array('status'=>3));
			$this->redirect(array('cancel'));
		} else {
			$this->redirect(array('vanhentunut'));
		}
	
	}

	public function actionSuccess()
	{
		Yii::app()->theme = 'classic';
		$this->render('success');		
	}

	public function actionCancel()
	{
		Yii::app()->theme = 'classic';
		$this->render('cancel');		
	}

	public function actionVanhentunut()
	{
		Yii::app()->theme = 'classic';
		$this->render('vanhentunut');		
	}

	protected function generateRandomString($length = 40) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function actionLaheta()
	{
		if(isset($_POST['id']))
		{
			
		$crm = CrmSopimukset::model()->findbypk($_POST['id']);
		$randstring = $this->generateRandomString();



		/* file */
		$file = $crm->liite.'.pdf';
		$path = Yii::app()->request->baseUrl."tiedostot/sopimukset/".Yii::app()->user->domain;

		$ft = FirmanTiedot::model()->findbypk(1);
		$message = '<h1>'.Yii::t('main', 'Sopimus').'</h1>';
		$message .= '<br>
		<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php/CrmSopimukset/vastaus?asia=hyvaksy&id='.$_POST['id'].'&code='.$randstring.'">
				<h2 style="color:green">'.Yii::t('main', 'Hyväksy').'</h2>
		</a>
		<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php/CrmSopimukset/vastaus?asia=hylatty&id='.$_POST['id'].'&code='.$randstring.'">
				<h4 style="color:red">'.Yii::t('main', 'Hylkää').'</h4>
		</a>
		';
		


		$subject = Yii::t('main', 'Sopimus'). ', '.$ft->tyonantaja;
		$mail = new YiiMailer();
		$mail->setFrom('no-reply@etunti.fi');
		$mail->setTo($crm->asiakkaan_sahkoposti);
		$mail->setSubject($subject);
		$mail->setBody($message);

		$tkPDF = '';
		if($crm->tarjous->tyonkuvaus_id != 0)
		{
	   		$tk_controller = Yii::app()->createController('Tyonkuvaus');
	   		$tkPDF = $tk_controller[0]->PdfOpener($crm->tarjous->tyonkuvaus_id, 'getFile');
   			if(file_exists(Yii::app()->basePath."/../".$tkPDF))
			{
				$mail->addAttachment($tkPDF);
			}
		}


   		if(file_exists(Yii::app()->basePath."/../tiedostot/sopimukset/".Yii::app()->user->domain."/".$crm->liite.".pdf"))
		{
			$mail->addAttachment($path.'/'.$file);
			//echo $message;
			//exit;
		}

		if($mail->send())
		{
	

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $crm->asiakkaan_sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->


			CrmSopimukset::model()->updatebypk($_POST['id'], array('status'=>1,'hyvaksyn_koodi'=>$randstring));
			$this->redirect(array('index'));
		}

		}

	}

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
		$model=new CrmSopimukset;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);


		if(isset($_POST['CrmSopimukset']))
		{

			$model->attributes=$_POST['CrmSopimukset'];

			if($model->save()){

				// <-- Tiedoston nimi
				$tiedosto = 'Sopimus';
				if(isset($model->id))
				{
					$site = Yii::app()->createController('Site');
  					$tiedosto = $site[0]->tiedostonNimiAsiakasKohdeAika($tiedosto, $model->asiakas_id, $model->tarjous->kohteen_osoite, $model->time);
				}
				//     Tiedoston nimi -->
				CrmSopimukset::model()->updateByPk($model->id, array('liite'=>$tiedosto));

				$as = Asiakkaat::model()->findbypk($model->asiakas_id);
				if(isset($as->sahkoposti))
					CrmSopimukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$as->sahkoposti));

				$this->docx($model, $tiedosto);

			}

		} 

		$this->render('create',array(
			'model'=>$model
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);


		if(isset($_POST['CrmSopimukset']))
		{

			$model->attributes=$_POST['CrmSopimukset'];
			if($model->save()){


				// <-- Tiedoston nimi
				$tiedosto = 'Sopimus';
				if(isset($model->id))
				{
					$site = Yii::app()->createController('Site');
  					$tiedosto = $site[0]->tiedostonNimiAsiakasKohdeAika($tiedosto, $model->asiakas_id, $model->tarjous->kohteen_osoite, $model->time);
				}
				//     Tiedoston nimi -->
				CrmSopimukset::model()->updateByPk($model->id, array('liite'=>$tiedosto));

				$as = Asiakkaat::model()->findbypk($model->asiakas_id);
				if(isset($as->sahkoposti))
					CrmSopimukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$as->sahkoposti));

				$this->docx($model, $tiedosto);
			}
		}

		$this->render('update',array(
			'model'=>$model
		));
	}

	protected function template_variables()
	{
		$var = '
		#paivays#
		#voimassa#
	
		#yritys#
		#yrityksen_osoite#
		#yrityksen_postinumero#
		#yrityksen_toimipaikka#
		#yrityksen_puhelin#
		#yrityksen_email#
		#yrityksen_yhteyshenkilo#
		#yrityksen_y_tunnus#

		#asiakas#
		#asiakkaan_osoite#
		#asiakkaan_postinumero#
		#asiakkaan_toimipaikka#
		#asiakkaan_puhelin#
		#asiakkaan_email#

		#teksti#
		
		#hinta_tyyppi#
		#hinta#
		#alv#
		#viivastyskorko#
		#maksuehto#
		#kohteen_osoite#
		#kohteen_postinumero#
		#kohteen_postitoimipaikka#

		#tyonantajan_edustaja#
		#tuote_palvelu#

		#prices_table#
		#tyonkuvaus#
		';

		return $var;

	}

	protected function kansio()
	{
		return 'sopimukset';
	}

	protected function templates_polkku()
	{
		return 'tiedostot/templates/'.Yii::app()->user->domain.'/'.$this->kansio().'/';
	}

	protected function valmiit_polkku()
	{
		return 'tiedostot/'.$this->kansio().'/'.Yii::app()->user->domain;
	}


	protected function docx($model, $tiedosto)
	{

	
			if (!file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku() )) {
			 	mkdir( Yii::app()->basePath.'/../'.$this->valmiit_polkku(), 0777, true );
			}

			$firma = FirmanTiedot::model()->findbypk(1);

			// <-- Jos se on Asiakas

				$asiakkaan_osoite 	= '';
				$asiakkaan_postinumero 	= '';
				$asiakkaan_toimipaikka 	= '';
				$asiakkaan_puhelin 	= '';
				$asiakkaan_email	= '';

			$as = Asiakkaat::model()->findbypk($model->tarjous->asiakas_id);
			if(isset($as->id))
			{
				if(isset($as->id) and !empty($as->yrityksen_nimi))
				   $asiakas = $as->yrityksen_nimi;
				elseif(isset($as->id) and empty($as->yrityksen_nimi) and !empty($as->yhteyshenkilo)) 
				   $asiakas = $as->yhteyshenkilo;
				else
				   $asiakas = '';

				$asiakkaan_osoite 	= $as->osoite;
				$asiakkaan_postinumero 	= $as->postinumero;
				$asiakkaan_toimipaikka 	= $as->kaupunki;
				$asiakkaan_puhelin 	= $as->puhelin;
				$asiakkaan_email	= $as->sahkoposti;
			}
			//     Jos se on Asiakas -->



			define('PHPDOCX_INCLUDE_PATH', (dirname(Yii::app()->basePath)).'/protected/vendors/phpdocx');
			spl_autoload_unregister(array('YiiBase','autoload'));
			require_once PHPDOCX_INCLUDE_PATH.'/lib/pdf/dompdf_config.inc.php';
			//require_once PHPDOCX_INCLUDE_PATH.'/classes/TransformDocAdv.inc';
			require_once PHPDOCX_INCLUDE_PATH.'/classes/CreateDocx.inc';
			spl_autoload_register(array('AutoLoader','load'));
			spl_autoload_register(array('YiiBase', 'autoload'));

			$template_tiedosto = $this->templates_polkku().$model->template;

			$asetukset = Asetukset::model()->findByPk(1);

			$docx = new CreateDocxFromTemplate($template_tiedosto);
			//$docx->enableCompatibilityMode();
			$docx->setTemplateSymbol('#');
			$variables = array(
				'paivays' => date("d.m.Y"),
				'voimassa' => $model->voimassa,
				'asiakas' => $asiakas,
				'asiakkaan_osoite' => $asiakkaan_osoite,
				'asiakkaan_postinumero' => $asiakkaan_postinumero,
				'asiakkaan_toimipaikka' => $asiakkaan_toimipaikka,
				'asiakkaan_puhelin' => $asiakkaan_puhelin,
				'asiakkaan_email' => $asiakkaan_email,
				'yritys' => $firma->tyonantaja,
				'yrityksen_osoite' => $firma->osoite,
				'yrityksen_postinumero' => $firma->postinumero,
				'yrityksen_toimipaikka' => $firma->postitoimipaikka,
				'yrityksen_y_tunnus' => $firma->y_tunnus,
				'yrityksen_puhelin' => $firma->puhelin,
				'yrityksen_email' => $firma->sahkoposti,
				'yrityksen_yhteyshenkilo' => $firma->johtaja,
				'yrityksen_y_tunnus' => $firma->y_tunnus,
				'teksti' => $model->teksti,
			);

			$tk = Tyonkuvaus::model()->findByPk($model->tarjous->tyonkuvaus_id);
			if(isset($tk->id))
			{
				$site = Yii::app()->createController('Site');
  				$tk_tiedosto = $site[0]->tiedostonNimiAsiakasKohdeAika($tiedosto, $tk->asiakas_id, $tk->kohde_id, $tk->time);
				$variables['tyonkuvaus'] = 'On kuvattu liitessä. '.$tk_tiedosto.'.pdf';
			}

			$docx->replaceVariableByText($variables);

			$a = Asiakkaat::model()->findByPk($model->tarjous->asiakas_id);
			$k = Kohteet::model()->findByPk($model->tarjous->kohde_id);
			$tarvikkeet = array();
			if( is_array(json_decode($model->tarjous->tarvikkeet, true)) )
			{
				foreach(json_decode($model->tarjous->tarvikkeet, true) as $l)
				{
	      				$v = Valikkoot::model()->findByPk($l);
					if( isset($v->id) )
					array_push($tarvikkeet, $v->value);
				}
			}

			$variables_2 = array(
				'hinta_tyyppi' => $model->tarjous->hinta_tyyppi,
				'hinta' => $model->tarjous->hinta,
				'alv' => $model->tarjous->alv,
				'kohteen_osoite' => $model->tarjous->kohteen_osoite,
				'kohteen_postinumero' => $model->tarjous->kohteen_postinumero,
				'kohteen_postitoimipaikka' => $model->tarjous->kohteen_postitoimipaikka,
				'tyonantajan_edustaja' => $asetukset->johtaja,
				'tarvikkeet' => implode(", ", $tarvikkeet),
				'maksuehto' => $a->maksuehto,
				'viivastyskorko' => $a->viivastyskorko,
				'tuote_palvelu' => $model->tarjous->tuote_palvelu,
			);
			$docx->replaceVariableByText($variables_2);


			$tarjoukset = Yii::app()->createController('CrmTarjoukset');
			$tb = $tarjoukset[0]->hinnatTaulu($model->tarjous_id);
			$docx->replaceVariableByHTML('prices_table', 'block', $tb, array('parseDivsAsPs' => true));

			$path = 'tiedostot/'.$this->kansio().'/'.Yii::app()->user->domain.'/'.$tiedosto;
			$docx->createDocx($path);

			$transform = new TransformDocAdvLibreOffice();
			$transform->transformDocument($path.'.docx', $path.'.pdf');

			$this->redirect(array('index'));
	}

	public function actionDelete($id)
	{
		// <-- tiedoston poistaminen
		$model = $this->loadModel($id);
		$model->delete();
		if (file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku().'/'.$model->liite.'.docx' )) 
			unlink(Yii::app()->baseUrl.$this->valmiit_polkku().'/'.$model->liite.'.docx');
		if (file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku().'/'.$model->liite.'.pdf' )) 
			unlink(Yii::app()->baseUrl.$this->valmiit_polkku().'/'.$model->liite.'.pdf');
		//     tiedoston poistaminen -->

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
/*
	// <-- Oikeudet
	   $checkOikeus = "asiakkaat_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->
*/

		if( Yii::app()->request->getPost('poistaTemplate') ){
			unlink( Yii::app()->request->getPost('poistaTemplate') );
			exit;
		}

		if( isset($_POST['file_upload']) )
		{

			if (!file_exists( Yii::app()->basePath.'/../'.$this->templates_polkku() )) {
				mkdir( Yii::app()->basePath.'/../'.$this->templates_polkku(), 0777, true );
			}

			$uploaddir = Yii::app()->basePath.'/../'.$this->templates_polkku();
			$uploadfile = $uploaddir . basename($_FILES["file"]["name"]);
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		
			} else {
				echo Yii::t('main', 'Lataaminen ei onnistuu');
		    	}
		}

       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";


		$dataProvider=new CActiveDataProvider('CrmSopimukset', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;

		$tal = $this->tal();

		$this->render('index', array(
			'dataProvider' => $dataProvider,
			'tal' => $tal,
		));
	}

	public function actionGet_tarjous($asiakas_id)
	{
		$bd = '<option value=>'.Yii::t('main', 'Valitse').'</options>';
		$data = CrmTarjoukset::model()->findAll(" asiakas_id='".$asiakas_id."' ");
		foreach($data as $item){
			$bd .= '<option value="'.$item->id.'">'.date("d.m.Y H:i", strtotime($item->time)).', '.$item->kohteen_osoite.'</option>';
		}

		$result = array(
			'options'=>$bd
		);
		echo json_encode($result);
	}


	protected function tal()
	{

        	$tal = array(
			'palvelusopimus_kuluttajat'=>Yii::t('main', 'Palvelusopimus kuluttajat'),
			'sosiaalialan_palvelusopimus'=>Yii::t('main', 'Sosiaalialan palvelusopimus'),
			'palvelusopimus_novosan'=>Yii::t('main', 'Palvelusopimus Novosan'),
			'avainten_luovutussopimus'=>Yii::t('main', 'Avainten luovutussopimus'),
			'hotelfinn_helsinki_siivousehdotus'=>Yii::t('main', 'Hotelfinn Helsinki siivousehdotus'),
		);
		return $tal;
	}


	public function actionAdmin()
	{
		$model=new CrmSopimukset('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CrmSopimukset']))
			$model->attributes=$_GET['CrmSopimukset'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CrmSopimukset the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CrmSopimukset::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CrmSopimukset $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='crm-tarjoukset-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

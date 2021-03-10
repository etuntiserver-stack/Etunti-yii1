<?php

class CrmTarjouksetController extends Controller
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
				'actions'=>array('success', 'cancel', 'vanhentunut'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view', 'laheta', 'get_tyonkuvaus_by_asiakas', 'get_tyonkuvaus_by_id', 'get_tarjouslaskenta_by_id', 'get_tyonkuvaus', 'get_kohteentiedot', 'get_asiakastilat', 'view_tyonkuvaus', 'get_kohde', 'tr_rivit_tyhja'),
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

	public function actionGet_asiakastilat($asiakastila)
	{
		$return = '';

		$criteria=new CDbCriteria;
		$criteria->condition=" asiakastila='".$asiakastila."' ";
      		$l = Asiakkaat::model()->findAll($criteria);
		$list = array();
		foreach($l as $v)
			$list[$v->id] = $v->Fullname;

		if(count($list) > 0)
		{
			$return .= CHtml::dropDownList('CrmTarjoukset[asiakas_id]', 'asiakas_id', $list,
			array('empty'=>'Valitse','class'=>'form-control'));
		}
		
		echo json_encode($return);

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

	public function actionLaheta($id)
	{

		$crm = CrmTarjoukset::model()->findbypk($id);
		$randstring = $this->generateRandomString();

		/* file */
		$file = $crm->liite.'.pdf';
		$path = Yii::app()->request->baseUrl."tiedostot/tarjoukset/".Yii::app()->user->domain;

		$ft = FirmanTiedot::model()->findbypk(1);

		$message = '
		<p>
Olet saanut tarjouksen yritykseltä '.$ft->tyonantaja.'. Tarjous löytyy tiedostosta, joka on tämän viestin liitteenä.
Tutustu tarjoukseen ja vahvista päätöksesi ilmoittamalla siitä tarjouksessa ilmoitetulle henkilölle. Helpoimmin hyväksyt tai hylkäät tarjouksen eDico sovelluksessa.

Ystävällisin terveisin.
'.$ft->tyonantaja.'
		</p>
		<center>
		<p>
		<span>
		<a href="https://play.google.com/store/apps/details?id=fi.etunti.dico&utm_source=global_co&utm_medium=prtnr&utm_content=Mar2515&utm_campaign=PartBadge&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1"><img alt="Get it on Google Play" src="'.Yii::app()->request->hostInfo.'/lib/app/google-play.jpg" style="height:100px" /></a>
		</span>

		<span>
		<a href="https://geo.itunes.apple.com/fi/app/etunti/id1100648690?mt=8"><img src="'.Yii::app()->request->hostInfo.'/lib/app/app-ios.jpg" style="height:100px" ></a>
		</span>
		</p>
		</center>
				';


		//echo $message;
		//exit;


		$subject = Yii::t('main', 'Tarjous'). ', '.$ft->tyonantaja;
		$mail = new YiiMailer();
		//$mail->clearLayout();//if layout is already set in config
		$mail->setFrom('no-reply@etunti.fi');
		$mail->setTo($crm->asiakkaan_sahkoposti);
		$mail->setSubject($subject);
		$mail->setBody($message);

		$tkPDF = '';

		$tk = Tyonkuvaus::model()->find(" id='".$crm->tyonkuvaus_id."' AND aktiivinen=1 ");
		if(isset($tk->id))
		{
	   		$tk_controller = Yii::app()->createController('Tyonkuvaus');
	   		$tkPDF = $tk_controller[0]->PdfOpener($crm->tyonkuvaus_id, 'getFile');
   			if(file_exists(Yii::app()->basePath."/../".$tkPDF))
			{
				$mail->addAttachment($tkPDF);
			}
		}


   		if(file_exists(Yii::app()->basePath."/../tiedostot/tarjoukset/".Yii::app()->user->domain."/".$crm->liite.".pdf"))
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


			CrmTarjoukset::model()->updatebypk($id, array('status'=>1,'hyvaksyn_koodi'=>$randstring));
			$this->redirect(array('index'));
		}
   		


	}


	public function actionGet_kohde($id)
	{
		$asiakas_tiedot = '';
		$asiakas_sahkoposti = '';
		$a = Asiakkaat::model()->findByPk($id);
		if(isset($a->id))
		{
			$asiakas_sahkoposti = $a->sahkoposti;
			$asiakas_tiedot = $this->renderPartial('//asiakkaat/view', 
				array('id'=>$a->id, 'model'=>$a)
			, true);
		}

		$bd = '<option value="0">Valitse</option>';
		$data = Kohteet::model()->findAll(" asiakas_id='".$id."' ");
		foreach($data as $item){
			$bd .= '<option value="'.$item->id.'">'.$item->osoite.'</option>';
		}

		$result = array(
			'options'=>$bd, 
			'asiakas_sahkoposti' => $asiakas_sahkoposti,
			'asiakas_tiedot' => $asiakas_tiedot
		);
		echo json_encode($result);
	}

	public function actionGet_tyonkuvaus($id)
	{
		$model = Tyonkuvaus::model()->findByPk($id);
		if(isset($model->id))
			echo json_encode($this->get_tyonkuvaus($id));

	}

	public function actionGet_kohteentiedot($id)
	{
		$kohde = array();
		$tk = '';
		$k = Kohteet::model()->findByPk($id);
		if(isset($k->id))
		{
			$kohde = $k->attributes;

			$data = Tyonkuvaus::model()->findAll(" kohde_id='".$k->id."' ");
			if( count($data) > 0 )
			{
			   $tk .= '<option value="0">Valitse</option>';
			   foreach($data as $item){
				$tk .= '<option value="'.$item->id.'">'.date("d.m.Y", strtotime($item->time)).' - '.$k->osoite.'</option>';
			   }
			}
		}
		$result = array('kohde'=>$kohde, 'tk'=>$tk);
		echo json_encode($result);
	}

	public function actionView_tyonkuvaus($id)
	{
		echo json_encode($this->get_tyonkuvaus($id));
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionTr_rivit_tyhja()
	{
		$this->renderPartial('tr_rivit_tyhja');
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CrmTarjoukset;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['CrmTarjoukset']))
		{

			$model->attributes=$_POST['CrmTarjoukset'];
			if(isset($_POST['CrmTarjoukset']['tarvikkeet']))
				$model->tarvikkeet=json_encode($_POST['CrmTarjoukset']['tarvikkeet']);
			else
				$model->tarvikkeet="";
			if($model->save()){


				// <-- Hinta
				if(isset($_POST['tkoodi']))
				{
				  foreach($_POST['tkoodi'] as $key=>$val)
				  {
					$lr = new TarjousHintaRivit;
					$lr->tarjous_id	=$model->id;
					$lr->rivi	=$key;
					$lr->tkoodi	=$_POST['tkoodi'][$key];
					//$lr->free_text	=$_POST['free_text'][$key];
					$lr->kpl	=$_POST['kpl'][$key];
					$lr->yksikko	=$_POST['yksikko'][$key];
					$lr->hinta	=$_POST['hinta'][$key];
					$lr->alv	=$_POST['alv'][$key];
					$lr->hinta_alv	=$_POST['hinta_alv'][$key];
					$lr->ale	=$_POST['ale'][$key];
	
					if(isset($_POST['tuoteID']))
						$lr->tuoteID	=$_POST['tuoteID'][$key];
	
					$lr->veroton	=$_POST['veroton'][$key];
					$lr->yhteensa_alv=$_POST['yhteensa_alv'][$key];
					$lr->save();
				  }
				}
				//     Hinta -->


				// <-- Tiedoston nimi
				$tiedosto = 'Tarjous';
				if(isset($model->id))
				{
					$new_model = CrmTarjoukset::model()->findByPk($model->id);
					$site = Yii::app()->createController('Site');
  					$tiedosto = $site[0]->tiedostonNimiAsiakasKohdeAika($tiedosto, $model->asiakas_id, $model->kohde_id, $new_model->time);
				}
				//     Tiedoston nimi -->

				CrmTarjoukset::model()->updateByPk($model->id, array('liite'=>$tiedosto));

				$as = Asiakkaat::model()->findbypk($model->asiakas_id);
				if(isset($as->sahkoposti))
					CrmTarjoukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$as->sahkoposti));

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

	
		if(isset($_POST['CrmTarjoukset']))
		{

			$model->attributes=$_POST['CrmTarjoukset'];

			if(isset($_POST['CrmTarjoukset']['tarvikkeet']))
				$model->tarvikkeet=json_encode($_POST['CrmTarjoukset']['tarvikkeet']);
			else
				$model->tarvikkeet="";

			if($model->save()){

				// <-- Hinta
				TarjousHintaRivit::model()->deleteAll("tarjous_id='".$model->id."'");
				if(isset($_POST['tkoodi']))
				{
				  foreach($_POST['tkoodi'] as $key=>$val)
				  {
					$lr = new TarjousHintaRivit;
					$lr->tarjous_id	=$model->id;
					$lr->rivi	=$key;
					$lr->tkoodi	=$_POST['tkoodi'][$key];
					//$lr->free_text	=$_POST['free_text'][$key];
					$lr->kpl	=$_POST['kpl'][$key];
					$lr->yksikko	=$_POST['yksikko'][$key];
					$lr->hinta	=$_POST['hinta'][$key];
					$lr->alv	=$_POST['alv'][$key];
					$lr->hinta_alv	=$_POST['hinta_alv'][$key];
					$lr->ale	=$_POST['ale'][$key];
	
					if(isset($_POST['tuoteID']))
						$lr->tuoteID	=$_POST['tuoteID'][$key];
	
					$lr->veroton	=$_POST['veroton'][$key];
					$lr->yhteensa_alv=$_POST['yhteensa_alv'][$key];
					$lr->save();
				  }
				}
				//     Hinta -->


				// <-- Tiedoston nimi
				$tiedosto = 'Tarjous';
				if(isset($model->id))
				{
					$site = Yii::app()->createController('Site');
  					$tiedosto = $site[0]->tiedostonNimiAsiakasKohdeAika($tiedosto, $model->asiakas_id, $model->kohde_id, $model->time);
				}
				//     Tiedoston nimi -->
				CrmTarjoukset::model()->updateByPk($model->id, array('liite'=>$tiedosto));

				$as = Asiakkaat::model()->findbypk($model->asiakas_id);
				$y = Yhteystiedot::model()->findbypk($model->yhteystiedot_id);
				if(isset($as->sahkoposti))
					CrmTarjoukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$as->sahkoposti));

				if(isset($y->sahkoposti))
					CrmTarjoukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$y->sahkoposti));

				$this->docx($model, $tiedosto);
			
			} else {
				var_dump($model->getErrors());
				exit;
			}
		}

		$this->render('update',array(
			'model'=>$model,
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

		#asiakas#
		#asiakkaan_osoite#
		#asiakkaan_postinumero#
		#asiakkaan_toimipaikka#

		#teksti#
		#tyonkuvaus#
		
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
		';

		return $var;

	}

	protected function kansio()
	{
		return 'tarjoukset';
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
			$as = Asiakkaat::model()->findbypk($model->asiakas_id);
			if(isset($as->id))
			{
				if(isset($as->id))
				   $asiakas = $as->Fullname;
				else
				   $asiakas = '';

				$asiakkaan_osoite = $as->osoite;
				$asiakkaan_postinumero = $as->postinumero;
				$asiakkaan_toimipaikka = $as->kaupunki;
			}
			//     Jos se on Asiakas -->



			define('PHPDOCX_INCLUDE_PAth', (dirname(Yii::app()->basePath)).'/protected/vendors/phpdocx');
			spl_autoload_unregister(array('YiiBase','autoload'));
			require_once PHPDOCX_INCLUDE_PAth.'/lib/pdf/dompdf_config.inc.php';
			//require_once PHPDOCX_INCLUDE_PAth.'/classes/TransformDocAdv.inc';
			require_once PHPDOCX_INCLUDE_PAth.'/classes/CreateDocx.inc';
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
				'yritys' => $firma->tyonantaja,
				'yrityksen_osoite' => $firma->osoite,
				'yrityksen_postinumero' => $firma->postinumero,
				'yrityksen_toimipaikka' => $firma->postitoimipaikka,
				'yrityksen_y_tunnus' => $firma->y_tunnus,
				'yrityksen_puhelin' => $firma->puhelin,
				'teksti' => $model->tarjous,
			);
			$docx->replaceVariableByText($variables);

			$a = Asiakkaat::model()->findByPk($model->asiakas_id);
			$k = Kohteet::model()->findByPk($model->kohde_id);
			$tarvikkeet = array();
			if( is_array(json_decode($model->tarvikkeet, true)) )
			{
				foreach(json_decode($model->tarvikkeet, true) as $l)
				{
	      				$v = Valikkoot::model()->findByPk($l);
					if( isset($v->id) )
					array_push($tarvikkeet, $v->value);
				}
			}

			$variables_2 = array(
				'hinta_tyyppi' => $model->hinta_tyyppi,
				'hinta' => $model->hinta,
				'alv' => $model->alv,
				'kohteen_osoite' => $model->kohteen_osoite,
				'kohteen_postinumero' => $model->kohteen_postinumero,
				'kohteen_postitoimipaikka' => $model->kohteen_postitoimipaikka,
				'tyonantajan_edustaja' => $asetukset->johtaja,
				'tarvikkeet' => implode(", ", $tarvikkeet),
				'maksuehto' => $a->maksuehto,
				'viivastyskorko' => $a->viivastyskorko,
				'tuote_palvelu' => $model->tuote_palvelu,
			);
			$docx->replaceVariableByText($variables_2);


			$tb = $this->hinnatTaulu($model->id);
			$docx->replaceVariableByHTML('prices_table', 'block', $tb, array('parseDivsAsPs' => true));


			$path = 'tiedostot/'.$this->kansio().'/'.Yii::app()->user->domain.'/'.$tiedosto;
			$docx->createDocx($path);

			$transform = new TransformDocAdvLibreOffice();
			$transform->transformDocument($path.'.docx', $path.'.pdf');

			$this->redirect(array('index'));
	}


	public function hinnatTaulu($tarjous_id)
	{
		$bod = '
		<style>
		#TableRivit{ width:100%;border:none; border-collapse: collapse; }
		#TableRivit, th, td {
		    	border: 1px solid black;
			font-size: 75%;
		}
		</style>
		';

		$bod .= '
<table id="TableRivit">
     <tr>
	<th>Nimike</th>
	<th>Määrä</th>
	<th>Yksikkö <span class="btn btn-primary btn-xs myBgColors muokaValiko" for="laskutus_yksikko"><i class="fa fa-pencil-square-o"></i></span></th>
	<th>Hinta</th>
	<th>ALV %</th>
	<th>ALV</th>
	<th>Ale %</th>
	<th>Veroton</th>
	<th>Yhteensä</th>
     </tr>';

		$trRivit=TarjousHintaRivit::model()->findAll("tarjous_id='".$tarjous_id."'", array('order'=>'id'));
		if( count($trRivit) > 0 )
		{
			$num = 0;
			foreach($trRivit as $rivi){ 
			$num++;
			$bod .= $this->renderPartial("tr_rivi_update",array('num'=>$num,'rivi'=>$rivi), true);
			}
		}

		$bod .= '
</table>';

		return $bod;

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

		TarjousHintaRivit::model()->deleteAll("tarjous_id='".$id."'");

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

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " asiakas_id IN ( SELECT id FROM asiakkaat WHERE tyoryhma IN ($ids) ) ";
		}
		//    Tyoryhmat -->

		$dataProvider=new CActiveDataProvider('CrmTarjoukset', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	public function get_tyonkuvaus($id)
	{
	
		$bd = '';
		$data = Tyonkuvaus::model()->findByPk($id);
		
		$bd .= '
		<table class="table table-bordered" style="background:white">
		    <tr>
		        <th>'.Yii::t('main','Tilat').'</th>
			<th>'.Yii::t('main','Työtehtävät ja päivät').'</th>
			<th>'.Yii::t('main','Kommenti').'</th>
		    </tr>';


		$rivit = TyonkuvausRivit::model()->findAll(" tyonkuvaus_id='".$data->id."' ");
		foreach($rivit as $key=>$r)
		{

		$exTilat = explode("\n", json_decode($r->tilat));
		$tilat = '';
		foreach($exTilat as $itm)
			$tilat .= '<br>'.trim($itm);

		$bd .= '
		    <tr class="rivi" num="'.$key.'">
		        <td>'.$tilat.'</td>
		        <td class="tyotehtavatVkoPvmTD">';
			$tyontehtavat = json_decode($r->tyontehtavat, true);
			$bd .= '<table class="table table-bordered" align="center" border="none">';
			$bd .= '<tr>
			<th>Työtehtävät</th><th>Vko. Pvm</th><th>Viikkoväli</th>
			</tr>';
			foreach($tyontehtavat as $k2=>$r2)
			{ 
                        $bd .= '<tr>';
			$bd .= '<td>';
			$bd .= $r2['tyotehtava'].'<br>';
			$bd .= '</td>';
			$bd .= '<td>';
			$bd .= '<b>('.$r2['vkopvm'].')</b><br>.';
			$bd .= '</td>';
			$bd .= '<td>';
			$bd .= '<b>Joka '.$r2['vkovali'].' vko.';
			$bd .= '</td>';
             		$bd .= '</tr>';
			}
			$bd .= '</table>';
				$bd .= '</td>

		        <td>'.$r->kommenti.'</td>
		    </tr>
		';
		}

		$bd .= '</table>';
		


		return trim($bd);
	}


	public function get_tyonkuvaus_by_id($id)
	{
	
		$bd = array();
		$data = Tyonkuvaus::model()->findByPk($id);
		if(isset($data->id))
		{
		$bd['otsikko'] = $data->otsikko;

		$rivit = TyonkuvausRivit::model()->findAll(" tyonkuvaus_id='".$data->id."' ");
		foreach($rivit as $key=>$r)
		{

		$exTilat = explode("\n", json_decode($r->tilat));
		$tilat = array();
		foreach($exTilat as $itm)
			$tilat[] = trim($itm);

		$bd['tilat'][] = $tilat;

			$tyontehtavat = json_decode($r->tyontehtavat, true);
			$tt = array();
			foreach($tyontehtavat as $k2=>$r2)
				$tt[] = array('tyotehtava'=>$r2['tyotehtava'],'vkopvm'=>$r2['vkopvm'],'vkovali'=>$r2['vkovali']);

		$bd['tyontehtavat'][] = $tt;



		$exLaatutaso = explode("\n", json_decode($r->laatutaso));
		$tasot = array();
		foreach($exLaatutaso as $itm)
			$tasot[] = trim($itm);

		$bd['laatutaso'][] = $tasot;

		$exKommenti = explode("\n", $r->kommenti);
		$kommentit = array();
		foreach($exKommenti as $itm)
			$kommentit[] = trim($itm);

		$bd['kommenti'][] = $kommentit;

		}

		}



		return $bd;
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CrmTarjoukset('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CrmTarjoukset']))
			$model->attributes=$_GET['CrmTarjoukset'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CrmTarjoukset the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CrmTarjoukset::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CrmTarjoukset $model the model to be validated
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

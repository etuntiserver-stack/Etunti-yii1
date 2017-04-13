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
				'actions'=>array('vastaus', 'success', 'cancel', 'vanhentunut'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view', 'laheta', 'get_tyonkuvaus_by_asiakas', 'get_tyonkuvaus_by_id', 'get_tarjouslaskenta_by_id', 'get_tyonkuvaus', 'get_kohteentiedot', 'get_asiakastilat', 'view_tyonkuvaus', 'get_kohde'),
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
		$crm = CrmTarjoukset::model()->findbypk($id);
		if($asia == 'hyvaksy' and isset($crm->id) and $crm->hyvaksyn_koodi == $code and $crm->status == 1){

			CrmTarjoukset::model()->updatebypk($id, array('status'=>2));
			$this->redirect(array('success'));

		} elseif($asia == 'hylatty' and isset($crm->id) and $crm->hyvaksyn_koodi == $code and $crm->status == 1){

			if( $crm->tyonkuvaus_id != 0 )
			Tyonkuvaus::model()->updatebypk($crm->tyonkuvaus_id, array('aktiivinen'=>0));

			if( $crm->kohde_id != 0 )
			Kohteet::model()->updatebypk($crm->kohde_id, array('aktiivinen'=>0));

			CrmTarjoukset::model()->updatebypk($id, array('status'=>3));
			$this->redirect(array('cancel'));
		} else {
			$this->redirect(array('vanhentunut'));
		}
	
	}


	public function actionGet_asiakastilat($asiakastila)
	{
		$return = '';

		$criteria=new CDbCriteria;
		$criteria->condition=" asiakastila='".$asiakastila."' ";
      		$l = Asiakkaat::model()->findAll($criteria);
		$list = array();
		foreach($l as $v)
		{
			if($v->tyyppi == 'yritys' and !empty($v->yrityksen_nimi))
			$list[$v->id] = $v->yrityksen_nimi;
			elseif($v->tyyppi == 'henkilo' and !empty($v->yhteyshenkilo))
			$list[$v->id] = $v->yhteyshenkilo;
			else
			$list[$v->id] = 'Asiakasnumero: '.$v->asiakasnumero;
		}

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
		$path = Yii::app()->request->baseUrl."tiedostot/crm/tarjoukset/".Yii::app()->user->domain;

		$firma = FirmanTiedot::model()->findbypk(1);
		$get_css = file_get_contents('css/email_send_table.css');

		$message = '<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		    <title></title>
		    <style type="text/css">'.$get_css.'</style>
		</head>
		<body>';
		$message .= '<center>';


		$message .= '<br>
		<center>
		<div id="outer">
		<a class="hyvaksy_button inner" href="http://'.$_SERVER['SERVER_NAME'].'/index.php/crmTarjoukset/vastaus?asia=hyvaksy&id='.$id.'&code='.$randstring.'">
				<h2>'.Yii::t('main', 'Hyväksy').'</h2>
		</a>
		<a class="hylkaa_button inner" href="http://'.$_SERVER['SERVER_NAME'].'/index.php/crmTarjoukset/vastaus?asia=hylatty&id='.$id.'&code='.$randstring.'">
				<h2>'.Yii::t('main', 'Hylkää').'</h2>
		</a>
		</div>
		</center>
		';
		$message .= '
		</center>
		</body>
		</html>';
		
		//echo $message;
		//exit;


		$subject = Yii::t('main', 'Tarjous'). ', '.$firma->tyonantaja;
		$mail = new YiiMailer();
		//$mail->clearLayout();//if layout is already set in config
		$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		$mail->setTo($crm->asiakkaan_sahkoposti);
		$mail->setSubject($subject);
		$mail->setBody($message);

   		if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$crm->liite.".pdf"))
		$mail->setAttachment($path.'/'.$file);

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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CrmTarjoukset;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

			$tm = '';


			$nimike		= 'crm_tarjous.docx';
			$polku 		= Yii::app()->basePath;
			$tiedosto 	= "/../tiedostot/templates/".Yii::app()->user->domain."/".$nimike;

		if(!file_exists($polku.$tiedosto))
			$tm = '<h2 class="alert alert-danger">'.Yii::t('main', 'Mallitiedosto puutuu, jos haluat ominaisuuden käyttöön ota yhteyttä'). ' <a href="mailto:tuki@etunti.fi">tuki@etunti.fi<a></h2>';


		if(isset($_POST['CrmTarjoukset']))
		{

		if(file_exists($polku.$tiedosto))
		{
			$model->attributes=$_POST['CrmTarjoukset'];
			if($model->save()){

				$as = Asiakkaat::model()->findbypk($model->asiakas_id);
				//$y = Yhteystiedot::model()->findbypk($model->yhteystiedot_id);
				if(isset($as->sahkoposti))
					CrmTarjoukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$as->sahkoposti));
/*
				if(isset($y->sahkoposti))
					CrmTarjoukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$y->sahkoposti));
*/
				$this->docx($model);

		}

			}
		}

		$this->render('create',array(
			'model'=>$model,
			'tm'=>$tm,
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
			if($model->save()){

				$as = Asiakkaat::model()->findbypk($model->asiakas_id);
				$y = Yhteystiedot::model()->findbypk($model->yhteystiedot_id);
				if(isset($as->sahkoposti))
					CrmTarjoukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$as->sahkoposti));

				if(isset($y->sahkoposti))
					CrmTarjoukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$y->sahkoposti));

				$this->docx($model);
			
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	protected function docx($model)
	{

			$liite = $model->id.'_'.date("d.m.Y");
			$crm = CrmTarjoukset::model()->updatebypk($model->id, array('liite'=>$liite));

	
			if (!file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain)) {
			 	mkdir(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain, 0777, true);
			}


			// <-- Tyonkuvaus
			if( $model->tyonkuvaus_id != 0 )
			{

			}
			//     Tyonkuvaus -->

			$firma = FirmanTiedot::model()->findbypk(1);

			// <-- Jos se on Asiakas
			$as = Asiakkaat::model()->findbypk($model->asiakas_id);
			if(isset($as->id))
			{
				if(isset($as->id) and !empty($as->yrityksen_nimi))
				   $asiakas = $as->yrityksen_nimi;
				elseif(isset($as->id) and empty($as->yrityksen_nimi) and !empty($as->yhteyshenkilo)) 
				   $asiakas = $as->yhteyshenkilo;
				else
				   $asiakas = '';

				$asiakkaan_osoite = $as->osoite;
				$asiakkaan_postinumero = $as->postinumero;
				$asiakkaan_toimipaikka = $as->kaupunki;
			}
			//     Jos se on Asiakas -->



			define('PHPDOCX_INCLUDE_PATH', (dirname(Yii::app()->basePath)).'/protected/vendors/phpdocx');
			spl_autoload_unregister(array('YiiBase','autoload'));
			require_once PHPDOCX_INCLUDE_PATH.'/lib/pdf/dompdf_config.inc.php';
			//require_once PHPDOCX_INCLUDE_PATH.'/classes/TransformDocAdv.inc';
			require_once PHPDOCX_INCLUDE_PATH.'/classes/CreateDocx.inc';
			spl_autoload_register(array('AutoLoader','load'));
			spl_autoload_register(array('YiiBase', 'autoload'));

			$template_tiedosto = 'tiedostot/templates/'.Yii::app()->user->domain.'/crm_tarjous.docx';

			$docx = new CreateDocxFromTemplate($template_tiedosto);
			//$docx->enableCompatibilityMode();
			$docx->setTemplateSymbol('#');
			$variables = array(
				'paivays' => date("d.m.Y"),
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

			if( $model->tyonkuvaus_id != 0 )
			{
			/*
				$tb = $this->get_tyonkuvaus($model->tyonkuvaus_id);
				$docx->replaceVariableByHTML('tyonkuvaus', 'block', $tb, 
					array('isFile' => false, 'parseDivsAsPs' => true, 'downloadImages' => false)
				);
			*/
			$tyonkuvaus = $this->get_tyonkuvaus_by_id($model->tyonkuvaus_id);

			$valuesTable = array(
			    array(
			        'Tilat','Työtehtävät','Laatutaso','Kommenti'
			    )
			);

				if( is_array($tyonkuvaus) and isset($tyonkuvaus['tilat']) )
				{
				    foreach($tyonkuvaus['tilat'] as $key=>$items)
				    {
					$tyontehtavat = $tyonkuvaus['tyontehtavat'][$key];
					$tt_result = '';
					foreach($tyontehtavat as $kt=>$it)
						$tt_result .= $it['tyotehtava'].': '.$it['vkopvm']."\r\n";
	
					$valuesTable[] = array(
						implode("\r\n", $items),
						$tt_result,
						implode("\r\n", $tyonkuvaus['laatutaso'][$key]),
						implode("\r\n", $tyonkuvaus['kommenti'][$key])
					);
	
				    }
				}

			$paramsTable = array(
			    //'border' => 'single',
			    //'tableAlign' => 'center',
			    //'borderWidth' => 10,
			    //'borderColor' => 'B70000',
			    //'textProperties' => array('bold' => true, 'font' => 'Algerian', 'fontSize' => 18),
			);
			$docx->addTable($valuesTable, $paramsTable);

			}




			$path = 'tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$liite;
			$docx->createDocx($path);

/*
			$document = new TransformDoc();
			$document->setStrFile($path.'.docx');
			$document->generatePDF($path.'.pdf');
*/
			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			{
			$transform = new TransformDocAdvLibreOffice();
			$transform->transformDocument($path.'.docx', $path.'.pdf');
			}

			$this->redirect(array('index'));
	}


	public function actionDelete($id)
	{

		$model=$this->loadModel($id);
		$t = 'tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$model->liite;
		if(file_exists(Yii::app()->basePath."/../".$t.".docx"))
			unlink($t.".docx");
		if(file_exists(Yii::app()->basePath."/../".$t.".pdf"))
			unlink($t.".pdf");
		$model->delete();

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
       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";
/*
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

		if(isset($_POST['puhelin']) and !empty(trim($_POST['puhelin'])))
	        $criteria->addCondition (" puhelin LIKE '%".$_POST['puhelin']."%' ");

		if(isset($_POST['sahkoposti']) and !empty(trim($_POST['sahkoposti'])))
	        $criteria->addCondition (" sahkoposti LIKE '%".$_POST['sahkoposti']."%' ");
*/

		$dataProvider=new CActiveDataProvider('CrmTarjoukset', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	protected function get_tyonkuvaus($id)
	{
	
		$bd = '';
		$data = Tyonkuvaus::model()->findByPk($id);
		
		$bd .= '
		<table class="table table-bordered" style="background:white">
		    <tr>
		        <th>'.Yii::t('main','Tilat').'</th>
			<th>'.Yii::t('main','Työtehtävät').'</th>
			<th>'.Yii::t('main','Laatutaso').'</th>
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
			foreach($tyontehtavat as $k2=>$r2)
				$bd .= '<br>'.$r2['tyotehtava'].': '.$r2['vkopvm'];



		$exLaatutaso = explode("\n", json_decode($r->laatutaso));
		$tasot = '';
		foreach($exLaatutaso as $itm)
			$tasot .= '<br>'.trim($itm);

		$exKommenti = explode("\n", json_decode($r->laatutaso));
		$kommentit = '';
		foreach($exKommenti as $itm)
			$kommentit .= '<br>'.trim($itm);

		$bd .= '</td>
		        <td>'.$tasot.'</td>
		        <td>'.$kommentit.'</td>
		    </tr>
		';
		}

		$bd .= '</table>';
		


		return trim($bd);
	}


	protected function get_tyonkuvaus_by_id($id)
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
				$tt[] = array('tyotehtava'=>$r2['tyotehtava'],'vkopvm'=>$r2['vkopvm']);

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


	protected function get_tarjouslaskenta($tb, $id)
	{

		$bd = '';
       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";
	        $criteria->condition = " $tb='".$id."' ";
		$model = Tarjouslaskenta::model()->findAll($criteria);
		
		if( count($model) > 0 )
		{
		$bd = '<h1>'.Yii::t('main', 'Valitse tarjouslaskenta').'</h1>';

		foreach($model as $data)
		{
			$tl = $this->renderPartial('//tarjouslaskenta/view', array('model'=>$data), true);

			$bd .= '<div class="tyokuvauksetValinta" id="tarjouslaskenta_'.$data->id.'">
				<h2>'.Yii::t('main', 'Tarjouslaskenta').' #'.$data->id.' <input type="radio" name="tarjouslaskenta" class="tarjouslaskenta" for="'.$data->id.'"></h2>
			</div>';
			$bd .= $tl;
			$bd .= '<hr>';
		}


		}

		return trim($bd);
	}


	public function actionGet_tarjouslaskenta_by_id($id)
	{

		$bd = array();
		$model = Tarjouslaskenta::model()->findByPk($id);
		
		if( isset($model->id) )
		{

                		$bd = array_filter($model->attributes);


		}

		echo json_encode($bd);
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

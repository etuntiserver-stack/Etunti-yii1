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
				'actions'=>array('admin','delete','create','update','index','view', 'laheta'),
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

	public function actionLaheta()
	{
		if(isset($_POST['id']))
		{
			$crm = CrmSopimukset::model()->findbypk($_POST['id']);
			$as = Asiakkaat::model()->findbypk($crm->asiakas_id);



function generateRandomString($length = 40) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$randstring = generateRandomString();



		/* file */
		$file = $crm->liite.'.pdf';
		$path = Yii::app()->request->baseUrl."tiedostot/crm/sopimukset/".Yii::app()->user->domain;

		$firma = FirmanTiedot::model()->findbypk(1);
		$message = Yii::t('main', 'CRM sopimus body');
		$message .= '<br>
		<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php/CrmSopimukset/vastaus?asia=hyvaksy&id='.$_POST['id'].'&code='.$randstring.'">
				<h2>'.Yii::t('main', 'Hyväksy').'
		</a>
		<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php/CrmSopimukset/vastaus?asia=hylatty&id='.$_POST['id'].'&code='.$randstring.'">
				<h2>'.Yii::t('main', 'Hylkä').'
		</a>
		';
		
   if(file_exists(Yii::app()->basePath."/../tiedostot/crm/sopimukset/".Yii::app()->user->domain."/".$crm->liite.".pdf"))
   {
		$subject = Yii::t('main', 'Sopimus'). ', '.$firma->tyonantaja;
		$mail = new YiiMailer();
		//$mail->clearLayout();//if layout is already set in config
		$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		$mail->setTo($as->sahkoposti);
		$mail->setSubject($subject);
		$mail->setBody($message);
		$mail->setAttachment($path.'/'.$file);

		   if($mail->send())
		   {

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $as->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

			CrmSopimukset::model()->updatebypk($_POST['id'], array('status'=>1,'hyvaksyn_koodi'=>$randstring));
			$this->redirect(array('index'));
		   }
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

			$tm = '';

		if(isset($_POST['CrmSopimukset']))
		{
			$nimike		= $_POST['CrmSopimukset']['template'].'.docx';
			$polku 		= Yii::app()->basePath;
			$tiedosto 	= "/../tiedostot/templates/".Yii::app()->user->domain."/".$nimike;
		}

		if(isset($_POST['CrmSopimukset']) and !file_exists($polku.$tiedosto))
			$tm = '<h2 class="alert alert-danger">'.Yii::t('main', 'Template puuttuu').'</h2>';

		if(isset($_POST['CrmSopimukset']) and file_exists($polku.$tiedosto))
		{

			$model->attributes=$_POST['CrmSopimukset'];

			if($model->save()){

				$as = Asiakkaat::model()->findbypk($model->asiakas_id);
				CrmSopimukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$as->sahkoposti));
				$this->docx($model);

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

	
			$tm = '';

		if(isset($_POST['CrmSopimukset']))
		{
			$nimike		= $_POST['CrmSopimukset']['template'].'.docx';
			$polku 		= Yii::app()->basePath;
			$tiedosto 	= "/../tiedostot/templates/".Yii::app()->user->domain."/".$nimike;
		}

		if(isset($_POST['CrmSopimukset']) and !file_exists($polku.$tiedosto))
			$tm = '<h2 class="alert alert-danger">'.Yii::t('main', 'Template puuttuu').'</h2>';

		if(isset($_POST['CrmSopimukset']) and file_exists($polku.$tiedosto))
		{

			$model->attributes=$_POST['CrmSopimukset'];
			$as = Asiakkaat::model()->findbypk($model->asiakas_id);
			$model->asiakkaan_sahkoposti=$as->sahkoposti;

			if($model->save()){
				$this->docx($model);
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'tm'=>$tm,
		));
	}


	protected function docx($model)
	{

			$liite 		= $model->id.'_'.date("d.m.Y");
			$tiedosto 	= $model->template.'.docx';

			$crm = CrmSopimukset::model()->updatebypk($model->id, array('liite'=>$liite));

			Yii::import('ext.yiiword.YiiWord', true);
			Yii::registerAutoloader(array('YiiWord', 'autoload'), true);

	
			if (!file_exists(Yii::app()->basePath."/../tiedostot/crm/sopimukset/".Yii::app()->user->domain)) {
			 	mkdir(Yii::app()->basePath."/../tiedostot/crm/sopimukset/".Yii::app()->user->domain, 0777, true);
			}
	
			$PHPWord = new PHPWord();
			$document = $PHPWord->loadTemplate('tiedostot/templates/'.Yii::app()->user->domain.'/'.$tiedosto);
			$file = '';


			$firma = FirmanTiedot::model()->findbypk(1);
			$as = Asiakkaat::model()->findbypk($model->asiakas_id);
		

			$document->setValue('paivays', iconv('UTF-8','ISO-8859-1',date("d.m.Y")));

			// Yritys
			$document->setValue('yritys', iconv('UTF-8','ISO-8859-1',$firma->tyonantaja));
			$document->setValue('y_tunnus', iconv('UTF-8','ISO-8859-1',$firma->y_tunnus));
			$document->setValue('yrityksen_osoite', iconv('UTF-8','ISO-8859-1',$firma->osoite));
			$document->setValue('yrityksen_postinumero', iconv('UTF-8','ISO-8859-1',$firma->postinumero));
			$document->setValue('yrityksen_toimipaikka', iconv('UTF-8','ISO-8859-1',$firma->postitoimipaikka));
			$document->setValue('yrityksen_puhelin', iconv('UTF-8','ISO-8859-1',$firma->puhelin));
			$document->setValue('yrityksen_sahkoposti', iconv('UTF-8','ISO-8859-1',$firma->sahkoposti));
			$document->setValue('yrityksen_johtaja', iconv('UTF-8','ISO-8859-1',$firma->johtaja));
			// Asiakas
			if(!empty($as->yrityksen_nimi))
			   $asiakas = $as->yrityksen_nimi;
			elseif(empty($as->yrityksen_nimi) and !empty($as->yhteyshenkilo)) 
			   $asiakas = $as->yhteyshenkilo;
			else
			   $asiakas = '';

			$document->setValue('asiakas', iconv('UTF-8','ISO-8859-1',$asiakas));
			$document->setValue('asiakkaan_osoite', iconv('UTF-8','ISO-8859-1',$as->osoite));
			$document->setValue('asiakkaan_postinumero', iconv('UTF-8','ISO-8859-1',$as->postinumero));
			$document->setValue('asiakkaan_toimipaikka', iconv('UTF-8','ISO-8859-1',$as->kaupunki));
			$document->setValue('asiakkaan_puhelin', iconv('UTF-8','ISO-8859-1',$as->puhelin));
			$document->setValue('asiakkaan_sahkoposti', iconv('UTF-8','ISO-8859-1',$as->sahkoposti));

			$path = 'tiedostot/crm/sopimukset/'.Yii::app()->user->domain.'/'.$liite;
			$document->setValue('teksti', htmlspecialchars(iconv('UTF-8','ISO-8859-1',$model->teksti)));
		  	$document->save($path.'.docx');

			shell_exec('unoconv -f pdf '.$path.'.docx');
			$this->redirect(array('index'));

	}

	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$t = 'tiedostot/crm/sopimukset/'.Yii::app()->user->domain.'/'.$model->liite;
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

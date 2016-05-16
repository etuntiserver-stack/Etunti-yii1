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
				'actions'=>array('success', 'cancel'),
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
                } else {
                        Yii::app()->theme = 'classic';
                }
                parent::init();
        }

	public function actionSuccess($id, $code)
	{
		Yii::app()->theme = 'classic';
		$asia = 0;
		$crm = CrmTarjoukset::model()->findbypk($id);
		if(isset($crm->id) and $crm->hyvaksyn_koodi == $code and $crm->status == 1)
		{
			$crm->status = 2;
			if($crm->save())
				$asia = 1;
		} 
		
		$this->render('success', array('asia'=>$asia));
		
	}

	public function actionCancel($id, $code)
	{
		Yii::app()->theme = 'classic';
		$asia = 0;
		$crm = CrmTarjoukset::model()->findbypk($id);
		if(isset($crm->id) and $crm->hyvaksyn_koodi == $code and $crm->status == 1)
		{
			$crm->status = 3;
			if($crm->save())
				$asia = 1;
		} 
		
		$this->render('success', array('asia'=>$asia));
	}


	public function actionLaheta()
	{
		if(isset($_POST['id']))
		{
			$crm = CrmTarjoukset::model()->findbypk($_POST['id']);
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
		$path = Yii::app()->request->baseUrl."tiedostot/crm/tarjoukset/".Yii::app()->user->domain;

		$firma = FirmanTiedot::model()->findbypk(1);
		$message = Yii::t('main', 'CRM tarjous body');
		$message .= '<br>
		<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php/crmTarjoukset/success?id='.$_POST['id'].'&code='.$randstring.'">
				<h2>'.Yii::t('main', 'Hyväksy').'
		</a>
		<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php/crmTarjoukset/cancel?id='.$_POST['id'].'&code='.$randstring.'">
				<h2>'.Yii::t('main', 'Hylkä').'
		</a>
		';
		
   if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$crm->liite.".pdf"))
   {
		$mail = new YiiMailer();
		//$mail->clearLayout();//if layout is already set in config
		$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		$mail->setTo($as->sahkoposti);
		$mail->setSubject(Yii::t('main', 'Tarjous'). ', '.$firma->tyonantaja);
		$mail->setBody($message);
		$mail->setAttachment($path.'/'.$file);

		   if($mail->send())
		   {

			CrmTarjoukset::model()->updatebypk($_POST['id'], array('status'=>1,'hyvaksyn_koodi'=>$randstring));
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
		$model=new CrmTarjoukset;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CrmTarjoukset']))
		{

			$model->attributes=$_POST['CrmTarjoukset'];

			if($model->save()){

				$as = Asiakkaat::model()->findbypk($model->asiakas_id);
				CrmTarjoukset::model()->updatebypk($model->id, array('asiakkaan_sahkoposti'=>$as->sahkoposti));


				$returnPath = $this->docx($model);

echo '
	<input type="hidden" id="polkku" value="'.$returnPath.'">

<script src="'.Yii::app()->request->baseUrl.'/js/jquery-1.9.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

	var polkku = $("#polkku").val();

        $.ajax({
           url: location.protocol + "//" + location.host + "/docxtopdf/index.php",
           type: "POST",
           data: { "polkku" : polkku },
           success: function(data){
		console.log(data);
		window.location.href="index";
           }
        });

});
</script>';
exit;


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
		$model=$this->loadModel($id);

	
			if(isset($_POST['CrmTarjoukset']))
			{

			$model->attributes=$_POST['CrmTarjoukset'];
			$as = Asiakkaat::model()->findbypk($model->asiakas_id);
			$model->asiakkaan_sahkoposti=$as->sahkoposti;

			if($model->save()){

				$returnPath = $this->docx($model);

echo '
	<input type="hidden" id="polkku" value="'.$returnPath.'">

<script src="'.Yii::app()->request->baseUrl.'/js/jquery-1.9.1.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

	var polkku = $("#polkku").val();

        $.ajax({
           url: location.protocol + "//" + location.host + "/docxtopdf/index.php",
           type: "POST",
           data: { "polkku" : polkku },
           success: function(data){
		console.log(data);
		window.location.href="index";
           }
        });

});
</script>';
exit;

	


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

			Yii::import('ext.yiiword.YiiWord', true);
			Yii::registerAutoloader(array('YiiWord', 'autoload'), true);

	
			if (!file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain)) {
			 	mkdir(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain, 0777, true);
			}
	
			$PHPWord = new PHPWord();
			$document = $PHPWord->loadTemplate('tiedostot/templates/crm_tarjous.docx');
			$file = '';

/*
			$firma = FirmanTiedot::model()->findbypk(1);
			$as = Asiakkaat::model()->findbypk($model->asiakas_id);
		


			if(!empty($firma->tyonantaja)) 
			   $document->setValue('tyonantaja', iconv('UTF-8','ISO-8859-1',$firma->tyonantaja));
			else
			   $document->setValue('tyonantaja', '');

			if(!empty($firma->osoite)) 
			   $document->setValue('osoite', iconv('UTF-8','ISO-8859-1',$firma->osoite));
			else
			   $document->setValue('osoite', '');

			if(!empty($firma->y_tunnus)) 
			   $document->setValue('y_tunnus', $firma->y_tunnus);
			else
			   $document->setValue('y_tunnus', '');


			if(!empty($firma->puhelin)) 
			   $document->setValue('puhelin', $firma->puhelin);

			else
			   $document->setValue('puhelin', '');

			if(!empty($firma->sahkoposti))

			   $document->setValue('sposti', $firma->sahkoposti);
			else
			   $document->setValue('sposti', '');
*/

			$path = 'tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$liite;
			$document->setValue('tarjous', htmlspecialchars(iconv('UTF-8','ISO-8859-1',$model->tarjous)));
		  	$document->save($path.'.docx');

			return $path;

	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

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

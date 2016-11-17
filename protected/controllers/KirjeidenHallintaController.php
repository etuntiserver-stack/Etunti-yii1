<?php

class KirjeidenHallintaController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'laheta'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}


	public function actionLaheta()
	{
		if(isset($_POST['id']))
		{

			KirjeidenHallinta::model()->updatebypk($_POST['id'], array('status'=>1));
			$crm = KirjeidenHallinta::model()->findbypk($_POST['id']);
			$asData = Asiakkaat::model()->findAll(" ryhma='".$crm->ryhma."' ");


	foreach($asData as $as)
	{

		/* file */
		$file = $crm->liite.'.pdf';
		$path = Yii::app()->request->baseUrl."tiedostot/crm/kirje/".Yii::app()->user->domain;

		$firma = FirmanTiedot::model()->findbypk(1);
		$message = Yii::t('main', 'Kirje body');
		
   		if(file_exists(Yii::app()->basePath."/../tiedostot/crm/kirje/".Yii::app()->user->domain."/".$crm->liite.".pdf") and !empty($as->sahkoposti))
   		{
		//echo $as->sahkoposti;

		$subject = Yii::t('main', 'Kirje'). ', '.$firma->tyonantaja;
		$mail = new YiiMailer();
		$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		$mail->setTo($as->sahkoposti);
		$mail->setSubject($subject);
		$mail->setBody($message);
		$mail->setAttachment($path.'/'.$file);
		$mail->send();


							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $as->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$file;
							$log->save();
							//     LOG -->

   		}

	}


		//$this->redirect(array('index'));


		}
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new KirjeidenHallinta;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

			$tm = '';

			$nimike		= 'crm_kirje.docx';
			$polku 		= Yii::app()->basePath;
			$tiedosto 	= "/../tiedostot/templates/".Yii::app()->user->domain."/".$nimike;

		if(!file_exists($polku.$tiedosto))
			$tm = '<h2 class="alert alert-danger">'.Yii::t('main', 'Template puuttuu').'</h2>';


		if(isset($_POST['KirjeidenHallinta']))
		{

		if(file_exists($polku.$tiedosto))
		{
			$model->attributes=$_POST['KirjeidenHallinta'];
			if($model->save()){

				$this->docx($model);

		}

			}
		}

		$this->render('create',array(
			'model'=>$model,
			'tm'=>$tm,
		));
	}


	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['KirjeidenHallinta']))
		{
			$model->attributes=$_POST['KirjeidenHallinta'];
			if($model->save()){
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
			$crm = KirjeidenHallinta::model()->updatebypk($model->id, array('liite'=>$liite));

			Yii::import('ext.yiiword.YiiWord', true);
			Yii::registerAutoloader(array('YiiWord', 'autoload'), true);

	
			if (!file_exists(Yii::app()->basePath."/../tiedostot/crm/kirje/".Yii::app()->user->domain)) {
			 	mkdir(Yii::app()->basePath."/../tiedostot/crm/kirje/".Yii::app()->user->domain, 0777, true);
			}
	
			$PHPWord = new PHPWord();
			$document = $PHPWord->loadTemplate('tiedostot/templates/'.Yii::app()->user->domain.'/crm_kirje.docx');
			$file = '';


			$firma = FirmanTiedot::model()->findbypk(1);
			// Yritys
			$document->setValue('yritys', iconv('UTF-8','ISO-8859-1',$firma->tyonantaja));
			$document->setValue('yrityksen_osoite', iconv('UTF-8','ISO-8859-1',$firma->osoite));
			$document->setValue('yrityksen_postinumero', iconv('UTF-8','ISO-8859-1',$firma->postinumero));
			$document->setValue('yrityksen_toimipaikka', iconv('UTF-8','ISO-8859-1',$firma->postitoimipaikka));
			$document->setValue('yrityksen_y_tunnus', iconv('UTF-8','ISO-8859-1',$firma->y_tunnus));
			$document->setValue('yrityksen_puhelin', iconv('UTF-8','ISO-8859-1',$firma->puhelin));
		
			$document->setValue('paivays', iconv('UTF-8','ISO-8859-1',date("d.m.Y")));
			$document->setValue('teksti', htmlspecialchars(iconv('UTF-8','ISO-8859-1',$model->teksti)));

			$path = 'tiedostot/crm/kirje/'.Yii::app()->user->domain.'/'.$liite;
		  	$document->save($path.'.docx');

			shell_exec('unoconv -f pdf '.$path.'.docx');
			$this->redirect(array('index'));

	}

	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$t = 'tiedostot/crm/kirje/'.Yii::app()->user->domain.'/'.$model->liite;
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

       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";

		$dataProvider=new CActiveDataProvider('KirjeidenHallinta', array(
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
		$model=new KirjeidenHallinta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['KirjeidenHallinta']))
			$model->attributes=$_GET['KirjeidenHallinta'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return KirjeidenHallinta the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=KirjeidenHallinta::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param KirjeidenHallinta $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='kirjeiden-hallinta-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

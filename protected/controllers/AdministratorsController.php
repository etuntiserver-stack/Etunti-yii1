<?php

class AdministratorsController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function isEtuntiAdmin() {

		$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	        if($m->id == Yii::app()->user->adminID)
	            return true;
		else
	            return false;
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
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
	   $checkOikeus = "administrators_1_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=new Administrators;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Administrators']))
		{

			$model->attributes=$_POST['Administrators'];
			$model->adm_salasana=md5($_POST['Administrators']['adm_salasana']);
			if($model->save())
			{


				$message = 'Hei '.$model->adm_nimi.'!<br>
				<b>'.Yii::t('main', 'Yritystunnus').':</b> '.Yii::app()->user->domain.'<br>
				<b>'.ii::t('main', 'Käyttäjätunnus').':</b> '.$model->adm_login.'<br>
				<b>'.Yii::t('main', 'Salasana').':</b> '.$_POST['Administrators']['adm_salasana'].'<br>
				<p>
				Tervetuloa Etunnin käyttäjäksi. 
				</p><br>
				<br>
				<p>Ystävällisin terveisin</p>
				Etunti<br>';

				$mail = new YiiMailer();
				$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
				$mail->setTo($model->adm_email);
				$mail->setSubject(Yii::t('main', 'Tervetuloa Etunnin käyttäjäksi.'));
				$mail->setBody($message);
				$mail->send();

				$this->redirect(array('index'));
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
	   $checkOikeus = "administrators_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Administrators']))
		{

			if($model->adm_salasana != $_POST['Administrators']['adm_salasana'])
			$_POST['Administrators']['adm_salasana']=md5($_POST['Administrators']['adm_salasana']);

			$model->attributes=$_POST['Administrators'];
			if($model->save())
				$this->redirect(array('index'));
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
	   $checkOikeus = "administrators_3_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

	// <-- Oikeudet
	   $checkOikeus = "administrators_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";


		if(isset($_POST['adm_login']) and !empty(trim($_POST['adm_login'])))
	        $criteria->addCondition (" adm_login LIKE '%".$_POST['adm_login']."%' ");

		if(isset($_POST['adm_nimi']) and !empty(trim($_POST['adm_nimi'])))
	        $criteria->addCondition (" adm_nimi LIKE '%".$_POST['adm_nimi']."%' ");

		if(isset($_POST['adm_email']) and !empty(trim($_POST['adm_email'])))
	        $criteria->addCondition (" adm_email LIKE '%".$_POST['adm_email']."%' ");

		$dataProvider=new CActiveDataProvider('Administrators', array(
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
		$model=new Administrators('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Administrators']))
			$model->attributes=$_GET['Administrators'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Administrators the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Administrators::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Administrators $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='administrators-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

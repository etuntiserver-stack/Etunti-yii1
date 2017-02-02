<?php

class AsetuksetController extends Controller
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

	public function accessRules()
	{
		return array(

			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('update','oikeudet', 'rekisteriseloste'),
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

	public function actionOikeudet()
	{
		if(isset($_POST['oikeudet']))
		{
			$as = Asetukset::model()->updatebypk(1,array('oikeudet' => json_encode($_POST['oikeudet'])));
			exit;
		} else {

			$this->render('oikeudet');
		}
	}


	public function actionRekisteriseloste()
	{

		if(isset($_POST['rekisteriseloste']))
		{
			$as = Asetukset::model()->updatebypk(1,array('rekisteriseloste' => json_encode($_POST['rekisteriseloste'])));
			exit;
		} else {
			$rt = Asetukset::model()->findbypk(1);
			$rekisteriseloste = json_decode($rt->rekisteriseloste);
			$this->render('rekisteriseloste',array(
				'rekisteriseloste'=>$rekisteriseloste
			));
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
		$model=new Asetukset;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Asetukset']))
		{
			$model->attributes=$_POST['Asetukset'];
			if(isset($_POST['Asetukset']['netvisor_mita_lahetetaan']))
			$model->netvisor_mita_lahetetaan=json_encode($_POST['Asetukset']['netvisor_mita_lahetetaan']);
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
	   $checkOikeus = "asetukset_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=$this->loadModel($id);
		$f = FirmanTiedot::model()->findbypk(1);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FirmanTiedot']))
		{
			$f->attributes=$_POST['FirmanTiedot'];
			$f->save();

		}

		if(isset($_POST['Asetukset']))
		{

			$model->attributes=$_POST['Asetukset'];
			if(isset($_POST['Asetukset']['netvisor_mita_lahetetaan']))
			$model->netvisor_mita_lahetetaan=json_encode($_POST['Asetukset']['netvisor_mita_lahetetaan']);
			if($model->save())
				$this->redirect(array('update','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'f'=>$f,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
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
		$dataProvider=new CActiveDataProvider('Asetukset');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Asetukset('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Asetukset']))
			$model->attributes=$_GET['Asetukset'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Asetukset the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Asetukset::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Asetukset $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='asetukset-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function oikeudenOtsikot()
	{
		$model = OikeusRyhmat::model()->findAll();
		foreach($model as $data)
		$otsiko[$data->id] = $data->nimike;
		return $otsiko;
	}

}

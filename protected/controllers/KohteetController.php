<?php

class KohteetController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view','osoite','autotaytaminen','createfromasiakas','googlemap','googlemap_k'),
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

	public function actionGooglemap()
	{

		$this->render('googlemap');
	}

	public function actionGooglemap_k()
	{

		$model=Kohteet::model()->findAll();

		$this->renderPartial('googlemap_k',array(
			'model'=>$model,
		));
	}

	public function actionAutotaytaminen($id)
	{
		$m=Asiakkaat::model()->findbypk($id);

		$ryhma = 0;
		if($m->ryhma != 0)
		{
		$l = Valikkoot::model()->findbypk($m->ryhma);
		$ryhma = $l['id']."-".$l['value'];
		}

		echo $m->yhteyshenkilo."//".$m->kaupunki."//".$m->postinumero."//".$m->sahkoposti."//".$m->puhelin."//".$ryhma;
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionOsoite($osoite)
	{
		$model = Kohteet::model()->find(" osoite = '".$osoite."' ");
		echo $model['id'];
	}

	public function actionCreatefromasiakas($id)
	{
		$model=new Kohteet;
		$asiakas=Asiakkaat::model()->findbypk($id);
		if(isset($_POST['Kohteet']))
		{
			$model->attributes=$_POST['Kohteet'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('createfromasiakas',array(
			'model'=>$model,
			'asiakas'=>$asiakas,
		));
	}

	public function actionCreate()
	{
		$model=new Kohteet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Kohteet']))
		{
			$model->attributes=$_POST['Kohteet'];
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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Kohteet']))
		{
			$model->attributes=$_POST['Kohteet'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		$dataProvider=new CActiveDataProvider('Kohteet');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Kohteet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Kohteet']))
			$model->attributes=$_GET['Kohteet'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Kohteet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Kohteet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Kohteet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='kohteet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

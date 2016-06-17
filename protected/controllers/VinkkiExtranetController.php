<?php

class VinkkiExtranetController extends Controller
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
				'actions'=>array('index','view', 'lahetetty'),
                		'expression'=>"Yii::app()->controller->isAsiakas() or Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create'),
                		'expression'=>"Yii::app()->controller->isAsiakas()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','view','admin','delete','update'),
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


	public function actionLahetetty($asiakas_id)
	{
		Yii::app()->theme = 'customer';
		$this->render('lahetetty', array('asiakas_id'=>$asiakas_id));
	}

	public function actionCreate()
	{
                Yii::app()->theme = 'customer';
		$as = Asiakkaat::model()->findbypk(Yii::app()->user->asiakas);
		$model=new VinkkiExtranet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['VinkkiExtranet']))
		{
			$model->attributes=$_POST['VinkkiExtranet'];
			if($model->save())
				$this->redirect(array('lahetetty','asiakas_id'=>$as->id));
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

		if(isset($_POST['VinkkiExtranet']))
		{
			$model->attributes=$_POST['VinkkiExtranet'];
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
	   $checkOikeus = "kohteet_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->
*/
       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";
		//$criteria->condition = "";

		$from = date("d.m.Y", strtotime("-1 month"));
		$to = date("d.m.Y");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}

	        $criteria->addCondition (" DATE(time) BETWEEN '".date("Y-m-d",strtotime($from))."' AND '".date("Y-m-d",strtotime($to))."' ");

		$dataProvider=new CActiveDataProvider('VinkkiExtranet', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 30;
		$this->render('index', array(
			'dataProvider' => $dataProvider,
			'from' => $from,
			'to' => $to,
		));

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new VinkkiExtranet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['VinkkiExtranet']))
			$model->attributes=$_GET['VinkkiExtranet'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return VinkkiExtranet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=VinkkiExtranet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param VinkkiExtranet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vinkki-extranet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function tilaMuutos($tila)
	{
		if($tila == 0)
		{
			echo '<span class="btn btn-sm btn-warning btn-block">'.Yii::t('main', 'avoin').'</span>';
		} else {
			echo '<span class="btn btn-sm btn-success btn-block">'.Yii::t('main', 'soitettu').'</span>';
		}
	}


}

<?php

class TietosuojaController extends Controller
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
				'actions'=>array('create','update'),
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
		$model=new Tietosuoja;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tietosuoja']))
		{
			$model->attributes=$_POST['Tietosuoja'];
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

		if(isset($_POST['Tietosuoja']))
		{
			$model->attributes=$_POST['Tietosuoja'];
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
	public function actionIndex($id)
	{
		$model=Tietosuoja::model()->findByPk($id);
		if( !isset($model->id) ){
			$new = new Tietosuoja;
			$new->id = 1;
			if($new->save())
				$this->redirect(array('index','id'=>1));
		}

		if(isset($_POST['Tietosuoja']))
		{
			$model->attributes=$_POST['Tietosuoja'];
			if($model->save())
				$this->redirect(array('index','id'=>$model->id));
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tietosuoja('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tietosuoja']))
			$model->attributes=$_GET['Tietosuoja'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tietosuoja the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tietosuoja::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tietosuoja $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tietosuoja-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function AsiakasMobileLaskin()
	{
		$last_pvm = '';
		$ts=Tietosuoja::model()->findByPk(1);
		if( $ts->asiakas_sailytysajan_tyyppi == 0 ){
			$last_pvm = date("Y-m-d", strtotime(" -".$ts->asiakas_sailytysaika_lukumaara." day"));
		}
		if( $ts->asiakas_sailytysajan_tyyppi == 1 ){
			$last_pvm = date("Y-m-d", strtotime(" -".$ts->asiakas_sailytysaika_lukumaara." month"));
		}
		if( $ts->asiakas_sailytysajan_tyyppi == 2 ){
			$last_pvm = date("Y-m-d", strtotime(" -".$ts->asiakas_sailytysaika_lukumaara." year"));
		}

		$data = array();
	       	$criteria = new CDbCriteria();
	       	//$criteria->select = " id,kohdenID, DATE(time) as time"; 
	       	$criteria->order = " DATE(time) DESC"; 
	       	$criteria->group = " kohdenID DESC"; 
	       	$criteria->condition = " 
			kohdenID!=0
		";
//			AND DATE(time) > $last_pvm
		$data = Mobile::model()->findAll($criteria);

		return array($last_pvm, $data);
	}
}

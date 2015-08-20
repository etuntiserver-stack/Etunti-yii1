<?php

class ToteutuneetController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view','luetutpvmtid','totpvmtid','al','totyhteensa','sunyhteensa'),
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

	public function actionAl($str){

		$this->renderPartial('al',array(
			'str'=>$str,
		));
	}

	public function actionTotyhteensa($pvm,$tid)
	{
		$this->renderPartial('totyhteensa',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
		));
	}

	public function actionSunyhteensa($pvm,$tid)
	{
		$this->renderPartial('sunyhteensa',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
		));

	}

	public function actionLuetutPvmTid($pvm,$tid,$from)
	{
		$this->renderPartial('luetutpvmtid',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'from'=>$from,
		));
	}

	public function actionTotPvmTid($pvm,$tid,$from)
	{

		$this->renderPartial('totpvmtid',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'from'=>$from,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
	   function sprint($val){
	       if($val > 0)
	   	   return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	   }

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
		$model=new Toteutuneet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Toteutuneet']))
		{
			$model->attributes=$_POST['Toteutuneet'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->renderPartial('create',array(
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

		if(isset($_POST['Toteutuneet']))
		{
			$model->attributes=$_POST['Toteutuneet'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->renderPartial('update',array(
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
	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		if(Yii::app()->request->getPost('etsi_month') == 'kaikki')
		    unset(Yii::app()->session['etsi_month']);
		  if(Yii::app()->request->getPost('etsi_month') and Yii::app()->request->getPost('etsi_month') != 'kaikki')
		  {
		    Yii::app()->session['etsi_month'] = Yii::app()->request->getPost('etsi_month');
  		}
		if(Yii::app()->request->getPost('etsi_tekijan_nimi') == 'kaikki')
		unset(Yii::app()->session['etsi_tekijan_nimi']);
		if(Yii::app()->request->getPost('etsi_tekijan_nimi') and Yii::app()->request->getPost('etsi_tekijan_nimi') != 'kaikki'){
		Yii::app()->session['etsi_tekijan_nimi'] = Yii::app()->request->getPost('etsi_tekijan_nimi');
		}
		if(Yii::app()->request->getPost('etsi_pvm') == 'kaikki')
		unset(Yii::app()->session['etsi_pvm']);
		if(Yii::app()->request->getPost('etsi_pvm') and Yii::app()->request->getPost('etsi_pvm') != 'kaikki'){
		Yii::app()->session['etsi_pvm'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('etsi_pvm')));
		}

       		$criteria = new CDbCriteria();

        	//$criteria->condition = " l_loppu = '' and l_alku = '' ";

        	$criteria->order = 'id DESC';
        	$criteria->group = "DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'),'%Y%m%d')";

		if(Yii::app()->session['etsi_tekijan_nimi'])
	        $criteria->addCondition ("tekijan_nimi = '".Yii::app()->session['etsi_tekijan_nimi']."'");
		if(Yii::app()->session['etsi_pvm'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".Yii::app()->session['etsi_pvm']."' ");
		if(Yii::app()->session['etsi_month'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m') = '".Yii::app()->session['etsi_month']."' ");
		$dataProvider=new CActiveDataProvider('Sivexkuitti', array(
			'criteria'=>$criteria,
			'pagination'=>false
		));

		//$dataProvider->pagination->pageSize = 50;
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Toteutuneet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Toteutuneet']))
			$model->attributes=$_GET['Toteutuneet'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Toteutuneet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Toteutuneet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Toteutuneet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='toteutuneet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

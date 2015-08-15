<?php


class SivexkuittiController extends Controller
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
			//'postOnly + delete', // we only allow deletion via POST request

        	array(
        	        'ext.starship.RestfullYii.filters.ERestFilter + 
	                REST.GET, REST.PUT, REST.POST, REST.DELETE'
            		),
       
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
				'actions'=>array('admin','delete','create','update','index','view','updatetime'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),

            		array('allow', 'actions'=>array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE'),
                		'expression'=>"Yii::app()->controller->imeiCheck()",
            		),
            		array('deny', 'actions'=>array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE'),
                		'message' => Yii::t('main', 'Imei error'),
            		),

			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actions()
	{

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: PUT, GET, POST");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

	$identity=new UserIdentity('demo','111111');
	if($identity->authenticate())
	    Yii::app()->user->login($identity);
	else
	    echo $identity->errorMessage;

	        return array(
	            'REST.'=>'ext.starship.RestfullYii.actions.ERestActionProvider',
	        );


	}


	public function imeiCheck() {

		if(isset($_SESSION['imei']))
		$m = Tyontekijat::model()->find(" imei = '".$_SESSION['imei']."' ");

	        if(isset($m->imei) and $m->imei == $_SESSION['imei'])
	            return true;
		else
	            return false;
	}

	public function isEtuntiAdmin() {

		$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	        if($m->id == Yii::app()->user->adminID)
	            return true;
		else
	            return false;
	}


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */


	public function actionUpdatetime()
	{

 		$newdate = date("d.m.Y H:i:s",strtotime($_POST['value']));

		$model = $this->loadModel($_POST['id']);
		$model->$_POST['request']=$newdate;
		$model->status=$_POST['status'];
		$model->save();
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
		$model=new Sivexkuitti;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Sivexkuitti']))
		{
			$model->attributes=$_POST['Sivexkuitti'];
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

		if(isset($_POST['Sivexkuitti']))
		{
			$model->attributes=$_POST['Sivexkuitti'];
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

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}


		if(Yii::app()->request->getPost('etsi_tekijan_nimi') == 'kaikki')
		unset(Yii::app()->session['etsi_tekijan_nimi']);
		if(Yii::app()->request->getPost('etsi_tekijan_nimi') and Yii::app()->request->getPost('etsi_tekijan_nimi') != 'kaikki'){
		Yii::app()->session['etsi_tekijan_nimi'] = Yii::app()->request->getPost('etsi_tekijan_nimi');
		}

		if(Yii::app()->request->getPost('etsi_kohteet') == 'kaikki')
		unset(Yii::app()->session['etsi_kohteet']);
		if(Yii::app()->request->getPost('etsi_kohteet') and Yii::app()->request->getPost('etsi_kohteet') != 'kaikki'){
		Yii::app()->session['etsi_kohteet'] = Yii::app()->request->getPost('etsi_kohteet');
		}
		if(Yii::app()->request->getPost('etsi_pvm') == 'kaikki')
		unset(Yii::app()->session['etsi_pvm']);
		if(Yii::app()->request->getPost('etsi_pvm') and Yii::app()->request->getPost('etsi_pvm') != 'kaikki'){
		Yii::app()->session['etsi_pvm'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('etsi_pvm')));
		}

       		$criteria = new CDbCriteria();
        	$criteria->order = 'time DESC';

		if(Yii::app()->session['etsi_tekijan_nimi'])
	        $criteria->addCondition ("tekijan_nimi = '".Yii::app()->session['etsi_tekijan_nimi']."'");
		if(Yii::app()->session['etsi_kohteet'])
	        $criteria->addCondition ("kohde_kannasta = '".Yii::app()->session['etsi_kohteet']."'");
		if(Yii::app()->session['etsi_pvm'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".Yii::app()->session['etsi_pvm']."' ");

		$dataProvider=new CActiveDataProvider('Sivexkuitti', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 100;
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Sivexkuitti('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Sivexkuitti']))
			$model->attributes=$_GET['Sivexkuitti'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Sivexkuitti the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Sivexkuitti::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Sivexkuitti $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sivexkuitti-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}




}

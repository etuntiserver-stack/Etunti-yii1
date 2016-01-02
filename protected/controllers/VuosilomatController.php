<?php

class VuosilomatController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view','vlupdater'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','vlupdater'),
                		'message'=>Yii::t('main', 'Tämä TASO ei kuuluu teille'),
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

		if(isset(Yii::app()->user->adminID) and in_array('2',$tas))
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


	public function actionVlupdater($id,$txt,$lat)
	{

	
	   if($id == 'new' and $txt == '')
	   {
		$model=new Vuosilomat;
		if(isset($_POST['Vuosilomat']))
		{
			$model->attributes=$_POST['Vuosilomat'];
			if($model->save()){
				echo $model->id.'//'.$model->tid.'//'.$model->pvm.'//'.$model->status;

			//$valikkoot = Valikkoot::model()->find(" select_type='tyoajanlaatu' and value like '%".$lat."%' ");
			$tv = new Tyovuoroot;
			$tv->tid=$model->tid;
			$tv->pvm=date("d.m.Y",strtotime($model->pvm));
			$tv->tyoajanlaatu=$lat;
			$tv->alku='00:00';
			$tv->loppu='00:00';
			$tv->pituus='00:00';
			$tv->save();
			} else {
				print_r($_POST);
			}
		}

	   } else {
		Tyovuoroot::model()->deleteAll(" tid = '".$_POST['Vuosilomat']['tid']."' and pvm='".date("d.m.Y",strtotime($_POST['Vuosilomat']['pvm']))."' and tyoajanlaatu like '%".$txt."%' ");
		$this->loadModel($id)->delete();
				echo 'removed';
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
		$model=new Vuosilomat;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Vuosilomat']))
		{
			$model->attributes=$_POST['Vuosilomat'];
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

		if(isset($_POST['Vuosilomat']))
		{
			$model->attributes=$_POST['Vuosilomat'];
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


		$dataProvider=new CActiveDataProvider('Vuosilomat');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Vuosilomat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Vuosilomat']))
			$model->attributes=$_GET['Vuosilomat'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Vuosilomat the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Vuosilomat::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Vuosilomat $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vuosilomat-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	protected function pyhat($date){

	$dateMonth = '';
	$pyh = array();

	$dateMonth = date("d.m.Y",strtotime($date));
	$asetukset = Asetukset::model()->findbypk(1);

	if(isset($asetukset->pyhapaivat))
	{
	$pyh = explode("\n",$asetukset->pyhapaivat);

	if(
	   date("N",strtotime($date)) == 6 
	   or date("N",strtotime($date)) == 7
	   or strstr($asetukset->pyhapaivat, $dateMonth)
	)
	return true;
	else
	return false;
 	}

	}

}

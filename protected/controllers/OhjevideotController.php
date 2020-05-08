<?php

class OhjevideotController extends Controller
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
				'expression' => "Yii::app()->User->isAdmin() || Yii::app()->controller->isDigisten()",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'expression' => "Yii::app()->User->isAdmin() || Yii::app()->controller->isDigisten()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'expression' => "Yii::app()->User->isAdmin() || Yii::app()->controller->isDigisten()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function isDigisten() {

		if($this->tasot(999))
		{
	            return true;
		} else {
	            return false;
		}
	}

	protected function tasot($num)
	{
		$tas = array();
		if(isset(Yii::app()->user->adminPaketti)) 
		$tas = explode(",",Yii::app()->user->adminPaketti);
		if(in_array($num,$tas))
		return true;
		else
		return false;
	}

        public function init()
        {
		/*
                if (Yii::app()->user->isAdmin()){
                        Yii::app()->theme = 'admin';
                } elseif ($this->isDigisten()){
                        Yii::app()->theme = 'etunti';
                } else {
                        Yii::app()->theme = 'classic';
		}
		*/
		if ($this->isDigisten())
                        Yii::app()->theme = 'etunti';
                else
                        die('Ei sallittu. Error');

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

		if (!file_exists(Yii::app()->basePath."/../lib/video")) {
  			mkdir(Yii::app()->basePath."/../lib/video", 0777, true);
  		}

		$model=new Ohjevideot;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Ohjevideot']))
		{
			$model->attributes=$_POST['Ohjevideot'];
            		$model->tiedoston_nimi=CUploadedFile::getInstance($model,'tiedoston_nimi');
            		if($model->save()){
		                $path=Yii::getPathOfAlias('webroot').'/lib/video/'.$model->tiedoston_nimi->getName();
		                $model->tiedoston_nimi->saveAs($path);
				$this->redirect(array('view','id'=>$model->id));
		        } else {
				print_r($model->getErrors());
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
		if (!file_exists(Yii::app()->basePath."/../lib/video")) {
  			mkdir(Yii::app()->basePath."/../lib/video", 0777, true);
  		}

		$model=$this->loadModel($id);
		$original_tiedosto = $model->tiedoston_nimi;

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Ohjevideot']))
		{
			$model->attributes=$_POST['Ohjevideot'];
			$tiedoston_nimi = CUploadedFile::getInstance($model, 'tiedoston_nimi');
			$model->tiedoston_nimi = $tiedoston_nimi !== null ? $tiedoston_nimi->getName() : $original_tiedosto;
            		if($model->save()){

				if (!empty($tiedoston_nimi)){
		           	     $path=Yii::getPathOfAlias('webroot').'/lib/video/'.$tiedoston_nimi->getName();
		          	      $tiedoston_nimi->saveAs($path);
				}
				$this->redirect(array('//site/ohjevideot'));

		        } else {
				var_dump($model->getErrors());
			}

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
		$mod = $this->loadModel($id);
		$this->loadModel($id)->delete();
		$file=Yii::getPathOfAlias('webroot').'/lib/video/'.$mod->tiedoston_nimi;

		if(file_exists($file))
		unlink($file);

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
/*
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Ohjevideot');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
*/
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ohjevideot('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ohjevideot']))
			$model->attributes=$_GET['Ohjevideot'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ohjevideot the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ohjevideot::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ohjevideot $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ohjevideot-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

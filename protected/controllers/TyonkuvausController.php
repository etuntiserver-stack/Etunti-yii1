<?php

class TyonkuvausController extends Controller
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
				'actions'=>array('index','view', 'create','update', 'admin','delete'),
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
		$model=new Tyonkuvaus;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyonkuvaus']))
		{
			$model->attributes=$_POST['Tyonkuvaus'];
			if($model->save())
				$this->redirect(array('update','id'=>$model->id));
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

		if(isset($_POST['Tyonkuvaus']))
		{
			$model->attributes=$_POST['Tyonkuvaus'];

			if(isset($_POST['TyonkuvausRivit']['tilat']))
			{

				// <-- Poistetaan edelliset
				TyonkuvausRivit::model()->deleteAll("tyonkuvaus_id='".$id."'");

				foreach($_POST['TyonkuvausRivit']['tilat'] as $key=>$items)
				{
					$tilat = $items;
					if(isset($_POST['TyonkuvausRivit']['tyotehtava'][$key]))
					{
						$tyontehtavat = array();
						foreach($_POST['TyonkuvausRivit']['tyotehtava'][$key] as $k2=>$i2)
						{
							$tyontehtavat[$k2] = array('tyotehtava'=>$i2, 'vkopvm' => $_POST['TyonkuvausRivit']['vkopvm'][$key][$k2]);
						}
					}

					$laatutasot = '';
					if(isset($_POST['TyonkuvausRivit']['laatutaso'][$key]))
					{
						$laatutasot = $_POST['TyonkuvausRivit']['laatutaso'][$key];
					}

					$kommenti = '';
					if(isset($_POST['TyonkuvausRivit']['kommenti'][$key]))
					{
						$kommenti = $_POST['TyonkuvausRivit']['kommenti'][$key];
					}


					$tk_rivit = new TyonkuvausRivit;
					$tk_rivit->tyonkuvaus_id = $model->id;
					$tk_rivit->tilat = json_encode($tilat);
					$tk_rivit->tyontehtavat = json_encode($tyontehtavat);
					$tk_rivit->laatutaso = json_encode($laatutasot);
					$tk_rivit->kommenti = $kommenti;
					$tk_rivit->save();

					/*
					echo '<pre>';
					print_r($laatutasot);
					echo '</pre>';
					echo '<hr>';
					*/
				}
			}
			//exit;


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
		TyonkuvausRivit::model()->deleteAll("tyonkuvaus_id='".$id."'");

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Tyonkuvaus');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tyonkuvaus('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyonkuvaus']))
			$model->attributes=$_GET['Tyonkuvaus'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tyonkuvaus the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tyonkuvaus::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tyonkuvaus $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyonkuvaus-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

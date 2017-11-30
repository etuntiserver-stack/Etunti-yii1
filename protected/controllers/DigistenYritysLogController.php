<?php

class DigistenYritysLogController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete'),
				'expression' => "Yii::app()->controller->isDigisten()",
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
                Yii::app()->theme = 'etunti';
                parent::init();
        }

	public function addTapahtuma($yritys_id, $tapahtuma, $paketti)
	{
		$model=new DigistenYritysLog;
		$model->yritys_id = $yritys_id;
		$model->tapahtuma = $tapahtuma;
		$model->paketti = $paketti;
		$model->save();
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
		$model=new DigistenYritysLog;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DigistenYritysLog']))
		{
			$model->attributes=$_POST['DigistenYritysLog'];
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

		if(isset($_POST['DigistenYritysLog']))
		{
			$model->attributes=$_POST['DigistenYritysLog'];
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

		if(isset($_POST['asiakkaatPerSivu']))
		{
			Yii::app()->user->setState('asiakkaatPerSivu', $_POST['asiakkaatPerSivu']);
			echo json_encode($_POST['asiakkaatPerSivu']);
			exit;
		}

       		$criteria = new CDbCriteria();
		$criteria->order = " id DESC ";

		if(isset($_GET['yritys']) and !empty($_GET['yritys']))
	        $criteria->addCondition (" yritys_id='".$_GET['yritys']."' ");

		$perSivu = 50;
		if(isset(Yii::app()->user->asiakkaatPerSivu))
		$perSivu = Yii::app()->user->asiakkaatPerSivu;

		$dataProvider=new CActiveDataProvider('DigistenYritysLog', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));
		$dataProvider->pagination->pageSize = $perSivu;

		$this->render('index', array(
			'dataProvider' => $dataProvider, 
			'perSivu' => $perSivu,
		));

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DigistenYritysLog('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DigistenYritysLog']))
			$model->attributes=$_GET['DigistenYritysLog'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DigistenYritysLog the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DigistenYritysLog::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DigistenYritysLog $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='digisten-yritys-log-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

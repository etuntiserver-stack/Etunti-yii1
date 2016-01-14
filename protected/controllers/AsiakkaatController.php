<?php

class AsiakkaatController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view', 'checkLastAsiakasID'),
                		//'expression'=>"Yii::app()->user->username == 'roman'",
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

	public function actionCheckLastAsiakasID()
	{
		$check = 0;
		$model=Asiakkaat::model()->find(" asiakasnumero='".$_POST['checkLastAsiakasID']."' ");
		if(isset($model->id))
		$check = 1;

		echo $check;
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
		$model=new Asiakkaat;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Asiakkaat']))
		{
			$model->attributes=$_POST['Asiakkaat'];
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

		if(isset($_POST['Asiakkaat']))
		{
			$model->attributes=$_POST['Asiakkaat'];
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
       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";

		if(isset($_POST['osoite']) and !empty($_POST['osoite']))
	        $criteria->addCondition (" id='".$_POST['osoite']."' ");

		if(isset($_POST['yhteyshenkilo']) and !empty(trim($_POST['yhteyshenkilo'])))
	        $criteria->addCondition (" yhteyshenkilo LIKE '%".$_POST['yhteyshenkilo']."%' ");

		if(isset($_POST['puhelin']) and !empty(trim($_POST['puhelin'])))
	        $criteria->addCondition (" puhelin LIKE '%".$_POST['puhelin']."%' ");

		if(isset($_POST['postinumero']) and !empty(trim($_POST['postinumero'])))
	        $criteria->addCondition (" postinumero LIKE '%".$_POST['postinumero']."%' ");

		if(isset($_POST['sahkoposti']) and !empty(trim($_POST['sahkoposti'])))
	        $criteria->addCondition (" sahkoposti LIKE '%".$_POST['sahkoposti']."%' ");

		$dataProvider=new CActiveDataProvider('Asiakkaat', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Asiakkaat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Asiakkaat']))
			$model->attributes=$_GET['Asiakkaat'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Asiakkaat the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Asiakkaat::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Asiakkaat $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='asiakkaat-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


    	protected function asiakasMuutosTheme($as)
	{ 
		$return = '';

		    $a = Asiakkaat::model()->findbypk($as);
		    if(isset($a->yrityksen_nimi) and !empty($a->yrityksen_nimi))
		    $return = $a->yrityksen_nimi;
		    elseif(isset($a->yhteyshenkilo) and empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
		    $return = $a->yhteyshenkilo;
		    else
		    $return = $as;

		    if(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'henkilo')
		    $return = '<b class="text-warning">Yhteyshenkilö</b><br>'.$return;
		    elseif(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'yritys')
		    $return = '<b class="text-success">Yritys</b><br>'.$return;

            	return $return;
	}

}

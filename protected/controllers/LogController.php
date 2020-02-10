<?php

class LogController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function accessRules()
	{
		return array(

			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin', 'delete', 'create', 'update', 'index'),
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
	        if(isset($m->id))
	            return true;
		else
	            $this->redirect(array('/user/logout'));
		} else {
	            $this->redirect(array('/user/logout'));
		}
	}

        public function init()
        {

                if (Yii::app()->controller->isEtuntiAdmin() and !isset(Yii::app()->user->user_theme)) {
                        Yii::app()->theme = 'etunti';
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
		$model=new Log;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Log']))
		{
			$model->attributes=$_POST['Log'];
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

		if(isset($_POST['Log']))
		{
			$model->attributes=$_POST['Log'];
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

		if(isset($_POST['naytaModal']))
		{
			$model=$this->loadModel($_POST['id']);
			if(isset($model->id)){
				$get = $_POST['get'];
				echo json_decode($model->$get, true);
			}
			exit;
		}


		if(isset($_POST['kohteetPerSivu']))
		{
			Yii::app()->user->setState('kohteetPerSivu', $_POST['kohteetPerSivu']);
			echo json_encode($_POST['kohteetPerSivu']);
			exit;
		}

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";

		if(isset($_GET['log_category']) and $_GET['log_category'] != 'kaikki')
	        $criteria->addCondition (" log_category='".(int)$_GET['log_category']."' ");

		if(isset($_GET['email_to']) and !empty($_GET['email_to']))
	        $criteria->addCondition (" email_to LIKE '%".$_GET['email_to']."%' ");

		if(isset($_GET['kuka']) and !empty($_GET['kuka']))
	        $criteria->addCondition (" kuka LIKE '%".$_GET['kuka']."%' ");

		if(isset($_GET['log_nimike']) and !empty($_GET['log_nimike']))
	        $criteria->addCondition (" log_nimike LIKE '%".$_GET['log_nimike']."%' ");

		if(isset($_GET['tilanne']) and !empty($_GET['tilanne']) and !isset($_GET['not_tilanne'])){
		        $criteria->addCondition (" tilanne LIKE '%".$_GET['tilanne']."%' ");
		}
		if(isset($_GET['tilanne']) and !empty($_GET['tilanne']) and isset($_GET['not_tilanne'])){
		        $criteria->addCondition (" tilanne NOT LIKE '%".$_GET['tilanne']."%' ");
		}

		if(isset($_GET['model']) and !empty($_GET['model']))
	        $criteria->addCondition (" model LIKE '%".$_GET['model']."%' ");

		if(isset($_GET['hakusana']) and !empty($_GET['hakusana']))
	        $criteria->addCondition (" old_values LIKE '%".$_GET['hakusana']."%' OR new_values LIKE '%".$_GET['hakusana']."%' ");

		if(isset($_GET['tyontekija']) and !empty($_GET['tyontekija']))
		{
	        	$criteria->addCondition (" old_values LIKE '%\"tid\":\"".$_GET['tyontekija']."\"%' OR new_values LIKE '%\"tid\":\"".$_GET['tyontekija']."\"%' ");
		}

		if(isset($_GET['osoite']) and !empty($_GET['osoite']))
		{
			$check = false;
			$k = Kohteet::model()->findAll("osoite LIKE '%".$_GET['osoite']."%'");
			foreach($k as $kid)
			{

			   if(isset($_GET['model']) and $_GET['model'] == 'Tyovuoroot')
			   {
		        	$criteria->compare ("old_values", "kohde\":\"".$kid->id, true); 
		        	$criteria->compare ("new_values", "kohde\":\"".$kid->id, true,  'OR'); 
				$check = true;
			   }
			   if(isset($_GET['model']) and $_GET['model'] == 'Kohteet')
			   {
		        	$criteria->compare ("old_values", "id\":\"".$kid->id, true); 
		        	$criteria->compare ("new_values", "id\":\"".$kid->id, true,  'OR'); 
				$check = true;
			   }
			   if(isset($_GET['model']) and $_GET['model'] == 'Mob')
			   {
		        	$criteria->compare ("old_values", "kohdenID\":\"".$kid->id, true); 
		        	$criteria->compare ("new_values", "kohdenID\":\"".$kid->id, true,  'OR'); 
				$check = true;
			   }

			   if(isset($_GET['model']) and $_GET['model'] == 'Mobile')
			   {
		        	$criteria->compare ("old_values", "kohdenID\":\"".$kid->id, true); 
		        	$criteria->compare ("new_values", "kohdenID\":\"".$kid->id, true,  'OR'); 
				$check = true;
			   }
			}


			if($check == false)
			{
		        	$criteria->compare ("old_values", $_GET['osoite'], true); 
		        	$criteria->compare ("new_values", $_GET['osoite'], true,  'OR'); 
			}

			//print_r($criteria);
			//exit;

		}


		if(isset($_GET['from']) and isset($_GET['to']) and !empty($_GET['from']) and !empty($_GET['to']))
		{
	        	$criteria->addCondition (" DATE(time) BETWEEN '".date("Y-m-d H:i:s", strtotime($_GET['from']))."' AND '".date("Y-m-d H:i:s", strtotime($_GET['to']))."' ");
		}

		if(!isset($_GET['mob_hae']))
		{
			$criteria->condition = " id=null ";
		}

		$dataProvider=new CActiveDataProvider('Log', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$perSivu = 50;
		if(isset(Yii::app()->user->kohteetPerSivu))
		$perSivu = Yii::app()->user->kohteetPerSivu;

		$dataProvider->pagination->pageSize = $perSivu;

		$this->render('index', array('dataProvider' => $dataProvider, 'perSivu' => $perSivu));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Log('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Log']))
			$model->attributes=$_GET['Log'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Log the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Log::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Log $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='log-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function nimikeMuutos($str){

		if($str == 'tyovuoro_lahetys')
			$str = Yii::t('main', 'Työvuorojen lähetys');
		if($str == 'uusi_tilaus')
			$str = Yii::t('main', 'Uusi tilaus');

		return $str;
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

	protected function kohdeOsoite($id)
	{
		$k = Kohteet::model()->findByPk($id);
		if(isset($k->id) and !empty($k->osoite))		
			return $k->osoite;
		else
			return false;

	}
}

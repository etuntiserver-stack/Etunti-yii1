<?php

class HinnastotController extends Controller
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

		$tas = '';
		if(isset(Yii::app()->user->adminPaketti))
		$tas = explode(",",Yii::app()->user->adminPaketti);

		if(isset(Yii::app()->user->adminID) and in_array('3',$tas))
		{
		   $m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	       	   if($m->id == Yii::app()->user->adminID)
		   {
			return true;
		   } else {
			$this->redirect(array('/site/otakaytoon', 'tila' => 'lasku'));
		   }		

		} else {
			$this->redirect(array('/site/otakaytoon', 'tila' => 'lasku'));
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
		$criteria = new CDbCriteria();
       		$criteria->order = " nimike ";
       		$criteria->condition = " aktiivinen=1 AND hinta_alv_0!=0 AND nayta_vain_onlinevarauksessa=0";
		$tp = TuotteetPalvelut::model()->findAll($criteria);
      		$yksikkot = Valikkoot::model()->findAll(" select_type='laskutus_yksikko' ",array('order' => "select_type"));

		$model=new Hinnastot;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Hinnastot']))
		{
			$model->attributes=$_POST['Hinnastot'];
			$model->alvsis=$_POST['alvsis'];
			if($model->save())
			{

				// <-- Riville
				HinnastotRivi::model()->deleteAll(" hinnastot_id = '".$model->id."' ");
				foreach($_POST['Rivi']['tuote']['tuote'] as $k => $itm)
				{

					$rivit = new HinnastotRivi;
					$rivit->hinnastot_id = $model->id;
					$rivit->tuote_palvelu_id = $itm;
					$rivit->hinnasto_hinta = str_replace(",", ".", $_POST['Rivi']['tuote']['hinnasto_hinta'][$k]);
					$rivit->hinta_tuote = $_POST['Rivi']['tuote']['hinta_tuote'][$k];
					$rivit->hinta_tuote_sis = str_replace(",", ".", $_POST['Rivi']['tuote']['hinta_tuote_sis'][$k]);
					$rivit->hinnasto_alv = $_POST['Rivi']['tuote']['hinnasto_alv'][$k];
					$rivit->hinnasto_yksikko = $_POST['Rivi']['tuote']['yksikko'][$k];
					$rivit->hinnasto_yht = $_POST['Rivi']['tuote']['hinnasto_yht'][$k];
					if(!$rivit->save()){
						var_dump($rivit->getErrors());
						exit;
					}
				}
				//     Riville -->

				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'tp'=>$tp,
			'yksikkot'=>$yksikkot,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$criteria = new CDbCriteria();
       		$criteria->order = " nimike ";
       		$criteria->condition = " aktiivinen=1 AND hinta_alv_0!=0 AND nayta_vain_onlinevarauksessa=0";
		$tp = TuotteetPalvelut::model()->findAll($criteria);
      		$yksikkot = Valikkoot::model()->findAll(" select_type='laskutus_yksikko' ",array('order' => "select_type"));

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Hinnastot']))
		{
			$model->attributes=$_POST['Hinnastot'];
			$model->alvsis=$_POST['alvsis'];
			if($model->save())
			{

				// <-- Riville
				HinnastotRivi::model()->deleteAll(" hinnastot_id = '".$model->id."' ");
				foreach($_POST['Rivi']['tuote']['tuote'] as $k => $itm)
				{

					$rivit = new HinnastotRivi;
					$rivit->hinnastot_id = $model->id;
					$rivit->tuote_palvelu_id = $itm;
					$rivit->hinnasto_hinta = str_replace(",", ".", $_POST['Rivi']['tuote']['hinnasto_hinta'][$k]);
					$rivit->hinta_tuote = $_POST['Rivi']['tuote']['hinta_tuote'][$k];
					$rivit->hinta_tuote_sis = str_replace(",", ".", $_POST['Rivi']['tuote']['hinta_tuote_sis'][$k]);
					$rivit->hinnasto_alv = $_POST['Rivi']['tuote']['hinnasto_alv'][$k];
					$rivit->hinnasto_yksikko = $_POST['Rivi']['tuote']['yksikko'][$k];
					$rivit->hinnasto_yht = $_POST['Rivi']['tuote']['hinnasto_yht'][$k];
					if(!$rivit->save()){
						var_dump($rivit->getErrors());
						exit;
					}
				}
				//     Riville -->

				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'tp'=>$tp,
			'yksikkot'=>$yksikkot,
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
		$dataProvider=new CActiveDataProvider('Hinnastot');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Hinnastot('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Hinnastot']))
			$model->attributes=$_GET['Hinnastot'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Hinnastot the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Hinnastot::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Hinnastot $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='hinnastot-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

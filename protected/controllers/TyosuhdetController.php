<?php

class TyosuhdetController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view'),
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
		$model=new Tyosuhdet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyosuhdet']))
		{
			$model->attributes=$_POST['Tyosuhdet'];
			if($model->save()){

				// <-- LOG
				$model_log 	= 'Tyosuhdet';
				$name_log 	= 'Työsuhteet';
				$status_log 	= 'Create';
	
					$old_values = null;
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->

				echo json_encode('saveOK');
			} else {
				echo json_encode('saveError');
			}
			exit;
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

		if(isset($_POST['Tyosuhdet']))
		{
			$vanha_attr = $model->attributes;
			$model->attributes=$_POST['Tyosuhdet'];
			if($model->save()){

				// <-- LOG
				$model_log 	= 'Tyosuhdet';
				$name_log 	= 'Työsuhteet';
				$status_log 	= 'Update';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->

				$fields = ["loppu"];
				$dataChanged = $this->integromatDataChanged($vanha_attr, $model->attributes, $fields);
				// contract-changed is read in themes/etunti/views/tyosuhdet/_form.php
				// submit function, around row 430, it's appended as an hidden field to the
				// Tyontekijat form, and read again in TyontekijatControllers actionUpdate
				echo json_encode(["response" => "saveOK", "contract-changed" => $dataChanged]);
			} else {
				echo json_encode('saveError');
			}
			exit;
				//$this->redirect(array('view','id'=>$model->id));
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
		$criteria->order = 'tekijan_nimi ASC';
        	$criteria->condition = " aktiivinen=1 ";

		if(Yii::app()->request->getPost('TekijaVuoro'))
		{
			Yii::app()->session['TekijaVuoro'] = Yii::app()->request->getPost('TekijaVuoro');
		}

		if(Yii::app()->session['TekijaVuoro'])
		{
			$impl = implode(",",Yii::app()->session['TekijaVuoro']);
			$criteria->addCondition ( " 
				id IN ($impl)
			");
		}

		$model = Tyontekijat::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
		  if(isset($_POST['sarakkeet'])) 
			$_POST['sarakkeet'] = json_decode($_POST['sarakkeet']);

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('index', array(
			'model' => $model
		  ),true));
	          $html2pdf->Output();
		} else {
		  $this->render('index', array('model' => $model));
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tyosuhdet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyosuhdet']))
			$model->attributes=$_GET['Tyosuhdet'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tyosuhdet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tyosuhdet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tyosuhdet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyosuhdet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

	/**
	 * Compares attributes that we would send to integromat, if the old models
	 * attributes don't match with the new attributes that would be saved to the datab ase,
	 * return true for "has changed". Otherwise return false for "not changed".
	 * This can be used to reduce the number of requests sent to integromat.
	 */
	private function integromatDataChanged($old_attr, $new_attr, $fields)
	{
		foreach($fields as $field) {
			// if any of the fields don't match, return true (for data is changed)
			// and don't even bother looking at the rest
			if($old_attr[$field] != $new_attr[$field]) {
				return 1;
			}
		}
		return 0;
	}

}

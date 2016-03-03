<?php

class TyontekijatController extends Controller
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
				'actions'=>array('admin', 'admin_ajax', 'delete', 'create', 'update', 'index', 'view','merkkipaivat', 'tulosta', 'migraatio'),
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

	public function actionMerkkipaivat()
	{
		//STR_TO_DATE(sivexkuitti.aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))

	       	$criteria = new CDbCriteria();
		$criteria->select = " 
			SUBSTRING_INDEX(tekijan_henkilotunnus,'-',1) as tunnus
			,t.*
		";
		//$criteria->order = " tekijan_henkilotunnus DESC";
		$criteria->condition = " aktiivinen='1' AND tekijan_henkilotunnus !='' ";

		$model=Tyontekijat::model()->findAll($criteria);
		$this->render('merkkipaivat',array(
			'model'=>$model,
		));
	}

	public function actionMigraatio()
	{
		$this->render('migraatio');
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionTulosta($id)
	{
			$model = Tyontekijat::model()->findbypk($id); 
	
		        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
			$html2pdf->setDefaultFont('Arial');
		        $html2pdf->WriteHTML($this->renderPartial('tulosta_pdf', array('model' => $model),true));
		        $html2pdf->Output();
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Tyontekijat;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyontekijat']))
		{
			$model->attributes=$_POST['Tyontekijat'];

			$imei=Tyontekijat::model()->find(" imei='".$_POST['Tyontekijat']['imei']."' and imei!='' ");
			if(isset($imei->imei))
			{
			echo Yii::t('main', 'Tämän imei on jo käytössä');
			exit;
			}

			if(isset($_POST['kortit'])) $model->kortit = implode("##***",$_POST['kortit']);
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

		if(isset($_POST['Tyontekijat']))
		{

			$model->attributes=$_POST['Tyontekijat'];

			$imei=Tyontekijat::model()->find(" id!='".$model->id."' and imei='".$_POST['Tyontekijat']['imei']."' and imei!='' ");
			if(isset($imei->imei))
			{
			echo Yii::t('main', 'Tämän imei on jo käytössä');
			exit;
			}

			if(isset($_POST['kortit'])) $model->kortit = implode("##***",$_POST['kortit']);
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
	
	   $filename = "../../img/tekijat/".Yii::app()->user->domain."/".$id.".jpg";
	   if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$id.".jpg"))
	   unlink(Yii::app()->basePath.$filename);

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

		if(isset($_POST['aktiivinen']) and $_POST['aktiivinen'] != 'kaikki')
	        $criteria->addCondition (" aktiivinen ='".(int)$_POST['aktiivinen']."' ");
		else
	        $criteria->addCondition (" aktiivinen=1 ");

		if(isset($_POST['osoite']) and !empty($_POST['osoite']))
	        $criteria->addCondition (" tekijan_katuosoite LIKE '%".$_POST['osoite']."%' ");

		if(isset($_POST['nimi']) and !empty(trim($_POST['nimi'])))
	        $criteria->addCondition (" tekijan_nimi LIKE '%".$_POST['nimi']."%' ");

		if(isset($_POST['puhelin']) and !empty(trim($_POST['puhelin'])))
	        $criteria->addCondition (" laiten_puh LIKE '%".$_POST['puhelin']."%' OR tekijan_puh LIKE '%".$_POST['puhelin']."%' ");

		if(isset($_POST['sahkoposti']) and !empty(trim($_POST['sahkoposti'])))
	        $criteria->addCondition (" tekijan_email LIKE '%".$_POST['sahkoposti']."%' ");

		$dataProvider=new CActiveDataProvider('Tyontekijat', array(
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
		$model=new Tyontekijat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyontekijat']))
			$model->attributes=$_GET['Tyontekijat'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAdmin_ajax()
	{

		$model=new Tyontekijat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyontekijat']))
			$model->attributes=$_GET['Tyontekijat'];

		$this->renderPartial('admin_ajax',array(
			'model'=>$model,
		));

	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tyontekijat the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tyontekijat::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tyontekijat $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyontekijat-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

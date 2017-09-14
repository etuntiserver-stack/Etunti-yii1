<?php

class VinkkiExtranetController extends Controller
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
				'actions'=>array('vastaus'),
				'users'=>array('*'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'lahetetty'),
                		'expression'=>"Yii::app()->controller->isAsiakas() or Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create'),
                		'expression'=>"Yii::app()->controller->isAsiakas()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','view','admin','delete','update', 'arvomuutos', 'arvohaku'),
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

	public function isAsiakas() 
	{
		if(isset(Yii::app()->user->asiakas))
		{
		$m = Asiakkaat::model()->findbypk(Yii::app()->user->asiakas);
	        if($m->id == Yii::app()->user->asiakas)
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

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionVastaus($domain, $asia, $id, $token)
	{
		Yii::app()->theme = 'classic';
		$vastaus = '';
       		$criteria = new CDbCriteria();
	        $criteria->condition = " id='".$id."' AND token='".$token."' ";
		$model = VinkkiExtranet::model()->find($criteria);
		$firma = FirmanTiedot::model()->findbypk(1);;
		if( !isset($model->id) )
		{
			$vastaus = 'Error';

		} elseif( isset($model->id) and $asia == 1) {

			$vastaus = '
			Olemme tallettaneet yhteystietonne rekisteriimme ja olemme teihin yhteydessä lähiaikoina.<br>
			Terveisin<br>
			'.$firma->tyonantaja;
			VinkkiExtranet::model()->updateByPk($id, array('token' => ''));

		} elseif( isset($model->id) and $asia == 0) {

			if(!isset($_GET['confirm']))
			{
			$vastaus = 'Haluatko varmasti hylätä suosituksen? Mikäli hylkäät suosituksen tietojasi ei talleteta järjestelmään.';
			$vastaus .= '<br>
			<div class="col-sm-6 col-sm-offset-5">
			  <div class="row">
			    <div class="col-sm-4">
			     <button class="btn-group btn btn-success btn-block yes">'.Yii::t('main', 'Kyllä').'</button>
			    </div>
			 </div>
			</div>';
			}

			if(isset($_GET['confirm']) and $_GET['confirm'] == 1)
			{
			$vastaus = 'Olet hylännyt suosituksen. Tietojasi ei talletettu järjestelmään.<br> Kiitos';
			$model->delete();
			}
		}

		$this->render('vastaus', array(
			'model'=>$model,
			'vastaus'=>$vastaus
		));
	}

	public function actionLahetetty($asiakas_id)
	{
		Yii::app()->theme = 'customer';
		$this->render('lahetetty', array('asiakas_id'=>$asiakas_id));
	}

	public function actionCreate()
	{
                Yii::app()->theme = 'customer';
		$as = Asiakkaat::model()->findbypk(Yii::app()->user->asiakas);
		$model=new VinkkiExtranet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['VinkkiExtranet']))
		{
			$model->attributes=$_POST['VinkkiExtranet'];
			if($model->save())
				$this->redirect(array('lahetetty','asiakas_id'=>$as->id));
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

		if(isset($_POST['VinkkiExtranet']))
		{
			$model->attributes=$_POST['VinkkiExtranet'];
			$model->muutos_pvm = date("Y-m-d H:i:s");
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

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

/*
	// <-- Oikeudet
	   $checkOikeus = "kohteet_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->
*/
		if(isset($_POST['yrityksen_nimi']) and empty($_POST['yrityksen_nimi']))
			unset(Yii::app()->session['yrityksen_nimi']);
		else if(isset($_POST['yrityksen_nimi']) and !empty($_POST['yrityksen_nimi']))
			Yii::app()->session['vinkkit_yrityksen_nimi'] = Yii::app()->request->getPost('yrityksen_nimi');
		if(isset($_POST['sahkoposti']) and empty($_POST['sahkoposti']))
			unset(Yii::app()->session['sahkoposti']);
		else if(isset($_POST['sahkoposti']) and !empty($_POST['sahkoposti']))
			Yii::app()->session['sahkoposti'] = Yii::app()->request->getPost('sahkoposti');

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";
		$criteria->condition = " token='' ";

		$from = date("d.m.Y", strtotime("-1 month"));
		$to = date("d.m.Y");



		if(Yii::app()->session['sahkoposti'])
	        $criteria->addCondition (" sahkoposti LIKE '%".Yii::app()->session['sahkoposti']."%' ");


		if(isset(Yii::app()->session['vinkkit_yrityksen_nimi']))
		{
	        	$criteria->addCondition (" 
				asiakas_id IN (SELECT id FROM asiakkaat 
				WHERE (yrityksen_nimi LIKE '%".Yii::app()->session['yrityksen_nimi']."%' OR yhteyshenkilo LIKE '%".Yii::app()->session['yrityksen_nimi']."%')
				) 
			");
		}


		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}

	        $criteria->addCondition (" DATE(time) BETWEEN '".date("Y-m-d",strtotime($from))."' AND '".date("Y-m-d",strtotime($to))."' ");

		$dataProvider=new CActiveDataProvider('VinkkiExtranet', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 30;
		$this->render('index', array(
			'dataProvider' => $dataProvider,
			'from' => $from,
			'to' => $to,
		));

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new VinkkiExtranet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['VinkkiExtranet']))
			$model->attributes=$_GET['VinkkiExtranet'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return VinkkiExtranet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=VinkkiExtranet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param VinkkiExtranet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{


		if(isset($_POST['ajax']) && $_POST['ajax']==='vinkki-extranet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function tilaMuutos($data)
	{
		if($data->tila == 0)
		{
			echo '<span class="btn btn-sm btn-danger btn-block painamalla" arvo="0" id="'.$data->id.'">'.Yii::t('main', 'Avoin').'</span>';
		} elseif($data->tila == 1) {
			echo '<span class="btn btn-sm btn-warning btn-block painamalla" arvo="1" id="'.$data->id.'">'.Yii::t('main', 'Hoidettu').'</span>';
		} elseif($data->tila == 2) {
			echo '<span class="btn btn-sm btn-success btn-block painamalla" arvo="2" id="'.$data->id.'">'.Yii::t('main', 'Asiakas').'</span>';
		}
	}


	public function actionArvohaku($id)
	{
		$model=$this->loadModel($id);
		echo $model->tila;
	}

	public function actionArvomuutos($id)
	{
	    if(isset($_POST['arvo']))
	    {
		VinkkiExtranet::model()->updateByPk($id, array('tila' => $_POST['arvo']));
		echo 'ok';
	    }
	}

}

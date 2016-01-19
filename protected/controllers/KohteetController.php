<?php

class KohteetController extends Controller
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
				'actions'=>array('admin','delete','create','update','index', 'view','osoite','autotaytaminen','createfromasiakas', 'googlemap','googlemap_k', 'avaimet'),
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

	public function actionAvaimet()
	{


		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

	       	$criteria = new CDbCriteria();
		$criteria->condition = "aktiivinen=1";

		if(Yii::app()->session['Tekija']){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

	        $criteria->addCondition ('id IN ('.$ids.') ');
		}


		$model = Tyontekijat::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('avaimet', array('model' => $model),true));
	          $html2pdf->Output();
		} else {
		$this->render('avaimet',array(
			'model'=>$model,
		));
		}

	}

	public function actionGooglemap()
	{

		$this->render('googlemap');
	}

	public function actionGooglemap_k()
	{

		$model=Kohteet::model()->findAll();

		$this->renderPartial('googlemap_k',array(
			'model'=>$model,
		));
	}

	public function actionAutotaytaminen($id)
	{
		$m=Asiakkaat::model()->findbypk($id);

		$ryhma = 0;
		if($m->ryhma != 0)
		{
		$l = Valikkoot::model()->findbypk($m->ryhma);
		$ryhma = $l['id']."-".$l['value'];
		}

		echo $m->yhteyshenkilo."//".$m->kaupunki."//".$m->postinumero."//".$m->sahkoposti."//".$m->puhelin."//".$ryhma;
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionOsoite($osoite)
	{
		$model = Kohteet::model()->find(" osoite = '".$osoite."' ");
		echo $model['id'];
	}

	public function actionCreatefromasiakas($id)
	{
		$model=new Kohteet;
		$asiakas=Asiakkaat::model()->findbypk($id);
		if(isset($_POST['Kohteet']))
		{
			$model->attributes=$_POST['Kohteet'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('createfromasiakas',array(
			'model'=>$model,
			'asiakas'=>$asiakas,
		));
	}

	public function actionCreate()
	{
		$model=new Kohteet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Kohteet']))
		{
			$model->attributes=$_POST['Kohteet'];
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

		if(isset($_POST['Kohteet']))
		{
			$model->attributes=$_POST['Kohteet'];
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
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

		if(isset($_POST['nimi']) and !empty(trim($_POST['nimi'])))
	        $criteria->addCondition (" etu_suku_nimet LIKE '%".$_POST['nimi']."%' ");

		if(isset($_POST['tag']) and !empty(trim($_POST['tag'])))
	        $criteria->addCondition (" tag_id LIKE '%".$_POST['tag']."%' ");

		if(isset($_POST['avain']) and !empty(trim($_POST['avain'])))
	        $criteria->addCondition (" avain LIKE '%".$_POST['avain']."%' ");

		if(isset($_POST['sahkoposti']) and !empty(trim($_POST['sahkoposti'])))
	        $criteria->addCondition (" email LIKE '%".$_POST['sahkoposti']."%' ");

		$dataProvider=new CActiveDataProvider('Kohteet', array(
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
		$model=new Kohteet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Kohteet']))
			$model->attributes=$_GET['Kohteet'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Kohteet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Kohteet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Kohteet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='kohteet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


    	protected function asiakasMuutos($data,$row)
	{ 
		$return = '';

		    $a = Asiakkaat::model()->findbypk($data->asiakas_id);
		    if(isset($a->yrityksen_nimi) and !empty($a->yrityksen_nimi))
		    $return = $a->yrityksen_nimi;
		    elseif(isset($a->yhteyshenkilo) and empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
		    $return = $a->yhteyshenkilo;
		    else
		    $return = $data->asiakas_id;

		    if(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'henkilo')
		    $return = '<b class="text-warning">Yhteyshenkilö</b><br>'.$return;
		    elseif(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'yritys')
		    $return = '<b class="text-success">Yritys</b><br>'.$return;

            	return $return;
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

    	protected function onkoKuva($data,$row)
	{ 
		$return = '';

		foreach(array_reverse(glob(Yii::app()->basePath."/../img/uploadedfromphone/".Yii::app()->user->domain."/".$data->id."_*.*")) as $file) 
		{
			if(!empty($file))
			{
			$return = '<i class="fa fa-camera"></i>';
			break;
			}
		}
            	return $return;
	}

}

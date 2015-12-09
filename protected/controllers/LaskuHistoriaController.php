<?php

class LaskuHistoriaController extends Controller
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
				'actions'=>array('index','view','paivakirja', 'avoimet', 'maksu_paivakirja', 'paakirja', 'maksu_paakirja'),
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
	       	  return true;
		else
	       	   return false;		

		} else {
	            return false;
		}
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

	public function actionAvoimet()
	{

		$asetukset = Asetukset::model()->findbypk(1);
		$palvelu = '';
		if($asetukset->palvelu_tyyppi == 1)
		$palvelu = 'POSTITA';
		if($asetukset->palvelu_tyyppi == 2)
		$palvelu = 'TRUST';


		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));
	
		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));

       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";
       		$criteria->condition = "";


		// <-- Trust
		if($palvelu == 'TRUST')
       		$criteria->Addcondition ( "
			id NOT IN (select lid from lasku_historia where trust_statuscode='101')
		");
		// <-- Trust

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
        	$criteria->addCondition ("DATE(paivays) BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('avoimet',array('model'=>$model,'palvelu'=>$palvelu), true));
	          $html2pdf->Output();

		} else {

		$this->render('avoimet',array(
			'model'=>$model,
			'palvelu'=>$palvelu
		));

		}

	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new LaskuHistoria;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['LaskuHistoria']))
		{
			$model->attributes=$_POST['LaskuHistoria'];
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

		if(isset($_POST['LaskuHistoria']))
		{
			$model->attributes=$_POST['LaskuHistoria'];
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
		$dataProvider=new CActiveDataProvider('LaskuHistoria');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}


	public function actionPaivakirja()
	{

		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));
	
		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));

       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";
       		$criteria->condition = "";

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
        	$criteria->addCondition ("DATE(paivays) BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('paivakirja',array('model'=>$model), true));
	          $html2pdf->Output();

		} else {

		$this->render('paivakirja',array(
			'model'=>$model,
		));

		}
	}


	public function actionPaakirja()
	{

		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));
	
		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));

       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";
       		$criteria->condition = "";

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
        	$criteria->addCondition ("DATE(paivays) BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('paakirja',array('model'=>$model), true));
	          $html2pdf->Output();

		} else {

		$this->render('paakirja',array(
			'model'=>$model,
		));

		}
	}

	public function actionMaksu_paakirja()
	{

		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));
	
		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));

       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";
       		$criteria->condition = "
			id IN (select lid from lasku_historia where trust_statuscode='101')

		";

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
        	$criteria->addCondition ("DATE(paivays) BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('maksu_paakirja',array('model'=>$model), true));
	          $html2pdf->Output();

		} else {

		$this->render('maksu_paakirja',array(
			'model'=>$model,
		));

		}
	}

	public function actionMaksu_paivakirja()
	{

		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));
	
		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));

       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";
       		$criteria->condition = "
			id IN (select lid from lasku_historia where trust_statuscode='101')
		";

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
        	$criteria->addCondition ("DATE(paivays) BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('maksu_paivakirja',array('model'=>$model), true));
	          $html2pdf->Output();

		} else {

		$this->render('maksu_paivakirja',array(
			'model'=>$model,
		));

		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new LaskuHistoria('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LaskuHistoria']))
			$model->attributes=$_GET['LaskuHistoria'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return LaskuHistoria the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=LaskuHistoria::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param LaskuHistoria $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lasku-historia-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


   	protected function statusMuutos($data,$row)
	{ 
		$str = $data->status;

		// <-- Trust
		if(isset($data->palvelu) and $data->palvelu == 'trust') 
		{
		    $json = json_decode($data->status, true);
		    $str = '';
		    echo '<pre>';
		    print_r($json);
		    echo '</pre>';
		}
		// Trust -->


		// <-- Postita
		if(isset($data->palvelu) and $data->palvelu == 'postita') 
		{
		    $json = json_decode($data->status, true);
		    $json = str_replace("{","",$json);
		    $json = str_replace("}","",$json);
		    $json = explode(", ",$json);
		    $json = str_replace('"','',$json);

		    $str = '';
		    echo '<pre>';
		    print_r($json);
		    echo '</pre>';
		}
		// Postita -->

		return $str;
	}


}

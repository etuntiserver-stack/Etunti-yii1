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
				'actions'=>array('index','view','paivakirja', 'avoimet', 'maksu_paivakirja', 'paakirja', 'maksu_paakirja', 'reskontraluettelo', 'alv_raportti'),
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

	public function actionAvoimet()
	{

		$asetukset = Asetukset::model()->findbypk(1);
		$palvelu = '';
		if($asetukset->palvelu_tyyppi == 1)
		$palvelu = 'postita';
		if($asetukset->palvelu_tyyppi == 2)
		$palvelu = 'trust';
		if($asetukset->palvelu_tyyppi == 3)
		$palvelu = 'local';



       		$criteria = new CDbCriteria();
       		$criteria->order = " id DESC ";

		$pvm = date("Y-m-d");
		if(isset($_POST['from']))
		$pvm = date("Y-m-d",strtotime($_POST['from']));

        	$criteria->condition = " 
			id IN ( SELECT lid FROM lasku_historia
			WHERE  time > '".$pvm."'
			AND yht_euro > 0 AND trust_statuscode!=101
			ORDER BY id DESC)
			AND tilanne!=999			
		";


		if(isset($_POST['tulosta']))
		{
		  $model = Lasku::model()->findAll($criteria);
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('avoimet',array('model'=>$model,'palvelu'=>$palvelu), true));
	          $html2pdf->Output();

		} else {

		$dataProvider=new CActiveDataProvider('Lasku',array('criteria'=>$criteria));
		$this->render('avoimet',array(
			'dataProvider'=>$dataProvider,
			'palvelu'=>$palvelu,
			'pvm'=>$pvm
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

       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";

		if(isset($_POST['lid']) and !empty(trim($_POST['lid'])))
	        $criteria->addCondition (" lid LIKE '%".$_POST['lid']."%' ");
	
		$dataProvider=new CActiveDataProvider('LaskuHistoria', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('index', array('dataProvider' => $dataProvider));

	}


	public function actionAlv_raportti()
	{

       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";

		$from = date("Y-m-d", strtotime("first day of this month"));
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}

        	$criteria->condition = " 
			DATE(paivays) BETWEEN '".$from."' AND '".$to."'
			AND tilanne!=999
		";

		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('paivakirja',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
		  ), true));
	          $html2pdf->Output();

		} else {

		$this->render('alv_raportti',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
		));

		}
	}



	public function actionPaivakirja()
	{

       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";

		$from = date("Y-m-d", strtotime("first day of this month"));
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}

        	$criteria->condition = " 
			DATE(paivays) BETWEEN '".$from."' AND '".$to."'
			AND tilanne!=999
		";

		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('paivakirja',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
		  ), true));
	          $html2pdf->Output();

		} else {

		$this->render('paivakirja',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
		));

		}
	}



	public function actionReskontraluettelo()
	{


       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";


		$from = date("Y-m-d", strtotime("first day of this month"));
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}

        	$criteria->condition = " 
			DATE(paivays) BETWEEN '".$from."' AND '".$to."'
			AND tilanne!=999
		";

		if(isset($_POST['asiakasLaskulle']) and !empty($_POST['asiakasLaskulle']))
       		$criteria->addCondition ( " as_nro='".$_POST['asiakasLaskulle']."' " );


		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('reskontraluettelo',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to
		  ), true));
	          $html2pdf->Output();

		} elseif(isset($_POST['laheta'])){

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('reskontraluettelo',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to
		  ), true));
         	  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);


		$file = $from.'_'.$to.'_'.$_POST['asiakasLaskulle'].'.pdf';
		$path = Yii::app()->request->baseUrl."emails/reskontraluettelot/".Yii::app()->user->domain;

  		if (!file_exists($path))
		  	mkdir($path, 0777, true);

		file_put_contents($path.'/'.$file, $content_PDF);


		$message = '<br> Laskut ajalta '.$from.' - '.$to;
		$saaja = $_POST['sahkoposti'];

		$mail = new YiiMailer();
		$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		$mail->setTo($saaja);
		$mail->setSubject(Yii::t('main', 'Laskut'). ' '.$from.' - '.$to);
		$mail->setBody($message);
		$mail->setAttachment($path.'/'.$file);
		if($mail->send())
			$this->redirect(array('reskontraluettelo','mail'=>'sent'));


		} else {

		$this->render('reskontraluettelo',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
		));

		}
	}


	public function actionPaakirja()
	{

       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";

		$from = date("Y-m-d", strtotime("first day of this month"));
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}

        	$criteria->condition = "
			DATE(paivays) BETWEEN '".$from."' AND '".$to."'
			AND tilanne!=999
		";

		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('paakirja',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
		  ), true));
	          $html2pdf->Output();

		} else {

		$this->render('paakirja',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
		));

		}
	}

	public function actionMaksu_paakirja()
	{


       		$criteria = new CDbCriteria();
       		$criteria->order = " paivays DESC ";
       		$criteria->condition = "
			id IN (select lid from lasku_historia where trust_statuscode='101')
			AND tilanne!=999
		";

		$from = date("Y-m-d", strtotime("first day of this month"));
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}

        	$criteria->addCondition ("DATE(paivays) BETWEEN '".$from."' AND '".$to."' ");

		$model = Lasku::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('maksu_paakirja',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
		  ), true));
	          $html2pdf->Output();

		} else {

		$this->render('maksu_paakirja',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
		));

		}
	}

	public function actionMaksu_paivakirja()
	{

		$asetukset=Asetukset::model()->findbypk(1);

       		$criteria = new CDbCriteria();
       		$criteria->order = " id DESC ";


		// Trust Maksettu
		if($asetukset->palvelu_tyyppi == 2)
		{
       		$criteria->addCondition("
			trust_statuscode='101'
		");
		}

		// Postita tai Local Maksettu
		if($asetukset->palvelu_tyyppi == 1 or $asetukset->palvelu_tyyppi == 3)
		{
       		$criteria->addCondition("
			status='MAKSETTU'
		");
		}


		$from = date("Y-m-d", strtotime("first day of this month"));
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}

        	$criteria->addCondition ("DATE(time) BETWEEN '".$from."' AND '".$to."' ");

		$model = LaskuHistoria::model()->findAll($criteria);

		if(isset($_POST['tulosta']))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('maksu_paivakirja',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
			'asetukset'=>$asetukset
		  ), true));
	          $html2pdf->Output();

		} else {

		$this->render('maksu_paivakirja',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
			'asetukset'=>$asetukset
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

<?php

class TyovuorootController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view','updatetime','showohje','did','muisti','operatio','viikko','fromto','autoinsert','autoremove','viikkottain','laheta','kk','pvmtid','laheta_k'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','updatetime','showohje','did','muisti','operatio','viikko','fromto','autoinsert','autoremove','viikkottain','laheta','kk','pvmtid','laheta_k'),
                		'message'=>Yii::t('main', 'Tämä TASO ei kuuluu teille'),
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

		if(isset(Yii::app()->user->adminID) and in_array('2',$tas))
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

	protected function sprint($val){
	   	    if($val > 0)
		   	   return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	public function actionKk()
	{

		if(isset($_POST['ilman']))
		{
		  unset(Yii::app()->session['Lounastauko']);
		  unset(Yii::app()->session['MATKA']);

		  if(!empty($_POST['ilman']) and count($_POST['ilman']) > 0)
		  {
		    foreach($_POST['ilman'] as $val){
			if($val == 'Lounastauko')
			Yii::app()->session['Lounastauko'] = 10;

			if($val == 'MATKA')
			Yii::app()->session['MATKA'] = 2;
		    }
		  }
		}

		function sprint($val){
	   	    if($val > 0)
		   	   return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
		}

		$dataProvider=new CActiveDataProvider('Tyovuoroot');
		$this->render('kk',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionPvmTid($pvm,$tid,$from)
	{

		$this->renderPartial('pvmtid',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'from'=>$from,
		));
	}

	public function actionLaheta($tid,$week,$year,$tulosta) {

		$tt = Tyontekijat::model()->findbypk($tid);

		if(Yii::app()->request->getPost('pdf'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true));
	          $html2pdf->Output();

		} elseif(Yii::app()->request->getPost('pdf_email'))
		{

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true));
         	  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);


		/* file */
		$file = $week.'_'.$year.'_'.$tid.'.pdf';
		$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;

  		if (!file_exists($path))
		  	mkdir($path, 0777, true);

		file_put_contents($path.'/'.$file, $content_PDF);
		/* file */
		$message = Yii::t('main', 'VIIKKO').'-'.$week.'<br>'.Yii::t('main', ' Liitteenä uusi PDF-tiedosto');

		$saaja = $tt->tekijan_email;
		$firma = FirmanTiedot::model()->findbypk(1);
		if(isset($firma->sahkoposti) and !empty($firma->sahkoposti))
		$saaja = $tt->tekijan_email.', '.$firma->sahkoposti;

		$mail = new YiiMailer();
		//$mail->clearLayout();//if layout is already set in config
		$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		$mail->setTo($saaja);
		$mail->setSubject(Yii::t('main', 'TYÖVUOROT'). ' '.$tt->tekijan_nimi);
		$mail->setBody($message);
		$mail->setAttachment($path.'/'.$file);
	
		if($mail->send())
		  $this->render('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>false,'tt'=>$tt));

		} else {
		  $this->render('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>false,'tt'=>$tt));
		}


	}


	public function actionLaheta_k($week,$year,$tulosta) {



		if(Yii::app()->request->getPost('pdf'))
		{
		  $tt = Tyontekijat::model()->findbypk($_POST['kuka']);

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('laheta',array('tid'=>$_POST['kuka'],'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true));
	          $html2pdf->Output();

		} elseif(Yii::app()->request->getPost('pdf_email'))
		{

		$kenelle = explode(",",Yii::app()->request->getPost('kenelle'));

		foreach($kenelle as $key)
		{
		 if(!empty($key))
		 {
		  $tt = Tyontekijat::model()->findbypk($key);

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('laheta',array('tid'=>$tt->id,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true));
         	  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);


		  /* file */
		  $file = $week.'_'.$year.'_'.$key.'.pdf';
		  $path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;

  		  if (!file_exists($path))
		  	mkdir($path, 0777, true);

		  file_put_contents($path.'/'.$file, $content_PDF);
		  /* file */
		  $message = Yii::t('main', 'VIIKKO').'-'.$week.'<br>'.Yii::t('main', ' Liitteenä uusi PDF-tiedosto');

		
		$saaja = $tt->tekijan_email;
		$firma = FirmanTiedot::model()->findbypk(1);
		if(isset($firma->sahkoposti) and !empty($firma->sahkoposti))
		$saaja = $tt->tekijan_email.', '.$firma->sahkoposti;


		  $mail = new YiiMailer();
		  //$mail->clearLayout();//if layout is already set in config
		  $mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		  $mail->setTo($saaja);
		  $mail->setSubject(Yii::t('main', 'TYÖVUOROT'). ' '.$tt->tekijan_nimi);
		  $mail->setBody($message);
		  $mail->setAttachment($path.'/'.$file);
		  $mail->send();
		
		 }
		}

		  $this->redirect('viikkottain');

		} else {
		  $this->render('laheta_k',array('week'=>$week,'year'=>$year,'tulosta'=>false));
		}


	}
	public function actionViikkottain() {

		$this->render('viikkottain');
	}

	public function actionOperatio()
	{

		// remove
		if(isset($_POST['id']) and isset($_POST['remove']))
		{
		    $this->loadModel($_POST['id'])->delete();
		    echo $_POST['id'];
		}
		// copy
		if(isset($_POST['id']) and isset($_POST['copy']))
		{
			$t = Tyovuoroot::model()->findbypk($_POST['id']);
			$model=new Tyovuoroot;
			$model->attributes=$t->attributes;
			$model->pvm=date("d.m.Y",strtotime($_POST['newPvm']));
			$model->tid=$_POST['newTid'];
			$model->alku=$t->alku;
			$model->loppu=$t->loppu;
			$model->pituus=$t->pituus;
			$model->kohde=$t->kohde;
			$model->save();
			echo $model->id;
		}
		// cut
		if(isset($_POST['id']) and isset($_POST['cut']))
		{
			$t = Tyovuoroot::model()->findbypk($_POST['id']);

			$model=new Tyovuoroot;
			$model->attributes=$t->attributes;
			$model->pvm=date("d.m.Y",strtotime($_POST['newPvm']));
			$model->tid=$_POST['newTid'];
			$model->alku=$t->alku;
			$model->loppu=$t->loppu;
			$model->pituus=$t->pituus;
			$model->kohde=$t->kohde;
			$model->save();
			$t = Tyovuoroot::model()->deletebypk($_POST['id']);
			echo $model->id;
		}
	}



	public function actionAutoinsert()
	{
	//print_r($_POST);
	  if(isset($_POST['checktietoja']))
	  {
		$m = Kohteet::model()->findbypk($_POST['kohdeVal']);
		$k = explode("//",$m->kenella_on_avain);

		  $ohje = '';
		if(isset($k[1]))
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."\n";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."\n\n";
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet:\n".$m->toimenpiteet;
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja:\n".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut:\n".$m->muut;
		echo $ohje;

		exit;
	  }

	  if(isset($_POST['asenna']))
	  {

		$fi = array(
		    1=>'Maanantai',
		    2=>'Tiistai',
		    3=>'Keskkiviikko',
		    4=>'Torstai',
		    5=>'Perjantai',
		    6=>'Lauantai',
		    0=>'Sunnuntai',
		);

		$pvmstart 	= $_POST['pfrom'];
		$startdate 	= strtotime($_POST['pfrom']);
		$enddate	= strtotime($_POST['pto']);
		$w		= $_POST['P'];
		$v 		= $_POST['viikkoja'];

		  $i=0; 
		  $var = 0;
		  if($v == 2 or $v == 4)
		  $var = 1;


		  while($startdate<$enddate) 
		   {  


		      if(in_array(date('w',$startdate),$w) and (date('W',$startdate) %$v) == $var)
		      {

		    	$pvm = date('d.m.Y',$startdate);
		    	echo $pvm.' '.$fi[date('w',$startdate)]."\n";

			if($_POST['valmis'] == "true")
			{

				$t = new Tyovuoroot;
				$t->tid = $_POST['tekija'];
				$t->kohde = $_POST['kohde'];
				$t->pvm = $pvm;
				$t->alku = $_POST['tfrom'];
				$t->loppu = $_POST['tto'];
				//$t->tyoajanlaatu = $_POST['tyoajanlaatu'];
				$t->tyoajanmerkinta = $_POST['tyoajanmerkinta'];
				$t->tietoja = $_POST['tietoja'];
				$t->save();
		
			}

		      }

			$i++; 
			$startdate+=86400; 

		   }	
	
		exit;
	  }
	
		$this->renderPartial('autoinsert');
	}


	public function actionAutoremove()
	{
	//print_r($_POST);

	  if(isset($_POST['asenna']))
	  {

		$fi = array(
		    1=>'Maanantai',
		    2=>'Tiistai',
		    3=>'Keskkiviikko',
		    4=>'Torstai',
		    5=>'Perjantai',
		    6=>'Lauantai',
		    0=>'Sunnuntai',
		);

		$startdate 	= strtotime($_POST['pfrom']);
		$enddate	= strtotime($_POST['pto']);
		$w		= $_POST['P'];
		$v 		= $_POST['viikkoja'];

		  $i=0; 
		  while($startdate<$enddate) 
		   {  
		      if(in_array(date('w',$startdate),$w) and (date('W',$startdate) % $v) == 0)
		      {
		    	$pvm = date('d.m.Y',$startdate);
		    	echo $pvm.' '.$fi[date('w',$startdate)]."\n";

			if($_POST['valmis'] == "true")
			{

			  Tyovuoroot::model()->deleteAll(" tid='".$_POST['tekija']."' and pvm='".$pvm."' and kohde='".$_POST['kohde']."' ");

			}
		      }

			$i++; 
			$startdate+=86400; 

		   }	
	
		exit;
	  }
	
		$this->renderPartial('autoremove');
	}



	public function actionShowohje($id)
	{
		$m = Kohteet::model()->findbypk($id);
		$k = explode("//",$m->kenella_on_avain);

		  $ohje = '';
		if(isset($k[1]))
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."\n";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."\n\n";
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet:\n".$m->toimenpiteet;
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja:\n".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut:\n".$m->muut;
		echo $ohje;
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionDid($pvm,$tid,$from)
	{

		$this->renderPartial('did',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'from'=>$from,
		));
	}

	public function actionViikko($tid,$viikko)
	{

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		$this->renderPartial('viikko',array(
			'tid'=>$tid,
			'viikko'=>$viikko,
		));
	}

	public function actionFromto($tid)
	{

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		$this->renderPartial('fromto',array(
			'tid'=>$tid,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

  	$tnimi = '';
	if(isset($_POST['tid'])){
  	  $tekija = Tyontekijat::model()->findbypk($_POST['tid']);
	  $tnimi = $tekija->tekijan_nimi;
	}
	?>
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Työvuoroon suunnittelu').' '.$tnimi; ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">
	<?php

		$model=new Tyovuoroot;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyovuoroot']))
		{
			$model->attributes=$_POST['Tyovuoroot'];
			$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
			if($model->save())
			{

			  if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
			  {
			   $t = Tyontekijat::model()->findbypk($model->tid);
			   $k = Kohteet::model()->findbypk($model->kohde);
			   if(isset($k->osoite) and !empty($k->osoite))
			   {
				$pushviesti = "Uusi työvuoro\n
					".$model->pvm."
					".$model->alku."-".$model->loppu." ".$k->osoite."
					".$model->tietoja;

				Domainit::PushNotify($t->id,"Hei ".$t->tekijan_nimi,$pushviesti);

			   }
			  }
			}
		exit;
		}

		$this->renderPartial('create',array(
			'model'=>$model,
		));
	?>
	</div>
	</div> <!-- end modal-body -->
	<?php
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)

	{

		$model=$this->loadModel($id);
	  	$t = Tyontekijat::model()->findbypk($model->tid);

	?>
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Työvuoroon suunnittelu').' '.$t->tekijan_nimi; ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">
	<?php



		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyovuoroot']))
		{

			$_POST['Tyovuoroot']['pvm'] = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
			$model->attributes=$_POST['Tyovuoroot'];
			if($model->save()){

			  if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
			  {
			   $k = Kohteet::model()->findbypk($model->kohde);
			   if(isset($k->osoite) and !empty($k->osoite))
			   {
				$pushviesti = "Työvuorossa on muutokset\n
					".$model->pvm."
					".$model->alku."-".$model->loppu." ".$k->osoite."
					".$model->tietoja;

				Domainit::PushNotify($t->id,"Hei ".$t->tekijan_nimi,$pushviesti);
			   }
			  }
			}
		}

		$this->renderPartial('update',array(
			'model'=>$model,
		));
	?>
	</div>
	</div> <!-- end modal-body -->
	<?php
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

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		$this->render('index');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tyovuoroot('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyovuoroot']))
			$model->attributes=$_GET['Tyovuoroot'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tyovuoroot the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tyovuoroot::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tyovuoroot $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyovuoroot-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

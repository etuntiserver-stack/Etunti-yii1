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
				'actions'=>array('admin','delete','create','update','index','view','updatetime','showohje','did','muisti','operatio','viikko','fromto','autoinsert','autoremove','viikkottain', 'viikkottain_pdf', 'laheta','kk','pvmtid','laheta_k', 'muistin', 'muisticlear', 'muistissa', 'vkolopput', 'vkolopchange', 'uusitilaus', 'tv2', 'PoistaTv', 'valitse_kokopaiva', 'tv_kohteet', 'siivous_tyonimike', 'getKohdeByAsiakas'),
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

	protected function sprint($val){
	   	    if($val > 0)
		   	   return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	public function actionGetKohdeByAsiakas($id)
	{
		$model = Kohteet::model()->findAll(" asiakas_id='".$id."' ");
			$bd = '';
			$bd .= '<option></option>';
			foreach($model as $k)
			$bd .= '<option value="'.$k->id.'">'.$k->osoite.'</option>';


		echo json_encode($bd);	
	}


	public function actionSiivous_tyonimike()
	{
		if(isset($_POST['siivousTyonimike']))
		{
			$siivousTyonimike = $_POST['siivousTyonimike'];
			$fromTV = date("Y-m-d", strtotime($_POST['fromTV']));
			$toTV = date("Y-m-d", strtotime($_POST['toTV']));
			$tekijanToimialue = $_POST['tekijanToimialue'];

			$criteria=new CDbCriteria;

			if(!empty($siivousTyonimike))
			{
			$criteria->addCondition (" 
				id IN(
				   SELECT tid FROM sivex_tvuoro
				   WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')
				   BETWEEN '".$fromTV."' AND '".$toTV."'
					AND kohde IN(
					   SELECT id FROM sivex_kohdet
					   WHERE siivous LIKE '%".$siivousTyonimike."%'
					)
				)
			");
			}

			if(!empty($tekijanToimialue))
			{
			$criteria->addCondition("
				tyo_toimialue='".$tekijanToimialue."' 
			");
			}

			$tv = Tyontekijat::model()->findAll($criteria);
			$t = array();
			foreach($tv as $data)
			$t[] = $data->id;

			echo json_encode($t);

		}
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
		if(isset($_POST['kirjenBody']) and !empty($_POST['kirjenBody']))
		$message .= '<br>'.str_replace("\n", "<br>", $_POST['kirjenBody']);
		

		$saaja = $tt->tekijan_email;
		$firma = FirmanTiedot::model()->findbypk(1);
		if(isset($firma->sahkoposti) and !empty($firma->sahkoposti))
		$saaja = array($tt->tekijan_email,$firma->sahkoposti);

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


		// file 
		$file = $week.'_'.$year.'_'.$key.'.pdf';
		$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;

  		if (!file_exists($path))
		 	mkdir($path, 0777, true);

		file_put_contents($path.'/'.$file, $content_PDF);
		// file 
		$message = Yii::t('main', 'VIIKKO').'-'.$week.'<br>'.Yii::t('main', ' Liitteenä uusi PDF-tiedosto');
		if(isset($_POST['kirjenBody']) and !empty($_POST['kirjenBody']))
		$message .= '<br>'.str_replace("\n", "<br>", $_POST['kirjenBody']);
		

		  $mail = new YiiMailer();
		  //$mail->clearLayout();//if layout is already set in config
		  $mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		  $mail->setTo($tt->tekijan_email);
		  $mail->setSubject(Yii::t('main', 'TYÖVUOROT'). ' '.$tt->tekijan_nimi);
		  $mail->setBody($message);
		  $mail->setAttachment($path.'/'.$file);
		  $mail->send();
		
		 }
		}





		// firmalle kaikki
		$firma = FirmanTiedot::model()->findbypk(1);
		if(isset($firma->sahkoposti) and !empty($firma->sahkoposti))
		{
		$saaja = $firma->sahkoposti;



	        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		$html2pdf->setDefaultFont('Arial');
	        $html2pdf->WriteHTML($this->renderPartial('laheta_k',array('week'=>$week,'year'=>$year,'tulosta'=>'lista'),true));
         	$content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);

		// file 
		$file = $week.'_'.$year.'_'.$key.'_toimisto.pdf';
		$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;

  		if (!file_exists($path))
		 	mkdir($path, 0777, true);

		file_put_contents($path.'/'.$file, $content_PDF);


		$message = Yii::t('main', 'VIIKKO').'-'.$week.'<br>'.Yii::t('main', ' Liitteenä uusi PDF-tiedosto');
		if(isset($_POST['kirjenBody']) and !empty($_POST['kirjenBody']))
		$message .= '<br>'.str_replace("\n", "<br>", $_POST['kirjenBody']);


		  $mail = new YiiMailer();
		  //$mail->clearLayout();//if layout is already set in config
		  $mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		  $mail->setTo($saaja);
		  $mail->setSubject(Yii::t('main', 'TYÖVUOROT ').$week.'-'.$year);
		  $mail->setBody($message);
		  $mail->setAttachment($path.'/'.$file);
		  $mail->send();
		}
		//






		  $this->redirect('viikkottain');

		} else {
		  $this->render('laheta_k',array('week'=>$week,'year'=>$year,'tulosta'=>false));
		}


	}
	public function actionViikkottain() {

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('viikkottain_pdf',array('no'=>'ei mitaan'),true));
	          $html2pdf->Output();
		} else {
		  $this->render('viikkottain');
		}
	}


	public function actionVkolopput()
	{
		if(isset($_SESSION['vkolopput']))
		   echo 1;
		else
		   echo 0;
		exit;
	}

	public function actionVkolopchange()
	{
		if(isset($_POST['nyt']) and $_POST['nyt'] == 0)
		   $_SESSION['vkolopput'] = true;
		elseif(isset($_POST['nyt']) and $_POST['nyt'] == 1)
		   unset($_SESSION['vkolopput']);
		exit;
	}

	public function actionValitse_kokopaiva()
	{
		if(isset($_POST['pvm']) and isset($_POST['tid']))
		{

			$criteria=new CDbCriteria;
			$criteria->condition = " 
				pvm='".date("d.m.Y", strtotime($_POST['pvm']))."' 
				AND tid='".$_POST['tid']."'
			";
			$tv = Tyovuoroot::model()->findAll($criteria);
			$for = '';
			if(isset($tv[0]))
			{
			  foreach($tv as $data)
			  {
				$id = $data->id."_".date("Ymd", strtotime($data->pvm))."_".$data->tid;
				$for = date("Ymd", strtotime($data->pvm))."_".$data->tid;
				$_SESSION['muistin'][$id] = $id;
				//print_r($_SESSION['muistin']);
			  }
			}
				echo $for;
		}

	}

	public function actionMuistin()
	{
		if(isset($_POST['id']))
		{
			$_SESSION['muistin'][$_POST['id']] = $_POST['id'];
			print_r($_SESSION['muistin']);
			
		}

	}

	public function actionMuistissa()
	{
		if(isset($_SESSION['muistin']))
		   echo implode(",",$_SESSION['muistin']);
		else
		   echo 'muistityhja';
	}

	public function actionMuisticlear()
	{
		if(isset($_POST['clear']))
		unset($_SESSION['muistin']);

	}

	public function actionPoistaTv()
	{
		if(isset($_POST['poistaTv']))
			$t = Tyovuoroot::model()->deletebypk($_POST['poistaTv']);
	}

	public function actionOperatio()
	{

		if(isset($_POST['checkThis']))
		{
			$did = $this->renderPartial('did',array('pvm'=>$_POST['newPvm'],'tid'=>$_POST['newTid'],'from'=>'ajax'), true);
			echo json_encode($did.'//');
			exit;
		}

		// remove
		if(isset($_POST['remove']) and isset($_SESSION['muistin']))
		{
		foreach($_SESSION['muistin'] as $cp)
		{
			$ex = explode("_",$cp);
			$t = Tyovuoroot::model()->deletebypk($ex[0]);
		}

			echo json_encode('//'.implode(",",$_SESSION['muistin']));
			exit;
		}

		// copy
		if(isset($_POST['copy']) and isset($_SESSION['muistin']))
		{

		foreach($_SESSION['muistin'] as $cp)
		{
			$ex = explode("_",$cp);
			$t = Tyovuoroot::model()->findbypk($ex[0]);
			$model=new Tyovuoroot;
			$model->attributes=$t->attributes;
			$model->pvm=date("d.m.Y",strtotime($_POST['newPvm']));
			$model->tid=$_POST['newTid'];
			$model->alku=$t->alku;
			$model->loppu=$t->loppu;
			$model->pituus=$t->pituus;
			$model->kohde=$t->kohde;
			$model->save();
			
		}

			$did = $this->renderPartial('did',array('pvm'=>$_POST['newPvm'],'tid'=>$_POST['newTid'],'from'=>'ajax'), true);
			echo json_encode($did.'//');
			exit;

		}
		// cut
		if(isset($_POST['cut']) and isset($_SESSION['muistin']))
		{
		foreach($_SESSION['muistin'] as $cp)
		{
			$ex = explode("_",$cp);
			$t = Tyovuoroot::model()->findbypk($ex[0]);
			if(isset($t->id))
			{
			$model=new Tyovuoroot;
			$model->attributes=$t->attributes;
			$model->pvm=date("d.m.Y",strtotime($_POST['newPvm']));
			$model->tid=$_POST['newTid'];
			$model->alku=$t->alku;
			$model->loppu=$t->loppu;
			$model->pituus=$t->pituus;
			$model->kohde=$t->kohde;
			$model->save();
			$t = Tyovuoroot::model()->deletebypk($ex[0]);	
			} else {
			echo json_encode('id puuttuu');
			break;
			}		
		}


			$did = $this->renderPartial('did',array('pvm'=>$_POST['newPvm'],'tid'=>$_POST['newTid'],'from'=>'ajax'), true);
			echo json_encode($did.'//'.implode(",",$_SESSION['muistin']));
			exit;
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
		if(!empty($m->aikataulu))
		  $ohje .= "\nAikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet: ".$m->toimenpiteet;
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja: ".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut: ".$m->muut;
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
		  if($v == 2 and date('W',$startdate)%2 == 1)
		  $var = 1;
		  elseif($v == 4 and date('W',$startdate)%2 == 0)
		  $var = 2;
		  elseif($v == 4 and date('W',$startdate)%2 == 1)
		  $var = 1;
		  elseif($v == 3 and date('W',$startdate)%3 == 1)
		  $var = 1;
		  elseif($v == 3 and date('W',$startdate)%2 == 1)
		  $var = 2;

		  while($startdate<$enddate) 
		   {  

		      $ero = (date('W',$startdate) %$v);
		      //echo date('d.m',$startdate).", ".date('W',$startdate)." | ".$var." | ".$ero."\n";


		      if(in_array(date('w',$startdate),$w) and $ero == $var)
		      {

		    	$pvm = date('d.m.Y',$startdate);
		    	echo $pvm." ".$fi[date('w',$startdate)]."\n";

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
		  $var = 0;
		  if($v == 2 and date('W',$startdate)%2 == 1)
		  $var = 1;
		  elseif($v == 4 and date('W',$startdate)%2 == 0)
		  $var = 2;
		  elseif($v == 4 and date('W',$startdate)%2 == 1)
		  $var = 1;
		  elseif($v == 3 and date('W',$startdate)%3 == 1)
		  $var = 1;
		  elseif($v == 3 and date('W',$startdate)%2 == 1)
		  $var = 2;

		  while($startdate<$enddate) 
		   {  
		      $ero = (date('W',$startdate) %$v);
		      //echo date('d.m',$startdate).", ".date('W',$startdate)." | ".$var." | ".$ero."\n";


		      if(in_array(date('w',$startdate),$w) and $ero == $var)
		      {
		    	$pvm = date('d.m.Y',$startdate);
		    	echo $pvm.' '.$fi[date('w',$startdate)]."\n";

			if($_POST['valmis'] == "true")
			{

			  Tyovuoroot::model()->deleteAll(" tid='".$_POST['tekija']."' and pvm='".$pvm."' and kohde='".$_POST['kohde']."' and alku='".$_POST['tfrom']."' and loppu='".$_POST['tto']."' ");

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
		if(!empty($m->aikataulu))
		  $ohje .= "\nAikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet: ".$m->toimenpiteet;
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja: ".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut: ".$m->muut;
		echo json_encode($ohje."//".$m->tietoja);
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionDid($pvm,$tid,$from)
	{
		if(isset($tietoja)) $tietoja = 1; else $tietoja = 0;
		$this->renderPartial('did',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'from'=>$from,
			'tietoja'=>$tietoja,
		));
	}

	public function actionViikko($tid,$viikko,$year)
	{

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		$this->renderPartial('viikko',array(
			'tid'=>$tid,
			'viikko'=>$viikko,
			'year'=>$year,
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
	if(isset($_POST['tid']) and $_POST['tid'] != 0){
  	  $tekija = Tyontekijat::model()->findbypk($_POST['tid']);
	  $tnimi = $tekija->tekijan_nimi;


		// Tyosuhde oikeus
		$oikeus = '<div class="alert alert-danger">'.Yii::t('main', 'Työsuhdetta ei ole määritelty tai työsuhde ei ole voimassa.').'</div>';
		$pvm = date("Ymd", strtotime($_POST['pvm']));
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			tid='".$tekija->id."' 
		";
		$ts = Tyosuhdet::model()->find($criteria);
		if(isset($ts->id) and !empty($ts->alku))
		{
			$alku = date("Ymd", strtotime($ts->alku));

			if($pvm >= $alku and empty($ts->loppu))
			$oikeus = '';
			elseif($pvm >= $alku and !empty($ts->loppu) and $pvm <= date("Ymd", strtotime($ts->loppu)))
			$oikeus = '';
		}
		// Tyosuhde oikeus

	}
	?>
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Työvuoron suunnittelu').': '.$tnimi; ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">
	<?php
		if(isset($oikeus)) echo $oikeus;

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


	public function actionUusitilaus()
	{

	if(!isset($_POST['Tyovuoroot']))
	{
	?>
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Uusi tilaus'); ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">
	<?php
	}

		$model=new Tyovuoroot;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyovuoroot']))
		{


		$asiakkaat = new Asiakkaat;
		$asiakkaat->tyyppi = 'henkilo';
		$asiakkaat->yhteyshenkilo = $_POST['yhteyshenkilo'];
		$asiakkaat->osoite = $_POST['osoite'];
		$asiakkaat->puhelin = $_POST['puhelin'];
		$asiakkaat->sahkoposti = $_POST['sahkoposti'];
		$asiakkaat->aktiivinen = 1;

		  if($asiakkaat->save())
		  {
			$kohteet = new Kohteet;
			$kohteet->asiakas_id = $asiakkaat->id;
			$kohteet->etu_suku_nimet = $asiakkaat->yhteyshenkilo;
			$kohteet->osoite = $asiakkaat->osoite;
			$kohteet->puh_nro = $asiakkaat->puhelin;
			$kohteet->email = $asiakkaat->sahkoposti;
			$kohteet->muut = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']))."\n".date("H:i",strtotime($_POST['Tyovuoroot']['alku']))."-".date("H:i",strtotime($_POST['Tyovuoroot']['loppu']))."\nHinta: ".$_POST['hinta'];

		  	   if($kohteet->save())
		  	   {
				$model->attributes=$_POST['Tyovuoroot'];
				$model->kohde = $kohteet->id;
				$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				if($model->save())
				{

				   if(isset($_POST['vieposti']))
				   {
					$message = '
					Asiakas: '.$asiakkaat->yhteyshenkilo.'<br>
					Työvuorot:  '.$model->pvm.', '.$model->alku.'-'.$model->loppu.'<br>
					Hinta: '.$_POST['hinta'].'<br>
					<h2>Kiitos tilauksesta.</h2>
					';
					$mail = new YiiMailer();
					//$mail->clearLayout();//if layout is already set in config
					$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
					$mail->setTo($_POST['sahkoposti']);
					$mail->setSubject(Yii::t('main', 'Kiitos tilausta'));
					$mail->setBody($message);
					$mail->send();
				   }
				
				   echo $_POST['Tyovuoroot']['pvm'].'//'.date("Ymd",strtotime($model->pvm)).'//'.$model->tid;

				}
			   }


		  }


		exit;
		}

	if(!isset($_POST['Tyovuoroot']))
	{
		$this->renderPartial('uusitilaus',array(
			'model'=>$model,
		));
	?>
	</div>
	</div> <!-- end modal-body -->
	<?php
	}
	}



	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)

	{

			$tekijan_nimi = '';

		$model=$this->loadModel($id);
	  	$t = Tyontekijat::model()->findbypk($model->tid);
		if(isset($t->id))
		{
			$tekijan_nimi = $t->tekijan_nimi;
		}

	?>
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Työvuoron suunnittelu').': '.$tekijan_nimi; ?></h2>
	
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

		$this->renderPartial('_form',array(
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
		$this->poistaminenOnlineVarauksetJokaMeniOhi();
		$this->render('index');
	}

	public function actionTv2()
	{
		$this->poistaminenOnlineVarauksetJokaMeniOhi();
		$this->render('tv2');
	}

	public function actionTv_kohteet()
	{
		$this->poistaminenOnlineVarauksetJokaMeniOhi();
		$this->render('tv_kohteet');
	}

	protected function poistaminenOnlineVarauksetJokaMeniOhi()
	{

		// <-- Poistaminen
		$criteria=new CDbCriteria;
		$criteria->order= " id DESC "; 
		$criteria->condition= " 
			(time + INTERVAL 1 DAY) < NOW()
			AND osoiteOnline=1
		";
		$poistaminen = Tyovuoroot::model()->deleteAll($criteria);
		// Poistaminen -->

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

	protected function pyhat($date){

	$dateMonth = '';
	$pyh = array();

	$dateMonth = date("d.m.Y",strtotime($date));
	$asetukset = Asetukset::model()->findbypk(1);
	$pyh = explode("\n",$asetukset->pyhapaivat);

	if(
	   date("N",strtotime($date)) == 6 
	   or date("N",strtotime($date)) == 7
	   or strstr($asetukset->pyhapaivat, $dateMonth)
	)
	return true;
	else
	return false;

 	}
}

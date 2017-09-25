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
				'actions'=>array('admin','delete','create','update','index','view','updatetime','showohje','did','muisti','operatio','viikko','fromto','autoinsert','autoremove','viikkottain', 'viikkottain_pdf', 'laheta','kk','pvmtid','laheta_k', 'muistin', 'muisticlear', 'muistissa', 'vkolopput', 'vkolopchange', 'uusitilaus', 'tv2', 'PoistaTv', 'valitse_kokopaiva', 'tv_kohteet', 'siivous_tyonimike', 'getKohdeByAsiakas', 'getKohdeById', 'getAsiakasByKohde', 'paivita_laatikot', 'poista_toistuva', 'onko_sama', 'asiakas_autocomplete', 'kohde_autocomplete', 'check_paallekkain', 'get_tekijantiedot', 'is_asiakas', 'is_yhteyshenkilo', 'hallinta', 'didnew'),
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

		if(!isset(Yii::app()->user->adminID))
		{
			//die('login error');
		  	echo '<script type="text/javascript">
				window.location.href=location.protocol + "//" + location.host + "/index.php/site/index";
			</script>';
			exit;
		}

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


	public function actionIs_yhteyshenkilo()
	{
		$bd = '';
		if(Yii::app()->request->getPost('yhteyshenkilo'))
		{
			$model = Asiakkaat::model()->find(" yhteyshenkilo LIKE '%".Yii::app()->request->getPost('yhteyshenkilo')."' ");
			if(isset($model->id))
			$bd = Yii::t('main', 'Asiakas on olemassa');	
		}
		echo json_encode($bd);
		exit;
	}

	public function actionIs_asiakas()
	{
		$bd = '';
		if(Yii::app()->request->getPost('yrityksen_nimi'))
		{
			$model = Asiakkaat::model()->find(" yrityksen_nimi LIKE '%".Yii::app()->request->getPost('yrityksen_nimi')."' ");
			if(isset($model->id))
			$bd .= Yii::t('main', 'Asiakas on olemassa');	
		}
		echo json_encode($bd);
		exit;
	}

	public function actionGetKohdeByAsiakas($id)
	{
		$model = Kohteet::model()->findAll(" asiakas_id='".$id."' ");
			$bd = '';
			$bd .= '<option value>'.Yii::t('main', 'Valitse kohde').'</option>';
			foreach($model as $k)
			$bd .= '<option value="'.$k->id.'">'.$k->osoite.'</option>';


		echo json_encode($bd);	
	}

	public function actionGetKohdeById($id)
	{
		$model = Kohteet::model()->findByPk($id);
			$bd = '';
			$bd .= '<option value>'.Yii::t('main', 'Valitse kohde').'</option>';
			$bd .= '<option value="'.$model->id.'">'.$model->osoite.'</option>';


		echo json_encode($bd);	
	}

	public function actionGet_tekijantiedot($id)
	{

		$model = Tyontekijat::model()->findByPk($id);

		$bd = '';
		$bd .= '<div class="section">';
		$bd .= Yii::t('main', 'Nimi').': <b>'.$this->etuSukunimi($model->id).'</b><br>';
		$bd .= Yii::t('main', 'Puhelinnumero').': <b>'.$model->laiten_puh.' '.$model->tekijan_puh.'</b><br>';
		$bd .= Yii::t('main', 'Sähköpostiosoite').': <b>'.$model->tekijan_email.'</b><br>';
		$bd .= Yii::t('main', 'Kotiosoite').': <b>'.$model->tekijan_katuosoite.'</b><br>';
		$bd .= '</div>';

		echo json_encode(array('bd'=>$bd, 'etusuku' => $this->etuSukunimi($model->id)));	
	}


	public function actionOnko_sama($id, $pvm, $tid, $kohde, $alku, $loppu)
	{
		$return = array();
		$return = $this->onko_sama($id, $pvm, $tid, $kohde, $alku, $loppu);
		echo json_encode($return);
	}


	public function onko_sama($id, $pvm, $tid, $kohde, $alku, $loppu)
	{
		$return = array();

			$criteria=new CDbCriteria;
			$criteria->condition="
				tid='".$tid."'
				AND pvm='".date("d.m.Y", strtotime($pvm))."'
				AND kohde='".$kohde."'
				AND alku='".$alku."'
				AND loppu='".$loppu."'
			";
			if(!empty($id))
			$criteria->addCondition(" id!='".$id."' ");

			$model = Tyovuoroot::model()->findAll($criteria);
			foreach($model as $data)
			{
				$osoite = '';
				$k = Kohteet::model()->findbypk($data->kohde);
				if(isset($k->id))
				$osoite = $k->osoite;

				$tt = '';
				$k = Tyontekijat::model()->findbypk($data->tid);
				if(isset($k->id))
				$tt = $k->tekijan_nimi;

				$return[] = array(
					'id'=>$data->id,
					'pvm'=>$data->pvm,
					'alku'=>$data->alku,
					'loppu'=>$data->loppu,
					'tekijan_nimi'=>$tt,
					'osoite'=>$osoite
				);
			}
			

		return $return;
	}


	public function actionCheck_paallekkain()
	{
		$count	= 0;
		$tid	= $_POST['tid'];
		$pvm	= $_POST['pvm'];
		$alku	= date("Y-m-d H:i:s", strtotime($_POST['alku']));
		$loppu	= date("Y-m-d H:i:s", strtotime($_POST['loppu']));
		

		$criteria=new CDbCriteria;
		$criteria->condition="
			tid='".$tid."' AND pvm='".date("d.m.Y", strtotime($pvm))."'
			AND (
				DATE_FORMAT(STR_TO_DATE(alku, '%H:%i'), '%Y-%m-%d %H:%i:%s') BETWEEN '".$alku."' AND '".$loppu."'
				OR DATE_FORMAT(STR_TO_DATE(loppu, '%H:%i'), '%Y-%m-%d %H:%i:%s') BETWEEN '".$alku."' AND '".$loppu."'
			)
		";
		$model = Tyovuoroot::model()->findAll($criteria);
		$count = count($model);
		echo json_encode($count);
	}


	public function actionGetAsiakasByKohde($id)
	{
		$k = Kohteet::model()->findbypk($id);
		 if(isset($k->asiakas_id))
		  $a = Asiakkaat::model()->findbypk($k->asiakas_id);
		   if(isset($a->id))
		   {
			if(!empty($a->yrityksen_nimi))
				$asiakas = $a->yrityksen_nimi;
			else if(!empty($a->yhteyshenkilo) and empty($a->yrityksen_nimi))
				$asiakas = $a->yhteyshenkilo;
			else
				$asiakas = $a->osoite;

		    	echo json_encode($asiakas);
		   }
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
				tyo_toimialue LIKE '%".$tekijanToimialue."%' 
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
		  $html2pdf->setTestTdInOnePage(false);
	          $html2pdf->WriteHTML($this->renderPartial('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true));
	          $html2pdf->Output();

		} elseif(Yii::app()->request->getPost('pdf_email'))
		{


	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
		  $html2pdf->setTestTdInOnePage(false);
		  $thisHtml = $this->renderPartial('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true);
	          $html2pdf->WriteHTML($thisHtml);
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
		$message .= '<br><div style="font-size: 120%">'.str_replace("\n", "<br>", $_POST['kirjenBody']).'</div>';
		

		$saaja = $tt->tekijan_email;
		$ft = FirmanTiedot::model()->findbypk(1);
		if(isset($ft->sahkoposti) and !empty($ft->sahkoposti))
		$saaja = array($tt->tekijan_email,$ft->sahkoposti);
	
		$subject = Yii::t('main', 'TYÖVUOROT'). ' '.$tt->tekijan_nimi;

		$mail = new YiiMailer();
		//$mail->clearLayout();//if layout is already set in config
		$mail->setFrom('no-reply@etunti.fi');
		$mail->setTo($saaja);
		$mail->setSubject($subject);
		$mail->setBody($message);
		$mail->setAttachment($path.'/'.$file);

			if($mail->send()){


							if(is_array($saaja)) $saaja = implode(",",$saaja); 
 
							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $saaja;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$file;
							$log->email_attachment_sisalto	= json_encode($thisHtml);
							$log->log_nimike	= 'tyovuoro_lahetys';
							$log->save();
							//     LOG -->

	

			  $this->render('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>false,'tt'=>$tt));
			} else {
			  echo 'Send error';
			}



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
		  $html2pdf->setTestTdInOnePage(false);
	          $html2pdf->WriteHTML($this->renderPartial('laheta',array('tid'=>$_POST['kuka'],'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true));
	          $html2pdf->Output();

		} elseif(Yii::app()->request->getPost('pdf_email'))
		{

		$kenelle = json_decode(Yii::app()->request->getPost('kenelle'));
		$kenelle = array_filter($kenelle);

		// <-- Update piilota_mobiilista nollaksi
		$tids = "(tid='".implode("' OR tid='", $kenelle)."')";
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			YEARWEEK(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='".$_POST['year'].$_POST['week']."' 
			AND $tids
		";

		if(isset($_POST['P']))
		$criteria->Addcondition ( " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%w') IN (".implode(",",$_POST['P']).") ");

		Tyovuoroot::model()->updateAll(array('piilota_mobiilista'=>0), $criteria);
		//    Update piilota_mobiilista nollaksi -->


  		if(!isset($_POST['P'])) {
		    echo 'Days error';
		    exit;
		}




		foreach($kenelle as $key)
		{
		 if(!empty($key))
		 {
		$tt = Tyontekijat::model()->findbypk($key);

	        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->setTestTdInOnePage(false);
		$thisHtml = $this->renderPartial('laheta',array('tid'=>$tt->id,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true);
	        $html2pdf->WriteHTML($thisHtml);
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
		
		$subject = Yii::t('main', 'TYÖVUOROT'). ' '.$tt->tekijan_nimi;

		  $ft = FirmanTiedot::model()->findbypk(1);
		  $mail = new YiiMailer();
		  //$mail->clearLayout();//if layout is already set in config
		  $mail->setFrom('no-reply@etunti.fi');
		  $mail->setTo($tt->tekijan_email);
		  $mail->setSubject($subject);
		  $mail->setBody($message);
		  $mail->setAttachment($path.'/'.$file);
		  	if($mail->send()){

 
							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $tt->tekijan_email;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$file;
							$log->email_attachment_sisalto	= json_encode($thisHtml);
							$log->log_nimike	= 'tyovuoro_lahetys';
							$log->save();
							//     LOG -->
			}

		 }
		}





		// firmalle kaikki
		$ft = FirmanTiedot::model()->findbypk(1);
		if(isset($ft->sahkoposti) and !empty($ft->sahkoposti))
		{
		$saaja = $ft->sahkoposti;



	        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->setTestTdInOnePage(false);
		$thisHtml = $this->renderPartial('laheta_k',array('week'=>$week,'year'=>$year,'tulosta'=>'lista'),true);
	        $html2pdf->WriteHTML($thisHtml);
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

		$subject = Yii::t('main', 'TYÖVUOROT ').$week.'-'.$year;

		  $mail = new YiiMailer();
		  //$mail->clearLayout();//if layout is already set in config
		  $mail->setFrom('no-reply@etunti.fi');
		  $mail->setTo($saaja);
		  $mail->setSubject($subject);
		  $mail->setBody($message);
		  $mail->setAttachment($path.'/'.$file);
		  	if($mail->send()){

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $saaja;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$file;
							$log->email_attachment_sisalto	= json_encode($thisHtml);
							$log->log_nimike	= 'tyovuoro_lahetys';
							$log->save();
							//     LOG -->
			}

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

		$model = Tyovuoroot::model()->findbypk($_POST['poistaTv']);
		$return = array();

		if(	isset($_POST['toistuva_aktiivinen']) 
			and $_POST['toistuva_aktiivinen'] == 'true'
			and isset($model->toistuva_id)
			and $model->toistuva_id != 0
			and isset($_POST['pfrom']) and !empty($_POST['pfrom'])
			and isset($_POST['pto']) and !empty($_POST['pto'])
		)
		{

			$poistoCriteria = new CDbCriteria;
			$poistoCriteria->condition = " 
				toistuva_id='".$model->toistuva_id."' 
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN 
				'".date("Y-m-d", strtotime($_POST['pfrom']))."'
					AND '".date("Y-m-d", strtotime($_POST['pto']))."'
			";
			$m = Tyovuoroot::model()->findAll($poistoCriteria);

			$toistuva = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id);
			$edelliset_tvuoro_arr = json_decode($toistuva->tvuoro_ids, true);

			$edelliset_tvuoro_ids = array();
			if(is_array($edelliset_tvuoro_arr))
			$edelliset_tvuoro_ids = $edelliset_tvuoro_arr;

			
			foreach($m as $model)
			{
				$ketjustaPois = $model->id;
				$edelliset_tvuoro_ids = array_values( array_diff($edelliset_tvuoro_ids, array($ketjustaPois)) );

				$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			}

			Tyovuoroot::model()->deleteAll($poistoCriteria);

			if( is_array($edelliset_tvuoro_ids) and count($edelliset_tvuoro_ids) > 0 )
			{
				ToistuvatTyovuorot::model()->updateByPk($model->toistuva_id, array(
					'tvuoro_ids'=>json_encode($edelliset_tvuoro_ids)
				));
			} elseif( is_array($edelliset_tvuoro_ids) and count($edelliset_tvuoro_ids) == 0 )
			{
				ToistuvatTyovuorot::model()->findByPk($model->toistuva_id)->delete();
			}

			echo json_encode($return);
			exit;
		}


		if(	isset($_POST['toistuva_aktiivinen']) 
			and $_POST['toistuva_aktiivinen'] != 'true'
		)
		{
			if(isset($model->id))
			{
				$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			}
			Tyovuoroot::model()->deletebypk($model->id);
			echo json_encode($return);
			exit;
		}
	}

	public function actionOperatio()
	{

		$asetukset = Asetukset::model()->findByPk(1);

		if(isset($_POST['checkThis']))
		{
			$did = $this->renderPartial('did',array(
				'pvm'=>$_POST['newPvm'],
				'tid'=>$_POST['newTid'],
				'from'=>'ajax',
				'asetukset'=>$asetukset
			), true);
			echo json_encode($did.'//');
			exit;
		}

		// remove
		if(isset($_POST['remove']) and isset($_SESSION['muistin']))
		{
		foreach($_SESSION['muistin'] as $cp)
		{
			$ex = explode("_",$cp);

			$t = Tyovuoroot::model()->findbypk($ex[0]);
			// <-- Jos se oli toistuvassa, poistetaan sen työvuoro ID
			if( $t->toistuva_id != 0)
			{
				$toistuva = ToistuvatTyovuorot::model()->findbypk($t->toistuva_id);
				if(isset($toistuva->id) and !empty($toistuva->tvuoro_ids))
				{
					$tvuoro_ids = json_decode($toistuva->tvuoro_ids, true);
					if(is_array($tvuoro_ids))
					{
						$ketjustaPois = $t->id;
						$result = array_values( array_diff($tvuoro_ids, array($ketjustaPois)) );
						ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tvuoro_ids'=>json_encode($result)));
					}
				}
			}
			//     Jos se oli toistuvassa, poistetaan sen työvuoro ID -->

			Tyovuoroot::model()->deletebypk($ex[0]);
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
			$model->toistuva_id=0;
			$model->tyopaari='';
			$model->save();
			
		}

			$did = $this->renderPartial('did',array(
					'pvm'=>$_POST['newPvm'],
					'tid'=>$_POST['newTid'],
					'from'=>'ajax',
					'asetukset'=>$asetukset
			), true);
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
	
			// <-- Jos se oli toistuvassa, poistetaan sen työvuoro ID
			if( $t->toistuva_id != 0)
			{
				$toistuva = ToistuvatTyovuorot::model()->findbypk($t->toistuva_id);
				if(isset($toistuva->id) and !empty($toistuva->tvuoro_ids))
				{
					$tvuoro_ids = json_decode($toistuva->tvuoro_ids, true);
					if(is_array($tvuoro_ids))
					{
						$ketjustaPois = $t->id;
						$result = array_values( array_diff($tvuoro_ids, array($ketjustaPois)) );
						ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tvuoro_ids'=>json_encode($result)));
					}
				}
			}
			//     Jos se oli toistuvassa, poistetaan sen työvuoro ID -->

			$model=new Tyovuoroot;
			$model->attributes=$t->attributes;
			$model->pvm=date("d.m.Y",strtotime($_POST['newPvm']));
			$model->tid=$_POST['newTid'];
			$model->alku=$t->alku;
			$model->loppu=$t->loppu;
			$model->pituus=$t->pituus;
			$model->kohde=$t->kohde;
			$model->toistuva_id=0;
			$model->tyopaari='';
			$model->save();
			$t = Tyovuoroot::model()->deletebypk($ex[0]);	
			} else {
			echo json_encode('id puuttuu');
			break;
			}		
		}


			$did = $this->renderPartial('did',array(
				'pvm'=>$_POST['newPvm'],
				'tid'=>$_POST['newTid'],
				'from'=>'ajax',
				'asetukset'=>$asetukset
			), true);
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
		  elseif($v == 3 and date('W',$startdate)%2 == 0)
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
		  elseif($v == 3 and date('W',$startdate)%2 == 0)
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
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."<br>";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."<br>";
		if(!empty($m->aikataulu))
		  $ohje .= "<br>Aikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "<br>Toimenpiteet: ".$m->toimenpiteet;
		if(!empty($m->tietoja))
		  $ohje .= "<br>Tietoja: ".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "<br>Muut: ".$m->muut;
		echo json_encode(array($ohje,$m->tietoja,$m->arvioitu_kesto));
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
		$asetukset = Asetukset::model()->findByPk(1);
		$this->renderPartial('did',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'from'=>$from,
			'tietoja'=>$tietoja,
			'asetukset'=>$asetukset
		));
	}

	public function actionDidnew()
	{
	     if(is_array(json_decode($_POST['kohteet_siivous'], true)))
	     $ks = json_decode($_POST['kohteet_siivous'], true);
	     else
	     $ks = array();


	     $asetukset = Asetukset::model()->findByPk(1);
 	     $this->renderPartial('did',array(
					'pvm'=>$_POST['pvm'],
					'tid'=>$_POST['tid'],
					'from'=>$_POST['from'], 
					'kohteet_siivous'=>$ks, 
					'asetukset'=>$asetukset,
					'asiakas'=>$_POST['asiakas'],
					'kohde'=>$_POST['kohde'],
	     ));

	}

	public function actionViikko($tid,$viikko,$year)
	{

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



	public function actionCreate()
	{



		$return = array();

		if(isset($_POST['ToistuvatTyovuorot']) and isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on')
		{
			$saankoSuoritta = $_POST['ToistuvatTyovuorot']['sopivatPaivat'];

			$toistuva=new ToistuvatTyovuorot;
			$toistuva->attributes=$_POST['ToistuvatTyovuorot'];
			$toistuva->attributes=$_POST['Tyovuoroot'];
			$toistuva->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if(isset($_POST['P']))
			$toistuva->viikko_paivat=json_encode($_POST['P']);


			if($saankoSuoritta == 1)
			{
				if(!$toistuva->save())
				{
					$return[] = array('ERROR'=>json_encode(var_dump($toistuva->getErrors())));
				}
			}

	
			if(!isset($_POST['tyopaari']))
			{
			$return[] = $this->toistuvaInsert(
				$toistuva->id,
				$toistuva->pfrom, 
				$toistuva->pto, 
				json_decode($toistuva->viikko_paivat, true),
				$toistuva->viikkoja, 
				$toistuva->tid, 
				$toistuva->kohde, 
				$toistuva->alku, 
				$toistuva->loppu, 
				$toistuva->pituus, 
				$toistuva->tyoajanmerkinta, 
				$toistuva->tietoja,
				$toistuva->status,
				'', // tyopaari
				$saankoSuoritta
				);
			}



			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    // <-- Lisätään pää työntekijä
			    $_POST['tyopaari'][] = $toistuva->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$return[] = $this->toistuvaInsert(
					$toistuva->id,
					$toistuva->pfrom, 
					$toistuva->pto, 
					json_decode($toistuva->viikko_paivat, true),
					$toistuva->viikkoja, 
					$tid, 
					$toistuva->kohde, 
					$toistuva->alku, 
					$toistuva->loppu, 
					$toistuva->pituus, 
					$toistuva->tyoajanmerkinta, 
					$toistuva->tietoja,
					$toistuva->status,
					json_encode($_POST['tyopaari']),
					$saankoSuoritta
					);
			    }

				ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tyopaari' => json_encode($_POST['tyopaari'])));
			}
			// jos on tyopaari -->



			// <-- tvuoro_ids Updater
			if( $saankoSuoritta == 1 and count($return) > 0 )
			{

				$tvuoro_ids	= array();
				foreach($return as $k=>$item)
				{
					foreach($item as $item2)
					{
						if(isset($item2['tvuoro_id']))
							$tvuoro_ids[] = $item2['tvuoro_id'];
					}
				}

				if( count($tvuoro_ids) > 0 )
				{
					ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tvuoro_ids' => json_encode($tvuoro_ids)));
				}

			}
			//  tvuoro_ids Updater -->


			echo json_encode($return);
			exit;
		}






		$model=new Tyovuoroot;

		if(isset($_POST['Tyovuoroot']))
		{

			$model->attributes=$_POST['Tyovuoroot'];

			// <-- Apuaika
			if(isset($_POST['Tyovuoroot']['apuaika']) and $_POST['Tyovuoroot']['apuaika'] == 1)
				$model->apuaika = 1;
			else if(isset($_POST['Tyovuoroot']['apuaika']) and $_POST['Tyovuoroot']['apuaika'] != 1)
				$model->apuaika = 0;
			//     Apuaika -->

			$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if($model->save())
			{

			// <-- PushNotify
			if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
			$this->pushNotifySending($model->id);
			// PushNotify -->



			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    $luotu = array();
			    $luotu[$model->id] = $model->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$m=new Tyovuoroot;
				$m->attributes=$_POST['Tyovuoroot'];
				$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				$m->tid=$tid;
				if($m->save())
				{
					$luotu[$m->id] = $m->tid;
					$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));

					// <-- PushNotify
					if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
					$this->pushNotifySending($m->id);
					// PushNotify -->

				}

			    }
			    foreach($luotu as $k=>$v)
					Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));


			}
			// jos on tyopaari -->


			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			echo json_encode($return);

			}
		exit;
		}


  	$tnimi = '';
	if(isset($_POST['tid']) and $_POST['tid'] != 0){
  	  $tekija = Tyontekijat::model()->findbypk($_POST['tid']);
	  $tnimi = $this->etuSukunimi($tekija->id);


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


        <!-- Admin Form Popup -->
        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"><i class="fa fa-clock-o"></i> 
		<?php echo Yii::t('main', 'Työvuoron suunnittelu').': '.$tnimi; ?>
	      </span>
            </div>
            <!-- end .panel-heading section -->

              <div class="panel-body p25">
		<?php
		if(isset($oikeus)) echo $oikeus;
		$this->renderPartial('_form',array(
			'model'=>$model,
		));
		?>
              </div>
              <!-- end .form-body section -->


          </div>
          <!-- end: .panel -->
        </div>
        <!-- end: .admin-form -->



	<?php
	}


	public function actionPaivita_laatikot()
	{
		if(isset($_POST['toistuva_id']))
		{
			$data = Tyovuoroot::model()->findAll(" toistuva_id='".$_POST['toistuva_id']."' ");
			foreach($data as $model)
			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));

			echo json_encode($return);
			exit;
		}
	}
/*
	public function actionPoista_toistuva()
	{
		if(isset($_POST['toistuva_id']))
		{
			$data = Tyovuoroot::model()->findAll(" toistuva_id='".$_POST['toistuva_id']."' ");
			foreach($data as $model)
			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
	
			ToistuvatTyovuorot::model()->findByPk($_POST['toistuva_id'])->delete();

			echo json_encode($return);
			exit;
		}
	}
*/
	public function actionUpdate($id)
	{

		$model=$this->loadModel($id);
		$edellinenToistuva = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id);

		$return = array();


		// <-- REPAIR, koko ketjun poisto ja luo uudestaan jos aloitus ei vanhempi kun tänään
		if(
			isset($edellinenToistuva->id) 
			and isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) 
			and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on'
			and isset($_POST['ToistuvatTyovuorot']['toistuva_repair']) 
			and $_POST['ToistuvatTyovuorot']['toistuva_repair'] == 'on'
		)
		{
			$fi = $this->vkoPaivat();
			//$return[] = array('ERROR'=>json_encode($tyopaari_forUpdater));
			$saankoSuoritta = $_POST['ToistuvatTyovuorot']['sopivatPaivat'];
			$edelliset_tvuoro_ids = json_decode($edellinenToistuva->tvuoro_ids, true);

			$toistuva=new ToistuvatTyovuorot;
			$toistuva->attributes=$_POST['ToistuvatTyovuorot'];
			$toistuva->attributes=$_POST['Tyovuoroot'];
			$toistuva->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if(isset($_POST['P']))
			$toistuva->viikko_paivat=json_encode($_POST['P']);

			if(strtotime($edellinenToistuva->pfrom) < strtotime(date("d.m.Y")))
			{
				$vanhat = true;
				$toistuva->pfrom = date("d.m.Y");
			} else {
				$vanhat = false;
			}


			if($saankoSuoritta == 1)
			{
				if(!$toistuva->save())
				{
					$return[] = array('ERROR'=>json_encode(var_dump($toistuva->getErrors())));
				}
			}


			$tvuoro_ids_implode = implode(",", $edelliset_tvuoro_ids);
			$criteria = new CDBcriteria;
			$criteria->condition=" 
				toistuva_id='".$edellinenToistuva->id."'
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
				BETWEEN '".date("Y-m-d", strtotime($edellinenToistuva->pfrom))."' AND '".date("Y-m-d", strtotime('-1 day'))."'
			";
			$t = Tyovuoroot::model()->findAll($criteria);
			$update_vanhat_ids = array();
			foreach($t as $item)
			{
				$update_vanhat_ids[] = $item->id;
				if( $saankoSuoritta != 1 )
				{
					$return[] = array(
						'tid'=>$item->tid, 
						'pvm'=>$item->pvm, 
						'ymd'=>date("Ymd",strtotime($item->pvm)), 
						'isSaved'=>false,
						'repair_ei-muutoksia'=>true, 
						'tekijan_nimi'=>$this->etuSukunimi($item->tid), 
						'vkopvm' => $fi[date("N",strtotime($item->pvm))]
					);
				} 
			}


			// <-- Pois kaikki vanhat
			$pois_criteria = new CDBcriteria;
			$pois_criteria->condition=" 
				toistuva_id='".$edellinenToistuva->id."'
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
			";
			//    Pois kaikki vanhat -->


			//$return[] = array('ERROR'=>json_encode($r));

			if($saankoSuoritta == 1)
			{
				if($vanhat == true and count($update_vanhat_ids) > 0)
				{
				ToistuvatTyovuorot::model()->updatebypk($edellinenToistuva->id, 
					array('tvuoro_ids' => json_encode($update_vanhat_ids), 'pto' => date("Y-m-d", strtotime('-1 day')))
				);
				} else {
				ToistuvatTyovuorot::model()->findbypk($edellinenToistuva->id)->delete();
				}

				Tyovuoroot::model()->deleteAll($pois_criteria);
			}



			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    // <-- Lisätään pää työntekijä
			    $_POST['tyopaari'][] = $toistuva->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$return[] = $this->toistuvaInsert(
					$toistuva->id,
					$toistuva->pfrom, 
					$toistuva->pto, 
					json_decode($toistuva->viikko_paivat, true),
					$toistuva->viikkoja, 
					$tid, 
					$toistuva->kohde, 
					$toistuva->alku, 
					$toistuva->loppu, 
					$toistuva->pituus, 
					$toistuva->tyoajanmerkinta, 
					$toistuva->tietoja,
					$toistuva->status,
					json_encode($_POST['tyopaari']),
					$saankoSuoritta
					);
			    }

				if($saankoSuoritta == 1)
				ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tyopaari' => json_encode($_POST['tyopaari'])));
			} else
			// jos on tyopaari -->
			{

			$return[] = $this->toistuvaInsert(
				$toistuva->id,
				$toistuva->pfrom, 
				$toistuva->pto, 
				json_decode($toistuva->viikko_paivat, true),
				$toistuva->viikkoja, 
				$toistuva->tid, 
				$toistuva->kohde, 
				$toistuva->alku, 
				$toistuva->loppu, 
				$toistuva->pituus, 
				$toistuva->tyoajanmerkinta, 
				$toistuva->tietoja,
				$toistuva->status,
				'', // tyopaari
				$saankoSuoritta
				);

			}


			// <-- tvuoro_ids Updater
			if( $saankoSuoritta == 1 and count($return) > 0 )
			{

				$tvuoro_ids	= array();
				foreach($return as $k=>$item)
				{
					foreach($item as $item2)
					{
						if(isset($item2['tvuoro_id']))
							$tvuoro_ids[] = $item2['tvuoro_id'];
					}
				}

				if( count($tvuoro_ids) > 0 )
				{
					ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tvuoro_ids' => json_encode($tvuoro_ids)));
				}

			}
			//  tvuoro_ids Updater -->

			if( count($return) > 0 )
				echo json_encode($return);
			else
				echo json_encode(array('ERROR'=>'Ei muutoksia'));
			exit;
		}
		//     REPAIR, koko ketjun poisto ja luo uudestaan jos aloitus ei vanhempi kun tänään -->


		// <-- Jos edellista tvuoro_ids ei loyty
		if(
			isset($edellinenToistuva->id) 
			and empty($edellinenToistuva->tvuoro_ids)
			and isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) 
			and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on'
		)
		{
			echo json_encode(array('ERROR'=>'<div class="alert bg-danger">Tämä ketju ei saa muokata.</div>'));
			exit;
		}
		//     Jos edellista tvuoro_ids ei loyty -->




		// <-- Toistuva tyovuorot ja tyopaarit olevasta työvuorosta jos ei olisi
		if(
			!isset($edellinenToistuva->id) 
			and isset($_POST['ToistuvatTyovuorot']) 
			and isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) 
			and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on')
		{


			$saankoSuoritta = $_POST['ToistuvatTyovuorot']['sopivatPaivat'];

			$toistuva=new ToistuvatTyovuorot;
			$toistuva->attributes=$_POST['ToistuvatTyovuorot'];
			$toistuva->attributes=$_POST['Tyovuoroot'];
			$toistuva->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if(isset($_POST['P']))
			$toistuva->viikko_paivat=json_encode($_POST['P']);


			if($saankoSuoritta == 1)
			{
			$toistuva->save();
			$model->delete();
			}

	
			if(!isset($_POST['tyopaari']))
			{
			$return[] = $this->toistuvaInsert(
				$toistuva->id,
				$toistuva->pfrom, 
				$toistuva->pto, 
				json_decode($toistuva->viikko_paivat, true),
				$toistuva->viikkoja, 
				$toistuva->tid, 
				$toistuva->kohde, 
				$toistuva->alku, 
				$toistuva->loppu, 
				$toistuva->pituus, 
				$toistuva->tyoajanmerkinta, 
				$toistuva->tietoja,
				$toistuva->status,
				'', // tyopaari
				$saankoSuoritta
				);
			}



			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    // <-- Lisätään pää työntekijä
			    $_POST['tyopaari'][] = $toistuva->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$return[] = $this->toistuvaInsert(
					$toistuva->id,
					$toistuva->pfrom, 
					$toistuva->pto, 
					json_decode($toistuva->viikko_paivat, true),
					$toistuva->viikkoja, 
					$tid, 
					$toistuva->kohde, 
					$toistuva->alku, 
					$toistuva->loppu, 
					$toistuva->pituus, 
					$toistuva->tyoajanmerkinta, 
					$toistuva->tietoja,
					$toistuva->status,
					json_encode($_POST['tyopaari']),
					$saankoSuoritta
					);
			    }

				if($saankoSuoritta == 1)
				ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tyopaari' => json_encode($_POST['tyopaari'])));
			}
			// jos on tyopaari -->



			// <-- tvuoro_ids Updater
			if( $saankoSuoritta == 1 and count($return) > 0 )
			{

				$tvuoro_ids	= array();
				foreach($return as $k=>$item)
				{
					foreach($item as $item2)
					{
						if(isset($item2['tvuoro_id']))
							$tvuoro_ids[] = $item2['tvuoro_id'];
					}
				}

				if( count($tvuoro_ids) > 0 )
				{
					$tvuoro_ids[] = $model->id;
					ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tvuoro_ids' => json_encode($tvuoro_ids)));
			
					if(isset($_POST['tyopaari']))
					{
						Tyovuoroot::model()->updatebypk($model->id, array(
							'tyopaari' => json_encode($_POST['tyopaari']),
						));
					}

					Tyovuoroot::model()->updatebypk($model->id, array(
						'toistuva_id' => $toistuva->id,
					));


				}

			}
			//  tvuoro_ids Updater -->



			if( count($return) > 0 )
				echo json_encode($return);
			else
				echo json_encode(array('ERROR'=>'Ei muutoksia'));
			exit;
		}
		//     Toistuva tyovuorot ja tyopaarit olevasta työvuorosta jos ei olisi -->





		// <-- Toistuva tyovuorot ja tyopaarit
		if(
			isset($edellinenToistuva->id) 
			and isset($_POST['ToistuvatTyovuorot']) 
			and isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) 
			and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on')
		{


			$saankoSuoritta = $_POST['ToistuvatTyovuorot']['sopivatPaivat'];

			$edelliset_tvuoro_ids = json_decode($edellinenToistuva->tvuoro_ids, true);
			$updateTyovuoroja = true;

			// <-- Uudet POST tiedot
			$tv = new Tyovuoroot;
			$tv->attributes=$_POST['Tyovuoroot'];
			//     Uudet POST tiedot -->


			$fi = $this->vkoPaivat();

			$result_diff_lisaaminen = array();
			$result_diff_poistaminen = array();
			$post_tyopaari_plus_paa = array();
			$edelliset_tyoparit_Arr = json_decode($edellinenToistuva->tyopaari, true);



			// <-- POST tyopaari
			if( isset($_POST['tyopaari']))
			{
				$post_tyopaari_plus_paa = $_POST['tyopaari'];
				$post_tyopaari_plus_paa[] = $model->tid;
				
				if( is_array($edelliset_tyoparit_Arr) )
				{
					$result_diff_lisaaminen = array_diff($_POST['tyopaari'], $edelliset_tyoparit_Arr);
					$result_diff_poistaminen = array_diff($edelliset_tyoparit_Arr, $post_tyopaari_plus_paa);
				} else {
					$result_diff_lisaaminen = $_POST['tyopaari'];
				}

			} else {


				if( is_array($edelliset_tyoparit_Arr) )
				{
					$result_diff_poistaminen = array_diff($edelliset_tyoparit_Arr, array($model->tid));
				}
			}

			// <-- For Updater
			$updateTyoparia = true;
			if( count($post_tyopaari_plus_paa) > 0 )
			{
				$tyopaari_forUpdater = json_encode($post_tyopaari_plus_paa);
			} else {
				$tyopaari_forUpdater = '';
			}
			//     For Updater -->
			//     POST tyopaari -->



			// <-- Jos Aikavälit ja Viikkonpäivät muutettu saman tien
			if(
				!empty($edellinenToistuva->tvuoro_ids) and is_array($edelliset_tvuoro_ids)
				and 
				($edellinenToistuva->pfrom != $_POST['ToistuvatTyovuorot']['pfrom']
				or $edellinenToistuva->pto != $_POST['ToistuvatTyovuorot']['pto']
				)
				and $edellinenToistuva->viikko_paivat != json_encode($_POST['P'])
			)
			{
				$return[] = array('ERROR'=>'<div class="alert bg-danger">Ei voi muokata aikaväli ja viikkonpäivät samalla</div>');
			}
			//     Jos Aikavälit ja Viikkonpäivät muutettu saman tien -->




			// <-- Jos ei ole muutoksia toistuva ja tyoparilla
			if(
				!empty($edellinenToistuva->tvuoro_ids) and is_array($edelliset_tvuoro_ids)
				and $edellinenToistuva->pfrom == $_POST['ToistuvatTyovuorot']['pfrom']
				and $edellinenToistuva->pto == $_POST['ToistuvatTyovuorot']['pto']
				and $edellinenToistuva->viikko_paivat == json_encode($_POST['P'])
				and $edellinenToistuva->viikkoja == $_POST['ToistuvatTyovuorot']['viikkoja']
			)
			{



				// <-- Poistaminen työparia
				if( count($result_diff_poistaminen) > 0 )
				{

					$tvuoro_ids = json_decode($edellinenToistuva->tvuoro_ids, true);
					if( is_array($tvuoro_ids) )
					{
						$tvuoro_ids_implode = implode(",", $tvuoro_ids);
						$tvuoro_tid_implode = implode(",", $result_diff_poistaminen);

						$criteria = new CDBcriteria;
						$criteria->condition=" 
							id IN ($tvuoro_ids_implode) 
							AND tid IN ($tvuoro_tid_implode)
						";
						$t = Tyovuoroot::model()->findAll($criteria);
						foreach($t as $item)
						{

							$ketjustaPois[] = $item->id;
							$edelliset_tvuoro_ids = array_values( array_diff($edelliset_tvuoro_ids, $ketjustaPois) );
					

							if( $saankoSuoritta != 1 )
							{

								$return[] = array(
									'tid'=>$item->tid, 
									'pvm'=>$item->pvm, 
									'ymd'=>date("Ymd",strtotime($item->pvm)), 
									'isSaved'=>false,
									'poistaminen'=>true, 
									'tekijan_nimi'=>$this->etuSukunimi($item->tid), 
									'vkopvm' => $fi[date("N",strtotime($item->pvm))]
								);

							} else {
								Tyovuoroot::model()->deleteByPk($item->id);
								$return[] = array(
								'tid'=>$item->tid, 
								'pvm'=>$item->pvm, 
								'ymd'=>date("Ymd",strtotime($item->pvm)),
								'isSaved'=>true
								);
							}
	
						}

					}

				}
				//     Poistaminen työparia -->




				// <-- Muokkaus
				$newPostArr = array(
					'kohde'=>$tv->kohde,
					'alku'=>$tv->alku,
					'loppu'=>$tv->loppu,
					'pituus'=>$tv->pituus,
					'tyoajanmerkinta'=>$tv->tyoajanmerkinta,
					'tietoja'=>$tv->tietoja,
					'status'=>$tv->status,
				);

				$ketjustaPois = array();
			   	foreach($edelliset_tvuoro_ids as $tvuoro_id)
			   	{

					$criteria = new CDBcriteria;
					$criteria->condition=" id='".$tvuoro_id."' ";
				  	$t = Tyovuoroot::model()->find($criteria);

					if( isset($t->id)  and $model->tid != $tv->tid and $model->tid == $t->tid )
					{

						$criteria = new CDBcriteria;
						$criteria->condition=" 
							id='".$tvuoro_id."' 
							AND tid='".$model->tid."'
							AND toistuva_id='".$edellinenToistuva->id."'
						";
					  	$t2 = Tyovuoroot::model()->findAll($criteria);
						foreach($t2 as $item2)
						{

							$return[] = array(
							'tid'=>$item2->tid, 
							'pvm'=>$item2->pvm, 
							'ymd'=>date("Ymd",strtotime($item2->pvm)), 
							'isSaved'=>false,
							'poistaminen'=>true, 
							'tekijan_nimi'=>$this->etuSukunimi($item2->tid), 
							'vkopvm' => $fi[date("N",strtotime($item2->pvm))]
							);


							$ketjustaPois[] = $item2->id;
							$edelliset_tvuoro_ids = array_values( array_diff($edelliset_tvuoro_ids, $ketjustaPois) );


							if( $saankoSuoritta == 1 )
							{
								Tyovuoroot::model()->deleteByPk($item2->id);
								$return[] = array(
								'tid'=>$item2->tid, 
								'pvm'=>$item2->pvm, 
								'ymd'=>date("Ymd",strtotime($item2->pvm)),
								'isSaved'=>true
								);

							}
						}

						continue;

					}
/*
				    	if( $saankoSuoritta == 1 and isset($t->id) )
				    	{

					     	Tyovuoroot::model()->updateByPk($t->id, $newPostArr);

						$return[] = array(
						'tid'=>$t->tid, 
						'pvm'=>$t->pvm, 
						'ymd'=>date("Ymd",strtotime($t->pvm)),
						'isSaved'=>true
						);


				    	} elseif( $saankoSuoritta != 1 and isset($t->id) ) {
*/

				    	if( $saankoSuoritta != 1 and isset($t->id) )
					{
						$return[] = array(
							'tid'=>$t->tid, 
							'pvm'=>$t->pvm, 
							'ymd'=>date("Ymd",strtotime($t->pvm)), 
							'isSaved'=>false,
							'muokkaus'=>true, 
							'tekijan_nimi'=>$this->etuSukunimi($t->tid), 
							'vkopvm' => $fi[date("N",strtotime($t->pvm))]
						);
						
				    	}
 
			   	}


				if( $model->tid != $tv->tid )
				{
					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						$edellinenToistuva->pfrom,
						$edellinenToistuva->pto,
						json_decode($edellinenToistuva->viikko_paivat, true),
						$edellinenToistuva->viikkoja, 
						$_POST['Tyovuoroot']['tid'], 
						$tv->kohde, 
						$tv->alku, 
						$tv->loppu, 
						$tv->pituus, 
						$tv->tyoajanmerkinta, 
						$tv->tietoja,
						$tv->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				}
				if($saankoSuoritta == 1 and $model->tid != $_POST['Tyovuoroot']['tid'])
				{

					foreach($return as $k=>$item)
					{
						foreach($item as $k=>$item2)
						{
							if(isset($item2['tvuoro_id']))
							$edelliset_tvuoro_ids[] = $item2['tvuoro_id'];
						}
					}
				}


				// <-- Poistetaan uudesta ketjusta jos tyontekija olisi vaihtanut
				if( $model->tid != $tv->tid )
				{
					$tvpupd = json_decode($tyopaari_forUpdater, true);
					$tvpupd[] = $tv->tid;
					$tyopaari_forUpdater = json_encode(array_values( array_diff($tvpupd, array($model->tid)) ));
				}
				//     Poistetaan uudesta ketjusta jos tyontekija olisi vaihtanut -->

				//     Muokkaus -->





				// <-- Kun lisätään työpari ketjuun
				if( isset($_POST['tyopaari']) and count($result_diff_lisaaminen) > 0 )
				{


					$tvuoro_ids_implode = implode(",", $edelliset_tvuoro_ids);
					$criteria = new CDBcriteria;
					$criteria->group=" pvm ";
					$criteria->condition=" id IN ($tvuoro_ids_implode) ";
				  	$t = Tyovuoroot::model()->findAll($criteria);
					foreach($t as $item)
					{
						foreach($result_diff_lisaaminen as $tid)
						{
							if( $saankoSuoritta != 1 )
							{

								$return[] = array(
									'tid'=>$tid, 
									'pvm'=>$item->pvm, 
									'ymd'=>date("Ymd",strtotime($item->pvm)), 
									'isSaved'=>false,
									'uusi'=>true, 
									'tekijan_nimi'=>$this->etuSukunimi($tid), 
									'vkopvm' => $fi[date("N",strtotime($item->pvm))]
								);

							} else {

								$t = Tyovuoroot::model()->findByPk($item->id);
								if(isset($t->id))
								{
									$newTv = new Tyovuoroot;
									$newTv->attributes = $item->attributes;
									$newTv->tid = $tid;
									$newTv->tyopaari = $tyopaari_forUpdater;
									if($newTv->save())
									{
										$return[] = array(
											'tid'=>$newTv->tid, 
											'pvm'=>$newTv->pvm, 
											'ymd'=>date("Ymd",strtotime($newTv->pvm)),
											'isSaved'=>true
											);
										$edelliset_tvuoro_ids[] = $newTv->id;

									} else {

										$return[] = array(
											'ERROR'=>$this->etuSukunimi($tid). 'työpari lisääminen ei onnistunut. '

											);
										exit;
									}
								}

							}
						}
					}
					
				}
				//     Kun lisätään työpari ketjuun -->


			}
			//     Jos ei ole muutoksia toistuva ja tyoparilla -->





			// <-- Jos on uusi Alkaen enemmmään kun edellinen ja Loppuen on sama tai enemmään
			if(
				!empty($edellinenToistuva->tvuoro_ids) and is_array($edelliset_tvuoro_ids)
				and $edellinenToistuva->viikko_paivat == json_encode($_POST['P'])
				and $edellinenToistuva->viikkoja == $_POST['ToistuvatTyovuorot']['viikkoja']
				and 
				(
				$edellinenToistuva->pto == $_POST['ToistuvatTyovuorot']['pto']
				or strtotime($_POST['ToistuvatTyovuorot']['pto']) > strtotime($edellinenToistuva->pto)
				)
				and strtotime($_POST['ToistuvatTyovuorot']['pfrom']) > strtotime($edellinenToistuva->pfrom)
			)
			{



				$updateTyoparia = false;
				$updateTyovuoroja = false;

				$uusiPfrom = $_POST['ToistuvatTyovuorot']['pfrom'];
				$uusiPto = $_POST['ToistuvatTyovuorot']['pto'];



				if( $model->tid != $_POST['Tyovuoroot']['tid'])
				{

					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						$uusiPfrom,
						$uusiPto,
						json_decode($edellinenToistuva->viikko_paivat, true),
						$edellinenToistuva->viikkoja, 
						$_POST['Tyovuoroot']['tid'], 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				}

				$tvuoro_ids_implode = implode(",", $edelliset_tvuoro_ids);
				$criteria = new CDBcriteria;
				$criteria->condition=" 
					id IN ($tvuoro_ids_implode) 
					AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
						BETWEEN '".date("Y-m-d", strtotime($uusiPfrom))."' AND '".date("Y-m-d", strtotime($uusiPto))."'
				";

				//if( $model->tid != $tv->tid )
					//$criteria->addCondition(" tid='".$model->tid."' ");

			  	$t = Tyovuoroot::model()->findAll($criteria);
				$uusiKetju = array();
				$poistetaan = array();
				$lastDate = '';
				foreach($t as $item)
				{

					$uusiKetju[]	= $item->id;
					$ketjustaPois[] = $item->id;
					$edelliset_tvuoro_ids = array_values( array_diff($edelliset_tvuoro_ids, $ketjustaPois) );

					if( $model->tid != $_POST['Tyovuoroot']['tid'] and $model->tid == $item->tid)
					{
						$poistetaan[] = $item->id;
					}

					if( $saankoSuoritta != 1 )
					{

					    	if( $model->tid != $_POST['Tyovuoroot']['tid'] and $model->tid == $item->tid)
					    	{

					    	    $return[] = array(
							'tid'=>$item->tid, 
							'pvm'=>$item->pvm, 
							'ymd'=>date("Ymd",strtotime($item->pvm)), 
							'isSaved'=>false,
							'poistaminen'=>true, 
							'tekijan_nimi'=>$this->etuSukunimi($item->tid).' '.$item->id, 
							'vkopvm' => $fi[date("N",strtotime($item->pvm))]
					    	    );


					    	} else {
					    	    $return[] = array(
							'tid'=>$item->tid, 
							'pvm'=>$item->pvm, 
							'ymd'=>date("Ymd",strtotime($item->pvm)), 
							'isSaved'=>false,
							'ketjunMuutos'=>true, 
							'tekijan_nimi'=>$this->etuSukunimi($item->tid), 
							'vkopvm' => $fi[date("N",strtotime($item->pvm))]
					    	    );
					    	}

					} else {

					    	if( $model->tid != $_POST['Tyovuoroot']['tid'] and $model->tid == $item->tid)
					    	{
							Tyovuoroot::model()->deleteByPk($item->id);
						}
					    	    $return[] = array(
							'tid'=>$item->tid, 
							'pvm'=>$item->pvm, 
							'ymd'=>date("Ymd",strtotime($item->pvm)), 
							'isSaved'=>true
						    );

					}

					$lastDate = $item->pvm;
				}



				if( $tyopaari_forUpdater == '' and $model->tid == $_POST['Tyovuoroot']['tid'])
				{
					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						date("d.m.Y", strtotime($lastDate.' +1 day')),
						$uusiPto,
						json_decode($edellinenToistuva->viikko_paivat, true),
						$edellinenToistuva->viikkoja, 
						$model->tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				} elseif( $tyopaari_forUpdater != '' and $model->tid == $_POST['Tyovuoroot']['tid']) {
				  
  				    foreach(json_decode($tyopaari_forUpdater, true) as $tid)
				    {
					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						date("d.m.Y", strtotime($lastDate.' +1 day')),
						$uusiPto,
						json_decode($edellinenToistuva->viikko_paivat, true),
						$edellinenToistuva->viikkoja, 
						$tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				    }
				}



				foreach($return as $k=>$item)
				{
					foreach($item as $k=>$item2)
					{
						if(isset($item2['tvuoro_id']))
						$uusiKetju[] = $item2['tvuoro_id'];
					}
				}


				// <-- Poistetaan uudesta ketjusta jos tyontekija olisi vaihtanut
				if( $model->tid != $tv->tid )
				{
					$uusiKetju = array_values( array_diff($uusiKetju, $poistetaan) );

					$tvpupd = json_decode($tyopaari_forUpdater, true);
					$tvpupd[] = $tv->tid;
					$tyopaari_forUpdater = json_encode(array_values( array_diff($tvpupd, array($model->tid)) ));
				}
				//     Poistetaan uudesta ketjusta jos tyontekija olisi vaihtanut -->

				//$return[] = array('ERROR'=>json_encode($tyopaari_forUpdater));

				// <-- Uusi toistuva ketju
				$toistuva = new ToistuvatTyovuorot;
				$toistuva->attributes=$_POST['ToistuvatTyovuorot'];
				$toistuva->attributes=$_POST['Tyovuoroot'];
				$toistuva->tvuoro_ids=json_encode($uusiKetju);
				$toistuva->tyopaari=$tyopaari_forUpdater;

				if(isset($_POST['P']))
				$toistuva->viikko_paivat=json_encode($_POST['P']);
		
				if($saankoSuoritta == 1 and count($uusiKetju) > 0)
				{
					$toistuva->save();


					if( strtotime($uusiPfrom) > strtotime($edellinenToistuva->pfrom) )
					{
					    ToistuvatTyovuorot::model()->updateByPk($edellinenToistuva->id, array(
						'pto'=>date("d.m.Y", strtotime($uusiPfrom.' -1 day'))
					    ));
					}


					$tvuoro_ids_implode = implode(",", $uusiKetju);
					$criteria = new CDBcriteria;
					$criteria->condition=" id IN ($tvuoro_ids_implode) ";
					Tyovuoroot::model()->updateAll(array(
						'tyopaari'=>$tyopaari_forUpdater,
						'toistuva_id'=>$toistuva->id
					), $criteria);


					// <-- Kortti update
					$newPostArr = array(
						'kohde'=>$tv->kohde,
						'alku'=>$tv->alku,
						'loppu'=>$tv->loppu,
						'pituus'=>$tv->pituus,
						'tyoajanmerkinta'=>$tv->tyoajanmerkinta,
						'tietoja'=>$tv->tietoja,
						'status'=>$tv->status,
					);

					Tyovuoroot::model()->updateAll($newPostArr, $criteria);
				  	$t = Tyovuoroot::model()->findAll($criteria);

					foreach($t as $item)
					{

					    $return[] = array(
						'tid'=>$item->tid, 
						'pvm'=>$item->pvm, 
						'ymd'=>date("Ymd",strtotime($item->pvm)), 
						'isSaved'=>true
					    );

					}
					//     Kortti update -->



				}
				//     Uusi toistuva ketju -->


				//$return[] = array('ERROR'=>$toistuva);
				//$return[] = array('ERROR'=>json_encode($uusiKetju));
			}
			//     Jos on uusi Alkaen enemmmään kun edellinen ja Loppuen on sama tai enemmään -->




			// <-- Jos on Alkaen on sama kun edellinen  mutta Loppuen on enemmään kun edellinen
			if(
				!empty($edellinenToistuva->tvuoro_ids) and is_array($edelliset_tvuoro_ids)
				and $edellinenToistuva->viikko_paivat == json_encode($_POST['P'])
				and $edellinenToistuva->pfrom == $_POST['ToistuvatTyovuorot']['pfrom']
				and $edellinenToistuva->viikkoja == $_POST['ToistuvatTyovuorot']['viikkoja']
				and strtotime($_POST['ToistuvatTyovuorot']['pto']) > strtotime($edellinenToistuva->pto)
			)
			{

				//$updateTyoparia = false;
				$uusiPfrom = $_POST['ToistuvatTyovuorot']['pfrom'];
				$uusiPto = $_POST['ToistuvatTyovuorot']['pto'];
				$lastDate = $edellinenToistuva->pto;


				if( $model->tid != $tv->tid )
				{
					$criteria = new CDBcriteria;
					$criteria->condition=" 
						toistuva_id='".$edellinenToistuva->id."'
						AND tid='".$model->tid."'
					";
				  	$t = Tyovuoroot::model()->findAll($criteria);
					$ketjustaPois = array();
					foreach($t as $item)
					{

						$ketjustaPois[] = $item->id;
						$edelliset_tvuoro_ids = array_values( array_diff($edelliset_tvuoro_ids, $ketjustaPois) );

						if( $saankoSuoritta != 1 )
						{
					    	    $return[] = array(
							'tid'=>$item->tid, 
							'pvm'=>$item->pvm, 
							'ymd'=>date("Ymd",strtotime($item->pvm)), 
							'isSaved'=>false,
							'poistaminen'=>true, 
							'tekijan_nimi'=>$this->etuSukunimi($item->tid), 
							'vkopvm' => $fi[date("N",strtotime($item->pvm))]
					    	    );

						} else {

						    Tyovuoroot::model()->deleteByPk($item->id);
					    	    $return[] = array(
							'tid'=>$item->tid, 
							'pvm'=>$item->pvm, 
							'ymd'=>date("Ymd",strtotime($item->pvm)), 
							'isSaved'=>true
						    );

						}
					}
				}



				if( $tyopaari_forUpdater == '' )
				{
					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						date("d.m.Y", strtotime($lastDate.' +1 day')),
						$uusiPto,
						json_decode($edellinenToistuva->viikko_paivat, true),
						$edellinenToistuva->viikkoja, 
						$model->tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				} else {
				  
  				    foreach(json_decode($tyopaari_forUpdater, true) as $tid)
				    {

					if( $model->tid != $tv->tid and $model->tid == $tid )
					continue;

					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						date("d.m.Y", strtotime($lastDate.' +1 day')),
						$uusiPto,
						json_decode($edellinenToistuva->viikko_paivat, true),
						$edellinenToistuva->viikkoja, 
						$tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				    }
				}



				if( $model->tid != $tv->tid )
				{

					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						$uusiPfrom,
						$uusiPto,
						json_decode($edellinenToistuva->viikko_paivat, true),
						$edellinenToistuva->viikkoja, 
						$_POST['Tyovuoroot']['tid'], 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				}


				if($saankoSuoritta == 1)
				{

					foreach($return as $k=>$item)
					{
						foreach($item as $k=>$item2)
						{
							if(isset($item2['tvuoro_id']))
							$edelliset_tvuoro_ids[] = $item2['tvuoro_id'];
						}
					}


				    	ToistuvatTyovuorot::model()->updateByPk($edellinenToistuva->id, array(
						'pto'=>$uusiPto
				    	));

				}



				// <-- Poistetaan jos tyontekija olisi vaihtanut
				if( $model->tid != $tv->tid )
				{

					$tvpupd = json_decode($tyopaari_forUpdater, true);
					$tvpupd[] = $tv->tid;
					$tyopaari_forUpdater = json_encode(array_values( array_diff($tvpupd, array($model->tid)) ));
				}
				//     Poistetaan jos tyontekija olisi vaihtanut -->


				//$return[] = array('ERROR'=>$tyopaari_forUpdater);
			}
			//     Jos on Alkaen on sama kun edellinen  mutta Loppuen on enemmään kun edellinen -->



			// <-- Jos on Alkaen on sama kun edellinen  mutta Loppuen on vähempi kun edellinen
			if(
				!empty($edellinenToistuva->tvuoro_ids) and is_array($edelliset_tvuoro_ids)
				and $edellinenToistuva->viikko_paivat == json_encode($_POST['P'])
				and $edellinenToistuva->viikkoja == $_POST['ToistuvatTyovuorot']['viikkoja']
				and $edellinenToistuva->pfrom == $_POST['ToistuvatTyovuorot']['pfrom']
				and strtotime($_POST['ToistuvatTyovuorot']['pto']) < strtotime($edellinenToistuva->pto)
			)
			{

				$uusiPto = $_POST['ToistuvatTyovuorot']['pto'];
				$criteria = new CDBcriteria;
				$criteria->condition=" 
					toistuva_id='".$edellinenToistuva->id."'
					AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') > '".date("Y-m-d", strtotime($uusiPto))."'
				";
			  	$t = Tyovuoroot::model()->findAll($criteria);
				$ketjustaPois = array();
				foreach($t as $item)
				{
					$ketjustaPois[] = $item->id;
					$edelliset_tvuoro_ids = array_values( array_diff($edelliset_tvuoro_ids, $ketjustaPois) );

					if( $saankoSuoritta != 1 )
					{
				    	    	$return[] = array(
						'tid'=>$item->tid, 
						'pvm'=>$item->pvm, 
						'ymd'=>date("Ymd",strtotime($item->pvm)), 
						'isSaved'=>false,
						'poistaminen'=>true, 
						'tekijan_nimi'=>$this->etuSukunimi($item->tid), 
						'vkopvm' => $fi[date("N",strtotime($item->pvm))]
				    	    	);

					} else {
						Tyovuoroot::model()->deleteByPk($item->id);
				    	    	$return[] = array(
						'tid'=>$item->tid, 
						'pvm'=>$item->pvm, 
						'ymd'=>date("Ymd",strtotime($item->pvm)), 
						'isSaved'=>true
					    	);
					}
				}

				//$return[] = array('ERROR'=>json_encode($edelliset_tvuoro_ids));

				if( $saankoSuoritta == 1 )
				{
				    	ToistuvatTyovuorot::model()->updateByPk($edellinenToistuva->id, array(
						'pto'=>$uusiPto
				    	));
				}

			}
			//     Jos on Alkaen on sama kun edellinen  mutta Loppuen on vähempi kun edellinen -->


			// <-- Jos on Alkaen on vähempi kun edellinen mutta Loppuen on sama
			if(
				!empty($edellinenToistuva->tvuoro_ids) and is_array($edelliset_tvuoro_ids)
				and $edellinenToistuva->viikko_paivat == json_encode($_POST['P'])
				and $edellinenToistuva->viikkoja == $_POST['ToistuvatTyovuorot']['viikkoja']
				and $edellinenToistuva->pfrom > $_POST['ToistuvatTyovuorot']['pfrom']
				and strtotime($_POST['ToistuvatTyovuorot']['pto']) == strtotime($edellinenToistuva->pto)
			)
			{

				//$return[] = array('ERROR'=>json_encode($edelliset_tvuoro_ids));

				//  <-- Uudet päivät
				if( $tyopaari_forUpdater == '' )
				{
					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						date("d.m.Y", strtotime($_POST['ToistuvatTyovuorot']['pfrom'])),
						date("d.m.Y", strtotime($edellinenToistuva->pfrom.' -1 day')),
						json_decode($edellinenToistuva->viikko_paivat, true),
						$edellinenToistuva->viikkoja, 
						$edellinenToistuva->tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				} else {

  				    foreach(json_decode($tyopaari_forUpdater, true) as $tid)
				    {

					if( $model->tid != $tv->tid and $model->tid == $tid )
					continue;

					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						date("d.m.Y", strtotime($_POST['ToistuvatTyovuorot']['pfrom'])),
						date("d.m.Y", strtotime($edellinenToistuva->pfrom.' -1 day')),
						json_decode($edellinenToistuva->viikko_paivat, true),
						$edellinenToistuva->viikkoja, 
						$tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				    }
				}
				//  Uudet päivät -->




				$uusiPfrom = $_POST['ToistuvatTyovuorot']['pfrom'];
				$tvuoro_ids_implode = implode(",", $edelliset_tvuoro_ids);
				$criteria = new CDBcriteria;
				$criteria->condition=" 
					id IN ($tvuoro_ids_implode) 
					AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
						BETWEEN '".date("Y-m-d", strtotime($uusiPfrom))."' AND '".date("Y-m-d", strtotime($edellinenToistuva->pto))."'
				";


			  	$t = Tyovuoroot::model()->findAll($criteria);
				$uusiKetju = array();
				foreach($t as $item)
				{
						if( $saankoSuoritta != 1 )
						{
					    	    $return[] = array(
							'tid'=>$item->tid, 
							'pvm'=>$item->pvm, 
							'ymd'=>date("Ymd",strtotime($item->pvm)), 
							'isSaved'=>false,
							'muokkaus'=>true, 
							'tekijan_nimi'=>$this->etuSukunimi($item->tid), 
							'vkopvm' => $fi[date("N",strtotime($item->pvm))]
					    	    );

						} else {

							// <-- Kortti update
							$newPostArr = array(
								'kohde'=>$tv->kohde,
								'alku'=>$tv->alku,
								'loppu'=>$tv->loppu,
								'pituus'=>$tv->pituus,
								'tyoajanmerkinta'=>$tv->tyoajanmerkinta,
								'tietoja'=>$tv->tietoja,
								'status'=>$tv->status,
							);

							Tyovuoroot::model()->updateAll($newPostArr, $criteria);

						}
				}



				if( $saankoSuoritta == 1 )
				{
					//echo json_encode($edelliset_tvuoro_ids);
					//exit;

					foreach($return as $item)
					  foreach($item as $line)
						$edelliset_tvuoro_ids[] = $line['tvuoro_id'];


				    	ToistuvatTyovuorot::model()->updateByPk($edellinenToistuva->id, array(
						'pfrom'=>$uusiPfrom
				    	));
				}

			}
			//     Jos on Alkaen on vähempi kun edellinen mutta Loppuen on sama -->


			// <-- Jos on Viikkon päivä on otettu pois
			$edelliset_viikko_paivat = json_decode($edellinenToistuva->viikko_paivat, true);
			$uudet_viikko_paivat = $_POST['P'];
			$viikko_paiva_otettupois = array_diff($edelliset_viikko_paivat, $uudet_viikko_paivat);
			$viikko_paiva_uusiPVMarray = array_diff($edelliset_viikko_paivat, $viikko_paiva_otettupois);

			if(
				!empty($edellinenToistuva->tvuoro_ids) and is_array($edelliset_tvuoro_ids)
				and count($viikko_paiva_otettupois) > 0
				and $edellinenToistuva->pfrom == $_POST['ToistuvatTyovuorot']['pfrom']
				and $edellinenToistuva->pto == $_POST['ToistuvatTyovuorot']['pto']
				and $edellinenToistuva->viikkoja == $_POST['ToistuvatTyovuorot']['viikkoja']
			)
			{



				$tvuoro_ids_implode = implode(",", $edelliset_tvuoro_ids);
				$tvuoro_vkopvm_implode = implode(",", array_values($viikko_paiva_otettupois));

				$criteria = new CDBcriteria;
				$criteria->condition=" 
					id IN ($tvuoro_ids_implode) 
					AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%w') IN ($tvuoro_vkopvm_implode) 
				";
			  	$t = Tyovuoroot::model()->findAll($criteria);
				$uusiKetju = array();
				foreach($t as $item)
				{

					$ketjustaPois[] = $item->id;
					$edelliset_tvuoro_ids = array_values( array_diff($edelliset_tvuoro_ids, $ketjustaPois) );

					if( $saankoSuoritta != 1 )
					{
					    $return[] = array(
						'tid'=>$item->tid, 
						'pvm'=>$item->pvm, 
						'ymd'=>date("Ymd",strtotime($item->pvm)), 
						'isSaved'=>false,
						'poistaminenVkoPvm'=>true, 
						'tekijan_nimi'=>$this->etuSukunimi($item->tid), 
						'vkopvm' => $fi[date("N",strtotime($item->pvm))]
					    );

					} else {

					    Tyovuoroot::model()->findByPk($item->id)->delete();
					    $return[] = array(
						'tid'=>$item->tid, 
						'pvm'=>$item->pvm, 
						'ymd'=>date("Ymd",strtotime($item->pvm)), 
						'isSaved'=>true
					    );

					}
				}

				//$return[] = array('ERROR'=>$edelliset_tvuoro_ids);
				if( $saankoSuoritta == 1 )
				{

					ToistuvatTyovuorot::model()->updateByPk($edellinenToistuva->id, array(
						'viikko_paivat'=>json_encode($viikko_paiva_uusiPVMarray)
					));
				}


			}
			//     Jos on Viikkon päivä on otettu pois -->




			// <-- Jos on Viikkon päivä on lisääntynyt
			$edelliset_viikko_paivat = json_decode($edellinenToistuva->viikko_paivat, true);
			$uudet_viikko_paivat = $_POST['P'];
			$viikko_paiva_lisaantynyt = array_diff($uudet_viikko_paivat, $edelliset_viikko_paivat);


			if(
				!empty($edellinenToistuva->tvuoro_ids) and is_array($edelliset_tvuoro_ids)
				and count($viikko_paiva_lisaantynyt) > 0
				and $edellinenToistuva->pfrom == $_POST['ToistuvatTyovuorot']['pfrom']
				and $edellinenToistuva->pto == $_POST['ToistuvatTyovuorot']['pto']
				and $edellinenToistuva->viikkoja == $_POST['ToistuvatTyovuorot']['viikkoja']
			)
			{

				$updateTyoparia = false;

				$edelliset_viikko_paivat = json_decode($edellinenToistuva->viikko_paivat, true);
				$result_vkoPvm = $edelliset_viikko_paivat + $viikko_paiva_lisaantynyt;
				ksort($result_vkoPvm);

				//$return[] = array('ERROR'=>json_encode($tyopaari_forUpdater));

				if( $tyopaari_forUpdater == '')
				{
					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						$edellinenToistuva->pfrom, 
						$edellinenToistuva->pto, 
						$viikko_paiva_lisaantynyt,
						$edellinenToistuva->viikkoja, 
						$model->tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				} else {
				  
  				    foreach(json_decode($tyopaari_forUpdater, true) as $tid)
				    {
					$return[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						$edellinenToistuva->pfrom, 
						$edellinenToistuva->pto, 
						$viikko_paiva_lisaantynyt,
						$edellinenToistuva->viikkoja, 
						$tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				    }
				}

				//$return[] = $returnInsert;

				if( $saankoSuoritta == 1 )
				{

					foreach($return as $k=>$item)
					{
						foreach($item as $k=>$item2)
						{
							if(isset($item2['tvuoro_id']))
							$edelliset_tvuoro_ids[] = $item2['tvuoro_id'];
						}
					}

					ToistuvatTyovuorot::model()->updatebypk($edellinenToistuva->id, array(
						'viikko_paivat'=>json_encode($result_vkoPvm)
					));

				}

			}
			//     Jos on Viikkon päivä on lisääntynyt -->



			// <-- Jos Työvuorojen viikkoväli ei sama kun edellisessa
			if(
				$edellinenToistuva->viikkoja != $_POST['ToistuvatTyovuorot']['viikkoja']
				and $edellinenToistuva->pfrom == $_POST['ToistuvatTyovuorot']['pfrom']
				and $edellinenToistuva->pto == $_POST['ToistuvatTyovuorot']['pto']
			)
			{
				//$return[] = array('ERROR'=>'<div class="alert bg-danger">Työvuorojen viikkoväli ei sama kun edellisessa. Suunnittelemassa</div>');


				$updateTyoparia = false;


				$r = array();
				if( $tyopaari_forUpdater == '')
				{
					$r[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						$edellinenToistuva->pfrom, 
						$edellinenToistuva->pto, 
						json_decode($edellinenToistuva->viikko_paivat, true),
						$_POST['ToistuvatTyovuorot']['viikkoja'], 
						$model->tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);
				} else {
				  
  				    foreach(json_decode($tyopaari_forUpdater, true) as $tid)
				    {
					$r[] = $this->toistuvaInsert(
						$edellinenToistuva->id,
						$edellinenToistuva->pfrom, 
						$edellinenToistuva->pto, 
						json_decode($edellinenToistuva->viikko_paivat, true),
						$_POST['ToistuvatTyovuorot']['viikkoja'], 
						$tid, 
						$edellinenToistuva->kohde, 
						$edellinenToistuva->alku, 
						$edellinenToistuva->loppu, 
						$edellinenToistuva->pituus, 
						$edellinenToistuva->tyoajanmerkinta, 
						$edellinenToistuva->tietoja,
						$edellinenToistuva->status,
						$edellinenToistuva->tyopaari,
						$saankoSuoritta
						);

				    }

				}



				// <-- Valmistetaan Ids jotka emme koske
				$ids_otetan_pois = array();
				$lisataan = array();
				foreach($r as $k=>$item)
				{

					foreach($item as $k=>$item2)
					{

						if(isset($item2['tvuoro_id']))
						{
							$lisataan[] = $item2['tvuoro_id'];
						} else {


						    if(isset($item2['onkosama']) and is_array($item2['onkosama']))
						    {
							foreach($item2['onkosama'] as $samat)
							{
								if(isset($samat['id']))
									$ids_otetan_pois[] = $samat['id'];
							}
						    }

						}
					}

				}
				//     Valmistetaan Ids jotka emme koske -->





				$edelliset_tvuoro_ids_pois = array();
				$edelliset_tvuoro_ids_pois = array_values( array_diff($edelliset_tvuoro_ids, $ids_otetan_pois) );
				//$return[] = array('ERROR'=>count($edelliset_tvuoro_ids_pois));


				if( count($edelliset_tvuoro_ids_pois) == 0 )
				{
					$return = $r;

				} else {

				$tvuoro_ids_implode = implode(",", $edelliset_tvuoro_ids_pois);
				$criteria = new CDBcriteria;
				$criteria->condition=" 
					id IN ($tvuoro_ids_implode) 
				";
			  	$t = Tyovuoroot::model()->findAll($criteria);
				$uusiKetju = array();
				$r2 = array();

				    foreach($t as $item)
				    {


					$ketjustaPois[] = $item->id;
					$edelliset_tvuoro_ids = array_values( array_diff($edelliset_tvuoro_ids, $ketjustaPois) );

					if( $saankoSuoritta != 1 )
					{
					    $return[] = array(
						'tid'=>$item->tid, 
						'pvm'=>$item->pvm, 
						'ymd'=>date("Ymd",strtotime($item->pvm)), 
						'isSaved'=>false,
						'poistaminen'=>true, 
						'tekijan_nimi'=>$this->etuSukunimi($item->tid), 
						'vkopvm' => $fi[date("N",strtotime($item->pvm))]
					    );

					} else {

					    $return[] = array(
						'tid'=>$item->tid, 
						'pvm'=>$item->pvm, 
						'ymd'=>date("Ymd",strtotime($item->pvm)), 
						'isSaved'=>true
					    );
					    Tyovuoroot::model()->deleteByPk($item->id);
					}

				    }

				} // if count ids


				if( $saankoSuoritta == 1 )
				{

					$edelliset_tvuoro_ids = array_merge($edelliset_tvuoro_ids, $lisataan);

					//echo json_encode($edelliset_tvuoro_ids);
					//exit;

					ToistuvatTyovuorot::model()->updatebypk($edellinenToistuva->id, array(
						'viikkoja'=>$_POST['ToistuvatTyovuorot']['viikkoja']
					));
				}

			}
			//     Jos Työvuorojen viikkoväli ei sama kun edellisessa -->




			// <-- Updater kaikki
			if( $saankoSuoritta == 1 and is_array($edelliset_tvuoro_ids) and count($edelliset_tvuoro_ids) > 0)
			{

				$tvuoro_ids_implode = implode(",", $edelliset_tvuoro_ids);
				$criteria = new CDBcriteria;
				$criteria->condition=" id IN ($tvuoro_ids_implode) ";

				if($updateTyoparia == true)
				{
					Tyovuoroot::model()->updateAll(array(
						'tyopaari'=>$tyopaari_forUpdater
					), $criteria);

					ToistuvatTyovuorot::model()->updateByPk($edellinenToistuva->id, array(
						'tyopaari'=>$tyopaari_forUpdater,
					));
				}

					ksort($edelliset_tvuoro_ids);
					ToistuvatTyovuorot::model()->updateByPk($edellinenToistuva->id, array(
						'tvuoro_ids'=>json_encode($edelliset_tvuoro_ids)
					));


				if($updateTyovuoroja == true)
				{
					$tvuoro_ids_implode = implode(",", $edelliset_tvuoro_ids);
					$criteria = new CDBcriteria;
					$criteria->condition=" id IN ($tvuoro_ids_implode) ";

					$newPostArr = array(
						'kohde'=>$tv->kohde,
						'alku'=>$tv->alku,
						'loppu'=>$tv->loppu,
						'pituus'=>$tv->pituus,
						'tyoajanmerkinta'=>$tv->tyoajanmerkinta,
						'tietoja'=>$tv->tietoja,
						'status'=>$tv->status,
					);

					Tyovuoroot::model()->updateAll($newPostArr, $criteria);
				  	$t = Tyovuoroot::model()->findAll($criteria);

					foreach($t as $item)
					{

					    $return[] = array(
						'tid'=>$item->tid, 
						'pvm'=>$item->pvm, 
						'ymd'=>date("Ymd",strtotime($item->pvm)), 
						'isSaved'=>true
					    );

					}
				}


			}
			//     Updater kaikki -->



			if( count($return) > 0 )
				echo json_encode($return);
			else
				echo json_encode(array('ERROR'=>'Ei muutoksia'));
			exit;
		}
		//     Toistuva tyovuorot ja tyopaarit -->








		// Jos Toistuva Ruksi oli päällä, alhalla koodi ei luetaan



		if(isset($_POST['Tyovuoroot']) and !isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']))
		{




			// <-- Oliko se toistuvassa tyovuorossa. Poistetaan ketjusta
			if( isset($edellinenToistuva->id) )
			{

				if(!empty($edellinenToistuva->tvuoro_ids) and is_array(json_decode($edellinenToistuva->tvuoro_ids, true)))
				{

					$poistoCriteria = new CDBcriteria;
					$poistoCriteria->condition="
						id!='".$model->id."'
						AND toistuva_id='".$edellinenToistuva->id."'
						AND pvm='".$model->pvm."'
					";
				  	$t = Tyovuoroot::model()->findAll($poistoCriteria);
					$ketjustaPois = array();
					foreach($t as $item)
					{
						$ketjustaPois[] = $item->id;

						$return[] = array(
							'tid'=>$item->tid, 
							'pvm'=>$item->pvm, 
							'ymd'=>date("Ymd",strtotime($item->pvm)),
							'isSaved'=>true
							);
					}
						$ketjustaPois[] = $model->id;

					$tvuoro_ids_Arr = json_decode($edellinenToistuva->tvuoro_ids, true);
					$result = array_values( array_diff($tvuoro_ids_Arr, $ketjustaPois) );
					ToistuvatTyovuorot::model()->updateByPk($edellinenToistuva->id, array('tvuoro_ids'=>json_encode($result) ));
				  	Tyovuoroot::model()->deleteAll($poistoCriteria);
				}
				$_POST['Tyovuoroot']['toistuva_id'] = 0;
			}
			//     Oliko se toistuvassa tyovuorossa. Poistetaan ketjusta -->






			$_POST['Tyovuoroot']['pvm'] = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			// <-- Tyontekijan vaihto
			if( $model->tid != $_POST['Tyovuoroot']['tid'] )
			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			// Tyontekijan vaihto -->

			$model->attributes=$_POST['Tyovuoroot'];

			// <-- Apuaika
			if(isset($_POST['Tyovuoroot']['apuaika']) and $_POST['Tyovuoroot']['apuaika'] == 1)
				$model->apuaika = 1;
			else if(isset($_POST['Tyovuoroot']['apuaika']) and $_POST['Tyovuoroot']['apuaika'] != 1)
				$model->apuaika = 0;
			//     Apuaika -->


			if($model->save()){

				// <-- Onko tyopari esitetty
				$post_tyopaari = array();
				if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
				$post_tyopaari = $_POST['tyopaari'];
				// Onko tyopari esitetty -->
	

				// <-- Vanhat
				$vanhat = json_decode($model->tyopaari, true);
				if(is_array($vanhat))
				{

				   foreach($vanhat as $tyovuoroID=>$tid)
				   {
					if(isset($tyovuoroID) and !empty($tyovuoroID) and $tyovuoroID!=$model->id )
					{
						$m = Tyovuoroot::model()->findByPk($tyovuoroID);
						if(isset($m->id))
						{
							$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));
							Tyovuoroot::model()->deleteByPk($m->id);
						}
					}	
				   }
				}
				// Vanhat -->


				if(count($post_tyopaari) == 0)
				Tyovuoroot::model()->updatebypk($model->id, array('tyopaari' => ''));


				// <-- jos on tyopaari
				if(count($post_tyopaari) > 0)
				{
	
				$luotu = array();
				$arr = array();
				$luotu[$model->id] = $model->tid;
				$arr[$model->id] = array($model->tid,$model->pvm);
	
				    foreach($_POST['tyopaari'] as $tid)
				    {
					$m=new Tyovuoroot;
					$m->attributes=$_POST['Tyovuoroot'];
					$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
					$m->tid=$tid;
					if($m->save())
					{
						$luotu[$m->id] = $m->tid;
				    		$arr[$m->id] = array($m->tid,$m->pvm);
					}
				    }
	
	
				    foreach($arr as $k=>$v)
				    {
					Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));
					$return[] = array('tid'=>$v[0], 'pvm'=>$v[1], 'ymd'=>date("Ymd",strtotime($v[1])));
		
					// <-- PushNotify
					if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
					$this->pushNotifySending($k);
					// PushNotify -->
				    }
	
				}
				// jos on tyopaari -->
	
	


				if(count($post_tyopaari) == 0 and $model->toistuva_id == 0)
				{
	
					// <-- PushNotify
					if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
					$this->pushNotifySending($model->id);
					// PushNotify -->

				}

			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			echo json_encode($return);

			} // model save 

			exit;
		}





		$criteria = new CDBcriteria;
		$criteria->order="tekijan_nimi";
		$criteria->condition="aktiivinen=1";
	  	$t = Tyontekijat::model()->findAll($criteria);
		$tekijan_nimi = '<select id="tekijanVaihdo" class="form-control">';
		if(count($t) > 0)
		{
		   if($model->tid == 0)
		   $tekijan_nimi .= '<option value="'.$model->id.'">'.Yii::t('main', 'Valitse').'</option>';

		   foreach($t as $tekijanData)
		   {
			if($tekijanData->id == $model->tid)
			$tekijan_nimi .= '<option value="'.$tekijanData->id.'" selected>'.$this->etuSukunimi($tekijanData->id).'</option>';
			else
			$tekijan_nimi .= '<option value="'.$tekijanData->id.'">'.$this->etuSukunimi($tekijanData->id).'</option>';
		   }
		}
		$tekijan_nimi .= '</select>';

	?>


        <!-- Admin Form Popup -->
        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"><i class="fa fa-clock-o"></i> 
		<?php echo Yii::t('main', 'Työvuoron suunnittelu').': '.$tekijan_nimi; ?>
	      </span>
            </div>
            <!-- end .panel-heading section -->

              <div class="panel-body p25">
		<?php
		$this->renderPartial('_form',array(
			'model'=>$model,
		));
		?>
              </div>
              <!-- end .form-body section -->


          </div>
          <!-- end: .panel -->
        </div>
        <!-- end: .admin-form -->

	<?php
	}



	protected function pushNotifySending($tv_id)
	{
		$m = Tyovuoroot::model()->findByPk($tv_id);
		$t = Tyontekijat::model()->findbypk($m->tid);
		$k = Kohteet::model()->findbypk($m->kohde);
		if(isset($k->osoite) and !empty($k->osoite) and isset($t->id))
		{
			$pushviesti = "Työvuorosi on muuttunut. Alta löydät uudet tiedot:\n
				".$m->pvm."
				".$m->alku."-".$m->loppu." ".$k->osoite."
				".$m->tietoja;

			Domainit::sendGCM($t->id,"Hei ".$t->tekijan_nimi,$pushviesti, null);
		}
	}

	public function actionUusitilaus()
	{

	if(!isset($_POST['Tyovuoroot']))
	{
	?>

        <!-- Admin Form Popup -->
        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"><i class="fa fa-clock-o"></i> 

		<?php echo Yii::t('main', 'Uusi tilaus'); ?>
	      </span>
            </div>
            <!-- end .panel-heading section -->

              <div class="panel-body p25">
	<?php
	}

		$model=new Tyovuoroot;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$return = array();

		if(isset($_POST['Tyovuoroot']))
		{


		$asiakkaat = new Asiakkaat;
		$asiakkaat->attributes = $_POST['Asiakkaat'];
		$asiakkaat->aktiivinen = 1;

		  if($asiakkaat->save())
		  {
			Asiakkaat::model()->updateByPk($asiakkaat->id, array( 'asiakasnumero' => $asiakkaat->id ));


			$kohteet = new Kohteet;
			$kohteet->asiakas_id = $asiakkaat->id;
			$kohteet->uusi_tilaus = 1;

			if(!empty($asiakkaat->yrityksen_nimi))
				$kohteet->etu_suku_nimet =$asiakkaat->yrityksen_nimi;
			elseif(empty($asiakkaat->yrityksen_nimi) and !empty($asiakkaat->yhteyshenkilo))
				$kohteet->etu_suku_nimet = $asiakkaat->yhteyshenkilo;

			if($_POST['onkoAsOsoiteSamaKunKohde'] == 'ei')
				$kohteet->osoite = $_POST['kohteenOsoite'];
			else
				$kohteet->osoite = $asiakkaat->osoite;

			$kohteet->puh_nro = $asiakkaat->puhelin;
			$kohteet->pnumero = $asiakkaat->postinumero;
			$kohteet->kaupunki = $asiakkaat->kaupunki;
			$kohteet->email = $asiakkaat->sahkoposti;

			$toimenpiteet = $_POST['Tyovuoroot']['toimenpiteet'];
			if(isset($_POST['onkoKokeilusiivous']) and !empty($_POST['onkoKokeilusiivous']))
			$toimenpiteet .= "\n".Yii::t('main', 'Onko kokeilusiivous').": ".$_POST['onkoKokeilusiivous']."\n";
			if(isset($_POST['oven_avaaminen']) and !empty($_POST['oven_avaaminen']))
			$toimenpiteet .= "\n".Yii::t('main', 'Oven avaaminen').": ".$_POST['oven_avaaminen']."\n";
			if(isset($_POST['mihin_avain_palautetaan']) and !empty($_POST['mihin_avain_palautetaan']))
			$toimenpiteet .= "\n".Yii::t('main', 'Mihin avain palautetaan').": ".$_POST['mihin_avain_palautetaan']."\n";
			if(isset($_POST['mihin_pysakoida_auto']) and !empty($_POST['mihin_pysakoida_auto']))
			$toimenpiteet .= "\n".Yii::t('main', 'Mihin työntekijä voi pysäköidä auton').": ".$_POST['mihin_pysakoida_auto']."\n";
			if(isset($_POST['onkoMaksajanTiedotSama']) and $_POST['onkoMaksajanTiedotSama'] == 'Ei'){
			$toimenpiteet .= "\n".Yii::t('main', 'Maksajan tiedot sama kuin tilaaja').": ".$_POST['onkoMaksajanTiedotSama']."\n";
			$toimenpiteet .= Yii::t('main', 'Maksajan tiedot').": ".$_POST['MaksajanTiedot']."\n";
			}
			if(isset($_POST['LahjakortinNumero']) and !empty($_POST['LahjakortinNumero']))
			$toimenpiteet .= "\n".Yii::t('main', 'Lahjakortin numero').": ".$_POST['LahjakortinNumero']."\n";



			$kohteet->toimenpiteet = $toimenpiteet;

			$kohteet->muut = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']))."\n".date("H:i",strtotime($_POST['Tyovuoroot']['alku']))."-".date("H:i",strtotime($_POST['Tyovuoroot']['loppu']))."\nHinta: ".$asiakkaat->hinta;

		  	   if($kohteet->save())
		  	   {
				$model->attributes=$_POST['Tyovuoroot'];
				$model->kohde = $kohteet->id;
				$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				if($model->save())
				{
				

			// <-- jos on tyopaari
			$luotu = array();
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    $luotu[$model->id] = $model->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$m=new Tyovuoroot;
				$m->attributes=$_POST['Tyovuoroot'];
				$m->kohde = $kohteet->id;
				$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				$m->tid=$tid;
				$m->status=3;
				if($m->save())
				{
					$luotu[$m->id] = $m->tid;
					$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));

				}

			    }
			    foreach($luotu as $k=>$v)
					Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));


			}
			// jos on tyopaari -->

				$sum = 0;
				$alv_0 = 0;
				$alv_sum = 0;
				   if(isset($_POST['vieposti']) and isset($asiakkaat->sahkoposti) and !empty($asiakkaat->sahkoposti))
				   {
					$message = '
					Asiakas: '.$asiakkaat->yhteyshenkilo.'<br>
					Työvuorot:  '.$model->pvm.', '.$model->alku.'-'.$model->loppu.'<br>';

					if(!empty($asiakkaat->hinta) and $asiakkaat->hinta_tyyppi == 1)
					{
						$tuntia = ((strtotime($model->loppu)-strtotime($model->alku))/3600);
						if( count($luotu) > 0 )
						$tuntia = $tuntia * count($luotu);

						$sum = ($asiakkaat->hinta*$tuntia) + ((($asiakkaat->hinta*$asiakkaat->alv)/100)*$tuntia);
						$alv_0 = $asiakkaat->hinta*$tuntia;
						$alv_sum = $sum-$alv_0;

						$message .= 'Hinta ALV 0: '.number_format($alv_0, 2, ',', ' ').' &euro;<br>';
						$message .= 'ALV: '.number_format($alv_sum, 2, ',', ' ').' &euro;<br>';
						$message .= 'Hinta: '.number_format($sum, 2, ',', ' ').' &euro;<br>';
					}

					if(!empty($kohteet->toimenpiteet))
					$message .= str_replace("\n", "<br>",$kohteet->toimenpiteet);

					$message .= '<h2>Kiitos tilauksesta.</h2>';
					$subject = Yii::t('main', 'Kiitos tilauksesta');

					$ft = FirmanTiedot::model()->findByPk(1);
					$mail = new YiiMailer();
					//$mail->clearLayout();//if layout is already set in config
					$mail->setFrom('no-reply@etunti.fi');
					$mail->setTo($asiakkaat->sahkoposti);
					$mail->setSubject($subject);
					$mail->setBody($message);

					foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/toimitusehdot*')) as $file) {
						$mail->setAttachment($file);
						//break;
					}

					if($mail->send())
					{


							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $asiakkaat->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->log_nimike	= 'uusi_tilaus';
							$log->save();
							//     LOG -->
					}
				   }
				
					$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)), 'alv'=>$alv_sum, 'alv_0' => $alv_0, 'sum' => $sum);
				  	echo json_encode($return);
					exit;
				}
			   } else { // Kohde save error

			  	echo json_encode($kohteet->getErrors());
			  	exit;

			   }


		  } else { // Asiakas save error

		  	echo json_encode($asiakkaat->getErrors());
		  	exit;

		  }

		  echo json_encode('Error');
		  exit;
		}

	if(!isset($_POST['Tyovuoroot']))
	{
		$this->renderPartial('uusitilaus',array(
			'model'=>$model,
		));
	?>
          </div>
          <!-- end: .panel -->
        </div>
        <!-- end: .admin-form -->
	<?php
	}
	}


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
		$kohteet_siivous = array();

		// <-- Reset
		if(isset($_GET['reset']))
		{
			unset(Yii::app()->session['year']);
			unset(Yii::app()->session['week']);
			unset(Yii::app()->session['vkolopput']);
			unset(Yii::app()->session['asiakas']);
			unset(Yii::app()->session['kohde']);
			unset(Yii::app()->session['tyontekijat']);
			unset(Yii::app()->session['tyo_toimialue']);
			unset(Yii::app()->session['kohteiden_tyonimike']);
			unset(Yii::app()->session['tyoryhma']);

			$this->redirect(array('index'));
		}
		//     Reset -->


		// <-- GET haku
		if(isset($_GET['year']) or isset($_GET['week']))
		{

			if(isset($_GET['year']) and !empty($_GET['year']))
				Yii::app()->session['year'] = $_GET['year'];
			
			if(isset($_GET['week']) and !empty($_GET['week']))
				Yii::app()->session['week'] = $_GET['week'];

			if(isset($_GET['tid']) and !empty($_GET['tid']))
				Yii::app()->session['tyontekijat'] = array($_GET['tid']);


			$this->redirect(array('index'));
		}		
		//  GET haku -->


		// <-- Post haku
		if(isset($_POST['haku']))
		{

			if(isset($_POST['kohteiden_tyonimike']) and !empty($_POST['kohteiden_tyonimike']))
				Yii::app()->session['kohteiden_tyonimike'] = $_POST['kohteiden_tyonimike'];
			if(isset($_POST['kohteiden_tyonimike']) and empty($_POST['kohteiden_tyonimike']))
				unset(Yii::app()->session['kohteiden_tyonimike']);

			if(isset($_POST['tyo_toimialue']) and !empty($_POST['tyo_toimialue']))
				Yii::app()->session['tyo_toimialue'] = $_POST['tyo_toimialue'];
			if(!isset($_POST['tyo_toimialue']))
				unset(Yii::app()->session['tyo_toimialue']);

			if(isset($_POST['tyoryhma']) and !empty($_POST['tyoryhma']))
				Yii::app()->session['tyoryhma'] = $_POST['tyoryhma'];
			if(!isset($_POST['tyoryhma']))
				unset(Yii::app()->session['tyoryhma']);

			// <-- Asiakas
			if(isset($_POST['asiakas']) and !empty($_POST['asiakas']))
				Yii::app()->session['asiakas'] = $_POST['asiakas'];
			if(isset($_POST['asiakas']) and empty($_POST['asiakas']))
				unset(Yii::app()->session['asiakas']);
			// Asiakas -->
	
			// <-- Kohde
			if(isset($_POST['kohde']) and !empty($_POST['kohde']))
				Yii::app()->session['kohde'] = $_POST['kohde'];
			if(isset($_POST['kohde']) and empty($_POST['kohde']))
				unset(Yii::app()->session['kohde']);
			// Kohde -->
	
			// <-- tyontekijat
			if(isset($_POST['tyontekijat']) and !empty($_POST['tyontekijat']))
				Yii::app()->session['tyontekijat'] = $_POST['tyontekijat'];
			if(!isset($_POST['tyontekijat']))
				unset(Yii::app()->session['tyontekijat']);
			//  tyontekijat -->

			if(isset($_POST['year']) and !empty($_POST['year']))
				Yii::app()->session['year'] = $_POST['year'];
			
			if(isset($_POST['week']) and !empty($_POST['week']))
				Yii::app()->session['week'] = $_POST['week'];





			$this->redirect(array('index'));
		}		
		//  Post haku -->


		// <-- Year Week
		if(!isset(Yii::app()->session['year']))
			Yii::app()->session['year'] = date("Y");

		if(!isset(Yii::app()->session['week']))
			Yii::app()->session['week'] = date("W");

		$year = Yii::app()->session['year'];
		$week = sprintf("%02d", Yii::app()->session['week']);
		Yii::app()->session['week'] = $week;
		//    Year Week -->


		if(!isset(Yii::app()->session['vkolopput']))
			$numDays = 5;
		else
			$numDays = 7;


		Yii::app()->session['from'] = date("Y-m-d", strtotime($year ."W". $week.'1'));
		Yii::app()->session['to'] = date("Y-m-d", strtotime($year ."W". $week . $numDays));


       		$criteria = new CDbCriteria();

		// <-- Oletus arvot
		if(!isset(Yii::app()->session['tyontekijat']))
		{
        		$criteria->order = "id DESC LIMIT 5";
	        	$criteria->select = "id,tekijan_nimi";
	        	$criteria->condition = " aktiivinen = '1' ";
			$tt = Tyontekijat::model()->findAll($criteria);
			$tekijatOletuksena = array();
			foreach($tt as $t)
			$tekijatOletuksena[] = $t->id;
	
			Yii::app()->session['tyontekijat'] = $tekijatOletuksena;
		}
		// Oletus arvot -->



		if(Yii::app()->session['tyontekijat'])
		{

		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

        		$criteria->select = "id,tekijan_nimi, tyoryhma";
        		$criteria->condition = " aktiivinen = '1' ";

		    	if(count(Yii::app()->session['tyontekijat'] > 1))
		      	$ids = implode(",", Yii::app()->session['tyontekijat']);
		    	else
		      	$ids = Yii::app()->session['tyontekijat'][0];


	        	$criteria->addCondition ('id IN ('.$ids.') ');
		}


		// <-- kohteiden_tyonimike
		if(isset(Yii::app()->session['kohteiden_tyonimike']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%'
		       )
		   )
		   ");

			$criteriaK = new CDbCriteria();
	       		$criteriaK->select = "id";
	       		$criteriaK->condition = " 
				siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%' 
				AND id IN(
					SELECT kohde FROM sivex_tvuoro 
					WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     			BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
				)
			";
			$k = Kohteet::model()->findAll($criteriaK);
			foreach($k as $kohde)
			 $kohteet_siivous[] = $kohde->id;

		}
		//   kohteiden_tyonimike -->


		// <-- tyo_toimialue
		if(isset(Yii::app()->session['tyo_toimialue']))
		{

		   $arr = array();
		   foreach(Yii::app()->session['tyo_toimialue'] as $it)
		   {
			$arr[] = str_replace("\\", "\\\\\\\\", json_encode($it));
		   }

		   $tyo_toimialue_like = "tyo_toimialue LIKE '%".implode("%' OR tyo_toimialue LIKE '%", $arr)."%'";
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		   )
		   AND ($tyo_toimialue_like)
		   ");
		}
		//   tyo_toimialue -->

		// <-- tyoryhma
		if(isset(Yii::app()->session['tyoryhma']))
		{

		   $arr = array();
		   foreach(Yii::app()->session['tyoryhma'] as $it)
		   {
			$arr[] = str_replace("\\", "\\\\\\\\", json_encode($it));
		   }

		   $tyoryhma_like = " tyoryhma LIKE '%".implode("%' OR tyoryhma LIKE '%", $arr)."%'";
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		   )
		   AND ($tyoryhma_like)
		   ");

		}
		//   tyoryhma -->

		// <-- Asiakas
		if(isset(Yii::app()->session['asiakas']))

		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE asiakas_id IN
   			    (
			       SELECT id FROM asiakkaat WHERE yrityksen_nimi 
					LIKE '%".Yii::app()->session['asiakas']."%' 
					OR yhteyshenkilo LIKE '%".Yii::app()->session['asiakas']."%' 
					OR puhelin LIKE '%".Yii::app()->session['asiakas']."%'
			    )
		       )
		   )
		   ");
		}
		// Asiakas -->

		// <-- Kohde
		if(isset(Yii::app()->session['kohde']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE osoite 
					LIKE '%".Yii::app()->session['kohde']."%' 
					OR puh_nro LIKE '%".Yii::app()->session['kohde']."%'
		       )
		   )
		   ");
		}
		//  Kohde -->

		$tyontekijat_model = Tyontekijat::model()->findAll($criteria);

		(isset(Yii::app()->session['asiakas'])) ? 	$asiakas = Yii::app()->session['asiakas'] : $asiakas ='';
		(isset(Yii::app()->session['kohde'])) ? 	$kohde = Yii::app()->session['kohde'] : $kohde ='';

		$this->render('index', array(
			'tyontekijat_model'	=>$tyontekijat_model,
			'tyontekijat'		=>Yii::app()->session['tyontekijat'],
			'year'			=>$year,
			'week'			=>$week,
			'numDays'		=>$numDays,
			'kohteet_siivous'	=>$kohteet_siivous,
			'asiakas'		=>$asiakas,
			'kohde'			=>$kohde,
			//'wkMaara'		=>$wkMaara,
		));

	}

	public function actionTv2()
	{
		$this->poistaminenOnlineVarauksetJokaMeniOhi();

		$kohteet_siivous = array();

		// <-- Reset
		if(isset($_GET['reset']))
		{
			unset(Yii::app()->session['from']);
			unset(Yii::app()->session['to']);
			unset(Yii::app()->session['asiakas']);
			unset(Yii::app()->session['kohde']);
			unset(Yii::app()->session['tyontekijat']);
			unset(Yii::app()->session['tyo_toimialue']);
			unset(Yii::app()->session['kohteiden_tyonimike']);
			unset(Yii::app()->session['tyoryhma']);

			$this->redirect(array('tv2'));
		}
		//     Reset -->

		// <-- Post haku
		if(isset($_POST['haku']))
		{

			if(isset($_POST['kohteiden_tyonimike']) and !empty($_POST['kohteiden_tyonimike']))
				Yii::app()->session['kohteiden_tyonimike'] = $_POST['kohteiden_tyonimike'];
			if(isset($_POST['kohteiden_tyonimike']) and empty($_POST['kohteiden_tyonimike']))
				unset(Yii::app()->session['kohteiden_tyonimike']);

			if(isset($_POST['tyo_toimialue']) and !empty($_POST['tyo_toimialue']))
				Yii::app()->session['tyo_toimialue'] = $_POST['tyo_toimialue'];
			if(!isset($_POST['tyo_toimialue']))
				unset(Yii::app()->session['tyo_toimialue']);

			if(isset($_POST['tyoryhma']) and !empty($_POST['tyoryhma']))
				Yii::app()->session['tyoryhma'] = $_POST['tyoryhma'];
			if(!isset($_POST['tyoryhma']))
				unset(Yii::app()->session['tyoryhma']);

			// <-- Asiakas
			if(isset($_POST['asiakas']) and !empty($_POST['asiakas']))

				Yii::app()->session['asiakas'] = $_POST['asiakas'];
			if(isset($_POST['asiakas']) and empty($_POST['asiakas']))
				unset(Yii::app()->session['asiakas']);
			// Asiakas -->
	
			// <-- Kohde
			if(isset($_POST['kohde']) and !empty($_POST['kohde']))
				Yii::app()->session['kohde'] = $_POST['kohde'];
			if(isset($_POST['kohde']) and empty($_POST['kohde']))
				unset(Yii::app()->session['kohde']);
			// Kohde -->
	
			// <-- tyontekijat
			if(isset($_POST['tyontekijat']) and !empty($_POST['tyontekijat']))
				Yii::app()->session['tyontekijat'] = $_POST['tyontekijat'];
			if(!isset($_POST['tyontekijat']))
				unset(Yii::app()->session['tyontekijat']);
			//  tyontekijat -->

			if(isset($_POST['from']) and !empty($_POST['from']))
				Yii::app()->session['from'] = date("Y-m-d",strtotime($_POST['from']));
	
			if(isset($_POST['to']) and !empty($_POST['to']))
				Yii::app()->session['to'] = date("Y-m-d",strtotime($_POST['to']));


			$this->redirect(array('tv2'));
		}		
		//  Post haku -->


		if(!isset(Yii::app()->session['from']))
			Yii::app()->session['from'] = date("Y-m-d");
		if(!isset(Yii::app()->session['to']))
			Yii::app()->session['to'] = date("Y-m-d",strtotime("+1 month", time()));



		$asetukset = Asetukset::model()->findByPk(1);
       		$criteria = new CDbCriteria();

		// <-- Oletus arvot
		if(!isset(Yii::app()->session['tyontekijat']))
		{

			if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0)
				$criteria->order = " tekijan_nimi,id DESC LIMIT 5 ";
			else
				$criteria->order = " sukunimi,id DESC LIMIT 5 ";


	        	$criteria->select = "id,tekijan_nimi";
	        	$criteria->condition = " aktiivinen = '1' ";
			$tt = Tyontekijat::model()->findAll($criteria);
			$tekijatOletuksena = array();
			foreach($tt as $t)
			$tekijatOletuksena[] = $t->id;
	
			Yii::app()->session['tyontekijat'] = $tekijatOletuksena;
		}
		// Oletus arvot -->


		if(Yii::app()->session['tyontekijat'])
		{

			if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0)
				$criteria->order = " tekijan_nimi ";
			else
				$criteria->order = " sukunimi ";


        		$criteria->select = "id,tekijan_nimi";
        		$criteria->condition = " aktiivinen = '1' ";

		    	if(count(Yii::app()->session['tyontekijat'] > 1))
		      	$ids = implode(",", Yii::app()->session['tyontekijat']);
		    	else
		      	$ids = Yii::app()->session['tyontekijat'][0];

	        	$criteria->addCondition ('id IN ('.$ids.') ');
		}



		// <-- kohteiden_tyonimike
		if(isset(Yii::app()->session['kohteiden_tyonimike']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%'
		       )
		   )
		   ");

			$criteriaK = new CDbCriteria();
	       		$criteriaK->select = "id";
	       		$criteriaK->condition = " 
				siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%' 
				AND id IN(
					SELECT kohde FROM sivex_tvuoro 
					WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     			BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
				)
			";
			$k = Kohteet::model()->findAll($criteriaK);
			foreach($k as $kohde)
			 $kohteet_siivous[] = $kohde->id;

		}
		//   kohteiden_tyonimike -->

		// <-- tyo_toimialue
		if(isset(Yii::app()->session['tyo_toimialue']))
		{

		   $tyo_toimialue_like = "tyo_toimialue LIKE '%".implode("%' OR tyo_toimialue LIKE '%", Yii::app()->session['tyo_toimialue'])."%'";
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND tid IN 
		       (
			    SELECT id FROM sivex_ttekijat WHERE $tyo_toimialue_like
		       )
		   )
		   ");
		}
		//   tyo_toimialue -->

		// <-- tyoryhma
		if(isset(Yii::app()->session['tyoryhma']))
		{

		   $tyoryhma_like = "tyoryhma LIKE '%".implode("%' OR tyoryhma LIKE '%", Yii::app()->session['tyoryhma'])."%'";
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND tid IN 
		       (
			    SELECT id FROM sivex_ttekijat WHERE $tyoryhma_like
		       )
		   )
		   ");
		}
		//   tyoryhma -->

		// <-- Asiakas
		if(isset(Yii::app()->session['asiakas']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE asiakas_id IN
   			    (
			       SELECT id FROM asiakkaat WHERE yrityksen_nimi 
					LIKE '%".Yii::app()->session['asiakas']."%' 
					OR yhteyshenkilo LIKE '%".Yii::app()->session['asiakas']."%' 
					OR puhelin LIKE '%".Yii::app()->session['asiakas']."%'
			    )
		       )
		   )
		   ");
		}
		// Asiakas -->

		// <-- Kohde
		if(isset(Yii::app()->session['kohde']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE osoite 
					LIKE '%".Yii::app()->session['kohde']."%' 
					OR puh_nro LIKE '%".Yii::app()->session['kohde']."%'
		       )
		   )
		   ");
		}
		//  Kohde -->

		$tyontekijat_model = Tyontekijat::model()->findAll($criteria);


		(isset(Yii::app()->session['asiakas'])) ? 	$asiakas = Yii::app()->session['asiakas'] : $asiakas ='';
		(isset(Yii::app()->session['kohde'])) ? 	$kohde = Yii::app()->session['kohde'] : $kohde ='';

		$this->render('tv2', array(
			'tyontekijat_model'	=>$tyontekijat_model,
			'from'			=>Yii::app()->session['from'],
			'to'			=>Yii::app()->session['to'],
			'tyontekijat'		=>Yii::app()->session['tyontekijat'],
			'kohteet_siivous'	=>$kohteet_siivous,
			'asiakas'		=>$asiakas,
			'kohde'			=>$kohde,
		));
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
	$asetukset = AsetuksetForAll::model()->findbypk(1);
	$pyh = explode("\n",$asetukset->viralliset_pyhapaivat);

	if(
	   date("N",strtotime($date)) == 6 
	   or date("N",strtotime($date)) == 7
	   or strstr($asetukset->viralliset_pyhapaivat, $dateMonth)
	)
	return true;
	else
	return false;

 	}

	public function tilanteet()
	{
        	$l = array(
			3=>Yii::t('main', 'Työ'),
			2=>Yii::t('main', 'Matka'),
			10=>Yii::t('main', 'Lounastauko')
		);
		return $l;
	}


	protected function toistuvaInsert($id, $pfrom, $pto, $p, $viikkoja, $tid, $kohde, $alku, $loppu, $pituus, $tyoajanmerkinta, $tietoja, $status, $tyopaari, $saankoSuoritta)
	{

		$fi = $this->vkoPaivat();



		$startDate	= $pfrom;
		$end_date	= $pto;
		$date		= $startDate;

		$var		= 1;

		if($viikkoja == 1)
			$var	= 0;

		$w		= $p;
		$v 		= $viikkoja;
		$weeksArr = array();
 		while (strtotime($date) <= strtotime($end_date)) {

			$viikonNumero = (date('W',strtotime($date)));
		  	$weeksArr[$viikonNumero] = $viikonNumero;
	                $date = date ("d.m.Y", strtotime("+1 day", strtotime($date)));
		}

		$i = 1;
		$sopivaViikot = array();
		foreach($weeksArr as $k=>$result)
		{
		    if($i % $viikkoja === $var) {
		        $sopivaViikot[$result] = $result;
		    }
		    $i++;
		}

		$date		= $startDate;
		$end_date	= $end_date;
		$return 	= array();
		$tyopaariUpdater = array();
 		while (strtotime($date) <= strtotime($end_date)) {

			$viikonNumero = (date('W',strtotime($date)));

	                if( 
				in_array(date('N',strtotime($date)),$w) 
				and in_array($viikonNumero,$sopivaViikot) 
			)
			{
				$pvm = $date;
				//$return[] = array('tid'=>$tid, 'pvm'=>$pvm, 'ymd'=>date("Ymd",strtotime($pvm)));
				$onkosama = $this->onko_sama(null, $pvm, $tid, $kohde, $alku, $loppu);
				$tt = Tyontekijat::model()->findByPk($tid);

				$tekijan_nimi='';
				if(isset($tt->tekijan_nimi) and $tid!=0)
					$tekijan_nimi=$tt->tekijan_nimi;
				elseif(!isset($tt->tekijan_nimi) and $tid==0)
					$tekijan_nimi='VARAUS';

				if(
					isset($_POST['ToistuvatTyovuorot']['toistuva_repair']) 
					and $_POST['ToistuvatTyovuorot']['toistuva_repair'] == 'on')
				{
					$repair = true;
					if($saankoSuoritta == 1){ $onkosama = ''; }
				} else {
					$repair = false;
				}

				if(empty($onkosama))
				{			

					$t = new Tyovuoroot;
					$t->tid = $tid;
					$t->kohde = $kohde;
					$t->pvm = $pvm;
					$t->alku = $alku;
					$t->loppu = $loppu;
					$t->pituus = $pituus;
					$t->tyoajanmerkinta = $tyoajanmerkinta;
					$t->tietoja = $tietoja;
					$t->status = $status;
					$t->tyopaari = $tyopaari;
					$t->toistuva_id = $id;
					if($saankoSuoritta == 1)
					{
						if($t->save())
						{
							$return[] = array(
								'tid'=>$t->tid, 
								'pvm'=>$t->pvm, 
								'ymd'=>date("Ymd",strtotime($t->pvm)), 
								'isSaved'=>true, 
								'tvuoro_id'=>$t->id, 
								'uusi'=>true
							);

						} else {
							$return[] = array('ERROR'=>json_encode(var_dump($t->getErrors())));
						}

					} else {

						if($repair)
						{
							$return[] = array(
								'tid'=>$tid, 
								'pvm'=>$pvm, 
								'ymd'=>date("Ymd",strtotime($pvm)), 
								'isSaved'=>false, 'tekijan_nimi'=>$tekijan_nimi, 
								'vkopvm' => $fi[date("N",strtotime($pvm))], 
								'uusi_repair'=>true 
							);
						} else {
							$return[] = array(
								'tid'=>$tid, 
								'pvm'=>$pvm, 
								'ymd'=>date("Ymd",strtotime($pvm)), 
								'isSaved'=>false, 'tekijan_nimi'=>$tekijan_nimi, 
								'vkopvm' => $fi[date("N",strtotime($pvm))], 
								'uusi'=>true 
							);
						}
					}

				} else {

					if($repair)
					{
						$return[] = array(
							'tid'=>$tid, 
							'pvm'=>$pvm, 
							'ymd'=>date("Ymd",strtotime($pvm)),
							'onkosama_repair'=>$onkosama, 
							'isSaved'=>false, 
							'tekijan_nimi'=>$tekijan_nimi, 
							'vkopvm' => $fi[date("N",strtotime($pvm))] 
						);
					} else {
						$return[] = array(
							'tid'=>$tid, 
							'pvm'=>$pvm, 
							'ymd'=>date("Ymd",strtotime($pvm)),
							'onkosama'=>$onkosama, 
							'isSaved'=>false, 
							'tekijan_nimi'=>$tekijan_nimi, 
							'vkopvm' => $fi[date("N",strtotime($pvm))] 
						);
					}
				}

			}
			//$return[] = array('tid'=>$tid, 'pvm'=>$date, 'ymd'=>date("Ymd",strtotime($date)));
	                $date = date ("d.m.Y", strtotime("+1 day", strtotime($date)));
		}

		return $return;

	}

	protected function vkoPaivat(){

		$arr = array(
		    1=>'Maanantai',
		    2=>'Tiistai',
		    3=>'Keskiviikko',
		    4=>'Torstai',
		    5=>'Perjantai',
		    6=>'Lauantai',
		    7=>'Sunnuntai',
		);
		return $arr;
	}

	public function actionAsiakas_autocomplete($key)
	{

		$criteria=new CDbCriteria;
		$criteria->order =" yrityksen_nimi!='' DESC,yhteyshenkilo!='' DESC";
		$criteria->condition =" 
			aktiivinen=1 
			AND (yrityksen_nimi LIKE '%".$key."%' OR yhteyshenkilo LIKE '%".$key."%' OR osoite LIKE '%".$key."%' )	
		";

 		$as = Asiakkaat::model()->findAll($criteria);
		$nm = array();
		$return = '';
		$return .= '
			<div class="row" style="position:absolute; z-index:9999999;margin-left:0px">
			  <div class="list-group">';

		if( count($as) > 0 )
		{
			foreach($as as $a)
			{
				if(!empty($a->yrityksen_nimi))
				$nm = array($a->yrityksen_nimi, $a->id);
				elseif(!empty($a->yhteyshenkilo))
				$nm = array($a->yhteyshenkilo, $a->id);
				else
				$nm = array($a->osoite, $a->id);
				
				$return .= '<a href="#" class="list-group-item asiakasSelecter" for="'.$nm[1].'">'.$nm[0].'</a>';
			}
		}

		$criteria=new CDbCriteria;
		$criteria->order =" etu_suku_nimet!='' DESC,etu_suku_nimet!='' DESC";
		$criteria->condition =" 
			aktiivinen=1 
			AND etu_suku_nimet LIKE '%".$key."%'	
		";

 		$k = Kohteet::model()->findAll($criteria);
		if( count($k) > 0 )
		{
		$return .= '<a href="#" class="list-group-item bg-warning"><h4 style="color:white">'.Yii::t('main', 'Kohteen yhteyshenkilöt').'</h4></a>';

			foreach($k as $item)
			{
				$return .= '<a href="#" class="list-group-item kohteenSelecter bg-warning" style="color:white" for="'.$item->id.'">'.$item->etu_suku_nimet.', '.$item->osoite.'</a>';
			}
		}
		$return .='</div></div>';

		if( count($as) > 0 or count($k) > 0 )
			echo json_encode($return);
		else
			echo json_encode('');
	}


	public function actionKohde_autocomplete($key)
	{

		$criteria=new CDbCriteria;
		$criteria->order =" osoite!='' DESC, osoite ASC";
		$criteria->condition =" 
			osoite LIKE '%".$key."%'	
		";

 		$as = Kohteet::model()->findAll($criteria);
		$nm = array();
		$return = '';
		if( count($as) > 0 )
		{

		$return .= '
			<div class="row" style="position:absolute; z-index:9999999;margin-left:0px">
			  <div class="list-group">';
			foreach($as as $a)
			{
				if(!empty($a->osoite))	
				$return .= '<a href="#" class="list-group-item kohdeSelecter" for="'.$a->id.'">'.$a->osoite.'</a>';
			}
			$return .='</div></div>';
		}





		echo json_encode($return);

	}


	public function previousNextWeeks($year,$week)
	{

		$previousWeek 	= date("W",strtotime($year ."W". $week.' -1 week'));

		if($previousWeek == '01') 
			$previousYear = $year;
		else
			$previousYear	= date("Y",strtotime($year ."W". $week.' -1 week'));

		$nextWeek 	= date("W",strtotime($year ."W". $week.' +1 week'));

		if($nextWeek == '01') 
			$nextYear = $year+1;
		else
			$nextYear 	= date("Y",strtotime($year ."W". $week.' +1 week'));

		$arr = array(
			'previousWeek' 	=> $previousWeek,
			'previousYear' 	=> $previousYear,
			'nextWeek' 	=> $nextWeek,
			'nextYear' 	=> $nextYear,
		);
		return $arr;
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

	protected function peruutettuArray()
	{
		$list = array(
		1 => Yii::t('main', 'Peruutettu'), 
		2 => Yii::t('main', 'Peruutettu laskutettava')
		);
		return $list;
	}

	public function actionHallinta()
	{

		if(Yii::app()->request->getPost('tekijaPaaSivulla'))
		Yii::app()->session['tekijaPaaSivulla'] = Yii::app()->request->getPost('tekijaPaaSivulla');
		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = Yii::app()->request->getPost('from');
		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = Yii::app()->request->getPost('to');
		if(Yii::app()->request->getPost('status'))
		Yii::app()->session['status'] = Yii::app()->request->getPost('status');
		if(Yii::app()->request->getPost('osoite'))
		Yii::app()->session['osoite'] = Yii::app()->request->getPost('osoite');

		$from = date("d.m.Y", strtotime('first day of this month'));
		$to = date("d.m.Y");
		if(isset(Yii::app()->session['from']))
		$from = date("d.m.Y", strtotime(Yii::app()->session['from']));
		if(isset(Yii::app()->session['to']))
		$to = date("d.m.Y", strtotime(Yii::app()->session['to']));


		$criteria = new CDBCriteria;
        	$criteria->condition = " 				
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."' 
		";

		if(isset(Yii::app()->session['tekijaPaaSivulla']))
		{
			$impl = implode(",", Yii::app()->session['tekijaPaaSivulla']);
	        	$criteria->addCondition (" tid IN ($impl) ");
		}
		if(isset(Yii::app()->session['status']))
		{
			$impl_status = implode(",", Yii::app()->session['status']);
	        	$criteria->addCondition (" status IN ($impl_status) ");
		}
		if(isset(Yii::app()->session['osoite']))
		{
	        	$criteria->addCondition (" kohde IN (SELECT id FROM sivex_kohdet WHERE osoite LIKE '%".Yii::app()->session['osoite']."%') ");
		}

		$model = Tyovuoroot::model()->findAll($criteria);

		//$dataProvider->pagination->pageSize = 50;

		$this->render('hallinta', array(
			'model' => $model,
			'from' => $from,
			'to' => $to,
		));

	}


	protected function getKohde($id)
	{
		$k = Kohteet::model()->findbypk($id);
		if(isset($k->id))
		return $k;
	}
}

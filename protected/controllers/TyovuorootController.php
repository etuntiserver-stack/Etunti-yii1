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
				'actions'=>array('admin','delete','create','update','index','view','updatetime','showohje','did','muisti','operatio','viikko','fromto','autoinsert','autoremove','viikkottain', 'viikkottain_pdf', 'laheta','kk','pvmtid','laheta_k', 'muistin', 'muisticlear', 'muistissa', 'vkolopput', 'vkolopchange', 'uusitilaus', 'tv2', 'tv3', 'PoistaTv', 'valitse_kokopaiva', 'tv_kohteet', 'siivous_tyonimike', 'getKohdeByAsiakas', 'getKohdeById', 'getAsiakasByKohde', 'paivita_laatikot', 'poista_toistuva', 'onko_sama', 'asiakas_autocomplete', 'kohde_autocomplete', 'check_paallekkain', 'get_tekijantiedot', 'is_asiakas', 'is_yhteyshenkilo', 'lista', 'didnew', 'palautta_toistuva_pvm', 'did3', 'didnew3'),
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

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
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
				AND peruutettu=0
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
			AND peruutettu=0
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

		$criteria=new CDbCriteria;
		$criteria->condition = " 
			peruutettu=0
		";
		$dataProvider=new CActiveDataProvider('Tyovuoroot', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));
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

		$from = date("Y-m-d", strtotime("{$_POST['year']}-W{$_POST['week']}-1"));
		$to = date("Y-m-d", strtotime("{$_POST['year']}-W{$_POST['week']}-7"));

		$kenelle = json_decode(Yii::app()->request->getPost('kenelle'));
		$kenelle = array_filter($kenelle);

		// <-- Update piilota_mobiilista nollaksi
		$tids = "(tid='".implode("' OR tid='", $kenelle)."')";
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to'
			AND $tids
			AND peruutettu=0
		";

		if(isset($_POST['P']))
		$criteria->Addcondition ( " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%w') IN (".implode(",",$_POST['P']).") ");

		Tyovuoroot::model()->updateAll(array('piilota_mobiilista'=>'0'), $criteria);
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
			$asetukset = Asetukset::model()->findByPk(1);
			$for = '';
			if(isset($tv[0]))
			{
			  foreach($tv as $data)
			  {

			   	// <-- Tyoryhmat
				if( 
				   isset($data->kohteet) 
				   and isset($asetukset) 
				   and $asetukset->tyoryhmat_kohde == 1 
				){
					$site = Yii::app()->createController('Site');
					$arr = $site[0]->TyoryhmatHelper();
					if( count($arr) > 0 and !in_array($data->kohteet->tyoryhma, $arr)){
			   			continue;
					}
				}
			   	//    Tyoryhmat -->

				$id = $data->id."_".date("Ymd", strtotime($data->pvm))."_".$data->tid;
				$for = date("Ymd", strtotime($data->pvm))."_".$data->tid;
				$_SESSION['muistin'][$id] = $id;
			  }
			}
				print_r($_SESSION['muistin']);
		}
		exit;

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
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
			";
			$m = Tyovuoroot::model()->findAll($poistoCriteria);

			
			foreach($m as $model)
			{

				$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
				$this->toistuvaDeletePvm($model->toistuva_id, $model->pvm);

				// <-- LOG
				if( isset($model->id) )
				{
					$model_log 	= 'Tyovuoroot';
					$name_log 	= 'Työvuorot';
					$status_log 	= 'Delete';
	
					$old_values = json_encode($model->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->

			}

			Tyovuoroot::model()->deleteAll($poistoCriteria);

			// <-- Otetaan pois tyovuoro_id noista jotka on tehtty
			$upd_criteria = new CDBcriteria;
			$upd_criteria->condition=" 
				toistuva_id='".$model->toistuva_id."'
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') < CURDATE()
			";
			Tyovuoroot::model()->updateAll(array('toistuva_id'=>0), $upd_criteria);
			//    Otetaan pois tyovuoro_id noista jotka on tehtty -->

			// <-- Tsekataan, onko jai jonkun tyovuorojen koskemattomana
			$tsekka_tv = Tyovuoroot::model()->findAll(" toistuva_id='".$model->toistuva_id."' ");
			//  Tsekataan, onko jai jonkun tyovuorojen koskemattomana -->



			if( $tsekka_tv == null )
			{

				// <-- LOG
				if( isset($model->toistuva_id) )
				{
					$model_log 	= 'ToistuvatTyovuorot';
					$name_log 	= 'Toistuvat työvuorot';
					$status_log 	= 'Delete';
					$t_m = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id);
					$old_values = json_encode($t_m->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->

				ToistuvatTyovuorot::model()->findByPk($model->toistuva_id)->delete();

			} else {

				// Keksi loogikka
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

			// <-- LOG
			if( isset($model->id) )
			{
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Delete';

				$old_values = json_encode($model->attributes);
				$new_values = null;
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

			// <-- Jos toistuva, otetaan sen Päivämäärä pois ketjusta
			if( isset($model->pvm) and $model->toistuva_id != 0)
			{
				$this->toistuvaDeletePvm($model->toistuva_id, $model->pvm);
			}
			//     Jos toistuva, otetaan sen Päivämäärä pois ketjusta -->

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

			// <-- Jos toistuva, otetaan sen Päivämäärä pois ketjusta
			if( isset($t->pvm) and $t->toistuva_id != 0)
			{
				$this->toistuvaDeletePvm($t->toistuva_id, $t->pvm);
			}
			//     Jos toistuva, otetaan sen Päivämäärä pois ketjusta -->

			// <-- LOG
			if( isset($t->id) )
			{
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Delete';

				$old_values = json_encode($t->attributes);
				$new_values = null;
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->


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

			// <-- LOG
			if( isset($t->id) and isset($model->id) )
			{
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Copy';

				$old_values = json_encode($t->attributes);
				$new_values = json_encode($model->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->
			
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
	
			// <-- Jos toistuva, otetaan sen Päivämäärä pois ketjusta
			if( isset($t->pvm) and $t->toistuva_id != 0)
			{
				$this->toistuvaDeletePvm($t->toistuva_id, $t->pvm);
			}
			//     Jos toistuva, otetaan sen Päivämäärä pois ketjusta -->

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

			Tyovuoroot::model()->deletebypk($ex[0]);	


			// <-- LOG
			if( isset($t->id) and isset($model->id) )
			{
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Move';

				$old_values = json_encode($t->attributes);
				$new_values = json_encode($model->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

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

	public function toistuvaDeletePvm($toistuva_id, $pvm)
	{
		$toistuva = ToistuvatTyovuorot::model()->findbypk($toistuva_id);
		if(isset($toistuva->id))
		{
			$poistettu_pvm = array();
			$poistettu_pvm = json_decode($toistuva->poistettu_pvm, true);

			$poistettu_pvm[] = $pvm;
			ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('poistettu_pvm'=>json_encode($poistettu_pvm)));

		}
	}

	public function actionPalautta_toistuva_pvm($id, $pvm)
	{
		$toistuva = ToistuvatTyovuorot::model()->findByPk($id);
		if(isset($toistuva->poistettu_pvm) and is_array(json_decode($toistuva->poistettu_pvm, true)))
		{
			$poistettu_pvm = json_decode($toistuva->poistettu_pvm, true);
			$uusi_ketju = array_values( array_diff($poistettu_pvm, array($pvm)) );
			ToistuvatTyovuorot::model()->updateByPk($toistuva->id, array('poistettu_pvm' => json_encode($uusi_ketju)));
			echo json_encode('ok');
		}
		exit;
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
		if($m === null){
			//throw new CHttpException(404, 'Kohdetta '.$id.' ei löydy');
			echo json_encode('Kohdetta '.$id.' ei löydy');
			exit;
		}

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

	public function actionDid($pvm,$tid,$kohde,$from)
	{
		if(isset($tietoja)) $tietoja = 1; else $tietoja = 0;
		if(isset($_GET['asiakas'])) $asiakas = $_GET['asiakas']; else $asiakas = '';
		$asetukset = Asetukset::model()->findByPk(1);
		$this->renderPartial('did',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'kohde'=>$kohde,
			'asiakas'=>$asiakas,
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
	     exit;
	}

	public function actionTv3()
	{
		if( !isset(Yii::app()->session['ov_poisto']) ){
			$this->poistaminenOnlineVarauksetJokaMeniOhi();
			Yii::app()->session['ov_poisto'] = 'suorittu';
		}
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

			$this->redirect(array('tv3'));
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


			$this->redirect(array('tv3'));
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
			$this->redirect(array('tv3'));
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

			// <-- Return order etu ja sukunimella
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->etuSukunimiCriteria($criteria);
			//     Return order etu ja sukunimella -->

	        	$criteria->select = "id,tekijan_nimi";
	        	$criteria->condition = ' aktiivinen=1 ';

			// <-- Tyoryhmat
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			} 
			//    Tyoryhmat -->

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
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(Yii::app()->session['tyoryhma']);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			}
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

		$this->render('tv3', array(
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

	public function actionDid3($pvm,$tid,$kohde,$from)
	{
		if(isset($tietoja)) $tietoja = 1; else $tietoja = 0;
		if(isset($_GET['asiakas'])) $asiakas = $_GET['asiakas']; else $asiakas = '';
		$asetukset = Asetukset::model()->findByPk(1);
		$this->renderPartial('did3',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'kohde'=>$kohde,
			'asiakas'=>$asiakas,
			'from'=>$from,
			'tietoja'=>$tietoja,
			'asetukset'=>$asetukset
		));
	}

	public function actionDidnew3()
	{
		$return = array();
		if( isset($_POST['pvm']) ){
	       		$criteria = new CDbCriteria();
			$criteria->order = " alku ASC";
			$criteria->condition = " tid = '".$_POST['tid']."' and pvm = '".date("d.m.Y",strtotime($_POST['pvm']))."' ";
			$tv = Tyovuoroot::model()->findAll($criteria); 
			foreach($tv as $tvVal){
				$osoite = '';
				if(isset($tvVal->kohteet->osoite)){ $osoite = substr($tvVal->kohteet->osoite,0,27); }
				$return[] = array(
					'id' => $tvVal->id,
					'alku' => $tvVal->alku,
					'loppu' => $tvVal->loppu,
					'osoite' => $osoite,
					'status' => $tvVal->status
				);
			}
		}
		echo json_encode($return);
		exit;
	}
/*
	public function actionDidnew3()
	{
	     if( isset($_POST['pvm']) ){
	     if(is_array(json_decode($_POST['kohteet_siivous'], true)))
	     $ks = json_decode($_POST['kohteet_siivous'], true);
	     else
	     $ks = array();


	     $asetukset = Asetukset::model()->findByPk(1);
 	     $this->renderPartial('did3',array(
					'pvm'=>$_POST['pvm'],
					'tid'=>$_POST['tid'],
					'from'=>$_POST['from'], 
					'kohteet_siivous'=>$ks, 
					'asetukset'=>$asetukset,
					'asiakas'=>$_POST['asiakas'],
					'kohde'=>$_POST['kohde'],
					'ajax_pyynto' => true
	     ));
	     }
	     exit;
	}
*/

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

			if( is_array($toistuva->lisa_tuotteet) and count($toistuva->lisa_tuotteet) > 0 ){
				$toistuva->lisa_tuotteet = json_encode($toistuva->lisa_tuotteet);
			} else {
				$toistuva->lisa_tuotteet = '';
			}

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
					$toistuva,
					$toistuva->tid,  
					json_decode($toistuva->viikko_paivat, true),
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
					$toistuva,
					$tid,  
					json_decode($toistuva->viikko_paivat, true),
					json_encode($_POST['tyopaari']),
					$saankoSuoritta
					);
			    }

				ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tyopaari' => json_encode($_POST['tyopaari'])));
			}
			// jos on tyopaari -->


			// <-- LOG
			if( $saankoSuoritta == 1 )
			{
			$model_log 	= 'ToistuvatTyovuorot';
			$name_log 	= 'Toistuvat työvuorot';
			$status_log 	= 'Create';
			if(isset($toistuva->id))
			{
				$old_values = null;
				$n_m = ToistuvatTyovuorot::model()->findbypk($toistuva->id);
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			}
			//     LOG -->


			echo json_encode($return);
			exit;
		}






		$model=new Tyovuoroot;

		if(isset($_POST['Tyovuoroot']))
		{

			$model->attributes=$_POST['Tyovuoroot'];
			if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
				$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
			} else {
				$model->lisa_tuotteet = '';
			}

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


			// <-- LOG
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Create';
			if(isset($_POST[$model_log]))
			{
				$old_values = null;
				$n_m = Tyovuoroot::model()->findbypk($model->id);
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->


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
		if(isset($_POST['tids']))
		{
			$tids_arr = $_POST['tids'];
			$tids_arr = array_unique(array_values($tids_arr));

			$kohde = '';
			if(isset(Yii::app()->session['kohde']))
			{
				$kohde = Yii::app()->session['kohde'];
			}
			$asiakas = '';
			if(isset(Yii::app()->session['asiakas']))
			{
				$asiakas = Yii::app()->session['asiakas'];
			}

			$return = array();
			if(isset(Yii::app()->session['from']) and isset(Yii::app()->session['to']))
			{

			   foreach($tids_arr as $tid)
			   {
				$start_date = Yii::app()->session['from'];
				$end_date = Yii::app()->session['to'];

				while (strtotime($start_date) <= strtotime($end_date)) {
					$return[] = array(
						'tid'=>$tid, 
						'pvm'=>date("d.m.Y", strtotime($start_date)), 
						'ymd'=>date("Ymd",strtotime($start_date)),
						'kohde' => $kohde,
						'asiakas' => $asiakas
					);

					$start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
				}
			   }
			}

			echo json_encode($return);
			exit;
		}
	}


	public function actionUpdate($id)
	{

		$model=$this->loadModel($id);
		$edellinenToistuva = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id);

		$return = array();


		// <-- Toistuva tyovuorot ja tyoparit
		if(
			isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) 
			and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on'
		)
		{
			$fi = $this->vkoPaivat();
			$saankoSuoritta = $_POST['ToistuvatTyovuorot']['sopivatPaivat'];

			if(isset($edellinenToistuva->id))
				$toistuva = ToistuvatTyovuorot::model()->findByPk($edellinenToistuva->id);
			else
				$toistuva = new ToistuvatTyovuorot;

			$toistuva->attributes = $_POST['ToistuvatTyovuorot'];
			$toistuva->attributes = $_POST['Tyovuoroot'];
			$toistuva->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if( is_array($toistuva->lisa_tuotteet) and count($toistuva->lisa_tuotteet) > 0 ){
				$toistuva->lisa_tuotteet = json_encode($toistuva->lisa_tuotteet);
			} else {
				$toistuva->lisa_tuotteet = '';
			}

			if(isset($_POST['P']))
			$toistuva->viikko_paivat=json_encode($_POST['P']);

			//$return[] = array('ERROR'=>json_encode($toistuva->attributes));


			if($saankoSuoritta == 1)
			{

				if(!$toistuva->save())
				{
					$return[] = array('ERROR'=>json_encode(var_dump($toistuva->getErrors())));
				}

				// <-- Pois valittuna Työvuoro
				if(!isset($edellinenToistuva->id))
				{
					Tyovuoroot::model()->deleteByPk($id);
				}
				//     Pois valittuna Työvuoro -->

				//date("Y-m-d",strtotime($_POST['ToistuvatTyovuorot']['pfrom']))


				// <-- Pois kaikki Aloitus pvm alkaen
				$pois_criteria = new CDBcriteria;
				$pois_criteria->condition=" 
					toistuva_id='".$toistuva->id."'
					AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= '".date("Y-m-d",strtotime($_POST['ToistuvatTyovuorot']['pfrom']))."'
				";
				Tyovuoroot::model()->deleteAll($pois_criteria);
				//    Pois kaikki Aloitus pvm alkaen -->

				// <-- Poistetaanko vai säilytetäänkö vanhan ja uuden aloituspäivämäärän väliin jäävät työvuorot
				if(
					isset($_POST['poisto_alkaen_taaksepain'])
					and isset($edellinenToistuva->id)
					and strtotime($_POST['ToistuvatTyovuorot']['pfrom']) > strtotime($edellinenToistuva->pfrom)
				)
				{
					$pois_valipavm = new CDBcriteria;
					$pois_valipavm->condition=" 
						toistuva_id='".$toistuva->id."'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
						BETWEEN '".date("Y-m-d",strtotime($edellinenToistuva->pfrom))."'
						AND '".date("Y-m-d",strtotime($_POST['ToistuvatTyovuorot']['pfrom']))."'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
					";
					Tyovuoroot::model()->deleteAll($pois_valipavm);
				}
				//     Poistetaanko vai säilytetäänkö vanhan ja uuden aloituspäivämäärän väliin jäävät työvuorot -->

				// <-- Otetaan pois tyovuoro_id noista jotka on jaanyt
				$upd_criteria = new CDBcriteria;
				$upd_criteria->condition=" 
					toistuva_id='".$toistuva->id."'
				";
				Tyovuoroot::model()->updateAll(array('toistuva_id'=>0), $upd_criteria);
				//    Otetaan pois tyovuoro_id noista jotka on jaanyt -->

			}



			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    // <-- Lisätään pää työntekijä
			    $_POST['tyopaari'][] = $toistuva->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$return[] = $this->toistuvaInsert(
					$toistuva, 
					$tid, 
					json_decode($toistuva->viikko_paivat, true),
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
					$toistuva,
					$toistuva->tid, 
					json_decode($toistuva->viikko_paivat, true),
					'', // tyopaari
					$saankoSuoritta
				);

			}


			if($saankoSuoritta == 1)
			{
					$nt = ToistuvatTyovuorot::model()->findbypk($toistuva->id);
					if(isset($edellinenToistuva->id))
					{
					// <-- LOG
					$model_log 	= 'ToistuvatTyovuorot';
					$name_log 	= 'Toistuvat työvuorot';
					$status_log 	= 'Update';
					$old_values = json_encode($edellinenToistuva->attributes);
					$new_values = json_encode($nt->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
					//     LOG -->
					} else {
					// <-- LOG
					$model_log 	= 'ToistuvatTyovuorot';
					$name_log 	= 'Toistuvat työvuorot';
					$status_log 	= 'Create';
					$old_values = null;
					$new_values = json_encode($nt->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
					//     LOG -->
					}
			}


			if( count($return) > 0 )
				echo json_encode($return);
			else
				echo json_encode(array('ERROR'=>'Ei muutoksia'));
			exit;
		}
		//     Toistuva tyovuorot ja tyoparit -->








		// Jos Toistuva Ruksi ei ole päällä
		if(isset($_POST['Tyovuoroot']) and !isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']))
		{


		// <-- LOG
		$model_log 	= 'Tyovuoroot';
		$name_log 	= 'Työvuorot';
		$status_log 	= 'Update';
		if(isset($_POST[$model_log]))
		{
			$old_values = json_encode($model->attributes);
			$new_values = json_encode($_POST[$model_log]);
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
		}
		//     LOG -->



			// <-- Jos toistuva, otetaan sen Päivämäärä pois ketjusta
			if( isset($model->pvm) and $model->toistuva_id != 0)
			{
				$this->toistuvaDeletePvm($model->toistuva_id, $model->pvm);
				$_POST['Tyovuoroot']['toistuva_id'] = 0;
			}
			//     Jos toistuva, otetaan sen Päivämäärä pois ketjusta -->


			$_POST['Tyovuoroot']['pvm'] = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			// <-- Tyontekijan vaihto
			if( $model->tid != $_POST['Tyovuoroot']['tid'] )
			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			// Tyontekijan vaihto -->

			$toistuva_id = $model->toistuva_id;

			$model->attributes = $_POST['Tyovuoroot'];
			if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
				$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
			} else {
				$model->lisa_tuotteet = '';
			}

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

						// <-- Poistetaan tyovuoro henkilosta joka oli toistuvissa
						if( $toistuva_id != 0 )
						{
						$criteria = new CDBcriteria;
						$criteria->condition = " 
							pvm='".$m->pvm."' 
							AND tid='".$m->tid."'
							AND toistuva_id='".$toistuva_id."'
						";
						Tyovuoroot::model()->deleteAll($criteria);
						}
						//  Poistetaan tyovuoro henkilosta joka oli toistuvissa -->

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

			} else { // model save 
				echo json_encode($model->getErrors());
			}

			exit;
		}





		$criteria = new CDBcriteria;
		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->
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
	       		$criteria = new CDbCriteria();
	       		$criteria->order = " cast(asiakasnumero as unsigned) DESC  ";
			$anum = Asiakkaat::model()->find($criteria);
			if( isset($anum->id) ){ $nextnum = $anum->asiakasnumero+1; } else { $nextnum = $anum->id; }		
			Asiakkaat::model()->updateByPk($asiakkaat->id, array( 'asiakasnumero' => $nextnum ));


			$kohteet = new Kohteet;
			$kohteet->asiakas_id = $asiakkaat->id;
			$kohteet->hinnasto_id = $asiakkaat->hinnasto_id;
			$kohteet->uusi_tilaus = 1;
			$kohteet->aktiivinen = 1;

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

					if(isset($_POST['Tyovuoroot']['tilausviesti']) and !empty($_POST['Tyovuoroot']['tilausviesti']))
					$message .= str_replace("\n", "<br>", $_POST['Tyovuoroot']['tilausviesti']);

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
		if( !isset(Yii::app()->session['ov_poisto']) ){
			$this->poistaminenOnlineVarauksetJokaMeniOhi();
			Yii::app()->session['ov_poisto'] = 'suorittu';
		}
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

			// <-- Return order etu ja sukunimella
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->etuSukunimiCriteria($criteria);
			//     Return order etu ja sukunimella -->

	        	$criteria->select = "id,tekijan_nimi";
	        	$criteria->condition = ' aktiivinen=1 ';

			// <-- Tyoryhmat
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			} 
			//    Tyoryhmat -->

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
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(Yii::app()->session['tyoryhma']);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			}
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
		if( !isset(Yii::app()->session['ov_poisto']) ){
			$this->poistaminenOnlineVarauksetJokaMeniOhi();
			Yii::app()->session['ov_poisto'] = 'suorittu';
		}

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

			// <-- Return order etu ja sukunimella
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->etuSukunimiCriteria($criteria);
			//     Return order etu ja sukunimella -->

	        	$criteria->select = "id,tekijan_nimi";
	        	$criteria->condition = ' aktiivinen=1 ';

			// <-- Tyoryhmat
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			} 
			//    Tyoryhmat -->

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
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(Yii::app()->session['tyoryhma']);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			}
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

		$asetukset = Asetukset::model()->findByPk(1);
		// <-- Poistaminen
		$criteria=new CDbCriteria;
		$criteria->order= " id DESC "; 
		$criteria->condition= " 
			(time + INTERVAL ".$asetukset->onlinevaraus_autoremove." MINUTE) < NOW()
			AND osoiteOnline=1
		";
		$poistaminen = Tyovuoroot::model()->findAll($criteria);

		foreach($poistaminen as $item){

				$tv = Tyovuoroot::model()->findByPk($item->id);
				// <-- LOG
				$model_log 	= 'Tyovuoroot';
				$name_log 	= 'Työvuorot';
				$status_log 	= 'Auto Delete';
	
					$old_values = json_encode($tv->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
		}

		$criteria=new CDbCriteria;
		$criteria->order= " id DESC "; 
		$criteria->condition= " 
			(time + INTERVAL ".$asetukset->onlinevaraus_autoremove." MINUTE) < NOW()
			AND osoiteOnline=1
		";
		Tyovuoroot::model()->deleteAll($criteria);
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
		{
			$tilanne = '('.date("d.m.Y H:i").' - '.Yii::app()->user->nimi.'): Työvuoroja '.$id.' ei löydy.';
			$log=new Log;
			$log->log_category 	= 3;
			$log->kuka 		= Yii::app()->user->nimi;
			$log->log_nimike	= 'error';
			$log->model		= 'Tyovuoroot';
			$log->tilanne		= $tilanne;
			$log->save();

			//throw new CHttpException(404, $tilanne);
			echo $tilanne;
		}
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

		if(date("N",strtotime($date)) == 7)
		{
			return 'su';
		}

		if(strstr($asetukset->viralliset_pyhapaivat, $dateMonth))
		{
			return 'pyhapaiva';
		}

		if(strstr($asetukset->erikoislauantai, $dateMonth))
		{
			return 'erikoislauantai';
		}

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


	protected function toistuvaInsert($attr, $tid, $viikko_paivat, $tyopaari, $saankoSuoritta)
	{


		$fi = $this->vkoPaivat();
		$tt = Tyontekijat::model()->findByPk($tid);
		
		// <-- Tsekataan poistettut PVM
		$poistettu_pvm = array();
		$toistuva = ToistuvatTyovuorot::model()->findByPk($attr->id);
		if(isset($toistuva->poistettu_pvm) and is_array(json_decode($toistuva->poistettu_pvm, true)))
		{
			$poistettu_pvm = json_decode($toistuva->poistettu_pvm, true);
		}
		//     Tsekataan poistettut PVM -->

		$startDate	= $attr->pfrom;
		$end_date	= $attr->pto;
		$date		= $startDate;

		$var		= 1;

		if($attr->viikkoja == 1)
			$var	= 0;

		$w		= $viikko_paivat;
		$v 		= $attr->viikkoja;
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
		    if($i % $attr->viikkoja === $var) {
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
				//$onkosama = $this->onko_sama(null, $pvm, $tid, $kohde, $alku, $loppu);


				$tekijan_nimi='';
				if(isset($tt->tekijan_nimi) and $tid!=0)
					$tekijan_nimi=$tt->tekijan_nimi;
				elseif(!isset($tt->tekijan_nimi) and $tid==0)
					$tekijan_nimi='VARAUS';

				if(in_array($pvm, $poistettu_pvm))
				{
							$return[] = array(
								'tid'=>$tid, 
								'pvm'=>$pvm, 
								'ymd'=>date("Ymd",strtotime($pvm)), 
								'isSaved'=>false, 
								'tekijan_nimi'=>$tekijan_nimi, 
								'vkopvm' => $fi[date("N",strtotime($pvm))],
								'toistuva_id'=>$attr->id,
								'otettu_pois'=>true 
							);
				} else {

					$t = new Tyovuoroot;
					$t->attributes = $attr->attributes;
					$t->tid = $tid;
					$t->pvm = $pvm;
					$t->tyopaari = $tyopaari;
					$t->toistuva_id = $attr->id;
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

							$return[] = array(
								'tid'=>$tid, 
								'pvm'=>$pvm, 
								'ymd'=>date("Ymd",strtotime($pvm)), 
								'isSaved'=>false, 
								'tekijan_nimi'=>$tekijan_nimi, 
								'vkopvm' => $fi[date("N",strtotime($pvm))], 
								'uusi'=>true 
							);

					}

				} // if otettu pois

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

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		$criteria->addCondition (" 
			aktiivinen=1 
			AND (yrityksen_nimi LIKE '%".$key."%' OR yhteyshenkilo LIKE '%".$key."%' OR osoite LIKE '%".$key."%' )	
		");

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


		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		$criteria->addCondition (" 
			aktiivinen=1 
			AND etu_suku_nimet LIKE '%".$key."%'	
		");

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

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		$criteria->addCondition (" 
			osoite LIKE '%".$key."%'	
		");

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

	public function actionLista()
	{

		if(isset($_POST['asiakkaatPerSivu']))
		{
			Yii::app()->user->setState('asiakkaatPerSivu', $_POST['asiakkaatPerSivu']);
			echo json_encode($_POST['asiakkaatPerSivu']);
			exit;
		}

		$from = date("d.m.Y", strtotime('first day of this month'));
		$to = date("d.m.Y");
		if(isset($_GET['from']))
		$from = date("d.m.Y", strtotime($_GET['from']));
		if(isset($_GET['to']))
		$to = date("d.m.Y", strtotime($_GET['to']));


		$criteria = new CDBCriteria;
        	$criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
        	$criteria->condition = " 				
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND peruutettu=0 
		";

		if(isset($_GET['tekijaPaaSivulla']))
		{
			$impl = implode(",", $_GET['tekijaPaaSivulla']);
	        	$criteria->addCondition (" tid IN ($impl) ");
		}
		if(isset($_GET['status']))
		{
			$impl_status = implode(",", $_GET['status']);
	        	$criteria->addCondition (" status IN ($impl_status) ");
		}
		if(isset($_GET['yrityksen_nimi']))
		{
	        	$criteria->addCondition (" kohde IN (SELECT id FROM sivex_kohdet WHERE 
				asiakas_id IN (
					SELECT id FROM asiakkaat WHERE
					yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR yhteyshenkilo LIKE '%".$_GET['yrityksen_nimi']."%'
				)
			) ");
		}
		if(isset($_GET['osoite']))
		{
	        	$criteria->addCondition (" kohde IN (SELECT id FROM sivex_kohdet WHERE osoite LIKE '%".$_GET['osoite']."%') ");
		}


		$dataProvider=new CActiveDataProvider('Tyovuoroot', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$perSivu = 50;
		if(isset(Yii::app()->user->asiakkaatPerSivu))
		$perSivu = Yii::app()->user->asiakkaatPerSivu;

		$dataProvider->pagination->pageSize = $perSivu;


		$this->render('lista', array(
			'dataProvider' => $dataProvider,
			'perSivu' => $perSivu,
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

	public function eiLasketaSubStr($val)
	{

		$return = false;
		if (strpos($val, 'Ei lasketa') !== false or strpos($val, 'Varallaolo') !== false) {
		    $return = true;
		}
		return $return;
	}
}

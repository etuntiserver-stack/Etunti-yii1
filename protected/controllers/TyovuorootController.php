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
				'actions'=>array('admin','delete','index','view','updatetime','showohje','muisti','operatio', 'viikko','viikkottain', 'viikkottain_pdf', 'laheta','kk','pvmtid','laheta_k', 'muistin', 'muisticlear', 'muistissa', 'vkolopput', 'vkolopchange', 'uusitilaus', 'virtual_migration', 'vmigrate_ajax_next', 'find_past_chains', 'freshdesk', 'beta', 'did4', 'PoistaTv', 'valitse_kokopaiva', 'tv_kohteet', 'siivous_tyonimike', 'getKohdeByAsiakas', 'getKohdeById', 'getAsiakasByKohde', 'paivita_laatikot', 'poista_toistuva', 'onko_sama', 'asiakas_autocomplete', 'kohde_autocomplete', 'get_tekijantiedot', 'is_asiakas', 'is_yhteyshenkilo', 'lista', 'siirto', 'vlupdater', 'palkkataulukko', 'hovertietoja', 'create4', 'update4', 'create4_form', 'update4_form', 'pvmTarkistus_lista', 'pois_pvm_ketjusta', 'palauta_pvm_kejuun', 'pto_muutos', 'contextmenu_valinnat', 'contextmenu_submits', 'getsumbyweekall', 'tvasetus'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','updatetime','showohje','muisti','operatio','viikko','viikkottain','laheta','kk','pvmtid','laheta_k'),
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

		if(isset(Yii::app()->user->adminID) and in_array('2',$tas) or (strpos(Yii::app()->user->domain, 'staging_') !== false))
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

	protected function TimeToSec($time) {
	    $sec = 0;
	    foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
	    return $sec;
	}

	public function actionTvasetus($whatfor)
	{

		$return = 'off';
		if( !isset($_SESSION[$whatfor]) ){
			$_SESSION[$whatfor] 	= true;
			$return 		= 'on';
		} else {
			unset($_SESSION[$whatfor]);
			$return 		= 'off';
		}

		echo json_encode([$whatfor => $return]);
		exit;
	}

	public function actionPalkkataulukko()
	{
		$asetukset = Asetukset::model()->findByPk(1);
		// <-- Order tyontekijat
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
			$tt_order_1 = "tekijan_nimi";
			$tt_order_2 = "sukunimi";
		} else {
			$tt_order_1 = "sukunimi";
			$tt_order_2 = "tekijan_nimi";
		}
		// Order tyontekijat -->

		$from = date("d.m.Y",strtotime("first day of this month"));
		$to = date("d.m.Y");

		if(isset($_GET['from']) and !empty($_GET['from'])){ $from = $_GET['from']; }
		if(isset($_GET['to']) and !empty($_GET['to'])){ $to = $_GET['to']; }

       		$criteria = new CDbCriteria();
		$criteria->select = " id,tekijan_nimi,sukunimi ";

		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

        	$criteria->condition = " aktiivinen=1 "; 

		if( isset($_GET['Tekija']) ){
			$ids = implode(",",$_GET['Tekija']);
	        	$criteria->addCondition ('id IN ('.$ids.') ');
		}

		$model = Tyontekijat::model()->findAll($criteria);

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		//$dataProvider->pagination->pageSize = 50;
		$this->render('palkkataulukko', array(
			'model' => $model,
			'from' => $from,
			'to' => $to,
			'tt_order_1' => $tt_order_1,
			'tt_order_2' => $tt_order_2,
		));
		
	}

	public function TidfromtoTyovuoroWithVirtual($from, $to, $tids, $no_ilta, $by_day, $status)
	{
		$result = [];
		$asetukset = AsetuksetForAll::model()->findbypk(1);

		// <-- Pyhapaivat
		$pyhapaivat = [];
		$p_explode = explode("\n", $asetukset->viralliset_pyhapaivat);
		$p_explode = array_map('trim', $p_explode); // clear spaces
		$p_explode = array_map('rtrim', $p_explode); // clear spaces
		$begin = date("d.m.Y", strtotime($from));
		$end   = date("d.m.Y", strtotime($to));
		while (strtotime($begin) <= strtotime($end)) {
			if (in_array($begin, $p_explode))
				$pyhapaivat[$begin] = $begin;
			$begin = date("d.m.Y", strtotime("+1 day", strtotime($begin)));
		}

		// <-- erikoislauantai
		$erikoislauantai = [];
		$p_explode = explode("\n", $asetukset->erikoislauantai);
		$p_explode = array_map('trim', $p_explode); // clear spaces
		$p_explode = array_map('rtrim', $p_explode); // clear spaces
		$begin = date("d.m.Y", strtotime($from));
		$end   = date("d.m.Y", strtotime($to));
		while (strtotime($begin) <= strtotime($end)) {
			if (in_array($begin, $p_explode))
				$erikoislauantai[$begin] = $begin;
			$begin = date("d.m.Y", strtotime("+1 day", strtotime($begin)));
		}

		$from 		= date("Y-m-d", strtotime($from));
		$to 		= date("Y-m-d", strtotime($to));
		$haku_criteria	= "(".$this->eiLasketa().") AND (peruutettu=0 OR peruutettu IS NULL)";
		$tv_arr 	= $this->tv_arr($from, $to, $tids, $haku_criteria, false, ['data']);
		$tids_after = [];
		foreach($tv_arr as $t => $arr)
			$tids_after[] = $t;

		// Initialize results array.
		foreach ((is_array($tids_after) ? $tids_after : [$tids]) as $tid) {
				$result[$tid] = [
				'tyotunnit' => ['kaikki' => 0, 'ilta' => 0, 'yo' => 0],
				'matkatunnit' => ['kaikki' => 0, 'ilta' => 0, 'yo' => 0],
				'lounaat' => ['kaikki' => 0, 'ilta' => 0, 'yo' => 0],
				'tp_maara' => 0,
				'pyhapaivat' => 0,
				'erikoislauantai' => 0,
				'sutunnit' => 0
				];
		}

		foreach ($tv_arr as $tid => $arr) {
			if(!isset($result[$tid]))
				continue;
			if($no_ilta and !$by_day)
				$result[$tid] = 0;
			foreach ($arr as $pvm => $arr2) {
				if($by_day)
					$result[$tid][$pvm] = 0;
				// Increment total work days.
				if(!$no_ilta and !$by_day)
					$result[$tid]['tp_maara']++;
				foreach ($arr2 as $unixtime => $attributes) {
					$alku_hm = date('Hi', strtotime($attributes[0]['data']['alku']));
					$loppu_hm = date('Hi', strtotime($attributes[0]['data']['loppu']));
					$iltatunnit = 0;
					$yotunnit = 0;
					$tunnit_yht = strtotime($attributes[0]['data']['loppu']) - strtotime($attributes[0]['data']['alku']);

					if($status !== null and !in_array($attributes[0]['data']['status'], $status))
						continue;
					if($no_ilta and !$by_day){
						$result[$tid] += $tunnit_yht;
						continue;
					}
					if($by_day){
						$result[$tid][$pvm] += $tunnit_yht;
						continue;
					}
					// Iltatunnit 18-23
					if ($alku_hm < 2300 && $loppu_hm > 1800) {

						// Use switch with process of elimination, leaving us with less conditions to check.
						switch (true) {

								// alku <= 18:00, loppu < 23:00
							case ($alku_hm <= 1800 && $loppu_hm < 2300):
								$iltatunnit += strtotime($attributes[0]['data']['loppu']) - strtotime('18:00');
								break;

								// alku <= 18:00, loppu >= 23:00
							case ($alku_hm <= 1800):
								$iltatunnit += strtotime('23:00') - strtotime('18:00');
								break;

								// alku > 18:00, loppu < 23:00
							case ($loppu_hm < 2300):
								$iltatunnit += $tunnit_yht;
								break;

								// alku > 18:00, loppu >= 23:00
							default:
								$iltatunnit += strtotime('23:00') - strtotime($attributes[0]['data']['alku']);
								break;
						}
					}

					// Yötunnit 23-06: 00-06
					if ($alku_hm < 600) {
						$yotunnit += $loppu_hm < 600 ?
							$tunnit_yht :
							strtotime('06:00') - strtotime($attributes[0]['data']['alku']);
					}

					// Yötunnit 23-06: 23-00
					if ($loppu_hm > 2300) {
						$yotunnit += $alku_hm > 2300 ?
							$tunnit_yht :
							strtotime($attributes[0]['data']['loppu']) - strtotime('23:00');
					}

					// Assign hours
					switch ($attributes[0]['data']['status']) {
						case 2:
							$result[$tid]['matkatunnit']['kaikki'] += $tunnit_yht;
							$result[$tid]['matkatunnit']['ilta'] += $iltatunnit;
							$result[$tid]['matkatunnit']['yo'] += $yotunnit;
							if (isset($pyhapaivat[$attributes[0]['data']['pvm']]))
								$result[$tid]['pyhapaivat'] += $tunnit_yht;
							if (isset($erikoislauantai[$attributes[0]['data']['pvm']]))
								$result[$tid]['erikoislauantai'] += $tunnit_yht;
							break;
						case 3:
							$result[$tid]['tyotunnit']['kaikki'] += $tunnit_yht;
							$result[$tid]['tyotunnit']['ilta'] += $iltatunnit;
							$result[$tid]['tyotunnit']['yo'] += $yotunnit;
							if (isset($pyhapaivat[$attributes[0]['data']['pvm']]))
								$result[$tid]['pyhapaivat'] += $tunnit_yht;
							if (isset($erikoislauantai[$attributes[0]['data']['pvm']]))
								$result[$tid]['erikoislauantai'] += $tunnit_yht;
							if (date("N", strtotime($attributes[0]['data']['pvm'])) == 7)
								$result[$tid]['sutunnit'] += $tunnit_yht;
							break;
						case 10:
							$result[$tid]['lounaat']['kaikki'] += $tunnit_yht;
							$result[$tid]['lounaat']['ilta'] += $iltatunnit;
							$result[$tid]['lounaat']['yo'] += $yotunnit;
							break;
					}
				}
			}
		}

		// Jos haluat tyhjät arvot pois:
		// array_walk($result, function(&$tid_arr, $tid) {
		// 	$tid_arr = array_filter($tid_arr, function($item, $key) {
		// 		return (is_array($item) || $item > 0);
		// 	}, ARRAY_FILTER_USE_BOTH);
		// });
		// echo '<pre>' . print_r($result, true) . '</pre>';

		return $result;
	}

	public function eiLasketa(){
		$return = "
		(tyoajanmerkinta NOT LIKE '%Ei lasketa%' AND tyoajanmerkinta NOT LIKE '%Varallaolo%')
		";
		return $return;
	}

	public function actionIs_yhteyshenkilo()
	{
		$bd = '';
		if(Yii::app()->request->getPost('yhteyshenkilo'))
		{
			$model = Asiakkaat::model()->find(" yhteyshenkilo LIKE '%".Yii::app()->request->getPost('yhteyshenkilo')."' ");
			if(isset($model->id))
			$bd = Yii::t('main', 'Asiakas löyty tietokannasta');	
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
			$bd .= Yii::t('main', 'Asiakas löyty tietokannasta');	
		}
		if(Yii::app()->request->getPost('sahkoposti'))
		{
			$model = Asiakkaat::model()->find(" sahkoposti LIKE '%".Yii::app()->request->getPost('sahkoposti')."' ");
			if(isset($model->id))
			$bd .= Yii::t('main', 'Asiakas löyty tietokannasta');	
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
		$bd .= '<div class="row">';
		$bd .= '<div class="col-sm-8">';
		$bd .= Yii::t('main', 'Nimi').': <b>'.$this->etuSukunimi($model->id).'</b><br>';
		$bd .= Yii::t('main', 'Työpuhelin').': <b>'.$model->laiten_puh.'</b><br>';
		$bd .= Yii::t('main', 'Oma puhelin').': <b>'.$model->tekijan_puh.'</b><br>';
		$bd .= Yii::t('main', 'Sähköpostiosoite').': <b>'.$model->tekijan_email.'</b><br>';
		$bd .= Yii::t('main', 'Kotiosoite').': <b>'.$model->tekijan_katuosoite.'</b><br>';

		$bd .= '</div><div class="col-sm-4">';
		$filepath = dirname(Yii::app()->getBasePath())."/img/tekijat/".Yii::app()->user->domain."/".$model->id.".jpg";
		if (file_exists($filepath)){
		   $imageData = base64_encode(file_get_contents($filepath));
		   $src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;
		   $bd .= '<div class="pull-right"><img src="'.$src.'" class="img-thumbnail"></div>';
		} else {
		   $bd .= '<img src="'.Yii::app()->request->baseUrl.'/img/tekijat/noname.jpg" class="img-thumbnail">';
		}
		$bd .= '</div></div>';

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
		$this->render('kk',array(

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

			$basePath = Yii::app()->basePath.'/../tmp/'.Yii::app()->user->domain.'/';
			$path = 'tmp/'.Yii::app()->user->domain.'/';

			if (!file_exists( $basePath )) {
			 	mkdir( $basePath, 0777, true );
			}
			$tiedosto = 'tyovuoro_'.date("d.m.Y");
			$html = '<meta charset="UTF-8">';
			$html .= $this->renderPartial('laheta',array('tid'=>$_POST['kuka'],'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true);

			file_put_contents($path.'/'.$tiedosto.'.html', $html);
			$output = exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.$tiedosto.'.html '.$path.$tiedosto.'.pdf 2>&1'); //-O landscape
			header("Content-Length: " . filesize ( $path.$tiedosto.'.pdf' ) ); 
		        header("Content-type: application/pdf"); 
		        header("Content-disposition: attachment; filename=".basename($path.$tiedosto.'.pdf'));
		        readfile($path.$tiedosto.'.pdf');
			unlink($path.$tiedosto.'.html');
			unlink($path.$tiedosto.'.pdf');
			exit;

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

			$html = '<meta charset="UTF-8">';
			$html .= $this->renderPartial('laheta',array('tid'=>$tt->id,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true);
			//echo $html;
			//exit;
			$basePath = Yii::app()->basePath.'/../emails/tyovuorot/'.Yii::app()->user->domain.'/';
			$path = 'emails/tyovuorot/'.Yii::app()->user->domain.'/';
			if (!file_exists( $basePath )) {
			 	mkdir( $basePath, 0777, true );
			}
			$tiedosto = $week.'_'.$year.'_'.$key;
			file_put_contents($path.'/'.$tiedosto.'.html', $html);
			$output = exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.$tiedosto.'.html '.$path.$tiedosto.'.pdf 2>&1'); //-O landscape

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
			$mail->setAttachment($path.'/'.$tiedosto.'.pdf');
			if($mail->send()){

 
							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->kuka 		= Yii::app()->user->nimi;
							$log->email_to 		= $tt->tekijan_email;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$tiedosto.'.pdf';
							$log->email_attachment_sisalto	= json_encode($html);
							$log->log_nimike	= 'tyovuoro_lahetys';
							$log->save();
							//     LOG -->
							if (file_exists( $path.$tiedosto.'.html' )) {
								unlink($path.$tiedosto.'.html');
							}

			}

		 }
		}


		// firmalle kaikki
		$ft = FirmanTiedot::model()->findbypk(1);
		if(isset($ft->sahkoposti) and !empty($ft->sahkoposti))
		{
			$saaja = $ft->sahkoposti;

			$html = '<meta charset="UTF-8">';
			$html .= $this->renderPartial('laheta_k',array('week'=>$week,'year'=>$year,'tulosta'=>'lista'),true);

			$basePath = Yii::app()->basePath.'/../emails/tyovuorot/'.Yii::app()->user->domain.'/';
			$path = 'emails/tyovuorot/'.Yii::app()->user->domain.'/';
			if (!file_exists( $basePath )) {
			 	mkdir( $basePath, 0777, true );
			}
			$tiedosto = $week.'_'.$year.'_'.$key.'_toimisto.pdf';
			file_put_contents($path.'/'.$tiedosto.'.html', $html);
			$output = exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.$tiedosto.'.html '.$path.$tiedosto.'.pdf 2>&1'); //-O landscape

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
			$mail->setAttachment($path.'/'.$tiedosto.'.pdf');
			if($mail->send()){

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $saaja;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$tiedosto.'.pdf';
							$log->email_attachment_sisalto	= json_encode($html);
							$log->log_nimike	= 'tyovuoro_lahetys';
							$log->save();
							//     LOG -->
							if (file_exists( $path.$tiedosto.'.html' )) {
								unlink($path.$tiedosto.'.html');
							}
			}

		}
		//

		  $this->redirect(['beta', 'mode' => 'vko']);

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
		$return = [];
		if(isset($_POST['pvm']) and isset($_POST['tid'])){
			$pvm_from = date("Y-m-d", strtotime($_POST['pvm']));
			$pvm_to = date("Y-m-d", strtotime($_POST['pvm']));
			$tv_arr = $this->tv_arr($pvm_from, $pvm_to, [$_POST['tid']], [], false, ['this_id']);
			if( isset($tv_arr[$_POST['tid']][$_POST['pvm']]) ){
				foreach($tv_arr[$_POST['tid']][$_POST['pvm']] as $k => $v){
					foreach($v as $v2){
						if( isset($v2['this_id']) ){
							$return[] = $this->check_muistista($v2['this_id']);
							$_SESSION['muistin'][] = $v2['this_id'];
						}
					}
				}
			}
		echo json_encode($return);
		}
		exit;

	}

	protected function check_muistista($id)
	{
		$return 	= [];
		$get_id 	= $this->this_id($id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		if( !$toistuva and is_array(json_decode($model->tyopaari, true)) and count(json_decode($model->tyopaari, true)) > 0 )
			$return['varoitus_tyopaari'] = ['alku' => $model->alku, 'loppu' => $model->loppu, 'osoite' => $model->osoite];
		if( $toistuva )
			$return['varoitus_toistuva'] = ['alku' => $model->alku, 'loppu' => $model->loppu, 'osoite' => $model->osoite];
		return $return;
	}

	public function actionMuistin()
	{
		$return = $this->check_muistista($_POST['id']);
		if(isset($_POST['id'])){
			$_SESSION['muistin'][] = $_POST['id'];
		}
		echo json_encode($return);
		exit;
	}

	public function actionMuistissa()
	{
		if(isset($_SESSION['muistin']))
		   echo json_encode($_SESSION['muistin']);
		else
		   echo json_encode('muistityhja');

		exit;
	}

	public function actionMuisticlear()
	{
		if(isset($_SESSION['muistin']) and isset($_POST['clear'])){
			echo json_encode($_SESSION['muistin']);
			unset($_SESSION['muistin']);
		}
	}

	public function actionPoistaTv($this_id)
	{

		$get_id 	= $this->this_id($this_id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		$pvm 		= $get_id['pvm'];
		$tid 		= $get_id['tid'];

		$return 	= [];
		$u		= Yii::app()->user->nimi;
		$d		= date("d.m.Y");
		$poisto_syy	= ['text'=>'ByPoistaTv', 'user'=>$u, 'date'=>$d];
		if( $toistuva and $_POST['tilanne'] == 'poista_pvm'){
			if($this->toistuvaDeletePvm($model->id, $pvm, $tid, $poisto_syy))
				$return = ['return' => 'ok'];
			else
				$return = ['return' => 'error'];
		}
		if( $toistuva and $_POST['tilanne'] == 'poista_ketju_kokonaan'){
			ToistuvatTyovuorot::model()->deleteByPk($model->id);
			$return = ['return' => 'ok'];
		}

		echo json_encode($return);
		exit;
	}

	public function actionOperatio($did_versio='did')
	{
		if( isset($_GET['did']) ){ $did_versio = $_GET['did']; }
		$haku_from = date("Y-m-d", strtotime(Yii::app()->session['from']));
		$haku_to = date("Y-m-d", strtotime(Yii::app()->session['to']));
		$asetukset = Asetukset::model()->findByPk(1);

		if(isset($_POST['checkThis']))
		{
			$did = $this->renderPartial($did_versio,array(
				'pvm'=>$_POST['newPvm'],
				'tid'=>$_POST['newTid'],
				'from'=>'ajax',
				'asetukset'=>$asetukset
			), true);
			echo json_encode($did.'//');
			exit;
		}

		// remove
		if(isset($_POST['remove']) and isset($_SESSION['muistin'])){
			$tids	= [];
			$removed = [];
			foreach($_SESSION['muistin'] as $cp){

				$get_id 	= $this->this_id($cp);
				if(!isset($get_id['model']))
					continue;
				$model 		= $get_id['model'];
				$toistuva 	= $get_id['toistuva'];
				$pvm 		= $get_id['pvm'];
				$tid 		= $get_id['tid'];
				$removed[] 	= $cp;

				if( $toistuva ){
					$u		= Yii::app()->user->nimi;
					$d		= date("d.m.Y");
					$poisto_syy	= ['text'=>'ByOperatioRemove', 'user'=>$u, 'date'=>$d];
					$this->toistuvaDeletePvm($model->id, $pvm, $tid, $poisto_syy);
				}

				if( !$toistuva ){
					if( !$this->tyopari_poisto($model, [$model->tid => $model->tid]) ){
						$this->tvDeleteLog($model);
						$model->deleteByPk($model->id);
					}
				}
			}
			$tv_arr = $this->tv_arr($haku_from, $haku_to, $tids, [], true, []);
			$return = ['poistettu' => $removed, 'tv_arr' => $tv_arr, 'tids' => $tids];
			echo json_encode($return);
			exit;
		}

		// copy
		if(isset($_POST['copy']) and isset($_SESSION['muistin'])){
			$tids	= [];
			$site = Yii::app()->createController('Site');
			foreach($_SESSION['muistin'] as $cp){

				$get_id 	= $this->this_id($cp);
				if(!isset($get_id['model']))
					continue;
				$model 		= $get_id['model'];
				$toistuva 	= $get_id['toistuva'];
				$pvm 		= $get_id['pvm'];
				$tid 		= $get_id['tid'];
				$tids[$tid]	= $tid;
				$tids[$_POST['newTid']]	= $_POST['newTid'];

				$vanha_pvm = $model->pvm;

				$tv_new = new Tyovuoroot;
				$tv_new->attributes = $model->attributes;
				$tv_new->pvm = date("d.m.Y",strtotime($_POST['newPvm']));
				$tv_new->tid = $_POST['newTid'];
				$tv_new->tyopaari = '';
				if($tv_new->save()){
					// <-- LOG
					$model_log 	= 'Tyovuoroot';
					$name_log 	= 'Työvuorot';
					$status_log 	= 'NewByCopy';
					$old_values = json_encode($model->attributes);
					$new_values = json_encode($tv_new->attributes);
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
					//     LOG -->
				}
			
			}

			$tv_arr = $this->tv_arr($haku_from, $haku_to, $tids, [], true, []);
			$return = ['tv_arr' => $tv_arr, 'tids' => $tids];
			echo json_encode($return);
			exit;

		}
		// <-- Siirto
		if(isset($_POST['cut']) and isset($_SESSION['muistin'])){
			$tids	= [$_POST['newTid']];
			$site = Yii::app()->createController('Site');
			foreach($_SESSION['muistin'] as $cp){

				$get_id 	= $this->this_id($cp);
				if(!isset($get_id['model']))
					continue;
				$model 		= $get_id['model'];
				$toistuva 	= $get_id['toistuva'];
				$pvm 		= $get_id['pvm'];
				$tid 		= $get_id['tid'];
				$tids[$tid]	= $tid;

				if( $toistuva  ){
					$u		= Yii::app()->user->nimi;
					$d		= date("d.m.Y");
					$poisto_syy	= ['text'=>'ByOperatioCut', 'user'=>$u, 'date'=>$d];
					$this->toistuvaDeletePvm($model->id, $pvm, $tid, $poisto_syy);
				}

				$tv_new = new Tyovuoroot;
				$tv_new->attributes = $model->attributes;
				$tv_new->pvm = date("d.m.Y",strtotime($_POST['newPvm']));
				$tv_new->tid = $_POST['newTid'];
				$tv_new->tyopaari = '';
				if($tv_new->save()){
					if( !$toistuva ){
						foreach(json_decode($model->tyopaari, true) as $tv_id => $tp_tid)
							$tids[$tp_tid]	= $tp_tid;
						if( !$this->tyopari_poisto($model, [$model->tid => $model->tid]) ){
							$this->tvDeleteLog($model);
							$model->deleteByPk($model->id);
						}
					}

					// <-- LOG
					$model_log 	= 'Tyovuoroot';
					$name_log 	= 'Työvuorot';
					$status_log 	= 'Move';	
					$old_values = json_encode($model->attributes);
					$new_values = json_encode($tv_new->attributes);
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
					//     LOG -->

				} else {
					echo json_encode(['error' => $tv_new->getErrors()]);
					exit;
				}

			} // foreach

			$tv_arr = $this->tv_arr($haku_from, $haku_to, $tids, [], true, []);
			$return = ['poistettu' => $_SESSION['muistin'], 'tv_arr' => $tv_arr, 'tids' => $tids];
			echo json_encode($return);
			exit;
		}
		//     Siirto -->
	}

	public function VirtualtoTV($toistuva_id, $tid, $pvm, $tilanne, $poisto_by)
	{

		$ttv = ToistuvatTyovuorot::model()->findByPk($toistuva_id);
		$model = new Tyovuoroot;
		$cleared_attr = $this->compareToistuvaAttributes($model->attributes, $ttv->attributes);
		$model->attributes = $cleared_attr;
		$model->tid = $tid;
		$model->pvm = $pvm;
		$model->tyopaari = '';
		if( isset($tilanne['peruutettu']) and $tilanne['peruutettu'] > 0 )
			$model->peruutettu = $tilanne['peruutettu'];

		if( isset($tilanne['laskutettu']) ){
			$model->laskutettu = 1;
			$model->lasku_id = $tilanne['lasku_id'];
		}

		if($model->save()){
			// <-- LOG
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'ToistuvaKetjustaPeruutamisessa';
			$old_values 	= null;
			$new_values = json_encode($model->attributes);
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			//     LOG -->
		}

		$kuka = '';
		if(isset(Yii::app()->user->asiakas)){
			$a = Asiakkaat::model()->findbypk(Yii::app()->user->asiakas);
			if( isset($a->id) and $a->tyyppi == 'yritys' )
				$kuka = $a->yrityksen_nimi;
			if( isset($a->id) and $a->tyyppi == 'henkilo' )
				$kuka = $a->yhteyshenkilo;
		}
		if(isset(Yii::app()->user->nimi))
			$kuka = Yii::app()->user->nimi;

		$u		= $kuka;
		$d		= date("d.m.Y");
		$poisto_syy	= ['text'=> $poisto_by, 'user' => $u, 'date' => $d];
		if($this->toistuvaDeletePvm($toistuva_id, $pvm, $tid, $poisto_syy))
			return true;
		else
			return false;

	}

	public function actionPois_pvm_ketjusta($toistuva_id, $tid, $pvm, $peruuttaminen)
	{

		if( (int)$peruuttaminen > 0 ){

			$tilanne 	= ['peruutettu' => (int)$peruuttaminen];
			$poisto_by	= 'ByCalendarPeruutettu';
			$this->VirtualtoTV($toistuva_id, $tid, $pvm, $tilanne, $poisto_by);


		} else {

			$poisto_by 	= 'ByCalendar';
			$u		= Yii::app()->user->nimi;
			$d		= date("d.m.Y");
			$poisto_syy	= ['text'=> $poisto_by, 'user' => $u, 'date' => $d];
			$this->toistuvaDeletePvm($toistuva_id, $pvm, $tid, $poisto_syy);

		}

		$toistuva = ToistuvatTyovuorot::model()->findbypk($toistuva_id);
		// <-- Tids
		$tids = [];
		$tids[$tid] = $tid;
		if( is_array(json_decode($toistuva->tyopaari, true)) ){
			foreach(json_decode($toistuva->tyopaari, true) as $tp_tid)
				$tids[$tp_tid] = $tp_tid;
		}

		$pvm_from 	= date("Y-m-d", strtotime($pvm));
		$pvm_to 	= date("Y-m-d", strtotime($pvm));
		$tv_arr 	= $this->tv_arr($pvm_from, $pvm_to, $tids, [], true, []);
		echo json_encode(['return' => 'ok', 'tv_arr' => $tv_arr]);
		exit;
	}

	public function actionPalauta_pvm_kejuun($toistuva_id, $tid, $pvm)
	{
		if($this->toistuvaRestorePvm($toistuva_id, $pvm, $tid)){
			$toistuva = ToistuvatTyovuorot::model()->findbypk($toistuva_id);
			// <-- Tids
			$tids = [];
			if( is_array(json_decode($toistuva->tyopaari, true)) ){
				foreach(json_decode($toistuva->tyopaari, true) as $tp_tid)
					$tids[$tp_tid] = $tp_tid;

				$tids[$tid] = $tid;
			} else {
				$tids[$tid] = $tid;
			}

			$pvm_from = date("Y-m-d", strtotime($pvm));
			$pvm_to = date("Y-m-d", strtotime($pvm));
			$tv_arr = $this->tv_arr($pvm_from, $pvm_to, $tids, [], true, []);
			echo json_encode(['return' => 'ok', 'tv_arr' => $tv_arr]);
		} else {
			echo json_encode(['return' => 'error']);
		}

		exit;
	}

	protected function checkOlemassaTv($toistuva, $pvm, $tid){
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				pvm='".date("d.m.Y", strtotime($pvm))."' 
				AND tid='".$tid."'
				AND kohde='".$toistuva->kohde."'
				AND alku='".$toistuva->alku."'
				AND loppu='".$toistuva->loppu."'
				AND status='".$toistuva->status."'
			";
			$tv = Tyovuoroot::model()->find($criteria);
			if( isset($tv->id) ){
				return true;
			}
		return false;
	}

	public function toistuvaRestorePvm($id, $pvm, $tid)
	{
		$toistuva = ToistuvatTyovuorot::model()->findbypk($id);
		if(isset($toistuva->id)){
			// <-- Onko oleva samanlainen
			if( $this->checkOlemassaTv($toistuva, $pvm, $tid) ){
				echo json_encode(['return' => 'on_olemassa']);
				exit;
			}
			//     Onko oleva samanlainen -->

			$poistettu_pvms = [];
			if( !empty($toistuva->new_poistettu_pvm) ){
				foreach(json_decode($toistuva->new_poistettu_pvm, true) as $key => $val){
					if( $val['tid'] == $tid and $val['pvm'] == $pvm )
						continue;
					$poistettu_pvms[] = $val;
				}
			}

			$clearing = []; // Otetaan pois jos on samanlainen
			foreach ($poistettu_pvms as $key => $value){
			  if(!in_array($value, $clearing))
			    $clearing[] = $value;
			}

			ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('new_poistettu_pvm'=>json_encode($clearing)));
			$after_update = ToistuvatTyovuorot::model()->findByPk($toistuva->id);
			// <-- LOG
			$model_log 	= 'ToistuvatTyovuorot';
			$name_log 	= 'Toistuvat työvuorot';
			$status_log 	= 'Update';	
			$old_values = json_encode($toistuva->attributes);
			$new_values = json_encode($after_update->attributes);
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			//     LOG -->
			return true;
		}

		return false;
	}

	public function actionPto_muutos($id)
	{
		ToistuvatTyovuorot::model()->updatebypk($id, array('pto'=>$_POST['pto']));
		echo json_encode('ok');
		exit;
	}

	public function toistuvaDeletePvm($id, $pvm, $tid, $syy='')
	{
		$toistuva = ToistuvatTyovuorot::model()->findbypk($id);
		if(isset($toistuva->id)){
			$poistettu_pvms = [];
			if( !empty($toistuva->new_poistettu_pvm) ){
				foreach(json_decode($toistuva->new_poistettu_pvm, true) as $key => $val)
					$poistettu_pvms[] = $val;
			}

			$poistettu_pvms[] = ['tid'=>$tid, 'pvm'=>date("d.m.Y", strtotime($pvm)), 'syy'=>$syy]; // Lisataan uusi

			$clearing = []; // Otetaan pois jos on samanlainen
			foreach ($poistettu_pvms as $key => $value){
			  if(!in_array($value, $clearing))
			    $clearing[] = $value;
			}

			ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('new_poistettu_pvm'=>json_encode($clearing)));
			$after_update = ToistuvatTyovuorot::model()->findByPk($toistuva->id);
			// <-- LOG
			$model_log 	= 'ToistuvatTyovuorot';
			$name_log 	= 'Toistuvat työvuorot';
			$status_log 	= 'Update';	
			$old_values = json_encode($toistuva->attributes);
			$new_values = json_encode($after_update->attributes);
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			//     LOG -->

			return true;
		}

		return false;
	}

	public function actionShowohje($id, $tv_id=null)
	{
		$asetukset = Asetukset::model()->findbypk(1);
		$tietoja = $asetukset->tyovuoro_tietoja_mobiilisovellukseen;
		$m = Kohteet::model()->findbypk($id);
		if($m === null){
			//throw new CHttpException(404, 'Kohdetta '.$id.' ei löydy');
			echo json_encode('Kohdetta '.$id.' ei löydy');
			exit;
		}

		$k = explode("//",$m->kenella_on_avain);

		$tyo_erittelyt = json_decode($m->tyo_erittelyt, true);
		if($tv_id !== null){
			$tv = Tyovuoroot::model()->find(" id='".$tv_id."' AND kohde='".$id."' ");
			if(isset($tv->id)){
				$tyo_erittelyt = json_decode($tv->tyo_erittelyt, true);
			}
		}

		  $ohje = '';
		if(isset($k[1]))
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."<br>";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."<br>";
		if(!empty($m->aikataulu))
		  $ohje .= "<br>Aikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "<br>Toimenpiteet: ".$m->toimenpiteet;
		if(!empty($m->tietoja)){
		  $ohje .= "<br>Tietoja: ".$m->tietoja;
		  $tietoja = $m->tietoja;
		}
		if(!empty($m->muut))
		  $ohje .= "<br>Muut: ".$m->muut;
		echo json_encode(array($ohje,$tietoja,$m->arvioitu_kesto,$m->osoite,$m->pnumero,$m->kaupunki,$tyo_erittelyt,$m->puh_nro,$m->email));
	
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	protected function updateAndDelete($toistuva_id, $item){

			$fp = fopen(Yii::app()->user->domain.'_migratio.log', "a");

			// Delete
			$criteria=new CDbCriteria;
			$criteria->select = "id";
			$criteria->condition = " 
				DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '".date("Y-m-d", strtotime($item['pvm']." this week monday"))."' 
				AND toistuva_id='".$toistuva_id."' 
			";
			$tvdel = Tyovuoroot::model()->findAll($criteria);

			$mytext = "POISTETAAN Työvuorot jolla toistuva_id=".$toistuva_id." ja PVM >= ".date("Y-m-d", strtotime($item['pvm']." this week monday"))." \r\n";
			fwrite($fp, $mytext);

			foreach ($tvdel as $v) {
				Tyovuoroot::model()->deletebypk($v->id);
			}

			// Update
			$criteria=new CDbCriteria;
			$criteria->select = "id";
			$criteria->condition = " toistuva_id!=0 AND toistuva_id='".$toistuva_id."' ";
			$tvupd = Tyovuoroot::model()->findAll($criteria);

			$mytext = "MUOKATAAN Työvuorot jolla toistuva_id=".$toistuva_id." --> toistuva_id=0 \r\n";
			fwrite($fp, $mytext);

			foreach ($tvupd as $v) {
				Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
			}

	}

	/**
	 * Page for virtual migration script requests.
	 */
	public function actionVirtual_migration($reset = null)
	{
		if ($reset) {
			$vmigrate = Yii::createComponent('VirtualMigration');
			$vmigrate->clearSessionVars();
		}
		$this->render('virtual_migration');
	}

	/**
	 * New migration script, based on actionBeta (per stage mass modifications),
	 * modified to loop per chain and do all work on a single chain at once.
	 * Called by AJAX from actionVirtual_migration() and associated view.
	 */
	public function actionVmigrate_ajax_next(?int $step = null)
	{
		$vmigrate = Yii::createComponent('VirtualMigration', $step);
		$results = $vmigrate->doNextStep();
		print_r(json_encode($results));
  }

  public function actionFind_past_chains()
  {
    if (!isset($_POST['find_past_chains_start'])) {
      return $this->render('find_past_chains');
    }

    /** @var object Current status */
    $s = (object)[
      'current' => 0,
      'total' => 0,
      'created' => 0,
      'deleted' => 0
    ];

    /** @var array Output buffer */
    $output_buffer = [];

    /** @var array Previous flush time (hrtime, sec::microsec), to limit output flush interval. */
    $previous_flush = [0, 0];

    /**
     * Add output to the buffer.
     * @param int $type      0:debug, 1:default, 2:primary/modification, 3:error
     * @param string $fmt    Format for sprintf.
     * @param mixed ...$args Optional args for sprintf.
     */
    $fnbuffer = function (int $type, string $fmt = null, ...$args) use (&$output_buffer) {
      array_unshift($args, $fmt);
      $output_buffer[] = [
        'text' => call_user_func_array('sprintf', $args),
        'type' => min(3, max(0, $type))
      ];
    };

    /**
     * Flush output buffer and optionally add output to the buffer first.
     * @param int $type      0:debug, 1:default, 2:primary/modification, 3:error
     * @param string $fmt    Format for sprintf.
     * @param mixed ...$args Optional args for sprintf.
     */
    $fnflush = function(int $type = 0, string $fmt = null, ...$args) use (&$output_buffer, &$s, &$previous_flush, $fnbuffer) {
      if (!empty($fmt)) {
        array_unshift($args, $type, $fmt);
        call_user_func_array($fnbuffer, $args);
      }
      echo json_encode([
        'lines' => $output_buffer,
        'current' => $s->current,
        'total' => $s->total,
        'created' => $s->created,
        'deleted' => $s->deleted
      ]);
      $previous_flush = hrtime();
      $output_buffer = [];
      ob_flush();
      flush();
    };

    /**
     * Add output to the buffer and flush if elapsed time exceeds cutoff.
     * @param int $type      0:debug, 1:default, 2:primary/modification, 3:error
     * @param string $fmt    Format for sprintf.
     * @param mixed ...$args Optional args for sprintf.
     */
    $fnout = function (int $type, string $fmt = null, ...$args) use (&$output_buffer, &$s, $previous_flush, $fnflush, $fnbuffer) {
      // 1. Get current hrtime and adjust for negative calc.
      // 2. Compare to previous flush time to check if elapsed time has exceeded cutoff.
      // 3. Add type and fmt to args array, and call flush if time has exceeded cutoff; otherwise, buffer.
      static $cutoff = 500000000; // 0.5 sec
      $time = hrtime();
      if ($previous_flush[1] > $time[1]) { $previous_flush[0]++; $previous_flush[1] = -$previous_flush[1]; }
      $cutoff_exceeded = ($time[0] != $previous_flush[0] || $time[1] - $previous_flush[1] > $cutoff);
      array_unshift($args, $type, $fmt);
      call_user_func_array(($cutoff_exceeded ? $fnflush : $fnbuffer), $args);
    };

    /** @var \CDbConnection */
    $db = Yii::app()->db1;

    /** @var array Zero indexed past shifts array */
    $tvr = $db->createCommand(
      "SELECT * FROM sivex_tvuoro WHERE pvm IS NOT NULL AND
      IFNULL(STR_TO_DATE(pvm, '%d.%m.%Y'), DATE(pvm)) < '2020-04-10'
      ORDER BY IFNULL(STR_TO_DATE(pvm, '%d.%m.%Y'), DATE(pvm)) ASC"
    )->queryAll();

    $s->total = count($tvr);
    $fnout(1, "Haettiin menneet työvuorot. Yhteensä: %d", $s->total);

    /** @var CDbSchema */
    $schema = $db->getSchema();

    /** @var CDbTableSchema */
    $table = $schema->getTable('sivex_tvuoro');
    if (!$table)
      return $fnflush(3, 'Taulua sivex_tvuoro ei löytynyt.');

    /** @var array List of column names to compare */
    $columns = array_diff($table->getColumnNames(), ['id', 'time', 'pvm', 'alku']);
    if (empty($columns))
      return $fnflush(3, 'Sarake array on tyhjä.');

    // Start looping shifts from the earliest one.
    for ($s->current; $s->current < $s->total - 1; $s->current++) {
      if ($s->current > 50) {
        $fnflush();
        return;
      }

      $item = (object)$tvr[$s->current];
      $matches = [];

      for ($i = $s->current + 1; $i < $s->total; $i++) {
        $next = (object)$tvr[$i];
        $match = true;
        foreach ($columns as $column) {
          if ($next->$column != $item->$column) {
            $match = false;
            break;
          }
        }
        if ($match)
          $matches[] = $next;
      }

      if (count($matches) == 0) {
        $fnout(0, "Some debug info!");
        continue;
      }

      $fnout(count($matches) > 0 ? 2 : 1, "{$s->current}: Työvuoro ID %s (tid %s, pvm %s): %d vastaavaa työvuoroa", $item->id, $item->tid, $item->pvm, count($matches));
    }
  }

  /**
   * Main action for the Freshdesk view.
   *
   * Renders the view normally unless some of the parameters are provided. If
   * any parameter is provided, then this action is considered an AJAX action,
   * which will echo the results in JSON encoded format.
   *
   * If parameters are provided for more than one API request, then the first
   * one takes priority and the other ones are ignored.
   *
   * @param int $ticket_id
   * If not null, and positive int, that ticket ID is returned (encoded echo).
   *
   * @param int $page
   * If not null, that page in list of tickets is returned.
   *
   * @param int $per_page
   * Specifies the amount of items per page when $page is specified. The maximum
   * seems to be either 100 or 300; however, it's better to do smaller batches.
   *
   * @return mixed
   * Freshdesk view, or null with echoed results if parameters are provided.
   */
  public function actionFreshdesk($ticket_id = null, $page = null, $per_page = 10)
  {
    /** @var Freshdesk */
    $freshdesk = Yii::createComponent('Freshdesk');

    if (is_numeric($ticket_id)) {
      // TODO
      echo json_encode(['errors' => 'not yet implemented']);
      return;
    }

    // If $page is provided, get a list of tickets.
    elseif (is_numeric($page)) {

      // Tickets are temporarily cached here
      $per_page = is_numeric($per_page) ? $per_page : 10;
      $first_index = ($page - 1) * $per_page;
      $tickets = Yii::app()->session['freshdesk_tickets'] ?? [];
      $tickets_eod = Yii::app()->session['freshdesk_tickets_eod'] ?? 0;
      $update_times = Yii::app()->session['freshdesk_tickets_update_times'] ?? [];
      $requested = array_splice($tickets, $first_index, $per_page);
      $old_tickets_on_page = false;

      // var_dump($tickets_eod, $first_index + count($requested));exit;
      if ($tickets_eod != 0 && $tickets_eod < $first_index + count($requested)) {
        Freshdesk::log('Empty page requested: %d', $page);
        echo json_encode(['eod' => true, 'errors' => 'Empty page requested.']);
        return;
      }

      $fn_index_loop = function (bool $check_exists, callable $fn) use ($tickets, $first_index, $per_page) {
        foreach (range($first_index, $first_index + $per_page - 1) as $index)
          if (($check_exists && !isset($tickets[$index])) || false === call_user_func($fn, $index))
            break;
      };

      $fn_index_loop(true, function ($index) use ($page, $update_times, &$old_tickets_on_page) {
        if (time() - ($update_times[$index] ?? 0) > 900) { // 900 seconds = 15 minutes
          $time_str = $update_times[$index] ?? 0 <= 0 ? '' : ' Previously updated: ' . date('Y-m-d H:i:s', $update_times[$index]);
          Freshdesk::log('Some tickets on page %s are old. Requesting fresh data from Freshdesk.%s', $page, $time_str);
          $old_tickets_on_page = true;
          return false;
        }
      });

      // If not enough items from array_splice, either this data has not yet
      // been fetched, or end has been reached.
      $is_eod = ($tickets_eod != 0 && $tickets_eod == $first_index + count($requested));
      if ($old_tickets_on_page || (count($requested) != $per_page && !$is_eod)) {

        $requested = $freshdesk->listTickets(null, null, $page, $per_page, null, ['requester', 'description'], 'updated_at', 'desc');
        $updated = false;

        // Check if end of data, so that repeat requests are not made.
        if (empty($requested)) {
          Freshdesk::log('Empty page requested: %d', $page);
          echo json_encode(['eod' => true, 'errors' => 'Empty page requested.']);
          return;
        } elseif (count($requested) != $per_page) {
          Freshdesk::log("Page %s requested, and end of data reached. (%d items received).", $page, count($requested));
          Yii::app()->session["freshdesk_tickets"] = array_merge($tickets, $requested);
          Yii::app()->session['freshdesk_tickets_eod'] = $first_index + count($requested);
          $updated = true;
        } else {
          Freshdesk::log("Page %s requested (%d items), saving to session.", $page, count($requested));
          $tickets = array_merge(array_splice($tickets, 0, $first_index), $requested, array_splice($tickets, $page * $per_page));
          Yii::app()->session["freshdesk_tickets"] = $tickets;
          $updated = true;
        }

        if ($updated) {
          // Set updated times.
          $fn_index_loop(false, function($index) use (&$update_times) { $update_times[$index] = time(); });
          Yii::app()->session['freshdesk_tickets_update_times'] = $update_times;
          Freshdesk::log('Updated times for tickets on page %s with %d items. Valid for 900 seconds (15 minutes).', $page, count($requested));
        }
      } else {
        Freshdesk::log("Page %s loaded from session (%d items%s).", $page, count($requested), $is_eod ? '; end of data' : '');
      }

      echo json_encode($requested);
      return;
    } else {
      return $this->render('freshdesk', [
        'freshdesk' => $freshdesk,
        'tickets' => Yii::app()->session['freshdesk_tickets']
      ]);
    }
  }

	public function actionBeta($kohteet_siivous = [], $kohde = '', $asiakas = '', $mode = null, $stage = null)
	{
		// <-- Ketjun kasikorjaus
		$startday 	= date("Y-m-d", strtotime("next monday"));

		//$startWeek = date("YW", strtotime("next monday"));
		if ( $stage == 1 ) {
			$fp = fopen(Yii::app()->user->domain.'_migratio.log', "w+");
			// <-- Otetaan pois aivan turhoja milijona
/*
			Yii::app()->db1->createCommand(
				"DELETE FROM sivex_tvuoro WHERE toistuva_id!='0' 
				AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '".date("Y-m-d", strtotime($startday." +6 month"))."'")
			->execute();
*/

			// Optimisointi
			$query = "OPTIMIZE TABLE sivex_tvuoro";
			$command = Yii::app()->db1->createCommand($query);
			$command->execute();

			$query = "OPTIMIZE TABLE toistuvat_tyovuorot";
			$command = Yii::app()->db1->createCommand($query);
			$command->execute();

			$mytext = "Optimisointi valmis\r\n";
			$mytext .= "STARTDAY on ".date("d.m.Y", strtotime("next monday")).", joka jakaa logikka kahden osan. Toinen on menneisyys ja toinen tulevaisuus \r\n";
			fwrite($fp, $mytext);

			echo 'STAGE 1 - OPTIMISOINTI valmis.<br>';
			echo CHtml::link('<h4>Seuraava</h4>', array('beta', 'mode' => $mode, 'stage' => 2));
			exit;
		}

		if ( $stage == 2 ) {
			$fp = fopen(Yii::app()->user->domain.'_migratio.log', "a");

			$tvr = Yii::app()->db1->createCommand()
				//->limit("100")
				->select("id, tid, pvm, toistuva_id")
				->from("sivex_tvuoro")
				->group("toistuva_id")
				->order("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) ASC")
				// <-- Etsitään aktiivisiä ketjua
				->where("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) BETWEEN '$startday' AND '".date("Y-m-d", strtotime($startday." +1 month"))."'")
				->andWhere("toistuva_id!=0")
				->queryAll();

			$toistuvat = Yii::app()->db1->createCommand()
				->select("id,tid,tyopaari")
				->from("toistuvat_tyovuorot")
				//->where()
				->queryAll();

			$toist_arr = [];
			foreach($toistuvat as $item)
				$toist_arr[$item['id']] = $item;

			$korjattu = 0;
			echo '<h3>Yhteensä '.count($tvr).'</h3>';
			foreach($tvr as $item){
				$toistuva_id = $item['toistuva_id'];
				if(isset($toist_arr[$toistuva_id])){
					$tids = [];
					$tids[$toist_arr[$toistuva_id]['tid']] = $toist_arr[$toistuva_id]['tid'];
					foreach(json_decode($toist_arr[$toistuva_id]['tyopaari'], true) as $tid)
						$tids[$tid] = $tid;

					if( !in_array($item['tid'], $tids) ){

						// <-- Jos EI työparia
						if( empty($toist_arr[$toistuva_id]['tyopaari'])){
							// ONGELMA 

							ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array( 'tid' => $item['tid'], 'pfrom' => date("d.m.Y", strtotime($item['pvm']." this week monday")) ));
							$mytext = "TID ongelma, Ketju ".$toistuva_id.", Uusi TID on - ".$item['tid'].", Uusi aloituspäivä: ".date("d.m.Y", strtotime($item['pvm']." this week monday"))." \r\n";
							fwrite($fp, $mytext);

							$this->updateAndDelete($toistuva_id, $item);

						} else {

							ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array( 'pfrom' => date("d.m.Y", strtotime($item['pvm']." this week monday")) ));
							$mytext = "Ketju ".$toistuva_id.", Uusi aloituspäivä: ".date("d.m.Y", strtotime($item['pvm']." this week monday"))." \r\n";
							fwrite($fp, $mytext);

							$this->updateAndDelete($toistuva_id, $item);
						}

					} else {

						ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array( 'pfrom' => date("d.m.Y", strtotime($item['pvm']." this week monday")) ));
						$mytext = "Ketju ".$toistuva_id.", Uusi aloituspäivä: ".date("d.m.Y", strtotime($item['pvm']." this week monday"))." \r\n";
						fwrite($fp, $mytext);

						$this->updateAndDelete($toistuva_id, $item);
					}

				} else {
					echo 'Ketjussa: '.$toistuva_id.' ONGELMA<br>';
				}
			}

			echo 'STAGE valmis - Korjattu: '.$korjattu.' kpl.<br>';
			echo CHtml::link('<h4>Seuraava</h4>', array('beta', 'mode' => $mode, 'stage' => 3));
			exit;
		}

		if ( $stage == 3 ) {
			$fp = fopen(Yii::app()->user->domain.'_migratio.log', "a");

			$tvr = Yii::app()->db1->createCommand()
				->select("id, tid, pvm, toistuva_id")
				->from("sivex_tvuoro")
				->group("toistuva_id")
				->order("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) DESC")
				->andWhere("toistuva_id!=0")
				->queryAll();

			$toistuvat = Yii::app()->db1->createCommand()
				->select("id,tid,tyopaari, pfrom, pto")
				->from("toistuvat_tyovuorot")
				//->where()
				->queryAll();

			$toist_arr = [];
			foreach($toistuvat as $item)
				$toist_arr[$item['id']] = $item;

			$korjattu = 0;
			echo '<h3>Yhteensä '.count($tvr).'</h3>';
			foreach($tvr as $item){
				$toistuva_id = $item['toistuva_id'];
				if(isset($toist_arr[$toistuva_id])){

					if( date("Ymd", strtotime($toist_arr[$toistuva_id]['pfrom'])) > date("Ymd", strtotime($startday)) ){

						// Delete
						$criteria=new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = " 
							DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '".date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom']))."' 
							AND toistuva_id='".$toistuva_id."' 
						";
						$tvdel = Tyovuoroot::model()->findAll($criteria);

						$mytext = "POISTETAAN Työvuorot jolla toistuva_id=".$toistuva_id." ja PVM >= kun ketjun alkamispäivä - ".date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom']))." \r\n";
						fwrite($fp, $mytext);
						echo $mytext.'<br>';

						foreach ($tvdel as $v)
							Tyovuoroot::model()->deletebypk($v->id);

						// Update
						$criteria=new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = " toistuva_id!=0 AND toistuva_id='".$toistuva_id."' ";
						$tvupd = Tyovuoroot::model()->findAll($criteria);

						$mytext = "MUOKATAAN Työvuorot jolla toistuva_id=".$toistuva_id." --> toistuva_id=0 \r\n";
						fwrite($fp, $mytext);
						echo $mytext.'<br>';

						foreach ($tvupd as $v)
							Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));

					}

					if( date("Ymd", strtotime($toist_arr[$toistuva_id]['pto'])) < date("Ymd", strtotime($startday)) ){

						ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
						$mytext = "Ketju ".$toistuva_id.", POISTETAAN, koska ketjun lopetuspäivä ajemmin kun ".date("d.m.Y", strtotime($startday))." \r\n";
						fwrite($fp, $mytext);

						// Update
						$criteria=new CDbCriteria;
						$criteria->select = "id";
						$criteria->condition = " toistuva_id!=0 AND toistuva_id='".$toistuva_id."' ";
						$tvupd = Tyovuoroot::model()->findAll($criteria);

						$mytext = "MUOKATAAN Työvuorot jolla toistuva_id=".$toistuva_id." --> toistuva_id=0 \r\n";
						fwrite($fp, $mytext);

						foreach ($tvupd as $v)
							Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));


					} else {

						// Toistuva pto on > startday
						if( date("Ymd", strtotime($item['pvm'])) < date("Ymd", strtotime($startday)) ){

							ToistuvatTyovuorot::model()->deletebypk($toistuva_id);

							$mytext = "Ketju ".$toistuva_id.", POISTETAAN, koska viimeinen työvuoro oli ajemmin kun ".date("d.m.Y", strtotime($startday))." \r\n";
							fwrite($fp, $mytext);

							// Update
							$criteria=new CDbCriteria;
							$criteria->select = "id";
							$criteria->condition = " toistuva_id!=0 AND toistuva_id='".$toistuva_id."' ";
							$tvupd = Tyovuoroot::model()->findAll($criteria);

							$mytext = "MUOKATAAN Työvuorot jolla toistuva_id=".$toistuva_id." --> toistuva_id=0 \r\n";
							fwrite($fp, $mytext);

							foreach ($tvupd as $v) {
								Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
							}

						} else {

							$criteria=new CDbCriteria;
							$criteria->order = "DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) ASC";
							$criteria->select = "pvm";
							$criteria->limit = "1";
							$criteria->condition = " 
								toistuva_id='".$toistuva_id."' 
							";
							$tvm = Tyovuoroot::model()->find($criteria);
							if( isset($tvm->pvm) and date("Ymd", strtotime($tvm->pvm)) > date("Ymd", strtotime($startday)) ){

								ToistuvatTyovuorot::model()->updatebypk($toistuva_id, array('pfrom' => $tvm->pvm));

								// Delete
								$criteria=new CDbCriteria;
								$criteria->select = "id";
								$criteria->condition = " 
									DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '".date("Y-m-d", strtotime($tvm->pvm))."' 
									AND toistuva_id='".$toistuva_id."' 
								";
								$tvdel = Tyovuoroot::model()->findAll($criteria);

								$mytext = "POISTETAAN Työvuorot jolla toistuva_id=".$toistuva_id." ja PVM >= kun ketjun alkamispäivä - ".date("Y-m-d", strtotime($toist_arr[$toistuva_id]['pfrom']))." \r\n";
								fwrite($fp, $mytext);
								echo $mytext.'<br>';

								foreach ($tvdel as $v)
									Tyovuoroot::model()->deletebypk($v->id);

								// Update
								$criteria=new CDbCriteria;
								$criteria->select = "id";
								$criteria->condition = " toistuva_id!=0 AND toistuva_id='".$toistuva_id."' ";
								$tvupd = Tyovuoroot::model()->findAll($criteria);

								$mytext = "MUOKATAAN Työvuorot jolla toistuva_id=".$toistuva_id." --> toistuva_id=0 \r\n";
								fwrite($fp, $mytext);
								echo $mytext.'<br>';

								foreach ($tvupd as $v)
									Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));

								echo '&nbsp;&nbsp; '.$tvm->pvm.'<br>';
							}
						}

					}

				} else {

					// Update
					$criteria=new CDbCriteria;
					$criteria->select = "id";
					$criteria->condition = " toistuva_id!=0 AND toistuva_id='".$toistuva_id."' ";
					$tvupd = Tyovuoroot::model()->findAll($criteria);

					$mytext = "MUOKATAAN Työvuorot jolla toistuva_id=".$toistuva_id." --> toistuva_id=0 \r\n";
					fwrite($fp, $mytext);

					foreach ($tvupd as $v)
						Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));

				}

			}

			echo 'STAGE valmis - korjattu<br>';
			echo CHtml::link('<h4>Seuraava</h4>', array('beta', 'mode' => $mode, 'stage' => 4));
			exit;
		}

		if ( $stage == 4 ) {

			$fp = fopen(Yii::app()->user->domain.'_migratio.log', "a");

			$tstv = Yii::app()->db1->createCommand()
				->select("id, pfrom, pto")
				->from("toistuvat_tyovuorot")
				->where("DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) < '$startday' AND DATE(STR_TO_DATE(pto, '%d.%m.%Y')) < '$startday'")
				->queryAll();

			echo '<h3>Yhteensä '.count($tstv).'</h3>';
			foreach($tstv as $item){
				//echo 'Ketju: '.$item['id'].', Pfrom: '.$item['pfrom'].', Pto: '.$item['pto'].'<br>';

				$toistuva_id = $item['id'];
				ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
				$mytext = "Ketju ".$toistuva_id.", POISTETAAN, koska ketjun lopetuspäivä ajemmin kun ".date("d.m.Y", strtotime($startday))." \r\n";
				fwrite($fp, $mytext);

				// Update
				$criteria=new CDbCriteria;
				$criteria->select = "id";
				$criteria->condition = " toistuva_id!=0 AND toistuva_id='".$toistuva_id."' ";
				$tvupd = Tyovuoroot::model()->findAll($criteria);

				$mytext = "MUOKATAAN Työvuorot jolla toistuva_id=".$toistuva_id." --> toistuva_id=0 \r\n";
				fwrite($fp, $mytext);

				foreach ($tvupd as $v)
					Tyovuoroot::model()->updatebypk($v->id, array('toistuva_id' => '0'));
			}

			$tstv = Yii::app()->db1->createCommand()
				->select("id, pfrom, pto")
				->from("toistuvat_tyovuorot")
				->where("DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) < '$startday'")
				->andWhere("id NOT IN(SELECT toistuva_id FROM sivex_tvuoro)")
				->queryAll();

			echo '<h3>Yhteensä '.count($tstv).'</h3>';
			foreach($tstv as $item){
				$toistuva_id = $item['id'];
				//echo 'Ketju: '.$item['id'].', Pfrom: '.$item['pfrom'].', Pto: '.$item['pto'].'<br>';
				ToistuvatTyovuorot::model()->deletebypk($toistuva_id);
				$mytext = "Ketju ".$toistuva_id.", POISTETAAN, koska ketjusta ei löytyi yhtään työvuoroa \r\n";
				fwrite($fp, $mytext);
			}

			echo CHtml::link('<h4>Seuraava</h4>', array('beta', 'mode' => $mode, 'stage' => 5));
			exit;
		}

		// <-- Poistettu_pvm redirect to another field
		if ($stage == 5) {
			$fp = fopen(Yii::app()->user->domain.'_migratio.log', "a");

			$tvr = Yii::app()->db1->createCommand()
				->select("poistettu_pvm,id,tyopaari,tid")
				->from("toistuvat_tyovuorot")
				->where("poistettu_pvm!='' AND new_poistettu_pvm IS NULL")
				->queryAll();
			$i = 0;
			foreach ($tvr as $arvo) {
				$i++;
				$poistetut_pvms = json_decode($arvo['poistettu_pvm'], true);
				if (count($poistetut_pvms) == 0)
					continue;

				// <-- Tids
				$tids = [];
				if (!empty($arvo['tyopaari'])) {
					foreach (json_decode($arvo['tyopaari'], true) as $tid) {
						$tids[$tid] = $tid;
					}
					$tids[$arvo['tid']] = $arvo['tid'];
				} else {
					$tids[$arvo['tid']] = $arvo['tid'];
				}
				$new_poistettu_pvm = [];
				foreach ($tids as $tid) {
					foreach ($poistetut_pvms as $k => $v) {
						if ( date("Ymd", strtotime($v)) > date("Ymd", strtotime($startday))) // Oikein
							$new_poistettu_pvm[$tid][$v] = ['tid' => $tid, 'pvm' => $v, 'syy' => ['text' => '', 'user' => '', 'date' => '']];
					}
				}
				$result = [];
				foreach ($new_poistettu_pvm as $k => $v)
					foreach ($v as $k2 => $v2)
						$result[] = $v2;
				$clearing = [];
				foreach ($result as $key => $value) {
					if (!in_array($value, $clearing))
						$clearing[] = $value;
				}
				$new_poistettu_pvm_arvo = (count($clearing) > 0)? json_encode(array_values($clearing)) : '';
				//echo 'Clearning: '.$new_poistettu_pvm_arvo.'<br>';
				ToistuvatTyovuorot::model()->updatebypk($arvo['id'], array('poistettu_pvm' => '', 'new_poistettu_pvm' => $new_poistettu_pvm_arvo));

			}

			echo CHtml::link('<h4>STAGE valmis. Seuraava</h4>', array('beta', 'mode' => $mode, 'stage' => 6));
			exit;
		}

		if ($stage == 6) {
			$fp = fopen(Yii::app()->user->domain.'_migratio.log', "a");

			Yii::app()->db1->createCommand(
			"DELETE FROM sivex_tvuoro WHERE toistuva_id!='0' AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '".date("Y-m-d", strtotime($startday))."'")
			->execute();

			Yii::app()->db1->createCommand("UPDATE sivex_tvuoro SET toistuva_id='0' WHERE toistuva_id!='0'")
			->execute();


			// Optimisointi
			$query = "OPTIMIZE TABLE sivex_tvuoro";
			$command = Yii::app()->db1->createCommand($query);
			$command->execute();

			$query = "OPTIMIZE TABLE toistuvat_tyovuorot";
			$command = Yii::app()->db1->createCommand($query);
			$command->execute();

			echo CHtml::link('<h4>STAGE valmis. Kaikki VALMIS</h4>', array('beta', 'mode' => $mode));
			exit;
		}
		//     Ketjun kasikorjaus -->
		// ------------------------------------------------

		$site = Yii::app()->createController('Site');
		$arrDate = array(1 => "Ma", 2 => "Ti", 3 => "Ke", 4 => "To", 5 => "Pe", 6 => "La", 7 => "Su");
		$asetukset = Asetukset::model()->findByPk(1);

		// <-- Reset
		if (isset($_GET['reset'])) {
			unset(Yii::app()->session['year']);
			unset(Yii::app()->session['week']);
			unset(Yii::app()->session['vkolopput']);
			unset($_SESSION['haku_asiakas']);
			unset($_SESSION['haku_kohde']);
			unset(Yii::app()->session['tyontekijat']);
			unset(Yii::app()->session['tyo_toimialue']);
			unset(Yii::app()->session['kohteiden_tyonimike']);
			unset(Yii::app()->session['tyoryhma']);

			$this->redirect(array('beta', 'mode' => $mode));
		}
		//     Reset -->

		// <-- GET haku
		if (isset($_GET['year']) or isset($_GET['week'])) {
			if (isset($_GET['year']) and !empty($_GET['year']))
				Yii::app()->session['year'] = $_GET['year'];
			if (isset($_GET['week']) and !empty($_GET['week']))
				Yii::app()->session['week'] = $_GET['week'];
			if (isset($_GET['tid']) and !empty($_GET['tid']))
				Yii::app()->session['tyontekijat'] = array($_GET['tid']);
			if (isset($_GET['tv_id']))
				$this->redirect(array('beta', 'mode' => $mode, 'tv_id' => $_GET['tv_id']));

			$this->redirect(array('beta', 'mode' => $mode));
		}
		//  GET haku -->

		// <-- Post haku
		if (isset($_POST['haku'])) {
			if (isset($_POST['kohteiden_tyonimike']) and !empty($_POST['kohteiden_tyonimike']))
				Yii::app()->session['kohteiden_tyonimike'] = $_POST['kohteiden_tyonimike'];
			if (isset($_POST['kohteiden_tyonimike']) and empty($_POST['kohteiden_tyonimike']))
				unset(Yii::app()->session['kohteiden_tyonimike']);

			if (isset($_POST['tyo_toimialue']) and !empty($_POST['tyo_toimialue']))
				Yii::app()->session['tyo_toimialue'] = $_POST['tyo_toimialue'];
			if (!isset($_POST['tyo_toimialue']))
				unset(Yii::app()->session['tyo_toimialue']);

			if (isset($_POST['tyoryhma']) and !empty($_POST['tyoryhma']))
				Yii::app()->session['tyoryhma'] = $_POST['tyoryhma'];
			if (!isset($_POST['tyoryhma']))
				unset(Yii::app()->session['tyoryhma']);

			// <-- Asiakas
			if (isset($_POST['haku_asiakas']) and !empty($_POST['haku_asiakas']))
				$_SESSION['haku_asiakas'] = $_POST['haku_asiakas'];
			if (isset($_POST['haku_asiakas']) and empty($_POST['haku_asiakas']))
				unset($_SESSION['haku_asiakas']);
			// Asiakas -->

			// <-- Kohde
			if (isset($_POST['haku_kohde']) and !empty($_POST['haku_kohde']))
				$_SESSION['haku_kohde'] = $_POST['haku_kohde'];
			if (isset($_POST['haku_kohde']) and empty($_POST['haku_kohde']))
				unset($_SESSION['haku_kohde']);
			// Kohde -->

			// <-- tyontekijat
			if (isset($_POST['tyontekijat']) and !empty($_POST['tyontekijat']))
				Yii::app()->session['tyontekijat'] = $_POST['tyontekijat'];
			if (!isset($_POST['tyontekijat']))
				unset(Yii::app()->session['tyontekijat']);
			//  tyontekijat -->

			if (isset($_POST['from']) and !empty($_POST['from']))
				Yii::app()->session['from'] = date("Y-m-d", strtotime($_POST['from']));

			if (isset($_POST['to']) and !empty($_POST['to']))
				Yii::app()->session['to'] = date("Y-m-d", strtotime($_POST['to']));

			$this->redirect(array('beta', 'mode' => $mode));
		}
		//  Post haku -->

		// <-- TYONTEKIJA MODE
		if ($mode == 'tt') {
			if (!isset(Yii::app()->session['from']))
				Yii::app()->session['from'] = date("Y-m-d");
			if (!isset(Yii::app()->session['to']))
				Yii::app()->session['to'] = date("Y-m-d", strtotime("+2 week", time()));
		}
		//    VKO MODE -->

		// <-- VKO MODE
		if ($mode == 'vko') {
			if (!isset(Yii::app()->session['year']))
				Yii::app()->session['year'] = date("Y", strtotime('this week sunday'));
			if (!isset(Yii::app()->session['week']))
				Yii::app()->session['week'] = date("W", strtotime('this week sunday'));

			$year = Yii::app()->session['year'];
			$week = sprintf("%02d", Yii::app()->session['week']);
			Yii::app()->session['week'] = $week;

			if (!isset(Yii::app()->session['vkolopput']))
				$numDays = 5;
			else
				$numDays = 7;

			Yii::app()->session['from'] = date("Y-m-d", strtotime($year . "W" . $week . '1'));
			Yii::app()->session['to'] = date("Y-m-d", strtotime($year . "W" . $week . $numDays));
		}
		//    VKO MODE -->

		// <-- HAKU
		$haku_criteria 	= [];

		// <-- kohteiden_tyonimike
		if(isset(Yii::app()->session['kohteiden_tyonimike'])){
			$criteria = new CDbCriteria();
	       		$criteria->select = "id";
	       		$criteria->condition = " 
				siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%' 
			";
			$k = Kohteet::model()->findAll($criteria);
			foreach($k as $item)
				$kohteet_siivous[] = $item->id;

		}
		//   kohteiden_tyonimike -->

		if (isset($_SESSION['haku_asiakas']) and !empty($_SESSION['haku_asiakas'])) {
			$asiakas = $_SESSION['haku_asiakas'];
			$haku_criteria[] = '
			kohde IN (
			    SELECT id FROM sivex_kohdet WHERE asiakas_id IN
   			    (
			       SELECT id FROM asiakkaat WHERE 
				yrityksen_nimi LIKE "%' . $asiakas . '%" 
				OR yhteyshenkilo LIKE "%' . $asiakas . '%" 
				OR puhelin LIKE "%' . $asiakas . '%"
			    )
			)';
		}
		if (isset($_SESSION['haku_kohde']) and !empty($_SESSION['haku_kohde'])) {
			$kohde = $_SESSION['haku_kohde'];
			$haku_criteria[] = '
			kohde IN (
			    SELECT id FROM sivex_kohdet WHERE 
				osoite LIKE "%' . $kohde . '%"
		       )';
		}
		if (isset($kohteet_siivous) and count($kohteet_siivous) > 0) {
			$impl = implode(',', $kohteet_siivous);
			$haku_criteria[] = " kohde IN ($impl) ";
		}
		//     HAKU -->

		// <-- Order tyontekijat
		if ($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0) {
			$tt_order_1 = "tekijan_nimi";
			$tt_order_2 = "sukunimi";
		} else {
			$tt_order_1 = "sukunimi";
			$tt_order_2 = "tekijan_nimi";
		}
		// Order tyontekijat -->

		$criteria = new CDbCriteria();
		$criteria->select = "id, $tt_order_1, $tt_order_2";
		$criteria->order = "$tt_order_1 ASC";
		$criteria->condition = "
			aktiivinen=1
		";

		// <-- tyo_toimialue
		if(isset(Yii::app()->session['tyo_toimialue'])){
			$arr = [];
			foreach(Yii::app()->session['tyo_toimialue'] as $it)
				$arr[] = str_replace("\\", "\\\\\\\\", json_encode($it));

			$tyo_toimialue_like = "tyo_toimialue LIKE '%".implode("%' OR tyo_toimialue LIKE '%", $arr)."%'";
			$criteria->addCondition ($tyo_toimialue_like);
		}
		//   tyo_toimialue -->

		// <-- tyoryhma
		if(isset(Yii::app()->session['tyoryhma']))
		{
			$tt_contr = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt_contr[0]->TyoryhmatTyontekijatHelper(Yii::app()->session['tyoryhma']);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			}
		}
		//   tyoryhma -->

		if (isset(Yii::app()->session['tyontekijat']) and count(Yii::app()->session['tyontekijat'] > 0)) {
			$ids = implode(",", Yii::app()->session['tyontekijat']);
			$criteria->addCondition('id IN (' . $ids . ') ');
		}

		// <-- Tyontekijat
		$tt 		= [];
		$haku_tids 	= [];
		$haku_tids[0] 	= 0; // Varaus
		$tyontekijat = Tyontekijat::model()->findAll($criteria);
		foreach ($tyontekijat as $item) {
			$tt[$item->id] = array('etusukunimi' => $item->$tt_order_1 . ' ' . $item->$tt_order_2);
			$haku_tids[$item->id] = $item->id;
		}
		//     Tyontekijat -->

		$haku_from 	= date("Y-m-d", strtotime(Yii::app()->session['from']));
		$haku_to 	= date("Y-m-d", strtotime(Yii::app()->session['to']));

		// Työsuhteet
		$tyosuhteet = Tyosuhdet::model()->findAll(" tid IN(" . implode(",", $haku_tids) . ") ");
		$vktyoaika = [];
		foreach ($tyosuhteet as $item)
			if (!empty($item->vktyoaika))
				$vktyoaika[$item->tid] = $item->vktyoaika;
		// Pyhapaivat
		$pyhapaivat = $this->pyhapaivatAll($haku_from, $haku_to);
		/*
		echo '<pre>';
		print_r( $pyhapaivat );
		echo '</pre>';
		exit;
		*/
		if ($mode == 'tt') {
			$this->render('tt', array(
				'tt'		=> $tt,
				'from'		=> $haku_from,
				'to'		=> $haku_to,
				'arrDate'	=> $arrDate,
				'site'		=> $site,
				'vktyoaika'	=> $vktyoaika,
				'haku_tids'	=> $haku_tids,
				'pyhapaivat'	=> $pyhapaivat,
				'haku_criteria' => $haku_criteria
			));
		}
		if ($mode == 'vko') {
			$this->render('vko', array(
				'tt'		=> $tt,
				'from'		=> $haku_from,
				'to'		=> $haku_to,
				'arrDate'	=> $arrDate,
				'site'		=> $site,
				'week'		=> $week,
				'year'		=> $year,
				'vktyoaika'	=> $vktyoaika,
				'haku_tids'	=> $haku_tids,
				'pyhapaivat'	=> $pyhapaivat,
				'haku_criteria' => $haku_criteria
			));
		}
	}

	public function actionGetsumbyweekall($this_sunday)
	{
		$tids		= (isset($_POST['tids']))?json_decode($_POST['tids'], true):[];
		$vko_from 	= date("Y-m-d", strtotime($this_sunday.' this week monday'));
		$vko_to 	= date("Y-m-d", strtotime($this_sunday));
		$getAll 	= $this->tv_arr($vko_from, $vko_to, $tids, [], false, ['tv_kesto']);
		$result = [];
		foreach($getAll as $k => $v)
			foreach($v as $unix => $dayarr)
				foreach($dayarr as $key => $arr)
					foreach($arr as $arr2)
						if(!isset($result[$arr2['this_tid']]))
							$result[$arr2['this_tid']] = $arr2['tv_kesto'];
						else
							$result[$arr2['this_tid']] += $arr2['tv_kesto'];

		$return 	= ['vkoAll'=>$result, 'did'=>date("Ymd", strtotime($this_sunday))];
		/*
		echo '<pre>';
		print_r($return);
		echo '</pre>';
		*/
		echo json_encode($return);
		exit;
	}

	public function pyhapaivatAll($from, $to)
	{
		$from 	= date("Y-m-d", strtotime($from));
		$to 	= date("Y-m-d", strtotime($to));
		$result = [];

		$asetuksetForAll 	= AsetuksetForAll::model()->findbypk(1);
		$vp = explode("\n",$asetuksetForAll->viralliset_pyhapaivat);
		$vp_pvms = [];
		foreach($vp as $vp_pvm)
			$vp_pvms[trim($vp_pvm)] = trim($vp_pvm);

		$el = explode("\n",$asetuksetForAll->erikoislauantai);
		$el_pvms = [];
		foreach($el as $vp_pvm)
			$el_pvms[trim($vp_pvm)] = trim($vp_pvm);

		$f = date("d.m.Y", strtotime($from));
		$viralliset_pyhapaivat 	= [];
		$erikoislauantai 	= [];
		while (strtotime($f) <= strtotime($to)){
			if( isset($vp_pvms[$f]) )
				$result[$f]['vp'] = true;
			if( isset($el_pvms[$f]) )
				$result[$f]['el'] = true;
			if( date("N", strtotime($f)) == 7 )
				$result[$f]['su'] = true;
			$f = date ("d.m.Y", strtotime("+1 day", strtotime($f)));
		}
		return $result;
	}

	protected function statukset($piilota_mobiilista){
		$status = [];
		$status[10] = '<i class="tvikooni fa fa-cutlery '.(($piilota_mobiilista == 0)?'text-success':'text-danger').'"></i>';
		$status[2] = '<i class="tvikooni fa fa-bus '.(($piilota_mobiilista == 0)?'text-warning':'text-danger').'"></i>';
		$status[3] = '<i class="tvikooni fa fa-hourglass '.(($piilota_mobiilista == 0)?'text-info':'text-danger').'"></i>';
		$status[11] = '<i class="tvikooni fa fa-clock-o '.(($piilota_mobiilista == 0)?'text-info':'text-danger').'"></i>';
		return $status;
	}

	public function tv_arr($haku_from, $haku_to, $haku_tids, $haku_criteria, $laatikkomuoto, $with){

		$asetukset 		= Asetukset::model()->findByPk(1);
		$asiakas_tyovuorossa 	= ($asetukset->asiakas_tyovuorossa == 1)? true:false;
		$haku_to_ts 		= strtotime($haku_to ?? 0);
		$tv_arr 		= [];

		// <-- Tv array
       		$criteria = new CDbCriteria();
		if( $haku_to === null ){
			$criteria->condition = "
				toistuva_id=0 AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '$haku_from'
			";
		} else {
			$criteria->condition = "
				toistuva_id=0 AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) BETWEEN '$haku_from' AND '$haku_to'
			";
		}
		$tids_criteria = '';
		if( count($haku_tids) > 0 ){
		      	$ids = implode(",", $haku_tids);
		        $criteria->addCondition('tid IN ('.$ids.')');
		}
	        $criteria->addCondition($haku_criteria);
		$tv = Tyovuoroot::model()->findAll($criteria);
		foreach($tv as $arvo){
			$return = $this->laatikkorakenne($arvo, $arvo->pvm, $arvo->tid, false, $laatikkomuoto, $with, $asiakas_tyovuorossa);
			$tv_arr[$arvo->tid][$arvo->pvm][strtotime($arvo->alku)][] = $return;
		}

		// <-- toistuvat
       		$criteria = new CDbCriteria();
		if( $haku_to === null )
			$criteria->condition = "DATE(STR_TO_DATE(pto, '%d.%m.%Y')) >= '$haku_from'";
		else
			$criteria->condition = "DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) <= '$haku_to' AND DATE(STR_TO_DATE(pto, '%d.%m.%Y')) >= '$haku_from'";

		$tids_criteria = '';
		if( count($haku_tids) > 0 ){
			//$tt_ret = [0 => 0];
			foreach($haku_tids as $k => $v)
				$tt_ret[$v] = $v;
		      	$ids = implode(",", $tt_ret);
			$tyopaari = "tyopaari LIKE '%\"".implode("\"%' OR tyopaari LIKE'%\"", $tt_ret)."\"%'";
		        $criteria->addCondition('tid IN ('.$ids.') OR ('.$tyopaari.')');
		}
		if( is_array($haku_criteria) and count($haku_criteria) > 0 ){
			if(isset($haku_criteria['uusi_tilaus']))
				unset($haku_criteria['uusi_tilaus']);
		}
	        $criteria->addCondition($haku_criteria);
		$t = ToistuvatTyovuorot::model()->findAll($criteria);
		foreach($t as $arvo){
			// <-- Tids
			$tids = [];
			if( !empty($arvo->tyopaari) ){
				foreach(json_decode($arvo->tyopaari, true) as $tp_tid){
					$tids[$tp_tid] = $tp_tid;
				}
				$tids[$arvo->tid] = $arvo->tid;
			} else {
				$tids[$arvo->tid] = $arvo->tid;
			}

			// <-- Poistettu_pvms
			$poistettu_pvms = [];
			if( !empty($arvo->new_poistettu_pvm) ){
				foreach(json_decode($arvo->new_poistettu_pvm, true) as $key => $val)
					if( isset($val['tid']) and isset($val['pvm']) and isset($val['syy']) )
						$poistettu_pvms[$val['tid']][$val['pvm']] = $val['syy'];
			}

			$startday 	= date("Y-m-d", strtotime($arvo->pfrom));
			$startday_ts	= strtotime($startday);
			$haku_from_ts	= strtotime($haku_from);
			$stopday 	= date("Y-m-d", strtotime($arvo->pto));

			$date = new \DateTime($startday, new DateTimeZone('Europe/Helsinki'));
			$date->modify('this week monday');
			$date_end = (new \DateTime($stopday, new DateTimeZone('Europe/Helsinki')))->getTimestamp();

			while ($date->getTimestamp() <= $date_end){
				$this_week_sunday = date("YW", strtotime($date->format("d.m.Y").' this week sunday'));
				if ( $this_week_sunday >= date("YW", strtotime($haku_from)) ){ // Jotta ei saada pitkä array päivästä
					foreach(json_decode($arvo->viikko_paivat, true) as $viikko_paiva) {
						$paiva = new \DateTime($date->format('Y-m-d'), new DateTimeZone('Europe/Helsinki'));
						$paiva->modify("+" . ($viikko_paiva - 1) . "day");
						$this_pvm = $paiva->format('d.m.Y');
						if ( (strtotime($this_pvm) < $startday_ts) or (strtotime($this_pvm) < $haku_from_ts) )
							continue;
						if ((false !== $haku_to_ts && strtotime($this_pvm) > $haku_to_ts) or strtotime($this_pvm) > strtotime($stopday)){
							break 2;
						}
						foreach($tids as $tid){
							if( isset($poistettu_pvms[$tid][$this_pvm]) )
								continue;
							$return = $this->laatikkorakenne($arvo, $this_pvm, $tid, true, $laatikkomuoto, $with, $asiakas_tyovuorossa);
							$tv_arr[$tid][$this_pvm][strtotime($arvo->alku)][] = $return;
						}

					}
				}
				$date->modify("+{$arvo->viikkoja}week");
			}
		}
		//     toistuvat -->

		//exit;

		/*
		echo '<pre>';
		print_r( $tv_arr );
		echo '</pre>';
		exit; */

		return $tv_arr;
	}

	protected function this_id_builder($id, $this_pvm, $this_tid){
		$this_pvm = date("Ymd", strtotime($this_pvm));
		return (int)'99999999'.str_pad($id, 8, '0', STR_PAD_LEFT).''.$this_pvm.''.$this_tid;
	}

	protected function laatikkorakenne($arvo, $this_pvm, $this_tid, $toistuva, $laatikkomuoto, $with, $asiakas_tyovuorossa){
		// <-- Status
		$status = $this->statukset($arvo->piilota_mobiilista);
		// Status -->

		$return 	= [];
		$tv_edit	= [];
		$this_id 	= ($toistuva)? $this->this_id_builder($arvo->id, $this_pvm, $this_tid) : $arvo->id;
		$toistuva_icon 	= ($toistuva)? '<i class="text-success fa fa-repeat"></i> ' : '';
		$mennytPaivat	= (strtotime($this_pvm) < strtotime(date("Y-m-d")))? 'mennytPaivat' : '';
		$osoite 	= ( isset($arvo->osoite) and !empty($arvo->osoite))?$arvo->osoite:'';
		$ikoonit	= ((isset($status[$arvo->status]))?$status[$arvo->status]:'').$toistuva_icon;
		$tv_kesto	= 0;
		$eilasketa 	= $this->eiLasketaSubStr($arvo->tyoajanmerkinta);
		if($eilasketa != true)
			$tv_kesto = strtotime($arvo->loppu)-strtotime($arvo->alku);

		if(empty($osoite) and isset($arvo->kohteet->osoite))
			$osoite = $arvo->kohteet->osoite;
		// <-- Return Array
		if( !$laatikkomuoto ){
			$arvo->pvm 	= $this_pvm;
			$arvo->tid 	= $this_tid;
			foreach($with as $k=>$v)
				$new_with[$v] = $v;

			$return['this_id']  	= $this_id;
			$return['kohde']  	= $arvo->kohde;
			$return['this_pvm'] 	= $this_pvm;
			$return['this_tid'] 	= $this_tid;
			$return['toistuva'] 	= $toistuva;

			if(isset($new_with['data']))
				$return['data'] = $arvo;
			if(isset($new_with['status']))
				$return['status'] = $arvo->status;
			if(isset($new_with['tyoajanlaatu']))
				$return['tyoajanlaatu'] = $arvo->tyoajanlaatu;
			if(isset($new_with['tv_kesto']))
				$return['tv_kesto'] = $tv_kesto;
			if(isset($new_with['kpl_maara']))
				$return['kpl_maara'] = 1;
			return $return;
		}
		$lisateksti = '';
		if(isset($arvo->kohteet->id) and $arvo->kohteet->aktiivinen != 1)
			$lisateksti .= '<br><span class="text-danger">Kohde passiivinen</span>';
		if($arvo->laskutettu == 1)
			$lisateksti .= '<br><span class="text-primary">Laskutettu</span>';
		if($arvo->peruutettu == 1)
			$lisateksti .= '<br><span class="text-danger">'. $this->peruutettuArray()[1] .'</span>';
		if($arvo->peruutettu == 2)
			$lisateksti .= '<br><span class="text-danger">'. $this->peruutettuArray()[2] .'</span>';
		if($arvo->tyopaari != '')
			$ikoonit .= ' <i class="fa fa-male text-success" style="font-size:120%" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Työpari').'"></i> ';

		$asiakasNakyvissa = '';
		if( $asiakas_tyovuorossa ){
			$name = '';
			if(isset($arvo->kohteet->asiakkaat) and $arvo->kohteet->asiakkaat->tyyppi == 'yritys')
				$name = $arvo->kohteet->asiakkaat->yrityksen_nimi;
			if(isset($arvo->kohteet->asiakkaat) and $arvo->kohteet->asiakkaat->tyyppi == 'henkilo')
				$name = $arvo->kohteet->asiakkaat->yhteyshenkilo;
			if(!empty($name))
				$asiakasNakyvissa = $name.'<br>';
		}

		$color 		= '#888';
		$bgcol 		= 'color:#333';
		if(!empty($arvo->tyoajanmerkinta)){
			$expl = explode("/",$arvo->tyoajanmerkinta);
			if(isset($expl[1]) and !empty($expl[1])){
				$color = $expl[1];
				$bgcol = 'color:'.$color;
			}
		}
		if(!empty($arvo->tyoajanlaatu)){
			$expl1 = explode("/",$arvo->tyoajanlaatu);
			if(isset($expl1[1]) and !empty($expl1[1])){ $color = $expl1[1]; }
			$tv_edit = (isset($expl1[0])) ? '<b class="tv_edit" id="'.$this_id.'" style="color:'.$color.'">'.$ikoonit.''.$expl1[0].'</b>' : '';
		} else {
			$tv_edit = '<span class="tv_edit '.$mennytPaivat.'" id="'.$this_id.'" style="'.$bgcol.'">'.$ikoonit.''.$arvo->alku.'-'.$arvo->loppu.'<br> '.$asiakasNakyvissa.$osoite.$lisateksti.'</span>';
		}
		$return = ['tv_edit' => $tv_edit, 'tv_kesto' => $tv_kesto, 'alku' => strtotime($arvo->alku), 'loppu' => strtotime($arvo->loppu)];
		return $return;
	}

	public function actionDid4($from, $to) {
		$from 		= date("Y-m-d", strtotime($from));
		$to 		= date("Y-m-d", strtotime($to));
		$tids 		= (isset($_POST['tids']))?json_decode($_POST['tids'], true):[];
		$haku_criteria	= (isset($_POST['haku_criteria']))?$_POST['haku_criteria']:[];
		$tv_arr = $this->tv_arr($from, $to, $tids, $haku_criteria, true, []);
		/*
		echo '<pre>';
		print_r( $tv_arr );
		echo '</pre>';
		exit;
		*/
		echo json_encode($tv_arr);
		exit;
	}

	protected function tv_arrJava($from, $to, $haku_criteria, $haku_tids, $taulu){

		$hk = json_encode($haku_criteria);
		// <-- Kaikki kerrallaan
		return "
		<script type=\"text/javascript\">
		$(document).ready(function(){
			var from = '$from';
			var to = '$to';
			var tids = '".json_encode($haku_tids)."';
			var haku_criteria = $hk;
			$.ajax({
				url: location.protocol + \"//\" + location.host + \"/index.php/tyovuoroot/did4?from=\" + from + \"&to=\" + to,
				type: \"POST\",
				data: { tids : tids, haku_criteria : haku_criteria },
				success:function(data){
					data = JSON.parse(data);
					//console.log(data);
					$.tv_arr_update(data);
					$(\".odotus\").remove();
				},error:function(data){
				  	console.log(data);
				}
			});

			setTimeoutConst = setTimeout(function() {
				$.vkolaskenta('".json_encode($haku_tids)."');
			}, 7000);
		});
		</script>";
	}

	public function actionHovertietoja($this_id) {

		$get_id 	= $this->this_id($this_id);
		$tvVal 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		$pvm 		= $get_id['pvm'];
		$tid 		= $get_id['tid'];

		$arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
		$asetukset = Asetukset::model()->findByPk(1);
		$asetukset_new = array();
		$asetukset_new['tyoryhmat_kohde'] = $asetukset->tyoryhmat_kohde;
		$asetukset_new['paikkakunta_tyovuorossa'] = $asetukset->paikkakunta_tyovuorossa;
		$asetukset_new['asiakas_tyovuorossa'] = $asetukset->asiakas_tyovuorossa;
		$asetukset_new['lasketaanko_lounastauko'] = $asetukset->lasketaanko_lounastauko;

		$hovertietoja = '';

		$columnDate = date("N/d.m",strtotime($pvm));
		$explColDate = explode("/",$columnDate);

		$hovertietoja .= '<h4>'.$arrDate[$explColDate[0]].', '.$explColDate[1].' '.$this->etuSukunimi($tid).'</h4>';

		// <-- Osoite
		$osoite = '';
		if(!empty($tvVal->osoite)){
			$osoite = $tvVal->osoite;
		} elseif(empty($tvVal->osoite) and isset($tvVal->kohteet->osoite)){
			$osoite = $tvVal->kohteet->osoite;
		}
		// Osoite -->

		// <-- Status
		$status = '';
		if($tvVal->status == 10){
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-success' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-cutlery '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 2) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-warning' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-bus '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 3) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-hourglass '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 11) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-clock-o '.$teksti_vari.'"></i>';
		}
		// Status -->

		// <-- Toistuva
		$toistuva = '';
		if($tvVal->toistuva_id != 0){
			$toistuva = ' <i class="tvikooni fa fa-repeat text-success" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Toistuva työvuoro').'"></i>';
		}
		// Toistuva -->

		// <-- Avaimet
		$avaimet = '';
		if(isset($tvVal->avaimet) and count($tvVal->avaimet) > 0){
			$avaimet =  ' <i class="tvikooni fa fa-key"></i>';
		}
		// Avaimet -->

		// <-- Asiakas nakyvissa
		$asiakasNakyvissa = '';
		if($asetukset_new['asiakas_tyovuorossa'] == 1){
		$name = '';
		if(isset($tvVal->kohteet->asiakkaat) and $tvVal->kohteet->asiakkaat->tyyppi == 'yritys')
		$name = $tvVal->kohteet->asiakkaat->yrityksen_nimi;
		if(isset($tvVal->kohteet->asiakkaat) and $tvVal->kohteet->asiakkaat->tyyppi == 'henkilo')
		$name = $tvVal->kohteet->asiakkaat->yhteyshenkilo;
		if(!empty($name)){ $asiakasNakyvissa = '<b>Asiakas:</b> '.$name.'<br>'; }
		}
		//  Asiakas nakyvissa -->

		// <-- Paikkakunta nakyvissa
		$paikkakuntaNakyvissa = '';
		if($asetukset_new['paikkakunta_tyovuorossa'] == 1){
		$paikkakunta = '';
		if(isset($tvVal->kohteet->kaupunki) and !empty($tvVal->kohteet->kaupunki))
		$paikkakunta = $tvVal->kohteet->kaupunki;
		if(!empty($paikkakunta)){ $paikkakuntaNakyvissa = '<b>Paikkakunta:</b> '.$paikkakunta.'<br>'; }
		}
		//  Paikkakunta nakyvissa -->

		// <-- Hovertietoja generoi
		// <-- peruutettu
		if($tvVal['peruutettu'] == 1 and isset($tv_controller)){
			$hovertietoja .= '<h3 class="text-danger">'. $this->peruutettuArray()[1] .'</h3>';
			$bgcol = 'color:red';
		}
		if($tvVal['peruutettu'] == 2 and isset($tv_controller)){
			$hovertietoja .= '<h3 class="text-danger">'. $this->peruutettuArray()[2] .'</h3>';
			$bgcol = 'color:red';
		}
		//    peruutettu -->
		$hovertietoja .= $asiakasNakyvissa;
		//if(!empty($asiakasNakyvissa)){ $title .= ', '; }
		$hovertietoja .= $paikkakuntaNakyvissa;
		$hovertietoja .= '<br><p><span class="didstatus">'.$status.$toistuva.$avaimet.'</span>&nbsp; &nbsp;<b>'.$tvVal->alku.'-'.$tvVal->loppu.'</b>: '.$osoite.'</p>';
		if( $tvVal->tyopaari != '' and $tvVal->tyopaari != "[\"$tvVal->tid\"]" ){
		$hovertietoja .= '<div class="hover_well"><h5>Työparit</h5>';
		   foreach(json_decode($tvVal->tyopaari, true) as $tyopaari){
			if( $tvVal->tid != $tyopaari )
			$hovertietoja .=  $this->etuSukunimi($tyopaari).'<br>';
		   }
		$hovertietoja .= '</div>';
		}
		if( !empty($tvVal->tietoja) ){ $hovertietoja .= '<div class="hover_well"><h5>Tietoja:</h5> '.str_replace("\n", "<br>", $tvVal->tietoja).'</div>'; }
		//    Hovertietoja generoi -->

		echo json_encode($hovertietoja);
		exit;
	}

	protected function time_to_float($time) {
	    $timeArr = explode(":", $time);
	    return $timeArr[0] + ($timeArr[1] / 60);
	}

	public function actionViikko($tid,$viikko,$year)
	{

		$this->renderPartial('viikko',array(
			'tid'=>$tid,
			'viikko'=>$viikko,
			'year'=>$year,
		));
	}

	public function actionPvmTarkistus_lista($this_id, $cal_start, $tid, $laatikko_pvm)
	{
		$get_id 	= $this->this_id($this_id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];

		$return 	= '';
		$toistuva 	= false;
		$viikko_paivat_origin = [];
		$tids_origin 	= [];
		$viikkoja_origin 	= null;

		$startday 	= date("Y-m-d", strtotime($_POST['pfrom']));
		$startday_ts	= strtotime($startday);
		if( empty($_POST['pto']) ){
			echo json_encode(['error' => '<br><center><p class="text-danger">Loppumispäivä puuttuu.</p></center>']);
			exit;
		}
		$stopday 	= date("Y-m-d", strtotime($_POST['pto']));
		$stopday_ts	= strtotime($stopday);
		$viikkoja 	= $_POST['viikkoja'];
		if( !isset($_POST['vkopaivat']) ){
			echo json_encode(['error' => '<br><center><p class="text-danger">Valitse vähintään yksi viikonpäivä.</p></center>']);
			exit;
		}
		$viikko_paivat 	= $_POST['vkopaivat'];
		$post_tids 	= $_POST['post_tids'];

		if( $toistuva ){
			$etusukunimi	= $this->etuSukunimi($tid);
			$startday 	= date("Y-m-d", strtotime($model->pfrom));
			$viikko_paivat_origin = json_decode($model->viikko_paivat, true);
			$viikkoja_origin = $model->viikkoja;
			$tids_origin[$get_id['tid']] = $get_id['tid'];
			foreach(json_decode($model->tyopaari, true) as $tid_origin)
				$tids_origin[$tid_origin] = $tid_origin;
			ksort($tid_origin);
		}

		if($toistuva and !isset($model->id)){
			echo json_encode(['error' => 'Toistuva error']);
			exit;
		}

		// <-- Tids
		$tids = [];
		foreach($post_tids as $tp_tid){
			$tids[$tp_tid] = $tp_tid;
		}

		// <-- Order tyontekijat
		$asetukset = Asetukset::model()->findByPk(1);
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
			$tt_order_1 = "tekijan_nimi";
			$tt_order_2 = "sukunimi";
		} else {
			$tt_order_1 = "sukunimi";
			$tt_order_2 = "tekijan_nimi";
		}
		// Order tyontekijat -->

		// <-- Tyontekijat
      		$criteria = new CDbCriteria();
		$criteria->select = "id, $tt_order_1, $tt_order_2";
		$criteria->order = "$tt_order_1 ASC";
		$tids_all = array_merge($tids, $tids_origin);
		$ids = "id='".implode("' OR id='", $tids_all)."'";
		$criteria->condition = "$ids";
		$tt = [];
		$tyontekijat = Tyontekijat::model()->findAll($criteria);
		foreach($tyontekijat as $item){
			$tt[$item->id] = array('etusukunimi' => $item->$tt_order_1.' '.$item->$tt_order_2);
		}
		//     Tyontekijat -->

		if( 
			$toistuva
			and strtotime($model->pfrom) < strtotime(date("d.m.Y")) 
			and strtotime($_POST['pfrom']) >= strtotime(date("d.m.Y")) 
		){
			$return .= '<div class="alert bg-info tarkistuksen_info_ilmoitus">';
			$return .= '<center><h4>Aloituspäivä on muutettu. Uusi ketju luodaan, ja vanha ketju asetetaan päättymään '.date("d.m.Y").' päivänä.<br><br>Huomio! Nykypäivän ja uuden ketjun aloituspäivän väliset työvuorot poistetaan.</h4></center>';
			$return .= '<br>';
			$return .= '<table class="table table-bordered">';
			$return .= '<tr>';
			$return .= '<th>'.$model->pfrom.' - '. date("d.m.Y", strtotime($laatikko_pvm.' -1 day')).'</th>';
			$return .= '<th>'.$_POST['pfrom'].' - '.$_POST['pto'].'</th>';
			$return .= '</tr>';
			$return .= '<tr><td>';
			$return .= '<p><b>';
			if( !empty($model->osoite) )
				$return .= $model->osoite;
			elseif(isset($model->kohteet->osoite) and empty($model->osoite))
				$return .= $model->kohteet->osoite;

			$return .= '</b><br>Klo.: '.$model->alku.' - '.$model->loppu.'<br>';
			$return .= 'Viikko päivät: ';
			foreach(json_decode($model->viikko_paivat, true) as $vkp)
				$return .= $this->vkoPaivatLyhyesti()[$vkp].' ';
			$return .= '<br>Työvuorojen viikkoväli: '.$model->viikkoja;
			$return .= '<br>Työvuorojen työntekijät: <br>';
			foreach( $tids_origin as $tid_o ){
				$return .= '<b>'.$tt[$tid_o]['etusukunimi'].'</b><br>';
			}
			$return .= '</p>';
			$return .= '</td>';
			$return .= '<td>';
			$return .= '<p><b>'.$_POST['osoite'].'</b>';
			$return .= '</b><br>Klo.: '.$_POST['alku'].' - '.$_POST['loppu'].'<br>';
			$return .= 'Viikko päivät: ';
			foreach($viikko_paivat as $vkp)
				$return .= $this->vkoPaivatLyhyesti()[$vkp].' ';
			$return .= '<br>Työvuorojen viikkoväli: '.$viikkoja;
			$return .= '<br>Työvuorojen työntekijät: <br>';
			foreach( $tids as $tid_u ){
				$return .= '<b>'.$tt[$tid_u]['etusukunimi'].'</b><br>';
			}
			$return .= '</p>';
			$return .= '</td></tr>';
			$return .= '</table>';
			$return .= '</div>';
		}

 		// <-- Poistettu_pvms
		$poistettu_pvms = [];
		if( $this_id != 'null' and !empty($model->new_poistettu_pvm) ){
			foreach(json_decode($model->new_poistettu_pvm, true) as $key => $val)
				if( isset($val['tid']) and isset($val['pvm']) and isset($val['syy']) )
					$poistettu_pvms[$val['tid']][$val['pvm']] = $val['syy'];
		}

		$date = new \DateTime($startday, new DateTimeZone('Europe/Helsinki'));
		$date->modify('this week monday');
		$date_end = (new \DateTime($stopday, new DateTimeZone('Europe/Helsinki')))->getTimestamp();
		$pvms = [];
		while ($date->getTimestamp() <= $date_end){
			//$loop_week_sunday = date("YW", strtotime($date->format("d.m.Y").' this week sunday'));
			$this_week_sunday = date("YW", strtotime($date->format("d.m.Y").' this week sunday'));
			if ( $this_week_sunday >= date("YW", strtotime($cal_start.' this week sunday')) ){ // Tama pitaa testata
				foreach($viikko_paivat as $viikko_paiva) {
					$paiva = new \DateTime($date->format('Y-m-d'), new DateTimeZone('Europe/Helsinki'));
					$paiva->modify("+" . ($viikko_paiva - 1) . "day");
					$cal_pvm = $paiva->format('j.m.Y');
					$this_pvm = $paiva->format('d.m.Y');
					if (strtotime($this_pvm) < $startday_ts)
						continue;
					// <-- Haku from to rajoitukset
					if (strtotime($this_pvm) > $stopday_ts){
						break 2;
					}

					foreach( $tids as $tid ){
						$this_id_builder = ( $this_id != 'null' )? $this->this_id_builder($model->id, $this_pvm, $tid) : '';
						$model_id = ( $this_id != 'null' )? $model->id : '';
						if( isset($poistettu_pvms[$tid][$this_pvm]) )
							$pvms[$cal_pvm][$tid] = [ 'html' => '<br><i class="link fa fa-recycle palauta_kejuun" toistuva_id="'.$model_id.'" tid="'.$tid.'" pvm="'.$this_pvm.'" this_id="'.$this_id_builder.'" title="'.Yii::t('log', $poistettu_pvms[$tid][$this_pvm]['text']).$poistettu_pvms[$tid][$this_pvm]['user'].' - '.$poistettu_pvms[$tid][$this_pvm]['date'].'"></i>', 'pois_tilanne' => true ];
						else
							$pvms[$cal_pvm][$tid] = [ 'html' => '<br><i class="link fa fa-gear cal_tilanne" toistuva_id="'.$model_id.'" tid="'.$tid.'" pvm="'.$this_pvm.'" this_id="'.$this_id_builder.'"></i>', 'pois_tilanne' => false ];
					}
				}
			}
			$date->modify("+{$viikkoja}week");
		}
		$m_start = new DateTime($cal_start);
		$m_start->modify("first day of this month");
		$m_interval = new DateInterval('P1M');
		$m_end = new DateTime($m_start->format("Y-m-d"));
		$m_end->modify("+3 month");
		$m_period = new DatePeriod($m_start, $m_interval, $m_end);
		foreach( $post_tids as $tid ){
			(isset($tt[$tid]['etusukunimi']))? $return .= '<center><h2><i class="btn btn-default fa fa-arrow-left vasemalle"></i>&nbsp; '.$tt[$tid]['etusukunimi'].' #'.$tid.' &nbsp;<i class="btn btn-default fa fa-arrow-right oikealle"></i></h2></center>' : '' ;
			$return .= '<div class="row">';
			foreach ($m_period as $dt) {
				$return .= '<div class="col-sm-4">';
				$return .= '<center><h5>'.$this->monthFI($dt->format("n")).' '.$dt->format("Y").'</h5></center>';
				$return .= $this->draw_calendar($dt->format("m"), $dt->format("Y"), $pvms, $tid, $poistettu_pvms);
				$return .= '</div>';
			}
			$return .= '</div>';
		}
		echo json_encode($return);
		exit;
	}

	protected function monthFI($arvo){
		$months=array(
			1=>Yii::t('main', 'Tammikuu'),
			2=>Yii::t('main', 'Helmikuu'),
			3=>Yii::t('main', 'Maaliskuu'),
			4=>Yii::t('main', 'Huhtikuu'),
			5=>Yii::t('main', 'Toukokuu'),
			6=>Yii::t('main', 'Kesäkuu'),
			7=>Yii::t('main', 'Heinäkuu'),
			8=>Yii::t('main', 'Elokuu'),
			9=>Yii::t('main', 'Syyskuu'),
			10=>Yii::t('main', 'Lokakuu'),
			11=>Yii::t('main', 'Marraskuu'),
			12=>Yii::t('main', 'Joulukuu')
			);
		return $months[$arvo];
	}

	protected function draw_calendar($month, $year, $pvms, $tid, $poistettu_pvms) {
		$calendar = '';
		$calendar .= '<table cellpadding="3" cellspacing="0" class="table table-striped">';
		$headings = $this->vkoPaivatLyhyesti();
		$calendar.= '<tr class="b-calendar__row">';
		for($head_day = 1; $head_day <= 7; $head_day++) {
			$calendar.= '<th class="b-calendar__head';
			if ($head_day != 0) {
				if (($head_day % 6 == 0) || ($head_day % 7 == 0)) {
					$calendar .= ' b-calendar__weekend';
				}
			}
			$calendar .= '">';
			$calendar.= '<div class="b-calendar__number">'.$headings[$head_day].'</div>';
			$calendar.= '</th>';
		}
		$calendar.= '</tr>';
		$running_day = date('w',mktime(0,0,0,$month,1,$year));
		$running_day = $running_day - 1;
		if ($running_day == -1) {
			$running_day = 6;
		}
		
		$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
		$day_counter = 0;
		$days_in_this_week = 1;
		$dates_array = array();
		$calendar.= '<tr class="b-calendar__row">';
		for ($x = 0; $x < $running_day; $x++) {
			$calendar.= '<td class="b-calendar__np"></td>';
			$days_in_this_week++;
		}
		for($list_day = 1; $list_day <= $days_in_month; $list_day++) {
			$this_cal_pvm 	= $list_day.'.'.$month.'.'.$year;
			$mennytPaivat	= (strtotime($this_cal_pvm) < strtotime(date("Y-m-d")))? 'mennytPaivat ' : '';
			$poisto = '';
			if( isset($pvms[$this_cal_pvm][$tid]) ){
				$this_class = 'bg-success';
				$poisto = $pvms[$this_cal_pvm][$tid]['html'];
				if($pvms[$this_cal_pvm][$tid]['pois_tilanne'])
					$this_class = 'bg-warning';
			} elseif( isset($poistettu_pvms[$tid][$this_cal_pvm])  ){
				// $poisto = 'poistettu'; // ehka se ei tarvite, kun tyovuorokortti avattu sen jalkeen pvmsta
			} else {
				$this_class = '';
			}
			$calendar.= '<td align="center" class="'.$mennytPaivat.$this_class;
			if ($running_day != 0) {
				if (($running_day % 5 == 0) || ($running_day % 6 == 0)) {
					$calendar .= ' b-calendar__weekend';
				}
			}
			$calendar .= '">';
			$calendar.= '<div class="pvm">'.$list_day.$poisto.'</div>';
			$calendar.= '</td>';
			if ($running_day == 6) {
				$calendar.= '</tr>';
				if (($day_counter + 1) != $days_in_month) {
					$calendar.= '<tr class="b-calendar__row">';
				}
				$running_day = -1;
				$days_in_this_week = 0;
			}
			$days_in_this_week++; 
			$running_day++; 
			$day_counter++;
		}
		if ($days_in_this_week < 8) {
			for($x = 1; $x <= (8 - $days_in_this_week); $x++) {
				$calendar.= '<td class="b-calendar__np"> </td>';
			}
		}
		$calendar.= '</tr>';
		$calendar.= '</table>';
		return $calendar;
	}

	public function actionCreate4_form($pvm, $tid)
	{
		$asetukset = Asetukset::model()->findByPk(1);
		$haku_tids = [];
		$haku_tids[$tid] = [$tid];
		$haku_from = date("Y-m-d", strtotime(Yii::app()->session['from']));
		$haku_to = date("Y-m-d", strtotime(Yii::app()->session['to']));

		// Tyosuhde oikeus
		$oikeus = '<div class="alert alert-danger">'.Yii::t('main', 'Työsuhdetta ei ole määritelty tai työsuhde ei ole voimassa.').'</div>';
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			tid='".$tid."' 
		";
		$ts = Tyosuhdet::model()->find($criteria);
		if(isset($ts->id) and !empty($ts->alku))
		{
			$alku = date("Ymd", strtotime($ts->alku));

			if( date("Ymd", strtotime($pvm)) >= date("Ymd", strtotime($alku)) and empty($ts->loppu))
			$oikeus = '';
			elseif($pvm >= $alku and !empty($ts->loppu) and $pvm <= date("Ymd", strtotime($ts->loppu)))
			$oikeus = '';
		}
		// Tyosuhde oikeus

		$model=new Tyovuoroot;

		$form_content = '';
	        $form_content = '
	        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
	          <div class="panel">
	            <div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
					<span aria-hidden="true">&times;</span>
				</button>
	              <span class="panel-title"><i class="fa fa-clock-o"></i> 
			'.Yii::t('main', 'Työvuoron suunnittelu').': '.$this->etuSukunimi($tid).' <span class="kohteen_lisatiedot"></span>
		      </span>
	            </div>
	              <div class="panel-body">
			'.((isset($oikeus))?$oikeus:'').'
			'.$this->renderPartial('_form4',
				array(
					'asetukset'	=> $asetukset,
					'haku_from' 	=> $haku_from,
					'haku_to' 	=> $haku_to,
					'haku_tids'	=> $haku_tids,
					'model'		=> $model, 
					'laatikko_pvm' 	=> $pvm, 
					'laatikko_tid' 	=> $tid,
					'this_id'	=> 'null',
					'toistuva'	=> false,
					'create_update'	=> 'create',
				), true).'
	              </div>
	          </div>
	        </div>';
		echo json_encode($form_content);
		exit;
	}

	public function actionCreate4($toistuva, $laatikko_pvm, $laatikko_tid)
	{

		$return = array();
		if( $toistuva == 'true' ){
			$toistuva = true;
			$model 	= new ToistuvatTyovuorot;
			$post 	= array_merge($_POST['Tyovuoroot'], $_POST['ToistuvatTyovuorot']);
		} else {
			$toistuva = false;
			$model 	= new Tyovuoroot;
			$post 	= $_POST['Tyovuoroot'];
		}

		if(isset($post)){
			$cleared_attr = $this->compareToistuvaAttributes($model->attributes, $post);
			$model->attributes = $cleared_attr;
			$this->model_json_converter($post, $model, $toistuva);

			// <-- Apuaika
			if(isset($post['apuaika']) and $post['apuaika'] == 1)
				$model->apuaika = 1;
			else if(isset($post['apuaika']) and $post['apuaika'] != 1)
				$model->apuaika = 0;
			//     Apuaika -->

			if($model->save()){

				$this_id = ($toistuva)? $this->this_id_builder($model->id, $laatikko_pvm, $laatikko_tid) : $model->id;

				// <-- PushNotify
				if(isset($post['PushNotify']) and $post['PushNotify'] == 'on')
					$this->pushNotifySending($this_id);
				// PushNotify -->

				// <-- jos on tyopaari
				if(!$toistuva and count(json_decode($model->tyopaari, true)) > 1)
					$this->tyopari_luonti($model);
				// jos on tyopaari -->

				// <-- LOG
				if( $toistuva ){
					$model_log 	= 'ToistuvatTyovuorot';
					$name_log 	= 'Toistuvat työvuorot';
					$n_m = ToistuvatTyovuorot::model()->findbypk($model->id);
				} else {
					$model_log 	= 'Tyovuoroot';
					$name_log 	= 'Työvuorot';
					$n_m = Tyovuoroot::model()->findbypk($model->id);
				}
				$status_log 	= 'Create';
				$old_values 	= null;
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
	
				$return[] = array('model' => $model->attributes);
				echo json_encode($return);

			}
		}

		exit;
	}

	public function this_id($this_id){
		if( $this_id != 'null' ){
			if( substr($this_id, 0, 8) == '99999999' ){
				$toistuva 	= true;
				$model 		= ToistuvatTyovuorot::model()->findByPk((int)substr($this_id, 8, 8));
				$tid 		= substr($this_id, 24);
				$pvm 		= date("d.m.Y", strtotime(substr($this_id, 16, 8)));
			} else {
				$toistuva 	= false;
				$model		= Tyovuoroot::model()->findByPk($this_id);
				if( isset($model->id) ){
					$tid 		= $model->tid;
					$pvm 		= $model->pvm;
				} else {
					echo json_encode( 'Työvuoro id: '.$this_id.' ei löydy' );
					exit;
				}
			}

		//echo json_encode( $pvm .' '.$tid.' '.$model->id );
		//exit;
		return ['model' => $model, 'toistuva' => $toistuva, 'pvm' => $pvm, 'tid' => $tid];
		}
	}

	public function compareToistuvaAttributes($attr1, $attr2){
		$cleared = [];
		foreach($attr1 as $key => $attr){
			if( isset($attr2[$key]) )
				$cleared[$key] = $attr2[$key];
		}
		if( isset($cleared['id']) )
			unset($cleared['id']);
		if( isset($cleared['time']) )
			unset($cleared['time']);
		return $cleared;
	}

	public function newTvFromToistuva($toistuva_model, $pvm, $tid, $syy) {
			$u		= Yii::app()->user->nimi;
			$d		= date("d.m.Y");
			$poisto_syy	= ['text'=>$syy, 'user'=>$u, 'date'=>$d];
			$this->toistuvaDeletePvm($toistuva_model['id'], $pvm, $tid, $poisto_syy);

			$tv_new = new Tyovuoroot;
			$cleared_attr = $this->compareToistuvaAttributes($tv_new->attributes, $toistuva_model);
			$tv_new->attributes = $cleared_attr;
			$tv_new->pvm = date("d.m.Y",strtotime($pvm));
			$tv_new->tid = $tid;
			$tv_new->tyopaari = '';
			$tv_new->toistuva_id = 0;
			if(!$tv_new->save()){
				var_dump($tv_new->getErrors());
				exit;
			}
	}

	public function actionContextmenu_submits($this_id)
	{
		$get_id 	= $this->this_id($this_id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		$pvm 		= $get_id['pvm'];
		$tid 		= $get_id['tid'];
		$return = [];
		if( !isset($model->id) ){
			echo json_encode(['error' => 'Kohdetta ei löydy']);
			exit;
		}
		if( !$toistuva ){
			Tyovuoroot::model()->updatebypk($model->id, array($_POST['field'] => $_POST['value']));
		} else {
			$u		= Yii::app()->user->nimi;
			$d		= date("d.m.Y");
			$poisto_syy	= ['text' => 'ByContextMeny', 'user'=>$u, 'date'=>$d];
			$this->toistuvaDeletePvm($model->id, $pvm, $tid, $poisto_syy);

			$tv_new = new Tyovuoroot;
			$cleared_attr = $this->compareToistuvaAttributes($tv_new->attributes, $model->attributes);
			$tv_new->attributes = $cleared_attr;
			$tv_new->pvm = date("d.m.Y",strtotime($pvm));
			$tv_new->tid = $tid;
			$tv_new->tyopaari = '';
			if($tv_new->save()){
				Tyovuoroot::model()->updatebypk($tv_new->id, array($_POST['field'] => $_POST['value']));
			} else {
				echo json_encode($tv_new->getErrors());
				exit;
			}
		}
		$haku_from = date("Y-m-d", strtotime(Yii::app()->session['from']));
		$haku_to = date("Y-m-d", strtotime(Yii::app()->session['to']));
		$tv_arr = $this->tv_arr($haku_from, $haku_to, [$tid], [], true, []);
		$return = ['tv_arr' => $tv_arr];
		echo json_encode($return);
		exit;
	}

	public function actionContextmenu_valinnat($this_id)
	{
		$get_id 	= $this->this_id($this_id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		$pvm 		= $get_id['pvm'];
		$tid 		= $get_id['tid'];
		$etusukunimi	= $this->etuSukunimi($tid);

		if(!isset($model->id)){
			echo 'error';
			exit;
		}

		$return = '<p><center><h5>'.$etusukunimi.'</h5><h5>'.$model->osoiteById.'</h5>'.$model->alku.'-'.$model->loppu.'</center></p><br>';

		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'tyovuoroot-form',
			'enableAjaxValidation'=>false,
		));

		if( $toistuva )
			$return .= '<span class="text-danger">Huomio!<p>Tämä muokaus irrotta päivä toistuvasta ketjusta ja tilalle luodaan yksittyinen työvuoro.</p></span>';
		$return .= '<div class="form_lomake" toistuva="'.(($toistuva)? 'true':'false').'">';

        	$tal = Valikkoot::model()->findAll(" select_type='tyoajanmerkinta' ", array('order' => 'select_type'));
		$return .= '<div class="row"><div class="col-sm-12">
		'.$form->labelEx($model,'tyoajanmerkinta').
		'<select name="Tyovuoroot_[tyoajanmerkinta]" class="form-control" id="tyoajanmerkinta">';
			if(!empty($model->tyoajanmerkinta)){
				$expl = explode("/",$model->tyoajanmerkinta);
				$value = (isset($expl[0])) ? $expl[0] : '';
				$return .= '<option value="'.$model->tyoajanmerkinta.'">'.$value.'</option>';
			}
		$return .= '
			<option style="color:" value="Normaali/">Normaali</option>
			<option style="color:red" value="Ei lasketa/red">Ei lasketa</option>';
			foreach($tal as $v){
			   $expl = explode("/",$v->value);
			   $color = (isset($expl[1])) ? $expl[1] : '';
			   $value = (isset($expl[0])) ? $expl[0] : '';
			   if($v->value != 'Normaali/' and $v->value != 'Ei lasketa/red')
			   $return .= '<option style="color:'.$color.'" value="'.$v->value.'">'.$value.'</option>';
			}
		$return .= '</select></div></div>';

		$list = $this->peruutettuArray();
		$return .= '<div class="row"><div class="col-sm-12">
		'.$form->labelEx($model,'peruutettu').'
		'.$form->dropDownList($model,"peruutettu", $list, 
		array("empty"=>"", "class"=>"form-control", "id" => "peruutettu")).'
		</div></div>';

		$list = array(0 => 'Ei laskutettu', 1 => 'Laskutettu');
		$return .= '<div class="row"><div class="col-sm-12">
		'.$form->labelEx($model,'laskutettu').'
		'.$form->dropDownList($model,"laskutettu", $list, 
		array("class"=>"form-control", "id" => "laskutettu")).'
		</div></div>';
		$return .= '<br>';
		$return .= '<div class="row">';
		$return .= '<div class="col-sm-12"><p><button class="close_context_menu btn btn-primary btn-block">Sulje</button></p></div>';
		$return .= '</div>';

		$this->endWidget();
		echo $return;
		exit;
	}

	public function actionUpdate4_form($this_id)
	{
		$asetukset = Asetukset::model()->findByPk(1);
		$get_id 	= $this->this_id($this_id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		$pvm 		= $get_id['pvm'];
		$tid 		= $get_id['tid'];
		$etusukunimi	= $this->etuSukunimi($tid);

		$haku_tids = [];
		$haku_tids[$tid] = [$tid];
		if( is_array(json_decode($model->tyopaari, true)) )
			foreach(json_decode($model->tyopaari, true) as $h_tid)
				$haku_tids[$h_tid] = $h_tid;

		$haku_from = date("Y-m-d", strtotime(Yii::app()->session['from']));
		$haku_to = date("Y-m-d", strtotime(Yii::app()->session['to']));

		// <-- Order tyontekijat
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
			$tt_order_1 = "tekijan_nimi";
			$tt_order_2 = "sukunimi";
		} else {
			$tt_order_1 = "sukunimi";
			$tt_order_2 = "tekijan_nimi";
		}
		// Order tyontekijat -->

       		$criteria = new CDbCriteria();
		$criteria->select = "id, $tt_order_1, $tt_order_2";
		$criteria->order = "$tt_order_1 ASC";
		$criteria->condition = "aktiivinen=1";
	  	$t = Tyontekijat::model()->findAll($criteria);
		$tekijan_nimi = '<select id="tekijanVaihdo" class="form-control">';
		foreach($t as $tekijanData){
			if($tekijanData->id == $tid)
			$tekijan_nimi .= '<option value="'.$tekijanData->id.'" selected>'.$tekijanData->$tt_order_1.' '.$tekijanData->$tt_order_2.'</option>';
			else
			$tekijan_nimi .= '<option value="'.$tekijanData->id.'">'.$tekijanData->$tt_order_1.' '.$tekijanData->$tt_order_2.'</option>';
		}
		$tekijan_nimi .= '</select>';
		$form_content = '';
	        $form_content = '
	        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
	          <div class="panel">
	            <div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
					<span aria-hidden="true">&times;</span>
				</button>
			<span class="panel-title"><i class="fa fa-clock-o"></i> 
				'.Yii::t('main', 'Työvuoron suunnittelu').' '.(($toistuva)?'ketju: ':'').' #'.$model->id.' <span class="kohteen_lisatiedot"></span> '.$tekijan_nimi.'
			</span>
	            </div>
	            <!-- end .panel-heading section -->
	              <div class="panel-body p25">
			'.$this->renderPartial('_form4',
				array(
					'asetukset'	=> $asetukset,
					'haku_from' 	=> $haku_from,
					'haku_to' 	=> $haku_to,
					'haku_tids'	=> $haku_tids,
					'this_id' 	=> $this_id, 
					'model'		=> $model, 
					'toistuva'	=> $toistuva, 
					'laatikko_pvm' 	=> $pvm, 
					'laatikko_tid' 	=> $tid, 
					'laatiko_etusukunimi' => $etusukunimi,
					'create_update'	=> 'update',
				), true).'
	              </div>
	          </div>
	        </div>';
	
		echo json_encode($form_content);
		exit;
	}

	public function actionUpdate4($this_id, $laatikko_pvm, $laatikko_tid)
	{

		$get_id 	= $this->this_id($this_id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		if(!isset($model->id)){ die('Työvuoroja '.$id.' ei löydy.'); }
		$cur_model_id	= $model->id;
		$cur_model	= $model;

		$return = [];
		if( $toistuva )
			$post = $_POST['ToistuvatTyovuorot'];
		else
			$post = $_POST['Tyovuoroot'];

		// <-- Variables
		//$post['pvm'] 		= date("d.m.Y",strtotime($laatikko_pvm)); Kun siirretaan tyoparit muu paivaan.. sitten se ei onnistuu
		$edellinen_model 	= $model->attributes;
		$edelliset_tyoparit 	= json_decode($edellinen_model['tyopaari'], true);
		$post_tyopaari		= (isset($post['tyopaari']))? $post['tyopaari'] : [];
		//     Variables -->

		// <-- Edico viesti jos peruutettu
		if( 
			!$toistuva
			and isset($model->kohteet->asiakas_id) 
			and $model->kohde == $post['kohde']
			and $model->peruutettu == 0 
			and $post['peruutettu'] != 0)
		{
			$edico_viesti = Yii::t('main', 'Työvuoro on peruutettu').".\n".$_POST['Tyovuoroot']['pvm'].", ".$_POST['Tyovuoroot']['alku']."-".$_POST['Tyovuoroot']['loppu'];
			Domainit::sendGCMeDico($model->kohteet->asiakas_id, Yii::t('main', 'Työvuoro on peruutettu'), $edico_viesti, null);
		}
		//     Edico viesti jos peruutettu -->

		// <-- Luodaan uusi ketju yksittaisesta.
		if( 
			!$toistuva and isset($post['is_toistuva'])
		){
			$model = new ToistuvatTyovuorot;
			$merge = array_merge($_POST['Tyovuoroot'], $_POST['ToistuvatTyovuorot']);
			$model->attributes = $merge;
			$this->model_json_converter($post, $model, $toistuva);
			$removedArr[$laatikko_tid] = $laatikko_tid;
			foreach(json_decode($model->tyopaari, true) as $updtp)
				$removedArr[$updtp] = $updtp;

			// <-- Tavallinen TV Tyopaari poisto jos olisi
			if(!$this->tyopari_poisto($cur_model, $removedArr)){
				$this->tvDeleteLog($cur_model);
				$cur_model->deleteByPk($cur_model->id);
			}
			//     Tavallinen TV Tyopaari poisto jos olisi -->

			if(!$model->save()){
				echo json_encode($model->getErrors());
			} else {

				// <-- PushNotify
				$this_id = $this->this_id_builder($model->id, $laatikko_pvm, $laatikko_tid);
				if(isset($post['PushNotify']) and $post['PushNotify'] == 'on')
					$this->pushNotifySending($this_id);
				// PushNotify -->

				$return = ['return' => 'uusi_ketju_ok'];
				echo json_encode($return);
			}
			exit;
		}
		//     Luodaan uusi ketju yksittaisesta. -->

		// <-- Luodaan yksittainen toistuvasta.
		if( 
			$toistuva and isset($edellinen_model['id'])
			and !isset($post['is_toistuva'])
		){
			$model = new Tyovuoroot;
			$cleared_attr = $this->compareToistuvaAttributes($model->attributes, $post);
			$model->attributes = $cleared_attr;
			$this->model_json_converter($post, $model, false);
			if(!$model->save()){
				echo json_encode($model->getErrors());
			} else {

				// <-- PushNotify
				$this_id = $model->id;
				if(isset($post['PushNotify']) and $post['PushNotify'] == 'on')
					$this->pushNotifySending($this_id);
				// PushNotify -->

				// <-- Poisto PVM/Henkilo ketjusta
				$u		= Yii::app()->user->nimi;
				$d		= date("d.m.Y");
				$poisto_syy	= ['text'=>'ByUpdateChangeToYksittyinen', 'user'=>$u, 'date'=>$d];
				if(count(json_decode($model->tyopaari, true)) > 1){
					foreach(json_decode($model->tyopaari, true) as $tp_tid )
						$this->toistuvaDeletePvm($edellinen_model['id'], $laatikko_pvm, $tp_tid, $poisto_syy);
				} else {
					$this->toistuvaDeletePvm($edellinen_model['id'], $laatikko_pvm, $laatikko_tid, $poisto_syy);
				}
				//     Poisto PVM/Henkilo ketjusta -->

				// <-- jos on tyopaari
				if(count(json_decode($model->tyopaari, true)) > 1)
					$this->tyopari_luonti($model);
				// jos on tyopaari -->

				$return[] = ['return' => 'luottu_uusi_tyovuoro', 'id' => $model->id];
			}
			echo json_encode($return);
			exit;
		}
		//     Luodaan yksittainen toistuvasta. -->

		// <-- Toistuva Alkamispaiva siirto.
		if( 
			$toistuva and isset($edellinen_model['id']) and isset($post['is_toistuva'])
			and strtotime($edellinen_model['pfrom']) != strtotime($laatikko_pvm) 
		){
			$model->attributes 	= $edellinen_model;
			$model->pto 		= date("d.m.Y", strtotime($laatikko_pvm . " -1 day"));
			$model->ilmoitus_paattymisesta = 1;

			// <-- Poistetut päivät siirto, JOS vaihdettu henkilö
			$all_new_tids = [$post['tid'] => $post['tid']];
			foreach($post_tyopaari as $ptid)
				$all_new_tids[$ptid] = $ptid;

			$edelliset_tyoparit_updater = [];
			foreach($edelliset_tyoparit as $tptid)
				$edelliset_tyoparit_updater[$tptid] = $tptid;

			$poistettu_pvms_fornew 		= [];
			$poistettu_pvms_fororigin 	= [];
			if( !empty($edellinen_model['new_poistettu_pvm']) ){
				foreach(json_decode($edellinen_model['new_poistettu_pvm'], true) as $key => $val){
					if( strtotime($val['pvm']) >= strtotime($post['pfrom']) and in_array($val['tid'], $all_new_tids, true) ){
						$poistettu_pvms_fornew[] = $val;
					}
					if( strtotime($val['pvm']) < strtotime($post['pfrom']) and isset($edelliset_tyoparit_updater[$val['tid']]) ){
						$poistettu_pvms_fororigin[] = $val;
					}
				}
			}

			$model->new_poistettu_pvm = (count($poistettu_pvms_fororigin) > 0)?json_encode($poistettu_pvms_fororigin):'';
			//     Poistetut päivät siirto, JOS vaihdettu henkilö -->

			if($model->save()){

				// <-- PushNotify
				$this_id = $this->this_id_builder($model->id, $laatikko_pvm, $laatikko_tid);
				if(isset($post['PushNotify']) and $post['PushNotify'] == 'on')
					$this->pushNotifySending($this_id);
				// PushNotify -->

				$new_toistuva = new ToistuvatTyovuorot;
				$new_toistuva->attributes = $post;
				$this->model_json_converter($post, $new_toistuva, $toistuva);
				$new_toistuva->new_poistettu_pvm = (count($poistettu_pvms_fornew) > 0)?json_encode($poistettu_pvms_fornew):'';
				if(!$new_toistuva->save()){
					echo json_encode($new_toistuva->getErrors());
				} else {

					// <-- PushNotify
					$this_id = $this->this_id_builder($new_toistuva->id, $laatikko_pvm, $laatikko_tid);
					if(isset($post['PushNotify']) and $post['PushNotify'] == 'on')
						$this->pushNotifySending($this_id);
					// PushNotify -->

					$return = ['return' => 'pfrom_muutos_ok'];
					echo json_encode($return);
				}
			}
			exit;
		}
		//     Toistuva Alkamispaiva siirto -->

		$model->attributes 	= $post;
		$this->model_json_converter($post, $model, $toistuva);
		$updated_tp = json_decode($model->tyopaari, true);

		if($model->save()){

			// <-- PushNotify
			$this_id = ($toistuva)? $this->this_id_builder($model->id, $laatikko_pvm, $laatikko_tid) : $model->id;
			if(isset($post['PushNotify']) and $post['PushNotify'] == 'on')
				$this->pushNotifySending($this_id);
			// PushNotify -->

			$current_model = $model;
			// <-- LOG
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Update';
			if(isset($_POST[$model_log]))
			{
				$old_values = json_encode($current_model->attributes);
				$new_values = json_encode($_POST[$model_log]);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

			// <-- TV tyopaari
			if( !$toistuva ){
				$site = Yii::app()->createController('Site');
				// <-- Lisataan tyoparia silloin kun ei ollut yhtaan
				if( count($edelliset_tyoparit) == 0 and count($post_tyopaari) > 0 )
					$this->tyopari_luonti($model);
				// <-- Lisataan tyoparia jos edellisessa olisi jotakin ja esiteltu tyoparia
				if( count($edelliset_tyoparit) > 0 and count($post_tyopaari) > 0 ){
					$removed 	= [];
					$luotu 		= [];
					$rm 		= array_diff( $edelliset_tyoparit, $updated_tp );
					$arr 		= array_diff( $updated_tp, $edelliset_tyoparit );
					foreach($rm as $tid)
						$removed[$tid] = $tid;
					foreach($edelliset_tyoparit as $tv_id => $tid){
						if(isset($removed[$tid])){
							Tyovuoroot::model()->deleteByPk($tv_id);
							continue;
						} else {
							$luotu[$tv_id] = $tid;
						}
					}
					foreach($arr as $tid){
						$arr = $this->add_TV_tid($model, $tid, $site);
						if(isset($arr['id']))
							$luotu[$arr['id']] = $arr['tid'];
					}
					foreach($luotu as $k => $v){
						if( $k == $model->id ){
							Tyovuoroot::model()->updatebypk($model->id, array('tyopaari' => json_encode($luotu)));
						} else {
							$upd = Tyovuoroot::model()->findByPk($k);
							if( isset($upd->id) ){
								$upd->attributes = $model->attributes;
								$upd->tid = $v;
								$upd->tyopaari = json_encode($luotu);
								$upd->save();
							}
						}
					}
				}
				// <-- Poistetaan kaikki tyoparit ja puhdistaan kentaa
				if( count($edelliset_tyoparit) > 0 and count($post_tyopaari) == 0 ){
					foreach($edelliset_tyoparit as $tv_id => $tid)
						if( $tid == $model->tid )
							Tyovuoroot::model()->updatebypk($model->id, array('tyopaari' => ''));
						else
							Tyovuoroot::model()->deleteByPk($tv_id);
				}
			}
			//     TV tyopaari -->

			$return = ['return' => 'muokattu'];
			echo json_encode($return);

		} else { // model save 
			echo json_encode($model->getErrors());
		}
		exit;
	}

	protected function tvDeleteLog($model)
	{
		$site = Yii::app()->createController('Site');
		// <-- LOG
		$model_log 	= 'Tyovuoroot';
		$name_log 	= 'Työvuorot';
		$status_log 	= 'Delete';
		$old_values = json_encode($model->attributes);
		$new_values = null;
		$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
		//     LOG -->

	}

	// <-- For tavalliset tyovuorot
	protected function tyopari_poisto($cur_model, $removedArr)
	{
		$edelliset_tyoparit = json_decode($cur_model->tyopaari, true);
		if( count($edelliset_tyoparit) == 0 )
			return false;

		$updater = [];
		foreach($edelliset_tyoparit as $tv_id => $tid){
			if( isset($removedArr[$tid]) ){
				$rm_model = Tyovuoroot::model()->findByPk($tv_id);
				if( isset($rm_model->id) ){
					$this->tvDeleteLog($rm_model);
					$rm_model->deleteByPk($rm_model->id);
				}
			} else {
				$updater[$tv_id] = $tid;
			}
		}
		foreach($updater as $tv_id => $tid){
			if( count($updater) == 1 )
				Tyovuoroot::model()->updatebypk($tv_id, array('tyopaari' => ''));
			else
				Tyovuoroot::model()->updatebypk($tv_id, array('tyopaari' => json_encode($updater)));
		}
		return true;
	}

	protected function tyopari_luonti($current_model)
	{
		$site = Yii::app()->createController('Site');
		$tids 		= json_decode($current_model->tyopaari, true);
		$luotu 		= [];
		foreach($tids as $tid){
			if( $current_model->tid == $tid ){
				$luotu[$current_model->id] = $current_model->tid;
				continue;
			}
			$arr = $this->add_TV_tid($current_model, $tid, $site);
			if(isset($arr['id']))
				$luotu[$arr['id']] = $arr['tid'];
		}
		foreach($luotu as $k => $v)
			Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));

		return true;
	}

	protected function add_TV_tid($current_model, $tid, $site)
	{
		$arr = [];
		$model = new Tyovuoroot;
		$model->attributes = $current_model->attributes;
		$model->tid = $tid;
		if($model->save()){

			// <-- PushNotify
			if(isset($post['PushNotify']) and $post['PushNotify'] == 'on')
				$this->pushNotifySending($model->id);
			// PushNotify -->

			// <-- LOG
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Create';
			$old_values 	= null;
			$new_values = json_encode($model->attributes);
			$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			//     LOG -->

			$arr = ['id' => $model->id, 'tid' => $model->tid];
			return $arr;
		}
		return false;
	}

	protected function model_json_converter($post, $model, $toistuva)
	{
		if( isset($post['is_toistuva']) and isset($post['viikko_paivat']) )
			$model->viikko_paivat = json_encode($post['viikko_paivat']);

		if( isset($post['tyopaari']) ){
			$post['tyopaari'][] = $post['tid'];
			$model->tyopaari = json_encode($post['tyopaari']); 
		} else {
			$model->tyopaari = ''; 
		}
		if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 )
			$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
		else
			$model->lisa_tuotteet = '';
	
		if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 )
			$model->tyo_erittelyt = json_encode($model->tyo_erittelyt, JSON_FORCE_OBJECT);
		else
			$model->tyo_erittelyt = '';

		if( is_array($model->muistiinpano) and count($model->muistiinpano) > 0 )
			$model->muistiinpano = json_encode($model->muistiinpano, JSON_FORCE_OBJECT);
		else
			$model->muistiinpano = '';

		return $model;
	}

	protected function pushNotifySending($this_id)
	{
		$get_id = $this->this_id($this_id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		$pvm 		= $get_id['pvm'];
		$tid 		= $get_id['tid'];

		$m = $model;
		$t = Tyontekijat::model()->findbypk($tid);
		$osoite = $model->osoite;
		if(empty($osoite) and isset($model->kohteet->osoite))
			$osoite = $model->kohteet->osoite;

		$pushviesti = "Työvuorosi on muuttunut. Alta löydät uudet tiedot:\n
			".$pvm."
			".$m->alku."-".$model->loppu." ".$osoite."
			".$m->tietoja;

		Domainit::sendGCM($tid,"Hei ".$t->tekijan_nimi,$pushviesti, null);
		return true;
	}

	protected function hinnastoHintaat($tp, $asiakkaat, $kohteet)
	{
		$return = [];
		// <-- 1. TuotteetPalvelut
		if(isset($tp->id))
		{
			$return['tp_nimike'] 	= $tp->nimike;
			$return['tp_id'] 	= $tp->id;
			$return['hinta_alv_0'] 	= $tp->hinta_alv_0;
			$return['hinta_alv_sis'] = $tp->hinta_alv_sis;
			$return['alv'] 		= $tp->alv;
			$return['yksikko']	= $tp->yksikko;
		}
		//     TuotteetPalvelut -->

		// <-- 2. Asiakas
		if(isset($tp->id) and isset($asiakkaat->id) and $asiakkaat->hinnasto_id != 0)
		{
			$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$asiakkaat->hinnasto_id."' ");
			if(isset($hinnasto->id))
			{
				$return['hinnasto_rivi_id'] 	= $hinnasto->id;
				$return['hinta_alv_0'] 		= $hinnasto->hinnasto_hinta;
				$return['hinta_alv_sis'] 	= $hinnasto->hinnasto_yht;
				$return['alv'] 			= $hinnasto->hinnasto_alv;
				$return['yksikko']		= $hinnasto->hinnasto_yksikko;
			}
		}
		//     Asiakas -->

		// <-- 3. Kohteet
		if(isset($tp->id) and isset($kohteet->id) and $kohteet->hinnasto_id != 0)
		{
			$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$kohteet->hinnasto_id."' ");
			if(isset($hinnasto->id))
			{
				$return['hinnasto_rivi_id'] 	= $hinnasto->id;
				$return['hinta_alv_0'] 		= $hinnasto->hinnasto_hinta;
				$return['hinta_alv_sis'] 	= $hinnasto->hinnasto_yht;
				$return['alv'] 			= $hinnasto->hinnasto_alv;
				$return['yksikko']		= $hinnasto->hinnasto_yksikko;
			}
		}
		//     Kohteet -->

		return $return; 
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

		// <-- Oleva asiakas
		if(isset($_POST['Tyovuoroot']['kohde']) and $_POST['Tyovuoroot']['kohde'] > 0)
		{

		$kohteet = Kohteet::model()->findByPk($_POST['Tyovuoroot']['kohde']);
		$asiakkaat = Asiakkaat::model()->findByPk($kohteet->asiakas_id);

		if(!isset($kohteet->id) and !isset($asiakkaat->id))
		{
			echo json_encode($return);
			exit;
		}

		$model->attributes=$_POST['Tyovuoroot'];
		if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
			$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
		} else {
			$model->lisa_tuotteet = '';
		}
		if( is_array($model->tyopaari) and count($model->tyopaari) > 0 ){
			$model->tyopaari = json_encode($model->tyopaari);
		} else {
			$model->tyopaari = '';
		}

		$model->kohde = $kohteet->id;
		$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
		if($model->save())
		{

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

			// <-- jos on tyopaari
			$luotu = array();
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    $luotu[$model->id] = $model->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$m=new Tyovuoroot;
				$m->attributes=$_POST['Tyovuoroot'];
				$m->tyopaari = $model->tyopaari;
				$m->lisa_tuotteet = $model->lisa_tuotteet;
				$m->kohde = $kohteet->id;
				$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				$m->tid=$tid;
				$m->status=3;
				if(!$m->save())
				{
					echo json_encode($m->getErrors());
					exit;
				} else {
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
				$yht_alv_nolla = 0;
				$yht_alv = 0;
				$yht_alv_sis = 0;
				   if(isset($_POST['vieposti']) and isset($asiakkaat->sahkoposti) and !empty($asiakkaat->sahkoposti))
				   {
					$message = '<div>';
					$message .= '
					Asiakas: '.$asiakkaat->yhteyshenkilo.'<br>
					Työvuorot:  '.$model->pvm.', '.$model->alku.'-'.$model->loppu.'<br>';
					$message .= '<style>.lahetys_taulu table {border-collapse: collapse; border: 1px solid grey;} .lahetys_taulu th, .lahetys_taulu td{border: 1px solid grey; padding: 7px 15px;}</style>';

					// <-- Paatuote
					$tp = TuotteetPalvelut::model()->findByPK($model->tuoteID);
					if( isset($tp->id) ){
					$message .= '<table class="lahetys_taulu">';
					$message .= '<tr>';
					$message .= '<th>Tuote/Palvelu</th>';
					$message .= '<th>Työntekijät</th>';
					$message .= '<th>Tunnit</th>';
					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<th>Tuntihinta</th>';
					$message .= '<th>Hinta</th>';
					$message .= '<th>ALV</th>';
					$message .= '<th>Yhteensä</th>';
					}
					$message .= '</tr>';

						$tp_maara = (isset($_POST['tyopaari']))?(count($_POST['tyopaari'])+1):1;
						$return_hinnaasto = $this->hinnastoHintaat($tp, $asiakkaat, $kohteet);
						$maara 	= $this->num( strtotime($model->loppu)-strtotime($model->alku) );
						$tunti_hinta = $return_hinnaasto['hinta_alv_0'];
						$hinta_alv_0 = ($return_hinnaasto['hinta_alv_0']*($maara*$tp_maara));
						$hinta_alv_sis = ($return_hinnaasto['hinta_alv_sis']*($maara*$tp_maara));
						$alv = ($hinta_alv_sis-$hinta_alv_0);
						$yht_alv_nolla += $hinta_alv_0;
						$yht_alv += $alv;
						$yht_alv_sis += $hinta_alv_sis;

						if(isset($return_hinnaasto['tp_nimike']) and isset($return_hinnaasto['hinta_alv_0'])){
						$message .= '<tr>';
						$message .= '<td>'.$return_hinnaasto['tp_nimike'].'</td>';
						$message .= '<td>'.$tp_maara.'</td>';
						$message .= '<td>'.$maara.'</td>';
						if(isset($_POST['vie_hintatietoja'])){
						$message .= '<td>'.number_format($tunti_hinta, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($hinta_alv_0, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($alv, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($hinta_alv_sis, 2, ',', ' ').'</td>';
						}
						$message .= '</tr>';
						}
					$message .= '</table>';
					}
					//     Paatuote -->

					if(isset($_POST['vie_hintatietoja']) and !empty($asiakkaat->hinta) and $asiakkaat->hinta_tyyppi == 1)
					{
						$tp_maara = (isset($_POST['tyopaari']))?(count($_POST['tyopaari'])+1):1;
						$tuntia = ((strtotime($model->loppu)-strtotime($model->alku))/3600);
						if( count($luotu) > 0 )
						$tuntia = $tuntia * count($luotu);

						$sum = (($asiakkaat->hinta*$tuntia) + ((($asiakkaat->hinta*$asiakkaat->alv)/100)*$tuntia))*$tp_maara;
						$alv_0 = ($asiakkaat->hinta*$tuntia)*$tp_maara;
						$alv_sum = $sum-$alv_0;

						$message .= 'Työntekijät: '.$tp_maara.'<br>';
						$message .= 'Hinta ALV 0: '.number_format($alv_0, 2, ',', ' ').' &euro;<br>';
						$message .= 'ALV: '.number_format($alv_sum, 2, ',', ' ').' &euro;<br>';
						$message .= 'Hinta: '.number_format($sum, 2, ',', ' ').' &euro;<br>';
						$yht_alv_nolla += $alv_0;
						$yht_alv += $alv_sum;
						$yht_alv_sis += $sum;
					}

					// <-- Lisatuotteet
					$lisa_tuotteet = json_decode($model->lisa_tuotteet, true);
					if( isset($lisa_tuotteet['tuote']) and is_array($lisa_tuotteet['tuote'])  ){
					$message .= '<table class="lahetys_taulu">';
					$message .= '<tr>';
					$message .= '<th>Tuote/Palvelu</th>';
					$message .= '<th>Määrä</th>';
					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<th>Hinta</th>';
					$message .= '<th>ALV</th>';
					$message .= '<th>Yhteensä</th>';
					}
					$message .= '</tr>';
					   foreach($lisa_tuotteet['tuote'] as $k => $v){
						$tp = TuotteetPalvelut::model()->findByPK($v);
						if( isset($tp->id) ){
							$return_hinnaasto = $this->hinnastoHintaat($tp, $asiakkaat, $kohteet);
							$maara = json_decode($model->lisa_tuotteet, true)['maara'][$k];
							$hinta_alv_0 = $return_hinnaasto['hinta_alv_0']*$maara;
							$hinta_alv_sis = $return_hinnaasto['hinta_alv_sis']*$maara;
							$alv	= ($hinta_alv_sis-$hinta_alv_0);
							$yht_alv_nolla += $hinta_alv_0;
							$yht_alv += $alv;
							$yht_alv_sis += $hinta_alv_sis;

							if(isset($return_hinnaasto['tp_nimike']) and isset($return_hinnaasto['hinta_alv_0'])){
							$message .= '<tr>';
							$message .= '<td>'.$return_hinnaasto['tp_nimike'].'</td>';
							$message .= '<td>'.$maara.'</td>';
							if(isset($_POST['vie_hintatietoja'])){
							$message .= '<td>'.number_format($hinta_alv_0, 2, ',', ' ').'</td>';
							$message .= '<td>'.number_format($alv, 2, ',', ' ').'</td>';
							$message .= '<td>'.number_format($hinta_alv_sis, 2, ',', ' ').'</td>';
							}
							$message .= '</tr>';
							}
						}
					   }
					$message .= '</table>';
					}
					//     Lisatuotteet -->

					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<br>';
					$message .= '<h3>Veroton hinta '.number_format($yht_alv_nolla, 2, ',', ' ').'&euro;<br>';
					$message .= 'ALV '.number_format($yht_alv, 2, ',', ' ').'&euro;<br>';
					$message .= 'Hinta ALV sis. '.number_format($yht_alv_sis, 2, ',', ' ').'&euro;<br>';
					$message .= '</h3><br>';
					}


					if(!empty($model->toimenpiteet))
					$message .= str_replace("\n", "<hr><br>",$model->toimenpiteet)."<br>";

					if(isset($_POST['Tyovuoroot']['tilausviesti']) and !empty($_POST['Tyovuoroot']['tilausviesti']))
					$message .= str_replace("\n", "<br>", $_POST['Tyovuoroot']['tilausviesti']);

					$message .= '<h2>Kiitos tilauksesta.</h2>';
					$message .= '</div>';
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
		}

		echo json_encode($return);
		exit;

		}
		//  Oleva asiakas -->

		if(isset($_POST['Tyovuoroot']))
		{

		$asiakkaat = new Asiakkaat;
		$asiakkaat->attributes = $_POST['Asiakkaat'];
		$asiakkaat->aktiivinen = 1;
		if(isset($_POST['Asiakkaat']['ryhma'])){
			$asiakkaat->ryhma=json_encode($_POST['Asiakkaat']['ryhma']);
		} else {
			$asiakkaat->ryhma="";
		}

		  if($asiakkaat->save())
		  {

			// <-- LOG
			$model_log 	= 'Asiakkaat';
			$name_log 	= 'Asiakkaat';
			$status_log 	= 'Create';
			if(isset($_POST[$model_log]))
			{
				$old_values = null;
				$n_m = Asiakkaat::model()->findbypk($asiakkaat->id);
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

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
			$kohteet->tietoja = $_POST['Tyovuoroot']['tietoja'];

		  	   if($kohteet->save())
		  	   {

				// <-- LOG
				$model_log 	= 'Kohteet';
				$name_log 	= 'Kohteet';
				$status_log 	= 'Create';
					$old_values = null;
					$n_m = Kohteet::model()->findbypk($kohteet->id);
					$new_values = json_encode($n_m->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->

				$model->attributes=$_POST['Tyovuoroot'];
				if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
					$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
				} else {
					$model->lisa_tuotteet = '';
				}
				if( is_array($model->tyopaari) and count($model->tyopaari) > 0 ){
					$model->tyopaari = json_encode($model->tyopaari);
				} else {
					$model->tyopaari = '';
				}
				$model->kohde = $kohteet->id;
				$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				if($model->save())
				{
				
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


			// <-- jos on tyopaari
			$luotu = array();
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    $luotu[$model->id] = $model->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$m=new Tyovuoroot;
				$m->attributes=$_POST['Tyovuoroot'];
				$m->tyopaari = $model->tyopaari;
				$m->lisa_tuotteet = $model->lisa_tuotteet;
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
				$yht_alv_nolla = 0;
				$yht_alv = 0;
				$yht_alv_sis = 0;
				   if(isset($_POST['vieposti']) and isset($asiakkaat->sahkoposti) and !empty($asiakkaat->sahkoposti))
				   {
					$message = '<div>';
					$message .= '
					Asiakas: '.$asiakkaat->yhteyshenkilo.'<br>
					Työvuorot:  '.$model->pvm.', '.$model->alku.'-'.$model->loppu.'<br>';
					$message .= '<style>.lahetys_taulu table {border-collapse: collapse; border: 1px solid grey;} .lahetys_taulu th, .lahetys_taulu td{border: 1px solid grey; padding: 7px 15px;}</style>';

					// <-- Paatuote
					$tp = TuotteetPalvelut::model()->findByPK($model->tuoteID);
					if( isset($tp->id) ){
					$message .= '<table class="lahetys_taulu">';
					$message .= '<tr>';
					$message .= '<th>Tuote/Palvelu</th>';
					$message .= '<th>Työntekijät</th>';
					$message .= '<th>Tunnit</th>';
					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<th>Tuntihinta</th>';
					$message .= '<th>Hinta</th>';
					$message .= '<th>ALV</th>';
					$message .= '<th>Yhteensä</th>';
					}
					$message .= '</tr>';

						$tp_maara = (isset($_POST['tyopaari']))?(count($_POST['tyopaari'])+1):1;
						$return_hinnaasto = $this->hinnastoHintaat($tp, $asiakkaat, $kohteet);
						$maara 	= $this->num( strtotime($model->loppu)-strtotime($model->alku) );
						$tunti_hinta = $return_hinnaasto['hinta_alv_0'];
						$hinta_alv_0 = ($return_hinnaasto['hinta_alv_0']*($maara*$tp_maara));
						$hinta_alv_sis = ($return_hinnaasto['hinta_alv_sis']*($maara*$tp_maara));
						$alv = ($hinta_alv_sis-$hinta_alv_0);
						$yht_alv_nolla += $hinta_alv_0;
						$yht_alv += $alv;
						$yht_alv_sis += $hinta_alv_sis;

						if(isset($return_hinnaasto['tp_nimike']) and isset($return_hinnaasto['hinta_alv_0'])){
						$message .= '<tr>';
						$message .= '<td>'.$return_hinnaasto['tp_nimike'].'</td>';
						$message .= '<td>'.$tp_maara.'</td>';
						$message .= '<td>'.$maara.'</td>';
						if(isset($_POST['vie_hintatietoja'])){
						$message .= '<td>'.number_format($tunti_hinta, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($hinta_alv_0, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($alv, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($hinta_alv_sis, 2, ',', ' ').'</td>';
						}
						$message .= '</tr>';
						}
					$message .= '</table>';
					}
					//     Paatuote -->

					if(isset($_POST['vie_hintatietoja']) and !empty($asiakkaat->hinta) and $asiakkaat->hinta_tyyppi == 1)
					{
						$tp_maara = (isset($_POST['tyopaari']))?(count($_POST['tyopaari'])+1):1;
						$tuntia = ((strtotime($model->loppu)-strtotime($model->alku))/3600);
						if( count($luotu) > 0 )
						$tuntia = $tuntia * count($luotu);

						$sum = (($asiakkaat->hinta*$tuntia) + ((($asiakkaat->hinta*$asiakkaat->alv)/100)*$tuntia))*$tp_maara;
						$alv_0 = ($asiakkaat->hinta*$tuntia)*$tp_maara;
						$alv_sum = $sum-$alv_0;

						$message .= 'Työntekijät: '.$tp_maara.'<br>';
						$message .= 'Hinta ALV 0: '.number_format($alv_0, 2, ',', ' ').' &euro;<br>';
						$message .= 'ALV: '.number_format($alv_sum, 2, ',', ' ').' &euro;<br>';
						$message .= 'Hinta: '.number_format($sum, 2, ',', ' ').' &euro;<br>';
						$yht_alv_nolla += $alv_0;
						$yht_alv += $alv_sum;
						$yht_alv_sis += $sum;
					}

					// <-- Lisatuotteet
					$lisa_tuotteet = json_decode($model->lisa_tuotteet, true);
					if( isset($lisa_tuotteet['tuote']) and is_array($lisa_tuotteet['tuote'])  ){
					$message .= '<table class="lahetys_taulu">';
					$message .= '<tr>';
					$message .= '<th>Tuote/Palvelu</th>';
					$message .= '<th>Määrä</th>';
					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<th>Hinta</th>';
					$message .= '<th>ALV</th>';
					$message .= '<th>Yhteensä</th>';
					}
					$message .= '</tr>';
					   foreach($lisa_tuotteet['tuote'] as $k => $v){
						$tp = TuotteetPalvelut::model()->findByPK($v);
						if( isset($tp->id) ){
							$return_hinnaasto = $this->hinnastoHintaat($tp, $asiakkaat, $kohteet);
							$maara = json_decode($model->lisa_tuotteet, true)['maara'][$k];
							$hinta_alv_0 = $return_hinnaasto['hinta_alv_0']*$maara;
							$hinta_alv_sis = $return_hinnaasto['hinta_alv_sis']*$maara;
							$alv	= ($hinta_alv_sis-$hinta_alv_0);
							$yht_alv_nolla += $hinta_alv_0;
							$yht_alv += $alv;
							$yht_alv_sis += $hinta_alv_sis;

							if(isset($return_hinnaasto['tp_nimike']) and isset($return_hinnaasto['hinta_alv_0'])){
							$message .= '<tr>';
							$message .= '<td>'.$return_hinnaasto['tp_nimike'].'</td>';
							$message .= '<td>'.$maara.'</td>';
							if(isset($_POST['vie_hintatietoja'])){
							$message .= '<td>'.number_format($hinta_alv_0, 2, ',', ' ').'</td>';
							$message .= '<td>'.number_format($alv, 2, ',', ' ').'</td>';
							$message .= '<td>'.number_format($hinta_alv_sis, 2, ',', ' ').'</td>';
							}
							$message .= '</tr>';
							}
						}
					   }
					$message .= '</table>';
					}
					//     Lisatuotteet -->

					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<br>';
					$message .= '<h3>Veroton hinta '.number_format($yht_alv_nolla, 2, ',', ' ').'&euro;<br>';
					$message .= 'ALV '.number_format($yht_alv, 2, ',', ' ').'&euro;<br>';
					$message .= 'Hinta ALV sis. '.number_format($yht_alv_sis, 2, ',', ' ').'&euro;<br>';
					$message .= '</h3><br>';
					}


					if(!empty($model->toimenpiteet))
					$message .= str_replace("\n", "<hr><br>",$model->toimenpiteet)."<br>";

					if(isset($_POST['Tyovuoroot']['tilausviesti']) and !empty($_POST['Tyovuoroot']['tilausviesti']))
					$message .= str_replace("\n", "<br>", $_POST['Tyovuoroot']['tilausviesti']);

					$message .= '<h2>Kiitos tilauksesta.</h2>';
					$message .= '</div>';
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
		if(isset($_GET['year']) or isset($_GET['week'])){
			if(isset($_GET['year']) and !empty($_GET['year']))
				Yii::app()->session['year'] = $_GET['year'];
			
			if(isset($_GET['week']) and !empty($_GET['week']))
				Yii::app()->session['week'] = $_GET['week'];

			if(isset($_GET['tid']) and !empty($_GET['tid']))
				Yii::app()->session['tyontekijat'] = array($_GET['tid']);

			if(isset($_GET['tv_id'])){ $this->redirect(array('index', 'tv_id' => $_GET['tv_id'])); } 
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
			Yii::app()->session['year'] = date("Y", strtotime('this week sunday'));

		if(!isset(Yii::app()->session['week']))
			Yii::app()->session['week'] = date("W", strtotime('this week sunday'));

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

	        	$criteria->select = "id,tekijan_nimi, sukunimi";
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


        		$criteria->select = "id,tekijan_nimi, sukunimi, tyoryhma";
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
		$tv_pois = Tyovuoroot::model()->findAll($criteria);

						foreach($tv_pois as $item){
							$tv = Tyovuoroot::model()->findbypk($item->id);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
						}

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

	public function tilanteet()
	{
        	$l = array(
			3=>Yii::t('main', 'Työ'),
			2=>Yii::t('main', 'Matka'),
			10=>Yii::t('main', 'Lounastauko'),
			11=>Yii::t('main', 'Lomat ja poissaolot')
		);
		return $l;
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

	protected function vkoPaivatLyhyesti(){

		$arr = array(
		    1=>'Ma',
		    2=>'Ti',
		    3=>'Ke',
		    4=>'To',
		    5=>'Pe',
		    6=>'La',
		    7=>'Su',
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
		if(isset($_GET['from']) and !empty($_GET['from']))
		$from = date("d.m.Y", strtotime($_GET['from']));
		if(isset($_GET['to']) and !empty($_GET['to']))
		$to = date("d.m.Y", strtotime($_GET['to']));

		$haku_criteria = [];

		if(isset($_GET['laskutettu']) and !empty($_GET['laskutettu'])){
	        	$haku_criteria[] = " laskutettu='".$_GET['laskutettu']."' ";
		} else {
	        	$haku_criteria[] = " laskutettu='0' ";
		}
		if(isset($_GET['peruutettu']) and $_GET['peruutettu'] == 1){
	        	$haku_criteria[] = " peruutettu!='0' ";
		} else {
	        	$haku_criteria[] = " peruutettu='0' OR peruutettu IS NULL";
		}
		if(isset($_GET['uusi_tilaus']) and !empty($_GET['uusi_tilaus'])){
	        	$haku_criteria['uusi_tilaus'] = " t.uusi_tilaus='".$_GET['uusi_tilaus']."' ";
		} else {
	        	$haku_criteria['uusi_tilaus'] = " t.uusi_tilaus='0' ";
		}
		if(isset($_GET['status']) and !empty($_GET['status'])){
			$impl_status = implode(",", $_GET['status']);
	        	$haku_criteria[] = " status IN ($impl_status) ";
		}
		if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi'])){
	        	$haku_criteria[] = " kohde IN (SELECT id FROM sivex_kohdet WHERE 
				asiakas_id IN (
					SELECT id FROM asiakkaat WHERE
					yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR yhteyshenkilo LIKE '%".$_GET['yrityksen_nimi']."%'
				)
			) ";
		}
		if(isset($_GET['osoite']) and !empty($_GET['osoite'])){
	        	$haku_criteria[] = " kohde IN (SELECT id FROM sivex_kohdet WHERE osoite LIKE '%".$_GET['osoite']."%') ";
		}

		$perSivu = 50;
		$tids = (isset($_GET['tekijaPaaSivulla']))?$_GET['tekijaPaaSivulla']:[];
		$with	= ['data'];
		$dataAll = $this->FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, $with);

		$this->render('lista', array(
			'dataAll' => $dataAll,
			'perSivu' => $perSivu,
			'from' => $from,
			'to' => $to,
		));

	}

	public function FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, $with)
	{

		if( count($tids) == 0 ){
			$tt = Tyontekijat::model()->findAll("aktiivinen=1");
			$arr_tids = [];
			foreach($tt as $item)
				$arr_tids[$item->id] = $item->id;
			$tids = $arr_tids;
		}

		$data = [];
		$pvm_from = date("Y-m-d", strtotime($from));
		$pvm_to = ($to !== null)?date("Y-m-d", strtotime($to)):null;
		$tv_arr = $this->tv_arr($pvm_from, $pvm_to, $tids, $haku_criteria, false, $with);

		/*
		$tids_after = [];
		foreach($tv_arr as $t => $arr)
			$tids_after[] = $t;
		*/

		// <-- Sort by PVM
		$sort = [];
		foreach($tids as $tid)
			if(isset($tv_arr[$tid]))
				foreach($tv_arr[$tid] as $k => $v)
					foreach($v as $k1 => $v1)
						foreach($v1 as $k2 => $v2)
							$sort[strtotime($v2['this_pvm'].' '.$v2['data']['alku'])][$tid][] = $v1;

		ksort($sort);

		foreach($sort as $k => $v)
			foreach($v as $k1 => $v1)
				foreach($v1 as $k2 => $v2)
					foreach($v2 as $k3 => $v3)
						$data[] = $v3;

		/*
		echo '<pre>';
		print_r( $data );
		echo '</pre>';
		exit;
		*/
	
		return $data;
	}

	public function actionSiirto($kenelta=null, $kenelle=null, $alkaen=null)
	{
		if( $alkaen !== null and date('Ymd', strtotime($alkaen)) < date('Ymd') ){
			Yii::app()->user->setFlash('danger','Työvuoroja menneisyydestä ei voida siirtää.');
				$this->redirect(array('siirto'));
		}

		$data_kenelta 	= [];
		$data_kenelle 	= [];
		$data_kenelta_k = [];
		if( $kenelta !== null and $kenelle !== null ){

			$from		= date("Y-m-d", strtotime($alkaen));
			$criteria = new CDBCriteria;
        		$criteria->condition = "
				tid='".$kenelta."'
				AND toistuva_id=0
				AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '$from'
			";
			$tv = Tyovuoroot::model()->findAll($criteria);
			$data_kenelta 	= $tv;

			$from		= date("Y-m-d", strtotime($alkaen));
			$criteria = new CDBCriteria;
        		$criteria->condition = "
				(tid='".$kenelta."' OR tyopaari LIKE'%\"$kenelta\"%')
				AND DATE(STR_TO_DATE(pto, '%d.%m.%Y')) >= '$from'
			";
			$toistuvat = ToistuvatTyovuorot::model()->findAll($criteria);
			$data_kenelta_k	= $toistuvat;

		}
		$tilanteet = $this->tilanteet();
		$this->render('siirto', array(
			'data_kenelta' => $data_kenelta,
			'data_kenelta_k' => $data_kenelta_k,
			'alkaen' => $alkaen,
			'tilanteet' => $tilanteet
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

	public function actionVlupdater($id,$txt)
	{

	
	   if($id == 'new' and $txt == '')
	   {
		$model=new Vuosilomat;
		if(isset($_POST['Vuosilomat']))
		{
			$model->attributes=$_POST['Vuosilomat'];
			if($model->save()){
				echo $model->id.'//'.$model->tid.'//'.$model->pvm.'//'.$model->status;

			//$valikkoot = Valikkoot::model()->find(" select_type='tyoajanlaatu' and value like '%".$lat."%' ");
			$tv = new Tyovuoroot;
			$tv->tid=$model->tid;
			$tv->pvm=date("d.m.Y",strtotime($model->pvm));
			$tv->tyoajanlaatu=$_POST['Vuosilomat']['tyoajanlaatu'];
			$tv->alku='00:00';
			$tv->loppu='00:00';
			$tv->pituus='00:00';
			$tv->tietoja=$_POST['Vuosilomat']['tietoja'];
			$tv->save();
			} else {
				print_r($_POST);
			}
		}

	   } else {
		//Tyovuoroot::model()->deleteAll(" tid = '".$_POST['Vuosilomat']['tid']."' and pvm='".date("d.m.Y",strtotime($_POST['Vuosilomat']['pvm']))."' and tyoajanlaatu like '%".$txt."%' ");
		//$this->loadModel($id)->delete();
		// pois kaytosta 06.06.2019
				echo 'removed';
	   }


		//$this->renderPartial('vlupdater');

	}

	public function tvlaskentaPerTuoteet($from, $to){

		$criteria = new CDBCriteria;
        	$criteria->condition = "
			(tuoteID!=0 OR lisa_tuotteet!='')
			AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) BETWEEN '$from' AND '$to'
		";
		$tv = Tyovuoroot::model()->findAll($criteria);
		$lisa_tuotteet = [];
		$tuotteet = [];
		foreach($tv as $item){
			if(!isset($tuotteet[$item->tuoteID]))
				$tuotteet[$item->tuoteID] = 0;
			else
				$tuotteet[$item->tuoteID] += 1;

			$dec = json_decode($item->lisa_tuotteet, true);
			if( is_array($dec) ){
				foreach($dec as $k => $v){
					if($k == 'tuote'){
						foreach($v as $k1 => $v1){
							if(!isset($lisa_tuotteet[$v1]))
								$lisa_tuotteet[$v1] = 1;
							else
								$lisa_tuotteet[$v1] += 1;
						}
					}
				}
			}
		}
		return ['tv' => $tuotteet, 'lisa_tuotteet' => $lisa_tuotteet];
	}
}

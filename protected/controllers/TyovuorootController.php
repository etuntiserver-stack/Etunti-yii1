<?php

/** {@inheritdoc/} */
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
				'actions'=>array('admin','delete','index','tv2','view','updatetime','showohje',
					'muisti','operatio', 'viikko','viikkottain', 'viikkottain_pdf', 
					'laheta','kk','pvmtid','laheta_k', 'muistin', 'muisticlear', 
					'muistissa', 'vkolopput', 'vkolopchange', 'uusitilaus', 
					'virtual_migration', 'vmigrate_ajax_next', 'find_past_chains', 
					'beta', 'did4', 'PoistaTv', 'valitse_kokopaiva', 'tv_kohteet', 
					'siivous_tyonimike', 'getKohdeByAsiakas', 'getKohdeById', 
					'getAsiakasByKohde', 'paivita_laatikot', 'poista_toistuva', 
					'onko_sama', 'asiakas_autocomplete', 'kohde_autocomplete', 
					'get_tekijantiedot', 'is_asiakas', 'is_yhteyshenkilo', 'lista', 
					'siirto', 'palkkataulukko', 'hovertietoja', 'create4', 'update4', 
					'create4_form', 'update4_form', 'pvmTarkistus_lista', 
					'pois_pvm_ketjusta', 'palauta_pvm_kejuun', 'pto_muutos', 
					'contextmenu_valinnat', 'contextmenu_submits', 'getsumbyweekall', 
					'tvasetus', 'omasiistijat_lista', 'omasiistijat_tarkistus', 
					'omasiistijat_ilmoitus', 'omasiistijat_siistijakohtainen_varoitus', 
					'os_cache_clear', 'massedit', 'aloitusaikojen_ilmoitus', 
					'tekijahovertietoja', 'employeeskills'),
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

		if(!isset(Yii::app()->user->adminID)) {
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
    if (Yii::app()->controller->isEtuntiAdmin())
      Yii::app()->theme = Yii::app()->user->user_theme ?? 'etunti';
    else
      Yii::app()->theme = 'classic';
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
		(tyoajanmerkinta NOT LIKE '%Ei lasketa%' AND tyoajanmerkinta NOT LIKE '%Varallaolo%' AND tyoajanmerkinta NOT LIKE '%Ehdollinen varallaolo%')
		";
		return $return;
	}

	public function actionIs_yhteyshenkilo()
	{
		$bd = '';
		if(Yii::app()->request->getPost('yhteyshenkilo'))
		{
			$model = Asiakkaat::model()->find(" etunimi LIKE '%".Yii::app()->request->getPost('yhteyshenkilo')."' OR sukunimi LIKE '%".Yii::app()->request->getPost('yhteyshenkilo')."'");
			if(isset($model->id))
			$bd = Yii::t('main', 'Asiakas löytyi tietokannasta');	
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
			$bd .= Yii::t('main', 'Asiakas löytyi tietokannasta');	
		}
		if(Yii::app()->request->getPost('sahkoposti'))
		{
			$model = Asiakkaat::model()->find(" sahkoposti LIKE '%".Yii::app()->request->getPost('sahkoposti')."' ");
			if(isset($model->id))
			$bd .= Yii::t('main', 'Asiakas löytyi tietokannasta');	
		}
		echo json_encode($bd);
		exit;
	}

	public function actionGetKohdeByAsiakas($id)
	{
		$return = [];
		$model = Kohteet::model()->findAll("asiakas_id='".$id."' order by ensisijainen DESC, osoite");
		$options = '';
		$options .= '<option value="0">Valitse osoite</option>';
		$ids = [];
		foreach($model as $k){
			$ids[$k->id] = $k->id;
			$options .= '<option value="'.$k->id.'">'.$k->osoite.'</option>';
		}
		$return = ['first' => array_shift($ids), 'options' => $options];

		echo json_encode($return);	
	}

	public function actionGetKohdeById($id)
	{
		$model = Kohteet::model()->findByPk($id);
			$bd = '';
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
				$asiakas = $a->Fullname;
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
			$explode = explode(",", $ft->sahkoposti);
			$saaja = $explode;

			$html = '<meta charset="UTF-8">';
			$html .= $this->renderPartial('laheta_k',array('week'=>$week,'year'=>$year,'tulosta'=>'lista'),true);

			$basePath = Yii::app()->basePath.'/../emails/tyovuorot/'.Yii::app()->user->domain.'/';
			$path = 'emails/tyovuorot/'.Yii::app()->user->domain.'/';
			if (!file_exists( $basePath )) {
			 	mkdir( $basePath, 0777, true );
			}
			// I have no idea what the key is supposed to be here...
			$key = "";
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
			$return['varoitus_tyopaari'] = ['alku' => $model->alku, 'loppu' => $model->loppu, 'osoite' => $model->OsoiteById];
		if( $toistuva )
			$return['varoitus_toistuva'] = ['alku' => $model->alku, 'loppu' => $model->loppu, 'osoite' => $model->OsoiteById];
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
		if(isset($_SESSION['muistin'])){
			$clearing = []; // Otetaan pois jos on samanlainen
			foreach ($_SESSION['muistin'] as $key => $value){
			  if(!in_array($value, $clearing))
			    $clearing[] = $value;
			}
			$_SESSION['muistin'] = $clearing;
			echo json_encode($_SESSION['muistin']);
		} else {
			echo json_encode('muistityhja');
		}
		exit;
	}

	public function actionMuisticlear()
	{
		if(isset($_SESSION['muistin']) and isset($_POST['clear'])){
			echo json_encode($_SESSION['muistin']);
			unset($_SESSION['muistin']);
		}
		exit;
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
		if( $_POST['tilanne'] == 'poista_pvm')
		{
			if( $toistuva ){
				$u		= Yii::app()->user->nimi;
				$d		= date("d.m.Y");
				$poisto_syy	= ['text'=>'ByTVcard', 'user'=>$u, 'date'=>$d];
				$this->toistuvaDeletePvm($model->id, $pvm, $tid, $poisto_syy);
			}
			if( !$toistuva ){
				if( !$this->tyopari_poisto($model, [$model->tid => $model->tid]) ){
					$this->tvDeleteLog($model);
					$model->deleteByPk($model->id);
				}
			}
		}
		if( $toistuva and $_POST['tilanne'] == 'poista_ketju_kokonaan'){
			ToistuvatTyovuorot::model()->deleteByPk($model->id);
			$return = ['return' => 'ok'];
		}
		if( $toistuva and $_POST['tilanne'] == 'poista_alkaen'){
			$laatikko_pvm = $_POST['laatikko_pvm'];
			$laatikko_tid = $_POST['laatikko_tid'];
			$tids_new = [];
			$tids_new[$laatikko_tid] = $laatikko_tid;
			foreach(json_decode($model->tyopaari, true) as $tid_origin)
				$tids_new[$tid_origin] = $tid_origin;
			ksort($tids_new);
			unset($tids_new[$laatikko_tid]);

			// < --poistettu_pvms_for_poistettava
			$poistettu_pvms_for_poistettava = [];
			foreach(json_decode($model->new_poistettu_pvm, true) as $key => $val){
				if( isset($val['tid']) and $val['tid'] == $laatikko_tid and isset($val['pvm']) and isset($val['syy']) ){
					$poistettu_pvms_for_poistettava[] = ['tid' => $val['tid'], 'pvm' => $val['pvm'], 'syy' => $val['syy']];
				}
			}
			$clearing_for_poistettava = []; // Otetaan pois jos on samanlainen
			foreach ($poistettu_pvms_for_poistettava as $key => $value){
			  if(!in_array($value, $clearing_for_poistettava))
			    $clearing_for_poistettava[] = $value;
			}

			// < --poistettu_pvms_for_old
			$poistettu_pvms_for_old = [];
			foreach(json_decode($model->new_poistettu_pvm, true) as $key => $val){
				if( isset($val['tid']) and $val['tid'] != $laatikko_tid and isset($val['pvm']) and isset($val['syy']) ){
					$poistettu_pvms_for_old[] = ['tid' => $val['tid'], 'pvm' => $val['pvm'], 'syy' => $val['syy']];
				}
			}
			$clearing_for_old = []; // Otetaan pois jos on samanlainen
			foreach ($poistettu_pvms_for_old as $key => $value){
			  if(!in_array($value, $clearing_for_old))
			    $clearing_for_old[] = $value;
			}

			$new_tv_for_poistettavahenkilo = new ToistuvatTyovuorot;
			$new_tv_for_poistettavahenkilo->attributes = $model->attributes;
			$new_tv_for_poistettavahenkilo->tid = $laatikko_tid;
			$new_tv_for_poistettavahenkilo->tyopaari = '';
			$new_tv_for_poistettavahenkilo->new_poistettu_pvm = (count($clearing_for_poistettava) > 0)? json_encode($clearing_for_poistettava): '';
			$new_tv_for_poistettavahenkilo->pto = date("d.m.Y", strtotime($laatikko_pvm." -1 day"));
			if( date("Ymd", strtotime($laatikko_pvm." -1 day")) >= date("Ymd", strtotime($new_tv_for_poistettavahenkilo->pfrom)))
				$new_tv_for_poistettavahenkilo->save();

			if( count($tids_new) > 0 ){
				$model->tid = array_shift($tids_new);
				$model->tyopaari = (count($tids_new) > 1)? json_encode($tids_new) : '';
				$model->new_poistettu_pvm = (count($clearing_for_old) > 0)? json_encode($clearing_for_old): '';
				$model->save();
			} else {
				$model->delete();
			}
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
			if( isset($a->id) )
				$kuka = $a->Fullname;
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

	public function actionPois_pvm_ketjusta($toistuva_id, $tid, $pvm, $peruuttaminen, $tyopaari_mukaan=null)
	{

		if(is_array(json_decode($tyopaari_mukaan, true))){
			$tyopaari_mukaan = json_decode($tyopaari_mukaan, true);
		}
		if( (int)$peruuttaminen > 0 ){

			$tilanne 	= ['peruutettu' => (int)$peruuttaminen];
			$poisto_by	= 'ByCalendarPeruutettu';

			if(is_array($tyopaari_mukaan) and count($tyopaari_mukaan) > 0){
				foreach($tyopaari_mukaan as $k => $v)
					$this->VirtualtoTV($toistuva_id, $k, $v, $tilanne, $poisto_by);
			} else {
				$this->VirtualtoTV($toistuva_id, $tid, $pvm, $tilanne, $poisto_by);
			}

		} else {

			$poisto_by 	= 'ByCalendar';
			$u		= Yii::app()->user->nimi;
			$d		= date("d.m.Y");
			$poisto_syy	= ['text'=> $poisto_by, 'user' => $u, 'date' => $d];

			if(is_array($tyopaari_mukaan) and count($tyopaari_mukaan) > 0){
				foreach($tyopaari_mukaan as $k => $v)
					$this->toistuvaDeletePvm($toistuva_id, $v, $k, $poisto_syy);
			} else {
				$this->toistuvaDeletePvm($toistuva_id, $pvm, $tid, $poisto_syy);
			}
		}

		$toistuva = ToistuvatTyovuorot::model()->findbypk($toistuva_id);
		// <-- Tids
		$tids = [];
		$tids[$tid] = $tid;
		if( isset($toistuva->tyopaari) and is_array(json_decode($toistuva->tyopaari, true)) ){
			foreach(json_decode($toistuva->tyopaari, true) as $tp_tid)
				$tids[$tp_tid] = $tp_tid;
		}

		$pvm_from 	= date("Y-m-d", strtotime($pvm." -1 day"));
		$pvm_to 	= date("Y-m-d", strtotime($pvm." +1 day"));
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

	protected function checkNextTv($this_id)
	{
		$return 	= '';
		$get_id 	= $this->this_id($this_id);

    // Lisätty 11.08.2020: Seuraava $model aiheutti virheen @actionCreate4_form.
    if (empty($get_id)) {
      return false;
    }

		$model 		= $get_id['model'];
		$pvm 		= $get_id['pvm'];
		$tid 		= $get_id['tid'];

		if(isset($model->kohde)){
			$with		= ['data'];
			$from		= date("Y-m-d", strtotime($pvm." +1 day"));
			$to		= date("Y-m-d", strtotime($from." +1 month"));
			$haku_criteria 	= ["kohde='".$model->kohde."' AND alku='".$model->alku."' AND loppu='".$model->loppu."' AND status='".$model->status."'"];
			$dataAll 	= $this->FromToSuunnitellutAll($from, $to, [$tid], $haku_criteria, $with);
			foreach($dataAll as $arr){
				$return	= $arr['this_pvm'];
				break;
			}
		}
		return $return;
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
		$kohteet = Yii::app()->createController('Kohteet');
		$url_linkit = $kohteet[0]->getKohdeUrls($m->url_linkkit);
		if($tv_id !== null){
			$tv = Tyovuoroot::model()->find(" id='".$tv_id."' AND kohde='".$id."' ");
			if(isset($tv->id)){
				$url_linkit = $this->getTVUrls($tv->url_linkkit);
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
		  $ohje .= "<br>Tietoja mobiilisovellukseen: ".$m->tietoja;
		  $tietoja = $m->tietoja;
		}
		if(!empty($m->muut))
		  $ohje .= "<br>Muut: ".$m->muut;

		// <-- Tiedostot
		$tiedostot = '';
		foreach(array_reverse(glob('tiedostot/kohteet/'.Yii::app()->user->domain.'/tyonkuvaukset/'.$m->id.'_*.*')) as $file) {
			$ext = pathinfo(basename($file), PATHINFO_EXTENSION);
		 	$tiedostot .= '
			  <div class="col-sm-4">';
				// <-- file_safe_opener
				$filepath = Yii::getPathOfAlias('application').'/../'.$file;
				$tiedostot .= CHtml::link(basename($file),
					array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
					array(
						'target'=>'_blank',
						'class'=>'link'
				));
				//     file_safe_opener// -->
			$tiedostot .= '</div>';
		}

		$tuote 				= 0;
		$tuotteen_yksikko 	= '';
		if(isset($m->laskurivi_tyyppi) and $m->laskurivi_tyyppi == 'tunti' and $m->tuote_h != 0)
			$tuote = $m->tuote_h;
			
		echo json_encode(array($ohje,$tietoja,$m->arvioitu_kesto,$m->osoite,$m->pnumero,$m->kaupunki,$tyo_erittelyt,$m->puh_nro,
			$m->email,$m->arvioitu_kello_alku,$m->arvioitu_kello_loppu, $tiedostot, 
			$url_linkit, $tuote, 
			Asiakkaat::FINNISH_SERVICE_WISH[$m->asiakkaat->finnish_service_wish ?? 0],
			$m->asiakkaat->asiakasnumero
		));
		exit;
	}

	public function getTVUrls($url_linkkit)
	{
		$ready 	= [];
		if(is_array(json_decode($url_linkkit, true)))
		{
			$arr 	= json_decode($url_linkkit, true);
			$urls 	= [];
	 		foreach($arr as $k => $v){
		 		foreach($v as $k1 => $v1){
					if($k == 'url')
						$urls[$k1] = $v1;
				}
			}
	 		foreach($arr as $k => $v){
		 		foreach($v as $k1 => $v1){
					if($k == 'nimike' and isset($urls[$k1]))
						$ready[$v1] = $urls[$k1];
				}
			}
		}	

		return $ready;
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

	public function actionTv2()
	{
		die('Tämä sivu on poistettu käytöstä!<br> Ohjelman vaseman reunassa löydät uudet sivut työvuoroihiin.');
	}
	public function actionIndex()
	{
		die('Tämä sivu on poistettu käytöstä!<br> Ohjelman vaseman reunassa löydät uudet sivut työvuoroihiin.');
	}

	public function actionBeta($kohteet_siivous = [], $kohde = '', $asiakas = '', $mode = null, $stage = null)
	{

		$site = Yii::app()->createController('Site');
		$arrDate = array(1 => "Ma", 2 => "Ti", 3 => "Ke", 4 => "To", 5 => "Pe", 6 => "La", 7 => "Su");
		$asetukset = Asetukset::model()->findByPk(1);

		// <-- Onlinevaraus autopoisttaminen
		$this->poistaminenOnlineVarauksetJokaMeniOhi($asetukset->onlinevaraus_autoremove);

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
			unset($_SESSION['haku_criteria_tv']);
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
			// tv_filter is set to 1 from _showshift.phps $link, which is used
			// to open a shift through a client
			if(isset($_GET["tv_filter"]) and isset($_GET["tv_id"]) and
				$_GET["tv_filter"] == 1) {

				// KP: set "tyoryhma" as the workers "tyoryhma",
				// and "tyo_toimialue" as the workers "tyo_toimialue" (this can be null)
				// this should speed up loading nicely
				$domain = Yii::app()->user->domain;
				if($domain == "kotipuhtaaksi") {
					$result = $this->this_id($_GET["tv_id"]);
					$shift = $result["model"];
					$worker = Tyontekijat::model()->findByPk($shift->tid);
					if($worker) {
						$groups = json_decode($worker->tyoryhma);
						Yii::app()->session["tyo_toimialue"] = $worker->tyo_toimialue;
						Yii::app()->session["tyoryhma"] = $groups;
					}
					
					// just in case, unset "tyontekijat" from session, which could be [0] at this time.
					// that would defeat the purpose of this, since the user wouldn't see any workers in the calendar
					unset(Yii::app()->session["tyontekijat"]);
				}
				$this->redirect(array('beta', 'mode' => $mode, 'tv_id' => $_GET['tv_id']));
			}

			if (isset($_GET['tv_id'])) 
				$this->redirect(array('beta', 'mode' => $mode, 'tv_id' => $_GET['tv_id']));
			if(isset($_GET['vapaat']))
				$this->redirect(array('beta', 'mode' => $mode, 'vapaat' => 'true'));
			else
				$this->redirect(array('beta', 'mode' => $mode));
		}

		//  GET haku -->

		// if KotiPuhtaaksi is navigating here (from the side bar), the url contains &blank=true, which we can
		// use to make this action return a blank calendar
		if(isset($_GET["blank"])) {
			$_POST["haku"] = true;
			$_POST["tyontekijat"] = [0];
		}

		// <-- Post haku
		if (isset($_POST['haku'])) {
			unset($_SESSION['haku_criteria_tv']);

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

			if (isset($_POST['year']) and !empty($_POST['year']))
				Yii::app()->session['year'] = $_POST['year'];
			if (isset($_POST['week']) and !empty($_POST['week']))
				Yii::app()->session['week'] = $_POST['week'];

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
				Yii::app()->session['year'] = date("Y", strtotime('this week monday')); // oli sunday ja oli ongelma vuoden vihdessa
			if (!isset(Yii::app()->session['week']))
				Yii::app()->session['week'] = date("W", strtotime('this week monday')); // oli sunday ja oli ongelma vuoden vihdessa

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
		$haku_from 	= date("Y-m-d", strtotime(Yii::app()->session['from']));
		$haku_to 	= date("Y-m-d", strtotime(Yii::app()->session['to']));

		// <-- VAPAAT Tyontekijat
		if(isset($_GET['vapaat'])){
			$tt_all = Tyontekijat::model()->findAll("aktiivinen=1 and naytta_tyovuorossa=1");
			$tids_all = [];
			foreach($tt_all as $item)
				$tids_all[$item->id] = $item->id;

			$with			= ['data'];
			$vapaat_criteria 	= "status!=11";
			$dataAll = $this->FromToSuunnitellutAll($haku_from, $haku_to, $tids_all, $vapaat_criteria, $with);
			$pvm_tids = [];
			foreach($dataAll as $k => $data){
				$pvm_tids[$data['this_pvm']][$data['this_tid']] = $data['this_tid'];
			}

			$period = new DatePeriod(
			     new DateTime($haku_from),
			     new DateInterval('P1D'),
			     new DateTime($haku_to)
			);
			// <-- Täysin vapaa päivä
			$vapaat = [];
			foreach($tids_all as $tid){
				foreach ($period as $key => $value) {
					if(!isset($pvm_tids[$value->format('d.m.Y')][$tid]))
						$vapaat[$tid] = $tid;
				}
			}

			$janos = array_diff( $tids_all, $vapaat );
			$tid_pvm = [];
			foreach($dataAll as $k => $arr){
				if(in_array($arr['this_tid'], $janos)){
					$data = $arr['data'];
					//echo $arr['this_tid'].' '.$data->alku.' '.$data->status.'<br>';
					$tid_pvm[$arr['this_pvm']][$arr['this_tid']][] = ['alku' => $data->alku, 'loppu' => $data->loppu];
				}
			}
			ksort($tid_pvm);
			// <-- Etsitään reikoja
			$max_time 		= 3600*4; // 4h
			$tids_with_reika	= [];
			foreach($tid_pvm as $pvm => $tid_arr){
				foreach($tid_arr as $tid => $ajaat_arr){
					if(isset($tids_with_reika[$tid]))
						continue;
					$last_loppu 	= 0;
					foreach($ajaat_arr as $k2 => $aika)
					{
						$this_alku 	= strtotime($aika['alku']);
						if($last_loppu != 0 and ($this_alku-$last_loppu) > $max_time){
							$tids_with_reika[$tid]	= $tid;
							break;
						}
						//echo '.$aika['alku'].' '.$aika['loppu'].'<br>';
						$last_loppu = strtotime($aika['loppu']);
					}
				}
			}
			$result = array_merge($vapaat, $tids_with_reika);
			/*
			echo '<pre>';
			print_r($result);
			echo '<pre>';
			exit;
			*/
			Yii::app()->session['tyontekijat'] = $result;
		}
		//     VAPAAT Tyontekijat -->

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
				OR etunimi LIKE "%' . $asiakas . '%"
				OR sukunimi LIKE "%' . $asiakas . '%" 
				OR puhelin LIKE "%' . $asiakas . '%"
				OR CONCAT(etunimi , " " , sukunimi) LIKE "%'.$asiakas.'%"
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
		$_SESSION['haku_criteria_tv'] = $haku_criteria;
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
		$criteria->select = "id, $tt_order_1, $tt_order_2, tyo_toimialue, tyoryhma, kortit";
		$criteria->order = "$tt_order_1 ASC";
		$criteria->condition = "
			aktiivinen=1 and naytta_tyovuorossa=1
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

		if(!isset(Yii::app()->session['tyontekijat']))
		{
			// <-- Tyoryhmat
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			} 
			//    Tyoryhmat -->
		}

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
			$tt[$item->id] = array('etusukunimi' => $item->$tt_order_1 . ' ' . $item->$tt_order_2, "toimialue_tyoryhma" => $item->getTyoryhmaToimialueString(), "kortit" => $item->kortit);
			$haku_tids[$item->id] = $item->id;
		}
		//     Tyontekijat -->

		// Työsuhteet
		$ts = Tyosuhdet::model()->findAll(" tid IN(" . implode(",", $haku_tids) . ") ");
		$tyosuhteet = [];
		foreach ($ts as $item)
			$tyosuhteet[$item->tid] = ['vktyoaika' => $item->vktyoaika, 'loppu' => $item->loppu];
		// Pyhapaivat
		$pyhapaivat = $this->pyhapaivatAll($haku_from, $haku_to);
		/*
		echo '<pre>';
		print_r( $pyhapaivat );
		echo '</pre>';
		exit;
    */

    /** @var Freshdesk object. */
    $freshdesk = Yii::createComponent('Freshdesk');

    // Get tickets per customer (ignore resolved (4) and closed (5) tickets).
    $freshdesk_customer_tickets = $freshdesk->ticketsByCustomerId([4, 5]);

		if ($mode == 'tt') {
			$this->render('tt', array(
				'tt'		=> $tt,
				'from'		=> $haku_from,
				'to'		=> $haku_to,
				'arrDate'	=> $arrDate,
				'site'		=> $site,
				'tyosuhteet'	=> $tyosuhteet,
				'haku_tids'	=> $haku_tids,
				'pyhapaivat'	=> $pyhapaivat,
        'haku_criteria' => $haku_criteria,
        'customer_tickets' => $freshdesk_customer_tickets
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
				'tyosuhteet'	=> $tyosuhteet,
				'haku_tids'	=> $haku_tids,
				'pyhapaivat'	=> $pyhapaivat,
        'haku_criteria' => $haku_criteria,
        'customer_tickets' => $freshdesk_customer_tickets
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
		$status[10] = '<i class="tvikooni fa fa-cutlery '.(($piilota_mobiilista == 0)?'text-success':'text-danger').'" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Lounastauko').'"></i>';
		$status[2] = '<i class="tvikooni fa fa-bus '.(($piilota_mobiilista == 0)?'text-warning':'text-danger').'" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Matka').'"></i>';
		$status[3] = '<i class="tvikooni fa fa-hourglass '.(($piilota_mobiilista == 0)?'text-info':'text-danger').'" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Työ').'"></i>';
		$status[11] = '<i class="tvikooni fa fa-clock-o '.(($piilota_mobiilista == 0)?'text-info':'text-danger').'" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Vapaa päivä').'"></i>';
		return $status;
	}
	
	public function tv_arr($haku_from, $haku_to, $haku_tids, $haku_criteria, $laatikkomuoto, $with, $customer_tickets = []){

		$asetukset 				= Asetukset::model()->findByPk(1);
		$asiakas_tyovuorossa 	= ($asetukset->asiakas_tyovuorossa == 1)? true:false;
		$haku_to_ts 			= strtotime($haku_to ?? 0);
		$tv_arr 				= [];
		$laskutetut_ids			= [];

		// <-- Check Laskutetut
		$start    	= (new DateTime($haku_from));
		$end      	= (new DateTime($haku_to));
		$interval 	= DateInterval::createFromDateString('1 month');
		$period   	= new DatePeriod($start, $interval, $end);
		$kks 		= [];
		foreach ($period as $dt) {
			$kks[$dt->format("m.Y")] = 'la_'.$dt->format("m.Y").'_';
		}
		if(count($kks) > 0)
		{
			$query 			= "etunti_tunniste LIKE '".implode("%' OR etunti_tunniste LIKE '", $kks)."%'";
			$laskutetut_ids = Lasku::LaskutetutIDs('tv_id', $query);
		}
		//  Check Laskutetut -->

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
		$osv = $this->os_check_warning($tv);
		foreach($tv as $arvo){
      		$osvaroitus = (isset($osv[$arvo->id]) ? $osv[$arvo->id] : false);
			$return = $this->laatikkorakenne($arvo, $arvo->pvm, $arvo->tid, false, $laatikkomuoto, $with, $asiakas_tyovuorossa, $customer_tickets, $osvaroitus, $laskutetut_ids);
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
			foreach($haku_tids as $k => $v) {
				$tt_ret[$v] = $v;
			}
			$ids = implode(",", $tt_ret);
			$tyopaari = "tyopaari LIKE '%\"".implode("\"%' OR tyopaari LIKE '%\"", $tt_ret)."\"%' ";
			$criteria->addCondition('tid IN ('.$ids.') OR ('.$tyopaari.')');
		}
		if( is_array($haku_criteria) and count($haku_criteria) > 0 ){
			if(isset($haku_criteria['uusi_tilaus']))
				unset($haku_criteria['uusi_tilaus']);
		}
	        $criteria->addCondition($haku_criteria);
    $t = ToistuvatTyovuorot::model()->findAll($criteria);
    $osvt = $this->os_check_warning($t);
		foreach($t as $arvo){
      $ostvaroitus = (isset($osvt[$arvo->id]) ? $osvt[$arvo->id] : false);

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
				$this_week_sunday = date("YW", strtotime($date->format("d.m.Y").' last month first day'));
				//if ( $this_week_sunday >= date("YW", strtotime($haku_from)) ){ // Jotta ei saada pitkä array päivästä
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
							$return = $this->laatikkorakenne($arvo, $this_pvm, $tid, true, $laatikkomuoto, $with, $asiakas_tyovuorossa, $customer_tickets, $ostvaroitus, $laskutetut_ids);
							$tv_arr[$tid][$this_pvm][strtotime($arvo->alku)][] = $return;
						}

					}
				//}
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

	protected function laatikkorakenne($arvo, $this_pvm, $this_tid, $toistuva, $laatikkomuoto, $with, $asiakas_tyovuorossa, $customer_tickets = [], $os_warning = false, $laskutetut_ids){
		// <-- Status
		$status = $this->statukset($arvo->piilota_mobiilista);
		// Status -->

		$return 	= [];
		$tv_edit	= [];
		$this_id 	= ($toistuva)? $this->this_id_builder($arvo->id, $this_pvm, $this_tid) : $arvo->id;
		$toistuva_icon 	= ($toistuva)? '<i class="text-success fa fa-repeat" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Toistuva työ').'"></i> ' : '';
		$mennytPaivat	= (strtotime($this_pvm) < strtotime(date("Y-m-d")))? 'mennytPaivat' : '';
		$osoite 	= ( isset($arvo->osoite) and !empty($arvo->osoite))?$arvo->osoite:'';
		$ikoonit	= ((isset($status[$arvo->status]))?$status[$arvo->status]:'').$toistuva_icon;
		$tv_kesto	= 0;
		$eilasketa 	= $this->eiLasketaSubStr($arvo->tyoajanmerkinta);
		$has_tickets 	= (isset($customer_tickets[$arvo->kohteet->asiakkaat->id ?? 0]));
		if($eilasketa != true)
			$tv_kesto = strtotime($arvo->loppu)-strtotime($arvo->alku);

		if(empty($osoite) and isset($arvo->kohteet->osoite))
			$osoite = $arvo->kohteet->osoite;

		// append postal code to address if we can
		if(!empty(Yii::app()->user->kotipuhtaaksi) and !empty($osoite) 
			and isset($arvo->kohteet->pnumero)) {
			$osoite .= ' (' . $arvo->kohteet->pnumero . ')';
		}

		// <-- Return Array
		if( !$laatikkomuoto ){
			$arvo->pvm 	= $this_pvm;
			$arvo->tid 	= $this_tid;
			foreach($with as $k=>$v)
				$new_with[$v] = $v;

			$return['this_id']  	= $this_id;
			$return['kohde']  		= $arvo->kohde;
			$return['this_pvm'] 	= $this_pvm;
			$return['this_tid'] 	= $this_tid;
      		$return['toistuva'] 	= $toistuva;
			$return['has_tickets'] 	= $has_tickets;

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
		if($arvo->laskutettu == 1 and !isset($laskutetut_ids[$this_id]))
			$lisateksti .= '<br><span class="text-primary">Laskutettu</span>';
		if(isset($laskutetut_ids[$this_id]))
			$lisateksti .= '<br><span class="text-primary">Laskutettu</span>';
		if($arvo->peruutettu == 1)
			$lisateksti .= '<br><span class="text-danger">'. $this->peruutettuArray()[1] .'</span>';
		if($arvo->peruutettu == 2)
			$lisateksti .= '<br><span class="text-danger">'. $this->peruutettuArray()[2] .'</span>';
		if($arvo->peruutettu == 3)
			$lisateksti .= '<br><span class="text-danger">'. $this->peruutettuArray()[3] .'</span>';
		if($arvo->peruutettu == 4)
			$lisateksti .= '<br><span class="text-danger">'. $this->peruutettuArray()[4] .'</span>';
		if($arvo->osoiteOnline == 1)
			$lisateksti .= '<br><span class="text-danger">Onlinevaraus kesken</span>';
		if($arvo->osoiteOnline == 2)
			$lisateksti .= '<br><span class="text-success">Onlinevaraus maksettu</span>';
		if($arvo->osoiteOnline == 3)
			$lisateksti .= '<br><span class="text-warning">eDico varaus</span>';
		if($arvo->tyopaari != '')
			$ikoonit .= ' <i class="fa fa-male text-success" style="font-size:120%" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Työpari').'"></i> ';
		if(isset($arvo->avaimet) and count($arvo->avaimet) > 0)
			$ikoonit .=  ' <i class="tvikooni fa fa-key text-warning" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Avain').'"></i> ';
		if ($has_tickets)
      $ikoonit .= ' <i class="fa fa-question text-primary" style="font-size:120%" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Avoimia Tukipyyntöjä').'"></i> ';


		$asiakasNakyvissa = '';
		if( $asiakas_tyovuorossa ){
			$name = '';
			if(isset($arvo->kohteet->asiakkaat))
				$name = $arvo->kohteet->asiakkaat->Fullname;
				
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
		$return = ['tv_edit' => $tv_edit, 'tv_kesto' => $tv_kesto, 'alku' => strtotime($arvo->alku), 'loppu' => strtotime($arvo->loppu), 'peruutettu' => (int)$arvo->peruutettu, 'omasiistijavaroitus' => $os_warning];
		return $return;
	}

	public function actionDid4($from, $to, $mode = null) {
		$from 		= date("Y-m-d", strtotime($from));
		$to 		= date("Y-m-d", strtotime($to));
		$tids 		= (isset($_POST['tids']))?json_decode($_POST['tids'], true):[];
		// the speed improvement made for kotipuhtaaksi affects the calendar in "tt" (tyontekijat) mode.
		// we'll take an optional argument in this action, which can be the mode of the calendar,
		// we'll pass 'tt' mode from _form4.php and uusitilaus.php, and if the mode is infact tt here
		// we'll just reintroduce the "bug" which caused the calendar to be super slow, which was that
		// 'tids' was not passed in correctly. so we'll clear the tids array to simulate that behavior,
		// which will cause 'tt' mode calendar to behave correctly. I'm calling it a "bug" in quotes because
		// it technically did work back then too, but it slowed the calendar down SIGNIFICANTLY when a domain
		// had a lot of employees.

		// the problem was when updating anything in 'tt' mode, every other employees shifts
		// would vanish, since this function only fetched the shifts for the one employee,
		// and the javascript function which calls this action would just completely replace all content
		// on the date row.
		if($mode == "tt") {
			$tids = [];
		}
		$customer_tickets = (isset($_POST['customer_tickets']) ? json_decode($_POST['customer_tickets'], true) : []);
		$haku_criteria	= (isset($_SESSION['haku_criteria_tv']))?$_SESSION['haku_criteria_tv']:[];
		$tv_arr = $this->tv_arr($from, $to, $tids, $haku_criteria, true, [], $customer_tickets);

		// only get the diff in keys in "vko" mode and when there's
		// defined employees in the search
		if($mode == "vko" && !empty($tids)) {
			$tv_arr_keys = array_keys($tv_arr);
			// get all employee IDs that weren't in the search
			$diff = array_diff($tv_arr_keys, $tids);
			// remove all employee IDs that we're not excepting to find
			// these keys come from repeating shifts, where for example only 1 of the searched employees
			// was in a repeating shift, but it creates an array of results for the other one from those repeating shifts
			// and if the employee has some non-repeating shifts for the day of the results, they'll be overwritten in the calendar.
			foreach($diff as $diffKey) {
				unset($tv_arr[$diffKey]);
			}
		}

		echo json_encode($tv_arr);
		exit;
  }

	protected function tv_arrJava($from, $to, $haku_criteria, $haku_tids, $taulu, $customer_tickets = []){

		$hk = json_encode($haku_criteria);
		// <-- Kaikki kerrallaan
		return "
		<script type=\"text/javascript\">
		$(document).ready(function(){
			var from = '$from';
			var to = '$to';
			var tids = '".json_encode($haku_tids)."';
			var haku_criteria = $hk;
			var customer_tickets = '" . json_encode($customer_tickets) . "';
			$.ajax({
				url: location.protocol + \"//\" + location.host + \"/index.php/tyovuoroot/did4?from=\" + from + \"&to=\" + to,
				type: \"POST\",
				data: { tids : tids, haku_criteria : haku_criteria, customer_tickets: customer_tickets },
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

	public function actionTekijahovertietoja($id) {
		$model = Tyontekijat::model()->findByPk($id);
		$tyoryhmat = json_decode($model->tyoryhma, true);
		$html = '
		<div class="row">
			<div class="col-sm-12">
				<b>'.Yii::t('main', 'Nimi').': '.$this->etuSukunimi($model->id).'</b><br>
				<b>'.Yii::t('main', 'Työpuhelin').': '.$model->laiten_puh.'</b><br>
				<b>'.Yii::t('main', 'Oma puhelin').': '.$model->tekijan_puh.'</b><br>
				<b>'.Yii::t('main', 'Sähköpostiosoite').': '.$model->tekijan_email.'</b><br>
				<b>'.Yii::t('main', 'Kotiosoite').': '.$model->tekijan_katuosoite.'</b><br>
				<b>'.Yii::t('main', 'Työryhmät').': '.implode(", ", $tyoryhmat).'</b><br>
			</div>
		</div>';
		echo json_encode(["id" => $id, "html" => $html]);
		return;
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
		if(isset($tvVal->kohteet->asiakkaat))
			$name = $tvVal->kohteet->asiakkaat->Fullname;
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
		if($tvVal['peruutettu'] == 3 and isset($tv_controller)){
			$hovertietoja .= '<h3 class="text-danger">'. $this->peruutettuArray()[3] .'</h3>';
			$bgcol = 'color:red';
		}
		if($tvVal['peruutettu'] == 4 and isset($tv_controller)){
			$hovertietoja .= '<h3 class="text-danger">'. $this->peruutettuArray()[4] .'</h3>';
			$bgcol = 'color:red';
		}
		//    peruutettu -->
		$hovertietoja .= $asiakasNakyvissa;
		// OS count tag, fetched async after the hover popup is shown
		if(isset($tvVal->kohteet->id) && !empty(Yii::app()->user->kp)) {
			$hovertietoja .= '<p class="mb-0" id="hover_os_count"></p>';
			$hovertietoja .= '<input type="hidden" id="hover_property_id" value="'.$tvVal->kohteet->id.'" />';
		}

		// show square meters of the property, if we have property data available.
		$sqm = "Ei asetettu";
		if(isset($tvVal->kohteet) && isset($tvVal->kohteet->kohteen_neliot)) {
			$sqm = $tvVal->kohteet->kohteen_neliot;
		}
		$keyNumber = "";
		if(isset($tvVal->avaimet) && count($tvVal->avaimet) > 0 && isset($tvVal->kohteet)) {
			foreach($tvVal->avaimet as $key) {
				// show only those keys that are for the shifts property
				if($key->kohde === $tvVal->kohteet->id) {
					$keyNumber .= "Avainnumero: "  . $key->avainnumero . "<br>";
				}
			}
			// remove trailing <br> tag
			$keyNumber = substr($keyNumber, 0, -4);
		}
		
		//if(!empty($asiakasNakyvissa)){ $title .= ', '; }
		$hovertietoja .= $paikkakuntaNakyvissa;
		$hovertietoja .= '<br><p class="mb-0"><span class="didstatus">'.$status.$toistuva.$avaimet.'</span>&nbsp; &nbsp;<b>'.$tvVal->alku.'-'.$tvVal->loppu.'</b>: '.$osoite.
			' <br><span>Kohteen neliöt: '.$sqm.'</span><br><span>'.$keyNumber.'</span></p>';

		if( $tvVal->tyopaari != '' and $tvVal->tyopaari != "[\"$tvVal->tid\"]" ){
		$hovertietoja .= '<div class="hover_well"><h5>Työparit</h5>';
		   foreach(json_decode($tvVal->tyopaari, true) as $tyopaari){
			// problem with this: the actual coworkers
			// in the calendar will also hide this, which means
			// it'll hide the actual coworker in some cases.
			//if( $tvVal->tid != $tyopaari )
			$hovertietoja .=  $this->etuSukunimi($tyopaari).'<br>';
		   }
		$hovertietoja .= '</div>';
		}

		// include notes "muistiinpanot" in the hover data
		$notes = json_decode($tvVal->muistiinpano, true);
		if(is_array($notes)) {
			$hovertietoja .= '<div class="hover_well"><h5>Muistiinpanot:</h5>';
			foreach($notes as $k => $note) {
				$hovertietoja .= "<p>$note</p>";
			}
		}
		
		$hovertietoja .= "</div>";

		if( !empty($tvVal->tietoja) ){ $hovertietoja .= '<div class="hover_well"><h5>Tietoja mobiilisovellukseen:</h5> '.str_replace("\n", "<br>", $tvVal->tietoja).'</div>'; }
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
			ksort($tids_origin);
		}

		if($toistuva and !isset($model->id)){
			echo json_encode(['error' => 'Toistuva error']);
			exit;
		}

		// <-- Tids
		$tids_before = [];
		if(isset($model->id)){
			$tids_before[$model->tid] = $model->tid;
			if(isset($model->tyopaari) and is_array(json_decode($model->tyopaari, true))){
				foreach(json_decode($model->tyopaari, true) as $tp_tid_before){
					$tids_before[$tp_tid_before] = $tp_tid_before;
				}
			}
		}

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
		$poistettu_pvms 	= [];
		$poistettu_pvms_upd	= [];
		$ero_plus 		= array_diff( $_POST['post_tids'], $tids_before );
		$ero_miinus 		= array_diff( $tids_before, $_POST['post_tids'] );
		$clearing_before 	= []; // Otetaan pois jos on samanlainen

		if( $this_id != 'null' and count($tids) > 0 and !empty($model->new_poistettu_pvm) ){

			foreach (json_decode($model->new_poistettu_pvm, true) as $key => $value){
			  if(!in_array($value, $clearing_before))
			    $clearing_before[] = $value;
			}

			foreach($clearing_before as $key => $val){
				if( 
					isset($val['tid']) and isset($tids[$val['tid']]) and isset($val['pvm']) and isset($val['syy']) 
					// and date("Ymd", strtotime($val['pvm'])) >= date("Ymd", strtotime($laatikko_pvm)) tule tuplana edelisen viikolle
				){
					$poistettu_pvms[$val['tid']][$val['pvm']] = $val['syy'];
					$poistettu_pvms_upd[] = ['tid' => $val['tid'], 'pvm' => $val['pvm'], 'syy' => $val['syy']];
				}
				foreach($ero_miinus as $miinus_tid){
					foreach($ero_plus as $plus_tid){
						if( 
							isset($val['tid']) and isset($val['pvm']) 
							and date("Ymd", strtotime($val['pvm'])) >= date("Ymd", strtotime($laatikko_pvm)) 
							and isset($val['syy']) )
						{
							$poistettu_pvms[$plus_tid][$val['pvm']] = $val['syy'];
							$poistettu_pvms_upd[] = ['tid' => $plus_tid, 'pvm' => $val['pvm'], 'syy' => $val['syy']];
						}
					}
				}
			}
		}
		if(isset($model->tyopaari) and is_array(json_decode($model->tyopaari, true)) and isset($_POST['post_tids'])){
			$addtp = array_diff( $_POST['post_tids'], json_decode($model->tyopaari, true) );
			foreach($addtp as $k => $ntid){
				foreach($poistettu_pvms as $ptid => $parr){
					foreach($parr as $ppvm => $syy){
						if(date("Ymd", strtotime($ppvm)) >= date("Ymd", strtotime($laatikko_pvm)))
						{
							$poistettu_pvms[$ntid][$ppvm] = $syy;
							$poistettu_pvms_upd[] = ['tid' => $ntid, 'pvm' => $ppvm, 'syy' => $syy];
						}
					}
				}
			}
		}
		if(isset($model->tyopaari) and empty($model->tyopaari) and isset($_POST['tyopaari_laatikko']) and count($_POST['tyopaari_laatikko']) > 0){
			foreach($_POST['tyopaari_laatikko'] as $k => $ntid){
				foreach($poistettu_pvms as $ptid => $parr){
					foreach($parr as $ppvm => $syy){
						if(date("Ymd", strtotime($ppvm)) >= date("Ymd", strtotime($laatikko_pvm)))
						{
							$poistettu_pvms[$ntid][$ppvm] = $syy;
							$poistettu_pvms_upd[] = ['tid' => $ntid, 'pvm' => $ppvm, 'syy' => $syy];
						}
					}
				}
			}
		}

		$clearing = []; // Otetaan pois jos on samanlainen
		foreach ($poistettu_pvms_upd as $key => $value){
		  if(!in_array($value, $clearing))
		    $clearing[] = $value;
		}

		$for_update = (isset($model->new_poistettu_pvm))? $model->new_poistettu_pvm : '' ;
		if(count($poistettu_pvms_upd) > 0)
			$for_update = json_encode($poistettu_pvms_upd);
		else
			if(count($clearing_before) > 0)
				$for_update = json_encode($clearing_before);

		// <-- Update poistetut
		//$return .= json_encode($ero_plus).'<br>';
		//$return .= json_encode($ero_miinus).'<br>';
		//$return .= json_encode($clearing);
		if(isset($model->id))
			ToistuvatTyovuorot::model()->updatebypk($model->id, ['new_poistettu_pvm' => $for_update]);


		$date = new \DateTime($startday, new DateTimeZone('Europe/Helsinki'));
		$date->modify('this week monday');
		$date_end = (new \DateTime($stopday, new DateTimeZone('Europe/Helsinki')))->getTimestamp();
		$pvms = [];
		while ($date->getTimestamp() <= $date_end){
			//$this_week_sunday = date("YW", strtotime($date->format("d.m.Y").' this week sunday'));
			//if ( $this_week_sunday >= date("YW", strtotime($cal_start.' this week sunday')) ){ // Tama pitaa testata
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
			//}
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

		if(isset($post))
		{
			$PushNotify = (isset($post['PushNotify']) and $post['PushNotify'] == 'on')? true : false;
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
				if($PushNotify and count(json_decode($model->tyopaari, true)) == 0)
					$this->pushNotifySending($this_id);
				// PushNotify -->

				// <-- jos on tyopaari
				if(!$toistuva and count(json_decode($model->tyopaari, true)) > 1)
					$this->tyopari_luonti($model, $post);
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

        	$list = array(0=>'Kyllä',1=>'Ei');
		$return .= '<div class="row"><div class="col-sm-12">
		'.$form->labelEx($model,'piilota_mobiilista').'
		'.$form->dropDownList($model,'piilota_mobiilista', $list, 
		array("class"=>"form-control", "id" => "piilota_mobiilista" )).'
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

    // Omasiistijävaroitus
    if (!empty(Yii::app()->user->kp)) {
      $osv = $this->os_check_warning($model);
      $omasiistijavaroitus = !empty($osv[$model->id]);
    } else {
      $omasiistijavaroitus = false;
    }

       		$criteria = new CDbCriteria();
		$criteria->select = "id, $tt_order_1, $tt_order_2";
		$criteria->order = "$tt_order_1 ASC";
		$criteria->condition = "aktiivinen=1";
	  	$t = Tyontekijat::model()->findAll($criteria);
		$tekijan_nimi = '<select id="tekijanVaihdo" class="form-control">';
		if($tid == 0)
			$tekijan_nimi .= '<option value="0" selected>VARAUS</option>';
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
				'.Yii::t('main', 'Työvuoron suunnittelu').' '.(($toistuva)?'ketju: ':'').' #'.$model->id.' '.$tekijan_nimi.'
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
          'omasiistijavaroitus' => $omasiistijavaroitus,
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
		
		// kp only
		if(!empty(Yii::app()->user->kp)) {
			// add "peruutettu laskutettava" product state matches
			if($post["peruutettu"] == 2) {
				// KP only, hardcoded product for "peruutettu laskutettava"
				$canceledProduct = [
					"tuote" => ["113"],
					"maara" => ["1"]
				];
				if(isset($post["lisa_tuotteet"])) {
					// if lisa_tuotteet already exists, just add canceled product to the data structure
					$post["lisa_tuotteet"]["tuote"][] = $canceledProduct["tuote"][0];
					$post["lisa_tuotteet"]["maara"][] = $canceledProduct["maara"][0];
				} else {
					$post["lisa_tuotteet"] = $canceledProduct;
				}
			} 
			// if for some reason we're removing "peruutettu laskutettava"
			// automatically remove those products from extra products.
			else {
				if(isset($post["lisa_tuotteet"])) {
					foreach($post["lisa_tuotteet"]["tuote"] as $arrKey => $productId) {
						if($productId == 113) {
							unset($post["lisa_tuotteet"]["tuote"][$arrKey]);
							unset($post["lisa_tuotteet"]["maara"][$arrKey]);
						}
					}
					// re-index lisa_tuotteet tuote and maara just in case
					$post["lisa_tuotteet"]["tuote"] = array_values($post["lisa_tuotteet"]["tuote"]);
					$post["lisa_tuotteet"]["maara"] = array_values($post["lisa_tuotteet"]["maara"]);
				}
			}

			if($post["peruutettu"] == 3) {
				// 30 = Leasing Perus-paketti 9.90€
				$canceledProduct = [
					"tuote" => ["30"],
					"maara" => ["1"]
				];
				if(isset($post["lisa_tuotteet"])) {
					// if lisa_tuotteet already exists, just add canceled product to the data structure
					$post["lisa_tuotteet"]["tuote"][] = $canceledProduct["tuote"][0];
					$post["lisa_tuotteet"]["maara"][] = $canceledProduct["maara"][0];
				} else {
					$post["lisa_tuotteet"] = $canceledProduct;
				}
			} else {
				if(isset($post["lisa_tuotteet"])) {
					foreach($post["lisa_tuotteet"]["tuote"] as $arrKey => $productId) {
						if($productId == 30) {
							unset($post["lisa_tuotteet"]["tuote"][$arrKey]);
							unset($post["lisa_tuotteet"]["maara"][$arrKey]);
						}
					}
					// re-index lisa_tuotteet tuote and maara just in case
					$post["lisa_tuotteet"]["tuote"] = array_values($post["lisa_tuotteet"]["tuote"]);
					$post["lisa_tuotteet"]["maara"] = array_values($post["lisa_tuotteet"]["maara"]);
				}
			}

			if($post["peruutettu"] == 4) {
				// 105 = Leasing Puhdas-paketti 19.90€
				$canceledProduct = [
					"tuote" => ["105"],
					"maara" => ["1"]
				];
				if(isset($post["lisa_tuotteet"])) {
					// if lisa_tuotteet already exists, just add canceled product to the data structure
					$post["lisa_tuotteet"]["tuote"][] = $canceledProduct["tuote"][0];
					$post["lisa_tuotteet"]["maara"][] = $canceledProduct["maara"][0];
				} else {
					$post["lisa_tuotteet"] = $canceledProduct;
				}
			} else {
				if(isset($post["lisa_tuotteet"])) {
					foreach($post["lisa_tuotteet"]["tuote"] as $arrKey => $productId) {
						if($productId == 105) {
							unset($post["lisa_tuotteet"]["tuote"][$arrKey]);
							unset($post["lisa_tuotteet"]["maara"][$arrKey]);
						}
					}
					// re-index lisa_tuotteet tuote and maara just in case
					$post["lisa_tuotteet"]["tuote"] = array_values($post["lisa_tuotteet"]["tuote"]);
					$post["lisa_tuotteet"]["maara"] = array_values($post["lisa_tuotteet"]["maara"]);
				}
			}

		}
		

		$PushNotify = (isset($post['PushNotify']) and $post['PushNotify'] == 'on')? true : false;

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
				if($PushNotify)
					$this->pushNotifySending($this_id);
				// PushNotify -->

				$return = ['return' => 'uusi_ketju_ok', 'id' => $this_id];
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
				if($PushNotify and count(json_decode($model->tyopaari, true)) == 0)
					$this->pushNotifySending($this_id);
				// PushNotify -->

				// <-- Poisto PVM/Henkilo ketjusta
				$u		= Yii::app()->user->nimi;
				$d		= date("d.m.Y");
				$poisto_syy	= ['text'=>'ByUpdateChangeToYksittyinen', 'user'=>$u, 'date'=>$d];
				if(count(json_decode($model->tyopaari, true)) > 1)
					foreach(json_decode($model->tyopaari, true) as $tp_tid )
						$this->toistuvaDeletePvm($edellinen_model['id'], $laatikko_pvm, $tp_tid, $poisto_syy);

				$this->toistuvaDeletePvm($edellinen_model['id'], $laatikko_pvm, $laatikko_tid, $poisto_syy);
				// <-- Kun ketjussa oli työparia ja nyt ei yhtään (Asennettu 14.08.2020)
				if(count($edelliset_tyoparit) > 0) // and empty($model->tyopaari) / otettu pois 05.03.2021
				{
					foreach($edelliset_tyoparit as $tptid)
						$this->toistuvaDeletePvm($edellinen_model['id'], $laatikko_pvm, $tptid, $poisto_syy);
				}
				//     Poisto PVM/Henkilo ketjusta -->

				// <-- jos on tyopaari
				if(count(json_decode($model->tyopaari, true)) > 1)
					$this->tyopari_luonti($model, $post);
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
					if( strtotime($val['pvm']) < strtotime($post['pfrom']) ){ // Oli ongelma MArtan kanssa - and isset($edelliset_tyoparit_updater[$val['tid']])
						$poistettu_pvms_fororigin[] = $val;
					}
				}
			}

			$model->new_poistettu_pvm = (count($poistettu_pvms_fororigin) > 0)?json_encode($poistettu_pvms_fororigin):'';
			//     Poistetut päivät siirto, JOS vaihdettu henkilö -->

			if($model->save()){

				// <-- PushNotify
				$this_id = $this->this_id_builder($model->id, $laatikko_pvm, $laatikko_tid);
				if($PushNotify)
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

					$return = ['return' => 'pfrom_muutos_ok', 'id' => $this_id];
					echo json_encode($return);
				}
			}
			exit;
		}
		//     Toistuva Alkamispaiva siirto -->

		$model->attributes 	= $post;
		$this->model_json_converter($post, $model, $toistuva);
		$updated_tp = json_decode($model->tyopaari, true);
		if($toistuva)
			$model->new_poistettu_pvm = $this->poistetutClearning($model);

		if($model->save()){

			// <-- PushNotify
			$this_id = ($toistuva)? $this->this_id_builder($model->id, $laatikko_pvm, $laatikko_tid) : $model->id;
			if($PushNotify)
				$this->pushNotifySending($this_id);
			// PushNotify -->

			// <-- UPDATE LOG
			$model_log 	= ( $toistuva )? 'ToistuvatTyovuorot' : 'Tyovuoroot' ;
			$name_log	= ( $toistuva )? 'Toistuvat työvuorot' : 'Työvuorot' ;
			$this->tvUpdateLog($edellinen_model, $model->attributes, $model_log, $name_log); // old, new, model name, model nimike

			// <-- TV tyopaari
			if( !$toistuva and !isset($edelliset_tyoparit[0]) ){ // jos $edelliset_tyoparit[0] on niin ongelma
				$site = Yii::app()->createController('Site');
				// <-- Lisataan tyoparia silloin kun ei ollut yhtaan
				if( count($edelliset_tyoparit) == 0 and count($post_tyopaari) > 0 )
					$this->tyopari_luonti($model, $post);
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
							$tvmodel = Tyovuoroot::model()->findByPk($tv_id);
							// <-- TV poisto
							if(isset($tvmodel->id)){
								$tvmodel->deleteByPk($tvmodel->id);
								$this->tvDeleteLog($tvmodel);
							}
							continue;
						} else {
							$luotu[$tv_id] = $tid;
						}
					}
					foreach($arr as $tid){
						$arr = $this->add_TV_tid($model, $tid, $site, []); // post - ei anna sopiva
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
				if( count($edelliset_tyoparit) > 0 and count($post_tyopaari) == 0 )
				{
					Tyovuoroot::model()->updatebypk($model->id, array('tyopaari' => ''));
					foreach($edelliset_tyoparit as $tv_id => $tid){
						$tvmodel = Tyovuoroot::model()->findByPk($tv_id);
						// <-- TV poisto
						if(isset($tvmodel->id) and $tvmodel->id != $model->id){
							$tvmodel->deleteByPk($tvmodel->id);
							$this->tvDeleteLog($tvmodel);
						}
					}
				}
			}
			//     TV tyopaari -->

			$return = ['return' => 'muokattu', 'id' => $this_id];
			echo json_encode($return);

		} else { // model save 
			echo json_encode($model->getErrors());
		}
		exit;
	}

	protected function poistetutClearning($model)
	{
		$clearing = [];
		if(!empty($model->new_poistettu_pvm) ){
			foreach (json_decode($model->new_poistettu_pvm, true) as $key => $value){
			  if(!in_array($value, $clearing))
			    $clearing[] = $value;
			}
		}
		if(count($clearing) > 0)
			$return = json_encode($clearing);
		else
			$return = '';

		return $return;
	}

	protected function tvUpdateLog($old_model, $new_model, $model_log, $name_log)
	{
		// <-- LOG
		$status_log 	= 'Update';
		$old_values = json_encode($old_model);
		$new_values = json_encode($new_model);
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
		//     LOG -->
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

		// <-- Normaali updater, eli kun array keyissa löydy TV id ( {"22":"10","21":"11"} )
		if(!isset($edelliset_tyoparit[0])){
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
		}

		return true;
	}

	protected function tyopari_luonti($current_model, $post)
	{
		$site = Yii::app()->createController('Site');
		$tids 		= json_decode($current_model->tyopaari, true);
		$luotu 		= [];
		foreach($tids as $tid){
			if( $current_model->tid == $tid ){
				$luotu[$current_model->id] = $current_model->tid;
				continue;
			}
			$arr = $this->add_TV_tid($current_model, $tid, $site, $post);
			if(isset($arr['id']))
				$luotu[$arr['id']] = $arr['tid'];
		}
		foreach($luotu as $k => $v)
			Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));

		return true;
	}

	protected function add_TV_tid($current_model, $tid, $site, $post)
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

		if( is_array($model->url_linkkit) and count($model->url_linkkit) > 0 )
			$model->url_linkkit = json_encode($model->url_linkkit, JSON_FORCE_OBJECT);
		else
			$model->url_linkkit = '';

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

		// <-- Tids
		$tids = [];
		$tids[$tid] = $tid;
		if(isset($model->tyopaari) and is_array(json_decode($model->tyopaari, true))){
			foreach(json_decode($model->tyopaari, true) as $tp_tid){
				$tids[$tp_tid] = $tp_tid;
			}
		}

		if($toistuva){
			$nextTv = $this->checkNextTv($this_id);
			if(!empty($nextTv))
				$pvm = $nextTv;
		}

		$osoite = $model->osoite;
		if(empty($osoite) and isset($model->kohteet->osoite))
			$osoite = $model->kohteet->osoite;

		foreach($tids as $tid){
			$t = Tyontekijat::model()->findbypk($tid);
			$pushviesti = "Työvuorosi on muuttunut. Alta löydät uudet tiedot:\n
				".$pvm."
				".$model->alku."-".$model->loppu." ".$osoite."
				".$model->tietoja;

			Domainit::sendGCM($tid,"Hei ".$t->tekijan_nimi,$pushviesti, null);
		}
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
		if(is_array($model->url_linkkit) and count($model->url_linkkit) > 0) {
			$model->url_linkkit = json_encode($model->url_linkkit);
		} else {
			$model->url_linkkit = "";
		}

		$model->kohde = $kohteet->id;
		$model->osoite = $kohteet->osoite;
		$model->postinumero = $kohteet->pnumero;
		$model->postitoimipaikka = $kohteet->toimipaikka;
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
				$m->osoite = $kohteet->osoite;
				$m->postinumero = $kohteet->pnumero;
				$m->postitoimipaikka = $kohteet->toimipaikka;
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
					Asiakas: '.$asiakkaat->Etusukunimi.'<br>
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
		// split netvisor data if necessary
		if(isset($asiakkaat->netvisor_dimension_name)) {
			$dimensions = explode("//", $asiakkaat->netvisor_dimension_name);
			if(isset($dimensions[0]) && isset($dimensions[1])) {
				$asiakkaat->netvisor_dimension_name = $dimensions[0];
				$asiakkaat->netvisor_dimension_item = $dimensions[1];
			}
			
		}
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
			if(isset($_POST["Kohteet"])) {
				$kohteet->attributes = $_POST["Kohteet"];
			}
			
			$kohteet->tyoryhma = $asiakkaat->tyoryhma;
			$kohteet->asiakas_id = $asiakkaat->id;
			$kohteet->uusi_tilaus = 1;
			$kohteet->aktiivinen = 1;

			if(isset($asiakkaat->id))
				$kohteet->etu_suku_nimet = $asiakkaat->Fullname;

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

				if(is_array($model->url_linkkit) and count($model->url_linkkit) > 0) {
					$model->url_linkkit = json_encode($model->url_linkkit);
				} else {
					$model->url_linkkit = "";
				}

				$model->kohde = $kohteet->id;
				$model->osoite = $kohteet->osoite;
				$model->postinumero = $kohteet->pnumero;
				$model->postitoimipaikka = $kohteet->toimipaikka;
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
				$m->url_linkkit = $model->url_linkkit;
				$m->kohde = $kohteet->id;
				$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				$m->tid=$tid;
				$m->status=3;
				$m->osoite = $kohteet->osoite;
				$m->postinumero = $kohteet->pnumero;
				$m->postitoimipaikka = $kohteet->toimipaikka;
				if($m->save())
				{
					$luotu[$m->id] = $m->tid;
					$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));

				} else {
					echo "Tyovuorot uusi asiakas error";
					echo json_encode($m->getErrors());
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
					Asiakas: '.$asiakkaat->Etusukunimi.'<br>
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
		$haku_from = date("Y-m-d", strtotime(Yii::app()->session['from']));
		$haku_to = date("Y-m-d", strtotime(Yii::app()->session['to']));
		$this->renderPartial('uusitilaus',array(
			'model'=>$model,
			'haku_from' => $haku_from,
			'haku_to' => $haku_to
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

	public function actionTv_kohteet()
	{
		$this->poistaminenOnlineVarauksetJokaMeniOhi();
		$this->render('tv_kohteet');
	}

	protected function poistaminenOnlineVarauksetJokaMeniOhi($interval)
	{
		// <-- Poistaminen
		$criteria=new CDbCriteria;
		$criteria->order= " id DESC "; 
		$criteria->condition= " 
			(time + INTERVAL $interval MINUTE) < NOW()
			AND osoiteOnline=1
		";
		$tv = Tyovuoroot::model()->findAll($criteria);
		foreach($tv as $item){
			$tv = Tyovuoroot::model()->findbypk($item->id);
			if(isset($tv->id)){
				// <-- LOG
				$model_log 	= 'Tyovuoroot';
				$name_log 	= 'Työvuorot';
				$status_log 	= 'Onlinevaraus Autoremove';
				$old_values = json_encode($tv->attributes);
				$new_values = null;
				$site = Yii::app()->createController('Site');
				$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
				$this->loadModel($tv->id)->delete();
			}
		}
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
		$criteria->order =" yrityksen_nimi!='' DESC,etunimi!='' DESC";

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
			AND (yrityksen_nimi LIKE '%".$key."%' OR CONCAT(etunimi,' ',sukunimi) LIKE '%".$key."%' OR osoite LIKE '%".$key."%' )	
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
				$nm = array($a->Fullname, $a->id);
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
		// if you're gonna add more options make sure to not overwrite KP only 3 and 4
		$list = array(
		1 => Yii::t('main', 'Peruutettu'), 
		2 => Yii::t('main', 'Peruutettu laskutettava')
		);
		if(!empty(Yii::app()->user->kotipuhtaaksi)) {
			$list[3] = Yii::t("main", "Peruutettu, laskutetaan välineet 9,90€");
			$list[4] = Yii::t("main", "Peruutettu, laskutetaan välineet 19,90€");
		}
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
					yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR etunimi LIKE '%".$_GET['yrityksen_nimi']."%' OR sukunimi LIKE '%".$_GET['yrityksen_nimi']."%'
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
							if(isset($v2['data']))
								$sort[strtotime($v2['this_pvm'].' '.$v2['data']['alku'])][$tid][] = $v1;
							else
								$sort[strtotime($v2['this_pvm'])][$tid][] = $v1;

		ksort($sort);

		foreach($sort as $k => $v)
			foreach($v as $k1 => $v1)
				foreach($v1 as $k2 => $v2)
					foreach($v2 as $k3 => $v3)
						$data[] = $v3;

		$clearing = []; // Otetaan pois jos on samanlainen
		foreach ($data as $key => $value){
		  if(!in_array($value, $clearing))
		    $clearing[] = $value;
		}

		/*
		echo '<pre>';
		print_r( $data );
		echo '</pre>';
		exit;
		*/
	
		return $clearing;
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

  /**
   * Mass edit function for shifts, which is used via AJAX with POST data.
   *
   * Possible POST parameters:
   *
   * - ids (array of ints) (REQUIRED):
   *     List of shift (työvuoro) IDs to operate on.
   * - actions (array of strings) (REQUIRED):
   *     Action to perform on list of shifts. Possible values (for now):
   *       - cancel - Mass cancellation (peruutus)
   *           requires parameters: cancel_type
   * - cancel_type (int):
   *     Cancellation type for action 'cancel'. Possible values:
   *       1: Peruutettu, 2: Peruutettu laskutettava
   */
  public function actionMassedit()
  {
    // Check variables and do all validation first. If anything is wrong even in
    // one action, later in the list of actions, cancel all operations. Do
    // changes only if everything is well.
    $errors = [];

    // Require list of ID(s).
    if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
      $errors[] = 'Massamuokkaus vaatii listan työvuoroista (ID) joille toiminto suoritetaan.';
    } else {
      $ids = $_POST['ids'];

      // Validate ID(s) (numeric).
      foreach ($ids as $id) {
        if (!is_numeric($id)) {
          $errors[] = 'Yksi tai useampi massamuokkaukselle annettu työvuoron ID on virheellinen.';
          break;
        }
      }
    }

    // Require list of action(s).
    if (!isset($_POST['actions']) || !is_array($_POST['actions'])) {
      $errors[] = 'Yksi tai useampi massamuokkaukselle annettu toiminto on virheellinen.';
    } else {
      $actions = $_POST['actions'];

      // Validate list of action(s).
      $validated_actions = []; // temp list to avoid duplicate checks and duplicate final actions.
                               // this should be used when looping and performing actions instead.
      foreach ($actions as $action) {

        // Avoid duplicate actions.
        if (in_array($action, $validated_actions)) {
          continue;
        }

        // Check that value is a string.
        if (!is_string($action)) {
          $errors[] = 'Yksi tai useampi massamuokkaukselle annettu toiminto on virheellinen.';
          break;
        }

        // Do action-specific validation.
        switch ($action) {
          case 'cancel':

            // Require cancel_type parameter.
            if (!isset($_POST['cancel_type'])) {
              $errors[] = 'Peruuttaminen (cancel) vaatii peruuttamistyypin valinnan (cancel_type).';
              break;
            } else {
              $cancel_type = $_POST['cancel_type'];
            }

            // Validate cancel_type parameter.
            if (!is_numeric($cancel_type) || !in_array($cancel_type, [0, 1, 2])) {
              $errors[] = 'Peruuttamistyypin valinta on viallinen. Sallitut arvot: 1 (peruutettu), 2 (peruutettu laskutettava).';
              break;
            }

            break;

            // Nothing to do with delete operation; allow it.
          case 'delete':
            break;

            // If action was not handled, choice is invalid; return error.
          default:
            $errors[] = "Virhe: Massamuokkaukselle annettu toiminto '$action' on virheellinen/ei tuettu.";
            break;
        }

        $validated_actions[] = $action;
      }
    }

    // Return if any validation errors occured.
    if (!empty($errors)) {
      echo json_encode(['errors' => $errors]);
      return;
    }

    // All is good; perform actions.
    $result = '';
    foreach ($validated_actions as $action) {
      switch ($action) {
        case 'cancel':
          foreach ($ids as $id) {

            // Get info on the shift, whether virtual or not.
            $tvinfo   = $this->this_id($id);
            $model    = $tvinfo['model'];
            $toistuva = $tvinfo['toistuva'];
            $pvm      = $tvinfo['pvm'];
            $tid      = $tvinfo['tid'];

            // Operate differently based on whether this is virtual shift or not.
            if ($toistuva) {
              $tilanne = ['peruutettu' => $cancel_type];
              $this->VirtualtoTV($model->id, $tid, $pvm, $tilanne, 'CancelByMassEdit');
            } else {
              $model->peruutettu = $cancel_type;
              $model->save();
            }

            // TODO?: Remember $this->pushNotifySending(id) : notify cleaner about change
          }

          switch ($cancel_type) {
            case 1: $result = 'Valitut vuorot merkitty peruutetuiksi.'; break;
            case 2: $result = 'Valituille vuoroille merkitty Peruutettu Laskutettava.'; break;
			case 3: $result = "Valituille vuoroille merkitty Peruutettu, laskutetaan välineet 9,90€"; break;
			case 4: $result = "Valituille vuoroille merkitty Peruutettu, laskutetaan välineet 19,90€"; break;
            default: $result = 'Valittujen vuorojen peruutus poistettu.'; break;
          }

          break;

        case 'delete':
          $deleted_count = 0;
          foreach ($ids as $id) {

            // Get info on the shift, whether virtual or not.
            $tvinfo   = $this->this_id($id);
            $model    = $tvinfo['model'];
            $toistuva = $tvinfo['toistuva'];
            $pvm      = $tvinfo['pvm'];
            $tid      = $tvinfo['tid'];

            // Operate differently based on whether this is virtual shift or not.
            if ($toistuva) {
              if (!$this->toistuvaDeletePvm($model->id, $pvm, $tid, 'MassapoistoAsiakkaanTyovuorolistalta')) {
                $errors[] = sprintf('Työvuoroa ID %d ei voitu poistaa toistuvasta ketjusta.', $model->id);
              } else {
                $deleted_count++;
              }
            } else {
              if (!$model->delete()) {
                $errors[] = sprintf('Työvuoroa ID %d ei voitu poistaa.', $model->id);
              } else {
                $deleted_count++;
              }
            }

            // TODO?: Remember $this->pushNotifySending(id) : notify cleaner about change
          }

          $result = sprintf('%d työvuoroa poistettu onnistuneesti.', $deleted_count);
          break;
      }
    }

    if (!empty($errors)) {
      echo json_encode(['result' => $result, 'errors' => $errors]);
    } else {
      echo json_encode(['result' => $result]);
    }
  }

  /**
   * Gets a domain (and server) specific key for caching.
   */
  protected function cachekey(string $fmt, ...$args) :string
  {                                                                                                                                                                                                                                                            
    static $prefix;

    // Format prefix on first request.
    if (empty($prefix)) {
      $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
      $domain = Yii::app()->user->domain;
      $prefix = sprintf("%s_%s_", $host, $domain);
    }

    // Format key if $args provided.
    if (!empty($args)) {
      array_unshift($args, $fmt);
      $key = call_user_func_array('sprintf', $args);
    } else {
      $key = $fmt;
    }

    // Return with generated key.
    return sprintf("%s_%s", $prefix, $key);
  }

  #region Omasiistijät
  /* ((( Omasiistijät */

  /**
   * Get list of workers that have approved shifts/cycles in a target location,
   * and echoes the list as JSON.
   * Calls TyovuorootController::os_full_data() with relevant data.
   *
   * @param int $location_id
   * ID of the target location.
   *
   * @return null
   * Outputs results as a JSON array of IDs.
   */
  public function actionOmasiistijat_lista($location_id = null)
  {
    if (is_numeric($_POST['location_id'] ?? ''))
      $location_id = (int)$_POST['location_id'];
    $os = $this->os_full_data();
    echo json_encode(isset($os[$location_id]) ? $os[$location_id] : []);
  }

  /**
   * Notifies customer about omasiistijät (for lack of an english word).
   *
   * FOR AJAX.
   *
   * @param int $customer_id
   * ID of the customer. Email (or phone number) is fetched from here.
   * @param array $names
   * Names of workers going for the shift.
   * @return bool
   * Echoed result, and true if mail was sent, or false if error occured.
   * JSON result set format: [
   *   'success': true/false
   *   'message': success message or error message
   * ]
   */
  public function actionOmasiistijat_ilmoitus($customer_id = null, $names = null)
  {
    // If not kp or testing, cancel action.
    if (empty(Yii::app()->user->kp)) {
      echo json_encode([
        'success' => false,
        'message' => 'Tämä ominaisuus ei ole käytössä ympäristössäsi.'
      ]);
      return false;
    }

    // Get email text and verify it's not empty.
    $asetukset = Asetukset::model()->findByPk(1);
    $email_subject = $asetukset->omasiistijat_email_subject ?? '';
    $email_body = $asetukset->omasiistijat_email_body ?? '';

    if (empty($email_subject)) {
      echo json_encode([
        'success' => false,
        'message' => 'Asetuksissa määritettävä omasiistijäilmoituksen otsikon teksti puuttuu.'
      ]);
      return false;
    } elseif (empty($email_body)) {
      echo json_encode([
        'success' => false,
        'message' => 'Asetuksissa määritettävä omasiistijäilmoituksen teksti puuttuu.'
      ]);
      return false;
    }

    // Get possible POST value for customer ID.
    if (is_numeric($_POST['customer_id'] ?? '')) {
      $customer_id = $_POST['customer_id'];
    }

    // Get possible POST value for list of JSON encoded worker names.
    if (isset($_POST['names'])) {
      $names_temp = json_decode($_POST['names'], true); // decode to temp var for checking
      if (is_array($names_temp)) {
        $names = array_values($names_temp);
      }
    }

    // Check for empty or invalid customer id.
    if (empty($customer_id) || !is_numeric($customer_id)) {
      echo json_encode([
        'success' => false,
        'message' => sprintf('Viallinen asiakas ID "%s".', json_encode($customer_id))
      ]);
      return false;
    }

    // Check for non-existent customer.
    if (empty($asiakas = Asiakkaat::model()->findByPk($customer_id))) {
      echo json_encode([
        'success' => false,
        'message' => sprintf('Asiakasta ID "%d" ei löydetty.', $customer_id)
      ]);
      return false;
    }

    // Check that the customer has an email specified. (TODO: validate?)
    if (empty($client_email = trim($asiakas->sahkoposti ?? ''))) {
      echo json_encode([
        'success' => false,
        'message' => sprintf('Asiakkaan ID %d sähköposti ei ole määritelty tai on viallinen.', $customer_id)
      ]);
      return false;
    }

    // Ensure that a list of names of workers is provided.
    if (!is_array($names)) {
      echo json_encode([
        'success' => false,
        'message' => 'Sisäinen virhe: Siistijöiden listan vastaanottaminen epäonnistui. Jos vika jatkuu, ilmoita asiasta ylläpidolle.'
      ]);
      return false;
    }

    // Check that the list is actually populated, to avoid logic errors.
    if (empty($names)) {
      echo json_encode([
        'success' => false,
        'message' => 'Sisäinen virhe: Vastaanotettu siistijöiden lista on tyhjä. Tämä voi johtua yhteydestä. Jos vika jatkuu, ilmoita asiasta ylläpidolle.'
      ]);
      return false;
    }

    // All checks done, ready to form message and mail. Initial mail base, from Henri.
    $namestr = '';
    $namecount = count($names);

    // Loop names list (could be 1 or 3, usually 2) and form cohesive wording.
    for ($i = 0; $i < $namecount; $i++) {
      switch (true) {
        case ($i == 0):
          $namestr .= $names[$i];
          break;
        case ($i == $namecount - 1):
          $namestr .= " ja {$names[$i]}";
          break;
        default:
          $namestr .= ", {$names[$i]}";
          break;
      }
    }

    // Get domain's company name and mail from the shared database.
    $dm = Domainit::model()->find("domain='" . Yii::app()->user->domain .  "'");
    $sender_name = $dm->yritys ?? '';
    $replyto_email = $dm->sahkoposti ?? '';

    // When kotipuhtaaksi, replace sender (KP already checked at top, but it will soon change).
    if (!empty(Yii::app()->user->kp)) {
      $sender_name = 'Koti Puhtaaksi Oy';
      $replyto_email = 'asiakaspalvelu@kotipuhtaaksi.fi';
    }

    if (empty($sender_name)) {
      echo json_encode([
        'success' => false,
        'message' => 'Sisäinen virhe: Yrityksen nimen haku yhteisestä kannasta epäonnistui. Jos vika jatkuu, ota yhteys ylläpitoon.'
      ]);
      return false;
    }

    if (empty($replyto_email)) {
      echo json_encode([
        'success' => false,
        'message' => 'Sisäinen virhe: Yrityksen sähköpostiosoite ei ole määritetty. Jos vika jatkuu, ota yhteys ylläpitoon.'
      ]);
      return false;
    }

    // Form mail text.
    // $email_body = <<<EOD
    // Hei!<br>
    // <br>
    // Valitettavasti omasiistijänne on estynyt seuraavalla siivouskäynnillä. Lupasimme ilmoittaa asiasta etukäteen.<br>
    // <br>
    // Ystävällisin Terveisin,<br>
    // <a href="https://www.kotipuhtaaksi.fi">Koti Puhtaaksi</a><br>
    // <a href="mailto:asiakaspalvelu@kotipuhtaaksi.fi">asiakaspalvelu@kotipuhtaaksi.fi</a><br>
    // <br>
    // (Vastaukset tähän sähköpostiin menee suoraan asiakastukilaatikkoomme. Vastaamme mahdollisimman pian!)
    // EOD;

    // Attempt to send mail.
    $mail = new YiiMailer();
    $mail->setFrom('no-reply@etunti.fi', $sender_name);
    $mail->setTo($client_email);
    $mail->setSubject($email_subject);
    $mail->setBody($email_body);
    $mail->addReplyTo($replyto_email);

	$customer = $asiakas->sahkoposti ?? "ID $customer_id";
    if($mail->send()) {

		// log success
		$log=new Log;
		$log->log_category 	= 1; // 1-email
		$log->email_to 		= $client_email;
		$log->email_subject	= $email_subject;
		$log->email_message	= $email_body;
		$log->save();

		// Return to the caller with good news.
		echo json_encode([
		  'success' => true,
		  'message' => "Ilmoitus lähetetään asiakkaalle $customer osoitteeseen $client_email. " .
			"Odota hetki kun työvuoro tallennetaan ja avataan uudelleen..",
		]);
	} else {
		// return to the caller with bad news
		echo json_encode([
			"success" => false,
			"message" => "Virhe ilmoitusta lähettäessä asiakkaalle $customer osoitteeseen $client_email.",
		]);
		return false;
	}

    
  }

  /**
   * Get current selection for whether warnings about regular cleaners not being
   * shown are enabled or not, or modify selection if a value is provided.
   *
   * For AJAX.
   *
   * @param int $id
   * Worker ID, or 0 for a list of workers with warnings disabled.
   *
   * @param int $value
   * Value for database, where 1: enabled and 0: disabled.
   *
   * @return null
   * If a value was provided, outputs result based on whether warnings were
   * enabled or disabled. Otherwise, outputs 1 or 0 for enabled or disabled.
   *
   * If $id == 0, outputs a list of workers with warnings disabled, in JSON.
   */
  public function actionOmasiistijat_siistijakohtainen_varoitus($id = null, $value = null)
  {
    // Get value from POST if provided.
    if (is_numeric($_POST['value'] ?? '')) {
      $value = $_POST['value'];
    }

    // Get ID from POST if provided.
    if (is_numeric($_POST['id'] ?? '')) {
      $id = $_POST['id'];
    }

    // If not kp or testing, return with appropriate output.
    if (empty(Yii::app()->user->kp)) {
      if ($value !== null) {
        if ($value == 0) {
          echo json_encode([]);
        } else {
          Yii::t('main', 'Tämä ominaisuus ei ole käytössä ympäristössäsi.');
        }
      } else {
        echo 1;
      }

      return false;
    }

    // Get list of cleaners if id = 0.
    if ($id == 0) {

      // Find tids for which warning should be enabled.
      $criteria = new CDbCriteria();
      $criteria->select = 'id';
      $criteria->condition = 'omasiistijavaroitukset = 0';
      $results = Tyontekijat::model()->findAll($criteria);

      // Format into simple array of IDs and echo as JSON.
      echo json_encode(array_column($results, 'id'));
    } else {

      // Update value or output current selection if value is null.
      if (!is_numeric($value)) {
        echo Tyontekijat::model()->findByPk($id)->omasiistijavaroitukset;
      } else {
        if (!in_array($value, [0, 1])) {
          echo Yii::t('main', 'Viallinen valinta omasiistijävaroitukselle.');
          return false;
        } else {
          Tyontekijat::model()->updateByPk($id, ['omasiistijavaroitukset' => $value]);
          echo Yii::t('Main', $value ? "Omasiistijävaroitukset aktivoitu - päivitä sivu." : "Omasiistijävaroitukset piiloitettu - päivitä sivu.");
        }
      }
    }
  }

  /**
   * Check if warnings about regular cleaners should be shown.
   *
   * Uses serialization feature of jQuery for dynamic changes in tv edit form.
   *
   * @param mixed $shift_ids
   * ID of the open shift.
   *
   * @param array $override
   * Array of keys/values to override in the model, for checking.
   *
   * @return bool
   * True or false; result is also echoed as 1: show and 0: hide warning.
   */
  public function actionOmasiistijat_tarkistus($shift_ids = null, $override = null)
  {
    if (isset($_POST['shift_ids']))
      $shift_ids = $_POST['shift_ids'];

    if (!is_numeric($shift_ids)) {

      foreach (json_decode($shift_ids) as $id)
        $shifts[] = $this->this_id($id)['model'];
      echo json_encode($this->os_check_warning($shifts));

    } else {

      $sdata = $this->this_id($shift_ids);
      $sid = $sdata['model']->id;

      if (!empty($override = json_decode(($_POST['override'] ?? $override) ?: [], true))) {
        foreach ($override as $key => $val)
          $sdata['model']->$key = $val;
      }

      $check_results = $this->os_check_warning($sdata['model']);
      echo (!empty($check_results[$sid]) ? 1 : 0);
    }
  }

  public function actionOs_cache_clear()
  {
    if (empty(Yii::app()->user->kp))
      return false;

    /** @var CCache $cc */
    $cc = Yii::app()->cache;
    $id = $this->cachekey('omasiistijat');
    $data = $cc->get($id);
    $cc->delete($id);

    $count = (is_countable($data) ? count($data) : -1);
    $fmt = 'Omasiistijat: Cleared %d items (debug type: %s).';
    $this->tracef('cache', $fmt, $count, gettype($data));
  }

  /**
   * Checks if the cleaners on a shift are not regulars, and warnings are on.
   *
   * Additional checks are made that should affect whether or not the warnings
   * are displayed, based on information on the object.
   *
   * @param mixed $shifts
   * Single value or an array. Values must be Tyovuoroot/ToistuvatTyovuorot
   * objects (or any object with same properties), or shift IDs.
   *
   * @return bool
   * Array of boolean values indexed by model IDs: True if warnings should be
   * displayed; otherwise, false.
   */
  public function os_check_warning($shifts)
  {
    if (!is_array($shifts))
      $shifts = [$shifts];

    if (empty(Yii::app()->user->kp)) {
      $t = [];
      foreach ($shifts as $s)
        $results[(is_object($s) ? $s->id : $s)] = false;
      return $t;
    }

    $os = $this->os_full_data();
    $results = [];

    foreach ($shifts as $s) {

      if (is_object($s)) {
        $tv = $s;
        $sid = $tv->id;
      } else {
        $tv = $this->this_id($s)['model'];
        $sid = $s;
      }

      $results[$sid] = false;

      if (empty($tv->kohde))
        continue;

      $kid = $tv->kohde;
      if ($kid == 0 || !isset($os[$kid]))
        continue;

      // Check primary cleaner.
      if (isset($os[$kid][$tv->tid]))
        continue;

      // Decode possible additional cleaners from worker pairs (tyoparit). Check
      // for any common values, in which case, the warnings should not be shown.
      if (!empty($tv->tyopaari)) {
        foreach (json_decode($tv->tyopaari) as $tp) {
          if (isset($os[$kid][$tp]))
            continue 2;
        }
      }

      if (isset($tv->peruutettu) && $tv->peruutettu != 0) continue;
      if (isset($tv->omasiistijailmoitus) && $tv->omasiistijailmoitus != 0) continue;
      if (isset($tv->omasiistijavaroitus) && $tv->omasiistijavaroitus == 0) continue;
      if (isset($tv->tt->omasiistijavaroitukset) && $tv->tt->omasiistijavaroitukset == 0) continue;

      $results[$sid] = true;
    }

    return $results;
  }

  /**
   * Get list of workers that have approved shifts/cycles in any location.
   *
   * @return array
   * Arrays with attr: "id", "tekijan_nimi", "sukunimi", indexed by location ID.
   */
  public function os_full_data()
  {
    static $data;

    if (!isset($data)) {

      /** @var CCache $cc */
      $cc = Yii::app()->cache;
      $cid = $this->cachekey('omasiistijat');
      $data = $cc->get($cid);

      if (false === $data) {

        // Get list of cleaners with approved hours in target location.
        $dbresults = Yii::app()->db1->createCommand("
          SELECT s.kohdenID, s.tid, t.tekijan_nimi, t.sukunimi
          FROM sivex_ttekijat t
          INNER JOIN
          (
            SELECT tid, kohdenID
            FROM sivexkuitti
            WHERE hyvaksytty!=''
            UNION DISTINCT
            SELECT tid, kohdenID
            FROM sivexkuitti_repaired
            WHERE hyvaksytty!=''
          ) s
          ON s.tid = t.id
          WHERE s.kohdenID != 0
          AND t.aktiivinen = 1
          ORDER BY s.kohdenID, t.sukunimi
        ")->queryAll();

        $data = [];
        foreach ($dbresults as $row) {
          $kid = $row['kohdenID'];
          $tid = $row['tid'];
          if (!key_exists($kid, $data))
            $data[$kid] = [];
          $data[$kid][$tid] = [
            'id' => $tid,
            'tekijan_nimi' => $row['tekijan_nimi'],
            'sukunimi' => $row['sukunimi']
          ];
        }

        $this->tracef('cache', "Omasiistijat: Cache refreshed with %d items.", count($data));
        $cc->set($cid, $data, 86400);
      }
    }

    $this->tracef('cache', "Omasiistijat: All data requested (count: %d).", count($data));
    return $data;
  }

  /* Omasiistijät ))) */
  #endregion

  /**
   * Writes a formatted trace message.
   * This method will only log a message when the application is in debug mode.
   * @param string $category  Category of the message. It is case-insensitive.
   * @param string $fmt       Message format for {@link vsprintf}.
   * @param mixed ...$args    Optional args for formatting the message.
   * @return string           Final message that was passed to {@link Yii::trace}
   */
  public function tracef($category = null, $fmt, ...$args)
  {
    $msg = vsprintf($fmt, $args);
    Yii::trace($msg, $category);
    return $msg;
  }

  /**
   * Output results as JSON.
   *
   * Generates JSON results array with standard success and message, useful for
   * returning from an AJAX handler function:
   * ```
   * return $this->outfmt(1, 'Message.');
   * ```
   *
   * @param bool $result
   * Whether the request is successful.
   *
   * @param string $message 
   * Custom message to output along with $result.
   *
   * @return bool
   * Specified {@see $result}.
   */
  protected function outfmt(bool $result, string $message, ...$args) :bool
  {
    // Format message if $args provided.
    if (!empty($args)) {
      array_unshift($args, $message);
      $message = call_user_func_array('sprintf', $args);
    }

    // Output result as JSON.
    echo json_encode([
      'success' => $result,
      'message' => $message
    ]);

    return $result;
  }

  /**
   * Notifies customer about shift starting times.
   *
   * FOR AJAX.
   *
   * @param $. (POST)
   * Values for the notification:
   *     asiakas_id, kohde_id, pvm, aloitusaika, lopetusaika
   *
   * @return bool
   * Echoed result, and true if mail was sent, or false if error occured.
   * JSON result set format: [
   *   'success': true/false
   *   'message': success message or error message
   * ]
   */
  public function actionAloitusaikojen_ilmoitus()
  {
    // If not kp or testing, cancel action.
    if (empty(Yii::app()->user->kp))
      return $this->outfmt(false, 'Tämä ominaisuus ei ole käytössä ympäristössäsi.');

    // Get email subject and body from settings and verify they're not empty.
    $asetukset = Asetukset::model()->findByPk(1);
    if (empty($email_subject = $asetukset->aloitusajat_email_subject ?? ''))
      return $this->outfmt(false, 'Asetuksissa määritettävä aloitusaikailmoituksen otsikko puuttuu.');
    if (empty($email_body = $asetukset->aloitusajat_email_body ?? ''))
      return $this->outfmt(false, 'Asetuksissa määritettävä aloitusaikailmoituksen teksti puuttuu.');

    // Check and assign required POST values.
    $errfmt = 'Sisäinen virhe: Vaadittu arvo (%s) ei tunnistettu/puuttuu.';
    if (empty($pvm = $_POST['pvm']))
      return $this->outfmt(false, $errfmt, 'pvm');
    if (empty($aloitusaika = $_POST['alku']))
      return $this->outfmt(false, $errfmt, 'alku');
    if (empty($lopetusaika = $_POST['loppu']))
      return $this->outfmt(false, $errfmt, 'loppu');

    // Check for non-existent customer.
    if (!is_numeric($asiakas_id = $_POST['asiakas_id']))
      return $this->outfmt(false, $errfmt, 'asiakas_id');
    elseif (empty($asiakas = Asiakkaat::model()->findByPk($asiakas_id)))
      return $this->outfmt(false, 'Asiakasta ID "%d" ei löydetty.', $asiakas_id);

	// Check that the customer has an email specified. (TODO: validate?)
	if(empty($client_emails = $asiakas->AloitusajatEmails ?? []))
		return $this->outfmt(false, "Asiakkaan ID %d sähköposti ei ole määritelty tai on viallinen.", $asiakas_id);

    // Check for non-existent location.
    if (!is_numeric($kohde_id = $_POST['kohde_id']))
      return $this->outfmt(false, $errfmt, 'kohde_id');
    elseif (empty($kohde = Kohteet::model()->findByPk($kohde_id)))
      return $this->outfmt(false, 'Kohde ID "%d" ei löydetty.', $kohde_id);

    // Replace any placeholders in the subject/body with variables. Format dates
    // as d.m.Y (20.02.2020) and times H:i (13:00). Placeholders:
    //   %osoite%, %pvm%, %aloitus%, %lopetus%
    $placeholders = [
      'osoite' => $kohde->osoite,
      'pvm' => $pvm,
      'aloitus' => $aloitusaika,
      'lopetus' => $lopetusaika
    ];

    foreach ($placeholders as $placeholder => $replacement) {
      $email_subject = str_replace("%{$placeholder}%", $replacement, $email_subject);
      $email_body = str_replace("%{$placeholder}%", $replacement, $email_body);
    }

    // Attempt to send mail.
    $sender_name = 'Koti Puhtaaksi Oy';
    $replyto_email = 'asiakaspalvelu@kotipuhtaaksi.fi';
    $mail = new YiiMailer();
    $mail->setFrom('asiakaspalvelu@kotipuhtaaksi.fi', $sender_name);
    $mail->setTo($client_emails);
    $mail->setSubject($email_subject);
    $mail->setBody($email_body);
    $mail->addReplyTo($replyto_email);
    $mail->send();

	
	$log = new Log();
	$log->kuka = Yii::app()->user->nimi;
	$log->log_category = Log::EMAIL_CATEGORY;
	$log->email_to = $asiakas->AloitusajatEmailsString;
	$log->email_subject = $email_subject;
	$log->email_message = $email_body;
	$log->save();

    // Return to the caller with good news.
    $customer = $asiakas->sahkoposti ?? "ID $asiakas_id";
    echo json_encode([
      'success' => true,
      'message' => "Ilmoitus lähetetään asiakkaalle $customer osoitteisiin $asiakas->AloitusajatEmailsString. " .
                   "Odota hetki kun työvuoro tallennetaan ja avataan uudelleen..",
      // 'message' => "$email_subject: $email_body"
    ]);
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
		if (strpos($val, 'Ei lasketa') !== false or strpos($val, 'Varallaolo') !== false or strpos($val, 'Ehdollinen varallaolo') !== false) {
		    $return = true;
		}
		return $return;
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

	/**
	 * Renders a partial displaying employees names and their language skills
	 * Used in shift form.
	 */
	public function actionEmployeeskills()
	{
		$req = Yii::app()->request;
		$employee_ids = $req->getQuery("employee_ids", []);
		$crit = new CDbCriteria();
		$crit->addInCondition("id", $employee_ids);
		$employees = Tyontekijat::model()->findAll($crit);
		return $this->renderPartial("employee_skills", ["employees" => $employees]);
	}
}

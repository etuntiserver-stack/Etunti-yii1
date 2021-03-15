<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('etunnin_asiakkaat', 'update_etunnin_asiakas', 'etunnin_asiakas_kk', 'laheta_et_kirje', 'etunnin_asiakas_kk_laskuri', 'delete_etunnin_asiakas', 'errorlog'),
                		'expression'=>"Yii::app()->controller->isDigisten()",
			),
			array('allow',
				'actions'=>array( 'error_custom', 'header', 'footer', 'lomake_tarjouspyynto', 'lomake_testiryhma', 'ajankohtaista', 'asiakkaat', 'lomake_lataailmainen', 'uusi_kommento', 'crontab', 'logout', 'salasanan_palauttaminen', 'change_password'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('spendingclients', 'site_error', 'etusivu','ohjesivu','etusivu_esimerki', 'change_color', 'valiko', 'valiko_ajax', 'kohderyhma', 'ohjevideot', 'mobemu', 'etusivu_ajax', 'ulkonaky', 'autocomplete', 'synkronoi_gps_sijainti', 'mail_template', 'getcityes', 'edico_etusivulle', 'maksullinen', 'tyot_tanaan', 'parassiivojatanaan', 'avoimet_kohteet', 'toteututhismonth', 'tehdyttunnittanaan', 'suunnitteltutunnittanaan', 'viestittanaan', 'kayttajaonline', 'suunniteltulistatanaan', 'getasiakasidbynimi', 'otakaytoon', 'ohjeet', 'kaaviot', 'management', 'management_tunnit', 'management_hours', 'spendingclients'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow',
				'actions'=>array('file_safe_opener'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin() || Yii::app()->controller->isAsiakas() || isset(Yii::app()->user->domain)",
			),
			array('allow',
				'actions'=>array('index','test','hyvaksy','hylkaa', 'confirm', 'aloita', 'defdb_dump', 'opentmp'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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

	public function isDigisten() {

		if($this->tasot(999))
			return true;
		else
			return false;
	}

	public function isEtuntiAdmin() {

		if( isset(Yii::app()->user->domain) and isset(Yii::app()->user->adminID)){
			$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	        	if( isset($m->id) and $m->id == Yii::app()->user->adminID ){
	            		return true;
			}
		} else {
	            	return false;
		}
	}

        public function init()
        {
                if (Yii::app()->controller->isEtuntiAdmin() and !isset(Yii::app()->user->user_theme)) {

			// <-- Ajaa kaikki modelit
			if(!isset(Yii::app()->user->AjaaKaikkiModelit))
			{
				Yii::app()->user->setState('AjaaKaikkiModelit', true);
				$this->AjaaKaikkiModelit();
			}
			//     Ajaa kaikki modelit -->

                        Yii::app()->theme = 'etunti';

                } elseif (Yii::app()->controller->isEtuntiAdmin() and isset(Yii::app()->user->user_theme)) {
                        Yii::app()->theme = Yii::app()->user->user_theme;
                } elseif (isset(Yii::app()->user->asiakas)) {
                        Yii::app()->theme = 'customer';
                } else {
                        Yii::app()->theme = 'classic';
                }
                parent::init();

        }

	public function actionOtakaytoon($tila)
	{
                Yii::app()->theme = 'etunti';
		if( !isset(Yii::app()->user->domain) ){ $this->redirect(array('index')); }
		$this->render('otakaytoon', array(
			'tila' => $tila
		));
	}

	/** Charts page. */
	public function actionKaaviot()
	{
		// Temporary default variables. (Copied from old code)
		$chart_type = 'line'; // line | bar | column | area
		$from = date("Y-m-d", strtotime(" -1 year first day of this month"));
		$to = date("Y-m-d", strtotime(" last day of last month"));
		$asiakas = '';
		$tyontekija = '';
		$kpl_maara = 10;
		$months = array(
			1 => Yii::t('main', 'Tammikuu'),
			2 => Yii::t('main', 'Helmikuu'),
			3 => Yii::t('main', 'Maaliskuu'),
			4 => Yii::t('main', 'Huhtikuu'),
			5 => Yii::t('main', 'Toukokuu'),
			6 => Yii::t('main', 'Kesäkuu'),
			7 => Yii::t('main', 'Heinäkuu'),
			8 => Yii::t('main', 'Elokuu'),
			9 => Yii::t('main', 'Syyskuu'),
			10 => Yii::t('main', 'Lokakuu'),
			11 => Yii::t('main', 'Marraskuu'),
			12 => Yii::t('main', 'Joulukuu')
		);
		$this->render('kaaviot', [
			'chart_type' => $chart_type,
			'from' => $from,
			'to' => $to,
			'asiakas' => $asiakas,
			'tyontekija' => $tyontekija,
			'kpl_maara' => $kpl_maara,
			'months' => $months
		]);
	}

	public function actionManagement_hours()
	{
                Yii::app()->theme = 'etunti';
		$this->render('management_hours');
	}

	public function actionManagement()
	{

	// <-- Oikeudet
	   $checkOikeus = "management_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

                Yii::app()->theme = 'etunti';
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

		$from = date ("Y-m-d", strtotime(" -1 year first day of this month"));
		$to = date ("Y-m-d", strtotime(" last day of last month"));

		if(isset($_GET['from']) and isset($_GET['to'])){
		$from 	= date ("Y-m-d", strtotime($_GET['from']));
		$to 	= date ("Y-m-d", strtotime($_GET['to']));
		}

		$criteria = new CDbCriteria();
		if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi'])){
		$criteria->condition = "
			yrityksen_nimi='".$_GET['yrityksen_nimi']."' OR CONCAT(etunimi , ' ' , sukunimi)='".$_GET['yrityksen_nimi']."' 
		";
		$asiakas = Asiakkaat::model()->find($criteria);
		}
		if(isset($_GET['asiakas_id']) and !empty($_GET['asiakas_id'])){
		$criteria->condition = "
			id='".$_GET['asiakas_id']."'
		";
		$asiakas = Asiakkaat::model()->find($criteria);
		}

		$this->render('management', array(
			'months' => $months,
			'from' => $from,
			'to' => $to,
			'asiakas' => (isset($asiakas->id))?$asiakas:'',
		));
	}

	public function actionManagement_tunnit()
	{
                Yii::app()->theme = 'etunti';
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

		$from = date ("Y-m-d", strtotime(" -1 year first day of this month"));
		$to = date ("Y-m-d", strtotime(" last day of last month"));

		if(isset($_GET['from']) and isset($_GET['to'])){
		$from 	= date ("Y-m-d", strtotime($_GET['from']));
		$to 	= date ("Y-m-d", strtotime($_GET['to']));
		}

		$this->render('management_tunnit', array(
			'months' => $months,
			'from' => $from,
			'to' => $to,
		));
	}
	public function actionOhjeet()
	{
		$this->render('ohjeet');
	}

	public function actionErrorlog()
	{
		$this->render('errorlog');
	}

	public function actionSite_error()
	{
		$this->renderPartial('error_custom');
	}

	public function actionOpentmp($domain, $file)
	{

		$openfile = Yii::app()->basePath.'/../tmp/'.$domain.'/'.$file;
		if (file_exists( $openfile ))
		{
				header("Content-Length: " . filesize ( $openfile ) );
		                header("Content-type: application/octet-stream");
		                header("Content-disposition: attachment; filename=".basename($openfile));
		                readfile($openfile);
				unlink($openfile);
		}
		exit;
	}

	public function actionFile_safe_opener($filepath, $ext)
	{
		//$file = file_get_contents($filepath);
		if (!file_exists( Yii::app()->basePath.'/../tmp/'.Yii::app()->user->domain )) {
		 	mkdir( Yii::app()->basePath.'/../tmp/'.Yii::app()->user->domain, 0777, true );
		}

		$newfile = 'tmp/'.Yii::app()->user->domain.'/'.basename($filepath);
		if (copy($filepath, $newfile)){
				header("Content-Length: " . filesize ( $newfile ) );
		                header("Content-type: application/octet-stream");
		                header("Content-disposition: attachment; filename=".basename($newfile));
		                readfile($newfile);
				unlink($newfile);
		}
		exit;
	}

	public function actionSynkronoi_gps_sijainti()
	{
		//header("Content-Type: text/html; charset=utf-8");
		$count = 0;
		if(isset($_POST['sunc']))
		{
			$asetuksetForAll = AsetuksetForAll::model()->findByPk(1);
			if(isset($asetuksetForAll->googlemaps_apikey) and !empty($asetuksetForAll->googlemaps_apikey))
			{
			    $model = Kohteet::model()->findAll();
			    foreach($model as $data)
			    {
				if(!empty($data->osoite) and !empty($data->kaupunki) and !empty($data->pnumero) and is_numeric($data->pnumero))
				{
					$count++;
					//$address = $data->id.' '.$data->pnumero.'+'.$data->kaupunki.'+'.$data->osoite.'<br>';


					$address = urlencode($data->pnumero.'+'.$data->kaupunki.'+'.$data->osoite);
					$content = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key=AIzaSyAsoAPXKSe3LfIiOYSerAotxCdC-jOFS2o');

					$response = json_decode($content, true);
					if(isset($response['status']) and $response['status'] == 'OK')
					{
						$lat = $response['results'][0]['geometry']['location']['lat'];
						$lng = $response['results'][0]['geometry']['location']['lng'];

						Kohteet::model()->updateByPk($data->id, array('gps_sijainti'=>$lat.','.$lng));

						//echo '<pre>';
						//print_r($response); //$response['results'][0]['geometry']['location']['lat']
						//echo '</pre>';
						//exit;
					}


				}
			    }
			}
		}

		echo urldecode($count);
	}

	public function actionMaksullinen()
	{

		$return = '';
		$arr = array();
		if(isset($_POST['dat']))
		{
			foreach($_POST['dat'] as $key => $item)
			  if($item == 'true')
				$arr[] = $key;

			ksort($arr);
			$return = '1,2';
			if(count($arr) > 0)
			$return .= ",".implode(",", $arr);

		}


		$domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' AND maksullinen=0 ");
		if(isset($domainit->id))
		{
				Domainit::model()->updateByPk($domainit->id, array('maksullinen' => 1));
				if(!empty($return))
				Domainit::model()->updateByPk($domainit->id, array('paketti' => $return));

				$subject = Yii::t('main', 'Etunti-käyttäjä vaihtoi maksulliseksi');
				$message = '
				'.$domainit->yritys.' vaihtoi Etunnin maksulliseksi.<br>
				Domain: '.$domainit->domain.'<br>
				Sähköposti-osoite: '.$domainit->sahkoposti.'
				';
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo('info@etunti.fi');
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();


				$DigistenYritysLog = Yii::app()->createController('DigistenYritysLog');
				$DigistenYritysLog[0]->addTapahtuma($domainit->id, 'ilmainen_maksulliseksi', $return);

				Yii::app()->user->setFlash('success', "Olette vaihtaneet ilmaisen palvelun laajempisisältöiseen maksulliseen palveluun.<br> Kysymyksissä pyydämme ottamaan yhteyttä sähköpostilla osoitteeseen tuki@etunti.fi");

		}
		$this->redirect(array('index'));
	}

	public function laskuri()
	{

		$domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' AND maksullinen=0 ");
		Yii::app()->user->setState('ilmainen_ilmoitus', $this->ilmainenIlmoitus());

		if( isset($domainit->id) )
		{

			$start_date = date( "Y-m-d", strtotime('first day of this month') );
			$end_date = date("Y-m-d", strtotime('last day of this month') );
			$sum_result = $this->digistenTunnitYhteensa($start_date, $end_date, 'kesto');

			if($domainit->ilmainen_versio_kayttotunnit != $sum_result)
				Domainit::model()->updateByPk($domainit->id, array('ilmainen_versio_kayttotunnit'=>$sum_result));

			Yii::app()->user->setState('ilmainen', true);
			Yii::app()->user->setState('ilmainen_kayttotunnit', $sum_result);

			return true;

		} else {
			Yii::app()->user->setState('ilmainen', false);
			return false;
		}

	}

	public function ilmainenIlmoitus()
	{
		$asetuksetForAll = AsetuksetForAll::model()->findbypk(1);
		return 'Ilmainen käyttö on mahdoton jos tunnit enemmään kun '. $asetuksetForAll->max_ilmaiset_tunnit;
	}

	public function laskuriForCron($domain)
	{

		$domainit = Domainit::model()->find(" domain='".$domain."' AND aktiivinen=1 AND maksullinen=1 ");
		if( isset($domainit->id) )
		{

			$start_date = date( "Y-m-d", strtotime('first day of last month') );
			$end_date = date("Y-m-d", strtotime('last day of last month') );
			$sum_result = $this->digistenTunnitYhteensa($start_date, $end_date, 'kesto');

			return $sum_result;

		} else {
			return false;
		}

	}

	public function digistenTunnitYhteensa($start_date, $end_date, $return_muoto)
	{
		$tt = Tyontekijat::model()->findAll();
		if(count($tt) == 0)
			return 0;

		$tids = [];
		foreach ($tt as $data)
			$tids[] = $data->id;

		$from 			= date( "Y-m-d", strtotime($start_date));
		$to 			= date( "Y-m-d", strtotime($end_date));
		$result 		= 0;
		$mob_result 		= 0;
		$tyovuorot_result 	= 0;

		// <-- Mobiili
		$mobile = Yii::app()->createController('Mobile');
		$tyotunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tids, [3], 2, true, 0, false, null, null);

		foreach($tyotunnit_all as $tid => $arvo)
			$mob_result += $arvo;

		// <-- Tyovuoro
		$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
		$haku_criteria	= "(".$this->eiLasketa().") AND status=3 AND (peruutettu=0 OR peruutettu IS NULL)";
		if($return_muoto == 'kesto')
		{
			$getAll 	= $tyovuorot[0]->tv_arr($from, $to, $tids, $haku_criteria, false, ['tv_kesto']);
			$tyovuorot_result = 0;
			foreach($getAll as $k => $v)
				foreach($v as $unix => $dayarr)
					foreach($dayarr as $key => $arr)
						foreach($arr as $arr2)
							$tyovuorot_result += $arr2['tv_kesto'];


			if($mob_result > $tyovuorot_result)
				$result = $mob_result;
			if($mob_result < $tyovuorot_result)
				$result = $tyovuorot_result;

			return $this->num($result);
		}
		
		
		if($return_muoto == 'table')
		{
			$getAll 	= $tyovuorot[0]->tv_arr($from, $to, $tids, $haku_criteria, false, ['tv_kesto', 'data']);
			$yht_sum 	= 0;
			$table 		= '<table class="table">
			<tr>
			<th>Pvm</th>
			<th>Osoite</th>
			<th>Klo</th>
			<th>Kesto</th>
			</tr>';
			foreach($getAll as $k => $v)
			{
				foreach($v as $unix => $dayarr)
				{
					foreach($dayarr as $key => $arr)
					{
						foreach($arr as $arr2)
						{
							$yht_sum += $arr2['tv_kesto'];
							$table .= '<tr>';
							$data = $arr2['data'];
							$table .= '
									<td>'.$arr2['this_pvm'].'</td>
									<td>'.(isset($data->kohteet->osoite)? $data->kohteet->osoite : '').'</td>
									<td>'.$data->alku.'-'.$data->loppu.'</td>
									<td>'.$this->num($arr2['tv_kesto']).'</td>';
							$table .= '</tr>';
						}
					}
				}
			}
			$table .= '<tr>
			<th></th>
			<th></th>
			<th>Yhteensä</th>
			<th>'.$this->num($yht_sum).'</th>
			</tr>';
			$table .= '</table>';
			return $table;
		}
		
	}

	public function actionTyot_tanaan()
	{
		// <-- Tyoryhmat
		$tyoryhmat_criteria = '';
		$tids = [];
		if( isset(Yii::app()->user->TyoryhmatTyontekijatHelperArray) ){
			$tids = Yii::app()->user->TyoryhmatTyontekijatHelperArray;
		}
		//    Tyoryhmat -->
		$s = 0;
		$thisday	= date("Y-m-d");
		$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
		$haku_criteria	= "status=3 AND peruutettu=0";
		$getAll 	= $tyovuorot[0]->tv_arr($thisday, $thisday, $tids, $haku_criteria, false, ['kpl_maara']);
		$count = [];
		foreach($getAll as $k => $v)
			foreach($v as $unix => $dayarr)
				foreach($dayarr as $key => $arr)
					foreach($arr as $kpl_maara)
						$count[] = $kpl_maara;

		$s = count($count);
		// -------------- //
		$criteria = new CDbCriteria();
		$criteria->select = "  COUNT(*) as count ";
		$criteria->condition = " DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) = CURDATE() AND status=1 ";
		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);
		$a = Mobile::model()->find($criteria);	

		$criteria = new CDbCriteria();
		$criteria->select = "  COUNT(*) as count ";
		$criteria->condition = " 
			DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) = CURDATE() and status=3
		";
		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);
		$t = Mobile::model()->find($criteria);

		$aa = 0;
		if(isset($a->count))
			$aa = $a->count;

		$tt = 0;
		if(isset($t->count))
			$tt = $t->count;

		$bd = '
		<input type="hidden" id="tanaan_sun" value="'.$s.'">
		<input type="hidden" id="tanaan_al" value="'.$aa.'">
		<input type="hidden" id="tanaan_tehdyt" value="'.$tt.'">

                      <table class="table mbn tc-med-1 tc-bold-last">
                        <thead>
                          <tr class="hidden">
                            <th>#</th>
                            <th>First Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <span class="fa fa-circle text-warning fs14 mr10"></span>'.Yii::t('main','Suunnitellut').'</td>
                            <td>'.$s.'</td>
                          </tr>
                          <tr>
                            <td>
                              <span class="fa fa-circle text-info fs14 mr10"></span>'.Yii::t('main','Käynnissä').'</td>
                            <td>'.$aa.'</td>
                          </tr>
                          <tr>
                            <td>
                              <span class="fa fa-circle text-primary fs14 mr10"></span>'.Yii::t('main','Tehdyt').'</td>
                            <td>'.$tt.'</td>
                          </tr>
                        </tbody>
                      </table>
		';
		echo json_encode($bd);
		exit;
	}

	public function actionAvoimet_kohteet()
	{
		$return = array();
       		$criteria = new CDbCriteria();
       		$criteria->select = " id,aloitan,loppui,kohde_kannasta  ";
       		$criteria->order = " id DESC  ";
       		$criteria->group = "kohde_kannasta";
       		$criteria->condition = "
			status=1
			AND EXTRACT(YEAR_MONTH FROM DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')))='".date("Ym")."'
		";
		$m = Mobile::model()->findAll($criteria);
		foreach($m as $data){
 			$data->loppui = date("d.m.Y H:i",time());
			$data->aloitan = date("d.m.Y H:i",strtotime($data->aloitan));
			$kesto =  strtotime($data->loppui) - strtotime($data->aloitan);
			$return[] = array(
				'kohde_kannasta'=>CHtml::link($data->kohde_kannasta.' #'.$data->id, array('/mobile/update', 'id' => $data->id)),
				'kesto'=>$this->sprint($kesto)
			);

		}
		echo json_encode($return);
		exit;
	}

	public function actionKayttajaonline()
	{

		$return = array();

       		$criteria = new CDbCriteria();
       		$criteria->order = " time DESC ";
       		$criteria->group = "user";
		$uo = UsersOnline::model()->findAll($criteria);
		foreach($uo as $data){
			$return[] = array('time'=>date("H:i",$data->time), 'user'=>$data->user);
		}
		echo json_encode($return);
		exit;
	}

	public function actionMail_template()
	{
		$this->renderPartial('mail_template');
	}

	protected function tasot($num)
	{
		$tas = array();
		if(isset(Yii::app()->user->adminPaketti))
		$tas = explode(",",Yii::app()->user->adminPaketti);
		if(in_array($num,$tas))
		return true;
		else
		return false;
	}

	public function actionEdico_etusivulle()
	{
		$return = array();

		if($this->tasot(5)) // crm
		{
			$avoin_vinkit = VinkkiExtranet::model()->findAll(" tila=1 ");
			$kasittelyt_vinkit = VinkkiExtranet::model()->findAll(" tila!=1 ");
			$avoin_palautteet = Palautteet::model()->findAll(" status=0 ");
			$kasittelyt_palautteet = Palautteet::model()->findAll(" status!=0 ");
			$return['avoin_vinkit'] = count($avoin_vinkit);
			$return['kasittelyt_vinkit'] = count($kasittelyt_vinkit);
			$return['avoin_palautteet'] = count($avoin_palautteet);
			$return['kasittelyt_palautteet'] = count($kasittelyt_palautteet);
		}

		echo json_encode($return);
		exit;
	}

	public function actionDefdb_dump($domain, $pass)
	{
                Yii::app()->theme = 'classic';
		if($domain == 'defdb' and $pass == 'Estrom2016!')
		{

			if (!file_exists( Yii::app()->basePath.'/../backup/defdb' )) {
			 	mkdir( Yii::app()->basePath.'/../backup/defdb', 0755, true );
			}

			exec("/usr/bin/mysqldump -u root -pMulgikapsas defdb | gzip -c > backup/defdb/defdb.sql.gz");
			$defdb = file_get_contents('backup/defdb/defdb.sql.gz');
			echo($defdb);
		}
		exit;
	}
/*
	protected function WHMtunnukset()
	{

			$c_panel_user = "estromfi";
			$user = "root";
			$token = "N5YBXZSXX245H3IL38U0CPGOMT7XGBTB";
			$host = 'https://srv.etunti.fi:2087/';

			if( $_SERVER['REMOTE_ADDR'] == '::1' or $_SERVER['REMOTE_ADDR'] == '127.0.0.1' )
			{
				$servername = "localhost";
				$username = "root";
				$password = "";
			} else {
				$servername = "localhost";
				$username = "root";
				$password = "Etunti2017!";
			}

		$return = array(
			'c_panel_user' => $c_panel_user,
			'user' => $user,
			'token' => $token,
			'host' => $host,
			'servername' => $servername,
			'username' => $username,
			'password' => $password,
		);

		return $return;

	}
*/
  	public function actionAloita()
	{

		$database = false;
		$vastaus = '';
		$kirjautumistunnus = '';

		if(isset($_POST['yrityksen_nimi']) and !empty($_POST['yrityksen_nimi']))
		{

			//print_r($_POST);
			//exit;

			unset($_SESSION['domain']);

			$str = trim($_POST['yrityksen_nimi']);
			$str = str_replace(array('ä','ö','ü','Ä','Ö','Ü'),array('a','o','u','A','O','U'), $str);
			$kirjautumistunnus = preg_replace('/[^\p{L}\p{N}\s]/u', '', $str);
			$kirjautumistunnus = str_replace(' ', '_', $kirjautumistunnus);
			$kirjautumistunnus = strtolower($kirjautumistunnus);
			$_SESSION['domain'] = $kirjautumistunnus;


			Yii::app()->db->setActive(false);
			Yii::app()->db->connectionString = 'mysql:host='.$this->dbhost().';dbname=etuntifw';
			Yii::app()->db->setActive(true);
			$connection=Yii::app()->db;
			$connection->createCommand("CREATE DATABASE IF NOT EXISTS `$kirjautumistunnus`")->execute();


			exec("mysqldump -u '".$connection->username."' -p'".$connection->password."' defdb > lib/defdb.sql");
			$str = "mysql -u ".$connection->username." -p".$connection->password." $kirjautumistunnus < lib/defdb.sql";
			exec($str, $output, $return_var);
			$database = true;


			$chk_domain = Domainit::model()->find(" domain='".$kirjautumistunnus."' ");
			if(!isset($chk_domain->id))
			{
				$paketti = '1,2,3,5';
				$new_domain = new Domainit;
				$new_domain->domain = $kirjautumistunnus;
				$new_domain->kirjautumistunnus = $kirjautumistunnus;
				$new_domain->yritys = $_POST['yrityksen_nimi'];
				$new_domain->y_tunnus = $_POST['yritys_tunnus'];
				$new_domain->paketti = $paketti;
				$new_domain->sahkoposti = $_POST['sahkoposti'];
				$new_domain->puhelin = $_POST['puhelinnumero'];
				$new_domain->aktiivinen = 1;
				$new_domain->maksullinen = 0;
				if($new_domain->save())
				{
					$DigistenYritysLog = Yii::app()->createController('DigistenYritysLog');
					$DigistenYritysLog[0]->addTapahtuma($new_domain->id, 'new_domain', $paketti);
				}
			}


			Yii::app()->db1->setActive(false);
			Yii::app()->db1->connectionString = 'mysql:host='.$this->dbhost().';dbname='.$kirjautumistunnus;
			Yii::app()->db1->setActive(true);


			$ft = FirmanTiedot::model()->findByPk(1);
			if(isset($ft->id))
			{
				FirmanTiedot::model()->updateByPk($ft->id, array(
					'tyonantaja' => $_POST['yrityksen_nimi'],
					'y_tunnus' => $_POST['yritys_tunnus'],
					'puhelin' => $_POST['puhelinnumero'],
					'sahkoposti' => $_POST['sahkoposti'],
				));
			}

			$adm = Administrators::model()->findByPk(1);
			if(isset($adm->id))
			{
				$token = sha1(uniqid(time().$adm->adm_nimi, true));
				Administrators::model()->updateByPk($adm->id, array(
					'adm_login' => 'admin',
					'adm_salasana' => '',
					'adm_email' => $_POST['sahkoposti'],
					'token' => $token,
				));


				$subject = Yii::t('main', 'Uusi Etuntikäyttäjä');
				$message = '
				<p>Yrityksen nimi: '.$_POST['yrityksen_nimi'].'</p>
				<p>Y-tunnus: '.$_POST['yritys_tunnus'].'</p>
				<p>Puhelinnumero: '.$_POST['puhelinnumero'].'</p>
				<p>Sähköpostiosoite: '.$_POST['sahkoposti'].'</p>
				';
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo('etuntimarkkinointi@etunti.fi');
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();


				$message = '';
				$message .= '<p>Yritystunnus: '.$kirjautumistunnus.'</p>';
				$message .= '<p>Käyttäjätunnus: admin</p>';
				$message .= '<p>Aktivoi käyttäjätunnuksesi <a href="'.Yii::app()->getBaseUrl(true).'/index.php/site/confirm?domain='.$kirjautumistunnus.'&token='.$token.'">tästä</a><br>';

				$subject = Yii::t('main', 'Tervetuloa Etunti');
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($_POST['sahkoposti']);
				$mail->setSubject($subject);
				$mail->setBody($message);
				if($mail->send())
				{
					Yii::app()->user->setFlash('success', "Kiitoksia tilauksesta.<br> Palvelun käyttöön tarvittavat tunnukset on lähetty sähköpostiisi.<br> Näillä tunnuksilla voit heti aloittaa palvelun käyttämisen.");
					//Yii::app()->session->destroy();
					$this->redirect(array('index'));
				}


			}


					Yii::app()->user->setFlash('danger', "Error");
					//Yii::app()->session->destroy();
					$this->redirect(array('index', 'aloita' => 'error', 'kirjautumistunnus' => $kirjautumistunnus, 'email' => $_POST['sahkoposti']));

		}

		$this->render('index', array(
			'vastaus' => $vastaus,
			'database' => $database,
			'kirjautumistunnus' => $kirjautumistunnus,
		));

	}

	public function AjaaKaikkiModelit()
	{

		$models = array();
		$modelsDir = Yii::getPathOfAlias("application.models");
		$dh = opendir($modelsDir);
		if ($dh !== false)
		{
		    $matches = array();
		    while (($modelFileName = readdir($dh)) !== false)
		    {
		        if (preg_match("/^([A-Za-z0-9]+)\.php$/", $modelFileName, $matches))
			{
			   if(
				isset($matches[1])
				and $matches[1] != 'Page'
				and $matches[1] != 'LoginForm'
			    )
			    {
		            	$m = new $matches[1];
				//echo $matches[1].'<br>';
			    }
			}
		    }
		    //exit;
		    closedir($dh);
		}
	}

	public function actionUlkonaky()
	{
		$ad = Administrators::model()->findByPk(Yii::app()->user->adminID);
		$result = '';
		if(isset($_POST['vaihdo']))
		{
			if( $_POST['vaihdo'] == 'sidebarSkin' )
			{

				$ulkonaky = json_decode($ad->ulkonaky, true);
				$ulkonaky['sidebarSkin'] = $_POST['sidebarSkin'];

				$result = json_encode($ulkonaky);
				Administrators::model()->updateByPk($ad->id, array('ulkonaky'=>$result));

			}

			if( $_POST['vaihdo'] == 'headerSkin' )
			{

				$ulkonaky = json_decode($ad->ulkonaky, true);
				$ulkonaky['headerSkin'] = $_POST['headerSkin'];

				$result = json_encode($ulkonaky);
				Administrators::model()->updateByPk($ad->id, array('ulkonaky'=>$result));

			}
		}

		if(isset($_POST['getSkins']))
		{
			$result = $ad->ulkonaky;
		}

		if(isset($_POST['clearStorage']))
		{
			Administrators::model()->updateByPk($ad->id, array('ulkonaky'=>''));
		}

		echo $result;
	}

	protected function rand_pass( $length ) {

    		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    		return substr(str_shuffle($chars),0,$length);

	}

	public function actionConfirm($token)
	{

		$model = Administrators::model()->find(" token!='' AND token='".$token."' ");
		if(!isset($model->id))
			die('Aktivointi linkki ei ole enää voimassa.');

		if( Yii::app()->request->getPost('uusi_salasana') )
		{

			if(
				isset($model->id)
				and !empty(Yii::app()->request->getPost('uusi_salasana'))
				and Yii::app()->request->getPost('uusi_salasana') == Yii::app()->request->getPost('varmista_uusi_salasana')
			)
			{
				$uusi_salasana = password_hash(Yii::app()->request->getPost('uusi_salasana'), PASSWORD_BCRYPT);
				$upd = Administrators::model()->updateByPk($model->id, array('adm_salasana' => $uusi_salasana, 'token' => ''));
				if( $upd != null )
				{
					Yii::app()->user->setFlash('success', "Salasanasi on luotu, kirjaudu sisään.");
					echo json_encode(array('ok'));
				}

			} elseif(
				isset($model->id)
				and !empty(Yii::app()->request->getPost('uusi_salasana'))
				and Yii::app()->request->getPost('uusi_salasana') != Yii::app()->request->getPost('varmista_uusi_salasana')
			)
			{
				echo json_encode('varmistaUusi');
			} elseif(
				isset($model->id)
				and ( empty(Yii::app()->request->getPost('uusi_salasana')) or empty(Yii::app()->request->getPost('varmista_uusi_salasana')) )
			)
			{
				echo json_encode('emptyUusi');

			} else {
				echo json_encode('error');
			}
			exit;
		}

                Yii::app()->theme = 'classic';
		$this->render('confirm', array(
			'token' => $token
		));

	}

	public function actionChange_password()
	{


		if( Yii::app()->request->getPost('vanha_salasana') )
		{

			$m1 = Administrators::model()->findByPk(Yii::app()->user->id);

	       		$criteria = new CDbCriteria();
		        $criteria->condition = "
				id='".Yii::app()->user->id."'
			";

			if( isset($m1->id) and strlen($m1->adm_salasana) < 60 ){
		        $criteria->addCondition (" adm_salasana='".md5(Yii::app()->request->getPost('vanha_salasana'))."' ");
			} elseif( isset($m1->id) and strlen($m1->adm_salasana) == 60 ){
				if (password_verify(Yii::app()->request->getPost('vanha_salasana'), $m1->adm_salasana)) {

				} else {
				    	echo json_encode( 'InvalidPassword');
					exit;
				}
			}

			$model = Administrators::model()->find($criteria);




			if(
				isset($model->id)
				and !empty(Yii::app()->request->getPost('uusi_salasana'))
				and Yii::app()->request->getPost('uusi_salasana') == Yii::app()->request->getPost('varmista_uusi_salasana')
			)
			{
				$uusi_salasana = password_hash(Yii::app()->request->getPost('uusi_salasana'), PASSWORD_BCRYPT);
				$upd = Administrators::model()->updateByPk($model->id, array('adm_salasana' => $uusi_salasana ));
				if( $upd != null )
				echo json_encode(array('ok'));
			} elseif(
				isset($model->id)
				and !empty(Yii::app()->request->getPost('uusi_salasana'))
				and Yii::app()->request->getPost('uusi_salasana') != Yii::app()->request->getPost('varmista_uusi_salasana')
			)
			{
				echo json_encode('varmistaUusi');
			} elseif(
				isset($model->id)
				and ( empty(Yii::app()->request->getPost('uusi_salasana')) or empty(Yii::app()->request->getPost('varmista_uusi_salasana')) )
			)
			{
				echo json_encode('emptyUusi');

			} else {
				echo json_encode('error');
			}
			exit;
		}

                Yii::app()->theme = 'classic';
		$this->render('change_password');
	}

	public function actionSalasanan_palauttaminen()
	{

		if(Yii::app()->getRequest()->getParam('check'))
		{
			if(empty(Yii::app()->getRequest()->getParam('domain'))){
				echo json_encode('domainEmpty');
				exit;
			}

			$domain 	= Yii::app()->getRequest()->getParam('domain');
			$username	= Yii::app()->request->getPost('username');

	       		$criteria = new CDbCriteria();
		        $criteria->condition = " adm_login='".$username."' ";
			$model = Administrators::model()->find($criteria);

			if(isset($model->id) and !empty($model->adm_email))
			{
				$token = sha1(uniqid(time().$model->adm_nimi, true));
				Administrators::model()->updateByPk($model->id, array('adm_salasana'=>'', 'token' => $token));
				$message = '';
				$message .= '<p>Aktivoi käyttäjätunnuksesi <a href="'.Yii::app()->getBaseUrl(true).'/index.php/site/confirm?token='.$token.'">tästä</a><br>';

				$ft = FirmanTiedot::model()->findbypk(1);

				$subject = Yii::t('main', 'Uusi salasana'). ' '.$model->adm_nimi;
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($model->adm_email);
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $model->adm_email;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				echo json_encode(array('ok',$model->adm_email));

			} else {
				echo json_encode('error');
			}
			exit;
		}

                Yii::app()->theme = 'classic';
		$this->render('salasanan_palauttaminen');
	}

	public function actionEtusivu_ajax()
	{
/*
		if(isset($_POST['suoritus']))
		{
		$suoritus = $_POST['suoritus'];
		$this->renderPartial('etusivu_ajax',array(
			'suoritus'=>$suoritus,
		));
		}
		exit;
*/
	}

	public function actionCrontab($full, $only_domain=null)
	{
                Yii::app()->theme = 'classic';
		$this->renderPartial('crontab',array(
			'full'=>$full,
			'only_domain' => $only_domain
		));
	}


	public function actionLaheta_et_kirje()
	{

		if(isset($_POST['Domainit']))
		{
			$ft = FirmanTiedot::model()->findbypk(1);

			$message = str_replace("\n", "<br>", $_POST['Domainit']['viesti']);
			foreach($_POST['Domainit']['sahkoposti'] as $sahkoposti)
			{

			$mail = new YiiMailer();
			$mail->setFrom('no-reply@etunti.fi');
			$mail->setTo($sahkoposti);
			$mail->setSubject(Yii::t('main', 'ETUNTI.FI'));
			$mail->setBody($message);
				if($mail->send())
				{

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $sahkoposti;
							$log->email_subject	= Yii::t('main', 'ETUNTI.FI');
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->
				}
			}

			$mail = new YiiMailer();
			$mail->setFrom('no-reply@etunti.fi');
			$mail->setTo('no-reply@etunti.fi');
			$mail->setSubject(Yii::t('main', 'ETUNTI.FI (KOPIO)'));
			$mail->setBody($message);
			$mail->send();

			$this->redirect(array('etunnin_asiakkaat'));
		}

		$model= new Domainit;
		$this->render('laheta_et_kirje',array(
			'model'=>$model,
		));
	}


	public function actionEtunnin_asiakas_kk_laskuri($id)
	{
		$model=Domainit::model()->findbypk($id);

		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host='.$this->dbhost().';dbname='.$model->domain;

		$this->render('etunnin_asiakas_kk_laskuri',array(
			'id'=>$id,
			'model'=>$model,
		));
	}



	public function actionEtunnin_asiakas_kk($id)
	{
		$model=Domainit::model()->findbypk($id);

		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host='.$this->dbhost().';dbname='.$model->domain;
		Yii::app()->db1->setActive(true);

		$this->render('etunnin_asiakas_kk',array(
			'id'=>$id,
			'model'=>$model,
		));
	}

	public function actionUpdate_etunnin_asiakas($id)
	{
		$model=Domainit::model()->findbypk($id);

		if(isset($_POST['Domainit']))
		{
			$model->attributes=$_POST['Domainit'];
			$model->time=date("Y-m-d H:i:s", strtotime($model->time));
			if(isset($_POST['tasot'])) $model->paketti = implode(",",$_POST['tasot']);
			if($model->save())
				$this->redirect(array('etunnin_asiakkaat'));
		}

		$this->render('update_etunnin_asiakas',array(
			'model'=>$model,
		));
	}

	public function actionDelete_etunnin_asiakas($id)
	{
		$drop = false;
		$model=Domainit::model()->findbypk($id);
		if(isset($model->id))
		{
			$yritystunnus = $model->domain;

			$connection=Yii::app()->db;
			$connection->createCommand("DROP DATABASE IF EXISTS `$yritystunnus`")->execute();
			$model->delete();
			$this->redirect(array('etunnin_asiakkaat'));

		}

	}

	public function actionEtunnin_asiakkaat()
	{
       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";
	        $criteria->condition = " domain!='defdb'  ";

		if(isset($_GET['aktiivinen']))
	        	$criteria->addCondition (" aktiivinen='".$_GET['aktiivinen']."' ");
		else
	        	$criteria->addCondition (" aktiivinen=1 ");

		if(isset($_GET['maksullinen']))
	        	$criteria->addCondition (" maksullinen='".$_GET['maksullinen']."' ");
		else
	        	$criteria->addCondition (" maksullinen=1 ");

		if(isset($_GET['domain_nimi']) and !empty($_GET['domain_nimi']))
	        $criteria->addCondition (" domain LIKE '%".$_GET['domain_nimi']."%' ");

		$dataProvider=new CActiveDataProvider('Domainit', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('etunnin_asiakkaat', array('dataProvider' => $dataProvider));
	}


	public function actionAjankohtaista()
	{
		//Yii::app()->theme = 'classic';
		//$this->render('ajankohtaista');
	}
	public function actionOhjevideot()
	{
		if(!isset(Yii::app()->user->domain)){
			$this->redirect(array('index'));
		}
		Yii::app()->theme = 'etunti';
		$this->render('ohjevideot');
	}
	public function actionAsiakkaat()
	{
		//$this->render('asiakkaat');
	}
	public function actionLomake_lataailmainen()
	{
		//Yii::app()->theme = 'classic';
		//$this->render('lomake_lataailmainen');
	}
	public function actionUusi_kommento()
	{
		if(isset($_POST))
		{
			$model = new BlogComments;
			$model->blog_id=$_POST['blog_id'];
			$model->nimimerkki=$_POST['nimimerkki'];
			$model->teksti=$_POST['teksti'];
			if($model->save())
			echo 'ok';
		}
		exit;
	}





	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}


	public function actionGetasiakasidbynimi($nimi)
	{

		$criteria = new CDbCriteria();
		$criteria->condition = " yrityksen_nimi='".$nimi."' OR CONCAT(etunimi , ' ' , sukunimi)='".$nimi."' ";
		$a = Asiakkaat::model()->find($criteria);
		if(isset($a->id))
			echo $a->id;
		else
			echo 0;
	}

	public function actionKohderyhma()
	{

		$data = array();

	 	if(isset($_POST) and (!empty($_POST['ryhma']) or !empty($_POST['myyja'])))
		{
       			$criteria = new CDbCriteria();

			if(!empty($_POST['ryhma']))
       			$criteria->addCondition ( " ryhma='".trim($_POST['ryhma'])."' " );

			if(!empty($_POST['myyja']))
       			$criteria->addCondition ( " myyja='".trim($_POST['myyja'])."' " );







			$a = Asiakkaat::model()->findAll($criteria);
			foreach($a as $asiakas)
			{
				$m = Administrators::model()->findbypk($asiakas->myyja);
				if(isset($m->adm_nimi)) $myyja = $m->adm_nimi; else $myyja = '';

				$data[] = array(
					'Asiakkaat',
					$asiakas->yrityksen_nimi,
					$asiakas->Etusukunimi,
					$asiakas->osoite,
					$asiakas->kaupunki,
					$asiakas->puhelin,
					$asiakas->sahkoposti,
					$myyja,
				);
			}

       			$criteria = new CDbCriteria();

			if(!empty($_POST['ryhma']))
       			$criteria->addCondition ( " ryhma='".trim($_POST['ryhma'])."' " );

			if(!empty($_POST['myyja']))
       			$criteria->addCondition ( " myyja='".trim($_POST['myyja'])."' " );

			$y = Yhteystiedot::model()->findAll($criteria);
			foreach($y as $asiakas)
			{
				$m = Administrators::model()->findbypk($asiakas->myyja);
				if(isset($m->adm_nimi)) $myyja = $m->adm_nimi; else $myyja = '';

				$data[] = array(
					'Yhteystiedot',
					$asiakas->yrityksen_nimi,
					$asiakas->Etusukunimi,
					$asiakas->osoite,
					$asiakas->postitoimipaikka,
					$asiakas->puhelin,
					$asiakas->sahkoposti,
					$myyja,
				);
			}

		}



		$this->render('kohderyhma', array('data'=>$data));
	}

	public function actionValiko()
	{
		$mod = '
		<input type="hidden" id="select_type" value="'.$_POST['select_type'].'">
		<div id="result"></div>';
		$mod .= '
		<script type="text/javascript">
		$(document).ready(function(){
		        $.ajax({
		           url: location.protocol + "//" + location.host + "/index.php/site/valiko_ajax",
		           type: "POST",
		           data: { "select_type" : $("#select_type").val() },
		           success: function(data){
				//console.log(data);
				$("#result").html(data);
				return false;
		           }
		        });
		});
		</script>';
		echo json_encode($mod);
		exit;
	}

	public function actionValiko_ajax()
	{
		// <-- Oikeudet
		$checkOikeus = "pudotusvalikot_4_".Yii::app()->user->adminStatus;
		$this->checkOikeus($checkOikeus, true);
		//  Oikeudet -->

		// muokka
		if(isset($_POST['muokkaSelects']) and isset($_POST['id'])){
			$value2	= '';
			if( isset($_POST['value2']) and $_POST['select_type'] == 'tyoryhma' )
			$value2	= json_encode($_POST['value2']);
			Valikkoot::model()->updatebypk($_POST['id'], 
				array(
					'value'=>$_POST['value'],
					'value2'=>$value2
				)
			);
		}
		// deleteFromSelect
		if(isset($_POST['deleteFromSelect']) and $_POST['deleteFromSelect'] == "true"){
	       		$criteria = new CDbCriteria();
			$criteria->condition = " select_type = '".$_POST['select_type']."' ";
			$v = Valikkoot::model()->findAll($criteria);	
			if( count($v) > 1 )
			   Valikkoot::model()->deletebypk($_POST['id']);
			else
			    echo '<script>alert("Viimeinen rivi ei voidaan poistaa");</script>';	
		}
		// uusi
		if(isset($_POST['uusiRiviSelects']) and  $_POST['uusiRiviSelects']){
			$v = new Valikkoot;
			$v->value=$_POST['value'];
			$v->select_type=$_POST['select_type'];
			if(!$v->save()){
				print_r($v->getErrors());
				exit;
			}
		}
		$this->renderPartial('valiko_ajax');
		exit;
	}


	public function actionChange_color()
	{
		//Yii::app()->user->setState('myBgColors', $_POST['myBgColors']);
	}

	public function actionEtusivu()
	{

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		if( count($tt_arr) > 0 ){
			Yii::app()->user->setState('TyoryhmatTyontekijatHelperArray', $tt_arr);
		}
		//    Tyoryhmat -->

		// <-- juuri_tullut_asiakkaaksi
		if( isset(Yii::app()->user->domain) )
		{
		   $dm = Domainit::model()->find(" domain='".Yii::app()->user->domain."' and maksullinen=0 ");
		   $fm = FirmanTiedot::model()->find(" id=1 AND juuri_tullut_asiakkaaksi=1 ");
		   if( isset($dm->id) and isset($fm->id) )
		   {
			$this->redirect(array('/asetukset/yrityksentiedot', 'id' => 1, 'first' => true));
		   }
		}
		//     juuri_tullut_asiakkaaksi -->

		// <-- Backup
		/*
		if (
			isset(Yii::app()->user->domain) and
			!file_exists(Yii::app()->basePath."/../backup/".Yii::app()->user->domain.'/'.date("Y-m-d").'_'.Yii::app()->user->domain.'.sql.gz')
			and $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1'
		)
		{

		   if (!file_exists(Yii::app()->basePath."/../backup/".Yii::app()->user->domain)) {
		  	mkdir(Yii::app()->basePath."/../backup/".Yii::app()->user->domain, 0777, true);
		   }


		  	    exec("/usr/bin/mysqldump -u root -pEtunti2017! ".Yii::app()->user->domain." | gzip -c > backup/".Yii::app()->user->domain."/".date("Y-m-d")."_".Yii::app()->user->domain.".sql.gz");


		      	    //echo '<span id="uusiVarmuskopioText">uusi varmuskopio on tehty</span>';

		   	    foreach(array_reverse(glob(Yii::app()->baseUrl.'backup/'.Yii::app()->user->domain.'/*')) as $file)
			    {
				$explNimi = explode("/",$file);
				$explNimi2 = explode("_",end($explNimi));
				if($explNimi2[0] < date("Y-m-d", strtotime("-7 day")))
				{
					//echo $explNimi2[0].' '.date("Y-m-d", strtotime("-7 day")).'<br>';
					unlink(Yii::app()->basePath.'/../backup/'.Yii::app()->user->domain.'/'.end($explNimi));
				}
		   	    }


		}
		*/
		// Backup -->


		if(isset($_POST['currentBody']))
		Yii::app()->user->setState('currentBody',$_POST['currentBody']);

		if(isset($_GET['theme']))
		{
		  Yii::app()->user->setState('user_theme',$_GET['theme']);
		  $this->redirect('/index.php/site/etusivu');
		}

		if( !$this->isEtuntiAdmin() ){ 	$this->redirect('index'); }

		// <-- Tyoryhmat
		/*
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$this->render('etusivu_tyoryhma');
		} else {
			$this->render('etusivu');
		}
		*/
		//    Tyoryhmat -->

		$this->render('etusivu');

	}

	public function actionLomake_testiryhma()
	{
		$this->render('lomake_testiryhma');
	}

	public function actionLomake_tarjouspyynto()
	{
		$this->renderPartial('lomake_tarjouspyynto');
	}

	public function actionEtusivu_esimerki()
	{
		$this->render('etusivu_esimerki');
	}

	public function actionOhjesivu()
	{
		$this->render('ohjesivu');
	}

	public function actionTest()
	{
	/*
        # Example from HTML2PDF wiki: Send PDF by email
        $content_PDF = $html2pdf->Output('', EYiiPdf::OUTPUT_TO_STRING);
        require_once(dirname(__FILE__).'/pjmail/pjmail.class.php');
        $mail = new PJmail();
        $mail->setAllFrom('webmaster@my_site.net', "My personal site");
        $mail->addrecipient('mail_user@my_site.net');
        $mail->addsubject("Example sending PDF");
        $mail->text = "This is an example of sending a PDF file";
        $mail->addbinattachement("my_document.pdf", $content_PDF);
        $res = $mail->sendmail();
	*/

        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($this->renderPartial('test', compact('model'),true));
        $html2pdf->Output();


	}

	public function actionHyvaksy($id,$code,$domain)
	{

		Yii::app()->theme = 'classic';

       		$criteria = new CDbCriteria();
       		$criteria->condition = " status=1 AND id='".$id."' AND code='".trim($code)."' ";
		$model = AsiakasHyvaksynta::model()->find($criteria);

		if(isset($model->id))
		{

		$ids = explode(",",$model->ids);
		foreach($ids as $val)
		{
		    $explVal = explode("_", $val);
		    if(isset($explVal[1]))
		    {
			if($explVal[0] == 'mobile')
			   Mobile::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'1_'.date("d.m.Y")));

			if($explVal[0] == 'toteutu')
			   Toteutuneet::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'1_'.date("d.m.Y")));
		    }

		}


		$this->render('hyvaksy', array(
			'asia' => true,
		));


		AsiakasHyvaksynta::model()->updatebypk($model->id, array('code'=>'','status'=>3));

		} else {

		$this->render('hyvaksy', array(
			'asia' => false,
		));

		}



	}

	public function actionHylkaa($id,$code,$domain)
	{

		Yii::app()->theme = 'classic';

		$this->render('hylkaa', array(
			'id' => $id,
			'code' => $code,
			'domain' => $domain,
		));
	}

	public function actionMobemu()
	{
		$this->render('mobemu');
	}

	public function actionIndex()
	{
		// <-- Check aloita lomake
		if( isset($_POST['check_lomake']) and $_POST['check_lomake'] == 'ytunnus' ){
			$ft = Domainit::model()->find(" y_tunnus='".$_POST['yritys_tunnus']."' ");
			if( isset($ft->id) )
			echo 'on_olemassa';
			exit;
		}
		if( isset($_POST['check_lomake']) and $_POST['check_lomake'] == 'sahkoposti' ){
			$ft = Domainit::model()->find(" sahkoposti='".$_POST['sahkoposti']."' ");
			if( isset($ft->id) )
			echo 'on_olemassa';
			exit;
		}

		if(isset($_GET['soittaa']) and isset($_GET['otsikko']) and isset($_GET['viesti']))
		{
		   $afa = AsetuksetForAll::model()->findByPk(1);
		   if(isset($afa->email))
		   {
			$message = $_GET['viesti'];
			$mail = new YiiMailer();
			$mail->setFrom($afa->email);
			$mail->setTo($afa->email);
			$mail->setSubject($_GET['otsikko']);
			$mail->setBody($message);
			if($mail->send())
			{
				Yii::app()->user->setFlash('success','Viesti lähetetty.');
				$this->redirect(array('etusivu'));
			}
		   }
		}

		$this->render('index');
	}


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		/*
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
		*/
		if( $error=Yii::app()->errorHandler->error and isset($_SERVER['REMOTE_ADDR']) and ($_SERVER['REMOTE_ADDR'] == '::1' or $_SERVER['REMOTE_ADDR'] == '127.0.0.1' )){
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
		$this->renderPartial('error_custom');

	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";


				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionSuunnitteltutunnittanaan()
	{
		$tids 		= [];

		// <-- Tyoryhmat
		$tyoryhmat_criteria = '';
		if( isset(Yii::app()->user->TyoryhmatTyontekijatHelperArray) ){
			$tids = Yii::app()->user->TyoryhmatTyontekijatHelperArray;
		}
		//    Tyoryhmat -->

		$suunniteltu 	= 0;
		$thisday	= date("Y-m-d");
		$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
		$haku_criteria	= "status=3 AND (peruutettu=0 OR peruutettu IS NULL)";
		$getAll 	= $tyovuorot[0]->tv_arr($thisday, $thisday, $tids, $haku_criteria, false, ['tv_kesto']);
		/*
		echo '<pre>';
		print_r($getAll);
		echo '<pre>';
		*/
		$result = 0;
		foreach($getAll as $k => $v)
			foreach($v as $unix => $dayarr)
				foreach($dayarr as $key => $arr)
					foreach($arr as $arr2)
						$result += $arr2['tv_kesto'];

		if($result > 0)
	                echo json_encode($this->sprint($result));
		else
	                echo json_encode('00:00');
		exit;
	}

	public function actionViestittanaan()
	{
	  	$viestit = 0;
		$criteria = new CDbCriteria();
        	$criteria->condition = "
			status=3 AND tekija='toimisto'
			AND DATE(time) = CURDATE()
		";
	  	$v = Viestinta::model()->findAll($criteria);
	  	$viestit = count($v);

                echo json_encode($viestit);
		exit;
	}

	protected function UudetMobiiliViestit(){

       		$criteria = new CDbCriteria();
		$criteria->condition = " status=3 ";
		$vi = Viestinta::model()->find($criteria);
		if(isset($vi->id)){
			return true;
		} else {
			return false;
		}
	}

	public function actionTehdyttunnittanaan()
	{
		// <-- Tyoryhmat
		$tyoryhmat_criteria = '';
		if( isset(Yii::app()->user->TyoryhmatTyontekijatHelperArray) ){
			$impl = implode(",", Yii::app()->user->TyoryhmatTyontekijatHelperArray);
			$tyoryhmat_criteria = "tid IN ($impl)";
		}
		//    Tyoryhmat -->

		$query = Yii::app()->db1->createCommand()
			->select("SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit")
			->from("sivexkuitti")
			->where("DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) = CURDATE() and status=3")
			->andwhere($tyoryhmat_criteria)
			->queryRow();

		$tehdyht = '00:00';
		if(isset($query['l_tunnit'])){
			$tehdyht = $this->sprint($query['l_tunnit']);
		}

                echo json_encode($tehdyht);
		exit;
	}

	public function actionToteututhismonth()
	{
		// <-- Tyoryhmat
		$tyoryhmat_criteria = '';
		if( isset(Yii::app()->user->TyoryhmatTyontekijatHelperArray) ){
			$impl = implode(",", Yii::app()->user->TyoryhmatTyontekijatHelperArray);
			$tyoryhmat_criteria = "tid IN ($impl)";
		}
		//    Tyoryhmat -->

		$month = date("Ym");
		$total_l = 0;
		$total_t = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";
        	$criteria->condition = "
			status='3'
			AND EXTRACT(YEAR_MONTH FROM DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')))  = '".$month."'
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND deleted=0
		";
		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);

		$lu = Mobile::model()->find($criteria);
		$total_l = $lu->l_tunnit;

		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";
        	$criteria->condition = "
			status='3'
			AND EXTRACT(YEAR_MONTH FROM DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')))  = '".$month."'
			AND deleted=0
		";
		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);

		$tot = Toteutuneet::model()->find($criteria);
		$total_t = $tot->l_tunnit;


		$result = $total_l+$total_t;
		if($result <= 0){
			$return = '00:00';
		} else {
			$return = $this->sprint($result);
		}

		echo json_encode($return);
		exit;
	}


	public function actionGetcityes()
	{
		// <-- Tyoryhmat
		$tyoryhmat_criteria = '';
		if( isset(Yii::app()->user->TyoryhmatTyontekijatHelperArray) ){
			$impl = implode(",", Yii::app()->user->TyoryhmatTyontekijatHelperArray);
			$tyoryhmat_criteria = "tid IN ($impl)";
		}
		//    Tyoryhmat -->

		$m1 = date("Y-m-d");
		$m2 = date("Y-m-d", strtotime($m1.'first day of this month -1 month'));
		$total_l = array();
		$toimipaikkaat = array();

       		$criteria = new CDbCriteria();
		$criteria->with=array('kohteet');
        	$criteria->select = " COUNT(*) as count, aloitan";
        	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))), kohteet.kaupunki ";
        	$criteria->condition = "
			status='3'
			AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))
			BETWEEN '".$m2."' AND '".$m1."'
			AND kohteet.kaupunki!=''
			AND t.id NOT IN(select kid from sivexkuitti_repaired)
			AND kohdenID!=0
		";
		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
			$toimipaikkaat[$l->kohteet->kaupunki][(int)date("m", strtotime($l->aloitan))] = array('kaupunki'=>$l->kohteet->kaupunki, 'count'=>$l->count);
		}

		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
		$criteria->with=array('kohteet');
        	$criteria->select = " COUNT(*) as count, aloitan";
        	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))), kohteet.kaupunki ";
        	$criteria->condition = "
			status='3'
			AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))
			BETWEEN '".$m2."' AND '".$m1."'
			AND kohteet.kaupunki!=''
			AND kohdenID!=0
		";

		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
			$toimipaikkaat[$l->kohteet->kaupunki][(int)date("m", strtotime($l->aloitan))] = array('kaupunki'=>$l->kohteet->kaupunki, 'count'=>$l->count);
		}


		echo json_encode(array('toimipaikkaat'=>$toimipaikkaat));
		exit;
	}

	public function tilatTanaan()
	{

		// <-- Tyoryhmat
		$tyoryhmat_criteria = '';
		if( isset(Yii::app()->user->TyoryhmatTyontekijatHelperArray) ){
			$impl = implode(",", Yii::app()->user->TyoryhmatTyontekijatHelperArray);
			$tyoryhmat_criteria = "tid IN ($impl)";
		}
		//    Tyoryhmat -->

		$total = array();
		$total[2] = 0;
		$total[3] = 0;
		$total[10] = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = " COUNT(*) as count,status";
        	$criteria->group = " status ";
        	$criteria->condition = "
			aloitan !='' and loppui !='' and (status=2 OR status=3 OR status=10)
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')  = CURDATE()
			AND t.id NOT IN(select kid from sivexkuitti_repaired)
		";

		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
		    $total[$l->status] += $l->count;
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = " COUNT(*) as count, status";
        	$criteria->group = " status ";
        	$criteria->condition = "
			aloitan !='' and loppui !='' and (status=2 OR status=3 OR status=10)
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')  = CURDATE()
		";

		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $total[$l->status] += $l->count;
		}


		return $total;
	}

	public function actionParassiivojatanaan()
	{

		// <-- Tyoryhmat
		$tyoryhmat_criteria = '';
		if( isset(Yii::app()->user->TyoryhmatTyontekijatHelperArray) ){
			$impl = implode(",", Yii::app()->user->TyoryhmatTyontekijatHelperArray);
			$tyoryhmat_criteria = "tid IN ($impl)";
		}
		//    Tyoryhmat -->

		$total_l = array();

       		$criteria = new CDbCriteria();
        	$criteria->select = " COUNT(*) as count,tid";
        	$criteria->order = " tekijan_nimi ";
        	$criteria->group = " tid ";
        	$criteria->condition = "
			DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))  = CURDATE()
			AND t.id NOT IN(select kid from sivexkuitti_repaired)
		";
		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
		    $total_l[] = array($this->etuSukunimi($l->tid),(int)$l->count);
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = " COUNT(*) as count,tid";
        	$criteria->order = " COUNT(*) LIMIT 4 ";
        	$criteria->group = " tid ";
        	$criteria->condition = "
			DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))  = CURDATE()


		";
		if( !empty($tyoryhmat_criteria) )
			$criteria->addCondition ($tyoryhmat_criteria);

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $total_l[] = array($this->etuSukunimi($l->tid),(int)$l->count);
		}


		echo json_encode($total_l);

	}


	protected function oikeudet($id,$sivu)
	{
		$return = '';


/*
 		$return .= CHtml::link("poista", '#', array(
		'submit'=>array('delete', "id"=>$id),
		'confirm' => 'Haluatko varmaasti poistaa?',
		'class'=>'btn btn-primary myBgColors'
		));


	     	$return .= '
		<script type="text/javascript">
		$(document).ready(function(){
		   $(":input").prop("disabled", true);
		});
		</script>';
*/


		echo $return;
	}


	public function checkOikeus($pyynto, $ajax=null)
	{

	   $return = '';
	   $asetukset = Asetukset::model()->findbypk(1);
	   $oikeudet = $asetukset->oikeudet;
	   if (!preg_match("/".$pyynto."/i", $oikeudet) and Yii::app()->user->username != 'admin' and Yii::app()->user->adminStatus != 1) {

	      	$return = '
		'.(($ajax==null)?'<link href="'.Yii::app()->request->baseUrl.'/css/bootstrap.min.css" rel="stylesheet" type="text/css">':'').'
		<br>
		<div class="col-sm-6 col-sm-offset-3">
		 <center>
		  <div class="alert bg-warning">
			<h2>Sinulla ei ole tarvittavia oikeuksia!</h2>
			<p>"admin" tunnuksella saa vaihda oikeuksia asetuksessa</p>
		  </div>
		 </center>
		</div>';

		echo $return;
		exit;
	   }

		echo $return;
	}



	public function checkOikeusFields($pyynto)
	{

	   $asetukset = Asetukset::model()->findbypk(1);
	   $oikeudet = $asetukset->oikeudet;
	   if (!preg_match("/".$pyynto."/i", $oikeudet) and Yii::app()->user->username != 'admin' and Yii::app()->user->adminStatus != 1) {
	   	$return = 0;
	   } else {
	   	$return = 1;
	   }

		return $return;
	}

	public function eiLasketa()
	{
		$return = "
		(tyoajanmerkinta NOT LIKE '%Ei lasketa%' AND tyoajanmerkinta NOT LIKE '%Varallaolo%' AND tyoajanmerkinta NOT LIKE '%Ehdollinen varallaolo%')
		";

		return $return;
	}

	public function eiLasketaSubStr($val)
	{

		$return = false;
		if (strpos($val, 'Ei lasketa') !== false or strpos($val, 'Varallaolo') !== false or strpos($val, 'Ehdollinen varallaolo') !== false) {
		    $return = true;
		}
		return $return;
	}

	public function moduliMuutos($m)
	{
		$return = "";
		$ex = explode(",", $m);
		foreach($ex as $e)
		{
			$t = Tasot::model()->find(" taso='".$e."' ");
			if(isset($t->id))
			$return .= '<b>'.$t->nimetys.':</b> '.$t->kuvaus."<br>";
		}

		echo $return;
	}


	public function netvisorYhteys()
	{

	   $return = array();
	   $a = Asetukset::model()->findbypk(1);
	   $fm = FirmanTiedot::model()->findbypk(1);

	   if($a->netvisor_kaytto == 1)
	   {
		if( Yii::app()->user->domain == 'demo' )
			$http = 'http';
		else
			$http = 'https';

		if(empty($a->netvisor_host))
		die('Netvisor HOST ei ole määritetty asetuksessa.');

		$url		= $http."://".$a->netvisor_host;
		$host 		= $a->netvisor_host;

		$sender 	= Yii::app()->user->domain; // $fm->tyonantaja
		$customerId	= $a->netvisor_customer_id;
		$partnerId	= $a->netvisor_partner_id;
		$timestamp	=  date("Y-m-d H:i:s");
		$language	= 'FI';
		$organisationIdentifier	= $a->netvisor_organisation_identifier;
		$transactionIdentifier	= rand(0,10000000);
		$userKey 	= $a->netvisor_userkey;
		$partnerKey	= $a->netvisor_partnerkey;

		$return = array($url,$host,$sender,$customerId,$partnerId,$timestamp,$language,$organisationIdentifier,$transactionIdentifier,$userKey, $partnerKey);

	   }

		return $return;

	}

	// <-- Autocomplete
	public function autocompleteFor($model, $sarake, $placeholder, $postvalue)
	{

		if(is_array($sarake))
		{
			$source_sarake = json_encode($sarake);
			$sarake = $sarake[0];
		} else {
			$source_sarake = $sarake;
		}

		$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
		    'name'=>$sarake,
		    'value'=> $postvalue,
		    'source'=>$this->createUrl('autocomplete', array('model'=>$model,'sarake'=>$source_sarake)),
		    'options'=>array(
		        'minLength'=>'2',
		    ),
		    'htmlOptions'=>array(
                        'showAnim'=>'fold',
			'class'=>'gui-input autocomplete_valikko',
		     	'placeholder'=> Yii::t('main', $placeholder),
		    ),
		));
	}

	public function actionAutocomplete($model, $sarake, $term)
	{

		$term = trim($term);
		$criteria = new CDBcriteria;

		if(is_array(json_decode($sarake, true)))
		{
			$sarake_nimi = json_decode($sarake, true)[0];
			$cond = '';
			$i = 0;
			foreach(json_decode($sarake, true) as $item)
			{
				if($i == 0)
					$cond .= $item." LIKE '%".$term."%'";
				else
					$cond .= " OR $item LIKE '%".$term."%'";

				$i++;
			}

			$criteria->condition = $cond;

		} else {
			$sarake_nimi = $sarake;
			$criteria->order = " $sarake ";
			$criteria->group = " $sarake ";
			$criteria->condition = " $sarake LIKE '%".$term."%' ";
		}

		// <-- Tyoryhmat
		if( $model == 'Asiakkaat' or $model == 'Kohteet' ){
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		}
		//    Tyoryhmat -->

		// <-- Tyoryhmat
		if( $model == 'Tyontekijat' ){
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
        		$criteria->addCondition (" id IN ($ids)");
		}
		}
		//    Tyoryhmat -->


		$m = $model::model()->findAll($criteria);

		$arr = array();
		foreach($m as $data)
		{

		    if($model == 'Asiakkaat' and $data->tyyppi == 'yritys' and $sarake != 'osoite' and $sarake != 'kaupunki' and $sarake != 'postinumero')
		    {
		    $arr[] = array(
		        'label'=>$data->yrityksen_nimi,
		        'value'=>$data->yrityksen_nimi,
		        'id'=>$data->id,
        	    );
		    } else if($model == 'Asiakkaat' and $data->tyyppi == 'henkilo' and $sarake != 'osoite' and $sarake != 'kaupunki' and $sarake != 'postinumero')
		    {
		    $arr[] = array(
		        'label'=>$data->Etusukunimi,
		        'value'=>$data->Etusukunimi,
		        'id'=>$data->id,
        	    );
		    } else if($model == 'Tyontekijat' and is_array(json_decode($sarake, true)))
		    {
		    $arr[] = array(
		        'label'=>$data->tekijan_nimi.' '.$data->sukunimi,
		        'value'=>$data->tekijan_nimi.' '.$data->sukunimi,
		        'id'=>$data->id,
        	    );
		    } else if($model == 'Domainit')
		    {
		    $arr[] = array(
		        'label'=>$data->yritys,
		        'value'=>$data->id,
		        'id'=>$data->id,
        	    );
		    } else {
		    $arr[] = array(
		        'label'=>$data->$sarake_nimi,
		        'value'=>$data->$sarake_nimi,
		        'id'=>$data->id,
        	    );
		    }
		}

		echo CJSON::encode($arr);
	}
	// Autocomplete -->


	public function check_user_agent ( $type = NULL ) {
	        $user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
	        if ( $type == 'bot' ) {
	                // matches popular bots
	                if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
	                        return true;
	                        // watchmouse|pingdom\.com are "uptime services"
	                }
	        } else if ( $type == 'browser' ) {
	                // matches core browser types
	                if ( preg_match ( "/mozilla\/|opera\//", $user_agent ) ) {
	                        return true;
	                }
	        } else if ( $type == 'mobile' ) {
	                // matches popular mobile devices that have small screens and/or touch inputs
	                // mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
	                // detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
	                if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
	                        // these are the most common
        	                return true;
        	        } else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
        	                // these are less common, and might not be worth checking
        	                return true;
        	        }
        	}
        	return false;
	}


	public function tyontekiatLista($name, $class, $id, $selectedArray, $aktiivinen=null, $tyoryhma=null)
	{
		$asetukset = Asetukset::model()->findByPk(1);
		$return = '';
		$criteria = new CDbCriteria();

		// <-- Return order etu ja sukunimella
		$criteria = $this->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

		if( $aktiivinen !== null ){
			$criteria->addCondition (" aktiivinen='".$aktiivinen."' ");
		}
		if( $tyoryhma !== null ){
	        	$criteria->addCondition (" REPLACE(REPLACE(tyoryhma,'\\\u00f6','ö'), '\\\u00e4', 'ä') LIKE '%".$tyoryhma."%' ");
		}

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
        		$criteria->addCondition (" id IN ($ids)");
		}
		//    Tyoryhmat -->

		if($class != null) $cl = ' class="'.$class.'" '; else $cl = '';
		if($id != null)	$i = ' id="'.$id.'" '; else $i = '';

		// <-- Order tyontekijat
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
			$tt_order_1 = "tekijan_nimi";
			$tt_order_2 = "sukunimi";
		} else {
			$tt_order_1 = "sukunimi";
			$tt_order_2 = "tekijan_nimi";
		}
		// Order tyontekijat -->

		$list = Tyontekijat::model()->findAll($criteria);
		$return .= '<select name="'.$name.'[]" '.$cl.' '.$i.' multiple title="Työntekijät">';
		foreach($list as $val){
		  if(isset($selectedArray) and in_array($val->id, $selectedArray))
		    $return .= '<option value="'.$val->id.'" selected>'.$val->$tt_order_1.' '.$val->$tt_order_2.'</option>';
		  else
		    $return .= '<option value="'.$val->id.'">'.$val->$tt_order_1.' '.$val->$tt_order_2.'</option>';
		}
		$return .= '</select>';


		return $return;
	}


	public function tyontekiatListaNoMulti($name, $class, $id, $selected, $aktiivinen)
	{
		$return = '';
		$asetukset = Asetukset::model()->findByPk(1);

		$criteria = new CDbCriteria();

		// <-- Return order etu ja sukunimella
		$criteria = $this->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

		if($aktiivinen == 1)
			$criteria->condition = " aktiivinen=1 ";

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
        		$criteria->addCondition (" id IN ($ids)");
		}
		//    Tyoryhmat -->


		if($name != null) 	$nm = ' name="'.$name.'" '; else $nm = '';
		if($class != null) 	$cl = ' class="'.$class.'" '; else $cl = '';
		if($id != null)		$i = ' id="'.$id.'" '; else $i = '';

		$list = Tyontekijat::model()->findAll($criteria);
		$return .= '<select '.$nm.' '.$cl.' '.$i.' title="Työntekijät">';

			if(empty($selected)){
				$return .= '<option value="kaikki">'.Yii::t('main', 'Työntekijät').'</option>';
			} else {
				$return .= '<option value="kaikki">'.Yii::t('main', 'Kaikki').'</option>';
			}

		foreach($list as $val){
			if(!empty($selected) and $val->id == $selected){
				$return .= '<option value="'.$val->id.'" selected>'.$this->etuSukunimi($val->id).'</option>';
			} else {
				$return .= '<option value="'.$val->id.'">'.$this->etuSukunimi($val->id).'</option>';
			}
		}
		$return .= '</select>';

		return $return;
	}


	public function tyontekiatArrayList($aktiivinen)
	{
		$list = array();

		$asetukset = Asetukset::model()->findByPk(1);
		$criteria = new CDbCriteria();
		// <-- Return order etu ja sukunimella
		$criteria = $this->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->
		$criteria->condition = " aktiivinen=1 ";

		$tt = Tyontekijat::model()->findAll($criteria);
		foreach($tt as $t)
		$list[$t->id] = $this->etuSukunimi($t->id);

		return $list;
	}


	public function etuSukunimi($tid) // $this->etuSukunimi($model->id)
	{
		$return = '';
		$t = Tyontekijat::model()->findByPk($tid);
		if(isset($t->id))
		{
			$asetukset = Asetukset::model()->findByPk(1);
			if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
				$return .= $t->tekijan_nimi;
				if(!empty($t->sukunimi))
					$return .= ' '.$t->sukunimi;
			} else {
				if(!empty($t->sukunimi))
					$return .= $t->sukunimi.' ';

				$return .= $t->tekijan_nimi;
			}
		}

		return $return;
	}

	public function etuSukunimiCriteria($criteria) // $this->etuSukunimi($model->id)
	{

		$asetukset = Asetukset::model()->findByPk(1);
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0)
		$criteria->order = " tekijan_nimi ";
		else
		$criteria->order = " sukunimi ";

		return $criteria;
	}

	public function daysBetween($d1, $d2)
	{
		$date1 = new DateTime($d1);
		$date2 = new DateTime($d2);

		return $date2->diff($date1)->format("%a");
	}

	public function aakkoset($string)
	{
		 $string = str_replace("ä", "a", $string);
		 $string = str_replace("ö", "o", $string);
		 $string = str_replace("Ä", "A", $string);
		 $string = str_replace("Ö", "O", $string);
		 $string = str_replace("´", "", $string);
		 $string = str_replace(" ", "_", $string);
		 $string = str_replace("/", "_", $string);
		 return $string;
	}


	// <-- Tiedoston nimi
	public function tiedostonNimiAsiakasKohdeAika($tyyppi, $asiakas_id, $kohde_id, $time)
	{
		$a = Asiakkaat::model()->findbypk($asiakas_id);
		$k = Kohteet::model()->findbypk($kohde_id);
  		$tiedosto = $tyyppi;

			if(isset($a->id))
				$tiedosto .= '_'.$a->Fullname;
			if(isset($k->osoite))
				$tiedosto .= '_'.$k->osoite;

			$tiedosto .= '_'.date("dmY_H_i", strtotime($time));

		$tiedosto = $this->aakkoset($tiedosto);
		//echo $tiedosto;
		//exit;

		return $tiedosto;
	}
	//     Tiedoston nimi -->

	public function initPostLoger($model_name, $log_nimike, $tilanne, $old_values, $new_values)
	{
		$log=new Log;

		if($model_name == 'Mob' and is_array(json_decode($new_values, true)))
		{
			$kuka = json_decode($new_values, true);
			if(isset($kuka['tekijan_nimi']))
			$log->kuka = $kuka['tekijan_nimi'];
		} elseif(isset(Yii::app()->user->nimi)) {
			$log->kuka = Yii::app()->user->nimi;
		}

		$log->log_category 	= 2;
		$log->log_nimike	= $log_nimike;
		$log->model		= $model_name;
		$log->tilanne		= $tilanne;
		$log->old_values	= $old_values;
		$log->new_values	= $new_values;
		$log->save();
	}

	public function logoShower($height)
	{

		$logo 	= Yii::app()->user->domain;
		$asetukset = Asetukset::model()->findbypk(1);
		if( $height === null )
			$korkeus = $asetukset->logon_korkeus;
		else
			$korkeus = $height;

		if (@getimagesize($asetukset->logon_polkku)) {
			$logo 	= $asetukset->logon_polkku;
			return '<img src="'.$logo.'" style="height:'.$korkeus.'px;">';
		}

		$polkku = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain;
		$polkku2 = 'tiedostot/firma/'.Yii::app()->user->domain;
		if (file_exists($polkku.'/systemlogo.jpg')) {
			$image = $polkku2.'/systemlogo.jpg';
			$imageData = base64_encode(file_get_contents($image));
			$logo = 'data: '.mime_content_type($image).';base64,'.$imageData;
			return '<img src="'.$logo.'" style="height:'.$korkeus.'px;">';
		}
		if (file_exists($polkku.'/systemlogo.png')) {
			$image = $polkku2.'/systemlogo.png';
			$imageData = base64_encode(file_get_contents($image));
			$logo = 'data: '.mime_content_type($image).';base64,'.$imageData;
			return '<img src="'.$logo.'" style="height:'.$korkeus.'px;">';
		}

		return $logo;
	}

	public function TyoryhmatHelper()
	{
		if( !$this->isEtuntiAdmin() ){ return false; }
		$arr = array();
		$asetukset = Asetukset::model()->findbypk(1);
		if( isset($asetukset->tyoryhmat) and $asetukset->tyoryhmat == 0 ){ return $arr; }
		if( isset($asetukset->tyoryhmat_kohde) and $asetukset->tyoryhmat_kohde == 0 ){ return $arr; }

		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		if( $this->checkOikeusFields($checkOikeus) == 0 ){
			$criteria = new CDbCriteria();
			$criteria->condition = "
				select_type='tyoryhma'
				AND value2 LIKE '%\"".Yii::app()->user->adminID."\"%'
			";

			$listData = Valikkoot::model()->findAll($criteria);
			foreach($listData as $item){
				$arr[$item->id] = $item->id;
			}
		}
	   	return $arr;
	}

	public function checkEdicoViestit()
	{
		if( !$this->isEtuntiAdmin() ){ return false; }

		$criteria = new CDbCriteria();
		$criteria->order = " id DESC";
		$criteria->condition = "
			status=1
		";
		$listData = EdicoViestinta::model()->findAll($criteria);
		foreach($listData as $item){
		    if( count($item->rivit) > 0 and isset(max($item->rivit)->luoja) and max($item->rivit)->luoja == 'asiakas'){
			//echo max($item->rivit)->luoja;
		   	return true;
		    }
		}
		return false;
	}

	protected function clean($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

	   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}

	protected function ylittaneetMyohastyneet()
	{
		// <-- ylittaneet
		$ylittaneet = '';
		$tv = Yii::app()->db1->createCommand()
			->select("kohde,tid,alku,loppu")
			->from("sivex_tvuoro")
			->where("DATE(STR_TO_DATE(pvm, '%d.%m.%Y'))=CURDATE() AND ilmoitus_avoimista_kohteesta=1")
			->queryAll();

		foreach($tv as $dat)
		{
			$k = Kohteet::model()->findbypk($dat['kohde']);
			$t = Tyontekijat::model()->findbypk($dat['tid']);
			if(isset($t->id) and isset($k->id))
			{

			$criteria=new CDbCriteria;
			$criteria->condition = " 
				kohdenID='".$k->id."' AND tid='".$t->id."'
				AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) = CURDATE()
				AND (status=1 OR status=3)
			";
			$mob = Mobile::model()->find($criteria);

			$tilanne = '';
			if( isset($mob->id) and $mob->status == 1 )
			$tilanne = '<span class="text-danger">'.Yii::t('main', 'Avoin').'</span>';
			elseif( isset($mob->id) and $mob->status == 3 )
			$tilanne = '<span class="text-success">'.Yii::t('main', 'Lopetettu klo:').' '.date("H:i", strtotime($mob->loppui)).'</span>';

			$ylittaneet .= '<tr><td><span class=""></span> '.$this->etuSukunimi($t->id).'<br>'.$k->osoite.'</td><td>'.$dat['alku'].'-'.$dat['loppu'].'<br>'.$tilanne.'</td></tr>';

			}

		}
		// ylittaneet -->

		// <-- myohastyneet
		$myohastyneet = '';
		$tv = Yii::app()->db1->createCommand()
			->select("kohde,tid,alku,loppu")
			->from("sivex_tvuoro")
			->where("DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) = CURDATE() AND ilmoitus_myohastyneista_kohteesta=1")
			->queryAll();

		foreach($tv as $dat)
		{
			$k = Kohteet::model()->findbypk($dat['kohde']);
			$t = Tyontekijat::model()->findbypk($dat['tid']);
			if(isset($t->id) and isset($k->id))
			{
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				kohdenID='".$k->id."' AND tid='".$t->id."'
				AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) = CURDATE()
			";
			$mob = Mobile::model()->find($criteria);

			$tilanne = '';
			if( !isset($mob->id) )
			$tilanne = '<span class="text-danger">'.Yii::t('main', 'Myöhässä:').' '.$this->sprint(time()-strtotime($dat['alku'])).'</span>';
	
			$myohastyneet .= '<tr><td><span class=""></span> '.$this->etuSukunimi($t->id).'<br>'.$k->osoite.'</td><td>'.$dat['alku'].'-'.$dat['loppu'].'<br>'.$tilanne.'</td></tr>';
			}
		}
		// myohastyneet -->

		return array($ylittaneet,$myohastyneet);
	}

	public function dbhost(){ // tama pitaa poista, etsi missa se kaytetaan
		$is_local = in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1']);
		$db_host = ($is_local)?'localhost':'localhost';
		return $db_host;
	}

	public function dbConnectArr(){
		$host = 'localhost';
		$connection = explode(";", Yii::app()->db->connectionString);
		if(isset($connection[0]))
			$hoststring = explode("=", $connection[0]);
				if(isset($hoststring[1]))
					$host = $hoststring[1];
	
		$return = ['host'=>$host, 'username'=>Yii::app()->db->username, 'password'=>Yii::app()->db->password];
		return $return;
	}

	/**
	 * This action is used to generate a list from last month
	 * (timeframe subject to change)
	 * from those clients that have been billed
	 */
	public function actionSpendingclients() {
		$this->render("spending_clients");
	}
}

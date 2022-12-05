<?php
	header("Access-Control-Allow-Origin: *");



//echo $_SERVER['HTTP_X_USERNAME'];
//var_dump($_GET);

class DicoController extends Controller
{
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    Const APPLICATION_ID = 'ASCCPE';
	/**
	 * These domains will receive an error when
	 * using eDico
	 */
	private $disallowedDomains = ["sivex"];
 
    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    /**
     * @return array action filters
     */
    public function filters()
    {
            return array();
    }
 




public function actionLogin($domain)
{

    switch($_GET['model'])
    {
        case 'asiakkaat':

		$return	= array();
		if($this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				sahkoposti='".$_POST['tunnus']."' 
				AND token=''
			";
			$model=Asiakkaat::model()->find($criteria);

			$l = $this->loginChecker($model, $_POST['salasana']);
			if($l['login'] == true)
			{
				$_POST['salasana'] = $l['new_salasana'];

				$asetukset=Asetukset::model()->findByPk(1);
				$asiakasNimi = $model->Fullname;

				$domainit=Domainit::model()->find(" domain = '".$domain."' ");
				(isset($domainit->paketti))? $paketti = $domainit->paketti: $paketti = '';

				$return['loginOK'] = array(
					'asiakasID'=>$model->id, 
					'asiakasNimi'=>$asiakasNimi, 
					$_POST, 
					'paketti'=>$paketti,
					'edico_tehdyt_tyot'=>$asetukset->edico_tehdyt_tyot,
				);
			}
		}

		$this->_sendResponse(200, CJSON::encode($return));
		exit;

            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }

}


	protected function kirjautuminen($domain, $tunnus, $salasana)
	{
		if(in_array($domain, $this->disallowedDomains)) {
			$this->_sendResponse(403, CJSON::encode("eDico on suljettu"));
			return false;
		}
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			sahkoposti='".$tunnus."' 
			AND token=''
		";
		$model=Asiakkaat::model()->find($criteria);

		$l = $this->loginChecker($model, $salasana);
		if($l['login'] == true)
		{
			Yii::app()->user->setState('domain', $domain);
			Yii::app()->user->setState('asiakas', $model->id);
			return true;
		} else {
			$this->_sendResponse(200, CJSON::encode('login_error'));
			exit;
			return false;
		}

	}

	protected function loginChecker($model, $salasana)
	{

		$login = false;
		$new_salasana = '';

		if (isset($model->id) and strlen($salasana) == 60 and $salasana == $model->salasana){
			$login = true;
			$new_salasana = $salasana;
		} elseif(isset($model->id) and strlen($salasana) != 60 and password_verify($salasana, $model->salasana)){
			$login = true;
			$new_salasana = $model->salasana;
		}

		return array('login' => $login, 'new_salasana' => $new_salasana);
	}

	public function actionRecovery($domain)
	{

		$return = array();

		if(isset($_POST['sahkoposti']))
		{
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				sahkoposti='".$_POST['sahkoposti']."' 
			";
			$a=Asiakkaat::model()->find($criteria);
		
			if(isset($a->id))
			{
				Yii::app()->user->setState('domain', $domain);
				Yii::app()->user->setState('asiakas', $a->id);
				$asiakkaat = Yii::app()->createController('Asiakkaat');
				$asiakkaat[0]->LahetaTunnukset($a->id);

				$return['ok'] = 'Asiakas: '.$a->id;
				$this->_sendResponse(200, CJSON::encode($return));
			}
		}

		$return['error'] = 'Error';
		$this->_sendResponse(200, CJSON::encode($return));
		exit;
	}


	public function actionCheck($domain)
	{

		$return = [];
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{
		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
				AND lahettaja='admin'
				AND asiakas_luettu=0
				AND teksti NOT LIKE '%sisainen%'
			";
			$pal=Palautteet::model()->find($criteria);
			if(isset($pal->id))
			{
				$return['uusi_palaute'] = $pal->keskustelu_id;
			}

			$criteria=new CDbCriteria;
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
				AND status=1
			";
			$tar=CrmTarjoukset::model()->find($criteria);
			if(isset($tar->id))
			{
				$f = "tiedostot/tarjoukset/".$domain."/".$tar->liite.".pdf";
   				if(file_exists(Yii::app()->basePath."/../".$f))
   				{
					$return['uusi_tarjous'] = $tar->id;
				}
			}
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
				AND status=1
			";
			$sop=CrmSopimukset::model()->find($criteria);
			if(isset($sop->id))
			{
				$return['uusi_sopimus'] = $sop->id;
			}
			if( $this->checkEdicoViestit($model->id) )
			{
				$return['uusi_viesti'] = $this->checkEdicoViestit($model->id);
			}
			$this->_sendResponse(200, CJSON::encode($return));
			exit;
		   }
		}
		
		$this->_sendResponse(200, CJSON::encode($return));
		exit;
	}

	public function checkEdicoViestit($asiakas_id)
	{
		$criteria = new CDbCriteria();
		$criteria->order = " id DESC";
		$criteria->condition = "
			asiakas_id='".$asiakas_id."'
			AND katsottu=0
		";
		$listData = EdicoViestintaRivit::model()->find($criteria);
		if( isset($listData->id) ){ return $listData->id; }
		return false;
	}

	protected function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
	{
	    $datetime1 = date_create($date_1);
	    $datetime2 = date_create($date_2);
	    
	    $interval = date_diff($datetime1, $datetime2);
	    
	    return $interval->format($differenceFormat);
	    
	}


	public function actionKohteet($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			// <-- submit form
			if(isset($_POST['Kohteet']))
			{

				$mk = Kohteet::model()->findByPk($_POST['id']);
				$mk->attributes=$_POST['Kohteet'];
				if($mk->save())
				{
					$this->_sendResponse(200, CJSON::encode(array('kohteet_lista'=>'tallennettu')));
					exit;
				}
			}
			//     submit form -->

			// <-- showlist
			if(isset($_POST['showlist']))
			{
				$criteria=new CDbCriteria;
				$criteria->condition = " 
					aktiivinen='1' 
					AND asiakas_id='".$model->id."'
				";
				if(isset($_POST['id']))
				$criteria->addCondition(" id='".$_POST['id']."' "); 

				$k=Kohteet::model()->findAll($criteria);
				$kohteet_lista = '';

				if(!isset($_POST['id']))
				$kohteet_lista .= '<legend><h1>'.Yii::t('main', 'Omat kohteet').'</h1></legend>';

				if(isset($k[0]))
				{
				   $kohteet_lista .= '<div class="row mb10">';
				   foreach($k as $v)
				   {
					// kohteet.html?id='.$v->id.'
					$kohteet_lista .= '
				          <div class="col-md-12" id="vinkit_painike">
					   <a href="#" class="link">
				            <div class="panel bg-info light of-h mb10">
				              <div class="pn pl20 p5">
				                <div class="icon-bg">
				                  <i class="fa fa-home"></i>
				                </div>
				                <h2 class="mt15 lh15">
				                  <b>'.$v->osoite.'</b>
				                </h2>
				                <h5 class="text-muted">'.$v->kaupunki.' '.$v->pnumero.'</h5>
				              </div>
				            </div>
					   </a>
				          </div>
					';
				   }
				   $kohteet_lista .= '</div>';


				   if(isset($_POST['id']))
				   {
					$kohteet_lista .= $this->renderPartial('//kohteet/form_asiakas', array('model'=>Kohteet::model()->findByPk($_POST['id'])), true);
				   }

				}


				$this->_sendResponse(200, CJSON::encode(array('kohteet_lista'=>$kohteet_lista)));
				exit;
			}
			//     showlist -->

		   }

		}


	}

	public function actionOmat($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model = Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {
				Yii::app()->theme = 'etunti';
				$return = $this->renderPartial('/asiakkaat/view_edico', array('model' => $model), true);
				// <-- tiedosto
				$path = 'tmp/'.$domain;
				$tiedosto = 'asiakas_tiedot_xls_'.$model->id;
				if (file_exists( Yii::app()->basePath.'/../'.$path.'/'.$tiedosto.'.xls' ) 
					and isset($_POST['asiakasID']) and isset($_POST['getExcel'])) 
				{
					$link = Yii::app()->request->hostInfo .'/'.$path.'/'.$tiedosto.'.xls';
					$filename = basename($link);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$this->_sendResponse(200, CJSON::encode(array('link'=>$link, 'filename'=>$filename, 'ext'=>$ext)));
					exit;
				}

				$tiedosto = 'asiakas_tiedot_pdf_'.$model->id;
				if (file_exists( Yii::app()->basePath.'/../'.$path.'/'.$tiedosto.'.pdf' ) 
					and isset($_POST['asiakasID']) and isset($_POST['getPDF'])) 
				{
					$link = Yii::app()->request->hostInfo .'/'.$path.'/'.$tiedosto.'.pdf';
					$filename = basename($link);
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$this->_sendResponse(200, CJSON::encode(array('link'=>$link, 'filename'=>$filename, 'ext'=>$ext)));
					exit;
				}
				//     tiedosto -->
				$this->_sendResponse(200, CJSON::encode(array('content'=>$return)));
		   }

		}

				exit;
	}

	public function actionTilaus($domain)
	{

		$return = '';
		$alennuskoodit = '';
		$tp_kontenti = '';
		$kohteet = '';
		$viesti = '';
		$aikaa = '';

		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model = Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id) and is_array(json_decode($model->alennuskoodit, true)))
		   {
			$ak_arr = json_decode($model->alennuskoodit, true);
			$alennuskoodit .= '<option value=>Valitse alennuskoodi</option>';
			foreach($ak_arr as $k => $v)
			{
				$ak = Kupongit::model()->findByPk($k);
				if(isset($ak->id) 
					and ( 
						( $ak->jatkuva == 1 and date("Ymd", strtotime($ak->voimassa)) >= date("Ymd") )
						or ( $ak->jatkuva == 0 and $ak->status == 0 and date("Ymd", strtotime($ak->voimassa)) >= date("Ymd") )
					) 
				){
					$alennuskoodit .= '<option value="'.$ak->kupongin_id.'">'.$ak->kupongin_id.'</option>';
				}
			}
		   }

		   if(isset($model->id))
		   {

			if(isset($_POST['Tilaus']))
			{

				//$this->_sendResponse(200, CJSON::encode($_POST['Tilaus']));
				//exit;

				$tilaus = new EdicoTilaukset;
				$tilaus->attributes = $_POST['Tilaus'];
				$kohteet = Kohteet::model()->findByPk($tilaus->kohde_id);

				$tilaus->asiakas_id = $model->id;
				$tilaus->osoite = $kohteet->osoite;
				$tilaus->postinumero = $kohteet->pnumero;
				$tilaus->postitoimipaikka = $kohteet->kaupunki;
				$tilaus->asiakas_puhelinnumero = $model->puhelin;

				if(!$tilaus->save()){
				$this->_sendResponse(200, CJSON::encode(array(
					'lahetyksen_error' => json_encode($tilaus->getErrors())
				)));
				exit;
				} else {
				$this->_sendResponse(200, CJSON::encode(array(
					'lahetyksen_tulos' => 'ok',
					'kiitos_lause' => '<div class="alert alert-success">Kiitos tilauksesta.</div>'
				)));
				}
				exit;
			}


			// <-- Tuotteet Palvelut
			if(isset($_POST['kohdeID']))
			{

			$tp_kontenti = '';
			$criteria = new CDbCriteria();
	       		$criteria->condition = " aktiivinen=1 AND hinta_alv_0!=0 AND kategoria LIKE '%eDico%' ";
			$tuoteet = TuotteetPalvelut::model()->findAll($criteria);
			
			$kohteet = Kohteet::model()->findByPk($_POST['kohdeID']);


			$tp_kontenti .= '<table class="table table-bordered table-striped">';
			foreach($tuoteet as $item)
			{

			  // <-- Check hinnasto By Kohde
			  $hinnasto_id = 0;
			  if(isset($kohteet->id) and $kohteet->hinnasto_id != 0)
			  {
				$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$item->id."' AND hinnastot_id='".$kohteet->hinnasto_id."' ");
				if(isset($hinnasto->id))
				{
					$hinnasto_id = $hinnasto->id;
					$item->yksikko = $hinnasto->hinnasto_yksikko;
					$item->hinta_alv_sis = $hinnasto->hinnasto_yht;
				}
			  }
			  //     Check hinnasto By Kohde -->
	

			$tp_kontenti .= '<tr class="tr_rivi" tuote_id="'.$item->id.'" hinnasto_id="'.$hinnasto_id.'">';
			$tp_kontenti .= '<td><span class="nimike">'.$item->nimike.'</span></td>';
			$tp_kontenti .= '<td><span class="hinta_alv_sis" hinta="'.$item->hinta_alv_sis.'">'.number_format($item->hinta_alv_sis, 2, ',', ' ').'</span> &euro;</td>';
			$tp_kontenti .= '<td><span class="yksikko">'.$item->yksikko.'</span></td>';
			$tp_kontenti .= '<td width="1"><button class="btn btn-default valiko"><i class="fa fa-2x" aria-hidden="true" style="width:25px;height:21px"></i></button></td>';
			$tp_kontenti .= '</tr>';
			}
			$tp_kontenti .= '</table>';
			} // kohdeID
			//     Tuotteet Palvelut -->


			// <-- Kohteet
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				aktiivinen='1' 
				AND asiakas_id='".$model->id."'
			";
			$k = Kohteet::model()->findAll($criteria);
			$kohteet = CHtml::dropDownList('Tilaus[kohde_id]', '', CHtml::listData($k, 'id', 'osoite'), 
			array('empty'=>'Valitse kohde', 'class'=>'form-control input-lg'));
			//     Kohteet -->

			// <-- Viesti kenta
			$viesti .= '<label>'.Yii::t('main', 'Viesti').'</label>';
			$viesti .= CHtml::textarea('Tilaus[viesti]', '', array('rows' => 6, 'class'=>'form-control', 'placeholder' => 'Viesti...'));
			//     Viesti kenta -->

			// <-- Aikaa kenta
			$aikaa .= '<label>'.Yii::t('main', 'Toivottu päivämäärä').'</label>';
			$aikaa .= CHtml::dateField('Tilaus[toivottu_pvm]', '', array('class'=>'form-control input-lg'));

			$aikaa .= '<div class="row">';
			$aikaa .= '<div class="col-xs-6">';
			$aikaa .= '<label>'.Yii::t('main', 'Toivottu aloitus aikaa').'</label>';
			$aikaa .= CHtml::textField('Tilaus[toivottu_aloitus]', '09:00', array('class'=>'form-control input-lg', 'placeholder' => 'Esim. 09:00'));
			$aikaa .= '</div><div class="col-xs-6">';
			$aikaa .= '<label>'.Yii::t('main', 'Toivottu lopetus aikaa').'</label>';
			$aikaa .= CHtml::textField('Tilaus[toivottu_lopetus]', '12:00', array('class'=>'form-control input-lg', 'placeholder' => 'Esim. 12:00'));
			$aikaa .= '</div></div>';
			//     Aikaa kenta -->
		   }


			$this->_sendResponse(200, CJSON::encode(array(
				'alennuskoodit' => $alennuskoodit,
				'tp_kontenti' => $tp_kontenti,
				'kohteet' => $kohteet,
				'viesti' => $viesti,
				'aikaa' => $aikaa,
			)));

		}

				exit;
	}

	public function actionInfo($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model = Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   $afa = AsetuksetForAll::model()->findByPk(1);
		   if(isset($model->id) and !empty($afa->app_info_sivu))
		   {
				$return = str_replace("\n", "<br>", $afa->app_info_sivu);
				$this->_sendResponse(200, CJSON::encode(array('content'=>$return)));
				exit;

		   }

		}


	}

	public function actionKayttoehdot($domain)
	{

		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{
		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);

		   // <-- Hyvaksyn
		   if(isset($_POST['hyvaksyn']) and isset($model->id))
		   {

				Asiakkaat::model()->updateByPk($model->id, array('app_kayttoehdot'=>1));
				$this->_sendResponse(200, CJSON::encode(array('hyvaksytty'=>true)));
				exit;
		   }
		   //     Hyvaksyn -->

		   if(isset($_POST['getkayttoehdot']) and isset($model->id))
		   {

				$path = Yii::app()->basePath."/../tiedostot/firma/".$domain.'/eDico_kayttoehdot.html';

				$return = '<legend><h1>Käyttöehdot</h1></legend>';
				if (file_exists($path)) {
		  			$html_content = file_get_contents($path);
					$return .= $html_content;
				}
				$return .= '<br><br><p><input type="checkbox" id="hyvaksyn_kayttoehdot"> <b>Hyväksyn käyttöehdot</b></p>';

				$this->_sendResponse(200, CJSON::encode(array('ok'=>$return)));
				exit;
		   }


		   if(isset($model->id) and $model->app_kayttoehdot == 1)
		   {
				$this->_sendResponse(200, CJSON::encode(array('kayttoehdot'=>'ok')));
		   } else {
				$this->_sendResponse(200, CJSON::encode(array('kayttoehdot'=>'error')));
		   }
		}

		exit;
	}


	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}


	public function actionHistoria($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

				// <-- Peruuttaa tyovuoroa
				if(isset($_POST['peruuttaa_tyovuoroa'])){

					$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
					$get_id 	= $tyovuorot[0]->this_id($_POST['id']);
					$tv 		= $get_id['model'];
					$toistuva 	= $get_id['toistuva'];
					$pvm 		= $get_id['pvm'];
					$tid 		= $get_id['tid'];


					if( isset($tv->id) )
					{
						$k = Kohteet::model()->findByPk($tv->kohde);
						$peruutettu = 0;
						$r = $this->dateDifference(date("Y-m-d", strtotime($pvm)), date("Y-m-d") );
						$asetukset=Asetukset::model()->findByPk(1);
						if( $asetukset->peruutta_paiva_ennen > 0 and $r > $asetukset->peruutta_paiva_ennen )
						{
							$peruutettu = 1;
							if($toistuva){
								$tilanne 	= ['peruutettu' => $peruutettu];
								$poisto_by	= 'ByEDICOPeruutettu';
								$tyovuorot[0]->VirtualtoTV($tv->id, $tid, $pvm, $tilanne, $poisto_by);
							} else {
								Tyovuoroot::model()->updateByPk($tv->id, array('peruutettu'=>$peruutettu));
							}

						} else {
							$peruutettu = 2;
							if($toistuva){
								$tilanne 	= ['peruutettu' => $peruutettu];
								$poisto_by	= 'ByEDICOPeruutettu';
								$tyovuorot[0]->VirtualtoTV($tv->id, $tid, $pvm, $tilanne, $poisto_by);
							} else {
								Tyovuoroot::model()->updateByPk($tv->id, array('peruutettu'=>$peruutettu));
							}
						}



							// <-- Sahkoposti lahetys
							$firma = FirmanTiedot::model()->findbypk(1);
							$get_css = file_get_contents(Yii::app()->request->baseUrl.'css/email_send_table.css');

							$asiakas = $model->Fullname;
	
							$message = '<html xmlns="http://www.w3.org/1999/xhtml">
							<head>
							    <title></title>
							    <style type="text/css">'.$get_css.'</style>
							</head>
							<body>';

							$message .= '<br>Hei, <p>Tilauksesi on peruutettu.</p>';
							$message .= '<p><b>'.$k->osoite.'</b>, '.$pvm.' '.$tv->alku.'-'.$tv->loppu.'</p>';
							if($peruutettu == 2)
							{
								$message .= '<h3>Peruutusehdot</h3>';
								$message .= '<p>'.$asetukset->peruutusehdot.'</p>';
							}
							$message .= '
							</body>
							</html>';


							$subject='=?UTF-8?B?'.base64_encode("Työvuoro peruutettu").'?=';
							$headers="From: ".$asiakas." <no-reply@etunti.fi>\r\n".
								"Reply-To: no-reply@etunti.fi\r\n".
								"MIME-Version: 1.0\r\n".
								"Content-type: text/html; charset=UTF-8";

							if($peruutettu != 0){
								mail($model->sahkoposti,$subject,$message,$headers);
								if(isset($firma->sahkoposti) and !empty($firma->sahkoposti))
									mail($firma->sahkoposti,$subject,$message,$headers);
							}
							//     Sahkoposti lahetys -->

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $model->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

						$return = array('OK'=>$_POST['id'], 'pvm diff'=>$r, 'peruutettu'=>$peruutettu);
					} else {
						$return = array('Error'=>$_POST['id']);
					}
					$this->_sendResponse(200, CJSON::encode($return));
					exit;

				}
				//     Peruuttaa tyovuoroa -->


				// <-- naytaAlennuskoodit
				if(isset($_POST['tyyppi']) and $_POST['tyyppi'] == 'naytaAlennuskoodit')
				{

				}
				//     naytaAlennuskoodit -->

				// <-- naytaVinkit
				if(isset($_POST['tyyppi']) and $_POST['tyyppi'] == 'naytaVinkit')
				{
			                Yii::app()->theme = 'customer';
					$mod = new VinkkiExtranet;
					if(isset($_POST['VinkkiExtranet']))
					{

						$token = $this->generateRandomString($length = 40);
						$mod->attributes=$_POST['VinkkiExtranet'];
						$mod->token=$token;

						if($mod->save())
						{

							// <-- Sahkoposti lahetys
							$firma = FirmanTiedot::model()->findbypk(1);
							$get_css = file_get_contents(Yii::app()->request->baseUrl.'css/email_send_table.css');

		$asiakas = $model->Fullname;

		$message = '<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		    <title></title>
		    <style type="text/css">'.$get_css.'</style>
		</head>
		<body>';

		$message .= '<br>
'.$asiakas.' on antanut meille vinkin, että voisit olla kiinnostunut yrityksemme '.$firma->tyonantaja.' palveluista. Mikäli hyväksyt tämän viestin, annat yritykselle '.$firma->tyonantaja.' oikeuden nähdä syötetyt henkilötiedot ja annat luvan yhteydenottoon. Yritys '.$firma->tyonantaja.' poistaa tietosi järjestelmästä, mikäli asiakkuutta ei synny 30 päivän sisällä, siitä kun olet hyväksynyt tämän viestin. Mikäli et hyväksy tietojen näyttämistä, tiedot poistetaan välittömästi. Tämä viesti vanhenee 14 päivän kuluessa.';


		$message .= '<br>
		<center>
		<div id="outer">
		<a class="hyvaksy_button inner" href="http://'.$_SERVER['SERVER_NAME'].'/index.php/vinkkiExtranet/vastaus?domain='.$domain.'&asia=1&id='.$mod->id.'&token='.$token.'">
				<h2>'.Yii::t('main', 'Hyväksy').'</h2>
		</a>
		<a class="hylkaa_button inner" href="http://'.$_SERVER['SERVER_NAME'].'/index.php/vinkkiExtranet/vastaus?domain='.$domain.'&asia=0&id='.$mod->id.'&token='.$token.'">
				<h2>'.Yii::t('main', 'Hylkää').'</h2>
		</a>
		</div>
		</center>
		';
		$message .= '
		</center>
		 <p style="font-size:80%">Tämä on EU:n tietosuoja-asetuksen mukainen ilmoitus.</p>
		</body>
		</html>';


							$subject='=?UTF-8?B?'.base64_encode($asiakas ." on antanut meille vinkin").'?=';
							$headers="From: ".$asiakas." <no-reply@etunti.fi>\r\n".
								"Reply-To: no-reply@etunti.fi\r\n".
								"MIME-Version: 1.0\r\n".
								"Content-type: text/html; charset=UTF-8";

							if(mail($mod->sahkoposti,$subject,$message,$headers))
							{
							//     Sahkoposti lahetys -->

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $mod->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

							$this->_sendResponse(200, CJSON::encode(array('OK'=>Yii::t('main', 'Vinkki lähetetty.'))));
							exit;
							} else {
							$this->_sendResponse(200, CJSON::encode(array('Error'=>Yii::t('main', 'Ei onnistunut lähetä.'))));
							exit;
							}
						} else {
							$this->_sendResponse(200, CJSON::encode(array('Error'=>$mod->getErrors())));
							exit;
						}

					} else {
						$return .= $this->renderPartial('//vinkkiExtranet/_form', array('model'=>$mod), true);
					}
				}
				//  naytaVinkit -->

				// <-- naytaPalautteet
				if(isset($_POST['tyyppi']) and $_POST['tyyppi'] == 'naytaPalautteet')
				{
			                Yii::app()->theme = 'customer';
					$mod = new Palautteet;
					if(isset($_POST['Palautteet']))
					{

						$palauteResponse = $this->luo_palaute($mod, $_POST);

						if($palauteResponse)
						{
							$this->_sendResponse(200, CJSON::encode(array('OK'=>Yii::t('main', 'Palaute lähetetty.'))));
							exit;
						} else {
							$this->_sendResponse(200, CJSON::encode(array('Error'=>$palauteResponse)));
							exit;
						}

					} else {
						$return .= $this->renderPartial('//palautteet/_form', array('model'=>$mod), true);
					}

					// <-- Palaute vastaus
					if(isset($_POST['palaute_id']))
					{
						$return = $this->Send_vastaus($_POST);
						$this->_sendResponse(200, CJSON::encode(array('OK'=>Yii::t('main', 'Palaute vastaus lähetetty.'))));
						exit;
					}
					//     Palaute vastaus -->

				}
				//  naytaPalautteet -->


				Yii::app()->theme = 'etunti';

				$naytaMita = '';
				if(isset($_POST['tyyppi']) and !empty($_POST['tyyppi']))
				$naytaMita = $_POST['tyyppi'];

				$naytaId = '';
				if(isset($_POST['id']) and !empty($_POST['id']))
				{
					$naytaId = $_POST['id'];
					Palautteet::model()->updateAll(array('asiakas_luettu'=>1),'keskustelu_id="'.$_POST['id'].'"');
				}

				$return .= $this->renderPartial('//asiakkaat/asiakas_historia', 
				array(
					'model'=>$model,
					$naytaMita=>true,
					'id' => $naytaId,
					'kayttaja' => 'asiakas',
				)
				, true);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
	}


	protected function generateRandomString($length = 40) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function actionGetlaskupdf($domain, $id)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			$lasku = Yii::app()->createController('Lasku');
			$result = $lasku[0]->Lasku_pdf($id);
			$this->_sendResponse(200, $result);

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
	}

	protected function luo_palaute($mod, $post)
	{
		$palauteet = Yii::app()->createController('Palautteet');
		return $palauteet[0]->UusiPalaute($mod, $post);
	}

	protected function Send_vastaus($post)
	{
	   	$asiakkaat = Yii::app()->createController('Asiakkaat');
		$asiakkaat[0]->palautteetVastaus($post);
	}



	public function actionTarjoukset($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			if(isset($_POST['asia']))
			{
				$return = false;
				$crm = CrmTarjoukset::model()->findbypk($_POST['id']);
				if($_POST['asia'] == 'hyvaksy' and isset($crm->id) and $crm->hyvaksyn_koodi == $_POST['code'] and $crm->status == 1){
					CrmTarjoukset::model()->updatebypk($_POST['id'], array('status'=>2));
					$return = true;
				}
				if($_POST['asia'] == 'hylatty' and isset($crm->id) and $crm->hyvaksyn_koodi == $_POST['code'] and $crm->status == 1){
					CrmTarjoukset::model()->updatebypk($_POST['id'], array('status'=>3));
					$return = true;
				}
				$asia = $_POST['asia'];
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}

			$liite = '';
			$link = '';
			$nimike = '';
			if(isset($_POST['liite']))
			{
				$nimike = $_POST['liite'];
				$this->valmistaNew($domain, $_POST['liite']);
				exit;
			}


			$criteria=new CDbCriteria;
			$criteria->order = " status "; 
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
				AND status!=0
			";
			$m2 = CrmTarjoukset::model()->findAll($criteria);

			if(count($m2) > 0)
			{
			$lista = '<br><div class="lista">';
			$lista .= '<legend><h2>'.Yii::t('main', 'TARJOUKSET').'</h2></legend><br>';
			foreach($m2 as $item)
			{
				$f = "tiedostot/tarjoukset/".$domain."/".$item->liite.".pdf";
   				if(!file_exists(Yii::app()->basePath."/../".$f))
   				{
					$lista .= 'Tiedosto ei löydy. Tarjous nro.: '. $item->id.' <hr>';				
					continue;
				}
		
					$txt = '';
					$bg_color = '';
					$tila = '';
					if($item->status == 1)
					{
						$txt = Yii::t('main', 'Uusi tarjous');
						$bg_color = 'bg-info';
						$tila = '
						<div class="row">
						 <div class="col-xs-6">
							<button class="asia btn btn-success btn-lg btn-block" aria-hidden="true" asia="hyvaksy" id="'.$item->id.'" code="'.$item->hyvaksyn_koodi.'"><i class="fa fa-check"></i> '.Yii::t('main', 'Hyväksy').'</button>
						 </div>
						 <div class="col-xs-6">
							<button class="pull-right asia btn btn-danger btn-lg btn-block" aria-hidden="true" asia="hylatty" id="'.$item->id.'" code="'.$item->hyvaksyn_koodi.'"><i class="fa fa-times"></i> '.Yii::t('main', 'Hylkää').'</button>
						 </div>
						</div>
						';
					}
					if($item->status == 2)
					{
						$txt = Yii::t('main', 'Hyväksytty tarjous');
						$bg_color = 'bg-success';
					}
					if($item->status == 3)
					{
						$txt = Yii::t('main', 'Hylätty tarjous');
						$bg_color = 'bg-danger';
					}
					$lista .= '
					  <div class="link avaaPDF alert '.$bg_color.' text-left" liite="'.$f.'" ext="pdf">
					   <i class="pull-right fa fa-file-pdf-o fa-5x" aria-hidden="true"></i>
					   <h3>'.$txt.'</h3>
					   '.date("d.m.Y H:i", strtotime($item->time)).'<br>
					   '.$item->kohteen_osoite.' '.$item->kohteen_postinumero.', '.$item->kohteen_postitoimipaikka.'
					  </div>
					  '.$tila.'
					  <hr>
					';

			}
			$lista .= '</div>';

				$return = array('lista'=>$lista, 'liite'=>$link, 'nimike'=>$nimike);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
				exit;
	}


	protected function valmistaNew($domain, $liite)
	{

		$t = $liite;
		$filename = '';
		$ext = '';
   		if(file_exists(Yii::app()->basePath."/../".$t))
   		{

			if (!file_exists( Yii::app()->basePath.'/../tmp/'.$domain )) {
			 	mkdir( Yii::app()->basePath.'/../tmp/'.$domain, 0777, true );
			}

			$this->removeAllFromTMP($domain);

			$file = Yii::app()->basePath."/../".$t;
			$liite = Yii::app()->basePath.'/../tmp/'.$domain.'/'.basename($file);
			if (!copy($file, $liite)) {
			    	$this->_sendResponse(200, CJSON::encode('Copy error'));
				exit;
			} else {
				$link = Yii::app()->request->hostInfo .'/tmp/'.$domain.'/'.basename($file);
				$filename = basename($file);
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
			}

			$this->_sendResponse(200, CJSON::encode(array('link'=>$link, 'filename'=>$filename, 'ext'=>$ext)));
			exit;
		}
		return false;
	}

	public function actionSopimukset($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			if(isset($_POST['asia']))
			{
				$return = false;
				$crm = CrmSopimukset::model()->findbypk($_POST['id']);
				if($_POST['asia'] == 'hyvaksy' and isset($crm->id) and $crm->hyvaksyn_koodi == $_POST['code'] and $crm->status == 1){
					CrmSopimukset::model()->updatebypk($_POST['id'], array('status'=>2));
					$return = true;
				}
				if($_POST['asia'] == 'hylatty' and isset($crm->id) and $crm->hyvaksyn_koodi == $_POST['code'] and $crm->status == 1){
					CrmSopimukset::model()->updatebypk($_POST['id'], array('status'=>3));
					$return = true;
				}
				$asia = $_POST['asia'];
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}

			$liite = '';
			$link = '';
			$nimike = '';
			if(isset($_POST['liite']))
			{
				$nimike = $_POST['liite'];
				$this->valmistaNew($domain, $_POST['liite']);
				exit;
			}


			$criteria=new CDbCriteria;
			$criteria->order = " status "; 
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
				AND status!=0
			";
			$m2 = CrmSopimukset::model()->findAll($criteria);

			if(count($m2) > 0)
			{
			$lista = '<br><div class="lista">';
			$lista .= '<legend><h2>'.Yii::t('main', 'SOPIMUKSET').'</h2></legend><br>';
			foreach($m2 as $item)
			{
				$f = "tiedostot/sopimukset/".$domain."/".$item->liite.".pdf";
   				if(!file_exists(Yii::app()->basePath."/../".$f))
   				{
					$lista .= 'Tiedosto ei löydy. Sopimus nro.: '. $item->id.' <hr>';				
					continue;
				}
		
					$txt = '';
					$bg_color = '';
					$tila = '';
					if($item->status == 1)
					{
						$txt = Yii::t('main', 'Uusi sopimus');
						$bg_color = 'bg-info';
						$tila = '
						<div class="row">
						 <div class="col-xs-6">
							<button class="asia btn btn-success btn-lg btn-block" aria-hidden="true" asia="hyvaksy" id="'.$item->id.'" code="'.$item->hyvaksyn_koodi.'"><i class="fa fa-check"></i> '.Yii::t('main', 'Hyväksy').'</button>
						 </div>
						 <div class="col-xs-6">
							<button class="pull-right asia btn btn-danger btn-lg btn-block" aria-hidden="true" asia="hylatty" id="'.$item->id.'" code="'.$item->hyvaksyn_koodi.'"><i class="fa fa-times"></i> '.Yii::t('main', 'Hylkää').'</button>
						 </div>
						</div>
						';
					}
					if($item->status == 2)
					{
						$txt = Yii::t('main', 'Hyväksytty sopimus');
						$bg_color = 'bg-success';
					}
					if($item->status == 3)
					{
						$txt = Yii::t('main', 'Hylätty sopimus');
						$bg_color = 'bg-danger';
					}
					$lista .= '
					  <div class="link avaaPDF alert '.$bg_color.' text-left" liite="'.$f.'" ext="pdf">
					   <i class="pull-right fa fa-file-pdf-o fa-5x" aria-hidden="true"></i>
					   <h3>'.$txt.'</h3>
					   '.date("d.m.Y H:i", strtotime($item->time)).'<br>
					   '.$item->kohteen_osoite.' '.$item->kohteen_postinumero.', '.$item->kohteen_postitoimipaikka.'
					  </div>
					  '.$tila.'
					  <hr>
					';

			}
			$lista .= '</div>';

				$return = array('lista'=>$lista, 'liite'=>$link, 'nimike'=>$nimike);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
				exit;
	}


	public function actionTyonkuvaukset($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			$liite = '';
			$link = '';
			$nimike = '';
			if(isset($_POST['liite']))
			{
				$nimike = $_POST['liite'];
				$t = $_POST['liite'];

					if (!file_exists( Yii::app()->basePath.'/../tmp/'.$domain )) {
					 	mkdir( Yii::app()->basePath.'/../tmp/'.$domain, 0777, true );
					}

					// <-- Remove all from tmp
					$this->removeAllFromTMP($domain);

					$site = Yii::app()->createController('Site');
					$tiedosto = 'Tyonkuvaus';
		  			$tiedosto = $site[0]->tiedostonNimiAsiakasKohdeAika($tiedosto, $model->id, null, date("Y-m-d H:i:s"));
					$path = 'tmp/'.$domain;

					$tk = Tyonkuvaus::model()->findByPk($_POST['liite']);
					$k = Kohteet::model()->findByPk($tk->kohde_id);
					(isset($k->id))? $kohde = $k->osoite:$kohde = '';
	   				$tarjoukset = Yii::app()->createController('CrmTarjoukset');
	   				$html = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
					$html .= '<style>'.file_get_contents(Yii::app()->basePath.'/../css/raportit_table2.css').'</style>';
					$html .= '</head><body>';
					$html .= '<p><h2>'.Yii::t('main', 'Työnkuvaus').'</h2> '.date("d.m.Y H:i", strtotime($tk->time)).' '.$kohde.'</p><br>';
	   				$html .= $tarjoukset[0]->get_tyonkuvaus($_POST['liite']);
					$html .= '</body></html>';

					file_put_contents($path.'/'.$tiedosto.'.html', $html);
					exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.'/'.$tiedosto.'.html '.$path.'/'.$tiedosto.'.pdf', $output, $return); //--orientation Landscape --title "Titulo: do PDF"
					if($output)
					{
					    if (file_exists( Yii::app()->basePath.'/../'.$path.'/'.$tiedosto.'.pdf' ))
					    {

						unlink($path.'/'.$tiedosto.'.html');
						$link = Yii::app()->request->hostInfo .'/'.$path.'/'.$tiedosto.'.pdf';
						$filename = basename($link);
						$ext = pathinfo($filename, PATHINFO_EXTENSION);

						$this->_sendResponse(200, CJSON::encode(array('link'=>$link, 'filename'=>$filename, 'ext'=>$ext)));
						exit;

					    }
					}

			}


			$criteria=new CDbCriteria;
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
				AND aktiivinen=1
			";
			$m2 = Tyonkuvaus::model()->findAll($criteria);
			if(count($m2) > 0)
			{
			$lista = '<br><div class="lista">';
			foreach($m2 as $item)
			{
					$k = Kohteet::model()->findByPk($item->kohde_id);
					(isset($k->id))? $kohde = $k->osoite:$kohde = '';
					$lista .= '
					<div class="row link avaaPDF" liite="'.$item->id.'">
					 <div class="col-sm-12">
					';
						$lista .= '<div class="alert bg-warning text-center"><h2>'.Yii::t('main', 'Työnkuvaus').'</h2> '.date("d.m.Y H:i", strtotime($item->time)).' '.$kohde.'</div>';
					$lista .= '
					 </div>
					</div>
					';

			}
			$lista .= '</div>';

				$return = array('lista'=>$lista, 'liite'=>$link, 'nimike'=>$nimike);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
				exit;
	}


	protected function removeAllFromTMP($domain)
	{
		exec('rm -rf tmp/'.$domain.'/*');
		return true;
	}

	public function actionMuuttiedostot($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			$liite = '';
			$link = '';
			$nimike = '';
			if(isset($_POST['liite']))
			{
				$nimike = $_POST['liite'];
				$link = $this->valmistaNew($domain, $_POST['liite']);
				exit;
			}

			$lista = '<br><div class="lista">';
			foreach(array_reverse(glob('tiedostot/asiakkaat/'.$domain.'/'.$model->id.'_*.*')) as $file) 
			{
				$explNimi = explode("/", $file);
				$e = explode(".", end($explNimi));

					$lista .= '
					<div class="row link avaaPDF" liite="'.$file.'" ext="'.end($e).'">
					 <div class="col-sm-12">
					';
						$lista .= '<div class="alert bg-warning text-center"><h2>'.end($explNimi).'</div>';
					$lista .= '
					 </div>
					</div>
					';

			}
			$lista .= '</div>';

				$return = array('lista'=>$lista, 'liite'=>$link, 'nimike'=>$nimike);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
				exit;
	}

	public function actionViestinta($domain)
	{

		if(isset($_POST['token']) and !empty($_POST['token']) and isset($_POST['asiakasID']) and !empty($_POST['asiakasID']))
		{
			Asiakkaat::model()->updateByPk($_POST['asiakasID'], array('gcm_reg_id' => $_POST['token']));
			$this->_sendResponse(200, CJSON::encode('token update ok'));
			exit;
		}

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($domain, $_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model = Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {
			// <-- Uusi viesti
			if( isset($_POST['uusi_viesti']) ){
				$v = new EdicoViestinta;
				$v->asiakas_id = $model->id;
				$v->otsikko = $_POST['otsikko'];
				$v->luoja = 'asiakas';
				if( $v->save() ){
					$vr = new EdicoViestintaRivit;
					$vr->viestinta_id = $v->id;
					$vr->asiakas_id = $model->id;
					$vr->teksti = $_POST['teksti'];
					$vr->luoja = 'asiakas';
					if( $vr->save() ){
						$lahetys_status = '<div class="alert alert-success">Viestisi lähetetty. Paina <a href="viestinta.html">tästä</a> jotta palaa takaisiin.</div>';
						$return = array('lahetys_status'=>$lahetys_status);
						$this->_sendResponse(200, CJSON::encode($return));
						exit;
					} else {
						$this->_sendResponse(200, CJSON::encode($vr->getErrors()));
						exit;
					}
				}
				$this->_sendResponse(200, CJSON::encode('Error: Lähetys'));
				exit;
			}
			// Uusi viesti -->


			// <-- Vastaus viesti
			if( isset($_POST['vastaus']) ){

					$vr = new EdicoViestintaRivit;
					$vr->viestinta_id = $_POST['id'];
					$vr->asiakas_id = $model->id;
					$vr->teksti = $_POST['vastaus'];
					$vr->luoja = 'asiakas';
					if( $vr->save() ){
						$lahetys_status = '<div class="alert alert-success">Viestisi lähetetty. Paina <a href="viestinta.html">tästä</a> jotta palaa takaisiin.</div>';
						$return = array('lahetys_status'=>$lahetys_status);
						$this->_sendResponse(200, CJSON::encode($return));
						exit;
					} else {
						$this->_sendResponse(200, CJSON::encode($vr->getErrors()));
						exit;
					}

				$this->_sendResponse(200, CJSON::encode('Error: Lähetys'));
				exit;
			}
			// Vastaus viesti -->

			if( isset($_POST['katsottu_id']) ){
				EdicoViestintaRivit::model()->updateByPk($_POST['katsottu_id'], array('katsottu' => 1));
			}

			$criteria=new CDbCriteria;
			$criteria->order = " id DESC  ";
			$criteria->condition = " 
				 asiakas_id='".$model->id."'
			";
		   	$v = EdicoViestinta::model()->findAll($criteria);
			$lista = '<h2>Viestintä</h2>';
			$lista .= '<br>
			<fieldset id="lahetys_lomake">
			<legend><h4>Uusi viesti</h4></legend>
			<p><input type="text" id="otsikko" class="form-control" placeholder="Otsikko.."></p>
			<p><textarea id="teksti" class="form-control" placeholder="Teksti.."></textarea></p>
			<p><button class="btn btn-success" id="laheta_viesti">Lähetä</button></p>
			</fieldset>
			<div id="lahetys_status"></div>
			';
			$lista .= '<br><div class="lista">';
			foreach($v as $item) 
			{
				$lista .= '<div class="well" '.(($item->status == 99)? 'style="border: 2px #fec121 solid"' : '').'>';
				$lista .= '<h4>'.date("d.m.Y H:i", strtotime($item->time)).' - '.$item->otsikko.'</h4>';
				$lista .= '<p><b>Viimeinen viesti:</b> '.max($item->rivit)->teksti.'</p>';

				$lista .= '<button class="btn btn-default btn-block" data-toggle="collapse" data-target="#ava_'.$item->id.'">Näytä kaikki viestit <i class="caret"></i></button>';


				$lista .= '<div id="ava_'.$item->id.'" class="collapse"><p>';
				foreach($item->rivit as $rivi){
					$kirjoittaja = '';
					if( $rivi->luoja == 'admin' and !empty($rivi->admin_id) ){
						$kirjoittaja = $rivi->adminname;
					}
					if( $rivi->luoja == 'asiakas' and !empty($rivi->asiakas_id) ){
						$kirjoittaja = $rivi->asiakasname;
					}
					$lista .= '<p id="rivi_'.$rivi->id.'"><b>'.date("d.m.Y H:i", strtotime($rivi->time)).' '.$kirjoittaja.'</b>: '.$rivi->teksti.'</p>';
				}
				$lista .= '</div>';

				if($item->status != 99){
				$lista .= '<br><br>
				<div class="vastaus">
					<input type="hidden" id="id" value="'.$item->id.'">
					<textarea id="vastaus" class="form-control" placeholder="Vastaa tähään keskusteluun.."></textarea>
					<button class="btn btn-success btn-block laheta_vastaus">Lähetä</button>
				</div>';
				}

				$lista .= '</p></div>';
			}
			$lista .= '</div>';

			$return = array('lista'=>$lista);
			$this->_sendResponse(200, CJSON::encode($return));
			exit;
		   } // $model->id
		}
		$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
		exit;
	}

	protected function valmistaTMP($domain, $liite, $ext)
	{

		$link = '';
		$t = $liite;
   		if(file_exists(Yii::app()->basePath."/../".$t))
   		{
			if (!file_exists( Yii::app()->basePath.'/../tmp/'.$domain )) {
			 	mkdir( Yii::app()->basePath.'/../tmp/'.$domain, 0777, true );
			}
			$rndm_str = $this->generateRandomString($length = 40);
			$file = Yii::app()->basePath."/../".$t;
			$liite = Yii::app()->basePath.'/../tmp/'.$domain.'/'.$rndm_str.'.'.$ext;
			if (!copy($file, $liite)) {
			    	$this->_sendResponse(200, CJSON::encode('Copy error'));
				exit;
			} else {
				$link = Yii::app()->request->hostInfo .'/index.php/site/opentmp?domain='.$domain.'&file='.$rndm_str.'.'.$ext;
			}
		}
		return $link;
	}


private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
    // set the status
    $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
    header($status_header);
    // and the content type
    header('Content-type: ' . $content_type);
 
    // pages with body are easy
    if($body != '')
    {
        // send the body
        echo $body;
    }
    // we need to create the body if none is passed
    else
    {
        // create some body messages
        $message = '';
 
        // this is purely optional, but makes the pages a little nicer to read
        // for your users.  Since you won't likely send a lot of different status codes,
        // this also shouldn't be too ponderous to maintain
        switch($status)
        {
            case 401:
                $message = 'You must be authorized to view this page.';
                break;
			case 403:
				$message = 'eDico is closed';
				break;
            case 404:
                $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                break;
            case 500:
                $message = 'The server encountered an error processing your request.';
                break;
            case 501:
                $message = 'The requested method is not implemented.';
                break;
        }
 
        // servers don't always have a signature turned on 
        // (this is an apache directive "ServerSignature On")
        $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
 
        // this should be templated in a real-world solution

        $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';
 
        echo $body;
    }
    Yii::app()->end();
}



private function _getStatusCodeMessage($status)
{
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $codes = Array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',

        501 => 'Not Implemented',
    );
    return (isset($codes[$status])) ? $codes[$status] : '';
}


private function _checkAuth()
{


    if(!(isset($_GET['X_USERNAME']) and isset($_GET['X_PASSWORD']))) {
        // Error: Unauthorized
        $this->_sendResponse(401);
    }
    $username = $_GET['X_USERNAME'];
    $password = $_GET['X_PASSWORD'];
    // Find the user
    $user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
    if($user===null) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Name is invalid');
    } else if(!$user->validatePassword($password)) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Password is invalid');
    }

/*
    // Check if we have the USERNAME and PASSWORD HTTP headers set?
    if(!(isset($_SERVER['HTTP_X_USERNAME']) and isset($_SERVER['HTTP_X_PASSWORD']))) {
        // Error: Unauthorized
        $this->_sendResponse(401);
    }
    $username = $_SERVER['HTTP_X_USERNAME'];
    $password = $_SERVER['HTTP_X_PASSWORD'];
    // Find the user
    $user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
    if($user===null) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Name is invalid');
    } else if(!$user->validatePassword($password)) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Password is invalid');
    }
*/
}


}

?>

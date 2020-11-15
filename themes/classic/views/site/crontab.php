<?php
header("Content-Type: text/html; charset=utf-8");

// Functionality was limited to domain 'demo' when called locally.
// Set 'full' to 1 or true to do full run locally.
// Added (and cleaned) 23.08.19 @ Arttu Huurinainen
$full = isset($full) ? $full : false;
$is_local = in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1']);
$local_run = !$full && $is_local; // If true, request is from localhost and limit to demo domain.

// If invalid password, exit script to reduce nesting.
$db_host = 'localhost';
$site = Yii::app()->createController('Site');
$conn = $site[0]->dbConnectArr();
if( isset($conn['host']) )
	$db_host = $conn['host'];

$koodi_aktiivinen = 1;
if ($local_run){
	$list = Domainit::model()->findAll(" domain='demo' ");
} else {
	if($only_domain != null)
	$list = Domainit::model()->findAll(" domain='$only_domain' ");
	else
	$list = Domainit::model()->findAll(" domain!='defdb' AND aktiivinen=1 ");
}

try {
	$mysqli = new mysqli($conn['host'], $conn['username'], $conn['password']);
} catch (\Exception $e) {
	echo $e->getMessage(), PHP_EOL;
	exit;
}

//echo "KPL yhteensa: " . count($list) . "\n";

foreach ($list as $d) {
	if ($mysqli->select_db($d->domain) === false) { continue; }
	$_SESSION['domain'] = $d->domain;
	echo $d->domain . "\n";
	
	Yii::app()->db1->setActive(false);
	Yii::app()->db1->connectionString = 'mysql:host=' . $db_host. ';dbname=' . $d->domain;
	Yii::app()->db1->setActive(true);


	if(empty($d->domain))
		continue;
		
	$asiakkaat = Asiakkaat::model()->find("id=1");
	continue;
	
	$asetukset = Asetukset::model()->findByPk(1);
	$tyovuoroot = Yii::app()->createController('Tyovuoroot');

	$aikavali_halytys = 15;
	if (!empty($asetukset->aikavali_halytys))
		$aikavali_halytys = $asetukset->aikavali_halytys;

	$ft = FirmanTiedot::model()->findByPk(1);
	$dh = DigistenHinnasto::model()->findByPk(1);
	$domainit = Domainit::model()->findByPk($d->id);

	// <-- Tuntien Autohyvaksyminen
	if ($asetukset->app_hyvaksynnan_peruste == 2) {
		$date_yday = date('Y-m-d', strtotime('-1 day'));
		$criteria = new CDbCriteria();
		$criteria->condition = "DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d') = '{$date_yday}' AND hyvaksytty=''";
		$mob_hyvaksymattomat_eilen = Mobile::model()->findAll($criteria);
		$tot_hyvaksymattomat_eilen = Toteutuneet::model()->findAll($criteria);
		if (count($mob_hyvaksymattomat_eilen) > 0) {
			if (time() > strtotime($asetukset->auto_hyvaksynta_klo)) {
				Mobile::model()->updateAll(array('hyvaksytty' => 'auto//' . date("d.m.Y")), $criteria);
			}
		}
		if (count($tot_hyvaksymattomat_eilen) > 0) {
			if (time() > strtotime($asetukset->auto_hyvaksynta_klo)) {
				Toteutuneet::model()->updateAll(array('hyvaksytty' => 'auto//' . date("d.m.Y")), $criteria);
			}
		}
	}
	//    Tuntien Autohyvaksyminen -->

	// <-- maksullinen versio
	if ($domainit->maksullinen == 1 and isset($dh->snapshot_pvm) and $dh->snapshot_pvm > 0) {
		$snapshot_paiva = $dh->snapshot_pvm;

		if (date("j")  == $snapshot_paiva) {
			$criteria = new CDbCriteria;
			$criteria->condition = " 
				domain='" . $d->domain . "'
				AND year = '" . date("Y", strtotime('first day of last month')) . "'
				AND month = '" . date("n", strtotime('first day of last month')) . "'
			";
			$digisten_tunnit = DigistenTunnitKk::model()->find($criteria);

			if (!isset($digisten_tunnit->id)) {
				$tunnit = 0;
				$tunnit = $site[0]->laskuriForCron($d->domain);
				$domainit = Domainit::model()->find(" domain='" . $d->domain . "' ");
				$paketti = isset($domainit->id) ? $domainit->paketti : '';

				if ($tunnit > 0) {
					$adm = Administrators::model()->findAll();
					$dt = new DigistenTunnitKk;
					$dt->domain_id = $d->id;
					$dt->domain = $d->domain;
					$dt->tunnit = (int) $tunnit;
					$dt->year = date("Y", strtotime('first day of last month'));
					$dt->month = date("n", strtotime('first day of last month'));
					$dt->tasot = $paketti;
					$dt->jarjestelmanvalvoja_maara = count($adm);

					if (!$dt->save())
						var_dump($dt->getErrors());
					else
						echo '<p>Snapshot ' . $d->domain . ', Tunnit: ' . (int) $tunnit . '</p>';
				}
			}
		}
	}
	//     maksullinen versio -->

	// <-- Ilmoitus määräajan ylittäneistä kohteista
	$criteria = new CDbCriteria;
	$criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(CONCAT(pvm,loppu), '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < (NOW() - INTERVAL $aikavali_halytys MINUTE)
		AND ilmoitus_avoimista_kohteesta=0
		AND id IN (SELECT tv_id FROM sivexkuitti 
			WHERE status=1 
			AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) = DATE(STR_TO_DATE(t.pvm, '%d.%m.%Y'))
			AND tid=t.tid)
		AND peruutettu=0
	";
	$m = Tyovuoroot::model()->findAll($criteria);

	if (count($m) > 0) {
		echo '<h2>' . strtoupper($ft->tyonantaja) . '</h2>';
		echo '<h2>' . Yii::t('main', 'Avoimet kohteet') . ' ' . date("d.m.Y H:i") . '</h2><br>';

		foreach ($m as $data) {
			$bod     = '';
			$osoite   = '';
			$tekijan_nimi   = '';
			$tyoryhmaForArr = '';
			$osoite = Yii::t('main', 'Osoite') . ': <b>' . $data->osoite . '</b><br>';
			$tekijan_nimi = Yii::t('main', 'Työntekijä') . ':  <b>' . $this->etuSukunimi($data->tid) . '</b><br>';
			$bod   .= $tekijan_nimi . $osoite;
			$bod   .= Yii::t('main', 'Lopetusajaksi oli määritelty') . ': ' . $data->pvm . ', ' . $data->alku;
			$bod   .= '<br>';

			if (!$local_run)
				Tyovuoroot::model()->updatebypk($data->id, array('ilmoitus_avoimista_kohteesta' => 1));

			// <-- Sahkopostin lahetys
			$tekija = Tyontekijat::model()->findByPk($data->tid);
			if (isset($tekija->id) and $asetukset->ilmoitus_myohastyneista_kohteesta_sahkopostiin == 1) {
				if (is_array(json_decode($tekija->tyoryhma, true))) {
					foreach (json_decode($tekija->tyoryhma, true) as $tyoryhma) {
						$mailMessage = '';
						$mailMessage .= '<h3>' . Yii::t('main', 'Työryhmä') . ' ' . $tyoryhma . '</h3><br>';
						$mailMessage .= $bod;
						$criteria = new CDbCriteria();
						$criteria->order = " value ";
						$criteria->condition = " select_type='tyoryhma'	AND value='$tyoryhma' ";
						$valikot = Valikkoot::model()->find($criteria);
						if (isset($valikot->value2) and is_array(json_decode($valikot->value2, true))) {
							foreach (json_decode($valikot->value2, true) as $adm_id) {
								$administrators = Administrators::model()->findByPk($adm_id);
								if (isset($administrators->adm_email) and !empty($administrators->adm_email)) {
									if (!$local_run) {
										$subject = Yii::t('main', 'Ilmoitus avoimista kohteesta ' . date("d.m.Y H:i"));
										$mail = new YiiMailer();
										$mail->setFrom('no-reply@etunti.fi');
										$mail->setTo($administrators->adm_email);
										$mail->setSubject($subject);
										$mail->setBody($mailMessage);
										$mail->send();

										// <-- LOG
										$log = new Log;
										$log->log_category   = 1; // 1-email
										$log->email_to     = $administrators->adm_email;
										$log->email_subject  = $subject;
										$log->email_message  = json_encode($mailMessage);
										$log->save();
										//     LOG -->
									}
									echo '<h4>' . $administrators->adm_email . '</h4>' . $mailMessage;
								}
							}
						}
					}
				}
			}
			//     Sahkopostin lahetys -->
		}
	}
	// Ilmoitus määräajan ylittäneistä kohteista -->

	// <-- ilmoitus_myohastyneista_kohteesta
	$criteria = new CDbCriteria;
	$criteria->condition = " 
		DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) = CURDATE()
		AND ilmoitus_myohastyneista_kohteesta=0
		AND DATE_ADD(DATE_FORMAT(STR_TO_DATE(CONCAT(pvm, alku), '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), INTERVAL $aikavali_halytys MINUTE) < NOW() 
		AND id NOT IN 
		(SELECT tv_id FROM sivexkuitti 
		WHERE DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) = DATE(STR_TO_DATE(t.pvm, '%d.%m.%Y'))
		AND tid=t.tid
		)
		AND tyoajanmerkinta LIKE '%Normaali%'
		AND status=3
		AND peruutettu=0
	";
	$m = Tyovuoroot::model()->findAll($criteria);

	if (count($m) > 0) {
		echo '<h2>' . strtoupper($ft->tyonantaja) . '</h2>';
		echo '<h2>' . Yii::t('main', 'Myöhästyneet kohteet') . ' ' . date("d.m.Y H:i") . '</h2><br>';

		foreach ($m as $data) {
			$bod     = '';
			$osoite   = '';
			$tekijan_nimi   = '';
			$tyoryhmaForArr = '';
			$osoite = Yii::t('main', 'Osoite') . ': <b>' . $data->osoite . '</b><br>';
			$tekijan_nimi = Yii::t('main', 'Työntekijä') . ':  <b>' . $this->etuSukunimi($data->tid) . '</b><br>';

			$bod   .= $tekijan_nimi . $osoite;
			$bod   .= Yii::t('main', 'Aloitusajaksi oli määritelty') . ': ' . $data->pvm . ', ' . $data->alku;
			$bod   .= '<br>';

			if (!$local_run) {
				Tyovuoroot::model()->updatebypk($data->id, array('ilmoitus_myohastyneista_kohteesta' => 1));
			}
			// <-- Sahkopostin lahetys
			$tekija = Tyontekijat::model()->findByPk($data->tid);
			if (isset($tekija->id) and $asetukset->ilmoitus_myohastyneista_kohteesta_sahkopostiin == 1) {
				if (is_array(json_decode($tekija->tyoryhma, true))) {
					foreach (json_decode($tekija->tyoryhma, true) as $tyoryhma) {
						$mailMessage   = '';
						$mailMessage   .= '<h3>' . Yii::t('main', 'Työryhmä') . ' ' . $tyoryhma . '</h3><br>';
						$mailMessage   .= $bod;
						$criteria = new CDbCriteria();
						$criteria->order = " value ";
						$criteria->condition = " 
					select_type='tyoryhma'	
					AND value='$tyoryhma'
				";
						$valikot = Valikkoot::model()->find($criteria);
						if (isset($valikot->value2) and is_array(json_decode($valikot->value2, true))) {
							foreach (json_decode($valikot->value2, true) as $adm_id) {
								$administrators = Administrators::model()->findByPk($adm_id);
								if (isset($administrators->adm_email) and !empty($administrators->adm_email)) {
									if (!$local_run) {
										$subject = Yii::t('main', 'Ilmoitus myöhästyneistä kohteesta ' . date("d.m.Y H:i"));
										$mail = new YiiMailer();
										$mail->setFrom('no-reply@etunti.fi');
										$mail->setTo($administrators->adm_email);
										$mail->setSubject($subject);
										$mail->setBody($mailMessage);
										$mail->send();

										// <-- LOG
										$log = new Log;
										$log->log_category   = 1; // 1-email
										$log->email_to     = $administrators->adm_email;
										$log->email_subject  = $subject;
										$log->email_message  = json_encode($mailMessage);
										$log->save();
										//     LOG -->
									}
									echo '<h4>' . $administrators->adm_email . '</h4>' . $mailMessage;
								}
							}
						}
					}
				}
			}
			//     Sahkopostin lahetys -->
		}
	}
	// ilmoitus_myohastyneista_kohteesta -->

	// <-- merkkipaivailmoitukset
	$message   = '';
	$arr     = array();
	$forMessage  = array();

	$criteria = new CDbCriteria;
	$criteria->select = "id, tekijan_nimi, tyoryhma, 
		DATE_FORMAT(STR_TO_DATE(SUBSTRING_INDEX(tekijan_henkilotunnus, '-', 1), '%d%m%y'), CONCAT(YEAR(CURDATE()),'-%m-%d')) as tekijan_henkilotunnus 
	";
	$criteria->condition = " 
		aktiivinen='1'
		AND tekijan_henkilotunnus!=''
		AND DATE_FORMAT(STR_TO_DATE(SUBSTRING_INDEX(tekijan_henkilotunnus, '-', 1), '%d%m%y'), CONCAT(YEAR(CURDATE()),'-%m-%d')) BETWEEN CURDATE() 
		AND (CURDATE() + INTERVAL 14 DAY)
		AND ilmoitus_merkkipaivasta_vuosi!=YEAR(CURDATE())
	";
	$tt = Tyontekijat::model()->findAll($criteria);

	if (isset($tt[0])) {
		echo '<h2>' . strtoupper($ft->tyonantaja) . '</h2>';
		echo '<h2>' . Yii::t('main', 'Ilmoitus merkkipäivästä') . ' ' . date("d.m.Y H:i") . '</h2><br>';

		foreach ($tt as $data) {

			$bod     = '';
			$bod   .= Yii::t('main', 'Merkkipäivä') . ': <b>' . date("d.m.Y", strtotime($data->tekijan_henkilotunnus)) . ', ' . $this->etuSukunimi($data->id) . '</b><br>';

			$tyoryhmaForArr = $data->tyoryhma;

			if (is_array(json_decode($tyoryhmaForArr, true))) {

				foreach (json_decode($tyoryhmaForArr, true) as $ryhma_item) {
					$arr[$ryhma_item]   = $ryhma_item;
					array_push(
						$forMessage,
						array(
							'tyoryhma' => $ryhma_item,
							'message' => $bod
						)
					);
				}
			} else {
				$arr[$tyoryhmaForArr]   = $tyoryhmaForArr;
				array_push(
					$forMessage,
					array(
						'tyoryhma' => $tyoryhmaForArr,
						'message' => $bod
					)
				);
			}

			if (!$local_run)
				Tyontekijat::model()->updateByPk($data->id, array('ilmoitus_merkkipaivasta_vuosi' => date("Y")));
		}
	}

	if (count($arr) > 0 and $asetukset->ilmoitus_merkkipaivasta == 1) {

		// <-- Jäjestelmanvalvojan kuluvia ryhmiä
		$criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = " 
			select_type='tyoryhma'	
			AND value2!=''
		";
		$valikot = Valikkoot::model()->findAll($criteria);
		$ft = FirmanTiedot::model()->findbypk(1);
		foreach ($arr as $ryhma) {

			foreach ($valikot as $data) {

				if ($data->value == $ryhma) {

					$sahkopostiArray  = array();
					$mailMessage   = '';
					$mailMessage   .= '<h3>' . Yii::t('main', 'Työryhmä') . ' ' . $ryhma . '</h3>';

					foreach ($forMessage as $key => $value) {
						if ($value['tyoryhma'] == $data->value)
							$mailMessage .= $value['message'] . '<br>';
					}

					$admin_ids = json_decode($data->value2);
					if (is_array($admin_ids)) {
						foreach ($admin_ids as $adm_id) {
							$administrators = Administrators::model()->findByPk($adm_id);
							if (isset($administrators->adm_email) and !empty($administrators->adm_email))
								array_push($sahkopostiArray, $administrators->adm_email);
						}
					}

					if (!$local_run) {
						$subject = Yii::t('main', 'Ilmoitus merkkipäivästä ' . date("d.m.Y H:i"));
						$mail = new YiiMailer();
						$mail->setFrom('no-reply@etunti.fi');
						$mail->setTo($sahkopostiArray);
						$mail->setSubject($subject);
						$mail->setBody($mailMessage);
						$mail->send();

						// <-- LOG
						$log = new Log;
						$log->log_category   = 1; // 1-email
						$log->email_to     = implode(",", $sahkopostiArray);
						$log->email_subject  = $subject;
						$log->email_message  = json_encode($message);
						$log->save();
						//     LOG -->
					}

					//print_r($sahkopostiArray);
					echo $mailMessage;
				}
			}
		}

		echo '<hr>';
	}
	// merkkipaivailmoitukset -->

	// <-- lmoitus toistuvien työvuorojen päättymisestä
	if (isset($asetukset->ilmoitus_toistuvien_tyovuorojen_paattymisesta) and $asetukset->ilmoitus_toistuvien_tyovuorojen_paattymisesta == 1) {
		$criteria = new CDbCriteria;
		//$criteria->select = "";
		$criteria->condition = " 
		DATE(STR_TO_DATE(pto, '%d.%m.%Y')) >= CURDATE()
		AND DATEDIFF(DATE(STR_TO_DATE(pto, '%d.%m.%Y')), CURDATE()) < ".(int)$asetukset->ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen."
		AND ilmoitus_paattymisesta!=1
		";
		$toistuvat = ToistuvatTyovuorot::model()->findAll($criteria);
		$m = '';
		$message = '';
		foreach ($toistuvat as $data) {
			/*
			$check_onkotyovuorot = Tyovuoroot::model()->find(" toistuva_id='" . $data->id . "' ");
			if (!isset($check_onkotyovuorot->id)) {
				continue;
			}
			*/
			//$k = Kohteet::model()->findbypk($data->kohde);
			//$t = Tyontekijat::model()->findbypk($data->tid);

			if(isset($t->id) and $t->aktiivinen == 0) {
				continue;
			}
			$osoite = '';
			if(isset($data->osoite) and !empty($data->osoite)) {
				$osoite = $data->osoite;
			} else {
				if (isset($data->kohteet->osoite)) {
					$osoite = $data->kohteet->osoite;
				}
			}
			$tekijan_nimi = '';
			$tekijan_nimi = $this->etuSukunimi($data->tid);

			if($data->tid == 0) {
				$tekijan_nimi = 'VARAUS';
			}

			if($data->status == 3) {
				$m .= '<hr><b>' . $tyovuoroot[0]->tilanteet()[$data->status] . '</b><br>';
				if(isset($data->kohteet->asiakkaat->Fullname))
					$m .= '<b>' . Yii::t('main', 'Asiakas') . ':</b> ' . $data->kohteet->asiakkaat->Fullname . '<br>';
					
				$m .= '<b>' . Yii::t('main', 'Osoite') . ':</b> ' . $osoite . '<br>';
			} else {
				$m .= '<hr><b>' . $tyovuoroot[0]->tilanteet()[$data->status] . '</b><br>';
			}
			$m .= '<b>' . Yii::t('main', 'Aikaväli') . ':</b> ' . $data->pfrom . '-' . $data->pto . '<br>';
			$m .= '<b>' . Yii::t('main', 'Klo') . ':</b> ' . $data->alku . '-' . $data->loppu . '<br>';
			$m .= '<b>' . Yii::t('main', 'Työntekijä') . ':</b> ' . $tekijan_nimi . '<br>';

			if (!empty($data->tyopaari)) {
				$tyopari = json_decode($data->tyopaari);
				foreach ($tyopari as $tid) {
					$tekijan_nimi2 = '';
					$t2 = Tyontekijat::model()->findbypk($tid);
					if (isset($t2->id) and $tid != $data->tid) {
						$tekijan_nimi2 = $this->etuSukunimi($t2->id);
						$m .= '<b>' . Yii::t('main', 'Työpari') . ':</b> ' . $tekijan_nimi2 . '<br>';
					}
				}
			}

			if (!$local_run)
				ToistuvatTyovuorot::model()->updateByPk($data->id, array('ilmoitus_paattymisesta' => 1));
		}

		// <-- Valmistetaan viesti
		if (!empty($m)) {
			$message .= '<h2>' . Yii::t('main', 'Ilmoitus toistuvien työvuorojen päättymisestä') . '</h2>';
			$message .= $m;
		}

		$saaja = array();
		$s = explode("\n", $asetukset->ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat);
		foreach ($s as $sp)
			if (!empty($sp))
				array_push($saaja, $sp);

		if (!$local_run and count($saaja) > 0 and !empty($message)) {

			$ft = FirmanTiedot::model()->findbypk(1);
			foreach ($saaja as $key => $sahkoposti) {
				$subject = Yii::t('main', 'Ilmoitus toistuvien työvuorojen päättymisestä');
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				if (!$mail->send()) {
					echo 'Mail send error to ' . $sahkoposti;

					// <-- LOG
					$log = new Log;
					$log->log_category   = 1; // 1-email
					$log->email_to     = $sahkoposti;
					$log->email_subject  = $subject;
					$log->email_message  = json_encode($message);
					$log->save();
					//     LOG -->

				}
			}
		}
		print_r($message);
		// Valmistetaan viesti -->

	}
	// lmoitus toistuvien työvuorojen päätymisestä -->

	// <-- Vinkkit autopoistaminen
	if (isset($asetukset->tietosuoja_vinkki_sailyttaminen)) {
		$criteria = new CDbCriteria;
		//$criteria->select = "";
		$criteria->condition = " 
		DATE(time) < '" . date("Y-m-d", strtotime('-' . $asetukset->tietosuoja_vinkki_sailyttaminen . ' day')) . "'
		AND token!=''
	";
		VinkkiExtranet::model()->deleteAll($criteria);
	}
	//     Vinkkit autopoistaminen -->


	// <-- Lasku Netvisor

	//    Lasku Netvisor -->

	// <-- Asiakas passiviseksi paivamaaran mukaan
	$criteria = new CDbCriteria;
	$criteria->condition = " 
	DATE(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y')) = CURDATE()
	AND aktiivinen=1
	";
	Asiakkaat::model()->updateAll(array('aktiivinen' => '0'), $criteria);
	//     Asiakas passiviseksi paivamaaran mukaan -->

	unset($_SESSION['domain']);
}

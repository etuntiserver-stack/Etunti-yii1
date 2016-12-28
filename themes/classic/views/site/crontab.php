<?php
 header("Content-Type: text/html; charset=utf-8");

 if($pass == 'Estrom2016!')
 {




   $koodi_aktiivinen = 1;
   if( $_SERVER['REMOTE_ADDR'] == '::1' or $_SERVER['REMOTE_ADDR'] == '127.0.0.1' )
   	$list = Domainit::model()->findAll(" domain!='defdb' AND domain='demo' ");
   else
   	$list = Domainit::model()->findAll(" domain!='defdb' ");

   foreach($list as $d)
   {

	
	Yii::app()->db1->setActive(false);
	Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname='.$d->domain;
        if( $_SERVER['REMOTE_ADDR'] == '::1' or $_SERVER['REMOTE_ADDR'] == '127.0.0.1' )
        {
      	    Yii::app()->db1->username = 'root';
            Yii::app()->db1->password = '';
    	} else {
      	    Yii::app()->db1->username = 'mulgikapsas';
            Yii::app()->db1->password = 'KristinA1';
	}
	Yii::app()->db1->setActive(true);


	$asetukset = Asetukset::model()->findByPk(1);
	$aikavali_halytys = 15;
	if(!empty($asetukset->aikavali_halytys))
	$aikavali_halytys = $asetukset->aikavali_halytys;

	$ft = FirmanTiedot::model()->findByPk(1);

	// <-- Ilmoitus määräajan ylittäneistä kohteista
		$message 	= '';
		$arr 		= array();
		$forMessage	= array();

		$criteria=new CDbCriteria;
		$criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(CONCAT(pvm,loppu), '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < (NOW() - INTERVAL $aikavali_halytys MINUTE)
			AND ilmoitus_avoimista_kohteesta=0
			AND kohde IN
			(
			SELECT kohdenID FROM sivexkuitti
			WHERE status=1 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = DATE_FORMAT(STR_TO_DATE(t.pvm, '%d.%m.%Y'), '%Y-%m-%d')
			AND tid=t.tid
			)
			AND kohde!=0
		";
		$m = Tyovuoroot::model()->findAll($criteria);

		if(isset($m[0]))
		{
			echo '<h2>'.strtoupper($ft->tyonantaja).'</h2>';
			echo '<h2>'.Yii::t('main', 'Avoimet kohteet').' '.date("d.m.Y H:i").'</h2>';


		  foreach($m as $data)
		  {

			$bod 		= '';
			$osoite 	= '';
			$tekijan_nimi 	= '';
			$tyoryhmaForArr = '';

			$k = Kohteet::model()->findbypk($data->kohde);
			if(isset($k->osoite))
				$osoite = Yii::t('main', 'Osoite').': <b>'. $k->osoite.'</b><br>';

			$tt = Tyontekijat::model()->findbypk($data->tid);
			if(isset($tt->id)){
				$tekijan_nimi = Yii::t('main', 'Työntekijä').':  <b>'.$this->etuSukunimi($tt->id).'</b><br>';
				$tyoryhmaForArr = $tt->tyoryhma;
			}


			$bod 	.= $tekijan_nimi.$osoite;
			$bod 	.= Yii::t('main', 'Lopetusajaksi oli määritelty').': <b>'.$data->pvm.' '.$data->loppu.'</b>';
			$bod 	.= '<br>';


			$arr[$tyoryhmaForArr] 	= $tyoryhmaForArr;
			array_push($forMessage, array(
						'tyoryhma'=>$tyoryhmaForArr, 
						'message'=>$bod) 
			);

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			Tyovuoroot::model()->updatebypk($data->id,array('ilmoitus_avoimista_kohteesta'=>1));

		
		  }
		}



		if(count($arr) > 0 and $asetukset->ilmoitus_avoimista_kohteesta_sahkopostiin == 1)
		{

			// <-- Järjestelmanvalvojan kuluvia ryhmiä
			$criteria = new CDbCriteria();
			$criteria->order = " value ";
			$criteria->condition = " 
				select_type='tyoryhma'	
				AND value2!=''
			";
			$valikot = Valikkoot::model()->findAll($criteria);

			foreach($arr as $ryhma){
				
				foreach($valikot as $data){

					if($data->value == $ryhma){

						$sahkopostiArray	= array();
						$mailMessage 	= '';
						$mailMessage 	.= '<h3>'.Yii::t('main', 'Työryhmä').' '.$ryhma.'</h3>';

						foreach($forMessage as $key=>$value){
							if($value['tyoryhma'] == $data->value)
							$mailMessage .= $value['message'].'<br>';
						}

						$admin_ids = json_decode($data->value2);
						if(is_array($admin_ids)){
							foreach($admin_ids as $adm_id){
								$administrators = Administrators::model()->findByPk($adm_id);
								if(isset($administrators->adm_email) and !empty($administrators->adm_email))
								array_push($sahkopostiArray, $administrators->adm_email);
							}
						}

						if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
						{
							$subject = Yii::t('main', 'Ilmoitus avoimista kohteesta '.date("d.m.Y H:i"));
							$mail = new YiiMailer();
							$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
							$mail->setTo($sahkopostiArray);
							$mail->setSubject($subject);
							$mail->setBody($mailMessage);
							$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= implode(",", $sahkopostiArray);
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
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
	// Ilmoitus määräajan ylittäneistä kohteista -->



	// <-- ilmoitus_myohastyneista_kohteesta
		$message 	= '';
		$arr 		= array();
		$forMessage	= array();

		$criteria=new CDbCriteria;
		$criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
			AND ilmoitus_myohastyneista_kohteesta=0
			AND DATE_ADD(DATE_FORMAT(STR_TO_DATE(CONCAT(pvm, alku), '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), INTERVAL $aikavali_halytys MINUTE) < NOW() 
			AND kohde NOT IN 
			(SELECT kohdenID FROM sivexkuitti 
			WHERE DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = DATE_FORMAT(STR_TO_DATE(t.pvm, '%d.%m.%Y'), '%Y-%m-%d')
			AND tid=t.tid
			)
			AND kohde!=0 AND tyoajanmerkinta='Normaali/'
			AND kohde IN (SELECT id FROM sivex_kohdet WHERE osoite NOT LIKE '%matka%' AND osoite NOT LIKE '%lounastauko%' )
		";
		$m = Tyovuoroot::model()->findAll($criteria);

		if(isset($m[0]))
		{
			echo '<h2>'.strtoupper($ft->tyonantaja).'</h2>';
			echo '<h2>'.Yii::t('main', 'Myöhästyneet kohteet').' '.date("d.m.Y H:i").'</h2><br>';

		  foreach($m as $data)
		  {

			$bod 		= '';
			$osoite 	= '';
			$tekijan_nimi 	= '';
			$tyoryhmaForArr = '';

			$k = Kohteet::model()->findbypk($data->kohde);
			if(isset($k->osoite))
				$osoite = Yii::t('main', 'Osoite').': <b>'. $k->osoite.'</b><br>';

			$tt = Tyontekijat::model()->findbypk($data->tid);
			if(isset($tt->id)){
				$tekijan_nimi = Yii::t('main', 'Työntekijä').':  <b>'.$this->etuSukunimi($tt->id).'</b><br>';
				$tyoryhmaForArr = $tt->tyoryhma;
			}


			$bod 	.= $tekijan_nimi.$osoite;
			$bod 	.= Yii::t('main', 'Aloitusajaksi oli määritelty').': '.$data->pvm.', '.$data->alku;
			$bod 	.= '<br>';


			$arr[$tyoryhmaForArr] 	= $tyoryhmaForArr;
			array_push($forMessage, array(
						'tyoryhma'=>$tyoryhmaForArr, 
						'message'=>$bod) 
			);

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			Tyovuoroot::model()->updatebypk($data->id,array('ilmoitus_myohastyneista_kohteesta'=>1));
		
		  }
		}


		if(count($arr) > 0 and $asetukset->ilmoitus_myohastyneista_kohteesta_sahkopostiin == 1)
		{

			// <-- Järjestelmanvalvojan kuluvia ryhmiä
			$criteria = new CDbCriteria();
			$criteria->order = " value ";
			$criteria->condition = " 
				select_type='tyoryhma'	
				AND value2!=''
			";
			$valikot = Valikkoot::model()->findAll($criteria);

			foreach($arr as $ryhma){
				
				foreach($valikot as $data){

					if($data->value == $ryhma){

						$sahkopostiArray	= array();
						$mailMessage 	= '';
						$mailMessage 	.= '<h3>'.Yii::t('main', 'Työryhmä').' '.$ryhma.'</h3>';

						foreach($forMessage as $key=>$value){
							if($value['tyoryhma'] == $data->value)
							$mailMessage .= $value['message'].'<br>';
						}

						$admin_ids = json_decode($data->value2);
						if(is_array($admin_ids)){
							foreach($admin_ids as $adm_id){
								$administrators = Administrators::model()->findByPk($adm_id);
								if(isset($administrators->adm_email) and !empty($administrators->adm_email))
								array_push($sahkopostiArray, $administrators->adm_email);
							}
						}

						if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
						{
							$subject = Yii::t('main', 'Ilmoitus myöhästyneistä kohteesta '.date("d.m.Y H:i"));
							$mail = new YiiMailer();
							$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
							$mail->setTo($sahkopostiArray);
							$mail->setSubject();
							$mail->setBody($mailMessage);
							$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= implode(",", $sahkopostiArray);
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
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
	// ilmoitus_myohastyneista_kohteesta -->




	// <-- merkkipaivailmoitukset
		$message 	= '';
		$arr 		= array();
		$forMessage	= array();

		$criteria=new CDbCriteria;
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

		if(isset($tt[0]))
		{
			echo '<h2>'.strtoupper($ft->tyonantaja).'</h2>';
			echo '<h2>'.Yii::t('main', 'Ilmoitus merkkipäivästä').' '.date("d.m.Y H:i").'</h2><br>';

		   foreach($tt as $data)
		   {

			$bod 		= '';
			$tyoryhmaForArr = $data->tyoryhma;

			$bod 	.= Yii::t('main', 'Merkkipäivä').': <b>'.date("d.m.Y", strtotime($data->tekijan_henkilotunnus)).', '.$this->etuSukunimi($data->id).'</b><br>';

			$arr[$tyoryhmaForArr] 	= $tyoryhmaForArr;
			array_push($forMessage, array(
						'tyoryhma'=>$tyoryhmaForArr, 
						'message'=>$bod) 
			);

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			Tyontekijat::model()->updateByPk($data->id, array('ilmoitus_merkkipaivasta_vuosi'=>date("Y")));
		   }

		}


		if(count($arr) > 0 and $asetukset->ilmoitus_merkkipaivasta == 1)
		{

			// <-- Järjestelmanvalvojan kuluvia ryhmiä
			$criteria = new CDbCriteria();
			$criteria->order = " value ";
			$criteria->condition = " 
				select_type='tyoryhma'	
				AND value2!=''
			";
			$valikot = Valikkoot::model()->findAll($criteria);

			foreach($arr as $ryhma){
				
				foreach($valikot as $data){

					if($data->value == $ryhma){

						$sahkopostiArray	= array();
						$mailMessage 	= '';
						$mailMessage 	.= '<h3>'.Yii::t('main', 'Työryhmä').' '.$ryhma.'</h3>';

						foreach($forMessage as $key=>$value){
							if($value['tyoryhma'] == $data->value)
							$mailMessage .= $value['message'].'<br>';
						}

						$admin_ids = json_decode($data->value2);
						if(is_array($admin_ids)){
							foreach($admin_ids as $adm_id){
								$administrators = Administrators::model()->findByPk($adm_id);
								if(isset($administrators->adm_email) and !empty($administrators->adm_email))
								array_push($sahkopostiArray, $administrators->adm_email);
							}
						}

						if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
						{
							$subject = Yii::t('main', 'Ilmoitus merkkipäivästä '.date("d.m.Y H:i"));
							$mail = new YiiMailer();
							$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
							$mail->setTo($sahkopostiArray);
							$mail->setSubject($subject);
							$mail->setBody($mailMessage);
							$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= implode(",", $sahkopostiArray);
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
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
	if(isset($asetukset->ilmoitus_toistuvien_tyovuorojen_paattymisesta) and $asetukset->ilmoitus_toistuvien_tyovuorojen_paattymisesta == 1 )
	{
		$criteria=new CDbCriteria;
		//$criteria->select = "";
		$criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pto, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN (CURDATE() - INTERVAL '".$asetukset->ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen."' DAY) 
			AND CURDATE()
			AND ilmoitus_paattymisesta!=1
		";
		$toistuvat = ToistuvatTyovuorot::model()->findAll($criteria);

		$m = '';
		$message = '';

		foreach($toistuvat as $data)
		{



			$k = Kohteet::model()->findbypk($data->kohde);
			$osoite = '';
			if(isset($k->osoite)) $osoite = $k->osoite;

			$t = Tyontekijat::model()->findbypk($data->tid);
			$tekijan_nimi = '';
			if(isset($t->tekijan_nimi)) $tekijan_nimi = $this->etuSukunimi($t->id);

			$m .= '<hr><b>'.Yii::t('main', 'Osoite').':</b> '.$osoite.'<br>';
			$m .= '<b>'.Yii::t('main', 'Aikaväli').':</b> '.$data->pfrom.'-'.$data->pto.'<br>';
			$m .= '<b>'.Yii::t('main', 'Klo').':</b> '.$data->alku.'-'.$data->loppu.'<br>';
			$m .= '<b>'.Yii::t('main', 'Työntekijä').':</b> '.$tekijan_nimi.'<br>';

			if(!empty($data->tyopaari))
			{
				$tyopari = json_decode($data->tyopaari);
				foreach($tyopari as $tid)
				{
					$tekijan_nimi2 = '';
					$t2 = Tyontekijat::model()->findbypk($tid);
					if(isset($t2->tekijan_nimi) and $tid != $data->tid)
					{
						$tekijan_nimi2 = $this->etuSukunimi($t2->id);
						$m .= '<b>'.Yii::t('main', 'Työpari').':</b> '.$tekijan_nimi2.'<br>';
					}
				}
			}

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			ToistuvatTyovuorot::model()->updateByPk($data->id, array('ilmoitus_paattymisesta'=>1));
		}


			// <-- Valmistetaan viesti
			if(!empty($m))
			{
				$message .= '<h2>'.Yii::t('main', 'Ilmoitus toistuvien työvuorojen päättymisestä').'</h2>';
				$message .= $m;
			}

			$saaja = array();
			$s = explode("\n", $asetukset->ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat);
			foreach($s as $sp)
				if(!empty($sp))
					array_push($saaja, $sp);

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' and count($saaja) > 0 and !empty($message) )
			{
			   foreach($saaja as $key=>$sahkoposti)
			   {		
				$subject = Yii::t('main', 'lmoitus toistuvien työvuorojen päättymisestä');
				$mail = new YiiMailer();
				$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
				$mail->setTo($sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				if(!$mail->send()){
					echo 'Mail send error to '.$sahkoposti;

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				}
			   }
			}
			print_r($message);
			// Valmistetaan viesti -->

	}
	// lmoitus toistuvien työvuorojen päättymisestä -->



	// <-- Lasku Netvisor

	//    Lasku Netvisor -->


   }
exit;

 }

?>

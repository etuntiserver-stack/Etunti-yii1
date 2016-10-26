<?php
 header("Content-Type: text/html; charset=utf-8");

 if($pass == 'Estrom2016!')
 {




   $koodi_aktiivinen = 1;

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

	// <-- ilmoitus_avoimista_kohteesta ylittaneet
		$message = '';

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
			$message .= '<h2>'.strtoupper($ft->tyonantaja).'</h2>';
			$message .= '<h2>'.Yii::t('main', 'Avoimet kohteet').' '.date("d.m.Y H:i").'</h2><br>';
		  foreach($m as $data)
		  {
			$message .= '<p>';
			$k = Kohteet::model()->findbypk($data->kohde);
			if(isset($k->osoite))
			$message .= $k->osoite.', ';
			$tt = Tyontekijat::model()->findbypk($data->tid);
			if(isset($tt->tekijan_nimi))
			$message .= $tt->tekijan_nimi;
			$message .= '<br>';

			$message .= Yii::t('main', 'Lopetusajaksi oli määritelty').': '.$data->pvm.' '.$data->loppu;
			$message .= '</p>';


			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			Tyovuoroot::model()->updatebypk($data->id,array('ilmoitus_avoimista_kohteesta'=>1));

		
		  }
		}

		if(!empty($message))
			echo $message;

		$saaja = ''; // $asetukset->sahkoposti
		if(!empty($ft->sahkoposti) and !empty($message) and $asetukset->ilmoitus_avoimista_kohteesta_sahkopostiin == 1)
		{
			$saaja = $ft->sahkoposti; // $ft->sahkoposti
			echo $saaja.'<br>';
			echo $message;

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			{
			$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($saaja);
			$mail->setSubject(Yii::t('main', 'Ilmoitus avoimista kohteesta '.date("d.m.Y H:i")));
			$mail->setBody($message);
			$mail->send();
			}


		echo '<hr>';

		}
	// ilmoitus_avoimista_kohteesta -->



	// <-- ilmoitus_myohastyneista_kohteesta
		$message = '';

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
			$message .= '<h2>'.strtoupper($ft->tyonantaja).'</h2>';
			$message .= '<h2>'.Yii::t('main', 'Myöhästyneet kohteet').' '.date("d.m.Y H:i").'</h2><br>';
		  foreach($m as $data)
		  {

			$message .= '<p>';
			$k = Kohteet::model()->findbypk($data->kohde);
			if(isset($k->osoite))
			$message .= $k->osoite.', ';
			$tt = Tyontekijat::model()->findbypk($data->tid);
			if(isset($tt->tekijan_nimi))
			$message .= $tt->tekijan_nimi;
			$message .= '<br>';

			$message .= Yii::t('main', 'Aloitusajaksi oli määritelty').': '.$data->pvm.' '.$data->alku;
			$message .= '</p>';


			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			Tyovuoroot::model()->updatebypk($data->id,array('ilmoitus_myohastyneista_kohteesta'=>1));
		
		  }
		}

		if(!empty($message))
			echo $message;

		$saaja = ''; // $asetukset->sahkoposti
		if(!empty($ft->sahkoposti) and !empty($message) and $asetukset->ilmoitus_myohastyneista_kohteesta_sahkopostiin == 1)
		{
			$saaja = $ft->sahkoposti; // $ft->sahkoposti
			echo $saaja.'<br>';


			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			{
			$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($saaja);
			$mail->setSubject(Yii::t('main', 'Ilmoitus myöhästyneistä kohteesta '.date("d.m.Y H:i")));
			$mail->setBody($message);
			$mail->send();
			}


		echo '<hr>';

		}
	// ilmoitus_myohastyneista_kohteesta -->




	// <-- merkkipaivailmoitukset
	if(isset($asetukset->merkkipaivailmoitukset_sahkoposti) and !empty($asetukset->merkkipaivailmoitukset_sahkoposti))
	{
		$criteria=new CDbCriteria;
		$criteria->select = "id, tekijan_nimi, 
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

		$message = '';
		foreach($tt as $data)
		{
			$message .= '#('.$data->id.'), '.$data->tekijan_nimi.'&nbsp;&nbsp;'.date("d.m.Y", strtotime($data->tekijan_henkilotunnus)).'<br>';
			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			Tyontekijat::model()->updateByPk($data->id, array('ilmoitus_merkkipaivasta_vuosi'=>date("Y")));
		}

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' and !empty($message) )
			{
			$saaja = $asetukset->merkkipaivailmoitukset_sahkoposti;
			$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($saaja);
			$mail->setSubject(Yii::t('main', 'Ilmoitus merkkipäivästä'));
			$mail->setBody($message);
			$mail->send();
			}


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
			if(isset($t->tekijan_nimi)) $tekijan_nimi = $t->tekijan_nimi;

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
						$tekijan_nimi2 = $t2->tekijan_nimi;
						$m .= '<b>'.Yii::t('main', 'Työpari').':</b> '.$tekijan_nimi2.'<br>';
					}
				}
			}

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			ToistuvatTyovuorot::model()->updateByPk($data->id, array('ilmoitus_paattymisesta'=>1));
		}

			if(!empty($m))
			{
				$message .= '<h1>'.Yii::t('main', 'Ilmoitus toistuvien työvuorojen päättymisestä').'</h1>';
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
				$mail = new YiiMailer();
				$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
				$mail->setTo($sahkoposti);
				$mail->setSubject(Yii::t('main', 'Ilmoitus merkkipäivästä'));
				$mail->setBody($message);
				if(!$mail->send())
				echo 'Mail send error to '.$sahkoposti;
			   }
			}
			print_r($message);

	}
	// lmoitus toistuvien työvuorojen päättymisestä -->







   }
exit;

 }

?>

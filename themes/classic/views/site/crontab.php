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


			if($koodi_aktiivinen == 1)
			{
				Tyovuoroot::model()->updatebypk($data->id,array('ilmoitus_avoimista_kohteesta'=>1));
			}
		
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

			if($koodi_aktiivinen == 1)
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


			if($koodi_aktiivinen == 1)
			{
				Tyovuoroot::model()->updatebypk($data->id,array('ilmoitus_myohastyneista_kohteesta'=>1));
			}
		
		  }
		}

		if(!empty($message))
			echo $message;

		$saaja = ''; // $asetukset->sahkoposti
		if(!empty($ft->sahkoposti) and !empty($message) and $asetukset->ilmoitus_myohastyneista_kohteesta_sahkopostiin == 1)
		{
			$saaja = $ft->sahkoposti; // $ft->sahkoposti
			echo $saaja.'<br>';


			if($koodi_aktiivinen == 1)
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






   }


 }

?>

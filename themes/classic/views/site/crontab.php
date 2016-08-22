<?php
 header("Content-Type: text/html; charset=utf-8");

 if($pass == 'Estrom2016!')
 {



   $aikavali = 15;
   $aktiivinen = 0;

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

		$ft = FirmanTiedot::model()->findByPk(1);

		$criteria=new CDbCriteria;
		$criteria->select = "
			( 
			   SELECT id FROM sivex_tvuoro 
			   WHERE 
			   DATE_FORMAT(STR_TO_DATE(CONCAT(pvm,loppu), '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < (NOW() - INTERVAL $aikavali MINUTE)
			   AND ilmoitus_avoimista_kohteesta=0
			   AND kohde=t.kohdenID
			   AND tid=t.tid
			) as tvid, t.*
 
		";

		$criteria->condition = " 
			kohdenID !=0
			AND kohdenID IN ( 
			   SELECT kohde FROM sivex_tvuoro 
			   WHERE 
			   DATE_FORMAT(STR_TO_DATE(CONCAT(pvm,loppu), '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < (NOW() - INTERVAL $aikavali MINUTE)
			   AND ilmoitus_avoimista_kohteesta=0
			   AND tid=t.tid
			)
			AND status=1 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN DATE_ADD(CURDATE(), INTERVAL -1 DAY) AND CURDATE()
		";


		$message = '';
		$m = Mobile::model()->findAll($criteria);
		if(isset($m[0]))
		{
			$message .= '<h2>'.strtoupper($ft->tyonantaja).'</h2>';
			$message .= '<h2>'.Yii::t('main', 'Avoimet kohteet').' '.date("d.m.Y H:i").'</h2><br>';
		  foreach($m as $data)
		  {
			$tv = Tyovuoroot::model()->findByPk($data->tvid);		
			$message .= '<p>'.$data->kohde_kannasta.', '.$data->tekijan_nimi.'<br>';
			$message .= Yii::t('main', 'Lopetusajaksi oli määritelty').': '.$tv->pvm.' '.$tv->loppu;
			$message .= '</p>';
			if($aktiivinen == 1)
			$tv = Tyovuoroot::model()->updateByPk($data->tvid,array('ilmoitus_avoimista_kohteesta'=>1));		
		  }
		}


		$saaja = ''; // $asetukset->sahkoposti
		if(!empty($ft->sahkoposti) and !empty($message))
		{
			$saaja = 'laptopsr@gmail.com'; // $ft->sahkoposti
			echo $saaja.'<br>';
			echo $message;

			if($aktiivinen == 1)
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


   }


 }

?>

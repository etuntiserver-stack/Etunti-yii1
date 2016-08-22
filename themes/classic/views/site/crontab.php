<?php

 if($pass == 'Estrom2016!')
 {




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


		$criteria=new CDbCriteria;
		$criteria->condition = " 
			kohdenID IN ( 
			   SELECT kohde FROM sivex_tvuoro 
			   WHERE 
			   DATE_FORMAT(STR_TO_DATE(CONCAT(pvm,loppu), '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < (NOW() - INTERVAL 15 MINUTE)
			   AND ilmoitus_avoimista_kohteesta=0
			   AND tid=t.tid
			)
			AND status=1 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN DATE_ADD(CURDATE(), INTERVAL -1 DAY) AND CURDATE()
		";

		$hailytys = array();
		$m = Mobile::model()->findAll($criteria);
		if(isset($m[0]))
		{
		  foreach($m as $data)
		  {
			$hailytys[$data->tid] = array($data->id, $data->aloitan, $data->kohdenID);			
		  }
		}

print_r($hailytys);

		$asetukset = FirmanTiedot::model()->findByPk(1);
		$saaja = ''; // $asetukset->sahkoposti
		if(!empty($asetukset->sahkoposti))
		{
			$saaja = 'laptopsr@gmail.com'; // $asetukset->sahkoposti
			echo $saaja.'<br>';
			$message = '';
	/*
			$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($saaja);
			$mail->setSubject($tt->tekijan_nimi.' '.Yii::t('main', 'unohti kirjaudua ulos kohteesta'));
			$mail->setBody($message);
			$mail->setAttachment($path.'/'.$file);
			$mail->send();
	*/

		}

   }


 }

?>

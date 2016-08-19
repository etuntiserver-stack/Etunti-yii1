<?php

 if($pass == 'Estrom2016!')
 {



$domain = '';
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
	echo $asetukset->johtaja.'<br>';
  
}


 }

?>

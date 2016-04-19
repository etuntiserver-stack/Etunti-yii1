<?php

		$site = Yii::app()->createController('Site');
		$eilasketa = $site[0]->eiLasketa();


		$criteria = new CDbCriteria();

        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";

        	$criteria->condition = " 
			$kohde_tid = '".$id."'
			AND loppu!='' and alku!='' 
			AND $eilasketa
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' 
		";


	  	$su = Tyovuoroot::model()->find($criteria);

		echo $su->l_tunnit;
?>

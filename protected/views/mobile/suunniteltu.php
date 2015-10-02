<?php


		$criteria = new CDbCriteria();

        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";

        	$criteria->condition = " 
			$kohde_tid = '".$id."'
			AND loppu!='' and alku!='' 
			AND tyoajanmerkinta NOT LIKE '%Ei lasketa%'
		";

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition (" 

			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' 

		");

	  	$su = Tyovuoroot::model()->find($criteria);

		echo $su->l_tunnit;
?>

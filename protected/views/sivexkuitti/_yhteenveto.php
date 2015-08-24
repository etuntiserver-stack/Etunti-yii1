<?php
/* @var $this SivexkuittiController */
/* @var $data Sivexkuitti */

		$total_l = 0;
		$total_t = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as l_tunnit,
		t.*";

        	$criteria->condition = " id not in (select kid from sivexkuitti_repaired) ";
        	$criteria->addCondition ( " tid = '".$data->tid."' " );

		if(Yii::app()->session['etsi_pvm'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".Yii::app()->session['etsi_pvm']."' ");

		$tot = Sivexkuitti::model()->findAll($criteria);

		  foreach($tot as $val)
		  {
			$total_l += $val->l_tunnit;
		  }


       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as t_tunnit,
		t.*";

        	$criteria->condition = " kid not in (select id from sivexkuitti) ";
        	$criteria->addCondition ( " tid = '".$data->tid."' " );

		if(Yii::app()->session['etsi_pvm'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".Yii::app()->session['etsi_pvm']."' ");

		$tot = Toteutuneet::model()->findAll($criteria);

		  foreach($tot as $val)
		  {
			$total_t += $val->t_tunnit;
		  }

		$total = $total_t + $total_l;

?>

<tr>

	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo sprint($data->l_tunnit); ?></td>
	<td><?php echo sprint($total); ?></td>

</tr>

	



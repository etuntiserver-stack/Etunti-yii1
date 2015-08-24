<?php
/* @var $this SivexkuittiController */
/* @var $data Sivexkuitti */

		$total_l = 0;
		$total_t = 0;
		$totalIlta = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))) as l_tunnit,
		t.id";

        	$criteria->addCondition ( " tid = '".$data->tid."' " );

		if(Yii::app()->session['Lounastauko'])
	        $criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
	        $criteria->addCondition (" status != '2' ");

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$lu = Sivexkuitti::model()->findAll($criteria);

		foreach($lu as $val)
		{

			$tot = Toteutuneet::model()->find(" kid = '".$val->id."' ");
			if(isset($tot['id']))
			$val->l_tunnit = (strtotime($tot['loppui'])-strtotime($tot['aloitan']));

			$total_l += $val->l_tunnit.'<br>';

		}


		$total = $total_l;

?>

<tr>

	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo sprint($data->l_tunnit); ?></td>
	<td><?php echo sprint($total); ?></td>
	<td><?php echo sprint($totalIlta); ?></td>


</tr>

	



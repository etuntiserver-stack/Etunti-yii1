<?php
/* @var $this SivexkuittiController */
/* @var $data Sivexkuitti */



       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as t_tunnit,
		t.*";

        	//$criteria->condition = " id not in (select id from sivexkuitti) ";
	        $criteria->addCondition (" tid = '".$data->tid."'");

		if(Yii::app()->session['etsi_pvm'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".Yii::app()->session['etsi_pvm']."' ");


		$tot = Toteutuneet::model()->findAll($criteria);

		$total = $data->l_tunnit;

		//if(isset($tot->t_tunnit))
		//$total = $tot->t_tunnit+$data->l_tunnit;



?>

<tr>

	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo sprint($total); 

		foreach($tot as $val){
		echo $val->tekijan_nimi." ".$val->t_tunnit.'<br>';
		}

?></td>


</tr>

	



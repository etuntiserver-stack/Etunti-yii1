<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$total_l 	= 0;
		$total_t 	= 0;
		$totalTp	= 0;
		$totalIlta 	= 0;
		$totalYo 	= 0;
		$totalSu	= 0;
		$tp		= 0;
		$al		= '';
		$lop		= '';
		$total_sunniteltu = 0;
		$total 		= 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))) as l_tunnit,
		t.id,t.aloitan,t.loppui";

        	$criteria->condition = "  tid = '".$data->tid."' and aloitan !='' and loppui !='' and status=3 ";
		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$lu = Mobile::model()->findAll($criteria);

		foreach($lu as $val)
		{

			$tot = Toteutuneet::model()->find(" kid = '".$val->id."' ");

			if(isset($tot['id']))
			{
			  $val->l_tunnit = (strtotime($tot['loppui'])-strtotime($tot['aloitan']));
			  $al = explode(" ",$tot['aloitan']);
			  $lop = explode(" ",$tot['loppui']);
			} else {
			  $al = explode(" ",$val->aloitan);
			  $lop = explode(" ",$val->loppui);
			}

			// Toteutuneet
			$total_l += $val->l_tunnit;
			// Ilta
			$totalIlta += $this->ilta($al,$lop);
			// Yo
			$totalYo += $this->yo($al,$lop);
			// Suunnuntai
			if(date('N', strtotime($al[0])) == 7)
			$totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
			// Työpäiviä
			if($tp != $al[0])
			{
			  if((strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1])) != 0)
				$totalTp += 1;
			}
			$tp = $al[0];
		}

		if($total_l != 0)
		$total = $this->sprint($total_l).'<br>('.$this->num($total_l).')';

		if($totalIlta != 0)
		$totalIlta = $this->sprint($totalIlta).'<br>('.$this->num($totalIlta).')';

		if($totalYo != 0)
		$totalYo = $this->sprint($totalYo).'<br>('.$this->num($totalYo).')';

		if($totalSu != 0)
		$totalSu = $this->sprint($totalSu).'<br>('.$this->num($totalSu).')';



  $matka = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>Yii::app()->session['from'],
		'to'=>Yii::app()->session['to'],
		'tid'=>$data->tid
		),true);

		if($matka != 0)
		$matka = $this->sprint($matka).'<br>('.$this->num($matka).')';
?>

<tr>

	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo $totalTp; ?></td>
	<td><?php echo $matka; ?></td>
	<td><?php echo $total; ?></td>
	<td><?php echo $totalIlta; ?></td>
	<td><?php echo $totalYo; ?></td>
	<td><?php echo $totalSu; ?></td>
	<td><?php echo ''; ?></td>
	<td><?php echo ''; ?></td>
	<td><?php echo ''; ?></td>
</tr>

	



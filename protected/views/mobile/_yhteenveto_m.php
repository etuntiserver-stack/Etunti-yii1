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

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))) as l_tunnit,
		t.id,t.aloitan,t.loppui";

        	$criteria->condition = "  status = '2' and tid = '".$data->tid."' and aloitan !='' and loppui !='' ";

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


		$total = $total_l;

?>

<tr>

	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>

	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	$total_sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$data->tid,'kohde_tid'=>'tid'),true);
	echo '<td>'.$this->sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo $this->sprint($data->l_tunnit); ?></td>
	<td><?php echo $this->sprint($total); ?></td>
	<td><?php echo $totalTp; ?></td>
	<td><?php echo $this->sprint($totalIlta); ?></td>
	<td><?php echo $this->sprint($totalYo); ?></td>
	<td><?php echo $this->sprint($totalSu); ?></td>
</tr>

	



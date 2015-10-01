<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$total_l 	= 0;
		$total_t 	= 0;
		$total_sunniteltu = 0;

       		$cr1 = new CDbCriteria();
		$this->totLu($cr1,$data->kohdenID,$data->kohde_kannasta);
		$cr1->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
		$lu = Mobile::model()->find($cr1);

       		$cr2 = new CDbCriteria();
		$this->totLu($cr2,$data->kohdenID,$data->kohde_kannasta);
		$tot = Toteutuneet::model()->find($cr2);


?>

<tr>

	<td><?php echo CHtml::encode($data->kohde_kannasta); ?></td>

	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	$total_sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$data->kohdenID,'kohde_tid'=>'kohde'),true);
	echo '<td>'.sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo sprint($data->l_tunnit); ?></td>
	<td><?php echo sprint($lu->l_tunnit+$tot->l_tunnit); ?></td>
	<td><?php echo $data->kpl; ?></td>

</tr>

	



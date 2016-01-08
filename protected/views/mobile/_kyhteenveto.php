<?php
/* @var $this MobileController */
/* @var $data Mobile */

	$total_l 	= 0;
	$total_t 	= 0;
	$total_sunniteltu = 0;

	$cr1 = new CDbCriteria();
	$this->totLu($cr1,$kohdenID);
	$lu = Mobile::model()->find($cr1);

	// tot
	$cr2 = new CDbCriteria();
	$this->totLu($cr2,$kohdenID);
	$cr2->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$tot1 = Mobile::model()->find($cr2);

	$cr3 = new CDbCriteria();
	$this->totLu($cr3,$kohdenID);
	$tot2 = Toteutuneet::model()->find($cr3);

	//kpl
	$kpl = 0;
	$kpl1 = 0;
	$kpl2 = 0;
	$cr4 = new CDbCriteria();
	$this->totKpl($cr4,$kohdenID);
	$cr4->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$k = Mobile::model()->find($cr4);

	if(isset($k->count))
	$kpl1 += $k->count;

	$cr5 = new CDbCriteria();
	$this->totKpl($cr5,$kohdenID);
	$k = Toteutuneet::model()->find($cr5);

	if(isset($k->count))
	$kpl2 += $k->count;

	$kpl = $kpl1+$kpl2;
?>

<tr>

	<td>
		<b class="link fa fa-caret-square-o-down showKuka text-danger" data-toggle="collapse" id="<?php echo 'kohde_'.$kohdenID; ?>" for="<?php echo 'kohde_'.$kohdenID; ?>" data-target="<?php echo '#kohdeshow_'.$kohdenID; ?>"></b>&nbsp;

		<?php echo CHtml::encode($kohde_kannasta); ?>

		<div class="collapse col-sm-offset-1" id="<?php echo 'kohdeshow_'.$kohdenID; ?>">
		<br>
		<div id="<?php echo 'showtyo_'.$kohdenID; ?>"></div>
		</div>

	</td>

	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	$total_sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$kohdenID,'kohde_tid'=>'kohde','from'=>$from,'to'=>$to),true);
	echo '<td>'.$this->sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo $this->sprint($lu->l_tunnit); ?></td>
	<td><?php echo $this->sprint($tot1->l_tunnit+$tot2->l_tunnit); ?></td>
	<td><?php echo $kpl; ?></td>

</tr>

	




<?php
/*

<tr>

	<td>
		<span class="link caret showKuka" data-toggle="collapse" id="<?php echo 'kohde_'.$data->id; ?>" for="<?php echo 'kohde_'.$data->kohde_kannasta; ?>" data-target="<?php echo '#kohdeshow_'.$data->id; ?>"></span>

		<?php echo CHtml::encode($kohde_kannasta); ?>

		<div class="collapse col-sm-offset-1" id="<?php echo 'kohdeshow_'.$data->id; ?>">
		<br>
		<div id="<?php echo 'showtyo_'.$data->id; ?>"></div>
		</div>

	</td>


	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	$total_sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$data->kohdenID,'kohde_tid'=>'kohde'),true);
	echo '<td>'.$this->sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo ''; ?></td>
	<td><?php echo $this->sprint($lu->l_tunnit+$tot->l_tunnit); ?></td>
	<td><?php echo $data->kpl; ?></td>

</tr>

*/
?>


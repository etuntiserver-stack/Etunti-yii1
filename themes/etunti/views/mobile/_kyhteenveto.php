<?php
/* @var $this MobileController */
/* @var $data Mobile */

?>

<tr>
	<td class="col5"><?=$asiakas?></td>
	<td class="col1">
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
	echo '<td class="col2">'.$this->sprint($sunniteltu).'</td>';
	}
	?>

	<td class="col3"><?=($luetut > 0)?$this->sprint($luetut):'00:00'?></td>
	<td class="col4"><?=($toteutuneet > 0)?$this->sprint($toteutuneet):'00:00'?></td>
	<td class="col5"><?php echo $kpl; ?></td>

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


<?php
/* @var $this MobileController */
/* @var $data Mobile */

?>

<tr>

	<td><?php echo $data->kohde_kannasta; ?></td>
	<td><?php echo $data->tekijan_nimi; ?></td>
	<td><?php echo date("d.m", strtotime($data->aloitan)).
	', '.date("H:i", strtotime($data->aloitan)).'-'.date("H:i", strtotime($data->loppui));
	?></td>
	<td><?php echo $this->sprint($data->l_tunnit); ?></td>

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


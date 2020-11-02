<?php
/* @var $this MobileController */
/* @var $data Mobile */




?>

<tr>

	<td class="col1">
		<i style="vertical-align: middle;" class="link fa fa-2x fa-caret-square-o-down showKuka text-danger" data-toggle="collapse" id="<?php echo 'tid_'.$data->tid; ?>" for="<?php echo 'tid_'.$data->tid; ?>" data-target="<?php echo '#tyotShow_'.$data->tid; ?>"></i>&nbsp;

		<?php 
			$tnimi = Tyontekijat::model()->findbypk($data->tid); 
			echo $this->etuSukunimi($tnimi->id);
		?>

		<div class="collapse" id="<?php echo 'tyotShow_'.$data->tid; ?>">
			<div id="<?php echo 'showtyo_'.$data->tid; ?>"></div>
		</div>
	</td>

	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	$total_sunniteltu = $tot_sun;
	echo '<td class="col2">'.$this->sprint($total_sunniteltu).'</td>';
	}
	?>

	<td class="col3"><?php echo $this->sprint($data->l_tunnit); ?></td>
	<td class="col4"><?php echo $this->sprint($return[0]); ?></td>
	<td class="col5"><?php echo $tp; ?></td>
	<td class="col6"><?php echo $this->sprint($return[1]); ?></td>
	<td class="col7"><?php echo $this->sprint($return[2]); ?></td>
	<td class="col8"><?php echo $this->sprint($return[3]); ?></td>
</tr>

	



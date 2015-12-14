<?php
/* @var $this MobileController */
/* @var $data Mobile */


	$return = $this->toteutu($data->tid,"yhteenveto");
?>

<tr>

	<td width="100" class="col-sm-5">
		<b class="link fa fa-caret-square-o-down showKuka text-danger" data-toggle="collapse" id="<?php echo 'tid_'.$data->tid; ?>" for="<?php echo 'tid_'.$data->tid; ?>" data-target="<?php echo '#tyotShow_'.$data->tid; ?>"></b>&nbsp;

		<?php 
			$tnimi = Tyontekijat::model()->findbypk($data->tid); 
			echo $tnimi->tekijan_nimi;
		?>

		<div class="collapse col-sm-offset-1" id="<?php echo 'tyotShow_'.$data->tid; ?>">
		<br>
		<div id="<?php echo 'showtyo_'.$data->tid; ?>"></div>
		</div>
	</td>

	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	$total_sunniteltu = $tot_sun;
	echo '<td>'.$this->sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo $this->sprint($data->l_tunnit); ?></td>
	<td><?php echo $this->sprint($return[0]); ?></td>
	<td><?php echo $tp; ?></td>
	<td><?php echo $this->sprint($return[1]); ?></td>
	<td><?php echo $this->sprint($return[2]); ?></td>
	<td><?php echo $this->sprint($return[3]); ?></td>
</tr>

	



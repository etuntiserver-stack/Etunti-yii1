<?php
/* @var $this MobileController */
/* @var $data Mobile */


	$return = $this->toteutu($data->tid,"yhteenveto");
?>

<tr>

	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>

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

	



<?php
/* @var $this TyontekijatController */
/* @var $data Tyontekijat */
?>


<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */
?>

<tr>
	<td>
		<?php echo $data->pvm; ?>
	</td>
	<td>
		<?php if(isset($data->kohteet->osoite)) echo $data->kohteet->osoite; ?>
	</td>
	<td>
		<?php echo $data->alku; ?>
	</td>
	<td>
		<?php echo $data->loppu; ?>
	</td>
	<td>
		<?php 
			echo $this->sprint(strtotime($data->loppu)-strtotime($data->alku)); 
		?>
	</td>
</tr>

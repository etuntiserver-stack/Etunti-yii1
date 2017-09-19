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
		<?php echo $data->time; ?>
	</td>
	<td>
		<?php $data->kohde_kannasta; ?>
	</td>
	<td>
		<?php echo $data->aloitan; ?>
	</td>
	<td>
		<?php echo $data->loppui; ?>
	</td>
	<td>
		<?php 
			echo $this->sprint(strtotime($data->loppui)-strtotime($data->aloitan)); 
		?>
	</td>
</tr>

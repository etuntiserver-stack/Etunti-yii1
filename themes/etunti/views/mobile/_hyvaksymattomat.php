<?php
/* @var $this LaskuController */
/* @var $data Lasku */
?>
<tr>

	<td>
		<?=date("d.m.Y", strtotime($data->aloitan))?>
	</td>
	<td>
		<?=date("H:i", strtotime($data->aloitan))?> - <?=date("H:i", strtotime($data->loppui))?>
	</td>
	<td>
		<?=$data->tekijan_nimi?>
	</td>
	<td>
		<?=$data->kohde_kannasta?>
	</td>
</tr>

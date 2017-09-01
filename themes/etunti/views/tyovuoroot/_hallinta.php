<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */
?>

<tr>
	<td><?=$this->etuSukunimi($data->tid)?></td>
	<td><?=$data->pvm?></td>
	<td><?=(isset($this->getKohde($data->kohde)->osoite))?$this->getKohde($data->kohde)->osoite:''?></td>
	<td><?=($data->status != 0)?$this->tilanteet()[$data->status]:''?></td>
</tr>



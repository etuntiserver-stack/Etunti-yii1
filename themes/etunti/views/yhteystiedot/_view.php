<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */
?>

<tr>
	<td>
		<?php echo $data->yrityksen_nimi; ?>
	</td>
	<td>
		<?php echo $data->osoite; ?>
	</td>
	<td>
		<?php echo $this->asiakasMuutosTheme($data->id); ?>
	</td>
	<td>
		<?php echo $data->postinumero; ?>
	</td>
	<td>
		<?php echo $data->puhelin; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $data->yhteystieto_tyyppi; ?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>


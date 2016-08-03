<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */
?>

<tr>
	<td>
		<?php echo $data->domain; ?>
	</td>
	<td>
		<?php echo $data->yritys; ?>
	</td>
	<td>
		<?php echo $data->puhelin; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $this->moduliMuutos($data->paketti); ?>
	</td>
	<td>
		<?php echo CHtml::link('Näytä', array('etunnin_asiakas_kk', 'id'=>$data->id), array('class'=>'btn btn-sm btn-primary myBgColors', 'style'=>'color:white')); ?>
	</td>
	<td>
		<?php 
			echo $data->getAttributeLabel('palveluhinta_persiivoja').': '.$data->palveluhinta_persiivoja.' &euro;<br>';
			echo $data->getAttributeLabel('tyovuorohinta_persiivoja').': '.$data->tyovuorohinta_persiivoja.' &euro;<br>';
			echo $data->getAttributeLabel('muut_tyokaluhinta').': '.$data->muut_tyokaluhinta.' &euro;';

		?>
	</td>
	<td>
		<?php echo CHtml::link('Näytä', array('etunnin_asiakas_kk_laskuri', 'id'=>$data->id), array('class'=>'btn btn-sm btn-primary myBgColors', 'style'=>'color:white')); ?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update_etunnin_asiakas', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>

<?php
/* @var $this LaskuController */
/* @var $data Lasku */
/*
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
	<td>
		<?php echo CHtml::link(Yii::t('main', 'Hyväksyntä sivulle'), 
				array('//toteutuneet/index', 'from' => date("d.m.Y", strtotime($data->aloitan)), 'to' => date("d.m.Y", strtotime($data->aloitan)), 'tid' => $data->tid), 
				array(
					//'target' => '_blank',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Siirä minut hyväksyntään') 
				)
			); 
		?>
	</td>
</tr>

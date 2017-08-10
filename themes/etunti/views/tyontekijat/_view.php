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
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>
	<td>
		<?php echo $this->etuSukunimi($data->id); ?>
	</td>
	<td>
		<?php echo $data->laiten_puh; ?>
	</td>
	<td>
		<?php echo $data->tekijan_puh; ?>
	</td>
	<td>
		<?php echo $data->tekijan_email; ?>
	</td>
	<td>
		<?php echo $data->tekijan_katuosoite; ?>
	</td>
	<td>
		<?php 
			if(is_array(json_decode($data->tyoryhma)))
				echo implode("<br>", json_decode($data->tyoryhma));
			else
				echo $data->tyoryhma; 
		?>
	</td>
	<td>
		<?php echo CHtml::link(Yii::t('main', 'Lähetä tunnukset työntekijälle'), 
				array('update', 'id'=>$data->id, 'laheta_tunnukset'=>true), 
				array(
					'class' => 'btn btn-primary btn-block myBgColors',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä'),
					//'target' => '_blank'
					'style' => 'color:white'
				)
			); 
		?>
	</td>
</tr>

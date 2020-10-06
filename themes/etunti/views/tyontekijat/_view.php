<?php
/* @var $this TyontekijatController */
/* @var $data Tyontekijat */
?>


<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */
	$versio = '<span class="text-danger">ei käytössä</span>';
	$criteria = new CDbCriteria();
	$criteria->order = "id DESC";
	$criteria->condition = "
		tid='".$data->id."'
	";
	$mb = Mobile::model()->find($criteria);
	if( isset($mb->id) ){
		$expl = explode("_", $mb->asiakas_num);
		if( isset($expl[0]) and !empty($expl[0]) and $expl[0] == $current_app_versio ){
			$versio = $expl[0].' <i class="text-success fa fa-check fa-2x"></i>';
		} elseif( isset($expl[0]) and !empty($expl[0]) and $expl[0] != $current_app_versio ){
			$versio = $expl[0].' <i class="text-danger fa fa-arrow-down fa-2x"></i>';
		}
	}
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
		<?php echo $versio; ?><br>
		<?php echo $data->app_platform; ?>
	</td>
	<td class="lahetys">
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

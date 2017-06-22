<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

		$list = array('0'=>Yii::t('main', 'Ei'),'1'=>Yii::t('main', 'Kyllä'));
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
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
	</td>
	<td>
		<h3><?php echo $data->kupongin_id; ?></h3>
	</td>
	<td>
		<?php echo date("d.m.Y", strtotime($data->voimassa)); ?>
	</td>
	<td>
		<?php 
			if($data->maara_tyyppi == 'euro')
			echo Yii::t('main', 'Euro') .' <b>'.$data->euro_maara.'&euro;</b>';
			if($data->maara_tyyppi == 'prosentti')
			echo Yii::t('main', 'Prosentti') .' <b>'.$data->prosentti_maara.'%</b>';
		?>
	</td>
	<td>
		<?=$list[$data->jatkuva]?>
	</td>
	<td>
		<?=$list[$data->status]?>
	</td>
	<td>
		<?php echo CHtml::link('<i class="fa fa-paper-plane-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('laheta', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä') 
				)
			); 
		?>
	</td>
</tr>


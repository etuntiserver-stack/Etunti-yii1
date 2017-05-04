<?php
/* @var $this DigistenTunnitKkController */
/* @var $data DigistenTunnitKk */
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
		<?php if(isset($data->domainit->yritys)) echo $data->domainit->yritys; ?>
	</td>
	<td>
		<?php echo $data->year.' / '.$data->month; ?>
	</td>
	<td>
		<?php echo $data->tunnit; ?>
	</td>
	<td>
		<?php 
			echo number_format($this->tuntihinta_laskin($data->id), 2, ',', ' ').' &euro;';
		?>
	</td>
	<td>
		<?php 
			$sum = ($this->tuntihinta_laskin($data->id)*$data->tunnit);
			echo number_format($sum, 2, ',', ' ').' &euro;';
		?>
	</td>
	<td>
		<?php echo CHtml::link(Yii::t('main', 'Luo lasku'), 
				array('//lasku/create', 'digisten_tunnit_id'=>$data->id), 
				array(
					'class'=>'btn btn-warning', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Luo lasku') 
				)
			); 
		?>
	</td>
</tr>


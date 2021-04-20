<?php
/* @var $this LaskuController */
/* @var $data Lasku */


	$asiakas='';
	if(isset($data->asiakkaat->id))
		$asiakas = $data->asiakkaat->Fullname;

?>
<tr>
        <?php if($lahettamattomat == true): ?>
	<td>
		<span class="btn btn-primary myBgColors" data-toggle="tooltip" data-placement="top" title="<?php echo Yii::t('main', 'Valitse lähetettäväksi'); ?>"><input type="checkbox" class="valitseLahetettavaksi" for="<?php echo $data->id; ?>"></span>
	</td>
   	<?php endif; ?>

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
		<button class="btn btn-primary myBgColors"> <i class="link fa fa-history" for="<?php echo $data->id; ?>"></i></button>
	</td>
	<td>
		<?php echo $data->laskunumero; ?>
	</td>
	<td>
		<?php echo $asiakas; ?>
	</td>
	<td>
		<?php echo $data->osoite; ?>
	</td>
	<td>
		<?php echo $data->viitenumero; ?>
	</td>
	<td>
		<?php echo date("d.m.Y H:i",strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $this->tilanneCheck($data,null); ?>
	</td>
	<td>
		<?php echo date("d.m.Y H:i",strtotime($data->tapahtumapvm)); ?>
	</td>
	<td>
		<?php 
			if($data->yhteensa_total > 0)
			echo number_format($data->yhteensa_total, 2, ",", " "); 
		?>

	</td>
	<td>
		<?php 
			if($this->avoinnaCheck($data,null) > 0)
			echo number_format($this->avoinnaCheck($data,null), 2, ",", " "); 
		?>
	</td>
	<td>
		<?php echo $data->laskun_nimetys; ?>
	</td>

</tr>

<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */
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
		<?php echo date("d.m.Y  H:i",strtotime($data->time)); ?>
	</td>
	<td>
		<input type="checkbox" class="avainnumero" value="<?=$data->id?>"> <?php echo $data->avainnumero; ?>
	</td>
	<td>
		<?php
		if(isset($data->kohteet->id)){
			$as = Asiakkaat::model()->findByPk($data->kohteet->asiakas_id);
			echo $as->Fullname;
		}
		?>
	</td>
	<td>
		<?php
		if(isset($data->kohteet->osoite)){
			echo $data->kohteet->osoite; 
		}
		?>
	</td>
	<td>
		<?php echo $this->etuSukunimi($data->tid); ?>
	</td>
	<td>
		<?php echo $data->ovikoodi; ?>
	</td>
	<td>
		  <?php if(empty($data->sijainti)) : ?>
		  	---
		  <?php else: ?>
		  	<?=$this->sijaintiText($data->id)?>
			<?php
			if($data->sijainti_omatekstti == 0 and $data->sijainti == 2)
				echo '<br>'. $data->palautetu_asiakkaalle_pvm;
			?>
		  <?php endif; ?>

	</td>
</tr>

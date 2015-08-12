<?php
/* @var $this SivexkuittiController */
/* @var $data Sivexkuitti */
$tag = explode("_",$data->asiakas_num);
?>

<tr>
	<td><?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?></td>
	<td><?php echo CHtml::encode(date("d.m",strtotime($data->aloitan))); ?></td>
	<td><?php echo CHtml::encode($tag[1]); ?></td>
	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo CHtml::encode($data->kohde_kannasta); ?></td>		
	<td><?php echo CHtml::encode(date("H:i",strtotime($data->aloitan))); ?></td>
	<td><?php echo CHtml::encode(date("H:i",strtotime($data->loppui))); ?></td>	
</tr>

	
	


	
	

	<?php /*

	<td><?php echo CHtml::encode($data->asiakas_num); ?></td>
	
	

	<td><?php echo CHtml::encode($data->time); ?></td>
	
	

	<td><?php echo CHtml::encode($data->requests); ?></td>
	
	

	<td><?php echo CHtml::encode($data->puh_numero); ?></td>
	
	

	<td><?php echo CHtml::encode($data->imei); ?></td>

	<td><?php echo CHtml::encode($data->getAttributeLabel('sim_serial_number')); ?>:</td>
	<?php echo CHtml::encode($data->sim_serial_number); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('subscriber_id')); ?>:</td>
	<?php echo CHtml::encode($data->subscriber_id); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('my_location')); ?>:</td>
	<?php echo CHtml::encode($data->my_location); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</td>
	<?php echo CHtml::encode($data->osoite); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('kohde_kannasta')); ?>:</td>
	<?php echo CHtml::encode($data->kohde_kannasta); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('kohdenID')); ?>:</td>
	<?php echo CHtml::encode($data->kohdenID); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('aloitan')); ?>:</td>
	<?php echo CHtml::encode($data->aloitan); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('loppui')); ?>:</td>
	<?php echo CHtml::encode($data->loppui); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('viesti')); ?>:</td>
	<?php echo CHtml::encode($data->viesti); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('tekijan_nimi')); ?>:</td>
	<?php echo CHtml::encode($data->tekijan_nimi); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</td>
	<?php echo CHtml::encode($data->tid); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('etaisyys')); ?>:</td>
	<?php echo CHtml::encode($data->etaisyys); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</td>
	<?php echo CHtml::encode($data->status); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('tietoja')); ?>:</td>
	<?php echo CHtml::encode($data->tietoja); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('admin')); ?>:</td>
	<?php echo CHtml::encode($data->admin); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('hyvaksytty')); ?>:</td>
	<?php echo CHtml::encode($data->hyvaksytty); ?>
	

	*/ ?>



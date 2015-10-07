<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $data AsiakasHyvaksynta */

$cl = 'alert alert-info';
if($data->status == 1)
$cl = 'alert alert-info';
if($data->status == 3)
$cl = 'alert alert-success';
if($data->status == 2)
$cl = 'alert alert-danger';
?>

<div class="<?php echo $cl; ?>">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asiakas_id')); ?>:</b>
	<?php echo CHtml::encode($data->asiakas_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php echo json_decode($data->kirjen_body); ?>

	<?php /*

	<b><?php echo CHtml::encode($data->getAttributeLabel('ids')); ?>:</b>
	<?php echo CHtml::encode($data->ids); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />


	<b><?php echo CHtml::encode($data->getAttributeLabel('selitys')); ?>:</b>
	<?php echo CHtml::encode($data->selitys); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kirjen_body')); ?>:</b>
	<?php echo CHtml::encode($data->kirjen_body); ?>
	<br />

	*/ ?>

</div>

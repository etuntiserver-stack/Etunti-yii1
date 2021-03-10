<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $data AsiakasHyvaksynta */

$cl = 'alert alert-default';
/*
if($data->status == 1)
$cl = 'alert alert-warning';
if($data->status == 3)
$cl = 'alert alert-success';
if($data->status == 2)
$cl = 'alert alert-danger';
*/

$asiakas = '';
$a = Asiakkaat::model()->findbypk($data->asiakas_id);
if(isset($a->id))
$asiakas = $a->Fullname;
else
$asiakas = $data->asiakas_id;


$tilanne = '';
if($data->status == 1)
$tilanne = Yii::t('main','Lähetetty');
elseif($data->status == 3)
$tilanne = Yii::t('main','Hyväksytty');
elseif($data->status == 2)
$tilanne = Yii::t('main','Hylätty');
?>

<div class="<?php echo $cl; ?>">

  <div class="row">

	<div class="col-sm-3">
	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asiakas_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($asiakas), array('asiakkaat/update', 'id'=>$data->asiakas_id)); ?>
	<br />


	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($tilanne); ?>
	<br />

	</div>

  </div>

	<div class="col-sm-offset-6" data-toggle="collapse" data-target="#col_<?php echo $data->id; ?>">
	  <b class="fa fa-caret-square-o-down link"></b>
	</div>

	<div class="collapse" id="col_<?php echo $data->id; ?>">
	<?php echo json_decode($data->kirjen_body); ?>
	<br />

	<?php if(!empty($data->selitys)) : ?>
	<b><?php echo CHtml::encode($data->getAttributeLabel('selitys')); ?>:</b>
	<?php echo CHtml::encode($data->selitys); ?>
	<br />
	<?php endif; ?>
	</div>




	<?php /*

	<b><?php echo CHtml::encode($data->getAttributeLabel('ids')); ?>:</b>
	<?php echo CHtml::encode($data->ids); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />




	<b><?php echo CHtml::encode($data->getAttributeLabel('kirjen_body')); ?>:</b>
	<?php echo CHtml::encode($data->kirjen_body); ?>
	<br />

	*/ ?>

</div>

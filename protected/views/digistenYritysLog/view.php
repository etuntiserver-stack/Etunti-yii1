<?php
/* @var $this DigistenYritysLogController */
/* @var $model DigistenYritysLog */

$this->breadcrumbs=array(
	'Digisten Yritys Logs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DigistenYritysLog', 'url'=>array('index')),
	array('label'=>'Create DigistenYritysLog', 'url'=>array('create')),
	array('label'=>'Update DigistenYritysLog', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DigistenYritysLog', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DigistenYritysLog', 'url'=>array('admin')),
);
?>

<h1>View DigistenYritysLog #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'yritys_id',
		'tapahtuma',
	),
)); ?>

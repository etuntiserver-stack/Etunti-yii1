<?php
/* @var $this DigistenTunnitKkController */
/* @var $model DigistenTunnitKk */

$this->breadcrumbs=array(
	'Digisten Tunnit Kks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DigistenTunnitKk', 'url'=>array('index')),
	array('label'=>'Create DigistenTunnitKk', 'url'=>array('create')),
	array('label'=>'Update DigistenTunnitKk', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DigistenTunnitKk', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DigistenTunnitKk', 'url'=>array('admin')),
);
?>

<h1>View DigistenTunnitKk #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'domain',
		'year',
		'month',
		'tunnit',
		'tasot',
		'maksettu',
	),
)); ?>

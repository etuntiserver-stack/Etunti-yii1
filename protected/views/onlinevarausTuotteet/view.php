<?php
/* @var $this OnlinevarausTuotteetController */
/* @var $model OnlinevarausTuotteet */

$this->breadcrumbs=array(
	'Onlinevaraus Tuotteets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List OnlinevarausTuotteet', 'url'=>array('index')),
	array('label'=>'Create OnlinevarausTuotteet', 'url'=>array('create')),
	array('label'=>'Update OnlinevarausTuotteet', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OnlinevarausTuotteet', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OnlinevarausTuotteet', 'url'=>array('admin')),
);
?>

<h1>View OnlinevarausTuotteet #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nimike',
		'hinta',
		'selitysteksti',
		'palvelu',
		'kesto',
	),
)); ?>

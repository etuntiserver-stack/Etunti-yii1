<?php
/* @var $this LaskutusTuotteetController */
/* @var $model LaskutusTuotteet */

$this->breadcrumbs=array(
	'Laskutus Tuotteets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List LaskutusTuotteet', 'url'=>array('index')),
	array('label'=>'Create LaskutusTuotteet', 'url'=>array('create')),
	array('label'=>'Update LaskutusTuotteet', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete LaskutusTuotteet', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LaskutusTuotteet', 'url'=>array('admin')),
);
?>

<h1>View LaskutusTuotteet #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'tuotenimi',
		'hinta_alv_0',
		'hinta_alv_sis',
		'alv',
		'yksikko',
	),
)); ?>

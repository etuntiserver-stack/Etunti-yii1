<?php
/* @var $this CrmTarjouksetController */
/* @var $model CrmTarjoukset */

$this->breadcrumbs=array(
	'Crm Tarjouksets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CrmTarjoukset', 'url'=>array('index')),
	array('label'=>'Create CrmTarjoukset', 'url'=>array('create')),
	array('label'=>'Update CrmTarjoukset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CrmTarjoukset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CrmTarjoukset', 'url'=>array('admin')),
);
?>

<h1>View CrmTarjoukset #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'asiakas_id',
		'tarjous',
		'hyvaksyn_koodi',
		'asiakkaan_sahkoposti',
		'status',
	),
)); ?>

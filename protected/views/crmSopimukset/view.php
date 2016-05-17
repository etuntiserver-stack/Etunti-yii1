<?php
/* @var $this CrmSopimuksetController */
/* @var $model CrmSopimukset */

$this->breadcrumbs=array(
	'Crm Sopimuksets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CrmSopimukset', 'url'=>array('index')),
	array('label'=>'Create CrmSopimukset', 'url'=>array('create')),
	array('label'=>'Update CrmSopimukset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CrmSopimukset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CrmSopimukset', 'url'=>array('admin')),
);
?>

<h1>View CrmSopimukset #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'asiakas_id',
		'teksti',
		'hyvaksyn_koodi',
		'asiakkaan_sahkoposti',
		'status',
		'liite',
	),
)); ?>

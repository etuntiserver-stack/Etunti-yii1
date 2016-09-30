<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */

$this->breadcrumbs=array(
	'Administrators'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Administrators', 'url'=>array('index')),
	array('label'=>'Create Administrators', 'url'=>array('create')),
	array('label'=>'Update Administrators', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Administrators', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Administrators', 'url'=>array('admin')),
);
?>

<h1>View Administrators #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'adm_login',
		'adm_salasana',
		'adm_email',
		'adm_nimi',
		'status',
	),
)); ?>

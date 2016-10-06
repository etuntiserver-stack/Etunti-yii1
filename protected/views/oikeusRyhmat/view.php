<?php
/* @var $this OikeusRyhmatController */
/* @var $model OikeusRyhmat */

$this->breadcrumbs=array(
	'Oikeus Ryhmats'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List OikeusRyhmat', 'url'=>array('index')),
	array('label'=>'Create OikeusRyhmat', 'url'=>array('create')),
	array('label'=>'Update OikeusRyhmat', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OikeusRyhmat', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OikeusRyhmat', 'url'=>array('admin')),
);
?>

<h1>View OikeusRyhmat #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nimike',
	),
)); ?>

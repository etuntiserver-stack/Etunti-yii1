<?php
/* @var $this KuviaKohteestaController */
/* @var $model KuviaKohteesta */

$this->breadcrumbs=array(
	'Kuvia Kohteestas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List KuviaKohteesta', 'url'=>array('index')),
	array('label'=>'Create KuviaKohteesta', 'url'=>array('create')),
	array('label'=>'Update KuviaKohteesta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete KuviaKohteesta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage KuviaKohteesta', 'url'=>array('admin')),
);
?>

<h1>View KuviaKohteesta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'kohde_id',
		'osoite',
		'tid',
		'tekijan_nimi',
		'tiedosto',
		'kuvaus',
	),
)); ?>

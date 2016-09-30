<?php
/* @var $this VinkkiExtranetController */
/* @var $model VinkkiExtranet */

$this->breadcrumbs=array(
	'Vinkki Extranets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List VinkkiExtranet', 'url'=>array('index')),
	array('label'=>'Create VinkkiExtranet', 'url'=>array('create')),
	array('label'=>'Update VinkkiExtranet', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete VinkkiExtranet', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage VinkkiExtranet', 'url'=>array('admin')),
);
?>

<h1>View VinkkiExtranet #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'asiakas_id',
		'nimi',
		'puhelin',
		'sahkoposti',
		'teksti',
	),
)); ?>

<?php
/* @var $this FirmanTiedotController */
/* @var $model FirmanTiedot */

$this->breadcrumbs=array(
	'Firman Tiedots'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List FirmanTiedot', 'url'=>array('index')),
	array('label'=>'Create FirmanTiedot', 'url'=>array('create')),
	array('label'=>'Update FirmanTiedot', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete FirmanTiedot', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FirmanTiedot', 'url'=>array('admin')),
);
?>

<h1>View FirmanTiedot #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tyonantaja',
		'osoite',
		'postinumero',
		'postitoimipaikka',
		'puhelin',
		'y_tunnus',
		'sahkoposti',
		'tilinumero',
		'iban',
		'bic',
		'johtaja',
	),
)); ?>

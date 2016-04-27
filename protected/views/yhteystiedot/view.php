<?php
/* @var $this YhteystiedotController */
/* @var $model Yhteystiedot */

$this->breadcrumbs=array(
	'Yhteystiedots'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Yhteystiedot', 'url'=>array('index')),
	array('label'=>'Create Yhteystiedot', 'url'=>array('create')),
	array('label'=>'Update Yhteystiedot', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Yhteystiedot', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Yhteystiedot', 'url'=>array('admin')),
);
?>

<h1>View Yhteystiedot #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'yhteystieto_tyyppi',
		'yrityksen_nimi',
		'y_tunnus',
		'yhteyshenkilo',
		'osoite',
		'postitoimipaikka',
		'postinumero',
		'puhelin',
		'sahkoposti',
		'ryhma',
		'myyja',
		'status',
	),
)); ?>

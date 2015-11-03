<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */

$this->breadcrumbs=array(
	'Asiakkaats'=>array('index'),
	$model->id,
);
/*
$this->menu=array(
	array('label'=>'List Asiakkaat', 'url'=>array('index')),
	array('label'=>'Create Asiakkaat', 'url'=>array('create')),
	array('label'=>'Update Asiakkaat', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Asiakkaat', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Asiakkaat', 'url'=>array('admin')),
);
*/
?>

<h1>Asiakkaat #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'asiakasnumero',
		'time',
		'yrityksen_nimi',
		'y_tunnus',
		'yhteyshenkilo',
		'osoite',
		'kaupunki',
		'postinumero',
		'puhelin',
		'sahkoposti',
		'ryhma',
		'aktiivinen',
	),
)); ?>

<?php
/* @var $this TarjouslaskentaController */
/* @var $model Tarjouslaskenta */

$this->breadcrumbs=array(
	'Tarjouslaskentas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Tarjouslaskenta', 'url'=>array('index')),
	array('label'=>'Create Tarjouslaskenta', 'url'=>array('create')),
	array('label'=>'Update Tarjouslaskenta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tarjouslaskenta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tarjouslaskenta', 'url'=>array('admin')),
);
?>

<h1>View Tarjouslaskenta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'yhteystiedot_id',
		'asiakas_id',
		'tuote_palvelu_id',
		'hinta_tyyppi',
		'neliot',
		'kayntikerrat',
		'tuntien_maara',
		'yhteensa',
		'tavoite_myyntikate',
		'palkkakustannus',
		'matkat',
		'iltalisa',
		'yolisa',
		'muut_kulut',
	),
)); ?>

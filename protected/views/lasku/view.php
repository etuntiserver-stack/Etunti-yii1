<?php
/* @var $this LaskuController */
/* @var $model Lasku */

$this->breadcrumbs=array(
	'Laskus'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Lasku', 'url'=>array('index')),
	array('label'=>'Create Lasku', 'url'=>array('create')),
	array('label'=>'Update Lasku', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Lasku', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Lasku', 'url'=>array('admin')),
);
?>

<h1>View Lasku #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'lid',
		'yid',
		'time',
		'tyyppi',
		'yritys',
		'y_tunnus',
		'nimi',
		'as_nro',
		'osoite',
		'postinumero',
		'toimipaikka',
		'laskutus',
		'sahkoposti',
		'verkkolaskuosoite',
		'v_tunnus',
		'yhteyshenkilo',
		'nimitarkenne',
		'puhelin',
		't_yritys',
		't_y_tunnus',
		't_nimi',
		't_osoite',
		't_postinumero',
		't_toimipaikka',
		't_puhelin',
		't_sahkoposti',
		'toimitusosoite',
		'paivays',
		'erapaiva',
		'toimituspaiva',
		'maksuehto',
		'viitenumero',
		'viivastyskorko',
		'yhteensa_total_verot',
		'yhteensa_total_veroton',
		'yhteensa_total',
		'saaja_iban',
		'saaja_virtualkoodi',
		'tilanne',
		'maksettu_euro',
		'hyvityslasku',
		'laskun_nimetys',
		'response',
		'response_finvoice',
		'tapahtumapvm',
	),
)); ?>

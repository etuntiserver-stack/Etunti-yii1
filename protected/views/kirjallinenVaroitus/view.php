<?php
/* @var $this KirjallinenVaroitusController */
/* @var $model KirjallinenVaroitus */

$this->breadcrumbs=array(
	'Kirjallinen Varoituses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List KirjallinenVaroitus', 'url'=>array('index')),
	array('label'=>'Create KirjallinenVaroitus', 'url'=>array('create')),
	array('label'=>'Update KirjallinenVaroitus', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete KirjallinenVaroitus', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage KirjallinenVaroitus', 'url'=>array('admin')),
);
?>

<h1>View KirjallinenVaroitus #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'key',
		'tyonantaja',
		'osoite',
		'postinumero',
		'postitoimipaikka',
		'puhelin',
		'y_tunnus',
		'sahkoposti',
		'tekijan_email',
		'tid',
		'tekijan_nimi',
		'tekijan_katuosoite',
		'tekijan_pnumero',
		'tekijan_ptoimipaikka',
		'tekijan_puh',
		'tekijan_henkilotunnus',
		'kirjallisen_varoituksen',
		'Paivays',
		'Paikka',
		'TyonantajanEdustaja',
		'NimikeTehtava',
		'tiedosto',
	),
)); ?>

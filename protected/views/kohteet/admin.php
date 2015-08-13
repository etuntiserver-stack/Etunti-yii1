<?php
/* @var $this KohteetController */
/* @var $model Kohteet */

$this->breadcrumbs=array(
	'Kohteet'=>array('index'),
	Yii::t('main', 'Hallinta'),
);

$this->menu=array(
	array('label'=>'Lista Kohteet', 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Luo kohde'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#kohteet-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('main', 'Kohteet'); ?></h1>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kohteet-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		//'time',
		'tag_id',
		//'gps_sijainti',
		//'lyhenne',
		'osoite',
		'kaupunki',
		'email',
		'puh_nro',
		/*
		'katuosoite',

		'toimipaikka',
		'pnumero',
		'email',
		'aikataulu',
		'hinnoittelu',
		'muut',
		'toimenpiteet',
		'tietoja',
		'tyoryhma',
		'ryhma',
		'aktiivinen',
		'avain',
		'kenella_on_avain',

		'siivous',
		'etu_suku_nimet',
		'maksuehto_paiva',
		'viivastyskorko',
		'lasku_tiedot',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

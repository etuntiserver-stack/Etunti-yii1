<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */

$this->breadcrumbs=array(
	Yii::t('main', 'Toteutuneet')=>array('index'),
	Yii::t('main', 'Hallinta'),
);

$this->menu=array(
	array('label'=>'List Toteutuneet', 'url'=>array('index')),
	array('label'=>'Create Toteutuneet', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#toteutuneet-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('main', 'Toteutuneet hallinta'); ?></h1>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'toteutuneet-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'kid',
		'asiakas_num',
		'time',
		'requests',
		'puh_numero',
		/*
		'imei',
		'bluetooth_name',
		'sim_serial_number',
		'subscriber_id',
		'my_location',
		'osoite',
		'kohde_kannasta',
		'kohdenID',
		'aloitan',
		'loppui',
		'viesti',
		'tekijan_nimi',
		'tid',
		'etaisyys',
		'status',
		'tietoja',
		'admin',
		'tyoajanlaatu',
		'tyoajanmerkinta',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

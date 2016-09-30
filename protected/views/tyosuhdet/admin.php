<?php
/* @var $this TyosuhdetController */
/* @var $model Tyosuhdet */

$this->breadcrumbs=array(
	'Tyosuhdets'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Tyosuhdet', 'url'=>array('index')),
	array('label'=>'Create Tyosuhdet', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tyosuhdet-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Tyosuhdets</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyosuhdet-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'tid',
		'alku',
		'loppu',
		'vktyoaika',
		'nimike',
		/*
		'palkkausmuoto',
		'tuntihinta',
		'matka_thinta',
		'lippu_kuumaks',
		'koe_loppu',
		'koe_hinta',
		'tuloraja_ajalle',
		'perusprosentti',
		'lisaprosentti',
		'kuukaudessa',
		'kahdessa_viikossa',
		'viikossa',
		'paivassa',
		'atk_varten',
		'yksi_tuloraja',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

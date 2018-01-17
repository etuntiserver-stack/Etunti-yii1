<?php
/* @var $this EdicoTulauksetController */
/* @var $model EdicoTulaukset */

$this->breadcrumbs=array(
	'Edico Tulauksets'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List EdicoTulaukset', 'url'=>array('index')),
	array('label'=>'Create EdicoTulaukset', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#edico-tulaukset-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Edico Tulauksets</h1>

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
	'id'=>'edico-tulaukset-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'asiakas_id',
		'kohde_id',
		'osoite',
		'postinumero',
		/*
		'postitoimipaikka',
		'asiakas_puhelinnumero',
		'toivottu_pvm',
		'toivottu_aloitus',
		'toivottu_lopetus',
		'viesti',
		'tuotteet',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

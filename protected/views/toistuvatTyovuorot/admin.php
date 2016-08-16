<?php
/* @var $this ToistuvatTyovuorotController */
/* @var $model ToistuvatTyovuorot */

$this->breadcrumbs=array(
	'Toistuvat Tyovuorots'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ToistuvatTyovuorot', 'url'=>array('index')),
	array('label'=>'Create ToistuvatTyovuorot', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#toistuvat-tyovuorot-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Toistuvat Tyovuorots</h1>

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
	'id'=>'toistuvat-tyovuorot-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'pfrom',
		'pto',
		'viikkoja',
		'viikko_paivat',
		/*
		'tid',
		'kohde',
		'pvm',
		'alku',
		'loppu',
		'kesto',
		'tyoajanmerkinta',
		'status',
		'tietoja',
		'tyopaari',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

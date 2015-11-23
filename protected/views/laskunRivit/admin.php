<?php
/* @var $this LaskunRivitController */
/* @var $model LaskunRivit */

$this->breadcrumbs=array(
	'Laskun Rivits'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List LaskunRivit', 'url'=>array('index')),
	array('label'=>'Create LaskunRivit', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#laskun-rivit-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Laskun Rivits</h1>


<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'laskun-rivit-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'lid',
		'rivi',
		'tkoodi',
		'nimike',
		/*
		'kpl',
		'yksikko',
		'hinta',
		'alv',
		'hinta_alv',
		'ale',
		'veroton',
		'yhteensa_alv',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

<?php
/* @var $this KorvauksetController */
/* @var $model Korvaukset */

$this->breadcrumbs=array(
	'Korvauksets'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Korvaukset', 'url'=>array('index')),
	array('label'=>'Create Korvaukset', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#korvaukset-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Korvauksets</h1>


<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'korvaukset-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'tid',
		'time',
		'pvm',
		'syy',
		'korvaus',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

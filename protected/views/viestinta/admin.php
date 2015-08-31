<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

$this->breadcrumbs=array(
	Yii::t('main', 'Viestintä')=>array('index'),
	Yii::t('main', 'Hallinta'),
);

$this->menu=array(
	array('label'=>'List Viestinta', 'url'=>array('index')),
	array('label'=>'Create Viestinta', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#viestinta-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('main', 'Viestintä hallinta'); ?></h1>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'viestinta-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'pvm',
		'tekija',
		'viesti',
		'admin',
		'status',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

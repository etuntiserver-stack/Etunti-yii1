<?php
/* @var $this ValikkootController */
/* @var $model Valikkoot */

$this->breadcrumbs=array(
	Yii::t('main', 'Valikkoot')=>array('index'),
	Yii::t('main', 'Hallinta'),
);

$this->menu=array(
	array('label'=>'List Valikkoot', 'url'=>array('index')),
	array('label'=>'Create Valikkoot', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#valikkoot-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('main', 'Valikkoot hallinta'); ?></h1>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'valikkoot-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'value',
		'select_type',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

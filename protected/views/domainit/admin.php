<?php
/* @var $this DomainitController */
/* @var $model Domainit */

$this->breadcrumbs=array(
	'Domainits'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Domainit', 'url'=>array('index')),
	array('label'=>'Create Domainit', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#domainit-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Domaini hallinta</h1>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'domainit-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'domain',
		'paketti',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

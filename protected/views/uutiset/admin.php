<?php
/* @var $this UutisetController */
/* @var $model Uutiset */

$this->breadcrumbs=array(
	'Uutisets'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Luo uutinen', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#uutiset-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Uutiset hallinta</h1>


<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'uutiset-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'otsikko',
		'teksti',
		'luoja',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

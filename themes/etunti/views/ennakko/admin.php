<?php
/* @var $this EnnakkoController */
/* @var $model Ennakko */

$this->breadcrumbs=array(
	'Ennakkos'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Ennakko', 'url'=>array('index')),
	array('label'=>'Create Ennakko', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ennakko-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Ennakkos</h1>


<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ennakko-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-striped table-hover',

	'columns'=>array(
		'id',
		'tid',
		'time',
		'pvm',
		'syy',
		'ennakko',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

<?php
/* @var $this LisatyotunnitController */
/* @var $model Lisatyotunnit */

$this->breadcrumbs=array(
	'Lisatyotunnits'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Lisatyotunnit', 'url'=>array('index')),
	array('label'=>'Create Lisatyotunnit', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#lisatyotunnit-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<legend>
   <div class="pull-right">
     <?php echo CHtml::link(' +','/index.php/lisatyotunnit/create',array('target'=>'_blank','class'=>'btn btn-default glyphicon glyphicon-user')); ?>
   </div>
<h1> <?php echo Yii::t('main', 'LISÄTYÖTUNNIT'); ?> <i class="glyphicon glyphicon-time"></i></h1>
</legend>


<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'lisatyotunnit-grid',
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
		'prosentti',
		/*
		'tunnimaara',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

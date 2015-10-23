<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

$this->breadcrumbs=array(
	Yii::t('main', 'Viestintä')=>array('index'),
	Yii::t('main', 'Hallinta'),
);
/*
$this->menu=array(
	array('label'=>'List Viestinta', 'url'=>array('index')),
	array('label'=>'Create Viestinta', 'url'=>array('create')),
);
*/
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

<legend>
   <div class="pull-right">
     <?php echo CHtml::link(' +','/index.php/viestinta/create',array('target'=>'_blank','class'=>'btn btn-default glyphicon glyphicon-envelope')); ?>
   </div>
<h1> <?php echo Yii::t('main', 'VIESTIT'); ?> <i class="glyphicon glyphicon-envelope"></i></h1>
</legend>



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

                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table table-striped table-bordered table-hover',

	'columns'=>array(
		'id',
		'time',
		//'pvm',
		'tekija',
		'viesti',
		'admin',
		'status',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

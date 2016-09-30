<?php
/* @var $this AsetuksetForAllController */
/* @var $model AsetuksetForAll */

$this->breadcrumbs=array(
	'Asetukset For Alls'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List AsetuksetForAll', 'url'=>array('index')),
	array('label'=>'Create AsetuksetForAll', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#asetukset-for-all-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<legend>
<h1><?php echo Yii::t('main','ASETUKSET'); ?></h1>
</legend>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'asetukset-for-all-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-striped table-hover',

	'columns'=>array(
		'id',
		'asetus',
		'api_access_key',
		//'muut',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

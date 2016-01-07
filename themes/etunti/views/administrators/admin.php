<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */

$this->breadcrumbs=array(
	Yii::t('main', 'Järjestelmänvalvojat')=>array('index'),
	Yii::t('main', 'Hallinta'),
);

$this->menu=array(
	array('label'=>Yii::t('main', 'Järjestelmänvalvoja lista'), 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Luo järjestelmänvalvoja'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#administrators-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<legend>
<h1> <?php echo Yii::t('main', 'JÄRJESTELMÄNVALVOJAT'); ?> <i class="glyphicon glyphicon-user"></i></h1>
</legend>




<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'administrators-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table table-striped table-bordered table-hover',

	'columns'=>array(
		'id',
		'adm_login',
		//'adm_salasana',
		'adm_email',
		'adm_nimi',
		'status',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

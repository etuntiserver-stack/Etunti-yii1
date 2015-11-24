<?php
/* @var $this LaskuHistoriaController */
/* @var $model LaskuHistoria */

$this->breadcrumbs=array(
	'Lasku Historias'=>array('index'),
	'Manage',
);
/*
$this->menu=array(
	array('label'=>'List LaskuHistoria', 'url'=>array('index')),
	array('label'=>'Create LaskuHistoria', 'url'=>array('create')),
);
*/
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#lasku-historia-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<legend>
<h1><?php echo Yii::t('main','Lasku historia'); ?></h1>
</legend>


<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'lasku-historia-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table small table-striped table-bordered table-hover',

	'columns'=>array(
		//'id',
		'lid',
		'time',

	array(
        'name'  => 'status',
        'value'=>array($this,'statusMuutos'),
	'type' => 'html',
    	),
		'palvelu',
		'yht_euro',
		/*
		array(
			'class'=>'CButtonColumn',
		),
		*/
array(

        'value' => '
	   CHtml::link("Katso", Yii::app()->createUrl("laskuHistoria/update",array("id"=>$data->id)))
	',
        'type'  => 'raw',
	//'visible'=>Yii::app()->user->avetak,
    ),
	),
)); ?>

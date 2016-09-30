<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $model AsiakasHyvaksynta */

$this->breadcrumbs=array(
	'Asiakas Hyvaksyntas'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List AsiakasHyvaksynta', 'url'=>array('index')),
	array('label'=>'Create AsiakasHyvaksynta', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#asiakas-hyvaksynta-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<legend>
<h1><?php echo Yii::t('main','Hyväksytty tunnit'); ?></h1>
</legend>


<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'asiakas-hyvaksynta-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'asiakas_id',
		'time',
		'ids',
		'sahkoposti',
		'code',
		/*
		'status',
		'selitys',
		'kirjen_body',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

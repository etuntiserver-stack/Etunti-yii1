<?php
/* @var $this LaskutusTuotteetController */
/* @var $model LaskutusTuotteet */

$this->breadcrumbs=array(
	'Laskutus Tuotteets'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List LaskutusTuotteet', 'url'=>array('index')),
	array('label'=>'Create LaskutusTuotteet', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#laskutus-tuotteet-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<legend>
<h1> <?php echo Yii::t('main', 'TUOTEET JA PALVELUT'); ?> <i class="glyphicon glyphicon-barcode"></i></h1>
</legend>


<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'laskutus-tuotteet-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'tuotenimi',
		'hinta_alv_0',
		'hinta_alv_sis',
		'alv',
		/*
		'yksikko',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

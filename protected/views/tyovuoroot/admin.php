<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */

$this->breadcrumbs=array(
	Yii::t('main', 'Työvuoroot')=>array('index'),
	Yii::t('main', 'Hallinta'),
);

$this->menu=array(
	array('label'=>'List Tyovuoroot', 'url'=>array('index')),
	array('label'=>'Create Tyovuoroot', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tyovuoroot-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('main', 'Työvuoroon hallinta'); ?></h1>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyovuoroot-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'tid',
array(
        'name'  => 'tt.tekijan_nimi',
        'filter'=>CHtml::activeTextField($model,'tekijan_nimi'),
    ),
array(
        'name'  => 'kohteet.osoite',
        'filter'=>CHtml::activeTextField($model,'osoite'),
    ),

		'pvm',
		'alku',
		'loppu',
		'pituus',



		/*
		'loppu',
		'pituus',
		'ruokatauko',
		'alku_r',
		'kesto',
		'tyoajanlaatu',
		'tyoajanmerkinta',
		'tietoja',
		'osoiteOnline',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

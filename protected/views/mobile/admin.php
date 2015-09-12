<?php
/* @var $this MobileController */
/* @var $model Mobile */

$this->breadcrumbs=array(
	'Mobiles'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Mobile', 'url'=>array('index')),
	array('label'=>'Create Mobile', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#mobile-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Luetut Hallinta</h1>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'mobile-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'asiakas_num',
		'kohde_kannasta',
		//'time',
		'aloitan',
		'loppui',
		'status',
		'imei',
		'tekijan_nimi',
		'viesti',
		/*
		'bluetooth_name',
		'sim_serial_number',
		'subscriber_id',
		'my_location',
		'osoite',
		'kohde_kannasta',
		'kohdenID',
		'aloitan',
		'loppui',
		'viesti',
		'tekijan_nimi',
		'tid',
		'etaisyys',
		'status',
		'tietoja',
		'admin',
		'hyvaksytty',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

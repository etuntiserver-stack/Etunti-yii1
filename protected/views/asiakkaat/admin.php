<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */

$this->breadcrumbs=array(
	Yii::t('main', 'Asiakkaat')=>array('index'),
	Yii::t('main', 'Hallinta'),
);

$this->menu=array(
	array('label'=>Yii::t('main', 'Lista asiakas'), 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Luo asiakas'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#asiakkaat-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('main', 'Asiakas hallinta'); ?></h1>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'asiakkaat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'etunimi',
		'sukunimi',
		'osoite',
		'kaupunki',
		/*
		'postinumero',
		'puhelin',
		'sahkoposti',
		'ryhma',
		'aktiivinen',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

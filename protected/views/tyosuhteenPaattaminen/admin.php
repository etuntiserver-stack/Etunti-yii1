<?php
/* @var $this TyosuhteenPaattaminenController */
/* @var $model TyosuhteenPaattaminen */

$this->breadcrumbs=array(
	'Tyosuhteen Paattaminens'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List TyosuhteenPaattaminen', 'url'=>array('index')),
	array('label'=>'Create TyosuhteenPaattaminen', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tyosuhteen-paattaminen-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Tyosuhteen Paattaminens</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyosuhteen-paattaminen-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'key',
		'titteli',
		'kuuleminen',
		'tyosuhteen_paattaminen',
		/*
		'tyonantaja',
		'osoite',
		'postinumero',
		'postitoimipaikka',
		'puhelin',
		'y_tunnus',
		'sahkoposti',
		'tekijan_email',
		'tid',
		'tekijan_nimi',
		'tekijan_katuosoite',
		'tekijan_pnumero',
		'tekijan_ptoimipaikka',
		'tekijan_puh',
		'tekijan_henkilotunnus',
		'teksti',
		'Paivays',
		'Paikka',
		'TyonantajanEdustaja',
		'NimikeTehtava',
		'tiedosto',
		'alku_pvm',
		'loppu_pvm',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

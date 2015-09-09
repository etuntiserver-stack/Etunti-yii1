<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */

$this->breadcrumbs=array(
	'Työntekijät'=>array('index'),
	Yii::t('main', 'Hallinta'),
);

$this->menu=array(
	array('label'=>Yii::t('main', 'Lista työntekijä'), 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Luo työntekijä'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tyontekijat-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="row">
  <div class="col-sm-3">
    <input type="checkbox" name="aktiiviset" class="sw">
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyontekijat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'imei',
		//'laiten_puh',
		'tekijan_nimi',
		//'tekijan_henkilotunnus',
		'tekijan_puh',
		'tekijan_email',
		'tyoryhma',
		//'aktiivinen',
		/*

		'tekijan_lanka_puh',
		'tekijan_katuosoite',
		'tekijan_pnumero',
		'tekijan_ptoimipaikka',

		'tyoehtosopimus',
		'tekijan_kulunvalvonta',
		'tekijan_pankkitili',
		'tekijan_konttori',

		'tekijan_tietoja',
		'tekijan_muisti',
		'salasana',
		'online_varauksen_valmina',
		'kortit',
		'ayjasenyys',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

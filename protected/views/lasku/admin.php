<?php
/* @var $this LaskuController */
/* @var $model Lasku */

$this->breadcrumbs=array(
	Yii::t('main', 'Laskut')=>array('index'),
	Yii::t('main', 'HALLINTA'),
);
/*
$this->menu=array(
	array('label'=>'List Lasku', 'url'=>array('index')),
	array('label'=>'Create Lasku', 'url'=>array('create')),
);
*/
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#lasku-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

?>

<legend>
   <div class="pull-right">
     <?php echo CHtml::link('Laskupäiväkirja (MR)','/index.php/laskuHistoria/paivakirja',array('class'=>'btn btn-default btn-sm')); ?>
     <?php echo CHtml::link(' +','/index.php/lasku/create',array('class'=>'btn btn-default btn-sm glyphicon glyphicon-barcode')); ?>
   </div>
<h1> <?php echo Yii::t('main', 'LASKUT'); ?> <i class="glyphicon glyphicon-barcode"></i></h1>
</legend>


<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'lasku-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table small table-striped table-bordered table-hover',

	'columns'=>array(
		'id',
		'laskunumero',

	array(
        'header'  => 'Asianro',
        'value'=>array($this,'asianro'),
	'type' => 'html',
    	),

		//'lid',
		//'yid',
               array(
                    'name'=>'time',
                    'value'=>'date("d.m.Y - H:i",strtotime($data->time))',                   
		),
		//'tyyppi',
		//'yritys',
		//'y_tunnus',
		'osoite',
		//'nimi',
		'yhteyshenkilo',
		//'as_nro',
	array(
        'header'  => 'Tilanne',
        'value'=>array($this,'tilanneCheck'),
	'type' => 'html',
    	),

		'tapahtumapvm',
		'yhteensa_total',
		'laskun_nimetys',
		/*
		'postinumero',
		'toimipaikka',
		'laskutus',
		'sahkoposti',
		'verkkolaskuosoite',
		'v_tunnus',

		'nimitarkenne',
		'puhelin',
		't_yritys',
		't_y_tunnus',
		't_nimi',
		't_osoite',
		't_postinumero',
		't_toimipaikka',
		't_puhelin',
		't_sahkoposti',
		'toimitusosoite',
		'paivays',
		'erapaiva',
		'toimituspaiva',
		'maksuehto',
		'viitenumero',
		'viivastyskorko',
		'yhteensa_total_verot',
		'yhteensa_total_veroton',

		'saaja_iban',
		'saaja_virtualkoodi',
		'tilanne',
		'maksettu_euro',
		'hyvityslasku',
		'laskun_nimetys',
		*/
		/*
		array(
			'class'=>'CButtonColumn',
		),
		*/
array(

        'value' => '
	   CHtml::link("Katso", Yii::app()->createUrl("lasku/update",array("id"=>$data->id)))
	',
        'type'  => 'raw',
	//'visible'=>Yii::app()->user->avetak,
    ),
	),
)); ?>

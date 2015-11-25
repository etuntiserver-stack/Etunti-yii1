<?php

Yii::app()->clientScript->registerScript('avoimet', "
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

$asetukset = Asetukset::model()->findbypk(1);
$palvelu = '';
if($asetukset->palvelu_tyyppi == 1)
$palvelu = 'POSTITA';
if($asetukset->palvelu_tyyppi == 2)
$palvelu = 'TRUST';

?>

<legend>
<h1> <?php echo Yii::t('main', 'Avoimet laskut ').$palvelu; ?> <i class="glyphicon glyphicon-barcode"></i></h1>
</legend>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'lasku-grid',
	'dataProvider'=>$model->avoimet(),
	'filter'=>$model,

                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table small table-striped table-bordered table-hover',

	'columns'=>array(
		//'id',
		'laskunumero',

	array(
        'header'  => 'Asianro',
        'value'=>array($this,'asianro'),
	'type' => 'html',
    	),

		//'lid',
		//'yid',
               array(
	            'header'  => 'Tapahtuma pvm',
                    'value'=>'date("d.m.Y H:i",strtotime($data->tapahtumapvm))',                   
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
		'tilanne',
		//'tapahtumapvm',
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

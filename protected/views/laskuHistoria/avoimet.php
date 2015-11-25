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
	//'type' => 'raw',
    	),
		'palvelu',
		'yht_euro',
		'trust_statuscode',
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

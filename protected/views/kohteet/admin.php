<?php
/* @var $this KohteetController */
/* @var $model Kohteet */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet')=>array('index'),
	Yii::t('main', 'Hallinta'),
);
/*
$this->menu=array(
	array('label'=>'Lista Kohteet', 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Luo kohde'), 'url'=>array('create')),
);
*/
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#kohteet-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<legend>
   <div class="pull-right">
     <?php echo CHtml::link(' Lisää uusi kohde','/index.php/kohteet/create',array('target'=>'_blank','class'=>'btn btn-default glyphicon glyphicon-home')); ?>
   </div>
<h1> <?php echo Yii::t('main', 'KOHTEET'); ?> <i class="glyphicon glyphicon-home"></i></h1>
</legend>



<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kohteet-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table table-striped table-hover',


	'columns'=>array(
		'id',
               array(
                    'name'=>'time',
                    'value'=>'date("d.m.Y - H:i",strtotime($data->time))',                   
		),
               array(
                    'name'=>'asiakas_id',
                    'filter'=>CHtml::dropDownList('Kohteet[asiakas_id]','',
		    CHtml::listData(Asiakkaat::model()->findAll(array('order' => "yhteyshenkilo")), 'id', 'yhteyshenkilo'),array('empty'=>'valitse','class'=>'form')),
		    'value'=>array($this,'asiakasMuutos'),
		    'type' => 'html',
                    
		),
		//'asiakas_id',
		'etu_suku_nimet',
		'tag_id',
		//'gps_sijainti',
		//'lyhenne',
		'osoite',
		//'kaupunki',
		'email',
		'puh_nro',
		//'tietoja',
		'avain',
		/*
		'katuosoite',

		'toimipaikka',
		'pnumero',
		'email',
		'aikataulu',
		'hinnoittelu',
		'muut',
		'toimenpiteet',

		'tyoryhma',
		'ryhma',
		'aktiivinen',

		'kenella_on_avain',

		'siivous',

		'maksuehto_paiva',
		'viivastyskorko',
		'lasku_tiedot',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

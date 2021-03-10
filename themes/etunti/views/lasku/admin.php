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

        <!-- begin: .tray-center -->
        <div class="tray-center">

   <div class="pull-right">

   <div class="form-inline">
     <select class="form-control input-sm" id="raportit">
     <option><?php echo Yii::t('main','Raportit'); ?></option>
     <option value="../laskuHistoria/avoimet"><?php echo Yii::t('main','Avoimet laskut'); ?></option>
     <option value="../laskuHistoria/paivakirja"><?php echo Yii::t('main','Lasku päiväkirjа'); ?></option>
     <option value="../laskuHistoria/paakirja"><?php echo Yii::t('main','Lasku pääkirja'); ?></option>
     <option value="../laskuHistoria/maksu_paivakirja"><?php echo Yii::t('main','Maksu päiväkirjа'); ?></option>
     <option value="../laskuHistoria/maksu_paakirja"><?php echo Yii::t('main','Maksu pääkirja'); ?></option>
     <option><?php echo Yii::t('main','ALV lista'); ?></option>
     <option value="../laskuHistoria/admin"><?php echo Yii::t('main','Lasku historia'); ?></option>
     <option value="../laskuHistoria/reskontraluettelo"><?php echo Yii::t('main','Reskontraluettelo'); ?></option>
     </select>
     <button class="btn btn-primary btn-sm" id="haeRaportti"><?php echo Yii::t('main','Hae'); ?></button>

     <!--<?php echo CHtml::link('Laskupäiväkirja','/index.php/laskuHistoria/paivakirja',array('class'=>'btn btn-default btn-sm')); ?>-->
     <?php echo CHtml::link('Uusi lasku','/index.php/lasku/create',array('class'=>'btn btn-success btn-sm')); ?>

   </div>
   </div>
	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-barcode"></i> <?php echo Yii::t('main', 'LASKUT'); ?> </h2>


        <!-- loppu: .tray-center -->
        </div>


<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?> 
<?php echo CHtml::link(' YRITYSHAKU','https://tietopalvelu.ytj.fi/yrityshaku.aspx?kielikoodi=1',array('target'=>'_blank','class'=>'link')); ?>

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
		//'id',
		'laskunumero',
/*
	array(
        'header'  => 'Asianro',
        'value'=>array($this,'asianro'),
	'type' => 'html',
    	),
*/
		'viitenumero',
		//'lid',
		//'yid',
               array(
                    'name'=>'time',
                    'value'=>'date("d.m.Y - H:i",strtotime($data->time))',                   
		),
		//'tyyppi',
		//'yritys',
		//'y_tunnus',
		//'osoite',
		//'nimi',
		//'etunimi',
		//'as_nro',
	array(
        'header'  => 'Tilanne',
        'value'=>array($this,'tilanneCheck'),
	'type' => 'html',
    	),

		'tapahtumapvm',
	array(
	'name'=>'yhteensa_total',
        'value'=>'number_format($data->yhteensa_total, 2, ",", " ")',
    	),

	array(
        'header'  => 'Avoinna',
        'value'=>array($this,'avoinnaCheck'),
	'type' => 'html',
    	),

		
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
	   CHtml::link("", Yii::app()->createUrl("lasku/update",array("id"=>$data->id)),array("class"=>"fa fa-pencil-square-o"))
	',
        'type'  => 'raw',
	//'visible'=>Yii::app()->user->avetak,
    ),
	),
)); ?>


<script type="text/javascript">
$(document).ready(function(){


$("#haeRaportti").click(function() {
	window.location.href=$("#raportit").val();
});

});
</script>

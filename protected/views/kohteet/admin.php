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


<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<div class="row small">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo Yii::t('main', 'KOHTEET'); ?> <i class="glyphicon glyphicon-home"></i> 
		 | <?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
		 | <?php echo CHtml::link('Lisää uusi kohde','/index.php/kohteet/create',array('target'=>'_blank','class'=>'')); ?>
		</h3>
            </div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kohteet-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

	'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css'),
	'cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css',

        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-striped small table-hover',


	'columns'=>array(
               array(
                    'name'=>'id',
                    'filter'=>CHtml::textField('Kohteet[id]','',array('class'=>'form-control input-sm')),
		),
               array(
                    'name'=>'time',
                    'filter'=>CHtml::textField('Kohteet[time]','',array('class'=>'form-control input-sm')),
                    'value'=>'date("d.m.Y - H:i",strtotime($data->time))',                   
		),
               array(
                    'name'=>'asiakas_id',
                    'filter'=>CHtml::dropDownList('Kohteet[asiakas_id]','',
		    CHtml::listData(Asiakkaat::model()->findAll(array('order' => "yhteyshenkilo")), 'id', 'yhteyshenkilo'),array('empty'=>'valitse','class'=>'form-control input-sm')),
		    'value'=>array($this,'asiakasMuutos'),
		    'type' => 'html',
                    
		),
		//'asiakas_id',
               array(
                    'name'=>'etu_suku_nimet',
                    'filter'=>CHtml::textField('Kohteet[etu_suku_nimet]','',array('class'=>'form-control input-sm')),
		),

		//'gps_sijainti',
		//'lyhenne',
               array(
                    'name'=>'tag_id',
                    'filter'=>CHtml::textField('Kohteet[tag_id]','',array('class'=>'form-control input-sm')),
		),
		//'kaupunki',
               array(
                    'name'=>'osoite',
                    'filter'=>CHtml::textField('Kohteet[osoite]','',array('class'=>'form-control input-sm')),
		),
               array(
                    'name'=>'email',
                    'filter'=>CHtml::textField('Kohteet[email]','',array('class'=>'form-control input-sm')),
		),
               array(
                    'name'=>'puh_nro',
                    'filter'=>CHtml::textField('Kohteet[puh_nro]','',array('class'=>'form-control input-sm')),
		),
               array(
                    'name'=>'avain',
                    'filter'=>CHtml::textField('Kohteet[avain]','',array('class'=>'form-control input-sm')),
		),
		//'tietoja',
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

		array(
			'class'=>'CButtonColumn',
		),
		*/

		array(
		    'value'=>array($this,'onkoKuva'),
		    'type' => 'html',
    		),
		array(

        		'value' => '
	   		CHtml::link("", Yii::app()->createUrl("kohteet/update",array("id"=>$data->id)),array("class"=>"fa fa-pencil-square-o"))
			',
        		'type'  => 'raw',
			//'visible'=>Yii::app()->user->avetak,
    		),

	),
)); ?>

        </div>
    </div>
</div>

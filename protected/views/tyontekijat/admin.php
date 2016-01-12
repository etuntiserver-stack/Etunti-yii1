<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */

$this->breadcrumbs=array(
	'Työntekijät'=>array('index'),
	Yii::t('main', 'Hallinta'),
);
/*
$this->menu=array(
	array('label'=>Yii::t('main', 'Lista työntekijä'), 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Luo työntekijä'), 'url'=>array('create')),
);
*/
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
<!--
<div class="row">
  <div class="col-sm-3">
    <input type="checkbox" name="aktiivinen" class="sw">
  </div>
</div>
-->

<div class="row">
	<h3><?php echo Yii::t('main', 'TYÖNTEKIJÄT'); ?> <i class="glyphicon glyphicon-envelope"></i> 
	| <?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
	| <?php echo CHtml::link('Lisää uusi työntekijä','/index.php/tyontekijat/create',array('class'=>'')); ?>
   	</h3>
</div>


<div class="row">

<div id="adminTable">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyontekijat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

	'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css'),
	'cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css',

        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-striped small table-hover',


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

		array(
			'class'=>'CButtonColumn',
		),

		*/

array(

        'value' => '
	   CHtml::link("", Yii::app()->createUrl("tyontekijat/update",array("id"=>$data->id)),array("class"=>"fa fa-pencil-square-o"))
	',
        'type'  => 'raw',
	//'visible'=>Yii::app()->user->avetak,
    ),

	),
)); ?>
</div>
</div>

<?php if(Yii::app()->session['aktiivinen']) : ?>
<script type="text/javascript">
$(document).ready(function(){

  $(".sw").bootstrapSwitch({
	size: "small",
	onColor: "warning",
	offColor: "success",
	onText: "Kaikki",
	offText: "Aktiiviset"
  });

$('input[name="aktiivinen"]').bootstrapSwitch('state', true, true);

});
</script>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){

  $(".sw").bootstrapSwitch({
	size: "small",
	onColor: "warning",
	offColor: "success",
	onText: "Kaikki",
	offText: "Aktiiviset"
  });


  $('input[name="aktiivinen"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
    if(state == true)
    {
	
        $.ajax({
           url: "admin_ajax",
	   type:'POST',
	   data: { aktiivinen : "yes" },
           success: function(data){
		console.log(data);
		$("#adminTable").html(data);
           }
        });

    } else {
	
        $.ajax({
           url: "admin_ajax",
	   type:'POST',
	   data: { aktiivinen : "no" },
           success: function(data){
		console.log(data);
		$("#adminTable").html(data);
           }
        });

    }
  });

});
</script>


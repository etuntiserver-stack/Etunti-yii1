<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */

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
<br>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<div class="row">
	<h3> <?php echo Yii::t('main', 'TYÖNTEKIJÄT'); ?>  
	 <?php echo CHtml::link('','#',array('class'=>'btn btn-primary search-button fa fa-search')); ?>
	 <?php echo CHtml::link('','/index.php/tyontekijat/create',array('class'=>'btn btn-primary fa fa-user-plus')); ?> 
	 <input type="checkbox" name="aktiivinen" class="sw">
   	</h3>
</div>


<div class="row">
  <div class="panel heading-border">
   <div class="panel-body">

<div id="adminTable">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyontekijat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,


        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-hover',


	'columns'=>array(
		'id',
		'imei',
		'tekijan_nimi',
		'tekijan_puh',
		'tekijan_email',
		'tekijan_henkilotunnus',
		'tyoryhma',

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
 </div>
</div>

<?php if(Yii::app()->session['aktiivinen']) : ?>
<script type="text/javascript">
$(document).ready(function(){

  $(".sw").bootstrapSwitch({
	//size: "small",
	onColor: "warning",
	offColor: "primary",
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
	//size: "small",
	onColor: "warning",
	offColor: "primary",
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


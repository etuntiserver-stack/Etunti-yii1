<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#asiakkaat-grid').yiiGridView('update', {
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


<div class="row">
	<h3><?php echo Yii::t('main', 'ASIAKKAAT'); ?> <i class="glyphicon glyphicon-user"></i> 
	<?php echo CHtml::link('','#',array('class'=>'search-button btn btn-primary fa fa-search')); ?>
	<?php echo CHtml::link('','/index.php/asiakkaat/create',array('class'=>'btn btn-primary fa fa-user-plus')); ?>
   	</h3>
</div>


<div class="row">
  <div class="panel heading-border">
   <div class="panel-body">

<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'asiakkaat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-hover',

	'columns'=>array(
		'id',
		'asiakasnumero',
		/*
               array(
                    'name'=>'time',
                    'value'=>'date("d.m.Y - H:i",strtotime($data->time))',                   
		),
		*/
		'yrityksen_nimi',
		'y_tunnus',
		'etunimi',
		'osoite',
		//'kaupunki',
  
               array(
                    'name'=>'ryhma',
                    'filter'=>CHtml::dropDownList('Asiakkaat[ryhma]','',
		    CHtml::listData(Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type")), 'id', 'value'),array('empty'=>'valitse')),
                    
		),
		'netvisorkey',
		/*
		'postinumero',
		'puhelin',
		'sahkoposti',

		'aktiivinen',

		array(
			'class'=>'CButtonColumn',
		),
		*/
array(

        'value' => '
	   CHtml::link("", Yii::app()->createUrl("asiakkaat/update",array("id"=>$data->id)),array("class"=>"fa fa-pencil-square-o"))
	',
        'type'  => 'raw',
	//'visible'=>Yii::app()->user->avetak,
    ),
	),
)); ?>

   </div>
 </div>
</div>

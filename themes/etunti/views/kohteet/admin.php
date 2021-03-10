<?php
/* @var $this KohteetController */
/* @var $model Kohteet */

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

<div class="row">
	<h3><?php echo Yii::t('main', 'KOHTEET'); ?> <i class="glyphicon glyphicon-home"></i> 
	<?php echo CHtml::link('','#',array('class'=>'search-button btn btn-primary fa fa-search')); ?>
	<?php echo CHtml::link('','/index.php/kohteet/create',array('class'=>'btn btn-primary fa fa-plus')); ?>
   	</h3>
</div>


<div class="row">
  <div class="panel heading-border">
   <div class="panel-body">


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kohteet-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,


        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-hover',


	'columns'=>array(
		'id',
/*
               array(
                    'name'=>'time',
                    'filter'=>CHtml::textField('Kohteet[time]','',array('class'=>'form-control input-sm')),
                    'value'=>'date("d.m.Y - H:i",strtotime($data->time))',                   
		),
*/
               array(
                    'name'=>'asiakas_id',
                    'filter'=>CHtml::dropDownList('Kohteet[asiakas_id]','',
		    CHtml::listData(Asiakkaat::model()->findAll(array('order' => "etunimi")), 'id', 'etunimi'),array('empty'=>'valitse')),
		    'value'=>array($this,'asiakasMuutos'),
		    'type' => 'html',
                    
		),

                    	'etu_suku_nimet',
			//'tag_id',
			'osoite',
			'email',
			'puh_nro',
			'avain',


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

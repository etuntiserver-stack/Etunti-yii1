<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

$this->breadcrumbs=array(
	Yii::t('main', 'Viestintä')=>array('index'),
	Yii::t('main', 'Hallinta'),
);
/*
$this->menu=array(
	array('label'=>'List Viestinta', 'url'=>array('index')),
	array('label'=>'Create Viestinta', 'url'=>array('create')),
);
*/
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#viestinta-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<legend>
   <div class="pull-right">
     <?php echo CHtml::link(' Lisää uusi viesti','/index.php/viestinta/create',array('class'=>'btn btn-default glyphicon glyphicon-envelope')); ?>
   </div>
<h1> <?php echo Yii::t('main', 'VIESTIT'); ?> <i class="glyphicon glyphicon-envelope"></i></h1>
</legend>


<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'viestinta-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table table-striped small table-bordered table-hover',

	'columns'=>array(
		//'id',
               array(
                    'name'=>'time',
                    'value'=>'date("d.m.Y - H:i",strtotime($data->time))',                   
		),
		//'pvm',
               array(
                    'name'=>'viesti',
                    'value'=>'$data->viesti',
		    'type' => 'html',
		),
               array(
                    'name'=>'admin',
                    'value'=>array($this,'lahettajaMuutos'),
		    'type' => 'html',
		),
               array(
                    'name'=>'tekija',
                    'value'=>array($this,'tekijaMuutos'),              
		),
/*
		//'status',
		array(
			'class'=>'CButtonColumn',
		),
*/

array(

        'value' => '
	   CHtml::link("", Yii::app()->createUrl("viestinta/view",array("id"=>$data->id)),array("class"=>"fa fa-pencil-square-o"))
	',
        'type'  => 'raw',
	//'visible'=>Yii::app()->user->avetak,
    ),

	),
)); ?>

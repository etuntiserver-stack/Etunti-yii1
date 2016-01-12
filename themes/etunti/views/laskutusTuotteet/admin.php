<?php
/* @var $this LaskutusTuotteetController */
/* @var $model LaskutusTuotteet */

$this->breadcrumbs=array(
	'Laskutus Tuotteets'=>array('index'),
	'Manage',
);
/*
$this->menu=array(
	array('label'=>'List LaskutusTuotteet', 'url'=>array('index')),
	array('label'=>'Create LaskutusTuotteet', 'url'=>array('create')),
);
*/
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#laskutus-tuotteet-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <i class="fa fa-shopping-cart"></i> <?php echo Yii::t('main', 'TUOTEET JA PALVELUT'); ?> 
	<?php echo CHtml::link('','/index.php/laskutusTuotteet/create',array('class'=>'btn btn-default fa fa-plus')); ?></h2>


        <!-- loppu: .tray-center -->
        </div>


<?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'laskutus-tuotteet-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table small table-striped table-bordered table-hover',

	'columns'=>array(
		'id',
		'time',
		'tuotenimi',
		'hinta_alv_0',
		'hinta_alv_sis',
		'alv',
		/*
		'yksikko',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

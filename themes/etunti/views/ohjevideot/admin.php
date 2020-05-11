<?php
/* @var $this OhjevideotController */
/* @var $model Ohjevideot */


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ohjevideot-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	<h2 class="myBgColors p10"> <i class="fa fa-file-video-o"></i> <?php echo Yii::t('main', 'Ohjevideot hallinta'); ?> 
	<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/ohjevideot/create',array('class'=>'btn btn-default fa fa-plus','data-toggle'=>'tooltip', 'data-placement'=>'top', 'title' => Yii::t('main', 'Lisää asiakas') )); ?>
	</h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">




<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ohjevideot-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'otsiko',
		'kuvaus',
		'tiedoston_nimi',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>

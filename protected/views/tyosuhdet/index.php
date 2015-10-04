<?php
/* @var $this TyosuhdetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tyosuhdets',
);
/*
$this->menu=array(
	array('label'=>'Create Tyosuhdet', 'url'=>array('create')),
	array('label'=>'Manage Tyosuhdet', 'url'=>array('admin')),
);
*/
?>

<legend>
  <h1> 
	<?php echo Yii::t('main', 'TYÖSUHTEET'); ?> <i class="fa fa-list"></i> 
  </h1>
</legend>

<br>
<div class="row">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',

  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


	'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 
)); ?>
</div>

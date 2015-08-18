<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet'),
);

$this->menu=array(
	array('label'=>'Luo Kohde', 'url'=>array('create')),
	array('label'=>'Kohteet hallinta', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Kohteet'); ?></h1>

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

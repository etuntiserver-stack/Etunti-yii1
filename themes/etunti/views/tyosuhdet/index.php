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

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="fa fa-list"></i> <?php echo Yii::t('main', 'TYÖSUHTEET'); ?>
		</h2>


        <!-- loppu: .tray-center -->
        </div>


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

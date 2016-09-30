<?php
/* @var $this LaskuController */
/* @var $model Lasku */

$this->breadcrumbs=array(
	'Laskus'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

if(isset($model->id) and $model->tilanne == '1'){
$this->menu=array(
	array('label'=>'Laskun mitätöinti', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
}
?>

<legend>
<h1> <?php echo $model->laskun_nimetys; ?> <?php echo $model->laskunumero; ?> <i class="glyphicon glyphicon-barcode"></i></h1>
</legend>


<?php echo $this->renderPartial('_form', array('model'=>$model,	'laskunRivit'=>$laskunRivit)); ?>

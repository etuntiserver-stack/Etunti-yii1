<?php
/* @var $this LaskutusTuotteetController */
/* @var $model LaskutusTuotteet */

$this->breadcrumbs=array(
	'Laskutus Tuotteets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LaskutusTuotteet', 'url'=>array('index')),
	array('label'=>'Manage LaskutusTuotteet', 'url'=>array('admin')),
);
?>

<legend>
<h1> <?php echo Yii::t('main', 'LUO LASKU TUOTE'); ?> <i class="glyphicon glyphicon-barcode"></i></h1>
</legend>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

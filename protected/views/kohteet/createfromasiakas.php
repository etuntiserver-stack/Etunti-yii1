<?php
/* @var $this KohteetController */
/* @var $model Kohteet */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet')=>array('index'),
	Yii::t('main', 'Luo'),
);

$this->menu=array(
	array('label'=>Yii::t('main', 'Kohde lista'), 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Kohde hallinta'), 'url'=>array('admin')),
);
?>

<legend>
<h1> <?php echo Yii::t('main', 'LUO KOHDE, ASIAKAS')." ID# ".$asiakas->id; ?> <i class="glyphicon glyphicon-home"></i></h1>
</legend>

<?php echo $this->renderPartial('_form_fromasiakas', array('model'=>$model,'asiakas'=>$asiakas)); ?>

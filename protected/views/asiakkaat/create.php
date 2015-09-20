<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */

$this->breadcrumbs=array(
	Yii::t('main', 'Asiakkaat')=>array('index'),
	Yii::t('main', 'Luo'),
);

$this->menu=array(
	array('label'=>Yii::t('main', 'Lista asiakas'), 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Asiakas hallinta'), 'url'=>array('admin')),
);
?>

<legend>
<h1> <?php echo Yii::t('main', 'LUO ASIAKAS'); ?> <i class="glyphicon glyphicon-user"></i></h1>
</legend>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

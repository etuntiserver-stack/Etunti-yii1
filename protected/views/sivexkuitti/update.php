<?php
/* @var $this SivexkuittiController */
/* @var $model Sivexkuitti */

$this->breadcrumbs=array(
	Yii::t('main', 'Mobiili')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main', 'tiedot'),
);

$this->menu=array(
	array('label'=>'Lista mobiili', 'url'=>array('index')),
	//array('label'=>'Create Sivexkuitti', 'url'=>array('create')),
	array('label'=>'Katso mobiili', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Hallinta mobiili', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Rivi'); ?> <?php echo $model->id; ?></h1>

<BR>
<div class="form">
  <div class="row">
	<div class="col-lg-4">
	<?php echo CHtml::button('Palaa takaisin',array("class"=>"btn btn-info","onClick"=>"history.back();return false;")); ?>
	</div>
  </div>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

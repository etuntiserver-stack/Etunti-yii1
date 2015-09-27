<?php
/* @var $this AsetuksetController */
/* @var $model Asetukset */

$this->breadcrumbs=array(
	'Asetuksets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Asetukset', 'url'=>array('index')),
	array('label'=>'Create Asetukset', 'url'=>array('create')),
	array('label'=>'View Asetukset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Asetukset', 'url'=>array('admin')),
);
?>

<legend>
  <h1> 
	<?php echo Yii::t('main', 'FIRMA'); ?> <i class="glyphicon glyphicon-phone"></i> 
  </h1>
</legend>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

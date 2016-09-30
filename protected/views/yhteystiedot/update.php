<?php
/* @var $this YhteystiedotController */
/* @var $model Yhteystiedot */

$this->breadcrumbs=array(
	'Yhteystiedots'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Yhteystiedot', 'url'=>array('index')),
	array('label'=>'Create Yhteystiedot', 'url'=>array('create')),
	array('label'=>'View Yhteystiedot', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Yhteystiedot', 'url'=>array('admin')),
);
?>

<h1>Update Yhteystiedot <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
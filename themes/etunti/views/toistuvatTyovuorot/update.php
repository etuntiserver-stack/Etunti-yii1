<?php
/* @var $this ToistuvatTyovuorotController */
/* @var $model ToistuvatTyovuorot */

$this->breadcrumbs=array(
	'Toistuvat Tyovuorots'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ToistuvatTyovuorot', 'url'=>array('index')),
	array('label'=>'Create ToistuvatTyovuorot', 'url'=>array('create')),
	array('label'=>'View ToistuvatTyovuorot', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ToistuvatTyovuorot', 'url'=>array('admin')),
);
?>

<h1>Update ToistuvatTyovuorot <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
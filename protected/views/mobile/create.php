<?php
/* @var $this MobileController */
/* @var $model Mobile */

$this->breadcrumbs=array(
	'Mobiles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Mobile', 'url'=>array('index')),
	array('label'=>'Manage Mobile', 'url'=>array('admin')),
);
?>

<h1>Create Mobile</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

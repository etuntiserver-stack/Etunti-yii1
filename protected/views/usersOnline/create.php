<?php
/* @var $this UsersOnlineController */
/* @var $model UsersOnline */

$this->breadcrumbs=array(
	'Users Onlines'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UsersOnline', 'url'=>array('index')),
	array('label'=>'Manage UsersOnline', 'url'=>array('admin')),
);
?>

<h1>Create UsersOnline</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
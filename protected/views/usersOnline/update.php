<?php
/* @var $this UsersOnlineController */
/* @var $model UsersOnline */

$this->breadcrumbs=array(
	'Users Onlines'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UsersOnline', 'url'=>array('index')),
	array('label'=>'Create UsersOnline', 'url'=>array('create')),
	array('label'=>'View UsersOnline', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UsersOnline', 'url'=>array('admin')),
);
?>

<h1>Update UsersOnline <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this UsersOnlineController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Users Onlines',
);

$this->menu=array(
	array('label'=>'Create UsersOnline', 'url'=>array('create')),
	array('label'=>'Manage UsersOnline', 'url'=>array('admin')),
);
?>

<h1>Users Onlines</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

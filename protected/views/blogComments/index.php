<?php
/* @var $this BlogCommentsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Blog Comments',
);

$this->menu=array(
	array('label'=>'Create BlogComments', 'url'=>array('create')),
	array('label'=>'Manage BlogComments', 'url'=>array('admin')),
);
?>

<h1>Blog Comments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

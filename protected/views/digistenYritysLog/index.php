<?php
/* @var $this DigistenYritysLogController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Digisten Yritys Logs',
);

$this->menu=array(
	array('label'=>'Create DigistenYritysLog', 'url'=>array('create')),
	array('label'=>'Manage DigistenYritysLog', 'url'=>array('admin')),
);
?>

<h1>Digisten Yritys Logs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

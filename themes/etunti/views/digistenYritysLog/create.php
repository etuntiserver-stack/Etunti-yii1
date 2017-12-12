<?php
/* @var $this DigistenYritysLogController */
/* @var $model DigistenYritysLog */

$this->breadcrumbs=array(
	'Digisten Yritys Logs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DigistenYritysLog', 'url'=>array('index')),
	array('label'=>'Manage DigistenYritysLog', 'url'=>array('admin')),
);
?>

<h1>Create DigistenYritysLog</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
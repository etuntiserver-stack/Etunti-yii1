<?php
/* @var $this DigistenYritysLogController */
/* @var $model DigistenYritysLog */

$this->breadcrumbs=array(
	'Digisten Yritys Logs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DigistenYritysLog', 'url'=>array('index')),
	array('label'=>'Create DigistenYritysLog', 'url'=>array('create')),
	array('label'=>'View DigistenYritysLog', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DigistenYritysLog', 'url'=>array('admin')),
);
?>

<h1>Update DigistenYritysLog <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
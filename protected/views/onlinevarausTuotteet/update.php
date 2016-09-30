<?php
/* @var $this OnlinevarausTuotteetController */
/* @var $model OnlinevarausTuotteet */

$this->breadcrumbs=array(
	'Onlinevaraus Tuotteets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OnlinevarausTuotteet', 'url'=>array('index')),
	array('label'=>'Create OnlinevarausTuotteet', 'url'=>array('create')),
	array('label'=>'View OnlinevarausTuotteet', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OnlinevarausTuotteet', 'url'=>array('admin')),
);
?>

<h1>Update OnlinevarausTuotteet <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
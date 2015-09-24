<?php
/* @var $this FirmanTiedotController */
/* @var $model FirmanTiedot */

$this->breadcrumbs=array(
	'Firman Tiedots'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FirmanTiedot', 'url'=>array('index')),
	array('label'=>'Create FirmanTiedot', 'url'=>array('create')),
	array('label'=>'View FirmanTiedot', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FirmanTiedot', 'url'=>array('admin')),
);
?>

<h1>Update FirmanTiedot <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
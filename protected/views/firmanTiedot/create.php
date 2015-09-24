<?php
/* @var $this FirmanTiedotController */
/* @var $model FirmanTiedot */

$this->breadcrumbs=array(
	'Firman Tiedots'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FirmanTiedot', 'url'=>array('index')),
	array('label'=>'Manage FirmanTiedot', 'url'=>array('admin')),
);
?>

<h1>Create FirmanTiedot</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this EnnakkoController */
/* @var $model Ennakko */

$this->breadcrumbs=array(
	'Ennakkos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ennakko', 'url'=>array('index')),
	array('label'=>'Manage Ennakko', 'url'=>array('admin')),
);
?>

<h1>Create Ennakko</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
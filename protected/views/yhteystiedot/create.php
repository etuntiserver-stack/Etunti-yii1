<?php
/* @var $this YhteystiedotController */
/* @var $model Yhteystiedot */

$this->breadcrumbs=array(
	'Yhteystiedots'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Yhteystiedot', 'url'=>array('index')),
	array('label'=>'Manage Yhteystiedot', 'url'=>array('admin')),
);
?>

<h1>Create Yhteystiedot</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
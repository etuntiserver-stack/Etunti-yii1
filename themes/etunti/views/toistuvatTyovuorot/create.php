<?php
/* @var $this ToistuvatTyovuorotController */
/* @var $model ToistuvatTyovuorot */

$this->breadcrumbs=array(
	'Toistuvat Tyovuorots'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ToistuvatTyovuorot', 'url'=>array('index')),
	array('label'=>'Manage ToistuvatTyovuorot', 'url'=>array('admin')),
);
?>

<h1>Create ToistuvatTyovuorot</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
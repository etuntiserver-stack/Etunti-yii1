<?php
/* @var $this KuviaKohteestaController */
/* @var $model KuviaKohteesta */

$this->breadcrumbs=array(
	'Kuvia Kohteestas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List KuviaKohteesta', 'url'=>array('index')),
	array('label'=>'Manage KuviaKohteesta', 'url'=>array('admin')),
);
?>

<h1>Create KuviaKohteesta</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
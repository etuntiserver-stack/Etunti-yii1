<?php
/* @var $this IlmoitusKaikkilleController */
/* @var $model IlmoitusKaikkille */

$this->breadcrumbs=array(
	'Ilmoitus Kaikkilles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List IlmoitusKaikkille', 'url'=>array('index')),
	array('label'=>'Manage IlmoitusKaikkille', 'url'=>array('admin')),
);
?>

<h1>Create IlmoitusKaikkille</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
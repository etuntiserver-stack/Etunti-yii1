<?php
/* @var $this OikeusRyhmatController */
/* @var $model OikeusRyhmat */

$this->breadcrumbs=array(
	'Oikeus Ryhmats'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OikeusRyhmat', 'url'=>array('index')),
	array('label'=>'Manage OikeusRyhmat', 'url'=>array('admin')),
);
?>

<h1>Create OikeusRyhmat</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this KirjallinenVaroitusController */
/* @var $model KirjallinenVaroitus */

$this->breadcrumbs=array(
	'Kirjallinen Varoituses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List KirjallinenVaroitus', 'url'=>array('index')),
	array('label'=>'Manage KirjallinenVaroitus', 'url'=>array('admin')),
);
?>

<h1>Create KirjallinenVaroitus</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this VinkkiExtranetController */
/* @var $model VinkkiExtranet */

$this->breadcrumbs=array(
	'Vinkki Extranets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List VinkkiExtranet', 'url'=>array('index')),
	array('label'=>'Manage VinkkiExtranet', 'url'=>array('admin')),
);
?>

<h1>Create VinkkiExtranet</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>